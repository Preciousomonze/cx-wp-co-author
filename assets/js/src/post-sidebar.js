import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from "@wordpress/i18n";
import { TextControl } from "@wordpress/components";
import { withSelect, withDispatch } from "@wordpress/data";
import { ToggleControl } from '@wordpress/components';
import { withState } from '@wordpress/compose';
 

// Enable/Disable Overriding Global Settings.
let ToggleOverrideGlobalSettings = ( ({enabled, onUpdateRestrict}) => {
    return (
    <ToggleControl
        label='Override/Disable Global Settings for this post.'
        help={ enabled ? 'Global Settings Disabled' : 'Using Global Settings' }
        checked={enabled}
        onChange={(enabled) => { onUpdateRestrict(enabled); }
        }
        />
    )});

ToggleOverrideGlobalSettings = withSelect( ( select ) => {
        return {
            enabled: select( 'core/editor' ).getEditedPostAttribute( 'meta' )['cx_co_ads_override_global_settings']
        };
    } )(ToggleOverrideGlobalSettings);

 ToggleOverrideGlobalSettings = withDispatch(
    ( dispatch ) => {
        return {
            onUpdateRestrict: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { cx_co_ads_override_global_settings: value } }
                );
            }
        }
    }
)(ToggleOverrideGlobalSettings);


// Enable/Disable ads display toggle.
let ToggleEnableAds = ( ({enabled, onUpdateRestrict}) => {
    return (
    <ToggleControl
        label='Enable Ads for this post.'
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

/**
 * AD shortcode section.
 */
 let AdShortCodeMetaField = (props) => {
    return (
        <TextControl 
            value={props.text_metafield}
            label={__("Ad Shortcode", "cx-co-ads")}
            onChange={(value) => props.onMetaFieldChange(value)}
        />
    )
}

AdShortCodeMetaField = withSelect(
    (select) => {
        return {
            text_metafield: select('core/editor').getEditedPostAttribute('meta')['cx_co_ads_ad_shortcode']
        }
    }
)(AdShortCodeMetaField);

AdShortCodeMetaField = withDispatch(
    (dispatch) => {
        return {
            onMetaFieldChange: (value) => {
                dispatch('core/editor').editPost(
                    { meta: { cx_co_ads_ad_shortcode: value } }
                );
            }
        }
    }
)(AdShortCodeMetaField);


// Adding all to the panel.
const PluginDocumentSettingPanelAds = (props) => (
	<PluginDocumentSettingPanel
		name="cx-co-ads-adsection-panel"
		title={__("Advert Section", "cx-co-ads")}
		className="cx-co-ads-adsection-panel"
	>
        <ToggleOverrideGlobalSettings />
        <ToggleEnableAds />
		<AdShortCodeMetaField />
	</PluginDocumentSettingPanel>
);

// Register.
registerPlugin( 'plugin-document-setting-panel-adsection', {
	render: PluginDocumentSettingPanelAds,
	icon: 'link',
} );
