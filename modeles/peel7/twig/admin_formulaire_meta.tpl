{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_meta.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width" style="padding:6px;">
		<tr>
			<td class="entete">{{ STR_ADMIN_META_PAGE_TITLE }}</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="bloc">{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_TECHNICAL_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="technical_code" size="70" value="{{ technical_code|str_form_value }}" /></td>
		</tr>
 		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td>
				<select class="form-control" name="site_id">
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		{% for l in langs %}
		<tr>
			<td class="bloc">{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} {{ l.lng|upper }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_META_TITLE }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td style="padding:6px;"><input type="text" class="form-control" name="meta_titre_{{ l.lng }}" size="70" value="{{ l.meta_titre|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_META_KEYWORDS }} {{ l.lng|upper }} ({{ STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN }}){{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td><textarea class="form-control" name="meta_key_{{ l.lng }}" style="width:100%" rows="5" cols="54">{{ l.meta_key|nl2br_if_needed|strip_tags }}</textarea></td>
		</tr>
		<tr >
			<td class="title_label">{{ STR_ADMIN_META_DESCRIPTION }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td><textarea class="form-control" name="meta_desc_{{ l.lng }}" style="width:100%" rows="10" cols="54">{{ l.meta_desc|nl2br_if_needed|strip_tags }}</textarea></td>
		</tr>
		{% endfor %}
		<tr>
			<td class="center"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>