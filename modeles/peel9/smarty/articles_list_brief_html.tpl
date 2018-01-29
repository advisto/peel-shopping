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
// $Id: articles_list_brief_html.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if $is_not_empty && !$title_article_disabled}
	<h1 property="name" class="page_title">{$name|html_entity_decode_if_needed}</h1>
{/if}
<div class="rub_content {if $is_not_empty}{$technical_code}{/if}">
	{if $is_not_empty}
		{if isset($offline_rub_txt)}
	<p style="color: red;">{$offline_rub_txt}</p>
		{/if}
		{if isset($main_image)}
			{if $main_image.file_type != 'image'}
	<a style="margin: 5px;" href="{$main_image.href|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$main_image.src|escape:'html'}" alt="{$name|escape:'html'}" /></a>
			{else}
	<p><img style="margin: 5px;" src="{$main_image.src|escape:'html'}" alt="{$name|escape:'html'}" /></p>
			{/if}
		{/if}
	{$description|html_entity_decode_if_needed|trim|nl2br_if_needed}
		{if isset($descriptions_clients)}
	{$descriptions_clients}
		{/if}
		{if isset($reference_multipage)}
	{$reference_multipage}
		{/if}
	{/if}
	{if isset($add_cart_by_reference)}
	{$add_cart_by_reference}
	{/if}
	{if isset($rubriques_sons_html)}
	{$rubriques_sons_html}
	{/if}
	{if isset($articles_html)}
	{$articles_html}
	{/if}
	{if isset($diaporama)}
	{$diaporama}
	{/if}
	{if isset($admin)}
	<p><a href="{$admin.href|escape:'html'}" class="title_label admin_link">{$admin.modify_content_category_txt}</a></p>
	{/if}
</div>
{if isset($plus)}
<div class="rub_content_plus">
	{foreach $plus.arts as $art}
	<div class="rub_content_plus_item">
		<h4 class="side_titre">{$art.titre|upper|html_entity_decode_if_needed}</h4>
		<div class="side_text">{$art.texte|html_entity_decode_if_needed}</div>
	</div>
	{/foreach}
</div>
{/if}