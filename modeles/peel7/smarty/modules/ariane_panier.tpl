{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: ariane_panier.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<div id="ariane_panier">
	<div class="cart_logo"><img src="{$cart_logo_src|escape:'html'}" alt="" /></div>
	<div class="in_caddie{if $in_caddie} current{elseif $was_in_caddie} visited_before{/if}">
	{if $in_caddie OR $was_in_caddie}
	<a href="{$caddie_affichage_href|escape:'html'}">1 - {$STR_CADDIE}</a>
	{else}
	1 - {$STR_CADDIE}
	{/if}
	</div>
	<div class="in_step1{if $in_step1} current{elseif $was_in_step1}{if $in_caddie} visited_after{else} visited_before{/if}{/if}">
	{if $in_step1 OR $was_in_step1}
	<a href="{$achat_maintenant_href|escape:'html'}">2 - {$STR_PAYMENT_MEAN}</a>
	{else}
	2 - {$STR_PAYMENT_MEAN}
	{/if}
	</div>
	<div class="in_step2{if $in_step2} current{elseif $was_in_step2 AND $in_step3} visited_before{/if}">
	3 - {$STR_MODULE_ARIANE_PANIER_SOMMARY}
	</div>
	<div class="in_step3{if $in_step3} current{/if}">4 - {$STR_CONFIRMATION}</div>
</div><div class="clear"></div>