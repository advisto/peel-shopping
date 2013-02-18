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
// $Id: admin_email-templates_report.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}{% if (results) %}
<br />
<p>Ce tableau vous permet de modifier les emails envoyés à vos clients mais aussi de gérer leur état : activé ou désactivé.</p>
<center>
	<table class="full_width" style="margin-bottom:5px" border="1" cellpadding="5">
		<tr>
			<td class="entete" colspan="9">Gestion des modèles d'emails</th>
		</tr>
		<tr>
			<th colspan="9">{{ links_multipage }}</th>
		</tr>
		<tr>
			<th class="menu">Identifiant</th>
			<th class="menu">{{ STR_ADMIN_TECHNICAL_CODE }}<br />(pour emails auto)</th>
			<th class="menu">Catégorie</th>
			<th class="menu">{{ STR_ADMIN_NAME }}</th>
			<th class="menu">{{ STR_ADMIN_SUBJECT }}</th>
			<th class="menu">Contenu<br />(sans sauts de lignes)</th>
			<th class="menu">{{ STR_ADMIN_LANGUAGE }}</th>
			<th class="menu">{{ STR_STATUS }}</th>
			<th class="menu">{{ STR_ADMIN_ACTION }}</th>
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
			<td class="center"><a href="{{ res.edit_href|escape('html') }}">Editer</a></td>
		</tr>
		{% endfor %}
{% endif %}
	</table>
</center>
{{ links_multipage }}