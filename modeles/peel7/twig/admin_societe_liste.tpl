{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_societe_liste.tpl 47592 2015-10-30 16:40:22Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_SOCIETE_LIST_TITLE }}</div>
<div><p><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" class="middle" /><a href="{{ add_href|escape('html') }}">{{ STR_ADMIN_ADD }}</a></p></div>
<table class="main_table">
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_COMPANY }}</td>
		<td class="menu">{{ STR_EMAIL }}</td>
	</tr>
	{% for res in results %}
	<tr>
		<td class="center"><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_ADMIN_DELETE_WARNING|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" /></a> &nbsp; <a title="{{ STR_MODIFY }}" href="{{ res.modif_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
		<td class="center"><a href="{{ res.modif_href|escape('html') }}">{{ res.societe }}</a></td>
		<td class="center"><a title="{{ STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND }}" href="mailto:{{ res.email }}">{{ res.email }}</a></td>
	</tr>
	{% endfor %}
</table>	