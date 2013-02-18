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
// $Id: admin_bas.tpl 35064 2013-02-08 14:16:40Z gboussin $
*}</div>
		<div class="main_footer_wide"><div class="main_footer"><a href="{$site_href|escape:'html'}" style="margin-right:70px;">{$site}</a> <a href="{$peel_website_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_ADMIN_PEEL_SOFTWARE}</a> - {$STR_ADMIN_VERSION} {$PEEL_VERSION} - <a href="{$sortie_href|escape:'html'}">{$STR_ADMIN_DISCONNECT}</a></div></div>
		<div class="under_footer">{$STR_ADMIN_SUPPORT}{$STR_BEFORE_TWO_POINTS}: <a href="{$peel_website_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_ADMIN_CONTACT_PEEL}</a> - {$STR_ADMIN_CONTACT_PEEL_ADDRESS}</div>
	</div>
	<!-- Fin Total -->
	{if isset($peel_debug)}
		{foreach $peel_debug as $key => $item_arr}
			<span {if $item_arr.duration<0.010}style="color:grey"{else}{if $item_arr.duration>0.100}style="color:red"{/if}{/if}>{$key}{$STR_BEFORE_TWO_POINTS}: {{math equation="x*y" x=$item_arr.duration y=1000}|string_format:'%04d'} ms - {if isset($item_arr.sql)}{$item_arr.sql}{/if} {if isset($item_arr.template)}{$item_arr.template}{/if}</span><br />
		{/foreach}
	{/if}
</body>
</html>