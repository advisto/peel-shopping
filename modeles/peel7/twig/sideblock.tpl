{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: sideblock.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}<div class="sideblock {{ block_class }} col-md-4">
	<div class="well">
		<div class="sideblock_header"></div>
		<div class="sideblock_content_container"><div class="sideblock_content">{{ text }}</div></div>
		<div class="sideblock_footer"></div>
	</div>
</div>