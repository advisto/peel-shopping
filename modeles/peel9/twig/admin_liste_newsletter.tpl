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
// $Id: admin_liste_newsletter.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<table class="full_width">
	<tr>
		<td class="entete" colspan="10">{{ STR_ADMIN_NEWSLETTERS_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="10">{% if is_crons_module_active %}<p class="alert alert-success">{{ STR_ADMIN_NEWSLETTERS_CRON_ACTIVATED_EXPLAIN }}</p>
		{% else %}<p class="alert alert-danger">{{ STR_ADMIN_NEWSLETTERS_CRON_DEACTIVATED_EXPLAIN }}</p>{% endif %}</td>
	</tr>
	<tr>
		<td colspan="10"><p><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" class="middle" /><a href="{{ add_href|escape('html') }}">{{ STR_ADMIN_NEWSLETTERS_CREATE }}</a></p></td>
	</tr>
{% if (results) %}
	{{ links_header_row }}
	{% for res in results %}
	{{ res.tr_rollover }}
		<td><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.sujet }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a></td>
		<td><a title="{{ STR_ADMIN_NEWSLETTERS_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.sujet }}</a></td>
		<td class="center">{{ res.date }}</td>
		<td class="center">{{ res.subscribers_number }}</td>
		<td class="center">{{ res.format }}</td>
		<td class="center">{{ res.statut }}</td>
		<td class="center">{{ res.date_envoi }}</td>
		<td class="center"><a href="{{ res.mail_href|escape('html') }}" data-confirm="{{ STR_ADMIN_NEWSLETTERS_SEND_CONFIRM|str_form_value }}"><img alt="{{ STR_ADMIN_NEWSLETTERS_SEND_ALL_USERS|str_form_value }}" src="{{ mail_src|escape('html') }}" /></a></td>
		<td class="center"><a href="{{ res.test_href|escape('html') }}">{{ STR_ADMIN_NEWSLETTERS_TEST_SENDING_TO_ADMINISTRATORS }}</a></td>
		<td class="center">{{ res.site_name }}</td>
	</tr>
	{% endfor %}
{% else %}
		<tr><td colspan="10"><b>{{ STR_ADMIN_NEWSLETTERS_NOTHING_FOUND }}</b></td></tr>
{% endif %}
</table>