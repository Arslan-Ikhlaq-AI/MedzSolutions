import Button from '../components/Button.jsx';
import { formatConsentDate } from '../components/CookieConsent.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import { allows, optionalCategories, useSavedConsent } from '../consent.js';
import { useSite } from '../context.js';

function ConsentStatus({ categories, version }) {
    const saved = useSavedConsent(version);
    const anyOptionalOn = optionalCategories.some((category) => allows(saved, category));

    return (
        <section className="consent-status" aria-labelledby="consent-status-title">
            <div className="consent-status-head">
                <span className="consent-status-icon"><Icon name="shield" /></span>
                <div>
                    <h3 id="consent-status-title">Your current settings</h3>
                    <p>{saved ? `Saved on this device on ${formatConsentDate(saved.t)}.` : 'You have not made a choice on this device yet.'}</p>
                </div>
            </div>

            <ul className="consent-status-list">
                {categories.map((category) => {
                    const on = category.required || allows(saved, category.id);

                    return (
                        <li key={category.id}>
                            <span>{category.title}</span>
                            <span className={`consent-pill${on ? ' is-on' : ''}`}>{category.required ? 'Always on' : on ? 'Allowed' : 'Off'}</span>
                        </li>
                    );
                })}
            </ul>

            {saved && (
                <p className="consent-status-id">
                    Consent ID <code>{saved.id}</code>
                    <span>Quote this ID if you contact us about your consent.</span>
                </p>
            )}

            <div className="consent-status-actions">
                <Button onClick={() => window.dispatchEvent(new Event('open-cookie-settings'))}>Change my settings</Button>
                {anyOptionalOn && (
                    <button type="button" className="cookie-btn cookie-btn-outline" onClick={() => window.dispatchEvent(new Event('withdraw-cookie-consent'))}>
                        Withdraw consent
                    </button>
                )}
            </div>
        </section>
    );
}

export default function CookiePolicy({ content, categories, version }) {
    const { company } = useSite().shared;

    return (
        <>
            <PageHeader title="Cookie Policy" crumbs={[{ label: 'Cookie Policy' }]} />
            <section className="section">
                <div className="container narrow-container">
                    <p className="policy-meta" data-animate="fade-up">Last updated {content.updated} · Policy version {version}</p>
                    <p className="lead-text" data-animate="fade-up">{content.intro}</p>

                    <ConsentStatus categories={categories} version={version} />

                    <h2 className="policy-heading" data-animate="fade-up">{content.what_title}</h2>
                    <p className="lead-text" data-animate="fade-up">{content.what_text}</p>

                    <h2 className="policy-heading" data-animate="fade-up">Cookies we use</h2>
                    {categories.map((category) => (
                        <article className="policy-category" key={category.id} data-animate="fade-up">
                            <h3>
                                {category.title}
                                <span className={`cookie-tag${category.required ? '' : ' is-muted'}`}>{category.required ? 'Always on' : category.active ? 'Optional' : 'Optional · not in use yet'}</span>
                            </h3>
                            <p>{category.description}</p>
                            {category.cookies.length > 0 ? (
                                <div className="policy-table-wrap">
                                    <table className="policy-table">
                                        <thead><tr><th>Cookie</th><th>Provider</th><th>Purpose</th><th>Lasts</th></tr></thead>
                                        <tbody>
                                            {category.cookies.map((cookie) => (
                                                <tr key={cookie.name}>
                                                    <td><code>{cookie.name}</code></td>
                                                    <td>{cookie.provider}</td>
                                                    <td>{cookie.purpose}</td>
                                                    <td>{cookie.duration}</td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <p className="policy-none">We do not currently set any cookies in this category.</p>
                            )}
                        </article>
                    ))}

                    <h2 className="policy-heading" data-animate="fade-up">{content.control_title}</h2>
                    <p className="lead-text" data-animate="fade-up">{content.control_text}</p>
                    <p className="lead-text" data-animate="fade-up">{content.journey_text}</p>
                    <p className="lead-text" data-animate="fade-up">{content.signal_text}</p>

                    <p className="lead-text policy-contact" data-animate="fade-up">{content.contact_text} <a href={`mailto:${company.email}`}>{company.email}</a>.</p>
                </div>
            </section>
        </>
    );
}
