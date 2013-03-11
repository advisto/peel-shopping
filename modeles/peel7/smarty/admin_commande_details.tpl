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
// $Id: admin_commande_details.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="2">{$STR_ADMIN_COMMANDER_CREATE_OR_UPDATE_TITLE}</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
{if $action_name != "insere" AND $action_name != "ajout"}
	<tr>
		<td colspan="2">
			<table class="main_table">
				<tr>
					<td colspan="2">
						<p><b>{$STR_INVOICE|upper}{$STR_BEFORE_TWO_POINTS}:</b>
						<img src="{$pdf_src|escape:'html'}" width="8" height="11" alt="" /> <a href="{$facture_pdf_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_INVOICE} PDF</a>
						<img src="{$pdf_src|escape:'html'}" width="8" height="11" alt="" /> <a href="{$sendfacture_pdf_href|escape:'html'}" onclick="return confirm('{$STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL_CONFIRM|filtre_javascript:true:true:true}')">{$STR_ADMIN_COMMANDER_SEND_PDF_BILL_BY_EMAIL}</a>
					{if $is_module_factures_html_active}
						- <a href="{$facture_html_href|escape:'html'}">{$STR_INVOICE} HTML</a>
					{/if}
						</p>
						<p><b>{$STR_PROFORMA|upper}{$STR_BEFORE_TWO_POINTS}:</b>
							<img src="{$pdf_src|escape:'html'}" width="8" height="11" alt="" /> <a href="{$proforma_pdf_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_PROFORMA} PDF</a>
							<img src="{$pdf_src|escape:'html'}" width="8" height="11" alt="" /> <a href="{$sendproforma_pdf_href|escape:'html'}" onclick="return confirm('{$STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL_CONFIRM|filtre_javascript:true:true:true}')">{$STR_ADMIN_COMMANDER_SEND_PDF_PROFORMA_BY_EMAIL}</a>
						</p>
						<p><b>{$STR_QUOTATION|upper}{$STR_BEFORE_TWO_POINTS}:</b>
							<img src="{$pdf_src|escape:'html'}" width="8" height="11" alt="" /> <a href="{$devis_pdf_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_QUOTATION} PDF</a>
							<img src="{$pdf_src|escape:'html'}" width="8" height="11" alt="" /> <a href="{$senddevis_pdf_href|escape:'html'}" onclick="return confirm('{$STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL_CONFIRM|filtre_javascript:true:true:true}')">{$STR_ADMIN_COMMANDER_SEND_PDF_QUOTATION_BY_EMAIL}</a>
						</p>
						<p><b>{$STR_ORDER_FORM|upper}{$STR_BEFORE_TWO_POINTS}:</b> <img src="{$pdf_src|escape:'html'}" width="8" height="11" alt="" /> <a href="{$bdc_pdf_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_ORDER_FORM} PDF</a></p>
					{if $is_module_factures_html_active}
						<form method="post" action="{$bdc_action}">
							<p><b>{$STR_ORDER_FORM} HTML</b> {$STR_ADMIN_COMMANDER_WITH_PARTIAL_AMOUNT}
							<input type="hidden" name="bdc_code_facture" value="{$bdc_code_facture|str_form_value}" />
							<input type="hidden" name="bdc_id" value="{$bdc_id|str_form_value}" />
							<input type="hidden" name="bdc_mode" value="bdc" />
							<input type="text" id="bdc_partial" name="bdc_partial" value="{$bdc_partial|str_form_value}" style="width:70px" /> {$bdc_devise}{$STR_BEFORE_TWO_POINTS}:
							<a id="partial_amount_link" onclick="get_partial_amount_link('{$partial_amount_link_js}');" target="{$partial_amount_link_target}" class="bouton" href="{$partial_amount_link_href|escape:'html'}">{$STR_ADMIN_COMMANDER_OPEN_IN_BROWSER}</a>
							<input type="submit" name="bdc_sendclient" class="bouton" value="{$STR_ADMIN_SEND_TO_CLIENT_BY_EMAIL|str_form_value}" onclick="return confirm('{$STR_ADMIN_COMMANDER_SEND_BY_EMAIL_CONFIRM|filtre_javascript:true:true:true}');" /></p>
						</form>
					{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
	{if $is_fianet_sac_module_active}
		<tr>
			<td><b>{$STR_ADMIN_COMMANDER_FIANET_FUNCTIONS}</b>{$STR_BEFORE_TWO_POINTS}: {$fianet_analyse_commandes}</td>
		</tr>
	{/if}
</table>
<form method="post" action="{$action|escape:'html'}">
	<table class="admin_commande_details">
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{$STR_ADMIN_COMMANDER_INFORMATION_ON_THIS_ORDER}</td>
		</tr>
		<tr>
			<td>{$STR_ORDER_NUMBER}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$id}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$ecom_nom}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_COMMANDER_PAYMENT_DATE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" name="a_timestamp" class="datepicker" value="{$date_facture|str_form_value}" />
			</td>
		</tr>
		{if !empty($intracom_for_billing)}
		<tr>
			<td>{$STR_ADMIN_COMMANDER_VAT_INTRACOM}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$intracom_for_billing}</td>
		</tr>
		{/if}
		<tr>
			<td>{$STR_ADMIN_COMMANDER_ORDER_DATE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$commande_date}</td>
		</tr>
		<tr>
			<td>{$STR_BY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><a href="{$email_href|escape:'html'}">{$email}</a></td>
		</tr>
{else}
</table>
{if $is_tnt_module_active}
	{$etiquette_tnt}
{/if}
<form method="post" action="{$action|escape:'html'}">
	<table class="admin_commande_details">
{/if}
	<tr>
		<td>{$STR_ADMIN_COMMANDER_BILL_NUMBER}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="numero" value="{$numero|str_form_value}" /><br />{$STR_ADMIN_COMMANDER_BILL_NUMBER_EXPLAIN}</td>
	</tr>
	<tr>
		<td class="form_commande_detail">{$STR_ADMIN_COMMANDER_TRACKING_NUMBER}{$STR_BEFORE_TWO_POINTS}:</td>
		<td class="form_commande_detail">
			<input id="delivery_tracking" name="delivery_tracking" value="{$delivery_tracking|trim|str_form_value}" type="text" />
			{if $is_icirelais_module_active}<div id="tracking_url"></div><br /><a href="javascript:setTracking('{$module_shipping_icirelais_tracking_url_txt|filtre_javascript:true:true:true}','{$TEXT_COMMENT_TRACKING|filtre_javascript:true:true:true}','{$TEXT_ERROR_TRACKING|filtre_javascript:true:true:true}')">{$TEXT_CREATE_TRACKING}</a>{/if}
		</td>
	</tr>
	</tr>
	{if isset($payment_select)}
	<tr>
		<td width="350">{$STR_PAYMENT_MEAN}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$payment_select}</td>
	</tr>
	{else}
	<tr>
		<td colspan="2"><div class="global_help">{$STR_ADMIN_COMMANDER_PAYMENT_MEAN_EXPLAIN}</div></td>
	</tr>
	{/if}
	<tr>
		<td>{$STR_ORDER_STATUT_PAIEMENT}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>
			<select name="statut_paiement">{$payment_status_options}</select>
		</td>
	</tr>
	<tr>
		<td>{$STR_ORDER_STATUT_LIVRAISON}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>
			<select name="statut_livraison">{$delivery_status_options}</select>

		</td>
	</tr>
	{if !empty($mode_transport)}
	<tr>
		<td>{$STR_SHIPPING_TYPE}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>
			<select name="type_transport">
				{$delivery_type_options}
			</select>
			<input type="hidden" name="transport" value="{$transport|str_form_value}" />
		</td>
	</tr>
	<tr>
		<td>{$STR_SHIPPING_COST}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="cout_transport" value="{$cout_transport|str_form_value}" /> {$devise} {$STR_TTC} {$STR_ADMIN_INCLUDING_VAT} <select name="tva_transport">{$vat_select_options}</select><br />({$STR_ADMIN_COMMANDER_SHIPPING_COST_EXPLAIN})</td>
	</tr>
	{else}
	<tr>
		<td colspan="2">
			<input type="hidden" name="cout_transport" value="{$cout_transport|str_form_value}" />
			<input type="hidden" name="tva_transport" value="{$tva_transport|str_form_value}" />
			<input type="hidden" name="type_transport" value="{$type_transport|str_form_value}" />
			<input type="hidden" name="transport" value="{$transport|str_form_value}" />
		</td>
	</tr>
	{/if}
	{if $is_devises_module_active}
	<tr>
		<td>{$STR_ADMIN_USED_CURRENCY}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>
			<select name="devise">
			{foreach $devises_options as $o}
				<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
			{/foreach}
			</select>
			</td>
		</tr>
	{/if}
	<tr>
		<td>{$STR_ADMIN_COMMANDER_SMALL_ORDERS_OVERCOST}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="small_order_overcost_amount" value="{$small_order_overcost_amount|str_form_value}" /> {$devise} TTC dont TVA <input type="text" name="tva_small_order_overcost" value="{$tva_small_order_overcost|str_form_value}" /> {$devise}</td>
	</tr>
	<tr>
		<td>{$STR_ADMIN_COMMANDER_CURRENCY_EXCHANGE_USED}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="currency_rate" value="{$currency_rate|str_form_value}" /></td>
	</tr>
	<tr>
		<td>{$STR_ADMIN_COMMANDER_ORDER_TOTAL}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><b>{$montant_displayed_prix} {$ttc_ht}</b></td>
	</tr>
	{if isset($total_remise_prix)}
	<tr>
		<td>{$STR_ADMIN_COMMANDER_INCLUDING_DISCOUNT}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><b>{$total_remise_prix}</b></td>
	</tr>
	{/if}
	{if !empty($code_promo)}
	<tr>
		<td>{$STR_ADMIN_COMMANDER_COUPON_USED}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><b>{$code_promo}</b></td>
	</tr>
	{/if}
	<tr>
		<td>{$STR_ADMIN_COMMANDER_INCLUDING_CREDIT_NOTE}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input name="avoir" type="text" value="{$avoir_prix|str_form_value}" /> {$devise}</td>
	</tr>
	{if $is_affilie}
	<tr>
		<td class="label_rouge">{$STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION}</td>
		<td class="label_rouge"><strong>{$affilie_prix}</strong></td>
	</tr>
	<tr>
		<td class="label_rouge">{$STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS}</td>
		<td class="label_rouge">
		<select name="statut_affilie">
			<option value="0"{if $statut_affilie == 0} selected="selected"{/if}>{$STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS_TO_COME}</option>
			<option value="1"{if $statut_affilie == 1} selected="selected"{/if}>{$STR_ADMIN_COMMANDER_AFFILIATE_COMMISSION_PAYMENT_STATUS_DONE}</option>
		</select>
		</td>
	</tr>
	<tr>
		<td class="label_rouge">{$STR_ADMIN_COMMANDER_AFFILIATE_RELATED_TO_ORDER}</td>
		<td class="label_rouge"><a href="{$affilie_href|escape:'html'}">{$affilie_email}</a></td>
	</tr>
	{/if}
	<tr>
		<td>{$STR_ADMIN_COMMANDER_GIFT_POINTS}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>{$total_points} {$STR_GIFT_POINTS}<br />
			<input type="hidden" name="points" value="{$total_points|str_form_value}" />
			{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:
			<select name="points_etat">
				<option value="0"{if $points_etat == 0} selected="selected"{/if}>{$STR_ADMIN_COMMANDER_NOT_ATTRIBUTED}</option>
				<option value="1"{if $points_etat == 1} selected="selected"{/if}>{$STR_ADMIN_COMMANDER_ATTRIBUTED}</option>
				<option value="2"{if $points_etat == 2} selected="selected"{/if}>{$STR_ADMIN_COMMANDER_CANCELED}</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="label">{$STR_COMMENTS}{$STR_BEFORE_TWO_POINTS}:<br />
			<textarea name="commentaires" style="width:100%" rows="5" cols="54">{$commentaires|trim}</textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="bloc">{$STR_ADMIN_COMMANDER_CLIENT_INFORMATION}</td>
	</tr>
	{if $action_name == "modif"}
	<tr>
		<td>{$STR_ADMIN_COMMANDER_ORDER_AUTHOR_EMAIL}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input name="email" type="text" value="{$email|str_form_value}" /></td>
	</tr>
	{/if}
	<tr>
		<td colspan="2" class="label">{$STR_INVOICE_ADDRESS}</td>
	</tr>
	<tr>
		<td colspan="2"><p class="global_help">{$STR_ADMIN_COMMANDER_BILL_ADDRESS_EXPLAIN}</p></td>
	</tr>
{foreach $client_infos as $c}
	{if $c.value == 'ship'}
	<tr>
		<td colspan="2" class="label">{$STR_ADMIN_COMMANDER_SHIPPING_ADDRESS}</td>
	</tr>
	{/if}
	<tr>
		<td>{$STR_SOCIETE}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="societe{$c.i}" style="width:100%" value="{$c.societe|str_form_value}" /></td>
	</tr>
	<tr>
		<td>{$STR_LAST_NAME}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="nom{$c.i}" style="width:100%" value="{$c.nom|str_form_value}" /></td>
	</tr>
	<tr>
		<td>{$STR_FIRST_NAME}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="prenom{$c.i}" style="width:100%" value="{$c.prenom|str_form_value}" /></td>
	</tr>
	<tr>
		<td>{$STR_EMAIL}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="email{$c.i}" style="width:100%" value="{$c.email|str_form_value}" /></td>
	</tr>
	<tr>
		<td>{$STR_TELEPHONE}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="contact{$c.i}" style="width:100%" value="{$c.telephone|str_form_value}" /></td>
	</tr>
	<tr>
		<td>{$STR_ADDRESS}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><textarea name="adresse{$c.i}" class="textarea-formulaire">{$c.adresse}</textarea></td>
	</tr>
	<tr>
		<td>{$STR_ZIP}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="code_postal{$c.i}" style="width:100%" value="{$c.zip|str_form_value}" /></td>
	</tr>
	<tr>
		<td>{$STR_TOWN}{$STR_BEFORE_TWO_POINTS}:</td>
		<td><input type="text" name="ville{$c.i}" style="width:100%" value="{$c.ville|str_form_value}" /></td>
	</tr>
	<tr>
		<td>{$STR_COUNTRY}{$STR_BEFORE_TWO_POINTS}:</td>
		<td>
			<select name="pays{$c.i}">
				{$c.country_select_options}
			</select>
		</td>
	</tr>
{/foreach}
	<tr>
		<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="bloc">{$STR_ADMIN_COMMANDER_ORDERED_PRODUCTS_LIST}</td>
	</tr>
	<tr>
		<td colspan="2" class="label">
			<table class="admin_commande_details">
				<tr>
					<td colspan="9" class="label">{$STR_ADMIN_COMMANDER_PRICES_MUST_BE_IN_ORDER_CURRENCY}</td>
				</tr>
				<tr style="background-color:#EEEEEE">
					<td width="20"></td>
					<td class="label center" width="40">{$STR_ADMIN_ID}</td>
					<td class="label center" width="65">{$STR_REFERENCE}</td>
					<td class="label center">{$STR_ADMIN_COMMANDER_PRODUCT_NAME}</td>
					<td class="label center" width="70">{$STR_SIZE}</td>
					<td class="label center" width="70">{$STR_COLOR}</td>
					<td class="label center" width="40">{$STR_QUANTITY_SHORT}</td>
					<td class="label center" width="70">{$STR_ADMIN_COMMANDER_PRODUCT_LISTED_PRICE} {$ttc_ht} </td>
					<td class="label center" width="60">{$STR_REMISE} {$devise}</td>
					<td class="label center" width="40">{$STR_REMISE} %</td>
					<td class="label center" width="70">{$STR_UNIT_PRICE} {$ttc_ht}</td>
					<td class="label center" width="60">{$STR_ADMIN_VAT_PERCENTAGE}</td>
					<td class="label center" width="120">{$STR_ADMIN_CUSTOM_ATTRIBUTES}</td>
				</tr>
			</table>
			{foreach $order_lines as $o}
			{$o}
			{/foreach}
			<div id="dynamic_order_lines"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center">
			{if !empty($code_promo)}
				{if $percent_code_promo > 0}
				<input type="hidden" name="percent_code_promo" value="{$percent_code_promo|str_form_value}" />
				{elseif $valeur_code_promo > 0}
				<input type="hidden" name="valeur_code_promo" value="{$valeur_code_promo|str_form_value}" />
				{/if}
				<input type="hidden" name="code_promo" value="{$code_promo|str_form_value}" />
			{/if}
				{$form_token}
				<input type="hidden" name="action" value="{$action_name|str_form_value}" />
				<input type="hidden" name="id" value="{$id|str_form_value}" />
				<input type="hidden" name="id_utilisateur" value="{$id_utilisateur|str_form_value}" />
				<p><input id="nb_produits" type="hidden" name="nb_produits" value="{$nb_produits|str_form_value}" />
{if !empty($get_mode)}
	{if $get_mode == "insere" OR $get_mode == "ajout"}
					<input type="submit" value="{$STR_ADMIN_UTILISATEURS_CREATE_ORDER|str_form_value}" class="bouton" />
	{else}
		{if $is_order_modification_allowed}
					<input type="submit" value="{$STR_ADMIN_FORM_SAVE_CHANGES|str_form_value}" class="bouton" />
		{else}
					{$STR_ADMIN_COMMANDER_WARNING_EDITION_NOT_ALLOWED}
		{/if}
	{/if}
{else}
					<input type="submit" value="{$STR_ADMIN_FORM_SAVE_CHANGES|str_form_value}" class="bouton" />
{/if}
				</p>
			</td>
		</tr>
{if $is_order_modification_allowed}
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_COMMANDER_ADD_PRODUCTS_TO_ORDER}</td>
		</tr>
		<tr>
			<td colspan="2" style="vertical-align:top; height:200px; border: 1px #000000 dotted; background-color: #FAFAFA; padding:5px">
<script><!--//--><![CDATA[//><!--
{literal}
function delete_order_line(id) {
	document.getElementById("line"+id).style.display="none";
	document.getElementById("q"+id).value="";
	document.getElementById("p"+id).value="";
	document.getElementById("remis"+id).value="";
	document.getElementById("perc"+id).value="";
	document.getElementById("ref"+id).value="";
	document.getElementById("l"+id).value="";
	document.getElementById("s"+id).innerHTML = "";
	document.getElementById("c"+id).innerHTML = "";
	document.getElementById("t"+id).innerHTML = "";
	document.getElementById("line"+id).outerHTML="";
}
{/literal}
new_order_line_html='{$order_line_js|filtre_javascript:true:true:false}';
{literal}
function add_order_line(id, ref, nom, purchase_prix, quantite, size_options_html, color_options_html, tva_options_html, purchase_prix_ht, prix_cat, prix_cat_ht) {
	this_line_html=new_order_line_html;
	document.getElementById("nb_produits").value++;
	this_line_id=document.getElementById("nb_produits").value;
	var i = document.createElement('div');
	i.innerHTML+=this_line_html.replace(/\[i\]/g, this_line_id).replace(/\[id\]/g, id).replace(/\[ref\]/g, ref).replace(/\[nom\]/g, nom).replace(/\[quantite\]/g, quantite).replace(/\[remise\]/g, '').replace(/\[remise_ht\]/g, '').replace(/\[percent\]/g, '').replace(/\[prix_cat\]/g, prix_cat).replace(/\[prix_cat_ht\]/g, prix_cat_ht).replace(/\[purchase_prix\]/g, purchase_prix).replace(/\[size_options_html\]/g, size_options_html).replace(/\[color_options_html\]/g, color_options_html).replace(/\[tva_options_html\]/g, tva_options_html).replace(/\[purchase_prix_ht\]/g, purchase_prix_ht);
	document.getElementById("dynamic_order_lines").appendChild(i);
	order_line_calculate(this_line_id, 'final');
	document.getElementById("suggestions_input").value="";
	jQuery("#suggestions").show();
	jQuery("#suggestions").html("<p class=\"global_success\">{/literal}{$STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER}{literal}</div>");
}

function order_line_calculate(id, mode){
	var p_cat = document.getElementById("p_cat"+id).value;
	// vérifie la quantité saisie, quantite à 0 par défaut : l' id correspond au numéro de la ligne de produit
	if(isFinite(parseInt(document.getElementById("q"+id).value)) && parseInt(document.getElementById("q"+id).value)>=0){
		document.getElementById("q"+id).value = parseInt(document.getElementById("q"+id).value);
	}else{
		document.getElementById("q"+id).value = 0;
	}
	if(mode=='final' && document.getElementById("p"+id).value!="" && isFinite(parseFloat(document.getElementById("p"+id).value))){
		if(document.getElementById("p"+id).value>=0 && isFinite(parseFloat(p_cat)) && document.getElementById("p"+id).value<parseFloat(p_cat)){
			// On calcule la remise à partir du prix final
			document.getElementById("remis"+id).value = numberFormat(parseFloat(p_cat)-parseFloat(document.getElementById("p"+id).value), 5);
		}else{
			// On calcule le prix initial à partir du prix final si avoir
			document.getElementById("remis"+id).value = '0';
			document.getElementById("p_cat"+id).value = numberFormat(parseFloat(document.getElementById("p"+id).value), 5);
			p_cat = document.getElementById("p_cat"+id).value;
		}
	}
	if(isFinite(parseFloat(p_cat)) && (mode!='final' || document.getElementById("p"+id).value!="-") && (mode!='percentage' || document.getElementById("perc"+id).value!='-')){
		// calcule la réduction : l'id correspond au numéro de la ligne de produit
		if(mode=='percentage' && document.getElementById("perc"+id).value!="" && isFinite(parseFloat(document.getElementById("perc"+id).value))){
			// On ne limite pas la remise en pourcentage => si on veut limiter, on peut rajouter dans condition if ci-dessus :  && document.getElementById("perc"+id).value>=-100 && document.getElementById("perc"+id).value<=100
			document.getElementById("remis"+id).value= numberFormat((parseFloat(p_cat)*document.getElementById("perc"+id).value/100), 5);
		}else if(document.getElementById("remis"+id).value!="" && isFinite(parseFloat(document.getElementById("remis"+id).value))){
			// On ne limite pas la remise au montant p_cat pour permettre édition des cases facilitée dans désordre par utilisateur => si on veut limiter, on peut rajouter dans condition if ci-dessus : && Math.abs(document.getElementById("remis"+id).value)<=Math.abs(parseFloat(p_cat))
			document.getElementById("perc"+id).value= numberFormat((document.getElementById("remis"+id).value)/ parseFloat(p_cat)*100, 5);
		}else {
			document.getElementById("perc"+id).value='0';
			if(mode!='percentage' && document.getElementById("remis"+id).value!='-' && document.getElementById("remis"+id).value!=''){
				document.getElementById("remis"+id).value='0';
			}
		}
		if(isFinite(parseFloat(document.getElementById("remis"+id).value))){
			document.getElementById("p"+id).value = numberFormat(parseFloat(p_cat)-parseFloat(document.getElementById("remis"+id).value), 5);
		}else{
			document.getElementById("p"+id).value = numberFormat(parseFloat(p_cat), 5);
		}
	}
}
{/literal}
//--><!]]></script>
				<p style="margin-top:0px;"><input value="{$STR_ADMIN_ADD_EMPTY_LINE|str_form_value}" name="add_product" class="bouton" type="button" onclick="add_order_line(0, '', '', 0, 1, 0, 0, '', '', '', '{$default_vat_select_options|filtre_javascript:true:true:true}', 0, 0, 0); return false;" /> {$STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH}{$STR_BEFORE_TWO_POINTS}: <input type="text" id="suggestions_input" name="suggestions_input" style="width:200px" value="" onkeyup="lookup(this.value, '{$id_utilisateur}', '{$zone_tva}', '{$devise}', '{$currency_rate}');" onclick="lookup(this.value, '{$id_utilisateur}', '{$zone_tva}', '{$devise}', '{$currency_rate}');" /></p>
				<div class="suggestions" id="suggestions"></div>
			</td>
		</tr>
{/if}
	</table>
</form>
{if isset($parrainage_form)}
<form method="post" action="{$parrainage_form.action}">
	<input type="hidden" name="mode" value="parrain" />
	<input type="hidden" name="id" value="{$parrainage_form.id|str_form_value}" />
	<input type="hidden" name="id_parrain" value="{$parrainage_form.id_parrain|str_form_value}" />
	<input type="hidden" name="email_parrain" value="{$parrainage_form.email|str_form_value}" />
	<table class="main_table">
		<tr>
			<td colspan="2" class="entete">{$STR_ADMIN_COMMANDER_SPONSORSHIP_MODULE}</td>
		</tr>
		<tr>
			<td colspan="2">
				{$STR_ADMIN_COMMANDER_ORDER_EMITTED_BY_GODCHILD} <a href="{$parrainage_form.href|escape:'html'}">{$parrainage_form.email}</a>.<br />
				{$STR_ADMIN_COMMANDER_THANK_SPONSOR_WITH_CREDIT_OF} <input type="text" size="5" maxlength="3" name="avoir" value="{$site_avoir|str_form_value}" /> {$site_symbole} {$STR_TTC}<br />
				{$STR_ADMIN_COMMANDER_THANK_SPONSOR_WITH_CREDIT_EXPLAIN}
			</td>
		</tr>
		<tr>
			<td class="center" colspan="2">
				<input type="submit" class="bouton" value="{$STR_ADMIN_COMMANDER_GIVE_CREDIT|str_form_value}" />
			</td>
		</tr>
	</table>
</form>
{/if}