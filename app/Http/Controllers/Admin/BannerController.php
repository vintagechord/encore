<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BannerController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('banners')) {
            return view('admin.banners.index', [
                'banners' => collect(),
                'tableMissing' => true,
            ]);
        }
        $banners = Banner::orderBy('display_order')->orderByDesc('id')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form', [
            'banner' => new Banner(['is_active' => true, 'display_order' => 0]),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }
        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('ok', '배너가 생성되었습니다.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', [
            'banner' => $banner,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validated($request, $banner->id);
        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }
        $banner->update($data);
        return redirect()->route('admin.banners.index')->with('ok', '배너가 업데이트되었습니다.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('ok', '삭제했습니다.');
    }

    protected function validated(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'title'          => ['required', 'string', 'max:150'],
            'link_url'       => ['nullable', 'string', 'max:500'],
            'is_active'      => ['nullable', 'boolean'],
            'display_order'  => ['nullable', 'integer', 'min:0', 'max:9999'],
            'image'          => ['nullable', 'image', 'max:5120'],
        ]) + [
            'is_active'     => $request->boolean('is_active'),
            'display_order' => $request->integer('display_order', 0),
        ];
    }
}

