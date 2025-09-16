import containerQueriesPlugin from '@tailwindcss/container-queries';
import plugin from 'tailwindcss/plugin';
import flattenColorPalette from 'tailwindcss/lib/util/flattenColorPalette';

import { readFileSync, existsSync } from 'node:fs';
import { resolve, dirname } from 'node:path';
import { config, wpPath } from './context';

const additionalContent = [...(config?.tailwind?.content || [])].map((path) =>
  resolve(wpPath || __dirname, path),
);

function toContrastColor(color: string) {
  return `oklab(from ${color} calc(1 / (.6 - l)) 0 0)`;
}

export default {
  important: '.tailwind',
  content: [
    './index.html',
    './src/**/*.{js,ts,jsx,tsx}',
    './autoload/**/*.php',
    './psr-4/**/*.php',
    './src/**/*.php',
    './views/**/*.php',
    ...additionalContent,
  ],
  safelist: [],
  theme: {
    colors: {
      current: 'currentColor',
      inherit: 'inherit',
      transparent: 'transparent',
      white: '#fff',
      primary: 'var(--color-primary)',
      'primary-dark': 'var(--color-primary-dark)',
      'primary-light': 'var(--color-primary-light)',
      'primary-contrasting': 'var(--color-primary-contrasting)',
      secondary: 'var(--color-secondary)',
      'secondary-dark': 'var(--color-secondary-dark)',
      'secondary-light': 'var(--color-secondary-light)',
      'secondary-contrasting': 'var(--color-secondary-contrasting)',
      background: 'var(--color-background)',
      'background-complementary': 'var(--color-background-complementary)',
      'background-card': 'var(--color-background-card)',
      'border-card': 'var(--color-border-card)',

      'secondary-text': 'var(--text-secondary)',
      'disabled-text': 'var(--text-disabled)',
      'border-divider': 'var(--color-border-divider)',
      'border-outline': 'var(--color-border-outline)',
      'border-input': 'var(--color-border-input)',
      link: 'var(--color-link)',
      'link-hover': 'var(--color-link-hover)',
      'link-active': 'var(--color-link-active)',
      'link-visited': 'var(--color-link-visited)',
      'link-visited-hover': 'var(--color-link-visited-hover)',
      alpha: 'var(--color-alpha)',
      success: 'var(--color-success)',
      'success-dark': 'var(--color-success-dark)',
      'success-light': 'var(--color-success-light)',
      'success-contrasting': 'var(--color-success-contrasting)',
      danger: 'var(--color-danger)',
      'danger-dark': 'var(--color-danger-dark)',
      'danger-light': 'var(--color-danger-light)',
      'danger-contrasting': 'var(--color-danger-contrasting)',
      warning: 'var(--color-warning)',
      'warning-dark': 'var(--color-warning-dark)',
      'warning-light': 'var(--color-warning-light)',
      'warning-contrasting': 'var(--color-warning-contrasting)',
      info: 'var(--color-info)',
      'info-dark': 'var(--color-info-dark)',
      'info-light': 'var(--color-info-light)',
      'info-contrasting': 'var(--color-info-contrasting)',

      complementary: 'var(--color-complementary)',
      'complementary-light': 'var(--color-complementary-light)',
      'complementary-lighter': 'var(--color-complementary-lighter)',
      'complementary-lightest': 'var(--color-complementary-lightest)',

      'layer-dark': 'var(--color-layer-dark, var(--color-complementary))',
      layer: 'var(--color-layer, var(--color-complementary-light))',
      'layer-light':
        'var(--color-layer-light, var(--color-complementary-lighter))',
      'layer-lighter':
        'var(--color-layer-lighter, var(--color-complementary-lightest))',

      default: 'var(--color-default)',
      black: 'var(--color-black)',
      darkest: 'var(--color-darkest)',
      darker: 'var(--color-darker)',
      dark: 'var(--color-dark)',
      light: 'var(--color-light)',
      lighter: 'var(--color-lighter)',
      lightest: 'var(--color-lightest)',
      custom: {
        DEFAULT: 'var(--color-custom)',
        50: 'hsl(from var(--color-custom) h s 95%)',
        100: 'hsl(from var(--color-custom) h s 90%)',
        200: 'hsl(from var(--color-custom) h s 80%)',
        300: 'hsl(from var(--color-custom) h s 70%)',
        400: 'hsl(from var(--color-custom) h s 60%)',
        500: 'hsl(from var(--color-custom) h s 50%)',
        600: 'hsl(from var(--color-custom) h s 40%)',
        700: 'hsl(from var(--color-custom) h s 30%)',
        800: 'hsl(from var(--color-custom) h s 20%)',
        900: 'hsl(from var(--color-custom) h s 10%)',
        950: 'hsl(from var(--color-custom) h s 5%)',
        tint: {
          50: 'hsl(from var(--color-custom) h s clamp(l + 5, 0, 100))',
          100: 'hsl(from var(--color-custom) h s clamp(l + 10, 0, 100))',
          200: 'hsl(from var(--color-custom) h s clamp(l + 20, 0, 100))',
          300: 'hsl(from var(--color-custom) h s clamp(l + 30, 0, 100))',
          400: 'hsl(from var(--color-custom) h s clamp(l + 40, 0, 100))',
          500: 'hsl(from var(--color-custom) h s clamp(l + 50, 0, 100))',
          600: 'hsl(from var(--color-custom) h s clamp(l + 60, 0, 100))',
          700: 'hsl(from var(--color-custom) h s clamp(l + 70, 0, 100))',
          800: 'hsl(from var(--color-custom) h s clamp(l + 80, 0, 100))',
          900: 'hsl(from var(--color-custom) h s clamp(l + 90, 0, 100))',
        },
        shade: {
          50: 'hsl(from var(--color-custom) h s clamp(l - 5, 0, 100))',
          100: 'hsl(from var(--color-custom) h s clamp(l - 10, 0, 100))',
          200: 'hsl(from var(--color-custom) h s clamp(l - 20, 0, 100))',
          300: 'hsl(from var(--color-custom) h s clamp(l - 30, 0, 100))',
          400: 'hsl(from var(--color-custom) h s clamp(l - 40, 0, 100))',
          500: 'hsl(from var(--color-custom) h s clamp(l - 50, 0, 100))',
          600: 'hsl(from var(--color-custom) h s clamp(l - 60, 0, 100))',
          700: 'hsl(from var(--color-custom) h s clamp(l - 70, 0, 100))',
          800: 'hsl(from var(--color-custom) h s clamp(l - 80, 0, 100))',
          900: 'hsl(from var(--color-custom) h s clamp(l - 90, 0, 100))',
        },
      },
    },
    screens: {
      sm: '32.5em', // 520px
      md: '56em', // 896px
      lg: '78em', // 1248px
      xl: '100em', // 1600px
    },
    containers: {
      sm: '32.5em', // 520px
      md: '56em', // 896px
      lg: '78em', // 1248px
      xl: '100em', // 1600px
    },
    extend: {
      // ...defaultTheme,
      fontSize: {
        h1: 'var(--h1-font-size)',
        h2: 'var(--h2-font-size)',
        h3: 'var(--h3-font-size)',
        h4: 'var(--h4-font-size)',
        h5: 'var(--h5-font-size)',
        h6: 'var(--h6-font-size)',
        caption: 'var(--caption-font-size)',
      },
      prose: {
        elements: {
          // Sorted from most specific to least specific
          body: 'p',
          blockquote: 'blockquote',
          ul: 'ul:not(:where(.unlist))',
          ol: 'ol:not(:where(.unlist))',
          list: 'ul:not(:where(.unlist)), ol:not(:where(.unlist))',
          li: 'li',
          link: 'a',
          lead: 'p.lead, p.lead a',
          heading:
            ':is(h1, h2, h3, h4, h5, h6):not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography))',
          h1: 'h1:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)), h1:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)) a, .typography-h1, .c-typography__variant--h1, .typography-h1 a, .c-typography__variant--h1',
          h2: 'h2:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)), h2:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)) a, .typography-h2, .c-typography__variant--h2, .typography-h2 a, .c-typography__variant--h2',
          h3: 'h3:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)), h3:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)) a, .typography-h3, .c-typography__variant--h3, .typography-h3 a, .c-typography__variant--h3',
          h4: 'h4:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)), h4:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)) a, .typography-h4, .c-typography__variant--h4, .typography-h4 a, .c-typography__variant--h4',
          h5: 'h5:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)), h5:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)) a, .typography-h5, .c-typography__variant--h5, .typography-h5 a, .c-typography__variant--h5',
          h6: 'h6:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)), h6:not(:where(.typography-h1,.typography-h2,.typography-h3,.typography-h4,.typography-h5,.typography-h6,.c-typography)) a, .typography-h6, .c-typography__variant--h6, .typography-h6 a, .c-typography__variant--h6',
        },
      },
    },
  },
  plugins: [
    containerQueriesPlugin,
    plugin(
      ({
        addBase,
        addVariant,
        matchVariant,
        addUtilities,
        matchUtilities,
        theme,
      }) => {
        addVariant(
          'interactive',
          '&:is(:any-link, :enabled, label, [tabindex])',
        );
        addVariant('inert', '&:not(:any-link, :enabled, label, [tabindex])');
        addVariant('contentless', [
          '&:empty:not(area, embed, hr, img, input, source, track)',
          '&:not(:has(:not(:empty), area, embed, hr, img, input, source, track))',
        ]);
        matchUtilities(
          {
            'text-contrast': (value) => {
              return {
                color: toContrastColor(value),
              };
            },
          },
          {
            values: flattenColorPalette(theme('colors')),
            type: ['color', 'any'],
          },
        );
        matchVariant(
          'prose',
          (
            value,
            // { modifier }
          ) => `& :where(${value}):where(:not(.no-prose))`,
          {
            values: theme('prose.elements'),
            // sort,
          },
        );
      },
    ),
  ],
};
