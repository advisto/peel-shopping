{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: light_html_page.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<!DOCTYPE html>
<html lang="{$lang}" dir="ltr">
{if !$full_head_section_text}
	<head>
		<meta charset="{$charset}" />
		<title>{$title}</title>
		{$additional_header}
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
		<!--[if lt IE 9]>
		<script src="{$wwwroot}/lib/js/html5shiv.js"></script>
		<![endif]-->
	</head>
{else}
	{$full_head_section_text}
{/if}
	<body class="light" {if !empty($onload)} onload="{$onload}"{/if} vocab="http://schema.org/">
		{$body}
	</body>
</html>