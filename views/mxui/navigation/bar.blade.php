@switch($bar_style ?? 'outline')
  @case ('outline')
    @include('mxui.navigation.bar.outline')
    @break
  @case ('solid')
    @include('mxui.navigation.bar.solid')
    @break
@endswitch
