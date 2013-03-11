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
// $Id: admin_haut.tpl 35805 2013-03-10 20:43:50Z gboussin $
*}<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="{$GENERAL_ENCODING}" />
	<meta name="robots" content="noindex,nofollow" />
	<title>{$doc_title}</title>
	{if isset($favicon_href)}<link rel="icon" type="image/x-icon" href="{$favicon_href}" />
	<link rel="shortcut icon" type="image/x-icon" href="{$favicon_href}" />{/if}
{if !empty($css_files)}
	{foreach $css_files as $css_href}
	<link rel="stylesheet" media="all" href="{$css_href|escape:'html'}" />
	{/foreach}
{/if}
{if !empty($js_files)}
	{foreach $js_files as $js_href}
	<script src="{$js_href|escape:'html'}"></script>
	{/foreach}
{/if}
{if $js_content}
	<script><!--//--><![CDATA[//><!--
		{$js_content}
	//--><!]]></script>
{/if}
</head>
<body>
	<!-- Début Total -->
	<div id="total">
		<div class="main_header_wide">
			<div class="main_header">
				<div id="flags">{*' &nbsp;'|implode:$flags_links_array*}{$flags}</div>
				<div id="page_title">{$page_title}</div>
				<div class="main_logo"><a href="{$administrer_url}/"><img src="{$logo_src|escape:'html'}" alt="{$site}" /></a></div>
				<div class="header_few_words_center">{$admin_welcome}</div>
				<div class="header_few_words_right">{$STR_ADMINISTRATION}</div>
			</div>
		</div>
		<div class="main_menu_wide">
			<div class="main_menu">
				{$admin_menu}
			</div>
		</div>
		<div class="main_content">
{if $is_demo_error}
		<p class="global_error">{$STR_ADMIN_DEMO_WARNING}</p>
{/if}	