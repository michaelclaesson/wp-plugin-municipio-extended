<ul class="grid grid-cols-[repeat(auto-fill,minmax(min(100%,18rem),1fr))] gap-2 sm:gap-6">
  @foreach($items as $item)
    <li class="p-3.5 pr-5 bg-secondary hover:bg-primary-dark text-base group">
      <a href="{{ $item['href'] }}" class="group">
        <div class="space-y-2">
          <div class="text-sm sm:text-h3 font-semibold leading-none underline group-hover:text-primary-contrasting">
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
