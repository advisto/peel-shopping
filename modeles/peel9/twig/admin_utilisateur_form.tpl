{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_utilisateur_form.tpl 55304 2017-11-28 15:49:01Z sdelaporte $
#}
<table class="main_table">
{% if hook_actions %}
		<tr>
			<td style="font-weight:bold;" colspan="2">{{ hook_actions }}</td>
		</tr>
{% endif %}


	</table>

<ul class="nav nav-tabs">
	<li role="presentation" class="active"><a href="#tab1" data-toggle="tab">{{ STR_ADMIN_CLIENT_PAGE }}</a></li>
	<li role="presentation"><a href="#tab2" data-toggle="tab">{{ STR_ADMIN_MENU_SALES_SALES_TITLE }}</a></li>
	<li role="presentation"><a href="#tab3" data-toggle="tab">{{ STR_ADDRESSES }}</a></li>
	<li role="presentation"><a href="#tab4" data-toggle="tab">{{ STR_ADMIN_CONTACT_AND_ACTION }}</a></li>
	<li role="presentation"><a href="#tab5" data-toggle="tab">{{ STR_ADMIN_CONNECTION_HISTORY }}</a></li>
	{% if is_annonces_module_active %}
	<li role="presentation"><a href="#tab6" data-toggle="tab">{{ STR_ADMIN_MENU_MODERATION_ADS_HEADER }}</a></li>
	{% endif %}
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="tab1">
		<form class="entryform form-inline" role="form" enctype="multipart/form-data" method="post" action="{{ action|escape('html') }}">
			<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
			<input type="hidden" name="id_utilisateur" value="{{ id_utilisateur|str_form_value }}" />
			<input type="hidden" name="remise_valeur" value="{{ remise_valeur|str_form_value }}" />
			{{ form_token }}
			<table>
		<tr>
			<td class="entete" colspan="2"><img src="{{ administrer_url }}/images/liste_clients.gif" width="16" height="16" alt="" align="absmiddle" />{{ STR_ADMIN_UTILISATEURS_EDIT_TITLE }} {{ email }}</td>
		</tr>
	{% if id_utilisateur %}
		{% if is_webmail_module_active %}
			<tr>
				<td style="font-weight:bold;" colspan="2">
					<a onclick="return(window.open(this.href)?false:true);" href="{{ wwwroot_in_admin }}/modules/webmail/administrer/webmail_send.php?id_utilisateur={{ id_utilisateur }}">{{ STR_ADMIN_UTILISATEURS_SEND_EMAIL }} {{ email }}</a>
				</td>
			</tr>
		{% endif %}
			<tr>
				<td style="font-weight:bold;" colspan="2">
					<a onclick="return(window.open(this.href)?false:true);" href="{{ administrer_url }}/commander.php?mode=ajout&id_utilisateur={{ id_utilisateur }}">{{ STR_ADMIN_UTILISATEURS_CREATE_ORDER_TO_THIS_USER }} #{{ id_utilisateur }}</a>
				</td>
			</tr>
		{% if more_infos_html_top_list_link %}
			<tr>
				<td style="font-weight:bold;" colspan="2">
					<a href="{{ more_infos_html_top_list_link }}">{{ STR_MODULE_DREAMTAKEOFF_DISPLAY_PROJECTS_WITH_RELATED_USER }}</a>
				</td>
			</tr>
		{% endif %}
	{% endif %}
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_UTILISATEURS_UPDATE_EXPLAIN }}</div></td>
		</tr>
 		<tr>
			<td class="title_label">{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="email" class="form-control" name="email" style="width:100%" value="{{ email|str_form_value }}" />{{ email_infos }}</td>
		</tr>
{% if pseudo_is_not_used is empty %}
		<tr>
			<td class="title_label">{{ STR_PSEUDO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="pseudo" style="width:100%" value="{{ pseudo|str_form_value }}" /></td>
		</tr>
{% endif %}
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="etat" id="etat">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="1" {% if etat=='1' %} selected="selected"{% endif %}>{{ STR_ADMIN_ACTIVATED }}</option>
					<option value="0" {% if etat=='0' %} selected="selected"{% endif %}>{{ STR_ADMIN_DEACTIVATED }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_PRIVILEGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" multiple="multiple" name="priv[]">
				{{ priv_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="site_id"  {% if disable_user_siteweb %}disabled="disabled"{% endif %}>
					{{ site_id_select_options }}
				</select>
				{% if disable_user_siteweb %}<input type="hidden" name="site_id" value="0" />{% endif %}
			</td>
		</tr>
{% if (date_insert) %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_REGISTRATION_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td width="60%">{{ date_insert }}</td>
		</tr>
{% endif %}
{% if (last_date) %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_LAST_CONNECTION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td width="60%">{{ last_date }}</td>
		</tr>
{% endif %}
{% if (user_ip) %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_LAST_IP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td width="60%">{{ user_ip }} {{ country_name }}</td>
		</tr>
{% endif %}
{% if (date_update) %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_LAST_UPDATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ date_update }}</td>
		</tr>
{% endif %}
{% if is_annonce_module_active %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_UTILISATEURS_ADMIN_NOTE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
	{% if user_admin_note_edit_forbidden is empty %}
				<select class="form-control" name="note_administrateur">
		{% for note_admin in {0:-1, 1:10, 2:20, 3:30, 4:40, 5:50} %}
					<option value="{{ note_admin|str_form_value }}" {% if note_administrateur == note_admin %} selected="selected"{% endif %}>{% if note_admin == - 1 %}{{ STR_NONE }}{% else %}{{ note_admin/10|round }}{% endif %}</option>
		{% endfor %}
				</select>
	{% else %}
			{{ note_administrateur }}
	{% endif %}
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_UTILISATEURS_MODERATION_MORE_STRICT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" id="control_plus" name="control_plus">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="1" {% if control_plus=='1' %} selected="selected"{% endif %}>{{ STR_YES }}</option>
					<option value="0" {% if control_plus=='0' %} selected="selected"{% endif %}>{{ STR_NO }}</option>
				</select>
			</td>
		</tr>
{% endif %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_ACCOUNT_MANAGER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="commercial_contact_id">
					<option value="0"{% if not (commercial_contact_id) %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_NO_ACCOUNT_MANAGER }}</option>
					{% for o in util_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
{% if is_groups_module_active %}
		<tr>
			<td>{{ STR_ADMIN_GROUP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
	{% if (groupes_options) %}
			<select class="form-control" name="id_groupe">
				<option value="">-------------------------------------------</option>
				{% for o in groupes_options %}
				<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }} / - {{ o.remise }} %</option>
				{% endfor %}
			</select>
	{% else %}
			{{ STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED }}
	{% endif %}
			</td>
		</tr>
{% endif %}
{% if mode == "insere" %}
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_UTILISATEURS_CLIENT_CODE_HELP }}</div></td>
		</tr>
{% endif %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_CLIENT_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_client" style="width:100%" value="{{ code_client|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_COMPANY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="societe" style="width:100%" value="{{ societe|str_form_value }}" /></td>
		</tr>
{% if is_annonce_module_active %}
		<tr>
			<td>{{ STR_ACTIVITY }}{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<td>
				<select class="form-control" id="activity" name="activity">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="punctual" {% if activity == "punctual" %} selected="selected"{% endif %}>{{ STR_PUNCTUAL }}</option>
					<option value="recurrent" {% if activity == "recurrent" %} selected="selected"{% endif %}>{{ STR_RECURRENT }}</option>
				</select>
			</td>
		</tr>
{% endif %}
		<tr>
			<td>{{ STR_GENDER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="civilite" value="Mlle"{% if civilite == "Mlle" %} checked="checked"{% endif %} />{{ STR_MLLE }}
				<input type="radio" name="civilite" value="Mme"{% if civilite == "Mme" %} checked="checked"{% endif %} />{{ STR_MME }}
				<input type="radio" name="civilite" value="M."{% if civilite == "M." %} checked="checked"{% endif %} />{{ STR_M }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_FIRST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="prenom" style="width:100%" value="{{ prenom|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_LAST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="nom_famille" style="width:100%" value="{{ nom_famille|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="tel" class="form-control" name="telephone" style="width:100%" value="{{ telephone|str_form_value }}" />{{ telephone_calllink }}</td>
		</tr>
		<tr>
			<td>{{ STR_FAX }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="tel" class="form-control" name="fax" style="width:100%" value="{{ fax|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_PORTABLE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="tel" class="form-control" name="portable" style="width:100%" placeholder="{{ form_placeholder_portable|str_form_value }}" value="{{ portable|str_form_value }}" />{{ portable_calllink }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea class="form-control" name="adresse" style="width:100%" rows="6" cols="54">{{ adresse }}</textarea></td>
		</tr>
		<tr>
			<td>{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_postal" style="width:100%" value="{{ code_postal|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="ville" style="width:100%" value="{{ ville|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="pays">
					{{ country_select_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_NAISSANCE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control datepicker" name="naissance" style="width:150px" value="{{ naissance|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_CODES_PROMOS_PERCENT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="remise_percent" style="width:150px" value="{{ remise_percent|str_form_value }}" /> %</td>
		</tr>
		<tr>
			<td>{{ STR_AVOIR }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td ><input type="text" class="form-control" name="avoir" style="width:150px" value="{{ avoir|str_form_value }}" /> {{ site_symbole }}</td>
		</tr>
		<tr>
			<td>{{ STR_GIFT_POINTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td ><input type="text" class="form-control" name="points" style="width:150px" value="{{ points|str_form_value }}" /> {{ STR_GIFT_POINTS }}</td>
		</tr>
		{% if is_module_vacances_active and vacances_type == 2 %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_ON_HOLIDAY_SUPPLIER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_vacances" value="1"{% if (on_vacances) %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_SUPPLIER_RETURN_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control datepicker" name="on_vacances_date" style="width:110px" value="{{ on_vacances_date|str_form_value }}" /></td>
		</tr>
		{% endif %}
		<tr>
			<td>{{ STR_SIREN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" name="siret" style="width:150px"  value="{{ siret|str_form_value }}" />
				{% if societe %}
				<a onclick="return(window.open(this.href)?false:true);" href="http://www.societe.com/cgi-bin/liste?nom={{ societe }}">{{ STR_ADMIN_UTILISATEURS_SOCIETE_COM }}{{ STR_BEFORE_TWO_POINTS }}: {{ societe }}</a>
				{% else %}
				<a onclick="return(window.open(this.href)?false:true);" href="http://www.societe.com/index_entrep.html">{{ STR_ADMIN_UTILISATEURS_SOCIETE_COM }}</a>
				{% endif %}
				- <a onclick="return(window.open(this.href)?false:true);" href="https://www.infogreffe.fr/">{{ STR_ADMIN_UTILISATEURS_INFOGREFFE }}</a>
			</td>
		</tr>
		<tr>
			<td>{{ STR_VAT_INTRACOM }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="intracom_for_billing" style="width:100%" value="{{ intracom_for_billing|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_MODULE_PREMIUM_APE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="ape" style="width:100%" value="{{ ape|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="url" style="width:100%" placeholder="http://" value="{{ url|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_WEBSITE_DESCRIPTION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea class="form-control" name="description" style="width:100%" rows="10" cols="54">{{ description }}</textarea></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_banque" style="width:100%" value="{{ code_banque|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_COUNTER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_guichet" style="width:100%" value="{{ code_guichet|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="numero_compte" style="width:100%" value="{{ numero_compte|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_RIB }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="cle_rib" style="width:100%" value="{{ cle_rib|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_DOMICILIATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="domiciliation" style="width:100%" value="{{ domiciliation|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_SWIFT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				{% if bic_file %}
					{% if bic %}
						{% include "uploaded_file.tpl" with {'f':bic,'STR_DELETE':STR_DELETE_THIS_FILE } %}
					{% else %}
						<input name="bic" type="file" value="" />
					{% endif %}
				{% else %}
					<input type="text" class="form-control" name="bic" style="width:100%" value="{{ bic|str_form_value }}" />
				{% endif %}
			</td>
		</tr>
		<tr>
			<td>{{ STR_IBAN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="iban" style="width:100%" value="{{ iban|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ORIGIN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{% include "user_origins.tpl" with {'origin_infos':origin_infos} %}{{ origin_infos.error_text }}</td>
		</tr>
		{% for f in specific_fields %}
			{% if (f.field_title) %}
				<tr>
					<td>{{ f.field_title }}{% if f.mandatory %}<span class="etoile">*</span>{% endif %}{{ STR_BEFORE_TWO_POINTS }}:</td>
					<td>{% include "specific_field.tpl" with {'f':f} %}{{ f.error_text }}</td>
				</tr>
			{% else %}
				<tr>
					<td colspan="2">
						{% include "specific_field.tpl" with {'f':f} %}{{ f.error_text }}
					</td>
				</tr>
			{% endif %}
		{% endfor %}
		{% if langues|length>1 %}
		<tr>
			<td>{{ STR_LANGUAGE_FOR_AUTOMATIC_EMAILS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" id="lang" name="lang">
					{% for o in langues %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
		{% endif %}
{% if is_annonce_module_active and ((id_categories) or (id_cat_1)) %}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES }}</td>
		</tr>
	{% if id_categories %}
		<tr>
			<td>{{ STR_FIRST_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" id="id_categories" name="id_categories">
					{{ id_categories }}
				</select>
			</td>
		</tr>
	{% elseif id_cat_1 %}
		<tr>
			<td>{{ STR_FIRST_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" id="id_cat_1" name="id_cat_1">
					{{ id_cat_1 }}
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_SECOND_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" id="id_cat_2" name="id_cat_2">
					{{ id_cat_2 }}
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_THIRD_CHOICE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" id="id_cat_3" name="id_cat_3">
					{{ id_cat_3 }}
				</select>
			</td>
		</tr>
	{% endif %}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
{% endif %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_CLIENT_BUDGET }} {{ STR_HT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="project_budget_ht" style="width:100%" value="{{ project_budget_ht|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_CLIENT_PROJECT_CHANCES }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="project_chances_estimated" style="width:100%" value="{{ project_chances_estimated|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_COMMENTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea class="form-control" name="comments" style="width:100%">{{ comments }}</textarea></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_DESCRIPTION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea class="form-control"  name="description" style="width:100%" rows="10" cols="54">{{ description }}</textarea></td>
		</tr>
		<tr>
			<td style="width:40%;">{{ STR_LOGO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left middle">
				{% if (logo_src) %}<img src="{{ logo_src|escape('html') }}" alt="" /> <a href="{{ logo_del_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="" /> {{ STR_ADMIN_DELETE_LOGO }}</a>{% else %}<input type="file" id="logo" name="logo" />{% endif %}
			</td>
		</tr>
		{% if is_annonce_module_active and is_modif_mode %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_PROJECT_PRODUCT_PROPOSED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="project_product_proposed" style="width:100%" value="{{ project_product_proposed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_PROJECT_DATE_FORECASTED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control datepicker" name="project_date_forecasted" style="width:110px" value="{{ project_date_forecasted|str_form_value }}" /></td>
		</tr>
		{% endif %}
		{% if is_clients_module_active %}
		<tr>
			<td class="top" colspan="2"><input type="checkbox" id="on_client_module" name="on_client_module" value="1"{% if issel_on_client_module %} checked="checked"{% endif %} /> <label for="on_client_module">{{ STR_ADMIN_UTILISATEURS_PROJECT_DESCRIPTION_DISPLAY }}</label></td>
		</tr>
		{% endif %}
		{% if is_photodesk_module_active %}
		<tr>
			<td class="top" colspan="2"><input type="checkbox" id="on_photodesk" name="on_photodesk" value="1"{% if issel_on_photodesk_module %} checked="checked"{% endif %} /> <label for="on_photodesk">{{ STR_ADMIN_UTILISATEURS_DISPLAY_IMAGE_IN_PHOTODESK }}</label></td>
		</tr>
		{% endif %}
		<tr>
			<td>{{ STR_NEWSLETTER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="top"><input type="checkbox" name="newsletter" value="1" {% if issel_newsletter %} checked="checked"{% endif %} /> {{ STR_ADMIN_UTILISATEURS_NEWSLETTER_CHECKBOX }}</td>
		</tr>
		{% if order_history_for_user_disable %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_ACCESS_HISTORY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="top"><input type="checkbox" name="access_history" value="1" {% if issel_access_history %} checked="checked"{% endif %} /> {{ STR_ADMIN_UTILISATEURS_ACCESS_HISTORY_CHECKBOX }}</td>
		</tr>
		{% endif %}
		<tr>
			<td>{{ STR_ADMIN_UTILISATEURS_COMMERCIAL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="top"><input type="checkbox" name="commercial" value="1" {% if issel_commercial %} checked="checked"{% endif %} /> {{ STR_ADMIN_UTILISATEURS_COMMERCIAL_CHECKBOX }}</td>
		</tr>
		{% if is_devises_module_active and devises_options %}
		<tr>
			<td>{{ STR_DEVISE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="top">
				<select class="form-control" name="devise">
					<option value="">{{ STR_ALL }}...</option>
				{% for o in devises_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}
				</select>
			</td>
		</tr>
		{% endif %}
		{% if STR_ADMIN_SITE_COUNTRY %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_SITE_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}: </td>
			<td>
				<select class="form-control" name="site_country">
					<option value="">{{ STR_ALL }}...</option>
					{{ site_country_select_options }}
				</select>
			</td>
		</tr>
		{% endif %}
		{% if mode == "insere" %}
		<tr>
			<td class="top" colspan="2"><input type="checkbox" name="notify" value="1" /> {{ STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD }}</td>
		</tr>
		{% endif %}
		<tr>
			<td colspan="2">
				<table class="full_width">
					<tr>
						<td colspan="2"><h2>{{ STR_ADMIN_UTILISATEURS_CLIENT_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</h2></td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_CLIENT_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
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
						</td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_WHO }}</td>
						<td class="left">
							<select class="form-control" name="seg_who" id="seg_who">
								{{ seg_who }}
							</select>
						</td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_BUY }}</td>
						<td class="left">
							<select class="form-control" name="seg_buy" id="seg_buy">
								{{ seg_buy }}
							</select>
						</td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_WANTS }}</td>
						<td class="left">
							<select class="form-control" name="seg_want" id="seg_want">
								{{ seg_want }}
							</select>
						</td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_THINKS }}</td>
						<td class="left">
							<select class="form-control" name="seg_think" id="seg_think">
								{{ seg_think }}
							</select>
						</td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_BY }}</td>
						<td class="left">
							<select class="form-control" name="seg_followed" id="seg_followed">
								{{ seg_followed }}
							</select>
						</td>
					</tr>
					<tr>
						<td>{{ STR_ADMIN_UTILISATEURS_JOB }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>
							<select class="form-control" id="fonction" name="fonction">
								<option value="">{{ STR_CHOOSE }}...</option>
								{{ fonction_options }}
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">{{ STR_ADMIN_UTILISATEURS_SEGMENTATION_TOTAL }}{{ STR_BEFORE_TWO_POINTS }}: {{ client_note }}</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
		{{ hook_output }}
	<table class="main_table">
		<tr>
			<td colspan="2"><p class="center"><input class="btn btn-primary" type="submit" value="{{ titre_soumet|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>
</div>
<div class="tab-pane" id="tab2">
	{% if (is_id_utilisateur) %}
		<table class="main_table">
			<tr>
				<td class="entete" colspan="{{ columns }}"><img src="{{ mini_liste_commande_src|escape('html') }}" width="22" height="19" alt="" align="absmiddle">{{ STR_ADMIN_UTILISATEURS_ORDERS_LIST }}</td>
			</tr>
			<tr>
				<td style="font-weight:bold;" colspan="{{ columns }}"><a onclick="return(window.open(this.href)?false:true);" href="{{ administrer_url }}/commander.php?mode=ajout&id_utilisateur={{ id_utilisateur }}">{{ STR_ADMIN_UTILISATEURS_CREATE_ORDER_TO_THIS_USER }} #{{ id_utilisateur }}</a></span></td>
			</tr>
			{% if gift_check_link %}
			<tr>
				<td style="font-weight:bold;" colspan="{{ columns }}"><img src="{{ wwwroot_in_admin }}/images/mail.gif" />&nbsp;<a data-confirm="{{ STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK_CONFIRM|str_form_value }}" href="{{ gift_checks_href|escape('html') }}">{{ STR_ADMIN_UTILISATEURS_CREATE_GIFT_CHECK }}</a></td>
			</tr>
			{% endif %}
			{% if (results) %}
			<tr>
				<td class="menu">{{ STR_ADMIN_ACTION }}</td>
				<td class="menu center">{{ STR_ORDER_NAME }}</td>
				<td class="menu center">{{ STR_DATE }}</td>
				<td class="menu center">{{ STR_TOTAL }} {{ STR_TTC }}</td>
				{% if is_parrainage_module_active %}
				<td class="menu center">{{ STR_AVOIR }}</td>
				{% endif %}
				<td class="menu center">{{ STR_ADMIN_UTILISATEURS_PRODUCTS_ORDERED }}</td>
				<td class="menu center" colspan="2">{{ STR_PAYMENT }}</td>
				<td class="menu center">{{ STR_DELIVERY }}</td>
			</tr>
			{% for res in results %}
			{{ res.tr_rollover }}
				<td class="center">
					<a title="{{ STR_MODIFY|str_form_value }}" href="{{ res.modif_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" alt="{{ STR_MODIFY|str_form_value }}" /></a>
					<a title="{{ STR_PRINT|str_form_value }}" href="{{ res.print_href|escape('html') }}"><img src="{{ printer_src|escape('html') }}" alt="{{ STR_PRINT|str_form_value }}" /></a>
				</td>
				<td class="center">{{ res.id }}</td>
				<td class="center">{{ res.date }}</td>
				<td class="center">{{ res.prix }}</td>
				{% if is_parrainage_module_active %}
				<td class="center">{{ res.recuperer_avoir_commande }}</td>
				{% endif %}
				<td class="center">{{ res.ordered_products }}</td>
				<td class="center">{{ res.payment_name }}</td>
				<td class="center">{{ res.payment_status_name }}</td>
				<td class="center">{{ res.delivery_status_name }}</td>
			</tr>
			{% endfor %}
			<tr>
				<td class="center" colspan="{{ columns }}">
					<br />
					<form class="entryform form-inline" role="form" method="post" action="{{ action2|escape('html') }}">
						{{ form_token2 }}
						<input type="submit" name="print_all_bill" value="{{ STR_ADMIN_UTILISATEURS_PRINT_ALL_BILLS|str_form_value }}" class="btn btn-primary" />
						<input type="hidden" name="user_id" value="{{ user_id|str_form_value }}" />
					</form>
				</td>
			</tr>
			{% else %}
			<tr>
				<td colspan="9" class="center">
					<p><b>{{ STR_ADMIN_UTILISATEURS_NO_ORDER_FOUND }}</b></p>
				</td>
			</tr>
			{% endif %}
		</table>
	{% endif %}
</div>
	<div class="tab-pane" id="tab3">
		{% if (is_id_utilisateur) %}
		<table class="full_width">
			<tr>
				<td colspan="2">
					<table class="full_width">
						<tr>
							<td class="entete">{{ STR_ADMIN_ADDRESS_CLIENT }}</td>
						</tr>
						<tr>
							<td>
							{{ get_address_list }}
							</td>
						</tr>
					</table>
				</td>
			</tr>	
		</table>
		{% endif %}
	</div>
	<div class="tab-pane" id="tab4">
		{% if is_commerciale_module_active and is_id_utilisateur %}
		<table class="full_width">
			<tr><td class="entete">{{ STR_ADMIN_UTILISATEURS_ADD_CONTACT_DATE }}</td></tr>
			<tr><td><br />{{ form_contact_user }}</td></tr>
		</table>
		<br />
		{% if (phone_event) %}
		<table id="phone_event" class="full_width">
			<tr><td class="entete">{{ STR_ADMIN_UTILISATEURS_MANAGE_CALLS }}</td></tr>
			<tr><td><br />{{ phone_event }}</td></tr>
		</table>
		{% endif %}
		<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
			<input name="mode" type="hidden" value="event_comment" />
			<center>
				<table class="full_width" >
					<tr>
						<td class="entete">{{ STR_ADMIN_UTILISATEURS_ADD_EVENT_REGARDING }} {{ pseudo }}</td>
					</tr>
				</table>
				<table>
					<tr>
						<th><br />{{ STR_ADMIN_UTILISATEURS_EVENT_DESCRIPTION }}</th>
					</tr>
					<tr>
						<td class="center">
							<textarea class="form-control" name="form_event_comment" rows="5" cols="50" id="event_comment">{{ event_comment }}</textarea>
						</td>
					</tr>	
					<tr>
						<td class="center">
						&nbsp;
						</td>
					</tr>
					<tr>
						<td class="center">
							<input name="form_event_submit" type="submit" value="{{ STR_ADMIN_UTILISATEURS_SAVE_EVENT|str_form_value }}" class="btn btn-primary" /><br />
							<br />
						</td>
					</tr>
				</table>
			</center>
		</form>
		{% endif %}
		{% if (is_id_utilisateur) %}
			{% if (download_files) %}
			<table id="download_files" class="full_width">
				<tr><td>{{ download_files }}</td></tr>
			</table>
			<br />
			{% endif %}
			{% if user_alerts is defined %}
				{{ user_alerts }}
			<br />
			{% endif %}
			{% if affiche_liste_abus %}
			<table class="full_width" >
				<tr>
					<td>{{ affiche_liste_abus }}</td>
				</tr>
			</table>
			{% endif %}
			
			{% if actions_moderations_user %}
			<table class="full_width" >
				<tr>
					<td class="entete">{{ STR_ADMIN_UTILISATEURS_ACTIONS_ON_THIS_ACCOUNT }}</td>
				</tr>
				<tr>
					<td>{{ actions_moderations_user }}</td>
				</tr>
			</table>
			{% endif %}
			{% if is_webmail_module_active %}
			<table class="full_width">
				<tr><td>&nbsp;</td></tr>
				<tr><td>{{ more_infos_html }}</td></tr>
			</table>
			<br />
			{% endif %}
		{% endif %}
	</div>
	<div class="tab-pane" id="tab5">
			{% if affiche_recherche_connexion_user is defined %}
			<table class="full_width">
				<tr>
					<td>{{ affiche_recherche_connexion_user }}</td>
				</tr>
			</table>
			{% endif %}
	</div>
	<div class="tab-pane" id="tab6">
		{% if is_abonnement_module_active and is_id_utilisateur %}
		<table class="full_width">
			<tr><td class="entete">{{ STR_MODULE_ABONNEMENT_ADMIN_MANAGE_SUBSCRIPTIONS }}</td></tr>
			<tr><td>{{ abonnement_admin }}</td></tr>
		</table>
		<br />
		{% endif %}
		{% if (add_credit_gold_user) and is_id_utilisateur %}
		<table class="full_width">
			<tr><td>{{ add_credit_gold_user }}</td></tr>
		</table>
		<br />
		{% endif %}

		{% if (liste_annonces_admin) and (is_id_utilisateur) %}
		<table class="full_width">
			<tr><td>{{ liste_annonces_admin }}</td></tr>
		</table>
		<br />
		{% endif %}
		{% if is_vitrine_module_active and is_id_utilisateur %}
		<tr><td colspan="2"><br />{{ vitrine_admin }}</td></tr>
		{% endif %}
	</div>
</div>







