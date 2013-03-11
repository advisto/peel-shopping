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
// $Id: admin_formulaire_type.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<form method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_TYPES_FORM_TITLE}</td>
		</tr>
		{foreach $langs as $l}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_LANGUAGES_SECTION_HEADER} {$l.lng|upper}</td></tr>
		<tr>
			<td class="label">{$STR_ADMIN_NAME} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:460px" type="text" name="nom_{$l.lng}" value="{$l.nom|str_form_value}" /></td>
   	 	</tr>
		{/foreach}
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</td></tr>
		<tr>
			<td class="label">{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" name="position" value="{$position|str_form_value}" /></td>
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
			<td colspan="2" class="bloc">{$STR_ADMIN_TYPES_TNT}{$STR_BEFORE_TWO_POINTS}:</td>
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
		<tr><td colspan="2" class="bloc">{$STR_ADMIN_TYPES_KWIXO}</td></tr>
		<tr>
			<td>{$STR_ADMIN_TYPES_LINK_TO_KWIXO}</td>
			<td>
				<input type="text" name="fianet_type_transporteur" value="{$fianet_type_transporteur|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p class="global_help">{$STR_ADMIN_TYPES_LINK_TO_KWIXO_EXPLAIN}</p>
			</td>
		</tr>
		{/if}
		<tr>
			<td class="center" colspan="2"><p><input class="bouton" type="submit" value="{$titre_bouton|str_form_value}" /></p></td>
		</tr>
	</table>
</form>	