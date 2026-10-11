import CtaBanner from '../components/CtaBanner.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { useSite } from '../context.js';

export default function Team({ content }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title="Meet Our Team" crumbs={[{ label: 'About Us', url: routes.about }, { label: 'Meet Our Team' }]} />
            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={content.eyebrow} title={content.title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up">{content.text}</p>
                    <div className="card-grid three">
                        {content.departments.map((department, index) => (
                            <article className="value-card team-card" key={department.title} data-animate="fade-up" style={{ '--delay': `${(index % 3) * 0.12}s` }}>
                                <span className="value-icon"><Icon name={department.icon} /></span>
                                <h3>{department.title}</h3>
                                <p>{department.text}</p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>
            <CtaBanner title={content.join_title} text={content.join_text} actionLabel="View Open Positions" actionUrl={routes.careers} />
        </>
    );
}
