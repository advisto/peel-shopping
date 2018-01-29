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
// $Id: admin_formulaire_configuration.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_CONFIGURATION_FORM_TITLE}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_LANGUAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			{foreach $langs as $l}
				<input type="radio" name="lang" id="lang_{$l.lng|str_form_value}" value="{$l.lng|str_form_value}"{if $l.issel} checked="checked"{/if} /> <label for="lang_{$l.lng|str_form_value}">{$l.name}</label><br />
			{/foreach}
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="etat" value="1" id="etat_1"{if $etat == '1'} checked="checked"{/if} /> <label for="etat_1">{$STR_ADMIN_ONLINE}</label><br />
				<input type="radio" name="etat" value="0" id="etat_0"{if $etat == '0' OR empty($etat)} checked="checked"{/if} /> <label for="etat_0">{$STR_ADMIN_OFFLINE}</label>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_CONFIGURATION_ORIGIN}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" class="form-control" name="origin" value="{$origin|html_entity_decode_if_needed|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_TECHNICAL_CODE}{$STR_BEFORE_TWO_POINTS}:<br /></td>
			<td>
				<input type="text" class="form-control" name="technical_code" value="{$technical_code|html_entity_decode_if_needed|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td>{$STR_TYPE}{$STR_BEFORE_TWO_POINTS}:<br /></td>
			<td>
				<input type="text" class="form-control" name="type" value="{$type|html_entity_decode_if_needed|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_CONFIGURATION_TEXT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				{if empty($string_as_textarea)}<input type="text" class="form-control" name="string" value="{$string|html_entity_decode_if_needed|str_form_value}" />{else}<textarea class="form-control" name="string" id="string" style="height:200px;">{$string}</textarea>{/if}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" {if $site_id_select_multiple} name="site_id[]" multiple="multiple" size="5"{else} name="site_id"{/if}>
					{$site_id_select_options}
				</select>
			</td>
		</tr>
		<tr>
			<td>{$STR_COMMENTS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<textarea class="form-control" name="explain" id="explain" style="height:100px;">{$explain}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><input class="btn btn-primary" type="submit" value="{$STR_VALIDATE|str_form_value}" /></td>
		</tr>
	</table>
</form>