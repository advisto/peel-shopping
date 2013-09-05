{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributsAdmin_liste.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}<table class="full_width" cellpadding="2">
	<tr><td colspan="4" class="entete">{{ STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST }} <strong>{{ nom|html_entity_decode_if_needed }}</strong></td></tr>
	<tr>
		<td colspan="4"><p>{{ STR_MODULE_ATTRIBUTS_ADMIN_ATTRIBUTE_OPTIONS_LIST_EXPLAIN }}</td>
	</tr>
	<tr>
		<td colspan="4"><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" class="middle" /><a href="{{ add_href|escape('html') }}">{{ STR_MODULE_ATTRIBUTS_ADMIN_CREATE_OPTION }}</a></td>
	</tr>
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_LIST }}</td>
		<td class="menu">{{ STR_PRICE }}</td>
		<td class="menu">{{ STR_PHOTO }}</td>
	</tr>
{% if num_results == 0 %}
	<tr><td colspan="4"><b>{{ STR_MODULE_ATTRIBUTS_ADMIN_NO_OPTION_DEFINED }}</b></td></tr>
{% else %}
	{% for res in results %}
		{{ res.tr_rollover }}
		<td class="center">
			<a onclick="return confirm('{{ STR_ADMIN_DELETE_WARNING|filtre_javascript(true,true,true) }}');" href="{{ res.drop_href|escape('html') }}"><img src="{{ res.drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a>
		</td>
		<td class="center">
			<a title="{{ STR_MODIFY|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.descriptif|html_entity_decode_if_needed }}</a>
		</td>
		<td class="center">{{ res.prix }} {{ STR_TTC }}</td>
		<td class="center">{% if (res.img_src) %}<img src="{{ res.img_src|escape('html') }}" alt="" />{% endif %}</td>
		</tr>
	{% endfor %}
{% endif %}
</table>