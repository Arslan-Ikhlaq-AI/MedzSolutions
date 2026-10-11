import Button from './Button.jsx';
import SectionTitle from './SectionTitle.jsx';

export default function SectionHeader({ eyebrow, title, actionLabel, actionUrl }) {
    return (
        <div className="section-header">
            <SectionTitle eyebrow={eyebrow} title={title} />
            {actionUrl && (
                <div className="section-action" data-animate="fade-up">
                    <Button href={actionUrl}>{actionLabel}</Button>
                </div>
            )}
        </div>
    );
}
