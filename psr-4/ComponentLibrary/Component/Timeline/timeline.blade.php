<!-- timeline.blade.php -->
<ul
  class="[--timeline-indent:12px] relative py-4 before:absolute before:left-[--timeline-indent] before:top-0 before:bottom-0 before:border-l-2 before:border-r-0 before:border-y-0 before:border-l-layer before:ml-[-1px] space-y-3 text-black">
  @php $latestEvent = ''; @endphp
  @foreach ($events as $event)
    @php
      if ((bool) (strtotime($event['date']) < strtotime('0:00'))) {
          $latestEvent = strtotime($event['date']) > $latestEvent ? strtotime($event['date']) : $latestEvent;
      }
    @endphp
  @endforeach
  @foreach ($events as $event)
    @php
      $hasPassed = (bool) (strtotime($event['date']) < strtotime('0:00'));
      $currentEvent = $latestEvent == strtotime($event['date']);
    @endphp
    <li class="grid grid-cols-[calc(var(--timeline-indent)*2)_1fr] gap-5 items-center">
      <div
        class="{{ clsx([
            'rounded-full size-8 border-[3px] border-layer flex place-items-center place-content-center text-[1.5rem] justify-self-center',
            'bg-layer text-dark' => $hasPassed && !$currentEvent,
            'bg-success-dark text-white' => $currentEvent,
            'bg-[--timeline-bg]' => !$currentEvent && !$hasPassed,
        ]) }}">
        @if ($currentEvent)
          @component('mxui.icon', ['icon' => 'play_arrow', 'filled' => true])
          @endcomponent
        @elseif ($hasPassed)
          @component('mxui.icon', ['icon' => 'check'])
          @endcomponent
        @endif
      </div>
      <div
        class="relative after:absolute after:border-r-layer after:left-0 after:top-1/2 after:border-y-[.75em] after:border-r-[1em] after:ml-[-1em] after:border-transparent after:mt-[-.75em] after:text-[length:10px] [--color-background-card:var(--color-layer)]">
        @component('mxui.card', [
            'link' => $event['link'],
            'meta' => $event['timelineDate'],
            'heading' => $event['title'],
            'content' => mx_safe_html($event['content']),
            'wrapContent' => true,
            'image' => isset($event['imageSrc'])
                ? [
                    'src' => $event['imageSrc'][0],
                    'alt' => $event['title'],
                    'backgroundColor' => 'none',
                ]
                : [],
        ])
        @endcomponent
      </div>
    </li>
  @endforeach
</ul>
