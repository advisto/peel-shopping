{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_code_pour_client.tpl 35064 2013-02-08 14:16:40Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="9">{$STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_TITLE}</td>
	</tr>
	<tr>
		<td colspan="9">
			{$STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE}{$STR_BEFORE_TWO_POINTS}: <a title="{$STR_ADMIN_UTILISATEURS_UPDATE|str_form_value}" href="{$modif_util_href|escape:'html'}">{$civilite} {$prenom} {$nom_famille} - {$email}</a>
		</td>
	</tr>
</table>
{if !empty($options)}
<p>{$STR_ADMIN_CODES_PROMOS_SELECT_TO_SEND}{$STR_BEFORE_TWO_POINTS}:</p>
<form method="post" action="{$action|escape:'html'}">
	{$form_token}
	<select name="code_promo_id">
		{foreach $options as $o}
		<option value="{$o.value|str_form_value}">{$STR_NAME}{$STR_BEFORE_TWO_POINTS}: {$o.nom} - {if $o.on_type == 1}{$STR_ADMIN_DISCOUNT}{$STR_BEFORE_TWO_POINTS}: {$o.percent} % {else}{$STR_VALUE}{$STR_BEFORE_TWO_POINTS}: {$o.valeur}{/if}{if $o.montant_min>0} - {$STR_ADMIN_CODES_PROMOS_MIN}{$STR_BEFORE_TWO_POINTS}: {$o.montant_min}{/if}</option>
		{/foreach}
	</select>
	<input type="hidden" name="id_utilisateur" value="{$id_utilisateur|str_form_value}" />
	<input type="hidden" name="mode" value="envoi_client" />
	<br /><br />
	<input class="bouton" type="submit" value="{$STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL|str_form_value}" />
	<br /><br />
	<a class="bouton" href="{$cancel_href|escape:'html'}">{$STR_CANCEL}</a>
</form>
{else}
<p><a href="{$codes_promos_href|escape:'html'}">{$STR_ADMIN_CODES_PROMOS_ERR_FIRST_CREATE_CODE_PROMO}</a></p>
{/if}
