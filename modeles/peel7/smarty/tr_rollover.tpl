{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: tr_rollover.tpl 50572 2016-07-07 12:43:52Z sdelaporte $
*}{if $line_number % 2 == 0}
	<tr class="classe1" onmouseover="this.className='classe3';" onmouseout="this.className='classe1';"{if !empty($id)} id="{$id}"{/if}{if !empty($onclick)} onclick="{$onclick}"{/if}{if !empty($style)} style="{$style}"{/if}>
{else}
	<tr class="classe2" onmouseover="this.className='classe3';" onmouseout="this.className='classe2';"{if !empty($id)} id="{$id}"{/if}{if !empty($onclick)} onclick="{$onclick}"{/if}{if !empty($style)} style="{$style}"{/if}>
{/if}