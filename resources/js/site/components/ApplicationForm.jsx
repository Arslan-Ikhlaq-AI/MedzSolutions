import { useFormFeedback } from '../forms.js';
import { Highlight } from '../text.jsx';
import Button from './Button.jsx';
import FormField, { FormMeta, FormStatus } from './FormField.jsx';

export default function ApplicationForm({ action, title, text }) {
    const { formRef, errors, old, status, csrfToken } = useFormFeedback('application');

    return (
        <form className="site-form form-card" method="post" action={action} encType="multipart/form-data" ref={formRef} id="apply">
            <FormMeta formKey="application" csrfToken={csrfToken} />
            <h2 className="form-card-title"><Highlight text={title} /></h2>
            <p className="form-card-text">{text}</p>
            <FormStatus status={status} />
            <div className="form-grid">
                <FormField form="application" label="Full name" name="name" error={errors.name} defaultValue={old.name} autoComplete="name" required />
                <FormField form="application" label="Email address" name="email" type="email" error={errors.email} defaultValue={old.email} autoComplete="email" required />
                <FormField form="application" label="Phone number" name="phone" type="tel" error={errors.phone} defaultValue={old.phone} autoComplete="tel" />
                <FormField form="application" label="LinkedIn profile URL" name="linkedin_url" type="url" error={errors.linkedin_url} defaultValue={old.linkedin_url} />
                <FormField form="application" label="Cover letter (optional)" name="cover_letter" as="textarea" error={errors.cover_letter} defaultValue={old.cover_letter} wide />
                <FormField form="application" label="Your CV" name="resume" type="file" accept=".pdf,.doc,.docx" error={errors.resume} wide required />
            </div>
            <Button type="submit">Submit Application</Button>
        </form>
    );
}
