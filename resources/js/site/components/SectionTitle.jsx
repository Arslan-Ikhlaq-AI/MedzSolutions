import SplitText from './SplitText.jsx';

export default function SectionTitle({ eyebrow, title, as = 'h2', className = '' }) {
    return (
        <div className={`section-title ${className}`.trim()}>
            {eyebrow && <p className="section-eyebrow" data-animate="fade-up">{eyebrow}</p>}
            <SplitText as={as} text={title} />
        </div>
    );
}
