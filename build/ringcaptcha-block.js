(()=>{"use strict";const e=window.wp.blocks,t=window.wp.element,n=(0,t.forwardRef)((function({icon:e,size:n=24,...i},a){return(0,t.cloneElement)(e,{width:n,height:n,...i,ref:a})})),i=window.React,a=window.wp.primitives,o=(0,i.createElement)(a.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,i.createElement)(a.Path,{fillRule:"evenodd",d:"M5 5.5h14a.5.5 0 01.5.5v1.5a.5.5 0 01-.5.5H5a.5.5 0 01-.5-.5V6a.5.5 0 01.5-.5zM4 9.232A2 2 0 013 7.5V6a2 2 0 012-2h14a2 2 0 012 2v1.5a2 2 0 01-1 1.732V18a2 2 0 01-2 2H6a2 2 0 01-2-2V9.232zm1.5.268V18a.5.5 0 00.5.5h12a.5.5 0 00.5-.5V9.5h-13z",clipRule:"evenodd"})),c=window.wp.i18n,r=window.wp.blockEditor,l=window.wp.components,d=function(e){var n=e.attributes,i=e.setAttributes,a=e.setPhoneNumber;return n.phoneVerification,(0,t.useEffect)((function(){var e=(navigator.language||"en").split(",")[0].substr(0,2);console.log("Widget useEffect triggered");var t=rc_options.gdpr_implementation||!1,n=rc_options.js_implementation||!1,o=rc_options.app_key||"";rc_options.gdpr_consent_message,t?(document.getElementById("widget-point").innerHTML='<div id="xyz" data-widget data-locale="'+e+'" data-type="dual" data-mode="signup"></div>',document.getElementById("gdpr_consent").value="false",n&&(document.getElementById("ringcaptcha_verified").value="false"),new RingCaptcha.Widget("#xyz",{app:o,locale:e,events:{verified:function(){document.getElementById("ringcaptcha_verified").value="true",i("verified")}}}).setup()):n?(document.getElementById("widget-point").innerHTML='<div id="xyz" data-widget data-locale="'+e+'" data-type="dual" data-mode="signup"></div>',document.getElementById("ringcaptcha_verified").value="false",new RingCaptcha.Widget("#xyz",{app:o,locale:e,events:{verified:function(){document.getElementById("ringcaptcha_verified").value="true",i("verified")}}}).setup()):(document.getElementById("widget-point").innerHTML='<div data-widget data-app="'+o+'" data-locale="'+e+'" data-type="dual"></div>',new RingCaptcha.Widget("[data-widget]",{app:o,locale:e,events:{verified:function(){document.getElementById("ringcaptcha_verified").value="true";var e=document.getElementsByName("ringcaptcha_phone_number");i("verified"),a(e[0].value)}}}).setup()),document.querySelectorAll(".panel.woocommerce_options_panel").forEach((function(e){console.log("Panel ID:",e.id,"Display:",e.style.display)}))}),[]),React.createElement("div",null,React.createElement("div",{id:"widget-point"}),React.createElement("input",{id:"gdpr_consent",type:"hidden",name:"gdpr_consent",value:"false"}),React.createElement("input",{id:"ringcaptcha_verified",type:"hidden",name:"ringcaptcha_verified",value:"false"}))};console.log("RingCaptcha Block: edit.js loaded");const p=JSON.parse('{"apiVersion":2,"name":"ringcaptcha/ringcaptcha-block","version":"1.0.0","title":"Phone Verification Block","category":"woocommerce","description":"A block for phone verification on the checkout page.","supports":{"html":false,"align":false,"multiple":false,"reusable":false},"parent":["woocommerce/checkout-shipping-address-block"],"attributes":{"lock":{"type":"object","default":{"remove":true,"move":true}}},"textdomain":"woo-phone-verification-on-checkout","editorScript":"file:../../../build/ringcaptcha-block.js","editorStyle":"file:../../../build/style-ringcaptcha-block.css"}');console.log("RingCaptcha Block: index.js loaded"),(0,e.registerBlockType)(p,{icon:{src:React.createElement(n,{icon:o})},edit:function(e){var t=e.attributes,n=e.setAttributes,i=t.phoneVerification,a=(0,r.useBlockProps)();return React.createElement("div",a,React.createElement(r.InspectorControls,null,React.createElement(l.PanelBody,{title:(0,c.__)("Phone Verification Settings","woo-phone-verification-on-checkout")},React.createElement(l.TextControl,{label:(0,c.__)("Verification Code","woo-phone-verification-on-checkout"),value:i,onChange:function(e){return n({phoneVerification:e})}}))),React.createElement(d,{attributes:t,setAttributes:n}))},save:function(e){var t=e.attributes.phoneVerification;return React.createElement("div",r.useBlockProps.save(),React.createElement("p",null,t))}})})();