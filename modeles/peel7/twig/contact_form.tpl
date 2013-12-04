{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: contact_form.tpl 39095 2013-12-01 20:24:10Z gboussin $
#}<h1 class="page_title">{{ STR_CONTACT }}</h1>
{% if (token_error) %}{{ token_error }}{% endif %}
<div id="contact">
	<div id="contact_info">{{ contact_info }}</div>
	<div id="contact_form">{% if (success_msg) and (success_msg) %}<div class="alert alert-success">{{ success_msg|nl2br_if_needed }}</div>{% endif %}
		<div class="contact_intro">{{ STR_CONTACT_INTRO }}</div>
		<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" name="form_contact" id="form_contact">
			{{ extra_field }}
			<table class="contact_form_table">
				<tr>
					<td {% if short_form %} colspan="2"{% endif %}><label for="sujet">{{ STR_CONTACT_SUBJECT }} <span class="etoile{% if short_form %} no-display{% endif %}">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label>
		{% if short_form %}
						<br />
		{% else %}
					</td>
					<td>
		{% endif %}
					<select class="form-control" id="sujet" name="sujet" style="">
					{% for key in sujet_options|keys %}
						<option value="{{ key|str_form_value }}"{% if key==sujet_options_selected %} selected="selected"{% endif %}>{{ sujet_options[key] }}</option>
					{% endfor %}
					</select>
					{{ sujet_error }}
					</td>
				</tr>
				<tr{% if short_form %} class="no-display"{% endif %}>
					<td><label for="commande_id">{{ STR_ORDER_NUMBER }} {{ STR_BEFORE_TWO_POINTS }}:<br /><i>({{ STR_REQUIRED_ORDER_NUMBER }})</i></label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="commande_id" name="commande_id" value="{{ commande_id|str_form_value }}" />{{ commande_error }}
					</td>
				</tr>
				<tr>
					<td><label for="societe">{{ STR_SOCIETE }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						{{ societe_error }}<input type="text" class="form-control" id="societe" name="societe" value="{{ societe_value|str_form_value }}" />
					</td>
				</tr>
				<tr>
					<td><label for="nom">{{ STR_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="nom" name="nom" value="{{ name_value|str_form_value }}" />{{ name_error }}
					</td>
				</tr>
				<tr{% if short_form %} class="no-display"{% endif %}>
					<td><label for="prenom">{{ STR_FIRST_NAME }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="prenom" name="prenom" value="{{ first_name_value|str_form_value }}" />{{ first_name_error }}
					</td>
				</tr>
				<tr>
					<td><label for="email">{{ STR_EMAIL }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="email" class="form-control" id="email" name="email" value="{{ email_value|str_form_value }}" />{{ email_error }}
					</td>
				</tr>
				<tr>
					<td><label for="adresse">{{ STR_ADDRESS }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<textarea class="textarea-contact form-control" rows="3" cols="54" id="adresse" name="adresse">{{ address_value }}</textarea>
					</td>
				</tr>
				<tr{% if short_form %} class="no-display"{% endif %}>
					<td><label for="code_postal">{{ STR_ZIP }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="code_postal" name="code_postal" value="{{ zip_value|str_form_value }}" />
					</td>
				</tr>
				<tr{% if short_form %} class="no-display"{% endif %}>
					<td><label for="ville">{{ STR_TOWN }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="ville" name="ville" value="{{ town_value|str_form_value }}" />
					</td>
				</tr>
				<tr{% if short_form %} class="no-display"{% endif %}>
					<td><label for="pays">{{ STR_COUNTRY }} {{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="text" class="form-control" id="pays" name="pays" value="{{ country_value|str_form_value }}" />
					</td>
				</tr>
				<tr>
					<td><label for="telephone">{{ STR_TELEPHONE }} <span class="etoile{% if short_form %} no-display{% endif %}">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
						<input type="tel" class="form-control" id="telephone" name="telephone" value="{{ telephone_value|str_form_value }}" />{{ telephone_error }}
					</td>
				</tr>
				{% if short_form %}
				<tr>
					<td colspan="2" style="height:14px;"></td>
				</tr>
				{% endif %}
				<tr>
					<td><label for="texte">{{ STR_TEXT }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label>
					<td><textarea class="form-control" id="texte" name="texte" rows="10">{{ texte_value }}</textarea>{{ texte_error }}</td>
				</tr>
				<tr>
					<td><label for="dispo">{{ STR_DISPO }}{{ STR_BEFORE_TWO_POINTS }}:</label></td>
					<td class="{{ align }}">
					   <select class="form-control" id="dispo" name="dispo">
						   <option value="A.M">{{ STR_DAY_AM }}</option>
						   <option value="P.M">{{ STR_DAY_PM }}</option>
						</select>
					</td>
				</tr>
				{% if (captcha) %}
				<tr>
					<td class="left">{{ captcha.validation_code_txt }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					<td>{{ captcha.inside_form }}</td>
				</tr>
				<tr>
					<td class="left">{{ captcha.validation_code_copy_txt }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</td>
					<td>{{ captcha.error }}<input name="code" type="text" class="form-control" size="5" maxlength="5" id="code" value="{{ captcha.value|str_form_value }}" /></td>
				</tr>
				{% endif %}
			</table>

			<div style="text-align:center; margin-top: 10px;">
				{{ token }}
			{% if short_form %}
				<a href="{{ href|escape('html') }}#" class="a_submit" onclick="document.form_contact.submit();return false;" ></a>
			{% else %}
				<input type="submit" class="btn btn-primary" value="{{ STR_SEND|str_form_value }}" />
			{% endif %}
			</div>
			<p{% if short_form %} class="no-display"{% endif %}>{{ cnil_txt|nl2br_if_needed }}</p>
			<p{% if short_form %} class="no-display"{% endif %}><span class="form_mandatory">(*) {{ STR_MANDATORY }}</span></p>
		</form>
	</div>
</div>