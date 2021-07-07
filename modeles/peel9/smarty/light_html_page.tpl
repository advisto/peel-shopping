{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: light_html_page.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}<!DOCTYPE html>
<html lang="{$lang}" dir="ltr" class="light">
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
		{if isset($peel_debug)}
			<div class="clearfix"></div>
			{foreach $peel_debug as $key => $item_arr}
			<span {if $item_arr.duration<0.010}style="color:grey"{else}{if $item_arr.duration>0.100}style="color:red"{/if}{/if}>{$key}{$STR_BEFORE_TWO_POINTS}: {{math equation="x*y" x=$item_arr.duration y=1000}|string_format:'%04d'} ms - Start{$STR_BEFORE_TWO_POINTS}{{math equation="x*y" x=$item_arr.start y=1000}|string_format:'%04d'} ms - {if isset($item_arr.sql)}{$item_arr.sql}{/if}{if isset($item_arr.template)}{$item_arr.template}{/if}{if isset($item_arr.text)}{$item_arr.text}{/if}</span><br />
			{/foreach}
		{/if}
		
	</body>
</html>