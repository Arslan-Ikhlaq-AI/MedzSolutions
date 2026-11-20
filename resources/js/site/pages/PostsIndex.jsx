import CtaBanner from '../components/CtaBanner.jsx';
import Icon from '../components/Icon.jsx';
import Media from '../components/Media.jsx';
import PageHeader from '../components/PageHeader.jsx';
import PostCard from '../components/PostCard.jsx';
import PostSidebar from '../components/PostSidebar.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import { useSite } from '../context.js';

function FeaturedPost({ post, label }) {
    return (
        <article className="featured-post" data-animate="fade-up">
            <a className="card-media-link" href={post.url} data-cursor-text="Read" tabIndex={-1} aria-hidden="true">
                <Media item={post} tone={0} ratio="4 / 3" />
            </a>
            <div className="featured-post-body">
                {label && <span className="tag resource-tag"><Icon name="spark" />{label}</span>}
                <p className="post-card-date">
                    <time dateTime={post.published_at}>{post.date}</time>
                    <span aria-hidden="true">•</span>
                    {post.category}
                </p>
                <h3><a href={post.url}>{post.title}</a></h3>
                <p className="featured-post-excerpt">{post.excerpt}</p>
                <a className="text-link" href={post.url}>Read the article <Icon name="arrow" /></a>
            </div>
        </article>
    );
}

export default function PostsIndex({ content, headerImage, posts, categories, latest, filters }) {
    const { routes } = useSite().shared;
    const isFiltered = Boolean(filters.search || filters.category);
    const featured = !isFiltered && posts.length > 2 ? posts[0] : null;
    const remaining = featured ? posts.slice(1) : posts;
    // A lone article left over after the two-column grid is shown wide instead.
    const leftover = remaining.length > 2 && remaining.length % 2 === 1 ? remaining[remaining.length - 1] : null;
    const listed = leftover ? remaining.slice(0, -1) : remaining;

    return (
        <>
            <PageHeader title="Blogs" image={headerImage} imagePosition="center 55%" compact crumbs={[{ label: 'Blogs' }]} />
            <section className="section">
                <div className="container">
                    <div className="content-with-sidebar">
                        <div className="content-main">
                            <SectionHeading title={content.title} text={content.text} align="left" />
                            {isFiltered && (
                                <p className="filter-summary" data-animate="fade-up">
                                    {posts.length} {posts.length === 1 ? 'article' : 'articles'}
                                    {filters.category && <> in <strong>{filters.category}</strong></>}
                                    {filters.search && <> matching <strong>“{filters.search}”</strong></>}
                                    <a href={routes.blog}>Clear filters</a>
                                </p>
                            )}
                            {featured && <FeaturedPost post={featured} label={content.featured_label} />}
                            {listed.length > 0 ? (
                                <div className="card-grid two">
                                    {listed.map((post, index) => <PostCard key={post.slug} post={post} delay={(index % 2) * 0.1} tone={index + 1} />)}
                                </div>
                            ) : (
                                <p className="empty-state">{content.empty}</p>
                            )}
                            {leftover && <FeaturedPost post={leftover} />}
                        </div>
                        <PostSidebar content={content} categories={categories} latest={latest} filters={filters} />
                    </div>
                </div>
            </section>
            <CtaBanner title={content.cta_title} text={content.cta_text} actionLabel="Contact Us" actionUrl={routes.contact} />
        </>
    );
}
