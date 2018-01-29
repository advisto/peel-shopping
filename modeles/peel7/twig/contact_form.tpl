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
// $Id: contact_form.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% if skip_introduction_text is empty %}<h1 class="page_title">{% if meta_title is empty %}{{ STR_CONTACT }}{% else %}{{ meta_title }}{% endif %}</h1>{% endif %}
{% if token_error is defined %}{{ token_error }}{% endif %}
<div id="contact">
{% if product_info_id is empty and contact_page_map_display is empty %}
	<div id="contact_info">{{ contact_info }}</div>
{% endif %}
	<div id="contact_form">{% if success_msg is defined and success_msg %}<div class="alert alert-success">{{ success_msg|nl2br_if_needed }}</div>{% endif %}
		<div class="contact_intro">{% if meta_description is empty %}{{ STR_CONTACT_INTRO }}{% else %}{{ meta_description }}{% endif %}</div>
			<form class="entryform form-inline well" role="form" method="post" action="{{ action|escape('html') }}#contact_form" name="form_contact" id="form_contact" enctype="multipart/form-data">
			<input type="hidden" id="product_info_id" name="product_info_id" value="{{ product_info_id|str_form_value }}" />
			{{ extra_field }}
			<table style="width:75%">
{% if site_configured_array %}
				<tr>
					<td><label for="sujet">{{ STR_WEBSITE }} <span class="etoile{% if short_form %} no-display{% endif %}">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td>
						<select class="form-control" id="site_id" name="site_id">
							{% for this_options in site_configured_array %}
								<option value="{{ this_options|str_form_value }}">{{ this_options }}</option>
							{% endfor %}
						</select>
					</td>
				</tr>
{% endif %}
{% if STR_CONTACT_SUBJECT %}
				<tr>
					<td {% if short_form %} colspan="2"{% endif %}><label for="sujet">{{ STR_CONTACT_SUBJECT }} <span class="etoile{% if short_form %} hidden{% endif %}">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label>
					</td>
					<td>
						<select class="form-control" id="sujet" name="sujet" style="">
							{% for key in sujet_options|keys %}
								<option value="{{ key|str_form_value }}"{% if key==sujet_options_selected %} selected="selected"{% endif %}>{{ sujet_options[key] }}</option>
							{% endfor %}
						</select>
						{{ sujet_error }}
					</td>
				</tr>
{% elseif mail_title %}
				<tr {% if hidden_sujet %} class="hidden" {% endif %}>
					<td><label for="sujet"></td>
					<td colspan="2">
						<select class="form-control" id="sujet" name="sujet" style="">
							<option value="{{  mail_title|str_form_value }}" selected="selected">{{ mail_title }}</option>
						</select>
					</td>
				</tr>
{% endif %}
		{% if STR_REQUIRED_ORDER_NUMBER %}
				<tr{% if short_form %} class="hidden"{% endif %}>
					<td><label for="commande_id">{{ STR_ORDER_NUMBER }} {{ STR_BEFORE_TWO_POINTS }}:<br /><i>({{ STR_REQUIRED_ORDER_NUMBER }})</i></label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="commande_id" name="commande_id" value="{{ commande_id|str_form_value }}" />{{ commande_error }}
					</td>
				</tr>
		{% endif %}
				<tr {% if hidden_texte %} class="hidden" {% endif %}>
					<td><label for="texte">{{ STR_TEXT }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td><textarea class="form-control" id="texte" name="texte" rows="10">{{ texte_value }}</textarea>{{ texte_error }}</td>
				</tr>
				<tr {% if hidden_societe %} class="hidden" {% endif %}>
					<td><label for="societe">{{ STR_SOCIETE }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						{{ societe_error }}<input type="text" class="form-control" id="societe" name="societe" value="{{ societe_value|str_form_value }}" />
					</td>
				</tr>
				<tr {% if hidden_nom %} class="hidden" {% endif %}>
					<td><label for="nom">{{ STR_NAME }} / {{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="nom" name="nom" value="{{ name_value|str_form_value }}" />{{ name_error }}
						{% if short_form or hidden_prenom %} <input type="hidden" id="prenom" name="prenom" value="{{ first_name_value|str_form_value }}" />{% endif %}
					</td>
				</tr>
				<tr {% if short_form or hidden_prenom %} class="hidden"{% endif %}>
					<td><label for="prenom">{{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="prenom" name="prenom" value="{{ first_name_value|str_form_value }}" />{{ first_name_error }}
					</td>
				</tr>
				<tr>
					<td><label for="email">{{ STR_EMAIL }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="email" class="form-control" id="email" name="email" value="{{ email_value|str_form_value }}" autocapitalize="none" />{{ email_error }}
						<input type="hidden" id="adresse" name="adresse" value="{{ address_value|str_form_value }}" />
						<input type="hidden" id="code_postal" name="code_postal" value="{{ zip_value|str_form_value }}" />
						<input type="hidden" id="dispo" name="dispo" value="" />
						<input type="hidden" id="ville" name="ville" value="{{ town_value|str_form_value }}" />
						<input type="hidden" id="pays" name="pays" value="{{ country_value|str_form_value }}" />
					</td>
				</tr>
				<tr {% if hidden_adresse %} class="hidden"{% endif %}>
					<td><label for="telephone">{{ STR_TELEPHONE }} <span class="etoile{% if short_form %} hidden{% endif %}">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="tel" class="form-control" id="telephone" name="telephone" value="{{ telephone_value|str_form_value }}" />{{ telephone_error }}
					</td>
				</tr>
				<tr{% if short_form or hidden_code_postal %} class="hidden"{% endif %}>
					<td><label for="code_postal">{{ STR_ZIP }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="code_postal" name="code_postal" value="{{ zip_value|str_form_value }}" />
					</td>
				</tr>
				<tr{% if short_form or hidden_ville %} class="hidden"{% endif %}>
					<td><label for="ville">{{ STR_TOWN }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="ville" name="ville" value="{{ town_value|str_form_value }}" />
					</td>
				</tr>
				<tr {% if short_form or hidden_pays %} class="hidden"{% endif %}>
					<td><label for="pays">{{ STR_COUNTRY }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="pays" name="pays" value="{{ country_value|str_form_value }}" />
					</td>
				</tr>
				<tr {% if hidden_telephone %} class="hidden"{% endif %}>
					<td><label for="telephone">{{ STR_TELEPHONE }} <span class="etoile{% if short_form %} hidden{% endif %}">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="tel" class="form-control" id="telephone" name="telephone" value="{{ telephone_value|str_form_value }}" />{{ telephone_error }}
					</td>
				</tr>
				{% if user_contact_file_upload %}
				<tr>
					<td class="title_label">{{ STR_FILE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					<td>{{ this_upload_html }}</td>
				</tr>
				{% endif %}
				{% if short_form %}
				<tr>
					<td colspan="2" style="height:14px;"></td>
				</tr>
				{% endif %}
				{% if captcha is defined %}
				<tr>
					<td class="left">{{ captcha.validation_code_txt }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					<td>{{ captcha.inside_form }}</td>
				</tr>
				<tr>
					<td class="left">{{ captcha.validation_code_copy_txt }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</td>
					<td><input name="code" type="text" class="form-control" size="5" maxlength="5" id="code" value="{{ captcha.value|str_form_value }}" />{{ captcha.error }}</td>
				</tr>
				{% endif %}
			</table>

			<div style="text-align:center; margin-top: 10px;">
				{{ token }}
				<div style="text-align:center; margin-top: 10px; margin-bottom: 10px;"><input type="submit" class="btn btn-primary btn-lg" value="{{ STR_SEND|str_form_value }}" />{% if ssl_image_src %}<img alt="SSL" src="{{ ssl_image_src }}" class="image_ssl right" />{% endif %}</div>
			</div>
			<p{% if short_form %} class="hidden"{% endif %}>{{ cnil_txt|nl2br_if_needed }}</p>
		</form>
		<p{% if short_form %} class="hidden"{% endif %}><span class="form_mandatory">(*) {{ STR_MANDATORY }}</span></p>
{% if product_info_id %}
		<div id="contact_info">{{ contact_info }}</div>
{% endif %}
	</div>
{% if skip_introduction_text is empty %}</div>{% endif %}