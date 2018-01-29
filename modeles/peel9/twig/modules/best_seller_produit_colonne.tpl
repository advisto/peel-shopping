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
// $Id: best_seller_produit_colonne.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}
<h2 class="products_title">{{ STR_TOP }}</h2>
{% if (products) %}
	{% if (module_best_sellers_return_result_as_link) %}
	{% for prod in products %}
		{{ prod.html }}<br />
	{% endfor %}
	
	{% else %}
	<div id="carousel_best_seller" class="carousel slide row" data-ride="carousel" data-interval="10000">
	  <!-- Indicators -->
	  {#<ol class="carousel-indicators">
		{% for i in 0..(((products|length)-1) // nb_col_md) %}
			<li data-target="#carousel_best_seller" data-slide-to="{{ i }}" class="{% if i==0 %}active{% endif %}"></li>
		{% endfor %}
	  </ol>#}

		<!-- Wrapper for slides -->
		<div class="carousel-inner">
			<div class="item active">
		{% for prod in products %}
			{% if prod.i%nb_col_md==1 and prod.i>1 %}
			</div>
			<div class="item">
			{% endif %}
				<div class="col-sm-{{ (12 // nb_col_sm) }} col-md-{{ (12 // nb_col_md) }}{% if prod.i-1%nb_col_md>nb_col_xs-1 %} hidden-xs{% endif %}{% if prod.i-1%nb_col_md>nb_col_sm-1 %} hidden-sm{% endif %}">
					{{ prod.html }}
				</div>
		{% endfor %}
			</div>
		</div>

		{% if ((products|length)-1)/nb_col_md>0 %}
		<!-- Controls -->
		<a class="left carousel-control" href="#carousel_best_seller" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
		</a>
		<a class="right carousel-control" href="#carousel_best_seller" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span>
		</a>
		{% endif %}
	</div>
	{% endif %}
{% endif %}