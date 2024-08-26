@if (!$hideTitle && !empty($postTitle))
  <h2 class="c-typography c-typography__variant--h2 module-title">{{ $postTitle }}</h2>
@endif
<div class="tailwind">
  @switch($format)
    @case ('list')
      @include('mxui.list-navigation')
      @break
    @case ('grid')
      @include('mxui.grid-navigation')
      @break
    @case ('bar')
      @include('mxui.bar-navigation')
      @break
    @case ('tree')
      @include('mxui.tree-navigation')
      @break
    @endswitch
</div>
