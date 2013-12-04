{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: devises.tpl 39162 2013-12-04 10:37:44Z gboussin $
#}<div class="select_currency">
	<select class="form-control" name="devise" onchange="document.location='{{ url_part|htmlspecialchars|addslashes }}'+this.options[this.selectedIndex].value" aria-label="{{ STR_MODULE_DEVISES_CHOISIR_DEVISE|str_form_value }}">
	{% for o in options %}
		<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}</option>
	{% endfor %}
	</select>
</div>