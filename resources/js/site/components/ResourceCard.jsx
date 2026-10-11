import Icon from './Icon.jsx';
import Media from './Media.jsx';

export default function ResourceCard({ resource, delay = 0, tone }) {
    return (
        <article className="resource-card" data-animate="fade-up" style={{ '--delay': `${delay}s` }}>
            <a className="card-media-link" href={resource.url} data-cursor-text="Read" tabIndex={-1} aria-hidden="true">
                <Media item={resource} tone={tone} ratio="16 / 9" />
            </a>
            <div className="resource-card-body">
                <span className="tag">{resource.type}</span>
                <h3><a href={resource.url}>{resource.title}</a></h3>
                <p>{resource.excerpt}</p>
                <a className="text-link" href={resource.url}>Read the {resource.type.toLowerCase()} <Icon name="arrow" /></a>
            </div>
        </article>
    );
}
