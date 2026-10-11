import SplitText from './SplitText.jsx';

/**
 * A plain section heading with a small gold ornament and optional intro line.
 */
export default function SectionHeading({ title, text, align = 'center', light = false }) {
    return (
        <div className={`section-heading is-${align}${light ? ' is-light' : ''}`}>
            <SplitText as="h2" text={title} />
            <span className="heading-ornament" aria-hidden="true" />
            {text && <p data-animate="fade-up">{text}</p>}
        </div>
    );
}
