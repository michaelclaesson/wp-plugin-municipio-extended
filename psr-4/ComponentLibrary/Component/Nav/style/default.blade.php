@if($item['icon'] || $item['label'])
  @link([
    'id' => $id . '-' . $item['id'] . '-' . $loop->index . '__label',
    'classList' => [$baseClass . '__link'],
    'href' => $item['href'],
    'xfn' => $item['xfn'] ?? false,
    'target' => $item['target'] ? $item['target'] : '_top',
  ])
    @icon([
      'icon' => $item['icon']['icon'] ?? null,
      'size' => $item['icon']['size'] ?? null,
      'classList' => $item['icon']['classList'] ?? [],
    ])
    @endicon
    @if ($item['label'])
    <span
      class="{{$baseClass}}__text"
      style="{{isset($item['color']) ? 'color:' . $item['color'] . ';' : ''}}">
      {!! $item['label'] !!}
    </span>
    @endif
  @endlink
@else
  <!-- Hidden link: Both label and icon is missing -->
@endif
