import { useEffect, useState } from 'react';
import { Rich } from '../text.jsx';
import Button from './Button.jsx';
import Icon from './Icon.jsx';
import SectionTitle from './SectionTitle.jsx';

const slideDuration = 7000;

/**
 * Rotating hero with a photo per slide. Pauses while hovered or focused, and stays put for reduced-motion users.
 */
export default function HeroSlider({ slides, actions }) {
    const [activeIndex, setActiveIndex] = useState(0);
    const [isPaused, setIsPaused] = useState(false);

    useEffect(() => {
        if (isPaused || slides.length < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return undefined;
        }

        const timer = window.setTimeout(() => setActiveIndex((index) => (index + 1) % slides.length), slideDuration);

        return () => window.clearTimeout(timer);
    }, [activeIndex, isPaused, slides.length]);

    const show = (step) => setActiveIndex((index) => (index + step + slides.length) % slides.length);
    const pad = (number) => String(number).padStart(2, '0');

    return (
        <section
            className={`hero hero-slider${isPaused ? ' is-paused' : ''}`}
            aria-roledescription="carousel"
            aria-label="Highlights"
            onMouseEnter={() => setIsPaused(true)}
            onMouseLeave={() => setIsPaused(false)}
            onFocus={() => setIsPaused(true)}
            onBlur={() => setIsPaused(false)}
        >
            <div className="hero-media" aria-hidden="true">
                {slides.map((slide, index) => (
                    <div
                        key={slide.title}
                        className={`hero-photo${index === activeIndex ? ' is-active' : ''}`}
                        style={{ backgroundImage: `url("${slide.image}")` }}
                    />
                ))}
            </div>
            <div className="container">
                <div className="hero-content">
                    <div className="hero-slides">
                        {slides.map((slide, index) => (
                            <div
                                className={`hero-slide${index === activeIndex ? ' is-active' : ''}`}
                                key={slide.title}
                                aria-roledescription="slide"
                                aria-label={`${index + 1} of ${slides.length}`}
                                aria-hidden={index !== activeIndex}
                            >
                                <SectionTitle eyebrow={slide.eyebrow} title={slide.title} as={index === 0 ? 'h1' : 'h2'} />
                                <p className="hero-text lead-text" data-animate="fade-up" style={{ '--delay': '.25s' }}>
                                    <Rich text={slide.body} />
                                </p>
                            </div>
                        ))}
                    </div>
                    <div className="hero-actions" data-animate="fade-up" style={{ '--delay': '.4s' }}>
                        {actions.map((action) => <Button key={action.label} href={action.url} variant={action.variant}>{action.label}</Button>)}
                    </div>
                    {slides.length > 1 && (
                        <div className="hero-controls" data-animate="fade-up" style={{ '--delay': '.5s' }}>
                            <span className="hero-counter" aria-hidden="true">
                                <strong>{pad(activeIndex + 1)}</strong> / {pad(slides.length)}
                            </span>
                            <div className="hero-progress">
                                {slides.map((slide, index) => (
                                    <button
                                        type="button"
                                        key={slide.title}
                                        className={`hero-dot${index === activeIndex ? ' is-active' : ''}`}
                                        onClick={() => setActiveIndex(index)}
                                        aria-label={`Show slide ${index + 1}`}
                                        aria-current={index === activeIndex || undefined}
                                    >
                                        <span style={{ '--slide-duration': `${slideDuration}ms` }} />
                                    </button>
                                ))}
                            </div>
                            <div className="hero-arrows">
                                <button type="button" className="hero-arrow" onClick={() => show(-1)} aria-label="Previous slide"><Icon name="left" /></button>
                                <button type="button" className="hero-arrow" onClick={() => show(1)} aria-label="Next slide"><Icon name="right" /></button>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </section>
    );
}
