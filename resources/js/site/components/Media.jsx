import Artwork from './Artwork.jsx';

/**
 * Shows an entry's real image when one is configured, otherwise its generated artwork.
 * "reveal" wipes the picture in from the left the first time it scrolls into view.
 */
export default function Media({ item, kind, seed, tone, ratio = '16 / 10', reveal = false, className = '' }) {
    return (
        <figure
            className={`media image-shine ${className}`.trim()}
            style={{ aspectRatio: ratio }}
            data-animate={reveal ? 'reveal' : undefined}
        >
            <div className="media-inner">
                {item?.image
                    ? <img src={item.image} alt={item.title ?? ''} loading="lazy" />
                    : <Artwork kind={kind ?? item?.artwork} seed={seed ?? item?.slug} tone={tone} />}
            </div>
        </figure>
    );
}
