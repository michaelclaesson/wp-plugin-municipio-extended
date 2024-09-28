<!-- timeline.blade.php -->
<ul
  class="relative py-4 before:absolute before:left-4 before:top-0 before:bottom-0 before:border-l before:border-2 before:border-layer before:ml-[-1px] space-y-3 text-black">
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
    <li class="grid grid-cols-[max-content_1fr] gap-6 items-center">
      <div
        class="{{ clsx([
            'rounded-full size-8 border-[3px] border-layer flex place-items-center place-content-center text-[1.5rem]',
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
        class="relative after:absolute after:border-r-layer after:left-0 after:top-1/2 after:border-y-[1.5em] after:border-r-[2em] after:ml-[-2em] after:border-transparent after:mt-[-1.5em] after:text-[length:.5rem]">
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
