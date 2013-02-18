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
// $Id: admin_liste_statut_livraison.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<table class="main_table">
	<tr>
		<td class="entete" colspan="3">{{ STR_ADMIN_STATUT_LIVRAISON_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="3"><p class="global_help">{{ STR_ADMIN_STATUT_LIVRAISON_EXPLAIN }}</p></td>
	</tr>
	<tr>
		<td colspan="3"><p><img src="{{ add_button_url|escape('html') }}" width="16" height="16" alt="" class="middle" /><a href="{{ add_status_url|escape('html') }}">{{ STR_ADMIN_STATUT_LIVRAISON_CREATE }}</a></p></td>
	</tr>
	{% if (results) %}
	<tr>
		<td class="menu">{{ STR_ADMIN_ID }}</td>
		<td class="menu">{{ STR_ADMIN_STATUT_STATUS_TYPE }}</td>
		<td class="menu">{{ STR_ADMIN_POSITION }}</td>
	</tr>
	{% for res in results %}
	{{ res.tr_rollover }}
		<td class="center">{{ res.id }}</td>
		<td><a title="{{ STR_ADMIN_STATUT_UPDATE|str_form_value }}" href="{{ res.modif_href|escape('html') }}">{{ res.nom }}</a></td>
		<td class="center position">{{ res.position }}</td>
	</tr>
	{% endfor %}
	{% else %}
	<tr><td colspan="3"><b>{{ STR_ADMIN_STATUT_NO_STATUS_FOUND }}</b></td></tr>
	{% endif %}
</table>