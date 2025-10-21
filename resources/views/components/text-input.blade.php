@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-700 bg-slate-900 text-slate-100 placeholder:text-slate-500 focus:border-indigo-400 focus:ring-indigo-400 rounded-md shadow-sm']) }}>
