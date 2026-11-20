import Icon from './Icon.jsx';

/**
 * A service summary card. An optional "number" adds a large index in the top corner.
 */
export default function ServiceCard({ service, number, delay = 0 }) {
    return (
        <article className="service-card" data-animate="fade-up" style={{ '--delay': `${delay}s` }}>
            {number && <span className="service-card-number" aria-hidden="true">{String(number).padStart(2, '0')}</span>}
            <span className="service-icon"><Icon name={service.icon} /></span>
            <h3><a href={service.url}>{service.title}</a></h3>
            <p>{service.excerpt}</p>
            <a className="text-link" href={service.url} aria-label={`Read more about ${service.title}`}>Read More <Icon name="arrow" /></a>
        </article>
    );
}
