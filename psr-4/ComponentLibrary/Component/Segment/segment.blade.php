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
  <div class="tailwind contents">
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
  </div>
@endif
