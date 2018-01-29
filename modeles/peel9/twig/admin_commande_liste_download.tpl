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
// $Id: admin_commande_liste_download.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<table class="full_width">
	<tr>
		<td class="entete" colspan="11">{{ STR_ADMIN_COMMANDER_DOWNLOADS_LIST_TITLE }}</td>
	</tr>
	<tr>
		<td class="title_label" colspan="11">
		{% if is_error %}
			<p class="alert alert-danger"><b>{{ STR_ADMIN_COMMANDER_WARNING_ALREADY_DOWNLOADED }} <a href="commander.php?mode=efface_download" class="alert-link">{{ STR_ADMIN_COMMANDER_ALREADY_DOWNLOADED_DELETE_LINK_TEXT }}</a></p>
		{% endif %}
		</td>
	</tr>
	{% if (results) %}
	{{ links_header_row }}
	{% for res in results %}
	{{ res.tr_rollover }}
		<td class="center">
			<a title="{{ STR_MODIFY|str_form_value }}" href="{{ res.modif_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" alt="{{ STR_MODIFY|str_form_value }}" /></a>
			{% if res.allow_delete_order %}<a data-confirm="{{ res.delete_confirm_txt|str_form_value }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" />{% endif %}
		</td>
		<td class="center">{{ res.id }}</td>
		<td class="center">{{ res.nom_produit }}</td>
		<td class="center"><a href="mailto:{{ res.email }}">{{ res.email }}</a></td>
		<td class="center">{{ res.date }}</td>
		<td class="center">{{ res.payment_name }}</td>
		<td class="center">{{ res.payment_status_name }}</td>
		<td class="center">{{ res.delivery_status_name }}</td>
		<td class="center"><a href="commander.php?mode=send_download&amp;commandeid={{ res.id }}"><img src="{{ mail_src|escape('html') }}" alt="" /></a> <a href="commander.php?mode=send_download&amp;commandeid={{ res.id }}&amp;email={{ res.email }}">{{ STR_ADMIN_SEND_NOW }}</a></td>
		<td class="center">{{ res.statut_envoi }} / ({{ res.nb_envoi }})</td>
		<td class="center">{{ res.date_download }} / ({{ res.nb_download }})</td>
	</tr>
	{% endfor %}
	<tr><td colspan="11" class="center">{{ links_multipage }}</td></tr>
	{% else %}
	<tr><td colspan="11"><div class="alert alert-warning">{{ STR_ADMIN_COMMANDER_NO_DOWLOAD_ORDER_FOUND }}</div></td></tr>
	{% endif %}
</table>