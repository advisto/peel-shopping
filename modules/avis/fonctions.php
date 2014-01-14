<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 39495 2014-01-14 11:08:09Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * formulaire_avis()
 *
 * @param integer $id
 * @param mixed $frm
 * @param class $form_error_object
 * @return
 */

function formulaire_avis($id, &$frm, &$form_error_object, $type)
{
	if ($type == 'produit') {
		$product_object = new Product($id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
	} elseif ($type == 'annonce') {
		$annonce_object = new Annonce($id);
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_formulaire.tpl');
	$tpl->assign('action', get_current_url(true));
	$tpl->assign('type', $type);
	if ($type == 'produit') {
		$tpl->assign('product_name', $product_object->name);
		$tpl->assign('prodid', intval($_REQUEST['prodid']));
		$tpl->assign('STR_MODULE_AVIS_WANT_COMMENT_PRODUCT', $GLOBALS['STR_MODULE_AVIS_WANT_COMMENT_PRODUCT']);
	} elseif ($type == 'annonce') {
		$tpl->assign('annonce_titre', $annonce_object->titre);
		$tpl->assign('ref', intval($_REQUEST['ref']));
		$tpl->assign('STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD']);
	}
	$tpl->assign('id_utilisateur', intval($_SESSION['session_utilisateur']['id_utilisateur']));
	$tpl->assign('prenom', vb($_SESSION['session_utilisateur']['prenom']));
	$tpl->assign('nom_famille', vb($_SESSION['session_utilisateur']['nom_famille']));
	$tpl->assign('email', vb($_SESSION['session_utilisateur']['email']));
	$tpl->assign('avis', vb($frm['avis']));
	$tpl->assign('pseudo', vb($frm['pseudo']));
	$tpl->assign('pseudo_ses', vb($_SESSION['session_utilisateur']['pseudo']));
	$tpl->assign('error_avis', $form_error_object->text('avis'));
	$tpl->assign('error_note', $form_error_object->text('note'));
	$tpl->assign('star_src', $GLOBALS['wwwroot'] . '/images/star1.gif');
	$tpl->assign('langue', $_SESSION['session_langue']);
	$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS']);
	$tpl->assign('STR_YOU_ARE', $GLOBALS['STR_YOU_ARE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
	$tpl->assign('STR_YOUR_OPINION', $GLOBALS['STR_YOUR_OPINION']);
	$tpl->assign('STR_REMINDING_CHAR', $GLOBALS['STR_REMINDING_CHAR']);
	$tpl->assign('STR_YOUR_NOTE', $GLOBALS['STR_YOUR_NOTE']);
	$tpl->assign('STR_MODULE_AVIS_SEND_YOUR_OPINION', $GLOBALS['STR_MODULE_AVIS_SEND_YOUR_OPINION']);
	$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
	echo $tpl->fetch();
}

/**
 * insere_avis()
 *
 * ajoute les infos dans la table avis
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_avis($frm)
{
	if ($frm['type'] == 'produit') {
		$product_object = new Product($frm['prodid'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		$urlprod = $product_object->get_product_url();
	} elseif ($frm['type'] == 'annonce') {
		$annonce_object = new Annonce($frm['ref']);
		$urlannonce = $annonce_object->get_annonce_url();
	}
	$sql = "INSERT INTO peel_avis (
		nom_produit
		, id_utilisateur
		, email
		, prenom
		, pseudo
		, avis
		, note
		, datestamp
		, etat
		, lang";

	if ($frm['type'] == 'produit') {
		$sql .= ", id_produit";
	} elseif ($frm['type'] == 'annonce') {
		$sql .= ", ref";
	}

	if ($frm['type'] == 'produit') {
		$sql .= ") VALUES (
		'" . nohtml_real_escape_string($frm['nom_produit']) . "'";
	} elseif ($frm['type'] == 'annonce') {
		$sql .= ") VALUES (
		'" . nohtml_real_escape_string($frm['titre_annonce']) . "'";
	}

	$sql .= "
		, '" . intval($frm['id_utilisateur']) . "'
		, '" . nohtml_real_escape_string($frm['email']) . "'
		, '" . nohtml_real_escape_string(String::strtolower($frm['prenom'])) . "'
		, '" . nohtml_real_escape_string($frm['pseudo']) . "'
		, '" . nohtml_real_escape_string($frm['avis']) . "'
		, '" . intval($frm['note']) . "'
		, '" . date('Y-m-d H:i:s', time()) . "'
		, '0'
		, '" . nohtml_real_escape_string($frm['langue']) . "'";

	if ($frm['type'] == 'produit') {
		$sql .= ", '" . intval($frm['prodid']) . "')";
	} elseif ($frm['type'] == 'annonce') {
		$sql .= ", '" . intval($frm['ref']) . "')";
	}

	query("UPDATE peel_utilisateurs
		SET pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "'
		WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'");
	$qid = query($sql);

	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_insere.tpl');
	$custom_template_tags['PRENOM'] = vb($_SESSION['session_utilisateur']['prenom']);
	$custom_template_tags['NOM_FAMILLE'] = vb($_SESSION['session_utilisateur']['nom_famille']);
	if ($frm['type'] == 'produit') {
		$custom_template_tags['NOM_PRODUIT'] = String::html_entity_decode_if_needed($frm['nom_produit']);
		$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_ON_PRODUCT', $GLOBALS['STR_MODULE_AVIS_YOUR_COMMENT_ON_PRODUCT']);
	} elseif ($frm['type'] == 'annonce') {
		$custom_template_tags['NOM_PRODUIT'] = String::html_entity_decode_if_needed($frm['titre_annonce']);
		$tpl->assign('STR_MODULE_ANNONCES_AVIS_YOUR_COMMENT_ON_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_YOUR_COMMENT_ON_AD']);
	}
	$custom_template_tags['AVIS'] = $frm['avis'];
	send_email($GLOBALS['support_sav_client'], '', '', 'insere_avis', $custom_template_tags, 'html', $GLOBALS['support'], true, false, true, $frm['email']);
	$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS']);
	$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION', $GLOBALS['STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION']);
	$tpl->assign('site', $GLOBALS['site']);
	$tpl->assign('type', $frm['type']);
	if ($frm['type'] == 'produit') {
		$tpl->assign('urlprod', $urlprod);
		$tpl->assign('nom_produit', $frm['nom_produit']);
	} elseif ($frm['type'] == 'annonce') {
		$tpl->assign('urlannonce', $urlannonce);
		$tpl->assign('titre_annonce', $frm['titre_annonce']);
	}
	echo $tpl->fetch(); 
}

/**
 * render_avis_public_list()
 *
 * @param mixed $prodid
 * @return
 */
function render_avis_public_list($prodid, $type, $display_specific_note = null)
{
	$output = '';
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_public_list.tpl');
	if ($type == 'produit') {
		$product_object = new Product($prodid, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		$urlprod = $product_object->get_product_url();
		$sqlAvis = "SELECT *
			FROM peel_avis
			WHERE id_produit = '" . intval($prodid) . "' AND etat = '1' AND lang = '" . nohtml_real_escape_string($_SESSION['session_langue']) . "'
			ORDER BY note DESC";
	} elseif ($type == 'annonce') {
		$annonce_object = new Annonce($prodid);
		$urlannonce = $annonce_object->get_annonce_url();
		$sqlAvis = "SELECT *
			FROM peel_avis
			WHERE ref = '" . intval($prodid) . "' AND etat = '1' AND lang = '" . nohtml_real_escape_string($_SESSION['session_langue']) . "'
			ORDER BY note DESC";
		$tpl->assign('STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD']);
		$tpl->assign('STR_MODULE_ANNONCES_BACK_TO_AD', $GLOBALS['STR_MODULE_ANNONCES_BACK_TO_AD']);
	}

	$resAvis = query($sqlAvis);
	$tpl->assign('type', $type);
	$tpl->assign('star_src', $GLOBALS['wwwroot'] . '/images/star1.gif');
	$tpl->assign('STR_MODULE_AVIS_PEOPLE_OPINION_ABOUT_PRODUCT', $GLOBALS['STR_MODULE_AVIS_PEOPLE_OPINION_ABOUT_PRODUCT']);
	$tpl->assign('STR_MODULE_AVIS_AVERAGE_RATING_GIVEN', $GLOBALS['STR_MODULE_AVIS_AVERAGE_RATING_GIVEN']);
	$tpl->assign('STR_MODULE_AVIS_OPINION_POSTED_BY', $GLOBALS['STR_MODULE_AVIS_OPINION_POSTED_BY']);
	$tpl->assign('STR_ON_DATE_SHORT', $GLOBALS['STR_ON_DATE_SHORT']);
	$tpl->assign('STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT', $GLOBALS['STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT']);
	$tpl->assign('STR_BACK_TO_PRODUCT', $GLOBALS['STR_BACK_TO_PRODUCT']);
	$tpl->assign('STR_POSTED_OPINION', $GLOBALS['STR_POSTED_OPINION']);
	$tpl->assign('STR_POSTED_OPINIONS', $GLOBALS['STR_POSTED_OPINIONS']);

	if (num_rows($resAvis) > 0) {
		$tpl->assign('are_results', true);
		$qid = "SELECT AVG(note) AS average_rating
			FROM peel_avis
			WHERE";
		if ($type == 'produit') {
			$qid .= " id_produit = '" . intval($prodid) . "'";
		} elseif ($type == 'annonce') {
			$qid .= " ref = '" . intval($prodid) . "'";
		}
		$qid .= " AND etat = '1'";

		$id = query($qid);
		$note = fetch_assoc($id);
		$avisnote = number_format($note['average_rating'], 0);

		$tpl->assign('avisnote', $avisnote);

		$tpl_results = array();
		$tpl_notation = array();
		$notation_array = array();
		$i = 0;
		while ($Avis = fetch_assoc($resAvis)) {
			// Compte le nombre de vote par note
			if (!isset($notation_array[$Avis['note']])) {
				$notation_array[$Avis['note']] = 0;
			}
			$notation_array[$Avis['note']]++;
			
			if (!empty($display_specific_note) && ($Avis['note'] != $display_specific_note)) {
				// Permet d'afficher une note seléctionnée en excluant les votes avec une autre note, tout en conservant le comptage du nombre total, et le calcul du nombre de vote par note
				continue;
			}
			if (!empty($Avis['pseudo'])) {
				$pseudo = String::html_entity_decode_if_needed($Avis['pseudo']);
			} else {
				$pseudo = String::html_entity_decode_if_needed($Avis['prenom']);
			}
			$tpl_results[] = array('i' => $i,
				'pseudo' => $pseudo,
				'date' => get_formatted_date($Avis['datestamp']),
				'avis' => $Avis['avis']
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
		
		$total_vote = array_sum($notation_array);
		for($i=5;$i!=0;$i--) {
			// Affiche les votes par ordre décroissant. Utilisation d'un for et non pas un foreach pour permettre l'affichage des notes sans vote (et donc pas présent dans le tableau notation_array)
			$width = (vn($notation_array[$i]) / $total_vote) * 100;
			$tpl_notation[] = array('note' => $i,
				'nb_this_vote' => vn($notation_array[$i]),
				'width' => ceil($width),
				'link' => get_current_url(false) . '?prodid='.$prodid.'&display_specific_note='.$i
				);
		}
		$tpl->assign('notations', $tpl_notation);
		
		$tpl->assign('display_nb_vote_graphic_view', vn($GLOBALS['site_parameters']['display_nb_vote_graphic_view']));
		$tpl->assign('all_results_url', get_current_url(false). '?prodid='.$prodid);
		$tpl->assign('total_vote', $total_vote);
	} else {
		$tpl->assign('are_results', false);
	}

	if ($type == 'produit') {
		$tpl->assign('product_name', $product_object->name);
		$tpl->assign('urlprod', $urlprod);
		unset($product_object);
	} elseif ($type == 'annonce') {
		$tpl->assign('annonce_titre', $annonce_object->titre);
		$tpl->assign('urlannonce', $urlannonce);
		unset($annonce_object);
	}
	echo $tpl->fetch();
}

?>