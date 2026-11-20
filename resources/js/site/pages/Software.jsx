import ContactForm from '../components/ContactForm.jsx';
import ContactLinks from '../components/ContactLinks.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { SoftwareTile } from '../components/SoftwareMarquee.jsx';
import WhyBand from '../components/WhyBand.jsx';
import { useSite } from '../context.js';

function SoftwareGroup({ icon, title, text, names }) {
    return (
        <div className="software-group" data-animate="fade-up">
            <div className="software-group-head">
                <span className="service-icon"><Icon name={icon} /></span>
                <div>
                    <h3 className="software-group-title">{title}</h3>
                    <p>{text}</p>
                </div>
                <span className="software-group-count">{names.length} platforms</span>
            </div>
            <ul className="software-grid">
                {names.map((name) => <li key={name}><SoftwareTile name={name} /></li>)}
            </ul>
        </div>
    );
}

export default function Software({ content, headerImage, software }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader
                title="Software"
                image={headerImage}
                imagePosition="center 70%"
                compact
                crumbs={[{ label: 'Resources', url: routes.resources }, { label: 'Software' }]}
            />

            <section className="section">
                <div className="container">
                    <SectionHeading title={content.title} text={content.text} />
                    <SoftwareGroup icon="layers" title={content.ehr_title} text={content.ehr_text} names={software.ehr} />
                    <SoftwareGroup icon="cycle" title={content.clearinghouse_title} text={content.clearinghouse_text} names={software.clearinghouses} />
                    <p className="software-note" data-animate="fade-up">
                        <Icon name="chat" />
                        <span>{content.note}</span>
                        <a className="text-link" href="#software-question">Ask about yours <Icon name="arrow" /></a>
                    </p>
                </div>
            </section>

            <WhyBand title={content.how.title} text={content.how.text} tiles={content.how.tiles} />

            <section className="section" id="software-question">
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
