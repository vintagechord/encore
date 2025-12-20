<?php

namespace App\Http\Controllers;

use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(10);
        return view('public.notices.index', compact('notices'));
    }

    public function show(Notice $notice)
    {
        if (!$notice->is_published) abort(404);
        return view('public.notices.show', compact('notice'));
    }
}

