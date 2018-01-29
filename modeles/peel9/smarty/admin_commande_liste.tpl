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
// $Id: admin_commande_liste.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if $return == 'full_html'}
<div class="entete">{$STR_ADMIN_COMMANDER_ORDERS_FOUND_COUNT}{$STR_BEFORE_TWO_POINTS}: {$links_nbRecord}</div>
<form id="search_form" class="entryform form-inline" role="form" method="get" action="{$action|escape:'html'}">
	<div style="margin-top: 15px; margin-bottom: 15px">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-6 center">
				<label for="search_id">{$STR_ORDER_NUMBER}{$STR_BEFORE_TWO_POINTS}:</label>
				<input type="text" class="form-control" id="search_id" name="id" value="{$id|str_form_value}" />
			</div>
			<div class="col-lg-4 col-md-4 col-sm-6 center">
				<label for="search_client_info">{$STR_EMAIL} / {$STR_LAST_NAME} / {$STR_FIRST_NAME}{$STR_BEFORE_TWO_POINTS}:</label>
				<input type="text" class="form-control" id="search_client_info" name="client_info" value="{$client_info|str_form_value}" />
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-lg-4 col-md-4 col-sm-6 center">
				<label for="search_searchProd">{$STR_ADMIN_COMMANDER_ORDERED_PRODUCT}{$STR_BEFORE_TWO_POINTS}:</label>
				<input type="text" class="form-control" id="search_searchProd" name="searchProd" value="{$searchProd|str_form_value}" />
			</div>
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-lg-4 col-md-4 col-sm-6 center">
				<label for="search_statut_paiement">{$STR_ORDER_STATUT_PAIEMENT}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_statut_paiement" name="statut_paiement">
					<option value="">{$STR_ADMIN_ALL_ORDERS}</option>
					{$payment_status_options}
				</select>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-lg-4 col-md-4 col-sm-6 center">
				<label for="search_statut_livraison">{$STR_ORDER_STATUT_LIVRAISON}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_statut_livraison" name="statut_livraison">
					<option value="">{$STR_ADMIN_ALL_ORDERS}</option>
					{$delivery_status_options}
				</select>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-6 center" style="padding-top:15px"><input type="hidden" name="mode" value="recherche" /><input type="submit" class="btn btn-primary" value="{$STR_SEARCH|str_form_value}" /></div>
		</div>
	</div>
</form>
	{if $is_fianet_sac_module_active}
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	<div class="center" style="margin-top: 27px;">
		<input class="btn btn-primary" type="submit" name="fianet_sac_update_status" value="{$STR_ADMIN_COMMANDER_FIANET_UPDATE|str_form_value}" />
	</div>
</form>
	{/if}
<form class="entryform form-inline" role="form" method="post" action="{$action2|escape:'html'}">
	{$form_token}
{/if}
{if isset($results)}
	{if $return == 'full_html'}
	<div class="alert alert-info">
		<img src="{$update_src|escape:'html'}" alt="" /> {$STR_ADMIN_COMMANDER_CLIENT_UPDATED_ICON_EXPLAIN}
	</div>
	<input type="hidden" name="mode" value="maj_statut" />
	<div class="table-responsive">
	{/if}
		<table id="tablesForm" class="table">
			{$links_header_row}
			{foreach $results as $res}
			{$res.tr_rollover}
			{if $return == 'full_html'}
				<td class="center">
					<a href="commander.php?mode=modif&amp;commandeid={$res.id}">{$STR_MODIFY}</a><br />
				{if $is_duplicate_module_active}
					<a href="{$res.dup_href|escape:'html'}" data-confirm="{$STR_ADMIN_ORDER_DUPLICATE_WARNING|str_form_value}" title="{$STR_ADMIN_ORDER_DUPLICATE|str_form_value}"><img src="{$res.dup_src|escape:'html'}" alt="" /></a>
				{/if}
				</td>
			{/if}
				<td class="center"><a href="commander.php?mode=modif&amp;commandeid={$res.id}">{$res.order_id}</a></td>
				<td class="center">{if !empty($res.numero)}<a href="commander.php?mode=modif&amp;commandeid={$res.id}">{$res.numero|default:'&nbsp;'}{/if}</a></td>
				<td class="center">{$res.date}</td>
				<td class="center">{$res.montant_prix}</td>
				<td class="center">{$res.avoir_prix}</td>
				<td class="center">{$res.modifUser}</td>
			{if $return == 'full_html'}
				<td class="center"><input type="checkbox" name="change_statut{$res.id}" id="checkbox_tbl_{$res.id}" value="1" /></td>
				<td class="center">{$res.payment_name}</td>
			{/if}
				<td class="center"><input type="hidden" name="id[]" value="{$res.id|str_form_value}" />
					{$res.payment_status_name}
				</td>
				{if !empty($res.picto_delivery_status_array)}
					<td class="center">
					{foreach $res.picto_delivery_status_array as $this_delivery_status_array}
						<img class="change_status change_status_{$res.id}" src="{$this_delivery_status_array.etat_src|escape:'html'}" alt="" onclick="{$this_delivery_status_array.etat_onclick|escape:'html'}" />
					{/foreach}
					</td>
				{else}
					<td class="center">{$res.delivery_status_name}</td>
				{/if}
			{if $is_fianet_sac_module_active}
				<td class="center"><center><table><tr><td>{$this_sac_status}</td></tr></table></center></td>
			{/if}
				<td class="center">{$res.site_name}</td>
			{if !empty($res.type)}
				<td class="center"><center><table><tr><td>{$res.type}</td></tr></table></center></td>
			{/if}
			</tr>
			{/foreach}
		</table>
	{if $return == 'full_html'}
	</div>
	<div style="margin-bottom: 10px; margin-top: 10px;">
		<div class="row center">
			<input type="button" value="{$STR_ADMIN_CHECK_ALL|str_form_value}" onclick="if (markAllRows('tablesForm')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
			<input type="button" value="{$STR_ADMIN_UNCHECK_ALL|str_form_value}" onclick="if (unMarkAllRows('tablesForm')) return false;" class="btn btn-info" />
		</div>
		<div class="row center" style="margin-top: 15px">
			<select class="form-control" name="statut_paiement" style="max-width: 200px">
				<option value="">- {$STR_ORDER_STATUT_PAIEMENT} -</option>
				{$payment_status_options2}
			</select>
			<select class="form-control" name="statut_livraison" style="max-width: 200px">
				<option value="">- {$STR_ORDER_STATUT_LIVRAISON} -</option>
				{$delivery_status_options2}
			</select>
			<input type="submit" value="{$STR_ADMIN_COMMANDER_UPDATED_STATUS_FOR_SELECTION|str_form_value}" class="btn btn-primary" />
		</div>
	</div>
		{if $is_module_genere_pdf_active}
	<div style="margin-bottom: 10px; margin-top: 10px;">
		<div class="row center">
			<input type="submit" name="export_pdf" value="{$STR_MODULE_FACTURES_ADMIN_TITLE|str_form_value}" class="btn btn-primary" />
		</div>
	</div>
		{/if}
		{if !empty($get_csv_export_from_html_table)}
	<div class="row center">
		<a href="{$get_current_url}?mode=export" class="btn btn-primary">{$STR_ADMIN_EXPORT}</a>
		</div>
		{/if}
	<div class="center">{$links_multipage}</div>
	{/if}
{else}
	<p>{$STR_ADMIN_COMMANDER_NO_ORDER_FOUND}</p>
{/if}
{if $return == 'full_html'}
</form>
{/if}