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
// $Id: index.tpl 54778 2017-10-05 12:56:11Z sdelaporte $
#}{% if error is defined %}
<div class="alert alert-danger">
	{{ error }}
</div>
{% endif %}
{% if home_title is defined %}
	{{ home_title }}
{% endif %}
<div class="page_home_content">
{% if carrousel_html is defined %}
	{{ carrousel_html }}
{% endif %}
{% if categorie_annonce is defined %}
	{{ categorie_annonce }}
{% endif %}
{% if search_map is defined %}
	{{ search_map }}
{% endif %}
{% if affiche_compte or user_register_form %}
	<div class="row">
		<div class="col-md-8">
			{{ contenu_html|replace({'[MODULES_LEFT]':MODULES_LEFT}) }}
		</div>
		<div class="col-md-4">
		{% if affiche_compte is defined %}
			{{ affiche_compte }}
		{% endif %}
		{% if user_register_form is defined %}
			{{ user_register_form }}
		{% endif %}
		</div>
	</div>
{% else %}
	{{ contenu_html|replace({'[MODULES_LEFT]':MODULES_LEFT}) }}
{% endif %}
	{{ home_middle_top|replace({'[MODULES_LEFT]':MODULES_LEFT}) }}
{% if fresh_ad_presentation is defined %}
	{{ fresh_ad_presentation }}
{% endif %}
	{{ categorie_accueil }}
	{% if notre_selection and website_type and website_type == 'shop' %}
	</div></div></div></div></div>
		<div class="full_size_background_section">
			<div class="container">
				<div class="row">
				{{ notre_selection }}
				</div>
			</div>
		</div>
	<div class="container"><div class="row"><div class="middle_column col-sm-12"><div class="middle_column_repeat"><div class="page_home_content">
	{% endif %}
	{{ home_middle }}
	<div class="row">
	{{ nouveaute }}
	</div>
	{{ center_middle_home }}
	{% if vitrine_list is defined %}
		{{ vitrine_list }}
	{% endif %}
</div>