import ContactLinks from '../components/ContactLinks.jsx';
import { useSite } from '../context.js';
import Logo from './Logo.jsx';

function LinkColumn({ title, links }) {
    return (
        <div className="footer-links">
            <h2 className="footer-title">{title}</h2>
            <ul>
                {links.map((link) => <li key={link.label}><a href={link.url}>{link.label}</a></li>)}
            </ul>
        </div>
    );
}

export default function Footer() {
    const { company, routes, services, year } = useSite().shared;

    const scrollToTop = (event) => {
        event.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    return (
        <footer className="site-footer">
            <div className="container">
                <div className="footer-main">
                    <div className="footer-about">
                        <Logo href={routes.home} name={company.name} withTagline />
                        <p>{company.summary}</p>
                        <ContactLinks className="footer-contact" />
                    </div>
                    <LinkColumn title="Quick Links" links={[
                        { label: 'About Us', url: routes.about },
                        { label: 'Meet Our Team', url: routes.team },
                        { label: 'Compliance', url: routes.compliance },
                        { label: 'Software', url: routes.software },
                        { label: 'Careers', url: routes.careers },
                        { label: 'Affiliate Program', url: routes.affiliate },
                    ]} />
                    <LinkColumn title="Services" links={services.slice(0, 6).map((service) => ({ label: service.title, url: service.url }))} />
                    <LinkColumn title="Resources" links={[
                        { label: 'Specialities', url: routes.specialities },
                        { label: 'Guides & Checklists', url: routes.resources },
                        { label: 'Blog', url: routes.blog },
                        { label: 'FAQs', url: routes.faqs },
                        { label: 'Contact Us', url: routes.contact },
                    ]} />
                </div>

                <div className="footer-bottom">
                    <p>Copyright © {year} <strong>{company.name}</strong>. All rights reserved.</p>
                    <ul>
                        <li><a href={routes.compliance}>Compliance</a></li>
                        <li><a href={routes.contact}>Contact</a></li>
                        <li className="is-strong"><a href="#top" onClick={scrollToTop}>Go to top</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    );
}
