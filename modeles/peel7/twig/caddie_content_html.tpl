{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: caddie_content_html.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}<div class="totalcaddie">
	{% if is_empty %}
	<p>{{ STR_EMPTY_CADDIE }}</p>
	{% else %}
		{% if (global_error) %}
		<div class="alert alert-danger">{{ global_error }}</div>
		{% endif %}
		{{ erreur_caddie }}
		{% if (zone_error) %}{{ zone_error }}{% endif %}
		{% if (shipping_type_error) %}{{ shipping_type_error }}{% endif %}
		<form class="entryform form-inline" role="form" id="caddieFormArticle" method="post" action="{{ action|escape('html') }}">
			<input type="hidden" value="commande" name="func" id="form_mode" />
			<div class="row">
				{{ products_summary_table }}
				<div class="col-sm-6">
					<div class="code_promo">
					{% if (code_promo) %}
						<div class="input-group">
							<span class="input-group-addon"><label for="code_promo">{{ code_promo.txt }}{{ STR_BEFORE_TWO_POINTS }}: </label></span>
							<input type="text" class="form-control" id="code_promo" name="code_promo" value="{{ code_promo.value|upper|str_form_value }}" />
							<span class="input-group-addon"><a href="#" onclick="return frmsubmit('recalc')"><span class="glyphicon glyphicon-refresh"></span></a></span>
						</div>
						{% if (code_promo_delete) %}
						<div><a href="{{ code_promo_delete.href|escape('html') }}"><img src="{{ code_promo_delete.src|escape('html') }}" alt="x" /></a> <a href="{{ code_promo_delete.href|escape('html') }}">{{ code_promo_delete.txt }} {{ code_promo.value }}</a></div>
						{% endif %}
					{% else %}
						<div class="caddie_bold">
							<a class="notice" href="{{ membre_href|escape('html') }}" title="{{ STR_LOGIN_FOR_REBATE|str_form_value }}">{{ STR_PLEASE_LOGIN }}</a> {{ STR_REBATE_NOW }}
						</div>
					{% endif %}
						<div style="padding-top:15px; padding-bottom:15px">
							<a href="#" onclick="return frmsubmit('recalc')"{% if (shipping_text) %} data-toggle="tooltip" title="{{ shipping_text|str_form_value }}"{% endif %} class="tooltip_link btn btn-success"><span class="glyphicon glyphicon-refresh"></span> {{ STR_UPDATE }}</a>
						</div>
					</div>
				{% if is_mode_transport %}
					<div class="livraison well">
						<fieldset>
							<legend>{{ STR_DELIVERY }}</legend>
							<div id="choix_zone">
								<p class="caddie_bold">{{ STR_SHIPPING_ZONE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}: {{ zone_error }}
									<select class="form-control" name="pays_zone" onchange="return frmsubmit('recalc')">
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
											<select class="form-control" name="type" onchange="return frmsubmit('recalc')">
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
							</div>
						</fieldset>
					</div>
				{% endif %}
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 center">
					<table class="table_order">
						<tr>
							<td colspan="2">
								{% if (STR_SUGGEST) %}
								<div class="center"><p>{{ STR_SUGGEST }}</p></div>
								{% endif %}
								{% if is_minimum_error %}
									<p class="center">
										{{ STR_MINIMUM_PURCHASE_OF }}{{ minimum_prix }}{{ STR_REQUIRED_VALIDATE_ORDER }}
									</p>
								{% else %}
									<p class="center">
										<button type="submit" class="tooltip_link btn btn-lg btn-primary"{% if (shipping_text) %} data-toggle="tooltip" title="{{ shipping_text|str_form_value }}"{% endif %} onclick="return frmsubmit('commande')">{{ STR_ORDER }} <span class="glyphicon glyphicon-chevron-right"></span></button>
									</p>
								{% endif %}
							</td>
						</tr>
					{% if is_cart_preservation_module_active %}
						<tr>
							<td colspan="2">
								<a class="cart_preservation_link btn btn-info" href="{{ preservation_href|escape('html') }}" ><span class="glyphicon glyphicon-save"></span> {{ STR_SAVE_CART }}</a>
							</td>
						</tr>
					{% endif %}
						<tr>
							<td class="td_caddie_link_shopping">
								<a href="{{ shopping_href|escape('html') }}" class="caddie_link btn btn-success"><span class="glyphicon glyphicon-chevron-left"></span> {{ STR_SHOPPING }}</a>
							</td>
							<td class="td_caddie_link_empty_cart">
								<a href="{{ empty_list_href|escape('html') }}" data-confirm="{{ STR_EMPTY_CART|str_form_value }}" class="caddie_link btn btn-warning"><span class="glyphicon glyphicon-remove"></span> {{ STR_EMPTY_LIST }}</a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</form>
	{% endif %}
</div>