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
// $Id: light_html_page.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<!DOCTYPE html>
<html lang="{$lang}" dir="ltr">
{if !$full_head_section_text}
	<head>
		{if !empty($charset)}<meta charset="{$charset}" />{/if}
		<title>{$title}</title>
		{$additional_header}
	{if !empty($css_files)}
		{foreach $css_files as $css_href}
		<link rel="stylesheet" media="all" href="{$css_href|escape:'html'}" />
		{/foreach}
	{/if}
		{if !empty($js_output)}{$js_output}{/if}
		<!--[if lt IE 9]>
		<script src="{$wwwroot}/lib/js/html5shiv.js"></script>
		<script src="{$wwwroot}/lib/js/respond.js"></script>
		<![endif]-->
	</head>
{else}
	{$full_head_section_text}
{/if}
	<body class="light" {if !empty($onload)} onload="{$onload}"{/if} vocab="http://schema.org/">
		{$notification_output}
		{$body}
	</body>
</html>