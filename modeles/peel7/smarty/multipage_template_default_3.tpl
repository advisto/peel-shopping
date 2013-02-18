{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: multipage_template_default_3.tpl 35103 2013-02-10 22:17:14Z gboussin $
*}<table class="multipage-area">
{if $total_page>1 && !$show_page_if_only_one}
	<tr class="multipage middle">
		<td class="multipage_left">{$first_page}{$previous_page}&nbsp;</td>
		<td class="center multipage_middle">
			{foreach $loop as $l}{$l.page} {/foreach}
		</td>
		<td class="multipage_right">&nbsp;{$next_page}{$last_page}</td>
	</tr>
{/if}
	<tr class="middle">
		<td class="center" {if $total_page>1 && !$show_page_if_only_one}colspan="3"{/if}>
			{$results_per_page}
		</td>
	</tr>
</table>