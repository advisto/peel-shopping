{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search_result.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
#}{% if is_annonce_module_active %}
	{% if (res_affiche_annonces) %}
<h1 class="search_result">{% if (search) %}{{ search|strtoupper }}{% else %}{{ STR_RESULT_SEARCH }} - {{ STR_MODULE_ANNONCES_SEARCH_RESULT_ADS }}{% endif %}</h1>
	{{ res_affiche_annonces }}
	{% elseif page<1 and not (result_affichage_produit) and not (arts_found) and not (brands_found) %}
<h1 class="search_result">{% if (search) %}{{ search|strtoupper }}{% else %}{{ STR_RESULT_SEARCH }} - {{ STR_MODULE_ANNONCES_SEARCH_RESULT_ADS }}{% endif %}</h1>
<div>{{ STR_MODULE_ANNONCES_SEARCH_NO_RESULT_ADS }}</div><br />
	{% endif %}
{% endif %}
{% if not is_annonce_module_active %}
	{% if (result_affichage_produit) %}
<h1 class="search_result">{% if (search) %}{{ search|strtoupper }}{% else %}{{ STR_RESULT_SEARCH }} - {{ STR_SEARCH_RESULT_PRODUCT }}{% endif %}</h1>
	{{ result_affichage_produit }}
	{% elseif page<1 and not (res_affiche_annonces) and not (arts_found) and not (brands_found) %}
<h1 class="search_result">{% if (search) %}{{ search|strtoupper }}{% else %}{{ STR_RESULT_SEARCH }} - {{ STR_SEARCH_RESULT_PRODUCT }}{% endif %}</h1>
<div>{{ STR_SEARCH_NO_RESULT_PRODUCT }}</div><br />
	{% endif %}
{% endif %}
{% if (are_terms) %}
	{% if (arts_found) %}
<h2 class="search_result">{{ STR_RESULT_SEARCH }} - {{ STR_SEARCH_RESULT_ARTICLE }}</h2><br />
		{% for art in arts_found %}
			<p>
				<b>{{ art.num }}. <a href="{{ art.category_href|escape('html') }}">{{ art.rubrique|html_entity_decode_if_needed }}</a></b> - <a href="{{ art.content_href|escape('html') }}">{{ art.titre|html_entity_decode_if_needed }}</a><br />
				{{ art.texte }}
			</p>
		{% endfor %}
	{% elseif page<1 and not is_annonce_module_active and not (res_affiche_annonces) and not (result_affichage_produit) and not (brands_found) %}
<h2 class="search_result">{{ STR_RESULT_SEARCH }} - {{ STR_SEARCH_RESULT_ARTICLE }}</h2><br />
<div>{{ STR_SEARCH_NO_RESULT_ARTICLE }}</div><br />
	{% endif %}
	<h2 class="search_result">{{ STR_RESULT_SEARCH }} - {{ STR_SEARCH_RESULT_BRAND }}</h2><br />
	{% if brands_found %}
<h2 class="search_result">{{ STR_RESULT_SEARCH }} {{ search|strtoupper }} {{ STR_SEARCH_RESULT_BRAND }}</h2>
		{% for brand in brands_found %}
<p>
	<b>{{ brand.num }}. <a href="{{ brand.href|escape('html') }}">{{ brand.nom|html_entity_decode_if_needed }}</a></b> - {{ brand.description|html_entity_decode_if_needed }}
</p>
		{% endfor %}
	{% elseif page<1 and not is_annonce_module_active and not (res_affiche_annonces) and not (result_affichage_produit) and not (arts_found) %}
<h2 class="search_result">{{ STR_RESULT_SEARCH }} {{ search|strtoupper }} {{ STR_SEARCH_RESULT_BRAND }}</h2>
<div>{{ STR_SEARCH_NO_RESULT_BRAND }}</div><br />
	{% endif %}
{% endif %}