{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_commande_liste.tpl 37953 2013-08-29 14:19:34Z sdelaporte $
*}<table class="full_width">
	<tr>
		<td class="entete">{$STR_ADMIN_COMMANDER_ORDERS_FOUND_COUNT}{$STR_BEFORE_TWO_POINTS}: {$links_nbRecord}</td>
	</tr>
	<tr>
		<td>
			<form method="get" action="{$action|escape:'html'}">
				<table class="full_width center">
					<tr>
						<td>{$STR_ORDER_NUMBER}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$STR_EMAIL} / {$STR_LAST_NAME} / {$STR_FIRST_NAME}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$STR_ADMIN_COMMANDER_ORDERED_PRODUCT}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$STR_ORDER_STATUT_PAIEMENT}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$STR_ORDER_STATUT_LIVRAISON}{$STR_BEFORE_TWO_POINTS}:</td>
						<td></td>
					</tr>
					<tr>
						<td><input type="number" name="id" value="{$id|str_form_value}" /></td>
						<td><input type="text" name="client_info" value="{$client_info|str_form_value}" /></td>
						<td><input type="search" name="searchProd" value="{$searchProd|str_form_value}" /></td>
						<td>
							<select name="statut_paiement">
								<option value="">{$STR_ADMIN_ALL_ORDERS}</option>
								{$payment_status_options}
							</select>
						</td>
						<td>
							<select name="statut_livraison">
								<option value="">{$STR_ADMIN_ALL_ORDERS}</option>
								{$delivery_status_options}
							</select>
						</td>
						<td class="center"><input type="hidden" name="mode" value="recherche" /><input type="submit" class="bouton" value="{$STR_SEARCH|str_form_value}" /></td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
{if $is_fianet_sac_module_active}
	<tr>
		<td>
			<form method="post" action="{$action|escape:'html'}">
				<center>
					<div style="margin-top: 27px;">
						<input class="bouton" type="submit" name="fianet_sac_update_status" value="{$STR_ADMIN_COMMANDER_FIANET_UPDATE|str_form_value}" />
					</div>
				</center>
			</form>
		</td>
	</tr>
{/if}
</table>
<form method="post" action="{$action2|escape:'html'}">
	{$form_token}
{if isset($results)}
	<div class="global_help"><img src="{$update_src|escape:'html'}" alt="" /> {$STR_ADMIN_COMMANDER_CLIENT_UPDATED_ICON_EXPLAIN|str_form_value}</div>

	<table class="full_width">
	{if $is_duplicate_module_active}
		<tr>
			<td colspan="13"><b>{$STR_NOTA_BENE}{$STR_BEFORE_TWO_POINTS}:</b> {$STR_ADMIN_ORDER_DUPLICATE_WARNING}</td>
		</tr>
	{/if}
		<tr>
			<td class="right">
				<input type="hidden" name="mode" value="maj_statut" />
				<table id="tablesForm" class="full_width" cellpadding="2">
					{$links_header_row}
					{foreach $results as $res}
					{$res.tr_rollover}
						<td class="center">
							<a href="commander.php?mode=modif&amp;commandeid={$res.order_id}">{$STR_MODIFY}</a><br />
						{if $is_duplicate_module_active}
							<a title="{$STR_ADMIN_ORDER_DUPLICATE|str_form_value}" href="{$res.dup_href|escape:'html'}"><img src="{$res.dup_src|escape:'html'}" alt="" /></a>
						{/if}
						</td>
						<td class="center">{$res.order_id}</td>
						<td class="center">{$res.numero|default:'&nbsp;'}</td>
						<td class="center">{$res.date}</td>
						<td class="center">{$res.montant_prix}</td>
						<td class="center">{$res.avoir_prix}</td>
						<td class="center">{$res.modifUser}</td>
						<td class="center"><input type="checkbox" name="change_statut{$res.order_id}" id="checkbox_tbl_{$res.order_id}" value="1" /></td>
						<td class="center">{$res.payment_name}</td>
						<td class="center"><input type="hidden" name="id[]" value="{$res.order_id|str_form_value}" />
							{$res.payment_status_name}
						</td>
						<td class="center">{$res.delivery_status_name}</td>
					{if $is_fianet_sac_module_active}
						<td class="center"><center><table><tr><td>{$this_sac_status}</td></tr></table></center></td>
					{/if}
					</tr>
					{/foreach}
				</table>
			</td>
		</tr>
		<tr>
			<td class="center">
				<p>
					<input type="button" value="{$STR_ADMIN_CHECK_ALL|str_form_value}" onclick="if (markAllRows('tablesForm')) return false;" class="bouton" />&nbsp;&nbsp;&nbsp;
					<input type="button" value="{$STR_ADMIN_UNCHECK_ALL|str_form_value}" onclick="if (unMarkAllRows('tablesForm')) return false;" class="bouton" />&nbsp;&nbsp;&nbsp;
					<select name="statut_paiement">
						<option value="">- {$STR_ORDER_STATUT_PAIEMENT} -</option>
						{$payment_status_options2}
					</select>
					<select name="statut_livraison">
						<option value="">- {$STR_ORDER_STATUT_LIVRAISON} -</option>
						{$delivery_status_options2}
					</select>
					<input type="submit" value="{$STR_ADMIN_COMMANDER_UPDATED_STATUS_FOR_SELECTION|str_form_value}" class="bouton" />
				</p>
			</td>
		</tr>
		<tr><td class="center">{$links_multipage}</td></tr>
	</table>
{else}
	<p>{$STR_ADMIN_COMMANDER_NO_ORDER_FOUND}</p>
{/if}
</form>