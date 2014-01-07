{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: cart_popup_div.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}
bootbox.dialog({
  message: "{{ html_var|filtre_javascript(true,false,true,true,false) }}",
  title: "{{ STR_MODULE_CART_POPUP_PRODUCT_ADDED|filtre_javascript(true,false,true) }}",
  buttons: {
	success: {
	  label: "{{ STR_SHOPPING|filtre_javascript(true,false,true) }}",
	  className: "btn-success",
	  callback: function() {
	  }
	},
	main: {
	  label: "{{ STR_CADDIE|filtre_javascript(true,false,true) }}",
	  className: "btn-primary",
	  callback: function() {
		window.location.href = "{{ caddie_href|filtre_javascript(true,false,true) }}";
		return false;
	  }
	}
  }
});