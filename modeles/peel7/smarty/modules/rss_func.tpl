{* Smarty
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
// $Id: rss_func.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
*}<div class="rss_bloc">
{if isset($fb_href)}
	<a style="margin-right:5px;" href="{$fb_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$fb_src|escape:'html'}" alt="F" title="Facebook" width="48" height="48" /></a>
{/if}
{if isset($twitter_href)}
 	<a style="margin-right:5px;" href="{$twitter_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$twitter_src|escape:'html'}" alt="T" style="vertical-align:top;" title="Twitter" width="48" height="48" /></a>
{/if}
{if isset($googleplus_href)}
 	<a style="margin-right:5px;" href="{$googleplus_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$googleplus_src|escape:'html'}" alt="G+" style="vertical-align:top;" title="Google+" width="48" height="48" /></a>
{/if}
	<a href="{$href|escape:'html'}" {if $rss_new_window}onclick="return(window.open(this.href)?false:true);"{/if}><img src="{$src|escape:'html'}" alt="rss" style="vertical-align:top;" title="RSS" width="48" height="48" /></a>
</div>