@props(['disabled' => false])

<input 
    {{ $disabled ? 'disabled' : '' }} 
    {!! $attributes->merge([
        'class' => 'block w-full px-2 py-2 bg-white border-2 border-[#ac88cb] focus:border-[#9adde6] focus:ring focus:ring-[#ac88cb]/30 rounded-lg shadow-sm'
    ]) !!}>
