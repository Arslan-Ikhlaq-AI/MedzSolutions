import Icon from './Icon.jsx';

/**
 * The services included in a core service, as a compact list of icons and titles.
 */
export default function FeatureList({ features }) {
    return (
        <ul className="feature-list">
            {features.map((feature) => (
                <li key={feature.title}>
                    <span className="feature-list-icon"><Icon name={feature.icon} /></span>
                    {feature.title}
                </li>
            ))}
        </ul>
    );
}
