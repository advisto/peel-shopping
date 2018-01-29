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
// $Id: admin_liste_categorie.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_CATEGORIES_LIST_TITLE }}</div>
<div><p><img src="{{ add_src|escape('html') }}" alt="" /><a href="{{ add_href|escape('html') }}">{{ STR_ADMIN_CATEGORIES_CREATE }}</a></p></div>
<div>
	<img src="{{ cat_src|escape('html') }}" width="16" height="16" alt="" class="middle" /> {{ STR_ADMIN_CATEGORIES_ADD_SUBCATEGORY }}
</div>
<div>
	<img src="{{ prod_src|escape('html') }}" width="16" height="16" alt="" class="middle" /> {{ STR_ADMIN_CATEGORIES_ADD_PRODUCT }}
</div>
<div>
	<img src="{{ drop_src|escape('html') }}" width="16" height="16" alt="" class="middle" /> {{ STR_ADMIN_CATEGORIES_DELETE_CATEGORY }}
</div>
<div class="table-responsive">
	<table class="table">
		<tr>
			<td class="menu">{{ STR_ADMIN_ACTION }}</td>
			<td class="menu">{{ STR_ADMIN_ID }}</td>
			<td class="menu">{{ STR_IMAGE }}</td>
			<td class="menu" style="text-align:left">{{ STR_ADMIN_CATEGORIES }}</td>
			<td class="menu" style="text-align:left">{{ STR_WEBSITE }}</td>
	{% if is_category_promotion_module_active %}
			<td class="menu">{{ STR_PROMOTION }}</td>
	{% endif %}
			<td class="menu">{{ STR_ADMIN_POSITION }}</td>
			<td class="menu">{{ STR_STATUS }}</td>
		</tr>
		{{ categorie_options }}
	</table>
</div>