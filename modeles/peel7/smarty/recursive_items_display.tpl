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
// $Id: recursive_items_display.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}{foreach $items as $it}
<li class="{if $it.has_sons}plus{else}minus{/if}{if $it.is_current} current{/if}{if isset($it.technical_code)} m_item_{$it.technical_code}{/if}">
	{if isset($it.href)}
		{$max_length=$item_max_length}
		{if !empty($it.nb)}
		{$max_length=$max_length-strlen($it.nb)-5}
		{/if}
		{if $it.has_sons AND $location == 'left'}
		{$max_length=$max_length-3}
		{/if}
		<a href="{$it.href|escape:'html'}">
			{if $it.has_sons AND $location == 'left'}
				<span class="menu_categorie_link">{$it.name|str_shorten:$max_length}{if !empty($it.nb)} ({$it.nb}){/if}</span><span style="float:right; display:block"><img src="{$sons_ico_src|escape:'html'}" alt="+" /></span>
			{else}
				{$it.name|str_shorten:$max_length}{if !empty($it.nb)} ({$it.nb}){/if}
			{/if}
		</a>
	{/if}
	{if $it.has_sons AND !empty($it.SONS)}
		{if !isset($it.href)}
			<a href="">{if $location == 'left'}<img src="{$sons_ico_src|escape:'html'}" alt="+" />{/if}</a>
		{/if}
		<ul class="sousMenu level{$it.depth}">{$it.SONS}</ul>
	{/if}
</li>
{/foreach}