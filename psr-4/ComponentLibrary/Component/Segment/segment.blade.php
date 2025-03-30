@if ($useHbg ?? null)
  <!-- segment.blade.php -->
  <section class="{{ $class ?? '' }}" {!! $attribute ?? '' !!}>
    @if ($floatingSlotHasData ?? null)
      <div class="{{ $baseClass }}__floating">
        {!! $floating !!}
      </div>
    @endif
    @includeWhen($image ?? null, 'Segment.components.image')
    @include('Segment.partials.' . $layout)
  </section>
@else
  <!-- Segment (MXUI), layout: {{ $layout }} -->
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
