{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produits.tpl 39162 2013-12-04 10:37:44Z gboussin $
#}{% if is_associated_product %}
	<div class="associated_product">
{% endif %}
{% if (titre_mode) %}
	{% if titre_mode == 'associated' %}
		<h{{ title_level }} class="other_product_buy_title">{{ titre }}</h{{ title_level }}>
	{% elseif titre_mode == 'home' %}
		<h{{ title_level }} class="home_title">{{ titre }}</h{{ title_level }}>
	{% elseif titre_mode == 'category' %}
		<h{{ title_level }} class="products_title">{{ titre }}</h{{ title_level }}><div class="pull-right">{{ filtre }}</div><div class="clearfix"></div>
	{% elseif titre_mode == 'default' %}
		<h{{ title_level }} class="products_title">{{ titre }}</h{{ title_level }}>
	{% endif %}
{% endif %}
{% if no_results %}{% if (no_results_msg) %}<p>{{ no_results_msg }}</p>{% endif %}
{% else %}
	<div class="produits row {% if allow_order %}allow_order{% endif %}">
	{% for prod in products %}
		{% if prods_line_mode %}
		<div{% if (titre_mode) and titre_mode == 'associated' %} property="isRelatedTo"{% endif %} typeof="product" class="{% if prod.display_border %} bordure{% endif %} col-sm-12 center">
		{% else %}
		<div{% if (titre_mode) and titre_mode == 'associated' %} property="isRelatedTo"{% endif %} typeof="product" class="produit_col{% if prod.display_border %} bordure{% endif %} col-sm-{{ (12 // nb_col_sm) }} col-md-{{ (12 // nb_col_md) }} center">
		{% endif %}
		{% if (prod.save_cart) %}
				<div class="save_cart_individual_action">
					<img src="{{ prod.save_cart.src|escape('html') }}" width="8" height="11" alt="" />
					<a href="{{ prod.save_cart.href|escape('html') }}" data-confirm="{{ prod.save_cart.confirm_msg|str_form_value }}" title="{{ prod.save_cart.title }}">{{ prod.save_cart.label }}</a>
				</div>
		{% endif %}
		{% if prods_line_mode %}
				<table class="line-item">
			{% if (prod.flash) %}
					<tr>
						<td colspan="6" class="col_flash">
							{{ prod.flash }}
						</td>
					</tr>
			{% endif %}
					<tr>
						<td class="col_image">
							<a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><img property="image" src="{{ prod.image.src|escape('html') }}"{% if prod.image.width %} width="{{ prod.image.width|str_form_value }}"{% endif %}{% if prod.image.height %} height="{{ prod.image.height|str_form_value }}"{% endif %} alt="{{ prod.image.alt|str_form_value }}" /></a>
						</td>
						<td class="col_product_description">
							<table>
								<tr>
									<td class="fc_titre_produit"><a property="url" href="{{ prod.href|escape('html') }}" title="{{ prod.name|str_form_value }}"><span property="name">{{ prod.name }}</span></a></td>
								</tr>
								<tr>
									<td><p><a href="{{ prod.href|escape('html') }}" class="col_description">{{ prod.description }}</a></p></td>
								</tr>
							</table>
						</td>
						<td style="text-align:center; width:22%;">
			{% if (prod.on_estimate) %}
							{{ prod.on_estimate }}
			{% endif %}
						</td>
						<td class="col_zoom" style="width:10%;">
			{% if (prod.image.zoom) %}
							<a href="{{ prod.image.zoom.href|escape('html') }}" {% if prod.image.zoom.is_lightbox %}class="lightbox" onclick="return false;"{% else %}onclick="return(window.open(this.href)?false:true);"{% endif %} title="{{ prod.name|str_form_value }}">{{ prod.image.zoom.label }}</a>
			{% endif %} <br />
							<p class="col_detail"><a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}">{{ details_text }}</a></p>
			{% if (prod.stock_state) %}
							{{ prod.stock_state }}
			{% endif %}
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
						<td colspan="6"><a href="{{ prod.admin.href|escape('html') }}" class="title_label">{{ prod.admin.label }}</a></td>
					</tr>
			{% endif %}
				</table><hr />
		{% else %}
				<table class="{{ cartridge_product_css_class }}">
					<tr>
						<td class="fc_titre_produit">
							<a property="url" title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><span property="name">{{ prod.name }}</span></a>
						</td>
					</tr>
					<tr>
						<td class="fc_image center middle" style="width:{{ small_width }}px; height:{{ small_height }}px;">
							<span class="image_zoom">
								<a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><img property="image" src="{{ prod.image.src|escape('html') }}"{% if prod.image.width %} width="{{ prod.image.width }}"{% endif %}{% if prod.image.height %} height="{{ prod.image.height }}"{% endif %} alt="{{ prod.image.alt }}" /></a>
								{% if (prod.image.zoom) %}<span class="fc_zoom"><a href="{{ prod.image.zoom.href|escape('html') }}" {% if prod.image.zoom.is_lightbox %}class="lightbox" onclick="return false;"{% else %}onclick="return(window.open(this.href)?false:true);"{% endif %} title="{{ prod.name|str_form_value }}"><span class="glyphicon glyphicon-fullscreen"></span></a></span>{% endif %}
							</span>
						</td>
					</tr>
					<tr>
						<td>
							{% if (prod.description) %}
							<div class="description_text"><a href="{{ prod.href|escape('html') }}">{{ prod.description }}</a></div>
							{% endif %}
							{% if (prod.flash) %}
							<div class="alert alert-warning">{{ prod.flash }}</div>
							{% endif %}
							{% if (prod.on_estimate) %}<div class="fc_prix">{{ prod.on_estimate }}</div>{% endif %}							
						</td>
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
		</div>
		{% if prod.i%nb_col_md==0 %}
		<div class="clearfix visible-md visible-lg"></div>
		{% endif %}
		{% if prod.i%nb_col_sm==0 %}
		<div class="clearfix visible-sm"></div>
		{% endif %}
	{% endfor %}
	</div>
	<div class="clearfix"></div>
	<div class="center">{{ multipage }}</div>
{% endif %}
{% if is_associated_product %}
</div>
{% endif %}