import { phoneHref } from '../components/ContactLinks.jsx';
import Icon from '../components/Icon.jsx';
import { useSite } from '../context.js';

/**
 * Small email and phone buttons fixed to the right edge of the screen.
 */
export default function FloatingContact() {
    const { company } = useSite().shared;

    return (
        <div className="floating-contact">
            <a href={`mailto:${company.email}`} aria-label={`Email ${company.name}`}><Icon name="mail" /></a>
            <a href={phoneHref(company.phone)} aria-label={`Call ${company.name}`}><Icon name="phone" /></a>
        </div>
    );
}
