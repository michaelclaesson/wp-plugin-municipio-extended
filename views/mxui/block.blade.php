{{--
background
--}}

@php
  $title ??= null;
  $content ??= null;
@endphp

<!-- mxui.block -->
<div {{ mx_attrs([
    'class' => ['tailwind', $classList],
]) }}>
  <section {{ mx_attrs([
      'class' => ['group grid'],
      $attributeList,
  ]) }}>
    @if ($title || $content)
      <div
        {{ mx_attrs([
            'class' => [
                'relative isolate row-start-1 col-start-1 z-2 text-white',
                'bg-[color:var(--c-segment-color-overlay,var(--color-alpha,#0000008c))]' => !empty($overlay),
                'flex flex-col text-center items-center justify-center',
            ],
        ]) }}>
        <div class="prose">
          @if ($title)
            <h1>
              {{ $title }}
            </h1>
          @endif
          @if ($content)
            <p>{{ $content }}</p>
          @endif
        </div>
      </div>
    @endif
    <div
      {{ mx_attrs([
          'class' => [
              'row-start-1 col-start-1 w-full',
              'aspect-video bg-secondary first:last:mb-0',
              'overflow-hidden border-none',
          ],
      ]) }}>
      @if ($image ?? null)
        @component('mxui.image', [
            'image' => $image,
            'size' => 'medium',
            'classList' => ['text-transparent', 'group-hover:scale-105 transition-transform duration-500' => $link],
        ])
        @endcomponent
      @endif
    </div>
  </section>
</div>
