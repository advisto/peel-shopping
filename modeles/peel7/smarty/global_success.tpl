{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: global_success.tpl 36232 2013-04-05 13:16:01Z gboussin $
*}<p class="global_success">{if isset($message)}{$message}{/if}{if isset($message_to_escape)}{$message_to_escape|strip_tags|escape:'html'|nl2br_if_needed}{/if}</p>
{if isset($text)}
<p>{$text}</p>
{/if}
{if isset($list_content)}
<ul>{$list_content}</ul>
{/if}