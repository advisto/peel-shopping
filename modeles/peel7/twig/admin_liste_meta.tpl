{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_meta.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<table class="full_width">
	<tr>
		<td class="entete">{{ STR_ADMIN_META_PAGE_TITLE }}</td>
	</tr>
	{% if (results) %}
	{% for res in results %}
	<tr>
		<td>
			<a title="{{ STR_ADMIN_META_UPDATE|str_form_value }}" href="{{ res.href|escape('html') }}">{{ res.technical_code }} - {{ res.anchor }}</a>
		</td>
	</tr>
	{% endfor %}
	{% else %}
	<tr><td><b>{{ STR_ADMIN_META_EMPTY_EXPLAIN }}</b></td></tr>
	{% endif %}
</table>