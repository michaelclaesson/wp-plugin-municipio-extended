<!-- nav-header-primary-compact.blade.php -->
@php
  $allowStyle ??= true;
  $class ??= null;
  $id ??= null;
  $items ??= [];
@endphp

@if (!empty($items))
  <ul {{ mx_attrs(['class' => ['text-base font-semibold -mx-6 w-auto', $class], $attributeList ?? []]) }}>
    @foreach ($items as $item)
      <li
        {{ mx_attrs([
            'id' => "$id-{$item['id']}-{$loop->index}__item",
            'class' => [],
            $item['attributeList'] ?? [],
        ]) }}>
        @component('mxui.clickable', [
            'id' => $id . '-' . $item['id'] . '-' . $loop->index . '__label',
            'href' => $item['href'],
            'classList' => [
                'block text-secondary-contrasting visited:text-secondary-contrasting',
                'bg-secondary-dark underline' => $item['active'] || $item['ancestor'],
                'py-3 px-6 flex items-center text-balance',
                'hover:bg-secondary-dark',
            ],
        ])
          {!! $item['label'] !!}
        @endcomponent
      </li>
    @endforeach
  </ul>
@endif
