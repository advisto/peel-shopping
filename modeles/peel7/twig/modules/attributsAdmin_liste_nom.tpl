{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributsAdmin_liste_nom.tpl 35187 2013-02-12 19:02:41Z gboussin $
#}<table class="main_table" cellpadding="2">
	<tr>
		<td class="entete" colspan="4">{{ STR_MODULE_ATTRIBUTS_ADMIN_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="4"><div class="global_help">{{ STR_MODULE_ATTRIBUTS_ADMIN_EXPLAIN }}</div></td>
	</tr>
	<tr>
		<td colspan="4"><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" class="middle" /><a href="{{ add_href|escape('html') }}">{{ STR_MODULE_ATTRIBUTS_ADMIN_CREATE }}</a></td>
	</tr>
{% if num_results == 0 %}
	<tr><td><b>{{ STR_MODULE_ATTRIBUTS_ADMIN_NOTHING_FOUND }}</b></td></tr>
{% else %}
	<tr>
		<td class="menu" width="200">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_ADMIN_NAME }}</td>
		<td class="menu">{{ STR_TYPE }}</td>
		<td class="menu">{{ STR_STATUS }}</td>
	</tr>
	{% for res in results %}
		{{ res.tr_rollover }}
			<td class="center"><a onclick="return confirm('{{ STR_ADMIN_CONFIRM_JAVASCRIPT|filtre_javascript(true,true,true) }}');" title="{{ STR_DELETE|str_form_value }} {{ res.nom }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a> <a href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" alt="{{ STR_ADMIN_UPDATE }}" /></a></td>
			<td class="center"><a title="{{ STR_MODULE_ATTRIBUTS_ADMIN_UPDATE|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.nom }}</a></td>
			<td class="center">
				{% if not res.texte_libre and not res.upload %}
					<a href="{{ res.texte_libre_href|escape('html') }}">{{ STR_MODULE_ATTRIBUTS_ADMIN_HANDLE_OPTIONS }}</a>
				{% elseif res.upload %}
					{{ STR_MODULE_ATTRIBUTS_ADMIN_UPLOAD_FIELD }}
				{% elseif res.texte_libre %}
					{{ STR_MODULE_ATTRIBUTS_ADMIN_CUSTOM_TEXT }}
				{% endif %}
			</td>
			<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
		</tr>
	{% endfor %}
{% endif %}
</table>