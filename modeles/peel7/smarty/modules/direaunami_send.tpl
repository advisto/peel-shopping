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
// $Id: direaunami_send.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<table class="direaunami_send">
	<tr>
		<td>
			<h2>{$STR_TELL_FRIEND}</h2>
			{if $is_error}
				<div class="global_error">{$STR_MODULE_DIREAUNAMI_MSG_ERR_FRIEND|nl2br_if_needed}</div>
			{else}
				<div class="global_success">{$STR_MODULE_DIREAUNAMI_MSG_FRIEND_SEND}</div>
				<p><a href="{$referer}">{$STR_MODULE_DIREAUNAMI_BACK_REFERER}</a></p>
			{/if}
		</td>
	</tr>
</table>