<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::query()->orderByDesc('id')->paginate(20);
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        $notice = new Notice();
        return view('admin.notices.form', compact('notice'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:200'],
            'body'  => ['required','string'],
            'is_published' => ['nullable','boolean'],
            'published_at' => ['nullable','date'],
        ]);
        $data['is_published'] = (bool)($data['is_published'] ?? false);
        $notice = Notice::create($data);
        return redirect()->route('admin.notices.edit', $notice)->with('ok', true);
    }

    public function edit(Notice $notice)
    {
        return view('admin.notices.form', compact('notice'));
    }

    public function update(Notice $notice, Request $request)
    {
        $data = $request->validate([
            'title' => ['required','string','max:200'],
            'body'  => ['required','string'],
            'is_published' => ['nullable','boolean'],
            'published_at' => ['nullable','date'],
        ]);
        $data['is_published'] = (bool)($data['is_published'] ?? false);
        $notice->update($data);
        return back()->with('ok', true);
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('admin.notices.index')->with('ok', true);
    }
}

