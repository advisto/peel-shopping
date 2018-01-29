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
// $Id: search_produit.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}{% if form_add_search_product_list %}
<script><!--//--><![CDATA[//><!--
		var new_order_line_html = '<tr class="top" id="line[i]"><td><span class="glyphicon glyphicon-remove-sign" title="{{ LANG.STR_DELETE }}" onclick="if(bootbox.confirm(\'{{ LANG.STR_DELETE_PROD_CART|filtre_javascript('true','true','false') }}\', function(result) {if(result) {delete_products_list_line([i], true, [id]);} } ))return false;" style="cursor:pointer"></span> </td><td class="center"> <img src="[photo_src]" /><input type="hidden" value="[id]" name="produit_id[]"> </td> <td class="center">[ref]</td> <td class="center"><a href="[href_produit]" target="_blank">[nom]</a></td>{% if display_barcode %}<td class="center"><img src="[barcode_image_src]" /><br />[code_barres]</td>{% endif %}<td class="center">[brand_link_html]</td> <td class="center"><a href="[href_category]" target="_blank">[category_name]</a></td> <td class="center">[prix]</td> <td class="center">[prix_minimal]</td><td class="center" id="display_quantity_[id]" ><input onkeyup="update_session_search_product_list(this.value, [id], \'update_session_add\');" style="width: 35px;" type="text" value="[quantite]" id="products_list_line_quantity_[id]" name="qte[]"></td></tr>';
	{% for res in results %}
		add_products_list_line('{{ res.id|str_form_value|htmlentities|filtre_javascript('true','true','true') }}', '{{ res.reference|str_form_value|htmlentities|filtre_javascript('true','true','true') }}', '{{ res.name|str_form_value|htmlentities|filtre_javascript('true','true','true') }}', '{0|filtre_javascript('true','true','true') }}', 1, '',  '',  '{0|filtre_javascript('true','true','true') }}', '{0|filtre_javascript('true','true','true') }}', '{0|filtre_javascript('true','true','true') }}', '{0|filtre_javascript('true','true','true') }}', '{{ LANG.STR_REQUEST_OK|filtre_javascript('true','true','true')  }} }}', 'search_product_list', 'search_' ,'{{ res.photo_src|str_form_value|htmlentities|filtre_javascript(true,true,false) }}' , '{{ res.ean_code|str_form_value|htmlentities|filtre_javascript('true','true','true') }}', '{{ res.barcode_image_src|str_form_value|htmlentities|filtre_javascript('true','true','true') }}', '{{ res.brand_link_html|filtre_javascript('true','true','true') }}', '{{ res.category_name|str_form_value|htmlentities|filtre_javascript('true','true','true') }}' , '{{ res.prix|str_form_value|htmlentities|filtre_javascript('true','true','true') }}' , '{{ res.minimal_price|str_form_value|htmlentities|filtre_javascript('true','true','true') }}', '{{ res.href_produit|str_form_value|htmlentities|filtre_javascript('true','true','true') }}', '{{ res.href_category|str_form_value|filtre_javascript('true','true','true') }}');
	{% endfor %}
//--><!]]></script>
{% else %}
	<ul>
	{% for res in results}
		<li><a href="{{ res.href }}">{{ res.name }}</a></li>
	{% else %}
		<li>{{ STR_AUCUN_RESULTAT }}</li>
	{% endfor %}
	</ul>
{% endif %}