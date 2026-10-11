import Footer from './Footer.jsx';
import Header from './Header.jsx';
import { usePreloader, useScrollAnimations } from './hooks.js';
import FloatingContact from './FloatingContact.jsx';
import MagicCursor from './MagicCursor.jsx';

export default function Layout({ children }) {
    const isReady = usePreloader();
    useScrollAnimations(isReady);

    return (
        <>
            <a className="skip-link" href="#main">Skip to content</a>
            <MagicCursor />
            <Header />
            <main id="main">{children}</main>
            <Footer />
            <FloatingContact />
        </>
    );
}
