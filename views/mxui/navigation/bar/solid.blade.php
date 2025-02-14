<div class="bg-primary contentless:hidden">
  <div class="o-container">
   <ul class="grid {{ $items ? 'grid-cols-[repeat(auto-fit,minmax(min(100%,7rem),1fr))]' : 'grid-cols-[repeat(auto-fit,minmax(min(100%,18rem),1fr))]' }} space-y-0 mx-auto justify-center">
      @foreach($items as $item)
        <li class="contents">
          <a href="{{ $item['href'] }}" class="text-primary-contrasting hover:text-primary-contrasting grid grid-rows-subgrid row-span-2 gap-2 items-center visited:hover:text-primary-contrasting text-center py-5 px-2 hover:bg-primary-dark transition-colors">
            <span class="text-[54px] leading-none">
              {{ mx_get_icon($item['icon'] ?: 'arrow_forward', [
                  'classList' => ['block'],
              ]) }}
            </span>
            <div class="text-sm font-medium underline">
              {{ $item['title'] }}
            </div>
          </a>
        </li>
      @endforeach
        <template>
          <li class="">
            <a href="#" class="text-primary-contrasting hover:text-primary-contrasting grid group grid-cols-[max-content_1fr] gap-2 visited:hover:text-primary-contrasting py-5 px-2 hover:bg-primary-dark transition-colors gap-x-2" slot="link">
              <span class="text-[13px] bg-white text-primary rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-1">
                {{ mx_get_icon('arrow_forward_ios') }}
              </span>
              <div class="text-md text-left underline min-w-0" slot="title"></div>
            </a>
          </li>
        </template>
    </ul>
  </div>
</div>
