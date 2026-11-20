import Button from '../components/Button.jsx';
import CheckList from '../components/CheckList.jsx';
import CtaBanner from '../components/CtaBanner.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import StatGrid from '../components/StatGrid.jsx';
import ValuesSection from '../components/ValuesSection.jsx';
import { useSite } from '../context.js';
import { Rich } from '../text.jsx';

export default function About({ content, headerImage, photos, commitments }) {
    const { routes } = useSite().shared;
    const { intro, values, team_cta: teamCta } = content;
    const claimCheck = commitments.items.find((item) => item.suffix === '%');

    return (
        <>
            <PageHeader title="About Us" text={content.header_text} image={headerImage} crumbs={[{ label: 'About Us' }]} />

            <section className="section">
                <div className="container">
                    <div className="split-grid">
                        <div className="about-collage">
                            <span className="about-collage-frame" aria-hidden="true" />
                            <span className="about-collage-dots" aria-hidden="true" />
                            <figure className="about-photo about-photo-main" data-animate="reveal">
                                <div className="media-inner">
                                    <img src={photos.main} alt={intro.image_alt} loading="lazy" width="960" height="640" />
                                </div>
                            </figure>
                            <figure className="about-photo about-photo-secondary" data-animate="reveal">
                                <div className="media-inner">
                                    <img src={photos.secondary} alt={intro.secondary_image_alt} loading="lazy" width="960" height="640" />
                                </div>
                            </figure>
                            <p className="about-badge" data-animate="fade-up" style={{ '--delay': '.3s' }}>
                                <span className="about-badge-icon"><Icon name="shield" /></span>
                                {intro.highlights[0]}
                            </p>
                            {claimCheck && (
                                <p className="about-stat" data-animate="fade-up" style={{ '--delay': '.45s' }}>
                                    <strong>{claimCheck.value}{claimCheck.suffix}</strong>
                                    <span>{claimCheck.label}</span>
                                </p>
                            )}
                        </div>
                        <div className="about-copy">
                            <SectionTitle eyebrow={intro.eyebrow} title={intro.title} />
                            {intro.paragraphs.map((paragraph, index) => (
                                <p className="lead-text" key={index} data-animate="fade-up" style={{ '--delay': `${index * 0.12}s` }}>
                                    <Rich text={paragraph} />
                                </p>
                            ))}
                            <CheckList items={intro.highlights} />
                            <div data-animate="fade-up">
                                <Button href={`${routes.home}#practice-review`}>Get a Free Practice Review</Button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <ValuesSection values={values} />

            <section className="section">
                <div className="container">
                    <SectionTitle eyebrow={commitments.eyebrow} title={commitments.title} className="is-centered narrow" />
                    <p className="lead-text section-lead" data-animate="fade-up">{commitments.text}</p>
                    <StatGrid items={commitments.items} />
                </div>
            </section>

            <CtaBanner title={teamCta.title} text={teamCta.text} actionLabel="Meet Our Team" actionUrl={routes.team} />
        </>
    );
}
