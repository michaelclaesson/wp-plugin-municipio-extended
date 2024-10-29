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
  $expandLinkCover ??= false;
  $buttons ??= null;
@endphp
<!-- mxui.card -->

<div
  {{ mx_attrs(
      [
          'class' => [
              'grid content-start rounded-[var(--c-card-border-radius,var(--radius-lg,calc(var(--base,8px)*1.5)))] bg-[--color-background-card] hover:bg-[var(--color-background-card-hover,var(--color-background-card))] hover:text-[var(--color-text-card-hover,var(--color-black))] overflow-hidden grid-cols-[100%]',
              'relative group' => !$expandLinkCover,
              'grid-rows-subgrid' => $asSubgrid,
              'grid-rows-[auto,auto,1fr,auto]' => !$asSubgrid,
              'w-full',
  
              // In some places (e.g. contact module in list mode), headers are added via $content, so we cannot style them in-place. Hence the weird selectors here.
              '[&_.c-card\_\_header]:bg-primary [&_.c-card\_\_header]:text-primary-contrasting' => $modifier == 'panel',
              '[&_.c-card\_\_header]:border-b-[length:calc(var(--base,8px)/2)] [&_.c-card\_\_header]:border-b-[color:var(--color-primary)]' =>
                  $modifier == 'accented',
  
              // Replacement for `.c-card .c-collection { border: none; }`
              '[&_.c-collection]:border-none',
              '@container',
  
              $classList,
          ],
      ],
      $attributeList ?? [],
  ) }}>
  @if (!empty($date) || !empty($heading) || !empty($meta))
    {{-- CardHeader --}}
    <div
      {{ mx_attrs([
          'class' => [
              'row-start-2',
              'row-span-1',
              'space-y-2',
              'px-[var(--card-px,1rem)] py-[var(--card-py,1rem)]',
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
              'classList' =>
                  'no-underline interactive:underline after:absolute after:inset-0 after:z-[1] after:inert:hidden hover:visited:text-inherit transition-none',
              'attributes' => ['data-mxui-card-link' => ''],
          ])
          </h{!! $headingLevel !!}>
      @endif
      @if (!empty($date))
        <div class="flex items-center gap-1 text-sm text-gray-500">
          {{-- <Icon class="text-deep-blue" name="calendar" /> --}}
          {{ $date }}
        </div>
      @endif
      @if (!empty($meta))
        <div class="flex items-center gap-1 text-sm text-gray-500">
          {{-- <Icon class="text-deep-blue" name="calendar" /> --}}
          {!! $meta !!}
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
      ]) }}>
      @component('mxui.image', [
          'image' => $image,
          'size' => 'medium',
          'classList' =>
              'group-hover:group-has-[[data-mxui-card-link][data-mxui-interactive]]:scale-105 transition-transform duration-500 text-transparent',
      ])
      @endcomponent
    </div>
    @if ($dateBadge)
      <div aria-hidden="true" class="absolute left-0 top-0 m-4 w-auto">
        @component('mxui.datebadge', [
            'date' => $date,
            'classList' => [],
        ])
        @endcomponent
      </div>
    @endif
  @endif
  @if (!empty($content))
    {{-- CardContent --}}
    <div
      {{ mx_attrs([
          'class' => [
              'row-start-3',
              'row-span-1',
              'px-[var(--card-px,1rem)] pb-[var(--card-py,1rem)]' => $wrapContent,
              'border-l-[length:var(--base,8px)] border-l-[color:var(--color-primary)]' => $modifier == 'highlight',
          ],
      ]) }}>
      @if ($proseWrap)
        <div class="prose">
      @endif
      @if (is_string($content))
        <p>{{ $content }}</p>
      @else
        {!! $content !!}
      @endif
      @if ($proseWrap)
    </div>
  @endif
  @if ($buttons)
    <ul class="px-0 space-y-0 flex flex-wrap gap-4 mt-4">
      @foreach ($buttons as $button)
        <li class="">
          @component('mxui.button', [
              'href' => $button['href'],
              'variant' => $button['color'] ?? ($button['buttonVariant'] ?? 'default'),
              'classList' => 'h-14',
          ])
            @if ($button['icon'])
              <span class="min-h-[1lh] flex-none flex items-center">
                @icon([
                    'icon' => $button['icon']['name'] ?? $button['icon'],
                    'classList' => ['block text-[1.5rem] leading-none flex-none']
                ])
                @endicon
              </span>
            @endif
            <span class="text-base flex-grow flex-shrink">
              {{ $button['text'] ?? $button['title'] }}
            </span>
          @endcomponent
        </li>
      @endforeach
    </ul>
  @endif
</div>
@endif
@if (!empty($tags))
  {{-- CardFooter --}}
  <div
    {{ mx_attrs([
        'class' => [
            'row-start-4',
            'row-span-1',
            'px-[var(--card-px,1rem)] py-[var(--card-py,1rem)]',
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
