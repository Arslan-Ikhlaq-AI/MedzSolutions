import { useId, useState } from 'react';
import Icon from './Icon.jsx';

export default function FaqAccordion({ items }) {
    const [openIndex, setOpenIndex] = useState(0);
    const baseId = useId();

    return (
        <div className="faq-accordion">
            {items.map((item, index) => {
                const isOpen = openIndex === index;
                const buttonId = `${baseId}-question-${index}`;
                const panelId = `${baseId}-answer-${index}`;

                return (
                    <div className={`faq-item${isOpen ? ' is-open' : ''}`} key={item.question} data-animate="fade-up" style={{ '--delay': `${Math.min(index, 4) * 0.08}s` }}>
                        <h3 className="faq-question">
                            <button type="button" id={buttonId} aria-expanded={isOpen} aria-controls={panelId} onClick={() => setOpenIndex(isOpen ? null : index)}>
                                <span>{item.question}</span>
                                <span className="faq-icon"><Icon name="chevron" /></span>
                            </button>
                        </h3>
                        <div className="faq-answer" id={panelId} role="region" aria-labelledby={buttonId}>
                            <div className="faq-answer-inner">
                                <p>{item.answer}</p>
                            </div>
                        </div>
                    </div>
                );
            })}
        </div>
    );
}
