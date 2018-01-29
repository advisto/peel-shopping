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
// $Id: alpha.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<h1 property="name" class="page_title">{$title}</h1>
<div class="page_content">
{foreach $map as $letter}
	{if !empty( $letter.items)}
		<div class="well" style="margin-bottom:7px; margin-top:15px; padding:10px">{$letter.value}</div>
		{foreach $letter.items as $item}
		<div><a href="{$item.href|escape:'html'}">{$item.name|html_entity_decode_if_needed}{if !empty($item.count)} ({$item.count}){/if}</a></div>
		{/foreach}
	{/if}
{/foreach}
</div>