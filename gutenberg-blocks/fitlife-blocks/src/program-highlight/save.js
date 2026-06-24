import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { title, description, bgImageUrl, ctaText, ctaUrl, difficulty } = attributes;

    const blockProps = useBlockProps.save({
        className: 'fitlife-program-highlight-card',
        style: bgImageUrl ? { backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url(${bgImageUrl})` } : {}
    });

    return (
        <div {...blockProps}>
            <div className="program-highlight-meta">
                <span className={`badge badge-${(difficulty || 'Beginner').toLowerCase()}`}>{difficulty}</span>
            </div>
            <RichText.Content tagName="h2" className="program-highlight-title" value={title} />
            <RichText.Content tagName="p" className="program-highlight-desc" value={description} />
            <div className="program-highlight-cta">
                <a href={ctaUrl} className="cta-button">{ctaText}</a>
            </div>
        </div>
    );
}
