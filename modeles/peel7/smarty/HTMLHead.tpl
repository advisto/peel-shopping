{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: HTMLHead.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
*}
<head>
	{$meta}
	{if isset($favicon_href)}<link rel="icon" type="image/x-icon" href="{$favicon_href}" />
	<link rel="shortcut icon" type="image/x-icon" href="{$favicon_href}" />{/if}
	{if isset($link_rss_html)}{$link_rss_html}{/if}
{foreach $css_files as $css_href}
	<link rel="stylesheet" media="all" href="{$css_href|escape:'html'}" />
{/foreach}
	{if isset($bg_colors)}
	<style>
		body {ldelim} background-color:{$bg_colors.body}; {rdelim}
		#menu1 li, .main_menu_wide {ldelim} background-color:{$bg_colors.menu}; {rdelim}
		<!--[if IE]>
			#contact_form{ldelim}height:100% !important;{rdelim}
		<![endif]-->
	</style>
	{/if}
	{$js_output}
	<!--[if lt IE 9]>
	<script src="{$wwwroot}/lib/js/html5shiv.js"></script>
    <script src="{$wwwroot}/lib/js/respond.js"></script>
	<![endif]-->
</head>