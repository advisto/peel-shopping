{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: recursive_items_display.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}
{if !empty($columns)}
<div class="col-sm-{floor(12/$columns)}"><ul>
{/if}
{foreach $items as $it name=data}
	{$max_length=$it.item_max_length}
	{if !empty($it.nb)}
		{$max_length=$max_length-strlen($it.nb)-3}
	{/if}
	{if $it.has_sons && $location == 'left'}
		{$max_length=$max_length-3}
	{/if}
	{if $display_mode=='option'}
<option value="{$it.value|str_form_value}"{if $it.is_selected} selected="selected" class="bold"{/if}{if !empty($it.disabled)} style="color:#AAAAAA"{/if}>{$it.indent}{$it.name|str_shorten:$max_length}</option>{if $it.has_sons && !empty($it.SONS)}{$it.SONS}{/if}
	{elseif $display_mode=='checkbox'}
<div class="col-md-4"><input name="{$input_name|str_form_value}[]" type="checkbox" value="{$it.value|str_form_value}"{if $it.is_selected} checked="checked" class="bold"{/if} /> {$it.indent}{$it.name|str_shorten:$max_length}</div>{if $it.has_sons && !empty($it.SONS)}{$it.SONS}{/if}
	{elseif $display_mode=='div'}
		{if $it.depth == 1}
			<div class="col-md-4">
		{/if}
		{if $it.has_sons && !empty($it.SONS)}
			<a id="{$it.id}" class="dropdown-toggle" href="{$it.href}">
				{if !empty($it.nb)}<span class="nb_item badge pull-right">{$it.nb}</span> {/if}
					<h4>{$it.name|str_shorten:$max_length}</h4>
			</a>
			<div class="listsousMenu level{$it.depth}" aria-labelledby="{$it.id}" role="menu">{$it.SONS}</div>
		{elseif isset($it.href)}
			<a href="{$it.href|escape:'html'}">
				{if $it.has_sons && $location == 'left'}
					<span class="menu_categorie_link">{if !empty($it.nb)} <span class="nb_item badge pull-right">{$it.nb}</span> {/if}<h4>{$it.name|str_shorten:$max_length}</h4></span> <span class="glyphicon glyphicon-chevron-right" title="+"></span>
				{else}
					{if $it.depth == 1}
						{if !empty($it.nb)}<span class="nb_item badge pull-right">{$it.nb}</span> {/if}<h4>{$it.name|str_shorten:$max_length}</h4>
					{else}
						{if !empty($it.nb)}<span class="nb_item badge pull-right">{$it.nb}</span> {/if}<h5>{$it.name|str_shorten:$max_length}</h5>
					{/if}
				{/if}
			</a>
		{/if}
		{if $it.depth == 1}
			<div class="clearfix"></div>
			</div>
		{/if}
	{else}
<li class="{if $it.has_sons}dropdown-submenu plus{else}minus{/if}{if $it.is_current} active{/if}{if isset($it.technical_code)} m_item_{$it.technical_code}{/if}{if $it.class} {$it.class}{/if}">
	{if $it.has_sons && !empty($it.SONS)}
		{$it.indent}<a id="{$it.id}" class="dropdown-toggle" href="{$it.href}">{$it.name|str_shorten:$max_length}{if !empty($it.nb)}<span class="nb_item badge pull-right">{$it.nb}</span> {/if}</a>
		<ul class="sousMenu level{$it.depth} dropdown-menu" aria-labelledby="{$it.id}" role="menu">{$it.SONS}</ul>
	{elseif isset($it.href)}
		{$it.indent}<a href="{$it.href|escape:'html'}">{if $it.has_sons && $location == 'left'}<span class="menu_categorie_link">{$it.name|str_shorten:$max_length}</span> <span class="glyphicon glyphicon-chevron-right" title="+"></span>{else}{$it.name|str_shorten:$max_length}{/if}{if !empty($it.nb)}<span class="nb_item badge pull-right">{$it.nb}</span> {/if}</a>
	{/if}
</li>
	{/if}
	{if !empty($columns) && $smarty.foreach.data.iteration%((count($items)/$columns)|ceil)==0 && $smarty.foreach.data.iteration<count($items)}
</ul></div><div class="col-sm-{floor(12/$columns)}"><ul>
	{/if}
{/foreach}
{if !empty($columns)}
</ul></div>
{/if}
