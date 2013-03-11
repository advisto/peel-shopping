{* Smarty
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
// $Id: rss_func.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<div class="rss_bloc">
	{if isset($fb_src) AND isset($fb_href)}
	<a style="margin-right:5px;" href="{$fb_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$fb_src|escape:'html'}" alt="facebook" title="facebook" /></a>
	{/if}
	<a href="{$href|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$src|escape:'html'}" alt="rss" style="vertical-align:top;" title="RSS" /></a>
</div>