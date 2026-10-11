import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import ServiceCard from '../components/ServiceCard.jsx';

export default function ServicesIndex({ content, services }) {
    return (
        <>
            <PageHeader title="Services" crumbs={[{ label: 'Services' }]} />
            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={content.eyebrow} title={content.title} className="is-centered narrow section-intro" />
                    <div className="card-grid three">
                        {services.map((service, index) => <ServiceCard key={service.slug} service={service} delay={(index % 3) * 0.12} />)}
                    </div>
                </div>
            </section>
        </>
    );
}
