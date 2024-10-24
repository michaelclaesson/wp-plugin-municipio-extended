@php
echo json_encode($layout);
echo json_encode($stretch);
@endphp

@switch($layout ?? 'card')
  @case ('card')
    @include('mxui.segment.card')
    @break
  @case ('split')
    @include('mxui.segment.split')
    @break
@endswitch