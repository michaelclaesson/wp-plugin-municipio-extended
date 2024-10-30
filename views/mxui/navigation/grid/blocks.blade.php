<ul class="grid grid-cols-[repeat(auto-fill,minmax(min(100%,18rem),1fr))] gap-2 sm:gap-6">
  @foreach($items as $item)
    <li>
      <a href="{{ $item['href'] }}" class="block w-full h-full p-4 bg-complementary-light hover:bg-primary hover:text-primary-contrasting">
        <div class="space-y-2">
          <div class="typography-h3 leading-none underline">
            {{ $item['title'] }}
          </div>
          @if (!empty($item['description']))
            <div class="text-xs sm:text-base">
              {{ $item['description'] }}
            </div>
          @endif
        </div>
      </a>
    </li>
  @endforeach
</ul>
