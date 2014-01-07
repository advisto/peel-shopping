<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: display_caddie.php 39443 2014-01-06 16:44:24Z sdelaporte $
// Fichier inclue uniquement sur les pages qui font appels aux fonctions ci-dessous
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('get_caddie_content_html')) {
	/**
	 * get_caddie_content_html()
	 *
	 * @param class $form_error_object
	 * @param mixed $mode_transport
	 * @return
	 */
	function get_caddie_content_html(&$form_error_object, $mode_transport)
	{
		$output = '';
		$listcadeaux_owner = '';
		// Vérifie pour offrir les frais de port que le seuil d'offre ou le nombre de produits sont différents de 0
		// si différent de 0, on 'construit' le message à afficher
		// Attention : un test !empty ne marche pas sur seuil_total_used car au format "0.00"
		$seuil_total_used = (is_reseller_module_active() && is_reseller()) ? $GLOBALS['site_parameters']['seuil_total_reve'] : $GLOBALS['site_parameters']['seuil_total'];
		$shipping_text = '';
		// Récupération des informations de la zone sélectionnée pour adapter le message.
		if(!empty($_SESSION['session_caddie']->zoneId)) {
			$q = query('SELECT on_franco_amount, on_franco_nb_products
				FROM peel_zones
				WHERE id="'.intval($_SESSION['session_caddie']->zoneId).'"');
			$zone_result = fetch_assoc($q);
			$on_franco_amount = $zone_result['on_franco_amount'];
			$on_franco_nb_products = $zone_result['on_franco_nb_products'];
		}
		if (round($seuil_total_used, 2) > 0 || !empty($GLOBALS['site_parameters']['nb_product']) || (!empty($on_franco_amount) && round($on_franco_amount, 2) > 0) || (!empty($on_franco_nb_products) && $on_franco_nb_products > 0)) {
			$shipping_text .= $GLOBALS['STR_SHIPPING_COST'] . ' (' . $GLOBALS['STR_OFFERED'] . ' ';
			// Le seuil d'exonération des frais de port pour une zone est prioritaire sur le seuil d'exonération des frais de port de la configuration générale de la boutique
			if((!empty($on_franco_amount) && round($on_franco_amount, 2) > 0) || (!empty($on_franco_nb_products) && $on_franco_nb_products > 0)) {
				$shipping_text .= !empty($on_franco_amount)? $GLOBALS['STR_FROM'] . ' ' . fprix($on_franco_amount, true) . ' ' . $GLOBALS['STR_TTC'] : '';
				if(!empty($on_franco_nb_products)) {
					$shipping_text .= !empty($on_franco_amount)? ' ' . $GLOBALS['STR_OR'] . ' '. $GLOBALS['STR_FROM'] . ' ' . $on_franco_nb_products . ' ' . $GLOBALS['STR_PRODUCTS_PURCHASED'] : $GLOBALS['STR_FROM'] . ' ' . $on_franco_nb_products . ' ' . $GLOBALS['STR_PRODUCTS_PURCHASED'];
				}
			} elseif (round($seuil_total_used, 2) > 0 ) {
				$shipping_text .= $GLOBALS['STR_FROM'] . ' ' . fprix($seuil_total_used, true) . ' ' . $GLOBALS['STR_TTC'];
			}
			if (!empty($GLOBALS['site_parameters']['nb_product']) && empty($on_franco_nb_products)) {
				$shipping_text .= (round($seuil_total_used, 2) > 0 || (!empty($on_franco_amount) && round($on_franco_amount, 2) > 0) ? ' ' . $GLOBALS['STR_OR_COORDINATION'] : '') . ' ' . $GLOBALS['STR_FROM'] . ' ' . $GLOBALS['site_parameters']['nb_product'] . ' ' . $GLOBALS['STR_PRODUCT_BUY'];
			}
			$shipping_text .= ')';
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('caddie_content_html.tpl');
		$tpl->assign('is_empty', ($_SESSION['session_caddie']->count_products() == 0));
		$tpl->assign('STR_EMPTY_CADDIE', $GLOBALS['STR_EMPTY_CADDIE']);
		
		if(!($_SESSION['session_caddie']->count_products() == 0)){
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			if (is_module_vacances_active() && get_vacances_type() == 1) {
				$tpl->assign('global_error', vb($GLOBALS['site_parameters']['module_vacances_client_msg_' . $_SESSION['session_langue']]));
			}
			$tpl->assign('erreur_caddie', $_SESSION['session_caddie']->affiche_erreur_caddie());
			$tpl->assign('action', get_current_url(false));
			$tpl->assign('products_summary_table', get_caddie_products_summary_table(true, true, $mode_transport, $shipping_text));
			$tpl->assign('shipping_text', $shipping_text);
			$tpl->assign('STR_UPDATE', $GLOBALS['STR_UPDATE']);
			
			if (est_identifie()) {
				$tpl->assign('code_promo', array(
					'txt' => $GLOBALS['STR_CODE_PROMO'],
					'value' => vb($_SESSION['session_caddie']->code_promo),
				));
				if (!empty($_SESSION['session_caddie']->code_promo)) {
					$tpl->assign('code_promo_delete', array(
						'href' => get_current_url(false) . '?code_promo=delete',
						'src' => $GLOBALS['wwwroot'] . '/icones/rupture.png',
						'txt' => $GLOBALS['STR_DELETE']
					));
				}
			} else {
				$tpl->assign('membre_href', $GLOBALS['wwwroot'] . '/membre.php');
				$tpl->assign('STR_LOGIN_FOR_REBATE', $GLOBALS['STR_LOGIN_FOR_REBATE']);
				$tpl->assign('STR_PLEASE_LOGIN', $GLOBALS['STR_PLEASE_LOGIN']);
				$tpl->assign('STR_REBATE_NOW', $GLOBALS['STR_REBATE_NOW']);
			}
			
			$tpl->assign('is_mode_transport', !empty($mode_transport));
			if (!empty($mode_transport)) {
				$tpl->assign('zone_error', $form_error_object->text('pays_zone'));
				$tpl->assign('STR_DELIVERY', $GLOBALS['STR_DELIVERY']);
				$tpl->assign('STR_SHIPPING_ZONE', $GLOBALS['STR_SHIPPING_ZONE']);
				$tpl->assign('STR_REFRESH', $GLOBALS['STR_REFRESH']);
				$tpl->assign('STR_SHIP_ZONE_CHOOSE', $GLOBALS['STR_SHIP_ZONE_CHOOSE']);
				// On gère le port : même en cas de tarification indépendante de la zone de livraison,
				// il faut demander la zone afin de pouvoir identifier les modes de livraison possibles
				$sqlZone = 'SELECT id, nom_' . $_SESSION['session_langue'] . ' AS nom
					FROM peel_zones
					ORDER BY position, nom';
				$resZone = query($sqlZone);
				$zone_options = array();
				while ($Zone = fetch_assoc($resZone)) {
					$selected = false;
					if (empty($_SESSION['session_caddie']->zoneId)) {
						// L'utilisateur vient d'arriver sur la page de caddie : on présélectionne la zone liée à l'adresse de son compte
						if (!empty($_SESSION['session_utilisateur']['zoneId']) && $_SESSION['session_utilisateur']['zoneId'] == $Zone['id']) {
							$selected = true;
							$_SESSION['session_caddie']->set_zone($_SESSION['session_utilisateur']['zoneId']);
						}
					} elseif (!empty($_SESSION['session_caddie']->zoneId) && $_SESSION['session_caddie']->zoneId == $Zone['id']) {
						$selected = true;
					}
					$zone_options[] = array(
						'value' => $Zone['id'],
						'issel' => $selected,
						'name' => $Zone['nom']
					);
				}
				$tpl->assign('zone_options', $zone_options);
				$tpl->assign('zone', $_SESSION['session_caddie']->zone);
				$tpl->assign('STR_SHIPPING_ZONE', $GLOBALS['STR_SHIPPING_ZONE']);
				$tpl->assign('is_zone', (!empty($_SESSION['session_caddie']->zoneId) && !empty($mode_transport)));
				if (!empty($_SESSION['session_caddie']->zoneId) && !empty($mode_transport)) {
					if ($mode_transport == 1) {
						// Ici on est dans le cas où le calcul des frais de ports est par poids ou par montant total
						$sqlType = 'SELECT DISTINCT(t.id), t.nom_' . $_SESSION['session_langue'] . '
							FROM peel_tarifs tf
							INNER JOIN peel_types t ON t.id = tf.type
							WHERE t.etat = 1 AND tf.zone = "' . intval($_SESSION['session_caddie']->zoneId) . '" AND (poidsmin<="' . floatval($_SESSION['session_caddie']->total_poids) . '" OR poidsmin=0) AND (poidsmax>="' . floatval($_SESSION['session_caddie']->total_poids) . '" OR poidsmax=0) AND (totalmin<="' . floatval($_SESSION['session_caddie']->total_produit) . '" OR totalmin=0) AND (totalmax>="' . floatval($_SESSION['session_caddie']->total_produit) . '" OR totalmax=0)
							ORDER BY t.position ASC, t.nom_' . $_SESSION['session_langue'] . ' ASC';
						$resType = query($sqlType);
					}
					$tpl->assign('shipping_type_error', $form_error_object->text('type'));
					$tpl->assign('STR_SHIPPING_TYPE', $GLOBALS['STR_SHIPPING_TYPE']);
					$tpl->assign('STR_SHIP_TYPE_CHOOSE', $GLOBALS['STR_SHIP_TYPE_CHOOSE']);
					$tpl->assign('STR_ERREUR_TYPE', $GLOBALS['STR_ERREUR_TYPE']);
					if (!empty($resType) && num_rows($resType) > 0) {
						$type_options = array();
						while ($Type = fetch_assoc($resType)) {
							$type_options[] = array(
								'value' => intval($Type['id']),
								'issel' => (vb($_SESSION['session_caddie']->typeId) == $Type['id']),
								'name' => $Type['nom_' . $_SESSION['session_langue']]
							);
						}
						$tpl->assign('shipping_type_options', $type_options);
					}
				}
			}
			
			$tpl->assign('is_cart_preservation_module_active', is_cart_preservation_module_active());
			$tpl->assign('preservation_href', $GLOBALS['wwwroot'] . '/modules/cart_preservation/cart_preservation.php?mode=save');
			$tpl->assign('STR_SAVE_CART', $GLOBALS['STR_SAVE_CART']);
			
			if (round($_SESSION['session_caddie']->avoir_user, 2) > round($_SESSION['session_caddie']->avoir, 2))
				$tpl->assign('STR_SUGGEST', $GLOBALS['STR_SUGGEST']);

			if ($GLOBALS['site_parameters']['minimal_amount_to_order'] > $_SESSION['session_caddie']->total_produit) {
				$tpl->assign('is_minimum_error', true);
				$tpl->assign('STR_MINIMUM_PURCHASE_OF', $GLOBALS['STR_MINIMUM_PURCHASE_OF']);
				$tpl->assign('minimum_prix', fprix($GLOBALS['site_parameters']['minimal_amount_to_order'], true));
				$tpl->assign('STR_REQUIRED_VALIDATE_ORDER', $GLOBALS['STR_REQUIRED_VALIDATE_ORDER']);
			} else {
				$tpl->assign('is_minimum_error', false);
				$tpl->assign('STR_ORDER', $GLOBALS['STR_ORDER']);
			}
			$tpl->assign('shopping_href', $GLOBALS['wwwroot'] . '/achat/');
			$tpl->assign('empty_list_href', get_current_url(false) . '?func=vide');
			$tpl->assign('STR_SHOPPING', $GLOBALS['STR_SHOPPING']);
			$tpl->assign('STR_EMPTY_LIST', $GLOBALS['STR_EMPTY_LIST']);
			$tpl->assign('STR_EMPTY_CART', $GLOBALS['STR_EMPTY_CART']);
		}
		$output = $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_order_step1')) {
	/**
	 * get_order_step1()
	 *
	 * @param array $frm Array with all fields data
	 * @param class $form_error_object
	 * @param mixed $mode_transport
	 * @return
	 */
	function get_order_step1(&$frm, &$form_error_object, $mode_transport)
	{
		$output = '';
		if (empty($_SESSION['session_caddie']) || $_SESSION['session_caddie']->count_products() == 0) {
			$output .= $GLOBALS['STR_EMPTY_CADDIE'];
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('order_step1.tpl');
			$tpl->assign('error_cvg', $form_error_object->text('cgv'));
			$tpl->assign('action', get_current_url(false));
			$tpl->assign('societe1', $frm['societe1']);
			$tpl->assign('nom1_error', $form_error_object->text('nom1'));
			$tpl->assign('nom1', $frm['nom1']);
			$tpl->assign('prenom1_error', $form_error_object->text('prenom1'));
			$tpl->assign('prenom1', $frm['prenom1']);
			$tpl->assign('email1_error', $form_error_object->text('email1'));
			$tpl->assign('email1', $frm['email1']);
			$tpl->assign('contact1_error', $form_error_object->text('contact1'));
			$tpl->assign('contact1', $frm['contact1']);
			$tpl->assign('adresse1_error', $form_error_object->text('adresse1'));
			$tpl->assign('adresse1', $frm['adresse1']);
			$tpl->assign('code_postal1_error', $form_error_object->text('code_postal1'));
			$tpl->assign('code_postal1', $frm['code_postal1']);
			$tpl->assign('ville1_error', $form_error_object->text('ville1'));
			$tpl->assign('ville1', $frm['ville1']);
			$tpl->assign('pays1_error', $form_error_object->text('pays1'));
			$tpl->assign('pays1_options', get_country_select_options($frm['pays1'], null));
			$tpl->assign('STR_STEP1', $GLOBALS['STR_STEP1']);
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('STR_INVOICE_ADDRESS', $GLOBALS['STR_INVOICE_ADDRESS']);
			$tpl->assign('STR_SOCIETE', $GLOBALS['STR_SOCIETE']);
			$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
			$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
			$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
			$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
			$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
			$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
			$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
			$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
			if (!empty($mode_transport) && is_delivery_address_necessary_for_delivery_type(vn($_SESSION['session_caddie']->typeId)) && (!is_socolissimo_module_active() || empty($_SESSION['session_commande']['is_socolissimo_order']))) {
				$tpl->assign('is_mode_transport', true);
				$tpl->assign('STR_SHIP_ADDRESS', $GLOBALS['STR_SHIP_ADDRESS']);
				if(is_icirelais_module_active() && !empty($_SESSION['session_commande']['is_icirelais_order'])) {
					$tpl->assign('text_temp_address', $GLOBALS["STR_MODULE_ICIRELAIS_TEMP_ADDRESS"]);
				}
				$tpl->assign('societe2', $frm['societe2']);
				$tpl->assign('nom2_error', $form_error_object->text('nom2'));
				$tpl->assign('nom2', $frm['nom2']);
				$tpl->assign('prenom2_error', $form_error_object->text('prenom2'));
				$tpl->assign('prenom2', $frm['prenom2']);
				$tpl->assign('email2_error', $form_error_object->text('email2'));
				$tpl->assign('email2', $frm['email2']);
				$tpl->assign('contact2_error', $form_error_object->text('contact2'));
				$tpl->assign('contact2', $frm['contact2']);
				$tpl->assign('adresse2_error', $form_error_object->text('adresse2'));
				$tpl->assign('adresse2', $frm['adresse2']);
				$tpl->assign('code_postal2_error', $form_error_object->text('code_postal2'));
				$tpl->assign('code_postal2', $frm['code_postal2']);
				$tpl->assign('ville2_error', $form_error_object->text('ville2'));
				$tpl->assign('ville2', $frm['ville2']);
				$tpl->assign('pays2_error', $form_error_object->text('pays2'));
				$tpl->assign('pays2_options', get_country_select_options($frm['pays2'], null, 'name', false, $_SESSION['session_caddie']->zoneId));
			}else {
				$tpl->assign('is_mode_transport', false);
			}
			if ($_SESSION['session_caddie']->total > 0) {
				$tpl->assign('is_payment_cgv', true);
				if(isset($erreurs['paiement'])) {
					$tpl->assign('STR_ERR_PAYMENT', $GLOBALS['STR_ERR_PAYMENT']);
				}
				$tpl->assign('payment_error', $form_error_object->text('payment_technical_code'));
				$tpl->assign('payment_select', get_payment_select($_SESSION['session_caddie']->payment_technical_code));
				$tpl->assign('STR_PAYMENT', $GLOBALS['STR_PAYMENT']);
			} else {
				$tpl->assign('is_payment_cgv', false);
			}
			$tpl->assign('STR_CGV_OK', $GLOBALS['STR_CGV_OK']);
			$tpl->assign('commentaires', $frm['commentaires']);
			$tpl->assign('STR_COMMENTS', $GLOBALS['STR_COMMENTS']);
			$tpl->assign('STR_ETAPE_SUIVANTE', $GLOBALS['STR_ETAPE_SUIVANTE']);
			$output = $tpl->fetch();
		}
		return $output;
	}
}

if (!function_exists('get_order_step2')) {
	/**
	 * get_order_step2()
	 *
	 * @param array $frm Array with all fields data
	 * @param mixed $mode_transport
	 * @return
	 */
	function get_order_step2(&$frm, $mode_transport)
	{
		$output = '';
		if ($_SESSION['session_caddie']->count_products() == 0) {
			$output .= $GLOBALS['STR_EMPTY_CADDIE'];
		} else {
			$is_delivery_address_necessary_for_delivery_type = is_delivery_address_necessary_for_delivery_type(vn($_SESSION['session_caddie']->typeId));
			$tpl = $GLOBALS['tplEngine']->createTemplate('order_step2.tpl');
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('STR_STEP2', $GLOBALS['STR_STEP2']);
			$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
			$tpl->assign('STR_INVOICE_ADDRESS', $GLOBALS['STR_INVOICE_ADDRESS']);
			$tpl->assign('STR_SOCIETE', $GLOBALS['STR_SOCIETE']);
			$tpl->assign('STR_CUSTOMER', $GLOBALS['STR_CUSTOMER']);
			$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
			$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
			$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
			$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
			$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
			$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
			$tpl->assign('date', get_formatted_date(time()));
			$tpl->assign('societe1', $frm['societe1']);
			$tpl->assign('nom1', $frm['nom1']);
			$tpl->assign('prenom1', $frm['prenom1']);
			$tpl->assign('contact1', $frm['contact1']);
			$tpl->assign('email1', $frm['email1']);
			$tpl->assign('adresse1', $frm['adresse1']);
			$tpl->assign('code_postal1', $frm['code_postal1']);
			$tpl->assign('ville1', $frm['ville1']);
			$tpl->assign('pays1', $frm['pays1']);
			if (trim($frm['commentaires']) != '') {
				$tpl->assign('STR_COMMENTS', $GLOBALS['STR_COMMENTS']);
				$tpl->assign('commentaires', $frm['commentaires']);
			}
			if (!empty($mode_transport)) {
				$tpl->assign('STR_SHIPPING_TYPE', $GLOBALS['STR_SHIPPING_TYPE']);
				$tpl->assign('STR_SHIP_ADDRESS', $GLOBALS['STR_SHIP_ADDRESS']);
				$tpl->assign('STR_PAYMENT', $GLOBALS['STR_PAYMENT']);
				$tpl->assign('STR_DELIVERY', $GLOBALS['STR_DELIVERY']);
				$tpl->assign('is_mode_transport', true);
				$tpl->assign('payment', get_payment_name($_SESSION['session_caddie']->payment_technical_code));
				$tpl->assign('shipping_zone', $_SESSION['session_caddie']->zone);
				$tpl->assign('shipping_type', $_SESSION['session_caddie']->type);
				$tpl->assign('is_delivery_address_necessary_for_delivery_type', $is_delivery_address_necessary_for_delivery_type);
				if($is_delivery_address_necessary_for_delivery_type) {
					$tpl->assign('societe2', $frm['societe2']);
					$tpl->assign('nom2', $frm['nom2']);
					$tpl->assign('prenom2', $frm['prenom2']);
					$tpl->assign('email2', $frm['email2']);
					$tpl->assign('contact2', $frm['contact2']);
					$tpl->assign('adresse2', $frm['adresse2']);
					$tpl->assign('code_postal2', $frm['code_postal2']);
					$tpl->assign('ville2', $frm['ville2']);
					$tpl->assign('pays2', $frm['pays2']);
				}
			} else {
				$tpl->assign('is_mode_transport', false);
			}
			$tpl->assign('action', $GLOBALS['wwwroot'] . '/achat/fin_commande.php');
			
			if (is_icirelais_module_active() && !empty($_SESSION['session_commande']['is_icirelais_order'])) {
				$tpl->assign('icirelais_id_delivery_points_radio_inputs', get_icirelais_id_delivery_points_radio_inputs($is_delivery_address_necessary_for_delivery_type));
			}
			if(is_tnt_module_active() && $GLOBALS['web_service_tnt']->is_type_tntdropoffpoint(vn($_SESSION['session_caddie']->typeId)) && $GLOBALS['web_service_tnt']->is_type_linked_to_tnt(vn($_SESSION['session_caddie']->typeId)) && (defined('IN_STEP1') || defined('IN_STEP2') || defined('IN_STEP3'))) {
				try {
					$tpl->assign('get_tnt_id_delivery_points_radio_inputs', $GLOBALS['web_service_tnt']->get_tnt_id_delivery_points_radio_inputs());
				} catch (SoapFault $ex) {
					// var_dump($ex->faultcode, $ex->faultstring, $ex->detail);
					echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_TNT_ERREUR_WEBSERVICE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.$ex->faultstring))->fetch();
				}
			}
			$tpl->assign('caddie_products_summary_table', get_caddie_products_summary_table(false, true, $mode_transport, null));
			$tpl->assign('STR_ORDER', $GLOBALS['STR_ORDER']);
			$tpl->assign('STR_BACK_TO_CADDIE_TXT', $GLOBALS['STR_BACK_TO_CADDIE_TXT']);
			$output = $tpl->fetch();
		}
		return $output;
	}
}

if (!function_exists('get_order_step3')) {
	/**
	 * get_order_step3()
	 *
	 * @param integer $commandeid
	 * @return
	 */
	function get_order_step3($commandeid)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('order_step3.tpl');
		$tpl->assign('payment_form', get_payment_form($commandeid, null, true));
		$tpl->assign('resume_commande', affiche_resume_commande($commandeid, false, true));
		$tpl->assign('conversion_page', affiche_contenu_html("conversion_page", true));
		$tpl->assign('STR_STEP3', $GLOBALS['STR_STEP3']);
		$tpl->assign('STR_MSG_THANKS', $GLOBALS["STR_MSG_THANKS"]);
		echo $tpl->fetch();
	}
}

if (!function_exists('affiche_resume_commande')) {
	/**
	 * affiche_resume_commande()
	 *
	 * @param integer $id
	 * @param boolean $affiche_statut
	 * @param boolean $show_only_owned_by_current_user
	 * @param boolean $show_payment_form
	 * @return
	 */
	function affiche_resume_commande($id, $affiche_statut, $show_only_owned_by_current_user = true, $show_payment_form = false)
	{
		if(!empty($_SESSION['session_last_bill_viewed']) && $id == $_SESSION['session_last_bill_viewed']){
			// L'utilisateur a payé une commande sans s'être connecté à son compte. On l'autorise à voir le résumé de sa commande
			$where = 'id = "' . intval($_SESSION['session_last_bill_viewed']) . '"';
		} else {
			// Cas normal d'un utilisateur connecté à son compte
			// OU si un utilisateur non loggué vient voir cette page sans avoir $_SESSION['session_last_bill_viewed'] correct : par exemple moteur Google Adsense qui vient pour afficher les publicités => il n'y aura rien de trouvé => page sans infos de commandes => normal
			$where = 'id = "' . intval($id) . '"';
		}
		if($show_only_owned_by_current_user){
			$where .= ' AND id_utilisateur = "' . intval(vb($_SESSION['session_utilisateur']['id_utilisateur'])) . '"';
		}
		$qid_commande = query('SELECT *
			FROM peel_commandes
			WHERE '.$where);
		$commande = fetch_object($qid_commande);
		$output = '';
		if (!empty($commande)) {
			$order_infos = get_order_infos_array($commande);
			$tpl = $GLOBALS['tplEngine']->createTemplate('resume_commande.tpl');
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('STR_MODULE_TELECHARGEMENT_FOR_DOWNLOAD', $GLOBALS['STR_MODULE_TELECHARGEMENT_FOR_DOWNLOAD']);
			$tpl->assign('STR_ORDER_DETAIL', $GLOBALS['STR_ORDER_DETAIL']);
			$tpl->assign('STR_ORDER_NUMBER', $GLOBALS['STR_ORDER_NUMBER']);
			$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
			$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
			$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
			$tpl->assign('STR_INVOICE_ADDRESS', $GLOBALS['STR_INVOICE_ADDRESS']);
			$tpl->assign('STR_PAYMENT', $GLOBALS['STR_PAYMENT']);
			$tpl->assign('id', $id);
			$tpl->assign('date', get_formatted_date($commande->o_timestamp, 'short', 'long'));
			$tpl->assign('order_amount', $order_infos['net_infos_array']['montant']);
			$tpl->assign('bill_address', $order_infos['client_infos_bill']);
			if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
				$tpl->assign('STR_SHIP_ADDRESS', $GLOBALS['STR_SHIP_ADDRESS']);
				$tpl->assign('ship_address', $order_infos['client_infos_ship']);
			}
			$tpl->assign('payment', get_payment_name($commande->paiement));
			if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
				$tpl->assign('STR_SHIPPING_TYPE', $GLOBALS['STR_SHIPPING_TYPE']);
				$tpl->assign('shipping_type', $commande->type);
			}
			if (!empty($commande->delivery_tracking)) {
				$tpl->assign('is_delivery_tracking', true);
				$tpl->assign('STR_TRACKING_LINK', $GLOBALS['STR_TRACKING_LINK']);
				$tpl->assign('delivery_tracking', $commande->delivery_tracking);
				if(is_icirelais_module_active()){
					$tpl->assign('icirelais', array(
						'src' => $GLOBALS['wwwroot'] . '/modules/icirelais/js/icirelais.js',
						'value' => vb($commande->delivery_tracking)
					));
					$tpl->assign('STR_MODULE_ICIRELAIS_CONFIGURATION_TRACKING_URL_TITLE', $GLOBALS['STR_MODULE_ICIRELAIS_CONFIGURATION_TRACKING_URL_TITLE']);
					$tpl->assign('STR_MODULE_ICIRELAIS_COMMENT_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_COMMENT_TRACKING']);
					$tpl->assign('STR_MODULE_ICIRELAIS_ERROR_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_ERROR_TRACKING']);
					$tpl->assign('STR_MODULE_ICIRELAIS_CREATE_TRACKING', $GLOBALS['STR_MODULE_ICIRELAIS_CREATE_TRACKING']);
				}
			} else {
				$tpl->assign('is_delivery_tracking', false);
			}

			if((defined('IN_STEP1') || defined('IN_STEP2') || defined('IN_STEP3')) && is_tnt_module_active() && $GLOBALS['web_service_tnt']->is_type_linked_to_tnt(vn($_SESSION['session_caddie']->typeId))) {
				$receiver_info['type_id'] = '';
				if(!empty($commande->type)) {
					// Récupération des informations sur le type de transport sélectionné.
					$sql = 'SELECT *
						FROM peel_types 
						WHERE nom_' . $commande->lang . ' = "'. nohtml_real_escape_string($commande->type).'"';
					$q = query($sql);
					if ($this_type = fetch_assoc($q)) {
						if ($this_type['is_tnt'] == 0) {
							die($GLOBALS['STR_MODULE_TNT_NOT_ASSOCIATED_ORDER']);
						}
					}
				}
				// Determine le type de livraison (DROPOFFPOINT,ENTERPRISE,INDIVIDUAL), et le code de service TNT associé(J,JD,JZ)
				if(!empty($_POST['relais_tnt']) && $this_type['tnt_threshold'] == 0) {
					// le relais colis est transmis en paramètre, et le type de livraison correspond à une livraison en relais colis
					$tab_relais_tnt = explode('###', $_POST['relais_tnt']);
					$order_infos['code_postal2'] = $tab_relais_tnt[0];
					$order_infos['ville2'] = $tab_relais_tnt[1];
					$receiver_info['type_id'] = $tab_relais_tnt[2];// code XETT du relai de colis
					$receiver_info['type']    = 'DROPOFFPOINT';
				} else {
					$order_infos['code_postal2'] = $_SESSION['session_commande']['code_postal2'];
					$order_infos['ville2'] = $_SESSION['session_commande']['ville2'];
				}
				
				if(!empty($commande->xETTCode) && $this_type['tnt_threshold'] == 0) {
					// le code xETTCode est renseigné pour la commande, et le type de livraison correspond à une livraison en relais colis
					$receiver_info['type_id'] = $commande->xETTCode; // code XETT du relai de colis
					$receiver_info['type']    = 'DROPOFFPOINT';
				} elseif(!empty($_SESSION['session_commande']['societe2'])) { 
					// Ce n'est pas une commande à livrer en point relais, et un nom d'entreprise est renseigné dans l'adresse de livraison => Livraison en entreprise  (code J).
					$receiver_info['type'] = 'ENTERPRISE';
				} else {
					// Autres cas : livraison à chez le particulier
					$receiver_info['type'] = 'INDIVIDUAL';
				}
				try {
					$tpl->assign('STR_MODULE_TNT_FEASIBILITY_REPORT', $GLOBALS['STR_MODULE_TNT_FEASIBILITY_REPORT']);
					$tpl->assign('tnt_message', $GLOBALS['web_service_tnt']->get_tnt_feasibility_test($order_infos, $receiver_info, true));
				} catch (SoapFault $ex) {
					$tpl->assign('tnt_erreur_message', $GLOBALS['STR_MODULE_TNT_ERREUR_WEBSERVICE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.$ex->faultstring );
				}

				$tpl->assign('tnt_message', $tnt_message);
			}

			if ($affiche_statut === 1 || $affiche_statut === true) {
				$tpl->assign('is_payment_delivery_status', true);
				$tpl->assign('order_statut_paiement_name', get_payment_status_name($commande->id_statut_paiement));
				$tpl->assign('STR_ORDER_STATUT_PAIEMENT', $GLOBALS['STR_ORDER_STATUT_PAIEMENT']);
				$tpl->assign('STR_ORDER_STATUT_LIVRAISON', $GLOBALS['STR_ORDER_STATUT_LIVRAISON']);
				$tpl->assign('order_statut_livraison_name', get_delivery_status_name($commande->id_statut_livraison));
				if (!empty($commande->numero)) {
					// Si le numéro de facture a été créé (ce moment est paramétrable dans la page de configuration du site), alors on transmet l'information sur la facture
					$tpl->assign('STR_INVOICE', $GLOBALS['STR_INVOICE']);
					$tpl->assign('STR_PRINT_YOUR_BILL', $GLOBALS['STR_PRINT_YOUR_BILL']);
					$tpl->assign('invoice', array(
						'src' => $GLOBALS['wwwroot'] . '/images/view_pdf.gif',
						'href' => $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . urlencode($commande->code_facture) . '&mode=facture',
					));
				}
			} else {
				$tpl->assign('is_payment_delivery_status', false);
			}
			if (is_payback_module_active()){
				$tpl->assign('STR_MODULE_PAYBACK_RETURN_REQUEST', $GLOBALS['STR_MODULE_PAYBACK_RETURN_REQUEST']);
				if(!defined('STR_RETURN_THIS_PRODUCT')) {
					$tpl->assign('return_this_product_txt', '');
				} else {
					$tpl->assign('return_this_product_txt', $GLOBALS['STR_MODULE_PAYBACK_RETURN_THIS_PRODUCT']);
				}
			}
			$tpl->assign('is_conditionnement_module_active', is_conditionnement_module_active());
			if (is_download_module_active()) {
				$tpl->assign('downloadable_file_link_array', get_downloadable_file_link(array('order_id' => $id)));
			}
			$products_data = array();
			$product_infos_array = get_product_infos_array_in_order($id, $commande->devise, $commande->currency_rate);
			foreach ($product_infos_array as $this_ordered_product) {
				if (is_attributes_module_active()) {
					$this_ordered_product["product_text"] = display_option_image($this_ordered_product["product_text"], true);
				}
				if(is_conditionnement_module_active ()) {
					// Les produits sont conditionnés sous forme de lot
					if(!empty($this_ordered_product['conditionnement'])){
						$qte_total = intval($this_ordered_product['quantite']) * $this_ordered_product['conditionnement'];
					} else {
						$qte_total = $this_ordered_product['quantite'];
					}
				}
				$products_data[] = array(
					'reference' => $this_ordered_product['reference'],
					'product_text' => str_replace("\n", '<br />', $this_ordered_product["product_text"]),
					'prix' => fprix($this_ordered_product['prix'], true, $commande->devise, true, $commande->currency_rate),
					'conditionnement' => (!empty($this_ordered_product['conditionnement'])?$this_ordered_product['conditionnement']:'-'),
					'conditionnement_qty' => vb($qte_total),
					'quantite' => $this_ordered_product['quantite'],
					'total_prix' => fprix($this_ordered_product['total_prix'], true, $commande->devise, true, $commande->currency_rate),
					'is_form_retour' => (is_payback_module_active() && in_array($commande->id_statut_paiement, array(2,3)) && $commande->id_statut_livraison == 3 && $this_ordered_product['statut'] = 1 && $this_ordered_product['quantite'] > 0),
					'action' => $GLOBALS['wwwroot'] . '/modules/payback/form_retour.php',
					'commandeid' => $commande->id,
					'utilisateurid' => $commande->id_utilisateur,
					'paiement' => $commande->paiement,
					'langue' => $commande->lang,
					'nom_produit' => $this_ordered_product['nom_produit'],
					'taille_produit' => $this_ordered_product['taille'],
					'couleur_produit' => $this_ordered_product['couleur'],
					'id_produit' => $this_ordered_product['id'],
					'prix_ht_produit' => $this_ordered_product['prix_ht'],
					'prix_ttc_produit' => $this_ordered_product['prix'],
					'tva_produit' => $this_ordered_product['tva'],
				);
			}
			$tpl->assign('products_data', $products_data);
			if($show_payment_form) {
				$tpl->assign('payment_form', get_payment_form($id), $commande->paiement);
			}
			$tpl->assign('STR_LIST_PRODUCT', $GLOBALS['STR_LIST_PRODUCT']);
			$tpl->assign('STR_REFERENCE', $GLOBALS['STR_REFERENCE']);
			$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
			$tpl->assign('STR_SOLD_PRICE', $GLOBALS['STR_SOLD_PRICE']);
			$tpl->assign('STR_QUANTITY', $GLOBALS['STR_QUANTITY']);
			$tpl->assign('STR_TOTAL_TTC', $GLOBALS['STR_TOTAL_TTC']);
			$tpl->assign('STR_CONDITIONNEMENT', $GLOBALS['STR_CONDITIONNEMENT']);
			$tpl->assign('STR_CONDITIONNEMENT_QTY', $GLOBALS['STR_CONDITIONNEMENT_QTY']);
			$output .= $tpl->fetch();
		}
	return $output;
	}
}

if (!function_exists('affiche_liste_commandes')) {
	/**
	 * affiche_liste_commandes()
	 *
	 * @param string $order
	 * @param string $sort
	 * @return
	 */
	function affiche_liste_commandes($order = "id" , $sort = "DESC")
	{
		$nb = 30;
		$output = '';
		// Charge la liste des commandes avec un numéro de commande et les affiche.
		$sql = "SELECT *
			FROM peel_commandes
			WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'
			ORDER BY " . nohtml_real_escape_string($order) . " " . nohtml_real_escape_string($sort) . "";
		$tpl = $GLOBALS['tplEngine']->createTemplate('liste_commandes.tpl');
		$Links = new Multipage($sql, 'commandes_history');
		$results_array = $Links->Query();
		if (empty($results_array)) {
			$tpl->assign('STR_NO_ORDER', $GLOBALS['STR_NO_ORDER']);
		} else {
			$tpl->assign('STR_TABLE_SUMMARY_ORDERS', $GLOBALS['STR_TABLE_SUMMARY_ORDERS']);
			$tpl->assign('STR_ORDER_NUMBER', $GLOBALS['STR_ORDER_NUMBER']);
			$tpl->assign('STR_DATE', $GLOBALS['STR_DATE']);
			$tpl->assign('STR_ORDER_STATUT_PAIEMENT', $GLOBALS['STR_ORDER_STATUT_PAIEMENT']);
			$tpl->assign('STR_ORDER_STATUT_LIVRAISON', $GLOBALS['STR_ORDER_STATUT_LIVRAISON']);
			$tpl->assign('STR_AMOUNT', $GLOBALS['STR_AMOUNT']);
			$tpl->assign('STR_PDF_BILL', $GLOBALS['STR_PDF_BILL']);
			$orders = array();
			foreach ($results_array as $order) {
				// Si le numéro de facture a été créé (ce moment est paramétrable dans la page de configuration du site), alors on transmet l'information sur la facture
				$orders[] = array(
					'href' =>  get_current_url(false) . '?mode=details&id=' . $order['id'] . '&timestamp=' . urlencode($order['o_timestamp']),
					'info_src' => $GLOBALS['wwwroot'] . '/icones/info.gif',
					'pdf_src' => $GLOBALS['wwwroot'] . '/images/view_pdf.gif',
					'facture_href' => (!empty($order['numero'])? $GLOBALS['wwwroot'] . '/factures/commande_pdf.php?code_facture=' . $order['code_facture'] . '&mode=facture':''),
					'id' => $order['id'],
					'numero' => $order['numero'],
					'date' => get_formatted_date($order['o_timestamp']),
					'payment_status_name' => get_payment_status_name($order['id_statut_paiement']),
					'delivery_status_name' => get_delivery_status_name($order['id_statut_livraison']),
					'prix' => fprix($order['montant'], true, $order['devise'], true, $order['currency_rate']),
					'paid' => in_array($order['id_statut_paiement'], array(2,3))
				);
			}
			$tpl->assign('orders', $orders);
			$tpl->assign('multipage', $Links->GetMultipage());
		}
		$tpl->assign('STR_ORDER_HISTORY', $GLOBALS['STR_ORDER_HISTORY']);
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('affichage_fin_cb')) {
	/**
	 * affichage_fin_cb()
	 *
	 * @param integer $order_id
	 * @param boolean $payment_validated
	 * @return
	 */
	function affichage_fin_cb($order_id, $payment_validated, $message = '')
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('fin_cb.tpl');
		$tpl->assign('payment_validated', $payment_validated);
		$tpl->assign('message', $message);
		if ($payment_validated) {
			$tpl->assign('payment_msg', $GLOBALS['STR_PAYMENT_SUCCEED']);
			$tpl->assign('bottom_msg', $GLOBALS['STR_YOU_CAN_EDIT_YOUR_ORDER']);
			$tpl->assign('resume_commande', affiche_resume_commande($order_id, false, true));
		}else{
			$tpl->assign('payment_msg', $GLOBALS['STR_PAYMENT_FAILED']);
			$tpl->assign('bottom_msg', $GLOBALS['STR_ORDER_RENEW_INVITE']);
		}
		$tpl->assign('STR_ORDER_STATUT', $GLOBALS['STR_ORDER_STATUT']);
		echo $tpl->fetch();
	}
}

if (!function_exists('get_caddie_products_summary_table')) {
	/**
	 * get_caddie_products_summary_table()
	 *
	 * @param mixed $with_form_fields
	 * @param mixed $with_totals_summary
	 * @param mixed $mode_transport
	 * @param mixed $shipping_text
	 * @return
	 */
	function get_caddie_products_summary_table($with_form_fields = false, $with_totals_summary = true, $mode_transport, $shipping_text = null)
	{
		$output = '';
		if (empty($shipping_text)) {
			$shipping_text = $GLOBALS['STR_SHIPPING_COST'];
		}
		if (display_prices_with_taxes_active()) {
			$total_remise_displayed = $_SESSION['session_caddie']->total_remise;
			$total_ecotaxe_displayed = $_SESSION['session_caddie']->total_ecotaxe_ttc;
			$small_order_overcost_displayed = $_SESSION['session_caddie']->small_order_overcost_amount;
			$cout_transport_displayed = $_SESSION['session_caddie']->cout_transport;
			$taxes_displayed = $GLOBALS['STR_TTC'];
		} else {
			$total_remise_displayed = $_SESSION['session_caddie']->total_remise_ht;
			$total_ecotaxe_displayed = $_SESSION['session_caddie']->total_ecotaxe_ht;
			$small_order_overcost_displayed = $_SESSION['session_caddie']->small_order_overcost_amount_ht;
			$cout_transport_displayed = $_SESSION['session_caddie']->cout_transport_ht;
			$taxes_displayed = $GLOBALS['STR_HT'];
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('caddie_products_summary_table.tpl');
		$tpl->assign('taxes_displayed', $taxes_displayed);
		$tpl->assign('suppression_src', $GLOBALS['repertoire_images'] . '/suppression.png');
		$tpl->assign('no_photo_src', $GLOBALS['repertoire_upload'] . '/' . $GLOBALS['site_parameters']['default_picture']);
		$tpl->assign('with_form_fields', $with_form_fields);
		$tpl->assign('is_conditionnement_module_active', is_conditionnement_module_active());
		$tpl->assign('is_attributes_module_active', is_attributes_module_active());
		$tpl->assign('tarif_paiement', fprix($_SESSION['session_caddie']->tarif_paiement, true));
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_TABLE_SUMMARY_CADDIE', $GLOBALS['STR_TABLE_SUMMARY_CADDIE']);
		$tpl->assign('STR_FRAIS_GESTION', $GLOBALS['STR_FRAIS_GESTION']);
		$tpl->assign('STR_PRODUCT', $GLOBALS['STR_PRODUCT']);
		$tpl->assign('STR_UNIT_PRICE', $GLOBALS['STR_UNIT_PRICE']);
		$tpl->assign('STR_OPTION_PRICE', $GLOBALS['STR_OPTION_PRICE']);
		$tpl->assign('STR_QUANTITY', $GLOBALS['STR_QUANTITY']);
		$tpl->assign('STR_REMISE', $GLOBALS['STR_REMISE']);
		$tpl->assign('STR_TOTAL_PRICE', $GLOBALS['STR_TOTAL_PRICE']);
		$tpl->assign('STR_DELETE_PROD_CART', $GLOBALS['STR_DELETE_PROD_CART']);
		$tpl->assign('STR_FOR_GIFT', $GLOBALS['STR_FOR_GIFT']);
		$tpl->assign('STR_DELIVERY_STOCK', $GLOBALS['STR_DELIVERY_STOCK']);
		$tpl->assign('STR_COLOR', $GLOBALS['STR_COLOR']);
		$tpl->assign('STR_SIZE', $GLOBALS['STR_SIZE']);
		$tpl->assign('STR_EMAIL_FRIEND', $GLOBALS['STR_EMAIL_FRIEND']);
		$tpl->assign('STR_HOLIDAY_AVAILABLE_CADDIE', $GLOBALS['STR_HOLIDAY_AVAILABLE_CADDIE']);
		$tpl->assign('STR_DAYS', $GLOBALS['STR_DAYS']);
		$tpl->assign('STR_ECOTAXE', $GLOBALS['STR_ECOTAXE']);
		$tpl->assign('STR_INCLUDED', $GLOBALS['STR_INCLUDED']);

		$products = array();
		foreach ($_SESSION['session_caddie']->articles as $numero_ligne => $product_id) {
			$product_object = new Product($product_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
			$product_object->set_configuration(vb($_SESSION['session_caddie']->couleurId[$numero_ligne]), vb($_SESSION['session_caddie']->tailleId[$numero_ligne]), vn($_SESSION['session_caddie']->id_attribut[$numero_ligne]), is_reseller_module_active() && is_reseller());
			if (!empty($product_object->id)) {
				// Récupération des variables du caddie
				$couleur = vb($_SESSION['session_caddie']->couleurId[$numero_ligne]);
				$taille = vb($_SESSION['session_caddie']->tailleId[$numero_ligne]);
				if (is_giftlist_module_active()) {
					$listcadeaux_owner = vn($_SESSION['session_caddie']->giftlist_owners[$numero_ligne]);
				}
				$quantite = vn($_SESSION['session_caddie']->quantite[$numero_ligne]);
				if (is_stock_advanced_module_active() && $product_object->on_stock == 1) {
					$stock_commandable = get_stock_commandable($product_object, $quantite);
				}
				$prix = vn($_SESSION['session_caddie']->prix[$numero_ligne]);
				$prix_ht = vn($_SESSION['session_caddie']->prix_ht[$numero_ligne]);
				$prix_cat = vn($_SESSION['session_caddie']->prix_cat[$numero_ligne]);
				$prix_cat_ht = vn($_SESSION['session_caddie']->prix_cat_ht[$numero_ligne]);
				$total_prix = vn($_SESSION['session_caddie']->total_prix[$numero_ligne]);
				$total_prix_ht = vn($_SESSION['session_caddie']->total_prix_ht[$numero_ligne]);
				$tva_percent = vn($_SESSION['session_caddie']->tva_percent[$numero_ligne]);
				$tva = vn($_SESSION['session_caddie']->tva[$numero_ligne]);
				$poids = vb($_SESSION['session_caddie']->poids[$numero_ligne]);
				$points = vb($_SESSION['session_caddie']->points[$numero_ligne]);
				$percent_remise_produit = vn($_SESSION['session_caddie']->percent_remise_produit[$numero_ligne]);
				$remise = vn($_SESSION['session_caddie']->remise[$numero_ligne]);
				$remise_ht = vn($_SESSION['session_caddie']->remise_ht[$numero_ligne]);
				if (is_stock_advanced_module_active()) {
					$etat_stock = vn($_SESSION['session_caddie']->etat_stock[$numero_ligne]);
					$delivery_stock = vb($_SESSION['session_caddie']->delai_stock[$numero_ligne]);
				}
				$email_check = vb($_SESSION['session_caddie']->email_check[$numero_ligne]);
				if (is_module_ecotaxe_active()) {
					$ecotaxe = vb($_SESSION['session_caddie']->ecotaxe_ttc[$numero_ligne]);
				}
				// $total_attribut = vn($_SESSION['session_caddie']->total_prix_attribut[$numero_ligne]);
				$urlprod_with_cid = $product_object->get_product_url(true, false) . "cId=" . $_SESSION['session_caddie']->couleurId[$numero_ligne];
				$display_picture = $product_object->get_product_main_picture(false, $_SESSION['session_caddie']->couleurId[$numero_ligne]);

				if (display_prices_with_taxes_active()) {
					$option = vn($_SESSION['session_caddie']->option[$numero_ligne]);
					$remise_displayed = $remise;
					// $total_attribut_displayed = $total_attribut;
					$prix_cat_displayed = $prix_cat;
					$prix_avant_code_promo_sans_option_displayed = $_SESSION['session_caddie']->prix_avant_code_promo[$numero_ligne] - $_SESSION['session_caddie']->option[$numero_ligne] * (1 - $_SESSION['session_caddie']->percent_remise_produit[$numero_ligne] / 100);
					$total_prix_displayed = $total_prix;
				} else {
					$option = vn($_SESSION['session_caddie']->option_ht[$numero_ligne]);
					$remise_displayed = $remise_ht;
					// $total_attribut_displayed = $total_attribut / (1 + $product_object->tva / 100);
					$prix_cat_displayed = $prix_cat_ht;
					$prix_avant_code_promo_sans_option_displayed = $_SESSION['session_caddie']->prix_ht_avant_code_promo[$numero_ligne] - $_SESSION['session_caddie']->option_ht[$numero_ligne] * (1 - $_SESSION['session_caddie']->percent_remise_produit[$numero_ligne] / 100);
					$total_prix_displayed = $total_prix_ht;
				}
				if (is_attributes_module_active() && !empty($product_object->configuration_attributs_description)) {
					$product_object->configuration_attributs_description = display_option_image($product_object->configuration_attributs_description, true);
				}
				$tmpProd = array(
					'delete_href' => $GLOBALS['wwwroot'] . '/achat/caddie_affichage.php?func=enleve&ligne=' . $numero_ligne . '&id=' . $product_object->id,
					'urlprod_with_cid' => $urlprod_with_cid,
					'numero_ligne' => $numero_ligne,
					'id' => $product_id,
					'listcadeaux_owner' => vb($listcadeaux_owner),
					'option' => $option,
					'id_attribut' => vb($_SESSION['session_caddie']->id_attribut[$numero_ligne]),
					'name' => $product_object->name,
					'configuration_attributs_description' => (!empty($product_object->configuration_attributs_list) ? $product_object->configuration_attributs_description : NULL),
					'email_check' => $email_check,
					'prix' => fprix($prix_cat_displayed, true)
					
				);
				if ($display_picture) {
					$tmpProd['src'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($display_picture, 75, 75, 'fit');
				} else {
					$tmpProd['src'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit');
				}
				if (!empty($listcadeaux_owner) && is_giftlist_module_active()) {
					$tmpProd['listcadeaux_owner_name'] = getUsername($listcadeaux_owner);
				}
				if (is_stock_advanced_module_active() && !empty($delivery_stock)) {
					$tmpProd['delivery_stock'] = get_formatted_duration((intval($delivery_stock) * 24 * 3600), false, 'month');
				}
				if (!empty($_SESSION['session_caddie']->couleurId[$numero_ligne])) {
					$tmpProd['color'] = array(
						'name' => get_color_name($couleur),
						'id' => intval($_SESSION['session_caddie']->couleurId[$numero_ligne])
					);
				}
				if (!empty($_SESSION['session_caddie']->tailleId[$numero_ligne])) {
					$tmpProd['size'] = array(
						'name' => get_size_name($taille),
						'id' => intval($_SESSION['session_caddie']->tailleId[$numero_ligne])
					);
				}
				if (is_module_vacances_active() && get_vacances_type() == 2) {
					//on récupère le fournisseur pour afficher sa date de retour
					$supplier_back = query("SELECT on_vacances, on_vacances_date
						FROM peel_utilisateurs
						WHERE id_utilisateur = " . $product_object->id_utilisateur . "");
					$res_supplier_back = fetch_assoc($supplier_back);
					$nbjours = get_vacances_jours($product_object->id_utilisateur);
					if ($nbjours) {
						$tmpProd['vacances'] = array(
							'nbjours' => $nbjours,
							'date' => get_formatted_date($res_supplier_back['on_vacances_date'])
						);
					}
				}
				if (round($prix_cat_displayed, 2) != round($prix_avant_code_promo_sans_option_displayed, 2)) {
					$tmpProd['prix_promo'] = fprix($prix_avant_code_promo_sans_option_displayed, true);
				}
				if (is_module_ecotaxe_active() && !empty($ecotaxe)) {
					$tmpProd['prix_ecotaxe'] = fprix($ecotaxe, true);
				}
				if ($option != 0) {
					$tmpProd['option_prix'] = fprix($option * (1 - $_SESSION['session_caddie']->percent_remise_produit[$numero_ligne] / 100), true);
					if (!empty($_SESSION['session_caddie']->percent_remise_produit[$numero_ligne]))
						$tmpProd['option_prix_remise'] = fprix($option, true);
				}
				if ($with_form_fields && $product_object->on_download == 0) {
					$tmpProd['quantite'] = array(
						'value' => $quantite,
					);
					// On prépare le message à afficher en javascript si la quantité demandée est trop élevée par rapport au stock disponible
					if (is_stock_advanced_module_active() && $product_object->on_stock == 1 && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
						$additionnal_quantity_possible = $stock_commandable - $quantite;
						$this_prepared_javascript_message = $GLOBALS['STR_QUANTITY_INSUFFICIENT'] . ' ';
						if ($additionnal_quantity_possible == 0) {
							// Aucun produit ajouté au caddie
							$this_prepared_javascript_message .= $GLOBALS['STR_ZERO_PRODUCT_ADD'];
						} elseif ($additionnal_quantity_possible == 1) {
							// un seul produit ajouté
							$this_prepared_javascript_message .= $additionnal_quantity_possible . ' ' . $GLOBALS['STR_QUANTITY_PRODUCT_ADD'];
						} else {
							// plus de un produit ajoutés au caddie
							$this_prepared_javascript_message .= $additionnal_quantity_possible . ' ' . $GLOBALS['STR_QUANTITY_PRODUCTS_ADD'];
						}
						$tmpProd['quantite']['message'] = $this_prepared_javascript_message;
						$tmpProd['quantite']['stock_commandable'] = $stock_commandable;
					}
				} else {
					$tmpProd['quantite'] = $quantite;
				}
				if(is_conditionnement_module_active ()) {
					if(!empty($product_object->conditionnement)){
						$tmpProd['conditionnement_qty'] = intval($quantite) * intval($product_object->conditionnement);
					}else{
						$tmpProd['conditionnement_qty'] = intval($quantite);
					}
					$tmpProd['conditionnement'] = (!empty($product_object->conditionnement)?$product_object->conditionnement:'-');
				}
				if($remise_displayed > 0) {
					$tmpProd['remise'] = fprix($remise_displayed, true);
				}
				$tmpProd['total_prix'] = fprix($total_prix_displayed, true);
				$products[] = $tmpProd;
			}
			unset($product_object);
		}
		$tpl->assign('products', $products);
		$tpl->assign('with_totals_summary', $with_totals_summary);
		$tpl->assign('STR_WITH_PROMO_CODE', $GLOBALS['STR_WITH_PROMO_CODE']);
		$tpl->assign('STR_ON_CATEGORY', $GLOBALS['STR_ON_CATEGORY']);
		$tpl->assign('STR_SMALL_ORDER_OVERCOST_TEXT', $GLOBALS['STR_SMALL_ORDER_OVERCOST_TEXT']);
		$tpl->assign('STR_OFFERED', $GLOBALS['STR_OFFERED']);
		$tpl->assign('STR_FROM', $GLOBALS['STR_FROM']);
		$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
		$tpl->assign('STR_TOTAL_HT', $GLOBALS['STR_TOTAL_HT']);
		$tpl->assign('STR_VAT', $GLOBALS['STR_VAT']);
		$tpl->assign('STR_NO_VAT_APPLIABLE', $GLOBALS['STR_NO_VAT_APPLIABLE']);
		$tpl->assign('STR_AVOIR', $GLOBALS['STR_AVOIR']);
		$tpl->assign('net_txt', $GLOBALS['STR_NET']);
		$tpl->assign('STR_ORDER_POINT', $GLOBALS['STR_ORDER_POINT']);
		$tpl->assign('STR_GIFT_POINTS', $GLOBALS['STR_GIFT_POINTS']);
		
		if ($with_totals_summary) {
			if (is_module_ecotaxe_active() && !empty($_SESSION['session_caddie']->total_ecotaxe_ttc)) {
				$tpl->assign('total_ecotaxe', fprix($total_ecotaxe_displayed, true));
			}
			// - Si la session client contient une remise > 0
			if (!empty($_SESSION['session_caddie']->total_remise)) {
				$tpl->assign('total_remise', fprix($total_remise_displayed, true));
				if (!empty($_SESSION['session_caddie']->percent_code_promo) || !empty($_SESSION['session_caddie']->valeur_code_promo)) {
					$tpl->assign('code_promo', array(
						'value' => $_SESSION['session_caddie']->code_promo,
						'total' => (!empty($_SESSION['session_caddie']->percent_code_promo) ? fprix($_SESSION['session_caddie']->total_reduction_percent_code_promo, true) : fprix($_SESSION['session_caddie']->valeur_code_promo, true)),
						'cat_name' => (!empty($_SESSION['session_caddie']->cat_code_promo) ? get_category_name($_SESSION['session_caddie']->cat_code_promo) : FALSE)
					));
				}
			}
			// Attention : un test !empty ne marche pas sur $GLOBALS['site_parameters']['small_order_overcost_limit'] car au format "0.00"
			if ($GLOBALS['site_parameters']['small_order_overcost_limit'] != 0 && $_SESSION['session_caddie']->total >= $GLOBALS['site_parameters']['minimal_amount_to_order']) {
				$tpl->assign('sool', array(
					'prix' => fprix($small_order_overcost_displayed, true),
					'limit_prix' => fprix($GLOBALS['site_parameters']['small_order_overcost_limit'], true)
				));
			}
			if ($mode_transport != 0) {
				$tpl->assign('transport', array(
					'prix' => fprix($cout_transport_displayed, true),
					'shipping_text' => $shipping_text
				));
			}
			// if ($_SESSION['session_caddie']->total > 0) {
			if (!is_micro_entreprise_module_active()) {
				$tpl->assign('micro', array(
					'prix_th' => fprix($_SESSION['session_caddie']->total_ht, true),
					'prix_tva' => fprix($_SESSION['session_caddie']->total_tva, true)
				));
			}
			if (!empty($_SESSION['session_caddie']->avoir)) {
				$tpl->assign('prix_avoir', fprix($_SESSION['session_caddie']->avoir, true));
			}
			$tpl->assign('prix_total', fprix($_SESSION['session_caddie']->total, true));
			$tpl->assign('total_points', $_SESSION['session_caddie']->total_points);
		}
		$output = $tpl->fetch();
		return $output;
	}
}

?>