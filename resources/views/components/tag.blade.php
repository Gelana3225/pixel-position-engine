@props(['size' => 'base'])

@php
    $classes = "bg-white/17 hover:bg-white/25 rounded-xl transition-colors duration-300";

    if($size === 'base') {
        $classes .= " px-5 py-1 text-2xs";

    }

    if($size === 'small') {
        $classes .= " px-1 py-1 text-sm";}
@endphp

<a href="#" class="{{ $classes }}">{{ $slot }}</a>