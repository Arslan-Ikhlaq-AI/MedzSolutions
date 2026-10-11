import PageHeader from '../components/PageHeader.jsx';
import PostCard from '../components/PostCard.jsx';
import ResourceCard from '../components/ResourceCard.jsx';
import SectionHeader from '../components/SectionHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { useSite } from '../context.js';

export default function ResourcesIndex({ content, resources, posts }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title="Resources" crumbs={[{ label: 'Resources' }]} />
            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={content.eyebrow} title={content.title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up">{content.text}</p>
                    <div className="card-grid two">
                        {resources.map((resource, index) => <ResourceCard key={resource.slug} resource={resource} delay={(index % 2) * 0.12} tone={index} />)}
                    </div>
                </div>
            </section>
            <section className="section surface">
                <div className="container">
                    <SectionHeader eyebrow="From the Blog" title="Latest *Articles*" actionLabel="View All Articles" actionUrl={routes.blog} />
                    <div className="card-grid three">
                        {posts.map((post, index) => <PostCard key={post.slug} post={post} delay={index * 0.12} tone={index} />)}
                    </div>
                </div>
            </section>
        </>
    );
}
