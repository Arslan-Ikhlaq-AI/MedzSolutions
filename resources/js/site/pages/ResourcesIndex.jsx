import CtaBanner from '../components/CtaBanner.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import PostCard from '../components/PostCard.jsx';
import ResourceCard from '../components/ResourceCard.jsx';
import SectionHeader from '../components/SectionHeader.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import { useSite } from '../context.js';

export default function ResourcesIndex({ content, headerImage, resources, posts }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title="Resources" image={headerImage} imagePosition="center 45%" compact crumbs={[{ label: 'Resources' }]} />

            <section className="section">
                <div className="container">
                    <SectionHeading title={content.title} text={content.text} />
                    <div className="card-grid two resource-grid">
                        {resources.map((resource, index) => <ResourceCard key={resource.slug} resource={resource} delay={(index % 2) * 0.1} tone={index} />)}
                    </div>
                </div>
            </section>

            <section className="section surface">
                <div className="container">
                    <SectionHeading title={content.explore_title} text={content.explore_text} />
                    <div className="explore-grid">
                        {content.explore.map((link, index) => (
                            <a className="explore-card" key={link.title} href={routes[link.route]} data-animate="fade-up" style={{ '--delay': `${index * 0.08}s` }}>
                                <span className="service-icon"><Icon name={link.icon} /></span>
                                <h3>{link.title}</h3>
                                <p>{link.text}</p>
                                <span className="text-link">Explore <Icon name="arrow" /></span>
                            </a>
                        ))}
                    </div>
                </div>
            </section>

            <section className="section">
                <div className="container">
                    <SectionHeader eyebrow="From the Blog" title="Latest *Articles*" actionLabel="View All Articles" actionUrl={routes.blog} />
                    <div className="card-grid three">
                        {posts.map((post, index) => <PostCard key={post.slug} post={post} delay={index * 0.12} tone={index} />)}
                    </div>
                </div>
            </section>

            <CtaBanner title={content.cta_title} text={content.cta_text} actionLabel={content.cta_label} actionUrl={routes.contact} />
        </>
    );
}
