{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_meta.tpl 38836 2013-11-19 14:54:55Z gboussin $
*}<div class="entete">{$STR_ADMIN_META_PAGE_TITLE}</div>
<div class="btn btn-default" style="margin-top:10px; margin-bottom: 10px"><span id="search_icon" class="glyphicon glyphicon-plus"></span> <a href="{$administrer_url}/meta.php?mode=ajout">{$STR_ADMIN_ADD}</a></div>
<table class="full_width">
	<tr>
	</tr>
	{if isset($results)}
	{foreach $results as $res}
	<tr>
		<td>
			<a href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" /> <a title="{$STR_ADMIN_META_UPDATE|str_form_value}" href="{$res.href|escape:'html'}">{$res.technical_code} - {$res.anchor}</a>
		</td>
	</tr>
	{/foreach}
	{else}
	<tr><td><div class="alert alert-info"><b>{$STR_ADMIN_META_EMPTY_EXPLAIN}</b></div></td></tr>
	{/if}
</table>