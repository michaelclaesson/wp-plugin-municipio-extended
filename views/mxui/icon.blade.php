{{-- 
TODO: Handle these props:
- decorative
--}}

@php
  $icon = mx_get_icon($icon);
  $size = $size ?? null;
  $customColor = $customColor ?? null;
@endphp
@if ($icon)
  <span
    {{ mx_attrs(
        [
            'class' => [
                'inline-block bg-current size-[1em] [mask-size:100%] [mask-repeat:no-repeat] text-[color:var(--icon-color,inherit)]',
                // 'flex-none',
                'text-[.5rem]' => $size === 'xs',
                'text-[1rem]' => $size === 'sm',
                'text-[1.5rem]' => $size === 'md',
                'text-[2rem]' => $size === 'lg',
                'text-[2.5rem]' => $size === 'xl',
                'text-[3rem]' => $size === 'xxl',
                $classList ?? null,
            ],
            'style' => [
                '--icon-color' => $customColor,
                'mask-image' =>
                    "url('" .
                    $icon->withRenderParams([
                        'filled' => $filled,
                    ])->url .
                    "')",
            ],
        ],
        $attributeList ?? null,
    ) }}></span>
@endif
