import { useBlockProps, RichText, MediaUpload, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
    const { title, description, bgImageUrl, ctaText, ctaUrl, difficulty } = attributes;

    const blockProps = useBlockProps({
        className: 'fitlife-program-highlight-block',
        style: bgImageUrl ? { backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url(${bgImageUrl})`, backgroundSize: 'cover', backgroundPosition: 'center', padding: '40px', borderRadius: '12px', color: '#fff' } : { backgroundColor: '#1e293b', padding: '40px', borderRadius: '12px', color: '#fff' }
    });

    const onSelectImage = (media) => {
        setAttributes({ bgImageUrl: media.url });
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Program Highlight Settings', 'fitlife')} initialOpen={true}>
                    <SelectControl
                        label={__('Difficulty Level', 'fitlife')}
                        value={difficulty}
                        options={[
                            { label: __('Beginner', 'fitlife'), value: 'Beginner' },
                            { label: __('Intermediate', 'fitlife'), value: 'Intermediate' },
                            { label: __('Advanced', 'fitlife'), value: 'Advanced' },
                        ]}
                        onChange={(val) => setAttributes({ difficulty: val })}
                    />
                    <TextControl
                        label={__('CTA Button Text', 'fitlife')}
                        value={ctaText}
                        onChange={(val) => setAttributes({ ctaText: val })}
                    />
                    <TextControl
                        label={__('CTA URL', 'fitlife')}
                        value={ctaUrl}
                        onChange={(val) => setAttributes({ ctaUrl: val })}
                    />
                    <div style={{ marginBottom: '15px' }}>
                        <label style={{ display: 'block', marginBottom: '5px', fontSize: '13px', color: '#1e293b' }}>
                            {__('Background Image', 'fitlife')}
                        </label>
                        <MediaUpload
                            onSelect={onSelectImage}
                            allowedTypes={['image']}
                            value={bgImageUrl}
                            render={({ open }) => (
                                <Button isSecondary onClick={open}>
                                    {bgImageUrl ? __('Change Image', 'fitlife') : __('Upload Image', 'fitlife')}
                                </Button>
                            )}
                        />
                        {bgImageUrl && (
                            <Button isDestructive onClick={() => setAttributes({ bgImageUrl: '' })} style={{ marginLeft: '10px' }}>
                                {__('Remove', 'fitlife')}
                            </Button>
                        )}
                    </div>
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <div style={{ marginBottom: '10px' }}>
                    <span className={`badge badge-${(difficulty || 'Beginner').toLowerCase()}`} style={{ padding: '4px 8px', borderRadius: '4px', fontSize: '0.8rem', fontWeight: 'bold' }}>
                        {difficulty}
                    </span>
                </div>
                <RichText
                    tagName="h2"
                    value={title}
                    onChange={(val) => setAttributes({ title: val })}
                    placeholder={__('Enter Program Title...', 'fitlife')}
                    style={{ fontSize: '2rem', color: '#fff', margin: '10px 0' }}
                />
                <RichText
                    tagName="p"
                    value={description}
                    onChange={(val) => setAttributes({ description: val })}
                    placeholder={__('Enter description...', 'fitlife')}
                    style={{ color: '#cbd5e1', fontSize: '1rem', marginBottom: '20px' }}
                />
                <div style={{ display: 'inline-block', background: '#10b981', padding: '10px 20px', borderRadius: '4px', color: '#0f172a', fontWeight: 'bold' }}>
                    <span>{ctaText || __('Join Today', 'fitlife')}</span>
                </div>
            </div>
        </>
    );
}
