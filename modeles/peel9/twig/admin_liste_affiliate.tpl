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
// $Id: admin_import_form.tpl 59808 2019-02-18 13:57:06Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_TITLE }}</div>
<div style="margin-top:5px;">
	<p><a href="{{ add_href|escape('html') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {{ STR_ADMIN_ADD }}</a></p>
</div>
<div class="table-responsive">
	<table class="table">
	{% if results %}
		<thead>
			<tr>
				<td class="menu">{{ STR_ADMIN_ACTION }}</td>
				<td class="menu">{{ STR_ADMIN_WEBSITE }}</td>
			</tr>
		</thead>
		<tbody class="sortable">
		{% for results in res %}
			{{ res.tr_rollover }}
				<td class="center"><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a> &nbsp; <a title="{{ STR_ADMIN_UPDATE }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
				<td class="center">{{ res.site_name }}</td>
			</tr>
		{% endfor %}
		</tbody>
	{% else %}
		<tbody class="sortable">
			<tr><td colspan="6"><div class="alert alert-warning">{{ STR_ADMIN_NO_RESULT }}</div></td></tr>
		</tbody>
	{% endif %}
	</table>
</div>