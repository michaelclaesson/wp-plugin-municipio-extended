<div class="tailwind content">
  <div class="">
    @if (!$hideTitle && !empty($post_title))
      @typography([
          'element' => 'h2',
          'classList' => ['module-title']
      ])
        {!! apply_filters('the_title', $post_title) !!}
      @endtypography
    @endif

    <div class="o-grid">
      @foreach ($contacts as $contact)
        {{-- @dump($contact) --}}
        <div class="{{ $columns }}">
          <div class="flex flex-col h-full">
            @component('mxui.image', [
                'image' => isset($contact['image'])
                    ? [
                        'src' => $contact['image']['url'],
                        'alt' => $contact['image']['alt'],
                    ]
                    : [],
                'size' => 'medium',
            ])
            @endcomponent
            {!! mx_safe_html(
                mx_render_view('mxui.contacts.contact-details', [
                    'contact' => $contact,
                ]),
            ) !!}

            {{-- @component('mxui.card', [
    'content' => mx_safe_html(
        mx_render_view('mxui.contacts.contact-details', [
            'contact' => $contact,
        ]),
    ),
    'wrapContent' => true,
    'image' => isset($contact['image'])
        ? [
            'src' => $contact['image']['url'],
            'alt' => $contact['image']['alt'],
        ]
        : [],
])
          @endcomponent --}}
          </div>
        </div>
      @endforeach
    </div>
    {{-- @dump($contacts) --}}
    {{-- @timeline(['events' => $events]) --}}
    {{-- @endtimeline --}}
  </div>
</div>
