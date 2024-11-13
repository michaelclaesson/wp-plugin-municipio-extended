<div class="text-black p-4">
  <h3 class="typography-h3 u-margin__top--2 u-margin__bottom--1">
    {{ $contact['first_name'] }}
    {{ isset($contact['last_name']) && !empty($contact['last_name']) ? $contact['last_name'] : '' }}
  </h3>

  <ul>
    @if (
        (isset($contact['work_title']) && !empty($contact['work_title'])) ||
            (isset($contact['administration_unit']) && !empty($contact['administration_unit'])))
      <li>
        {{ isset($contact['work_title']) && !empty($contact['work_title']) ? $contact['work_title'] : '' }}</li>
      <li itemprop="department">
        {{ isset($contact['administration_unit']) && !empty($contact['administration_unit']) ? $contact['administration_unit'] : '' }}
      </li>
      </li>
    @endif

    @if (isset($contact['phone']) && !empty($contact['phone']))
      @foreach ($contact['phone'] as $phone)
        <li class="flex items-center gap-2"> {{ mx_get_icon('call') }} <a itemprop="telephone" class=""
            href="tel:{{ $phone['number'] }}">{{ $phone['number'] }}</a></li>
      @endforeach
    @endif

    @if (isset($contact['email']) && !empty($contact['email']))
      <li class="flex gap-2 items-center w-full"> {{ mx_get_icon('mail') }}<a itemprop="email" translate="no" class="flex-shrink min-w-0"
          href="mailto:{{ $contact['email'] }}"><span class="block truncate">{{ $contact['email'] }}</span></a></li>
    @endif

    {{-- @if (!empty($module->post_content))
            <li class="small description">{!! apply_filters('the_content', apply_filters('Modularity/Display/SanitizeContent', $this->post_content)) !!}</li>
            @endif --}}
  </ul>
</div>
