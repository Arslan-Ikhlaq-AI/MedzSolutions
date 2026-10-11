import Button from '../components/Button.jsx';
import ContactForm from '../components/ContactForm.jsx';
import ContactLinks, { phoneHref } from '../components/ContactLinks.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { useSite } from '../context.js';
import { Highlight, Rich } from '../text.jsx';

function ContactCards({ company }) {
    const [weekdays, weekend] = company.hours;
    const cards = [
        { icon: 'phone', label: 'Call Us', value: company.phone, href: phoneHref(company.phone), note: 'Speak directly with a billing specialist' },
        { icon: 'mail', label: 'Email Us', value: company.email, href: `mailto:${company.email}`, note: 'We reply within one working day' },
        { icon: 'pin', label: 'Where We Work', value: 'Nationwide, USA', note: 'Meetings by phone or video call' },
        { icon: 'clock', label: 'Working Hours', value: weekdays.time, note: `${weekdays.days} · Weekends ${weekend.time.toLowerCase()}` },
    ];

    return (
        <section className="contact-cards">
            <div className="container">
                <div className="contact-cards-grid">
                    {cards.map((card, index) => (
                        <article className="contact-card" key={card.label} data-animate="fade-up" style={{ '--delay': `${index * 0.08}s` }}>
                            <span className="contact-card-icon"><Icon name={card.icon} /></span>
                            <h2 className="contact-card-label">{card.label}</h2>
                            {card.href
                                ? <a className="contact-card-value" href={card.href}>{card.value}</a>
                                : <p className="contact-card-value">{card.value}</p>}
                            <p className="contact-card-note">{card.note}</p>
                        </article>
                    ))}
                </div>
            </div>
        </section>
    );
}

function MapSection({ company, content }) {
    return (
        <section className="map-section" aria-label="Map">
            <div className="map-info">
                <div className="map-card" data-animate="fade-up">
                    <p className="section-eyebrow">{content.map_eyebrow}</p>
                    <h2><Highlight text={content.map_title} /></h2>
                    <p className="map-card-text">{content.map_text}</p>
                    <ul className="map-hours">
                        {company.hours.map((row) => (
                            <li key={row.days}><span>{row.days}</span><strong>{row.time}</strong></li>
                        ))}
                    </ul>
                    <ContactLinks />
                    <Button href={company.map.directions} target="_blank" rel="noopener noreferrer">Open in Google Maps</Button>
                </div>
            </div>
            <iframe
                className="map-frame"
                src={company.map.embed}
                title={`${company.name} on Google Maps`}
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
                allowFullScreen
            />
        </section>
    );
}

export default function Contact({ content, headerImage, sideImage }) {
    const { company } = useSite().shared;

    return (
        <>
            <PageHeader title="Contact Us" text={content.header_text} image={headerImage} crumbs={[{ label: 'Contact Us' }]} />
            <ContactCards company={company} />

            <section className="section contact-main" id="contact-form">
                <div className="container">
                    <div className="contact-main-grid">
                        <aside className="contact-aside">
                            <figure className="contact-photo" data-animate="reveal">
                                <div className="media-inner">
                                    <img src={sideImage} alt={content.side_image_alt} loading="lazy" width="960" height="640" />
                                </div>
                                <figcaption className="contact-photo-badge">
                                    <span className="contact-photo-badge-icon"><Icon name="chat" /></span>
                                    {content.side_badge}
                                </figcaption>
                            </figure>
                            <div className="next-steps" data-animate="fade-up">
                                <h3>{content.next_steps_title}</h3>
                                <ol>
                                    {content.next_steps.map((step) => (
                                        <li key={step.title}>
                                            <strong>{step.title}</strong>
                                            <span>{step.text}</span>
                                        </li>
                                    ))}
                                </ol>
                            </div>
                        </aside>
                        <div className="contact-form-column">
                            <SectionTitle eyebrow={content.eyebrow} title={content.title} />
                            <p className="lead-text" data-animate="fade-up"><Rich text={content.body} /></p>
                            <div className="contact-form-card" data-animate="fade-up">
                                <h3 className="form-card-title"><Highlight text={content.form_title} /></h3>
                                <p className="form-card-text">{content.form_text}</p>
                                <ContactForm />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <MapSection company={company} content={content} />
        </>
    );
}
