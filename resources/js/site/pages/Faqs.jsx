import AskBox from '../components/AskBox.jsx';
import FaqAccordion from '../components/FaqAccordion.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';

export default function Faqs({ content, faqs }) {
    return (
        <>
            <PageHeader title="FAQs" crumbs={[{ label: 'FAQs' }]} />
            <section className="section">
                <div className="container">
                    <div className="faq-layout">
                        <div>
                            <SectionTitle eyebrow={content.eyebrow} title={content.title} className="section-intro" />
                            <FaqAccordion items={faqs} />
                        </div>
                        <AskBox title={content.ask_title} text={content.ask_text} />
                    </div>
                </div>
            </section>
        </>
    );
}
