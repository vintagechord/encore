<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeRequest;
use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $quotes = Quote::with('intake')->latest('id')->paginate(20);
        return view('admin.quotes.index', compact('quotes'));
    }

    public function create(Request $request)
    {
        $intake = $request->query('intake') ? IntakeRequest::find($request->query('intake')) : null;
        return view('admin.quotes.form', [
            'quote' => new Quote(['currency'=>'KRW']),
            'intake' => $intake,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'intake_request_id' => ['required','exists:intake_requests,id'],
            'title' => ['required','string','max:190'],
            'body' => ['nullable','string'],
            'amount' => ['required','integer','min:0'],
            'currency' => ['required','string','max:10'],
            'status' => ['required','in:draft,sent,accepted,rejected'],
        ]);
        if ($data['status'] === 'sent') $data['sent_at'] = now();
        Quote::create($data);
        return redirect()->route('admin.quotes.index')->with('ok','견적서를 저장했습니다.');
    }

    public function edit(Quote $quote)
    {
        return view('admin.quotes.form', [
            'quote' => $quote,
            'intake' => $quote->intake,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Quote $quote)
    {
        $data = $request->validate([
            'title' => ['required','string','max:190'],
            'body' => ['nullable','string'],
            'amount' => ['required','integer','min:0'],
            'currency' => ['required','string','max:10'],
            'status' => ['required','in:draft,sent,accepted,rejected'],
        ]);
        if ($data['status'] === 'sent' && !$quote->sent_at) $data['sent_at'] = now();
        $quote->update($data);
        return redirect()->route('admin.quotes.index')->with('ok','견적서를 업데이트했습니다.');
    }

    public function destroy(Quote $quote)
    {
        $quote->delete();
        return redirect()->route('admin.quotes.index')->with('ok','삭제했습니다.');
    }
}

