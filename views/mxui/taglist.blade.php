<!-- mxui.taglist -->
@if (!empty($tags))
  <ul
    {{ mx_attrs([
        'class' => ['flex flex-wrap gap-[.75em] space-y-0 no-prose', $class],
        'id' => $uid,
    ]) }}>
    @foreach ($tags as $tag)
      <li class="{{ clsx(['hidden' => $compress && $loop->index > $compress]) }}"
        style="--color-custom: {{ $tag['color'] }};">
        @component(
            'mxui.clickable',
            array_merge($tag, [
                'classList' => [
                    'block rounded font-medium py-[.5em] px-[.75em]',
                    $backdrop ?? false
                        ? 'bg-[#0009] interactive:hover:bg-[#000c] backdrop-blur'
                        : 'bg-lighter interactive:hover:bg-light',
                    $tag['color'] ? 'border-custom border-l-[.75em]' : null,
                ],
            ]))
        @endcomponent
      </li>
    @endforeach
    @if ($compress && $compress < count($tags))
      <span class="" data-js-compressed="{{ $compress }}" data-js-compressed-class="hidden">
        ...({{ count($tags) - $compress }})
      </span>
    @endif
  </ul>
@endif
