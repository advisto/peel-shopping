{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: new.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<table width="150" style="background-color:#cccccc">
	<tr>
		<td class="top">
			<p class="titredroite">{$title}</p>
			{foreach $data as $item}
				<p class="center">
				{if $item.is_full}
					<a href="{$item.href|escape:'html'}" title="{$item.name}"><img class="searchImg" width="130" alt="{$item.name}" src="{$item.src|escape:'html'}" onmouseover="showtrail('{$item.trail}','{$item.name}','{$item.descriptif}','5.0000','1','1',280,1);" onmouseout="hidetrail();" /></a>
				{else}
					<a href="{$item.href|escape:'html'}"><img src="{$item.src|escape:'html'}" width="130" alt="{$item.alt}" /></a>
				{/if}
				</p>
			{/foreach}
		</td>
	</tr>
</table>