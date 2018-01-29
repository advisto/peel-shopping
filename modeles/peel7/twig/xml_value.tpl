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
// $Id: xml_value.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<!-- forum -->
<div class="bloc-contenu">
<ul>
{% for link in links %}
	<li><a href="{{ link.href|escape('html') }}">- {{ link.label|html_entity_decode_if_needed|strip_tags|str_shorten({{ line_length_max }},'','..') }}</a></li>
{% endfor %}
</ul>
</div>
<!-- fin forum -->