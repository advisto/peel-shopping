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
// $Id: multipage_template_default_1.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<div class="multipage-area">
	<div class="center">
		{{ results_per_page }}
	</div>
	{% if total_page>1 and not show_page_if_only_one %}
	<div class="multipage">
		<ul class="pagination">
			{% if first_page %}<li>{{ first_page }}</li>{% endif %}
			{% if previous_page %}<li>{{ previous_page }}</li>{% endif %}
			{% for l in loop %}<li{% if l.i==current_page %} class="active"{% endif %}>{{ l.page }}</li>{% endfor %}
			{% if next_page %}<li>{{ next_page }}</li>{% endif %}
			{% if last_page %}<li>{{ last_page }}</li>{% endif %}
		</ul>
	</div>
	{% endif %}
</div>