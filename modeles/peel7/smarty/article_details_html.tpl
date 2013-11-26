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
// $Id: article_details_html.tpl 38682 2013-11-13 11:35:48Z gboussin $
*}{if !$is_article}
	{$STR_NO_FIND_ART}
{else}
	<h1 class="page_title">{$titre|html_entity_decode_if_needed}</h1>
	<div class="rub_content">
		{if $is_offline}
		<p style="color: red;">{$STR_OFFLINE_ART}</p>
		{/if}
		<div style="padding-top:5px;">
		{if isset($main_image)}
			{if $main_image.is_pdf}
				<a style="margin: 5px;" href="{$main_image.href|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$wwwroot}/images/logoPDF_small.png" alt="{$titre}" /></a>
			{else}
				<p><img style="margin: 5px;" src="{$main_image.href|escape:'html'}" alt="{$titre}" /></p>
			{/if}
		{/if}
			<div style="text-align:justify;">{$chapo|html_entity_decode_if_needed|nl2br_if_needed}</div>
			<div style="text-align:justify;">{$texte|html_entity_decode_if_needed|nl2br_if_needed}</div>
			{if isset($share_feature)}
				{$share_feature}
			{elseif isset($tell_friends)}
				<p class="right"><img src="{$tell_friends.src|escape:'html'}" alt="{$tell_friends.txt}" />&nbsp;<a href="{$tell_friends.href|escape:'html'}">{$tell_friends.txt}</a></p>
			{/if}
		</div>
		{if isset($admin)}
		<p><a href="{$admin.href|escape:'html'}" class="title_label">{$admin.modify_article_txt}</a></p>
		{/if}
	</div>
{/if}