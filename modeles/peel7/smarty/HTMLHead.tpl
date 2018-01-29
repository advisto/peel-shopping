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
// $Id: HTMLHead.tpl 55930 2018-01-29 08:36:36Z sdelaporte $
*}
<head{if isset($head_attributes)} {$head_attributes}{/if}>
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