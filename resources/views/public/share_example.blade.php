<x-guest-layout>
  <div class="max-w-6xl mx-auto px-6 py-10">
    <h1 class="text-3xl md:text-4xl font-extrabold mb-6">추천 예시</h1>
    <p class="text-slate-500 mb-8">아래는 샘플 카드예요. 실제 추천은 문의 접수 후 맞춤으로 생성됩니다.</p>

    @php
      $samples = [
        ['name' => '아티스트 A', 'desc' => '힙합/R&B · 40분 공연', 'price' => '1,500만 원'],
        ['name' => '아티스트 B', 'desc' => '밴드/록 · 60분 공연', 'price' => '900만 원'],
        ['name' => '아티스트 C', 'desc' => '팝 · 30분 공연', 'price' => '700만 원'],
        ['name' => '아티스트 D', 'desc' => '재즈 · 50분 공연', 'price' => '800만 원'],
        ['name' => '아티스트 E', 'desc' => 'EDM DJ · 90분 공연', 'price' => '1,200만 원'],
        ['name' => '아티스트 F', 'desc' => '발라드 · 30분 공연', 'price' => '600만 원'],
      ];
    @endphp

    <div class="grid gap-6 md:grid-cols-3">
      @foreach($samples as $s)
        <article class="p-6 rounded-2xl border bg-white/50">
          <h3 class="text-lg font-semibold">{{ $s['name'] }}</h3>
          <p class="text-slate-600 mt-1">{{ $s['desc'] }}</p>
          <p class="text-slate-500 mt-2">예상 예산: {{ $s['price'] }}</p>
          <a href="{{ route('inquiry.create') }}" class="inline-block mt-4 px-4 py-2 rounded-xl border">이 아티스트로 문의</a>
        </article>
      @endforeach
    </div>
  </div>
</x-guest-layout>

