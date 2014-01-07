{* Smarty
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
// $Id: admin_best_sell_products.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_PRODUITS_ACHETES_MOST_WANTED}</div>
<div class="table-responsive">
	<table class="table admin_best_sell_products">
		<tr>
			<th class="menu">{$STR_PRODUCT}</th>
			<th class="menu">{$STR_ADMIN_PRODUITS_ACHETES_COUNT_IN_PREFERED}</th>
			<th class="menu" style="width:120px">{$STR_QUANTITY}</th>
			<th class="menu" style="width:120px">{$STR_AMOUNT}</th>
		</tr>
	{foreach $prods as $p}
		{$p.tr_rollover}
			<td>{$p.lien}</td>
			<td class="center">{$p.nombre}</td>
			<td class="right">{$p.quantite_totale}</td>
			<td class="right">{$p.prix}</td>
		</tr>
	{/foreach}
	</table>
</div>