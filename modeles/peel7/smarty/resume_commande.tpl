{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: resume_commande.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<h2>{$STR_ORDER_DETAIL}</h2>
<table class="full_width" cellpadding="3">
	<caption></caption>
	<tr>
		<td>{$STR_ORDER_NUMBER}{$STR_BEFORE_TWO_POINTS}: </td>
		<td>{$id}</td>
	</tr>
	<tr>
		<td>{$STR_DATE}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$date}</td>
	</tr>
	<tr>
		<td>{$STR_AMOUNT}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><b>{$order_amount} {$STR_TTC}</b></td>
	</tr>
	<tr>
		<td>{$STR_INVOICE_ADDRESS}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$bill_address|nl2br_if_needed}</td>
	</tr>
{if isset($ship_address)}
	<tr>
		<td>{$STR_SHIP_ADDRESS}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$ship_address|nl2br_if_needed}</td>
	</tr>
{/if}
	<tr>
		<td>{$STR_PAYMENT}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$payment}</td>
	</tr>
{if isset($shipping_type)}
	<tr>
		<td>{$STR_SHIPPING_TYPE}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$shipping_type|html_entity_decode_if_needed}</td>
	</tr>
{/if}
{if $is_delivery_tracking}
	<tr>
		<td>{$STR_TRACKING_LINK}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$delivery_tracking}
			{if isset($icirelais)}
			<script src="{$icirelais.src|escape:'html'}"></script>
			<input id="delivery_tracking" name="delivery_tracking" type="hidden" value="{$icirelais.value|str_form_value}" />
			<div id="tracking_url"></div><a href="javascript:setTracking('{$module_shipping_icirelais_tracking_url_txt|filtre_javascript:true:true:true}','{$TEXT_COMMENT_TRACKING|filtre_javascript:true:true:true}','{$TEXT_ERROR_TRACKING|filtre_javascript:true:true:true}')">{$TEXT_CREATE_TRACKING}</a>
			{/if}

		</td>
	</tr>
{/if}
{if isset($tnt_message)}
	<tr>
		<td colspan="2"><h2>{$STR_MODULE_TNT_FEASIBILITY_REPORT}{$before_two_points}:</h2></td>
	</tr>
	<tr>
		<td colspan="2">{$tnt_message}{if isset($tnt_erreur_message)}{$tnt_erreur_message}{/if}</td>
	</tr>
{/if}
{if $is_payment_delivery_status}
	<tr>
		<td>{$STR_ORDER_STATUT_PAIEMENT}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$order_statut_paiement_name}</td>
	</tr>
	<tr>
		<td>{$STR_ORDER_STATUT_LIVRAISON}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$order_statut_livraison_name}</td>
	</tr>
	{if isset($invoice)}
	<tr>
		<td>{$STR_INVOICE}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><img src="{$invoice.src|escape:'html'}" width="8" height="11" alt="" /><a href="{$invoice.href|escape:'html'}">{$STR_PRINT_YOUR_BILL}</a></td>
	</tr>
	{/if}
{/if}
	<tr>
		<td colspan="2"><h2>{$STR_LIST_PRODUCT}</h2></td>
	</tr>
	<tr>
		<td colspan="2">
			<table class="caddie">
				<tr>
					<th>{$STR_REFERENCE}</th>
					<th>{$STR_PRODUCT}</th>
					<th>{$STR_SOLD_PRICE}</th>
					<th>{$STR_QUANTITY}</th>
					{if $is_conditionnement_module_active}<th>{$STR_CONDITIONNEMENT}</td><td class="center">{$STR_CONDITIONNEMENT_QTY}</th>{/if}
					<th>{$STR_TOTAL_TTC}</th>
					{if isset($STR_MODULE_PAYBACK_RETURN_REQUEST)}<th>{$STR_MODULE_PAYBACK_RETURN_REQUEST}</th>{/if}
				</tr>
				{foreach $products_data as $pd}
				<tr>
					<td class="lignecaddie" align="center">{$pd.reference}</td>
					<td class="lignecaddie" align="center">{$pd.product_text}</td>
					<td class="lignecaddie" align="center">{$pd.prix}</td>
					<td class="lignecaddie" align="center">{$pd.quantite}</td>
					{if $is_conditionnement_module_active}<td class="lignecaddie" align="center">{$pd.conditionnement}</td><td class="lignecaddie" align="center">{$pd.conditionnement_qty}</td>{/if}
					<td class="lignecaddie" align="center" style="width:71px;">{$pd.total_prix}</td>
					{if $pd.is_form_retour}
					<td class="lignecaddie" align="center">
						<form method="post" action="{$pd.action}">
							<input type="hidden" name="commandeid" value="{$pd.commandeid|intval}" />
							<input type="hidden" name="utilisateurid" value="{$pd.utilisateurid|intval}" />
							<input type="hidden" name="paiement" value="{$pd.paiement|str_form_value}" />
							<input type="hidden" name="langue" value="{$pd.langue|str_form_value}" />
							<input type="hidden" name="nom_produit" value="{$pd.nom_produit|str_form_value}" />
							<input type="hidden" name="qte_produit" value="{$pd.quantite|str_form_value}" />
							<input type="hidden" name="taille_produit" value="{$pd.taille_produit|str_form_value}" />
							<input type="hidden" name="couleur_produit" value="{$pd.couleur_produit|str_form_value}" />
							<input type="hidden" name="id_produit" value="{$pd.id_produit|intval}" />
							<input type="hidden" name="prix_ht_produit" value="{$pd.prix_ht_produit|str_form_value}" />
							<input type="hidden" name="prix_ttc_produit" value="{$pd.prix_ttc_produit|str_form_value}" />
							<input type="hidden" name="tva_produit" value="{$pd.tva_produit|str_form_value}" />
							<input type="submit" class="clicbouton" value="{$return_this_product_txt|str_form_value}" />
						</form>
					</td>
					{/if}
				</tr>
				{/foreach}
			</table>
		</td>
	</tr>
</table>