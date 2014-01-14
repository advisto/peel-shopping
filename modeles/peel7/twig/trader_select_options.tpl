{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: trader_select_options.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
#}<option value="">{{ STR_NOT_ATTRIBUED }}</option>
{% for o in options %}
	<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
{% endfor %}
{% if is_admin_mode %}
<option value="">Autre</option>
{% endif %}