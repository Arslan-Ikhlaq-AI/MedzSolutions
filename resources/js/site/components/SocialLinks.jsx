import { useSite } from '../context.js';

const logos = {
    linkedin: <path fill="currentColor" d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5ZM3 9.75h4V21H3Zm6.5 0h3.8v1.6h.06c.53-1 1.83-2.05 3.77-2.05 4.03 0 4.77 2.65 4.77 6.1V21h-4v-4.9c0-1.17-.02-2.68-1.63-2.68-1.64 0-1.89 1.28-1.89 2.6V21h-4Z" />,
    facebook: <path fill="currentColor" d="M14 8.5V6.8c0-.8.5-1.3 1.4-1.3H17V2.2h-2.6C11.6 2.2 10.4 4 10.4 6.4v2.1H8v3.3h2.4V22H14V11.8h2.6l.4-3.3Z" />,
    instagram: <g fill="none" stroke="currentColor" strokeWidth="1.9"><rect x="3" y="3" width="18" height="18" rx="5" /><circle cx="12" cy="12" r="4.2" /><circle cx="17.4" cy="6.6" r=".6" fill="currentColor" /></g>,
    tiktok: <path fill="currentColor" d="M16.6 2h-3.3v13.3a2.9 2.9 0 1 1-2.9-2.9c.3 0 .6 0 .9.1V9.1a6.3 6.3 0 1 0 5.3 6.2V8.6a7.9 7.9 0 0 0 4.4 1.4V6.6a4.5 4.5 0 0 1-4.4-4.6Z" />,
};

export function SocialIcon({ network }) {
    return <svg className="icon social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">{logos[network]}</svg>;
}

/**
 * The company's social media profiles as round icon links, or as labelled
 * buttons when "withLabels" is set.
 */
export default function SocialLinks({ className = '', withLabels = false }) {
    const { company } = useSite().shared;

    if (!company.social?.length) {
        return null;
    }

    return (
        <ul className={`social-links${withLabels ? ' has-labels' : ''} ${className}`.trim()}>
            {company.social.map((profile) => (
                <li key={profile.network}>
                    <a
                        href={profile.url}
                        className={`is-${profile.network}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label={withLabels ? undefined : `${company.name} on ${profile.label}`}
                        title={withLabels ? undefined : profile.label}
                    >
                        <SocialIcon network={profile.network} />
                        {withLabels && <span>{profile.label}</span>}
                    </a>
                </li>
            ))}
        </ul>
    );
}
