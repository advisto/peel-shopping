{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_societe_liste.tpl 39392 2013-12-20 11:08:42Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete" colspan="2">{$STR_ADMIN_SOCIETE_LIST_TITLE}</td>
	</tr>
	<tr>
		<td class="menu">{$STR_COMPANY}</td>
		<td class="menu">{$STR_EMAIL}</td>
	</tr>
	{foreach $results as $res}
	<tr>
		<td class="center"><a href="{$res.href|escape:'html'}">{$res.societe}</a></td>
		<td class="center"><a title="{$STR_ADMIN_MENU_MANAGE_WEBMAIL_SEND}" href="mailto:{$res.email}">{$res.email}</a></td>
	</tr>
	{/foreach}
</table>	