import Button from '../components/Button.jsx';
import ContactLinks, { phoneHref } from '../components/ContactLinks.jsx';
import CtaBanner from '../components/CtaBanner.jsx';
import FaqAccordion from '../components/FaqAccordion.jsx';
import Icon from '../components/Icon.jsx';
import Media from '../components/Media.jsx';
import OnboardingSteps from '../components/OnboardingSteps.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import ServiceCard from '../components/ServiceCard.jsx';
import StatGrid from '../components/StatGrid.jsx';
import WhyBand from '../components/WhyBand.jsx';
import { useSite } from '../context.js';
import { plainText } from '../text.jsx';

export default function ServiceShow({ service, content, headerImage, values, onboarding, commitments, services }) {
    const { company, routes } = useSite().shared;
    const reviewUrl = `${routes.home}#practice-review`;
    const otherServices = services.filter((item) => item.slug !== service.slug).slice(0, 3);

    return (
        <>
            <PageHeader
                title={service.title}
                image={headerImage}
                compact
                crumbs={[{ label: 'Services', url: routes.services }, { label: service.title }]}
            />

            <section className="section service-overview">
                <div className="container">
                    <div className="content-with-sidebar">
                        <div className="content-main">
                            <p className="service-overview-eyebrow" data-animate="fade-up">
                                <span className="service-overview-icon"><Icon name={service.icon} /></span>
                                {content.overview_eyebrow}
                            </p>
                            <Media item={service} ratio="16 / 8" reveal className="feature-media" />
                            <div className="prose service-prose" data-animate="fade-up" dangerouslySetInnerHTML={{ __html: service.body }} />
                        </div>
                        <aside className="content-sidebar">
                            <div className="sidebar-box services-list-box" data-animate="fade-up">
                                <h2 className="sidebar-title">{content.sidebar_title}</h2>
                                <ul>
                                    {services.map((item) => {
                                        const isCurrent = item.slug === service.slug;

                                        return (
                                            <li key={item.slug} className={isCurrent ? 'is-active' : undefined}>
                                                <a href={item.url} aria-current={isCurrent ? 'page' : undefined}>
                                                    {item.title}
                                                    <Icon name="arrow" />
                                                </a>
                                            </li>
                                        );
                                    })}
                                </ul>
                            </div>
                            <div className="sidebar-box help-box" data-animate="fade-up">
                                <span className="help-icon"><Icon name={service.icon} /></span>
                                <h2>{content.help_title}</h2>
                                <p>{content.help_text}</p>
                                <Button href={reviewUrl} variant="light">{content.primary_cta}</Button>
                                <a className="help-phone" href={phoneHref(company.phone)}>
                                    <Icon name="phone" /> {company.phone}
                                </a>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>

            <WhyBand title={values.title} text={content.benefits_text} tiles={values.items} />

            <OnboardingSteps title={content.process_title} text={content.process_text} steps={onboarding.steps} />

            <section className="stats-band" aria-label={plainText(commitments.title)}>
                <div className="container">
                    <StatGrid items={commitments.items.slice(0, 4)} className="stats-band-grid" />
                </div>
            </section>

            {service.faqs?.length > 0 && (
                <section className="section surface">
                    <div className="container">
                        <div className="faq-split">
                            <div className="faq-split-intro">
                                <SectionHeading title={content.faq_title} text={content.faq_text} align="left" />
                                <div data-animate="fade-up">
                                    <Button href={routes.contact}>{content.faq_cta}</Button>
                                </div>
                                <ContactLinks />
                            </div>
                            <FaqAccordion items={service.faqs} />
                        </div>
                    </div>
                </section>
            )}

            <section className="section">
                <div className="container">
                    <SectionHeading title={content.related_title} text={content.related_text} />
                    <div className="card-grid three">
                        {otherServices.map((item, index) => <ServiceCard key={item.slug} service={item} delay={index * 0.08} />)}
                    </div>
                    <div className="section-more" data-animate="fade-up">
                        <Button href={routes.services}>{content.related_cta}</Button>
                    </div>
                </div>
            </section>

            <CtaBanner title={content.cta_title} text={content.cta_text} actionLabel={content.primary_cta} actionUrl={reviewUrl} />
        </>
    );
}
