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
// $Id: change_params.php 55428 2017-12-07 16:06:06Z sdelaporte $
define('IN_CHANGE_PARAMS', true);
include("../configuration.inc.php");
necessite_identification();

$GLOBALS['page_name'] = 'change_params';
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_CHANGE_PARAMS'];
$GLOBALS['allow_fineuploader_on_page'] = true;
$noticemsg_keep_form = '';
include($GLOBALS['dirroot']."/lib/fonctions/display_user_forms.php");

$form_error_object = new FormError();
// Dans un premier temps on stocke dans $mandatory_fields les champs obligatoires indiqués dans $GLOBALS['site_parameters']['user_change_mandatory_fields'] de préférence si défini, sinon par $GLOBALS['site_parameters']['user_mandatory_fields'] à défaut.
$mandatory_fields = array();
foreach(vb($GLOBALS['site_parameters']['user_change_mandatory_fields'], $GLOBALS['site_parameters']['user_mandatory_fields']) as $key => $value) {
	if(check_if_module_active('annonces')) {
		if($key == 'pseudo') {
			// Si le module d'annonce est présent, le pseudo n'est éditable donc pas transmit dans le formulaire. Le test sur le champ pseudo est prévu dans user*_change_mandatory_fields uniquement pour l'inscription dans ce cas.
			continue;
		}
	}
	if($key == 'code' || $key == 'mot_passe_confirm') {
		// il n'y a pas de module captcha dans le formulaire de mise à jour d'utilisateur. Le test sur le champ code est prévu dans user_mandatory_fields uniquement pour l'inscription
		continue;
	}
	$mandatory_fields[$key] = $value;
}

if (!empty($_SESSION['session_utilisateur']['user_type'])) {
	// Chargement des champs obligatoires pour un profil d'utilisateur
	foreach(vb($GLOBALS['site_parameters']['user_'.$_SESSION['session_utilisateur']['user_type'].'_change_mandatory_fields'], array()) as $key => $value) {
		$mandatory_fields[$key] = $value;
	}
}

// Dans un second temps on ajoute à cette variable les champs obligatoires qui doivent être vérifiés dans tous les cas, ou si des modules ou variables de configurations sont présents.
if(!empty($GLOBALS['site_parameters']['add_b2b_form_inputs'])) {
	$mandatory_fields['societe'] = 'STR_ERR_SOCIETY';
	$mandatory_fields['type'] = 'STR_ERR_YOU_ARE';
	$mandatory_fields['activity'] = 'STR_ERR_ACTIVITY';
	$mandatory_fields['siret'] = 'STR_ERR_SIREN';
}
if(check_if_module_active('annonces')) {
	if(vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'checkbox') {
		$mandatory_fields['id_categories'] = 'STR_ERR_FIRST_CHOICE';
	} elseif(vb($GLOBALS['site_parameters']['type_affichage_user_favorite_id_categories']) == 'select') {
		$mandatory_fields['id_cat_1'] = 'STR_ERR_FIRST_CHOICE';
	}
}
foreach($mandatory_fields as $key => $value) {
	// Transformation des valeurs du tableau avec les variables de langue du même nom
	if (strpos($value, 'STR_') === 0 && !empty($GLOBALS[$value])) {
		$mandatory_fields[$key] = $GLOBALS[$value];
	}
}
if (!empty($_GET['complete_account_message_display']) && !empty($GLOBALS['STR_COMPLETE_ACCOUNT_MESSAGE'])) {
	$noticemsg_keep_form .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_COMPLETE_ACCOUNT_MESSAGE'], 'box_dismiss_disable'=>true))->fetch();
}
$frm = $_POST;
/* Supprime l'image associée à l'utilisateur. */
switch (vb($_REQUEST['mode'])) {
	case "supprfile" :
		$id = intval(vn($_REQUEST['id']));
		$sql = "SELECT logo
			FROM peel_utilisateurs
			WHERE id_utilisateur = '" . intval($id)."'";
		$res = query($sql);
		$file = fetch_assoc($res);
		query("UPDATE peel_utilisateurs
			SET logo = ''
			WHERE id_utilisateur = '" . intval($id)."'");
		delete_uploaded_file_and_thumbs($file['logo']);
		$noticemsg_keep_form .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MSG_CHANGE_PARAMS'], $file['logo'])))->fetch();
		break;
}
if (a_priv('demo')) {
	$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
} elseif (!empty($frm)) {
	// D'abord on génère une erreur pour tous les champs obligatoires qui sont vides
	$form_error_object->valide_form($frm, $mandatory_fields);

	if (check_if_module_active('abonnement') && is_user_verified($_SESSION['session_utilisateur']['id_utilisateur'])) {
		$disabled_verified_fields = true;
		$original_frm = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
		foreach(array('email', 'telephone', 'portable', 'fax', 'prenom', 'nom_famille', 'societe', 'siret', 'adresse', 'code_postal', 'ville', 'pays', 'intracom_for_billing') as $this_key) {
			// On complète les infos transmises par le formulaire avec ce qui est dans la BDD pour mettre à jour l'utilisateur
			$frm[$this_key] = vb($original_frm[$this_key]);
		}
	}
	/* Le formulaire a été soumis, vérifie si les infos de l'utilisateur sont correctes */
	if(!empty($frm['email'])) {
		if (function_exists('searchKeywordFiltersInMail')) {
			$add_mail_error = searchKeywordFiltersInMail($frm['email']);
			if ($add_mail_error) {
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL']);
			}
		}
		if (!$form_error_object->has_error('email')) {
			if (!EmailOK($frm['email'])) {
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_BAD']);
			} elseif ((num_rows(query("SELECT 1
				FROM peel_utilisateurs
				WHERE id_utilisateur!='" . intval($frm['id_utilisateur']) . "' AND email = '" . nohtml_real_escape_string($frm['email']) . "' AND priv NOT IN ('" . implode("','", $GLOBALS['disable_login_by_privilege']) . "') AND " . get_filter_site_cond('utilisateurs'))) > 0)) {
				// Test de l'unicité de l'email, sauf pour les utilisateurs n'étant pas inscrit via le téléchargement.
				$form_error_object->add('email', $GLOBALS['STR_ERR_EMAIL_STILL']);
			}
		}
	}
	if(!empty($frm['pseudo'])) {
		$add_pseudo_error = (StringMb::strpos($frm['pseudo'], '@') !== false);
		if (function_exists('searchKeywordFiltersInLogin')) {
			$add_pseudo_error = ($add_pseudo_error || searchKeywordFiltersInLogin($frm['pseudo'])) ;
		}
		if ($add_pseudo_error) {
			$form_error_object->add('pseudo', $GLOBALS['STR_ERR_PSEUDO']);
		} elseif ((num_rows(query("SELECT 1
				FROM peel_utilisateurs
				WHERE id_utilisateur!='" . intval($frm['id_utilisateur']) . "' AND pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "' AND " . get_filter_site_cond('utilisateurs') . "")) > 0)) {
			$form_error_object->add('pseudo', $GLOBALS['STR_ERR_NICKNAME_STILL']);
		}
	}
	if(!empty($frm['token'])) {
		if (!verify_token('change_params', 120, false)) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
	}
	if (!empty($frm['siret']) && vb($frm['pays']) == 1 && !preg_match("#([0-9]){9,14}#", str_replace(array(' ', '.'), '', $frm['siret']))) {
		// Si nous sommes en France, nous avons renseigné le numéro $GLOBALS['STR_SIREN'], cela nécessite un contrôle de la valeur rentrée par l'utilisateur
		$form_error_object->add('siret', $GLOBALS['STR_ERR_SIREN']);
	}
	if (!empty($GLOBALS['site_parameters']['user_tva_intracom_validation_on_change_params_page']) && check_if_module_active('vatlayer') && !empty($frm['intracom_for_billing']) && !vatlayer_check_vat($frm['intracom_for_billing'])) {
		$form_error_object->add('intracom_for_billing', $GLOBALS['STR_MODULE_VATLAYER_ERR_INTRACOM']);
	}
	if (!$form_error_object->count()) {
		$noticemsg = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_MSG_CHANGE_PARAMS'], 'list_content' => $GLOBALS['STR_CHANGE_PARAMS_OK']))->fetch();
		
		if (!empty($GLOBALS['site_parameters']['newsletter_and_commercial_double_optin_validation']) && ((!empty($frm['newsletter']) && empty($_SESSION['session_utilisateur']['newsletter'])) || (!empty($frm['commercial']) && empty($_SESSION['session_utilisateur']['commercial'])))) {
			$noticemsg .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_REGISTER_NEWLSETTER_COMMERCIAL_YES']))->fetch();
		}

		$frm['logo'] = upload('logo', false, 'any', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['logo']));
		if (!empty($frm['dream_societe_kbis'])) {
			$frm['dream_societe_kbis'] = upload('dream_societe_kbis', false, 'dream_societe_kbis', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['logo']));
			if (StringMb::strpos(vb($_POST['dream_societe_kbis']), '/cache/') === 0) {
				// Comme il y a /cache/ dans le POST, cela veut dire que l'utilisateur a uploadé un nouveau fichier pour ce champ. Dans le cas contraire on a juste le nom du document, sans /cache/.
				// Donc on rempli une variable qui indique quel fichier a été mis à jour par l'utilisateur.
				$_SESSION['document_updated_by_user']['dream_societe_kbis'] = $frm['dream_societe_kbis'];
			}
		}
		if (!empty($frm['societe']) && !empty($frm['naissance_company'])) {
			$frm['naissance'] = $frm['naissance_company'];
		}
		$frm['priv'] = $_SESSION['session_utilisateur']['priv'];
		maj_utilisateur($frm, true);

	}
} else {
	$frm = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
}

if (empty($noticemsg)) {
	if (!empty($frm['naissance'])) {
		$frm['naissance_company'] = $frm['naissance'];
	}
	$output = vb($noticemsg_keep_form) . get_user_change_params_form($frm, $form_error_object, $mandatory_fields);
} else {
	$output = $noticemsg;
}

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");