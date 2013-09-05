{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: global_error.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<p class="global_error">{if isset($message)}{$message}{/if}{if isset($message_to_escape)}{$message_to_escape|strip_tags|escape:'html'|nl2br_if_needed}{/if}</p>
{if isset($text)}
<p>{$text}</p>
{/if}
{if isset($link)}
<p><a href="{$link.href|escape:'html'}" class="label">{$link.value}</a></p>
{/if}