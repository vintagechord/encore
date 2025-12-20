<x-app-layout>
    {{-- Keep header slot empty to avoid shifting the top menu bar; title is inside content below. --}}

    <style>
      /* Align account settings page to Encore site tone */
      .enc-container { max-width:1120px; margin:0 auto; padding:24px 20px; }
      /* Cards */
      .bg-slate-900 { background: var(--card) !important; }
      .border-slate-800, .border-slate-700 { border-color: var(--border) !important; }
      .text-slate-100, .text-slate-200 { color: var(--fg) !important; }
      .text-slate-400, .placeholder\:text-slate-500::placeholder { color: var(--muted) !important; }
      .shadow-slate-900\/60 { box-shadow: 0 12px 30px rgba(2, 4, 10, .55) !important; }
      [data-theme="light"] .shadow-slate-900\/60 { box-shadow: 0 12px 30px rgba(2, 6, 23, .1) !important; }
      /* Inputs */
      .bg-slate-900 { background: var(--card) !important; }
      .focus\:ring-indigo-400, .focus\:border-indigo-400 { --tw-ring-color: var(--ring) !important; border-color: var(--ring) !important; }
      /* Primary button */
      .bg-indigo-600 { background: var(--accent) !important; border-color: var(--accent) !important; }
      .hover\:bg-indigo-500:hover, .focus\:bg-indigo-500:focus { background: var(--accent-hover) !important; }
      .focus\:ring-indigo-400 { --tw-ring-color: var(--ring) !important; }
      .focus\:ring-offset-slate-900 { --tw-ring-offset-color: var(--card) !important; }
      /* Headline */
      h1.text-slate-100 { font-size:22px; }
    </style>

    <div class="enc-container">
        <div class="space-y-6">
            <section class="p-4 sm:p-8 bg-slate-900 border border-slate-800 shadow-lg shadow-slate-900/60 sm:rounded-lg">
                <h1 class="text-slate-100" style="margin:0 0 12px; font-weight:700;">계정 설정</h1>
                @include('profile.partials.update-profile-information-form')
            </section>

            <section class="p-4 sm:p-8 bg-slate-900 border border-slate-800 shadow-lg shadow-slate-900/60 sm:rounded-lg">
                @include('profile.partials.update-password-form')
            </section>

            <section class="p-4 sm:p-8 bg-slate-900 border border-slate-800 shadow-lg shadow-slate-900/60 sm:rounded-lg">
                @include('profile.partials.delete-user-form')
            </section>
        </div>
    </div>
</x-app-layout>
