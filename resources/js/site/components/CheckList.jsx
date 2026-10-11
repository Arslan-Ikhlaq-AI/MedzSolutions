import Icon from './Icon.jsx';

export default function CheckList({ items, compact = false }) {
    return (
        <ul className={`check-list${compact ? ' is-compact' : ''}`} data-animate="fade-up">
            {items.map((item) => <li key={item}><Icon name="check" />{item}</li>)}
        </ul>
    );
}
