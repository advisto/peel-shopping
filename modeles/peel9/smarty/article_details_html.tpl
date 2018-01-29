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
// $Id: article_details_html.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if !$is_article}
	{$STR_NO_FIND_ART}
{else}
	{if $titre}<h1 property="name" class="page_title">{$titre|html_entity_decode_if_needed}</h1>{/if}
	<div class="rub_content">
		{if $is_offline}
		<p style="color: red;">{$STR_OFFLINE_ART}</p>
		{/if}
		{if isset($main_image)}
			{if $main_image.file_type != 'image'}
				<a style="margin: 5px;" href="{$main_image.href|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$main_image.src|escape:'html'}" alt="{$titre}" /></a>
			{else}
				<p><img style="margin: 5px;" src="{$main_image.src|escape:'html'}" alt="{$titre}" /></p>
			{/if}
		{/if}
		{if isset($chapo)}<div style="text-align:justify;">{$chapo|html_entity_decode_if_needed|nl2br_if_needed}</div>{/if}
		{$texte|html_entity_decode_if_needed|nl2br_if_needed}
		{if isset($share_feature)}
			{$share_feature}
		{elseif isset($tell_friends)}
			<p class="right"><img src="{$tell_friends.src|escape:'html'}" alt="{$tell_friends.txt}" />&nbsp;<a href="{$tell_friends.href|escape:'html'}">{$tell_friends.txt}</a></p>
		{/if}
		{if isset($admin)}
		<p><a href="{$admin.href|escape:'html'}" class="title_label">{$admin.modify_article_txt}</a></p>
		{/if}
	</div>
{/if}