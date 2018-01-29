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
// $Id: admin_prix_pourcentage.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	{$form_token}
	<div class="entete">{$STR_ADMIN_PRIX_POURCENTAGE_TITLE}</div>
	<div class="alert alert-info">{$STR_ADMIN_PRIX_POURCENTAGE_EXPLAIN}</div>
	<div class="col-sm-5 center">
		<p><b>{$STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_CATEGORY}</b>{$STR_BEFORE_TWO_POINTS}:</p>
		<select class="form-control" id="form_categories" class="formulaire1" name="categories[]" multiple="multiple" style="width:100%" size="15" onchange="var select=document.getElementById('form_products'); for (var i in select.options) {ldelim}select.options[i].selected=''; {rdelim}">
		{foreach $cats_options as $o}
			<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
		{/foreach}
		</select>
	</div>
	<div class="col-sm-2 center" style="font-size:20px; font-weight:bold">
		<p>{$STR_OR|strtoupper}</p>
	</div>
	<div class="col-sm-5 center">
		<p><b>{$STR_ADMIN_PRIX_POURCENTAGE_CHOOSE_PRODUCT}</b>{$STR_BEFORE_TWO_POINTS}:</p>
		<script><!--//--><![CDATA[//><!--
			var new_order_line_html = '<tr class="top" id="sortable_[i]"><td><img src="{$administrer_url}/images/b_drop.png" alt="{$STR_DELETE}" onclick="if(bootbox.confirm(\'{$STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM|filtre_javascript:true:true:false}\', function(result) {ldelim}if(result) {ldelim}admin_delete_products_list_line([i], true);{rdelim} {rdelim} ))return false;" title="{$STR_ADMIN_PRODUCT_ORDERED_DELETE}" style="cursor:pointer" /> <input type="hidden" name="produits[]" value="[id]"></td><td>[ref] [nom]</td></tr>';
		//--><!]]></script>
		<div class="full_width" style="border: 1px #000000 dotted; background-color: #FAFAFA; padding:5px">
			<table class="table admin_commande_details">
				<thead>
					<tr style="background-color:#EEEEEE">
						<td colspan="2" class="title_label center" style="width:65px">{$STR_REFERENCE} - {$STR_ADMIN_NAME}</td>
					</tr>
				</thead>
				{* Attention : pour éviter bug IE8, il ne doit pas y avoir d'espaces entre tbody et tr ! *}
				<tbody id="dynamic_order_lines"></tbody>
			</table>
			<p style="margin-top:0px;">{$STR_DELETE} {$STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" id="suggestions_input" name="suggestions_input" value="" onkeyup="lookup(this.value, '', '', '', '', 'product');" onclick="lookup(this.value, '', '', '', '', 'product');" /></p>
			<div class="suggestions" id="suggestions"></div>
			<input id="nb_produits" type="hidden" name="nb_produits" value="{$nb_produits|str_form_value}" />
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="center" style="padding-top:15px;">
		<b>{$STR_ADMIN_PRIX_POURCENTAGE_USERS_RELATED}</b>{$STR_BEFORE_TWO_POINTS}:
		<select class="form-control" name="for_price" style="width:150px" >
			<option value="">{$STR_CHOOSE}...</option>
			<option value="all"{if $for_price == 'all'} selected="selected"{/if}>{$STR_ADMIN_ALL}</option>
			<option value="1"{if $for_price == '1'} selected="selected"{/if}>{$STR_ADMIN_PRIX_POURCENTAGE_CLIENTS_ONLY}</option>
			<option value="2"{if $for_price == '2'} selected="selected"{/if}>{$STR_ADMIN_PRIX_POURCENTAGE_RESELLERS_ONLY}</option>
		</select><br />
	</div>
	<div class="center" style="padding-top:15px;">
		<b>{$STR_ADMIN_PRIX_POURCENTAGE_ENTER_PERCENTAGE}</b>{$STR_BEFORE_TWO_POINTS}:
		<input style="width:150px" type="text" class="form-control" {if !empty($percent_prod)} value="{$percent_prod|str_form_value}"{/if} name="percent_prod" />
		<select class="form-control" name="operation" style="width:150px">
			<option value="">{$STR_CHOOSE}...</option>
			<option value="plus"{if $operation == 'plus'} selected="selected"{/if}>{$STR_ADMIN_PRIX_POURCENTAGE_RAISE}</option>
			<option value="minus"{if $operation == 'minus'} selected="selected"{/if}>{$STR_ADMIN_PRIX_POURCENTAGE_LOWER}</option>
		</select><br />
	</div>
	<div class="center" style="padding-top:15px;">
		<input class="formulaire1" type="hidden" name="submit" value="ok" />
		<input class="btn btn-primary" type="submit" value="{$STR_VALIDATE|str_form_value}" name="validate" />
	</div>
</form>