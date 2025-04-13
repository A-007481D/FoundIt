@props(['mobile' => false, 'active' => false])

@php
    $classes = $mobile ? 'block px-3 py-2 rounded-md text-base font-medium text-gray-600 hover:text-purple-600'
                     : 'px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-purple-600';
    $activeClasses = $active ? 'text-purple-600 border-b-2 border-purple-500' : '';
@endphp

<a {{ $attributes->merge(['class' => "$classes $activeClasses"]) }}>
    {{ $slot }}
</a>
