@if (!$hideTitle && !empty($postTitle))
  <h2 class="c-typography c-typography__variant--h2 module-title">{{ $postTitle }}</h2>
@endif
<div class="tailwind">
  @switch($format)
    @case ('list')
      @include('mxui.navigation.list')
      @break
    @case ('grid')
      @include('mxui.navigation.grid')
      @break
    @case ('bar')
      @include('mxui.navigation.bar')
      @break
    @case ('tree')
      @include('mxui.navigation.tree')
      @break
    @endswitch
</div>
