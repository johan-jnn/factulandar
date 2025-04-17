<{{ $element }} 
    contenteditable="{{ $editable === true ? 'true' : 'false' }}" 
    x-data='{value:{{ $initVal }}}'
    aria-placeholder="{{ $placeholder }}" 
    aria-label="{{ $label }}" 
    x-modelable="value"
    @isset($text)
        x-text='{{ $text }}'
    @endisset
    x-model="{{ $model }}" @input='value=(() => {{ $value }})()'>
    {{ $slot }}
</{{ $element }}>
