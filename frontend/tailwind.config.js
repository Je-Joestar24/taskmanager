/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./index.html",
        "./src/**/*.{vue,js,ts,jsx,tsx}"
    ],
    theme: {
        extend: {
            colors: {
                bg: {
                    DEFAULT: "var(--theme-bg)",
                    secondary: "var(--theme-bg-secondary)",
                    tertiary: "var(--theme-bg-tertiary)",
                    card: "var(--theme-bg-card)",
                    overlay: "var(--theme-bg-overlay)",
                },
                text: {
                    DEFAULT: "var(--theme-text)",
                    secondary: "var(--theme-text-secondary)",
                    muted: "var(--theme-text-muted)",
                    inverse: "var(--theme-text-inverse)",
                },
                primary: "var(--theme-primary)",
                secondary: "var(--theme-secondary)",
                accent: "var(--theme-accent)",
                success: "var(--theme-success)",
                warning: "var(--theme-warning)",
                error: "var(--theme-error)",

                border: {
                    DEFAULT: "var(--theme-border)",
                    secondary: "var(--theme-border-secondary)",
                    accent: "var(--theme-border-accent)",
                },

                notification: {
                    success: "var(--theme-notification-success)",
                    error: "var(--theme-notification-error)",
                    warning: "var(--theme-notification-warning)",
                    info: "var(--theme-notification-info)",
                },

                glow: {
                    light: "var(--theme-glow)",
                    heavy: "var(--theme-glow-heavy)",
                },
                globe: "var(--theme-globe)",
            },

            boxShadow: {
                sm: "var(--theme-shadow-sm)",
                DEFAULT: "var(--theme-shadow)",
                md: "var(--theme-shadow-md)",
                lg: "var(--theme-shadow-lg)",
                xl: "var(--theme-shadow-xl)",
                heavy: "var(--theme-shadow-heavy)",
                shine: "var(--theme-shadow-shine)",
                glow: "var(--theme-shadow-glow)",
            },

            borderRadius: {
                card: "var(--theme-card-radius)",
            },

            screens: {
                xl: "var(--container-xl)",
                lg: "var(--container-lg)",
                md: "var(--container-md)",
                sm: "var(--container-sm)",
            },

            transitionProperty: {
                theme: "var(--theme-transition)",
                fast: "var(--theme-transition-fast)",
                slow: "var(--theme-transition-slow)",
            },
        },
    },
    plugins: [],
}
