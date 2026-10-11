import CheckList from '../components/CheckList.jsx';
import ContactForm from '../components/ContactForm.jsx';
import ContactLinks from '../components/ContactLinks.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { useSite } from '../context.js';
import { Rich } from '../text.jsx';

export default function Compliance({ content }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title="Compliance" crumbs={[{ label: 'Resources', url: routes.resources }, { label: 'Compliance' }]} />
            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={content.eyebrow} title={content.title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up"><Rich text={content.intro} /></p>
                    <div className="card-grid three">
                        {content.sections.map((item, index) => (
                            <article className="value-card compliance-card" key={item.title} data-animate="fade-up" style={{ '--delay': `${(index % 3) * 0.12}s` }}>
                                <span className="value-icon"><Icon name={item.icon} /></span>
                                <h3>{item.title}</h3>
                                <p>{item.text}</p>
                                <CheckList items={item.points} compact />
                            </article>
                        ))}
                    </div>
                </div>
            </section>
            <section className="section surface">
                <div className="container">
                    <div className="contact-layout">
                        <div className="contact-intro">
                            <SectionTitle eyebrow="Compliance Team" title={content.form_title} />
                            <p className="lead-text" data-animate="fade-up">{content.form_text}</p>
                            <ContactLinks />
                        </div>
                        <div className="contact-form-box" data-animate="fade-up">
                            <ContactForm subject={content.subject} />
                        </div>
                    </div>
                </div>
            </section>
        </>
    );
}
