{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: compte_mini.tpl 53849 2017-05-19 12:29:39Z sdelaporte $
#}<div class="{% if location=='header' %}hidden-xs{% elseif location=='footer' %}visible-xs{% endif %}">
	<div style="padding:5px;">
		{{ STR_HELLO }}&nbsp;{{ prenom|html_entity_decode_if_needed }} {{ nom_famille|html_entity_decode_if_needed }}
{% if (admin) %}
		<br /><a href="{{ admin.href|escape('html') }}">{{ admin.txt|escape('html') }}</a>
{% endif %}
	{% if quick_add_product_from_search_page_href %}
			<br /><a href="{{ quick_add_product_from_search_page_href|escape('html') }}">{{ LANG.STR_EASY_LIST }}</a>
	{% endif %}
	<br /><a href="{{ sortie_href|escape('html') }}">{{ STR_DECONNECT }}</a>
	<br /><a href="{{ compte_href|escape('html') }}">{{ STR_COMPTE }}</a>
	
	{% if history_href %}
		<br /><a href="{{ history_href|escape('html') }}">{{ STR_ORDER_HISTORY }}</a>
	{% endif %}
{% if (fb_deconnect_lbl) %}
	<br /><a id="facebook_connect_logout" href="#">{{ fb_deconnect_lbl }}</a>
{% endif %}
	</div>
</div>