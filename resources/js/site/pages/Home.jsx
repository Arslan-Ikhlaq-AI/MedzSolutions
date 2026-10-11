import Button from '../components/Button.jsx';
import CheckList from '../components/CheckList.jsx';
import ContactLinks from '../components/ContactLinks.jsx';
import FaqAccordion from '../components/FaqAccordion.jsx';
import HeroSlider from '../components/HeroSlider.jsx';
import HoursPanel from '../components/HoursPanel.jsx';
import Icon from '../components/Icon.jsx';
import MeetingForm from '../components/MeetingForm.jsx';
import ReviewForm from '../components/ReviewForm.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import ServiceCard from '../components/ServiceCard.jsx';
import StatGrid from '../components/StatGrid.jsx';
import Testimonials from '../components/Testimonials.jsx';
import { useSite } from '../context.js';
import { Highlight, Rich } from '../text.jsx';

function Photo({ src, alt, className = '' }) {
    return (
        <figure className={`photo-frame ${className}`.trim()} data-animate="reveal">
            <div className="media-inner">
                <img src={src} alt={alt} loading="lazy" width="960" height="640" />
            </div>
        </figure>
    );
}

export default function Home({ content, photos, services, specialities, specialityNames, software, challenges, faqs, testimonials }) {
    const { routes } = useSite().shared;
    const { welcome, about, why, choose, support, onboarding, commitments } = content;

    return (
        <>
            <HeroSlider
                slides={content.slides}
                actions={[
                    { label: content.primary_cta, url: '#practice-review', variant: 'gold' },
                    { label: content.secondary_cta, url: routes.services, variant: 'outline' },
                ]}
            />

            <section className="home-intro" id="schedule">
                <div className="container">
                    <div className="home-intro-grid">
                        <div className="home-intro-copy">
                            <h2 className="welcome-title" data-animate="fade-up"><Highlight text={welcome.title} /></h2>
                            <p className="welcome-subtitle" data-animate="fade-up">{welcome.subtitle}</p>
                            {about.paragraphs.map((paragraph) => (
                                <p className="lead-text" key={paragraph} data-animate="fade-up"><Rich text={paragraph} /></p>
                            ))}
                            <CheckList items={about.highlights} />
                            <Photo src={photos.welcome} alt={welcome.image_alt} className="welcome-photo" />
                            <a className="text-link" href={routes.about} data-animate="fade-up">{welcome.link} <Icon name="arrow" /></a>
                        </div>
                        <div className="home-intro-form">
                            <MeetingForm content={content.meeting} specialityNames={specialityNames} />
                        </div>
                    </div>
                </div>
            </section>

            <section className="why-band">
                <div className="container">
                    <SectionHeading title={why.title} text={why.text} />
                    <div className="why-tiles">
                        {why.tiles.map((tile, index) => (
                            <article className={`why-tile ${index % 2 ? 'is-gold' : 'is-navy'}`} key={tile.title} data-animate="fade-up" style={{ '--delay': `${index * 0.08}s` }}>
                                <Icon name={tile.icon} />
                                <h3>{tile.title}</h3>
                                <p>{tile.text}</p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>

            <section className="section">
                <div className="container">
                    <div className="split-grid split-wide">
                        <Photo src={photos.choose} alt={choose.image_alt} className="choose-photo" />
                        <div>
                            <SectionHeading title={choose.title} align="left" />
                            {choose.paragraphs.map((paragraph) => <p className="lead-text" key={paragraph} data-animate="fade-up">{paragraph}</p>)}
                            <div data-animate="fade-up">
                                <Button href={routes.resources}>{choose.cta}</Button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section className="section surface">
                <div className="container">
                    <SectionHeading title={content.services.title} text={content.services.text} />
                    <div className="card-grid three">
                        {services.map((service, index) => <ServiceCard key={service.slug} service={service} delay={(index % 3) * 0.08} />)}
                    </div>
                    <div className="section-more" data-animate="fade-up">
                        <Button href={routes.services}>{content.services.cta}</Button>
                    </div>
                </div>
            </section>

            <section className="section">
                <div className="container">
                    <SectionHeading title={content.specialities.title} />
                    <ul className="speciality-badges">
                        {specialities.map((speciality, index) => (
                            <li className="speciality-badge" key={speciality.name} data-animate="fade-up" style={{ '--delay': `${(index % 8) * 0.05}s` }}>
                                <span className="speciality-badge-circle"><Icon name={speciality.icon} /></span>
                                <span className="speciality-badge-name">{speciality.name}</span>
                            </li>
                        ))}
                    </ul>
                    <div className="section-more" data-animate="fade-up">
                        <a className="text-link" href={routes.specialities}>{content.specialities.cta} <Icon name="arrow" /></a>
                    </div>
                </div>
            </section>

            <section className="software-strip">
                <div className="container software-strip-inner">
                    <p className="software-strip-label">{content.software.title.replace(/\*/g, '')}</p>
                    <ul className="software-names">
                        {software.map((name) => <li key={name}>{name}</li>)}
                    </ul>
                    <a className="text-link" href={routes.software}>{content.software.cta} <Icon name="arrow" /></a>
                </div>
            </section>

            <section className="section review-section" id="practice-review">
                <div className="container">
                    <ReviewForm content={content.review} challenges={challenges} />
                </div>
            </section>

            <section className="section">
                <div className="container">
                    <div className="split-grid split-wide">
                        <div>
                            <SectionHeading title={support.title} align="left" />
                            {support.paragraphs.map((paragraph) => <p className="lead-text" key={paragraph} data-animate="fade-up"><Rich text={paragraph} /></p>)}
                            <CheckList items={support.highlights} />
                            <div data-animate="fade-up">
                                <Button href={routes.team}>Meet Our Team</Button>
                            </div>
                        </div>
                        <Photo src={photos.support} alt={support.image_alt} className="support-photo" />
                    </div>
                </div>
            </section>

            <section className="section surface">
                <div className="container">
                    <SectionHeading title={onboarding.title} text={onboarding.text} />
                    <ol className="onboard-steps">
                        {onboarding.steps.map((step, index) => (
                            <li className="onboard-step" key={step.title} data-animate="fade-up" style={{ '--delay': `${index * 0.1}s` }}>
                                <span className="onboard-number">{index + 1}</span>
                                <h3>{step.title}</h3>
                                <p>{step.text}</p>
                            </li>
                        ))}
                    </ol>
                </div>
            </section>

            <section className="stats-band" aria-label={commitments.title.replace(/\*/g, '')}>
                <div className="container">
                    <StatGrid items={commitments.items.slice(0, 4)} className="stats-band-grid" />
                </div>
            </section>

            <section className="section">
                <div className="container">
                    <div className="faq-split">
                        <div className="faq-split-intro">
                            <SectionHeading title={content.faqs.title} text={content.faqs.text} align="left" />
                            <div data-animate="fade-up">
                                <Button href={routes.faqs}>{content.faqs.cta}</Button>
                            </div>
                            <ContactLinks />
                        </div>
                        <FaqAccordion items={faqs} />
                    </div>
                </div>
            </section>

            <Testimonials heading={content.testimonials} testimonials={testimonials} />
            <HoursPanel heading={content.visit} />
        </>
    );
}
