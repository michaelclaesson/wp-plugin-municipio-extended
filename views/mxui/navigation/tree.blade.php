@switch($tree_style ?? 'standard')
  @case ('standard')
    @include('mxui.navigation.tree.standard')
    @break
  @case ('highlighted')
    @include('mxui.navigation.tree.highlighted')
    @break
@endswitch
