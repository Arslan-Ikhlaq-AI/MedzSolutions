import { useEffect, useRef, useState } from 'react';

const interactiveSelector = 'a, button, [role="button"], label, select, summary';

/**
 * A small accent dot that trails the pointer, grows over links and buttons,
 * and shows a label (e.g. "View") over elements with data-cursor-text.
 */
export default function MagicCursor() {
    const cursorRef = useRef(null);
    const labelRef = useRef(null);
    const [isEnabled, setIsEnabled] = useState(false);

    useEffect(() => {
        setIsEnabled(window.matchMedia('(hover: hover) and (pointer: fine)').matches);
    }, []);

    useEffect(() => {
        if (!isEnabled) {
            return undefined;
        }

        const cursor = cursorRef.current;
        const smoothing = window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 1 : 0.2;
        const position = { x: 0, y: 0 };
        const target = { x: 0, y: 0 };
        let frame = 0;

        const render = () => {
            position.x += (target.x - position.x) * smoothing;
            position.y += (target.y - position.y) * smoothing;
            cursor.style.transform = `translate3d(${position.x}px, ${position.y}px, 0)`;

            const isSettled = Math.abs(target.x - position.x) < 0.1 && Math.abs(target.y - position.y) < 0.1;
            frame = isSettled ? 0 : window.requestAnimationFrame(render);
        };

        const onPointerMove = (event) => {
            target.x = event.clientX;
            target.y = event.clientY;

            if (!cursor.classList.contains('is-visible')) {
                position.x = target.x;
                position.y = target.y;
                cursor.classList.add('is-visible');
            }

            const element = event.target instanceof Element ? event.target : null;
            const labelled = element?.closest('[data-cursor-text]');
            const isTyping = Boolean(element?.closest('input, textarea'));

            labelRef.current.textContent = labelled ? labelled.dataset.cursorText : '';
            cursor.classList.toggle('is-label', Boolean(labelled));
            cursor.classList.toggle('is-hover', !labelled && Boolean(element?.closest(interactiveSelector)));
            cursor.classList.toggle('is-hidden', isTyping);

            if (!frame) {
                frame = window.requestAnimationFrame(render);
            }
        };

        const onPointerLeave = () => cursor.classList.remove('is-visible');
        const onPointerDown = () => cursor.classList.add('is-pressed');
        const onPointerUp = () => cursor.classList.remove('is-pressed');

        window.addEventListener('pointermove', onPointerMove, { passive: true });
        window.addEventListener('pointerdown', onPointerDown);
        window.addEventListener('pointerup', onPointerUp);
        document.documentElement.addEventListener('mouseleave', onPointerLeave);

        return () => {
            window.cancelAnimationFrame(frame);
            window.removeEventListener('pointermove', onPointerMove);
            window.removeEventListener('pointerdown', onPointerDown);
            window.removeEventListener('pointerup', onPointerUp);
            document.documentElement.removeEventListener('mouseleave', onPointerLeave);
        };
    }, [isEnabled]);

    if (!isEnabled) {
        return null;
    }

    return (
        <div className="magic-cursor" ref={cursorRef} aria-hidden="true">
            <div className="magic-cursor-ball">
                <span className="magic-cursor-label" ref={labelRef} />
            </div>
        </div>
    );
}
