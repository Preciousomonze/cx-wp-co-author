import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from "@wordpress/i18n";
import { TextControl } from "@wordpress/components";
import { withSelect, withDispatch } from "@wordpress/data";

/**
 * Co Authors section
 */
let CoAuthorMetaField = (props) => {
    return (
        <TextControl 
        value={props.text_metafield}
        label={__("Add Co-authors Username (Separate with comma)", "cx-coa")}
        onChange={(value) => props.onMetaFieldChange(value)}
        />
    )
}

CoAuthorMetaField = withSelect(
    (select) => {
        return {
            text_metafield: select('core/editor').getEditedPostAttribute('meta')['cx_coa_co_authors']
        }
    }
)(CoAuthorMetaField);

CoAuthorMetaField = withDispatch(
    (dispatch) => {
        return {
            onMetaFieldChange: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { cx_coa_co_authors: value } }
                );
            }
        }
    }
)(CoAuthorMetaField);

const PluginDocumentSettingPanelCoAuthors = (props) => (
	<PluginDocumentSettingPanel
		name="cx-coa-coauthors-panel"
		title={__("Co Authors", "cx-coa")}
		className="cx-coa-coauthors-panel"
	>
		<CoAuthorMetaField />
	</PluginDocumentSettingPanel>
);

registerPlugin( 'plugin-document-setting-panel-coauthors', {
	render: PluginDocumentSettingPanelCoAuthors,
	icon: 'user',
} );
