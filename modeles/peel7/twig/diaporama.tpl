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
// $Id: diaporama.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<table class="diaporama_tab">
	{% for diapo in diaporama %}
		{% if diapo.is_row %}
	<tr>
		{% endif %}
		<td>
			<a class="nyroModal" rel="gal" href="{{ diapo.image }}" {% if diapo.j!=0 %} rev="{{ diapo.thumbs }}"{% endif %}>
				<img oncontextmenu="return false" ondragstart="return false" onselectstart="return false" border="0" src="{{ diapo.thumbs }}" alt=""  />
			</a>
		</td>
		{% if diapo.empty_cells %}
			{% for var in 1..diapo.empty_cells %}
		<td></td>
			{% endfor %}
	</tr>
		{% endif %}
	{% endfor %}
</table>