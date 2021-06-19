import { registerBlockType } from '@wordpress/blocks';
import { withSelect } from '@wordpress/data';
import { useBlockProps } from '@wordpress/block-editor';
 
registerBlockType( 'gutenberg-examples/example-dynamic', {
    apiVersion: 2,
    title: 'Example: last post',
    icon: 'megaphone',
    category: 'widgets',
 
    edit: withSelect( ( select ) => {
        return {
            posts: select( 'core' ).getEntityRecords( 'postType', 'post' ),
        };
    } )( ( { posts } ) => {
        const blockProps = useBlockProps();
 
        return (
            <div { ...blockProps }>
                { ! posts && 'Loading' }
                { posts && posts.length === 0 && 'No Posts' }
                { posts && posts.length > 0 && (
                    <a href={ posts[ 0 ].link }>
                        { posts[ 0 ].title.rendered }
                    </a>
                ) }
            </div>
        );
    } ),
} );