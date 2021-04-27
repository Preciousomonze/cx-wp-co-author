import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from "@wordpress/i18n";
import { TextControl } from "@wordpress/components";
import { withSelect, withDispatch } from "@wordpress/data";

/**
 * AD Link section.
 */
let AdLinkMetaField = (props) => {
    return (
        <TextControl 
            value={props.text_metafield}
            label={__("Ad Link", "cx-co-ads")}
            onChange={(value) => props.onMetaFieldChange(value)}
        />
    )
}

AdLinkMetaField = withSelect(
    (select) => {
        return {
            text_metafield: select('core/editor').getEditedPostAttribute('meta')['cx_co_ads_ad_link']
        }
    }
)(AdLinkMetaField);

AdLinkMetaField = withDispatch(
    (dispatch) => {
        return {
            onMetaFieldChange: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { cx_co_ads_ad_link: value } }
                );
            }
        }
    }
)(AdLinkMetaField);

const PluginDocumentSettingPanelAds = (props) => (
	<PluginDocumentSettingPanel
		name="cx-co-ads-adlink-panel"
		title={__("Advert link", "cx-co-ads")}
		className="cx-co-ads-adlink-panel"
	>
		<AdLinkMetaField />
	</PluginDocumentSettingPanel>
);

registerPlugin( 'plugin-document-setting-panel-adlink', {
	render: PluginDocumentSettingPanelAds,
	icon: 'link',
} );
