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
// $Id: admin_liste_ecotaxes.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<table class="full_width">
	<tr>
		<td class="entete" colspan="6">{{ STR_ADMIN_ECOTAXES_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="6">
			<div style="margin-top:5px;">
				<p><a href="{{ add_href|escape('html') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {{ STR_ADMIN_ECOTAXES_ADD_ECOTAX }}</a></p>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="6"><div class="alert alert-info">{{ STR_ADMIN_ECOTAXES_EXPLAIN }}</div></td>
	</tr>

	{% if (results) %}
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_ADMIN_CODE }}</td>
		<td class="menu">{{ STR_ADMIN_ECOTAX }}</td>
		<td class="menu">{{ STR_PRICE }} {{ STR_HT }}</td>
		<td class="menu">{{ STR_PRICE }} {{ STR_TTC }}</td>
		<td class="menu">{{ STR_ADMIN_WEBSITE }}</td>
	</tr>
	{% for res in results %}
	{{ res.tr_rollover }}
	
		<td class="center"><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.nom|str_form_value }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a> &nbsp; &nbsp; <a title="{{ STR_ADMIN_ECOTAXES_MODIFY_ECOTAX|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
		<td class="center">{{ res.code }}</td>
		<td><a title="{{ STR_ADMIN_ECOTAXES_MODIFY_ECOTAX|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.nom|str_shorten(100,'','...') }}</a></td>
		<td class="center">{{ res.prix_ht }}</td>
		<td class="center">{{ res.prix_ttc }}</td>
		<td class="center">{{ res.site_name }}</td>
	</tr>
	{% endfor %}
	{% else %}
	<tr><td colspan="5"><b>{{ STR_ADMIN_ECOTAXES_NO_ECOTAX_FOUND }}</b></td></tr>
	{% endif %}
</table>