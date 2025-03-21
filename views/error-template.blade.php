@extends('templates.master')

@php
  $mx_post = mx_get_post();
@endphp

@section('content')
  <div class="tailwind">
    <div class="t-404">
      @typography(['element' => 'h1'])
        {{ $mx_post->title }}
      @endtypography

      @includeIf('partials.sidebar', ['id' => 'content-area-top', 'classes' => ['o-grid']])

      {!! apply_filters('the_content', $mx_post->content) !!}

      @includeIf('partials.sidebar', ['id' => 'content-area', 'classes' => ['o-grid']])

      <div class="flex gap-4">
        @component('mxui.button', [
            'type' => 'button',
            'onclick' => 'history.go(-1);',
            'variant' => 'primary',
            'classList' => ['text-lg px-4 gap-3'],
        ])
          <span class="min-h-[1lh] flex-none flex items-center text-[1.5em] -m-1">
            {{ mx_get_icon('arrow_back') }}
          </span>
          {{ __('Go back', 'municipio') }}
        @endcomponent

        @component('mxui.button', [
            'href' => '/',
            'variant' => 'secondary',
            'classList' => ['text-lg px-4 gap-3'],
        ])
          <span class="min-h-[1lh] flex-none flex items-center text-[1.5em] -m-1">
            {{ mx_get_icon('home') }}
          </span>
          {{ __('Go to homepage', 'municipio') }}
        @endcomponent
      </div>

      @includeIf('partials.sidebar', ['id' => 'content-area-bottom', 'classes' => ['o-grid']])

    </div>
  </div>
@stop
