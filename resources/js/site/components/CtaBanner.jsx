import Button from './Button.jsx';
import SplitText from './SplitText.jsx';

export default function CtaBanner({ title, text, actionLabel, actionUrl }) {
    return (
        <section className="section cta-section">
            <div className="container">
                <div className="cta-banner">
                    <div>
                        <SplitText as="h2" text={title} />
                        <p data-animate="fade-up">{text}</p>
                    </div>
                    <div data-animate="fade-up">
                        <Button href={actionUrl} variant="light">{actionLabel}</Button>
                    </div>
                </div>
            </div>
        </section>
    );
}
