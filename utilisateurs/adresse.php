<?php
// This file should be in UTF8 without BOM - Accents examples : éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0,  which is subject to an    |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: adresse.php 55332 2017-12-01 10:44:06Z sdelaporte $

include("../configuration.inc.php");
necessite_identification();
if(!empty($GLOBALS['site_parameters']['user_multiple_addresses_disable'])) {
	redirect_and_die(get_url('/'));
}

define("IN_ADRESSE", true);
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADDRESS_TEXT'];
$output = '';

// En cas de changement d'adresse par défaut par l'utilisateur depuis le menu déroulant
$address_types_array = array('bill', 'ship');
foreach($address_types_array as $address_change_type) {
	if(!empty($_POST['personal_address_'. $address_change_type])) {
		$sql = "UPDATE peel_utilisateurs
			SET address_" . word_real_escape_string($address_change_type) . "_default='" . real_escape_string($_POST['personal_address_'.$address_change_type])."'
			WHERE id_utilisateur='" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'";
		query($sql);
		$_SESSION['session_utilisateur']["address_" . $address_change_type . "_default"] = $_POST['personal_address_'.$address_change_type];
	}
}
if(!empty($_POST['personal_address_ship'])) {
	if ($_SESSION['session_caddie']->count_products() > 0) {
		redirect_and_die(get_url('achat/caddie_affichage.php', array('func'=>'force_update')));
	}
}


switch (vb($_REQUEST['mode'])) {
	case 'create_new_address':
		$output .= '<h1>'.$GLOBALS['STR_REGISTER_ORDER_ADDRESS'].'</h1>
			' . get_address_form();
		break;
	
	case 'insert_address':
		if(insert_or_update_address($_POST)) {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_YOUR_NEW_ADDRESS_CREATE']))->fetch();
		} else {
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERROR_OCCURRED']))->fetch();
		}
		$output .= get_address_list($_SESSION['session_utilisateur']['id_utilisateur']);
		break;
		
	case 'suppr_address':
		// supprimer l'adresse dans PEEL_ADRESSES
		$output .= delete_address($_GET['id'], $_SESSION['session_utilisateur']['id_utilisateur']);
		$output .= get_address_list($_SESSION['session_utilisateur']['id_utilisateur']);
		break;
	
	case 'modif_address':
		// modifier l'adresse dans PEEL_ADRESSES
		$q = query('SELECT *
			FROM peel_adresses
			WHERE id = "' . intval($_GET['id']) . '"');
		if($result = fetch_assoc($q)) {
			$output .= get_address_form($result);
		}
		break;
		
	case 'update_address':
		insert_or_update_address($_POST);
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_YOUR_UPDATE_ADDRESS_CREATE']))->fetch();
		$output .= get_address_list($_SESSION['session_utilisateur']['id_utilisateur']);
		break;
	
	default :
		$output .= get_address_list($_SESSION['session_utilisateur']['id_utilisateur']);
		break;
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");
