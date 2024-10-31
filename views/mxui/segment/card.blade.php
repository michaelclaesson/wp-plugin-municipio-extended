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
  'class' => ['tailwind', $classList]
])}}>
  <div class='@container'>
    <section
    {{ mx_attrs(
      [
        'class' => ['flex flex-col min-h-full group @[50rem]:flex-row'],
      ],
      $attributeList,
      ) }}>
    <div
    class="-mt-8 mx-4 w-auto @[50rem]:mt-8 @[50rem]:absolute @[50rem]:w-1/2 @[50rem]:mx-0 {{ $reverseColumns ? '@[50rem]:left-1/2' : '@[50rem]:right-1/2' }}">
    @component('mxui.card', [
      'heading' => $title,
      'content' => $content ? new \Illuminate\Support\HtmlString($content) : null,
      'date' => $date,
      'meta' => $meta,
      'tags' => $tags,
      'link' => $link,
      'wrapContent' => true,
      'classList' => ['[--card-px:2.5rem] [--card-py:1.5rem] @[50rem]:min-h-80 isolate'],
      'expandLinkCover' => true,
      'buttons' => $buttons,
      ])
      @endcomponent
    </div>
    @if (!empty($image))
      <div
        {{ mx_attrs([
          'class' => [
            'w-full @[50rem]:relative @[50rem]:w-3/4 @[50rem]:max-h-96',
            '-order-1',
            $reverseColumns ? '' : '@[50rem]:left-1/4',
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
