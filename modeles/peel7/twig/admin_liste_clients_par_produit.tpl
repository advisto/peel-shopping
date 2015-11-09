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
// $Id: admin_liste_clients_par_produit.tpl 47592 2015-10-30 16:40:22Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_PRODUITS_ACHETES_LIST_TITLE }} {{ nom }}</div>
<div class="table-responsive">
	<table class="table admin_liste_clients_par_produit">
		<tr>
			<th class="menu">{{ STR_LAST_NAME }}</th>
			<th class="menu">{{ STR_FIRST_NAME }}</th>
			<th class="menu">{{ STR_ADDRESS }}</th>
			<th class="menu">{{ STR_EMAIL }}</th>
			<th class="menu">{{ STR_TELEPHONE }}</th>
			<th class="menu">{{ STR_QUANTITY_SHORT }}</th>
			<th class="menu">{{ STR_TOTAL_AMOUNT }}</th>
		</tr>
	{% for c in clients %}
		{{ c.tr_rollover }}
			<td class="center"><a href="{{ c.href|escape('html') }}">{{ c.nom_famille }}</a></td>
			<td class="center">{{ c.prenom }}</td>
			<td class="center">{{ c.adresse }}<br />{{ c.code_postal }} {{ c.ville }}</td>
			<td class="center">{{ c.email }}</td>
			<td class="center">{{ c.telephone }}</td>
			<td class="center">{{ c.total_quantite }}</td>
			<td class="center">{{ c.prix }}</td>
		</tr>
	{% endfor %}
	<tr>
	{% if is_module_export_ventes_active %}
		<td colspan="5" class="label" align="right" style="padding-bottom:15px"><a href="{{ export_href|escape('html') }}" class="label"><img src="{{ excel_src|escape('html')}}" align="absmiddle" alt="" />&nbsp;{{ STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT }}</a></td>
		<td colspan="2" class="label">&nbsp;</td>
	{% else %}
		<td colspan="7" class="label">&nbsp;</td>
	{% endif %}
	</tr>
	</table>
</div>	