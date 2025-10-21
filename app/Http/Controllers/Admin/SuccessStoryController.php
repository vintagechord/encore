<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuccessStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuccessStoryController extends Controller
{
    public function index()
    {
        $stories = SuccessStory::query()
            ->orderBy('display_order')
            ->orderByDesc('event_date')
            ->orderByDesc('id')
            ->get();

        return view('admin.success_stories.index', compact('stories'));
    }

    public function create()
    {
        return view('admin.success_stories.form', [
            'story' => new SuccessStory([
                'category' => '섭외 확정',
                'is_active' => true,
            ]),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')
                ->store('success-stories', 'public');
        }

        SuccessStory::create($data);

        return redirect()->route('admin.success-stories.index')
            ->with('ok', '섭외 사례가 저장되었습니다.');
    }

    public function edit(SuccessStory $success_story)
    {
        return view('admin.success_stories.form', [
            'story' => $success_story,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, SuccessStory $success_story)
    {
        $data = $this->validated($request, $success_story->id);

        if ($request->hasFile('thumbnail')) {
            if ($success_story->thumbnail_path) {
                Storage::disk('public')->delete($success_story->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')
                ->store('success-stories', 'public');
        } elseif ($request->boolean('remove_thumbnail')) {
            if ($success_story->thumbnail_path) {
                Storage::disk('public')->delete($success_story->thumbnail_path);
            }
            $data['thumbnail_path'] = null;
        }

        $success_story->update($data);

        return redirect()->route('admin.success-stories.index')
            ->with('ok', '섭외 사례가 업데이트되었습니다.');
    }

    public function destroy(SuccessStory $success_story)
    {
        if ($success_story->thumbnail_path) {
            Storage::disk('public')->delete($success_story->thumbnail_path);
        }
        $success_story->delete();

        return redirect()->route('admin.success-stories.index')
            ->with('ok', '섭외 사례를 삭제했습니다.');
    }

    protected function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'category'       => ['nullable', 'string', 'max:120'],
            'title'          => ['required', 'string', 'max:150'],
            'role'           => ['nullable', 'string', 'max:120'],
            'event_name'     => ['nullable', 'string', 'max:190'],
            'event_date'     => ['nullable', 'date'],
            'location'       => ['nullable', 'string', 'max:190'],
            'summary'        => ['nullable', 'string', 'max:600'],
            'is_active'      => ['boolean'],
            'display_order'  => ['nullable', 'integer', 'min:0', 'max:9999'],
            'thumbnail'      => ['nullable', 'image', 'max:5120'],
            'thumbnail_path' => ['nullable', 'string', 'max:255'],
        ]) + [
            'is_active'     => $request->boolean('is_active'),
            'display_order' => $request->integer('display_order', 0),
        ];
    }
}
