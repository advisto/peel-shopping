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
// $Id: cart_popup_content.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="popup_cart_middle" >
	<div class="popup_cart_title">{$STR_CADDIE}</div>
	<div class="popup_cart_content">
		<table>
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