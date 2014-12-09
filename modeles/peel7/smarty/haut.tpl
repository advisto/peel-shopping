{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: haut.tpl 43252 2014-11-18 10:16:54Z sdelaporte $
*}<!DOCTYPE html>
<html lang="{$lang}" dir="ltr" {if isset($facebook_xmls)}{$facebook_xmls}{/if}>
	{$HTML_HEAD}
	<body vocab="http://schema.org/" typeof="WebPage">
	{if isset($update_msg)}
		<div class="center" style="font-size:14px;font-weight:bold;"><br /><br />{$update_msg}<br /><br /></div>
	{/if}
		{if isset($auto_login_with_facebook)}{$auto_login_with_facebook}{/if}
		{if isset($logout_with_facebook)}{$logout_with_facebook}{/if}
		
		{if isset($welcome_ad_div)}{$welcome_ad_div}{/if}
		{if isset($cart_popup_div)}{$cart_popup_div}{/if}
		
		<!-- Début Total -->
		<div id="total" class="clearfix">
			<!-- Début header -->
			{if $CONTENT_HEADER}<div class="page_warning alert-dismissable"><div class="container"><div class="row"><div class="col-sm-12">{$CONTENT_HEADER} <button type="button" class="close remember-close" data-dismiss="alert" id="page_warning_close">×</button></div></div></div></div>{/if}
			<header id="main_header">
				<div class="navbar yamm navbar-default navbar-static-top">
					<div class="navbar-inner">
						<div class="container">
							<div class="navbar-header">
								{if isset($logo_link) && $logo_link.src}
								<div class="navbar-brand"><a href="{$logo_link.href}"><img src="{$logo_link.src}" alt="" /></a></div>
								{/if}
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								{if !empty($flags_links_array) || !empty($flags)}<div id="flags" class="pull-right hidden-xs">{if !empty($flags_links_array)}{'&nbsp;'|implode:$flags_links_array}{/if}{$flags}</div>{/if}
								{if !empty($module_devise)}<div id="currencies" class="pull-right hidden-xs">{$module_devise}</div>{/if}
								{if $show_open_account}
								<div id="header_signin" class="pull-right hidden-xs">
									<a href="{$account_register_url}" class="btn btn-default">{$STR_OPEN_ACCOUNT}</a>
								</div>
								{/if}
								{if empty($disable_header_login)}
								<div id="header_login" class="pull-right">
									{* {if !$est_identifie}<a href="compte.php" class="btn btn-default"><span class="glyphicon glyphicon-user header_user"></span>{$STR_LOGIN}</a>{else}<span class="glyphicon glyphicon-user header_user"></span><a href="compte.php" class="btn btn-default">{$session_utilisateur_email} <span class="caret"></span>{/if}</a> *}
									<div class="dropdown">
										<a class="dropdown-toggle btn btn-default" href="#" data-toggle="dropdown"><span class="visible-xs"><span class="glyphicon glyphicon-user header_user"></span><span class="caret"></span></span><span class="hidden-xs"><span class="glyphicon glyphicon-user header_user"></span><span class="header_user_text">{if !$est_identifie}{$STR_LOGIN}{else}{$session_utilisateur_email}{/if} <span class="caret"></span></span></span></a>
										<div class="dropdown-menu">
											{$account_dropdown}
										</div>
									</div>
								</div>
								{/if}
								{$MODULES_HEADER}
							</div>
						</div>
					</div>
				</div>
				<div class="container">{$category_introduction_text}</div>
			</header>
			<!-- Fin Header -->
			{if $CONTENT_SCROLLING != ''}
			<marquee onmouseout="this.start();" onmouseover="this.stop();" truespeed="1" scrollamount="3" scrolldelay="40">
				{$CONTENT_SCROLLING}
			</marquee>
			{/if}
			
			<!-- Début main_content -->
			<div id="main_content" class="column_{$page_columns_count}" style="clear:both">
				{if $page_columns_count > 1}
				<!-- Début left_column -->
				<div class="side_column left_column container">
					<div class="row">
						{if !empty($appstore_link)}
						<a href="{$appstore_link|escape:'html'}" class="appstore_link"><img src="{$appstore_image|escape:'html'}" alt="Download on AppStore" style="width:100%" /></a>
						{/if}
						{$MODULES_LEFT}
						{if isset($user_information_boutique)}{$user_information_boutique}{/if}
					</div>
				</div>
				<!-- Fin left_column -->   
				{/if}
				
				<!-- Début middle_column -->
				<div class="middle_column container">
					{if isset($ariane_panier)}{$ariane_panier}{/if}
					
					<div class="middle_column_header">&nbsp;</div>
					<div class="middle_column_repeat row">
						{if isset($CARROUSEL_CATEGORIE)}{$CARROUSEL_CATEGORIE}{/if}
						<div class="col-md-12">
							<a href="#" id="haut_de_page"></a>
							{$MODULES_TOP_MIDDLE}
							{$output_create_or_update_order}
							{$error_text_to_display}