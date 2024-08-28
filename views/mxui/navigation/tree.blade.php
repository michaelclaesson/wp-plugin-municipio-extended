<ul class="divide-y space-y-0 *:border-border-divider my-8">
  @foreach($items as $item)
    <li class="p-0 flex gap-[--grid-gap] py-8 first:pt-0 last:pb-0">
      <div class="w-[180px] flex-none">
        @if ($image = ($item['image']['medium'] ?? null))
          <img src="{!! $image['src'] !!}" alt="{{ $image['alt'] }}" srcset="{!! $image['srcset'] !!}" class="block w-full">
        @endif
      </div>
      <div class="space-y-4">
        <div class="">
          <a href="{{ $item['href'] }}" class="text-h2 leading-none font-medium underline text-link hover:text-link-hover">
            {{ $item['title'] }}
          </a>
        </div>
        @if (!empty($item['description']))
          <p class="">
            {{ $item['description'] }}
          </p>
        @endif
        @if (!empty($item['children']))
          <ul class="flex flex-wrap gap-2 space-y-0">
            @foreach($item['children'] as $child)
              <li class="p-0">
                <a href="{{ $child['href'] }}" class="text-h3 block p-3 leading-none rounded bg-secondary text-secondary-contrast hover:bg-secondary-dark visited:hover:text-white transition-colors">
                  {{ $child['title'] }}
                </a>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </li>
  @endforeach
</ul>
