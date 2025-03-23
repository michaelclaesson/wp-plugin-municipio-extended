{{--
containerAware
dateBadge
height
icon
layout
overlay
reverseColumns
sub_title

buttons
--}}


<!-- mxui.segment.card -->
<div {{ mx_attrs([
    'class' => ['tailwind', $classList],
]) }}>
  <div class='@container'>
    <section
      {{ mx_attrs(
          [
              'class' => ['grid min-h-full group', '@sm:grid-cols-[1fr_1fr_minmax(12.5rem,1fr)_minmax(12.5rem,1fr)]'],
          ],
          $attributeList,
      ) }}>
      <div
        class="{{ clsx([
            'z-[1] w-auto',
            'row-start-2 -mt-8 last:mt-0 mx-4',
            '@sm:my-8 @sm:mx-0 @sm:row-start-1 @sm:row-span-1',
            $reverseColumns ? '@sm:col-start-3 @sm:col-end-5' : '@sm:col-start-1 @sm:col-end-3',
        ]) }}">
        @component('mxui.card', [
            'heading' => $title,
            'content' => $content ? new \Illuminate\Support\HtmlString($content) : null,
            'date' => $date,
            'meta' => $meta,
            'tags' => $tags,
            'link' => $link,
            'wrapContent' => true,
            'classList' => ['[--card-px:2.5rem] [--card-py:1.5rem] isolate'],
            'expandLinkCover' => true,
            'buttons' => $buttons ?? [],
            'proseWrap' => $proseWrap ?? null,
            'headingVariant' => $headingVariant ?? null,
        ])
        @endcomponent
      </div>
      @if (!empty($image))
        <div
          {{ mx_attrs([
              'class' => [
                  'row-start-1',
                  'w-full @sm:row-start-1 @sm:place-self-stretch',
                  // '@sm:max-h-96',
                  $reverseColumns ? '@sm:col-start-1 @sm:col-end-4' : '@sm:col-start-2 @sm:col-end-5',
                  'aspect-video bg-secondary first:last:mb-0',
                  'overflow-hidden border-none rounded-[var(--c-segment-image-border-radius,var(--radius-lg,calc(var(--base,8px)*1.5)))]',
              ],
          ]) }}>
          @component('mxui.image', [
              'image' => $image,
              'size' => 'medium',
              'classList' =>
                  'group-hover:group-has-[[data-mxui-card-link][data-mxui-interactive]]:scale-105 transition-transform duration-500 text-transparent',
          ])
          @endcomponent
        </div>
      @endif
    </section>
  </div>
</div>
