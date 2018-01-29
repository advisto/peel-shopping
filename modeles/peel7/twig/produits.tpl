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
// Id: produits.tpl 47083 2015-10-01 10:18:12Z sdelaporte 
#}{% if is_associated_product %}
	{% if associated_product_multiple_add_to_cart and prods_line_mode %} 
		<form action="{{ wwwroot }}/achat/caddie_ajout.php?multiprodid=true" method="post" enctype="multipart/form-data" role="form"> 
	{% endif %}
	{% if associated_product_multiple_add_to_cart is empty and prods_line_mode %} 
		<hr />
	{% endif %}
	<div class="associated_product list-group">
{% endif %}
{% if (titre_mode) and (titre) %}
	{% if  titre_mode == 'associated' %}
		<h{{ title_level }} class="other_product_buy_title">{{ titre }}</h{{ title_level }}>
	{% elseif titre_mode == 'home' %}
		<h{{ title_level }} class="home_title">{{ titre }}</h{{ title_level }}>
	{% elseif titre_mode == 'category' %}
		<h{{ title_level }} class="products_title">{{ titre }}</h{{ title_level }}>
	{% elseif titre_mode == 'default' %}
		<h{{ title_level }} class="products_title">{{ titre }}</h{{ title_level }}>
	{% endif %}
{% endif %}
{% if  (filtre) %}<div class="pull-right">{{ filtre }}</div><div class="clearfix"></div>{% endif %}
{% if  no_results %}{% if (no_results_msg) %}<p>{{ no_results_msg }}</p>{% endif %}
{% else %}
	<div class="produits row {% if  allow_order %}allow_order{% endif %}">
	{% for prod in products %}
			{% if  prods_line_mode %}
		<div class="col-sm-12">
			<div{% if  (titre_mode) and titre_mode == 'associated' %} property="isRelatedTo"{% endif %} typeof="product" class="center{% if prod.display_border %} bordure{% endif %}{% if is_associated_product %} list-group-item{% endif %}">
			{% else %}
		<div>
			<div{% if  (titre_mode) and titre_mode == 'associated' %} property="isRelatedTo"{% endif %} typeof="product" class="produit_col{% if prod.display_border %} bordure{% endif %} col-sm-{{ (12 // nb_col_sm) }} col-md-{{ (12 // nb_col_md) }} center">
			{% endif %}
			{% if  (prod.save_cart) %}
				<div class="save_cart_individual_action">
					<img src="{{ prod.save_cart.src|escape('html') }}" width="8" height="11" alt="" />
					<a href="{{ prod.save_cart.href|escape('html') }}" data-confirm="{{ prod.save_cart.confirm_msg|str_form_value }}" title="{{ prod.save_cart.title }}">{{ prod.save_cart.label }}</a>
				</div>
			{% endif %}
			{% if  prods_line_mode %}
				<div class="line-item">
				{% if (prod.flash) %}
					<div class="col_flash">
						{{ prod.flash }}
					</div>
				{% endif %}
			{% if prod.gallery_button is defined %}
					<div class="col_gallery_button">
						{{ prod.gallery_button }}
					</div>
				{% endif %}
					<div class="row">
						<div class="col_image col-md-2">
							{% if (prod.image) %}<a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><img property="image" src="{{ prod.image.src|escape('html') }}"{% if  prod.image.width %} width="{{ prod.image.width }}"{% endif %}{% if  prod.image.height %} height="{{ prod.image.height }}"{% endif %} alt="{{ prod.image.alt|str_form_value }}" /></a>{% endif %}
						</div>
						<div class="col_product_description col-md-{% if (prod.check_critere_stock) %}6{% else %}8{% endif %}">
							<div class="fc_titre_produit"><a property="url" href="{{ prod.href|escape('html') }}" title="{{ prod.name|str_form_value }}"><span property="name">{{ prod.name }}</span></a></div>
							{% if product_description_catalogue_disabled is empty %}
							<div><p><a href="{{ prod.href|escape('html') }}" class="col_description">{{ prod.description }}</a></p></div>
							{% endif %}
						</div>
						<div class="col_zoom col-md-2">
				{% if (prod.image.zoom) %}
					{% if  prod.image.zoom.is_lightbox %}
							<a href="{{ prod.image.zoom.href|escape('html') }}" class="lightbox" onclick="return false;" title="{{ prod.name|str_form_value }}">{{ prod.image.zoom.label }}</a>
					{% elseif (prod.image.zoom.is_pdf) %}
							<a href="{{ prod.image.zoom.href|escape('html') }}" onclick="return(window.open(this.href)?false:true);" title="{{ prod.name|str_form_value }}">{{ prod.image.zoom.label }}</a>
					{% endif %}
				{% endif %}
							<br />
							<p class="col_detail"><a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}">{{ details_text }}</a></p>
				{% if (prod.stock_state) %}
					{{ prod.stock_state }}
				{% endif %}
						</div>
						<div class="fc_add_to_cart col-md-2">
			{% if prod.departements_get_bootbox_dialog is defined %}
				{{ prod.departements_get_bootbox_dialog }}
			{% else %}
				{% if is_associated_product and associated_product_multiple_add_to_cart %}
						<div>
							<input min="{% if prod.quantity %}{{ prod.quantity }} {% endif %}" type="number" style="width: 100px" value="{% if prod.quantity %}{{ prod.quantity }}{% endif %}" name="qte[]" class="form-control" style="display:inline;">
							<input type="hidden" name="produit_id[]" value="{{ prod.id }}" />
						</div>
				{% elseif prod.check_critere_stock is defined %}
							<!-- Ajout au panier -->
							{{ prod.check_critere_stock }}
				{% else %}
						{% if prod.check_critere_stock is empty %}<div class="fc_prix">{% if prod.on_estimate %}{{ prod.on_estimate }}{% else %}<span class="prix">&nbsp;</span>{% endif %}</div>{% endif %}
				{% endif %}
			{% endif %}
				
						</div>
					</div>
				{% if (prod.admin) %}
					<div>
						<a href="{{ prod.admin.href|escape('html') }}" class="title_label">{{ prod.admin.label }}</a>
					</div>
				{% endif %}
			{% if prod.modify_product_by_owner is defined %}
					<div>
						<a href="{{ prod.modify_product_by_owner.href|escape('html') }}" class="title_label">{{ prod.modify_product_by_owner.label }}</a>
					</div>
				{% endif %}
				</div>
			{% else %}
				<table class="full-width {{ cartridge_product_css_class }}">
					<tr>
						<td class="fc_titre_produit">
							<a property="url" title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><span property="name">{{ prod.name }}</span></a>
						</td>
					</tr>
					<tr>
						<td class="fc_image center middle" style="width:{{ small_width }}px; height:{{ small_height }}px;">
							{% if prod.thumbnail_promotion %}
							<div class="produit_thumbnail_promotion"><span>-{{ prod.promotion }}</span></div>
							{% endif %}
							<span class="image_zoom">
							{% if  (prod.image) %}
								<a title="{{ prod.name|str_form_value }}" href="{{ prod.href|escape('html') }}"><img property="image" src="{{ prod.image.src|escape('html') }}"{% if  prod.image.width %} width="{{ prod.image.width }}"{% endif %}{% if  prod.image.height %} height="{{ prod.image.height }}"{% endif %} alt="{{ prod.image.alt|str_form_value }}" /></a>
								{% if  (prod.image.zoom) %}
									{% if  prod.image.zoom.is_lightbox %}
								<span class="fc_zoom"><a href="{{ prod.image.zoom.href|escape('html') }}" class="lightbox" onclick="return false;" title="{{ prod.name|str_form_value }}"><span class="glyphicon glyphicon-fullscreen"></span></a></span>
									{% elseif prod.image.zoom.file_type and prod.image.zoom.file_type!='image' %}
								<span class="fc_zoom"><a href="{{ prod.image.zoom.href|escape('html') }}" onclick="return(window.open(this.href)?false:true);" title="{{ prod.name|str_form_value }}"><span class="glyphicon glyphicon-fullscreen"></span></a></span>
									{% endif %}
								{% endif %}
							{% endif %}
							</span>
						</td>
					</tr>
					<tr>
						<td>
							{% if  (prod.description) and product_description_catalogue_disabled is empty %}
							<div class="description_text"><a href="{{ prod.href|escape('html') }}">{{ prod.description }}</a></div>
							{% endif %}
							{% if (prod.flash) %}
							<div class="alert alert-warning">{{ prod.flash }}</div>
							{% endif %}
							<div class="fc_prix">{% if  (prod.on_estimate) %}{{ prod.on_estimate }}{% else %}<span class="prix">&nbsp;</span>{% endif %}</div>
						</td>
					</tr>
				{% if prod.departements_get_bootbox_dialog is defined %}
					<tr>
						<td class="fc_add_to_cart">
							{{ prod.departements_get_bootbox_dialog }}
						</td>
					</tr>
				{% else %}	
					{% if (prod.check_critere_stock) %}
						<tr>
							<td class="fc_add_to_cart">
								<!-- Ajout au panier -->
								{{ prod.check_critere_stock }}
							</td>
						</tr>
					{% endif %}
				{% endif %}
				
				{% if prod.product_list_html_zone %}
					<tr>
						<td>
							{{ prod.product_list_html_zone }}
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
		</div>
		{% endfor %}
		{% if (total) %}
		<div class="col-sm-12">
			<span class="prix">{{ STR_TOTAL }}{{ STR_BEFORE_TWO_POINTS }}: {{ total }}</span>
		</div>
		{% endif %}
	</div>
	<div class="clearfix"></div>
	<div class="center">{{ multipage }}</div>
{% endif %}
{% if is_associated_product %}
</div>
{% endif %}
{% if is_associated_product %}
</div>
	{% if associated_product_multiple_add_to_cart and prods_line_mode %} 
	<input class="btn btn-primary" type="submit" value="{{ STR_ADD_CART }}" />
	</form> 
	{% endif %}
{% endif %}