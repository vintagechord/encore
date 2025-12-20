<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeRequest;
use Illuminate\Http\Request;

class IntakeStatusController extends Controller
{
    public function update(IntakeRequest $intake, Request $request)
    {
        $data = $request->validate([
            // 확장된 상태 흐름: new → processing → recommended → closed → booked → completed
            'status' => ['required','in:new,processing,recommended,closed,booked,completed']
        ]);
        $intake->status = $data['status'];
        $intake->save();
        return back()->with('ok', true);
    }
}
