<img {{ mx_attrs([
  'src' => $src ?? null,
  'srcset' => $srcset ?? null,
  'alt' => $alt ?? "",
  'class' => [
    'object-cover',
    'w-full',
    'h-full',
    $classList ?? [],
  ],
]) }}>
