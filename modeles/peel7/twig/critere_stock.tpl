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
// $Id: critere_stock.tpl 35130 2013-02-11 16:44:03Z sdelaporte $
#}{% if is_form %}
<form enctype="multipart/form-data" method="post" action="{{ action|escape('html') }}" id="{{ form_id }}">
	<div class="affiche_critere_stock {{ update_class }}">
	{% if not condensed_display_mode %}
		{% if is_color %}
		<table>
			<tr>
				<td class="attribut-color">
					<label>{{ STR_COLOR }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				</td>
				<td>
					<select name="couleur" id="{{ id_select_color }}" onchange="{{ color_on_change_action }}">
						<option value="0">{{ STR_CHOOSE_COLOR }}</option>
						{% for c in colors %}
							<option value="{{ c.id|str_form_value }}"{% if c.issel %} selected="selected"{% endif %}{% if not c.isavailable %} disabled="disabled"{% endif %}>{{ c.name }}{% if not c.isavailable %} - {{ STR_NO_AVAILABLE }}{% endif %}</option>
						{% endfor %}
					</select>
				</td>
			</tr>
		</table>
		{% endif %}
		{% if is_sizes %}
		<table>
			<tr>
				<td class="attribut-cell">
					<label>{{ STR_SIZE }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				</td>
				<td>
					<select id="{{ id_select_size }}" name="taille" onchange="update_product_price{{ save_suffix_id }}()">
						<option value="0">{{ STR_CHOOSE_SIZE }}</option>
						{% for so in sizes_options %}
							<option value="{{ so.id|intval }}"{% if so.issel %} selected="selected"{% endif %}{% if not so.isavailable %} disabled="disabled"{% endif %}>
							{{ so.name }}{{ so.suffix }}
							</option>
						{% endfor %}
					</select>
				</td>
			</tr>
		</table>
		{% endif %}
	{% else %}
		<p class="retour">
			<select name="critere" id="critere" onchange="document.location='{{ urlprod_with_cid }}'+getElementById('critere').value.split('|')[0]+'&amp;liste='+getElementById('critere').value;">
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
		<table class="full_expand_in_container">
			<tr>
				<td style="padding-right: 10px; width: 260px;">
{% if is_form %}
	{% if (affiche_attributs_form_part) %}{{ affiche_attributs_form_part }}{% endif %}
	{% if (affiche_etat_stock) %}{{ affiche_etat_stock }}{% endif %}
	{% if (stock_remain_all) %}
					<p class="label">{{ STR_STOCK_ATTRIBUTS }}{{ STR_BEFORE_TWO_POINTS }}: {{ stock_remain_all }}</p>
	{% endif %}
{% endif %}
{% if (delai_stock) %}
					<p class="label">{{ STR_DELIVERY_STOCK }}{{ STR_BEFORE_TWO_POINTS }}: {{ delai_stock }}</p>
{% endif %}
					<div property="offers" typeof="Offer" class="product_affiche_prix">{{ product_affiche_prix }}</div>
				</td>
			</tr>
{% if (etat_stock) %}
			<tr>
				<td>{{ etat_stock }}</td>
			</tr>
{% else %}
	{% if (formulaire_alerte) %}
			<tr>
				<td>
					{{ formulaire_alerte }}
				</td>
			</tr>
	{% endif %}
{% endif %}
{% if is_form %}
	{% if on_estimate %}
			<tr>
				<td>
					<table class="full_width">
						<tr>
							<td style="padding-right:10px">
								<div class="product_quantity">
		{% if qte_hidden %}
									<input type="hidden" name="qte" value="{{ qte_value|str_form_value }}" />
		{% else %}
									<span>{{ STR_QUANTITY }}{{ STR_BEFORE_TWO_POINTS }}: </span><input type="text" size="3" name="qte" value="{{ qte_value|str_form_value }}" />
		{% endif %}
								</div>
							</td>
							<td class="right">
		{% if (giftlist) %}
								<input type="hidden" name="listcadeaux_owner" value="{{ giftlist.listcadeaux_owner|str_form_value }}" />
								<input type="hidden" name="id" value="{{ giftlist.id|intval }}" />
								<input type="hidden" id="list_mode" name="mode" value="" />
								{{ giftlist.form }}<br /><br />
		{% endif %}
								<script><!--//--><![CDATA[//><!--
								function verif_form{{ save_suffix_id }}(check_color, check_size) {ldelim }}
									if (check_color == 1 && document.getElementById("couleur{{ save_suffix_id }}").options[document.getElementById("couleur{{ save_suffix_id }}").selectedIndex].value == 0) {ldelim }}
										alert("{{ STR_NONE_COLOR_SELECTED|filtre_javascript(true,false,true) }}");
										return false;
									{rdelim }} else if (check_size == 1 && document.getElementById("taille{{ save_suffix_id }}").options[document.getElementById("taille{{ save_suffix_id }}").selectedIndex].value == 0) {ldelim }}
										alert("{{ STR_NONE_SIZE_SELECTED|filtre_javascript(true,false,true) }}");
										return false;
									{rdelim }} else {ldelim }}
										return true;
									{rdelim }}
								{rdelim }}
								//--><!]]></script>
								<input type="submit" class="bouton_add_cart" onclick="if (verif_form{{ save_suffix_id }}({{ color_array_result }}, {{ sizes_infos_array_result }}) == true) {ldelim }}{{ anim_prod_var }}{rdelim }} else {ldelim }} return false; {rdelim }}" value="{{ STR_ADD_CART|str_form_value }}" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
	{% endif %}
{% endif %}
		</table>
{% if is_form %}
	</div>
	{% if (conditionnement) %}
		<input name="conditionnement" type="hidden" value="{{ conditionnement|str_form_value }}" />
	{% endif %}
</form>
{% endif %}