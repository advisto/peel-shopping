{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: contact_form.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
*}{if !$skip_introduction_text}<h1 property="name" class="page_title">{$STR_CONTACT}</h1>
{if isset($token_error)}{$token_error}{/if}
<div id="contact">
	<div id="contact_info">{$contact_info}</div>
{/if}
	<div id="contact_form">{if isset($success_msg) && !empty($success_msg)}<div class="alert alert-success">{$success_msg|nl2br_if_needed}</div>{/if}
		<div class="contact_intro">{$STR_CONTACT_INTRO}</div>
			<form class="entryform form-inline well" role="form" method="post" action="{$action|escape:'html'}#contact_form" name="form_contact" id="form_contact">
			{$extra_field}
			<table class="contact_form_table">
				<tr>
					<td {if $short_form} colspan="2"{/if}><label for="sujet">{$STR_CONTACT_SUBJECT} <span class="etoile{if $short_form} no-display{/if}">*</span>{$STR_BEFORE_TWO_POINTS}:</label>
		{if $short_form}
						<br />
		{else}
					</td>
					<td>
		{/if}
					<select class="form-control" id="sujet" name="sujet" style="">
						{html_options options=$sujet_options selected=$sujet_options_selected}
					</select>
					{$sujet_error}
					</td>
				</tr>
		{if !empty($STR_REQUIRED_ORDER_NUMBER)}
				<tr{if $short_form} class="no-display"{/if}>
					<td><label for="commande_id">{$STR_ORDER_NUMBER} {$STR_BEFORE_TWO_POINTS}:<br /><i>({$STR_REQUIRED_ORDER_NUMBER})</i></label></td>
					<td class="{$align}">
						<input type="text" class="form-control" id="commande_id" name="commande_id" value="{$commande_id|str_form_value}" />{$commande_error}
					</td>
				</tr>
		{/if}
				<tr>
					<td><label for="societe">{$STR_SOCIETE} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						{$societe_error}<input type="text" class="form-control" id="societe" name="societe" value="{$societe_value|str_form_value}" />
					</td>
				</tr>
				<tr>
					<td><label for="nom">{$STR_NAME} / {$STR_FIRST_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="text" class="form-control" id="nom" name="nom" value="{$name_value|str_form_value}" />{$name_error}
						<input type="hidden" id="prenom" name="prenom" value="{$first_name_value|str_form_value}" />
					</td>
				</tr>
				<tr>
					<td><label for="email">{$STR_EMAIL} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="email" class="form-control" id="email" name="email" value="{$email_value|str_form_value}" />{$email_error}
						<input type="hidden" id="adresse" name="adresse" value="{$address_value|str_form_value}" />
						<input type="hidden" id="code_postal" name="code_postal" value="{$zip_value|str_form_value}" />
						<input type="hidden" id="dispo" name="dispo" value="" />
						<input type="hidden" id="ville" name="ville" value="{$town_value|str_form_value}" />
						<input type="hidden" id="pays" name="pays" value="{$country_value|str_form_value}" />
					</td>
				</tr>
				<tr>
					<td><label for="telephone">{$STR_TELEPHONE} <span class="etoile{if $short_form} no-display{/if}">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="tel" class="form-control" id="telephone" name="telephone" value="{$telephone_value|str_form_value}" />{$telephone_error}
					</td>
				</tr>
				{if $short_form}
				<tr>
					<td colspan="2" style="height:14px;"></td>
				</tr>
				{/if}
				<tr>
					<td><label for="texte">{$STR_TEXT} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label>
					<td><textarea class="form-control" id="texte" name="texte" rows="10">{$texte_value}</textarea>{$texte_error}</td>
				</tr>
				{if isset($captcha)}
				<tr>
					<td class="left">{$captcha.validation_code_txt}{$STR_BEFORE_TWO_POINTS}:</td>
					<td>{$captcha.inside_form}</td>
				</tr>
				<tr>
					<td class="left">{$captcha.validation_code_copy_txt} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
					<td><input name="code" type="text" class="form-control" size="5" maxlength="5" id="code" value="{$captcha.value|str_form_value}" />{$captcha.error}</td>
				</tr>
				{/if}
				<tr>
					<td></td>
					<td>{$token}<div style="text-align:center; margin-top: 10px; margin-bottom: 10px;"><input type="submit" class="btn btn-primary btn-lg" value="{$STR_SEND|str_form_value}" /></div></td>
				</tr>
			</table>
		</form>
		<p{if $short_form} class="no-display"{/if}><span class="form_mandatory">(*) {$STR_MANDATORY}</span></p>
	</div>
{if !$skip_introduction_text}</div>{/if}