{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: haut.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
#}<!DOCTYPE html>
<html lang="{{ lang }}" dir="ltr">
	{{ HTML_HEAD }}
	<body vocab="http://schema.org/" typeof="WebPage">
	{% if (update_msg) %}
		<div class="center" style="font-size:14px;font-weight:bold;"><br /><br />{{ update_msg }}<br /><br /></div>
	{% endif %}
		{% if (auto_login_with_facebook) %}{{ auto_login_with_facebook }}{% endif %}
		{% if (logout_with_facebook) %}{{ logout_with_facebook }}{% endif %}
		
		{% if (welcome_ad_div) %}{{ welcome_ad_div }}{% endif %}
		{% if (cart_popup_div) %}{{ cart_popup_div }}{% endif %}
		
		<!-- Début Total -->
		<div id="total" class="clearfix">
			<!-- Début header -->
			{% if CONTENT_HEADER %}<div class="page_warning alert-dismissable"><div class="container"><div class="row"><div class="col-sm-12">{{ CONTENT_HEADER }} <button type="button" class="close remember-close" data-dismiss="alert" id="page_warning_close">×</button></div></div></div></div>{% endif %}
			<header id="main_header">
				<div class="navbar yamm navbar-default navbar-static-top">
					<div class="navbar-inner">
						<div class="container">
							<div class="navbar-header">
								{% if (logo_link) and logo_link.src %}
								<div class="navbar-brand"><a href="{{ logo_link.href }}"><img src="{{ logo_link.src }}" alt="" /></a></div>
								{% endif %}
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<div id="flags" class="pull-right hidden-xs">{% if (flags_links_array) %}{{ flags_links_array|join(' &nbsp;') }}{% endif %}{{ flags }}</div>
								{% if (module_devise) %}<div id="currencies" class="pull-right hidden-xs">{{ module_devise }}</div>{% endif %}
								{% if show_open_account %}
								<div id="header_signin" class="pull-right hidden-xs">
									<a href="{{ account_register_url }}" class="btn btn-default">{{ STR_OPEN_ACCOUNT }}</a>
								</div>
								{% endif %}
								<div id="header_login" class="pull-right">
									{# {% if not est_identifie %}<a href="compte.php" class="btn btn-default"><span class="glyphicon glyphicon-user header_user"></span>{{ STR_LOGIN }}</a>{% else %}<span class="glyphicon glyphicon-user header_user"></span><a href="compte.php" class="btn btn-default">{{ session_utilisateur_email }} <span class="caret"></span>{/if}</a> #}
									<div class="dropdown">
										<a class="dropdown-toggle btn btn-default" href="#" data-toggle="dropdown"><span class="visible-xs"><span class="glyphicon glyphicon-user header_user"></span><span class="caret"></span></span><span class="hidden-xs"><span class="glyphicon glyphicon-user header_user"></span><span class="header_user_text">{% if not est_identifie %}{{ STR_LOGIN }}{% else %}{{ session_utilisateur_email }}{% endif %} <span class="caret"></span></span></span></a>
										<div class="dropdown-menu">
											{{ account_dropdown }}
										</div>
									</div>
								</div>
								{{ header_html }}
								{{ MODULES_HEADER }}
							</div>
						</div>
					</div>
				</div>
			</header>
			<!-- Fin Header -->
			{% if CONTENT_SCROLLING != '' %}
			<marquee onmouseout="this.start();" onmouseover="this.stop();" truespeed="1" scrollamount="3" scrolldelay="40">
				{{ CONTENT_SCROLLING }}
			</marquee>
			{% endif %}
			
			<!-- Début main_content -->
			<div id="main_content" class="column_{{ page_columns_count }}" style="clear:both">
				{% if (CARROUSEL_CATEGORIE) %}{{ CARROUSEL_CATEGORIE }}{% endif %}
				
				{% if page_columns_count > 1 %}
				<!-- Début left_column -->
				<div class="side_column left_column container">
					<div class="row">
						{% if appstore_link %}
						<a href="{{ appstore_link|escape('html') }}" class="appstore_link"><img src="{{ appstore_image|escape('html') }}" alt="Download on AppStore" style="width:100%" /></a>
						{% endif %}
						{{ MODULES_LEFT }}
						{% if (user_information_boutique) %}{{ user_information_boutique }}{% endif %}
					</div>
				</div>
				<!-- Fin left_column -->   
				{% endif %}
				
				<!-- Début middle_column -->
				<div class="middle_column container">
					{% if (ariane_panier) %}{{ ariane_panier }}{% endif %}
					
					<div class="middle_column_header">&nbsp;</div>
					<div class="middle_column_repeat row">
						<div class="col-md-12">
							<a href="#" id="haut_de_page"></a>
							{{ MODULES_TOP_MIDDLE }}
							{{ output_create_or_update_order }}
							{{ error_text_to_display }}