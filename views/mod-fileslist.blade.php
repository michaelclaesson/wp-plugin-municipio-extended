
<!-- mod-fileslist.blade.php -->
<div class="tailwind content">
@card([
    'heading'       => false,
    'classList'     => [$classes],
    'attributeList' => [
        'js-filter-container'   => $uID,
    ],
    'context' => 'module.files.list'
])
    @if (!$hideTitle && !empty($postTitle))
        <div class="p-4 break-words">
            @typography([
                'id'      => 'mod-fileslist-' . $ID .'-label',
                'element' => 'h2',
                'variant' => 'h4'
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif

    @if($isFilterable)

    <div class="p-4 break-words">
        @field([
            'type'          => 'text',
            'attributeList' => [
                'type'              => 'search',
                'name'              => 'search',
                'js-filter-input'   => $uID
            ],
            'label'         => __('Search', 'municipio')
        ])
        @endfield
    </div>
    @endif

    
    @collection([
        'sharpTop' => true
    ])

        @foreach($rows as $row)
            @php
                $filesize = str_replace('.', ',', $row['filesize']); 
            @endphp

            @collection__item([
                'link'          => $row['href'],
                'icon'          => 'download',
                'displayIcon'          => 'true',
                'attributeList' => [
                    'js-filter-item' => '',
                    'style'          => 'color: var(--color-link);',
                ]
            ])

               <div class="underline">
                    @typography([
                        'element'       => 'span',
                        'variant'       => 'bold',
                        'attributeList' => [
                        ' js-filter-data' => ''
                        ],
                    ])
                        {{ $row['title'] }} ({{ $row['type'] }}, {{ $filesize }})
                    @endtypography
                </div>            

                @if(!empty($row['description']))
                    @typography([
                        'element'       => 'span',
                        'variant'       => 'meta',
                        'attributeList' => [
                        ' js-filter-data' => '',
                        'style'          => 'color: var(--color-base);',
                        ]
                    ])
                        {{ $row['description'] }}
                    @endtypography
                @endif

            @endcollection__item

        @endforeach
    @endcollection
   
@endcard


</div>