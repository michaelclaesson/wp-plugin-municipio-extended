@if (!$hideTitle && !empty($postTitle))
  @typography([
      'id' => 'mod-text-' . $ID . '-label',
      'element' => 'h2',
      'variant' => 'h2',
      'classList' => ['module-title']
  ])
    {!! $postTitle !!}
  @endtypography
@endif

@if ($type == 'upload')
  <div class="embed embed__ratio--16-9">
    @video([
        'formats' => [['src' => $source, 'type' => 'mp4']],
        'subtitles' => $subtitles,
        'width' => 1080,
        'height' => 720,
        'attributeList' => [
            'poster' => $image !== false ? $image : '',
            'preload' => 'auto',
            'loop' => true,
            'muted' => true
        ],
        'classList' => ['ratio-16-9', 'embed__fit--cover']
    ])
    @endvideo
  </div>
@elseif ($type == 'embed')
  <div class="tailwind">
    @component('mxui.iframe', [
        'url' => $embed_link,
    ])
    @endcomponent
  </div>
@else
  @notice([
      'type' => 'info',
      'message' => [
          'text' => sprintf($lang->embedFailed, $embed_link)
      ],
      'icon' => [
          'name' => 'report',
          'size' => 'md',
          'color' => 'white'
      ],
      'classList' => ['u-margin--2']
  ])
  @endnotice
@endif
