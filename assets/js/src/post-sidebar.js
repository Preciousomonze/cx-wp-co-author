import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from "@wordpress/i18n";
import { TextControl } from "@wordpress/components";
import { withSelect, withDispatch } from "@wordpress/data";
import { ToggleControl } from '@wordpress/components';
import { withState } from '@wordpress/compose';
 
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


// Enable/Disable ads display toggle.
let ToggleEnableAds = ( ({enabled, onUpdateRestrict}) => {
    return (
    <ToggleControl
        label='Enable Ads for this post.<small>(Ignore to use global settings)</small>'
        help={ enabled ? 'Ads display enabled' : 'Ads display disabled' }
        checked={enabled}
        onChange={(enabled) => { onUpdateRestrict(enabled); }
        }
        />
    )});

ToggleEnableAds = withSelect( ( select ) => {
        return {
            enabled: select( 'core/editor' ).getEditedPostAttribute( 'meta' )['cx_co_ads_enable_ads']
        };
    } )(ToggleEnableAds);

 ToggleEnableAds = withDispatch(
    ( dispatch ) => {
        return {
            onUpdateRestrict: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { cx_co_ads_enable_ads: value } }
                );
            }
        }
    }
)(ToggleEnableAds);



// Adding all to the panel.
const PluginDocumentSettingPanelAds = (props) => (
	<PluginDocumentSettingPanel
		name="cx-co-ads-adlink-panel"
		title={__("Advert link", "cx-co-ads")}
		className="cx-co-ads-adlink-panel"
	>
        <ToggleEnableAds />
		<AdLinkMetaField />
	</PluginDocumentSettingPanel>
);

// Register.
registerPlugin( 'plugin-document-setting-panel-adlink', {
	render: PluginDocumentSettingPanelAds,
	icon: 'link',
} );
