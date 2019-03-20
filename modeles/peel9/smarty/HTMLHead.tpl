{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: HTMLHead.tpl 59873 2019-02-26 14:47:11Z sdelaporte $
*}
<head{if isset($head_attributes)} {$head_attributes}{/if}>
	{$content_tag_htmlhead}
	{$meta}
	{if isset($favicon_href)}<link rel="icon" type="image/x-icon" href="{$favicon_href}" />
	<link rel="shortcut icon" type="image/x-icon" href="{$favicon_href}" />{/if}
	{if isset($link_rss_html)}{$link_rss_html}{/if}
{foreach $css_files as $css_href}
	<link rel="stylesheet" href="{$css_href|escape:'html'}" {if $css_href|strpos:'.print.'!==false}media="print"{else}media="all"{/if} />
{/foreach}
	<style>
	{if !empty($background_banner)}
	{foreach $background_banner as $this_background}
	#main_content{ldelim}
		background:url('{$this_background.url_img}') no-repeat scroll top center rgba(0, 0, 0, 0);
		cursor:pointer;
	{rdelim}
	{/foreach}
	{elseif isset($bg_colors)}
		body {ldelim} background-color:{$bg_colors.body}; {rdelim}
		#menu1 li, .main_menu_wide {ldelim} background-color:{$bg_colors.menu}; {rdelim}
		<!--[if IE]>
			#contact_form{ldelim}height:100% !important;{rdelim}
		<![endif]-->
	{/if}
	{if !empty($site_static_css_style)}
		{$site_static_css_style}
	{/if}
	</style>
	{$js_output}
	{$css_output}
	<!--[if lt IE 9]>
	<script src="{$wwwroot}/lib/js/html5shiv.js"></script>
    <script src="{$wwwroot}/lib/js/respond.js"></script>
	<![endif]-->
</head>