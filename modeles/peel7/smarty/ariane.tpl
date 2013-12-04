{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: ariane.tpl 39162 2013-12-04 10:37:44Z gboussin $
*}<div property="breadcrumb" class="breadcrumb">
	{if !empty($ariane)}{if $ariane.href}<a href="{$ariane.href|escape:'html'}" title="{$ariane.txt}">{/if}<span class="glyphicon glyphicon-home" alt="{$ariane.txt}"></span>{if $ariane.href}</a>{/if}{/if}
	{if $other.txt}
		{if !empty($ariane)} &gt; {/if}
		{if $other.href}<span typeof="Breadcrumb"><a property="url" href="{$other.href|escape:'html'}" title="{$other.txt}"><span property="title">{/if}{$other.txt}{if $other.href}</span></a></span>{/if}
	{/if}
	{if $buttons}<div class="breadcrumb_buttons">{$buttons}</div><div class="clearfix"></div>{/if}
</div>