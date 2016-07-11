{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.tpl 50572 2016-07-07 12:43:52Z sdelaporte $
#}{% if (error) %}
<div class="alert alert-danger">
	{{ error }}
</div>
{% endif %}
{% if (home_title) %}
	{{ home_title }}
{% endif %}
<div class="page_home_content">
{% if carrousel_html %}
	{{ carrousel_html }}
{% endif %}
{% if categorie_annonce is defined %}
	{{ categorie_annonce }}
{% endif %}
{% if  affiche_compte or user_register_form %}
	<div class="row">
		<div class="col-md-8">
			{{ contenu_html }}
		</div>
		<div class="col-md-4">
		{% if affiche_compte %}
			{{ affiche_compte }}
		{% endif %}
		{% if user_register_form %}
			{{ user_register_form }}
		{% endif %}
		</div>
	</div>
{% else %}
	{{ contenu_html }}
{% endif %}
{% if fresh_ad_presentation %}
	{{ fresh_ad_presentation }}
{% endif %}
	{{ categorie_accueil }}
	{{ notre_selection }}
	{{ nouveaute }}
</div>