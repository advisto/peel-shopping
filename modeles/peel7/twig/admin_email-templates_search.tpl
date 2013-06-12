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
// $Id: admin_email-templates_search.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<br />
<form action="" method="get" name="form_search">
	<table class="full_width">
		<tr>
			<td class="entete" colspan="4">{{ STR_ADMIN_CHOOSE_SEARCH_CRITERIA }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_LANGUAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ STR_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="hidden" name="mode" value="search" /></td>
		</tr>
		<tr>
			<td>
				<select name="form_lang_template" id="form_lang_template">
					<option value="">{{ STR_CHOOSE }}...</option>
					{% for l in langs %}
					<option value="{{ l.value|str_form_value }}"{% if l.issel %} selected="selected"{% endif %}>{{ l.name }}</option>
					{% endfor %}
				</select>
			</td>
			<td>
				<select name="form_id_cat">
					<option value="">{{ STR_CHOOSE }}...</option>
					{% for o in options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
					{% endfor %}
				</select>
			</td>
			<td>
				<select name="etat" id="etat">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="1" {% if etat == "1" %} selected="selected"{% endif %}>{{ STR_ADMIN_ACTIVATED }}</option>
					<option value="0" {% if etat == "0" %} selected="selected"{% endif %}>{{ STR_ADMIN_DEACTIVATED }}</option>
				</select>
			</td>
			<td>
				<input type="submit" value="{{ STR_SEARCH|str_form_value }}" class="bouton" />
			</td>
		</tr>
	</table>
</form>