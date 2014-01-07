{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_marque.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}<table class="admin_liste_marque">
	<tr><td colspan="6" class="entete">{{ STR_ADMIN_MARQUES_TITLE }}</td></tr>
	<tr><td colspan="6"><p><img src="{{ add_src }}" width="16" height="16" alt="" class="middle" /><a href="{{ href|escape('html') }}">{{ STR_ADMIN_MARQUES_ADD_BRAND }}</a></p></td></tr>
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_ADMIN_ID }}</td>
		<td class="menu">{{ STR_ADMIN_IMAGE }}</td>
		<td class="menu">{{ STR_BRAND }}</td>
		<td class="menu">{{ STR_ADMIN_POSITION }}</td>
		<td class="menu">{{ STR_STATUS }}</td>
	</tr>
	{% if (results) %}
	{% for res in results %}
	{{ res.tr_rollover }}
		<td class="center"><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a>
			<a title="{{ STR_ADMIN_MARQUES_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
		<td class="center">{{ res.id }}</td>
		<td class="center">{% if (res.img_src) %}<img src="{{ res.img_src|escape('html') }}" alt="" />{% endif %}</td>
		<td class="center"><a title="{{ STR_ADMIN_MARQUES_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.nom|html_entity_decode_if_needed }}</a></td>
		<td class="center position">{{ res.position }}</td>
		<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
	</tr>
	{% endfor %}
	{% else %}
		<tr><td><b>{{ STR_ADMIN_MARQUES_NOTHING_FOUND }}</b></td></tr>
	{% endif %}
	<tr><td class="center" colspan="4">{{ links_multipage }}</td></tr>
</table>