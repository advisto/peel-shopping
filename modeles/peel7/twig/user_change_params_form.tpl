{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: user_change_params_form.tpl 37156 2013-06-05 12:42:24Z sdelaporte $
#}<h1 class="page_title">{{ STR_CHANGE_PARAMS }}</h1>
{% if (token_error) %}{{ token_error }}{% endif %}
<form class="entryform" method="post" action="{{ action|escape('html') }}">
<div class="inscription_form">
	{% if (verified_account_info) %}{{ verified_account_info }}{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label>{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="email" id="email" value="{{ email|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ email_error }}<br />{{ email_explain }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label>{{ STR_GENDER }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<input type="radio" name="civilite" value="Mlle"{% if civilite_mlle_issel %} checked="checked"{% endif %} />{{ STR_MLLE }}
			<input type="radio" name="civilite" value="Mme"{% if civilite_mme_issel %} checked="checked"{% endif %} />{{ STR_MME }}
			<input type="radio" name="civilite" value="M."{% if civilite_m_issel %} checked="checked"{% endif %} />{{ STR_M }}
		</span>
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="pseudo">{{ STR_PSEUDO }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">{% if is_annonce_module_active %}<b>{{ pseudo|html_entity_decode_if_needed }}</b></span><input type="hidden" name="pseudo" value="{{ pseudo|html_entity_decode_if_needed|str_form_value }}" />{% else %}<input class="champtexte" type="text" name="pseudo" id="pseudo" value="{{ pseudo|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} />{% endif %}</span>{{ pseudo_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="prenom">{{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="prenom" id="prenom" value="{{ first_name|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ first_name_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="nom_famille">{{ STR_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="nom_famille" id="nom_famille" value="{{ name|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ name_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="societe">{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="societe" id="societe" value="{{ societe|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ societe_error }}
	</div>
{% if is_destockplus_module_active or is_algomtl_module_active }}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="url">{{ STR_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="champtexte" id="url" name="url" placeholder="http://" value="{{ url|html_entity_decode_if_needed|str_form_value }}" /></span>
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="type">{{ STR_YOU_ARE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select id="type" name="type">
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
		<span class="enregistrementgauche"><label for="activity">{{ STR_ACTIVITY }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select id="activity" name="activity">
				<option value="">{{ STR_CHOOSE }}...</option>
				<option value="punctual" {% if activity=='punctual' %} selected="selected"{% endif %}>{{ STR_PUNCTUAL }}</option>
				<option value="recurrent" {% if activity=='recurrent' %} selected="selected"{% endif %}>{{ STR_RECURRENT }}</option>
			</select>
		</span>{{ activity_error }}
	</div>
{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="fonction">{{ STR_FONCTION }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select id="fonction" name="fonction">
				<option value="">{{ STR_CHOOSE }}...</option>
				<option value="leader" {% if fonction=='leader' %} selected="selected"{% endif %}>{{ STR_LEADER }}</option>
				<option value="manager" {% if fonction=='manager' %} selected="selected"{% endif %}>{{ STR_MANAGER }}</option>
				<option value="employee" {% if fonction=='employee' %} selected="selected"{% endif %}>{{ STR_EMPLOYEE }}</option>
			</select>
		</span>{{ fonction_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="tva">{{ STR_INTRACOM_FORM }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="champtexte" id="tva" name="intracom_for_billing" value="{{ intracom_form|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ intracom_form_error }}
	</div>
{% if is_annonce_module_active %} 
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="siret">{{ siret_txt }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="champtexte" id="siret" name="siret" value="{{ siret|html_entity_decode_if_needed|str_form_value }}" /></span>{{ siret_error }}
	</div>
{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="telephone">{{ STR_TELEPHONE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="telephone" id="telephone" value="{{ telephone|str_form_value }}" {{ content_rows_info }} /></span>{{ telephone_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="portable">{{ STR_PORTABLE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="portable" id="portable" value="{{ portable|str_form_value }}" {{ content_rows_info }} /></span>
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="fax">{{ STR_FAX }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="fax" id="fax" value="{{ fax|str_form_value }}" {{ content_rows_info }} /></span>
	</div>
{% if (birthday_show) %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="naissance">{{ STR_NAISSANCE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">{{ naissance }}<br />{{ STR_ERR_BIRTHDAY1 }}</span>
	</div>
{% else %}
	{% if (birthday_edit) %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="naissance">{{ STR_NAISSANCE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte datepicker" type="text" name="naissance" id="naissance" value="{{ naissance|str_form_value }}" />{{ naissance_error }}
	</div>
	{% elseif (birthday_contact_admin) %}
		{{ STR_ERR_BIRTHDAY2 }}
	{% endif %}
{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="adresse">{{ STR_ADDRESS }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><textarea cols="30" rows="2" name="adresse" id="adresse" {{ content_rows_info }}>{{ adresse|html_entity_decode_if_needed }}</textarea></span>{{ adresse_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="code_postal">{{ STR_ZIP }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="code_postal" id="code_postal" value="{{ zip|str_form_value }}" {{ content_rows_info }} /></span>{{ zip_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="ville">{{ STR_TOWN }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input class="champtexte" type="text" name="ville" id="ville" value="{{ town|html_entity_decode_if_needed|str_form_value }}" {{ content_rows_info }} /></span>{{ town_error }}
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="pays">{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select class="champtexte" name="pays" id="pays" {{ content_rows_info }}>
				{{ country_options }}
			</select>
		</span>
	</div>
{% if is_annonce_module_active %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="promo_code">{{ STR_PROMO_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite"><input type="text" class="champtexte" id="promo_code" name="promo_code" value="{{ promo_code|str_form_value }}" /></span>
	</div>
	<div class="enregistrement">
		<span>{{ STR_ANNOUNCEMENT_INDICATION }}</span>
	</div>
	<div class="enregistrement">
		{% if favorite_category %}
		<span class="enregistrementgauche"><label for="favorite_category">{{ STR_FIRST_CHOICE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select id="favorite_category" name="favorite_category">
				{{ favorite_category }}
			</select>
		</span>
		{{ favorite_category_error }}
		{% else %}
		<span class="enregistrementgauche"><label for="id_cat_1">{{ STR_FIRST_CHOICE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select id="id_cat_1" name="id_cat_1">
				{{ favorite_category_1 }}
			</select>
		</span>
		{{ id_cat_1_error }}
		<span class="enregistrementgauche"><label for="id_cat_2">{{ STR_SECOND_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select id="id_cat_2" name="id_cat_2">
				{{ favorite_category_2 }}
			</select>
		</span>
		{{ id_cat_2_error }}
		<span class="enregistrementgauche"><label for="id_cat_3">{{ STR_THIRD_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select id="id_cat_3" name="id_cat_3">
				{{ favorite_category_3 }}
			</select>
		</span>
		{{ id_cat_3_error }}
	</div>
	{% endif %}
{% endif %}
	<div class="enregistrement">
		<span class="enregistrementgauche"><label for="origin">{{ STR_USER_ORIGIN }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">{% include "user_origins.tpl" with {'origin_infos':origin_infos} }}{{ origin_infos.error_text }}</span>
	</div>
	<div class="enregistrement">
		<span class="enregistrementgauche"><label >{{ STR_LANGUAGE_FOR_AUTOMATIC_EMAILS }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
		<span class="enregistrementdroite">
			<select id="lang" name="lang">
			{% for key in language_for_automatic_emails_options|keys %}
				<option value="{{ key|str_form_value }}"{% if key=language_for_automatic_emails_selected %} selected="selected"{% endif %}>{{ options.key }}</option>
			{% endfor %}
			</select>
		</span>
	</div>
	<div class="enregistrement">
		<span class="enregistrement"><input type="checkbox" name="newsletter" value="1"{% if newsletter_issel %} checked="checked"{% endif %} />{{ STR_NEWSLETTER_YES }}</span>
	</div>
	<div class="enregistrement">
		<span class="enregistrement"><input type="checkbox" name="commercial" value="1"{% if commercial_issel %} checked="checked"{% endif %} />{{ STR_COMMERCIAL_YES }}</span>
	</div>	
</div>
	<p class="center">
		{{ token }}<input type="submit" value="{{ STR_CHANGE|str_form_value }}" class="clicbouton" />
		<input type="hidden" name="id_utilisateur" value="{{ id_utilisateur|str_form_value }}" />
	</p>
	<p>{{ cnil_txt|textEncode }}</p>
</form>