{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: carrousel_reference.tpl 54014 2017-06-09 09:26:20Z jlesergent $
*}
{% if references %}
	{% if module_best_sellers_return_result_as_link %}
	{% for ref in references %}
		{{ ref.html }}<br />
	{% endfor %}
	
	{else}
	<div class="col-md-2"></div>
	<div id="carrousel_reference" class="col-md-8 carousel slide" data-ride="carousel" data-interval="10000">
	  <!-- Indicators -->
	  {*<ol class="carousel-indicators">
		{% for i in 0..((references.count-1)/nb_col_md|floor) %}
			<li data-target="#carrousel_reference" data-slide-to="{{ i }}" class="{% if i==0 %}active{% endif %}"></li>
		{% endfor %}
	  </ol>*}

		<!-- Wrapper for slides -->
		<div class="carousel-inner">
			<div class="item active">
		{% for ref in references %}
			{% if ref.i%1!=1 && ref.i>1 %}
			</div>
			<div class="item">
			{% endif %}
				<div class="center col-sm-{(12/nb_col_sm|floor)} col-md-{(12/nb_col_md|floor)}{% if (ref.i-1)% nb_col_md>nb_col_xs-1} hidden-xs{% endif %}{% if ($ref.i-1)%nb_col_md>nb_col_sm-1} hidden-sm{% endif %}">
					{{ ref.html }}
				</div>
		{% endfor %}
			</div>
		</div>

		{% if ((references.count-1|floor)/nb_col_md)>0 %}
		<!-- Controls -->
		<a class="left carousel-control" href="#carrousel_reference" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left"></span>
		</a>
		<a class="right carousel-control" href="#carrousel_reference" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right"></span>
		</a>
		{% endif %}
	</div>
	<div class="col-md-2"></div>
	{% endif %}
{/if}