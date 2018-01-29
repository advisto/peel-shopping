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
// $Id: pensebete_display.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<h1 property="name">{$STR_MODULE_PENSEBETE_PENSE_BETE_PRODUIT}</h1>
{if $are_prods}
<div class="table-responsive">
	<table class="table table-striped table-hover reminder_array" aria-label="{$STR_TABLE_SUMMARY_CADDIE|str_form_value}">
		<thead>
			<tr>
				<th colspan="2" scope="col" class="center"></th>
				<th scope="col" class="center">{$STR_PRODUCT}</th>
				<th scope="col" class="center">{$STR_REMISE}</th>
				<th scope="col" class="center">{$STR_UNIT_PRICE} {$ttc_ht}</th>
			</tr>
		</thead>
		<tbody>
		{foreach $prods as $p}
			<tr>
				<td class="lignecaddie_suppression"><a href="{$p.del_href|escape:'html'}"><span class="glyphicon glyphicon-remove-sign" title="{$STR_DELETE_PROD_CART|str_form_value}" style="color: #FF0000; font-size:22px;"></span></a></td>
				<td class="lignecaddie_produit_image">
				{if !empty($p.img)}
					<a href="{$p.urlprod}"><img src="{$p.img}" alt="" /></a>
				{/if}
				</td>
				<td class="lignecaddie_produit_details"><a href="{$p.urlprod}">{$p.name|html_entity_decode}</a></td>
				<td class="lignecaddie_prix center">{if !empty($p.promotion)}{$p.promotion} % {else}-{/if}</td>
				<td class="lignecaddie_prix">{$p.prix}</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
</div>
{else}
<p>{$STR_MODULE_PENSEBETE_NO_PRODUCT_IN_REMINDER}</p>
{/if}