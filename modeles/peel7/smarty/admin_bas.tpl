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
// $Id: admin_bas.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}				</div>
			</div>
		</div>
		<div class="push"></div>
	</div>
	<div id="footer">
		<div class="container">
			<footer class="footer">
				<div class="main_footer_wide"><div class="main_footer">{if !empty($site)}<a href="{$site_href|escape:'html'}">{$site}</a> - {/if}<a href="{$peel_website_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_ADMIN_PEEL_SOFTWARE}</a> - {$STR_ADMIN_VERSION} {$PEEL_VERSION}</div></div>
				<div class="under_footer">{$STR_ADMIN_SUPPORT}{$STR_BEFORE_TWO_POINTS}: <a href="{$peel_support_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_ADMIN_CONTACT_PEEL}</a> -  <a href="https://www.advisto.fr/" onclick="return(window.open(this.href)?false:true);">{$STR_ADMIN_CONTACT_PEEL_ADDRESS}</a></div>
			</footer>
		</div>
	</div>
	<!-- Fin Total -->
	{$js_output}
	{if isset($peel_debug)}
		{foreach $peel_debug as $key => $item_arr}
			<span {if $item_arr.duration<0.010}style="color:grey"{else}{if $item_arr.duration>0.100}style="color:red"{/if}{/if}>{$key}{$STR_BEFORE_TWO_POINTS}: {{math equation="x*y" x=$item_arr.duration y=1000}|string_format:'%04d'} ms - Start{$STR_BEFORE_TWO_POINTS}{{math equation="x*y" x=$item_arr.start y=1000}|string_format:'%04d'} ms - {if isset($item_arr.sql)}{$item_arr.sql}{/if}{if isset($item_arr.template)}{$item_arr.template}{/if}{if isset($item_arr.text)}{$item_arr.text}{/if}</span><br />
		{/foreach}
	{/if}
</body>
</html>