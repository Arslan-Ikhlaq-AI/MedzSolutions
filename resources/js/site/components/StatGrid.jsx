import { useEffect, useRef, useState } from 'react';

function CountUp({ value, suffix }) {
    const ref = useRef(null);
    const [current, setCurrent] = useState(0);

    useEffect(() => {
        const element = ref.current;

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
            setCurrent(value);

            return undefined;
        }

        let frame = 0;
        const observer = new IntersectionObserver(([entry]) => {
            if (!entry.isIntersecting) {
                return;
            }

            observer.disconnect();
            const started = performance.now();
            const tick = (now) => {
                const progress = Math.min(1, (now - started) / 1600);
                setCurrent(Math.round(value * (1 - (1 - progress) ** 3)));

                if (progress < 1) {
                    frame = window.requestAnimationFrame(tick);
                }
            };
            frame = window.requestAnimationFrame(tick);
        }, { threshold: 0.4 });

        observer.observe(element);

        return () => {
            observer.disconnect();
            window.cancelAnimationFrame(frame);
        };
    }, [value]);

    return <span ref={ref} aria-label={`${value}${suffix}`}><span aria-hidden="true">{current}{suffix}</span></span>;
}

/**
 * Service-standard tiles; numeric values count up the first time they scroll into view.
 */
export default function StatGrid({ items, className = '' }) {
    return (
        <div className={`stat-grid ${className}`.trim()}>
            {items.map((item, index) => (
                <div className="stat-tile" key={item.label} data-animate="fade-up" style={{ '--delay': `${(index % 3) * 0.1}s` }}>
                    <p className="stat-value">
                        {'value' in item ? <CountUp value={item.value} suffix={item.suffix} /> : item.text}
                    </p>
                    <p className="stat-label">{item.label}</p>
                </div>
            ))}
        </div>
    );
}
