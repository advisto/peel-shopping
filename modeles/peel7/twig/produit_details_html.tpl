{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: produit_details_html.tpl 35321 2013-02-16 14:48:56Z gboussin $
#}
<div typeof="Product">
	<table class="product_title">
	{% if (global_error) %}
		<div class="global_error">
			{{ global_error.txt }}
			{% if global_error.date %}<span>{{ global_error.date }}</span>{% endif %}
		</div>
	{% endif %}
	<tr>
		{{ prev }}
		<td class="title-details-product"><h2 property="name">{{ product_name }}</h2></td>
		<td class="title-details-price">
			{% if title_price.txt %}
				<span class="title_price_free">{{ title_price.txt }}</span>
			{% else %}
				{{ title_price.value }}
			{% endif %}
		</td>
		{{ next }}
	</tr>
	</table>
	{% if (flash_txt) %}
	<table>
		<tr>
			<td class="fp_flash">{{ flash_txt }}</td>
		</tr>
	</table>
	{% endif %}
	{% if (admin) %}
		<p class="center"><a href="{{ admin.href|escape('html') }}" class="label">{{ admin.modify_txt }}</a></p>
		{% if admin.is_offline %}
			<p style="color: red;">{{ admin.offline_txt }}</p>
		{% endif %}
	{% endif %}
	<table class="fp">
		<tr>
			<td class="top">
				<div class="fp_produit">
					<div class="fp_image_grande">
						<div class="image_grande" id="slidingProduct{{ product_id }}">
							{% if (main_image) %}
								{% if main_image.is_pdf %}
									<a href="{{ main_image.href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ wwwroot }}/images/logoPDF_small.png" alt="{{ product_name }}" width="{{ medium_width }}" height="{{ medium_height }}" /></a>
								{% else %}
									<a id="zoom1" {{ a_zoom_attributes }} href="{{ main_image.href|escape('html') }}" title="{{ product_name }}"><img property="image" id="mainProductImage" class="zoom" src="{{ main_image.src|escape('html') }}" alt="{{ product_name }}" /></a>
								{% endif %}
							{% else %}
								<a href="{{ product_href|escape('html') }}"><img src="{{ no_photo_src }}" alt="{{ photo_not_available_alt }}" /></a>
							{% endif %}
						</div>
						{% if (product_images) %}
							<ul id="files">
								{% for img in product_images %}
									{% if img.is_pdf %}
										<li>
											<a href="{{ img.href|escape('html') }}" onclick="return(window.open(this.href)?false:true);"><img src="{{ wwwroot }}/images/logoPDF_small.png" width="50" alt="{{ product_name }}" /></a>
										</li>
									{% else %}
										<li id="{{ img.id }}">
											<a {{ img.a_attr }} title="{{ product_name }}"><img src="{{ img.src|escape('html') }}" alt="{{ product_name }}" width="50" /></a>
										</li>
									{% endif %}
								{% endfor %}
							</ul>
						{% endif %}
						<br />
						<table id="product_link_to_modules_container">
							<tr>
								<td>
									<!-- dire à un ami, avis des internautes -->
									{% if (tell_friends) %}
									<table class="product_link_to_modules">
										<tr class="picto-tell_friends">
											<td class="img-tell_friends">
												<a href="{{ tell_friends.href|escape('html') }}" class="partage"><img src="{{ tell_friends.src|escape('html') }}" alt="{{ tell_friends.txt }}" /></a>
											</td>
											<td class="txt-tell_friends">
												<a href="{{ tell_friends.href|escape('html') }}" class="label partage">{{ tell_friends.txt }}</a>
											</td>
										</tr>
									</table>
									{% endif %}
									{% if (avis) %}
									<table class="product_link_to_modules">
										<tr class="picto-avis">
											<td class="img-avis">
												<a href="{{ avis.href|escape('html') }}"><img src="{{ avis.src|escape('html') }}" alt="{{ avis.txt }}" /></a>
											</td>
											<td class="txt-avis">
												<a href="{{ avis.href|escape('html') }}" class="label partage">{{ avis.txt }}</a>
											</td>
										</tr>
									</table>
									{% endif %}
								</td>
								<td>
									{% if (tous_avis) %}
									<table class="product_link_to_modules">
										<tr class="picto-tous_avis">
											<td class="img-tous_avis">
												<a href="{{ tous_avis.href|escape('html') }}"><img src="{{ tous_avis.src|escape('html') }}" alt="{{ tous_avis.txt }}" /></a>
											</td>
											<td class="txt-tous_avis">
												<a href="{{ tous_avis.href|escape('html') }}" class="label partage">{{ tous_avis.txt }}</a>
											</td>
										</tr>
									</table>
									{% endif %}
									{% if (pensebete) %}
									<table class="product_link_to_modules">
										<tr class="picto-pensebete">
											<td class="img-pensebete">
												<a href="{{ pensebete.href|escape('html') }}" class="label"><img src="{{ pensebete.src|escape('html') }}" alt="{{ pensebete.txt }}" /></a>
											</td>
											<td class="txt-pensebete">
												<a href="{{ pensebete.href|escape('html') }}" class="label partage">{{ pensebete.txt }}</a>
											</td>
										</tr>
									</table>
									{% endif %}
									<table class="product_link_to_modules">
										<tr class="picto-print">
											<td class="img-print">
												<a href="javascript:window.print()"><img src="{{ print.src|escape('html') }}" alt="{{ print.txt }}" title="{{ print.txt }}" /></a>
											</td>
											<td class="txt-print">
												<a href="javascript:window.print()" class="label partage">{{ print.txt }}</a>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						{{ javascript }}
					</div>
					<h1 class="titre_produit">{{ product_name }}</h1>
					{% if (reference) %}
						<h4 property="mpn">{{ reference.label }} {{ reference.txt }}</h4>
					{% endif %}
					{% if (ean_code) %}
						<h4 property="gtin8">{{ ean_code.label }}: {{ ean_code.txt }}</h4>
					{% endif %}
					{% if (conditionnement) %}
						<p><b>{{ STR_CONDITIONING }}{{ STR_BEFORE_TWO_POINTS }}: </b>{{ conditionnement }}</p>
					{% endif %}
					{% if (marque) %}
						<h3 property="brand">{{ marque.label }}: <b>{{ marque.txt }}</b></h3>
					{% endif %}
					{% if (points) %}
						<p>{{ points.label }}: {{ points.txt }}</p>
					{% endif %}
						<div class="description" property="description">
							{% if (descriptif) %}<p>{{ descriptif }}</p>{% endif %}
							{% if (description) %}<div>{{ description }}</div>{% endif %}
						</div>
					{% if (extra_link) %}
						<p class="extra_link"><a href="{{ extra_link }}" onclick="return(window.open(this.href)?false:true);">{{ extra_link }}</a></p>
					{% endif %}
					{% if (explanation_table) %}
						{{ explanation_table }}
					{% endif %}
					{% if (check) %}
						{{ check }}
					{% elseif (critere_stock) %}
						{{ critere_stock }}
					{% else %}
						<div class="on_estimate">
							<table >
								<tr>
									<td class="center">
										<span style="font-size: 20px;">{{ on_estimate.label }}</span>
									</td>
								</tr>
								<tr>
									<td class="middle">
										<form method="post" action="{{ on_estimate.action }}">
										<input class="clicbouton" type="submit" value="{{ on_estimate.contact_us|str_form_value }}">
										</form>
									</td>
								</tr>
							</table>
						</div>
						<div style="clear:both;"></div>
					{% endif %}
					{% if (qrcode_image_src) %}<div class="qrcode"><img src="{{ qrcode_image_src|escape('html') }}" alt="" /></div>{% endif %}
				</div>
			</td>
		</tr>
	</table>				
	{% if (tabs) %}
		<br />
		<table class="fp">
			<tr>
				{% for tab in tabs %}
					<td id="title_{{ tab.index }}" class="{% if tab.is_current %}current_tab{% else %}tab{% endif %}" onclick="switch_product_tab('tab_{{ tab.index }}','title_{{ tab.index }}');return false;">
						<h3>{{ tab.title }}</h3>
					</td>
				{% endfor %}
			</tr>
			<tr>
				<td class="tab_content" colspan="{count(tabs) %}">
				{% for tab in tabs %}
					<div style="display: {% if tab.is_current %}block{% else %}none{% endif %};" id="tab_{{ tab.index }}">{{ tab.content }}</div>
				{% endfor %}
				<input value="tab_1" id="current_tab_id" type="hidden" />
				<input value="title_1" id="current_tab_title" type="hidden" />
				</td>
			</tr>
		</table>
	{% endif %}
	{% if (youtube_code) %}
		{{ youtube_code }}
	{% endif %}
	{{ associated_products }}
</div>