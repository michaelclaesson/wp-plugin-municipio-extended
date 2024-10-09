@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="tailwind">
  <div class="{{ clsx([
      "o-grid",
      "o-grid--stretch" => !empty($stretch),
      "o-grid--no-gutter" => !empty($noGutter),
      "grid grid-cols-[repeat(auto-fill,minmax(min(100%,18rem),1fr))] gap-[--grid-gap]",
    ]) }}">
    @if($posts)
      <div class="{{ clsx([
        "o-grid",
        "o-grid--stretch" => !empty($stretch),
        "o-grid--no-gutter" => !empty($noGutter),
        // "@container",
        "min-w-0 grid grid-cols-[repeat(auto-fill,minmax(min(100%,18rem),1fr))] col-start-1 col-end-[-2] gap-[--grid-gap]",
      ]) }}">
        @foreach ($posts as $post)
          <div class="{{ clsx([
            $posts_columns,
            'hidden' => $loop->index >= 2,
            'col-span-1'
          ]) }}">
            @include('partials.post.card')
          </div>
        @endforeach
      </div>
      <div>
        @card([
          'heading' => false,
          'context' => 'module.posts.list',
          "classList" => explode(' ', clsx([
          ]))
        ])
          @collection([
            'sharpTop' => false,
            'bordered' => false
          ])
            @foreach ($posts as $post)
              @collection__item([
                'displayIcon' => true,
                'icon' => 'arrow_forward',
                'link' => $post->permalink,
                'classList' => explode(' ', clsx([
                  'hidden' => $loop->index < 2,
                ]))
              ])
                @typography([
                  'element' => 'h2',
                  'variant' => 'h4'
                ])
                  {{ $post->postTitle }}
                @endtypography
              @endcollection__item
            @endforeach
          @endcollection
        @endcard
        @include('partials.more')
      </div>
    @endif
  </div>
</div>
