{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_configuration.tpl 39392 2013-12-20 11:08:42Z gboussin $
#}<table class="full_width">
	<tr>
		<td class="entete" colspan="8">{{ STR_ADMIN_CONFIGURATION_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="8">
			<p><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" class="middle" /><a href="{{ add_href|escape('html') }}">{{ STR_ADMIN_CONFIGURATION_CREATE }}</a></p>
			<b>{{ STR_NOTA_BENE }}{{ STR_BEFORE_TWO_POINTS }}:</b> {{ STR_ADMIN_CONFIGURATION_EXPLAIN }}
		</td>
	</tr>
	{% if (results) %}
		<tr>
			<td class="menu">{{ STR_ADMIN_ACTION }}</td>
			<td class="menu">{{ STR_ADMIN_LANGUAGE }}</td>
			<td class="menu">{{ STR_TYPE }}</td>
			<td class="menu">{{ STR_ADMIN_TECHNICAL_CODE }}</td>
			<td class="menu">{{ STR_VALUE }}</td>
			<td class="menu">{{ STR_DATE }}</td>
			<td class="menu">{{ STR_ADMIN_CONFIGURATION_ORIGIN }}</td>
			<td class="menu">{{ STR_STATUS }}</td>
		</tr>
		{% for res in results %}
		{{ res.tr_rollover }}
		<td class="center" width="50">
			<a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.technical_code }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }} {{ res.technical_code }}" /></a>
			<a title="{{ STR_ADMIN_CONFIGURATION_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" alt="edit" /></a>
		</td>
		<td class="center">{{ res.lang|html_entity_decode_if_needed }}</td>
		<td class="center">{{ res.type|html_entity_decode_if_needed }}</td>
		<td class="center">{{ res.technical_code|html_entity_decode_if_needed }}</td>
		<td class="center">{{ res.string|html_entity_decode_if_needed }}</td>
		<td class="center" width="150">{{ res.date }}</td>
		<td class="center">{{ res.origin|html_entity_decode_if_needed }}</td>
		<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
		</tr>
		{% endfor %}
	{% else %}
		<tr><td><b>-</b></td></tr>
	{% endif %}
</table>