@switch($grid_style ?? 'default')
  @case ('default')
    @include('mxui.navigation.grid.default')
    @break
  @case ('blocks')
    @include('mxui.navigation.grid.blocks')
    @break
@endswitch
