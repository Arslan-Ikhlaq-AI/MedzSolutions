import ApplicationForm from '../components/ApplicationForm.jsx';
import Button from '../components/Button.jsx';
import Icon from '../components/Icon.jsx';
import { JobMeta } from '../components/JobCard.jsx';
import PageHeader from '../components/PageHeader.jsx';
import { useSite } from '../context.js';

export default function CareerShow({ job, content, action, otherJobs }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader title={job.title} crumbs={[{ label: 'Careers', url: routes.careers }, { label: job.title }]} />
            <section className="section">
                <div className="container">
                    <div className="content-with-sidebar">
                        <div className="content-main">
                            <div data-animate="fade-up"><JobMeta job={job} /></div>
                            <div className="prose job-description" data-animate="fade-up" dangerouslySetInnerHTML={{ __html: job.body }} />
                            <ApplicationForm action={action} title={content.apply_title} text={content.apply_text} />
                        </div>
                        <aside className="content-sidebar">
                            <div className="sidebar-box project-details" data-animate="fade-up">
                                <h2 className="sidebar-title">Job Summary</h2>
                                <dl>
                                    <div><dt>Department</dt><dd>{job.department}</dd></div>
                                    <div><dt>Location</dt><dd>{job.location}</dd></div>
                                    <div><dt>Employment</dt><dd>{job.type}</dd></div>
                                </dl>
                                <div className="sidebar-action">
                                    <Button href="#apply">Apply Now</Button>
                                </div>
                            </div>
                            {otherJobs.length > 0 && (
                                <div className="sidebar-box services-list-box" data-animate="fade-up">
                                    <h2 className="sidebar-title">Other Openings</h2>
                                    <ul>
                                        {otherJobs.map((item) => (
                                            <li key={item.slug}><a href={item.url}>{item.title}<Icon name="arrow" /></a></li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                        </aside>
                    </div>
                </div>
            </section>
        </>
    );
}
