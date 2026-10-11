import Icon from './Icon.jsx';
import SectionTitle from './SectionTitle.jsx';

export default function Testimonials({ heading, testimonials }) {
    if (testimonials.length === 0) {
        return null;
    }

    return (
        <section className="section surface">
            <div className="container">
                <SectionTitle eyebrow={heading.eyebrow} title={heading.title} className="is-centered narrow section-intro" />
                <div className="card-grid three">
                    {testimonials.map((testimonial, index) => (
                        <figure className="testimonial-card" key={testimonial.name} data-animate="fade-up" style={{ '--delay': `${index * 0.12}s` }}>
                            <Icon name="quote" className="testimonial-quote-icon" />
                            <blockquote>{testimonial.quote}</blockquote>
                            <figcaption>
                                <strong>{testimonial.name}</strong>
                                <span>{testimonial.role}{testimonial.practice ? `, ${testimonial.practice}` : ''}</span>
                            </figcaption>
                        </figure>
                    ))}
                </div>
            </div>
        </section>
    );
}
