import Button from '../components/Button.jsx';
import ContactLinks from '../components/ContactLinks.jsx';
import CtaBanner from '../components/CtaBanner.jsx';
import FaqAccordion from '../components/FaqAccordion.jsx';
import FeatureList from '../components/FeatureList.jsx';
import Icon from '../components/Icon.jsx';
import Media from '../components/Media.jsx';
import OnboardingSteps from '../components/OnboardingSteps.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import StatGrid from '../components/StatGrid.jsx';
import WhyBand from '../components/WhyBand.jsx';
import { useSite } from '../context.js';
import { plainText } from '../text.jsx';

export default function ServicesIndex({ content, headerImage, services, onboarding, commitments, faqs }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title={content.header_title} image={headerImage} compact crumbs={[{ label: 'Services' }]} />

            <section className="section services-catalog">
                <div className="container">
                    <SectionHeading title={content.title} text={content.text} />
                    {services.map((service, index) => (
                        <article className={`core-service-row${index % 2 ? ' is-reversed' : ''}`} id={service.slug} key={service.slug}>
                            <Media item={service} ratio="4 / 3" reveal className="core-service-row-media" />
                            <div className="core-service-row-body" data-animate="fade-up">
                                <span className="core-services-icon"><Icon name={service.icon} /></span>
                                <span className="service-group-label">{content.group_label} {String(index + 1).padStart(2, '0')}</span>
                                <h3><a href={service.url}>{service.title}</a></h3>
                                <p>{service.excerpt}</p>
                                <h4>{content.list_title}</h4>
                                <FeatureList features={service.features} />
                                <Button href={service.url}>{content.more}</Button>
                            </div>
                        </article>
                    ))}
                </div>
            </section>

            <WhyBand title={content.why.title} text={content.why.text} tiles={content.why.tiles} />

            <OnboardingSteps title={content.process_title} text={content.process_text} steps={onboarding.steps} />

            <section className="stats-band" aria-label={plainText(commitments.title)}>
                <div className="container">
                    <StatGrid items={commitments.items.slice(0, 4)} className="stats-band-grid" />
                </div>
            </section>

            <section className="section surface">
                <div className="container">
                    <div className="faq-split">
                        <div className="faq-split-intro">
                            <SectionHeading title={content.faq_title} text={content.faq_text} align="left" />
                            <div data-animate="fade-up">
                                <Button href={routes.faqs}>{content.faq_cta}</Button>
                            </div>
                            <ContactLinks />
                        </div>
                        <FaqAccordion items={faqs} />
                    </div>
                </div>
            </section>

            <CtaBanner title={content.cta_title} text={content.cta_text} actionLabel={content.primary_cta} actionUrl={`${routes.home}#practice-review`} />
        </>
    );
}
