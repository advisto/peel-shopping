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
// $Id: products_ordered_history.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}
<h1 class="liste_commandes">{$STR_PRODUCTS_PURCHASED_LIST}</h1>
{if isset($STR_NO_ORDER)}
<div><p>{$STR_NO_ORDER}</p></div>
{else}
<div class="table-responsive">
	<table class="table">
		{$links_header_row}
		{foreach $products as $prod}
		<tr style="background-color: #{cycle values="F4F4F4,ffffff"}">
			<td class="center">{if !empty($prod.href_produit)}<a href="{$prod.href_produit}">{/if}{$prod.nom_produit}{if !empty($prod.href_produit)}</a>{/if}</td>
			<td class="center">{$prod.quantite}</td>
			<td class="center">{$prod.o_timestamp}</td>
			<td class="center">{$prod.numero}</td>
		</tr>
		{/foreach}
	</table>
</div>
<div>{$links_multipage}</div>
{/if}