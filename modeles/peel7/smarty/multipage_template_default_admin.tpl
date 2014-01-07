{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: multipage_template_default_admin.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
*}<div class="multipage-area">
	<div class="center">
		{$results_per_page}
	</div>
	{if $total_page>1 && !$show_page_if_only_one}
	<div class="multipage">
		<ul class="pagination">
			{if $first_page}<li>{$first_page}</li>{/if}
			{if $previous_page}<li>{$previous_page}</li>{/if}
			{foreach $loop as $l}<li{if $l.i==$current_page} class="active"{/if}>{$l.page}</li>{/foreach}
			{if $next_page}<li>{$next_page}</li>{/if}
			{if $last_page}<li>{$last_page}</li>{/if}
		</ul>
	</div>
	{/if}
</div>