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
// $Id: fonctions.php 48447 2016-01-11 08:40:08Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Gestion du changement de status d'un élément dans une table si pas prévu par défaut
 *
 * @param array $params
 * @return
 */
function avis_hook_rpc_status(&$params) {
	if ($params['new_status'] == 1 && $params['mode']=='avis' && check_if_module_active('annonces')) {
		// avis validé, on prévient tous les followers si l'avis déposé est celui du propriétaire de l'annonce (puisque dans ce cas on considère l'avis comme une news)
		$sql_avis = "SELECT ref, id_utilisateur
			FROM peel_avis
			WHERE id='".intval($_POST['id'])."'";
		$query_avis = query($sql_avis);
		$result_avis = fetch_assoc($query_avis);
		$annonce_object = new Annonce($result_avis['ref']);
		if (!empty($annonce_object->id_utilisateur) && $result_avis['id_utilisateur']==$annonce_object->id_utilisateur) {
			// l'annonce associée au vote a été trouvée, et la personne qui a déposé l'annonce est le propriétaire de l'annonce => Il faut prévenir les followers
			$query = query("SELECT user_id
				 FROM peel_ads_likes
				 WHERE ad_id=".intval($result_avis['ref'])."
				 LIMIT 500"); // limitation à 500 pour ne pas provoquer de problème lors de masse d'email trop importante
			while($result = fetch_assoc($query)) {
				$utilisateur = get_user_information($result['user_id']);
				$custom_template_tags['NOM'] = $utilisateur['nom_famille'];
				$custom_template_tags['PRENOM'] = $utilisateur['prenom'];
				$custom_template_tags['AD_NAME'] = $annonce_object->get_titre();
				$custom_template_tags['AD_OWNER'] = $annonce_object->pseudo;
				send_email($utilisateur['email'], '', '', 'news_added_on_followed_project', $custom_template_tags, null, $GLOBALS['support'], true, false, true, $annonce_object->email);
			}
		}
	}
}

/**
 * formulaire_avis()
 *
 * @param integer $id
 * @param mixed $frm
 * @param class $form_error_object
 * @return
 */
function formulaire_avis($id, &$frm, &$form_error_object, $type, $ad_owner_opinion = false, $opinion_id = null)
{
	if ($type == 'produit') {
		$product_object = new Product($id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
	} elseif ($type == 'annonce') {
		$annonce_object = new Annonce($id);
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_formulaire.tpl');
	$tpl->assign('action', get_current_url(true));
	$tpl->assign('type', $type);
	$tpl->assign('ad_owner_opinion', $ad_owner_opinion);
	$tpl->assign('opinion_id', $opinion_id);
	$tpl->assign('no_notation', vn($GLOBALS['site_parameters']['module_avis_no_notation']));
	if (!empty($GLOBALS['site_parameters']['module_avis_use_html_editor'])) {
		$tpl->assign('html_editor', getTextEditor('avis', vb($GLOBALS['site_parameters']['module_avis_html_editor_width'], 475), vb($GLOBALS['site_parameters']['module_avis_html_editor_height'], 280), "", '../../', 3));
	}
	
	if ($type == 'produit') {
		$tpl->assign('product_name', $product_object->name);
		$tpl->assign('prodid', intval($id));
		$tpl->assign('STR_MODULE_AVIS_WANT_COMMENT_PRODUCT', $GLOBALS['STR_MODULE_AVIS_WANT_COMMENT_PRODUCT']);
	} elseif ($type == 'annonce') {
		$tpl->assign('annonce_titre', $annonce_object->get_titre());
		$tpl->assign('ref', intval($id));
		if ($ad_owner_opinion) {
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD_OWNER_OPINION']);
		} else {
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD']);
		}
	}
	if ($type == 'annonce' && $ad_owner_opinion) {
		$tpl->assign('STR_YOUR_OPINION', $GLOBALS['STR_DONNEZ_AVIS_AD_OWNER_YOUR_OPINION']);
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS_AD_OWNER_OPINION']);
	} else {
		$tpl->assign('STR_YOUR_OPINION', $GLOBALS['STR_YOUR_OPINION']);
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS']);
	}
	
	$tpl->assign('id_utilisateur', intval(vn($_SESSION['session_utilisateur']['id_utilisateur'])));
	$tpl->assign('prenom', vb($_SESSION['session_utilisateur']['prenom']));
	$tpl->assign('nom_famille', vb($_SESSION['session_utilisateur']['nom_famille']));
	$tpl->assign('email', vb($_SESSION['session_utilisateur']['email']));
	$tpl->assign('avis', vb($frm['avis']));
	$tpl->assign('pseudo', vb($frm['pseudo']));
	$tpl->assign('pseudo_ses', vb($_SESSION['session_utilisateur']['pseudo']));
	$tpl->assign('error_avis', $form_error_object->text('avis'));
	$tpl->assign('error_note', $form_error_object->text('note'));
	$tpl->assign('star_src', get_url('/images/star1.gif'));
	$tpl->assign('langue', $_SESSION['session_langue']);
	$tpl->assign('STR_YOU_ARE', $GLOBALS['STR_YOU_ARE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
	$tpl->assign('STR_REMINDING_CHAR', $GLOBALS['STR_REMINDING_CHAR']);
	$tpl->assign('STR_YOUR_NOTE', $GLOBALS['STR_YOUR_NOTE']);
	$tpl->assign('STR_MODULE_AVIS_SEND_YOUR_OPINION', $GLOBALS['STR_MODULE_AVIS_SEND_YOUR_OPINION']);
	$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
	return $tpl->fetch();
}

/**
 * insere_avis()
 *
 * ajoute les infos dans la table avis
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_avis($frm, $ad_owner_opinion)
{
	if ($frm['type'] == 'produit') {
		$product_object = new Product($frm['prodid'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
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
		, '" . nohtml_real_escape_string(vb($frm['pseudo'])) . "'
		, '" . real_escape_string(String::getCleanHTML($frm['avis'])) . "'
		, '" . intval(vn($frm['note'])) . "'
		, '" . date('Y-m-d H:i:s', time()) . "'
		, '0'
		, '" . nohtml_real_escape_string($frm['langue']) . "'";

	if ($frm['type'] == 'produit') {
		$sql .= ", '" . intval($frm['prodid']) . "')";
	} elseif ($frm['type'] == 'annonce') {
		$sql .= ", '" . intval($frm['ref']) . "')";
	}
	if(!empty($frm['pseudo'])) {
		query("UPDATE peel_utilisateurs
			SET pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "'
			WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND " . get_filter_site_cond('utilisateurs') . "");
	}
	$qid = query($sql);
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_insere.tpl');
	$custom_template_tags['PRENOM'] = vb($_SESSION['session_utilisateur']['prenom']);
	$custom_template_tags['NOM_FAMILLE'] = vb($_SESSION['session_utilisateur']['nom_famille']);
	if ($frm['type'] == 'produit') {
		$custom_template_tags['NOM_PRODUIT'] = String::html_entity_decode_if_needed($frm['nom_produit']);
		$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_ON_PRODUCT', $GLOBALS['STR_MODULE_AVIS_YOUR_COMMENT_ON_PRODUCT']);
	} elseif ($frm['type'] == 'annonce') {
		$custom_template_tags['NOM_PRODUIT'] = String::html_entity_decode_if_needed($frm['titre_annonce']);
	}
	$custom_template_tags['AVIS'] = $frm['avis'];
	send_email($GLOBALS['support_sav_client'], '', '', 'insere_avis', $custom_template_tags, null, $GLOBALS['support'], true, false, true, $frm['email']);
	if ($ad_owner_opinion && !empty($annonce_object)) {
		/*
		// envoi d'une notification aux followers de cette annonce. A faire uniquement lorsque l'avis est validé par l'administrateur.
		$query = query("SELECT user_id
			 FROM peel_ads_likes
			 WHERE ad_id=".intval($frm['ref'])."
			 LIMIT 500"); // limitation à 500 pour ne pas provoquer de problème lors de masse d'email trop importante
		while($result = fetch_assoc($query)) {
			$utilisateur = get_user_information($result['user_id']);
			$custom_template_tags['NOM'] = $utilisateur['nom_famille'];
			$custom_template_tags['PRENOM'] = $utilisateur['prenom'];
			$custom_template_tags['AD_NAME'] = $annonce_object->get_titre();
			$custom_template_tags['AD_OWNER'] = $annonce_object->pseudo;
			// send_email($utilisateur['email'], '', '', 'news_added_on_followed_project', $custom_template_tags, null, $GLOBALS['support'], true, false, true, $frm['email']);
		}

		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS_AD_OWNER_OPINION']);
		$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION', $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_AVIS_YOUR_NEWS_WAITING_FOR_VALIDATION'],$GLOBALS['site'])))->fetch());
		$tpl->assign('STR_MODULE_ANNONCES_AVIS_YOUR_COMMENT_ON_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_YOUR_NEWS_ON_AD']);
		*/
	} else {
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS']);
		$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION', $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION'],$GLOBALS['site'])))->fetch());
	}
	$tpl->assign('site', $GLOBALS['site']);
	$tpl->assign('type', $frm['type']);
	if ($frm['type'] == 'produit') {
		$tpl->assign('urlprod', $urlprod);
		$tpl->assign('nom_produit', $frm['nom_produit']);
	} elseif ($frm['type'] == 'annonce') {
		$tpl->assign('urlannonce', $urlannonce);
		$tpl->assign('titre_annonce', $frm['titre_annonce']);
	}
	return $tpl->fetch(); 
}

/**
 * render_avis_public_list()
 *
 * @param mixed $prodid
 * @param mixed $type
 * @param mixed $display_specific_note
 * @param mixed $no_display_if_empty
 * @param mixed $ad_owner_opinion Pour afficher les commmentaires laissé par le porteur de l'annonce
 * @return
 */
function render_avis_public_list($prodid, $type, $display_specific_note = null, $no_display_if_empty = false, $ad_owner_opinion = false)
{
	$output = '';
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_public_list.tpl');
	if ($type == 'produit') {
		$product_object = new Product($prodid, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
		$urlprod = $product_object->get_product_url();
		$sqlAvis = "SELECT a.*, u.civilite
			FROM peel_avis a
			INNER JOIN peel_utilisateurs u ON a.id_utilisateur = u.id_utilisateur AND " . get_filter_site_cond('utilisateurs', 'u') . "
			WHERE a.id_produit='" . intval($prodid) . "' AND a.etat='1' AND a.lang='" . nohtml_real_escape_string($_SESSION['session_langue']) . "'
			ORDER BY a.note DESC";
		$tpl->assign('STR_MODULE_AVIS_OPINION_POSTED_BY', $GLOBALS['STR_MODULE_AVIS_OPINION_POSTED_BY']);
	} elseif ($type == 'annonce') {
		// Le mode ad_owner_opinion affiche que les avis publiés par le propriétaire de l'annonce
		$annonce_object = new Annonce($prodid);
		$urlannonce = $annonce_object->get_annonce_url();
		$sqlAvis = "SELECT *
			FROM peel_avis
			WHERE ref='" . intval($prodid) . "' AND etat='1' AND lang='" . nohtml_real_escape_string($_SESSION['session_langue']) . "' AND id_utilisateur" . (!empty($ad_owner_opinion)?"=":"!=") .  intval($annonce_object->id_utilisateur) ."
			ORDER BY note DESC";
		if (!empty($annonce_object) && a_priv('admin*')) {
			$tpl->assign('ad_admin_edit_option', true);
		} else {
			$tpl->assign('ad_admin_edit_option', false);
		}
		if (!empty($ad_owner_opinion)) {
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD', $GLOBALS['STR_MODULE_ANNONCES_OWNER_NO_OPINION_FOR_THIS_AD']);
			$tpl->assign('STR_MODULE_AVIS_OPINION_POSTED_BY', $GLOBALS['STR_MODULE_AVIS_OPINION_POSTED_BY_AD_OWNER_OPINION']);
		} else {
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD']);
			$tpl->assign('STR_MODULE_AVIS_OPINION_POSTED_BY', $GLOBALS['STR_MODULE_AVIS_OPINION_POSTED_BY']);
		}
		$tpl->assign('STR_MODULE_ANNONCES_BACK_TO_AD', $GLOBALS['STR_MODULE_ANNONCES_BACK_TO_AD']);
	} else {
		return null;
	}

	$resAvis = query($sqlAvis);
	$tpl->assign('type', $type);
	$tpl->assign('ad_owner_opinion', $ad_owner_opinion);
	$tpl->assign('star_src', get_url('/images/star1.gif'));
	$tpl->assign('STR_MODULE_AVIS_PEOPLE_OPINION_ABOUT_PRODUCT', $GLOBALS['STR_MODULE_AVIS_PEOPLE_OPINION_ABOUT_PRODUCT']);
	$tpl->assign('STR_MODULE_AVIS_AVERAGE_RATING_GIVEN', $GLOBALS['STR_MODULE_AVIS_AVERAGE_RATING_GIVEN']);
	if (!empty($annonce_object) && $ad_owner_opinion && $annonce_object->id_utilisateur == vn($_SESSION['session_utilisateur']['id_utilisateur'])) {
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS_AD_OWNER_OPINION']);
		$tpl->assign('ad_owner_edit_option', true);
	} else {
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS']);
		$tpl->assign('ad_owner_edit_option', false);
	}
	$tpl->assign('STR_ON_DATE_SHORT', $GLOBALS['STR_ON_DATE_SHORT']);
	$tpl->assign('STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT', $GLOBALS['STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT']);
	$tpl->assign('STR_BACK_TO_PRODUCT', $GLOBALS['STR_BACK_TO_PRODUCT']);
	$tpl->assign('STR_POSTED_OPINION', $GLOBALS['STR_POSTED_OPINION']);
	$tpl->assign('STR_POSTED_OPINIONS', $GLOBALS['STR_POSTED_OPINIONS']);

	if (num_rows($resAvis) > 0) {
		$are_results = true;
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
				'date' => get_formatted_date($Avis['datestamp'], 'short', true),
				'avis' => $Avis['avis'],
				'note' => $Avis['note'],
				'id' => $Avis['id'],
				'allow_edit_and_suppr_avis' => vn($GLOBALS['site_parameters']['allow_edit_and_suppr_avis_by_owner']) && vn($_SESSION['session_utilisateur']['id_utilisateur']) == $Avis['id_utilisateur']
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
		
		$total_vote = array_sum($notation_array);
		for($j=5;$j!=0;$j--) {
			// Affiche les votes par ordre décroissant. Utilisation d'un for et non pas un foreach pour permettre l'affichage des notes sans vote (et donc pas présent dans le tableau notation_array)
			$width = (vn($notation_array[$j]) / $total_vote) * 100;
			$tpl_notation[] = array('note' => $j,
				'nb_this_vote' => vn($notation_array[$j]),
				'width' => ceil($width),
				'link' => get_current_url(false) . '?prodid='.$prodid.'&display_specific_note='.$j
				);
		}
		$tpl->assign('notations', $tpl_notation);
		
		$tpl->assign('display_nb_vote_graphic_view', vn($GLOBALS['site_parameters']['display_nb_vote_graphic_view']) && empty($ad_owner_opinion));
		$tpl->assign('module_avis_no_notation', vn($GLOBALS['site_parameters']['module_avis_no_notation']));
		$tpl->assign('all_results_url', get_current_url(false). '?prodid='.$prodid);
		$tpl->assign('total_vote', $total_vote);
	} else {
		$tpl->assign('are_results', false);
		$are_results = false;
	}

	$tpl->assign('id', $prodid);
	if ($type == 'produit') {
		$tpl->assign('product_name', $product_object->name);
		$tpl->assign('urlprod', $urlprod);
		unset($product_object);
	} elseif ($type == 'annonce') {
		$tpl->assign('annonce_titre', $annonce_object->get_titre());
		$tpl->assign('urlannonce', $urlannonce);
		unset($annonce_object);
	}
	
	if (empty($are_results) && $no_display_if_empty) {
		$output = '';
	} else {
		$output = $tpl->fetch();
	}
	return $output;
}

/*
 *
 * Supprime un avis, si la personne qui fait la demande est le propriétaire de l'avis.
 *
 *
*/
function delete_avis($id) {

	// On doit vérifier que l'utilisateur à bien le droit de supprimer l'avis. Pour cela il y a deux cas : L'utilisateur est administrateur du site, ou l'utilisateur est le propriétaire du produit/annonce noté.
	$sql = "SELECT *
		FROM peel_avis
		WHERE id=".intval($id);
	$q = query($sql);
	$result = fetch_assoc($q);
	if (!empty($result['ref'])) {
		$annonce_object = new Annonce($result['ref']);
		if($_SESSION['session_utilisateur']['id_utilisateur'] == $annonce_object->id_utilisateur) {
			$deleted_by_user_allowed=true;
		}
	}
	$sql = "DELETE FROM peel_avis
		WHERE";
	if (empty($deleted_by_user_allowed) && !a_priv('admin')) {
		$sql .= "
		id_utilisateur='".intval(vn($_SESSION['session_utilisateur']['id_utilisateur']))."' AND";
	}
	$sql .= "
		 id='".intval(vn($id))."'";

	$q = query($sql);
	return  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_REQUEST_OK']))->fetch();
}


/**
 * Met à jour la avis $id avec les nouvelles valeurs contenues dans $frm
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_avis($frm)
{
	$qid = query("UPDATE peel_avis SET
			avis = '" . nohtml_real_escape_string($frm['avis']) . "'
			, note = '" . nohtml_real_escape_string($frm['note']) . "'
			, etat = '" . nohtml_real_escape_string($frm['etat']) . "'
		WHERE id = '" . intval($frm['id']) . "'");
}
















