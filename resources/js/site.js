import { createElement } from 'react';
import { createRoot } from 'react-dom/client';
import SiteApp from './site/SiteApp.jsx';

createRoot(document.getElementById('site')).render(createElement(SiteApp, window.siteData));
