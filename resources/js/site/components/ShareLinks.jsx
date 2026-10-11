import { useState } from 'react';
import Icon from './Icon.jsx';

export default function ShareLinks({ url, title }) {
    const [copied, setCopied] = useState(false);
    const encodedUrl = encodeURIComponent(url);
    const networks = [
        { label: 'LinkedIn', href: `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}` },
        { label: 'X', href: `https://x.com/intent/post?url=${encodedUrl}&text=${encodeURIComponent(title)}` },
        { label: 'Facebook', href: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}` },
    ];

    const copyLink = async () => {
        try {
            await navigator.clipboard.writeText(url);
            setCopied(true);
            window.setTimeout(() => setCopied(false), 2000);
        } catch {
            window.prompt('Copy this link:', url);
        }
    };

    return (
        <div className="share-links">
            <span className="share-label"><Icon name="share" /> Share:</span>
            <ul>
                {networks.map((network) => (
                    <li key={network.label}>
                        <a href={network.href} target="_blank" rel="noopener noreferrer">{network.label}</a>
                    </li>
                ))}
                <li>
                    <button type="button" onClick={copyLink}>
                        <Icon name="link" /> {copied ? 'Copied!' : 'Copy link'}
                    </button>
                </li>
            </ul>
        </div>
    );
}
