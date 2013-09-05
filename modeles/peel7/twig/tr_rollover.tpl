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
// $Id: tr_rollover.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}{% if line_number%2 == 0 %}
	<tr class="classe1" onmouseover="this.className='classe3';" onmouseout="this.className='classe1';"{% if (id) %} id="{{ id }}"{% endif %}{% if (onclick) %} onclick="{{ onclick }}"{% endif %}{% if (style) %} style="{{ style }}"{% endif %}>
{% else %}
	<tr class="classe2" onmouseover="this.className='classe3';" onmouseout="this.className='classe2';"{% if (id) %} id="{{ id }}"{% endif %}{% if (onclick) %} onclick="{{ onclick }}"{% endif %}{% if (style) %} style="{{ style }}"{% endif %}>
{% endif %}