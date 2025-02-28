@include('mxui.clickable', [
    'classList' => [
        'rounded-[var(--c-button-border-radius,var(--radius-md,var(--base,8px)))]',
        'text-[.95rem] h-12 min-w-18 px-5',
        'flex gap-1 items-center',
        'transition-colors',
        'font-[number:var(--button-font-weight,var(--font-weight-button,500))]',
        [
            'primary' => 'bg-primary hover:bg-primary-dark text-primary-contrasting',
            'secondary' =>
                'bg-secondary hover:bg-primary text-secondary-contrasting hover:text-primary-contrasting',
        ][$variant ?? 'default'] ?? 'bg-layer hover:bg-primary hover:text-primary-contrasting',
        $classList ?? null,
    ],
])
