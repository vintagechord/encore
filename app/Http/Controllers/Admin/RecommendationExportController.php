<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeRequest;
use App\Models\RecommendationSet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RecommendationExportController extends Controller
{
    /**
     * 특정 문의의 "최신" 추천안을 CSV로 내보냅니다.
     * - artists 관계가 있으면 (rank/name/score/reason) 행으로
     * - 아니면 items(JSON 배열) 행으로
     * 각 행에 기본 메타(문의/셋 정보)도 포함합니다.
     */
    public function __invoke(IntakeRequest $intake, Request $request)
    {
        $set = RecommendationSet::with(['artists' => function ($q) {
            $q->withPivot(['rank', 'score', 'reason'])->orderBy('artist_recommendation_set.rank');
        }])
            ->where('intake_request_id', $intake->id)
            ->latest('id')
            ->first();

        if (!$set) {
            return response()->json(['message' => '추천안이 없습니다.'], 404);
        }

        $hasArtists = $set->artists && $set->artists->count() > 0;

        // 헤더 정의
        if ($hasArtists) {
            $header = [
                'rank',
                'artist',
                'score',
                'reason',
                'intake_id',
                'contact_name',
                'contact_email',
                'intake_created_at',
                'set_id',
                'set_label',
                'set_sent_at',
            ];
        } else {
            // items(JSON) 기반
            $header = [
                'index',
                'name',
                'price',
                'memo',
                'intake_id',
                'contact_name',
                'contact_email',
                'intake_created_at',
                'set_id',
                'set_label',
                'set_sent_at',
            ];
        }

        // CSV 바디
        $rows = [];
        $intakeCreated = optional($intake->created_at)->toIso8601String();
        $sentAt        = optional($set->sent_at)->toIso8601String();

        if ($hasArtists) {
            foreach ($set->artists as $a) {
                $rows[] = [
                    (int) ($a->pivot->rank ?? 0),
                    (string) ($a->name ?? ''),
                    is_null($a->pivot->score) ? '' : (string) $a->pivot->score,
                    (string) ($a->pivot->reason ?? ''),
                    $intake->id,
                    (string) $intake->contact_name,
                    (string) $intake->contact_email,
                    (string) $intakeCreated,
                    $set->id,
                    (string) $set->label,
                    (string) $sentAt,
                ];
            }
        } else {
            $items = is_array($set->items) ? $set->items
                : (is_string($set->items) ? json_decode($set->items, true) : []);

            $items = is_array($items) ? array_values($items) : [];
            foreach ($items as $idx => $it) {
                $name = is_array($it) ? ($it['name'] ?? ($it['title'] ?? '')) : (string) $it;
                $price = is_array($it) ? ($it['price'] ?? null) : null;
                $memo  = is_array($it) ? ($it['memo'] ?? ($it['note'] ?? '')) : '';

                $rows[] = [
                    $idx + 1,
                    (string) $name,
                    is_null($price) ? '' : (string) $price,
                    (string) $memo,
                    $intake->id,
                    (string) $intake->contact_name,
                    (string) $intake->contact_email,
                    (string) $intakeCreated,
                    $set->id,
                    (string) $set->label,
                    (string) $sentAt,
                ];
            }
        }

        // CSV 생성
        $fp = fopen('php://temp', 'r+');
        // 헤더
        fputcsv($fp, $header);
        // 데이터
        foreach ($rows as $r) {
            fputcsv($fp, $r);
        }
        rewind($fp);
        $content = stream_get_contents($fp);
        fclose($fp);

        // Excel 호환을 위한 UTF-8 BOM 추가
        $csv = "\xEF\xBB\xBF" . $content;

        $filename = sprintf(
            'recommendations-intake-%d-%s.csv',
            $intake->id,
            Carbon::now('UTC')->format('Ymd-His\Z')
        );

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
