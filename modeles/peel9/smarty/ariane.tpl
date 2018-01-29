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
// $Id: ariane.tpl 53897 2017-05-29 15:55:22Z gboussin $
*}{if empty($hidden)}<div property="breadcrumb" class="breadcrumb"><span typeof="BreadcrumbList">
	{if !empty($ariane)}{if $ariane.href}<span property="itemListElement" typeof="ListItem"><a href="{$ariane.href|escape:'html'}" property="item" typeof="WebPage" title="{$ariane.txt}"><span property="name" class="hidden">{$ariane.txt}</span>{/if}<span class="glyphicon glyphicon-home" title="{$ariane.txt}"></span>{if $ariane.href}</a><meta property="position" content="1" /></span>{/if}{/if}
	{if $other.txt}
		{if !empty($ariane)} &gt; {/if}
		{if $other.href}<span property="itemListElement" typeof="ListItem"><a property="item" typeof="WebPage" href="{$other.href|escape:'html'}" title="{$other.txt}"><span property="name">{/if}{$other.txt}{if $other.href}</span></a><meta property="position" content="2" /></span>{/if}
	{/if}
</span>
	{if $buttons}<div class="breadcrumb_buttons">{$buttons}</div><div class="clearfix"></div>{/if}
</div>{else}
{* Ne pas utiliser car incompatible avec Google Rich Snippet qui ne prend que les liens a réellement dans le property="breadcrumb" 
<span property="breadcrumb" content="{$other.txt|strip_tags|trim|escape:'html'}">
	{if $other.txt && $other.href}<span typeof="Breadcrumb"><span property="url" content="{$other.href|escape:'html'}"><span property="title" content="{$other.txt|escape:'html'}"></span></span></span>{elseif !empty($other.hidden)}{$other.hidden}{/if}
</span>
*}
{/if}