@props(['maxHeight' => '300px', 'paddingRight' => '8px', 'as' => 'div'])

<{{ $as }} 
    {{ $attributes->merge([
        'class' => 'cw-scrollbar',
        'style' => "max-height: {$maxHeight}; overflow-y: auto; padding-right: {$paddingRight};"
    ]) }}
>
    {{ $slot }}
</{{ $as }}>
