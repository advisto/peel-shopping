{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: banner.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}{foreach $banners as $ban}
<div class="publicite" style="margin-top:3px;">
{if isset($ban.html)}
{$ban.html}
{else}
	{if !empty($ban.lien)}
	<a href="{$ban.href|escape:'html'}" {$ban.extra_javascript}{if !empty($ban.target) AND $ban.target != 'self'} {if $ban.target == '_blank'}onclick="return(window.open(this.href)?false:true);"{else}target="{$ban.target}"{/if}{/if}>
	{/if}
	{if isset($ban.swf)}
		{$ban.swf}
	{else}
		<img src="{$ban.src|escape:'html'}" alt="{$ban.lien}"{if $ban.width != $max_banner_width} width="{$ban.width}px"{if $ban.height != $max_banner_height} height="{$ban.height}px"{/if} />
	{/if}
	{if !empty($ban.lien)}
	</a>
	{/if}
{/if}
</div>
{/foreach}
