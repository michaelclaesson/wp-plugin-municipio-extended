<!-- nav-header-primary-compact.blade.php -->
@php
  $allowStyle ??= true;
  $class ??= null;
  $id ??= null;
  $items ??= [];
@endphp

@if (!empty($items))
  <ul {{ mx_attrs(['class' => [$class, 'text-base', 'font-semibold'], $attributeList ?? []]) }}>
    @foreach ($items as $item)
      <li
        {{ mx_attrs([
          'id' => "$id-{$item['id']}-{$loop->index}__item",
          'class' => [
            'bg-secondary-dark underline decoration-white' => $item['active'] || $item['ancestor'],
            'py-3 px-10 first:pl-0 last:pr-0 my-auto',
            'hover:bg-secondary-dark hover:underline hover:decoration-white',
          ],
          $item['attributeList'] ?? [],
        ]) }}
      >
        <div class="{{$baseClass}}__item-wrapper">
          {{-- Nav item --}}
          @if($allowStyle)
            @includeIf('Nav.style.' . ($item['style'] ?? 'default'))
          @else
            @includeIf('Nav.style.default')
          @endif

          {{-- Children list --}}
          @includeWhen($item['hasToggle'] ?? false, 'Nav.toggle')
        </div>

        {{-- Children list --}}
        @includeWhen($item['hasChildren'] ?? false, 'Nav.children')
      </li>
    @endforeach
  </ul>
@endif




