{# Twig
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
// $Id: user_register_form.tpl 35330 2013-02-16 18:27:13Z gboussin $
#}<h1 class="page_title">{{ STR_FIRST_REGISTER_TITLE }}</h1>
<div class="user_register_form">
	<p>{{ STR_FIRST_REGISTER_TEXT }}</p>
	<table class="partner-list">
		<tr>
			<td class="title">
				<h3 style="padding-left:0;">{{ STR_OPEN_ACCOUNT }}</h3>
			</td>
		</tr>
	</table>
	<form class="entryform" method="post" action="{{ action|escape('html') }}">
	<div class="inscription_form" >
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="email">{{ STR_EMAIL }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input class="champtexte" type="text" id="email" name="email" value="{{ email|html_entity_decode_if_needed|str_form_value }}" /></span>{{ email_error }}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="pseudo">{{ STR_PSEUDO }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input class="champtexte" type="text" id="pseudo" name="pseudo" value="{{ pseudo|html_entity_decode_if_needed|str_form_value }}" /></span>{{ pseudo_error }}<br />
			<span class="enregistrementgauche">&nbsp;</span>
			<span>{{ STR_STRONG_PSEUDO_NOTIFICATION }}</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="mot_passe">{{ STR_PASSWORD }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input class="champtexte" type="password" id="mot_passe" name="mot_passe" /></span>{{ password_error }}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="pwd_level">{{ STR_PASSWORD_SECURITY }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite" id="pwd_level_image"></span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche">&nbsp;</span>
			<span class="enregistrementdroite">{{ STR_STRONG_PASSWORD_NOTIFICATION }} </span>
		</div>
		{% if is_annonce_module_active %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="mot_passe_confirm">{{ STR_PASSWORD_CONFIRMATION }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input class="champtexte" type="password" id="mot_passe_confirm" name="mot_passe_confirm" /></span>
			{{ password_confirmation_error }}
		</div>
		{% endif %}
	</div>
	<div class="inscription_form" style="margin-top:10px;" >
		<div class="enregistrement">
			<span class="enregistrementgauche"><label>{{ STR_GENDER }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">
				<input type="radio" name="civilite" value="Mlle"{% if civilite_mlle_issel %} checked="checked"{% endif %} />{{ STR_MLLE }}
				<input type="radio" name="civilite" value="Mme"{% if civilite_mme_issel %} checked="checked"{% endif %} />{{ STR_MME }}
				<input type="radio" name="civilite" value="M."{% if civilite_m_issel %} checked="checked"{% endif %} />{{ STR_M }}
			</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="prenom">{{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="prenom" name="prenom" value="{{ first_name|html_entity_decode_if_needed|str_form_value }}" /></span>{{ first_name_error }}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="nom_famille">{{ STR_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="nom_famille" name="nom_famille" value="{{ name|html_entity_decode_if_needed|str_form_value }}" /></span>{{ name_error }}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="societe">{{ STR_SOCIETE }}{{ STR_BEFORE_TWO_POINTS }}{% if is_societe_mandatory %}<span class="etoile">*</span>{% endif %}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="societe" name="societe" value="{{ societe|html_entity_decode_if_needed|str_form_value }}" /></span>{{ societe_error }}
		</div>
{% if is_destockplus_module_active or is_algomtl_module_active %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="url">{{ STR_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="url" name="url" value="{{ url|html_entity_decode_if_needed|str_form_value }}" /></span>
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
			</span> {{ type_error }}
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
			<span class="enregistrementgauche"><label for="intracom_for_billing">{{ STR_INTRACOM_FORM }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="intracom_for_billing" name="intracom_for_billing" value="{{ intracom_form|html_entity_decode_if_needed|str_form_value }}" /></span>{{ intracom_form_error }}
		</div>
{% if is_annonce_module_active %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="siret">{{ siret_txt }} <span class="etoile"></span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="siret" name="siret" value="{{ siret|html_entity_decode_if_needed|str_form_value }}" /></span> {{ siret_error }}
		</div>
{% endif %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="naissance">{{ STR_NAISSANCE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input name="naissance" class="champtexte datepicker" type="text" id="naissance" size="10" maxlength="10" value="{{ naissance|str_form_value }}" /></span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="telephone">{{ STR_TELEPHONE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="telephone" name="telephone" value="{{ telephone|str_form_value }}" /></span>{{ telephone_error }}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="portable">{{ STR_PORTABLE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="portable" name="portable" value="{{ portable|str_form_value }}" /></span>
		</div>
		{% if is_annonce_module_active %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="user_fax">{{ STR_FAX }} <span class="etoile"></span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="user_fax" name="user_fax" value="{{ fax|html_entity_decode_if_needed|str_form_value }}" /></span>
		</div>
		{% endif %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="adresse">{{ STR_ADDRESS }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><textarea rows="3" cols="54" class="textarea-formulaire mono-colonne" id="adresse" name="adresse">{{ adresse|html_entity_decode_if_needed }}</textarea></span>{{ adresse_error }}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="code_postal">{{ STR_ZIP }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="code_postal" name="code_postal" value="{{ zip|str_form_value }}" /></span>{{ zip_error }}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="ville">{{ STR_TOWN }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="ville" name="ville" value="{{ town|html_entity_decode_if_needed|str_form_value }}" /></span>{{ town_error }}
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="pays">{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}<span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">
				<select id="pays" name="pays">
					{{ country_options }}
				</select>
			</span>
		</div>
		{% if is_annonce_module_active %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="user_website">{{ STR_WEBSITE }} <span class="etoile"></span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="user_website" name="user_website" value="{{ website|html_entity_decode_if_needed|str_form_value }}" /></span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="promo_code">{{ STR_PROMO_CODE }} <span class="etoile"></span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="text" class="champtexte" id="promo_code" name="promo_code" value="{{ promo_code|html_entity_decode_if_needed|str_form_value }}" /></span>
		</div>
		<div class="enregistrement">
			<span>{{ STR_ANNOUNCEMENT_INDICATION }}</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche">
			<label for="id_cat_1">{{ STR_FIRST_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">
				<select id="id_cat_1" name="id_cat_1">
					{{ favorite_category_1 }}
				</select> {{ id_cat_1_error }}
			</span>
			<span class="enregistrementgauche">
			<label for="id_cat_2">{{ STR_SECOND_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">
				<select id="id_cat_2" name="id_cat_2">
					{{ favorite_category_2 }}
				</select> {{ id_cat_2_error }}
			</span>
			<span class="enregistrementgauche">
			<label for="id_cat_3">{{ STR_THIRD_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">
				<select id="id_cat_3" name="id_cat_3">
					{{ favorite_category_3 }}
				</select> {{ id_cat_3_error }}
			</span>
		</div>
		{% endif %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="origin">{{ STR_USER_ORIGIN }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">{% include "user_origins.tpl" with {'origin_infos':origin_infos} }}{{ origin_infos.error_text }}</span>
		</div>
		{% if (captcha) %}
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="code">{{ captcha.validation_code_txt }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">
				{{ captcha.inside_form }}
			</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="code">{{ captcha.validation_code_copy_txt }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite">
				<input name="code" size="5" maxlength="5" type="text" id="code" value="{{ captcha.value|str_form_value }}" />
			</span>{{ captcha.error }}
		</div>			
		{% endif %}
		<p><span class="form_mandatory">(*) {{ STR_MANDATORY }}</span></p>
	</div>
	<table class="inscription_form_table">
		{% if is_annonce_module_active %}
		<tr>
			<td colspan="2">
				<div>
					<span><input type="checkbox" id="cgv_confirm" name="cgv_confirm" value="1"{% if cgv_issel %} checked="checked"{% endif %} />
					<label for="cgv_confirm">{{ STR_CGV_YES }}</label>
					</span>{{ cgv_yes_error }}
				</div>
			</td>
		</tr>
		{% endif %}
		<tr>
			<td colspan="2">
				<div>
					<span><input type="checkbox" id="newsletter" name="newsletter" value="1"{% if newsletter_issel %} checked="checked"{% endif %} />
					<label for="newsletter">{{ STR_NEWSLETTER_YES }}</label>
					</span>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div>
					<span>
						<input type="checkbox" id="commercial" name="commercial" value="1"{% if commercial_issel %} checked="checked"{% endif %} />
						<label for="commercial">{{ STR_COMMERCIAL_YES }}</label>
					</span>
					<p class="center">{{ token }}<input class="clicbouton" type="submit" value="{{ STR_OPEN_ACCOUNT|str_form_value }}" /></p>
					<p>{{ cnil_txt }}</p>
				</div>
			</td>
		</tr>
	</table>
	</form>
</div>
{{ js_password_control }}