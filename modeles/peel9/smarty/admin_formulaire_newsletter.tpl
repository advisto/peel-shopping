{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_newsletter.tpl 59873 2019-02-26 14:47:11Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete">{$STR_ADMIN_NEWSLETTERS_FORM_TITLE}</td>
		</tr>
		<tr>
			<td>
				<div class="alert alert-info">{$STR_ADMIN_NEWSLETTERS_WARNING}</div>
			</td>
		</tr>
		<tr>
			<td>
				<p class="alert alert-info">{$LANG.STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE_INFO}</p>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}: </td>
		</tr>
		<tr>
			<td>
				<select class="form-control" name="site_id">
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td>
				<select class="form-control" name="template_technical_code" id="template_technical_code">
					{$template_technical_code_options}
				</select>
			</td>
		</tr>
		{foreach $langs as $l}
		<tr>
			<td>{$STR_ADMIN_SUBJECT} {$l.lng}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="sujet_{$l.lng}" style="width:100%" value="{$l.sujet|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_MESSAGE} {$l.lng}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td>{$l.message_te}</td>
		</tr>
		{/foreach}
		{if $products_in_newsletter}
		<tr>
			<td>
				<script><!--//--><![CDATA[//><!--
					 var new_order_line_html = '<tr class="top" id="sortable_[i]"><td><img src="{$administrer_url}/images/b_drop.png" alt="{$STR_DELETE}" onclick="if(bootbox.confirm(\'{$STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM|filtre_javascript:true:true:false}\', function(result) {ldelim}if(result) {ldelim}admin_delete_products_list_line([i], true);{rdelim} {rdelim} ))return false;" title="{$STR_ADMIN_PRODUCT_ORDERED_DELETE}" style="cursor:pointer" /> <input type="hidden" name="product_ids[]" value="[id]"></td><td>[ref] [nom]</td></tr>';
				//--><!]]></script>
				<div class="full_width" style="border: 1px #000000 dotted; background-color: #FAFAFA; padding:5px">
					<table class="table admin_commande_details">
						<thead>
							<tr style="background-color:#EEEEEE">
								<td colspan="{if $associated_product_multiple_add_to_cart}3{else}2{/if}" class="title_label center" style="width:65px">{$STR_REFERENCE} - {$STR_ADMIN_NAME}</td>
							</tr>
						</thead>
						{* Attention : pour éviter bug IE8, il ne doit pas y avoir d'espaces entre tbody et tr ! *}
						<tbody id="dynamic_order_lines">{foreach $produits_options as $o}<tr class="top" id="sortable_{$o.i}">
									<td>
										<img src="{$administrer_url}/images/b_drop.png" alt="{$STR_DELETE}" onclick="if(bootbox.confirm('{$STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM|filtre_javascript:true:true:false}', function(result) {ldelim}if(result) {ldelim}admin_delete_products_list_line({$o.i}, true);{rdelim} {rdelim} ))return false;" title="{$STR_ADMIN_PRODUCT_ORDERED_DELETE}" style="cursor:pointer" />
										<input type="hidden" name="product_ids[]" value="{$o.value|str_form_value}">
									</td>
									<td>{$o.reference} {$o.name}</td>
								</tr>{/foreach}</tbody>
					</table>
					<p style="margin-top:0px;">{$STR_DELETE} {$STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" id="suggestions_input" name="suggestions_input" style="width:200px" value="" onkeyup="lookup(this.value, '', '', '', '', '', '#suggestions', 'products');" onclick="lookup(this.value, '', '', '', '', '', '#suggestions', 'products');" />


					<div class="suggestions" id="suggestions"></div>
					<input id="nb_produits" type="hidden" name="nb_produits" value="{$nb_produits|str_form_value}" />
				</div>
			</td>
		</tr>
		{/if}
		<tr>
			<td class="center"><p><input class="btn btn-primary" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>