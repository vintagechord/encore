<?php

namespace App\Http\Controllers;

use App\Models\IntakeRequest;
use App\Models\Payment;
use App\Models\RecommendationSet;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Member dashboard summary
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        // 기본 탭은 1초 Set
        $type = $request->query('type') ?: 'instant'; // 'instant' | 'one_day' | 'direct'

        // 추천셋 페이지(구 대시보드)
        // - instant/one_day: 실제 추천셋이 생성된 문의만 노출
        // - direct: 추천셋 없이도 목록 표시 (요청 이력 확인용)
        if ($type === 'direct') {
            $intakesQ = IntakeRequest::with(['latestSet'])
                ->where('contact_email', $user->email)
                ->where('category', 'direct');
        } else {
            $intakesQ = IntakeRequest::with(['latestSet'])
                ->where('contact_email', $user->email)
                ->where('category', 'option')
                ->whereHas('recommendationSets');

            if ($type === 'instant') {
                // 기존 데이터(키 없음)는 1초 Set로 간주하여 포함
                $intakesQ->where(function($q){
                    $q->where('notes', 'like', '%"request_type":"instant"%')
                      ->orWhere('notes', 'not like', '%"request_type":%')
                      ->orWhereNull('notes');
                });
            } elseif ($type === 'one_day') {
                $intakesQ->where('notes', 'like', '%"request_type":"one_day"%');
            }
        }

        $intakes = $intakesQ->latest('id')->paginate(10);

        // 결제 테이블이 아직 없을 수 있으므로 방어적으로 처리
        $payments = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('payments')) {
                $payments = Payment::query()
                    ->where('user_id', $user->id)
                    ->latest('id')
                    ->limit(5)
                    ->get();
            }
        } catch (\Throwable $e) {
            // 무시하고 빈 목록
            $payments = collect();
        }

        return view('member.dashboard', [
            'intakes' => $intakes,
            'payments' => $payments,
        ]);
    }

    /**
     * List of intakes (matched by email)
     */
    public function inquiries(Request $request)
    {
        $user = $request->user();
        // 기본 탭은 1초 Set
        $type = $request->query('type') ?: 'instant'; // 'instant' | 'one_day' | 'direct'

        $base = IntakeRequest::with(['latestSet'])
            ->withLatestFlags()
            ->where('contact_email', $user->email)
            ->latest('id');

        // 의뢰내역(옵션)은 실제 '의뢰하기'가 진행된 항목만: notes.type == 'option_order'
        $optQ = (clone $base)
            ->where('category', 'option')
            ->where('notes', 'like', '%"type":"option_order"%');
        if ($type === 'instant') {
            $optQ->where(function($q){
                $q->where('notes', 'like', '%"request_type":"instant"%')
                  ->orWhere('notes', 'not like', '%"request_type":%')
                  ->orWhereNull('notes');
            });
        } elseif ($type === 'one_day') {
            $optQ->where('notes', 'like', '%"request_type":"one_day"%');
        }
        // type이 direct인 경우 옵션 결과를 비워 섹션을 숨김
        if ($type === 'direct') { $optQ->whereRaw('1=0'); }
        $optionInquiries = $optQ->paginate(10, ['*'], 'opt');

        $dirQ = (clone $base)->where('category', 'direct');
        if ($type && $type !== 'direct') { $dirQ->whereRaw('1=0'); }
        $directInquiries = $dirQ->paginate(10, ['*'], 'dir');

        return view('member.inquiries', [
            'optionInquiries' => $optionInquiries,
            'directInquiries' => $directInquiries,
            'type' => $type,
        ]);
    }

    /**
     * Private recommendations view (for user without public token).
     */
    public function recommendations(IntakeRequest $intake)
    {
        $set = RecommendationSet::with(['intakeRequest','entries.artist'])
            ->where('intake_request_id', $intake->id)
            ->latest('id')
            ->first();

        if (!$set) {
            // Fallback to dynamic selector
            return redirect()->route('inquiry.select_options', ['intake' => $intake->id]);
        }

        // If public token exists, use public view for consistency
        if (!empty($set->public_token)) {
            return Redirect::to(RouteFacade::has('share.token') ? route('share.token', ['token'=>$set->public_token]) : url('/r/'.$set->public_token));
        }

        // Build options from set
        $ctrl = app(\App\Http\Controllers\PublicRecommendationsController::class);
        [$options] = $ctrl->buildOptions($set, 0);

        return view('inquiry.select_options', [
            'set'    => $set,
            'intake' => $set->intakeRequest,
            'options'=> $options,
        ]);
    }

    /**
     * Private option detail view for set without token.
     */
    public function recommendationOption(IntakeRequest $intake, int $idx)
    {
        $set = RecommendationSet::with(['intakeRequest','entries.artist'])
            ->where('intake_request_id', $intake->id)
            ->latest('id')
            ->first();
        if (!$set) abort(404);

        $ctrl = app(\App\Http\Controllers\PublicRecommendationsController::class);
        [$options, $option] = $ctrl->buildOptions($set, $idx);
        if (!$option) abort(404);

        return view('inquiry.option_report', [
            'set'    => $set,
            'intake' => $set->intakeRequest,
            'option' => $option,
            'options'=> $options,
            'index'  => $idx,
        ]);
    }

    /**
     * Payment history (simple)
     */
    public function payments(Request $request)
    {
        // 결제 테이블이 없으면 빈 목록 반환
        if (!Schema::hasTable('payments')) {
            $payments = collect();
            return view('member.payments', compact('payments'));
        }

        $payments = Payment::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->paginate(20);

        return view('member.payments', compact('payments'));
    }

    /**
     * Favorites list
     */
    public function favorites(Request $request)
    {
        $user = $request->user();
        if (!$user || !Schema::hasTable('artist_favorites')) {
            $artists = collect();
            return view('member.favorites', compact('artists'));
        }

        $artists = $user->favoriteArtists()
            ->with('discipline')
            ->latest('artist_favorites.id')
            ->paginate(12);

        return view('member.favorites', compact('artists'));
    }
}
