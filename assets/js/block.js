( function( blocks, element, serverSideRender ) {
    var el = element.createElement;

    blocks.registerBlockType( 'reading-time-calculator/display-block', {
        title: 'Reading Time',
        icon: 'clock',
        category: 'widgets',
        edit: function( props ) {
            return el(
                'div',
                { className: 'rtc-block-editor-preview' },
                el( serverSideRender, {
                    block: 'reading-time-calculator/display-block',
                    attributes: props.attributes
                } )
            );
        },
        save: function() {
            // Rendered dynamically on server-side via PHP callback
            return null;
        },
    } );
} )( window.wp.blocks, window.wp.element, window.wp.serverSideRender );