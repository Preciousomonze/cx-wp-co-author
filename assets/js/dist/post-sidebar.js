!function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=6)}([function(e,t){e.exports=window.wp.element},function(e,t){e.exports=window.wp.data},function(e,t){e.exports=window.wp.components},function(e,t){e.exports=window.wp.i18n},function(e,t){e.exports=window.wp.plugins},function(e,t){e.exports=window.wp.editPost},function(e,t,n){"use strict";n.r(t);var o=n(0),r=n(4),i=n(5),c=n(3),a=n(2),l=n(1),u=(n(7),function(e){return Object(o.createElement)(a.TextControl,{value:e.text_metafield,label:Object(c.__)("Ad Link","cx-co-ads"),onChange:function(t){return e.onMetaFieldChange(t)}})});u=Object(l.withSelect)((function(e){return{text_metafield:e("core/editor").getEditedPostAttribute("meta").cx_co_ads_ad_link}}))(u),u=Object(l.withDispatch)((function(e){return{onMetaFieldChange:function(t){e("core/editor").editPost({meta:{cx_co_ads_ad_link:t}})}}}))(u);var d=function(e){var t=e.enabled,n=e.onUpdateRestrict;return Object(o.createElement)(a.ToggleControl,{label:"Enable Ads for this post.<small>(Ignore to use global settings)</small>",help:t?"Ads display enabled":"Ads display disabled",checked:t,onChange:function(e){n(e)}})};d=Object(l.withSelect)((function(e){return{enabled:e("core/editor").getEditedPostAttribute("meta").cx_co_ads_enable_ads}}))(d),d=Object(l.withDispatch)((function(e){return{onUpdateRestrict:function(t){e("core/editor").editPost({meta:{cx_co_ads_enable_ads:t}})}}}))(d),Object(r.registerPlugin)("plugin-document-setting-panel-adlink",{render:function(e){return Object(o.createElement)(i.PluginDocumentSettingPanel,{name:"cx-co-ads-adlink-panel",title:Object(c.__)("Advert link","cx-co-ads"),className:"cx-co-ads-adlink-panel"},Object(o.createElement)(d,null),Object(o.createElement)(u,null))},icon:"link"})},function(e,t){e.exports=window.wp.compose}]);