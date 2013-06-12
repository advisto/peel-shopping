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
// $Id: multipage_template_default_1.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<table class="multipage-area">
	<tr class="middle">
		<td class="center" {% if total_page>1 and not show_page_if_only_one %}colspan="3"{% endif %}>
			{{ results_per_page }}
		</td>
	</tr>
{% if total_page>1 and not show_page_if_only_one %}
	<tr class="multipage middle">
		<td class="multipage_left">{{ first_page }}{{ previous_page }}&nbsp;</td>
		<td class="center multipage_middle">
			{% for l in loop %}{{ l.page }} {% endfor %}
		</td>
		<td class="multipage_right">&nbsp;{{ next_page }}{{ last_page }}</td>
	</tr>
{% endif %}
</table>