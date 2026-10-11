import { useState } from 'react';
import Icon from './Icon.jsx';

export function FormMeta({ formKey, csrfToken }) {
    return (
        <>
            <input type="hidden" name="_token" value={csrfToken} />
            <input type="hidden" name="_form" value={formKey} />
        </>
    );
}

export function FormStatus({ status }) {
    return status ? <p className="form-status" role="status">{status}</p> : null;
}

function FileInput({ id, name, accept, describedBy, invalid, required }) {
    const [fileName, setFileName] = useState('');

    return (
        <label className={`file-input${invalid ? ' is-invalid' : ''}`} htmlFor={id}>
            <input
                id={id}
                name={name}
                type="file"
                accept={accept}
                required={required}
                aria-invalid={invalid || undefined}
                aria-describedby={describedBy}
                onChange={(event) => setFileName(event.target.files[0]?.name ?? '')}
            />
            <span className="icon-circle"><Icon name="upload" /></span>
            <span className="file-input-text">{fileName || 'Choose a file (PDF, DOC or DOCX, up to 5 MB)'}</span>
        </label>
    );
}

/**
 * A labelled form control that shows its validation error underneath.
 * Text inputs use the label as a placeholder; dates, selects and files show it above.
 */
export default function FormField({ form, label, name, type = 'text', as = 'input', error, defaultValue, wide = false, options = [], ...props }) {
    const id = `${form}-${name}`;
    const message = Array.isArray(error) ? error[0] : error;
    const describedBy = message ? `${id}-error` : undefined;
    const showLabel = as === 'select' || type === 'date' || type === 'file';
    const shared = {
        id,
        name,
        'aria-invalid': message ? true : undefined,
        'aria-describedby': describedBy,
        ...props,
    };

    let control;

    if (type === 'file') {
        control = <FileInput id={id} name={name} accept={props.accept} describedBy={describedBy} invalid={Boolean(message)} required={props.required} />;
    } else if (as === 'textarea') {
        control = <textarea rows={5} placeholder={label} defaultValue={defaultValue} {...shared} />;
    } else if (as === 'select') {
        control = (
            <select {...(props.value === undefined ? { defaultValue: defaultValue ?? '' } : {})} {...shared}>
                <option value="">Select…</option>
                {options.map((option) => <option key={option} value={option}>{option}</option>)}
            </select>
        );
    } else {
        control = <input type={type} placeholder={label} defaultValue={defaultValue} {...shared} />;
    }

    return (
        <div className={`form-field${wide ? ' is-wide' : ''}`}>
            <label className={showLabel ? 'field-label' : 'sr-only'} htmlFor={id}>{label}</label>
            {control}
            {message && <span className="field-error" id={`${id}-error`}>{message}</span>}
        </div>
    );
}
