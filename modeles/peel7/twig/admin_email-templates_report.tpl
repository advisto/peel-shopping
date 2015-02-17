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
// $Id: admin_email-templates_report.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
#}{% if (results) %}
<br />
<div class="entete">{{ STR_ADMIN_EMAIL_TEMPLATES_TITLE }}</div>
<div>{{ links_multipage }}</div>
<div class="table-responsive email_templates_report">
	<table class="table">
		<tr>
			<th class="menu">{{ STR_ADMIN_ID }}</th>
			<th class="menu">{{ STR_ADMIN_TECHNICAL_CODE }}</th>
			<th class="menu">{{ STR_CATEGORY }}</th>
			<th class="menu">{{ STR_ADMIN_NAME }}</th>
			<th class="menu">{{ STR_ADMIN_SUBJECT }}</th>
			<th class="menu">{{ STR_ADMIN_HTML_TEXT }}</th>
			<th class="menu">{{ STR_ADMIN_LANGUAGE }}</th>
			<th class="menu">{{ STR_STATUS }}</th>
			<th class="menu">{{ STR_ADMIN_ACTION }}</th>
			<th class="menu">{{ STR_ADMIN_WEBSITE }}</th>
		</tr>
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