{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2017 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.5, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: alpha.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
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