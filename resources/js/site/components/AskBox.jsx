import { useSite } from '../context.js';
import Button from './Button.jsx';
import ContactLinks from './ContactLinks.jsx';
import Media from './Media.jsx';

export default function AskBox({ title, text }) {
    const { routes } = useSite().shared;

    return (
        <aside className="ask-box" data-animate="fade-up">
            <Media kind="support" seed="ask-a-question" tone={2} ratio="16 / 9" />
            <div className="ask-box-body">
                <h3>{title}</h3>
                <p>{text}</p>
                <ContactLinks />
                <Button href={routes.contact} variant="light">Contact Us</Button>
            </div>
        </aside>
    );
}
