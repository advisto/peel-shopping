{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: payment_form.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}{if $type == 'check'}
<p><b>{$STR_FOR_A_CHECK_PAYMENT}</b></p>
<p>- <a href="{$commande_pdf_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_PRINT_PROFORMA}</a></p>
<p>- {$STR_SEND_CHECK} <b>{$amount_to_pay_formatted}</b> {$STR_FOLLOWING_ADDRESS}{$STR_BEFORE_TWO_POINTS}:<br />{$societe}</p>
{elseif $type == 'transfer'}
<p><b>{$STR_FOR_A_TRANSFERT}</b></p>
<p>- <a href="{$commande_pdf_href|escape:'html'}" onclick="return(window.open(this.href)?false:true);">{$STR_PRINT_PROFORMA}</a></p>
<p>- {$STR_SEND_TRANSFER} <b>{$amount_to_pay_formatted}</b> {$STR_FOLLOWING_ACCOUNT}{$STR_BEFORE_TWO_POINTS}:<br />{$rib}</p>
{elseif $type == 'paypal' AND isset($form)}
	<div class="center">
	{$STR_FOR_A_PAYPAL_PAYMENT}<br />
	{$form}
	<br />
	{$paypal_img_html}
	</div>
{elseif isset($form)}
	<div class="center">{$form}</div>
{/if}
{if isset($js_action) AND isset($autosend_delay)}
<script><!--//--><![CDATA[//><!--
	setTimeout ('{$js_action}', {$autosend_delay});
//--><!]]></script>
{/if}