import Button from '../components/Button.jsx';
import Media from '../components/Media.jsx';
import PageHeader from '../components/PageHeader.jsx';
import ResourceCard from '../components/ResourceCard.jsx';
import SectionHeader from '../components/SectionHeader.jsx';
import ShareLinks from '../components/ShareLinks.jsx';
import { useSite } from '../context.js';

export default function ResourceShow({ resource, related }) {
    const { routes, currentUrl } = useSite().shared;

    return (
        <>
            <PageHeader title={resource.title} crumbs={[{ label: 'Resources', url: routes.resources }, { label: resource.title }]} />
            <section className="section">
                <div className="container narrow-container">
                    <Media item={resource} ratio="16 / 7" reveal className="feature-media" />
                    <p className="resource-type" data-animate="fade-up"><span className="tag">{resource.type}</span></p>
                    <article className="prose" data-animate="fade-up" dangerouslySetInnerHTML={{ __html: resource.body }} />
                    <div className="post-footer">
                        <Button href={routes.contact}>Talk to a Billing Expert</Button>
                        <ShareLinks url={currentUrl} title={resource.title} />
                    </div>
                </div>
            </section>
            {related.length > 0 && (
                <section className="section surface">
                    <div className="container">
                        <SectionHeader eyebrow="Keep Learning" title="More *Resources*" actionLabel="All Resources" actionUrl={routes.resources} />
                        <div className="card-grid three">
                            {related.map((item, index) => <ResourceCard key={item.slug} resource={item} delay={index * 0.12} tone={index} />)}
                        </div>
                    </div>
                </section>
            )}
        </>
    );
}
