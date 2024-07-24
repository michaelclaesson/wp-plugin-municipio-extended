@if (!$hideTitle && !empty($postTitle))
  <h2>{{ $postTitle }}</h2>
@endif
<div class="tailwind">
  @switch($format)
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
