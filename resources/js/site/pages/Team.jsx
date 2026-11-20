import CtaBanner from '../components/CtaBanner.jsx';
import Icon from '../components/Icon.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionHeading from '../components/SectionHeading.jsx';
import { useSite } from '../context.js';

function initialsOf(name) {
    return name.split(' ').map((part) => part[0]).slice(0, 2).join('').toUpperCase();
}

function MemberCard({ member, delay }) {
    return (
        <article className="team-member" data-animate="fade-up" style={{ '--delay': `${delay}s` }}>
            <figure className="team-member-photo">
                {member.image
                    ? <img src={member.image} alt={`${member.name}, ${member.role}`} loading="lazy" width="600" height="720" />
                    : (
                        <span className="team-member-placeholder" aria-hidden="true">
                            <span className="team-member-initials">{initialsOf(member.name)}</span>
                        </span>
                    )}
            </figure>
            <div className="team-member-info">
                <h3>{member.name}</h3>
                <p className="team-member-role">{member.role}</p>
                <p className="team-member-text">{member.text}</p>
            </div>
        </article>
    );
}

export default function Team({ content, headerImage, members }) {
    const { routes } = useSite().shared;

    return (
        <>
            <PageHeader
                title="Meet Our Team"
                text={content.header_text}
                image={headerImage}
                imagePosition="center 20%"
                crumbs={[{ label: 'About Us', url: routes.about }, { label: 'Meet Our Team' }]}
            />

            <section className="section">
                <div className="container">
                    <SectionHeading title={content.title} text={content.text} />
                    <div className="team-grid">
                        {members.map((member, index) => <MemberCard key={member.name} member={member} delay={(index % 3) * 0.1} />)}
                    </div>
                </div>
            </section>

            <section className="why-band">
                <div className="container">
                    <SectionHeading title={content.departments_title} text={content.departments_text} />
                    <div className="why-tiles is-three">
                        {content.departments.map((department, index) => (
                            <article className={`why-tile ${index % 2 ? 'is-accent' : 'is-brand'}`} key={department.title} data-animate="fade-up" style={{ '--delay': `${(index % 3) * 0.08}s` }}>
                                <Icon name={department.icon} />
                                <h3>{department.title}</h3>
                                <p>{department.text}</p>
                            </article>
                        ))}
                    </div>
                </div>
            </section>

            <CtaBanner title={content.join_title} text={content.join_text} actionLabel="View Open Positions" actionUrl={routes.careers} />
        </>
    );
}
