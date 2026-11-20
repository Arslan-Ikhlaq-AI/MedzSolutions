import Icon from './Icon.jsx';
import SectionHeading from './SectionHeading.jsx';

/**
 * The home page's alternating navy and gold tile band. Six tiles switch to a three-column grid.
 */
export default function WhyBand({ title, text, tiles }) {
    const columns = tiles.length % 3 === 0 ? ' is-three' : '';

    return (
        <section className="why-band">
            <div className="container">
                <SectionHeading title={title} text={text} />
                <div className={`why-tiles${columns}`}>
                    {tiles.map((tile, index) => (
                        <article className={`why-tile ${index % 2 ? 'is-gold' : 'is-navy'}`} key={tile.title} data-animate="fade-up" style={{ '--delay': `${(index % 4) * 0.08}s` }}>
                            <Icon name={tile.icon} />
                            <h3>{tile.title}</h3>
                            <p>{tile.text}</p>
                        </article>
                    ))}
                </div>
            </div>
        </section>
    );
}
