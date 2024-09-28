<div class="tailwind content">
  <div
    class="bg-[--timeline-bg] [--timeline-bg:--color-primary] rounded-[var(--radius-lg,calc(var(--base,8px)*1.5))] p-4 layer-white [&_.c-card]:bg-layer text-primary-contrasting">
    @if (!$hideTitle && !empty($post_title))
      @typography([
          'element' => 'h2',
          'classList' => ['module-title']
      ])
        {!! apply_filters('the_title', $post_title) !!}
      @endtypography
    @endif
    @timeline(['events' => $events])
    @endtimeline
  </div>
</div>
