@php
  $link = is_string($link ?? null) ? ['href' => $link] : ($link ?? null);
  $href = ($href ?? null) ?: ($link['href'] ?? null) ?: ($link['url'] ?? null);
  $content = ($content ?? null) ?: ($link['content'] ?? null) ?: ($link['text'] ?? null);
@endphp

@if (!empty($href))
  <a {{ mx_attrs([
    'href' => $href,
    'class' => [
      $classList,
    ],
  ]) }}>
    {{ $content }}
  </a>
@else
  <span>{{ $content }}</span>
@endif
