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
// $Id: admin_formulaire_site.tpl 53855 2017-05-19 13:42:10Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	{$form_token}
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tab1" data-toggle="tab">{$STR_ADMIN_VARIOUS_INFORMATION_HEADER}</a></li>
			<li><a href="#tab2" data-toggle="tab">{$STR_ADMIN_MODULES}</a></li>
			<li><a href="#tab3" data-toggle="tab">{$STR_ADMIN_SITES_MODULES_POSITIONS}</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="tab1">
				
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_SITES_TITLE}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_MANDATORY}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_GENERAL_PARAMETERS}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SITE_ACTIVATION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="site_suspended" value="false"{if !$site_suspended} checked="checked"{/if} /> {$STR_ADMIN_SITES_SITE_ACTIVATED}
				<input type="radio" name="site_suspended" value="true"{if $site_suspended} checked="checked"{/if} /> {$STR_ADMIN_SITES_SITE_SUSPENDED}
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN}<br />
			<b>{$STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN2}{$STR_BEFORE_TWO_POINTS}: {$membre_admin_href}</b><br />
			{$STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN3}</div></td>
		</tr>
	{foreach $langs as $l}
		<tr>
			<td>{$STR_ADMIN_SITES_SITE_NAME} {$l.lng|upper}{if $l.lng == $session_langue}<span class="etoile">*</span>{/if}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="nom_{$l.lng}" value="{$l.nom|str_form_value}" /></td>
		</tr>
	{/foreach}
		<tr>
			<td>{$STR_ADMIN_WWWROOT}<span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="wwwroot" value="{$wwwroot}" placeholder="http://www.domain.com" /></td>
		</tr>
		{if !empty($STR_ADMIN_SITES_SITE_COUNTRY_PRESELECTED)}	
			<tr>
				<td>{$STR_ADMIN_SITES_SITE_COUNTRY_PRESELECTED}{$STR_BEFORE_TWO_POINTS}:</td>
				<td><select class="form-control" name="default_country_id" style="max-width:250px">{$country_select_options}</select></td>
			</tr>
		{/if}
		<tr>
			<td>{$STR_ADMIN_SITES_TEMPLATE_USED}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			{if isset($directory_options)}
			<select class="form-control" name="template_directory" style="max-width:250px">
				<option value="">{$STR_CHOOSE}...</option>
				{foreach $directory_options as $o}
				<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.value}</option>
				{/foreach}
			</select>
			{/if}
			</td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PAGE_LINKS_DISPLAY_MODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="template_multipage" style="max-width:250px">
					<option value="">{$STR_CHOOSE}...</option>
					<option value="default_1"{if $template_multipage == 'default_1'} selected="selected"{/if}>{$STR_ADMIN_SITES_DISPLAY} n°1</option>
					<option value="default_2"{if $template_multipage == 'default_2'} selected="selected"{/if}>{$STR_ADMIN_SITES_DISPLAY} n°2</option>
					<option value="default_3"{if $template_multipage == 'default_3'} selected="selected"{/if}>{$STR_ADMIN_SITES_DISPLAY} n°3</option>
					<option value="default_4"{if $template_multipage == 'default_4'} selected="selected"{/if}>{$STR_ADMIN_SITES_DISPLAY} n°4</option>
					{if $template_multipage && $template_multipage != 'default_1' && $template_multipage != 'default_2' && $template_multipage != 'default_3'}<option value="{$template_multipage}" selected="selected">{$STR_ADMIN_SITES_DISPLAY} "{$template_multipage}"</option>{/if}
				</select>
			</td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_CSS_FILES}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="css" value="{$css|str_form_value}" /></td>
   	 	</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_CSS_FILES_EXPLAIN}</div></td>
   	 	</tr>
	{foreach $langs as $l}
		<tr>
			<td>{$STR_ADMIN_SITES_LOGO_URL} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="logo_{$l.lng}" value="{$l.logo|str_form_value}" /></td>
		</tr>
	{/foreach}
		<tr>
			<td>{$STR_ADMIN_SITES_LOGO_HEADER_DISPLAY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="on_logo" value="1"{if $on_logo == '1'} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="on_logo" value="0"{if $on_logo == '0'} checked="checked"{/if} /> {$STR_NO}
			</td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_FAVICON}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			{if isset($favicon)}
				<img src="{$favicon.src|escape:'html'}" alt="{$favicon.favicon|str_form_value}" width="32" /> &nbsp; &nbsp; &nbsp;
				<a href="{$favicon.sup_href|escape:'html'}"><img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" />{$STR_DELETE_THIS_FILE}</a>
				<input type="hidden" name="favicon" value="{$favicon.favicon|str_form_value}" />
			{else}
				<input style="max-width:250px" type="file" name="favicon" value="" />
			{/if}
			</td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_ZOOM_SELECTION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="zoom" value="jqzoom" {if $zoom == 'jqzoom'} checked="checked"{/if} /> {$STR_ADMIN_SITES_JQZOOM}
				<input type="radio" name="zoom" value="cloud-zoom" {if $zoom == 'cloud-zoom'} checked="checked"{/if} /> {$STR_ADMIN_SITES_CLOUD_ZOOM}
				<input type="radio" name="zoom" value="lightbox" {if $zoom == 'lightbox'} checked="checked"{/if} /> {$STR_ADMIN_SITES_LIGHTBOX}
				<input type="radio" name="zoom" value="" {if $zoom == ''} checked="checked"{/if} /> {$STR_NONE}
			</td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION} {$STR_BEFORE_TWO_POINTS}:</td>
 			<td>
				<input type="checkbox" name="enable_prototype" value="1"{if $enable_prototype} checked="checked"{/if} /> {$STR_ADMIN_SITES_JAVASCRIPT_AJAX_ACTIVATE}
				<input type="checkbox" name="enable_jquery" value="1"{if $enable_jquery} checked="checked"{/if} /> {$STR_ADMIN_SITES_JAVASCRIPT_JQUERY_ACTIVATE}
			</td>
   	 	</tr>
		<tr>
 			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION_EXPLAIN}</div>
			</td>
	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="export_encoding" style="max-width:250px">
					<option value="utf-8"{if $export_encoding == 'utf-8'} selected="selected"{/if}>{$STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_UTF8}</option>
					<option value="iso-8859-1"{if $export_encoding == 'iso-8859-1'} selected="selected"{/if}>{$STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_ISO}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="module_autosend" value="1" {if $module_autosend == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="module_autosend" value="0" {if $module_autosend == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" maxlength="3" type="number" class="form-control" name="module_autosend_delay" value="{$module_autosend_delay|str_form_value}" /> {$STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS_EXPLAIN}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_CATEGORY_COUNT_METHOD}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="radio" value="individual" name="category_count_method" {if $category_count_method == 'individual'} checked="checked"{/if} /> {$STR_ADMIN_SITES_CATEGORY_COUNT_INDIVIDUAL}
			<input type="radio" value="global" name="category_count_method" {if $category_count_method == 'global'} checked="checked"{/if} /> {$STR_ADMIN_SITES_CATEGORY_COUNT_GLOBAL}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_CART_POPUP_SIZE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" class="form-control" maxlength="3" name="popup_width" value="{$popup_width|str_form_value}" style="width:100px" /> px *
				<input type="text" class="form-control" maxlength="3" name="popup_height" value="{$popup_height|str_form_value}" style="width:100px" /> px
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_CSS_FILES_EXPLAIN}</div></td>
   	 	</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_SECURITY}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_ADMIN_FORCE_SSL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="admin_force_ssl" value="1" {if $admin_force_ssl == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="admin_force_ssl" value="0" {if $admin_force_ssl == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_ADMIN_FORCE_SSL_EXPLAIN}<br />
				<a href="{$membre_href|escape:'html'}">{$STR_ADMIN_SITES_HTTPS_TEST}</a></div>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SESSIONS_DURATION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" maxlength="5" type="text" class="form-control" name="sessions_duration" value="{$sessions_duration|str_form_value}" /> {$STR_MINUTES} </td>
   	 	</tr>
 		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_SESSIONS_DURATION_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" value="{$display_errors_for_ips|str_form_value}" name="display_errors_for_ips" style="width:100%" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_PRODUCTS_DISPLAY}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PRODUCTS_COUNT_IN_MENU}{$STR_BEFORE_TWO_POINTS}:</td>
 			<td>
				<input type="radio" name="display_nb_product" value="1" {if $display_nb_product == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="display_nb_product" value="0" {if $display_nb_product == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_THUMBS_SIZE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" class="form-control" maxlength="3" name="small_width" value="{$small_width|str_form_value}" style="width:100px" /> px. *
				<input type="text" class="form-control" maxlength="3" name="small_height" value="{$small_height|str_form_value}" style="width:100px" /> px.
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_IMAGES_SIZE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" class="form-control" maxlength="3" style="width:100px" name="medium_width" value="{$medium_width|str_form_value}" /> px. *
				<input type="text" class="form-control" maxlength="3" style="width:100px" name="medium_height" value="{$medium_height|str_form_value}" /> px.
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PRODUCTS_FILTER_DISPLAY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="module_filtre" value="1" {if $module_filtre == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="module_filtre" value="0" {if $module_filtre == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_ALLOW_ADD_PRODUCT_IN_LIST_PAGES}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" value="1" name="category_order_on_catalog" {if $category_order_on_catalog == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" value="0" name="category_order_on_catalog" {if $category_order_on_catalog == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="type_affichage_attribut" value="0" {if $type_affichage_attribut == 0} checked="checked"{/if} /> {$STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU}
				<input type="radio" name="type_affichage_attribut" value="1" {if $type_affichage_attribut == 1} checked="checked"{/if} /> {$STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS}
				<input type="radio" name="type_affichage_attribut" value="2" {if $type_affichage_attribut == '2'} checked="checked"{/if} /> {$STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY_EXPLAIN}</div>
			</td>
		<tr>
			<td>{$STR_ADMIN_SITES_PRODUCTS_PER_PAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" maxlength="3" type="number" class="form-control" name="nb_produit_page" value="{$nb_produit_page|str_form_value}" /></td>
   	 	</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_PRODUCTS_PER_PAGE_EXPLAIN}</div>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_ADD_TO_CART_ANIMATION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="anim_prod" value="1" {if $anim_prod == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="anim_prod" value="0" {if $anim_prod == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_DEFAULT_PRODUCT_PAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			{if $default_picture}
				<img src="{$default_picture_url|escape:'html'}" alt="{$default_picture|str_form_value}" width="32" /> &nbsp; &nbsp; &nbsp;
				<a href="{$default_picture_delete_url|escape:'html'}"><img src="{$default_picture_delete_icon_url|escape:'html'}" width="16" height="16" alt="" />{$STR_DELETE_THIS_FILE}</a>
				<input type="hidden" name="default_picture" value="{$default_picture|str_form_value}" />
			{else}
				<input style="max-width:250px" type="file" name="default_picture" value="{$default_picture|str_form_value}" />
			{/if}
			</td>
   	 	</tr>
	{if $is_best_seller_module_active}
		<tr>
			<td>{$STR_ADMIN_SITES_TOP_SALES_CONFIGURATION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="act_on_top" value="1" {if $act_on_top == 1} checked="checked"{/if} /> {$STR_ADMIN_SITES_AUTO_TOP_SALES}
				<input type="radio" name="act_on_top" value="0" {if $act_on_top == 0} checked="checked"{/if} /> {$STR_ADMIN_SITES_CONFIGURED_TOP_SALES}
			</td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_TOP_SALES_MAX_PRODUCTS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" maxlength="3" type="number" class="form-control" name="nb_on_top" value="{$nb_on_top|str_form_value}" /></td>
   	 	</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_LAST_VISITS_MAX_PRODUCTS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" maxlength="3" type="number" class="form-control" name="nb_last_views" value="{$nb_last_views|str_form_value}" /></td>
   	 	</tr>
	{/if}
		<tr>
			<td>{$STR_ADMIN_SITES_AUTO_PROMOTIONS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="auto_promo" value="1" {if $auto_promo == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="auto_promo" value="0" {if $auto_promo == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_CONFIGURED_PROMOTIONS} <a href="{$promotions_href|escape:'html'}" class="alert-link">{$promotions_href}</a>.</div>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_GLOBAL_DISCOUNT_PERCENTAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" maxlength="8" type="text" class="form-control" name="global_remise_percent" value="{$global_remise_percent|str_form_value}" /> % </td>
   	 	</tr>

		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="text" class="form-control" maxlength="2" name="pays_exoneration_tva" value="{$pays_exoneration_tva|str_form_value}" /> {$STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_SHORT_EXPLAIN}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_EXPLAIN}</div>
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_BILLING_HEADER}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_BILLING_NUMBER_FORMAT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="format_numero_facture" value="{$format_numero_facture|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_BILLING_NUMBER_FORMAT_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_HEADER}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="quotation_delay" value="{$quotation_delay|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_SMALL_ORDERS}</h2></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_SMALL_ORDERS_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SMALL_ORDERS_LIMIT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="small_order_overcost_limit" value="{$small_order_overcost_limit|str_form_value}" /> {$site_symbole} {$STR_TTC}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SMALL_ORDERS_AMOUNT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="small_order_overcost_amount" value="{$small_order_overcost_amount|str_form_value}" /> {$site_symbole} {$STR_TTC}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SMALL_ORDERS_VAT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="small_order_overcost_tva_percent" value="{$small_order_overcost_tva_percent|str_form_value}" /> %</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="minimal_amount_to_order" value="{$minimal_amount_to_order|str_form_value}" /> {$site_symbole} {$STR_TTC}</td>
		</tr>
		<tr>
			<td>{$LANG.STR_ADMIN_SITES_MINIMUM_ORDER_REVE_AMOUNT_ALLOWED}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="minimal_amount_to_order_reve" value="{$minimal_amount_to_order_reve|str_form_value}" /> {$site_symbole} {$STR_TTC}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td colspan="2" class="bloc">{$STR_ADMIN_SITES_ORDERS_UPDATING_LIMITATION} <a name="a_keep_old_orders_intact"></a></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBID}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="keep_old_orders_intact" value="0" {if $keep_old_orders_intact == 0} checked="checked"{/if} /> {$STR_NO} <br />
				<input type="radio" name="keep_old_orders_intact" value="1" {if $keep_old_orders_intact == 1} checked="checked"{/if} /> {$STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_LAST_YEAR}<br />
				<input type="radio" name="keep_old_orders_intact" value="2" {if $keep_old_orders_intact > 1} checked="checked"{/if} /> {$STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE} <input type="text" class="form-control datepicker" name="keep_old_orders_intact_date" value="{$keep_old_orders_intact_date|str_form_value}" style="width:110px" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_DELIVERY_COST_HEADER}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="seuil_total" value="{$seuil_total|str_form_value}" /> {$site_symbole} {$STR_TTC} - {$STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_DELIVERY_COST_RESELLER_FRANCO_LIMIT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="seuil_total_reve" value="{$seuil_total_reve|str_form_value}" /> {$site_symbole} {$STR_TTC} - {$STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_DELIVERY_COST_METHOD}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="mode_transport" style="max-width:250px">
					<option value="0" {if $mode_transport == 0} selected="selected"{/if}>{$STR_ADMIN_SITES_DELIVERY_COST_NONE}</option>
					<option value="1" {if $mode_transport == 1} selected="selected"{/if}>{$STR_ADMIN_SITES_DELIVERY_COST_GENERAL}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_DELIVERY_COST_METHOD_EXPLAIN}</div>
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="text" class="form-control" value="{$nb_product|str_form_value}" name="nb_product" /> {$STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART}</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART_EXPLAIN}</div>
				<div class="alert alert-info">{$STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_EXPLAIN}{$STR_BEFORE_TWO_POINTS}: {foreach $zones as $z}<a href="{$z.href|escape:'html'}" class="alert-link">{$z.nom}</a>{if !$z@last}, {/if}{foreachelse}<b><a href="{$zones_href|escape:'html'}">{$STR_ADMIN_SITES_DELIVERY_FRANCO_NO_ZONE}</a></b>{/foreach}.</div>
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_VAT_DISPLAY_MODE_HEADER}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_VAT_DISPLAY_MODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="display_prices_with_taxes" value="1" {if $display_prices_with_taxes == 1} checked="checked"{/if} /> {$STR_TTC}
				<input type="radio" name="display_prices_with_taxes" value="0" {if $display_prices_with_taxes == 0} checked="checked"{/if} /> {$STR_HT}
				&nbsp; &nbsp; {$STR_ADMIN_SITES_VAT_DISPLAY_MODE_EXPLAIN}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_VAT_DISPLAY_MODE_IN_ADMIN}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="display_prices_with_taxes_in_admin" value="1" {if $display_prices_with_taxes_in_admin == 1} checked="checked"{/if} /> {$STR_TTC}
				<input type="radio" name="display_prices_with_taxes_in_admin" value="0" {if $display_prices_with_taxes_in_admin == 0} checked="checked"{/if} /> {$STR_HT}
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_TEXT_EDITOR}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_TEXT_EDITOR_EXPLAIN}</td>
			<td>
				<input type="radio" name="html_editor" value="" {if $html_editor == ''} checked="checked"{/if} /> {$STR_ADMIN_SITES_DEFAULT} (textearea)<br />
				<input type="radio" name="html_editor" value="0" {if $html_editor == '0'} checked="checked"{/if} /> <b>{$STR_ADMIN_SITES_TEXT_EDITOR_FCKEDITOR}</b><br />
				<input type="radio" name="html_editor" value="3" {if $html_editor == '3'} checked="checked"{/if} /> {$STR_ADMIN_SITES_TEXT_EDITOR_CKEDITOR}<br />
				<input type="radio" name="html_editor" value="1" {if $html_editor == '1'} checked="checked"{/if} /> {$STR_ADMIN_SITES_TEXT_EDITOR_NICEDITOR}<br />
				<input type="radio" name="html_editor" value="4" {if $html_editor == '4'} checked="checked"{/if} /> TinyMCE<br />
			</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_EMAIL_CONFIGURATION}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_EMAIL_SENDING_ALLOWED}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="send_email_active" value="1" {if $send_email_active == 1} checked="checked"{/if} /> {$STR_ADMIN_ACTIVATE}
				<input type="radio" name="send_email_active" value="0" {if $send_email_active == 0} checked="checked"{/if} /> {$STR_ADMIN_DEACTIVATE} {$STR_ADMIN_SITES_EMAIL_SENDING_DEACTIVATE_EXPLAIN}
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_EMAIL_CONFIGURATION_EXPLAIN}</div>
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL}<span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_webmaster" value="{$email_webmaster|str_form_value}" /> {$STR_MODULE_PREMIUM_MANDATORY_EMAIL}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SUPPORT_SENDER_NAME}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="nom_expediteur" value="{$nom_expediteur|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_SUPPORT_SENDER_NAME_EXPLAIN}</div>
			</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_commande" value="{$email_commande|str_form_value}" /> {$STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN}</td>
		</tr>
		<tr>
			<td>{$STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_client" value="{$email_client|str_form_value}" /> {$STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN}</td>
		</tr>
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_ANALYTICS_TAG}</h2></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_ANALYTICS_TAG_EXPLAIN|htmlspecialchars}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><textarea class="form-control" name="tag_analytics" style="width:100%" rows="5" cols="54">{$tag_analytics}</textarea></td>
		</tr>
	</table>
				</div>
	
	
	
	
				<div class="tab-pane" id="tab2">
	<table class="main_table">
	{foreach $modules_infos as $m}
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_MODULE}{$STR_BEFORE_TWO_POINTS}: {$m.name} {$m.package} {$m.version}{$STR_BEFORE_TWO_POINTS}</h2></td>
		</tr>
		{if empty($m.contact)}
			{if $m.type == "light"}
		<tr>
			<td colspan="2">{$STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT}</td>
		</tr>
			{else}
		<tr>
			<td>{$STR_ADMIN_SITES_MODULE_INSTALL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="install[{$m.technical_code}]" value="1" {if $m.installed} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="install[{$m.technical_code}]" value="0" {if !$m.installed} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
			{/if}
			{if !empty($m.configuration_variable)}
		<tr>
			<td>{$STR_ADMIN_ACTIVATE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="{$m.configuration_variable}" value="1" {if $m.enabled} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="{$m.configuration_variable}" value="0" {if !$m.enabled} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
			{/if}
			{if $m.technical_code == "devises"}
				{if $nouveau_mode == "modif"}
				{* La devise n'est pas affichée lors de la création d'un site puisque la liste des devises pour ce site n'est pas encore présente en BDD. La devise par défaut sera l'euro *}
		<tr>
			<td colspan="2">{$STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY_EXPLAIN}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_DEFAULT_CURRENCY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<select class="form-control" name="devise_defaut" style="max-width:250px">
				{foreach $devices_options as $o}
				<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
				{/foreach}		
				</select> {$STR_ADMIN_SITES_DEFAULT_CURRENCY_WARNING} - <a href="{$devises_href|escape:'html'}">{$STR_ADMIN_SITES_CURRENCIES_LINK}</a>
			</td>
		</tr>
				{/if}
			{/if}
			{if $m.technical_code == "paypal"}
		<tr>
			<td>{$STR_ADMIN_SITES_PAYPAL_EMAIL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_paypal" value="{$email_paypal|str_form_value}" /></td>
		</tr>
			{/if}
			{if $m.technical_code == "moneybookers"}
		<tr>
			<td>{$STR_ADMIN_SITES_MONEYBOOKERS_EMAIL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="email_moneybookers" value="{$email_moneybookers|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD}{$STR_BEFORE_TWO_POINTS}:<br />
			</td>
			<td><input style="max-width:250px" type="text" class="form-control" name="secret_word" value="{$secret_word|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD_EXPLAIN}</div></td>
		</tr>
			{/if}
			{if $m.technical_code == "kekoli"}
 		<tr>
			<td>{$STR_ADMIN_SITES_DELIVERY_CARRIER_DELAY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:50px" type="text" class="form-control" name="availability_of_carrier" value="{$availability_of_carrier|str_form_value}" /> jours</td>
		</tr>
			{/if}
			{if $m.technical_code == "menus"}
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_ROLLOVER_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_ROLLOVER_DISPLAY_MODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="type_rollover" value="1" {if $type_rollover == 1} checked="checked"{/if} /> {$STR_ADMIN_SITES_ROLLOVER_DISPLAY_REPLACE}
				<input type="radio" name="type_rollover" value="2" {if $type_rollover == 2} checked="checked"{/if} /> {$STR_ADMIN_SITES_ROLLOVER_DISPLAY_SCROLLING}
			</td>
		</tr>
			{/if}
			{if $m.technical_code == "precedent_suivant"}
				{if	!empty($STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION_EXPLAIN)}
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION_EXPLAIN}</div></td>
		</tr>
				{/if}
		<tr>
			<td>{$STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="in_category" value="1" {if $in_category == 1} checked="checked"{/if} /> {$STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_DIRECT_PARENT}<br />
				<input type="radio" name="in_category" value="0" {if $in_category == 0} checked="checked"{/if} /> {$STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_ALL_PARENTS}
			</td>
		</tr>
			{/if}
			{if $m.technical_code == "stock_advanced"}
		<tr>
			<td>{$STR_ADMIN_SITES_ALLOW_ORDERS_WITHOUT_STOCKS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="allow_add_product_with_no_stock_in_cart" value="1" {if $allow_add_product_with_no_stock_in_cart == 1} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="allow_add_product_with_no_stock_in_cart" value="0" {if $allow_add_product_with_no_stock_in_cart == 0} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_STOCKS_BOOKING_SECONDS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="number" class="form-control" name="timemax" value="{$timemax|str_form_value}" /> {$STR_ADMIN_SITES_STOCKS_BOOKING_DEFAULT}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_STOCKS_BOOKING_EXPLAIN}</div></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_STOCKS_LIMIT_ALERT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" type="number" class="form-control" name="seuil" value="{$seuil|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="payment_status_decrement_stock" value="pending,being_checked,completed"{if $payment_status_decrement_stock == 'pending,being_checked,completed'} checked="checked"{/if} /> {$STR_YES}
				<input type="radio" name="payment_status_decrement_stock" value="being_checked,completed"{if $payment_status_decrement_stock == 'being_checked,completed'} checked="checked"{/if} /> {$STR_NO}
			</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{$STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS_EXPLAIN}</div></td>
		</tr>
			{/if}
			{if $m.technical_code == "affiliation"}
		<tr>
			<td>{$STR_ADMIN_SITES_AFFILIATION_COMMISSION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" maxlength="2" type="text" class="form-control" name="commission_affilie" value="{$commission_affilie|str_form_value}" /> %</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_AFFILIATION_LOGO}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:500px" type="text" class="form-control" name="logo_affiliation" value="{$logo_affiliation|str_form_value}" /></td>
		</tr>
			{/if}
			{if $m.technical_code == "parrainage"}
		<tr>
			<td>{$STR_ADMIN_SITES_SPONSOR_COMMISSION}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100px" maxlength="3" type="text" class="form-control" name="avoir" value="{$avoir|str_form_value}" /> {$site_symbole}</td>
		</tr>
			{/if}
			{if $m.technical_code == "sign_in_twitter"}
		<tr>
			<td>{$STR_ADMIN_SITES_TWITTER_CONSUMER_KEY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="" type="text" class="form-control" name="twitter_consumer_key" value="{$twitter_consumer_key|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_TWITTER_CONSUMER_SECRET}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="" type="text" class="form-control" name="twitter_consumer_secret" value="{$twitter_consumer_secret|str_form_value}" /></td>
		</tr>
			{/if}
			{if $m.technical_code == "vacances"}
		<tr>
			<td>{$STR_ADMIN_SITES_VACANCY_MODULE_TYPE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="module_vacances_type" value="0" {if $module_vacances_type == 0} checked="checked"{/if} /> {$STR_ADMIN_SITES_VACANCY_MODULE_TYPE_ADMIN}
				<input type="radio" name="module_vacances_type" value="1" {if $module_vacances_type == 1} checked="checked"{/if} /> {$STR_ADMIN_SITES_VACANCY_MODULE_TYPE_SUPPLIER}
			</td>
		</tr>
				{foreach $langs as $l}
		<tr>
			<td>{$STR_ADMIN_SITES_VACANCY_ADMIN_MESSAGE} {$l.lng|upper}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" value="{$l.module_vacances_value|str_form_value}" name="module_vacances_client_msg_{$l.lng}" id="module_vacances_client_msg_{$l.lng}" size="100" /></td>
		</tr>
				{/foreach}
			{/if}
			{if $m.technical_code == "socolissimo"}
		<tr>
			<td>{$STR_ADMIN_SITES_SO_COLISSIMO_FOID}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:200px" type="text" class="form-control" name="socolissimo_foid" value="{$socolissimo_foid|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SO_COLISSIMO_SHA1_KEY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:200px" type="text" class="form-control" name="socolissimo_sha1_key" value="{$socolissimo_sha1_key|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SO_COLISSIMO_URL_KO}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="socolissimo_urlko" value="{$socolissimo_urlko|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:50px" type="text" class="form-control" name="socolissimo_preparationtime" value="{$socolissimo_preparationtime|str_form_value}" /> {$STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME_EXPLAIN}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:50px" type="text" class="form-control" name="socolissimo_forwardingcharges" value="{$socolissimo_forwardingcharges|str_form_value}" /> {$site_symbole} - {$STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES_EXPLAIN}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:50px" type="text" class="form-control" name="socolissimo_firstorder" value="{$socolissimo_firstorder|str_form_value}" /> {$STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER_EXPLAIN}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:50px" type="text" class="form-control" name="socolissimo_pointrelais" value="{$socolissimo_pointrelais|str_form_value}" /> {$STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS_EXPLAIN}</td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SO_COLISSIMO_DYFORWARDINGCHARGESCMT}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:50px" type="text" class="form-control" name="socolissimo_dyForwardingChargesCMT" value="{$socolissimo_dyForwardingChargesCMT|str_form_value}" /></td>
		</tr>
			{/if}
			{if $m.technical_code == "tnt"}
			<tr>
				<td>{$STR_ADMIN_SITES_TNT_USERNAME}{$STR_BEFORE_TWO_POINTS}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="tnt_username" value="{$tnt_username|str_form_value}" /></td>
			</tr>
			<tr>
				<td>{$STR_ADMIN_SITES_TNT_PASSWORD}{$STR_BEFORE_TWO_POINTS}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="tnt_password" value="{$tnt_password|str_form_value}" /></td>
			</tr>
			<tr>
				<td>{$STR_ADMIN_SITES_TNT_ACCOUNT_NUMBER}{$STR_BEFORE_TWO_POINTS}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="tnt_account_number" value="{$tnt_account_number|str_form_value}" /></td>
			</tr>
			<tr>
				<td>{$STR_ADMIN_SITES_TNT_EXPEDITION_DELAY}{$STR_BEFORE_TWO_POINTS}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="expedition_delay" value="{$expedition_delay|str_form_value}" /></td>
			</tr>
			<tr>
				<td>{$STR_ADMIN_SITES_TNT_TRESHOLD}{$STR_BEFORE_TWO_POINTS}:</td>
				<td><input style="width:100%" type="text" class="form-control" name="tnt_treshold" value="{$tnt_treshold|str_form_value}" /></td>
			</tr>
			{/if}
			{if $m.technical_code == "sips"}
		<tr>
			<td>{$STR_ADMIN_SITES_SIPS_CERTIFICATE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="sips" value="{$sips|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_ADMIN_SITES_SIPS_EXPLAIN}</td>
		</tr>
			{/if}
			{if $m.technical_code == "paybox"}
		<tr>
			<td>{$STR_ADMIN_SITES_PAYBOX_CGI}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="paybox_cgi" placeholder="http://" value="{$paybox_cgi|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PAYBOX_SITE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="paybox_site" value="{$paybox_site|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PAYBOX_RANG}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="paybox_rang" value="{$paybox_rang|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_PAYBOX_ID}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="paybox_identifiant" value="{$paybox_identifiant|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_ADMIN_SITES_PAYBOX_TEST_EXPLAIN}</td>
		</tr>
			{/if}
			{if $m.technical_code == "systempay"}
		<tr>
			<td>{$STR_ADMIN_SITES_SYSTEMPAY_CERTIFICATE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_cle_prod" value="{$systempay_cle_prod|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SYSTEMPAY_TEST}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_cle_test" value="{$systempay_cle_test|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SYSTEMPAY_ID}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_code_societe" value="{$systempay_code_societe|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SYSTEMPAY_OCCURENCES}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_payment_count" value="{$systempay_payment_count|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SYSTEMPAY_DAYS_BETWEEN_OCCURENCES}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="systempay_payment_period" value="{$systempay_payment_period|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_SYSTEMPAY_TEST_MODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<label for="non">{$STR_NO}</label><input type="radio" name="systempay_test_mode" value="false" id="non" {if !$systempay_test_mode} checked="checked"{/if} />
				<label for="oui">{$STR_YES}</label><input type="radio" name="systempay_test_mode" value="true" id="oui" {if $systempay_test_mode} checked="checked"{/if} />
			</td>
		</tr>
			{/if}
			{if $m.technical_code == "partenaires"}
		<tr>
			<td>{$STR_ADMIN_SITES_PARTNERS_DISPLAY_MODE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="radio" value="individual" name="partner_count_method" {if $partner_count_method == 'individual'} checked="checked"{/if} /> {$STR_ADMIN_SITES_PARTNERS_INDIVIDUAL}
			<input type="radio" value="global" name="partner_count_method" {if $partner_count_method == 'global'} checked="checked"{/if} /> {$STR_ADMIN_SITES_PARTNERS_GLOBAL}</td>
		</tr>
			{/if}
			{if $m.technical_code == "facebook"}
		<tr>
			<td>{$STR_ADMIN_SITES_FACEBOOK_ADMIN}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="fb_admins" value="{$fb_admins|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_FACEBOOK_PAGE_LINK}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="width:100%" type="text" class="form-control" name="facebook_page_link" placeholder="http://" value="{$facebook_page_link|str_form_value}" /></td>
		</tr>
			{/if}
			{if $m.technical_code == "facebook_connect"}
		<tr>
			<td>{$STR_ADMIN_SITES_FACEBOOK_APPID}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="" type="text" class="form-control" name="fb_appid" value="{$fb_appid|str_form_value}" /> </td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_FACEBOOK_SECRET}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="" type="text" class="form-control" name="fb_secret" value="{$fb_secret|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_ADMIN_SITES_FACEBOOK_BASEURL}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input style="" type="text" class="form-control" name="fb_baseurl" value="{$fb_baseurl|str_form_value}" /></td>
		</tr>
			{/if}
			{if $m.technical_code == "facebook_connect"}
			{/if}
			{if $m.technical_code == "facebook_connect"}
			{/if}
			{if $m.technical_code == "facebook_connect"}
			{/if}
		{else}
		<tr>
			<td colspan="2"><a href="https://www.peel.fr/utilisateurs/contact.php">{$STR_ADMIN_CONTACT_PEEL_FOR_MODULE}</a></td>
		</tr>
		{/if}
	{/foreach}
	</table>
			</div>
	{if $nouveau_mode == "modif"}
		{* Les modules ne sont pas affichés lors de la création d'un site puisque la liste des modules pour ce site n'est pas encore présente en BDD. La configurations de module sera celle par défaut, comme défini dans create_new_site.sql *}
			<div class="tab-pane" id="tab3">
	<table class="main_table">
		<tr>
			<td class="bloc" colspan="2"><h2>{$STR_ADMIN_SITES_MODULES_POSITIONS}</h2></td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{$STR_ADMIN_SITES_MODULES_POSITIONS_EXPLAIN}</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="full_width">
			{foreach $modules as $m}
					{$m.tr_rollover}
						<td>
							<div class="edit_module_title">{$m.title}{$STR_BEFORE_TWO_POINTS}:</div>
						</td>
						<td>
							<div class="edit_module_attribut">
								<table>
									<tr>
										<td>
											<label for="display_mode_{$m.id}_left">{$STR_ADMIN_DISPLAY_MODE}{$STR_BEFORE_TWO_POINTS}:</label>
										</td>
										<td>
											<input type="text" style="max-width:150px" class="form-control" name="display_mode_{$m.id}" value="{$m.display_mode}" size="10" />
										</td>
									</tr>
									<tr>
										<td><label for="emplacement_{$m.id}">{$STR_ADMIN_HTML_PLACE}{$STR_BEFORE_TWO_POINTS}:</label></td>
										<td>
										{foreach $emplacement_array as $e_code => $e_name}
											{assign var="var_name" value="is_{$e_code}_off"}
											<input type="radio" name="module_{$m.id}" id="{$m.id}_{$e_code}" value="{$e_code}"{if $m.location == $e_code} checked="checked"{/if}{if isset($m.$var_name) && !empty($m.$var_name)} disabled="disabled"{/if} /> <label for="{$m.id}_{$e_code}">{$e_name}</label>
										{/foreach}
										</td>
									</tr>
									<tr>
										<td>
											<label for="etat_{$m.id}">{$STR_ADMIN_ACTIVATE}{$STR_BEFORE_TWO_POINTS}:</label>
										</td>
										<td>
											<input type="checkbox" name="etat_{$m.id}" id="etat_{$m.id}" value="1"{if $m.etat == '1'} checked="checked"{/if} />
										</td>
									</tr>
									<tr>
										<td>
											<label for="home_{$m.id}">{$STR_ADMIN_SITES_ON_HOMEPAGE_ONLY}{$STR_BEFORE_TWO_POINTS}:</label>
										</td>
										<td>
											<input type="checkbox" name="home_{$m.id}" id="home_{$m.id}" value="1"{if $m.in_home == '1'} checked="checked"{/if} />
										</td>
									</tr>
									<tr>
										<td>
											<label for="position_{$m.id}_left">{$STR_ADMIN_POSITION}{$STR_BEFORE_TWO_POINTS}:</label>
										</td>
										<td>
											<input type="text"  style="max-width:50px" class="form-control" name="position_{$m.id}" value="{$m.position|str_form_value}" />
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
			{/foreach}
				</table>
			</td>
		</tr>
				</table>
			</div>
	{/if}

		</div>
		<br />
		<p class="center"><input class="btn btn-primary btn-lg" type="submit" value="{$titre_bouton|str_form_value}" /></p>
	</div>
</form>