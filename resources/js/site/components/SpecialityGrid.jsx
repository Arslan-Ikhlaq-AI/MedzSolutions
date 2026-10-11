import Icon from './Icon.jsx';

export default function SpecialityGrid({ specialities }) {
    return (
        <ul className="speciality-grid">
            {specialities.map((speciality, index) => (
                <li className="speciality-card" key={speciality.name} data-animate="fade-up" style={{ '--delay': `${(index % 4) * 0.08}s` }}>
                    <span className="speciality-icon"><Icon name={speciality.icon} /></span>
                    <h3>{speciality.name}</h3>
                    <p>{speciality.text}</p>
                </li>
            ))}
        </ul>
    );
}
