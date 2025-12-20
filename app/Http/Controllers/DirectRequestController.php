<?php

namespace App\Http\Controllers;

use App\Models\IntakeRequest;
use Illuminate\Http\Request;

class DirectRequestController extends Controller
{
    public function create(Request $request)
    {
        $prefill = [
            'requested' => (string) $request->query('requested', ''),
        ];
        return view('inquiry.direct_request', $prefill);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'organization_type'      => ['required', 'in:business,public,school,nonprofit,other'],
            'org_name'               => ['required', 'string', 'max:150'],
            // 사업자등록번호: 10자리 숫자, 하이픈 허용, 포맷 000-00-00000
            'biz_reg_no'             => [
                'nullable', 'string', 'max:14',
                'regex:/^\d{3}-?\d{2}-?\d{5}$/',
                'required_if:organization_type,business'
            ],
            'contact_name'           => ['required', 'string', 'max:100'],
            'contact_email'          => ['required', 'email', 'max:190'],
            'contact_phone'          => ['nullable', 'string', 'max:50'],
            'requested_artist_name'  => ['required', 'string', 'max:190'],
            'event_start'            => ['nullable', 'date'],
            'event_end'              => ['nullable', 'date', 'after_or_equal:event_start'],
            'notes'                  => ['nullable', 'string', 'max:5000'],
            'intent_confirmed'       => ['required', 'accepted'],
            // 사업자등록증 사본 업로드: pdf 또는 이미지, 10MB까지
            'biz_cert'               => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240', 'required_if:organization_type,business'],
        ], [
            'biz_reg_no.required_if' => '사업자(법인/개인) 선택 시 사업자등록번호가 필요합니다.',
            'biz_reg_no.regex'       => '사업자등록번호 형식이 올바르지 않습니다(예: 123-45-67890).',
            'biz_cert.required_if'   => '사업자(법인/개인) 선택 시 사업자등록증 사본을 업로드해 주세요.',
            'biz_cert.mimes'         => '사업자등록증은 PDF 또는 이미지 파일만 가능합니다.',
        ]);

        // 도메인 이메일 제한(개인 이메일 금지)
        $restrictedTypes = ['business','public','school','nonprofit'];
        if (in_array($data['organization_type'], $restrictedTypes, true)) {
            $forbid = ['gmail.com','naver.com','hanmail.net','daum.net','hotmail.com','outlook.com','yahoo.com','icloud.com','kakao.com','nate.com'];
            $domain = strtolower(substr(strrchr($data['contact_email'], '@') ?: '', 1));
            if ($domain === '' || in_array($domain, $forbid, true)) {
                return back()->withErrors(['contact_email' => '기관/기업 도메인 이메일로 문의해 주세요.'])->withInput();
            }
        }

        // 사업자등록번호 체크섬 검증(선택 입력 시)
        if (!empty($data['biz_reg_no'])) {
            $digits = preg_replace('/\D/', '', (string)$data['biz_reg_no']);
            if (strlen($digits) !== 10) {
                return back()->withErrors(['biz_reg_no' => '사업자등록번호는 숫자 10자리여야 합니다.'])->withInput();
            }
            $nums = array_map('intval', str_split($digits));
            // 가중치 적용: 1,3,7,1,3,7,1,3,5(특수)
            $weights = [1,3,7,1,3,7,1,3];
            $sum = 0;
            for ($i=0; $i<8; $i++) { $sum += $nums[$i] * $weights[$i]; }
            $p = $nums[8] * 5; // 9번째 자리 * 5 → 각 자리수 합산
            $sum += intdiv($p, 10) + ($p % 10);
            $check = (10 - ($sum % 10)) % 10;
            if ($nums[9] !== $check) {
                return back()->withErrors(['biz_reg_no' => '유효하지 않은 사업자등록번호입니다.'])->withInput();
            }
        }

        // 정규화: 종료일이 비어있으면 시작일 복사
        if (empty($data['event_end']) && !empty($data['event_start'])) {
            $data['event_end'] = $data['event_start'];
        }

        // 파일 저장
        $bizCertPath = null;
        if ($request->hasFile('biz_cert')) {
            try {
                $bizCertPath = $request->file('biz_cert')->store('biz-certs', 'public');
            } catch (\Throwable $e) {
                return back()->withErrors(['biz_cert' => '파일을 저장하지 못했습니다. 잠시 후 다시 시도해 주세요.'])->withInput();
            }
        }

        // 로그인 사용자와 실제 연락 이메일을 분리 저장: 대시보드 연동은 계정 이메일 기준
        $user = $request->user();
        $accountEmail = $user?->email;
        $clientEmail = $data['contact_email'];
        $notesData = [
            'request_type' => 'direct',
            'notes_text'   => $data['notes'] ?? null,
        ];
        if ($accountEmail && strcasecmp($accountEmail, $clientEmail) !== 0) {
            $notesData['client_email'] = $clientEmail; // 실제 조직 측 연락처
            $data['contact_email'] = $accountEmail;   // 대시보드 연결을 위해 계정 이메일로 고정
        }

        $payload = [
            'contact_name'          => $data['contact_name'],
            'contact_email'         => $data['contact_email'],
            'contact_phone'         => $data['contact_phone'] ?? null,
            'org_name'              => $data['org_name'],
            'biz_cert_path'         => $bizCertPath,
            'event_start'           => $data['event_start'] ?? null,
            'event_end'             => $data['event_end'] ?? ($data['event_start'] ?? null),
            'category'              => 'direct',
            'genre_counts'          => [],
            'requested_artist_name' => $data['requested_artist_name'],
            'notes'                 => json_encode($notesData, JSON_UNESCAPED_UNICODE),
            'status'                => 'new',
        ];

        $intake = IntakeRequest::create($payload);

        return redirect()->route('inquiry.thanks');
    }
}
