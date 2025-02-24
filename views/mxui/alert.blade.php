@php
  $title ??= null;
  $content ??= null;
  $type ??= null;
  $icon ??= null;
@endphp

<div
  {{ mx_attrs([
      'class' => [
          'bg-info text-info-contrasting' => $type === 'info',
          'bg-success text-success-contrasting' => $type === 'success',
          'bg-warning text-warning-contrasting' => $type === 'warning',
          'bg-danger text-danger-contrasting' => $type === 'danger',
          $classList ?? null,
      ],
      'role' => 'alert',
      'aria-label' => $title,
  ]) }}>
  <div class="py-4 flex gap-3 o-container">
    @icon([
        'icon' => $icon,
        'classList' => ['block', 'text-[1.5rem]', 'leading-none', 'flex-none', 'opacity-70'],
        'aria-hidden' => 'true'
    ])
    @endicon
    <div>
      @typography([
          'variant' => 'h4',
          'element' => 'h4'
      ])
        {{ $title }}
      @endtypography
      @typography([
          'element' => 'span'
      ])
        {!! $content !!}
      @endtypography
    </div>
  </div>
</div>
