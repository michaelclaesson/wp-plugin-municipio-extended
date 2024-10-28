@if ($useHbg)
  <!-- icon.blade.php -->
  @if ($icon)
    <{{ $componentElement }} class="{{ $class }}" {!! $attribute !!}>
      @includeWhen(!empty($svgFromLink), 'Icon.partials.svgImage')
      @includeWhen(!empty($svgElementFromFile), 'Icon.partials.svgElement')
      </{{ $componentElement }}>
  @endif
@else
  @if ($icon)
    <span class="tailwind contents">
      @include('mxui.icon')
    </span>
  @endif
@endif
