{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_marque.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_MARQUES_FORM_TITLE}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input  type="number" class="form-control" name="position" value="{$position|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="etat" value="1"{if $etat == '1'} checked="checked"{/if} /> {$STR_ADMIN_ONLINE}<br />
				<input type="radio" name="etat" value="0"{if $etat == '0' OR empty($etat)} checked="checked"{/if} /> {$STR_ADMIN_OFFLINE}
			</td>
		</tr>
{foreach $langs as $l}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_LANGUAGES_SECTION_HEADER} {$l.lng|upper}</td></tr>
		<tr>
			<td class="title_label" colspan="2">{$STR_ADMIN_NAME}{$STR_BEFORE_TWO_POINTS}: {$l.error}</td>
		</tr>
		<tr>
			<td colspan="2"><input style="width: 100%" type="text" class="form-control" name="nom_{$l.lng}" value="{$l.nom|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_DESCRIPTION}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2">{$l.description_te}</td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_META_TITLE} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" class="form-control" name="meta_titre_{$l.lng}" size="70" value="{$l.meta_titre|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_META_KEYWORDS} {$l.lng|upper} ({$STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN}){$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" name="meta_key_{$l.lng}" style="width:100%" rows="2" cols="54">{$l.meta_key|nl2br_if_needed|html_entity_decode_if_needed|strip_tags}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_META_DESCRIPTION} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" name="meta_desc_{$l.lng}" style="width:100%" rows="3" cols="54">{$l.meta_desc|nl2br_if_needed|html_entity_decode_if_needed|strip_tags}</textarea></td>
		</tr>
{/foreach}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</td></tr>
		{if isset($image)}
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_IMAGE}{$STR_BEFORE_TWO_POINTS}:<br />
				<img src="{$image.src|escape:'html'}" alt="" /><br />
				{$STR_ADMIN_FILE_NAME}{$STR_BEFORE_TWO_POINTS}: {$image.nom}&nbsp;
				<a href="{$image.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" />{$STR_ADMIN_DELETE_IMAGE}</a>
				<input type="hidden" name="image" value="{$image.nom|str_form_value}" />
			</td>
		</tr>
		{else}
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_IMAGE}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><input name="image" type="file" value="" /></td>
		</tr>
		{/if}
		{if $is_marque_promotion_module_active}
		<tr>
			<td class="title_label">{$STR_ADMIN_MARQUES_DISCOUNT_ON_BRAND}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="promotion_devises" value="{$promotion_devises|str_form_value}" /> {$site_symbole} {$STR_TTC}
				<input style="width:100px" type="text" class="form-control" name="promotion_percent" value="{$promotion_percent|str_form_value}" />%
			</td>
		</tr>
		{/if}
		<tr>
			<td colspan="2" class="center"><br /><input class="btn btn-primary" type="submit" value="{$titre_soumet|str_form_value}" /></td>
		</tr>
	</table>
</form>