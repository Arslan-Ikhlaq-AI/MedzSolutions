import { useFormFeedback } from '../forms.js';
import Button from './Button.jsx';
import FormField, { FormMeta, FormStatus } from './FormField.jsx';

export default function AffiliateForm({ action, partnerTypes, partnerType, onPartnerTypeChange }) {
    const { formRef, errors, old, status, csrfToken } = useFormFeedback('affiliate');

    return (
        <form className="site-form" method="post" action={action} ref={formRef}>
            <FormMeta formKey="affiliate" csrfToken={csrfToken} />
            <FormStatus status={status} />
            <div className="form-grid">
                <FormField form="affiliate" label="Full name" name="name" error={errors.name} defaultValue={old.name} autoComplete="name" required />
                <FormField form="affiliate" label="Email address" name="email" type="email" error={errors.email} defaultValue={old.email} autoComplete="email" required />
                <FormField form="affiliate" label="Phone number" name="phone" type="tel" error={errors.phone} defaultValue={old.phone} autoComplete="tel" />
                <FormField form="affiliate" label="Company or organisation" name="company" error={errors.company} defaultValue={old.company} autoComplete="organization" />
                <FormField
                    form="affiliate"
                    label="Partnership type"
                    name="partner_type"
                    as="select"
                    options={partnerTypes}
                    value={partnerType}
                    onChange={(event) => onPartnerTypeChange(event.target.value)}
                    error={errors.partner_type}
                    wide
                    required
                />
                <FormField form="affiliate" label="Website (https://…)" name="website" type="url" error={errors.website} defaultValue={old.website} wide />
                <FormField form="affiliate" label="Tell us how you work with healthcare practices" name="audience" as="textarea" error={errors.audience} defaultValue={old.audience} wide />
            </div>
            <Button type="submit">Send Application</Button>
        </form>
    );
}
