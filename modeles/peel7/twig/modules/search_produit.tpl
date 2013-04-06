{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: search_produit.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<ul>
{% if results %}
	{% for res in results %}
	<li><a href="{{ res.urlprod }}">{{ res.name }}</a></li>
	{% endfor %}
{% else %}
	<li>{{ STR_AUCUN_RESULTAT }}</li>
{% endif %}
</ul>