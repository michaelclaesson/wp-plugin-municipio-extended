<!-- mxui.taglist -->
@if (!empty($tags))
  <ul {{ mx_attrs([
      'class' => ['flex flex-wrap gap-3 space-y-0', $class],
      'id' => $uid,
  ]) }}>
    @foreach ($tags as $tag)
      <li class="{{ clsx(['hidden' => $compress && $loop->index > $compress]) }}"
        style="--color-custom: {{ $tag['color'] }};">
        @component(
            'mxui.clickable',
            array_merge($tag, [
                'classList' => [
                    'block bg-lighter interactive:hover:bg-light rounded font-medium py-[.5em] px-[.75em]',
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
