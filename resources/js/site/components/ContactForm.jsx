import { useSite } from '../context.js';
import { useFormFeedback } from '../forms.js';
import Button from './Button.jsx';
import FormField, { FormMeta, FormStatus } from './FormField.jsx';

export default function ContactForm({ subject = '' }) {
    const { routes } = useSite().shared;
    const { formRef, errors, old, status, csrfToken } = useFormFeedback('contact');

    return (
        <form className="site-form" method="post" action={routes.contactStore} ref={formRef}>
            <FormMeta formKey="contact" csrfToken={csrfToken} />
            <FormStatus status={status} />
            <div className="form-grid">
                <FormField form="contact" label="Full name" name="name" error={errors.name} defaultValue={old.name} autoComplete="name" required />
                <FormField form="contact" label="Email address" name="email" type="email" error={errors.email} defaultValue={old.email} autoComplete="email" required />
                <FormField form="contact" label="Phone number" name="phone" type="tel" error={errors.phone} defaultValue={old.phone} autoComplete="tel" />
                <FormField form="contact" label="Subject" name="subject" error={errors.subject} defaultValue={old.subject ?? subject} />
                <FormField form="contact" label="How can we help your practice?" name="message" as="textarea" error={errors.message} defaultValue={old.message} wide required />
            </div>
            <Button type="submit">Send Message</Button>
        </form>
    );
}
