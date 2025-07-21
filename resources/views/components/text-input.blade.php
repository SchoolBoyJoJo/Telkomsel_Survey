@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'border border-gray-400 bg-white text-gray-800 placeholder-gray-500 focus:border-red-600 focus:ring-red-600 rounded-md shadow-sm transition duration-150 ease-in-out',
    ]) !!}
/>

