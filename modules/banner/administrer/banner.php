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
// $Id: banner.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../../../configuration.inc.php");
necessite_identification();
necessite_priv("admin_content");

$GLOBALS['DOC_TITLE'] = $GLOBALS["STR_ADMIN_MENU_CONTENT_BANNERS"];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$start = intval(vn($_GET['start'])); // Détermine la variable start (début de page)
$id = intval(vn($_REQUEST['id']));
$frm = $_POST;
$form_error_object = new FormError();

if (check_if_module_active('banner')) {
    switch (vb($_REQUEST['mode'])) {
        case "ajout" :
            if (!isset($categorie_id)) {
                $categorie_id = 0;
            }
            affiche_formulaire_ajout_banniere($categorie_id, $frm);
            break;

        case "modif" :
			$qid = query("SELECT *
				FROM peel_banniere
				WHERE id = " . intval($id) . " AND " .  get_filter_site_cond('banniere', null, true));
			if($frm = fetch_assoc($qid)) {
				affiche_formulaire_modif_banniere($id, $frm);
			}else{
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_ADMIN_ERR_NOT_FOUND"]))->fetch();
			}
            break;

        case "suppr" :
            supprime_banniere($id);
			affiche_filtre_banner($frm);
            affiche_liste_banniere();
            break;

        case "insere" :
			$form_error_object->valide_form($frm,
			array('date_debut' => $GLOBALS['STR_MODULE_BANNER_MSG_ERR_DATE'],
				'date_fin' => $GLOBALS['STR_MODULE_BANNER_MSG_ERR_DATE'],
				));
			if (!$form_error_object->count()) {
				$frm['image'] = upload('image', false, 'image_or_swf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image']));
				insere_banniere($frm);
				affiche_filtre_banner($frm);
				affiche_liste_banniere();
			}else{
				if (!isset($categorie_id)) {
					$categorie_id = 0;
				}
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_BANNER_MSG_ERR_DATE']))->fetch();
				affiche_formulaire_ajout_banniere($categorie_id, $frm);
			}
            break;

        case "maj" :
			$form_error_object->valide_form($frm,
			array('date_debut' => $GLOBALS['STR_MODULE_BANNER_MSG_ERR_DATE'],
				'date_fin' => $GLOBALS['STR_MODULE_BANNER_MSG_ERR_DATE'],
				));
			if (((empty($frm['image'])) && empty($_FILES['image']['name'])) && empty($frm['tag_html'])) {
				$form_error_object->add('no_content','Il faut renseigner soit une image, soit un tag HTML.');
			}
			if (!$form_error_object->count()) {
				$frm['image'] = upload('image', false, 'image_or_swf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['image']));
				echo maj_banniere($id, $frm);
				affiche_filtre_banner($frm);
				affiche_liste_banniere();
			} else {
				if ($form_error_object->has_error('date_debut')) {
					echo $form_error_object->text('date_debut') ;
				}
				if ($form_error_object->has_error('no_content')) {
					echo $form_error_object->text('no_content');
				}
				if ($form_error_object->has_error('date_fin')) {
					echo $form_error_object->text('date_fin');
				}
				affiche_formulaire_modif_banniere($id, $frm);
			}
            break;

        case "supprfile" :
            delete_banner_image(vn($_REQUEST['id']), $_GET['file']);
            affiche_formulaire_modif_banniere(vn($_REQUEST['id']), $frm);
            break;

		case "search":
			$inner = '';
			$cond = '';
			if(!empty($frm['categorie_banniere'])) {
				if($frm['filter_categorie_banniere'] != 0) {
					$cond .= ' AND pb.id_categorie="' . nohtml_real_escape_string($frm['filter_categorie_banniere']) . '"';
				}
			}
			if(!empty($frm['filter_lang'])) {
				$cond .= ' AND pb.lang="' . nohtml_real_escape_string($frm['filter_lang']) . '"';
			}
			if(!empty($frm['filter_description'])) {
				$cond .= ' AND pb.description LIKE "%' . nohtml_real_escape_string($frm['filter_description']) . '%"';
			}
			if(!empty($frm['filter_date_debut'])) {
				$cond .= ' AND pb.date_debut LIKE "' . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['filter_date_debut'])) . '%"';
			}
			if(!empty($frm['filter_date_fin'])) {
				$cond .= ' AND pb.date_fin LIKE "' . nohtml_real_escape_string(get_mysql_date_from_user_input($frm['filter_date_fin'])) . '%"';
			}
			if(isset($frm['filter_etat'])) {
				if($frm['filter_etat']!="-") {
					$cond .= ' AND pb.etat="' . intval($frm['filter_etat']) . '"';
				}
			}
			affiche_filtre_banner($frm);
			affiche_liste_banniere($inner, $cond);
			break;

        default :
			affiche_filtre_banner($frm);
            affiche_liste_banniere();
            break;
    }
} else {
	echo $GLOBALS['tplEngine']->createTemplate('modules/activate_module_first.tpl', array('href' => $GLOBALS['administrer_url'] . '/sites.php', 'STR_ADMIN_MODULE_ACTIVATE_LINK' => $GLOBALS['STR_ADMIN_MODULE_ACTIVATE_LINK']))->fetch();
}
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");
