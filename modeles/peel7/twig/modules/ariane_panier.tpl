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
// $Id: ariane_panier.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<div id="ariane_panier">
	<ul class="pager">
		<li class="in_caddie {% if in_caddie %}current{% elseif was_in_caddie %}visited_before{% else %}disabled{% endif %}">
			<a href="{{ caddie_affichage_href|escape('html') }}"><span class="bold">1</span> - <span class="glyphicon glyphicon-shopping-cart"></span> {{ STR_CADDIE }}</a>
		</li>
		<li class="in_step1 {% if in_step1 %}current{% elseif was_in_step1 and not in_caddie %}visited_before{% else %}disabled{% endif %}">
			<a href="{% if not in_step1 and (was_in_step1 and not in_caddie) %}{{ achat_maintenant_href|escape('html') }}{% else %}#{% endif %}"><span class="bold">2</span> - <span class=""></span> {{ STR_PAYMENT_MEAN }}</a>
		</li>
		<li class="in_step2 {% if in_step2 %}current{% elseif was_in_step2 and in_step3 %}visited_before{% else %}disabled{% endif %}">
			<a href="#"><span class="bold">3</span> - {{ STR_MODULE_ARIANE_PANIER_SOMMARY }}</a>
		</li>
		<li class="in_step3 {% if in_step3 %}current{% else %}disabled{% endif %}">
			<a href="#"><span class="bold">4</span> - {{ STR_CONFIRMATION }}</a>
		</li>
	</ul>
</div>
<div class="clearfix"></div>