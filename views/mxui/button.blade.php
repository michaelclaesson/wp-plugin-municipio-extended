@include('mxui.clickable', [
    'classList' => [
        'rounded-[var(--c-button-border-radius,var(--radius-md,var(--base,8px)))]',
        'text-[.95rem] min-h-10 min-w-18 py-2 px-5',
        'inline-flex gap-1 items-center',
        'transition-colors',
        'no-underline border-[length:var(--border-width-button,var(--border-width-medium,2px))] border',
        'font-[number:var(--button-font-weight,var(--font-weight-button,500))]',
        [
            'primary' =>
                'bg-button-primary hover:bg-button-primary-hover text-button-primary-contrasting visited:text-button-primary-contrasting hover:text-button-primary-hover-contrasting hover:visited:text-primary-hover-contrasting border-transparent',
            'secondary' =>
                'bg-button-secondary hover:bg-button-secondary-hover text-button-secondary-contrasting visited:text-button-secondary-contrasting hover:text-button-secondary-hover-contrasting hover:visited:text-button-secondary-hover-contrasting border-transparent',
            'outlined' =>
                'bg-transparent hover:bg-[#00000018] text-button-primary visited:text-button-primary border-button-primary hover:text-button-primary-hover hover:visited:text-button-primary-hover hover:border-button-primary-hover',
        ][$variant ?? 'default'] ??
        'bg-button text-button-contrasting hover:bg-button-hover hover:text-button-hover-contrasting hover:visited:text-button-hover-contrasting border-transparent',
        $classList ?? null,
    ],
])
