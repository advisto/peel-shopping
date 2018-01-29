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
// $Id: admin_utilisateur_liste.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<form id="search_form" class="entryform form-inline" role="form" method="get" action="{$action|escape:'html'}">
	<div class="entete">{$STR_ADMIN_CHOOSE_SEARCH_CRITERIA}</div>
	<div class="row">
		<div class="col-md-3 col-sm-4 col-xs-12 center">
			<label for="search_email">{$STR_ADMIN_ID} / {$STR_EMAIL}{if empty($pseudo_is_not_used)} / {$STR_PSEUDO}{/if} {$STR_BEFORE_TWO_POINTS}:</label>
			<input type="text" class="form-control" id="search_email" name="email" value="{$email|str_form_value}" autocapitalize="none" />
		</div>
		<div class="col-md-3 col-sm-4 col-xs-12 center">
			<label for="search_client_info">{$STR_FIRST_NAME} / {$STR_LAST_NAME}{$STR_BEFORE_TWO_POINTS}:</label>
			<input type="text" class="form-control" id="search_client_info" name="client_info" value="{$client_info|str_form_value}" />
		</div>
		<div class="col-md-3 col-sm-4 col-xs-12 center">
			<label for="search_societe">{$STR_COMPANY} / {$STR_SIREN} / {$STR_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</label>
			<input type="text" class="form-control" id="search_societe" name="societe" value="{$societe|str_form_value}" />
		</div>
		<div class="clearfix visible-sm"></div>
		<div class="col-md-3 col-sm-4 col-xs-12 center">
			<label for="search_ville_cp">{$STR_TOWN} / {$STR_ZIP}{$STR_BEFORE_TWO_POINTS}:</label>
			<input type="text" class="form-control" id="search_ville_cp" name="ville_cp" value="{$ville_cp|str_form_value}" />
		</div>
		<div class="clearfix visible-md visible-lg"></div>
		<div class="col-md-3 col-sm-4 col-xs-12 center" data-toggle="buttons-checkbox" style="margin-top: 20px; margin-bottom: 20px">
			<a class="btn btn-default {if $is_advanced_search}active{else}collapsed{/if}" data-toggle="collapse" href="#search_details">{$STR_MORE_DETAILS} <span id="search_icon" class="glyphicon glyphicon-chevron-{if $is_advanced_search}down{else}right{/if}"></span></a>
		</div>
		<div id="search_details" class="{if !$is_advanced_search}collapse{else}in{/if}">
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_priv">{$STR_ADMIN_UTILISATEURS_PROFILE_TYPE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="priv" id="search_priv">
					<option value="">{$STR_CHOOSE}...</option>
					{$profil_select_options}
				</select>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_newsletter">{$STR_ADMIN_UTILISATEURS_NEWSLETTER_SUBSCRIBER}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="newsletter" id="search_newsletter">
					<option value="">{$STR_CHOOSE}...</option>
					{$newsletter_options}
				</select>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_offre_commercial">{$STR_ADMIN_UTILISATEURS_COMMERCIAL_OFFERS}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="offre_commercial" id="search_offre_commercial">
					<option value="">{$STR_CHOOSE}...</option>
					{$offre_commercial_options}
				</select>
			</div>
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_commercial">{$STR_ADMIN_UTILISATEURS_MANAGED_BY}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_commercial" name="commercial">
					<option value="0">--</option>
					{foreach $commercial_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.prenom}{$STR_BEFORE_TWO_POINTS}{$o.nom_famille}</option>
					{/foreach}
				</select>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_etat">{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="etat" id="search_etat">
					<option value="">{$STR_CHOOSE}...</option>
					<option value="1"{if $etat == '1'} checked="checked"{/if}>{$STR_ADMIN_ACTIVATED}</option>
					<option value="0"{if $etat == '0'} checked="checked"{/if}>{$STR_ADMIN_DEACTIVATED}</option>
				</select>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_origin">{$STR_ORIGIN}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_origin" name="origin">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $user_origin_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_user_lang">{$STR_ADMIN_LANGUAGE}{if $is_crons_module_active}{$STR_ADMIN_UTILISATEURS_MANDATORY_FOR_MULTIPLE_SEND}{/if}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="user_lang" id="search_user_lang">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $langs as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_tel">{$STR_TELEPHONE} / {$STR_FAX}{$STR_BEFORE_TWO_POINTS}:</label>
				<input type="text" class="form-control" name="tel" id="search_tel" value="{$tel|str_form_value}" />
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_seg_who">{$STR_ADMIN_UTILISATEURS_WHO}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="seg_who" id="search_seg_who">
					<option value="">{$STR_CHOOSE}...</option>
					{$seg_who}
				</select>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_type">{$STR_TYPE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_type" name="type">
					<option value="">{$STR_CHOOSE}...</option>
					<option disabled="disabled" style="text-align:center;font-weight:bold;" value="">{$STR_BUYERS}{$STR_BEFORE_TWO_POINTS}:</option>
					<option value="importers_exporters"{if $type=='importers_exporters'} selected="selected"{/if}>{$STR_IMPORTERS_EXPORTERS}</option>
					<option value="commercial_agent"{if $type=='commercial_agent'} selected="selected"{/if}>{$STR_COMMERCIAL_AGENT}</option>
					<option value="purchasing_manager"{if $type=='purchasing_manager'} selected="selected"{/if}>{$STR_PURCHASING_MANAGER}</option>
					<option disabled="disabled" style="text-align:center;font-weight:bold;" value="">{$STR_WORD_SELLERS}{$STR_BEFORE_TWO_POINTS}:</option>
					<option value="wholesaler"{if $type=='wholesaler'} selected="selected"{/if}>{$STR_WHOLESALER}</option>
					<option value="half_wholesaler"{if $type=='half_wholesaler'} selected="selected"{/if}>{$STR_HALF_WHOLESALER}</option>
					<option value="retailers"{if $type=='retailers'} selected="selected"{/if}>{$STR_RETAILERS}</option>
				</select>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_fonction">{$STR_FONCTION}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_fonction" name="fonction">
					<option value="">{$STR_CHOOSE}...</option>
					{$fonction_options}
				</select>
			</div>
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_site_on">{$STR_ADMIN_WEBSITE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="site_on" id="search_site_on">
					<option value="">{$STR_CHOOSE}...</option>
					<option value="1"{if $site_on=='1'} selected="selected"{/if}>{$STR_YES}</option>
					<option value="0"{if $site_on=='0'} selected="selected"{/if}>{$STR_NO}</option>
				</select>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_seg_buy">{$STR_ADMIN_UTILISATEURS_BUY}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="seg_buy" id="search_seg_buy">
					<option value="">{$STR_CHOOSE}...</option>
					{$seg_buy}
				</select>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_seg_want">{$STR_ADMIN_UTILISATEURS_WANTS}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="seg_want" id="search_seg_want">
					<option value="">{$STR_CHOOSE}...</option>
					{$seg_want}
				</select>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_seg_think">{$STR_ADMIN_UTILISATEURS_THINKS}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="seg_think" id="search_seg_think">
					<option value="">{$STR_CHOOSE}...</option>
					{$seg_think}
				</select>
			</div>
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_seg_followed">{$STR_ADMIN_UTILISATEURS_FOLLOWED_BY}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="seg_followed" id="search_seg_followed">
					<option value="">{$STR_CHOOSE}...</option>
					{$seg_followed}
				</select>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_pays">{$STR_COUNTRY}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="pays" id="search_pays">
					<option value="">{$STR_CHOOSE}...</option>
					{$country_select_options}
				</select>
			</div>
			<div class="col-md-6 col-sm-8 col-xs-12 center">
				<fieldset>
					<legend>{$STR_CONTINENT}{$STR_BEFORE_TWO_POINTS}:</legend>
					{foreach $continent_inputs as $c}
						<div style="display:inline-block;"><input type="checkbox" id="search_continent_{$c.value|str_form_value}" name="continent[]" value="{$c.value|str_form_value}"{if $c.issel} checked="checked"{/if} />&nbsp;<label for="search_continent_{$c.value|str_form_value}">{$c.name}</label></div>
					{/foreach}
				</fieldset>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_date_insert">{$STR_ADMIN_UTILISATEURS_REGISTRATION_DATE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="date_insert" id="search_date_insert" onkeyup="display_input2_element(this.id)" onchange="display_input2_element(this.id)" onclick="display_input2_element(this.id)">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $date_insert_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
				<input type="text" class="form-control datepicker" name="date_insert_input1" id="search_date_insert_input1" value="{$date_insert_input1|str_form_value}" style="width: 110px" />
				<span id="search_date_insert_input2_span">
					{$STR_ADMIN_DATE_BETWEEN_AND}
					<input type="text" class="form-control datepicker" name="date_insert_input2" id="search_date_insert_input2" value="{$date_insert_input2|str_form_value}" style="width: 110px;" />
				</span>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_date_last_paiement">{$STR_ADMIN_UTILISATEURS_LAST_PAYMENT_DATE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="date_last_paiement" id="search_date_last_paiement" onkeyup="display_input2_element(this.id)" onchange="display_input2_element(this.id)" onclick="display_input2_element(this.id)">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $date_last_paiement_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
				<input type="text" class="form-control datepicker" name="date_last_paiement_input1" id="search_date_last_paiement_input1" value="{$date_last_paiement_input1|str_form_value}"  style="width: 110px" />
				<span id="search_date_last_paiement_input2_span">
					{$STR_ADMIN_DATE_BETWEEN_AND}
					<input type="text" class="form-control datepicker" name="date_last_paiement_input2" id="search_date_last_paiement_input2" value="{$date_last_paiement_input2|str_form_value}" style="width: 110px;" />
				</span>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_date_statut_commande">{$STR_ADMIN_UTILISATEURS_ORDER_STATUS_DATE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="date_statut_commande" id="search_date_statut_commande" onkeyup="display_input2_element(this.id)" onchange="display_input2_element(this.id)" onclick="display_input2_element(this.id)">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $date_statut_commande_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
				<input type="text" class="form-control datepicker" name="date_statut_commande_input1" id="search_date_statut_commande_input1" value="{$date_statut_commande_input1|str_form_value}" style="width:110px" />
				<span id="search_date_statut_commande_input2_span">
				{$STR_ADMIN_DATE_BETWEEN_AND}
				<input type="text" class="form-control datepicker" name="date_statut_commande_input2" id="search_date_statut_commande_input2" value="{$date_statut_commande_input2|str_form_value}" style="width: 110px;" /></span>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_products_list">{$STR_ADMIN_UTILISATEURS_PRODUCT_BOUGHT_AND_QUANTITY}{$STR_BEFORE_TWO_POINTS}:</label>
				<div id="search_products_list_wrapper"><input type="text" class="form-control" name="list_produit" id="search_products_list" value="{$list_produit|str_form_value}" /></div>
				<select class="form-control" name="nombre_produit" id="search_nombre_produit">
					<option value="">{$STR_CHOOSE}...</option>
					{$nombre_produit}
				</select>
			</div>
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_date_contact_prevu">{$STR_ADMIN_UTILISATEURS_CONTACT_FORECASTED_DATE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="date_contact_prevu" id="search_date_contact_prevu" onkeyup="display_input2_element(this.id)" onchange="display_input2_element(this.id)" onclick="display_input2_element(this.id)">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $date_contact_prevu_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
				<input type="text" class="form-control datepicker" name="date_contact_prevu_input1" id="search_date_contact_prevu_input1" value="{$date_contact_prevu_input1|str_form_value}" style="width: 110px;" />
				<span id="search_date_contact_prevu_input2_span">
				{$STR_ADMIN_DATE_BETWEEN_AND}
				<input type="text" class="form-control datepicker" name="date_contact_prevu_input2" id="search_date_contact_prevu_input2" value="{$date_contact_prevu_input2|str_form_value}" style="width: 110px;" /></span>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_raison">{$STR_ADMIN_REASON}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_raison" name="raison">
					<option value="">{$STR_CHOOSE}...</option>
					{$raison}
				</select>
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_date_derniere_connexion">{$STR_ADMIN_UTILISATEURS_LAST_LOGIN_DATE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="date_derniere_connexion" id="search_date_derniere_connexion" onkeyup="display_input2_element(this.id)" onchange="display_input2_element(this.id)" onclick="display_input2_element(this.id)">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $date_derniere_connexion_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
				<input type="text" class="form-control datepicker" name="date_derniere_connexion_input1" id="search_date_derniere_connexion_input1" value="{$date_derniere_connexion_input1|str_form_value}" style="width: 110px;" />
				<span id="search_date_derniere_connexion_input2_span">
					{$STR_ADMIN_DATE_BETWEEN_AND}
					<input type="text" class="form-control datepicker" name="date_derniere_connexion_input2" id="search_date_derniere_connexion_input2" value="{$date_derniere_connexion_input2|str_form_value}" style="width: 110px;" />
				</span>
			</div>
		{if $is_abonnement_module_active}
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_abonne">{$STR_ADMIN_UTILISATEURS_SUBSCRIBER}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="abonne" id="search_abonne">
					<option value="">{$STR_CHOOSE}...</option>
					{$abonne}
				</select>
			</div>
		{/if}
		{if $is_annonce_module_active}
			{if !empty($id_categories) || !empty($id_cat_1)}
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				{$STR_ADMIN_CHOOSE_FAVORITE_CATEGORIES}{$STR_BEFORE_TWO_POINTS}:<br />
				{if !empty($id_categories)}
					{$STR_FIRST_CHOICE}{$STR_BEFORE_TWO_POINTS}:<br />{$id_categories}
				{elseif !empty($id_cat_1)}
				<label for="search_id_cat_1">{$STR_FIRST_CHOICE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_id_cat_1" name="id_cat_1">
					{$id_cat_1}
				</select>
				<label for="search_id_cat_2">{$STR_SECOND_CHOICE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_id_cat_2" name="id_cat_2">
					{$id_cat_2}
				</select>
				<label for="search_id_cat_3">{$STR_THIRD_CHOICE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" id="search_id_cat_3" name="id_cat_3">
					{$id_cat_3}
				</select>
				{/if}
			</div>
			{/if}
			<div class="clearfix visible-md visible-lg"></div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_with_gold_ad">{$STR_MODULE_ANNONCES_ADMIN_USER_WITH_GOLD}{$STR_BEFORE_TWO_POINTS}:</label>
				<input name="with_gold_ad" id="search_with_gold_ad" type="checkbox" value="1"{if $with_gold_ad == '1'} checked="checked"{/if} />
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_ads_count">{$STR_MODULE_ANNONCES_ADMIN_USER_ADS_COUNT}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="ads_count" id="search_ads_count" onkeyup="display_input2_element(this.id)" onchange="display_input2_element(this.id)" onclick="display_input2_element(this.id)">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $ads_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
				<input type="text" class="form-control" name="ads_count_input1" id="search_ads_count_input1" value="{$ads_count_input1|str_form_value}" style="width: 110px;" />
				<span id="search_ads_count_input2_span">
					{$STR_AND}
					<input type="text" class="form-control" name="ads_count_input2" id="search_ads_count_input2" value="{$ads_count_input2|str_form_value}" style="width: 110px;" />
				</span>
			</div>	
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_list_annonce">{$STR_MODULE_ANNONCES_ADMIN_ADS_AVAILABLE}{$STR_BEFORE_TWO_POINTS}:</label>
				<select class="form-control" name="list_annonce" id="search_list_annonce">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $annonces_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
			</div>
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_annonces_contiennent">{$STR_MODULE_ANNONCES_ADMIN_ADS_CONTAIN}{$STR_BEFORE_TWO_POINTS}:</label>
				<input type="text" class="form-control" name="annonces_contiennent" id="search_annonces_contiennent" value="{$annonces_contiennent|str_form_value}" />
			</div>	
		{/if}
		{if $is_groups_module_active}
			<div class="col-md-3 col-sm-4 col-xs-12 center">
				<label for="search_group">{$STR_ADMIN_GROUP}{$STR_BEFORE_TWO_POINTS}:</label>
			{if isset($groupes_options)}
				<select class="form-control" name="group" id="search_group">
					<option value="">{$STR_CHOOSE}...</option>
					{foreach $groupes_options as $o}
					<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
					{/foreach}
				</select>
			{else}
				{$STR_ADMIN_UTILISATEURS_NO_GROUP_DEFINED}
			{/if}
			</div>
		{/if}
		</div>
		<div id="search_col" class="col-md-3 col-sm-3 col-xs-12 center pull-right" style="margin-top: 20px; margin-bottom: 20px">
			<input type="hidden" name="mode" value="search" /><input type="submit" class="btn btn-primary" value="{$STR_SEARCH|str_form_value}" />
		</div>
	</div>
</form>

<form class="entryform form-inline" role="form" action="{$wwwroot}/modules/webmail/administrer/webmail_send.php" method="post" style="margin-top:10px">
	<div class="entete">{$STR_ADMIN_UTILISATEURS_USERS_COUNT}{$STR_BEFORE_TWO_POINTS}: {$nbRecord}</div>
	<div><span class="glyphicon glyphicon-plus"></span> <a href="{$administrer_url}/utilisateurs.php?mode=ajout">{$STR_ADMIN_UTILISATEURS_CREATE}</a></div>
{if isset($results)}
	<div><a href="{$wwwroot_in_admin}/modules/export/administrer/export_clients.php?priv={$priv}&amp;cle={$cle}">{$STR_ADMIN_UTILISATEURS_EXCEL_EXPORT}</a></div>
	<div class="center">{$link_multipage}</div>
	<div class="table-responsive">
	<table id="tablesForm" class="table">
		{$link_HeaderRow}
	{foreach $results as $res}
		{$res.tr_rollover}
			<td style="width:111px">
				<table style="width:111px">
					<tr>
						<td><input name="user_ids[]" type="checkbox" value="{$res.id_utilisateur|str_form_value}" id="{$res.id_utilisateur}" /></td>
						<td><a data-confirm="{$STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD_CONFIRM}" title="{$STR_ADMIN_UTILISATEURS_SEND_NEW_PASSWORD|str_form_value} {$res.email}" href="{$res.init_href|escape:'html'}"><img src="{$administrer_url}/images/password-24.gif" alt="" /></a></td>
						<td><a title="{$STR_ADMIN_UTILISATEURS_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}"><img src="{$administrer_url}/images/b_edit.png" width="17" height="17" alt="" /></a></td>
						<td><a {if !empty($res.etat)} data-confirm="{$STR_ADMIN_UTILISATEURS_DEACTIVATE_USER}"{/if} title="{$STR_ADMIN_UTILISATEURS_UPDATE_STATUS|str_form_value}" href="{$res.modif_etat_href|escape:'html'}"><img class="change_status" src="{$res.etat_src|escape:'html'}" alt="" /></a></td>
					</tr>
					<tr>
						<td></td>
						<td><a href="{$administrer_url}/codes_promos.php?mode=code_pour_client&amp;id_utilisateur={$res.id_utilisateur}" title="{$STR_ADMIN_CODES_PROMOS_SEND_BY_EMAIL_SUBTITLE|str_form_value}"><img src="{$wwwroot_in_admin}/icones/cheque.gif" width="25" height="25" alt="{$STR_ADMIN_UTILISATEURS_GIFT_CHECK|str_form_value}" /></a></td>
						<td><a href="{$administrer_url}/commander.php?mode=ajout&amp;id_utilisateur={$res.id_utilisateur}" title="{$STR_ADMIN_UTILISATEURS_CREATE_ORDER|str_form_value}"><img src="{$wwwroot_in_admin}/icones/proforma.gif" width="25" height="25" alt="{$STR_ORDER_FORM|str_form_value}" /></a></td>
						<td><a data-confirm="{$STR_ADMIN_DELETE_WARNING|str_form_value}" title="{$STR_DELETE|str_form_value} {$res.email}" href="{$res.drop_href|escape:'html'}"><img src="{$administrer_url}/images/b_drop.png" alt="" /></a></td>
					</tr>
				</table>
			</td>
			<td class="center">
				{$res.profil_name}<br /><a title="{$STR_ADMIN_UTILISATEURS_UPDATE|str_form_value}" href="{$res.edit_href|escape:'html'}">{$res.code_client|html_entity_decode_if_needed}</a>
				{if $is_annonce_module_active}
				<br />{$res.pseudo|html_entity_decode_if_needed}<br />{$res.annonces_count} {$STR_MODULE_ANNONCES_AD}
				{/if}
			</td>
			<td class="center"><b>{$res.prenom|html_entity_decode_if_needed} {$res.nom_famille|html_entity_decode_if_needed}</b><br />{if $is_not_demo}<a href="{$wwwroot_in_admin}/modules/webmail/administrer/webmail_send.php?id_utilisateur={$res.id_utilisateur}">{$res.email|html_entity_decode_if_needed}</a>{/if}{if !empty($res.email_infos)}<br />{$res.email_infos}{/if}
			{if $is_annonce_module_active}
				<br />{$res.societe|html_entity_decode_if_needed}, 
				{if $res.siret_length > 6}{$STR_NUMBER}{$res.siret|html_entity_decode_if_needed}
				{else}
					{if !empty($STR_SIRET)}
						{if !empty($res.siret)}
				<br /><span style="color:red">{$STR_SIRET} {$res.siret|html_entity_decode_if_needed}</span>
						{else}
				<br /><span style="color:red">{$STR_SIRET} : {$STR_NONE}.</span>
						{/if}
					{/if}
				{/if}
				<br />{$res.code_postal|html_entity_decode_if_needed} {$res.ville} - {$res.country_name}
			{/if}
			</td>
			<td>{$res.phone_output}</td>
			{if $is_groups_module_active}
			<td class="center">
			{if !empty($res.group_nom) && !empty($res.group_remise)}
				{$res.group_nom|html_entity_decode_if_needed} - {$res.group_remise} %
			{else}
				-
			{/if}
			</td>
			{/if}
			<td class="center">{$res.date_insert}</td>
			<td class="center">{if $res.count_ordered}<a href="{$administrer_url}/commander.php?mode=recherche&amp;client_info={$res.email}" title="{$STR_ADMIN_UTILISATEURS_ORDERS_LIST|str_form_value}">{$res.total_ordered} ({$res.count_ordered})</a>{else}-{/if}</td>
			<td class="center">{$res.remise_percent} %</td>
			<td class="center">{$res.avoir_prix}</td>
			<td class="center">{$res.points}</td>
			{if $is_parrainage_module_active}
			<td class="center">{$res.recuperer_parrain}</td>
			{/if}
			<td class="center">{$res.site_name}</td>
		</tr>
	{/foreach}
	</table>
	</div>
	<div class="center">
		<input type="button" class="btn btn-info" onclick="if (markAllRows('tablesForm')) return false;" value="{$STR_ADMIN_CHECK_ALL|str_form_value}" />
		<input type="button" class="btn btn-info" onclick="if (unMarkAllRows('tablesForm')) return false;" value="{$STR_ADMIN_UNCHECK_ALL|str_form_value}" />
			&nbsp; &nbsp; &nbsp; &nbsp; <input class="btn btn-primary" type="submit" value="{$STR_ADMIN_UTILISATEURS_SEND_EMAIL_TO_SELECTED_USERS|str_form_value}" />
		<a class="btn btn-primary" href="{$export_client_href}">{$STR_ADMIN_MENU_WEBMASTERING_CLIENTS_EXPORT}</a></td>
	</div>
	<div class="center">{$link_multipage}</div>
	<div class="alert alert-info">{$STR_ADMIN_UTILISATEURS_LIST_EXPLAIN}</div>
{else}
	<div><br /><b>{$STR_ADMIN_UTILISATEURS_NO_SUPPLIER_FOUND}</b></div>
{/if}
</form>
{if isset($send_email_all_form)}
<center>{$send_email_all_form}</center>
<div class="alert alert-info">{$STR_ADMIN_UTILISATEURS_FILER_EXPLAIN}</div>
{/if}