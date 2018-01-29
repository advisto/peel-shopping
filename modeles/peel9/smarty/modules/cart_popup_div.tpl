{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: cart_popup_div.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}
bootbox.dialog({
	message: "{$html_var|filtre_javascript:true:false:true:true:false}",
	title: "{$STR_MODULE_CART_POPUP_PRODUCT_ADDED|filtre_javascript:true:false:true}",
	buttons: {
		{if !empty($link) && !empty($product_technical_code)} 
		"{$product_technical_code}" : {
			label: "{$label|filtre_javascript:true:false:true}",
			className: "btn-info",
			callback: function() {
				window.location.href = "{$link|filtre_javascript:true:false:true}";
				return false;
			}
		},
		{/if}
		success: {
			label: "{$success_label|filtre_javascript:true:false:true}",
			className: "btn-success",
			callback: function() {
			{if !empty($success_href)}
				window.location.href = "{$success_href|filtre_javascript:true:false:true}";
				return false;
			{/if}
			}
		},
		main: {
			label: "{$main_label|filtre_javascript:true:false:true}",
			className: "btn-primary",
			callback: function() {
			  window.location.href = "{$main_href|filtre_javascript:true:false:true}";
			  return false;
			}
		}
	}
});