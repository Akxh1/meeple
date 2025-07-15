import '../css/app.css';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';
import Orb from './components/Orb'; // <-- Step 3a: Import Orb

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Inertia App Setup
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#4B5563',
    },
});

// Light/Dark theme init
initializeTheme();

// Step 3b: Mount Orb separately if #orb-root exists
document.addEventListener('DOMContentLoaded', () => {
    const orbTarget = document.getElementById('orb-root');
    if (orbTarget) {
        const orbRoot = createRoot(orbTarget);
        orbRoot.render(
            <Orb
                hoverIntensity={0.5}
                rotateOnHover={true}
                hue={180} // Adjust hue to match your theme
            />
        );
    }
});
