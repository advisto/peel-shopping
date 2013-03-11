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
// $Id: cart_popup_content.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<div class="popup_cart_top" style="background:url({$header_src}) no-repeat scroll left top transparent;">
	<div class="popup_cart_top_left">{$STR_MODULE_CART_POPUP_CART_POPUP_PRODUCT_ADDED}</div>
	<div class="popup_cart_top_right">
		<a class="close" href="javascript:close_interstitiel('popup_cart_container');">&nbsp;&nbsp;</a>
	</div>
</div>

<div class="popup_cart_middle" >
	<div class="popup_cart_title">{$STR_CADDIE}</div>
	<div class="popup_cart_content">
		<table cellpadding="5" width="60%" style="color:#6c6c6c;">
			<tr>
				<td class="left">{$STR_QUANTITY}{$STR_BEFORE_TWO_POINTS}:</td>
				<td class="center">{$count_products}</td>
			</tr>
			<tr>
				<td class="left">{$STR_AMOUNT}{$STR_BEFORE_TWO_POINTS}:</td>
				<td class="center">{if $display_prices_with_taxes_active}{$total} {$STR_TTC}{else}{$total_ht} {$STR_HT}{/if}</td>
			</tr>
		</table>
	</div>
</div>
<div class="popup_cart_bottom">
		<div class="popup_cart_bottom_left">
			<a class="popup_cart_close_link" href="javascript:close_interstitiel('popup_cart_container');">{$STR_SHOPPING}</a>
		</div>
		<div class="popup_cart_bottom_right">
			<a  class="popup_cart_caddie_link" href="{$caddie_href|escape:'html'}" onclick="close_interstitiel('popup_cart_container');">{$STR_CADDIE}</a>
		</div>
</div>