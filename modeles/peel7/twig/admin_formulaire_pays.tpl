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
// $Id: admin_formulaire_pays.tpl 55930 2018-01-29 08:36:36Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_PAYS_ADD_COUNTRY }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}: </td>
			<td>
				<select class="form-control" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		{% for l in langs %}
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} - {{ lang_names[l.lng]|upper }}</h2></td></tr>
		<tr>
			<td class="title_label">{{ STR_COUNTRY }} {{ l.lng|upper }}:</td>
			<td><input type="text" class="form-control" name="pays_{{ l.lng }}" value="{{ l.pays|str_form_value }}" /></td>
		</tr>
		{% endfor %}
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_PAYS_ISO_CODES_HEADER }}</h2></td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PAYS_ISO_2 }}</td>
			<td><input style="width:100px" type="text" class="form-control" name="iso" value="{{ iso|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PAYS_ISO_3 }}</td>
			<td><input style="width:100px" type="text" class="form-control" name="iso3" value="{{ iso3|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PAYS_ISO_NUMERIC }}</td>
			<td><input style="width:100px" type="text" class="form-control" name="iso_num" value="{{ iso_num|str_form_value }}" /></td>
		</tr>
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</h2></td></tr>
		<tr>
			<td class="title_label">{{ STR_STATUS }}</td>
			<td>
			 	<input type="radio" name="etat" value="1"{% if etat == '1' %} checked="checked"{% endif %} /> {{ STR_YES }}&nbsp;
				<input type="radio" name="etat" value="0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_SHIPPING_ZONE }}</td>
			<td>
				<select class="form-control" name="zone">
				{% for o in options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_POSITION }}</td>
			<td><input type="number" class="form-control" name="position" style="width:100px" value="{{ position|str_form_value }}" /></td>
		</tr>
		<tr><td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td></tr>
	</table>
</form>