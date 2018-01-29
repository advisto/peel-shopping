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
// $Id: admin_liste_taille.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<div class="entete" colspan="6">{{ STR_ADMIN_TAILLES_TITRE }}</div>
<div class="alert alert-info">{{ STR_ADMIN_TAILLES_LIST_EXPLAIN }}</div>
<div><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" class="middle" /><a href="{{ add_href|escape('html') }}">{{ STR_ADMIN_TAILLES_CREATE }}</a></div>
{% if (results) %}
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<td class="menu">{{ STR_ADMIN_ACTION }}</td>
				<td class="menu">{{ STR_SIZE }}</td>
				<td class="menu">{{ STR_PRICE }}</td>
				<td class="menu">{{ STR_ADMIN_RESELLER_PRICE }}</td>
				<td class="menu">{{ STR_ADMIN_POSITION }}</td>
				<td class="menu">{{ STR_ADMIN_WEBSITE }}</td>
			</tr>
		</thead>
		<tbody class="sortable">
			{% for res in results %}
			{{ res.tr_rollover }}
				<td><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}');" alt="{{ STR_DELETE|str_form_value }} {{ res.nom }}" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a></td>
				<td class="center"><a title="{{ STR_ADMIN_TAILLES_UPDATE|str_form_value }}" href="{{ res.modif_href|escape('html') }}">{{ res.nom }}</a></td>
				<td class="center">{{ res.prix }}</td>
				<td class="center">{{ res.prix_revendeur }}</td>
				<td class="center position">{{ res.position }}</td>
				<td class="center">{{ res.site_name }}</td>
			</tr>
			{% endfor %}
		</tbody>
	</table>
</div>
{% else %}
<div class="alert alert-warning">{{ STR_ADMIN_TAILLES_NOTHING_FOUND }}</div>
{% endif %}