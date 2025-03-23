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
                'bg-primary hover:bg-primary-dark text-primary-contrasting visited:text-primary-contrasting hover:text-primary-contrasting hover:visited:text-primary-contrasting border-transparent',
            'secondary' =>
                'bg-secondary hover:bg-primary text-secondary-contrasting visited:text-secondary-contrasting hover:text-primary-contrasting hover:visited:text-primary-contrasting border-transparent',
            'outlined' =>
                'bg-transparent hover:bg-[#00000018] text-primary visited:text-primary border-primary hover:text-primary-dark hover:visited:text-primary-dark hover:border-primary-dark',
        ][$variant ?? 'default'] ??
        'bg-layer hover:bg-primary hover:text-primary-contrasting hover:visited:text-primary-contrasting border-transparent',
        $classList ?? null,
    ],
])
