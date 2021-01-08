import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from "@wordpress/i18n";
import { TextControl } from "@wordpress/components";
import { withSelect, withDispatch } from "@wordpress/data";

let SubtitleMetaField = (props) => {
    return (
        <TextControl 
            value={props.text_metafield}
            label={__("Co Authors", "cx-coa")}
            onChange={(value) => props.onMetaFieldChange(value)}
        />
    )
}

SubtitleMetaField = withSelect(
    (select) => {
        return {
            text_metafield: select('core/editor').getEditedPostAttribute('meta')['cx_coauthors']
        }
    }
)(SubtitleMetaField);

SubtitleMetaField = withDispatch(
    (dispatch) => {
        return {
            onMetaFieldChange: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { cx_coauthors: value } }
                );
            }
        }
    }
)(SubtitleMetaField);

const PluginDocumentSettingPanelDemo = (props) => (
	<PluginDocumentSettingPanel
		name="cx-coa-coauthors-panel"
		title={__("Co Authors", "cx-coa")}
		className="cx-coa-coauthors-panel"
	>
		<SubtitleMetaField />
	</PluginDocumentSettingPanel>
);

registerPlugin( 'plugin-document-setting-panel-demo', {
	render: PluginDocumentSettingPanelDemo,
	icon: 'edit',
} );