import { useId, useState } from 'react';
import Button from './Button.jsx';
import FeatureList from './FeatureList.jsx';
import Icon from './Icon.jsx';

/**
 * The core services as tabs, each panel listing what the service includes and linking to its page.
 */
export default function CoreServicesTabs({ services, tabLabel, listTitle, moreLabel }) {
    const [activeIndex, setActiveIndex] = useState(0);
    const baseId = useId();
    const service = services[activeIndex];

    const onKeyDown = (event) => {
        const step = { ArrowDown: 1, ArrowRight: 1, ArrowUp: -1, ArrowLeft: -1 }[event.key];

        if (step) {
            event.preventDefault();
            setActiveIndex((activeIndex + step + services.length) % services.length);
        }
    };

    return (
        <div className="tracks core-services" data-animate="fade-up">
            <div className="track-tabs" role="tablist" aria-orientation="vertical" onKeyDown={onKeyDown}>
                {services.map((item, index) => (
                    <button
                        type="button"
                        role="tab"
                        key={item.slug}
                        id={`${baseId}-tab-${index}`}
                        aria-selected={index === activeIndex}
                        aria-controls={`${baseId}-panel`}
                        tabIndex={index === activeIndex ? 0 : -1}
                        className={`track-tab${index === activeIndex ? ' is-active' : ''}`}
                        onClick={() => setActiveIndex(index)}
                    >
                        <span className="track-tab-label">{tabLabel} {String(index + 1).padStart(2, '0')}</span>
                        <span className="track-tab-title">{item.title}</span>
                    </button>
                ))}
            </div>
            <div className="track-panel" role="tabpanel" id={`${baseId}-panel`} aria-labelledby={`${baseId}-tab-${activeIndex}`} key={service.slug}>
                <span className="core-services-icon"><Icon name={service.icon} /></span>
                <h3>{service.title}</h3>
                <p className="track-summary">{service.excerpt}</p>
                <h4>{listTitle}</h4>
                <FeatureList features={service.features} />
                <Button href={service.url}>{moreLabel}</Button>
            </div>
        </div>
    );
}
