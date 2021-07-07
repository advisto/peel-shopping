{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributsAdmin_liste.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}
<form action="{$action}" method="POST">
	<table class="full_width">
	<tr><td colspan="5" class="entete">{$STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST} <strong>{$nom|html_entity_decode_if_needed}</strong></td></tr>
	<tr>
		<td colspan="5">
			<div style="margin-top:5px;">
				<p><a href="{$add_href|escape:'html'}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {$STR_MODULE_ATTRIBUTS_ADMIN_CREATE_OPTION}</a></p>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="5"><div class="alert alert-info">{$STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST_EXPLAIN}</div></td>
	</tr>
	<tr>
		<td class="menu">{$STR_ADMIN_ACTION}</td>
		<td class="menu">{$STR_LIST}</td>
		<td class="menu">{$STR_PRICE}</td>
		<td class="menu">{$STR_PHOTO}</td>
		<td class="menu">{$STR_ADMIN_WEBSITE}</td>
	</tr>
{if $num_results == 0}
	<tr><td colspan="5"><b>{$STR_MODULE_ATTRIBUTS_ADMIN_NO_OPTION_DEFINED}</b></td></tr>
{else}
	{foreach $results as $res}
	{$res.tr_rollover}
		<td class="center">
			<input type="checkbox" value="{$res.id}" name="attribut_id[]" />
			<a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" href="{$res.drop_href|escape:'html'}"><img src="{$res.drop_src|escape:'html'}" alt="{$STR_DELETE|str_form_value}" /></a>
		</td>
		<td class="center">
			<a title="{$STR_MODIFY|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.descriptif|html_entity_decode_if_needed}</a>
		</td>
		<td class="center">{$res.prix} {$STR_TTC}</td>
		<td class="center">{if !empty($res.img_src)}<img src="{$res.img_src|escape:'html'}" alt="" />{/if}</td>
		<td class="center">
			{$res.site_name|html_entity_decode_if_needed}
		</td>
	</tr>
	{/foreach}
{/if}
	<tr><td colspan="5">&nbsp;</td></tr>
	<tr>
		<td colspan="5">
			<input type="hidden" name="nom_attribut_id" value="{$nom_attribut_id}" />
			<table>
				<tr>
					<td>
						<select class="form-control" name="assignation_mode">
							<option value="assign">{$STR_ADMIN_ASSOCIATED}</option>
							<option value="unassign">{$STR_ADMIN_DISASSOCIATED}</option>
						</select>
					</td><td>
						&nbsp;{$STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTES_CHECKED_IN_CATEGORY_PRODUCTS}&nbsp;
					</td><td>
						<select class="form-control" name="categories">
							{$categorie_options}
						</select>
					</td>
					<td>&nbsp;<input type="submit" class="btn btn-primary" name="submit_product_attribut_form" value="{$STR_SEND}" /></td>
				</tr>
			</table>
		</td>
	</tr>
	</table>
</form>