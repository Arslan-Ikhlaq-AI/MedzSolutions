import Icon from './Icon.jsx';

export default function Button({ href, children, variant = 'primary', type = 'button', className = '', ...props }) {
    const classes = `btn btn-${variant} ${className}`.trim();
    const content = (
        <>
            <span className="btn-label">{children}</span>
            <Icon name="arrow" className="btn-icon" />
        </>
    );

    if (href) {
        return <a className={classes} href={href} {...props}>{content}</a>;
    }

    return <button className={classes} type={type} {...props}>{content}</button>;
}
