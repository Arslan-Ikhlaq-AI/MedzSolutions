function initials(name) {
    return name.replace(/\(.*\)/, '').split(/\s+/).filter(Boolean).slice(0, 2).map((word) => word[0]).join('').toUpperCase();
}

export function SoftwareTile({ name }) {
    return (
        <span className="software-tile">
            <span className="software-initials" aria-hidden="true">{initials(name)}</span>
            {name}
        </span>
    );
}

/**
 * An endlessly scrolling row of platform names; the duplicate copy is hidden from screen readers.
 */
export default function SoftwareMarquee({ names }) {
    return (
        <div className="software-marquee">
            <ul className="software-track">
                {names.map((name) => <li key={name}><SoftwareTile name={name} /></li>)}
            </ul>
            <ul className="software-track" aria-hidden="true">
                {names.map((name) => <li key={name}><SoftwareTile name={name} /></li>)}
            </ul>
        </div>
    );
}
