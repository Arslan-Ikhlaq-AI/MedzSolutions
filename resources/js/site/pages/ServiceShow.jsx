import AskBox from '../components/AskBox.jsx';
import Button from '../components/Button.jsx';
import FaqAccordion from '../components/FaqAccordion.jsx';
import Icon from '../components/Icon.jsx';
import Media from '../components/Media.jsx';
import PageHeader from '../components/PageHeader.jsx';
import SectionHeader from '../components/SectionHeader.jsx';
import SectionTitle from '../components/SectionTitle.jsx';
import ServiceCard from '../components/ServiceCard.jsx';
import ValuesSection from '../components/ValuesSection.jsx';
import { phoneHref } from '../components/ContactLinks.jsx';
import { useSite } from '../context.js';

export default function ServiceShow({ service, content, values, services }) {
    const { company, routes } = useSite().shared;
    const otherServices = services.filter((item) => item.slug !== service.slug).slice(0, 3);

    return (
        <>
            <PageHeader title={service.title} crumbs={[{ label: 'Services', url: routes.services }, { label: service.title }]} />

            <section className="section">
                <div className="container">
                    <div className="content-with-sidebar">
                        <div className="content-main">
                            <Media item={service} ratio="16 / 9" reveal className="feature-media" />
                            <div className="prose" data-animate="fade-up" dangerouslySetInnerHTML={{ __html: service.body }} />
                        </div>
                        <aside className="content-sidebar">
                            <div className="sidebar-box services-list-box" data-animate="fade-up">
                                <h2 className="sidebar-title">{content.sidebar_title}</h2>
                                <ul>
                                    {services.map((item) => {
                                        const isCurrent = item.slug === service.slug;

                                        return (
                                            <li key={item.slug} className={isCurrent ? 'is-active' : undefined}>
                                                <a href={item.url} aria-current={isCurrent ? 'page' : undefined}>
                                                    {item.title}
                                                    <Icon name="arrow" />
                                                </a>
                                            </li>
                                        );
                                    })}
                                </ul>
                            </div>
                            <div className="sidebar-box help-box" data-animate="fade-up">
                                <span className="help-icon"><Icon name={service.icon} /></span>
                                <h2>{content.help_title}</h2>
                                <p>{content.help_text}</p>
                                <Button href={routes.contact} variant="light">Get In Touch</Button>
                                <a className="help-phone" href={phoneHref(company.phone)}>
                                    <Icon name="phone" /> {company.phone}
                                </a>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>

            <ValuesSection values={values} />

            <section className="section">
                <div className="container">
                    <div className="faq-layout">
                        <div>
                            <SectionTitle eyebrow={content.faq_eyebrow} title={content.faq_title} className="section-intro" />
                            <FaqAccordion items={service.faqs} />
                        </div>
                        <AskBox title={content.ask_title} text={content.ask_text} />
                    </div>
                </div>
            </section>

            <section className="section surface">
                <div className="container">
                    <SectionHeader eyebrow={content.related_eyebrow} title={content.related_title} actionLabel="View All Services" actionUrl={routes.services} />
                    <div className="card-grid three">
                        {otherServices.map((item, index) => <ServiceCard key={item.slug} service={item} delay={index * 0.12} />)}
                    </div>
                </div>
            </section>
        </>
    );
}
