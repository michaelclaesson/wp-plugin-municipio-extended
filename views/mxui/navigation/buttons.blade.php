<ul class="px-0 space-y-0 flex flex-wrap gap-4">
  @foreach ($items as $item)
    <li class="">
      @component('mxui.button', [
          'href' => $item['href'],
          'variant' => $item['buttonVariant'] ?? 'default',
      ])
        @if ($item['icon'])
          <span class="min-h-[1lh] flex-none flex items-center">
            @icon([
                'icon' => $item['icon']['name'] ?? $item['icon'],
                'classList' => ['block text-[1.5rem] leading-none flex-none']
            ])
            @endicon
          </span>
        @endif
        <span class="text-base flex-grow flex-shrink">
          {{ $item['title'] }}
        </span>
      @endcomponent
    </li>
  @endforeach
</ul>
