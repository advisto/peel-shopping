{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: rib.tpl 64741 2020-10-21 13:48:51Z sdelaporte $
*}<div>
	{foreach $data as $item}
		<b>{$item.label}:</b> {$item.value}<br />
	{/foreach}
</div>