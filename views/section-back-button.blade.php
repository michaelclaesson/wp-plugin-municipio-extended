@if($post['sectionPageAncestor'])
<div class="tailwind">
    <a href="{{ $post['sectionPageAncestor']['href'] }}" class="inline-flex items-center border border-primary hover:border-black text-link hover:text-black p-3 hover:bg-[#0001] transition-colors  mb-4">
        <span class="w-[24px] text-xl leading-none mr-2">
            @icon([
                    'icon' => $item['icon'] ?: 'arrow_back',
                    'classList' => []
                ])
            @endicon
        </span>
        <span class="font-bold no-underline">
        Till startsidan för  {{ $post['sectionPageAncestor']['title'] }}
        </span>
    </a>
</div>
@endif 