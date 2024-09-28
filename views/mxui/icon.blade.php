@php
  $icon = mx_get_icon($icon);
@endphp
<span class="inline-block bg-current size-[1em] [mask-size:100%] [mask-repeat:no-repeat]"
  style="mask-image: url('{{ $icon->withRenderParams([
      'filled' => $filled,
  ])->url }}')"></span>
