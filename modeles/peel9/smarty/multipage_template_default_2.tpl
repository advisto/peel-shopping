{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: multipage_template_default_2.tpl 61970 2019-11-20 15:48:40Z sdelaporte $
*}<table class="multipage-area">
	<tr class="multipage middle">
		<td class="multipage_left">{$first_page}{$previous_page}&nbsp;</td>
		<td class="center multipage_middle">
			{$page}&nbsp; {foreach $loop as $l}{if $l.i!=$first_link_page}{$STR_MULTIPAGE_SEPARATOR}{/if}{$l.page}{/foreach}
		</td>
		<td class="multipage_right">&nbsp;{$next_page}{$last_page}</td>
		<td class="center">
			{$results_per_page}
		</td>
	</tr>
</table>