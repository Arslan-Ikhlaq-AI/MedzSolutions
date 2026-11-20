import Button from '../components/Button.jsx';
import ContactLinks from '../components/ContactLinks.jsx';
import CtaBanner from '../components/CtaBanner.jsx';
import FaqAccordion from '../components/FaqAccordion.jsx';
import OnboardingSteps from '../components/OnboardingSteps.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import ServiceCard from '../components/ServiceCard.jsx';
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
                    <div className="card-grid three">
                        {services.map((service, index) => (
                            <ServiceCard key={service.slug} service={service} number={index + 1} delay={(index % 3) * 0.08} />
                        ))}
                    </div>
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
