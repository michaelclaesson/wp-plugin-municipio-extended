@if ($useHbg)
  <!-- segment.blade.php -->
  <section class="{{ $class }}" {!! $attribute !!}>
    @if ($floatingSlotHasData)
      <div class="{{ $baseClass }}__floating">
        {!! $floating !!}
      </div>
    @endif
    @includeWhen($image, 'Segment.components.image')
    @include('Segment.partials.' . $layout)
  </section>
@else
    @switch($layout ?? 'card')
      @case('card')
        @include('mxui.segment')
      @break

      @case('full-width')
        @include('mxui.block')
      @break

      @default
        @include('mxui.segment')
    @endswitch
@endif
