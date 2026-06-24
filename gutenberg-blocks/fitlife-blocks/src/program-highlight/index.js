import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './style.css';
import './editor.css';
import metadata from './block.json';

registerBlockType(metadata, {
    edit: Edit,
    save: Save,
});
