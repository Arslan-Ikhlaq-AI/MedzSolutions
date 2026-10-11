import Media from './Media.jsx';

export default function PostCard({ post, delay = 0, tone }) {
    return (
        <article className="post-card" data-animate="fade-up" style={{ '--delay': `${delay}s` }}>
            <a className="card-media-link" href={post.url} data-cursor-text="Read" tabIndex={-1} aria-hidden="true">
                <Media item={post} tone={tone} ratio="4 / 3" />
            </a>
            <div className="post-card-body">
                <p className="post-card-date">
                    <time dateTime={post.published_at}>{post.date}</time>
                    <span aria-hidden="true">•</span>
                    {post.category}
                </p>
                <h3><a href={post.url}>{post.title}</a></h3>
            </div>
        </article>
    );
}
