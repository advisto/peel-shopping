<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: achat_maintenant.php 48447 2016-01-11 08:40:08Z sdelaporte $
include("../configuration.inc.php");

$output = '';
if (empty($GLOBALS['site_parameters']['unsubscribe_order_process'])) {
	// Test sur l'identification, il faut obligatoirement être connecté à son compte pour renseigner un code promo. Les utilisateurs 'stop' (attente revendeur) ou 'stand' (attente affiliation) ne peuvent pas se connecter à leur compte, ne peuvent donc pas passer commande et ne bénéficient donc pas des avantages liés au statut final 'reve' (revendeur confirmé) ou 'affi' (affilié confirmé). Les utilisateurs 'load' (téléchargement) ou 'newsletter' (abonné newsletter) ne peuvent pas se connecter, et donc ne peuvent pas non plus passer commande.
	necessite_identification();
}
include("../lib/fonctions/display_caddie.php");
if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
	$user_table_fields_names = get_table_field_names('peel_utilisateurs');
	$order_table_fields_names = get_table_field_names('peel_commandes');
}
if (check_if_module_active('socolissimo') && !empty($_REQUEST) && !empty($_REQUEST['PUDOFOID']) && !empty($_REQUEST['CEEMAIL']) && !empty($_REQUEST['SIGNATURE']) && !empty($_REQUEST['ORDERID'])) {
	// On veut vérifier s'il y a eu passage par la page SO de SoColissimo
	put_session_commande_from_so_page();
	$_SESSION['session_caddie']->update();
} elseif (!empty($_GET['shortkpid'])){
	put_session_commande_from_kiala_page($_GET);
} elseif (!empty($_POST)) {
	put_session_commande($_POST);
	if(empty($GLOBALS['site_parameters']['user_multiple_addresses_disable'])) {
		// En cas de changement d'adresse par l'utilisateur depuis le menu déroulant (fonction get_personal_address_form)
		if(vb($_POST['personal_address_bill']) == 'manage' || vb($_POST['personal_address_ship']) == 'manage') {
			redirect_and_die(get_url('/utilisateurs/adresse.php'));
		}
		// On teste si l'utilisateur veut préremplir une adresse à partir d'une adresse déjà enregistrée
		if(!empty($_POST['personal_address_bill'])) {
			$address_change_type = 'bill';
		} elseif(!empty($_POST['personal_address_ship'])) {
			$address_change_type = 'ship';
		}
	}
	if(empty($address_change_type)) {
		// On traite le formulaire
		if (!isset($form_error_object)) {
			$form_error_object = new FormError();
		}
		$check_fields = array('nom1' => $GLOBALS['STR_ERR_NAME'],
			'prenom1' => $GLOBALS['STR_ERR_FIRSTNAME'],
			'contact1' => $GLOBALS['STR_ERR_TEL'],
			'email1' => $GLOBALS['STR_ERR_EMAIL'],
			'adresse1' => $GLOBALS['STR_ERR_ADDRESS'],
			'code_postal1' => $GLOBALS['STR_ERR_ZIP'],
			'ville1' => $GLOBALS['STR_ERR_TOWN'],
			'cgv' => $GLOBALS['STR_ERR_CGV']);
		// Le moyen de paiement n'est pas sélectionnable si la commande est égal à 0
		if ($_SESSION['session_caddie']->total > 0) {
			$check_fields['payment_technical_code'] = $GLOBALS['STR_ERR_PAYMENT'];
		}
		if (!empty($GLOBALS['site_parameters']['mode_transport']) && is_delivery_address_necessary_for_delivery_type(vn($_SESSION['session_caddie']->typeId)) && (!check_if_module_active('socolissimo') || empty($_SESSION['session_commande']['is_socolissimo_order']))) {
			// Si l'on vient de So Colissimo, on ne veut pas, sur /achat/achat_maintenant.php, revérifier les infos de livraison
			$check_fields['nom2'] = $GLOBALS['STR_ERR_NAME'];
			$check_fields['prenom2'] = $GLOBALS['STR_ERR_FIRSTNAME'];
			$check_fields['contact2'] = $GLOBALS['STR_ERR_TEL'];
			$check_fields['email2'] = $GLOBALS['STR_ERR_EMAIL'];
			$check_fields['adresse2'] = $GLOBALS['STR_ERR_ADDRESS'];
			$check_fields['code_postal2'] = $GLOBALS['STR_ERR_ZIP'];
			$check_fields['ville2'] = $GLOBALS['STR_ERR_TOWN'];
			$q_check_country_to_zone = query('SELECT zone
				FROM peel_pays
				WHERE pays_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string(vb($_SESSION['session_commande']['pays2'])) . '" AND ' . get_filter_site_cond('pays'));
			if ($r_check_country_to_zone = fetch_assoc($q_check_country_to_zone)) {
				if ($r_check_country_to_zone['zone'] != $_SESSION['session_caddie']->zoneId) {
					$form_error_object->add('pays2', $GLOBALS['STR_ERR_INFO_NEEDED_TO_CADDIE']);
				}
			}
			if (!empty($GLOBALS['site_parameters']['order_mandatory_fields'])) {
				$check_fields = array_merge($check_fields, $GLOBALS['site_parameters']['order_mandatory_fields']);
			}
		}
		$form_error_object->valide_form($_SESSION['session_commande'], $check_fields);
		$_SESSION['session_caddie']->set_paiement($_POST['payment_technical_code']);
		$_SESSION['session_caddie']->update();

		if (!$form_error_object->count()) {
			// Pas d'erreur dans le formulaire de commande.
			if (!empty($GLOBALS['site_parameters']['register_during_order_process']) && !empty($_POST['register_during_order_process'])) {
				// Création du compte en fonction du paramètrage et du souhait de l'utilisateur.
				// Conversion des données fournies dans le formulaire d'adresse de facturation avant de transmettre à la fonction de création d'utilisateur
				$frm['societe'] = vb($_SESSION['session_commande']['societe1']);
				$frm['nom_famille'] = vb($_SESSION['session_commande']['nom1']);
				$frm['prenom'] = vb($_SESSION['session_commande']['prenom1']);
				$frm['telephone'] = vb($_SESSION['session_commande']['contact1']);
				$frm['adresse'] = vb($_SESSION['session_commande']['adresse1']);
				$frm['code_postal'] = vb($_SESSION['session_commande']['code_postal1']);
				$frm['ville'] = vb($_SESSION['session_commande']['ville1']);
				$frm['pays'] = vb($_SESSION['session_commande']['pays1']);
				$frm['email'] = vb($_SESSION['session_commande']['email1']);
				if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
					foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
						if ((String::substr($this_field, -5) == '_bill') && !empty($_SESSION['session_commande'][$this_field]) && in_array($this_field, $order_table_fields_names)) {
							// On a ajouté dans la table utilisateurs un champ qui concerne l'adresse de facturation => Il faut préremplir les champs du formulaire d'adresse de facturation avec ces infos.
							$frm[String::substr($this_field, 0, -5).'1'] = vb($_SESSION['session_commande'][$this_field]);
							// Il faut définir $frm[$this_field] aussi, pour permettre la récupération de la valeur par get_specific_field_infos
							$frm[$this_field] = vb($_SESSION['session_commande'][$this_field]);
						}
					}
				}
				insere_utilisateur($frm, false, true);
				user_login_now($frm['email'], '', false);
			}
			define("IN_STEP2", true);
			$GLOBALS['DOC_TITLE'] =  $GLOBALS['STR_STEP2'];
		}
	} else {
		// Sauvegarde de l'id de l'adresse choisie par l'utilisateur.
		$_SESSION['session_commande']['personal_address_'.$address_change_type] = $_POST['personal_address_'.$address_change_type];
	}
} elseif (check_if_module_active('socolissimo') && !empty($_SESSION['session_commande']['is_socolissimo_order'])) {
	if (!PEEL_SOCOLISSIMO_IFRAME && empty($_REQUEST['PUDOFOID']) && empty($_SESSION['session_commande']['client2'])) {
		// On a le module So Colissimo activé, et la commande est liée auprocess SoColissimo ---> On ne veut accéder à achat_maintenant que si on vient de la page SO, pour être certain que cette dernière est bien incluse dans le process de commande
		redirect_and_die(get_url('caddie_affichage'));
	}
}

// Chargement des informations d'adresse par défaut ou si changement demandé par l'utilisateur
foreach(array('bill' => 1, 'ship' => 2) as $address_type => $session_commande_address_id) {
	if(!empty($address_change_type) && $address_type == $address_change_type && !empty($_POST['personal_address_'.$address_change_type])) {
		$this_new_address =  vb($_POST['personal_address_'.$address_change_type]);
	} elseif(empty($_SESSION['session_commande']['adresse' . $session_commande_address_id])) {
		$utilisateur = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
		if (!empty($utilisateur['address_' . $address_type . '_default'])) {
			$this_new_address = $utilisateur['address_' . $address_type . '_default'];
		} else {
			$this_new_address = 'original_address';
		}
	} elseif(empty($_GET) && empty($_POST) && !empty($_SESSION['session_utilisateur']["address_" . $address_type . "_default"])) {
		// Chargement de la configuration par défaut choisie depuis la page utilisateurs/adresse.php, si aucune valeur n'est envoyées en POST.
		$this_new_address = vb($_SESSION['session_utilisateur']['address_' . $address_type . "_default"]);
	} else {
		continue;
	}
	// On va remplir l'adresse
	if($this_new_address == 'original_address') {
		// Si l'utilisateur utilise l'adresse dans peel_utilisateur, qu'il a remplie lors de la création de son compte.
		$where = 'id_utilisateur = "'.intval($_SESSION['session_utilisateur']['id_utilisateur']).'"';
		$table_to_use = 'peel_utilisateurs';
	} else {
		// Adresse renseignée depuis la page de création d'adresse utilisateurs/adresse.php
		$where = 'id="'.intval($this_new_address).'"';
		$table_to_use = 'peel_adresses';
	}
	// Recherche des informations de l'adresse choisie
	$sql = 'SELECT civilite, prenom, nom_famille AS nom, societe, IF(portable!="", portable, telephone) AS contact, adresse, code_postal, ville, pays, email 
		FROM ' . word_real_escape_string($table_to_use) . ' 
		WHERE ' . $where;
	$q = query($sql);
	if($result = fetch_assoc($q)) {
		foreach($result as $key => $value) {
			if (!empty($value)) {
				// Si la valeur existe, on remplit la session avec ce qui vient de la base de donnée.
				if($key=='pays') {
					// l'id est stockée en BDD, et c'est le nom qui est utilisé dans ce formulaire.
					if (empty($value)) {
						$value = vn($GLOBALS['site_parameters']['default_country_id']);
					}
					$value = get_country_name($value);
				}
				$_SESSION['session_commande'][$key . $session_commande_address_id] = $value;
			}
		}
	}
}

// Adresse de facturation :
// Pour un mode de livraison rattaché ou non à SoColissimo : elle est préremplie en STEP 1 avec les infos du compte utilisateur
// En STEP 2, on la récupere après traitement du formulaire dans $_SESSION['session_commande']

$frm['societe1'] = vb($_SESSION['session_commande']['societe1']);
$frm['nom1'] = vb($_SESSION['session_commande']['nom1']);
$frm['prenom1'] = vb($_SESSION['session_commande']['prenom1']);
$frm['contact1'] = vb($_SESSION['session_commande']['contact1']);
$frm['adresse1'] = vb($_SESSION['session_commande']['adresse1']);
$frm['code_postal1'] = vb($_SESSION['session_commande']['code_postal1']);
$frm['ville1'] = vb($_SESSION['session_commande']['ville1']);
$frm['pays1'] = vb($_SESSION['session_commande']['pays1']);
$frm['email1'] = vb($_SESSION['session_commande']['email1']);
if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
	foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
		if ((String::substr($this_field, -5) == '_bill') && !empty($_SESSION['session_commande'][$this_field]) && in_array($this_field, $order_table_fields_names)) {
			// On a ajouté dans la table utilisateurs un champ qui concerne l'adresse de facturation => Il faut préremplir les champs du formulaire d'adresse de facturation avec ces infos.
			$frm[String::substr($this_field, 0, -5).'1'] = vb($_SESSION['session_commande'][$this_field]);
			// Il faut définir $frm[$this_field] aussi, pour permettre la récupération de la valeur par get_specific_field_infos
			$frm[$this_field] = vb($_SESSION['session_commande'][$this_field]);
		} else {
			// Autre champ de peel_commandes
			$frm[$this_field] = vb($_SESSION['session_commande'][$this_field]);
		}
	}
}

// Adresse de Livraison :
if (!empty($GLOBALS['site_parameters']['mode_transport']) && is_delivery_address_necessary_for_delivery_type(vn($_SESSION['session_caddie']->typeId))) {
	// - Pour un mode de livraison non rattaché à SO Colissimo : elle est préremplie en STEP 1 avec les infos du compte utilisateur
	// - Pour un mode de livraison rattaché à SO Colissimo, la page SO vient avant STEP 1, et on a donc déjà saisi et validé l'adresse de livraison. ---> les infos sont dans $_SESSION['session_commande']
	$frm['societe2'] = vb($_SESSION['session_commande']['societe2']);
	$frm['nom2'] = vb($_SESSION['session_commande']['nom2']);
	$frm['prenom2'] = vb($_SESSION['session_commande']['prenom2']);
	$frm['contact2'] = vb($_SESSION['session_commande']['contact2']);
	$frm['adresse2'] = vb($_SESSION['session_commande']['adresse2']);
	$frm['code_postal2'] = vb($_SESSION['session_commande']['code_postal2']);
	$frm['ville2'] = vb($_SESSION['session_commande']['ville2']);
	$frm['pays2'] = vb($_SESSION['session_commande']['pays2']);
	$frm['email2'] = vb($_SESSION['session_commande']['email2']);

	if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
		foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
			if ((String::substr($this_field, -5) == '_ship') && !empty($_SESSION['session_commande'][String::substr($this_field, 0, -5).'2']) && in_array($this_field, $order_table_fields_names)) {
				// la session commande contient un champ qui concerne l'adresse de livraison => Il faut préremplir les champs du formulaire d'adresse de livraison avec ces infos.
				$frm[String::substr($this_field, 0, -5).'2'] = vb($_SESSION['session_commande'][String::substr($this_field, 0, -5).'2']);
				// Il faut définir $frm[$this_field] aussi, pour permettre la récupération de la valeur par get_specific_field_infos
				$frm[$this_field] = vb($_SESSION['session_commande'][$this_field]);
			}
		}
	}
}

// Autres informations
$frm['commande_interne'] = vb($_POST['commande_interne'], vb($_SESSION['session_commande']['commande_interne']));
$frm['commentaires'] = vb($_POST['commentaires'], vb($_SESSION['session_commande']['commentaires']));
$frm['cgv'] = vb($_POST['cgv'], vb($_SESSION['session_commande']['cgv']));


$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['achat_maintenant_page_columns_count'];
if (!empty($GLOBALS['site_parameters']['mode_transport']) && (empty($_SESSION['session_caddie']->zoneId) || empty($_SESSION['session_caddie']->typeId))) {
	define('IN_CADDIE', true);
	$GLOBALS['DOC_TITLE'] =  $GLOBALS['STR_CADDIE'];
	$tpl = $GLOBALS['tplEngine']->createTemplate('global_error.tpl');
	$tpl->assign('message', $GLOBALS['STR_ERR_INFO_NEEDED_TO_CADDIE']);
	$tpl->assign('link', array('href' => get_url('/achat/'),
			'value' => $GLOBALS['STR_ORDER_PROCESS_CONTINUE']
			));
	$output .= $tpl->fetch();
} else {
	if (!defined('IN_STEP2')) {
		define("IN_STEP1", true);
		$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_STEP1'];
	}
	if (!empty($GLOBALS['site_parameters']['short_order_process'])) {
		if ($_SESSION['session_caddie']->count_products() > 0) {
			// Fin du process de commande, si le paramètre short_order_process est actif. Ce paramètre implique l'absence de paiement et de validation des CGV => Utile pour des demandes de devis
			// on prend les informations préremplies automatiquement ci-dessus dans $frm pour mettre dans $_SESSION['session_commande'] sans être passé par POST
			if (!empty($GLOBALS['site_parameters']['user_specific_field_titles'])) {
				foreach($GLOBALS['site_parameters']['user_specific_field_titles'] as $this_field => $this_title) {
					if ((String::substr($this_field, -5) == '_bill') && !empty($utilisateur[$this_field]) && in_array($this_field, $user_table_fields_names)) {
						// On a ajouté dans la table utilisateurs un champ qui concerne l'adresse de livraison => Il faut préremplir les champs du formulaire d'adresse de facturation avec ces infos.
						$frm[String::substr($this_field, 0, -5).'1'] = vb($utilisateur[$this_field]);
						// Il faut définir $frm[$this_field] aussi, pour permettre la récupération de la valeur par get_specific_field_infos
						$frm[$this_field] = vb($utilisateur[$this_field]);
					}
				}
			}
			put_session_commande($frm);
			$commandeid = $_SESSION['session_caddie']->save_in_database($_SESSION['session_commande']);

			// Le caddie est réinitialisé pour ne pas laisser le client passer une deuxième commande en soumettant une deuxième fois le formulaire
			$_SESSION['session_caddie']->init();
			$output .= affiche_contenu_html('short_order_process', true);
			$output .= get_order_step3($commandeid);
		} else {
			redirect_and_die(get_url('/'));
		}
	} elseif (!defined('IN_STEP2')) {
		if (check_if_module_active('socolissimo') && !empty($_SESSION['session_commande']['is_socolissimo_order']) && PEEL_SOCOLISSIMO_IFRAME && empty($_REQUEST['PUDOFOID']) && empty($_SESSION['session_commande']['client2'])) {
			// On a le module So Colissimo activé, et la commande est liée auprocess SoColissimo
			// On est en mode iframe pour SO Colissimo
			$output .= '<iframe id="SOLivraison" name="SOLivraison" width="100%" height="800" src="' . $GLOBALS['wwwroot'] . '/modules/socolissimo/iframe.php"></iframe>';
		} elseif (check_if_module_active('kiala') && is_type_linked_to_kiala($_SESSION['session_caddie']->typeId) && empty($_SESSION['session_commande']['client2']) && empty($_SESSION['session_commande']['is_kiala_order'])) {
			$output .= getKialaForm($_SESSION['session_utilisateur'], $_SESSION['session_caddie']);
		} else {
			if (!isset($form_error_object)) {
				$form_error_object = new FormError();
			}
			$output .= get_order_step1($frm, $form_error_object, $GLOBALS['site_parameters']['mode_transport']);
		}
	} else {
		$output .= get_order_step2($frm, $GLOBALS['site_parameters']['mode_transport']);
	}
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

