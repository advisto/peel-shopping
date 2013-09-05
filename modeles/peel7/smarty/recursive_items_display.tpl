{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: recursive_items_display.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}{foreach $items as $it}
<li class="{if $it.has_sons}plus{else}minus{/if}{if $it.is_current} current{/if}{if isset($it.technical_code)} m_item_{$it.technical_code}{/if}">
	{if isset($it.href)}
		{$max_length=$item_max_length}
		{if !empty($it.nb)}
		{$max_length=$max_length-strlen($it.nb)-3}
		{/if}
		{if $it.has_sons AND $location == 'left'}
		{$max_length=$max_length-3}
		{/if}
		<a href="{$it.href|escape:'html'}">
			{if $it.has_sons AND $location == 'left'}
				<span class="menu_categorie_link">{$it.name|str_shorten:$max_length}{if !empty($it.nb)} <span class="nb_item">{$it.nb}</span>{/if}</span><span style="float:right; display:block"><img src="{$sons_ico_src|escape:'html'}" alt="+" /></span>
			{else}
				{$it.name|str_shorten:$max_length}{if !empty($it.nb)} <span class="nb_item">{$it.nb}</span>{/if}
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