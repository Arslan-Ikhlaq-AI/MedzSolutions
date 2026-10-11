import ContactForm from '../components/ContactForm.jsx';
import ContactLinks from '../components/ContactLinks.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { SoftwareTile } from '../components/SoftwareMarquee.jsx';
import { useSite } from '../context.js';

function SoftwareGroup({ title, names }) {
    return (
        <div className="software-group" data-animate="fade-up">
            <h3 className="software-group-title">{title}</h3>
            <ul className="software-grid">
                {names.map((name) => <li key={name}><SoftwareTile name={name} /></li>)}
            </ul>
        </div>
    );
}

export default function Software({ content, software }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title="Software" crumbs={[{ label: 'Resources', url: routes.resources }, { label: 'Software' }]} />
            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={content.eyebrow} title={content.title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up">{content.text}</p>
                    <SoftwareGroup title={content.ehr_title} names={software.ehr} />
                    <SoftwareGroup title={content.clearinghouse_title} names={software.clearinghouses} />
                    <p className="software-note" data-animate="fade-up">{content.note}</p>
                </div>
            </section>
            <section className="section surface">
                <div className="container">
                    <div className="contact-layout">
                        <div className="contact-intro">
                            <SectionTitle eyebrow="Get In Touch" title={content.form_title} />
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
