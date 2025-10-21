<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeRequest;
use App\Models\RecommendationSet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IntakeExportController extends Controller
{
    public function __invoke(Request $req): StreamedResponse
    {
        // 옵션 필터: q(이름/이메일), from/to(YYYY-MM-DD), limit
        $q     = trim((string) $req->get('q', ''));
        $from  = $req->get('from'); // '2025-09-01' 형식
        $to    = $req->get('to');
        $limit = max(1, min(5000, (int) $req->integer('limit', 1000)));

        $base = IntakeRequest::query()
            ->select(['intake_requests.id', 'contact_name', 'contact_email', 'created_at'])
            ->addSelect([
                'latest_sent_at' => RecommendationSet::select('sent_at')
                    ->whereColumn('intake_request_id', 'intake_requests.id')->orderByDesc('id')->limit(1),
                'latest_token'   => RecommendationSet::select('public_token')
                    ->whereColumn('intake_request_id', 'intake_requests.id')->orderByDesc('id')->limit(1),
                'latest_set_id'  => RecommendationSet::select('id')
                    ->whereColumn('intake_request_id', 'intake_requests.id')->orderByDesc('id')->limit(1),
            ]);

        if ($q !== '') {
            $base->where(function ($w) use ($q) {
                $w->where('contact_name', 'like', "%{$q}%")
                    ->orWhere('contact_email', 'like', "%{$q}%");
            });
        }
        if ($from) {
            $base->whereDate('intake_requests.created_at', '>=', $from);
        }
        if ($to) {
            $base->whereDate('intake_requests.created_at', '<=', $to);
        }

        $rows = $base->latest('intake_requests.id')->limit($limit)->get();

        $filename = 'intakes_' . now('UTC')->format('Ymd_His') . '.csv';
        $tzLocal = 'Asia/Seoul';

        return response()->streamDownload(function () use ($rows, $tzLocal) {
            $out = fopen('php://output', 'w');

            // ★ 추가: UTF-8 BOM을 먼저 써서 엑셀에서 한글이 깨지지 않게 함
            fwrite($out, "\xEF\xBB\xBF");

            // CSV 헤더
            fputcsv($out, [
                'intake_id',
                'contact_name',
                'contact_email',
                'created_at_utc',
                'created_at_local',
                'latest_set_id',
                'latest_token',
                'latest_sent_at_utc',
                'latest_sent_at_local'
            ]);

            foreach ($rows as $r) {
                $created = $r->created_at ? Carbon::parse($r->created_at) : null;
                $createdUtc = $created ? $created->copy()->tz('UTC')->toIso8601String() : '';
                $createdLoc = $created ? $created->copy()->tz($tzLocal)->toIso8601String() : '';

                $sent = $r->latest_sent_at ? Carbon::parse($r->latest_sent_at, 'UTC') : null;
                $sentUtc = $sent ? $sent->copy()->tz('UTC')->toIso8601String() : '';
                $sentLoc = $sent ? $sent->copy()->tz($tzLocal)->toIso8601String() : '';

                fputcsv($out, [
                    $r->id,
                    $r->contact_name,
                    $r->contact_email,
                    $createdUtc,
                    $createdLoc,
                    $r->latest_set_id,
                    $r->latest_token,
                    $sentUtc,
                    $sentLoc,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
