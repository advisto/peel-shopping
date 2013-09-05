{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: caddie_content_html.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}<div class="totalcaddie">
	{% if is_empty %}
	<p>{{ STR_EMPTY_CADDIE }}</p>
	{% else %}
		{% if (global_error) %}
		<div class="global_error">{{ global_error }}</div>
		{% endif %}
		{{ erreur_caddie }}
		<form id="caddieFormArticle" method="post" action="{{ action|escape('html') }}">
			{{ products_summary_table }}
			<table class="code_promo">
				<tr>
					<td colspan="3" style="padding-bottom:15px">
						<input type="hidden" value="recalc" name="func" />
						<a href="javascript:document.getElementById('caddieFormArticle').submit();"{% if (shipping_text) %} onmouseover="return overlib('{{ shipping_text|filtre_javascript(true,true,true) }}');" onmouseout="return nd();"{% endif %}>{{ STR_UPDATE }}</a>
					</td>
				</tr>
				{% if (code_promo) %}
				<tr>
					<td>
						<label for="code_promo">{{ code_promo.txt }}{{ STR_BEFORE_TWO_POINTS }}: </label>
					</td>
					<td>
						<input type="text" id="code_promo" name="code_promo" value="{{ code_promo.value|upper|str_form_value }}" />
					</td>
					<td>
						<input type="submit" value="" name="" class="bouton_ok" />
					</td>
				</tr>
				{% if (code_promo_delete) %}
				<tr><td class="right" colspan="3" style="padding-right:10px"><a href="{{ code_promo_delete.href|escape('html') }}"><img src="{{ code_promo_delete.src|escape('html') }}" /></a> <a href="{{ code_promo_delete.href|escape('html') }}">{{ code_promo_delete.txt }} {{ code_promo.value }}</a></td></tr>
				{% endif %}
				{% else %}
				<tr>
					<td class="caddie_bold" colspan="3">
						<a class="notice" href="{{ membre_href|escape('html') }}" title="{{ STR_LOGIN_FOR_REBATE|str_form_value }}">{{ STR_PLEASE_LOGIN }}</a> {{ STR_REBATE_NOW }}
					</td>
				</tr>
				{% endif %}
			</table>
			<table class="cart_button_and_link">
				<tr>
					<td class="half_expand_in_container">
					{% if is_mode_transport %}
						<table class="livraison">
							<tr>
								<th>{{ STR_DELIVERY }}</th>
							</tr>
							<tr>
								<td id="choix_zone">
									<p class="caddie_bold">{{ STR_SHIPPING_ZONE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: {{ zone_error }}
										<select name="pays_zone" onchange="frmsubmit('{{ STR_REFRESH }}')">
											<option value="">{{ STR_SHIP_ZONE_CHOOSE }}</option>
											{% for zo in zone_options %}
											<option value="{{ zo.value|str_form_value }}"{% if zo.issel %} selected="selected"{% endif %}>{{ zo.name|html_entity_decode_if_needed }}</option>
											{% endfor %}
										</select>
									</p>
									{% if (zone) %}
									<p>{{ STR_SHIPPING_ZONE }}{{ STR_BEFORE_TWO_POINTS }}: {{ zone }}</p>
									{% endif %}
									<p class="caddie_bold">
										{% if is_zone %}
											{% if (shipping_type_options) %}
												{{ STR_SHIPPING_TYPE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: {{ shipping_type_error }}
												<select name="type" onchange="frmsubmit('{{ STR_REFRESH }}')">
													<option value="">{{ STR_SHIP_TYPE_CHOOSE }}</option>
													{% for sto in shipping_type_options %}
													<option value="{{ sto.value|str_form_value }}"{% if sto.issel %} selected="selected"{% endif %}>{{ sto.name|html_entity_decode_if_needed }}</option>
													{% endfor %}
												</select>
											{% else %}
												<span style="color:red;">{{ STR_ERREUR_TYPE }}</span><br />
											{% endif %}
										{% endif %}
									</p>
								</td>
							</tr>
						</table>
					{% endif %}
					</td>
					<td class="bottom">
						<table class="table_order">
							<tr>
								<td colspan="2">
									{% if (STR_SUGGEST) %}
									<div class="center"><p>{{ STR_SUGGEST }}</p><input type="hidden" value="" name="func" /></div>
									{% endif %}
									{% if is_minimum_error %}
										<p class="center">
											{{ STR_MINIMUM_PURCHASE_OF }}{{ minimum_prix }}{{ STR_REQUIRED_VALIDATE_ORDER }}
										</p>
									{% else %}
										<p class="center">
											<input type="submit" class="bouton_order" value="{{ STR_ORDER|str_form_value }}" name="func" {% if (shipping_text) %} onmouseover="return overlib('{{ shipping_text|filtre_javascript(true,true,true) }}');" onmouseout="return nd();"{% endif %} />
										</p>
									{% endif %}
								</td>
							</tr>
						{% if is_cart_preservation_module_active %}
							<tr>
								<td colspan="2">
									<a class="cart_preservation_link" href="{{ preservation_href|escape('html') }}" >{{ STR_SAVE_CART }}</a>
								</td>
							</tr>
						{% endif %}
							<tr>
								<td class="td_caddie_link_shopping">
									<a href="{{ shopping_href|escape('html') }}" class="caddie_link">{{ STR_SHOPPING }}</a>
								</td>
								<td class="td_caddie_link_empty_cart">
									<a href="{{ empty_list_href|escape('html') }}" onclick="return confirm('{{ STR_EMPTY_CART|filtre_javascript(true,true,true) }}');" class="caddie_link">{{ STR_EMPTY_LIST }}</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>
	{% endif %}
</div>