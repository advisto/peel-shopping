{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: avis_public_list.tpl 48447 2016-01-11 08:40:08Z sdelaporte $
#}
<h1 property="name">{{ STR_MODULE_AVIS_PEOPLE_OPINION_ABOUT_PRODUCT }}: {% if type == 'produit' %}{{ product_name }}{% elseif type == 'annonce' %}{{ annonce_titre }}{% endif %}</h1>
{% if are_results %}
	{% if not module_avis_no_notation and not ad_owner_opinion %}
		<b>{{ STR_MODULE_AVIS_AVERAGE_RATING_GIVEN }}</b> 
	{% endif %}
	{% for foo in 1..avisnote %}<img src="{{ star_src|escape('html') }}" alt="" />{% endfor %}
	{% if display_nb_vote_graphic_view %}
	<a href="{{ all_results_url }}">{{ total_vote }} {{ STR_POSTED_OPINIONS|lower }}</a>
	<table class="notation_tab">
		{% for notation in notations %}
		<tr>
			<td class="bar_contener">
				<div class="progress progress-striped">
					<div class="progress-bar" role="progressbar" aria-valuenow="{{ notation.width }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ notation.width }}%;">
						<span class="sr-only">{{ notation.width }}% Complete</span>
					</div>
				</div>
			</td>
			<td style="width:120px;">
				{% for foo in 1..notation.note %}<img src="{{ star_src|escape('html') }}" alt="" />{% endfor %}
			</td>
			<td>
				<a href="{{ notation.link }}">{{ notation.nb_this_vote }} {% if notation.nb_this_vote>1 %} {{ STR_POSTED_OPINIONS|lower }} {% else %} {{ STR_POSTED_OPINION|lower }} {% endif %}</a>
			</td>
		</tr>
		{% endfor %}
	</table>
	{% endif %}
	{% for res in results %}
	<div class="td_avis">
		<i>{{ STR_MODULE_AVIS_OPINION_POSTED_BY }} {{ res.pseudo }} {% if not module_avis_no_notation %} {% for foo in 1..res.note %}<img src="{{ star_src|escape('html') }}" alt="" />{% endfor %}{% endif %} {{ STR_ON_DATE_SHORT }} {{ res.date }}</i><br />{{ res.avis|html_entity_decode_if_needed|nl2br_if_needed }}
	</div>
	{% if res.allow_edit_and_suppr_avis %}
		<a href="{{ wwwroot }}/modules/avis/avis.php?{% if type == 'annonce' %}ref{% else %}prodid{% endif %}={{ id }}&amp;type={{ type }}&amp;id={{ res.id }}&amp;mode=edit">{{ LANG.STR_MODIFY }}</a> - 
		<a href="{{ wwwroot }}/modules/avis/avis.php?{% if type == 'annonce' %}ref{% else %}prodid{% endif %}={{ id }}&amp;type={{ type }}&amp;id={{ res.id }}&amp;mode=suppr">{{ LANG.STR_DELETE }}</a>
	{% endif %}
	{% endfor %}
{% else %}
	{% if type == 'produit' %}
	<div style="margin-top: 10px;">{{ STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT }}.</div>
	{% elseif type == 'annonce' %}
	<div style="margin-top: 5px;">{{ STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD }}.</div>
	{% endif %}
{% endif %}
{% if type == 'produit' %}
<p style="margin-top: 20px;">
	<a href="{{ urlprod }}">{{ STR_BACK_TO_PRODUCT|escape('html') }}</a>
</p>
{% elseif type == 'annonce' and not ad_owner_opinion %}
<p style="margin-top: 20px;">
	<a href="{{ urlannonce }}">{{ STR_MODULE_ANNONCES_BACK_TO_AD|escape('html') }}</a>
</p>
{% endif %}
{% if ad_owner_opinion %}
	- <a href="{{ wwwroot }}/modules/avis/avis.php?id={{ id }}&amp;type=annonce">{{ LANG.STR_ADD_NEWS|escape('html') }}</a>
{% endif %}