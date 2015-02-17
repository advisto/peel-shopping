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
// $Id: avisAdmin_liste.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
#}
<div class="entete">{{ STR_MODULE_AVIS_ADMIN_LIST }}</div>
<p><a href="{{ add_prod_href|escape('html') }}"><img src="{{ add_src|escape('html') }}" width="16" height="16" class="middle" alt="{{ STR_MODULE_AVIS_ADMIN_ADD_ON_PRODUCT|str_form_value }}" />{{ STR_MODULE_AVIS_ADMIN_ADD_ON_PRODUCT }}</a></p>
{% if is_annonce_module_active %}<p><a href="{{ add_annonce_href|escape('html') }}"><img src="{{ add_src|escape('html') }}" width="16" height="16" class="middle" alt="{{ STR_MODULE_AVIS_ADMIN_ADD_ON_AD|str_form_value }}" />{{ STR_MODULE_AVIS_ADMIN_ADD_ON_AD }}</a></p>{% endif %}
<div class="table-responsive">
	<table class="table avisAdmin_liste">
		<tr>
			<td class="menu">{{ STR_ADMIN_ACTION }}</td>
			<td class="menu">{{ STR_REFERENCE }}</td>
			<td class="menu">{{ STR_ADMIN_SUBJECT }}</td>
			<td class="menu">{{ STR_ADMIN_NOTE }}</td>
			<td class="menu">{{ STR_DATE }}</td>
			<td class="menu">{{ STR_STATUS }}</td>
			<td class="menu">{{ STR_BY }}</td>
		</tr>
	{% if results %}
		{% for res in results %}
		{{ res.tr_rollover }}
		<td class="center">
			<a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a>
			<a title="{{ STR_MODULE_AVIS_ADMIN_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" alt="" /></a>
		</td>
		<td class="center">{{ res.reference }}</td>
		<td class="center">{{ res.nom|html_entity_decode_if_needed }}</td>
		<td class="center">{% for foo in 1..res.note %}<img src="{{ star_src|escape('html') }}" alt="" style="vertical-align:middle" />{% endfor %}</td>
		<td class="center">{{ res.date }}</td>
		<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
		<td class="center"><a href="{{ res.util_href|escape('html') }}">{{ res.prenom }} ({{ res.email }})</a></td>
		{% endfor %}
	{% else %}
		<tr><td colspan="7"><b>{{ STR_MODULE_AVIS_ADMIN_NOTHING_FOUND }}</b></td></tr>
	{% endif %}
	</table>
</div>
<div class="center">{{ links_multipage }}</div>