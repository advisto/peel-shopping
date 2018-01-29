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
// $Id: critere_stock.tpl 54211 2017-07-04 13:08:18Z sdelaporte $
#}{% if is_form %}
<form class="entryform form-inline" role="form" enctype="multipart/form-data" method="post" action="{{ action|escape('html') }}" id="{{ form_id }}">
{% endif %}
	<div class="affiche_critere_stock well pull-right {{ update_class }}">
{% if is_form %}
	{% if not condensed_display_mode %}
		{% if is_color %}
		<table class="color_option">
			<tr>
				<td class="attribut-color">
					<label>{{ STR_COLOR }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				</td>
				<td>
					<select class="form-control" name="couleur" id="{{ id_select_color }}" onchange="{{ color_on_change_action }}">
						<option value="0">{{ STR_CHOOSE_COLOR }}</option>
						{% for c in colors %}
							<option value="{{ c.id|str_form_value }}"{% if c.issel %} selected="selected"{% endif %}{% if not c.isavailable %} disabled="disabled"{% endif %}>{{ c.name }}{{ c.suffix }}{% if not c.isavailable %} - {{ STR_NO_AVAILABLE }}{% endif %}</option>
						{% endfor %}
					</select>
				</td>
			</tr>
		</table>
		{% endif %}
		{% if is_sizes %}
		<table class="size_option">
			<tr>
				<td class="attribut-cell">
					<label>{{ STR_SIZE }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				</td>
				<td>
					<select class="form-control" id="{{ id_select_size }}" name="taille" onchange="update_product_price{{ save_suffix_id }}();bootbox_sizes_options(this);">
						<option value="0">{{ STR_CHOOSE_SIZE }}</option>
						{% for so in sizes_options %}
							<option {% if so.bootbox_sizes_options %}{{ so.bootbox_sizes_options }}{% endif %} value="{{ so.id|intval }}"{% if so.issel %} selected="selected"{% endif %}{% if not so.isavailable %} disabled="disabled"{% endif %}{% if so.found_stock_info >0 %}style="font-weight:bold;"{% endif %}>
							{{ so.name }}{{ so.suffix }}
							</option>
						{% endfor %}
					</select>
				</td>
			</tr>
		</table>
		{% endif %}
	{% elseif (stock_options) %}
		<p class="retour">
			<select class="form-control" name="critere" id="critere" onchange="document.location='{{ urlprod_with_cid }}'+getElementById('critere').value.split('|')[0]+'&amp;liste='+getElementById('critere').value;">
				{% for so in stock_options %}
					{% if so.isavailable %}
					<option value="{{ so.value }}"{% if so.issel %} selected="selected"{% endif %}>{{ so.label }}</option>
					{% else %}
					<option value="null">{{ so.couleur_nom }} &nbsp; {{ so.taille }}{{ STR_BEFORE_TWO_POINTS }}: {{ STR_NO_AVAILABLE }}</option>
					{% endif %}
				{% endfor %}
			</select>
		</p>
	{% endif %}
{% endif %}
		{{ display_javascript_for_price_update }}
{% if is_form %}
	{% if (affiche_attributs_form_part) %}{{ affiche_attributs_form_part }}{% endif %}
	{% if (affiche_etat_stock) %}{{ affiche_etat_stock }}{% endif %}
	{% if (stock_remain_all) %}
		<p class="title_label">{{ STR_STOCK_ATTRIBUTS }}{{ STR_BEFORE_TWO_POINTS }}: {{ stock_remain_all }}</p>
	{% endif %}
	{% if product_soon_available %}
		<p class="title_label">{{ product_soon_available }}</p>
	{% endif %}
{% endif %}
{% if (delai_stock) %}
		<p class="title_label">{{ STR_DELIVERY_STOCK }}{{ STR_BEFORE_TWO_POINTS }}: {{ delai_stock }}</p>
{% endif %}
		<div property="offers" typeof="Offer" class="product_affiche_prix">{{ product_affiche_prix }}</div>
{% if (etat_stock) %}
		{{ etat_stock }}
{% else %}
	{% if (formulaire_alerte) %}
		{{ formulaire_alerte }}
	{% endif %}
{% endif %}
{% if is_form %}
	{% if not on_estimate %}
		<table>
		{% if display_order_minimum %}
 			<tr>
				<td><span class="product_affiche_order_min">{{ STR_ORDER_MIN }} {{ qte_value }}</span></td>
			<tr>
		{% endif %}
			<tr>
				<td style="vertical-align:bottom">
					<div class="product_quantity pull-left">
		{% if qte_hidden %}
						<input type="hidden" name="qte" value="{{ qte_value|str_form_value }}" />
		{% else %}
						<label>{{ STR_QUANTITY }}{{ STR_BEFORE_TWO_POINTS }}: </label><input type="text" class="form-control" name="qte" value="{{ qte_value|str_form_value }}" style="width: 100px"/>
		{% endif %}
					</div>
					<div class="product_order pull-right">
		{% if (giftlist) %}
						<input type="hidden" name="listcadeaux_owner" value="{{ giftlist.listcadeaux_owner|str_form_value }}" />
						<input type="hidden" name="id" value="{{ giftlist.id|intval }}" />
						<input type="hidden" id="list_mode" name="mode" value="" />
						{{ giftlist.form }}<br /><br />
		{% endif %}
		{% if (save_cart_id) %}
								<input type="hidden" id="save_cart_id" name="save_cart_id" value="{{ save_cart_id }}" />
		{% endif %}
						<script><!--//--><![CDATA[//><!--
						function verif_form{{ save_suffix_id }}(check_color, check_size) {
							if (check_color == 1 and document.getElementById("couleur{{ save_suffix_id }}").options[document.getElementById("couleur{{ save_suffix_id }}").selectedIndex].value == 0) {
								bootbox.alert("{{ STR_NONE_COLOR_SELECTED|filtre_javascript(true,false,true,false) }}");
								return false;
							} else if (check_size == 1 and document.getElementById("taille{{ save_suffix_id }}").options[document.getElementById("taille{{ save_suffix_id }}").selectedIndex].value == 0) {
								bootbox.alert("{{ STR_NONE_SIZE_SELECTED|filtre_javascript(true,false,true,false) }}");
								return false;
							} else {
								return true;
							}
						}
						//--><!]]></script>
						<input type="submit" class="btn btn-primary" onclick="if (verif_form{{ save_suffix_id }}({{ color_array_result }}, {{ sizes_infos_array_result }}) == true) { {{ anim_prod_var }} } else { return false; }" value="{{ STR_ADD_CART|str_form_value }}" />
					</div>
				</td>
			</tr>
		</table>
	{% endif %}
{% endif %}
{% if is_form %}
	</div>
	{% if (conditionnement) %}
		<input name="conditionnement" type="hidden" value="{{ conditionnement|str_form_value }}" />
	{% endif %}
</form>
{% endif %}