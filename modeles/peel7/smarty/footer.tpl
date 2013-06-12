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
// $Id: footer.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<ul class="link">
	<li class="first">&copy;{$site}</li>
	<li>{$propulse} <a href="https://www.peel.fr/" onclick="return(window.open(this.href)?false:true);">PEEL sites ecommerce</a></li>
	{foreach $links as $l}
		<li><a href="{$l.href|escape:'html'}">{if $l.selected}<b>{/if}{$l.label}{if $l.selected}</b>{/if}</a></li>
	{/foreach}
	{if isset($rss)}
		<li>{$rss}</li>
	{/if}
	{if isset($facebook_page)}
		<li>{$facebook_page}</li>
	{/if}
</ul>
{$contenu_html}