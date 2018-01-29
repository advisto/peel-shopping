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
// $Id: admin_formulaire_statut.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_STATUT_FORM_TITLE }}</td>
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
			<td class="title_label">{{ STR_ADMIN_NAME }} {{ l.lng|upper }}:</td>
			<td><input type="text" class="form-control" name="nom_{{ l.lng }}" value="{{ l.nom|str_form_value }}" /></td>
   	 	</tr>
		{% endfor %}
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</h2></td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_TECHNICAL_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="new_id" value="{{ new_id|str_form_value }}" /></td>
   	 	</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="number" class="form-control" name="position" value="{{ position|str_form_value }}" /></td>
   	 	</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>