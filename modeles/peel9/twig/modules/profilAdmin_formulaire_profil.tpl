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
// $Id: profilAdmin_formulaire_profil.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_PROFIL_ADMIN_TITLE }}</td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_MODULE_PROFIL_ADMIN_EXPLAIN }}</td>
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
			<td>{{ STR_ADMIN_NAME }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td style="width:540px"><input type="text" class="form-control" name="name_{{ l.lng }}" style="width:100%" value="{{ l.name|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_DESCRIPTION }} {{ l.lng|upper }} ({{ STR_MODULE_PROFIL_ADMIN_DESCRIPTION_EXPLAIN }} {{ l.name }})</td>
		</tr>
		<tr>
			<td class="left" colspan="2">
				<textarea class="form-control" id="description_document_{{ l.lng }}" name="description_document_{{ l.lng }}">{{ l.description_document }}</textarea>
			</td>
		</tr>
	{% if l.document %}
		<tr>
			<td class="title_label">{{ STR_FILE }} {{ l.lng|upper }} {{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
		{% if l.document %}
			{% include "uploaded_file.tpl" with {'f':l.documen,'STR_DELETE':STR_DELETE_THIS_FILE } %}
		{% else %}
			<input name="document_{{ l.lng }}" type="file" value="" />
		{% endif %}
			</td>
		</tr>
	{% endif %}
{% endfor %}
		<tr>
			<td>{{ STR_MODULE_PROFIL_ADMIN_ABBREVIATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			{% if mode == "insere" %}
			<td><input type="text" class="form-control" name="priv" value="{{ priv|str_form_value }}" /></td>
			{% else %}
			<td>{{ priv }}<input type="hidden" name="priv" value="{{ priv|str_form_value }}" /></td>
			{% endif %}
		</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>