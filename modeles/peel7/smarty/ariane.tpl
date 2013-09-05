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
// $Id: ariane.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<div property="breadcrumb">
	{if $ariane.href}<a href="{$ariane.href|escape:'html'}" title="{$ariane.txt}">{/if}<img src="{$wwwroot}/images/home_ariane.jpg" alt="{$ariane.txt}" />{if $ariane.href}</a>{/if}
	{if $other.txt}
		&gt;
		{if $other.href}<span typeof="Breadcrumb"><a property="url" href="{$other.href|escape:'html'}" title="{$other.txt}"><span property="title">{/if}{$other.txt}{if $other.href}</span></a></span>{/if}
	{/if}
</div>