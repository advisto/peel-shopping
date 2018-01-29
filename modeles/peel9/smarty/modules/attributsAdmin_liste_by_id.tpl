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
// $Id: attributsAdmin_liste_by_id.tpl 54013 2017-06-08 16:34:43Z sdelaporte $
*}
<form class="entryform form-inline" role="form" method="post" name="associe_produit_attribut" action="{$action|escape:'html'}">
	<table cellpadding="4" class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_TITLE} "{$product_name}"</td>
		</tr>
		<tr>
			<td colspan="2">
				<a href="{$product_revenir_href|escape:'html'}">{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_BACK_TO_PRODUCT}</a><br />
				<a href="{$product_liste_revenir_href|escape:'html'}">{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_BACK_TO_PRODUCTS_LIST}</a><br />
				<div class="alert alert-info">{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_EXPLAIN_SELECT}</div>
			</td>
		</tr>
		<tr>
			<td class="menu">{$STR_ADMIN_ATTRIBUTE}</td>
			<td class="menu">{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTIONS_ASSOCIATED}</td>
		</tr>
	{foreach $results as $res}
		{$res.tr_rollover}
			<td class="title_label">{if !empty($res.nom)}{$res.nom|html_entity_decode_if_needed}{else}[{$res.id}]{/if}</td>
			<td>
		{if !empty($res.sub_res) || $res.texte_libre || $res.upload}
			{if !$res.texte_libre && !$res.upload && !empty($res.sub_res)}
					<select class="form-control" name="attribut_id_{$res.id}[]" multiple="multiple" style="width:100%" size="{if ($res.sub_res|@count)<5}{$res.sub_res|@count}{else}5{/if}">
				{foreach $res.sub_res as $sr}
						<option value="{$sr.value|str_form_value}" {if $sr.issel} selected="selected"{/if}>{$sr.desc|html_entity_decode_if_needed}{if $sr.prix>0} - {$STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_OVERCOST} : {$sr.prix} {$ttc_ht}{/if}</option>
				{/foreach}
					</select>
			{elseif $res.texte_libre || $res.upload}
					<select class="form-control" name="attribut_id_{$res.id}[]" multiple="multiple" style="width:100%" size="1">
						<option value="0" {if $res.issel} selected="selected"{/if}>
						{if !empty($res.texte_libre)}
							[{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_FREE_TEXT}]
						{elseif !empty($res.upload)}
							[{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_ADD_UPLOAD}]
						{/if}
						</option>
					</select> 
			{/if}
		{else}
				{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_NO_OPTION} <a href="{$wwwroot_in_admin}/modules/attributs/administrer/attributs.php?mode=liste&attid={$res.id}">{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_MANAGE_LINK}</a>.
		{/if}
			</td>
		</tr>
	{/foreach}
	</table>
	<br />
	<div class="center"><input type="submit" name="submit" class="btn btn-primary" value="{$STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_ASSOCIATE_ATTRIBUTE|str_form_value}" /></div>
</form>