@php
  $link = is_string($link ?? null) ? ['href' => $link] : $link ?? null;
  $href = $href ?? null ?: $link['href'] ?? null ?: $link['url'] ?? null;
  $content = $content ?? null ?: $link['content'] ?? null ?: $link['text'] ?? null ?: $label ?? null ?: $slot ?? null;
@endphp

@if (!empty($href))
  <a
    {{ mx_attrs(
        [
            'href' => $href,
            'class' => [$classList ?? null],
            'data-mxui-clickable' => '',
            'data-mxui-interactive' => true,
        ],
        $attributes ?? null,
    ) }}>
    {{ $content }}
  </a>
@else
  <span
    {{ mx_attrs(
        [
            'class' => [$classList ?? null],
            'data-mxui-clickable' => '',
        ],
        $attributes ?? null,
    ) }}>{{ $content }}</span>
@endif
