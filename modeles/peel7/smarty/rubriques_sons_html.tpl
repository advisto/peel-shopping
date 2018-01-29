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
// $Id: rubriques_sons_html.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<h3>{$list_rubriques_txt}:</h3>
<ul>
{foreach $data as $item}
	<li>
		{if isset($item.image_src)}
		<img src="{$item.image_src|escape:'html'}" alt="" /><br />
		{/if}
		<b><a href="{$item.href|escape:'html'}">{if isset($item.lien_src)}<img src="{$item.lien_src|escape:'html'}" alt="{$item.name}" />{else}{$item.name}{/if}</a></b>
		{if !empty($description)}<p>{$description}</p>{/if}
	</li>
{/foreach}
</ul>
