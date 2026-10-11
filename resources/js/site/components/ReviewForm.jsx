import { useSite } from '../context.js';
import { useFormFeedback } from '../forms.js';
import { Rich } from '../text.jsx';
import Button from './Button.jsx';
import FormField, { FormMeta, FormStatus } from './FormField.jsx';
import Icon from './Icon.jsx';
import SectionTitle from './SectionTitle.jsx';

/**
 * The "free practice review": tick the revenue challenges that apply, then leave contact details.
 */
export default function ReviewForm({ content, challenges }) {
    const { routes } = useSite().shared;
    const { formRef, errors, old, status, csrfToken } = useFormFeedback('review');
    const checked = old.challenges ?? [];
    const challengeError = errors.challenges?.[0] ?? Object.entries(errors).find(([key]) => key.startsWith('challenges.'))?.[1]?.[0];

    return (
        <form className="review-form" method="post" action={routes.consultations} ref={formRef}>
            <FormMeta formKey="review" csrfToken={csrfToken} />
            <input type="hidden" name="type" value="review" />
            <div className="review-layout">
                <div className="review-intro">
                    <SectionTitle eyebrow={content.eyebrow} title={content.title} />
                    <p className="lead-text" data-animate="fade-up"><Rich text={content.text} /></p>
                    <fieldset className="challenge-list" data-animate="fade-up" aria-describedby={challengeError ? 'review-challenges-error' : undefined}>
                        <legend className="sr-only">Revenue challenges</legend>
                        {Object.entries(challenges).map(([key, label]) => (
                            <label className="challenge-option" key={key}>
                                <input type="checkbox" name="challenges[]" value={key} defaultChecked={checked.includes(key)} />
                                <span className="challenge-check"><Icon name="check" /></span>
                                <span>{label}</span>
                            </label>
                        ))}
                    </fieldset>
                    {challengeError && <span className="field-error" id="review-challenges-error">{challengeError}</span>}
                </div>
                <div className="site-form form-card" data-animate="fade-up">
                    <h3 className="form-card-title">Book a Free Consultation</h3>
                    <p className="form-card-text">We will review your answers and contact you within one working day.</p>
                    <FormStatus status={status} />
                    <div className="form-grid single">
                        <FormField form="review" label="Full name" name="name" error={errors.name} defaultValue={old.name} autoComplete="name" required />
                        <FormField form="review" label="Email address" name="email" type="email" error={errors.email} defaultValue={old.email} autoComplete="email" required />
                        <FormField form="review" label="Phone number" name="phone" type="tel" error={errors.phone} defaultValue={old.phone} autoComplete="tel" />
                    </div>
                    <Button type="submit">{content.button}</Button>
                </div>
            </div>
        </form>
    );
}
