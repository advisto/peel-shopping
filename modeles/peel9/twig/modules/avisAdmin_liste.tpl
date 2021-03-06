{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: avisAdmin_liste.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}

<div class="entete">{{ STR_MODULE_AVIS_ADMIN_LIST }}</div>
	<div style="margin-top:5px;">
		<p><a href="{{ add_prod_href|escape('html') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {{ STR_MODULE_AVIS_ADMIN_ADD_ON_PRODUCT|str_form_value }}</a></p>
	</div>
<p>
{% if is_annonce_module_active %}
	<div style="margin-top:5px;">
		<p><a href="{{ add_annonce_href|escape('html') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {{ STR_MODULE_AVIS_ADMIN_ADD_ON_AD }}</a></p>
	</div>
{% endif %}

<div class="table-responsive">
	<table class="table avisAdmin_liste">
		{{ links_header_row }}
	{% if results %}
		{% for res in results %}
		{{ res.tr_rollover }}
			<td class="center">
				<a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a>
				<a title="{{ STR_MODULE_AVIS_ADMIN_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" alt="" /></a>
			</td>
			<td class="center"><a href="{{ res.edit_href|escape('html') }}">{{ res.reference }}</a></td>
			<td class="center">{% if res.reference_url %}<a href="{{ res.reference_url }}">{{ res.nom|html_entity_decode_if_needed }}</a>{% else %}{{ res.nom|html_entity_decode_if_needed }}{% endif %}</td>
			<td class="center">{% for foo in 1..res.note %}<img src="{{ star_src|escape('html') }}" alt="" style="vertical-align:middle" />{% endfor %}</td>
			<td class="center">{{ res.date }}</td>
			<td class="center">{{ res.date_validation }}</td>
			<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
			<td class="center"><a href="{{ res.util_href|escape('html') }}">{{ res.prenom }} ({{ res.email }})</a></td>
			<td class="center">{{ res.site_name }}</td>
		</tr>
		{% endfor %}
	{% else %}
		<tr><td colspan="9"><b>{{ STR_MODULE_AVIS_ADMIN_NOTHING_FOUND }}</b></td></tr>
	{% endif %}
	</table>
</div>
<div class="center">{{ links_multipage }}</div>