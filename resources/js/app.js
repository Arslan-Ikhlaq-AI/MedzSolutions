import { createElement } from 'react';
import { createRoot } from 'react-dom/client';
import AuthApp from './app.jsx';

const root = document.getElementById('app');

createRoot(root).render(createElement(
    AuthApp,
    {
        mode: root.dataset.mode,
        name: root.dataset.name,
        email: root.dataset.email,
        errors: JSON.parse(root.dataset.errors),
        status: root.dataset.status,
    },
));
