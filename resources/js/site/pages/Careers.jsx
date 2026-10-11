import CtaBanner from '../components/CtaBanner.jsx';
import Icon from '../components/Icon.jsx';
import JobCard from '../components/JobCard.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import { useSite } from '../context.js';

export default function Careers({ content, headerImage, teamImage, jobs, search }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title="Careers" text={content.header_text} image={headerImage} crumbs={[{ label: 'Careers' }]} />

            <section className="section">
                <div className="container">
                    <div className="careers-intro">
                        <figure className="careers-photo" data-animate="reveal">
                            <div className="media-inner">
                                <img src={teamImage} alt={content.team_image_alt} loading="lazy" width="960" height="640" />
                            </div>
                        </figure>
                        <div>
                            <SectionTitle eyebrow={content.perks_eyebrow} title={content.perks_title} />
                            <p className="lead-text" data-animate="fade-up">{content.perks_text}</p>
                            <ul className="perk-list">
                                {content.perks.map((perk, index) => (
                                    <li key={perk.title} data-animate="fade-up" style={{ '--delay': `${index * 0.08}s` }}>
                                        <span className="perk-icon"><Icon name={perk.icon} /></span>
                                        <span>
                                            <strong>{perk.title}</strong>
                                            {perk.text}
                                        </span>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <section className="section surface" id="openings">
                <div className="container">
                    <div className="openings-head">
                        <SectionTitle eyebrow={content.list_eyebrow} title={content.list_title} />
                        <form className="search-field careers-search" method="get" action={`${routes.careers}#openings`} role="search" data-animate="fade-up">
                            <label className="sr-only" htmlFor="career-search">Search open positions</label>
                            <input id="career-search" type="search" name="search" placeholder="Search roles…" defaultValue={search} />
                            <button type="submit" aria-label="Search"><Icon name="search" /></button>
                        </form>
                    </div>
                    {jobs.length > 0 ? (
                        <div className="job-list">
                            {jobs.map((job, index) => <JobCard key={job.slug} job={job} delay={Math.min(index, 3) * 0.06} />)}
                        </div>
                    ) : (
                        <p className="empty-state">{content.empty}</p>
                    )}
                </div>
            </section>

            <CtaBanner title={content.general_title} text={content.general_text} actionLabel="Send Your Details" actionUrl={routes.contact} />
        </>
    );
}
