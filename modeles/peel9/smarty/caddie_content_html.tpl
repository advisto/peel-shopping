{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: caddie_content_html.tpl 64741 2020-10-21 13:48:51Z sdelaporte $
*}<div class="totalcaddie">
	{if $is_empty}
	<p>{$STR_EMPTY_CADDIE}</p>
	{else}
		{if isset($global_error)}
		<div class="alert alert-danger">{$global_error}</div>
		{/if}
		{$erreur_caddie}
		{if !empty($zone_error)}{$zone_error}{/if}
		{if !empty($shipping_type_error)}{$shipping_type_error}{/if}
		{if !empty($minimum_error)}{$minimum_error}{/if}
		<form class="entryform form-inline" role="form" id="caddieFormArticle" method="post" action="{$action|escape:'html'}">
			<input type="hidden" value="commande" name="func" id="form_mode" />
			<div class="row">
				{$products_summary_table}
				<div class="col-sm-6">
				{if $enable_code_promo}
					<div class="code_promo">
					{if isset($code_promo)}
						<div class="input-group">
							<span class="input-group-addon"><label for="code_promo">{$code_promo.txt}{$STR_BEFORE_TWO_POINTS}: </label></span>
							<input type="text" class="form-control" id="code_promo" name="code_promo" value="{$code_promo.value|upper|str_form_value}" />
							<span class="input-group-addon"><a href="#" onclick="return frmsubmit('recalc')"><span class="glyphicon glyphicon-refresh"></span></a></span>
						</div>
						{if isset($code_promo_delete)}
						<div><a href="{$code_promo_delete.href|escape:'html'}"><img src="{$code_promo_delete.src|escape:'html'}" alt="x" /></a> <a href="{$code_promo_delete.href|escape:'html'}">{$code_promo_delete.txt} {$code_promo.value}</a></div>
						{/if}
					{else}
						<div class="caddie_bold">
							<a class="notice" href="{$membre_href|escape:'html'}" title="{$STR_LOGIN_FOR_REBATE|str_form_value}">{$STR_PLEASE_LOGIN}</a> {$STR_REBATE_NOW}
						</div>
					{/if}
				{if !empty($user_tva_intracom)}
					<br />
					<div class="input-group">
						<span class="input-group-addon"><label for="intracom_for_billing">{$user_tva_intracom.txt}{$STR_BEFORE_TWO_POINTS}: </label></span>
						<input type="text" class="form-control" id="intracom_for_billing" name="intracom_for_billing" value="{$user_tva_intracom.value|upper|str_form_value}" />
						<span class="input-group-addon"><a href="#" onclick="return frmsubmit('recalc')"><span class="glyphicon glyphicon-refresh"></span></a></span>
					</div>
					{if $intracom_for_billing_error}
						<div>{$intracom_for_billing_error}</div>
					{/if}
				{/if}
				{if isset($captcha)}
				<table>
					<tr>
						<td class="left">{$captcha.validation_code_txt}{$STR_BEFORE_TWO_POINTS}:</td>
						<td>{$captcha.inside_form}</td>
					</tr>
					<tr>
						<td class="left">{$captcha.validation_code_copy_txt} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
						<td><input name="code" type="text" class="form-control" size="5" maxlength="5" id="code" value="{$captcha.value|str_form_value}" />{$captcha.error}</td>
					</tr>
				</table>
				{/if}
						<div style="padding-top:15px; padding-bottom:15px">
							<a href="#" onclick="return frmsubmit('recalc')"{if !empty($shipping_text)} data-toggle="tooltip" title="{$shipping_text|str_form_value}"{/if} class="tooltip_link btn btn-success"><span class="glyphicon glyphicon-refresh"></span> {$STR_UPDATE}</a>
						</div>
					</div>
				{/if}
				{if $is_mode_transport}
					<div class="livraison well">
						<fieldset>
							<legend>{$STR_DELIVERY}</legend>
							<div id="choix_zone">
								{if $display_pays_zone_select}
								<p class="caddie_bold">{$STR_SHIPPING_ZONE}&nbsp;<span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}: {$zone_error}
									{if $shipping_zone_display_mode !='radio'}
									<select class="form-control" name="pays_zone" onchange="return frmsubmit('recalc')">
										<option value="">{$STR_SHIP_ZONE_CHOOSE}</option>
										{foreach $zone_options as $zo}
										<option value="{$zo.value|str_form_value}"{if $zo.issel} selected="selected"{/if}>{$zo.name|html_entity_decode_if_needed}</option>
										{/foreach}
									</select>
									{else}
										<table class="shipping_type_display_mode">
										{foreach $zone_options as $zo}
										<tr>
											<td>
												<input type="radio" name="pays_zone" value="{$zo.value|str_form_value}" {if $zo.issel} checked="checked"{/if} onchange="return frmsubmit('recalc')"  />
											</td>
											<td>
												{$zo.name|html_entity_decode_if_needed}
											</td>
										</tr>
										{/foreach}
										</table>
									{/if}
								</p>
								{else}
									<input type="hidden" value="{$zoneId}" name="pays_zone" />
								{/if}
								{if !empty($zone)}
								<p>{$STR_SHIPPING_ZONE}{$STR_BEFORE_TWO_POINTS}: {$zone}</p>
								{/if}
								<p class="caddie_bold">
									{if $is_zone}
										{if isset($shipping_type_options)}
											{$STR_SHIPPING_TYPE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}: {$shipping_type_error}
											{if $shipping_type_display_mode !='radio'}
											<select class="form-control" name="type" onchange="return frmsubmit('recalc')">
												<option value="">{$STR_SHIP_TYPE_CHOOSE}</option>
												{foreach $shipping_type_options as $sto}
												<option value="{$sto.value|str_form_value}"{if $sto.issel} selected="selected"{/if}>{$sto.name|html_entity_decode_if_needed}</option>
												{/foreach}
											</select>
											{else}
											<table class="shipping_type_display_mode">
												{foreach $shipping_type_options as $sto}
												<tr>
													<td>
														<input onchange="return frmsubmit('recalc')" type="radio" name="type" value="{$sto.value|str_form_value}" {if $sto.issel} checked="checked"{/if} />
													</td>
													<td {if empty($sto.picture)}colspan="2"{/if}>{$sto.name|html_entity_decode_if_needed}</td>
													{if !empty($sto.picture)}
													<td>
														<img src="{$sto.picture}" alt="{$sto.name|str_form_value}" />
													</td>
													{/if}
												</tr>
													{if !empty($sto.text)}
													<tr><td colspan="3"> {$sto.text|str_form_value} </td></tr>
													{/if}
												{/foreach}
											</table>
											{/if}
										{else}
											<span style="color:red;">{$STR_ERREUR_TYPE}</span><br />
										{/if}
									{/if}
								</p>
							</div>
						</fieldset>
					</div>
				{/if}
				{if !empty($payment_multiple)}
					<div class="paiement well">
						<fieldset>
							<legend>{$STR_PAYMENT}</legend>
					{foreach $payment_multiple as $nb}
						<input type="radio" name="payment_multiple" value="{$nb}" {if $nb == 1}checked="checked"{/if} />{$nb}x&nbsp;
					{/foreach}
						</fieldset>
					</div>
				{/if}	
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 center">
					<table class="table_order">
						<tr>
							<td colspan="2">
								{if isset($STR_SUGGEST)}
								<div class="center"><p>{$STR_SUGGEST}</p></div>
								{/if}
								{if $is_minimum_error}
									{if !empty($minimum_produit)}
								<p class="center">
									{$minimum_produit}{$STR_MINIMUM_PRODUCT}
								</p>
									{else}
								<p class="center">
									{$STR_MINIMUM_PURCHASE_OF}{$minimum_prix}{$STR_REQUIRED_VALIDATE_ORDER}
								</p>
									{/if}
								{else}
									{if isset($devis_url) || !empty($devis_by_privilege)}<a href="{$devis_url|escape:'html'}" class="btn btn-lg btn-warning button_order">{$STR_DEVIS}</a>{/if}
								<p class="center">
									{if !empty($recommanded_product_on_cart_page)}
										{$recommanded_product_on_cart_page}
									{elseif !empty($STR_ORDER) && empty($devis_by_privilege)}
									<button type="submit" class="tooltip_link btn btn-lg btn-primary submit-once-only"{if !empty($shipping_text)} data-toggle="tooltip" title="{$shipping_text|str_form_value}"{/if} onclick="return frmsubmit('commande')">{$STR_ORDER} <span class="glyphicon glyphicon-chevron-right"></span></button>
									{/if}
								</p>
								{/if}
							</td>
						</tr>
					{if $is_cart_preservation_module_active}
						<tr>
							<td colspan="2">
								<a class="cart_preservation_link btn btn-info" href="{$preservation_href|escape:'html'}" ><span class="glyphicon glyphicon-save"></span> {$STR_SAVE_CART}</a>
							</td>
						</tr>
					{/if}
					{if !empty($add_cart_to_comparator_url)}
						<tr>
							<td colspan="2">
								{$add_cart_to_comparator_url}
							</td>
						</tr>
					{/if}
					{if $export_product_list_to_pdf}
						<tr>
							<td colspan="2">
								<a class="cart_preservation_link btn btn-info" href="{$genere_pdf_href|escape:'html'}" target="_blank" ><img src="{$wwwroot}/images/logoPDF_small.png" style="width:50px;" target="_blank" /> {$STR_MODULE_FACTURES_ADVANCED_EXPORT_LIST_PDF}</a>
							</td>
						</tr>
					{/if}
						<tr>
							<td class="td_caddie_link_shopping">
								<a href="{$shopping_href|escape:'html'}" class="caddie_link btn btn-success"><span class="glyphicon glyphicon-chevron-left"></span> {$STR_SHOPPING}</a>
							</td>
							<td class="td_caddie_link_empty_cart">
								<a href="{$empty_list_href|escape:'html'}" data-confirm="{$STR_EMPTY_CART|str_form_value}" class="caddie_link btn btn-warning"><span class="glyphicon glyphicon-remove"></span> {$STR_EMPTY_LIST}</a>
							</td>
						</tr>
						{if isset($short_order_process_by_estimate)}
						<tr>
							<td colspan="2">
								<a class="cart_preservation_link btn btn-info" style="background-color: #999;
border-color: #999;" href="{$shopping_href|escape:'html'}achat_maintenant.php?short_order_process=1" ><span class="glyphicon glyphicon-save"></span> {$STR_CARD_ESTIMATE}</a>
							</td>
						</tr>
						{/if}
					</table>
				</div>
			</div>
		</form>
	{/if}
	{if !empty($display_quote_list)}
		<div style="margin-top: 100px;">
			<h2 class="page_title">{$STR_QUOTE_LIST_TITLE}</h2>
			<div class="table-responsive">
				<table class="table">
					{$links_header_row}
					{foreach $order_array as $this_order}
					<tr style="background-color: #{cycle values="F4F4F4,ffffff"}">
						<td class="center">
							<a href="{$this_order.href|escape:'html'}"><img src="{$this_order.info_src|escape:'html'}" width="21" height="21" alt="info" /></a>
						{if !empty($this_order.devis_href)}
							<br /><a onclick="return(window.open(this.href)?false:true);" href="{$this_order.devis_href|escape:'html'}" style="white-space: nowrap;"><img src="{$this_order.pdf_src|escape:'html'}" width="8" height="11" alt="" />&nbsp;{$STR_PDF_QUOTATION}</a>
						{/if}
						</td>
						<td class="center">{$this_order.numero}</td>
						<td class="center">{$this_order.o_timestamp}</td>
						<td class="center">{$this_order.product_list}</td>
						<td class="center">{$this_order.date_fin_validite}</td>
						<td class="center">{$this_order.montant_ht}</td>
					</tr>
					{/foreach}
				</table>
			</div>
		</div>
	{/if}
</div>