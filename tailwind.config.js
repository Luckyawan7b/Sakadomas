
/** @type {import('tailwindcss').Config} */
export default {
    // Aktifkan dark mode via class (misal: <html class="dark">)
    darkMode: 'class',

    // Scan semua file Blade & JS untuk class purging
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.jsx',
    ],

    theme: {
        extend: {

            // ================================================================
            // COLOR SYSTEM — Material You inspired palette
            // Semua token warna mengikuti konvensi Material Design 3
            // ================================================================
            colors: {
                // Primary — Hijau tua peternakan
                'primary':                    '#204e2b',
                'on-primary':                 '#ffffff',
                'primary-container':          '#386641',
                'on-primary-container':       '#afe2b3',
                'primary-fixed':              '#bcefc0',
                'primary-fixed-dim':          '#a0d3a5',
                'on-primary-fixed':           '#00210a',
                'on-primary-fixed-variant':   '#22502d',

                // Secondary — Coklat tanah / kulit domba
                'secondary':                  '#805533',
                'on-secondary':               '#ffffff',
                'secondary-container':        '#fdc39a',
                'on-secondary-container':     '#794e2e',
                'secondary-fixed':            '#ffdcc5',
                'secondary-fixed-dim':        '#f4bb92',
                'on-secondary-fixed':         '#301400',
                'on-secondary-fixed-variant': '#653d1e',

                // Tertiary — Abu-abu hangat / padang rumput kering
                'tertiary':                   '#47453b',
                'on-tertiary':                '#ffffff',
                'tertiary-container':         '#5e5d51',
                'on-tertiary-container':      '#d9d6c7',

                // Error
                'error':                      '#ba1a1a',
                'on-error':                   '#ffffff',
                'error-container':            '#ffdad6',
                'on-error-container':         '#93000a',

                // Background & Surface
                'background':                  '#fef9f2',
                'on-background':               '#1d1c17',
                'surface':                     '#fef9f2',
                'on-surface':                  '#1d1c17',
                'surface-variant':             '#e6e2db',
                'on-surface-variant':          '#414941',
                'surface-bright':              '#fef9f2',
                'surface-container-lowest':    '#ffffff',
                'surface-container-low':       '#f8f3ec',
                'surface-container':           '#f2ede6',
                'surface-container-high':      '#ece8e1',
                'surface-container-highest':   '#e6e2db',
                'surface-dim':                 '#ded9d3',

                // Outline
                'outline':                     '#727970',
                'outline-variant':             '#c1c9be',

                // Inverse
                'inverse-surface':             '#32302c',
                'inverse-on-surface':          '#f5f0e9',
                'inverse-primary':             '#a0d3a5',
            },

            // ================================================================
            // TYPOGRAPHY
            // ================================================================
            fontFamily: {
                'noto-serif': ['"Noto Serif"', 'Georgia', 'serif'],
                'manrope':    ['"Manrope"', 'system-ui', 'sans-serif'],
            },

            // ================================================================
            // ANIMATION
            // ================================================================
            keyframes: {
                fadeInUp: {
                    '0%':   { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.4s ease-out forwards',
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
    ],
};
