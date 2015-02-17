{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: xml_value.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
#}<!-- forum -->
<div class="bloc-contenu">
<ul>
{% for link in links %}
	<li class="forumListe"><a href="{{ link.href|escape('html') }}">- {{ link.label|html_entity_decode_if_needed|strip_tags|str_shorten(30,'','..') }}</a></li>
{% endfor %}
</ul>
</div>
<!-- fin forum -->