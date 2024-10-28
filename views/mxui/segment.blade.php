@switch($layout ?? 'card')
  @case ('card')
    @include('mxui.segment.card')
  @break

  @case ('split')
    @include('mxui.segment.split')
  @break
@endswitch
