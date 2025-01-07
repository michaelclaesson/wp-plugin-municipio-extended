
  <div class="tailwind">
      @foreach ($tags as $tag)
        <li
        class="inline-flex items-center border border-primary text-link p-3 mb-3 mr-2">
            {{ $tag }}
        </li>
 @endforeach
  </div>

