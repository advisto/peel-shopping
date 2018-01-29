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
// $Id: admin_home_orders_desc2.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<h3>{$STR_ADMIN_INDEX_ORDERS_DESC2}{$STR_BEFORE_TWO_POINTS}:</h3>
<div class="table-responsive">
	<table class="table home_block_data_table">
		<tr>
			<th>{$STR_ADMIN_ID} / {$STR_ADMIN_NAME}</th>
			<th>{$STR_DATE}</th>
			<th>{$STR_TOTAL} {$ttc_ht}</th>
			<th>{$STR_PAYMENT}</th>
	{if $is_fianet_sac_module_active}
			<th>{$STR_ADMIN_INDEX_FIANET_VALIDATION}</th>
	{/if}
		</tr>
	{foreach $results as $res}
		{$res.tr_rollover}
			<td>{$res.id}<br />{$res.nom_bill}</td>
			<td>{$res.date}</td>
			<td>{$res.prix}</td>
			<td>{$res.statut_paiement}</td>
		{if $is_fianet_sac_module_active}
			<td class="center"><center><table><tr><td>{$this_order_sac_status}</td></tr></table></center></td>
		{/if}
		</tr>
	{/foreach}
	</table>
</div>