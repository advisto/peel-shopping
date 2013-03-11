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
// $Id: brand_link_html.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}{if $as_list}
<ul>
{foreach $links as $link}
	<li><a href="{$link.href|escape:'html'}">{$link.value|html_entity_decode_if_needed}</a></li>
{/foreach}
</ul>
{else}
{foreach $links as $link}
	<a href="{$link.href|escape:'html'}">{$link.value|html_entity_decode_if_needed}</a>
{/foreach}
{/if}