import { Fragment } from 'react';
import { highlightSegments, plainText } from '../text.jsx';

/**
 * A heading whose words rise into place one after another when it scrolls into view.
 * Screen readers get the whole sentence once instead of the separate word spans.
 */
export default function SplitText({ as: Tag = 'h2', text, className = '' }) {
    let wordIndex = 0;

    const renderWords = (segmentText) => segmentText.split(/(\s+)/).map((word, index) => {
        if (word.trim() === '') {
            return word ? ' ' : null;
        }

        return <span className="split-word" style={{ '--word': wordIndex++ }} key={index}>{word}</span>;
    });

    return (
        <Tag className={`split-text ${className}`.trim()} data-animate="split">
            <span className="sr-only">{plainText(text)}</span>
            <span aria-hidden="true">
                {highlightSegments(text).map((segment, index) => (
                    segment.highlight
                        ? <span className="text-highlight" key={index}>{renderWords(segment.text)}</span>
                        : <Fragment key={index}>{renderWords(segment.text)}</Fragment>
                ))}
            </span>
        </Tag>
    );
}
