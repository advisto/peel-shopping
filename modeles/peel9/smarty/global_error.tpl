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
// $Id: global_error.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="clearfix"></div>
<div class="alert alert-danger fade in">
	<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
	{if isset($message)}{$message}{/if}{if isset($message_to_escape)}{$message_to_escape|strip_tags|escape:'html'|nl2br_if_needed}{/if}{if isset($text)}<p>{$text}</p>{/if}{if isset($link)}<p><a href="{$link.href|escape:'html'}" class="alert-link">{$link.value}</a></p>{/if}
</div>
