@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-indigo-400 text-start text-base font-medium text-indigo-200 bg-slate-900 focus:outline-none focus:text-indigo-100 focus:bg-slate-800 focus:border-indigo-300 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-300 hover:text-indigo-200 hover:bg-slate-900/70 hover:border-indigo-400 focus:outline-none focus:text-indigo-200 focus:bg-slate-900/70 focus:border-indigo-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
