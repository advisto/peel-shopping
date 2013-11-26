{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: guide_advistofr_module.tpl 38760 2013-11-16 21:47:38Z gboussin $
*}<ul>
	{$menu_contenu}
	{foreach $links as $l}
		<li class="minus {if $l.selected}active{/if} m_item_contact"><a href="{$l.href|escape:'html'}">{$l.label}</a></li>
	{/foreach}
</ul>