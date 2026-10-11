import CtaBanner from '../components/CtaBanner.jsx';
import PageHeader from '../components/PageHeader.jsx';
import PostCard from '../components/PostCard.jsx';
import PostSidebar from '../components/PostSidebar.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { useSite } from '../context.js';

export default function PostsIndex({ content, posts, categories, latest, filters }) {
    const { routes } = useSite().shared;
    const isFiltered = Boolean(filters.search || filters.category);

    return (
        <>
            <PageHeader title="Blogs" crumbs={[{ label: 'Blogs' }]} />
            <section className="section">
                <div className="container">
                    <div className="content-with-sidebar">
                        <div className="content-main">
                            <SectionTitle eyebrow={content.eyebrow} title={content.title} className="section-intro" />
                            {isFiltered && (
                                <p className="filter-summary" data-animate="fade-up">
                                    {posts.length} {posts.length === 1 ? 'article' : 'articles'}
                                    {filters.category && <> in <strong>{filters.category}</strong></>}
                                    {filters.search && <> matching <strong>“{filters.search}”</strong></>}
                                    <a href={routes.blog}>Clear filters</a>
                                </p>
                            )}
                            {posts.length > 0 ? (
                                <div className="card-grid two">
                                    {posts.map((post, index) => <PostCard key={post.slug} post={post} delay={(index % 2) * 0.12} tone={index} />)}
                                </div>
                            ) : (
                                <p className="empty-state">{content.empty}</p>
                            )}
                        </div>
                        <PostSidebar content={content} categories={categories} latest={latest} filters={filters} />
                    </div>
                </div>
            </section>
            <CtaBanner title={content.cta_title} text={content.cta_text} actionLabel="Contact Us" actionUrl={routes.contact} />
        </>
    );
}
