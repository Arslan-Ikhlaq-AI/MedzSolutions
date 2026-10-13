import { phoneHref } from '../components/ContactLinks.jsx';
import Icon from '../components/Icon.jsx';
import { useSite } from '../context.js';

/**
 * Small email and phone buttons fixed to the right edge of the screen.
 */
export default function FloatingContact() {
    const { company } = useSite().shared;

    return (
        <>
            <a className="whatsapp-button" href={company.whatsapp_url} target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp" title="Chat with us on WhatsApp">
                <svg viewBox="0 0 32 32" aria-hidden="true" focusable="false">
                    <path fill="currentColor" d="M16.04 3C9.4 3 4 8.38 4 15c0 2.35.69 4.55 1.88 6.4L4 29l7.77-1.83A12.04 12.04 0 0 0 16.04 27C22.68 27 28 21.62 28 15S22.68 3 16.04 3Zm0 21.9c-1.5 0-2.9-.4-4.1-1.1l-.3-.18-3.5.82.84-3.4-.2-.33A9.8 9.8 0 0 1 6.2 15c0-5.4 4.4-9.8 9.84-9.8 5.4 0 9.76 4.4 9.76 9.8s-4.36 9.9-9.76 9.9Zm5.4-7.35c-.3-.15-1.75-.86-2.02-.96-.27-.1-.47-.15-.67.15-.2.3-.77.96-.94 1.16-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.88-.79-1.48-1.76-1.65-2.06-.17-.3-.02-.46.13-.6.13-.14.3-.35.45-.52.15-.18.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.5h-.57c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.48 0 1.46 1.07 2.88 1.22 3.08.15.2 2.1 3.2 5.08 4.49.7.3 1.26.48 1.7.62.72.23 1.37.2 1.88.12.57-.08 1.75-.72 2-1.4.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35Z"/>
                </svg>
            </a>
            <div className="floating-contact">
                <a href={`mailto:${company.email}`} aria-label={`Email ${company.name}`}><Icon name="mail" /></a>
                <a href={phoneHref(company.phone)} aria-label={`Call ${company.name}`}><Icon name="phone" /></a>
            </div>
        </>
    );
}
