{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_plan_formulaire_contact.tpl 39162 2013-12-04 10:37:44Z gboussin $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete">{{ STR_ADMIN_PLAN_UPDATE }}</td>
		</tr>
		
		{% for l in langs %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} {{ l.lng|upper }}</td></tr>

		<tr>
			<td class="title_label" colspan="2">{{ STR_ADMIN_HEADER_HTML_TEXT }} {{ l.lng }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">{{ l.text_te }}</td>
		</tr>
		{% endfor %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</td></tr>
		<tr>
			<td colspan="2">{{ STR_ADMIN_PLAN_TAG_EXPLAIN }}</td>
		</tr>
		<tr>
			<td colspan="2"><b>{{ STR_ADMIN_PLAN_TAG_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</b>
				{{ error }}
			</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" style="width:76%; height:140px;" name="map_tag">{{ map_tag }}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="center"><br /><input class="btn btn-primary" type="submit" value="{{ normal_bouton|str_form_value }}" /></td>
		</tr>
	</table>
</form>