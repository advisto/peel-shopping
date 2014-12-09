{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_rpc.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
#}<ul>
	{% if (results) %}
	{% for res in results %}
		{% if (return_mode_for_displayed_values) and return_mode_for_displayed_values == "order" %}
			<li onclick="add_products_list_line('{{ res.id|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.reference|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.nom|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.purchase_prix }}', '{1|filtre_javascript(true,true,true) }}', '{{ res.size_options_html|htmlentities|filtre_javascript(true,true,true) }}',  '{{ res.color_options_html|htmlentities|filtre_javascript(true,true,true) }}',  '{{ res.tva_options_html|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.purchase_prix_ht|str_form_value }}', '{{ res.prix_cat|str_form_value }}', '{{ res.prix_cat_ht|str_form_value }}', '{{ STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript(true,true,true) }}', 'order');">{{ STR_ADMIN_PRODUITS_ADD_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}: <b>{{ res.reference }} {{ res.nom|html_entity_decode_if_needed }}</b> - {{ res.purchase_prix_displayed }}</li>
		{% else %}
			<li onclick="add_products_list_line('{{ res.id|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.reference|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.nom|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ 0|filtre_javascript(true,true,true) }}', '{1|filtre_javascript(true,true,true) }}', '',  '',  '{{ 0|filtre_javascript(true,true,true) }}', '{{ 0|filtre_javascript(true,true,true) }}', '{{ 0|filtre_javascript(true,true,true) }}', '{{ 0|filtre_javascript(true,true,true) }}', '{{ STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript(true,true,true) }}', 'product');">{{ STR_ADMIN_PRODUITS_ADD_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}: <b>{{ res.reference }} {{ res.nom|html_entity_decode_if_needed }}</b> - {{ res.purchase_prix_displayed }}</li>
		{% endif %}
	{% endfor %}
	{% else %}
	<li>{{ STR_AUCUN_RESULTAT }}</li>
	{% endif %}
</ul>