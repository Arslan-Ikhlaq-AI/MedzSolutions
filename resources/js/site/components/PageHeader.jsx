import { useSite } from '../context.js';
import Button from './Button.jsx';
import SplitText from './SplitText.jsx';

/**
 * Page title band with breadcrumbs. An optional photo sits behind the brand overlay.
 */
export default function PageHeader({ title, crumbs = [], text, image, actions = [] }) {
    const { shared } = useSite();
    const trail = [{ label: 'Home', url: shared.routes.home }, ...crumbs];

    return (
        <section
            className={`page-header${image ? ' has-image' : ''}`}
            style={image ? { '--page-header-image': `url("${image}")` } : undefined}
        >
            <div className="container">
                <div className="page-header-box">
                    <SplitText as="h1" text={title} />
                    {text && <p className="page-header-text" data-animate="fade-up">{text}</p>}
                    {actions.length > 0 && (
                        <div className="page-header-actions" data-animate="fade-up">
                            {actions.map((action) => <Button key={action.label} href={action.url} variant={action.variant}>{action.label}</Button>)}
                        </div>
                    )}
                    <nav aria-label="Breadcrumb" data-animate="fade-up">
                        <ol className="breadcrumb">
                            {trail.map((crumb, index) => {
                                const isCurrent = index === trail.length - 1;

                                return (
                                    <li key={crumb.label} className={isCurrent ? 'is-current' : undefined} aria-current={isCurrent ? 'page' : undefined}>
                                        {crumb.url && !isCurrent ? <a href={crumb.url}>{crumb.label}</a> : crumb.label}
                                    </li>
                                );
                            })}
                        </ol>
                    </nav>
                </div>
            </div>
        </section>
    );
}
