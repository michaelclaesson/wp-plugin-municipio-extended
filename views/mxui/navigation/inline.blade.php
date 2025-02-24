<!-- mxui.navigation.inline -->
<div class="bg-primary contentless:hidden">
  <div class="o-container">
      <ul class="px-0 space-y-0 flex flex-wrap gap-4 justify-start md:justify-center">
      @foreach($items as $item)
        <li class="contents">
          <a href="{{ $item['href'] }}" class="text-primary-contrasting hover:text-primary-contrasting grid group grid-cols-[max-content_1fr] gap-2 visited:hover:text-primary-contrasting py-5 px-2 hover:bg-primary-dark transition-colors gap-x-2">
            <span class="text-[13px] bg-white text-primary rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-1">
              {{ mx_get_icon($item['icon'] ?: 'arrow_forward') }}
            </span>
            <div class="ttext-md text-left underline min-w-0">
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
              <div class="text-md underline" slot="title"></div>
            </a>
          </li>
        </template>
    </ul>
  </div>
</div>
