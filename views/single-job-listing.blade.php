@extends('templates.master')

@section('before-layout')
@stop

@section('helper-navigation')
  @includeWhen($helperNavBeforeContent, 'partials.navigation.helper')
@stop

@section('hero-top-sidebar')
  @includeIf('partials.hero')
  @includeIf('partials.sidebar', ['id' => 'under-hero', 'classes' => ['o-grid']])
  @includeIf('partials.sidebar', ['id' => 'top-sidebar'])
@stop

@section('above')
  @include('partials.sidebar', ['id' => 'above-columns-sidebar', 'classes' => ['o-grid']])
@stop

@section('sidebar-left')
  @if ($showSidebars)

    @include('partials.sidebar', [
        'id' => 'left-sidebar',
        'classes' => ['o-grid'],
    ])

    @if ($customizer->secondaryNavigationPosition == 'left')
      @if ($secondaryMenuItems)
        <div class="u-margin__bottom--4 u-display--none@xs u-display--none@sm u-display--none@md">
          @paper()
            @includeIf('partials.navigation.sidebar', ['menuItems' => $secondaryMenuItems])
          @endpaper
        </div>
      @endif
    @endif

    @include('partials.sidebar', [
        'id' => 'left-sidebar-bottom',
        'classes' => ['o-grid'],
    ])

  @endif
@stop

@section('content')

  {!! $hook->loopStart !!}

  @includeIf('partials.sidebar', ['id' => 'content-area-top', 'classes' => ['o-grid']])

@section('loop')
  @includeIf('partials.loop')

  @if ($isExpired)
    <div class="notice warning">
      <div class="notice__wrapper">
        <div class="notice__text">
          <div class="notice__description">
            <p><?php _e('The application period for this reqruitment has ended.', 'job-listings'); ?></p>
          </div>
        </div>

      </div>
    </div>
  @endif
@show

@includeIf('partials.sidebar', ['id' => 'content-area', 'classes' => ['o-grid']])

@includeWhen($displayQuicklinksAfterContent, 'partials.navigation.fixed')

@includeWhen($displaySecondaryQuery, 'partials.secondary', [
    'posts' => $secondaryQuery->posts ?? [],
    'postType' => $secondaryPostType ?? null,
])

{!! $hook->loopEnd !!}

@stop

@section('sidebar-right')
<div class="tailwind contents">
  @component('mxui.card', [
      'heading' => 'Information',
      'wrapContent' => true,
      'classList' => ['mt-4 first:mt-0'],
  ])
    <dl class="space-y-4">

      @if ($endDate && !$isExpired)
        <div>
          <dt class="typography-h4"><?php _e('Deadline for applications:', 'eslov'); ?></dt>
          <dd>{{ $endDate }}</dd>
        </div>
      @endif

      @if ($employmentType)
        <div>
          <dt class="typography-h4"><?php _e('Employment type:', 'job-listings'); ?></dt>
          <dd>{{ $employmentType }}</dd>
        </div>
      @endif

      @if ($numberOfPositions)
        <div>
          <dt class="typography-h4"><?php _e('Number of positions:', 'job-listings'); ?></dt>
          <dd>{{ $numberOfPositions }}</dd>
        </div>
      @endif

      @if ($employmentGrade)
        <div>
          <dt class="typography-h4"><?php _e('Extent:', 'job-listings'); ?></dt>
          <dd>{{ $employmentGrade }}</dd>
        </div>
      @endif

      @if ($location)
        <div>
          <dt class="typography-h4"><?php _e('Location:', 'job-listings'); ?></dt>
          <dd>{{ $location }}</dd>
        </div>
      @endif

      @if ($department)
        <div>
          <dt class="typography-h4"><?php _e('Company:', 'job-listings'); ?></dt>
          <dd>{{ $department }}</dd>
        </div>
      @endif

    </dl>
  @endcomponent

  @if ($contacts)
    @component('mxui.card', [
        'heading' => 'Kontakta oss',
        'wrapContent' => true,
        'classList' => ['mt-4 first:mt-0'],
    ])
      <ul class="space-y-4">
        @foreach ($contacts as $contact)
          <li>
            <h4 class="typography-h4">{{ $contact->name }}</h4>
            <p>{{ $contact->position }}</p>
            @foreach ($contact->phone as $phone)
              <p>{{ $phone }}</p>
            @endforeach
            @if ($contact->email)
              <p>
                <a class="typography-link" href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
              </p>
            @endif
          </li>
        @endforeach
      </ul>
    @endcomponent
  @endif

  <div class="mt-4 first:mt-0">
    @if ($isExpired)
      @component('mxui.button', [
          'type' => 'button',
          'disabled' => true,
      ])
        <?php _e('The application period has ended', 'job-listings'); ?>
      @endcomponent
    @else
      @component('mxui.button', [
          'href' => $applyLink,
      ])
        <?php _e('Apply here', 'job-listings'); ?>
        ({{ $daysLeft }} <?php _e('days left', 'job-listings'); ?>)
      @endcomponent
    @endif
  </div>
</div>

{{-- @if ($customizer->secondaryNavigationPosition == 'right')
    @if ($secondaryMenuItems)
      <div class="u-margin__bottom--4 u-display--none@xs u-display--none@sm u-display--none@md">
        @paper()
          @includeIf('partials.navigation.sidebar', ['menuItems' => $secondaryMenuItems])
        @endpaper
      </div>
    @endif
  @endif --}}

@includeIf('partials.sidebar', ['id' => 'right-sidebar', 'classes' => ['o-grid']])
@stop

@section('below')
@includeIf('partials.sidebar', ['id' => 'content-area-bottom', 'classes' => ['o-grid']])

<!-- Comments -->
@section('article.comments.before')@show
@includeIf('partials.comments')
@section('article.comments.after')@show

@stop
