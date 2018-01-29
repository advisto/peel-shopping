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
// $Id: contact_form.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}{if empty($skip_introduction_text)}<h1 class="page_title">{if empty($meta_title)}{$STR_CONTACT}{else}{$meta_title}{/if}</h1>{/if}
{if isset($token_error)}{$token_error}{/if}
<div id="contact">
{if empty($product_info_id) && empty($contact_page_map_display)}
	<div id="contact_info">{$contact_info}</div>
{/if}
	<div id="contact_form">{if isset($success_msg) && !empty($success_msg)}<div class="alert alert-success">{$success_msg|nl2br_if_needed}</div>{/if}
		<div class="contact_intro">{if empty($meta_description)}{$STR_CONTACT_INTRO}{else}{$meta_description}{/if}</div>
			<form class="entryform form-inline well" role="form" method="post" action="{$action|escape:'html'}#contact_form" name="form_contact" id="form_contact" enctype="multipart/form-data">
			<input type="hidden" id="product_info_id" name="product_info_id" value="{$product_info_id|str_form_value}" />
			{$extra_field}
			<table style="width:75%">
{if !empty($site_configured_array)}
				<tr>
					<td><label for="sujet">{$STR_WEBSITE} <span class="etoile{if $short_form} no-display{/if}">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td>
						<select class="form-control" id="site_id" name="site_id">
							{html_options options=$site_configured_array}
						</select>
					</td>
				</tr>
{/if}
{if !empty($STR_CONTACT_SUBJECT)}
				<tr>
					<td><label for="sujet">{$STR_CONTACT_SUBJECT} <span class="etoile{if $short_form} hidden{/if}">*</span>{$STR_BEFORE_TWO_POINTS}:</label>
					</td>
					<td>
						<select class="form-control" id="sujet" name="sujet" style="">
							{html_options options=$sujet_options selected=$sujet_options_selected}
						</select>
						{$sujet_error}
					</td>
				</tr>
{elseif !empty($mail_title)}
				<tr {if !empty($hidden_sujet)} class="hidden" {/if}>
					<td><label for="sujet"></td>
					<td colspan="2">
						<select class="form-control" id="sujet" name="sujet" style="">
							<option value="{$mail_title|str_form_value}" selected="selected">{$mail_title}</option>
						</select>
					</td>
				</tr>
{/if}
		{if !empty($STR_REQUIRED_ORDER_NUMBER)}
				<tr{if $short_form} class="hidden"{/if}>
					<td><label for="commande_id">{$STR_ORDER_NUMBER} {$STR_BEFORE_TWO_POINTS}:<br /><i>({$STR_REQUIRED_ORDER_NUMBER})</i></label></td>
					<td class="{$align}">
						<input type="text" class="form-control" id="commande_id" name="commande_id" value="{$commande_id|str_form_value}" />{$commande_error}
					</td>
				</tr>
		{/if}
				<tr {if !empty($hidden_texte)} class="hidden" {/if}>
					<td><label for="texte">{$STR_TEXT} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td><textarea class="form-control" id="texte" name="texte" rows="10">{$texte_value}</textarea>{$texte_error}</td>
				</tr>
				<tr {if !empty($hidden_societe)} class="hidden" {/if}>
					<td><label for="societe">{$STR_SOCIETE} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						{$societe_error}<input type="text" class="form-control" id="societe" name="societe" value="{$societe_value|str_form_value}" />
					</td>
				</tr>
				<tr {if !empty($hidden_nom)} class="hidden" {/if}>
					<td><label for="nom">{$STR_NAME} / {$STR_FIRST_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="text" class="form-control" id="nom" name="nom" value="{$name_value|str_form_value}" />{$name_error}
						{if $short_form || !empty($hidden_prenom)} <input type="hidden" id="prenom" name="prenom" value="{$first_name_value|str_form_value}" />{/if}
					</td>
				</tr>
				<tr {if $short_form || !empty($hidden_prenom)} class="hidden"{/if}>
					<td><label for="prenom">{$STR_FIRST_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="text" class="form-control" id="prenom" name="prenom" value="{$first_name_value|str_form_value}" />{$first_name_error}
					</td>
				</tr>
				<tr>
					<td><label for="email">{$STR_EMAIL} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="email" class="form-control" id="email" name="email" value="{$email_value|str_form_value}" autocapitalize="none" />{$email_error}
						<input type="hidden" id="adresse" name="adresse" value="{$address_value|str_form_value}" />
						<input type="hidden" id="code_postal" name="code_postal" value="{$zip_value|str_form_value}" />
						<input type="hidden" id="dispo" name="dispo" value="" />
						<input type="hidden" id="ville" name="ville" value="{$town_value|str_form_value}" />
						<input type="hidden" id="pays" name="pays" value="{$country_value|str_form_value}" />
					</td>
				</tr>
				<tr {if !empty($hidden_adresse)} class="hidden"{/if}>
					<td><label for="telephone">{$STR_TELEPHONE} <span class="etoile{if $short_form} hidden{/if}">*</span>{$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="tel" class="form-control" id="telephone" name="telephone" value="{$telephone_value|str_form_value}" />{$telephone_error}
					</td>
				</tr>
				<tr{if $short_form || !empty($hidden_code_postal)} class="hidden"{/if}>
					<td><label for="code_postal">{$STR_ZIP} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="text" class="form-control" id="code_postal" name="code_postal" value="{$zip_value|str_form_value}" />
					</td>
				</tr>
				<tr{if $short_form || !empty($hidden_ville)} class="hidden"{/if}>
					<td><label for="ville">{$STR_TOWN} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="text" class="form-control" id="ville" name="ville" value="{$town_value|str_form_value}" />
					</td>
				</tr>
				<tr {if $short_form || !empty($hidden_pays)} class="hidden"{/if}>
					<td><label for="pays">{$STR_COUNTRY} {$STR_BEFORE_TWO_POINTS}:</label></td>
					<td class="{$align}">
						<input type="text" class="form-control" id="pays" name="pays" value="{$country_value|str_form_value}" />
					</td>
				</tr>
				{if !empty($user_contact_file_upload)}
				<tr>
					<td class="title_label">{$STR_FILE}{$STR_BEFORE_TWO_POINTS}:</td>
					<td>{$this_upload_html}</td>
				</tr>
				{/if}
				{if $short_form}
				<tr>
					<td colspan="2" style="height:14px;"></td>
				</tr>
				{/if}
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
			</table>

			<div style="text-align:center; margin-top: 10px;">
				{$token}
				<div style="text-align:center; margin-top: 10px; margin-bottom: 10px;"><input type="submit" class="btn btn-primary btn-lg" value="{$STR_SEND|str_form_value}" />{if !empty($ssl_image_src)}<img alt="SSL" src="{$ssl_image_src}" class="image_ssl right" />{/if}</div>
			</div>
			<p{if $short_form} class="hidden"{/if}>{$cnil_txt|nl2br_if_needed}</p>
		</form>
		<p{if $short_form} class="hidden"{/if}><span class="form_mandatory">(*) {$STR_MANDATORY}</span></p>
{if !empty($product_info_id)}
		<div id="contact_info">{$contact_info}</div>
{/if}
	</div>
{if empty($skip_introduction_text)}</div>{/if}