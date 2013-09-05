{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: ariane_panier.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}<div id="ariane_panier">
	<div class="cart_logo"><img src="{{ cart_logo_src|escape('html') }}" alt="" /></div>
	<div class="in_caddie{% if in_caddie %} current{% elseif was_in_caddie %} visited_before{% endif %}">
	{% if in_caddie or was_in_caddie %}
	<a href="{{ caddie_affichage_href|escape('html') }}">1 - {{ STR_CADDIE }}</a>
	{% else %}
	1 - {{ STR_CADDIE }}
	{% endif %}
	</div>
	<div class="in_step1{% if in_step1 %} current{% elseif was_in_step1 %}{% if in_caddie %} visited_after{% else %} visited_before{% endif %}{% endif %}">
	{% if in_step1 or was_in_step1 %}
	<a href="{{ achat_maintenant_href|escape('html') }}">2 - {{ STR_PAYMENT_MEAN }}</a>
	{% else %}
	2 - {{ STR_PAYMENT_MEAN }}
	{% endif %}
	</div>
	<div class="in_step2{% if in_step2 %} current{% elseif was_in_step2 and in_step3 %} visited_before{% endif %}">
	3 - {{ STR_MODULE_ARIANE_PANIER_SOMMARY }}
	</div>
	<div class="in_step3{% if in_step3 %} current{% endif %}">4 - {{ STR_CONFIRMATION }}</div>
</div><div class="clear"></div>