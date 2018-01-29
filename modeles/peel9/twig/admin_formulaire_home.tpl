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
// $Id: admin_formulaire_home.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_HTML_FORM_TITLE }}</td>
		</tr>

		<tr>
			<td>{{ STR_ADMIN_LANGUAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% for l in langs %}
				<input type="radio" name="lang" id="lang_{{ l.lng|str_form_value }}" value="{{ l.lng|str_form_value }}"{% if l.issel %} checked="checked"{% endif %} /> {{ l.name }}<br />
			{% endfor %}
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
 		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:<br /></td>
			<td>
				<select class="form-control" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	{% if (STR_ADMIN_SITE_COUNTRY) %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_SITE_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				{{ site_country_checkboxes }}
			</td>
		</tr>
	{% endif %}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="etat" value="1" id="etat_1"{% if etat == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_ONLINE }}<br />
				<input type="radio" name="etat" value="0" id="etat_0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /> {{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_HTML_PLACE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				{% for e_code,e_name in emplacement_array %}
					<input type="radio" name="emplacement" value="{{ e_code }}"{% if emplacement == e_code %} checked="checked"{% endif %} /> {{ e_name }}<br />
				{% endfor %}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{{ STR_ADMIN_TITLE_NOT_DISPLAYED }}{{ STR_BEFORE_TWO_POINTS }}:<br /></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="text" class="form-control" name="titre" value="{{ titre|html_entity_decode_if_needed|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{{ STR_ADMIN_HTML_TEXT }}{{ STR_BEFORE_TWO_POINTS }}:<div class="alert alert-info">{{ STR_ADMIN_HTML_PHOTOS_WARNING }}</div></td>
		</tr>
		<tr>
			<td colspan="2">{{ contenu_html_te }}</td>
		</tr>
		<tr>
			<td colspan="2" width="760" align="center"><input class="btn btn-primary" type="submit" value="{{ STR_VALIDATE|str_form_value }}" /></td>
		</tr>
	</table>
</form>