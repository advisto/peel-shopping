{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: footer.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
*}<ul class="link">
	{foreach $links as $l}
	<li><a href="{$l.href|escape:'html'}">{if $l.selected}<b>{/if}{$l.label}{if $l.selected}</b>{/if}</a></li>
	{/foreach}
	{if isset($facebook_page)}
	<li>{$facebook_page}</li>
	{/if}
	<li class="li_separated">{$propulse} <a href="https://www.peel.fr/" onclick="return(window.open(this.href)?false:true);">PEEL sites ecommerce</a></li>
	{if !empty($site)}<li>&copy;{$site}</li>{/if}
</ul>