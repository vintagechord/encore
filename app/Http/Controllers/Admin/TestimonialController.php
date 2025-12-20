<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TestimonialController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('testimonials')) {
            return view('admin.testimonials.index', [
                'testimonials' => collect(),
                'tableMissing' => true,
            ]);
        }

        $testimonials = Testimonial::orderBy('display_order')->orderByDesc('id')->get();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.form', [
            'testimonial' => new Testimonial(['is_active' => true, 'display_order' => 0]),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Testimonial::create($data);
        return redirect()->route('admin.testimonials.index')->with('ok', '샘플/후기가 생성되었습니다.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.form', [
            'testimonial' => $testimonial,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $this->validated($request);
        $testimonial->update($data);
        return redirect()->route('admin.testimonials.index')->with('ok', '샘플/후기가 업데이트되었습니다.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('ok', '삭제했습니다.');
    }

    protected function validated(Request $request): array
    {
        return $request->validate([
            'kind' => ['required', 'string', 'max:40'],
            'title' => ['required', 'string', 'max:150'],
            'body' => ['required', 'string', 'max:2000'],
            'meta' => ['nullable', 'string', 'max:160'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'is_active' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'display_order' => $request->integer('display_order', 0),
            'rating' => $request->filled('rating') ? (float) $request->input('rating') : null,
        ];
    }
}
