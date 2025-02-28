<div class="mt-4 first:mt-0">
  @if ($isExpired)
    @component('mxui.button', [
        'type' => 'button',
        'disabled' => true,
    ])
      <?php _e('The application period has ended', 'job-listings'); ?>
    @endcomponent
  @else
    @component('mxui.button', [
        'href' => $applyLink,
    ])
      <?php _e('Apply here', 'job-listings'); ?>
      ({{ $daysLeft }} <?php _e('days left', 'job-listings'); ?>)
    @endcomponent
  @endif
</div>
