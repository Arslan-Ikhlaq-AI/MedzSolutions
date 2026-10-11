import Icon from './Icon.jsx';

export function JobMeta({ job }) {
    return (
        <ul className="job-meta">
            <li><Icon name="briefcase" />{job.department}</li>
            <li><Icon name="pin" />{job.location}</li>
            <li><Icon name="clock" />{job.type}</li>
        </ul>
    );
}

export default function JobCard({ job, delay = 0 }) {
    return (
        <a className="job-row" href={job.url} data-animate="fade-up" style={{ '--delay': `${delay}s` }}>
            <span className="job-row-main">
                <span className="job-row-title">{job.title}</span>
                <JobMeta job={job} />
                <span className="job-row-text">{job.excerpt}</span>
            </span>
            <span className="job-row-cta">
                View role
                <Icon name="arrow" />
            </span>
        </a>
    );
}
