@switch($index)
  @case('top-sidebar')
    <div class="o-container">
      <div class="o-grid">
        {{-- These empty spans are a trick to make grid gap act as padding --}}
        <span></span>
        {{ $slot }}
        <span></span>
      </div>
    </div>
  @break

  @default
    {{ $slot }}
@endswitch
