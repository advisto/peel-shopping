{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: user_change_params_form.tpl 48447 2016-01-11 08:40:08Z sdelaporte $
*}<h1 property="name" class="page_title">{$STR_CHANGE_PARAMS}</h1>
{if isset($token_error)}{$token_error}{/if}
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
{if empty($enable_display_only_user_specific_field)}
<div class="inscription_form">
	{if isset($verified_account_info)}{$verified_account_info}{/if}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label>{$STR_EMAIL}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="email" class="form-control" name="email" id="email" value="{$email|html_entity_decode_if_needed|str_form_value}" {$content_rows_info} /></span>{$email_error}<br />{$email_explain}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label>{$STR_GENDER}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">
			<input type="radio" name="civilite" value="Mlle"{if $civilite_mlle_issel} checked="checked"{/if} /> {$STR_MLLE} &nbsp;
			<input type="radio" name="civilite" value="Mme"{if $civilite_mme_issel} checked="checked"{/if} /> {$STR_MME} &nbsp;
			<input type="radio" name="civilite" value="M."{if $civilite_m_issel} checked="checked"{/if} /> {$STR_M}
		</span>{$gender_error}
	</div>
	{if !empty($STR_PSEUDO)}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="pseudo">{$STR_PSEUDO} {if !$pseudo_is_optionnal}<span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label>{/if}</span>
		<span class="enregistrementdroite">{if $is_annonce_module_active}<b>{$pseudo|html_entity_decode_if_needed}</b></span>{else}<input type="text" class="form-control" name="pseudo" id="pseudo" value="{$pseudo|html_entity_decode_if_needed|str_form_value}" {$content_rows_info} />{/if}</span>{$pseudo_error}
	</div>
	{/if}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="prenom">{$STR_FIRST_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="prenom" id="prenom" value="{$first_name|html_entity_decode_if_needed|str_form_value}" {$content_rows_info} /></span>{$first_name_error}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="nom_famille">{$STR_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="nom_famille" id="nom_famille" value="{$name|html_entity_decode_if_needed|str_form_value}" {$content_rows_info} /></span>{$name_error}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="societe">{$STR_SOCIETE}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="societe" id="societe" value="{$societe|html_entity_decode_if_needed|str_form_value}" {$content_rows_info} /></span>{$societe_error}
	</div>
	{foreach $specific_fields as $f}
		{if $f.field_position=='company'}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="{$f.field_name}">{$f.field_title}{if !empty($f.mandatory)}<span class="etoile">*</span>{/if}{$STR_BEFORE_TWO_POINTS}:</label></span>
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
		</span>{$type_error}
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
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="siret">{$siret_txt}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" id="siret" name="siret" value="{$siret|html_entity_decode_if_needed|str_form_value}" /></span>{$siret_error}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="tva">{$STR_INTRACOM_FORM}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" id="tva" name="intracom_for_billing" value="{$intracom_form|html_entity_decode_if_needed|str_form_value}" {$content_rows_info} /></span>{$intracom_form_error}
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
		<span class="enregistrementgauche"><label for="telephone">{$STR_TELEPHONE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="tel" class="form-control" name="telephone" id="telephone" value="{$telephone|str_form_value}" {$content_rows_info} /></span>{$telephone_error}
	</div>
{if !empty($STR_PORTABLE)}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="portable">{$STR_PORTABLE}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="tel" class="form-control" name="portable" id="portable" value="{$portable|str_form_value}" {$content_rows_info} /></span>
	</div>
{/if}
{if false}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="fax">{$STR_FAX}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="tel" class="form-control" name="fax" id="fax" value="{$fax|str_form_value}" {$content_rows_info} /></span>
	</div>
{/if}
{if !empty($STR_NAISSANCE)}
{if !empty($birthday_show)}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="naissance">{$STR_NAISSANCE}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">{$naissance}<br />{$STR_ERR_BIRTHDAY1}</span>
	</div>
{else}
	{if !empty($birthday_edit)}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="naissance">{$STR_NAISSANCE}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input class="form-control datepicker" type="text" name="naissance" id="naissance" value="{$naissance|str_form_value}" style="width:110px" />{$naissance_error}
	</div>
	{elseif !empty($birthday_contact_admin)}
		{$STR_ERR_BIRTHDAY2}
	{/if}
{/if}
{/if}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="adresse">{$STR_ADDRESS} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><textarea class="form-control" cols="30" rows="2" name="adresse" id="adresse" {$content_rows_info}>{$adresse|html_entity_decode_if_needed}</textarea></span>{$adresse_error}
	</div>
	{foreach $specific_fields as $f}
		{if $f.field_position=='adresse'}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="{$f.field_name}">{$f.field_title}{if !empty($f.mandatory)}<span class="etoile">*</span>{/if}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">{include file="specific_field.tpl" f=$f}{$f.error_text}</span>
	</div>
		{/if}
	{/foreach}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="code_postal">{$STR_ZIP} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="code_postal" id="code_postal" value="{$zip|str_form_value}" {$content_rows_info} /></span>{$zip_error}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="ville">{$STR_TOWN} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="ville" id="ville" value="{$town|html_entity_decode_if_needed|str_form_value}" {$content_rows_info} /></span>{$town_error}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="pays">{$STR_COUNTRY}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" name="pays" id="pays" {$content_rows_info}>
				{$country_options}
			</select>
		</span>
	</div>
{if !empty($STR_PROMO_CODE)}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="promo_code">{$STR_PROMO_CODE}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" id="promo_code" name="promo_code" value="{$promo_code|str_form_value}" /></span>
	</div>
{/if}
{if $is_annonce_module_active}
	{if !empty($id_categories) || !empty($id_cat_1)}
	<div class="enregistrement">
		<span>{$STR_ANNOUNCEMENT_INDICATION}</span>
	</div>
	{/if}
	{if !empty($id_categories)}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="id_categories">{$STR_FIRST_CHOICE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="id_categories" name="id_categories">
				{$id_categories}
			</select>
		</span>
		{$id_categories_error}
	</div>
	{elseif !empty($id_cat_1)}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="id_cat_1">{$STR_FIRST_CHOICE} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="id_cat_1" name="id_cat_1">
				{$id_cat_1}
			</select>
		</span>
		{$id_cat_1_error}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="id_cat_2">{$STR_SECOND_CHOICE}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="id_cat_2" name="id_cat_2">
				{$id_cat_2}
			</select>
		</span>
		{$id_cat_2_error}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="id_cat_3">{$STR_THIRD_CHOICE}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="id_cat_3" name="id_cat_3">
				{$id_cat_3}
			</select>
		</span>
		{$id_cat_3_error}
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
		{if $f.field_position!='company' && $f.field_position!='adresse'}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="{$f.field_name}">{$f.field_title}{if !empty($f.mandatory)}<span class="etoile">*</span>{/if}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">{include file="specific_field.tpl" f=$f}{$f.error_text}</span>
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
{if !empty($STR_LOGO)}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="logo">{$STR_LOGO}{$STR_BEFORE_TWO_POINTS}:</label></span>
		<span class="enregistrementdroite">
		{if isset($logo)}
			{include file="uploaded_file.tpl" f=$logo STR_DELETE=$logo.STR_DELETE_THIS_FILE}
		{else}
			<input name="logo" type="file" value="" />
		{/if}
	</div>
{/if}
{if !empty($STR_NEWSLETTER_YES)}
	<div class="enregistrement">
		<span class="enregistrement"><input type="checkbox" name="newsletter" value="1"{if $newsletter_issel} checked="checked"{/if} /> {$STR_NEWSLETTER_YES}</span>
	</div>
{/if}
{if !empty($STR_COMMERCIAL_YES)}
	<div class="enregistrement">
		<span class="enregistrement"><input type="checkbox" name="commercial" value="1"{if $commercial_issel} checked="checked"{/if} /> {$STR_COMMERCIAL_YES}</span>
	</div>	
{/if}
</div>
	<p class="center">
		{$token}<input type="submit" value="{$STR_CHANGE|str_form_value}" class="btn btn-primary btn-lg" />
		<input type="hidden" name="id_utilisateur" value="{$id_utilisateur|str_form_value}" />
	</p>
	<p>{$cnil_txt|textEncode}</p>
</form>