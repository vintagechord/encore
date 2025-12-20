<?php

namespace App\Http\Controllers;

use App\Models\IntakeRequest;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(IntakeRequest $intake, Request $request)
    {
        $this->authorizeView($intake, $request);
        $amount = (int)($request->query('amount') ?? $intake->budget_max ?? $intake->budget_min ?? 0);
        $meta = [];
        $notes = json_decode((string)$intake->notes, true);
        if (is_array($notes) && ($notes['type'] ?? '') === 'option_order') {
            $meta['option'] = $notes;
            if (empty($amount)) $amount = (int)($notes['budget_max'] ?? $notes['budget_min'] ?? 0);
        }
        return view('payments.checkout', compact('intake','amount','meta'));
    }

    public function store(IntakeRequest $intake, Request $request)
    {
        $this->authorizeView($intake, $request);
        $data = $request->validate([
            'amount' => ['required','integer','min:0'],
            'method' => ['required','in:card,bank'],
        ]);

        $isCard = $data['method'] === 'card';
        $status = $isCard ? 'paid' : 'pending';
        $pay = Payment::create([
            'user_id' => $request->user()->id,
            'amount' => (int)$data['amount'],
            'currency' => 'KRW',
            'status' => $status,
            'description' => 'Option order payment for intake #'.$intake->id,
            'meta' => [
                'intake_id' => $intake->id,
                'method' => $data['method'],
                'option' => json_decode((string)$intake->notes, true),
            ],
            'paid_at' => $isCard ? now() : null,
        ]);

        $intake->status = 'closed';
        $intake->save();

        $redir = redirect()->route('payment.show', ['payment'=>$pay->id]);
        if (!$isCard) {
            $redir->with('ok', '무통장 입금 주문이 완료되었습니다. 입금 확인 후 처리됩니다.');
        }
        return $redir;
    }

    public function show(Payment $payment, Request $request)
    {
        if ($payment->user_id !== $request->user()->id) abort(403);
        return view('payments.show', compact('payment'));
    }

    protected function authorizeView(IntakeRequest $intake, Request $request): void
    {
        $user = $request->user();
        if (!$user) abort(403);
        if ($intake->contact_email !== $user->email) abort(403);
    }
}
