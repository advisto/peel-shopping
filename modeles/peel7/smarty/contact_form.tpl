{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: contact_form.tpl 36258 2013-04-06 11:00:04Z gboussin $
*}<h1 class="page_title">{$STR_CONTACT}</h1>
{if isset($token_error)}{$token_error}{/if}
<div id="contact">
	<div id="contact_info">{$contact_info}</div>
	<div id="contact_form">{if isset($success_msg) AND !empty($success_msg)}<div class="global_success">{$success_msg|nl2br_if_needed}</div>{/if}
		<div class="contact_intro">{$STR_CONTACT_INTRO}</div>
		<form class="entryform" method="post" action="{$action|escape:'html'}" name="form_contact" id="form_contact">
			{$extra_field}
			<table cellpadding="3" class="full_width">
				<tr>
					<td {if $is_advistofr_module_active} colspan="2"{/if}><label for="sujet">{$STR_CONTACT_SUBJECT} <span class="etoile{if $is_advistofr_module_active} no-display{/if}">(*)</span>{$STR_BEFORE_TWO_POINTS}:</label>
		{if $is_advistofr_module_active}
						<br />
		{else}
					</td>
					<td>
		{/if}
					<select id="sujet" name="sujet" style="">
						{html_options options=$sujet_options selected=$sujet_options_selected}
					</select>
					{$sujet_error}
					</td>
				</tr>
				<tr{if $is_advistofr_module_active} class="no-display"{/if}>
					<td><label for="commande_id">{$STR_ORDER_NUMBER} {$STR_BEFORE_TWO_POINTS}:<br /><i>({$STR_REQUIRED_ORDER_NUMBER})</i></label></td>
					<td class="{$align}">
						<input class="form" type="text" id="commande_id" name="commande_id" value="{$commande_id|str_form_value}" />{$commande_error}
					</td>
				</tr>
				<tr>
					<td><label for="societe">{$STR_SOCIETE} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						{$societe_error}<input class="form" type="text" id="societe" name="societe" value="{$societe_value|str_form_value}" />
					</td>
				</tr>
				<tr>
					<td><label for="nom">{$STR_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input class="form" type="text" id="nom" name="nom" value="{$name_value|str_form_value}" />{$name_error}
					</td>
				</tr>
				<tr{if $is_advistofr_module_active} class="no-display"{/if}>
					<td><label for="prenom">{$STR_FIRST_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input class="form" type="text" id="prenom" name="prenom" value="{$first_name_value|str_form_value}" />{$first_name_error}
					</td>
				</tr>
				<tr>
					<td><label for="email">{$STR_EMAIL} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input class="form" type="text" id="email" name="email" value="{$email_value|str_form_value}" />{$email_error}
					</td>
				</tr>
				<tr>
					<td><label for="adresse">{$STR_ADDRESS} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<textarea rows="3" cols="54" class="textarea-contact" id="adresse" name="adresse">{$address_value}</textarea>
					</td>
				</tr>
				<tr{if $is_advistofr_module_active} class="no-display"{/if}>
					<td><label for="code_postal">{$STR_ZIP} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input class="form" type="text" id="code_postal" name="code_postal" value="{$zip_value|str_form_value}" />
					</td>
				</tr>
				<tr{if $is_advistofr_module_active} class="no-display"{/if}>
					<td><label for="ville">{$STR_TOWN} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input class="form" type="text" id="ville" name="ville" value="{$town_value|str_form_value}" />
					</td>
				</tr>
				<tr{if $is_advistofr_module_active} class="no-display"{/if}>
					<td><label for="pays">{$STR_COUNTRY} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input class="form" type="text" id="pays" name="pays" value="{$country_value|str_form_value}" />
					</td>
				</tr>
				<tr>
					<td><label for="telephone">{$STR_TELEPHONE} <span class="etoile{if $is_advistofr_module_active} no-display{/if}">(*)</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input class="form" type="text" id="telephone" name="telephone" value="{$telephone_value|str_form_value}" />{$telephone_error}
					</td>
				</tr>
				{if $is_advistofr_module_active}
				<tr>
					<td colspan="2" style="height:14px;"></td>
				</tr>
				{/if}
				<tr>
					<td><label for="texte">{$STR_TEXT} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label>
					<td>{$texte_error}<textarea id="texte" name="texte" rows="10">{$texte_value}</textarea></td>
				</tr>
				<tr>
					<td><label for="dispo">{$STR_DISPO}{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
					   <select id="dispo" name="dispo">
						   <option value="A.M">{$STR_DAY_AM}</option>
						   <option value="P.M">{$STR_DAY_PM}</option>
						</select>
					</td>
				</tr>
				{if isset($captcha)}
				<tr>
					<td class="left">{$captcha.validation_code_txt}{$STR_BEFORE_TWO_POINTS}:</td>
					<td>{$captcha.inside_form}</td>
				</tr>
				<tr>
					<td class="left">{$captcha.validation_code_copy_txt} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
					<td>{$captcha.error}<input name="code" type="text" size="5" maxlength="5" id="code" value="{$captcha.value|str_form_value}" /></td>
				</tr>
				{/if}
			</table>

			<div style="text-align:center; margin-top: 10px;">
				{$token}
			{if $is_advistofr_module_active}
				<a href="{$href|escape:'html'}#" class="a_submit" onclick="document.form_contact.submit();return false;" ></a>
			{else}
				<input type="submit" class="clicbouton" value="{$STR_SEND|str_form_value}" />
			{/if}
			</div>
			<p{if $is_advistofr_module_active} class="no-display"{/if}>{$cnil_txt|nl2br_if_needed}</p>
			<p{if $is_advistofr_module_active} class="no-display"{/if}><span class="form_mandatory">(*) {$STR_MANDATORY}</span></p>
		</form>
	</div>
</div>