import { useId, useState } from 'react';
import AffiliateForm from '../components/AffiliateForm.jsx';
import AskBox from '../components/AskBox.jsx';
import Button from '../components/Button.jsx';
import CheckList from '../components/CheckList.jsx';
import ContactLinks from '../components/ContactLinks.jsx';
import FaqAccordion from '../components/FaqAccordion.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { useFormFeedback } from '../forms.js';

function PartnerTracks({ content, onApply }) {
    const [activeIndex, setActiveIndex] = useState(0);
    const baseId = useId();
    const track = content.tracks[activeIndex];

    const onKeyDown = (event) => {
        const step = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 }[event.key];

        if (step) {
            event.preventDefault();
            setActiveIndex((activeIndex + step + content.tracks.length) % content.tracks.length);
        }
    };

    return (
        <div className="tracks">
            <div className="track-tabs" role="tablist" aria-orientation="vertical" onKeyDown={onKeyDown}>
                {content.tracks.map((item, index) => (
                    <button
                        type="button"
                        role="tab"
                        key={item.title}
                        id={`${baseId}-tab-${index}`}
                        aria-selected={index === activeIndex}
                        aria-controls={`${baseId}-panel`}
                        tabIndex={index === activeIndex ? 0 : -1}
                        className={`track-tab${index === activeIndex ? ' is-active' : ''}`}
                        onClick={() => setActiveIndex(index)}
                    >
                        <span className="track-tab-label">{item.label}</span>
                        <span className="track-tab-title">{item.title}</span>
                    </button>
                ))}
            </div>
            <div className="track-panel" role="tabpanel" id={`${baseId}-panel`} aria-labelledby={`${baseId}-tab-${activeIndex}`} key={track.title}>
                <h3>{track.title}</h3>
                <p className="track-summary">{track.summary}</p>
                <dl className="track-details">
                    <div><dt>{content.track_labels[0]}</dt><dd>{track.who}</dd></div>
                    <div><dt>{content.track_labels[1]}</dt><dd>{track.how}</dd></div>
                </dl>
                <h4>{content.track_labels[2]}</h4>
                <CheckList items={track.benefits} compact />
                <Button href="#affiliate-form" onClick={() => onApply(track.title)}>{track.cta}</Button>
            </div>
        </div>
    );
}

export default function Affiliate({ content, headerImage, programImage, action }) {
    const { old } = useFormFeedback('affiliate');
    const partnerTypes = content.tracks.map((track) => track.title);
    const [partnerType, setPartnerType] = useState(old.partner_type ?? '');
    const { why, program } = content;

    return (
        <>
            <PageHeader title={content.header_title} text={content.header_text} image={headerImage} actions={content.actions} crumbs={[{ label: 'Affiliate Program' }]} />

            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={why.eyebrow} title={why.title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up">{why.text}</p>
                    <div className="card-grid three">
                        {why.items.map((item, index) => (
                            <article className="value-card" key={item.title} data-animate="fade-up" style={{ '--delay': `${(index % 3) * 0.1}s` }}>
                                <span className="value-icon"><Icon name={item.icon} /></span>
                                <h3>{item.title}</h3>
                                <p>{item.text}</p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>

            <section className="section surface" id="partner-types">
                <div className="container">
                    <SectionTitle eyebrow={content.tracks_eyebrow} title={content.tracks_title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up">{content.tracks_text}</p>
                    <div data-animate="fade-up">
                        <PartnerTracks content={content} onApply={setPartnerType} />
                    </div>
                </div>
            </section>

            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={content.steps_eyebrow} title={content.steps_title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up">{content.steps_text}</p>
                    <ol className="step-row">
                        {content.steps.map((step, index) => (
                            <li className="step-item" key={step.title} data-animate="fade-up" style={{ '--delay': `${index * 0.12}s` }}>
                                <span className="step-badge"><Icon name={step.icon} /></span>
                                <span className="step-count" aria-hidden="true">{String(index + 1).padStart(2, '0')}</span>
                                <h3>{step.title}</h3>
                                <p>{step.text}</p>
                            </li>
                        ))}
                    </ol>
                </div>
            </section>

            <section className="section surface">
                <div className="container">
                    <div className="program-grid">
                        <figure className="careers-photo program-photo" data-animate="reveal">
                            <div className="media-inner">
                                <img src={programImage} alt={program.image_alt} loading="lazy" width="960" height="640" />
                            </div>
                        </figure>
                        <div>
                            <SectionTitle eyebrow={program.eyebrow} title={program.title} />
                            {program.paragraphs.map((paragraph) => <p className="lead-text" key={paragraph} data-animate="fade-up">{paragraph}</p>)}
                            <CheckList items={program.points} />
                            <aside className="commission-box" data-animate="fade-up">
                                <h3>{program.commission_title}</h3>
                                <p>{program.commission_text}</p>
                                <CheckList items={program.commission_points} compact />
                            </aside>
                        </div>
                    </div>
                </div>
            </section>

            <section className="section" id="affiliate-form">
                <div className="container">
                    <div className="contact-layout">
                        <div className="contact-intro">
                            <SectionTitle eyebrow={content.form_eyebrow} title={content.form_title} />
                            <p className="lead-text" data-animate="fade-up">{content.form_text}</p>
                            <ContactLinks />
                        </div>
                        <div className="contact-form-box" data-animate="fade-up">
                            <AffiliateForm action={action} partnerTypes={partnerTypes} partnerType={partnerType} onPartnerTypeChange={setPartnerType} />
                        </div>
                    </div>
                </div>
            </section>

            <section className="section surface">
                <div className="container">
                    <div className="faq-layout">
                        <div>
                            <SectionTitle eyebrow={content.faq_eyebrow} title={content.faq_title} className="section-intro" />
                            <FaqAccordion items={content.faqs} />
                        </div>
                        <AskBox title="Questions about partnering?" text="Our partnership team will happily explain how the program works and whether it is a good fit." />
                    </div>
                </div>
            </section>
        </>
    );
}
