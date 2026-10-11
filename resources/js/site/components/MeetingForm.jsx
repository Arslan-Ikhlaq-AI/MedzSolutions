import { useSite } from '../context.js';
import { useFormFeedback } from '../forms.js';
import { Highlight } from '../text.jsx';
import Button from './Button.jsx';
import FormField, { FormMeta, FormStatus } from './FormField.jsx';

function today() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());

    return now.toISOString().slice(0, 10);
}

export default function MeetingForm({ content, specialityNames }) {
    const { routes } = useSite().shared;
    const { formRef, errors, old, status, csrfToken } = useFormFeedback('meeting');

    return (
        <form className="site-form form-card" method="post" action={routes.consultations} ref={formRef} data-animate="fade-up">
            <FormMeta formKey="meeting" csrfToken={csrfToken} />
            <input type="hidden" name="type" value="meeting" />
            <h3 className="form-card-title"><Highlight text={content.title} /></h3>
            <p className="form-card-text">{content.text}</p>
            <FormStatus status={status} />
            <div className="form-grid">
                <FormField form="meeting" label="Full name" name="name" error={errors.name} defaultValue={old.name} autoComplete="name" required />
                <FormField form="meeting" label="Email address" name="email" type="email" error={errors.email} defaultValue={old.email} autoComplete="email" required />
                <FormField form="meeting" label="Phone number" name="phone" type="tel" error={errors.phone} defaultValue={old.phone} autoComplete="tel" />
                <FormField form="meeting" label="Practice name" name="practice_name" error={errors.practice_name} defaultValue={old.practice_name} autoComplete="organization" />
                <FormField form="meeting" label="Specialty" name="specialty" as="select" options={specialityNames} error={errors.specialty} defaultValue={old.specialty} />
                <FormField form="meeting" label="Preferred date" name="preferred_date" type="date" min={today()} error={errors.preferred_date} defaultValue={old.preferred_date} required />
            </div>
            <Button type="submit">{content.button}</Button>
        </form>
    );
}
