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
// $Id: admin_liste_meta.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_META_PAGE_TITLE }}</div>
<div class="btn btn-default" style="margin-top:10px; margin-bottom: 10px"><span id="search_icon" class="glyphicon glyphicon-plus"></span> <a href="{{ administrer_url }}/meta.php?mode=ajout">{{ STR_ADMIN_ADD }}</a></div>
<table class="full_width">
	<tr>
		<td class="entete">{{ STR_ADMIN_META_PAGE_TITLE }}</td>
	</tr>
{% if (results) %}
	{% for res in results %}
	<tr>
		<td>
			<a href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" width="16" height="16" alt="" /></a> <a title="{{ STR_ADMIN_META_UPDATE|str_form_value }}" href="{{ res.href|escape('html') }}">{{ res.technical_code }} - {{ res.anchor }}</a>
		</td>
	</tr>
	{% endfor %}
{% else %}
	<tr><td><b>{{ STR_ADMIN_META_EMPTY_EXPLAIN }}</b></td></tr>
{% endif %}
</table>