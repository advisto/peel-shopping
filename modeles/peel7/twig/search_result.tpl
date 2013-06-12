{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search_result.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}{% if is_annonce_module_active %}
	<h2 class="search_result">{{ STR_RESULT_SEARCH }} - {{ STR_MODULE_ANNONCES_SEARCH_RESULT_ADS }}</h2>
	{% if (res_affiche_annonces) %}
	{{ res_affiche_annonces }}
	{% else %}
	<div>{{ STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS }}</div><br />
	{% endif %}
{% endif %}
{% if not is_annonce_module_active %}
	<h2 class="search_result">{{ STR_RESULT_SEARCH }} - {{ STR_SEARCH_RESULT_PRODUCT }}</h2><br />
	{% if (result_affichage_produit) %}
	{{ result_affichage_produit }}
	{% else %}
	<div>{{ STR_SEARCH_NO_RESULT_PRODUCT }}</div><br />
	{% endif %}
{% endif %}
{% if (are_terms) %}
	<h2 class="search_result">{{ STR_RESULT_SEARCH }} - {{ STR_SEARCH_RESULT_ARTICLE }}</h2><br />
	{% if (arts_found) %}
		{% for art in arts_found %}
			<p>
				<b>{{ art.num }}. <a href="{{ art.category_href|escape('html') }}">{{ art.rubrique|html_entity_decode_if_needed }}</a></b> - <a href="{{ art.content_href|escape('html') }}">{{ art.titre|html_entity_decode_if_needed }}</a><br />
				{{ art.texte }}
			</p>
		{% endfor %}
	{% else %}
		<div>{{ STR_SEARCH_NO_RESULT_ARTICLE }}</div><br />
	{% endif %}
	<h2 class="search_result">{{ STR_RESULT_SEARCH }} - {{ STR_SEARCH_RESULT_BRAND }}</h2><br />
{% endif %}