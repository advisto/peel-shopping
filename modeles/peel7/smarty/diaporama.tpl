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
// $Id: diaporama.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<table class="diaporama_tab">
	{foreach $diaporama as $diapo}
		{if $diapo.is_row}
	<tr>
		{/if}
		<td>
			<a class="nyroModal" rel="gal" href="{$diapo.image}" {if $diapo.j !=0} rev="{$diapo.thumbs}"{/if}>
				<img oncontextmenu="return false" ondragstart="return false" onselectstart="return false" border="0" src="{$diapo.thumbs}" alt=""  />
			</a>
		</td>
		{if !empty($diapo.empty_cells)}
			{for $var=1 to $diapo.empty_cells}
		<td></td>
			{/for}
	</tr>
		{/if}
	{/foreach}
</table>