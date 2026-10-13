import { useEffect, useState } from 'react';

const cookieName = 'medz_consent';
const keys = { analytics: 'a', marketing: 'm', preferences: 'p' };

export const optionalCategories = Object.keys(keys);

let channel = null;
let visitRecorded = false;
const loaded = new Set();

/**
 * Reads the visitor's saved choice, or null when they have not chosen yet or the policy has changed.
 */
export function readConsent(version) {
    const entry = document.cookie.split('; ').find((row) => row.startsWith(`${cookieName}=`));

    if (!entry) {
        return null;
    }

    try {
        const saved = JSON.parse(decodeURIComponent(entry.slice(cookieName.length + 1)));

        return saved.v === version ? saved : null;
    } catch {
        return null;
    }
}

export function allows(saved, category) {
    return category === 'necessary' || Boolean(saved?.[keys[category]]);
}

export function choicesFrom(saved) {
    return Object.fromEntries(optionalCategories.map((category) => [category, allows(saved, category)]));
}

export function describe(saved) {
    return { id: saved.id, necessary: true, ...choicesFrom(saved), gpc: Boolean(saved.g), updatedAt: new Date(saved.t) };
}

/**
 * True when the browser sends a Global Privacy Control signal (an opt-out of selling or sharing data).
 */
export function hasPrivacySignal() {
    return typeof navigator !== 'undefined' && navigator.globalPrivacyControl === true;
}

function newId() {
    if (window.crypto?.randomUUID) {
        return window.crypto.randomUUID();
    }

    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, (character) => {
        const random = (Math.random() * 16) | 0;

        return (character === 'x' ? random : (random & 0x3) | 0x8).toString(16);
    });
}

/**
 * Stores the choice in a first-party cookie.
 */
export function saveConsent({ version, days, previousId, choices, gpc }) {
    const saved = {
        v: version,
        id: previousId ?? newId(),
        a: Boolean(choices.analytics),
        m: Boolean(choices.marketing),
        p: Boolean(choices.preferences),
        g: gpc ? 1 : 0,
        t: Date.now(),
    };
    const secure = window.location.protocol === 'https:' ? '; Secure' : '';

    document.cookie = `${cookieName}=${encodeURIComponent(JSON.stringify(saved))}; Max-Age=${days * 86400}; Path=/; SameSite=Lax${secure}`;

    return saved;
}

/**
 * Turns on the scripts the visitor allowed. Placeholders look like
 *   <script type="text/plain" data-consent="analytics" data-src="https://…"></script>
 * and are swapped for real scripts once their category is allowed.
 */
function activateScripts(saved) {
    document.querySelectorAll('script[type="text/plain"][data-consent]').forEach((placeholder) => {
        const category = placeholder.dataset.consent;

        if (!allows(saved, category)) {
            return;
        }

        const script = document.createElement('script');

        [...placeholder.attributes].forEach(({ name, value }) => {
            if (!['type', 'data-src', 'data-consent'].includes(name)) {
                script.setAttribute(name, value);
            }
        });

        if (placeholder.dataset.src) {
            script.src = placeholder.dataset.src;
        } else {
            script.text = placeholder.text;
        }

        script.dataset.consentLoaded = category;
        placeholder.replaceWith(script);
        loaded.add(category);
    });
}

/**
 * Applies a choice to the page: updates Google Consent Mode, tells tag managers, loads allowed
 * scripts and lets components on the page know.
 */
export function applyConsent(saved) {
    const state = (granted) => (granted ? 'granted' : 'denied');

    window.gtag?.('consent', 'update', {
        ad_storage: state(saved.m),
        ad_user_data: state(saved.m),
        ad_personalization: state(saved.m),
        analytics_storage: state(saved.a),
        personalization_storage: state(saved.p),
    });
    window.dataLayer?.push({ event: 'medz_consent_update', medz_consent: choicesFrom(saved) });

    activateScripts(saved);
    window.dispatchEvent(new CustomEvent('medz:consent', { detail: describe(saved) }));
}

/**
 * Scripts that are already running cannot be unloaded, so withdrawing consent for one of their
 * categories needs a page reload.
 */
export function needsReload(saved) {
    return [...loaded].some((category) => !allows(saved, category));
}

function deleteCookie(name) {
    const parts = window.location.hostname.split('.');
    const domains = [''];

    for (let index = 0; index < parts.length - 1; index++) {
        domains.push(`; Domain=.${parts.slice(index).join('.')}`);
    }

    domains.forEach((domain) => {
        document.cookie = `${name}=; Max-Age=0; Path=/${domain}`;
    });
}

/**
 * Deletes the cookies of the given categories. Names ending in "*" match by prefix.
 */
export function clearCookies(categories, catalog) {
    const patterns = catalog.filter((category) => categories.includes(category.id)).flatMap((category) => category.cookies.map((cookie) => cookie.name));
    const present = document.cookie.split('; ').map((row) => row.split('=')[0]).filter(Boolean);

    present
        .filter((name) => patterns.some((pattern) => (pattern.endsWith('*') ? name.startsWith(pattern.slice(0, -1)) : name === pattern)))
        .forEach(deleteCookie);
}

/**
 * Sends a copy of the choice to the server as a record of consent. Failure never blocks the visitor.
 */
export async function recordConsent({ endpoint, csrfToken, version, saved, source }) {
    try {
        await fetch(endpoint, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({
                consent_id: saved.id,
                analytics: saved.a,
                marketing: saved.m,
                preferences: saved.p,
                gpc: Boolean(saved.g),
                source,
                policy_version: version,
                page: window.location.pathname,
            }),
            keepalive: true,
        });
    } catch {
        // The cookie in the browser is the source of truth; the server copy is best effort.
    }
}

/**
 * Notes which page this visitor opened, so the team can see what was useful if they later get in
 * touch. Only called once analytics is allowed; the server checks the consent cookie again.
 */
export function recordVisit({ endpoint, csrfToken }) {
    if (visitRecorded || !endpoint) {
        return;
    }

    visitRecorded = true;

    const params = new URLSearchParams(window.location.search);
    let referrer = null;

    try {
        referrer = document.referrer && new URL(document.referrer).host !== window.location.host ? document.referrer : null;
    } catch {
        referrer = null;
    }

    fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
            path: window.location.pathname,
            title: document.title.split(' – ')[0].slice(0, 160),
            referrer,
            utm_source: params.get('utm_source')?.slice(0, 100) || null,
            utm_medium: params.get('utm_medium')?.slice(0, 100) || null,
            utm_campaign: params.get('utm_campaign')?.slice(0, 100) || null,
        }),
        keepalive: true,
    }).catch(() => {
        // Visit history is a nice-to-have; never bother the visitor about it.
    });
}

function sharedChannel() {
    if (!channel && typeof BroadcastChannel !== 'undefined') {
        channel = new BroadcastChannel('medz-consent');
    }

    return channel;
}

/**
 * Keeps other open tabs of the website in step with a new choice.
 */
export function broadcast(saved) {
    sharedChannel()?.postMessage(saved);
}

export function onBroadcast(handler) {
    const current = sharedChannel();

    if (!current) {
        return () => {};
    }

    const listener = (event) => handler(event.data);
    current.addEventListener('message', listener);

    return () => current.removeEventListener('message', listener);
}

/**
 * Lets other scripts work with consent:
 *   window.medzConsent.allows('analytics')  → true / false
 *   window.medzConsent.get()                → the current choice, or null
 *   window.medzConsent.open()               → opens the preference center
 * and react to changes with window.addEventListener('medz:consent', …).
 */
export function exposeConsent(version) {
    window.medzConsent = {
        get: () => {
            const saved = readConsent(version);

            return saved ? describe(saved) : null;
        },
        allows: (category) => allows(readConsent(version), category),
        open: () => window.dispatchEvent(new Event('open-cookie-settings')),
    };
}

/**
 * The visitor's current choice, kept up to date as it changes.
 */
export function useSavedConsent(version) {
    const [saved, setSaved] = useState(null);

    useEffect(() => {
        const update = () => setSaved(readConsent(version));

        update();
        window.addEventListener('medz:consent', update);

        return () => window.removeEventListener('medz:consent', update);
    }, [version]);

    return saved;
}
