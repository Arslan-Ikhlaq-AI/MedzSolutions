import { useSite } from '../context.js';
import ContactLinks from './ContactLinks.jsx';
import Icon from './Icon.jsx';
import Media from './Media.jsx';
import SectionHeading from './SectionHeading.jsx';

/**
 * Working hours and contact details beside a map (when configured) or artwork.
 */
export default function HoursPanel({ heading }) {
    const { company } = useSite().shared;

    return (
        <section className="section hours-section" id="visit">
            <div className="container">
                <div className="hours-layout">
                    <div className="hours-card" data-animate="fade-up">
                        {heading && <SectionHeading title={heading.title} align="left" />}
                        <h3 className="hours-title"><Icon name="clock" /> Working Hours</h3>
                        <ul className="hours-list">
                            {company.hours.map((row) => (
                                <li key={row.days}><span>{row.days}</span><strong>{row.time}</strong></li>
                            ))}
                        </ul>
                        <h3 className="hours-title"><Icon name="pin" /> Contact Info</h3>
                        <p className="hours-address">{company.address}</p>
                        <ContactLinks />
                    </div>
                    <div className="hours-map" data-animate="fade-up" style={{ '--delay': '.15s' }}>
                        {company.map?.embed
                            ? <iframe src={company.map.embed} title={`${company.name} on Google Maps`} loading="lazy" referrerPolicy="no-referrer-when-downgrade" />
                            : <Media kind="map" seed="working-hours" tone={0} ratio="16 / 11" />}
                    </div>
                </div>
            </div>
        </section>
    );
}
