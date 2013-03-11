{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: sideblocktitle.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<div class="sideblocktitle {{ block_class }}">
	<div class="sideblocktitle_header">
		{% if (title) %}<h2>{{ title }}</h2>{% endif %}
	</div>
	<div class="sideblocktitle_content_container">
		<div class="sideblocktitle_content">{{ text }}</div>
	</div>
	<div class="sideblocktitle_footer"></div>
</div>