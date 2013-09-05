{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: haut.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<!DOCTYPE html>
<html lang="{$lang}" dir="ltr">
	{$HTML_HEAD}
	<body vocab="http://schema.org/" typeof="WebPage">
	{if isset($update_msg)}
		<div align="center" style="font-size:14px;font-weight:bold;"><br /><br />{$update_msg}<br /><br /></div>
	{/if}
		{if isset($auto_login_with_facebook)}{$auto_login_with_facebook}{/if}
		{if isset($logout_with_facebook)}{$logout_with_facebook}{/if}
		
		{if isset($welcome_ad_div)}{$welcome_ad_div}{/if}
		{if isset($cart_popup_div)}{$cart_popup_div}{/if}
		
		<div id="overDiv"></div>
		<!-- Début Total -->
		<div id="total">
			<!-- Début header -->
			<div id="main_header">
				<div id="flags">{*' &nbsp;'|implode:$flags_links_array*}{$flags}</div>
				{if isset($module_devise)}{$module_devise}{/if}
				
				<div class="main_logo">
					{if isset($logo_link) && $logo_link.src}
					<a href="{$logo_link.href}"><img src="{$logo_link.src}" alt="" /></a>
					{/if}
				</div>
				{$header_html}
				{$MODULES_HEADER}
				{$CONTENT_HEADER}
			</div>
			<!-- Fin Header -->
			{$MODULES_ARIANE}
			{if $CONTENT_SCROLLING != ''}
			<marquee onmouseout="this.start();" onmouseover="this.stop();" truespeed="1" scrollamount="3" scrolldelay="40">
				{$CONTENT_SCROLLING}
			</marquee>
			{/if}
			
			<!-- Début main_content -->
			<div id="main_content" class="column_{$page_columns_count}">
				{if isset($CARROUSEL_CATEGORIE)}{$CARROUSEL_CATEGORIE}{/if}
				
				{if $page_columns_count > 1}
				<!-- Début left_column -->
				<div class="side_column left_column">
					{if !empty($appstore_link)}
					<a href="{$appstore_link|escape:'html'}" class="appstore_link"><img src="{$appstore_image|escape:'html'}" alt="Download on AppStore" style="width:100%" /></a>
					{/if}
					{$MODULES_LEFT}
					{if isset($user_information_boutique)}{$user_information_boutique}{/if}
				</div>
				<!-- Fin left_column -->   
				{/if}
				
				<!-- Début middle_column -->
				<div class="middle_column">
					{if isset($ariane_panier)}{$ariane_panier}{/if}
					
					<div class="middle_column_header">&nbsp;</div>
					<div class="middle_column_repeat">
						<table class="full_width">
							<tr>
								<td>
									<a href="#" id="haut_de_page"></a>
									{$MODULES_TOP_MIDDLE}
									{$error_text_to_display}