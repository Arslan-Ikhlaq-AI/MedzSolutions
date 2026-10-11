import Icon from './Icon.jsx';

export default function ServiceCard({ service, delay = 0 }) {
    return (
        <article className="service-card" data-animate="fade-up" style={{ '--delay': `${delay}s` }}>
            <span className="service-icon"><Icon name={service.icon} /></span>
            <h3><a href={service.url}>{service.title}</a></h3>
            <p>{service.excerpt}</p>
            <a className="text-link" href={service.url} aria-label={`Read more about ${service.title}`}>Read More <Icon name="arrow" /></a>
        </article>
    );
}
