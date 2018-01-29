{* Smarty
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
// $Id: sideblocktitle.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="sideblocktitle {$block_class}">
	<div class="well">
		<div class="sideblocktitle_header">
			{if !empty($title)}<h2>{$title}</h2>{/if}
		</div>
		<div class="sideblocktitle_content_container">
			<div class="sideblocktitle_content">{$text}</div>
		</div>
		<div class="sideblocktitle_footer"></div>
	</div>
</div>