{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: guide.tpl 60372 2019-04-12 12:35:34Z sdelaporte $
*}
{if !$affiche_guide_returned_link_list_without_ul}
<ul>
{/if}
	{foreach $links as $l}
	<li class="minus"><a href="{$l.href|escape:'html'}">{if $l.selected}<b>{/if}{$l.label}{if $l.selected}</b>{/if}</a></li>
	{/foreach}
	{if !empty($menu_contenu)}
		{$menu_contenu}
	{/if}
{if !$affiche_guide_returned_link_list_without_ul}
</ul>
{/if}