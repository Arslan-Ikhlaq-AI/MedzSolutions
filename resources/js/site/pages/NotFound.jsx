import Button from '../components/Button.jsx';
import SplitText from '../components/SplitText.jsx';
import { useSite } from '../context.js';

export default function NotFound() {
    const { routes } = useSite().shared;

    return (
        <section className="not-found">
            <div className="container">
                <p className="not-found-code" aria-hidden="true">404</p>
                <SplitText as="h1" text="Oops! This Page *Wandered Off*" />
                <p className="lead-text" data-animate="fade-up">The page you are looking for may have moved or no longer exists. Let&rsquo;s get you back on track.</p>
                <div data-animate="fade-up">
                    <Button href={routes.home}>Back To Home</Button>
                </div>
            </div>
        </section>
    );
}
