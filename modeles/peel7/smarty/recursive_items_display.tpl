{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: recursive_items_display.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
*}{foreach $items as $it}
	{$max_length=$it.item_max_length}
	{if !empty($it.nb)}
		{$max_length=$max_length-strlen($it.nb)-3}
	{/if}
	{if $it.has_sons && $location == 'left'}
		{$max_length=$max_length-3}
	{/if}
	{if $display_mode=='option'}
<option value="{$it.value|str_form_value}"{if $it.is_selected} selected="selected" class="bold"{/if}>{$it.indent}{$it.name|str_shorten:$max_length}</option>{if $it.has_sons && !empty($it.SONS)}{$it.SONS}{/if}
	{elseif $display_mode=='checkbox'}
<div class="col-md-4"><input name="{$input_name|str_form_value}[]" type="checkbox" value="{$it.value|str_form_value}"{if $it.is_selected} checked="checked" class="bold"{/if} /> {$it.indent}{$it.name|str_shorten:$max_length}</div>{if $it.has_sons && !empty($it.SONS)}{$it.SONS}{/if}
	{else}
<li class="{if $it.has_sons}dropdown-submenu plus{else}minus{/if}{if $it.is_current} active{/if}{if isset($it.technical_code)} m_item_{$it.technical_code}{/if}">{$it.indent}
	{if $it.has_sons && !empty($it.SONS)}
		<a id="{$it.id}" class="dropdown-toggle" href="{$it.href}">{if !empty($it.nb)}<span class="nb_item badge pull-right">{$it.nb}</span> {/if}{$it.name|str_shorten:$max_length}</a>
		<ul class="sousMenu level{$it.depth} dropdown-menu" aria-labelledby="{$it.id}" role="menu">{$it.SONS}</ul>
	{elseif isset($it.href)}
		<a href="{$it.href|escape:'html'}">
			{if $it.has_sons && $location == 'left'}
				<span class="menu_categorie_link">{if !empty($it.nb)} <span class="nb_item badge pull-right">{$it.nb}</span> {/if}{$it.name|str_shorten:$max_length}</span> <span class="glyphicon glyphicon-chevron-right" title="+"></span>
			{else}
				{if !empty($it.nb)}<span class="nb_item badge pull-right">{$it.nb}</span> {/if}{$it.name|str_shorten:$max_length}
			{/if}
		</a>
	{/if}
</li>
	{/if}
{/foreach}