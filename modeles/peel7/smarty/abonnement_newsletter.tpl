{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: abonnement_newsletter.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
*}<h1 property="name" class="page_title">{$STR_NEWSLETTER_TITLE}</h1>
<div class="page_content">
{if isset($errors)}
	{$errors.token}
	{$errors.email}
	{$errors.notif}
{else}
	<p class="alert alert-success">{$newsletter_subscribe_txt}</p>
{/if}
</div>