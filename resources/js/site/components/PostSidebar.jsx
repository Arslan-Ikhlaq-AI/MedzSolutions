import { useSite } from '../context.js';
import Icon from './Icon.jsx';

export default function PostSidebar({ content, categories, latest, filters }) {
    const { routes } = useSite().shared;

    return (
        <aside className="content-sidebar post-sidebar">
            <form className="sidebar-box sidebar-search" method="get" action={routes.blog} role="search" data-animate="fade-up">
                <label className="sidebar-title" htmlFor="blog-search">{content.search_label}</label>
                <div className="search-field">
                    <input id="blog-search" type="search" name="search" placeholder="Search keywords…" defaultValue={filters.search} />
                    {filters.category && <input type="hidden" name="category" value={filters.category} />}
                    <button type="submit" aria-label="Search"><Icon name="search" /></button>
                </div>
            </form>
            <nav className="sidebar-box category-box" aria-label={content.categories_title} data-animate="fade-up">
                <h2 className="sidebar-title">{content.categories_title}</h2>
                <ul>
                    <li className={filters.category ? undefined : 'is-active'}>
                        <a href={routes.blog}>All articles</a>
                    </li>
                    {categories.map((category) => (
                        <li key={category.name} className={filters.category === category.name ? 'is-active' : undefined}>
                            <a href={`${routes.blog}?category=${encodeURIComponent(category.name)}`}>
                                {category.name}
                                <span>{category.count}</span>
                            </a>
                        </li>
                    ))}
                </ul>
            </nav>
            <div className="sidebar-box latest-box" data-animate="fade-up">
                <h2 className="sidebar-title">{content.latest_title}</h2>
                <ul>
                    {latest.map((post) => (
                        <li key={post.slug}>
                            <a href={post.url}>{post.title}</a>
                            <time dateTime={post.published_at}>{post.date}</time>
                        </li>
                    ))}
                </ul>
            </div>
        </aside>
    );
}
