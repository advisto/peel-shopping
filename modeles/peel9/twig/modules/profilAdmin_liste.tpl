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
// $Id: profilAdmin_liste.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<table class="main_table">
	<tr>
		<td class="entete" colspan="4">{{ STR_MODULE_PROFIL_ADMIN_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="4">
			<table>
				<tr>
					<td><br /><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" /></td>
					<td><a href="{{ add_href|escape('html') }}">{{ STR_MODULE_PROFIL_ADMIN_CREATE }}</a></td>
				</tr>
			</table>
			<p class="alert alert-info">{{ STR_MODULE_PROFIL_ADMIN_LIST_EXPLAIN }}</p>
		</td>
	</tr>
	{% if (results) %}
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_ADMIN_PROFIL }}</td>
		<td class="menu">{{ STR_MODULE_PROFIL_ADMIN_ABBREVIATE }}</td>
		<td class="menu">{{ STR_ADMIN_WEBSITE }}</td>
	</tr>
	{% for res in results %}
	{{ res.tr_rollover }}
		<td class="center"><a title="{{ STR_MODULE_PROFIL_ADMIN_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
		<td class="center"><a title="{{ STR_MODULE_PROFIL_ADMIN_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.name }}</a></td>
		<td class="center">{{ res.priv }}</td>
		<td class="center">{{ res.site_name }}</td>
	</tr>
	{% endfor %}
	{% else %}
	<tr><td colspan="4"><div class="alert alert-warning">{{ STR_MODULE_PROFIL_ADMIN_NOTHING_FOUND }}</div></td></tr>
	{% endif %}
</table>