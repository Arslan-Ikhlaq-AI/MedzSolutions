import { useSite } from '../context.js';

export default function Logo({ href, name, withTagline = false }) {
    const { brand, company } = useSite().shared;

    return (
        <a className="logo" href={href} aria-label={`${name} home`}>
            <img className="logo-mark" src={brand.mark} alt="" width="50" height="51" />
            <span className="logo-text" aria-hidden="true">
                <span className="logo-word">{name}</span>
                {withTagline && <span className="logo-tagline">{company.tagline}</span>}
            </span>
        </a>
    );
}
