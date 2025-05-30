@php
  $image = mx_get_image($image ?? null, $size ?? null);
  if (!$image) {
      throw new \Exception('A valid WpImage object must be passed to mxui.image component');
  }
@endphp

<!-- mxui.image -->
<img
  {{ mx_attrs([
      'src' => $image['src'] ?? null,
      'srcset' => $image['srcset'] ?? null,
      'sizes' => $sizes ?? null,
      'alt' => $alt ?? ($image['alt'] ?? ''),
      'class' => ['object-cover', 'w-full', 'h-full', 'mt-0', $classList ?? []],
      'data-mxui-type' => 'image',
  ]) }}>
