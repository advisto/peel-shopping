{# Twig
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
// $Id: haut.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<!DOCTYPE html>
<html lang="{{ lang }}" dir="ltr">
	{{ HTML_HEAD }}
	<body vocab="http://schema.org/" typeof="WebPage">
		{% if auto_login_with_facebook %}{{ auto_login_with_facebook }}{% endif %}
		{% if logout_with_facebook %}{{ logout_with_facebook }}{% endif %}
		
		{% if (welcome_ad_div) %}{{ welcome_ad_div }}{% endif %}
		{% if (cart_popup_div) %}{{ cart_popup_div }}{% endif %}
		
		<!-- Début Total -->
		<div id="total" class="clearfix page_{{ page_name }}">
			<!-- Début header -->
			{% if update_msg is defined %}
			<div class="center" style="font-size:16px; font-weight:bold; padding:10px">{{ update_msg }}</div>
			{% endif %}
			{% if CONTENT_HEADER %}<div class="page_warning alert-dismissable"><div class="container"><div class="row"><div class="col-sm-12">{{ CONTENT_HEADER }} <button type="button" class="close remember-close" data-dismiss="alert" id="page_warning_close">×</button></div></div></div></div>{% endif %}
			<header id="main_header">
				<div class="navbar yamm navbar-default navbar-static-top">
					<div class="navbar-inner">
						<div class="container">
							<div class="navbar-header">
								{% if (logo_link) and logo_link.src %}
								<div class="navbar-brand"><a href="{{ logo_link.href }}"><img src="{{ logo_link.src }}" alt="{{ logo_link.alt|str_form_value }}" /></a>{% if header_custom_baseline_html %}{{ header_custom_baseline_html }}{% endif %}</div>
								{% endif %}
								
								{% if multi_logo_header is empty %}
									{% if (logo_link) and logo_link.src %}
									<div class="navbar-brand"><a href="{{ logo_link.href }}"><img src="{{ logo_link.src }}" alt="{{ logo_link.alt|str_form_value }}" /></a>{% if header_custom_baseline_html %}{{ header_custom_baseline_html }}{% endif %}</div>
									{% endif %}
								{% else %}
									{% for items in multi_logo_header %}
										<div class="navbar-brand {{ items.class|escape('html') }}"><a href="{{ items.href }}"><img src="{{ items.src }}" alt="{{ items.alt|str_form_value }}" /></a>
										{% if header_custom_baseline_html is defined %}{{ header_custom_baseline_html }}{% endif %}</div>
									{% endfor %}
 								{% endif %}
								{% if disable_navbar_toggle is empty %}
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								{% endif %}
								{% if (flags_links_array) or (flags) %}<div id="flags" class="pull-right hidden-xs">{% if (flags_links_array) %}{{ flags_links_array|join(' &nbsp;') }}{% endif %}{{ flags }}</div>{% endif %}
								{% if (module_devise) %}<div id="currencies" class="pull-right hidden-xs">{{ module_devise }}</div>{% endif %}
								{% if show_open_account %}
								<div id="header_signin" class="pull-right hidden-xs">
									<a href="{{ account_register_url }}" class="btn btn-default">{{ STR_OPEN_ACCOUNT }}</a>
								</div>
								{% endif %}
								{% if not(disable_header_login) %}
								<div id="header_login" class="pull-right">
									{# {% if not est_identifie %}<a href="compte.php" class="btn btn-default"><span class="glyphicon glyphicon-user header_user"></span>{{ STR_LOGIN }}</a>{% else %}<span class="glyphicon glyphicon-user header_user"></span><a href="compte.php" class="btn btn-default">{{ session_utilisateur_email }} <span class="caret"></span>{% endif %}</a> #}
									<div class="dropdown">
										<a class="dropdown-toggle btn btn-default" href="#" data-toggle="dropdown"> <span class="visible-xs"> {% if user_logo_src is empty %} <span class="glyphicon glyphicon-user header_user"></span> {% else %} <img src="{{ user_logo_src }}" class="glyphicon header_user" alt="" /> {% endif %} <span class="caret"></span></span> <span class="hidden-xs"> {% if user_logo_src is empty %} <span class="glyphicon glyphicon-user header_user"></span> {% else %}<img src="{{ user_logo_src }}" class="glyphicon header_user" alt="" /> {% endif %}<span class="header_user_text"><span class="header_user_text_inside"> {% if not est_identifie %}{{ STR_LOGIN }}{% else %}{{ session_utilisateur_email }}{% endif %} </span> <span class="caret"></span></span></span></a>
										<div class="dropdown-menu">
											{{ account_dropdown }}
										</div>
									</div>
								</div>
								{% endif %}
								{% if unread_messages_info %}
								<div id="header_message" class="pull-right">
									<div class="dropdown">
										<a href="{{ messaging_url }}" class="dropdown-toggle btn btn-default fa fa-envelope fa-lg">{{ unread_messages_info }}</a>
									</div>
								</div>
								{% endif %}
								{{ MODULES_HEADER }}
								{{ header_custom_html }}
							</div>
						</div>
					</div>
				</div>
				{% if product_category_introduction_text_display_disable is empty %}
					<div class="container">{{ category_introduction_text }}</div>
				{% endif %}
			</header>
			<!-- Fin Header -->
			{% if CONTENT_SCROLLING != '' %}
			<div onmouseout="this.start();" onmouseover="this.stop();" truespeed="1" scrollamount="3" scrolldelay="40">
				{{ CONTENT_SCROLLING }}
			</div>
			{% endif %}
			
			<!-- Début main_content -->
			<div id="main_content" class="column_{{ page_columns_count }}" style="clear:both">
				{% if MODULES_ABOVE_MIDDLE %}
				<!-- Début above_middle -->
				<div class="above_middle container">
					<div class="row">
						{{ MODULES_ABOVE_MIDDLE }}
						{% if user_information_boutique is defined %}{{ user_information_boutique }}{% endif %}
					</div>
				</div>
				<!-- Fin above_middle -->   
				{% endif %}
				<div class="container">
					<div class="row">
						{% if MODULES_LEFT %}
						<!-- Début left_column -->
						<div class="side_column left_column col-sm-3 col-lg-2">
							{% if appstore_link %}
							<a href="{{ appstore_link|escape('html') }}" class="appstore_link"><img src="{{ appstore_image|escape('html') }}" alt="Download on AppStore" style="width:100%" /></a>
						{% endif %}
							{{ MODULES_LEFT }}
						</div>
						<!-- Fin left_column -->   
				{% endif %}

						<!-- Début middle_column -->
						<div class="middle_column {% if MODULES_LEFT and MODULES_RIGHT %}col-sm-6 col-lg-8{% elseif MODULES_LEFT or MODULES_RIGHT %}col-sm-9 col-lg-10{% else %}col-sm-12{% endif %}">
					{% if (ariane_panier) %}{{ ariane_panier }}{% endif %}
							<div class="middle_column_header">&nbsp;</div>
							<div class="middle_column_repeat row">
						{% if (CARROUSEL_CATEGORIE) %}{{ CARROUSEL_CATEGORIE }}{% endif %}
								<a href="#" id="haut_de_page"></a>
							{{ MODULES_TOP_MIDDLE }}
							<span class="clearfix"></span>
							{{ output_create_or_update_order }}
							{{ notification_output }}