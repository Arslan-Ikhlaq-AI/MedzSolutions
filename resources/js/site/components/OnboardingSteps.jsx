import SectionHeading from './SectionHeading.jsx';

/**
 * Numbered onboarding steps joined by a dashed line, as on the home page.
 */
export default function OnboardingSteps({ title, text, steps }) {
    return (
        <section className="section onboarding-section">
            <div className="container">
                <SectionHeading title={title} text={text} />
                <ol className="onboard-steps">
                    {steps.map((step, index) => (
                        <li className="onboard-step" key={step.title} data-animate="fade-up" style={{ '--delay': `${index * 0.1}s` }}>
                            <span className="onboard-number">{index + 1}</span>
                            <h3>{step.title}</h3>
                            <p>{step.text}</p>
                        </li>
                    ))}
                </ol>
            </div>
        </section>
    );
}
