{# Twig
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
// $Id: user_change_params_form.tpl 55291 2017-11-27 17:13:45Z sdelaporte $
#}<h1 property="name" class="page_title">{{ STR_CHANGE_PARAMS }}</h1>
{% if (token_error) %}{{ token_error }}{% endif %}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
{% if not (enable_display_only_user_specific_field) %}
	<div class="inscription_form">
	{% if (verified_account_info) %}{{ verified_account_info }}{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label>{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="email" class="form-control" name="email" id="email" value="{{ email|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ email_error }}<br />{{ email_explain }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label>{{ STR_GENDER }}{% if mandatory.civilite %}<span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<input type="radio" name="civilite" value="Mlle"{% if civilite_mlle_issel %} checked="checked"{% endif %} /> {{ STR_MLLE }} &nbsp;
			<input type="radio" name="civilite" value="Mme"{% if civilite_mme_issel %} checked="checked"{% endif %} /> {{ STR_MME }} &nbsp;
			<input type="radio" name="civilite" value="M."{% if civilite_m_issel %} checked="checked"{% endif %} /> {{ STR_M }}
		</span>{{ gender_error }}
	</div>
	{% if (STR_PSEUDO) %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="pseudo">{{ STR_PSEUDO }} {% if mandatory.pseudo %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">{% if is_annonce_module_active %}<b>{{ pseudo|html_entity_decode_if_needed }}</b></span>{% else %}<input class="form-control" type="text" name="pseudo" id="pseudo" value="{{ pseudo|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} />{% endif %}</span>{{ pseudo_error }}
	</div>
	{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="prenom">{{ STR_FIRST_NAME }} {% if mandatory.prenom %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="prenom" id="prenom" value="{{ first_name|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ first_name_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="nom_famille">{{ STR_NAME }} {% if mandatory.nom_famille %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="nom_famille" id="nom_famille" value="{{ name|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ name_error }}
	</div>
	<div class="company_section">
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="societe">{{ STR_SOCIETE }}{% if mandatory.societe %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="form-control" name="societe" id="societe" value="{{ societe|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ societe_error }}
		</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="siret">{{ siret_txt }}{% if mandatory.siret %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" id="siret" name="siret" value="{{ siret|html_entity_decode_if_needed|str_form_value }}" /></span>{{ siret_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="tva">{{ STR_INTRACOM_FORM }}{% if mandatory.tva %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" id="tva" name="intracom_for_billing" value="{{ intracom_form|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ intracom_form_error }}
	</div>
	{% endif %}
	{% for f in specific_fields %}
		{% if f.field_position=='company' %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="{{ f.field_name }}">{{ f.field_title }}{% if (f.mandatory) %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">{% include "specific_field.tpl" with {'f':f} %}{{ f.error_text }}</span>
		</div>
		{% endif %}
	{% endfor %}
	</div>
{% if add_b2b_form_inputs %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="url">{{ STR_WEBSITE }}{% if mandatory.url %}<span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" id="url" name="url" placeholder="http://" value="{{ url|html_entity_decode_if_needed|str_form_value }}" /></span>
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="type">{{ STR_YOU_ARE }} {% if mandatory.type %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="type" name="type">
				<option value="">{{ STR_CHOOSE }}...</option>
				<option disabled="disabled" style="text-align:center;font-weight:bold;" value="">{{ STR_BUYERS }}{{ STR_BEFORE_TWO_POINTS }}:</option>
				<option value="importers_exporters"{% if type=='importers_exporters' %} selected="selected"{% endif %}>{{ STR_IMPORTERS_EXPORTERS }}</option>
				<option value="commercial_agent"{% if type=='commercial_agent' %} selected="selected"{% endif %}>{{ STR_COMMERCIAL_AGENT }}</option>
				<option value="purchasing_manager"{% if type=='purchasing_manager' %} selected="selected"{% endif %}>{{ STR_PURCHASING_MANAGER }}</option>
				<option disabled="disabled" style="text-align:center;font-weight:bold;" value="">{{ STR_WORD_SELLERS }}{{ STR_BEFORE_TWO_POINTS }}:</option>
				<option value="wholesaler"{% if type=='wholesaler' %} selected="selected"{% endif %}>{{ STR_WHOLESALER }}</option>
				<option value="half_wholesaler"{% if type=='half_wholesaler' %} selected="selected"{% endif %}>{{ STR_HALF_WHOLESALER }}</option>
				<option value="retailers"{% if type=='retailers' %} selected="selected"{% endif %}>{{ STR_RETAILERS }}</option>
			</select>
		</span>{{ type_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="activity">{{ STR_ACTIVITY }} {% if mandatory.activity %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="activity" name="activity">
				<option value="">{{ STR_CHOOSE }}...</option>
				<option value="punctual" {% if activity=='punctual' %} selected="selected"{% endif %}>{{ STR_PUNCTUAL }}</option>
				<option value="recurrent" {% if activity=='recurrent' %} selected="selected"{% endif %}>{{ STR_RECURRENT }}</option>
			</select>
		</span>{{ activity_error }}
	</div>
{% endif %}
{% if (STR_FONCTION) %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="fonction">{{ STR_FONCTION }}{% if is_fonction_mandatory %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="fonction" name="fonction">
				<option value="" data-description="" >{{ STR_CHOOSE }}...</option>
				{{ fonction_options }}
			</select>
		</span>{{ fonction_error }}
		<div id="popover" class="hidden" style="top: 297px; left: 723.633px; display: block;"><div class="arrow"></div><div id="message_option" class="popover-content"></div></div>
	</div>
{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="telephone">{{ STR_TELEPHONE }} {% if mandatory.telephone %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="tel" class="form-control" name="telephone" id="telephone" value="{{ telephone|str_form_value }}" {{ content_rows_info }} /></span>{{ telephone_error }}
	</div>
{% if (STR_PORTABLE) %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="portable">{{ STR_PORTABLE }}{% if mandatory.portable %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="tel" class="form-control" name="portable" id="portable" placeholder="{{ form_placeholder_portable|str_form_value }}" value="{{ portable|str_form_value }}" {{ content_rows_info }} /></span>
	</div>
{% endif %}
{% if false %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="fax">{{ STR_FAX }}{% if mandatory.fax %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="tel" class="form-control" name="fax" id="fax" value="{{ fax|str_form_value }}" {{ content_rows_info }} /></span>
	</div>
{% endif %}
{% if (STR_NAISSANCE) %}
{% if (birthday_show) %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="naissance">{{ STR_NAISSANCE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">{{ naissance }}<br />{{ STR_ERR_BIRTHDAY1 }}</span>
	</div>
{% else %}
	{% if (birthday_edit) %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="naissance">{{ STR_NAISSANCE }}{% if mandatory.naissance %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="form-control datepicker" type="text" name="naissance" id="naissance" value="{{ naissance|str_form_value }}" />{{ naissance_error }}
	</div>
	{% elseif (birthday_contact_admin) %}
		{{ STR_ERR_BIRTHDAY2 }}
	{% endif %}
{% endif %}
{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="adresse">{{ STR_ADDRESS }} {% if mandatory.adresse %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><textarea class="form-control" cols="30" rows="2" name="adresse" id="adresse" {{ content_rows_info }}>{{ adresse|html_entity_decode_if_needed }}</textarea></span>{{ adresse_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="code_postal">{{ STR_ZIP }} {% if mandatory.code_postal %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="code_postal" id="code_postal" value="{{ zip|str_form_value }}" {{ content_rows_info }} /></span>{{ zip_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="ville">{{ STR_TOWN }} {% if mandatory.ville %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" name="ville" id="ville" value="{{ town|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ town_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="pays">{{ STR_COUNTRY }}{% if mandatory.pays %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" name="pays" id="pays" {{ content_rows_info }}>
				{{ country_options }}
			</select>
		</span>
	</div>
{% if STR_PROMO_CODE %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="promo_code">{{ STR_PROMO_CODE }}{% if mandatory.promo_code %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="form-control" id="promo_code" name="promo_code" value="{{ promo_code|str_form_value }}" /></span>
	</div>
	<div class="enregistrement">
		<span>{{ STR_ANNOUNCEMENT_INDICATION }}</span>
	</div>
	{% if id_categories %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="id_categories">{{ STR_FIRST_CHOICE }} {% if mandatory.id_categories %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="id_categories" name="id_categories">
				{{ id_categories }}
			</select>
		</span>
	</div>
		{{ id_categories_error }}
	{% elseif id_cat_1 %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="id_cat_1">{{ STR_FIRST_CHOICE }} {% if mandatory.id_cat_1 %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="id_cat_1" name="id_cat_1">
				{{ id_cat_1 }}
			</select>
		</span>
		{{ id_cat_1_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="id_cat_2">{{ STR_SECOND_CHOICE }}{% if mandatory.id_cat_2 %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="id_cat_2" name="id_cat_2">
				{{ id_cat_2 }}
			</select>
		</span>
		{{ id_cat_2_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="id_cat_3">{{ STR_THIRD_CHOICE }}{% if mandatory.id_cat_3 %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="id_cat_3" name="id_cat_3">
				{{ id_cat_3 }}
			</select>
		</span>
		{{ id_cat_3_error }}
	</div>
	{% endif %}
{% endif %}
{% if (STR_USER_ORIGIN) %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="origin">{{ STR_USER_ORIGIN }}{% if mandatory.origin_infos %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">{% include "user_origins.tpl" with {'origin_infos':origin_infos} %}{{ origin_infos.error_text }}</span>
	</div>
{% endif %}
	{% for f in specific_fields %}
		{% if f.field_position!='company' %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="{{ f.field_name }}">{{ f.field_title }}{% if f.mandatory %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">{% include "specific_field.tpl" with {'f':f} %}{{ f.error_text }}</span>
	</div>
		{% endif %}
	{% endfor %}
	{% if language_for_automatic_emails_options|length>1 %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label >{{ STR_LANGUAGE_FOR_AUTOMATIC_EMAILS }}{% if mandatory.lang %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="form-control" id="lang" name="lang">
			{% for key in language_for_automatic_emails_options|keys %}
				<option value="{{ key|str_form_value }}"{% if key==language_for_automatic_emails_selected %} selected="selected"{% endif %}>{{ language_for_automatic_emails_options.key }}</option>
			{% endfor %}
			</select>
		</span>
	</div>
	{% endif %}
	{% if STR_LOGO %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="logo">{{ STR_LOGO }}{% if mandatory.logo %} <span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">
			{% if logo is defined %}
				{% include "specific_field.tpl" with {'f':f} %}
			{% else %}
				<input name="logo" type="file" value="" />
			{% endif %}
		</div>
	{% endif %}
	{% if (STR_NEWSLETTER_YES) %}
	<div class="enregistrement">
		<span class="enregistrement"><input type="checkbox" name="newsletter" value="1"{% if newsletter_issel %} checked="checked"{% endif %} /> {{ STR_NEWSLETTER_YES }}</span>
	</div>
	{% endif %}
	{% if (STR_COMMERCIAL_YES) %}
	<div class="enregistrement">
		<span class="enregistrement"><input type="checkbox" name="commercial" value="1"{% if commercial_issel %} checked="checked"{% endif %} /> {{ STR_COMMERCIAL_YES }}</span>
	</div>	
	{% endif %}
</div>
	<p class="center">
		{{ token }}<input type="submit" value="{{ STR_CHANGE|str_form_value }}" class="btn btn-primary btn-lg" />
		<input type="hidden" name="id_utilisateur" value="{{ id_utilisateur|str_form_value }}" />
	</p>
	<p>{{ cnil_txt|textEncode }}</p>
</form>