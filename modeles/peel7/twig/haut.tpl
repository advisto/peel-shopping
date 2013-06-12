{# Twig
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
// $Id: haut.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<!DOCTYPE html>
<html lang="{{ lang }}" dir="ltr">
	{{ HTML_HEAD }}
	<body vocab="http://schema.org/" typeof="WebPage">
	{% if (update_msg) %}
		<div align="center" style="font-size:14px;font-weight:bold;"><br /><br />{{ update_msg }}<br /><br /></div>
	{% endif %}
		{% if (auto_login_with_facebook) %}{{ auto_login_with_facebook }}{% endif %}
		{% if (logout_with_facebook) %}{{ logout_with_facebook }}{% endif %}
		
		{% if (welcome_ad_div) %}{{ welcome_ad_div }}{% endif %}
		{% if (cart_popup_div) %}{{ cart_popup_div }}{% endif %}
		
		<div id="overDiv"></div>
		<!-- Début Total -->
		<div id="total">
			<!-- Début header -->
			<div id="main_header">
				<div id="flags">{#' &nbsp;'|implode:$flags_links_array#}{{ flags }}</div>
				{% if (module_devise) %}{{ module_devise }}{% endif %}
				
				<div class="main_logo">
					{% if (logo_link) and logo_link.src %}
					<a href="{{ logo_link.href }}"><img src="{{ logo_link.src }}" alt="" /></a>
					{% endif %}
				</div>
				{{ header_html }}
				{{ MODULES_HEADER }}
				{{ CONTENT_HEADER }}
			</div>
			<!-- Fin Header -->
			{{ MODULES_ARIANE }}
			{% if CONTENT_SCROLLING != '' %}
			<marquee onmouseout="this.start();" onmouseover="this.stop();" truespeed="1" scrollamount="3" scrolldelay="40">
				{{ CONTENT_SCROLLING }}
			</marquee>
			{% endif %}
			
			<!-- Début main_content -->
			<div id="main_content" class="column_{{ page_columns_count }}">
				{% if (CARROUSEL_CATEGORIE) %}{{ CARROUSEL_CATEGORIE }}{% endif %}
				
				{% if page_columns_count > 1 %}
				<!-- Début left_column -->
				<div class="side_column left_column">
					{{ MODULES_LEFT }}
					{% if (user_information_boutique) %}{{ user_information_boutique }}{% endif %}
				</div>
				<!-- Fin left_column -->   
				{% endif %}
				
				<!-- Début middle_column -->
				<div class="middle_column">
					{% if (ariane_panier) %}{{ ariane_panier }}{% endif %}
					
					<div class="middle_column_header">&nbsp;</div>
					<div class="middle_column_repeat">
						<table class="full_width">
							<tr>
								<td>
									<a href="#" id="haut_de_page"></a>
									{{ MODULES_TOP_MIDDLE }}
									{{ error_text_to_display }}