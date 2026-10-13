import { useCallback, useEffect, useRef, useState } from 'react';
import {
    allows,
    applyConsent,
    broadcast,
    choicesFrom,
    clearCookies,
    exposeConsent,
    hasPrivacySignal,
    needsReload,
    onBroadcast,
    optionalCategories,
    readConsent,
    recordConsent,
    recordVisit,
    saveConsent,
} from '../consent.js';
import { useSite } from '../context.js';
import Icon from './Icon.jsx';

export const formatConsentDate = (timestamp) => new Intl.DateTimeFormat('en-US', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date(timestamp));

export function CookieGlyph() {
    return (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true" focusable="false">
            <path d="M21 12.2A9 9 0 1 1 11.8 3a4.5 4.5 0 0 0 5 5 4 4 0 0 0 4.2 4.2Z" />
            <circle cx="9" cy="10" r="1" fill="currentColor" />
            <circle cx="14.5" cy="15" r="1" fill="currentColor" />
            <circle cx="8.5" cy="15.5" r="1" fill="currentColor" />
        </svg>
    );
}

function SlidersGlyph() {
    return (
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" aria-hidden="true" focusable="false">
            <path d="M4 7h9M17 7h3M4 17h3M11 17h9" />
            <circle cx="15" cy="7" r="2" />
            <circle cx="9" cy="17" r="2" />
        </svg>
    );
}

function Switch({ id, checked, onChange, label }) {
    return (
        <label className="cookie-switch" htmlFor={id}>
            <input id={id} type="checkbox" role="switch" checked={checked} onChange={(event) => onChange(event.target.checked)} />
            <span className="cookie-switch-track" aria-hidden="true" />
            <span className="sr-only">{label}</span>
        </label>
    );
}

function CookieList({ category }) {
    if (category.cookies.length === 0) {
        return <p className="cookie-none">No cookies of this type are set on this website at the moment. If that changes, we will ask you again.</p>;
    }

    return (
        <ul className="cookie-list">
            {category.cookies.map((cookie) => (
                <li key={cookie.name}>
                    <div className="cookie-list-head">
                        <code>{cookie.name}</code>
                        <span>{cookie.provider}</span>
                    </div>
                    <p>{cookie.purpose}</p>
                    <span className="cookie-list-duration"><Icon name="clock" /> {cookie.duration}</span>
                </li>
            ))}
        </ul>
    );
}

function PreferenceCenter({ cookies, brandMark, policyUrl, saved, gpc, onSave, onClose }) {
    const [choices, setChoices] = useState(() => choicesFrom(saved));
    const [expanded, setExpanded] = useState(null);
    const dialogRef = useRef(null);
    const allOn = { analytics: true, marketing: !gpc, preferences: true };
    const allOff = Object.fromEntries(optionalCategories.map((id) => [id, false]));

    useEffect(() => {
        const dialog = dialogRef.current;
        const previousFocus = document.activeElement;
        const focusable = () => [...dialog.querySelectorAll('button, a[href], input')];

        document.body.classList.add('has-cookie-dialog');
        dialog.querySelector('.cookie-close')?.focus();

        const onKeyDown = (event) => {
            if (event.key === 'Escape') {
                onClose();

                return;
            }

            if (event.key === 'Tab') {
                const items = focusable();
                const first = items[0];
                const last = items[items.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (!event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }
        };

        document.addEventListener('keydown', onKeyDown);

        return () => {
            document.body.classList.remove('has-cookie-dialog');
            document.removeEventListener('keydown', onKeyDown);
            previousFocus?.focus?.();
        };
    }, [onClose]);

    return (
        <div className="cookie-overlay" onMouseDown={(event) => event.target === event.currentTarget && onClose()}>
            <div className="cookie-dialog" role="dialog" aria-modal="true" aria-labelledby="cookie-dialog-title" aria-describedby="cookie-dialog-intro" ref={dialogRef}>
                <header className="cookie-dialog-head">
                    <span className="cookie-dialog-mark"><img src={brandMark} alt="" width="30" height="30" /></span>
                    <div>
                        <p className="cookie-dialog-eyebrow">Privacy preference center</p>
                        <h2 id="cookie-dialog-title">Manage your cookie choices</h2>
                    </div>
                    <button type="button" className="cookie-close" onClick={onClose} aria-label="Close cookie preferences"><Icon name="close" /></button>
                </header>

                <div className="cookie-dialog-body">
                    <p className="cookie-dialog-intro" id="cookie-dialog-intro">{cookies.intro} <a href={policyUrl}>Read our Cookie Policy</a>.</p>

                    {gpc && (
                        <p className="cookie-signal">
                            <Icon name="shield" />
                            <span>Your browser sends a <strong>Global Privacy Control</strong> signal, so marketing cookies stay off unless you switch them on yourself.</span>
                        </p>
                    )}

                    <ul className="cookie-categories">
                        {cookies.categories.map((category) => {
                            const isOpen = expanded === category.id;
                            const count = category.cookies.length;

                            return (
                                <li className={`cookie-category${isOpen ? ' is-open' : ''}`} key={category.id}>
                                    <div className="cookie-category-head">
                                        <button
                                            type="button"
                                            className="cookie-category-toggle"
                                            aria-expanded={isOpen}
                                            aria-controls={`cookie-panel-${category.id}`}
                                            onClick={() => setExpanded(isOpen ? null : category.id)}
                                        >
                                            <Icon name="chevron" />
                                            <span className="cookie-category-name">{category.title}</span>
                                            <span className="cookie-count">{count > 0 ? `${count} ${count === 1 ? 'cookie' : 'cookies'}` : 'None in use'}</span>
                                        </button>
                                        {category.required ? (
                                            <span className="cookie-always">Always active</span>
                                        ) : (
                                            <Switch
                                                id={`cookie-${category.id}`}
                                                label={`Allow ${category.title.toLowerCase()} cookies`}
                                                checked={choices[category.id]}
                                                onChange={(value) => setChoices((current) => ({ ...current, [category.id]: value }))}
                                            />
                                        )}
                                    </div>
                                    <div className="cookie-category-panel" id={`cookie-panel-${category.id}`} hidden={!isOpen}>
                                        <p>{category.description}</p>
                                        <CookieList category={category} />
                                    </div>
                                </li>
                            );
                        })}
                    </ul>
                </div>

                <footer className="cookie-dialog-foot">
                    <p className="cookie-receipt">
                        {saved ? (
                            <>Consent ID <code title={saved.id}>{saved.id.split('-')[0]}</code> · Saved {formatConsentDate(saved.t)}</>
                        ) : (
                            'You have not made a choice yet.'
                        )}
                    </p>
                    <div className="cookie-dialog-actions">
                        <button type="button" className="cookie-btn cookie-btn-outline" onClick={() => onSave(choices, 'preferences')}>Confirm my choices</button>
                        <button type="button" className="cookie-btn cookie-btn-secondary" onClick={() => onSave(allOff, 'preferences')}>Reject all</button>
                        <button type="button" className="cookie-btn cookie-btn-primary" onClick={() => onSave(allOn, 'preferences')}>Accept all</button>
                    </div>
                </footer>
            </div>
        </div>
    );
}

/**
 * Cookie banner, preference center and confirmation message. Nothing optional
 * runs until the visitor chooses; the choice is kept in a cookie, applied to the page (Google
 * Consent Mode, blocked scripts), shared with other open tabs and recorded on the server.
 */
export default function CookieConsent() {
    const { shared, csrfToken } = useSite();
    const { cookies, routes, brand } = shared;
    const [saved, setSaved] = useState(null);
    const [bannerVisible, setBannerVisible] = useState(false);
    const [dialogOpen, setDialogOpen] = useState(false);
    const [toast, setToast] = useState(null);
    const [gpc] = useState(hasPrivacySignal);

    useEffect(() => {
        exposeConsent(cookies.version);

        const existing = readConsent(cookies.version);

        // Remove anything left behind by tools the visitor has not (or no longer) allowed.
        clearCookies(optionalCategories.filter((category) => !allows(existing, category)), cookies.categories);

        if (existing) {
            setSaved(existing);
            applyConsent(existing);

            if (allows(existing, 'analytics')) {
                recordVisit({ endpoint: cookies.visitEndpoint, csrfToken });
            }

            return undefined;
        }

        const timer = window.setTimeout(() => setBannerVisible(true), 700);

        return () => window.clearTimeout(timer);
    }, [cookies.version, cookies.categories, cookies.visitEndpoint, csrfToken]);

    useEffect(() => onBroadcast(() => {
        const current = readConsent(cookies.version);

        if (!current) {
            return;
        }

        setSaved(current);
        setBannerVisible(false);
        setDialogOpen(false);

        if (needsReload(current)) {
            window.location.reload();
        } else {
            applyConsent(current);
        }
    }), [cookies.version]);

    const choose = useCallback((choices, source) => {
        const previous = readConsent(cookies.version);
        const record = saveConsent({ version: cookies.version, days: cookies.days, previousId: previous?.id ?? saved?.id, choices, gpc });
        const reload = needsReload(record);

        const refused = optionalCategories.filter((category) => !allows(record, category));

        clearCookies(refused, cookies.categories);
        applyConsent(record);
        setSaved(record);
        setBannerVisible(false);
        setDialogOpen(false);
        recordConsent({ endpoint: cookies.endpoint, csrfToken, version: cookies.version, saved: record, source });
        broadcast(record);

        if (allows(record, 'analytics')) {
            recordVisit({ endpoint: cookies.visitEndpoint, csrfToken });
        }

        if (reload) {
            setToast('Preferences saved. Refreshing the page to apply them…');
            window.setTimeout(() => {
                clearCookies(refused, cookies.categories);
                window.location.reload();
            }, 1200);
        } else {
            setToast('Your cookie preferences have been saved.');
        }
    }, [cookies, csrfToken, gpc, saved]);

    useEffect(() => {
        const open = () => setDialogOpen(true);
        const withdraw = () => choose({ analytics: false, marketing: false, preferences: false }, 'policy_page');

        window.addEventListener('open-cookie-settings', open);
        window.addEventListener('withdraw-cookie-consent', withdraw);

        return () => {
            window.removeEventListener('open-cookie-settings', open);
            window.removeEventListener('withdraw-cookie-consent', withdraw);
        };
    }, [choose]);

    useEffect(() => {
        if (!toast) {
            return undefined;
        }

        const timer = window.setTimeout(() => setToast(null), 4000);

        return () => window.clearTimeout(timer);
    }, [toast]);

    const closeDialog = useCallback(() => setDialogOpen(false), []);

    return (
        <>
            {bannerVisible && !dialogOpen && (
                <section className="cookie-banner" aria-labelledby="cookie-banner-title" aria-describedby="cookie-banner-text">
                    <div className="cookie-banner-head">
                        <span className="cookie-banner-icon"><CookieGlyph /></span>
                        <h2 id="cookie-banner-title">We value your privacy</h2>
                    </div>
                    <p id="cookie-banner-text">
                        We use essential cookies to make this website work. With your consent we would also like to use optional cookies to understand how it is used and to improve it.
                        {' '}<a href={routes.cookiePolicy}>Cookie Policy</a>
                    </p>
                    {gpc && (
                        <p className="cookie-signal is-compact">
                            <Icon name="shield" />
                            <span>Global Privacy Control detected: marketing cookies stay off.</span>
                        </p>
                    )}
                    <div className="cookie-banner-actions">
                        <button type="button" className="cookie-btn cookie-btn-secondary" onClick={() => choose({ analytics: false, marketing: false, preferences: false }, 'banner')}>Reject all</button>
                        <button type="button" className="cookie-btn cookie-btn-primary" onClick={() => choose({ analytics: true, marketing: !gpc, preferences: true }, 'banner')}>Accept all</button>
                    </div>
                    <button type="button" className="cookie-manage" onClick={() => setDialogOpen(true)}>
                        <SlidersGlyph /> Manage preferences
                    </button>
                </section>
            )}

            {dialogOpen && (
                <PreferenceCenter
                    cookies={cookies}
                    brandMark={brand.mark}
                    policyUrl={routes.cookiePolicy}
                    saved={saved}
                    gpc={gpc}
                    onSave={choose}
                    onClose={closeDialog}
                />
            )}

            <div className="cookie-toast-region" role="status" aria-live="polite">
                {toast && (
                    <div className="cookie-toast">
                        <span className="cookie-toast-icon"><Icon name="check" /></span>
                        {toast}
                    </div>
                )}
            </div>
        </>
    );
}
