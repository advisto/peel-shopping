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
// $Id: admin_liste_ecotaxes.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<table class="full_width">
	<tr>
		<td class="entete" colspan="5">{{ STR_ADMIN_ECOTAXES_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="5"><div class="global_help">{{ STR_ADMIN_ECOTAXES_EXPLAIN }}</div></td>
	</tr>
	<tr>
		<td colspan="5">
			<table>
				<tr>
					<td><img src="{{ add_src|escape('html') }}" width="16" height="16" alt="" /></td>
					<td><a href="{{ add_href|escape('html') }}">{{ STR_ADMIN_ECOTAXES_ADD_ECOTAX }}</a></td>
				</tr>
			</table><br />
		</td>
	</tr>
	{% if (results) %}
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_ADMIN_CODE }}</td>
		<td class="menu">{{ STR_ADMIN_ECOTAX }}</td>
		<td class="menu">{{ STR_PRICE }} {{ STR_HT }}</td>
		<td class="menu">{{ STR_PRICE }} {{ STR_TTC }}</td>
	</tr>
	{% for res in results %}
	{{ res.tr_rollover }}
	
		<td class="center"><a onclick="return confirm('{{ STR_ADMIN_DELETE_WARNING|filtre_javascript(true,true,true) }}');" title="{{ STR_DELETE|str_form_value }} {{ res.nom|str_form_value }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a> &nbsp; &nbsp; <a title="{{ STR_ADMIN_ECOTAXES_MODIFY_ECOTAX|str_form_value }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a></td>
		<td class="center">{{ res.code }}</td>
		<td><a title="{{ STR_ADMIN_ECOTAXES_MODIFY_ECOTAX|str_form_value }}" href="{{ res.edit_href|escape('html') }}">{{ res.nom|str_shorten(100,'','...') }}</a></td>
		<td class="center">{{ res.prix_ht }}</td>
		<td class="center">{{ res.prix_ttc }}</td>
	</tr>
	{% endfor %}
	{% else %}
	<tr><td><b>{{ STR_ADMIN_ECOTAXES_NO_ECOTAX_FOUND }}</b></td></tr>
	{% endif %}
</table>