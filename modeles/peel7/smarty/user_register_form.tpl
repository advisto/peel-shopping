{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: user_register_form.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
*}<h1 property="name" class="page_title">{$STR_FIRST_REGISTER_TITLE}</h1>
<div class="user_register_form">
	{if !empty($STR_FIRST_REGISTER_TEXT)}<p>{$STR_FIRST_REGISTER_TEXT}</p>{/if}
	{if !empty($STR_OPEN_ACCOUNT)}<h2>{$STR_OPEN_ACCOUNT}</h2>{/if}
	<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
{if empty($enable_display_only_user_specific_field)}
	<div class="inscription_form">
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="email">{$STR_EMAIL} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="email" class="form-control" id="email" name="email" value="{$email|html_entity_decode_if_needed|str_form_value}" /></span>{$email_error}
		</div>
	{if !empty($STR_PSEUDO)}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="pseudo">{$STR_PSEUDO} {if !$pseudo_is_optionnal}<span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label>{/if}</span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="pseudo" name="pseudo" value="{$pseudo|html_entity_decode_if_needed|str_form_value}" /></span>{$pseudo_error}<br />
			<span class="enregistrementgauche">&nbsp;</span>
			<span>{$STR_STRONG_PSEUDO_NOTIFICATION}</span>
		</div>
	{/if}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="mot_passe">{$STR_PASSWORD} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="password" class="form-control" id="mot_passe" name="mot_passe" size="32" /></span>{$password_error}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="pwd_level">{$STR_PASSWORD_SECURITY}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><div id="pwd_level_image"></div><span class="enregistrementdroite">{$STR_STRONG_PASSWORD_NOTIFICATION} </span></span>
		</div>
		{if $is_annonce_module_active}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="mot_passe_confirm">{$STR_PASSWORD_CONFIRMATION} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="password" class="form-control" id="mot_passe_confirm" name="mot_passe_confirm" size="32" /></span>
			{$password_confirmation_error}
		</div>
		{/if}
	</div>
	<div class="inscription_form" style="margin-top:10px;" >
		<div class="enregistrement">
			<span class="enregistrementgauche"><label>{$STR_GENDER}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<input type="radio" name="civilite" value="Mlle"{if $civilite_mlle_issel} checked="checked"{/if} /> {$STR_MLLE} &nbsp;
				<input type="radio" name="civilite" value="Mme"{if $civilite_mme_issel} checked="checked"{/if} /> {$STR_MME} &nbsp;
				<input type="radio" name="civilite" value="M."{if $civilite_m_issel} checked="checked"{/if} /> {$STR_M}
			</span>{$gender_error}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="prenom">{$STR_FIRST_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="prenom" name="prenom" value="{$first_name|html_entity_decode_if_needed|str_form_value}" /></span>{$first_name_error}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="nom_famille">{$STR_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="nom_famille" name="nom_famille" value="{$name|html_entity_decode_if_needed|str_form_value}" /></span>{$name_error}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="societe">{$STR_SOCIETE}{$STR_BEFORE_TWO_POINTS}{if $is_societe_mandatory}<span class="etoile">*</span>{/if}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="societe" name="societe" value="{$societe|html_entity_decode_if_needed|str_form_value}" /></span>{$societe_error}
		</div>
		{foreach $specific_fields as $f}
			{if $f.field_position=='company'}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="{$f.field_name}">{$f.field_title}{if !empty($f.mandatory_fields)}<span class="etoile">*</span>{/if}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">{include file="specific_field.tpl" f=$f}{$f.error_text}</span>
		</div>
			{/if}
		{/foreach}
	{if $add_b2b_form_inputs}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="url">{$STR_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="url" name="url" placeholder="http://" value="{$url|html_entity_decode_if_needed|str_form_value}" /></span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="type">{$STR_YOU_ARE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<select class="form-control" id="type" name="type">
					<option value="">{$STR_CHOOSE}...</option>
					<option disabled="disabled" style="text-align:center;font-weight:bold;" value="">{$STR_BUYERS}{$STR_BEFORE_TWO_POINTS}:</option>
					<option value="importers_exporters"{if $type=='importers_exporters'} selected="selected"{/if}>{$STR_IMPORTERS_EXPORTERS}</option>
					<option value="commercial_agent"{if $type=='commercial_agent'} selected="selected"{/if}>{$STR_COMMERCIAL_AGENT}</option>
					<option value="purchasing_manager"{if $type=='purchasing_manager'} selected="selected"{/if}>{$STR_PURCHASING_MANAGER}</option>
					<option disabled="disabled" style="text-align:center;font-weight:bold;" value="">{$STR_WORD_SELLERS}{$STR_BEFORE_TWO_POINTS}:</option>
					<option value="wholesaler"{if $type=='wholesaler'} selected="selected"{/if}>{$STR_WHOLESALER}</option>
					<option value="half_wholesaler"{if $type=='half_wholesaler'} selected="selected"{/if}>{$STR_HALF_WHOLESALER}</option>
					<option value="retailers"{if $type=='retailers'} selected="selected"{/if}>{$STR_RETAILERS}</option>
				</select>
			</span> {$type_error}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="activity">{$STR_ACTIVITY} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<select class="form-control" id="activity" name="activity">
					<option value="">{$STR_CHOOSE}...</option>
					<option value="punctual" {if $activity=='punctual'} selected="selected"{/if}>{$STR_PUNCTUAL}</option>
					<option value="recurrent" {if $activity=='recurrent'} selected="selected"{/if}>{$STR_RECURRENT}</option>
				</select>
			</span>{$activity_error}
		</div>
	{/if}
	{if !empty($STR_FONCTION)}
			<div class="enregistrement">
				<span class="enregistrementgauche"><label for="fonction">{$STR_FONCTION}{$STR_BEFORE_TWO_POINTS}:</label></span>
				<span class="enregistrementdroite">
					<select class="form-control" id="fonction" name="fonction">
						<option value="">{$STR_CHOOSE}...</option>
						{$fonction_options}
					</select>
				</span>{$fonction_error}
			</div>
	{/if}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="intracom_for_billing">{$STR_INTRACOM_FORM}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="intracom_for_billing" name="intracom_for_billing" value="{$intracom_form|html_entity_decode_if_needed|str_form_value}" /></span>{$intracom_form_error}
		</div>
	{if $is_annonce_module_active}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="siret">{$siret_txt} {if $is_siret_mandatory}<span class="etoile">*</span>{/if}</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="siret" name="siret" value="{$siret|html_entity_decode_if_needed|str_form_value}" /></span> {$siret_error}
		</div>
	{/if}
	{if !empty($STR_NAISSANCE)}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="naissance">{$STR_NAISSANCE}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input name="naissance" class="form-control datepicker" type="text" id="naissance" size="10" maxlength="10" value="{$naissance|str_form_value}" style="width:110px" /></span>
		</div>
	{/if}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="telephone">{$STR_TELEPHONE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="tel" class="form-control" id="telephone" name="telephone" value="{$telephone|str_form_value}" /></span>{$telephone_error}
		</div>
	{if !empty($STR_PORTABLE)}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="portable">{$STR_PORTABLE}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="tel" class="form-control" id="portable" name="portable" value="{$portable|str_form_value}" /></span>
		</div>
	{/if}
	{if false}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="fax">{$STR_FAX} <span class="etoile"></span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="tel" class="form-control" id="fax" name="fax" value="{$fax|html_entity_decode_if_needed|str_form_value}" /></span>
		</div>
	{/if}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="adresse">{$STR_ADDRESS} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><textarea class="form-control mono-colonne" rows="3" cols="54" id="adresse" name="adresse">{$adresse|html_entity_decode_if_needed}</textarea></span>{$adresse_error}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="code_postal">{$STR_ZIP} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="code_postal" name="code_postal" value="{$zip|str_form_value}" /></span>{$zip_error}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="ville">{$STR_TOWN} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="ville" name="ville" value="{$town|html_entity_decode_if_needed|str_form_value}" /></span>{$town_error}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="pays">{$STR_COUNTRY}{$STR_BEFORE_TWO_POINTS}<span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<select class="form-control" id="pays" name="pays">
					{$country_options}
				</select>
			</span>
		</div>
	{if $is_annonce_module_active}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="promo_code">{$STR_PROMO_CODE} <span class="etoile"></span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" id="promo_code" name="promo_code" value="{$promo_code|html_entity_decode_if_needed|str_form_value}" /></span>
		</div>
		<div class="enregistrement">
			<span>{$STR_ANNOUNCEMENT_INDICATION}</span>
		</div>
		{if !empty($favorite_category)}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="favorite_category">{$STR_FIRST_CHOICE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<select class="form-control" id="favorite_category" name="favorite_category">
					{$favorite_category}
				</select>
			</span>
			{$favorite_category_error}
		</div>
		{else}
		<div class="enregistrement">
			<span class="enregistrementgauche">
			<label for="id_cat_1">{$STR_FIRST_CHOICE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<select class="form-control" id="id_cat_1" name="id_cat_1">
					{$favorite_category_1}
				</select> {$id_cat_1_error}
			</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche">
			<label for="id_cat_2">{$STR_SECOND_CHOICE}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<select class="form-control" id="id_cat_2" name="id_cat_2">
					{$favorite_category_2}
				</select> {$id_cat_2_error}
			</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche">
			<label for="id_cat_3">{$STR_THIRD_CHOICE}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<select class="form-control" id="id_cat_3" name="id_cat_3">
					{$favorite_category_3}
				</select> {$id_cat_3_error}
			</span>
		</div>
		{/if}
	{/if}
	{if !empty($STR_USER_ORIGIN)}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="origin">{$STR_USER_ORIGIN}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">{include file="user_origins.tpl" origin_infos=$origin_infos}{$origin_infos.error_text}</span>
		</div>
	{/if}
{/if}
{foreach $specific_fields as $f}
	{if $f.field_position!='company'}
		<div class="enregistrement">
		{if !empty($f.field_title)}
			<span class="enregistrementgauche"><label for="{$f.field_name}">{$f.field_title}{if !empty($f.mandatory_fields)}<span class="etoile">*</span>{/if}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">{include file="specific_field.tpl" f=$f}{$f.error_text}</span>
		{else}
			{include file="specific_field.tpl" f=$f}{$f.error_text}
		{/if}
		</div>
	{/if}
{/foreach}
{if $language_for_automatic_emails_options|@count>1}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label >{$STR_LANGUAGE_FOR_AUTOMATIC_EMAILS}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<select class="form-control" id="lang" name="lang">
				{html_options options=$language_for_automatic_emails_options selected=$language_for_automatic_emails_selected}
				</select>
			</span>
		</div>
{/if}
{if isset($captcha)}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="code">{$captcha.validation_code_txt}{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				{$captcha.inside_form}
			</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="code">{$captcha.validation_code_copy_txt} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
			<span class="enregistrementdroite">
				<input name="code" size="5" maxlength="5" type="text" class="form-control" id="code" value="{$captcha.value|str_form_value}" />
			</span>{$captcha.error}
		</div>
{/if}
		<p><span class="form_mandatory">(*) {$STR_MANDATORY}</span></p>
	</div>
	<table class="inscription_form_table">
{if $is_annonce_module_active}
		<tr>
			<td colspan="2">
				<div>
					<span><input type="checkbox" id="cgv_confirm" name="cgv_confirm" value="1"{if $cgv_issel} checked="checked"{/if} />
					<label for="cgv_confirm">{$STR_CGV_YES}</label>
					</span>{$cgv_yes_error}
				</div>
			</td>
		</tr>
{/if}
{if !empty($STR_NEWSLETTER_YES)}
		<tr>
			<td colspan="2">
				<div>
					<input type="checkbox" id="newsletter" name="newsletter" value="1"{if $newsletter_issel} checked="checked"{/if} />
					<label for="newsletter">{$STR_NEWSLETTER_YES}</label>
				</div>
			</td>
		</tr>
{/if}
		<tr>
			<td colspan="2">
				<div>
{if !empty($STR_COMMERCIAL_YES)}
					<input type="checkbox" id="commercial" name="commercial" value="1"{if $commercial_issel} checked="checked"{/if} />
					<label for="commercial">{$STR_COMMERCIAL_YES}</label>
{/if}
					<p class="center">{$token}<input class="btn btn-primary" type="submit" value="{$submit_text|str_form_value}" /></p>
					<p>{$cnil_txt}</p>
				</div>
			</td>
		</tr>
	</table>
	</form>
</div>
{$js_password_control}