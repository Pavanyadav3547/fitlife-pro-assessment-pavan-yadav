/**
 * Gutenberg Editor variations and enhancements for FitLife
 */
wp.domReady(() => {
    // Task 3.3: Register Block Variation for core Columns block
    wp.blocks.registerBlockVariation('core/columns', {
        name: 'fitlife-3-column-feature',
        title: wp.i18n.__('FitLife 3-Column Feature', 'fitlife'),
        description: wp.i18n.__('A custom three-column feature block for programs and options.', 'fitlife'),
        icon: 'layout',
        attributes: {
            columns: 3,
            align: 'wide'
        },
        innerBlocks: [
            ['core/column', { placeholder: wp.i18n.__('Feature 1 info...', 'fitlife') }],
            ['core/column', { placeholder: wp.i18n.__('Feature 2 info...', 'fitlife') }],
            ['core/column', { placeholder: wp.i18n.__('Feature 3 info...', 'fitlife') }]
        ],
        scope: ['block', 'inserter']
    });
});
