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
// $Id: admin_liste_langue.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_LANGUES_TITLE }}</div>
<div>
	<div style="margin-top:5px;">
		<p><a href="{{ add_href|escape('html') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {{ STR_ADMIN_LANGUES_ADD_LANGUAGE }}</a></p>
	</div>
	<p><a href="langues.php?mode=repair">{{ STR_ADMIN_LANGUES_REPAIR_LINK }}</a></p>
</div>
<div class="alert alert-info">
	<b>{{ STR_WARNING }}{{ STR_BEFORE_TWO_POINTS }}:</b><br />
	<ul>
		<li>{{ STR_ADMIN_LANGUES_EXPLAIN1 }}</li>
		<li>{{ STR_ADMIN_LANGUES_EXPLAIN2 }}</li>
		<li>{{ STR_ADMIN_LANGUES_EXPLAIN3 }}</li>
	</ul>
</div>
{% if (results) %}
<div class="table-responsive">
	<table class="table">
		<thead>
			<tr>
				<td class="menu">{{ STR_ADMIN_ACTION }}</td>
				<td class="menu">{{ STR_ADMIN_LANGUAGE }}</td>
				<td class="menu">{{ STR_ADMIN_LANGUES_EXTENSION }}</td>
				<td class="menu">{{ STR_ADMIN_FLAG }}</td>
				<td class="menu">{{ STR_ADMIN_URL_REWRITING }}</td>
				<td class="menu">{{ STR_ADMIN_POSITION }}</td>
				<td class="menu">{{ STR_STATUS }}</td>
				<td class="menu">{{ STR_ADMIN_WEBSITE }}</td>
			</tr>
		</thead>
		<tbody class="sortable">
			{% for res in results %}
			{{ res.tr_rollover }}
				<td class="center"><a title="{{ STR_ADMIN_LANGUES_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
				<td class="center"><a title="{{ STR_ADMIN_LANGUES_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.nom }}</a></td>
				<td class="center">{{ res.lang }}</td>
				<td class="center">{% if (res.flag_src) %}<img src="{{ res.flag_src|escape('html') }}" alt="" />{% endif %}</td>
				<td class="center">{{ res.url_rewriting }}</td>
				<td class="center position">{{ res.position }}</td>
				<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
				<td class="center position">{{ res.site_name }}</td>
			</tr>
			{% endfor %}
		</tbody>
	</table>
</div>
{% else %}
<div class="alert alert-warning">{{ STR_ADMIN_LANGUES_NOTHING_FOUND }}</div>
{% endif %}