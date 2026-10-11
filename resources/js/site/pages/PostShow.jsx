import Icon from '../components/Icon.jsx';
import Media from '../components/Media.jsx';
import PageHeader from '../components/PageHeader.jsx';
import PostCard from '../components/PostCard.jsx';
import SectionHeader from '../components/SectionHeader.jsx';
import ShareLinks from '../components/ShareLinks.jsx';
import { useSite } from '../context.js';

export default function PostShow({ post, recentPosts }) {
    const { routes, currentUrl } = useSite().shared;

    return (
        <>
            <PageHeader title={post.title} crumbs={[{ label: 'Blogs', url: routes.blog }, { label: post.title }]} />

            <section className="section">
                <div className="container narrow-container">
                    <Media item={post} ratio="16 / 8" reveal className="feature-media" />
                    <ul className="post-meta" data-animate="fade-up">
                        <li><Icon name="calendar" /><time dateTime={post.published_at}>{post.date}</time></li>
                        <li><Icon name="tag" />{post.category}</li>
                        <li><Icon name="clock" />{post.reading_time}</li>
                    </ul>
                    <article className="prose" data-animate="fade-up" dangerouslySetInnerHTML={{ __html: post.body }} />
                    <div className="post-footer">
                        <p className="post-category"><span>Category:</span> <span className="tag">{post.category}</span></p>
                        <ShareLinks url={currentUrl} title={post.title} />
                    </div>
                </div>
            </section>

            {recentPosts.length > 0 && (
                <section className="section surface">
                    <div className="container">
                        <SectionHeader eyebrow="Keep Reading" title="Recent *Articles*" actionLabel="View All Articles" actionUrl={routes.blog} />
                        <div className="card-grid three">
                            {recentPosts.map((item, index) => <PostCard key={item.slug} post={item} delay={index * 0.12} tone={index} />)}
                        </div>
                    </div>
                </section>
            )}
        </>
    );
}
