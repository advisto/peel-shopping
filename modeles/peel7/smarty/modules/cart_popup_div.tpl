{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: cart_popup_div.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<div id="popup_cart_container_background"></div>
<div id="popup_cart_container"></div>
<script><!--//--><![CDATA[//><!--
window.onload = function() {ldelim}
	var screen_width = getScreenInnerWidth();
	var screen_height = getScreenInnerHeight();
	var html_mil = "{$html_js_var}";
	var larg={$width|filtre_javascript:true:true:false};
	var haut={$height|filtre_javascript:true:true:false};
	var html_haut = "<table class=\"popup_cart\" id=\"popup_cart\" style=\"position:absolute; left:"+Math.max(0,Math.round((screen_width-larg)/2))+"px;top:"+Math.max(0,Math.round((screen_height-haut)/2-25))+"px;width:"+larg+"px;height:"+(haut)+"px;\">	<tr><td class=\"center top\">";
	var html_bas = "</td></tr></table>";
	show_interstitiel("popup_cart_container", "", html_haut, html_mil, html_bas);
{rdelim};
//--><!]]></script>