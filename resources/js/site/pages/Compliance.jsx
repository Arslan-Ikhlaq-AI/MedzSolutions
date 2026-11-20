import CheckList from '../components/CheckList.jsx';
import ContactForm from '../components/ContactForm.jsx';
import ContactLinks from '../components/ContactLinks.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { useSite } from '../context.js';
import { Rich } from '../text.jsx';

export default function Compliance({ content, headerImage }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader
                title="Compliance"
                image={headerImage}
                imagePosition="center 60%"
                compact
                crumbs={[{ label: 'Resources', url: routes.resources }, { label: 'Compliance' }]}
            />

            <section className="section">
                <div className="container">
                    <SectionHeading title={content.title} text={<Rich text={content.intro} />} />
                    <ul className="compliance-highlights">
                        {content.highlights.map((highlight, index) => (
                            <li key={highlight.label} data-animate="fade-up" style={{ '--delay': `${index * 0.08}s` }}>
                                <span className="compliance-highlight-icon"><Icon name={highlight.icon} /></span>
                                {highlight.label}
                            </li>
                        ))}
                    </ul>
                    <div className="card-grid three">
                        {content.sections.map((item, index) => (
                            <article className="compliance-card" key={item.title} data-animate="fade-up" style={{ '--delay': `${(index % 3) * 0.08}s` }}>
                                <span className="service-icon"><Icon name={item.icon} /></span>
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
