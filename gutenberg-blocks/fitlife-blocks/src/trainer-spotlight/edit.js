import { useBlockProps } from '@wordpress/block-editor';
import { SelectControl, Placeholder, Spinner } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
    const { trainerId } = attributes;
    const [trainers, setTrainers] = useState([]);
    const [loading, setLoading] = useState(true);
    const [selectedTrainerDetails, setSelectedTrainerDetails] = useState(null);

    const blockProps = useBlockProps({
        className: 'fitlife-trainer-spotlight-editor-wrapper'
    });

    // Fetch trainers list on mount
    useEffect(() => {
        apiFetch({ path: '/fitlife/v1/trainers' })
            .then((res) => {
                const options = [
                    { label: __('Select a trainer...', 'fitlife'), value: '' },
                    ...res.map(trainer => ({
                        label: `${trainer.name} (${trainer.specialty})`,
                        value: String(trainer.id)
                    }))
                ];
                setTrainers(options);
                
                // Store full list for previewing
                if (trainerId) {
                    const match = res.find(t => String(t.id) === String(trainerId));
                    if (match) setSelectedTrainerDetails(match);
                }
                
                setLoading(false);
            })
            .catch((err) => {
                console.error(err);
                setLoading(false);
            });
    }, []);

    // Keep preview in sync with selection
    const handleTrainerChange = (id) => {
        setAttributes({ trainerId: id });
        if (id) {
            // Find trainer details from our fetched list (requires fetching again or storing the list)
            apiFetch({ path: '/fitlife/v1/trainers' })
                .then((res) => {
                    const match = res.find(t => String(t.id) === String(id));
                    if (match) setSelectedTrainerDetails(match);
                })
                .catch(console.error);
        } else {
            setSelectedTrainerDetails(null);
        }
    };

    if (loading) {
        return (
            <div {...blockProps} style={{ padding: '20px', textAlign: 'center' }}>
                <Spinner /> {__('Loading trainers...', 'fitlife')}
            </div>
        );
    }

    return (
        <div {...blockProps}>
            <Placeholder
                icon="businessman"
                label={__('Trainer Spotlight', 'fitlife')}
                instructions={__('Select a trainer from the directory to spotlight on this page.', 'fitlife')}
            >
                <SelectControl
                    label={__('Choose Trainer', 'fitlife')}
                    value={trainerId}
                    options={trainers}
                    onChange={handleTrainerChange}
                />
            </Placeholder>

            {selectedTrainerDetails && (
                <div style={{ marginTop: '20px', padding: '20px', backgroundColor: '#1e293b', border: '1px solid #10b981', borderRadius: '8px', color: '#fff', display: 'flex', gap: '20px', alignItems: 'center' }}>
                    {selectedTrainerDetails.photo_url ? (
                        <img src={selectedTrainerDetails.photo_url} alt="" style={{ width: '100px', height: '100px', borderRadius: '50%', objectFit: 'cover' }} />
                    ) : (
                        <div style={{ width: '100px', height: '100px', borderRadius: '50%', backgroundColor: '#0f172a', display: 'flex', alignItems: 'center', justifyContent: 'center', fontSize: '2rem' }}>
                            👤
                        </div>
                    )}
                    <div>
                        <h4 style={{ margin: '0 0 5px 0', fontSize: '1.2rem', color: '#fff' }}>{selectedTrainerDetails.name}</h4>
                        <p style={{ margin: '0 0 5px 0', color: '#10b981', fontSize: '0.9rem', fontWeight: 'bold' }}>{selectedTrainerDetails.specialty}</p>
                        <p style={{ margin: '0', color: '#94a3b8', fontSize: '0.85rem' }}>{selectedTrainerDetails.certification}</p>
                    </div>
                </div>
            )}
        </div>
    );
}
