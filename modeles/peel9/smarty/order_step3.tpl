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
// $Id: order_step3.tpl 60372 2019-04-12 12:35:34Z sdelaporte $
*}<h1 property="name" class="order_step3">{$STR_STEP3}</h1>
<p>{$STR_MSG_THANKS}</p>
{if !empty($payment_form)}
{$payment_form}<br />
{/if}
<fieldset>
	{$resume_commande}
</fieldset>
{$conversion_page}