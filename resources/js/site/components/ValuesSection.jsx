import Icon from './Icon.jsx';
import SectionTitle from './SectionTitle.jsx';

export default function ValuesSection({ values }) {
    return (
        <section className="section values-section surface">
            <div className="container">
                <SectionTitle eyebrow={values.eyebrow} title={values.title} className="is-centered narrow section-intro" />
                <div className="card-grid three">
                    {values.items.map((item, index) => (
                        <article className="value-card" key={item.title} data-animate="fade-up" style={{ '--delay': `${(index % 3) * 0.12}s` }}>
                            <span className="value-icon"><Icon name={item.icon} /></span>
                            <h3>{item.title}</h3>
                            <p>{item.text}</p>
                        </article>
                    ))}
                </div>
            </div>
        </section>
    );
}
