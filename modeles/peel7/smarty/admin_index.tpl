{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_index.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}{if isset($KeyyoCalls)}{$KeyyoCalls}{/if}
<table class="main_table">
	<tr>
		<td style="width:33%; vertical-align:top;">{$orders}</td>
		<td style="width:33%; vertical-align:top;">{$sales}</td>
		<td style="width:33%; vertical-align:top;">{$products}</td>
	</tr>
	<tr>
		<td style="padding-top:10px; vertical-align:top;">{$delivery}</td>
		<td style="padding-top:10px; vertical-align:top;">{$users}</td>
		<td style="padding-top:10px; vertical-align:top;">{$peel}</td>
	</tr>
</table>
<br />
<div class="center">{$data_lang}</div>
<p class="global_error center"><a href="{$sortie_href|escape:'html'}">{$STR_ADMIN_INDEX_SECURITY_WARNING}</a></p>