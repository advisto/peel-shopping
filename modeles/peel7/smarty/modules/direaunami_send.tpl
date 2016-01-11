{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: direaunami_send.tpl 48447 2016-01-11 08:40:08Z sdelaporte $
*}<h1 property="name">{$STR_TELL_FRIEND}</h1>
{if $is_error}
	<div class="alert alert-danger">{$STR_MODULE_DIREAUNAMI_MSG_ERR_FRIEND|nl2br_if_needed}</div>
{else}
	<div class="alert alert-success">{$STR_MODULE_DIREAUNAMI_MSG_FRIEND_SEND}</div>
	<p><a href="{$referer}">{$STR_MODULE_DIREAUNAMI_BACK_REFERER}</a></p>
{/if}