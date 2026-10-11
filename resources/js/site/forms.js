import { useEffect, useRef } from 'react';
import { useSite } from './context.js';

/**
 * Errors, previous input and the success message that belong to one form on the page.
 * Every form posts a hidden "_form" key, so feedback never leaks into a neighbouring form.
 */
export function useFormFeedback(formKey) {
    const { flash, csrfToken } = useSite();
    const formRef = useRef(null);
    const ownsFeedback = flash.old?._form === formKey;
    const errors = ownsFeedback ? flash.errors ?? {} : {};
    const old = ownsFeedback ? flash.old : {};
    const status = flash.form === formKey ? flash.status : null;
    const hasFeedback = Boolean(status) || Object.keys(errors).length > 0;

    useEffect(() => {
        if (hasFeedback) {
            formRef.current?.scrollIntoView({ block: 'center' });
        }
    }, [hasFeedback]);

    return { formRef, errors, old, status, csrfToken };
}
