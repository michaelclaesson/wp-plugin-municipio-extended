@if ($useHbg)
  <!-- card.blade.php -->
  <{{ $componentElement }} class="{{$class}}" {!! $attribute !!}>
    @includeWhen(!$slotHasData, 'Card.views.base')
    {!! $slot !!}
    @if($afterContentSlotHasData)
      {!! $afterContent !!}
    @endif
  </{{ $componentElement }}>
@else
  <div class="tailwind contents">
    @include('mxui.card')
  </div>
@endif
