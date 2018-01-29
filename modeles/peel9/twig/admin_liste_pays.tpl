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
// $Id: admin_liste_pays.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_PAYS_TITLE }}</div>
<div class="alert alert-info">{{ STR_ADMIN_PAYS_LIST_EXPLAIN }}</div>
<p><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" class="middle" /><a href="{{ add_href|escape('html') }}">{{ STR_ADMIN_PAYS_CREATE }}</a></p>
<form class="entryform form-inline" role="form" action="{{ action|escape('html') }}" method="post">
	{{ STR_ADMIN_PAYS_ZONE_UPDATE_LABEL }}{{ STR_BEFORE_TWO_POINTS }}: 
	<select class="form-control" name="zones" style="width: 150px">
	{% for o in options %}
		<option value="{{ o.value|str_form_value }}">{{ o.name }}</option>
	{% endfor %}
	</select>
	<input type="radio" value="1" name="etat" /> {{ STR_ADMIN_ACTIVATE }} / <input type="radio" value="0" name="etat" /> {{ STR_ADMIN_DEACTIVATE }} <input class="btn btn-primary" type="submit" value="{{ STR_VALIDATE|str_form_value }}" />
</form>
{% if (results) %}
<div class="table-responsive">
	<table id="admin_liste_pays" class="table">
		<thead>
			<tr>
				<td class="menu">{{ STR_ADMIN_ACTION }}</td>
				<td class="menu center" colspan="2">{{ STR_COUNTRY }}</td>
				<td class="menu center">{{ STR_ADMIN_MENU_MANAGE_ZONES }}</td>
				<td class="menu center">{{ STR_ADMIN_POSITION }}</td>
				<td class="menu center">{{ STR_STATUS }}</td>
				<td class="menu center">{{ STR_ADMIN_WEBSITE }}</td>
			</tr>
		</thead>
		<tbody class="sortable">
		{% for res in results %}
			{{ res.tr_rollover }}
				<td class="center" style="min-width: 60px"><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}');" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a> &nbsp; <a title="{{ STR_ADMIN_PAYS_MODIFY }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
				<td class="center" style="min-width: 30px">{{ res.flag }}</td>
				<td style="padding-left:10px"><a title="{{ STR_ADMIN_PAYS_MODIFY }}" href="{{ res.edit_href|escape('html') }}">{{ res.pays }}</a></td>
				<td class="center">{{ res.zone }}</td>
				<td class="center position">{{ res.position }}</td>
				<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
				<td class="center position">{{ res.site_name }}</td>
			</tr>
		{% endfor %}
		</tbody>
	</table>
</div>
{% else %}
<div class="alert alert-warning">{{ STR_ADMIN_PAYS_NOTHING_FOUND }}</div>
{% endif %}