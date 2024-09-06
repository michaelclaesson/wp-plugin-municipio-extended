{{--
Left to handle:

'meta' => $post->readingTime,
'metaFirst' => true,
'containerAware' => true,
'hasAction' => true,
'postId' => $post->id,
'postType' => $post->postType,
'icon' => $post->termIcon,
--}}

@php
  $asSubgrid ??= false;
  $classList ??= null;
  $content ??= null;
  $date ??= null;
  $dateBadge ??= false;
  $heading ??= null;
  $headingLevel ??= 3;
  $image ??= null;
  $link ??= null;
  $wrapContent ??= !empty($content) && is_string($content);
  $proseWrap ??= false;
@endphp

<div {{ mx_attrs(
  [
    'class' => [
      'grid content-start rounded-[var(--c-card-border-radius,var(--radius-lg,calc(var(--base,8px)*1.5)))] bg-white group relative overflow-hidden grid-cols-[100%]',
      'grid-rows-subgrid' => $asSubgrid,
      'grid-rows-[auto,auto,1fr,auto]' => !$asSubgrid,
      'w-full',

      // In some places (e.g. contact module in list mode), headers are added via $content, so we cannot style them in-place. Hence the weird selectors here.
      '[&_.c-card\_\_header]:bg-primary [&_.c-card\_\_header]:text-primary-contrasting' => $modifier == 'panel',
      '[&_.c-card\_\_header]:border-b-[length:calc(var(--base,8px)/2)] [&_.c-card\_\_header]:border-b-[color:var(--color-primary)]' => $modifier == 'accented',

      // Replacement for `.c-card .c-collection { border: none; }`
      '[&_.c-collection]:border-none',

      $classList,
    ],
  ],
  $attributeList ?? []
) }}>
  @if (!empty($date) || !empty($heading))
    {{-- CardHeader --}}
    <div {{ mx_attrs([
      'class' => [
        'row-start-2',
        'row-span-1',
        "space-y-2",
        "p-4",
        'border-l-[length:var(--base,8px)] border-l-[color:var(--color-primary)]' => $modifier == 'highlight',
        'border-b-[length:calc(var(--base,8px)/2)] border-b-[color:var(--color-primary)]' => $modifier == 'accented',
        'bg-primary text-primary-contrasting' => $modifier == 'panel',
      ],
    ]) }}>
      @if (!empty($heading))
        {{-- CardTitle --}}
        <h{!! $headingLevel !!} class="typography-h3">
          {{-- CardClickable --}}
          @include('mxui.clickable', [
            'link' => $link ?? null,
            'content' => $heading ?? null,
            'classList' => 'no-underline after:absolute after:inset-0 after:z-[1] interactive:hover:underline after:inert:hidden hover:visited:text-inherit'
          ])
        </h{!! $headingLevel !!}>
      @endif
      @if (!empty($date))
        <div class="flex items-center gap-1 text-sm text-gray-500">
          {{-- <Icon class="text-deep-blue" name="calendar" /> --}}
          {{ $date }}
        </div>
      @endif
    </div>
  @endif
  @if (!empty($image))
    {{-- CardMedia --}}
    <div
      {{ mx_attrs([
        'class' => [
          'w-full',
          'row-start-1',
          'row-span-1',
          'aspect-video bg-secondary first:last:mb-0',
          'overflow-hidden border-none',
        ],
      ]) }}
    >
      @include('mxui.image', (is_array($image) ? $image : ['src' => $image]) + ['classList' => 'group-hover:scale-105 transition-transform duration-500 text-transparent'])
    </div>
    <div
      aria-hidden="true"
      class="absolute left-0 top-0 m-4 w-auto"
    >
      @component('mxui.datebadge', [
        'date' => $date, 'classList' => [],
      ])
      @endcomponent
    </div>
  @endif
  @if(!empty($content))
    {{-- CardContent --}}
    <div {{ mx_attrs([
      'class' => [
        'row-start-3',
        'row-span-1',
        'px-4 pb-4' => $wrapContent,
        'border-l-[length:var(--base,8px)] border-l-[color:var(--color-primary)]' => $modifier == 'highlight',
      ],
    ]) }}>
      @if($proseWrap)
        <div class="prose">
      @endif
      @if (is_string($content))
        <p>{{ $content }}</p>
      @else
        {!! $content !!}
      @endif
      @if($proseWrap)
        </div>
      @endif
    </div>
  @endif
  @if(!empty($tags))
    {{-- CardFooter --}}
    <div {{ mx_attrs([
      'class' => [
        'row-start-4',
        'row-span-1',
        'px-4 py-4',
        'border-t border-t-border-divider',
        'border-l-[length:var(--base,8px)] border-l-[color:var(--color-primary)]' => $modifier == 'highlight',
      ],
    ]) }}>
      @tags([
        'compress' => 4, 
        'tags' => $tags, 
        'format' => false,
        'classList' => []
      ])
      @endtags
    </div>
  @endif
</div>
