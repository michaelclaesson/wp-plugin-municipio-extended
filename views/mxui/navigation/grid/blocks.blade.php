<ul class="grid grid-cols-[repeat(auto-fill,minmax(min(100%,18rem),1fr))] gap-2 sm:gap-6">
  @foreach($items as $item)
    <li class="relative p-4 bg-complementary-light hover:bg-primary text-base group">
      <a href="{{ $item['href'] }}" class="group after:absolute after:inset-0 after:z-[1] after:inert:hidden">
        <div class="space-y-2">
          <div class="typography-h3 leading-none underline group-hover:text-primary-contrasting">
            {{ $item['title'] }}
          </div>
          @if (!empty($item['description']))
            <div class="text-xs sm:text-base group-hover:text-primary-contrasting">
              {{ $item['description'] }}
            </div>
          @endif
        </div>
      </a>
    </li>
  @endforeach
</ul>
