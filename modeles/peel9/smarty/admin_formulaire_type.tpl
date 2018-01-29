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
// $Id: admin_formulaire_type.tpl 53949 2017-06-02 12:14:22Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_TYPES_FORM_TITLE}</td>
		</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}: </td>
			<td>
				<select class="form-control" name="site_id">
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_LANGUAGES_SECTION_HEADER} - {$lang_names[$l.lng]|upper}</h2></td></tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="nom_{$l.lng}" value="{$l.nom|str_form_value}" /></td>
   	 	</tr>
		{/foreach}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</h2></td></tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="number" class="form-control" name="position" value="{$position|str_form_value}" /></td>
   	 	</tr>
		<tr>
			<td class="title_label">{$STR_ADMIN_ZONES_FRANCO_LIMIT_AMOUNT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="on_franco_amount" value="{$on_franco_amount|str_form_value}" /></td>
   	 	</tr>
		<tr>
			<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="etat" value="1"{if $etat == '1'} checked="checked"{/if} /> {$STR_ADMIN_ONLINE}<br />
				<input type="radio" name="etat" value="0"{if $etat == '0' OR empty($etat)} checked="checked"{/if} /> {$STR_ADMIN_OFFLINE}
			</td>
		</tr>
		<tr>
			<td>{$STR_SHIP_ADDRESS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="without_delivery_address" value="0" {if $without_delivery_address == 0} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="without_delivery_address" value="1" {if $without_delivery_address == 1} checked="checked"{/if} /> {$STR_ADMIN_TYPES_NO_DELIVERY}
			</td>
		</tr>
		{if $is_socolissimo_module_active}
		<tr>
			<td>{$STR_ADMIN_TYPES_LINK_TO_SOCOLISSIMO}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="is_socolissimo" value="1" {if $is_socolissimo == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="is_socolissimo" value="0" {if $is_socolissimo == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		{/if}
		{if $is_kiala_module_active}
		<tr>
			<td>{$STR_ADMIN_TYPES_LINK_TO_KIALA}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="is_kiala" value="1" {if $is_kiala == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="is_kiala" value="0" {if $is_kiala == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		{/if}
		{if $is_ups_module_active}
		<tr>
			<td>{$STR_ADMIN_TYPES_LINK_TO_UPS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="is_ups" value="1" {if $is_ups == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="is_ups" value="0" {if $is_ups == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		{/if}
		{if $is_icirelais_module_active}
		<tr>
			<td>{$STR_ADMIN_TYPES_LINK_TO_ICIRELAIS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="is_icirelais" value="1" {if $is_icirelais == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="is_icirelais" value="0" {if $is_icirelais == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		{/if}
		{if $is_tnt_module_active}
		<tr>
			<td colspan="2" class="bloc"><h2>{$STR_ADMIN_TYPES_TNT}{$STR_BEFORE_TWO_POINTS}:</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TYPES_LINK_TO_TNT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="is_tnt" value="1" {if $is_tnt == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="is_tnt" value="0" {if $is_tnt == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TYPES_TNT_DESTINATION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="tnt_threshold" value="1" {if $tnt_threshold == 1} checked="checked"{/if} /> {$STR_ADMIN_TYPES_TNT_HOME}
				<input type="radio" name="tnt_threshold" value="0" {if $tnt_threshold == 0} checked="checked"{/if} /> {$STR_ADMIN_TYPES_TNT_DELIVERY_POINT}
			</td>
		</tr>
		{/if}
		{if $is_fianet_module_active}
		<tr><td colspan="2" class="bloc"><h2>{$STR_ADMIN_TYPES_KWIXO}</h2></td></tr>
		<tr>
			<td>{$STR_ADMIN_TYPES_LINK_TO_KWIXO}</td>
			<td>
				<input type="text" class="form-control" name="fianet_type_transporteur" value="{$fianet_type_transporteur|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p class="alert alert-info">{$STR_ADMIN_TYPES_LINK_TO_KWIXO_EXPLAIN}</p>
			</td>
		</tr>
		{/if}
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>	