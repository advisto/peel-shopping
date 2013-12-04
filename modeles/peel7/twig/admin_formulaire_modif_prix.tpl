{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_modif_prix.tpl 39162 2013-12-04 10:37:44Z gboussin $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="modifier" />
	<table class="admin_formulaire_modif_prix">
		<tr>
			<td class="entete" colspan="5">{{ STR_ADMIN_PRIX_FORM_TITLE }} {{ category_name }}</td>
		</tr>
		<tr><td colspan="5" align="center"><br /></td></tr>
		<tr>
			<td class="menu center">{{ STR_PRODUCT }}</td>
			<td class="menu center">{{ STR_ADMIN_PRIX_PUBLIC_PRICE }}</td>
			<td class="menu center">{{ STR_ADMIN_RESELLER_PRICE }}</td>
			<td class="menu center">{{ STR_ADMIN_PRIX_PURCHASE_PRICE }}</td>
			<td class="menu center">{{ STR_REMISE }}</td>
		</tr>
		{% if (results) %}
		{% for res in results %}
		{{ res.tr_rollover }}
			<td class="center"><input type="hidden" name="id[]" value="{{ res.id|str_form_value }}" /><a href="{{ res.modif_href|escape('html') }}">{{ res.nom|html_entity_decode_if_needed }}</a></td>
			<td class="center"><input type="text" class="form-control" name="prix[]" style="width:150px" value="{{ res.prix|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}</td>
			<td class="center"><input type="text" class="form-control" name="prix_revendeur[]" style="width:150px" value="{{ res.prix_revendeur|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}</td>
			<td class="center"><input type="text" class="form-control" name="prix_achat[]" style="width:150px" value="{{ res.prix_achat|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}</td>
			<td class="center"><input type="text" class="form-control" name="promotion[]" style="width:150px" value="{{ res.promotion|str_form_value }}" /> %</td>
		</tr>
		{% endfor %}
		{% else %}
			<tr><td colspan="5" align="center">{{ STR_ADMIN_PRIX_NO_PRODUCT_FOUND }}</td></tr>
		{% endif %}
		<tr><td colspan="5" align="center"><br /><br /><input type="submit" value="{{ STR_ADMIN_PRIX_UPDATE|str_form_value }}" class="btn btn-primary" /><br /><br /></td></tr>
	</table>
</form>