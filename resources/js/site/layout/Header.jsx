import { useEffect, useRef, useState } from 'react';
import Button from '../components/Button.jsx';
import { phoneHref } from '../components/ContactLinks.jsx';
import Icon from '../components/Icon.jsx';
import { useSite } from '../context.js';
import Logo from './Logo.jsx';

const fixedAfterScroll = 600;

function TopBar({ company }) {
    return (
        <div className="top-bar">
            <div className="container top-bar-inner">
                <ul>
                    <li><Icon name="clock" />{company.hours[0].days}: {company.hours[0].time}</li>
                    <li><Icon name="mail" /><a href={`mailto:${company.email}`}>{company.email}</a></li>
                </ul>
                <a className="top-bar-phone" href={phoneHref(company.phone)}><Icon name="phone" />Call us: {company.phone}</a>
            </div>
        </div>
    );
}

export default function Header() {
    const { shared } = useSite();
    const { company, routes, services, currentUrl } = shared;
    const barRef = useRef(null);
    const [isFixed, setIsFixed] = useState(false);
    const [barHeight, setBarHeight] = useState(null);
    const [isMenuOpen, setIsMenuOpen] = useState(false);
    const [openSubmenu, setOpenSubmenu] = useState(null);

    useEffect(() => {
        const measure = () => {
            if (!barRef.current.classList.contains('is-fixed')) {
                setBarHeight(barRef.current.offsetHeight);
            }
        };
        const onScroll = () => setIsFixed(window.scrollY > fixedAfterScroll);

        measure();
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', measure);

        return () => {
            window.removeEventListener('scroll', onScroll);
            window.removeEventListener('resize', measure);
        };
    }, []);

    useEffect(() => {
        if (!isMenuOpen) {
            return undefined;
        }

        const onKeyDown = (event) => event.key === 'Escape' && setIsMenuOpen(false);
        document.addEventListener('keydown', onKeyDown);

        return () => document.removeEventListener('keydown', onKeyDown);
    }, [isMenuOpen]);

    const isActive = (url) => (url === routes.home ? currentUrl === url : currentUrl === url || currentUrl.startsWith(`${url}/`));
    const links = [
        { label: 'Home', url: routes.home },
        { label: 'About Us', url: routes.about, children: [
            { title: `About ${company.name}`, url: routes.about },
            { title: 'Meet Our Team', url: routes.team },
        ] },
        { label: 'Services', url: routes.services, children: [{ title: 'All Services', url: routes.services }, ...services] },
        { label: 'Resources', url: routes.resources, children: [
            { title: 'Resources', url: routes.resources },
            { title: 'Compliance', url: routes.compliance },
            { title: 'Software', url: routes.software },
            { title: 'Specialities', url: routes.specialities },
            { title: 'Blog', url: routes.blog },
        ] },
        { label: 'Affiliate Program', url: routes.affiliate },
        { label: 'Careers', url: routes.careers },
        { label: 'Contact', url: routes.contact },
    ];
    const isLinkActive = (link) => isActive(link.url) || Boolean(link.children?.some((child) => isActive(child.url)));
    const toggleSubmenu = (label) => setOpenSubmenu((open) => (open === label ? null : label));

    return (
        <header className="site-header" id="top">
            <TopBar company={company} />
            <div className="header-bar-slot" style={{ minHeight: barHeight ?? undefined }}>
                <div className={`header-bar${isFixed ? ' is-fixed' : ''}`} ref={barRef}>
                    <div className="container header-inner">
                        <Logo href={routes.home} name={company.name} />

                        <nav className="main-nav" aria-label="Main">
                            <ul>
                                {links.map((link) => (
                                    <li key={link.label} className={link.children ? 'has-submenu' : undefined}>
                                        <a href={link.url} className={isLinkActive(link) ? 'is-active' : undefined} aria-current={currentUrl === link.url ? 'page' : undefined}>
                                            {link.label}
                                            {link.children && <Icon name="chevron" />}
                                        </a>
                                        {link.children && (
                                            <ul className="submenu">
                                                {link.children.map((child) => (
                                                    <li key={child.title}>
                                                        <a href={child.url} className={currentUrl === child.url ? 'is-active' : undefined}>{child.title}</a>
                                                    </li>
                                                ))}
                                            </ul>
                                        )}
                                    </li>
                                ))}
                            </ul>
                        </nav>

                        <div className="header-actions">
                            <Button href={`${routes.home}#practice-review`} className="header-cta">Get Started</Button>
                            <button
                                type="button"
                                className={`nav-toggle${isMenuOpen ? ' is-open' : ''}`}
                                aria-expanded={isMenuOpen}
                                aria-controls="mobile-menu"
                                onClick={() => setIsMenuOpen((open) => !open)}
                            >
                                <span className="sr-only">{isMenuOpen ? 'Close menu' : 'Open menu'}</span>
                                <span className="nav-toggle-bar" />
                                <span className="nav-toggle-bar" />
                                <span className="nav-toggle-bar" />
                            </button>
                        </div>
                    </div>

                    <nav id="mobile-menu" className={`mobile-menu${isMenuOpen ? ' is-open' : ''}`} aria-label="Mobile">
                        <ul>
                            {links.map((link) => {
                                const submenuId = `mobile-${link.label.toLowerCase().replace(/\s+/g, '-')}`;
                                const isOpen = openSubmenu === link.label;

                                return (
                                    <li key={link.label}>
                                        {link.children ? (
                                            <>
                                                <div className="mobile-menu-row">
                                                    <a href={link.url} className={isLinkActive(link) ? 'is-active' : undefined}>{link.label}</a>
                                                    <button
                                                        type="button"
                                                        className={`submenu-toggle${isOpen ? ' is-open' : ''}`}
                                                        aria-expanded={isOpen}
                                                        aria-controls={submenuId}
                                                        aria-label={`${isOpen ? 'Hide' : 'Show'} ${link.label} links`}
                                                        onClick={() => toggleSubmenu(link.label)}
                                                    >
                                                        <Icon name="chevron" />
                                                    </button>
                                                </div>
                                                <ul id={submenuId} className={`mobile-submenu${isOpen ? ' is-open' : ''}`}>
                                                    {link.children.map((child) => (
                                                        <li key={child.title}><a href={child.url} className={currentUrl === child.url ? 'is-active' : undefined}>{child.title}</a></li>
                                                    ))}
                                                </ul>
                                            </>
                                        ) : (
                                            <a href={link.url} className={isLinkActive(link) ? 'is-active' : undefined}>{link.label}</a>
                                        )}
                                    </li>
                                );
                            })}
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
    );
}
