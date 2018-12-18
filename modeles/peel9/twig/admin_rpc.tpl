{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// Id: admin_rpc.tpl 46326 2015-07-08 08:31:44Z sdelaporte 
#}<ul>
	{% if mode == "products" %}
		{% if (results) %}
			{% for res in results %}
				{% if (return_mode_for_displayed_values) and return_mode_for_displayed_values == "order" %}
	<li onclick="add_products_list_line('{{ res.id|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.reference|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.nom|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.image_thumbs|str_form_value|htmlentities|filtre_javascript(true,true,true) }}','{{ res.image|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.purchase_prix }}', '{{ 1|filtre_javascript(true,true,true) }}', '{{ res.size_options_html|htmlentities|filtre_javascript(true,true,true) }}',  '{{ res.color_options_html|htmlentities|filtre_javascript(true,true,true) }}',  '{{ res.tva_options_html|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.purchase_prix_ht|str_form_value }}', '{{ res.prix_cat|str_form_value }}', '{{ res.prix_cat_ht|str_form_value }}', '{{ STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript(true,true,true) }}', 'order');">{{ STR_ADMIN_PRODUITS_ADD_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}: <b>{{ res.reference }} {{ res.nom|html_entity_decode_if_needed }}</b> - {{ res.purchase_prix_displayed }}</li>
				{% else %}
	<li onclick="add_products_list_line('{{ res.id|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.reference|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.nom|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ 0|filtre_javascript(true,true,true) }}', '{{ res.image_thumbs|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.image|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ 1|filtre_javascript(true,true,true) }}', '',  '',  '{{ 0|filtre_javascript(true,true,true) }}', '{{ 0|filtre_javascript(true,true,true) }}', '{{ 0|filtre_javascript(true,true,true) }}', '{{ 0|filtre_javascript(true,true,true) }}', '{{ STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript(true,true,true) }}', 'product');">{{ STR_ADMIN_PRODUITS_ADD_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}: <b>{{ res.reference }} {{ res.nom|html_entity_decode_if_needed }}</b> - {{ res.purchase_prix_displayed }}</li>
				{% endif %}
			{% endfor %}
		{% else %}
	<li>{{ STR_AUCUN_RESULTAT }}</li>
		{% endif %}
	{% elseif mode == "offers" %}
		{% if (results) %}
			{% for res in results %}
	<li style="list-style-type:none" class="offres_proposees" onclick="add_offers_list_line('{{ res.id|str_form_value|htmlentities|filtre_javascript(true,true,true) }}', '{{ res.nom|str_form_value|htmlentities|filtre_javascript(true,true,true) }}','{{ res.user_id|str_form_value|htmlentities|filtre_javascript(true,true,true) }}','{{ STR_ADMIN_OFFER_ADD_OFFER|str_form_value|htmlentities|filtre_javascript(true,true,true) }}','{{ res.id|str_form_value|htmlentities|filtre_javascript(true,true,true) }}');">{{ STR_ADMIN_OFFER_ADD_OFFER }}{{ STR_BEFORE_TWO_POINTS }}: <b>{{ res.nom|html_entity_decode_if_needed }}</b></li>
			{% endfor %}
		{% else %}
	<li>{{ STR_OFFER_NO_RESULT }}</li>
		{% endif %}
	{% elseif mode == "offer_add_user" %}
		{% if (results) %}
			{% for res in results %}
	<li style="list-style-type:none" class="offres_proposees" onclick="add_user_to_offer('{{ res.id_utilisateur|str_form_value|htmlentities|filtre_javascript(true,true,true) }}','{{ res.nom_famille|html_entity_decode_if_needed }} {{ res.prenom|html_entity_decode_if_needed }}','{{ res.msg|str_form_value|htmlentities|filtre_javascript(true,true,true) }}')"><b>{{ res.nom_famille|html_entity_decode_if_needed }} {{ res.prenom|html_entity_decode_if_needed }}</b> - {{ res.societe|html_entity_decode_if_needed }} {% if (res.laboratoire) %}{{ res.laboratoire|html_entity_decode_if_needed }} {% endif %}{{ res.ville|html_entity_decode_if_needed }} {{ res.email|html_entity_decode_if_needed }}</li>
			{% endfor %}
		{% else %}
	<li>{{ STR_OFFER_NO_RESULT }}</li>
		{% endif %}
	{% endif %}	
</ul>