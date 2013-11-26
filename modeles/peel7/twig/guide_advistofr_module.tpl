{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: guide_advistofr_module.tpl 38965 2013-11-24 16:18:38Z gboussin $
#}<ul>
	{{ menu_contenu }}
	{% for l in links %}
		<li class="minus {% if l.selected %}active{% endif %} m_item_contact"><a href="{{ l.href|escape('html') }}">{{ l.label }}</a></li>
	{% endfor %}
</ul>