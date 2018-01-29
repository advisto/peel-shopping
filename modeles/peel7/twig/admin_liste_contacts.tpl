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
// $Id: admin_liste_contacts.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_CONTACTS_TITLE }}</div>
<table> 
	<tr>
		<td><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" /></td>
		<td><a href="{{ add_href|escape('html') }}">{{ STR_ADMIN_CONTACTS_ADD }}</a></td>
	</tr>
</table>
<div class="table-responsive">
	<table class="table">
	{% if results is defined %}
		<thead>
			<tr>
				<td class="menu">{{ STR_ADMIN_ACTION }}</td>
				<td class="menu">{{ STR_ADMIN_WEBSITE }}</td>
			</tr>
		</thead>
		<tbody class="sortable">
		{% for res in results %}
			{{ res.tr_rollover }}
				<td class="center"><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a> &nbsp; <a title="{{ STR_ADMIN_CONTACTS_UPDATE }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
				<td class="center">{{ res.site_name }}</td>
			</tr>
		{% endfor %}
		</tbody>
	{% else %}
		<tbody class="sortable">
			<tr><td colspan="6"><div class="alert alert-warning">{{ STR_ADMIN_CONTACTS_NO_FOUND }}</div></td></tr>
		</tbody>
	{% endif %}
	</table>
</div>