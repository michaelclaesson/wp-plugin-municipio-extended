<ul class="grid grid-cols-[repeat(auto-fill,minmax(min(100%,16rem),1fr))] gap-[--grid-gap] space-y-0">
    @foreach($items as $item)
        @php
        $content = wp_trim_words($item['post']->post_content ?? '', 8, '...');
        @endphp
        <li style="background-color: {{ $cards_color }};">
            @card([
                'link' => $item['href'],
                'imageFirst' => true,
                'heading' => $item['title'],
                'content' => $content,
                'tags' => $item['termsUnlinked'] ?? '',
                'meta' => $item['readingTime'] ?? '',
                'date' => $item['postDateFormatted'] ?? '',
                'dateBadge' => $item['dateBadge'] ?? '',
                'classList' => ['u-height--100', 'cards-navigation'],
                'containerAware' => true,
                'hasAction' => true,
                'hasPlaceholder' => $item['hasPlaceholderImage'] ?? false,
                'image' => $item['image'] ?? '',
                'postId' => $item['id'] ?? '',
                'postType' => $item['postType'] ?? '',
                'attributeList' => $item['attributeList'] ?? [],
            ])
            @includeWhen(!empty($post->callToActionItems['floating']), 'partials.floating')
            @endcard
        </li>
    @endforeach
</ul>











