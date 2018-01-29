<?php
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
// $Id: produits.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);

include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_PRODUITS_TITLE'];
define('ON_PRODUCT_PAGE', true);

$id = intval(vn($_REQUEST['id']));
$form_error_object = new FormError();
$frm = $_POST;
$output = '';

switch (vb($_REQUEST['mode'])) {
	case 'modif_tab';
		$lng = $_GET['tab_lang'];
		$output .= affiche_formulaire_modif_tab($id, $lng);
		break;

	case 'maj_tab';
		$lng = $_GET['tab_lang'];
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_tab($frm);
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_PRODUITS_MSG_UPDATE_OK']))->fetch();
			$frm = array();
			$output .= affiche_formulaire_modif_produit(vn($_REQUEST['id']), $frm);
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			}
			$output .= affiche_formulaire_modif_tab($id, $lng);
		}
		break;

	case "ajout" :
		$output .= affiche_formulaire_ajout_produit(vn($_REQUEST['categories']), $frm, $form_error_object);
		break;

	case "stock" :
		if (check_if_module_active('stock_advanced')) {
			$output .= affiche_formulaire_stock(intval($_GET['id']));
		} else {
			$output .= affiche_liste_produits($_GET);
		}
		break;

	case "commande" :
		$output .= affiche_liste_produits_acommander();
		break;

	case "stocknul" :
		if (check_if_module_active('stock_advanced')) {
			$output .= affiche_liste_produits_stocknul($_POST);
		} else {
			$output .= affiche_liste_produits($_GET);
		}
		break;

	case "InsereStock" :
		if (check_if_module_active('stock_advanced')) {
			$output .= insere_stock_produit($_POST);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PRODUITS_MSG_STOCKS_UPDATED'], get_current_url(false))))->fetch();
			$output .= affiche_formulaire_stock(intval($_GET['id']));
		}
		break;

	case "modif" :
		$output .= affiche_formulaire_modif_produit(vn($_REQUEST['id']), $frm);
		break;

	case "client" :
		$output .= affiche_formulaire_ajout_produit(vn($_REQUEST['id']), $frm, $form_error_object);
		break;

	case "suppr" :
		$output .= supprime_produit(vn($_REQUEST['id']));
		$output .= affiche_liste_produits($_POST);
		break;

	case "delete_results" :
		// Pour gagner du temps de manière importante sur des sites ayants beaucoup de produits sans images, on efface en deux étapes :
		// Etape 1 : suppression des produits avec images (lent, 1 par 1)
		$sql_select_only_with_images = get_admin_products_search_sql($_GET, false, true);
		$query = query($sql_select_only_with_images);
		while($result = fetch_assoc($query)) {
			supprime_produit($result['id']);
		}
		// Etape 2 : suppression des produits sans images en une seule fois, avec un gros DELETE contenant des jointures
		$sql_delete = get_admin_products_search_sql($_GET, true, false);
		query($sql_delete);
		if (affected_rows()) {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PRODUITS_MSG_DELETED_OK'], $GLOBALS['STR_ALL'])))->fetch();
		}
		$output .= affiche_liste_produits(array());
		break;

	case "supprfile" :
		if (!empty($_GET['coul']) && !empty($_GET['file'])) {
			$output .= supprime_fichier_couleur(vn($_REQUEST['id']), $_GET['file'], $_GET['coul']);
		} else {
			$output .= supprime_fichier_produit(vn($_REQUEST['id']), $_GET['file']);
		}
		$output .= affiche_formulaire_modif_produit(vn($_REQUEST['id']), $frm);
		break;

	case "insere" :
		if (!empty($frm)) {
			if (empty($frm['on_check']) && empty($GLOBALS['site_parameters']['allow_products_without_category'])) {
				// les chèques cadeaux ne sont pas associés à une catégorie. Ils s'affichent sur la page /modules/gift_check/cheques.php
				$check_fields_array = array('categories' => $GLOBALS['STR_ADMIN_PRODUITS_ERR_EMPTY_CATEGORY']);
			}
			if(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])) {
				if(in_array($GLOBALS['site_parameters']['product_name_forced_lang'], $GLOBALS['admin_lang_codes'])) {
					$check_fields_array['nom_' . $GLOBALS['site_parameters']['product_name_forced_lang']] = $GLOBALS['STR_ADMIN_PRODUITS_ERR_EMPTY_NAME'];
				}
			} else {
				$check_fields_array['nom_' . $_SESSION['session_langue']] = $GLOBALS['STR_ADMIN_PRODUITS_ERR_EMPTY_NAME'];
			}
			$form_error_object->valide_form($frm, $check_fields_array);
			if (!verify_token($_SERVER['PHP_SELF'] . vb($frm['mode']) . vb($frm['id']))) {
				$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
			}
		}
		if (!$form_error_object->count()) {
			if(!empty($GLOBALS['site_parameters']['products_table_additionnal_fields'])) {
					foreach(array_keys($GLOBALS['site_parameters']['products_table_additionnal_fields']) as $this_key) {
					if (strpos($this_key, 'image') === 0) {
						$frm[$this_key] = upload($this_key, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm[$this_key]));
					}
				}
			}
			foreach (array('image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8', 'image9', 'image10') as $this_image) {
				$frm[$this_image] = upload($this_image, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm[$this_image]));
			}
			$output .= insere_produit($frm);
			if (!empty($GLOBALS['site_parameters']['product_redirect_to_edit_page_after_create']) && !empty($GLOBALS['last_insert_product_id'])) {
				redirect_and_die($GLOBALS['administrer_url'] . '/produits.php?mode=modif&id=' . $GLOBALS['last_insert_product_id']);
			} else {
				$output .= affiche_liste_produits(array());
			}
		} else {
			if ($form_error_object->has_error('token')) {
				$output .=  $form_error_object->text('token');
			} else {
				$output .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
			if (!isset($frm['categories'])) {
				$categorie_id = 0;
			} else {
				$categorie_id = $frm['categories'];
			}
			$output .= affiche_formulaire_controle_produit($categorie_id, $frm, $form_error_object);
		}
		break;

	case "maj" :
		if (!empty($frm)) {
			if (empty($frm['on_check']) && empty($GLOBALS['site_parameters']['allow_products_without_category'])) {
				// les chèques cadeaux ne sont pas associés à une catégorie. Ils s'affichent sur la page /modules/gift_check/cheques.php
				$check_fields_array = array('categories' => $GLOBALS['STR_ADMIN_PRODUITS_ERR_EMPTY_CATEGORY']);
			}
			if(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])) {
				if(in_array($GLOBALS['site_parameters']['product_name_forced_lang'], $GLOBALS['admin_lang_codes'])) {
					$check_fields_array['nom_' . $GLOBALS['site_parameters']['product_name_forced_lang']] = $GLOBALS['STR_ADMIN_PRODUITS_ERR_EMPTY_NAME'];
				}
			} else {
				$check_fields_array['nom_' . $_SESSION['session_langue']] = $GLOBALS['STR_ADMIN_PRODUITS_ERR_EMPTY_NAME'];
			}
			$form_error_object->valide_form($frm, $check_fields_array);
		}
		if (!$form_error_object->count()) {
			foreach (array('image1', 'image2', 'image3', 'image4', 'image5', 'image6', 'image7', 'image8', 'image9', 'image10') as $this_image) {
				$frm[$this_image] = upload($this_image, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm[$this_image]));
			}
			if(!empty($GLOBALS['site_parameters']['products_table_additionnal_fields'])) {
				foreach(array_keys($GLOBALS['site_parameters']['products_table_additionnal_fields']) as $this_key) {
				if (strpos($this_key, 'image') === 0) {
						$frm[$this_key] = upload($this_key, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm[$this_key]));
					}
				}
			}
			$output .= maj_produit($frm['id'], $frm);
			$output .= affiche_liste_produits($frm);
		}

		if ($form_error_object->count()) {
			$output .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			$output .= affiche_formulaire_modif_produit($frm['id'], $frm);
		}

		break;

	case "supplier" :
		$output .= affiche_liste_produits_fournisseur();
		break;

	case "duplicate" :
		if (check_if_module_active('duplicate') && isset($_GET['id'])) {
			duplicate_product(intval($_GET['id']));
		}
		$output .= affiche_liste_produits($_GET);
		break;

	default :
		$output .= affiche_liste_produits($_GET);
		break;
}
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un produit
 *
 * @param integer $categorie_id
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_ajout_produit($categorie_id = 0, &$frm, &$form_error_object)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
			$frm['descriptif_' . $lng] = "";
			$frm['description_' . $lng] = "";
			// gestion des meta
			$frm['meta_titre_' . $lng] = "";
			$frm['meta_key_' . $lng] = "";
			$frm['meta_desc_' . $lng] = "";
		}
		$frm['reference'] = "";
		$frm['reference_fournisseur'] = "";
		$frm['ean_code'] = "";
		$frm['etat_stock'] = "";
		$frm['affiche_stock'] = "";
		$frm['allow_add_product_with_no_stock_in_cart'] = "";
		$frm['delai_stock'] = "";
		$frm['on_stock'] = "";
		$frm['on_rupture'] = "";
		$frm['on_check'] = "";
		$frm['on_top'] = "";
		$frm['on_special'] = "";
		$frm['on_new'] = "";
		$frm['on_promo'] = "";
		$frm['on_reseller'] = "";
		$frm['on_rollover'] = "";
		$frm['on_stock'] = "";
		$frm['on_download'] = "";
		$frm['extra_link'] = "";
		$frm['technical_code'] = "";
		$frm['on_download'] = "";
		$frm['zip'] = "";
		$frm['prix'] = 0;
		$frm['prix_flash'] = 0;
		$frm['prix_promo'] = 0;
		$frm['prix_achat'] = 0;
		$frm['prix_revendeur'] = 0;
		$frm['default_image'] = "";
		$frm['image1'] = "";
		$frm['image2'] = "";
		$frm['image3'] = "";
		$frm['image4'] = "";
		$frm['image5'] = "";
		$frm['image6'] = "";
		$frm['image7'] = "";
		$frm['image8'] = "";
		$frm['image9'] = "";
		$frm['image10'] = "";
		$frm['youtube_code'] = "";
		$frm['tva'] = "";
		$frm['poids'] = "0.00";
		$frm['on_perso'] = "";
		$frm['promotion'] = "0.00";
		$frm['volume'] = "0.00";
		$frm['etat'] = "";
		$frm['points'] = 0;
		$frm['site_id'] = '';
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$frm['site_country'] =  $GLOBALS['site_parameters']['site_country_allowed_array'];
		}
		/* gestion des ventes flash */
		if (check_if_module_active('flash')) {
			$frm['on_flash'] = "";
			$frm['flash_start'] = "";
			$frm['flash_end'] = "";
		}
	}
	if (is_array($categorie_id)) {
		$frm['categories'] = $categorie_id;
	} else {
		$frm['categories'] = array($categorie_id);
	}
	$frm['references'] = array();
	$frm['reference_fournisseur'] = array();
	$frm['couleurs'] = array();
	$frm['tailles'] = array();
	if (check_if_module_active('payment_by_product')) {
		$frm['paiment_allowed'] = array();
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = '';
	$frm['date_insere'] = "";
	$frm['date_maj'] = "";
	$frm['id_marque'] = "";
	$frm['id_utilisateur'] = "";
	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_PRODUITS_ADD_PRODUCT'];

	return affiche_formulaire_produit($frm, $form_error_object, true);
}

/**
 * Affiche un formulaire vierge pour ajouter un produit
 *
 * @param integer $categorie_id
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_controle_produit($categorie_id = 0, &$frm, &$form_error_object)
{
	/* Valeurs par défault */
	if (is_array($categorie_id)) {
		$frm['categories'] = $categorie_id;
	} else {
		$frm['categories'] = array($categorie_id);
	}
	$frm['nouveau_mode'] = "insere";
	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_PRODUITS_ADD_PRODUCT'];

	// La création du produit n'est pas faite, puisque le formulaire est invalide.
	return affiche_formulaire_produit($frm, $form_error_object, true);
}

/**
 * Affiche le formulaire de modification pour le produit sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_produit($id, &$frm)
{
	$form_error_object = new FormError();
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_produits
			WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('produits', null, true) . "");
		if ($frm = fetch_assoc($qid)) {
			if (check_if_module_active('stock_advanced')) {
				// On nettoie la table des stocks
				nettoie_stocks($id);
			}
			if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$frm['site_country'] = explode(',', vb($frm['site_country']));
			}

			// On adapte les données récupérées en BDD pour les formulaires pour afficher en HT ou TTC comme on veut
			// Ensuite on manipulera ces données même si N rechargement de formulaire sans les remodifier, et enfin on les renormalisera dans l'autre sens lors de l'insert ou la mise à jour en BDD.
			if (!display_prices_with_taxes_in_admin ()) {
				$frm['prix'] = $frm['prix'] / (1 + $frm['tva'] / 100);
				if (check_if_module_active('flash')) {
					$frm['prix_flash'] = vn($frm['prix_flash']) / (1 + $frm['tva'] / 100);
				} else {
					$frm['prix_flash'] = 0;
				}
			}
			if (check_if_module_active('reseller') && (!display_prices_with_taxes_in_admin () || !empty($GLOBALS['site_parameters']['force_display_reseller_prices_without_taxes']))) {
				$frm['prix_revendeur'] = round(vn($frm['prix_revendeur']) / (1 + $frm['tva'] / 100), 2); // C'est le prix HT qui fait foi, pas le prix TTC pour le prix revendeur => on arrondit au centime le prix revendeur HT
			} else {
				$frm['prix_revendeur'] = 0;
			}
			// L'affichage du prix d'achat se fait toujours en HT, indépendemment de la configuration de l'affichage HT/TTC de la boutique.
			$frm['prix_achat'] = round(vn($frm['prix_achat']) / (1 + $frm['tva'] / 100), 2);
		} else {
			return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_PRODUITS_ERR_NOT_FOUND']))->fetch();
		}
	}
	/* Charge les catégories du produit */
	$qid = query("SELECT categorie_id, nom_" . $_SESSION['session_langue'] . " AS nom_categorie
		FROM peel_produits_categories pp
		INNER JOIN peel_categories pc ON pp.categorie_id=pc.id AND " . get_filter_site_cond('categories', 'pc') . "
		WHERE produit_id = '" . intval($id) . "'");
	$frm['categories'] = array();
	while ($cat = fetch_assoc($qid)) {
		$frm['categories'][] = $cat['categorie_id'];
		$GLOBALS['categorie_names_by_id'][$cat['categorie_id']] = $cat['nom_categorie'];
	}
	/* Charge les références du produit */
	$references = query("SELECT reference_id
		FROM peel_produits_references
		WHERE produit_id = '" . intval($id) . "'");
	$frm['references'] = array();
	$frm['nb_produits'] = 0;
	while ($ref = fetch_assoc($references)) {
		$frm['nb_produits']++;
		$frm['references'][] = $ref['reference_id'];
	}
	/* Charge les couleurs du produit */
	$couleurs = query("SELECT couleur_id
		FROM peel_produits_couleurs
		WHERE produit_id = '" . intval($id) . "'");
	$frm['couleurs'] = array();
	while ($couleur = fetch_assoc($couleurs)) {
		$frm["couleurs"][] = $couleur['couleur_id'];
	}
	/* Charge les tailles du produit */
	$tailles = query("SELECT taille_id
		FROM peel_produits_tailles
		WHERE produit_id = '" . intval($id) . "'");
	$frm['tailles'] = array();
	while ($taille = fetch_assoc($tailles)) {
		$frm["tailles"][] = $taille['taille_id'];
	}
	if (check_if_module_active('payment_by_product')) {
		$frm['paiment_allowed'] = select_payment_for_this_product($id);
	}
	$frm['nouveau_mode'] = "maj";
	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	return affiche_formulaire_produit($frm, $form_error_object, false);
}

/**
 * affiche_formulaire_produit()
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @param boolean $create_product_process Cette variable définie si la fonction affiche_formulaire_produit est utilisée lors de la création d'un produit ou pour une modification de produit.
 * @return
 */
function affiche_formulaire_produit(&$frm, &$form_error_object, $create_product_process = false)
{
	$output = '';
	$GLOBALS['load_timepicker']=true;
	if (empty($frm['default_image'])) {
		$frm['default_image'] = 1;
	}
	if (empty($frm['nb_produits'])) {
		$frm['nb_produits'] = 5;
	}
	$all_sites_name_array = get_all_sites_name_array(true);
	if (isset($_SESSION['session_admin_multisite']) && $_SESSION['session_admin_multisite'] === 0) {
		// L'administrateur multisite peut voir des informations qui s'applique à tous les sites. Donc cette mention doit être retournée dans le tableau.
		$all_sites_name_array[0] = $GLOBALS['STR_ADMIN_ALL_SITES'];
	}
	// Les prix ci-dessous sont homogènes dans tous les cas, et doivent juste être reformatés. Ils sont :
	// - soit chargés de la base de données et convertis en HT/TTC suivant la configuration adaptée
	// - soit viennent du formulaire déjà validé mais qui avait des erreurs, et n'ont pas être remodifiés
	$prix = fprix(get_float_from_user_input($frm['prix']), false, $GLOBALS['site_parameters']['code'], false, null, false, false);
	$prix_flash = fprix(get_float_from_user_input(vb($frm['prix_flash'])), false, $GLOBALS['site_parameters']['code'], false, null, false, false);
	$prix_revendeur = fprix(get_float_from_user_input(vb($frm['prix_revendeur'])), false, $GLOBALS['site_parameters']['code'], false, null, false, false);
	$prix_achat = fprix(get_float_from_user_input($frm['prix_achat']), false, $GLOBALS['site_parameters']['code'], false, null, false, false);
	$prix_promo = fprix(get_float_from_user_input($frm['prix_promo']), false, $GLOBALS['site_parameters']['code'], false, null, false, false);
	// Si aucune référence n'est choisie on initialise le tableau des références.
	if (!isset($frm['references'])) {
		$frm['references'] = array();
	}
	if (!isset($frm['reference_fournisseur'])) {
		$frm['reference_fournisseur'] = array();
	}
	// Si aucune couleur n'est choisie on initialise le tableau des couleurs
	if (!isset($frm['couleurs'])) {
		$frm['couleurs'] = array();
	}
	$categorie_options = get_categories_output(null, 'categories',  vb($frm['categories']), 'option', '&nbsp;&nbsp;', null, null, true, 80);
	if (empty($categorie_options) && (empty($frm['id']) || vb($_GET['mode'])!='modif')) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_produit_table.tpl');
		$tpl->assign('href', $GLOBALS['administrer_url'] . '/categories.php?mode=ajout');
		$tpl->assign('STR_ADMIN_PRODUITS_ADD', $GLOBALS['STR_ADMIN_PRODUITS_ADD']);
		$tpl->assign('STR_ADMIN_PRODUITS_CREATE_CATEGORY_FIRST', $GLOBALS['STR_ADMIN_PRODUITS_CREATE_CATEGORY_FIRST']);
		$output .= $tpl->fetch();
	} else {
		// On n'affiche pas le lien vers le produit, car il existera lors de la validation du formulaire ci-dessous
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_produit.tpl');
		$tpl->assign('action', get_current_url(false) . '?page=' . (!empty($_GET['page']) ? $_GET['page'] : 1));
		$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vb($frm['id']))));
		$tpl->assign('mode', vb($frm['nouveau_mode']));
		$tpl->assign('id', intval(vb($frm['id'])));
		$tpl->assign('reseller_price_taxes_txt', (display_prices_with_taxes_in_admin() && empty($GLOBALS['site_parameters']['force_display_reseller_prices_without_taxes'])? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
		$tpl->assign('get_mode', vb($_GET['mode']));
		$tpl->assign('create_product_process', $create_product_process);
		$tpl->assign('allow_products_without_category', vb($GLOBALS['site_parameters']['allow_products_without_category']));
		if (!$create_product_process) {
			$tpl->assign('nom', vn($frm['nom_' . $_SESSION['session_langue']]));
			if (!empty($frm['categories'])) {
				$prod_href = get_product_url($frm['id'], $frm['nom_' . $_SESSION['session_langue']], $frm['categories'][0], $GLOBALS['categorie_names_by_id'][$frm['categories'][0]], false, false, vb($frm['site_id']));
			} else {
				// Le lien du produit est généré sans categorie. Le produit sera accessible depuis ce lien.
				$prod_href = get_product_url($frm['id'], $frm['nom_' . $_SESSION['session_langue']], 0, null, false, false, $frm['site_id']);
			}
			$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_PRODUITS_UPDATE'] . ' "' .  vb($frm['nom_' . $_SESSION['session_langue']]) . '" - <a href="' . $prod_href . '" onclick="return(window.open(this.href)?false:true);">' . $GLOBALS['STR_ADMIN_SEE_RESULT_IN_REAL'] . '</a>';
			if(empty($GLOBALS['site_parameters']['avoid_increment_products_nb_view'])) {
				$sql_nb_view = query("SELECT nb_view
					FROM peel_produits
					WHERE id = " . intval($frm['id']) . " AND " . get_filter_site_cond('produits', null, true) . "");
				$prod = fetch_assoc($sql_nb_view);
				$GLOBALS['DOC_TITLE'] .= ' - ' . $GLOBALS['STR_ADMIN_PRODUITS_VIEWS_COUNT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $prod['nb_view'];
			}
			$tpl->assign('nb_view', vn($prod['nb_view']));
			$tpl->assign('prod_href', $prod_href);
		} else {
			$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_PRODUITS_ADD'];		
		}
		$tpl->assign('categorie_error', $form_error_object->text('categories'));
		$tpl->assign('categories_suggest_mode', vb($GLOBALS['site_parameters']['categories_suggest_mode']));
		if(!empty($GLOBALS['site_parameters']['categories_suggest_mode'])) {
			$i = 0;
			$tpl_categories_options = array();
			foreach(vb($frm['categories'], array()) as $this_id) {
				$tpl_categories_options[] = array('value' => intval($this_id),
					'name' => get_category_name($this_id, 10),
					'i' => $i,
				);
				$i++;
			}
			$tpl->assign('categorie_options', $tpl_categories_options);
			$tpl->assign('nb_categories', $i);
		} else {
			$tpl->assign('categorie_options', $categorie_options);
			$tpl->assign('categorie_error', $form_error_object->text('categories'));
		}
		$tpl->assign('position', vn($frm['position']));

		$tpl->assign('module_departement_active', check_if_module_active('departements'));
		$tpl->assign('is_module_gift_checks_active', check_if_module_active('gift_check'));
		$tpl->assign('is_on_check', !empty($frm['on_check']));

		$tpl->assign('skip_home_special_products', !empty($GLOBALS['site_parameters']['skip_home_special_products']));
		$tpl->assign('is_on_special', !empty($frm['on_special']));
		$tpl->assign('is_on_new', !empty($frm['on_new']));
		
		$tpl->assign('site_auto_promo', $GLOBALS['site_parameters']['auto_promo']);
		$tpl->assign('is_on_promo', !empty($frm['on_promo']));
		$tpl->assign('is_on_reseller', !empty($frm['on_reseller']));
		$tpl->assign('extra_link', vb($frm['extra_link']));
		$tpl->assign('technical_code', vb($frm['technical_code']));

		$tpl->assign('is_best_seller_module_active', check_if_module_active('best_seller'));
		$tpl->assign('is_on_top', !empty($frm['on_top']));

		$tpl->assign('is_conditionnement_module_active', check_if_module_active('conditionnement'));
		if (check_if_module_active('product_references_by_options')&&(empty($GLOBALS['site_parameters']['product_references_by_color_and_size_disabled']))) {
			$tpl->assign('product_multiple_references_form', product_multiple_references_form($frm['id']));
			$tpl->assign('STR_ADMIN_PRODUCT_MULTIPLE_REFERENCE_FORM', $GLOBALS['STR_ADMIN_PRODUCT_MULTIPLE_REFERENCE_FORM']);
		}
		$tpl->assign('conditionnement', vb($frm['conditionnement']));
		$tpl->assign('conditioning_text', vb($frm['conditioning_text']));
		$tpl->assign('unit_per_pallet', vb($frm['unit_per_pallet']));
	
		$tpl->assign('is_rollover_module_active', check_if_module_active('menus'));
		$tpl->assign('is_on_rollover', !empty($frm['on_rollover']));

		$tpl->assign('is_on_estimate', !empty($frm['on_estimate']));
		$tpl->assign('display_recommanded_product_on_cart_page', !empty($GLOBALS['site_parameters']['display_recommanded_product_on_cart_page']));
		$tpl->assign('is_recommanded_product_on_cart_page', !empty($frm['recommanded_product_on_cart_page']));
		$tpl->assign('etat', vb($frm['etat']));
		$tpl->assign('reference', vb($frm['reference']));
		$tpl->assign('reference_fournisseur', vb($frm['reference_fournisseur']));
		$tpl->assign('ean_code', vb($frm['ean_code']));

		$tpl->assign('is_id', !empty($frm['id']));
		$tpl_lang_names = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$tpl_lang_names[] = array('lng' => $lng,
				'nom' => vb($frm['nom_' . $lng]),
				'nom_error' => $form_error_object->text('nom_' . $lng),
				'modif_tab_href' => $GLOBALS['administrer_url'] . '/produits.php?mode=modif_tab&id=' . $frm['id'] . '&tab_lang=' . $lng,
				'descriptif' => vb($frm['descriptif_' . $lng]),
				'description_te' => getTextEditor('description_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['description_' . $lng]))),
				'meta_titre' => vb($frm['meta_titre_' . $lng]),
				'meta_key' => vb($frm['meta_key_' . $lng]),
				'meta_desc' => vb($frm['meta_desc_' . $lng])
				);
		}
		$tpl->assign('langs', $tpl_lang_names);
		$tpl->assign('product_name_forced_lang', vb($GLOBALS['site_parameters']['product_name_forced_lang']));
		$tpl->assign('product_description_forced_lang', vb($GLOBALS['site_parameters']['product_description_forced_lang']));
		$tpl->assign('associated_product_multiple_add_to_cart', vb($GLOBALS['site_parameters']['associated_product_multiple_add_to_cart']));

		$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
		$tpl->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
		$tpl->assign('prix', vn($prix));

		$tpl->assign('prix_promo', vn($prix_promo));
		$tpl->assign('is_reseller_module_active', check_if_module_active('reseller'));
		$tpl->assign('prix_revendeur', vn($prix_revendeur));

		$tpl->assign('prix_achat', vn($prix_achat));
		$tpl->assign('vat_select_options', get_vat_select_options(vb($frm['tva'])));

		$tpl->assign('is_module_ecotaxe_active', check_if_module_active('ecotaxe'));
		$tpl_ecotaxe_options = array();
		if (check_if_module_active('ecotaxe')) {
			$sql = "SELECT id, code, nom_" . $_SESSION['session_langue'] . " AS nom, prix_ttc, coefficient
				FROM peel_ecotaxes
				WHERE " . get_filter_site_cond('ecotaxes') . "
				ORDER BY code";
			$query = query($sql);
			while ($e = fetch_assoc($query)) {
				$tpl_ecotaxe_options[] = array('value' => intval($e['id']),
					'issel' => $e['id'] == vb($frm['id_ecotaxe']),
					'code' => $e['code'],
					'nom' => $e['nom'],
					'prix' => ($e['coefficient']>0?$e['coefficient']:fprix($e['prix_ttc'], true, $GLOBALS['site_parameters']['code'], false) . ' ' . $GLOBALS['STR_TTC']) 
					);
			}
		}
		$tpl->assign('ecotaxe_options', $tpl_ecotaxe_options);

		if (check_if_module_active('payment_by_product')) {
			$tpl->assign('payment_by_product', display_payment_by_product($frm['paiment_allowed']));
		}

		$tpl->assign('promotion', vb($frm['promotion']));

		$tpl->assign('is_gifts_module_active', check_if_module_active('gifts'));
		$tpl->assign('points', vn($frm['points']));
		$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
		$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$tpl->assign('site_country_checkboxes', get_site_country_checkboxes(vb($frm['site_country'], array())));
			$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
		}
		
		$tpl->assign('poids', vb($frm['poids']));
		$tpl->assign('volume', vb($frm['volume']));
		$tpl->assign('display_price_by_weight', vn($frm['display_price_by_weight']));

		$tpl->assign('is_lot_module_active', check_if_module_active('lot'));
		if (check_if_module_active('lot')) {
			if (vb($frm['nouveau_mode']) == "maj") {
				if (check_if_module_active('departements')) {
					$query = query("SELECT * FROM peel_zones");
					$lots = array();
					while($zone = fetch_assoc($query)) {
						$tmplot = array();
						$tmplot['lot_explanation_table'] = get_lot_explanation_table($frm['id'], null, $zone['id']);
						$tmplot['lot_href'] = $GLOBALS['wwwroot_in_admin'] . '/modules/lot/administrer/lot.php?id=' . vb($frm['id']) . '&zone_id=' . $zone['id'];
						$tmplot['zone_name'] = $zone['nom_' . $_SESSION['session_langue']];
						if (num_rows(query("SELECT 1 FROM peel_quantites WHERE produit_id='" . intval($frm['id']) . "' AND zone_id='" . intval($zone['id']) . "' AND " . get_filter_site_cond('quantites'))) > 0) {
							$tmplot['lot_supprime_href'] = $GLOBALS['wwwroot_in_admin'] . '/modules/lot/administrer/lot.php?id=' . vb($frm['id']) . '&zone_id=' . $zone['id'].'&mode=supprime';
						}
						$lots[] = $tmplot;
					}
					$tpl->assign('lots', $lots);
				} else {
					$tpl->assign('lot_explanation_table', get_lot_explanation_table($frm['id']));
					$tpl->assign('lot_href', $GLOBALS['wwwroot_in_admin'] . '/modules/lot/administrer/lot.php?id=' . vb($frm['id']));
					if (num_rows(query("SELECT 1 FROM peel_quantites WHERE produit_id='" . intval($frm['id']) . "' AND " . get_filter_site_cond('quantites'))) > 0) {
						$tpl->assign('lot_supprime_href', $GLOBALS['wwwroot_in_admin'] . '/modules/lot/administrer/lot.php?id=' . vb($frm['id']) . '&mode=supprime');
					}
				}
			}
		}

		$tpl->assign('default_image', vb($frm['default_image']));

		$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		$tpl_files = array();
		for ($i = 1; $i <= 10; $i++) {
			$tpl_files[$i] = get_uploaded_file_infos('image' . $i, vb($frm['image' . $i]), get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image' . $i);
		}
		
		if(!empty($GLOBALS['site_parameters']['products_table_additionnal_fields'])) {
			foreach($GLOBALS['site_parameters']['products_table_additionnal_fields'] as $this_key => $this_value) {
				if (strpos($this_key, 'image') === 0) {
					$tpl_files[$i] = get_uploaded_file_infos($this_key, vb($frm[$this_key]), get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=' . $this_key);
					$i++;
				}
			}
		}
		$tpl->assign('files', $tpl_files);

		$tpl_colors = array();
		/**
		 * ******************************* Gestion des images des couleurs ***************************************
		 */
		$selectCouleur = "SELECT c.*, pc.default_image, pc.image1, pc.image2, pc.image3, pc.image4, pc.image5, pc.couleur_id as coul
			FROM peel_couleurs c
			INNER JOIN peel_produits_couleurs pc ON pc.couleur_id = c.id AND pc.produit_id = '" . intval(vb($frm['id'])) . "'
			WHERE " .  get_filter_site_cond('couleurs', 'c') . "
			ORDER BY c.position ASC, c.nom_" . $_SESSION['session_langue'] . " ASC";
		$query = query($selectCouleur);
		// Compteur permettant de fournir l'image par défaut en fonction de chaque couleur
		$nomCouleur_array = array();
		while ($nomCouleur = fetch_assoc($query)) {
			$nomCouleur_array[] = $nomCouleur;
		}
		// Le nombre de champs d'images téléchargeables est limité par la configuration PHP max_file_uploads qui peut être modifiée dans php.ini ou httpd.conf
		// Il est donc nécessaire de limiter le nombre de champs par couleur afin de ne pas dépasser cette limite
		if (function_exists('ini_get') && @ini_get('max_file_uploads') && !empty($nomCouleur_array)) {
			$upload_images_per_color = min(5, ceil(ini_get('max_file_uploads')) / count($nomCouleur_array));
		} else {
			$upload_images_per_color = 2;
		}
		$tpl->assign('upload_images_per_color', $upload_images_per_color);
		foreach($nomCouleur_array as $this_couleur) {
			$image_found = false;
			for($i = 1;$i <= $upload_images_per_color;$i++) {
				if (!empty($this_couleur['image' . $i])) {
					$image_found = true;
					break;
				}
			}
			$tpl_images = array();
			if ($image_found) {
				for ($i = 1; $i <= $upload_images_per_color; $i++) {
					$tpl_images[$i] = get_uploaded_file_infos('imagecouleur' . $this_couleur['id'] . '_' . $i, $this_couleur['image' . $i], get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&coul=' . $this_couleur['coul'] . '&file=image' . $i . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1));
				}
			}
			$tpl_colors[] = array('nom' => $this_couleur['nom_' . $_SESSION['session_langue']],
				'id' => $this_couleur['id'],
				'issel' => vb($frm['default_color_id']) == $this_couleur['coul'],
				'coul' => $this_couleur['coul'],
				'default_image' => vb($this_couleur['default_image']),
				'images' => $tpl_images
				);
		}
		$tpl->assign('colors', $tpl_colors);

		$tpl->assign('youtube_code', vb($frm['youtube_code']));

		$tpl_util_options = array();
		$select = query("SELECT id_utilisateur, societe
			FROM peel_utilisateurs
			WHERE CONCAT('+',priv,'+') LIKE '%+supplier+%' AND " . get_filter_site_cond('utilisateurs') . "
			ORDER BY societe");
		while ($nom = fetch_assoc($select)) {
			$tpl_util_options[] = array('value' => intval($nom['id_utilisateur']),
				'issel' => $nom['id_utilisateur'] == vb($frm['id_utilisateur']),
				'name' => $nom['societe']
				);
		}
		$tpl->assign('util_options', $tpl_util_options);

		$tpl_marques_options = array();
		$select = query("SELECT id, nom_" . $_SESSION['session_langue'] . ", etat
		   FROM peel_marques
		   WHERE " . get_filter_site_cond('marques') . "
		   ORDER BY position, nom_" . $_SESSION['session_langue'] . " ASC");
		while ($nom = fetch_assoc($select)) {
			$tpl_marques_options[] = array('value' => intval($nom['id']),
				'issel' => $nom['id'] == vb($frm['id_marque']),
				'name' => $nom['nom_' . $_SESSION['session_langue']] . (empty($nom['etat'])?' ('.$GLOBALS["STR_ADMIN_DEACTIVATED"].')':'')
				);
		}
		$tpl->assign('marques_options', $tpl_marques_options);

		if (check_if_module_active('stock_advanced')) {
			$tpl_gestion_stock = affiche_gestion_stock($frm);
			$tpl->assign('gestion_stock', $tpl_gestion_stock);
		}

		$tpl_produits_options = array();
		$select = query("SELECT pr.reference_id, p.reference, p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS name, pr.quantity
			FROM peel_produits p
			LEFT JOIN peel_produits_references pr ON pr.reference_id = p.id
			WHERE produit_id = ".intval($frm['id'])." AND " . get_filter_site_cond('produits', 'p', true) . "
			ORDER BY reference ASC");
		$i=1;
		while ($nom = fetch_assoc($select)) {
			$tpl_produits_options[] = array('value' => intval($nom['reference_id']),
				'reference' => $nom['reference'],
				'name' => $nom['name'],
				'i' => $i,
				'qt' => intval($nom['quantity']),
			);
			$i++;
		}
		$tpl->assign('nb_produits', $frm['nb_produits']);
		$tpl->assign('produits_options', $tpl_produits_options);
		$tpl->assign('is_on_ref_produit', vn($frm['on_ref_produit']) == 1);
		$tpl->assign('nb_ref_produits', intval(vn($frm['nb_ref_produits'])));
		$tpl->assign('administrer_url', $GLOBALS['administrer_url']);

		$tpl->assign('is_attributes_module_active', check_if_module_active('attributs'));
		if (check_if_module_active('attributs')) {
			$tpl->assign('produits_attributs_href', $GLOBALS['wwwroot_in_admin'] . "/modules/attributs/administrer/produits_attributs.php?id=" . $frm['id']);
			$tpl->assign('nom_attributs_href', $GLOBALS['wwwroot_in_admin'] . '/modules/attributs/administrer/nom_attributs.php');
		}

		$tpl_couleurs_options = array();
		$select = query("SELECT c.*
			FROM peel_couleurs c
			WHERE " .  get_filter_site_cond('couleurs', 'c') . "
			ORDER BY c.position ASC, c.nom_" . $_SESSION['session_langue'] . " ASC");

		while ($nom = fetch_assoc($select)) {
			if (isset($_SESSION['session_admin_multisite']) && $_SESSION['session_admin_multisite'] === 0) {
				// L'administrateur multisite consulte la liste des couleurs existantes. Dans ce cas toutes les couleurs de tous les sites sont affichées, et on affiche le nom du site à coté du nom de la couleur pour éviter des erreurs d'administration.
				$color_name = '[' . $all_sites_name_array[$nom['site_id']] . '] ' . $nom['nom_' . $_SESSION['session_langue']];
			} else {
				$color_name = $nom['nom_' . $_SESSION['session_langue']];
			}
			$tpl_couleurs_options[] = array('value' => intval($nom['id']),
				'issel' => in_array($nom['id'], vn($frm['couleurs'])),
				'name' => $color_name);
		}
		$tpl->assign('couleurs_options', $tpl_couleurs_options);

		$tpl_tailles_options = array();
		$select = query("SELECT t.*
			FROM peel_tailles t
			WHERE  " . get_filter_site_cond('tailles', 't') . "
			ORDER BY t.position ASC, t.prix ASC, t.nom_" . $_SESSION['session_langue'] . " ASC");
		while ($nom = fetch_assoc($select)) {
			if (isset($_SESSION['session_admin_multisite']) && $_SESSION['session_admin_multisite'] === 0) {
				// L'administrateur multisite consulte la liste des tailles existantes. Dans ce cas toutes les tailles de tous les sites sont affichées, et on affiche le nom du site à coté du nom de la taille pour éviter des erreurs d'administration.
				$size_name = '[' . $all_sites_name_array[$nom['site_id']] . '] ' . $nom['nom_' . $_SESSION['session_langue']];
			} else {
				$size_name = $nom['nom_' . $_SESSION['session_langue']];
			}
			$tpl_tailles_options[] = array('value' => $nom['id'],
				'issel' => is_array(vn($frm['tailles'])) && in_array($nom['id'], vn($frm['tailles'])),
				'name' => $size_name,
				'prix' => ($nom['prix'] != 0 ? fprix($nom['prix'], true, $GLOBALS['site_parameters']['code'], false) : null)
				);
		}
		$tpl->assign('tailles_options', $tpl_tailles_options);

		$tpl->assign('products_table_additionnal_fields', !empty($GLOBALS['site_parameters']['products_table_additionnal_fields']));
		if(!empty($GLOBALS['site_parameters']['products_table_additionnal_fields'])) {
			$products_table_additionnal_fields = array();
			foreach($GLOBALS['site_parameters']['products_table_additionnal_fields'] as $this_key => $this_value) {
				if (strpos($this_key, 'image') === 0) {
					// Les images sont gérés dans la partie FICHIERS ASSOCIÉS AUX PRODUITS.
					continue;
				}
				$products_table_additionnal_fields[] = array('title' => $this_value, 'name' => $this_key, 'value' => vb($frm[$this_key]), 'type' => vb($GLOBALS['site_parameters']['products_table_additionnal_fields_form_type'][$this_key], 'text'));
			}
			$tpl->assign('products_table_additionnal_fields_array', $products_table_additionnal_fields);
		}

		$tpl->assign('is_download_module_active', check_if_module_active('download'));
		$tpl->assign('is_on_download', !empty($frm['on_download']));
		$tpl->assign('zip', vb($frm['zip']));

		$tpl->assign('is_flash_sell_module_active', check_if_module_active('flash'));
		if (check_if_module_active('flash')) {
			$tpl->assign('prix_flash', vn($prix_flash));
			$tpl->assign('flash_start', get_formatted_date(vb($frm['flash_start']), 'short', 'long'));
			$tpl->assign('flash_end', get_formatted_date(vb($frm['flash_end']), 'short', 'long'));
			$tpl->assign('is_on_flash', !empty($frm['on_flash']));
		}
		$tpl->assign('is_module_gift_checks_active', check_if_module_active('gift_check'));
		$tpl->assign('is_on_gift', !empty($frm['on_gift']));
		$tpl->assign('on_gift_points', vb($frm['on_gift_points']));

		$tpl->assign('normal_bouton', $frm['normal_bouton']);
		$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
		$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
		$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
		$tpl->assign('STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER', $GLOBALS['STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER']);
		$tpl->assign('STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH', $GLOBALS['STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH']);
		$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
		$tpl->assign('STR_ADMIN_ADD_EMPTY_LINE', $GLOBALS['STR_ADMIN_ADD_EMPTY_LINE']);
		$tpl->assign('STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM', $GLOBALS["STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM"]);
		$tpl->assign('STR_ADMIN_PRODUCT_ORDERED_DELETE', $GLOBALS["STR_ADMIN_PRODUCT_ORDERED_DELETE"]);
		$tpl->assign('STR_ADMIN_PRODUITS_UPDATE', $GLOBALS['STR_ADMIN_PRODUITS_UPDATE']);
		$tpl->assign('STR_ADMIN_SEE_RESULT_IN_REAL', $GLOBALS['STR_ADMIN_SEE_RESULT_IN_REAL']);
		$tpl->assign('STR_ADMIN_PRODUITS_ADD', $GLOBALS['STR_ADMIN_PRODUITS_ADD']);
		$tpl->assign('STR_ADMIN_PRODUITS_VIEWS_COUNT', $GLOBALS['STR_ADMIN_PRODUITS_VIEWS_COUNT']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
		$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
		$tpl->assign('STR_DELETE_CONFIRM', $GLOBALS['STR_DELETE_CONFIRM']);
		$tpl->assign('STR_ADMIN_PRODUITS_POSITION_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_POSITION_EXPLAIN']);
		$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_GIFT_CHECK', $GLOBALS['STR_ADMIN_PRODUITS_IS_GIFT_CHECK']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_HOME', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_HOME']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_NEW', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_NEW']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_RESELLER', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_RESELLER']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_PROMOTIONS', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_PROMOTIONS']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_PROMOTIONS_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_PROMOTIONS_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_EXTRA_LINK', $GLOBALS['STR_ADMIN_PRODUITS_EXTRA_LINK']);
		$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
		$tpl->assign('STR_ADMIN_PRODUITS_BEST_SELLERS', $GLOBALS['STR_ADMIN_PRODUITS_BEST_SELLERS']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_ROLLOVER', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_ROLLOVER']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_ESTIMATE', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_ESTIMATE']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_CART_PAGE', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_CART_PAGE']);
		$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
		$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
		$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
		$tpl->assign('STR_REFERENCE', $GLOBALS['STR_REFERENCE']);
		$tpl->assign('STR_REFERENCE_FOURNISSEUR', $GLOBALS['STR_REFERENCE_FOURNISSEUR']);
		$tpl->assign('STR_CONDITIONNEMENT', $GLOBALS['STR_CONDITIONNEMENT']);
		$tpl->assign('STR_ADMIN_CONDITIONNEMENT_TEXT', $GLOBALS['STR_ADMIN_CONDITIONNEMENT_TEXT']);
		$tpl->assign('STR_CONDITIONNEMENT_QTY', $GLOBALS['STR_CONDITIONNEMENT_QTY']);
		$tpl->assign('STR_ADMIN_PRODUITS_EAN_CODE', $GLOBALS['STR_ADMIN_PRODUITS_EAN_CODE']);
		$tpl->assign('STR_ADMIN_PRODUITS_UNIT_PER_PALLET', $GLOBALS['STR_ADMIN_PRODUITS_UNIT_PER_PALLET']);
		$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
		$tpl->assign('STR_ADMIN_PRODUITS_PRICE_IN', $GLOBALS['STR_ADMIN_PRODUITS_PRICE_IN']);
		$tpl->assign('STR_ADMIN_PRODUITS_PRICE_PROMOTION', $GLOBALS['STR_ADMIN_PRODUITS_PRICE_PROMOTION']);
		$tpl->assign('STR_ADMIN_PRODUITS_RESELLER_PRICE_IN', $GLOBALS['STR_ADMIN_PRODUITS_RESELLER_PRICE_IN']);
		$tpl->assign('STR_ADMIN_PRODUITS_PURCHASE_PRICE_IN', $GLOBALS['STR_ADMIN_PRODUITS_PURCHASE_PRICE_IN']);
		$tpl->assign('STR_ADMIN_VAT_PERCENTAGE', $GLOBALS['STR_ADMIN_VAT_PERCENTAGE']);
		$tpl->assign('STR_ADMIN_ECOTAX', $GLOBALS['STR_ADMIN_ECOTAX']);
		$tpl->assign('STR_ADMIN_NOT_APPLICABLE', $GLOBALS['STR_ADMIN_NOT_APPLICABLE']);
		$tpl->assign('STR_ADMIN_PRODUITS_DISCOUNT_PERCENTAGE', $GLOBALS['STR_ADMIN_PRODUITS_DISCOUNT_PERCENTAGE']);
		$tpl->assign('STR_ADMIN_PRODUITS_DISCOUNT_PERCENTAGE_OVER_LISTED_PRICE', $GLOBALS['STR_ADMIN_PRODUITS_DISCOUNT_PERCENTAGE_OVER_LISTED_PRICE']);
		$tpl->assign('STR_ADMIN_PRODUITS_GIFT_POINTS', $GLOBALS['STR_ADMIN_PRODUITS_GIFT_POINTS']);
		$tpl->assign('STR_ADMIN_PRODUITS_WEIGHT', $GLOBALS['STR_ADMIN_PRODUITS_WEIGHT']);
		$tpl->assign('STR_ADMIN_PRODUITS_WEIGHT_UNIT', $GLOBALS['STR_ADMIN_PRODUITS_WEIGHT_UNIT']);
		$tpl->assign('STR_ADMIN_PRODUITS_VOLUME', $GLOBALS['STR_ADMIN_PRODUITS_VOLUME']);
		$tpl->assign('STR_ADMIN_PRODUITS_VOLUME_UNIT', $GLOBALS['STR_ADMIN_PRODUITS_VOLUME_UNIT']);
		$tpl->assign('STR_ADMIN_PRODUITS_DISPLAY_PRICE_PER_KILO', $GLOBALS['STR_ADMIN_PRODUITS_DISPLAY_PRICE_PER_KILO']);
		$tpl->assign('STR_ADMIN_PRODUITS_DISPLAY_PRICE_PER_LITER', $GLOBALS['STR_ADMIN_PRODUITS_DISPLAY_PRICE_PER_LITER']);
		$tpl->assign('STR_ADMIN_PRODUITS_DISPLAY_NO_PRICE_PER_UNIT', $GLOBALS['STR_ADMIN_PRODUITS_DISPLAY_NO_PRICE_PER_UNIT']);
		$tpl->assign('STR_ADMIN_PRODUITS_LOT_PRICE', $GLOBALS['STR_ADMIN_PRODUITS_LOT_PRICE']);
		$tpl->assign('STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE', $GLOBALS['STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE']);
		$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
		$tpl->assign('STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_LOT_PRICE_HANDLE_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_FILES_HEADER', $GLOBALS['STR_ADMIN_PRODUITS_FILES_HEADER']);
		$tpl->assign('STR_ADMIN_PRODUITS_FILES_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_FILES_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER', $GLOBALS['STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER']);
		$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
		$tpl->assign('STR_FILE', $GLOBALS['STR_FILE']);
		$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
		$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
		$tpl->assign('STR_ADMIN_PRODUITS_FILE_FOR_COLOR', $GLOBALS['STR_ADMIN_PRODUITS_FILE_FOR_COLOR']);
		$tpl->assign('STR_ADMIN_PRODUITS_FILES_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_FILES_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER', $GLOBALS['STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER']);
		$tpl->assign('STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER_CONSTRAINT', $GLOBALS['STR_ADMIN_PRODUITS_DEFAULT_FILE_NUMBER_CONSTRAINT']);
		$tpl->assign('STR_ADMIN_DELETE_IMAGE', $GLOBALS['STR_ADMIN_DELETE_IMAGE']);
		$tpl->assign('STR_ADMIN_PRODUITS_ADD_INPUT_FOR_THIS_COLOR', $GLOBALS['STR_ADMIN_PRODUITS_ADD_INPUT_FOR_THIS_COLOR']);
		$tpl->assign('STR_ADMIN_PRODUITS_VIDEO_TAG', $GLOBALS['STR_ADMIN_PRODUITS_VIDEO_TAG']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_TABS_TITLE', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_TABS_TITLE']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_TABS_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_TABS_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_TAB', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_TAB']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_TAB_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_TAB_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_TEXT_RELATED_IN', $GLOBALS['STR_ADMIN_PRODUITS_TEXT_RELATED_IN']);
		$tpl->assign('STR_ADMIN_PRODUITS_SHORT_DESCRIPTION', $GLOBALS['STR_ADMIN_PRODUITS_SHORT_DESCRIPTION']);
		$tpl->assign('STR_ADMIN_PRODUITS_DESCRIPTION', $GLOBALS['STR_ADMIN_PRODUITS_DESCRIPTION']);
		$tpl->assign('STR_ADMIN_META_TITLE', $GLOBALS['STR_ADMIN_META_TITLE']);
		$tpl->assign('STR_ADMIN_META_TITLE_EXPLAIN', $GLOBALS['STR_ADMIN_META_TITLE_EXPLAIN']);
		$tpl->assign('STR_ADMIN_META_KEYWORDS', $GLOBALS['STR_ADMIN_META_KEYWORDS']);
		$tpl->assign('STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN', $GLOBALS['STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN']);
		$tpl->assign('STR_ADMIN_META_KEYWORDS_EXPLAIN', $GLOBALS['STR_ADMIN_META_KEYWORDS_EXPLAIN']);
		$tpl->assign('STR_ADMIN_META_DESCRIPTION', $GLOBALS['STR_ADMIN_META_DESCRIPTION']);
		$tpl->assign('STR_ADMIN_META_DESCRIPTION_EXPLAIN', $GLOBALS['STR_ADMIN_META_DESCRIPTION_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_LINK_PRODUCT_TO_SUPPLIER', $GLOBALS['STR_ADMIN_PRODUITS_LINK_PRODUCT_TO_SUPPLIER']);
		$tpl->assign('STR_ADMIN_PRODUITS_CHOOSE_BRAND', $GLOBALS['STR_ADMIN_PRODUITS_CHOOSE_BRAND']);
		$tpl->assign('STR_ADMIN_PRODUITS_CHOOSE_REFERENCE', $GLOBALS['STR_ADMIN_PRODUITS_CHOOSE_REFERENCE']);
		$tpl->assign('STR_ADMIN_PRODUITS_CHOOSE_REFERENCE_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_CHOOSE_REFERENCE_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_AUTO_REF_PRODUCT', $GLOBALS['STR_ADMIN_PRODUITS_AUTO_REF_PRODUCT']);
		$tpl->assign('STR_ADMIN_PRODUITS_AUTO_REF_NUMBER_PRODUCTS', $GLOBALS['STR_ADMIN_PRODUITS_AUTO_REF_NUMBER_PRODUCTS']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_CRITERIA', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_CRITERIA']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_CRITERIA_INTRO', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_CRITERIA_INTRO']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_CRITERIA_LINK', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_CRITERIA_LINK']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_CRITERIA_TEASER', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_CRITERIA_TEASER']);
		$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_COLORS_SIZES_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_COLORS_SIZES_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_OTHER_OPTION', $GLOBALS['STR_ADMIN_PRODUITS_OTHER_OPTION']);
		$tpl->assign('STR_ADMIN_PRODUITS_PRODUCT_COLORS', $GLOBALS['STR_ADMIN_PRODUITS_PRODUCT_COLORS']);
		$tpl->assign('STR_ADMIN_PRODUITS_OTHER_OPTION', $GLOBALS['STR_ADMIN_PRODUITS_OTHER_OPTION']);
		$tpl->assign('STR_ADMIN_PRODUITS_PRODUCT_SIZES', $GLOBALS['STR_ADMIN_PRODUITS_PRODUCT_SIZES']);
		$tpl->assign('STR_ADMIN_PRODUITS_DOWNLOAD_PRODUCTS_HEADER', $GLOBALS['STR_ADMIN_PRODUITS_DOWNLOAD_PRODUCTS_HEADER']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_DOWLOAD', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_DOWLOAD']);
		$tpl->assign('STR_ADMIN_PRODUITS_FILE_NAME', $GLOBALS['STR_ADMIN_PRODUITS_FILE_NAME']);
		$tpl->assign('STR_ADMIN_PRODUITS_FLASH_SALE', $GLOBALS['STR_ADMIN_PRODUITS_FLASH_SALE']);
		$tpl->assign('STR_ADMIN_PRODUITS_FLASH_SALE_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_FLASH_SALE_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_FLASH_PRICE', $GLOBALS['STR_ADMIN_PRODUITS_FLASH_PRICE']);
		$tpl->assign('STR_ADMIN_PRODUITS_FLASH_START_DATE', $GLOBALS['STR_ADMIN_PRODUITS_FLASH_START_DATE']);
		$tpl->assign('STR_ADMIN_PRODUITS_FLASH_END_DATE', $GLOBALS['STR_ADMIN_PRODUITS_FLASH_END_DATE']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_FLASH', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_FLASH']);
		$tpl->assign('STR_ADMIN_PRODUITS_GIFT_CHECK_HEADER', $GLOBALS['STR_ADMIN_PRODUITS_GIFT_CHECK_HEADER']);
		$tpl->assign('STR_ADMIN_PRODUITS_IS_ON_GIFT', $GLOBALS['STR_ADMIN_PRODUITS_IS_ON_GIFT']);
		$tpl->assign('STR_ADMIN_PRODUITS_GIFT_POINTS_NEEDED', $GLOBALS['STR_ADMIN_PRODUITS_GIFT_POINTS_NEEDED']);
		$tpl->assign('STR_ERR_CAT', $GLOBALS['STR_ERR_CAT']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_PRODUITS_GIFT_CHECK_EXPLAIN', $GLOBALS['STR_ADMIN_PRODUITS_GIFT_CHECK_EXPLAIN']);
		$tpl->assign('STR_ADMIN_PRODUITS_DEFAULT_COLOR_IN_FRONT', $GLOBALS['STR_ADMIN_PRODUITS_DEFAULT_COLOR_IN_FRONT']);
		$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
		$output .= $tpl->fetch();
	}
	return $output;
}

/**
 * Supprime le produit spécifié par $id. Il faut supprimer le produit
 * puis les entrées correspondantes de la table peel_produits_categories et autres tables liées.
 *
 * @param integer $id
 * @return
 */
function supprime_produit($id)
{
	$output = '';
	/* Charge les infos du produit. */
	$qid = query("SELECT *, nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS name
		FROM peel_produits
		WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('produits', null, true) . "");
	$product_infos = fetch_assoc($qid);
	// delete_all_product_file(intval($id));
	for ($i = 1; $i <= 10; $i++) {
		if(!empty($product_infos['image' . $i])) {
			supprime_fichier_produit($id, 'image' . $i);
		}
	}

	$sql = query("SELECT *
		FROM peel_produits_couleurs
		WHERE produit_id = " . intval($id));
	while ($prod = fetch_assoc($sql)) {
		for ($i = 1; $i <= 5; $i++) {
			if(!empty($prod['image' . $i])) {
				supprime_fichier_couleur($id, 'image' . $i, $prod['couleur_id']);
			}
		}
	}

	/* Efface ce produit dans les tables de jointure telles que la table peel_produits_categories */
	query("DELETE FROM peel_produits_categories WHERE produit_id = '" . intval($id) . "'");
	query("DELETE FROM peel_produits_references WHERE produit_id = '" . intval($id) . "'");
	query("DELETE FROM peel_produits_couleurs WHERE produit_id = '" . intval($id) . "'");
	query("DELETE FROM peel_produits_tailles WHERE produit_id = '" . intval($id) . "'");
	if (check_if_module_active('stock_advanced') && $product_infos['on_stock'] == 1) {
		query("DELETE FROM peel_stocks WHERE produit_id = '" . intval($id) . "'");
	}
	// Efface le produit
	query("DELETE FROM peel_produits WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('produits', null, true));

	if (affected_rows()) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PRODUITS_MSG_DELETED_OK'], StringMb::html_entity_decode_if_needed($product_infos['name']))))->fetch();
	}
	return $output;
}

/**
 * Supprime le fichier lié au produit spécifié par $id, au nom de file.
 *
 * @param integer $id
 * @param mixed $file
 * @return
 */
function supprime_fichier_produit($id, $file)
{
	$output = '';
	if (strpos($file, 'image') === 0) {
		$sql = "SELECT " . word_real_escape_string($file) . "
			FROM peel_produits
			WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('produits', null, true) . "";
		$res = query($sql);
		if ($file_infos = fetch_assoc($res)) {
			query("UPDATE peel_produits
				SET `" . word_real_escape_string($file) . "`=''
				WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('produits', null, true) . "");
		}
	}
	if (!empty($file_infos) && delete_uploaded_file_and_thumbs($file_infos[$file])) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_RUBRIQUES_MSG_DELETED_OK'], $file_infos[$file])))->fetch();
	}
	return $output;
}

/**
 * Supprime le fichier lié au produit spécifié par $id, au nom de file et pour la couleur couleur_id.
 *
 * @param integer $id
 * @param mixed $file
 * @param mixed $couleur_id
 * @return
 */
function supprime_fichier_couleur($id, $file, $couleur_id)
{
	$output = '';
	if (in_array($file, array('default_image', 'image1', 'image2', 'image3', 'image4', 'image5'))) {
		$sql = "SELECT " . word_real_escape_string($file) . "
			FROM peel_produits_couleurs
			WHERE produit_id = '" . intval($id) . "' AND couleur_id='" . intval($couleur_id) . "'";
		$res = query($sql);
		if ($file_infos = fetch_row($res)) {
			query("UPDATE peel_produits_couleurs
				SET " . word_real_escape_string($file) . "=''
				WHERE produit_id = '" . intval($id) . "' AND couleur_id='" . intval($couleur_id) . "'");
		}
	}
	if (!empty($file_infos[$file]) && delete_uploaded_file_and_thumbs($file_infos[$file])) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_RUBRIQUES_MSG_DELETED_OK'], $file_infos[$file])))->fetch();
	}
	return $output;
}

/**
 * Ajoute un nouveau produit.  Les champs sont dans la variable $frm
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_produit($frm)
{
	$output = '';
	if ($frm['promotion']>0) {
		$frm['on_promo'] = 1;
	} else {
		$frm['on_promo'] = 0;
	}
	if (display_prices_with_taxes_in_admin ()) {
		$prix = $frm['prix'];
	} else {
		$prix = $frm['prix'] * (1 + $frm['tva'] / 100);
	}
	if (display_prices_with_taxes_in_admin ()) {
		$prix_promo = $frm['prix_promo'];
	} else {
		$prix_promo = $frm['prix_promo'] * (1 + $frm['tva'] / 100);
	}
	if (check_if_module_active('flash')) {
		if (display_prices_with_taxes_in_admin ()) {
			$prix_flash = $frm['prix_flash'];
		} else {
			$prix_flash = $frm['prix_flash'] * (1 + $frm['tva'] / 100);
		}
	}
	if (check_if_module_active('reseller') && !empty($frm['prix_revendeur'])) {
		if (display_prices_with_taxes_in_admin() && empty($GLOBALS['site_parameters']['force_display_reseller_prices_without_taxes'])) {
			$prix_revendeur = $frm['prix_revendeur'];
		} else {
			$prix_revendeur = $frm['prix_revendeur'] * (1 + $frm['tva'] / 100);
		}
	} else {
		$prix_revendeur = 0;
	}
	$prix_achat = $frm['prix_achat'] * (1 + $frm['tva'] / 100);
	/* ajoute le produit dans la table produits */
	$product_fields[] = "date_insere = '" . date('Y-m-d H:i:s', time()) . "'";

	// Début de la section comme pour MAJ
	$product_fields[] = "reference = '" . nohtml_real_escape_string($frm['reference']) . "'";
	$product_fields[] = "reference_fournisseur = '" . nohtml_real_escape_string($frm['reference_fournisseur']) . "'";
	$product_fields[] = "ean_code = '" . nohtml_real_escape_string($frm['ean_code']) . "'";
	$product_fields[] = "prix = '" . nohtml_real_escape_string($prix) . "'";
	$product_fields[] = "prix_promo = '" . nohtml_real_escape_string($prix_promo) . "'";
	$product_fields[] = "prix_revendeur = '" . nohtml_real_escape_string($prix_revendeur) . "'";
	$product_fields[] = "prix_achat = '" . nohtml_real_escape_string($prix_achat) . "'";
	$product_fields[] = "default_image = '" . nohtml_real_escape_string($frm['default_image']) . "'";
	$product_fields[] = "image1 = '" . nohtml_real_escape_string($frm['image1']) . "'";
	$product_fields[] = "image2 = '" . nohtml_real_escape_string($frm['image2']) . "'";
	$product_fields[] = "image3 = '" . nohtml_real_escape_string($frm['image3']) . "'";
	$product_fields[] = "image4 = '" . nohtml_real_escape_string($frm['image4']) . "'";
	$product_fields[] = "image5 = '" . nohtml_real_escape_string($frm['image5']) . "'";
	$product_fields[] = "image6 = '" . nohtml_real_escape_string($frm['image6']) . "'";
	$product_fields[] = "image7 = '" . nohtml_real_escape_string($frm['image7']) . "'";
	$product_fields[] = "image8 = '" . nohtml_real_escape_string($frm['image8']) . "'";
	$product_fields[] = "image9 = '" . nohtml_real_escape_string($frm['image9']) . "'";
	$product_fields[] = "image10 = '" . nohtml_real_escape_string($frm['image10']) . "'";
	$product_fields[] = "youtube_code = '" . real_escape_string($frm['youtube_code']) . "'";
	$product_fields[] = "promotion = '" . nohtml_real_escape_string($frm['promotion']) . "'";
	$product_fields[] = "tva = '" . nohtml_real_escape_string($frm['tva']) . "'";
	$product_fields[] = "etat = '" . nohtml_real_escape_string($frm['etat']) . "'";
	$product_fields[] = "date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
	$product_fields[] = "on_special = '" . nohtml_real_escape_string(vn($frm['on_special'])) . "'";
	$product_fields[] = "poids = '" . nohtml_real_escape_string($frm['poids']) . "'";
	$product_fields[] = "on_reseller = '" . nohtml_real_escape_string(vn($frm['on_reseller'])) . "'";
	$product_fields[] = "on_promo = '" . nohtml_real_escape_string(vn($frm['on_promo'])) . "'";
	$product_fields[] = "on_new = '" . nohtml_real_escape_string(vn($frm['on_new'])) . "'";
	$product_fields[] = "alpha = '" . nohtml_real_escape_string(StringMb::substr(StringMb::strtoupper(vb($frm['nom_' . $_SESSION['session_langue']])), 0, 1)) . "'";
	$product_fields[] = "on_stock = '" . intval(vn($frm['on_stock'])) . "'";
	$product_fields[] = "affiche_stock = '" . intval(vn($frm['affiche_stock'])) . "'";
	$product_fields[] = "delai_stock = '" . nohtml_real_escape_string(StringMb::html_entity_decode_if_needed(vb($frm['delai_stock']))) . "'";
	$product_fields[] = "etat_stock = '" . intval(vn($frm['etat_stock'])) . "'";
	$product_fields[] = "extra_link = '" . nohtml_real_escape_string(vb($frm['extra_link'])) . "'";
	$product_fields[] = "technical_code = '" . nohtml_real_escape_string(vb($frm['technical_code'])) . "'";
	$product_fields[] = "id_marque = '" . intval(vn($frm['id_marque'])) . "'";
	$product_fields[] = "on_rupture = '" . intval(vn($frm['on_rupture'])) . "'";
	$product_fields[] = "id_ecotaxe = '" . intval(vn($frm['id_ecotaxe'])) . "'";
	$product_fields[] = "id_utilisateur = '" . intval(vn($frm['id_utilisateur'])) . "'";
	$product_fields[] = "position = '" . intval($frm['position']) . "'";
	$product_fields[] = "on_ref_produit = '" . intval(vn($frm['on_ref_produit'])) . "'";
	$product_fields[] = "nb_ref_produits = '" . intval($frm['nb_ref_produits']) . "'";
	$product_fields[] = "display_price_by_weight = '" . nohtml_real_escape_string($frm['display_price_by_weight']) . "'";
	$product_fields[] = "volume = '" . nohtml_real_escape_string($frm['volume']) . "'";
	$product_fields[] = "on_estimate = '" . nohtml_real_escape_string(vn($frm['on_estimate'])) . "'";
	$product_fields[] = "recommanded_product_on_cart_page = '" . nohtml_real_escape_string(vn($frm['recommanded_product_on_cart_page'])) . "'";
	$product_fields[] = "default_color_id = '" . intval(vn($frm['default_color_id'])) . "'";
	$product_fields[] = "site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value(vb($frm['site_id']))) . "'";
	$product_fields[] = "allow_add_product_with_no_stock_in_cart = '" . intval(vn($frm['allow_add_product_with_no_stock_in_cart'])) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$product_fields[] = "site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		if(empty($GLOBALS['site_parameters']['product_name_forced_lang']) || $lng == $GLOBALS['site_parameters']['product_name_forced_lang']) {
			$product_fields[] = "nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
		}
		if(empty($GLOBALS['site_parameters']['product_description_forced_lang']) || $lng == $GLOBALS['site_parameters']['product_description_forced_lang']) {
			$product_fields[] = "description_" . $lng . " = '" . real_escape_string($frm['description_' . $lng]) . "'";
		}
		$product_fields[] = "descriptif_" . $lng . " = '" . real_escape_string($frm['descriptif_' . $lng]) . "'";
		$product_fields[] = "meta_titre_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'";
		$product_fields[] = "meta_key_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'";
		$product_fields[] = "meta_desc_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}
	if (check_if_module_active('flash')) {
		$product_fields[] = "prix_flash = '" . nohtml_real_escape_string($prix_flash) . "'";
		$product_fields[] = "on_flash = '" . nohtml_real_escape_string(vn($frm['on_flash'])) . "'";
		$product_fields[] = "flash_start = '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['flash_start'])) . "'";
		$product_fields[] = "flash_end = '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['flash_end'])) . "'";
	}
	if (check_if_module_active('best_seller')) {
		$product_fields[] = "on_top = '" . nohtml_real_escape_string(vn($frm['on_top'])) . "'";
	}

	if (check_if_module_active('menus')) {
		$product_fields[] = "on_rollover = '" . nohtml_real_escape_string(vn($frm['on_rollover'])) . "'";
	}
	if (check_if_module_active('gifts')) {
		$product_fields[] = "points = '" . nohtml_real_escape_string($frm['points']) . "'";
		$product_fields[] = "on_gift = '" . nohtml_real_escape_string(vn($frm['on_gift'])) . "'";
		$product_fields[] = "on_gift_points = '" . nohtml_real_escape_string(vn($frm['on_gift_points'])) . "'";
	}
	if (check_if_module_active('gift_check')) {
		$product_fields[] = "on_check = '" . nohtml_real_escape_string(vn($frm['on_check'])) . "'";
	}
	if (check_if_module_active('download')) {
		$product_fields[] = "zip = '" . nohtml_real_escape_string($frm['zip']) . "'";
		$product_fields[] = "on_download = '" . nohtml_real_escape_string(vn($frm['on_download'])) . "'";
	}
	if (check_if_module_active('conditionnement')) {
		$product_fields[] = "conditionnement = '" . intval($frm['conditionnement']) . "'";
		$product_fields[] = "unit_per_pallet = '" . intval($frm['unit_per_pallet']) . "'";
		$product_fields[] = "conditioning_text = '" . nohtml_real_escape_string($frm['conditioning_text']) . "'";
	}
	if(!empty($GLOBALS['site_parameters']['products_table_additionnal_fields'])) {
		foreach($GLOBALS['site_parameters']['products_table_additionnal_fields'] as $this_key => $this_value) {
			$product_fields[] = word_real_escape_string($this_key) . " = '" . nohtml_real_escape_string(vb($frm[$this_key])) . "'";
		}
	}
	$product_fields = get_table_field_names('peel_produits', null, false, $product_fields);
	// Fin de la section identique à MAJ des produits
	
	$sql = "INSERT INTO peel_produits
		SET	" . implode(', ', $product_fields) . "";
	query($sql);

	$product_id = insert_id();
	$GLOBALS['last_insert_product_id'] = $product_id;
	/* ajoute le produit sous les catégories spécifiées */
	for ($i = 0; $i < count(vn($frm['categories'])); $i++) {
		if (!empty($frm['categories'][$i])) {
		$qid = query("INSERT INTO peel_produits_categories (categorie_id, produit_id)
			VALUES ('" . nohtml_real_escape_string($frm['categories'][$i]) . "', '" . intval($product_id) . "')");
	}
	}

	/* ajoute les références associées */
	for ($i = 0; $i < count(vn($frm['references'])); $i++) {
		if (!empty($frm['references'][$i])) {
			$query = query("SELECT reference_id
				FROM peel_produits_references 
				WHERE produit_id = " . intval($product_id) ." AND " . nohtml_real_escape_string($frm['references'][$i]));
			if (!$result = fetch_assoc($query)) {
				// Association de produit non présente.
				$qid = query("INSERT INTO peel_produits_references (reference_id, produit_id)
					VALUES ('" . nohtml_real_escape_string($frm['references'][$i]) . "', '" . intval($product_id) . "')");
			}
		}
	}

	/* ajoute les couleurs associées */
	for ($i = 0; $i < count(vn($frm['couleurs'])); $i++) {
		if (!empty($frm['couleurs'][$i])) {
			$qid = query("INSERT INTO peel_produits_couleurs (couleur_id, produit_id)
				VALUES ('" . nohtml_real_escape_string($frm['couleurs'][$i]) . "', '" . intval($product_id) . "')");
		}
	}

	/* ajoute les tailles associées */
	for ($i = 0; $i < count(vn($frm['tailles'])); $i++) {
		if (!empty($frm['tailles'][$i])) {
			$qid = query("INSERT INTO peel_produits_tailles (taille_id, produit_id)
				VALUES ('" . nohtml_real_escape_string($frm['tailles'][$i]) . "', '" . intval($product_id) . "')");
		}
	}
	if (check_if_module_active('stock_advanced')) {
		insert_product_in_stock_table_if_not_exist($product_id, vn($frm['on_stock']));
	}

	if (check_if_module_active('payment_by_product') && !empty($frm['paiment_allowed'])) {
		insert_payment_by_product($frm['paiment_allowed'], $product_id);
	}
	if (!empty($product_id)) {
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PRODUITS_MSG_CREATED_OK'], StringMb::html_entity_decode_if_needed($frm['nom_' . $_SESSION['session_langue'] . '']))))->fetch();
	}
	return $output;
}

/**
 * Met à jour le produit $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_produit($id, $frm)
{
	// Le nombre de champs d'images téléchargeable est limité par la configuration PHP upload_max_filesize qui peut être modifiée dans php.ini ou httpd.conf
	// Il est donc nécessaire de limiter le nombre de champs par couleur afin de ne pas dépasser cette limite
	if (function_exists('ini_get') && @ini_get('max_file_uploads') && !empty($frm['couleurs'])) {
		$upload_images_per_color = min(5, ceil(ini_get('max_file_uploads')) / count($frm['couleurs']));
	} else {
		$upload_images_per_color = 2;
	}
	if (check_if_module_active('product_references_by_options')) {
		handle_product_multiple_references_form($frm);
	}
	if (display_prices_with_taxes_in_admin ()) {
		$prix = get_float_from_user_input($frm['prix']);
	} else {
		$prix = get_float_from_user_input($frm['prix']) * (1 + $frm['tva'] / 100);
	}
	if (display_prices_with_taxes_in_admin ()) {
		$prix_promo = get_float_from_user_input($frm['prix_promo']);
	} else {
		$prix_promo = get_float_from_user_input($frm['prix_promo']) * (1 + $frm['tva'] / 100);
	}
	if (check_if_module_active('flash')) {
		if (display_prices_with_taxes_in_admin ()) {
			$prix_flash = get_float_from_user_input($frm['prix_flash']);
		} else {
			$prix_flash = get_float_from_user_input($frm['prix_flash']) * (1 + $frm['tva'] / 100);
		}
	}
	if (check_if_module_active('reseller')) {
		if (display_prices_with_taxes_in_admin () && empty($GLOBALS['site_parameters']['force_display_reseller_prices_without_taxes'])) {
			$prix_revendeur = get_float_from_user_input($frm['prix_revendeur']);
		} else {
			$prix_revendeur = get_float_from_user_input($frm['prix_revendeur']) * (1 + $frm['tva'] / 100);
		}
	} else {
		$prix_revendeur = 0;
	}
	if (check_if_module_active('payment_by_product')) {
		update_payment_by_product(vb($frm['paiment_allowed'], array()), $id);
	}
	$prix_achat = get_float_from_user_input($frm['prix_achat']) * (1 + $frm['tva'] / 100);
	
	/* Met à jour la table produits */
	$product_fields[] = "reference = '" . nohtml_real_escape_string($frm['reference']) . "'";
	$product_fields[] = "reference_fournisseur = '" . nohtml_real_escape_string($frm['reference_fournisseur']) . "'";
	$product_fields[] = "ean_code = '" . nohtml_real_escape_string($frm['ean_code']) . "'";
	$product_fields[] = "prix = '" . nohtml_real_escape_string($prix) . "'";
	$product_fields[] = "prix_promo = '" . nohtml_real_escape_string($prix_promo) . "'";
	$product_fields[] = "prix_revendeur = '" . nohtml_real_escape_string($prix_revendeur) . "'";
	$product_fields[] = "prix_achat = '" . nohtml_real_escape_string($prix_achat) . "'";
	$product_fields[] = "default_image = '" . nohtml_real_escape_string($frm['default_image']) . "'";
	$product_fields[] = "image1 = '" . nohtml_real_escape_string($frm['image1']) . "'";
	$product_fields[] = "image2 = '" . nohtml_real_escape_string($frm['image2']) . "'";
	$product_fields[] = "image3 = '" . nohtml_real_escape_string($frm['image3']) . "'";
	$product_fields[] = "image4 = '" . nohtml_real_escape_string($frm['image4']) . "'";
	$product_fields[] = "image5 = '" . nohtml_real_escape_string($frm['image5']) . "'";
	$product_fields[] = "image6 = '" . nohtml_real_escape_string($frm['image6']) . "'";
	$product_fields[] = "image7 = '" . nohtml_real_escape_string($frm['image7']) . "'";
	$product_fields[] = "image8 = '" . nohtml_real_escape_string($frm['image8']) . "'";
	$product_fields[] = "image9 = '" . nohtml_real_escape_string($frm['image9']) . "'";
	$product_fields[] = "image10 = '" . nohtml_real_escape_string($frm['image10']) . "'";
	$product_fields[] = "youtube_code = '" . real_escape_string($frm['youtube_code']) . "'";
	$product_fields[] = "promotion = '" . nohtml_real_escape_string($frm['promotion']) . "'";
	$product_fields[] = "tva = '" . nohtml_real_escape_string($frm['tva']) . "'";
	$product_fields[] = "etat = '" . nohtml_real_escape_string($frm['etat']) . "'";
	$product_fields[] = "date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
	$product_fields[] = "on_special = '" . nohtml_real_escape_string(vn($frm['on_special'])) . "'";
	$product_fields[] = "poids = '" . nohtml_real_escape_string($frm['poids']) . "'";
	$product_fields[] = "on_reseller = '" . nohtml_real_escape_string(vn($frm['on_reseller'])) . "'";
	$product_fields[] = "on_promo = '" . nohtml_real_escape_string(vn($frm['on_promo'])) . "'";
	$product_fields[] = "on_new = '" . nohtml_real_escape_string(vn($frm['on_new'])) . "'";
	$product_fields[] = "alpha = '" . nohtml_real_escape_string(StringMb::substr(StringMb::strtoupper(vb($frm['nom_' . $_SESSION['session_langue']])), 0, 1)) . "'";
	$product_fields[] = "on_stock = '" . intval(vn($frm['on_stock'])) . "'";
	$product_fields[] = "affiche_stock = '" . intval(vn($frm['affiche_stock'])) . "'";
	$product_fields[] = "delai_stock = '" . nohtml_real_escape_string(StringMb::html_entity_decode_if_needed(vb($frm['delai_stock']))) . "'";
	$product_fields[] = "etat_stock = '" . intval(vn($frm['etat_stock'])) . "'";
	$product_fields[] = "extra_link = '" . nohtml_real_escape_string(vb($frm['extra_link'])) . "'";
	$product_fields[] = "technical_code = '" . nohtml_real_escape_string(vb($frm['technical_code'])) . "'";
	$product_fields[] = "id_marque = '" . intval(vn($frm['id_marque'])) . "'";
	$product_fields[] = "on_rupture = '" . intval(vn($frm['on_rupture'])) . "'";
	$product_fields[] = "id_ecotaxe = '" . intval(vn($frm['id_ecotaxe'])) . "'";
	$product_fields[] = "id_utilisateur = '" . intval(vn($frm['id_utilisateur'])) . "'";
	$product_fields[] = "position = '" . intval($frm['position']) . "'";
	$product_fields[] = "on_ref_produit = '" . intval(vn($frm['on_ref_produit'])) . "'";
	$product_fields[] = "nb_ref_produits = '" . intval($frm['nb_ref_produits']) . "'";
	$product_fields[] = "display_price_by_weight = '" . nohtml_real_escape_string($frm['display_price_by_weight']) . "'";
	$product_fields[] = "volume = '" . nohtml_real_escape_string($frm['volume']) . "'";
	$product_fields[] = "on_estimate = '" . nohtml_real_escape_string(vn($frm['on_estimate'])) . "'";
	$product_fields[] = "recommanded_product_on_cart_page = '" . nohtml_real_escape_string(vn($frm['recommanded_product_on_cart_page'])) . "'";
	$product_fields[] = "default_color_id = '" . intval(vn($frm['default_color_id'])) . "'";
	$product_fields[] = "allow_add_product_with_no_stock_in_cart = '" . intval(vn($frm['allow_add_product_with_no_stock_in_cart'])) . "'";
	$product_fields[] = "site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value(vb($frm['site_id']))) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$product_fields[] = "site_country = '" . real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		if(empty($GLOBALS['site_parameters']['product_name_forced_lang']) || $lng == $GLOBALS['site_parameters']['product_name_forced_lang']) {
			$product_fields[] = "nom_" . $lng . " = '" . nohtml_real_escape_string($frm['nom_' . $lng]) . "'";
		}
		if(empty($GLOBALS['site_parameters']['product_description_forced_lang']) || $lng == $GLOBALS['site_parameters']['product_description_forced_lang']) {
			$product_fields[] = "description_" . $lng . " = '" . real_escape_string($frm['description_' . $lng]) . "'";
		}
		$product_fields[] = "descriptif_" . $lng . " = '" . real_escape_string($frm['descriptif_' . $lng]) . "'";
		$product_fields[] = "meta_titre_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'";
		$product_fields[] = "meta_key_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'";
		$product_fields[] = "meta_desc_" . $lng . " = '" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}
	if (check_if_module_active('flash')) {
		$product_fields[] = "prix_flash = '" . nohtml_real_escape_string($prix_flash) . "'";
		$product_fields[] = "on_flash = '" . nohtml_real_escape_string(vn($frm['on_flash'])) . "'";
		$product_fields[] = "flash_start = '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['flash_start'])) . "'";
		$product_fields[] = "flash_end = '" . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['flash_end'])) . "'";
	}
	if (check_if_module_active('best_seller')) {
		$product_fields[] = "on_top = '" . nohtml_real_escape_string(vn($frm['on_top'])) . "'";
	}

	if (check_if_module_active('menus')) {
		$product_fields[] = "on_rollover = '" . nohtml_real_escape_string(vn($frm['on_rollover'])) . "'";
	}
	if (check_if_module_active('gifts')) {
		$product_fields[] = "points = '" . nohtml_real_escape_string($frm['points']) . "'";
		$product_fields[] = "on_gift = '" . nohtml_real_escape_string(vn($frm['on_gift'])) . "'";
		$product_fields[] = "on_gift_points = '" . nohtml_real_escape_string(vn($frm['on_gift_points'])) . "'";
	}
	if (check_if_module_active('gift_check')) {
		$product_fields[] = "on_check = '" . nohtml_real_escape_string(vn($frm['on_check'])) . "'";
	}
	if (check_if_module_active('download')) {
		$product_fields[] = "zip = '" . nohtml_real_escape_string($frm['zip']) . "'";
		$product_fields[] = "on_download = '" . nohtml_real_escape_string(vn($frm['on_download'])) . "'";
	}
	if (check_if_module_active('conditionnement')) {
		$product_fields[] = "conditionnement = '" . intval($frm['conditionnement']) . "'";
		$product_fields[] = "unit_per_pallet = '" . intval($frm['unit_per_pallet']) . "'";
		$product_fields[] = "conditioning_text = '" . nohtml_real_escape_string($frm['conditioning_text']) . "'";
	}
	if(!empty($GLOBALS['site_parameters']['products_table_additionnal_fields'])) {
		foreach($GLOBALS['site_parameters']['products_table_additionnal_fields'] as $this_key => $this_value) {
			$product_fields[] = word_real_escape_string($this_key) . " = '" . nohtml_real_escape_string(vb($frm[$this_key])) . "'";
		}
	}
	$product_fields = get_table_field_names('peel_produits', null, false, $product_fields);
	$sql = "UPDATE peel_produits
		SET	" . implode(', ', $product_fields) . "
		WHERE id =" . intval($id) . "";
	query($sql);
	/* Efface toutes les catégories auxquelles le produit est associé */
	query("DELETE FROM peel_produits_categories WHERE produit_id = '" . intval($id) . "'");
	query("DELETE FROM peel_produits_references WHERE produit_id = '" . intval($id) . "'");
	query("DELETE FROM peel_produits_couleurs WHERE produit_id = '" . intval($id) . "'");
	query("DELETE FROM peel_produits_tailles WHERE produit_id = '" . intval($id) . "'");

	if (empty($frm['categories'])) {
		$frm['categories'][] = 0;
	}
	if (empty($frm['references'])) {
		$frm['references'][] = 0;
	}
	if (empty($frm['couleurs'])) {
		$frm['couleurs'][] = 0;
	}
	if (empty($frm['tailles'])) {
		$frm['tailles'][] = 0;
	}
	for ($i = 0; $i < count($frm['categories']); $i++) {
		query("INSERT INTO peel_produits_categories (categorie_id, produit_id)
			VALUES ('" . nohtml_real_escape_string($frm['categories'][$i]) . "', '" . intval($id) . "')");
	}
	
	for ($i = 0; $i < count(vn($frm['references'])); $i++) {
		if (!empty($frm['references'][$i])) {
			$query = query("SELECT reference_id
				FROM peel_produits_references
				WHERE produit_id = " . intval($id) ." AND reference_id = " . nohtml_real_escape_string($frm['references'][$i]));
			$result = fetch_assoc($query);
			if (empty($result)) {
				// Association de produit non présente.
				query("INSERT INTO peel_produits_references (reference_id, produit_id, quantity)
					VALUES ('" . nohtml_real_escape_string($frm['references'][$i]) . "', '" . intval($id) . "', '" . intval(vn($frm['quantity_product_reference'][$i])) . "')");
			}
		}
	}
	
	foreach($frm['couleurs'] as $this_color_id) {
		// On recupere chaque champ default_image par couleur
		query("INSERT INTO peel_produits_couleurs (couleur_id, produit_id, default_image)
			VALUES ('" . nohtml_real_escape_string($this_color_id) . "', '" . intval($id) . "','" . intval(vn($frm["default_image" . $this_color_id])) . "')");
		if(isset($_POST['default_image' . $this_color_id])) {
			// En cas de nouvelle association d'une couleur avec le produit, il ne peut pas y avoir d'ajout d'images pour cette nouvelle couleur.
			query("UPDATE peel_produits_couleurs
				SET default_image = '" . nohtml_real_escape_string($_POST['default_image' . $this_color_id]) . "'
				WHERE produit_id = '" . intval($id) . "' AND couleur_id ='" . intval($this_color_id) . "'");
		}
		for ($h = 1; $h <= $upload_images_per_color; $h++) {
			$this_field_name = 'imagecouleur' . $this_color_id . '_' . $h;
			$_POST[$this_field_name] = upload($this_field_name, false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($_POST[$this_field_name]));
			query("UPDATE peel_produits_couleurs
				SET image" . word_real_escape_string($h) . "='" . nohtml_real_escape_string($_POST[$this_field_name]) . "'
				WHERE produit_id='" . intval($id) . "' AND couleur_id='" . intval($this_color_id) . "'");
		}
	}

	for ($i = 0; $i < count($frm["tailles"]); $i++) {
		query("INSERT INTO peel_produits_tailles (taille_id, produit_id)
			VALUES ('" . nohtml_real_escape_string($frm["tailles"][$i]) . "', '" . intval($id) . "')");
	}
	if (check_if_module_active('stock_advanced') && $frm['on_stock'] == 1) {
		// Mise à jour des stocks des tailles
		if (!empty($frm['tailles'])) {
			query('DELETE FROM peel_stocks WHERE produit_id="' . intval($id) . '" AND taille_id NOT IN("' . implode('","', nohtml_real_escape_string($frm['tailles'])) . '");');
			query('DELETE FROM peel_stocks_temp WHERE produit_id="' . intval($id) . '" AND taille_id NOT IN("' . implode('","', nohtml_real_escape_string($frm['tailles'])) . '");');
		}
		// Mise à jour des stocks des couleurs
		if (!empty($frm['couleurs'])) {
			query('DELETE FROM peel_stocks WHERE produit_id="' . intval($id) . '" AND couleur_id NOT IN("' . implode('","', nohtml_real_escape_string($frm['couleurs'])) . '");');
			query('DELETE FROM peel_stocks_temp WHERE produit_id="' . intval($id) . '" AND couleur_id NOT IN("' . implode('","', nohtml_real_escape_string($frm['couleurs'])) . '");');
		}
		insert_product_in_stock_table_if_not_exist($id, vn($frm['on_stock']));
	}
	if(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])) {
		$product_name = vb($frm['nom_' . $GLOBALS['site_parameters']['product_name_forced_lang']]);
	} else {
		$product_name = vb($frm['nom_' . $_SESSION['session_langue']]);
	}
	return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_PRODUITS_MSG_PRODUCT_UPDATE_OK'], StringMb::html_entity_decode_if_needed($product_name))))->fetch();
}

/**
 * affiche_liste_produits_fournisseur()
 *
 * @return
 */
function affiche_liste_produits_fournisseur()
{
	$supplier = get_user_information(vb($_GET['id_utilisateur']));
	
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_produits_fournisseur.tpl');
	$tpl->assign('societe', $supplier['societe']);
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', $GLOBALS['administrer_url'] . '/produits.php?mode=ajout');

	$sql = "SELECT ";
	if (check_if_module_active('gifts')) {
		$sql .= "p.points, p.on_gift, p.on_gift_points, ";
	}
	$sql .= "p.id, p.reference, p.reference_fournisseur, p.etat_stock, p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS name, p.id_utilisateur, p.prix, p.etat, p.date_maj, p.on_stock
		FROM peel_produits p
		WHERE p.id_utilisateur = '" . intval($_GET['id_utilisateur']) . "' AND " . get_filter_site_cond('produits', 'p', true) . "
		ORDER BY p.id ASC";
	$Links = new Multipage($sql, 'produits');
	$results_array = $Links->Query();
	if (!empty($results_array)) {
		$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);
		$tpl->assign('ttc_ht', (display_prices_with_taxes_in_admin() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
		$tpl->assign('is_gifts_module_active', check_if_module_active('gifts'));
		$tpl->assign('is_stock_advanced_module_active', check_if_module_active('stock_advanced'));
		$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');

		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $ligne) {
			$tpl_cats = array();
			$sqlCAT = "SELECT id, nom_" . $_SESSION['session_langue'] . "
				FROM peel_produits_categories pc
				INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
				WHERE pc.produit_id = " . intval($ligne['id']) . "";
			$resCAT = query($sqlCAT);
			if (num_rows($resCAT) > 0) {
				while ($cat = fetch_assoc($resCAT)) {
					$tpl_cats[] = $cat['nom_' . $_SESSION['session_langue']];
				}
			}

			$tpl_util = null;
			if ($this_user = get_user_information($ligne['id_utilisateur'])) {
				$tpl_util = array('href' => $GLOBALS['administrer_url'] . "/utilisateurs.php?mode=modif&id_utilisateur=" . $this_user['id_utilisateur'],
					'societe' => $this_user['societe']
					);
			}
			$tmpLigne = array('tr_rollover' => tr_rollover($i, true),
				'nom' => (!empty($ligne['name'])?$ligne['name']:'['.$ligne['id'].']'),
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'] . '&page=' . (!empty($_GET['page']) ? $_GET['page'] : 1),
				'reference' => $ligne['reference'],
				'reference_fournisseur' => $ligne['reference_fournisseur'],
				'cats' => $tpl_cats,
				'site_name' => get_site_name($cat['site_id']),
				'prix' => fprix((display_prices_with_taxes_in_admin() ? $ligne['prix'] : $ligne['prix'] / (1 + $ligne['tva'] / 100)), false, $GLOBALS['site_parameters']['code'], false),
				'etat_onclick' => 'change_status("produits", "' . $ligne['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (empty($ligne['etat']) ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'on_stock' => $ligne['on_stock'],
				'stock_href' => get_current_url(false) . '?mode=stock&id=' . $ligne['id'],
				'stock_src' => $GLOBALS['administrer_url'] . '/images/stock.gif',
				'points' => $ligne['points'],
				'date' => get_formatted_date($ligne['date_maj']),
				'util' => $tpl_util
				);
			if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$tmpLigne['site_country'] = get_country_name($ligne['site_country']);
			}
			$tpl_results[] = $tmpLigne;
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('STR_ADMIN_PRODUITS_SUPPLIER_PRODUCTS', $GLOBALS['STR_ADMIN_PRODUITS_SUPPLIER_PRODUCTS']);
	$tpl->assign('STR_ADMIN_CATEGORIES_ADD_PRODUCT', $GLOBALS['STR_ADMIN_CATEGORIES_ADD_PRODUCT']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_REFERENCE', $GLOBALS['STR_REFERENCE']);
	$tpl->assign('STR_REFERENCE_FOURNISSEUR', $GLOBALS['STR_REFERENCE_FOURNISSEUR']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_PRICE', $GLOBALS['STR_PRICE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_STOCK', $GLOBALS['STR_STOCK']);
	$tpl->assign('STR_GIFT_POINTS', $GLOBALS['STR_GIFT_POINTS']);
	$tpl->assign('STR_ADMIN_UPDATED_DATE', $GLOBALS['STR_ADMIN_UPDATED_DATE']);
	$tpl->assign('STR_ADMIN_PRODUITS_SUPPLIER', $GLOBALS['STR_ADMIN_PRODUITS_SUPPLIER']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);
	$tpl->assign('STR_ADMIN_PRODUITS_UPDATE', $GLOBALS['STR_ADMIN_PRODUITS_UPDATE']);
	$tpl->assign('STR_ADMIN_PRODUITS_MANAGE_STOCKS', $GLOBALS['STR_ADMIN_PRODUITS_MANAGE_STOCKS']);
	$tpl->assign('STR_ADMIN_PRODUITS_NOTHING_FOUND', $GLOBALS['STR_ADMIN_PRODUITS_NOTHING_FOUND']);
	return $tpl->fetch();
}

/**
 * maj_tab()
 *
 * @param mixed $frm
 * @return
 */
function maj_tab($frm)
{
	$product_fields = array("display_tab = '" . intval($frm['display_tab']) . "'", "tab1_html_" . $frm['lng'] . " = '" . real_escape_string($frm['tab1_html_' . $frm['lng']]) . "'", "tab2_html_" . $frm['lng'] . " = '" . real_escape_string($frm['tab2_html_' . $frm['lng']]) . "'", "tab3_html_" . $frm['lng'] . " = '" . real_escape_string($frm['tab3_html_' . $frm['lng']]) . "'", "tab4_html_" . $frm['lng'] . " = '" . real_escape_string($frm['tab4_html_' . $frm['lng']]) . "'", "tab5_html_" . $frm['lng'] . " = '" . real_escape_string($frm['tab5_html_' . $frm['lng']]) . "'", "tab6_html_" . $frm['lng'] . " = '" . real_escape_string($frm['tab6_html_' . $frm['lng']]) . "'", "tab1_title_" . $frm['lng'] . " = '" . nohtml_real_escape_string($frm['tab1_title_' . $frm['lng']]) . "'", "tab2_title_" . $frm['lng'] . " = '" . nohtml_real_escape_string($frm['tab2_title_' . $frm['lng']]) . "'", "tab3_title_" . $frm['lng'] . " = '" . nohtml_real_escape_string($frm['tab3_title_' . $frm['lng']]) . "'", "tab4_title_" . $frm['lng'] . " = '" . nohtml_real_escape_string($frm['tab4_title_' . $frm['lng']]) . "'", "tab5_title_" . $frm['lng'] . " = '" . nohtml_real_escape_string($frm['tab5_title_' . $frm['lng']]) . "'", "tab6_title_" . $frm['lng'] . " = '" . nohtml_real_escape_string($frm['tab6_title_' . $frm['lng']]) . "'");
	$product_fields = get_table_field_names('peel_produits', null, false, $product_fields);
	if(!empty($product_fields)) {
		$sql = "UPDATE peel_produits SET
			" . implode(', ', $product_fields) . "
			WHERE id ='" . intval($frm['id']) . "' AND " . get_filter_site_cond('produits', null, true) . "";
		query($sql);
	}
}

/**
 * Charge les infos de la marques.
 *
 * @param integer $id
 * @param mixed $lng
 * @return
 */
function affiche_formulaire_modif_tab($id, $lng)
{
	$sql = "SELECT id, display_tab, nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS name";
	$sql .= ", tab1_html_" . $lng;
	$sql .= ", tab2_html_" . $lng;
	$sql .= ", tab3_html_" . $lng;
	$sql .= ", tab4_html_" . $lng;
	$sql .= ", tab5_html_" . $lng;
	$sql .= ", tab6_html_" . $lng;
	$sql .= ", tab1_title_" . $lng;
	$sql .= ", tab2_title_" . $lng;
	$sql .= ", tab3_title_" . $lng;
	$sql .= ", tab4_title_" . $lng;
	$sql .= ", tab5_title_" . $lng;
	$sql .= ", tab6_title_" . $lng;
	$sql .= "
		FROM peel_produits
		WHERE id = " . intval($id) ." AND " . get_filter_site_cond('produits', null, true) . "";

	$qid = query($sql);
	if ($frm = fetch_assoc($qid)) {
		$frm["nouveau_mode"] = "maj_tab";
		$frm["titre_soumet"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		$product_name = $frm["name"];
		// Affiche la liste des marques, en présélectionnant la marques choisie.
		return affiche_formulaire_tab($frm, $form_error_object, $product_name, $lng);
	} else {
		return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_PRODUITS_ERR_NOT_FOUND']))->fetch();
	}
}

/**
 * affiche_formulaire_tab()
 *
 * @param mixed $frm
 * @param class $form_error_object
 * @param mixed $product_name
 * @param mixed $lng
 * @return
 */
function affiche_formulaire_tab(&$frm, &$form_error_object, $product_name, $lng)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_tab.tpl');
	$tpl->assign('action', get_current_url(false) . '?tab_lang=' . $lng . '&mode=modif');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('lng', $lng);
	$tpl->assign('product_name', $product_name);
	$tpl->assign('display_tab', vb($frm['display_tab']));
	$tpl->assign('tab1_title', vb($frm['tab1_title_' . $lng]));
	$tpl->assign('tab1_html_te', getTextEditor('tab1_html_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['tab1_html_' . $lng]))));
	$tpl->assign('tab2_title', vb($frm['tab2_title_' . $lng]));
	$tpl->assign('tab2_html_te', getTextEditor('tab2_html_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['tab2_html_' . $lng]))));
	$tpl->assign('tab3_title', vb($frm['tab3_title_' . $lng]));
	$tpl->assign('tab3_html_te', getTextEditor('tab3_html_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['tab3_html_' . $lng]))));
	$tpl->assign('tab4_title', vb($frm['tab4_title_' . $lng]));
	$tpl->assign('tab4_html_te', getTextEditor('tab4_html_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['tab4_html_' . $lng]))));
	$tpl->assign('tab5_title', vb($frm['tab5_title_' . $lng]));
	$tpl->assign('tab5_html_te', getTextEditor('tab5_html_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['tab5_html_' . $lng]))));
	$tpl->assign('tab6_title', vb($frm['tab6_title_' . $lng]));
	$tpl->assign('tab6_html_te', getTextEditor('tab6_html_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['tab6_html_' . $lng]))));
	$tpl->assign('titre_soumet', $frm["titre_soumet"]);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_PRODUITS_UPDATE_TABS_CONTENT', $GLOBALS['STR_ADMIN_PRODUITS_UPDATE_TABS_CONTENT']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_PRODUITS_TAB', $GLOBALS['STR_ADMIN_PRODUITS_TAB']);
	$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
	return $tpl->fetch();
}