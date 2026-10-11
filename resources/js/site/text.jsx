import { Fragment } from 'react';

/**
 * Splits "Words with *highlighted* parts" into plain and highlighted segments.
 */
export function highlightSegments(text) {
    return text.split(/(\*[^*]+\*)/).filter(Boolean).map((part) => (
        part.startsWith('*') && part.endsWith('*')
            ? { text: part.slice(1, -1), highlight: true }
            : { text: part, highlight: false }
    ));
}

export function plainText(text) {
    return text.replace(/\*/g, '');
}

/**
 * Renders "Words with *highlighted* parts" without the letter-by-letter animation.
 */
export function Highlight({ text }) {
    return highlightSegments(text).map((segment, index) => (
        segment.highlight
            ? <span className="text-highlight" key={index}>{segment.text}</span>
            : <Fragment key={index}>{segment.text}</Fragment>
    ));
}

/**
 * Renders a short paragraph, turning **text** into <strong>.
 */
export function Rich({ text }) {
    return text.split(/(\*\*[^*]+\*\*)/).filter(Boolean).map((part, index) => (
        part.startsWith('**') && part.endsWith('**')
            ? <strong key={index}>{part.slice(2, -2)}</strong>
            : <Fragment key={index}>{part}</Fragment>
    ));
}
