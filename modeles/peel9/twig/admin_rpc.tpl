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
// Id: admin_rpc.tpl 46326 2015-07-08 08:31:44Z sdelaporte 
#}<ul>
	{% if mode == "products" %}
		{%if add_specific_lines_in_order is defined %}
			{% if add_specific_lines_in_order %}
				{{ add_specific_lines_in_order }}
			{% else %}
				<li>{{ STR_AUCUN_RESULTAT }}</li>
			{% endif %}
		{% elseif results is defined %}
			{% for res in results %}
			<script><!--//--><![CDATA[//><!--
			var arr{{ res.id }} = {
				"id" : "{{ res.id|str_form_value|htmlentities|filtre_javascript(true,true,true) }}", 
				"ref" : "{{ res.reference|str_form_value|htmlentities|filtre_javascript(true,true,true) }}",
				"nom" : "{{ res.nom|str_form_value|htmlentities|filtre_javascript(true,true,true) }}",
				"quantite" : "1",
				"image_thumbs" : "{{ res.image_thumbs|str_form_value|htmlentities|filtre_javascript(true,true,true) }}",
				"image_large" : "{{ res.image|str_form_value|htmlentities|filtre_javascript(true,true,true) }}",
				"purchase_prix_ht" : "{{ res.purchase_prix_ht|str_form_value }}",
				"tva_options_html" : "{{ res.tva_options_html|filtre_javascript(true,true,true) }}",
				"color_options_html" : "{{ res.color_options_html|filtre_javascript(true,true,true) }}",
				"size_options_html" : "{{ res.size_options_html|filtre_javascript(true,true,true) }}",
				"purchase_prix" : "{{ res.purchase_prix }}",
				"prix_cat" : "{{ res.prix_cat|str_form_value }}",
				"prix_cat_ht" : "{{ res.prix_cat_ht|str_form_value }}",
				"remise" : "0",
				"remise_ht" : "0",
				"percent" : "0"
			}
			//--><!]]></script>
			{% endfor %}
			{% for res in results %}
				{% if return_mode_for_displayed_values and return_mode_for_displayed_values == "order" %}
	<li onclick="add_products_list_line(arr{{ res.id }},'{{ STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript(true,true,true) }}', 'order', true);">{{ STR_ADMIN_PRODUITS_ADD_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}: <b>{{ res.reference }} {{ res.nom|html_entity_decode_if_needed }}</b> - {{ res.purchase_prix_displayed }}</li>
				{% else %}
	<li onclick="add_products_list_line(arr{{ res.id }}, '{{ STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript(true,true,true) }}', 'product', false);">{{ STR_ADMIN_PRODUITS_ADD_PRODUCT }}{{ STR_BEFORE_TWO_POINTS }}: <b>{{ res.reference }} {{ res.nom|html_entity_decode_if_needed }}</b> - {{ res.purchase_prix_displayed }}</li>
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