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
// $Id: admin_email-templates_report.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}{% if (results) %}
<br />
<div class="entete">{{ STR_ADMIN_EMAIL_TEMPLATES_TITLE }}</div>
<div>{{ links_multipage }}</div>
<div class="table-responsive email_templates_report">
	<table class="table">
		{{ links_header_row }}
		{% for res in results %}
		{{ res.tr_rollover }}
			<td class="center"><b>{{ res.id }}</b></td>
			<td class="center"><b>{{ res.technical_code }}</b></td>
			<td class="center"><b>{{ res.category_name }}</b></td>
			<td class="center"><b>{{ res.name }}</b></td>
			<td class="center">{{ res.subject|htmlentities }}</td>
			<td style="padding:8px;">{{ res.text|htmlentities }}</td>
			<td class="center" style="padding-left:5px;padding-right:5px;">{{ res.lang }}</td>
			<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
			<td class="center"><a href="{{ res.edit_href|escape('html') }}">{{ STR_MODIFY }}</a></td>
			<td class="center">{{ res.site_name }}</td>
		</tr>
		{% endfor %}
	</table>
</div>
<div>{{ links_multipage }}</div>
{% endif %}