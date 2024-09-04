<!-- mxui.datebadge -->
<div {{ mx_attrs([
  'class' => [
    'size-12' => $size === 'sm',
    'size-16' => $size !== 'sm',
    'flex flex-col items-center justify-center rounded bg-lighter text-black',
    $classList,
  ],
], $attributeList ) }}>
  <span {{ mx_attrs([
    'class' => [
      'typography-h2' => $size === 'sm',
      'typography-h1' => $size !== 'sm',
      'leading-none'
    ]
  ]) }}>
    {{ $date['day'] }}
  </span>
  <span {{ mx_attrs([
    'class' => [
      'typography-h5' => $size === 'sm',
      'typography-h4' => $size !== 'sm',
      'leading-none'
    ]
  ]) }}>
    {{ $date['monthShort'] }}
  </span>
</div>
