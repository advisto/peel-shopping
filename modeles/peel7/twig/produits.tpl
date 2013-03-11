{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produits.tpl 35112 2013-02-11 11:09:34Z gboussin $
#}{% if is_associated_product %}
	<div class="associated_product">
{% endif %}
{% if (titre_mode) %}
	{% if titre_mode == 'associated' %}
		<h3 class="other_product_buy_title">{{ titre }}</h3>
	{% elseif titre_mode == 'home' %}
		<h2 class="home_title">{{ titre }}</h2>
	{% elseif titre_mode == 'category' %}
		<table class="product_title"><tr><td><h2>{{ titre }}</h2></td><td class="right" style="padding-right: 10px;">{{ filtre }}</td></tr></table>
	{% elseif titre_mode == 'default' %}
		<h2>{{ titre }}</h2>
	{% endif %}
{% endif %}
{% if no_results %}{% if (no_results_msg) %}<p>{{ no_results_msg }}</p>{% endif %}
{% else %}
	<table class="produits {% if allow_order %}allow_order{% endif %}">
	{% for prod in products %}
		{% if prods_line_mode %}
		<tr>
			<td{% if (titre_mode) and titre_mode == 'associated' %} property="isRelatedTo"{% endif %} typeof="product">
		{% else %}
			{% if prod.is_row %}
		<tr>
			{% endif %}
			<td{% if (titre_mode) and titre_mode == 'associated' %} property="isRelatedTo"{% endif %} typeof="product" class="produit_col{% if prod.display_border %} bordure{% endif %}">
		{% endif %}
		{% if (prod.save_cart) %}
				<div class="save_cart_individual_action">
					<img src="{{ prod.save_cart.src|escape('html') }}" width="8" height="11" alt="" />
					<a href="{{ prod.save_cart.href|escape('html') }}" onclick="return confirm('{{ prod.save_cart.confirm_msg|filtre_javascript(true,true,true) }}');" title="{{ prod.save_cart.title }}">{{ prod.save_cart.label }}</a>
				</div>
		{% endif %}
		{% if prods_line_mode %}
				<table>
			{% if (prod.flash) %}
					<tr>
						<td colspan="6" class="col_flash">
							{{ prod.flash }}
						</td>
					</tr>
			{% endif %}
					<tr>
						<td class="col_image" style="width:10%;">
							<a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><img property="image" src="{{ prod.image.src|escape('html') }}"{% if prod.image.width %} width="{{ prod.image.width }}"{% endif %}{% if prod.image.height %} height="{{ prod.image.height }}"{% endif %} alt="{{ prod.image.alt }}" /></a>
						</td>
						<td style="width:45%;">
							<a property="url" href="{{ prod.href|escape('html') }}" title="{{ prod.name|str_form_value }}"><span property="name">{{ prod.name }}</span></a>
						</td>
						<td style="text-align:center; width:12%;">
			{% if (prod.on_estimate) %}
							{{ prod.on_estimate }}
			{% endif %}
						</td>
						<td style="text-align:center; width:10%;">
			{% if (prod.stock_state) %}
								{{ prod.stock_state }}
			{% endif %}
						</td>
						<td class="col_zoom" style="width:10%;">
			{% if (prod.image.zoom) %}
							<a href="{{ prod.image.zoom.href|escape('html') }}" {% if prod.image.zoom.is_lightbox %}class="lightbox"{% else %}onclick="return(window.open(this.href)?false:true);"{% endif %} title="{{ prod.name|str_form_value }}">{{ prod.image.zoom.label }}</a>
			{% endif %} <br />
							<p class="col_detail"><a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}">{{ details_text }}</a></p>
						</td>
			{% if (prod.check_critere_stock) %}
						<td class="fc_add_to_cart">
						<!-- Ajout au panier -->
							{{ prod.check_critere_stock }}
						</td>
			{% endif %}
					</tr>
			{% if (prod.admin) %}
					<tr>
						<td colspan="6"><a href="{{ prod.admin.href|escape('html') }}" class="label">{{ prod.admin.label }}</a></td>
					</tr>
			{% endif %}
				</table><hr />
		{% else %}
				<table class="{{ cartridge_product_css_class }}">
			{% if (prod.flash) %}
					<tr>
						<td colspan="2" class="fc_flash">{{ prod.flash }}</td>
					</tr>
			{% endif %}
					<tr>
						<td colspan="2" class="fc_titre_produit">
							<a property="url" title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><span property="name">{{ prod.name }}</span></a>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="fc_image center middle" style="width:{{ small_width }}px; height:{{ small_height }}px;">
							<a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><img property="image" src="{{ prod.image.src|escape('html') }}"{% if prod.image.width %} width="{{ prod.image.width }}"{% endif %}{% if prod.image.height %} height="{{ prod.image.height }}"{% endif %} alt="{{ prod.image.alt }}" /></a>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="fc_prix">
							{% if (prod.on_estimate) %}
							{{ prod.on_estimate }}
							{% endif %}
						</td>
					</tr>
					<tr>
						<td class="fc_zoom">
							{% if (prod.image.zoom) %}
							<a href="{{ prod.image.zoom.href|escape('html') }}" {% if prod.image.zoom.is_lightbox %}class="lightbox"{% else %}onclick="return(window.open(this.href)?false:true);"{% endif %} title="{{ prod.name|str_form_value }}">{{ prod.image.zoom.label }}</a>
							{% endif %}
						</td>
						<td class="fc_detail"><a class="plus_detail" href="{{ prod.href|escape('html') }}" title="{{ prod.name|str_form_value }}">{{ details_text }}</a></td>
					</tr>
			{% if (prod.check_critere_stock) %}
					<tr>
						<td colspan="2" class="fc_add_to_cart">
							<!-- Ajout au panier -->
							{{ prod.check_critere_stock }}
						</td>
					</tr>
			{% endif %}
				</table>
		{% endif %}
			</td>
		{% if (prod.empty_cells) %}
			{% for var in 1..prod.empty_cells %}
			<td></td>
			{% endfor %}
		</tr>
		{% endif %}
	{% endfor %}
		<tr>
			<td class="center" colspan="{{ n_columns }}">{{ multipage }}</td>
		</tr>
	</table>
{% endif %}
{% if is_associated_product %}
</div>
{% endif %}
