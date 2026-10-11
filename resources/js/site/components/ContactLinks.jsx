import { useSite } from '../context.js';
import Icon from './Icon.jsx';

export function phoneHref(phone) {
    return `tel:${phone.replace(/[^+\d]/g, '')}`;
}

/**
 * The company phone number and email address as icon links.
 */
export default function ContactLinks({ className = '' }) {
    const { company } = useSite().shared;

    return (
        <ul className={`contact-links ${className}`.trim()}>
            <li>
                <a href={phoneHref(company.phone)}>
                    <span className="icon-circle"><Icon name="phone" /></span>
                    {company.phone}
                </a>
            </li>
            <li>
                <a href={`mailto:${company.email}`}>
                    <span className="icon-circle"><Icon name="mail" /></span>
                    {company.email}
                </a>
            </li>
        </ul>
    );
}
