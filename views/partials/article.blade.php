<article class="c-article c-article--readable-width s-article u-clearfix {{ get_theme_mod("mx_article_alignment", 'left') === 'center' ? 's-article--centered' : '' }}" id="article" {!! !empty($postLanguage) ? 'lang="' . $postLanguage . '"' : '' !!}>

    <!-- Title -->
    @section('article.title.before')@show
    @if ($postTitleFiltered || isset($callToActionItems['floating']))
        @group([
            'justifyContent' => 'space-between'
        ])
            @if ($postTitleFiltered)
                @typography([
                    'element' => 'h1',
                    'variant' => 'h1',
                    'id' => 'page-title',
                ])
                    {!! $postTitleFiltered !!}
                @endtypography
            @endif
            @if (!empty($callToActionItems['floating']))
                @icon($callToActionItems['floating'])
                @endicon
            @endif
        @endgroup
    @endif
    @section('article.title.after')@show

    <!-- Blog style author signature -->
    @includeWhen(
        (!$postTypeDetails->hierarchical || $isBlogStyle),
        'partials.signature',
        array_merge(
            (array) $signature,
            (array) ['classList' => []]
        )
    )

    <!-- Featured image -->
    @if ($displayFeaturedImage && $featuredImage['src'])
        @section('article.featuredimage.before')@show
        @if (!empty($featuredImage['src']))
            @image([
                'src' => $featuredImage['src'],
                'alt' => $featuredImage['alt'] ?? '',
                'classList' => ['c-article__feature-image', 'u-box-shadow--1']
            ])
            @endimage
        @endif
        @section('article.featuredimage.after')@show
    @endif
    

    <!-- Content -->
    @section('article.content.before')@show
    {!! $hook-> articleContentBefore !!}
    @if ($postAgeNotice)
        @notice([
            'message' => [
                'text' => $postAgeNotice
            ],
            'type' => 'info',
            'icon' => [
                'name' => 'history',
                'size' => 'md',
                'color' => 'white'
            ]
        ])
        @endnotice
    @endif
    {!! $postContentFiltered !!}
    @section('article.content.after')@show

    @if (get_theme_mod('mx_content_area_placement', 'outside') === 'inside')
        @includeIf('partials.sidebar', ['id' => 'content-area', 'classes' => ['o-grid']])
    @endif

    <!-- Terms -->
    @section('article.terms.before')@show

    @section('article.terms.after')@show

    {!! $hook-> articleContentAfter !!}

    

    <!-- Blog style author signature -->
    @section('content.below')
        @includeWhen(
            ($postTypeDetails->hierarchical && !$isBlogStyle),
            'partials.signature',
            array_merge(
                (array) $signature,
                (array) ['classList' => []]
            )
        )

        @if ($postType === 'page')
            @includeIf('partials.sidebar', ['id' => 'related-pages-area', 'classes' => ['o-grid']])
        @endif
    @endsection

    

</article>