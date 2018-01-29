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
// $Id: admin_liste_meta.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_META_PAGE_TITLE}</div>
<div class="btn btn-default" style="margin-top:10px; margin-bottom: 10px"><span id="search_icon" class="glyphicon glyphicon-plus"></span> <a href="{$administrer_url}/meta.php?mode=ajout">{$STR_ADMIN_ADD}</a></div>
<table class="full_width">
{if isset($results)}
	{foreach $results as $res}
	<tr>
		<td>
			<a href="{$res.drop_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" /></a> <a title="{$STR_ADMIN_META_UPDATE|str_form_value}" href="{$res.href|escape:'html'}">{$res.technical_code} - {$res.anchor} - {$res.site_name}</a>
		</td>
	</tr>
	{/foreach}
{else}
	<tr><td><div class="alert alert-info">{$STR_ADMIN_META_EMPTY_EXPLAIN}</div></td></tr>
{/if}
</table>