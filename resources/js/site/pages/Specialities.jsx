import CtaBanner from '../components/CtaBanner.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import SpecialityGrid from '../components/SpecialityGrid.jsx';
import { useSite } from '../context.js';

export default function Specialities({ content, specialities }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title="Specialities" crumbs={[{ label: 'Resources', url: routes.resources }, { label: 'Specialities' }]} />
            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={content.eyebrow} title={content.title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up">{content.text}</p>
                    <SpecialityGrid specialities={specialities} />
                </div>
            </section>
            <CtaBanner title={content.cta_title} text={content.cta_text} actionLabel="Contact Us" actionUrl={routes.contact} />
        </>
    );
}
