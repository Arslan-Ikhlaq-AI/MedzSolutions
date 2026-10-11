import { useEffect, useState } from 'react';

/**
 * Fades out the server-rendered preloader once the page has loaded and reports when it is gone.
 */
export function usePreloader() {
    const [isReady, setIsReady] = useState(false);

    useEffect(() => {
        const preloader = document.getElementById('preloader');
        let removeTimer;
        let finished = false;

        const finish = () => {
            if (finished) {
                return;
            }

            finished = true;
            preloader?.classList.add('is-hidden');
            removeTimer = window.setTimeout(() => preloader?.remove(), 600);
            setIsReady(true);
        };

        // Slow third-party assets should never keep visitors staring at the spinner.
        const fallbackTimer = window.setTimeout(finish, 4000);

        if (document.readyState === 'complete') {
            finish();
        } else {
            window.addEventListener('load', finish, { once: true });
        }

        return () => {
            window.removeEventListener('load', finish);
            window.clearTimeout(fallbackTimer);
            window.clearTimeout(removeTimer);
        };
    }, []);

    return isReady;
}

/**
 * Adds "is-visible" to every [data-animate] element the first time it scrolls into view.
 */
export function useScrollAnimations(isReady) {
    useEffect(() => {
        if (!isReady) {
            return undefined;
        }

        const elements = document.querySelectorAll('[data-animate]:not(.is-visible)');

        if (!('IntersectionObserver' in window)) {
            elements.forEach((element) => element.classList.add('is-visible'));

            return undefined;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -8% 0px', threshold: 0.05 });

        elements.forEach((element) => observer.observe(element));

        return () => observer.disconnect();
    }, [isReady]);
}
