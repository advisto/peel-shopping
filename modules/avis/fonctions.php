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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $
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
 * Ajout d'une section sur la page de détail produits
 *
 * @param array $params
 * @return
 */
function avis_hook_product_details_additional_infos(&$params) {
	$tpl_array = array();
	$q_average_rating = query("SELECT AVG(note) AS average_rating
		FROM peel_avis
		WHERE id_produit = '" . intval($params['id']) . "' AND etat = '1'");
	$r_average_rating = fetch_assoc($q_average_rating);
	$average_rating = number_format($r_average_rating['average_rating'], 0);
	$sqlAvis = "SELECT note
		FROM peel_avis
		WHERE id_produit = '" . intval($params['id']) . "' AND etat = '1' AND lang = '" . nohtml_real_escape_string($_SESSION['session_langue']) . "'
		ORDER BY note DESC";
	$resAvis = query($sqlAvis);
	$notation_array = array();
	while ($Avis = fetch_assoc($resAvis)) {
		if (!isset($notation_array[$Avis['note']])) {
			$notation_array[$Avis['note']] = 0;
		}
		$notation_array[$Avis['note']]++;
	}

	$tpl_array['avis'] = array(
		'href' => $GLOBALS['wwwroot'] . '/modules/avis/avis.php?prodid=' . $params['id'],
		'src' => $GLOBALS['site_parameters']['general_give_your_opinion_image'],
		'txt' => $GLOBALS['STR_DONNEZ_AVIS']
	);
	$tpl_array['tous_avis'] = array(
		'href' => (!empty($GLOBALS['site_parameters']['display_opinion_on_product_tab'])? get_current_url(true) . '#tab_opinion' : $GLOBALS['wwwroot'] . '/modules/avis/liste_avis.php?prodid=' . $params['id']),
		'src' => $GLOBALS['site_parameters']['general_read_all_reviews_image'],
		'txt' => $GLOBALS['STR_TOUS_LES_AVIS'],
		'STR_POSTED_OPINION' => $GLOBALS['STR_POSTED_OPINION'],
		'STR_POSTED_OPINIONS' => $GLOBALS['STR_POSTED_OPINIONS'],
		'STR_MODULE_AVIS_NOTE' => $GLOBALS['STR_MODULE_AVIS_NOTE'],
		'nb_avis' => array_sum($notation_array),
		'star_src' => get_url('/images/star1.gif'),
		'average_rating' => $average_rating,
		'display_opinion_resume_in_product_page' => !empty($GLOBALS['site_parameters']['display_opinion_resume_in_product_page'])
	);
	return $tpl_array;
}

/**
 * Affiche les résultats de recherche
 *
 * @param array $params
 * @return
 */
function avis_hook_search_complementary($params) {
	// Recherche dans les avis
	$results = array();
	$urls_array = array(); // On ne veut pas de doublon dans les résultats
	if(empty($params['terms']) || vn($params['page'])>1) {
		return null;
	}
	$fields[] = 'nom_produit';
	$fields[] = 'avis';
	foreach(array('avis', 'news') as $this_mode) {
		$results_array = array();
		$i = 0;
		$sql_cond = build_terms_clause($params['terms'], $fields, $params['match']);
		if($this_mode == 'news') {
			$sql_cond .= " AND note='-99'";
			$title = $GLOBALS["STR_MODULE_AVIS_POSTED_NEWS"];
		} else {
			$sql_cond .= " AND note!='-99'";
			$title = $GLOBALS["STR_POSTED_OPINIONS"];
		}
		$sql = "SELECT *
			FROM peel_avis
			WHERE etat=1 AND " . $sql_cond . "
			ORDER BY id DESC
			LIMIT ". vn($GLOBALS['site_parameters']['avis_search_results_max'], 40);
		$query = query($sql);
		while ($result = fetch_assoc($query)) {
			unset($nom);
			if(!empty($result['id_produit'])) {
				$url = get_product_url($result['id_produit']);
			} else {
				$annonce_object = new Annonce($result['ref']);
				if(empty($annonce_object->ref) || empty($annonce_object->etat)) {
					continue;
				}
				$url = $annonce_object->get_annonce_url();
				$nom = $annonce_object->get_titre();
				unset($annonce_object);
			}
			if(in_array($url, $urls_array)) {
				continue;
			}
			$urls_array[] = $url;
			// on supprime le HTML du contenu
			if(!empty($result['nom_produit'])) {
				$nom = StringMb::strip_tags(StringMb::html_entity_decode_if_needed($result['nom_produit']));
			}
			if(empty($nom)) {
				continue;
			}
			$description = StringMb::strip_tags(StringMb::html_entity_decode_if_needed($result['avis']));
			// on coupe le texte si trop long
			$nom = StringMb::str_shorten($nom, $params['taille_texte_affiche'], '', '...', $params['taille_texte_affiche']-20);
			$description = StringMb::str_shorten($description, $params['taille_texte_affiche'], '', '...', $params['taille_texte_affiche']-20);
			// on fait une recherche sur le texte sans accent avec les mots de l'utilisateur,
			// si qqchose est trouvé, highlight_found_text l'ajoute dans le tableau  $GLOBALS['found_words_array'][]
			$nom = highlight_found_text($nom, $params['terms'], $GLOBALS['found_words_array']);
			$description = highlight_found_text($description, $params['terms'], $GLOBALS['found_words_array']);
			// affichage
			$i++;
			$results_array[] = array('num' => $i,
				'id' => $result['id'],
				'name' => $nom,
				'href' => $url,
				'description' => $description
				);
		}
		if(!empty($results_array)) {
			$results[$this_mode] = array('results' => $results_array, 'title' => $title, 'no_result' => null);
		}
	}
	return $results;
}

/**
 * formulaire_avis()
 *
 * @param integer $reference_id
 * @param array $frm
 * @param class $form_error_object
 * @param string $mode
 * @param integer $opinion_id
 * @return
 */
function formulaire_avis($reference_id, &$frm, &$form_error_object, $type, $mode = 'avis', $opinion_id = null, $campaign_id = null)
{
	if ($type == 'produit') {
		$product_object = new Product($reference_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
	} elseif ($type == 'annonce') {
		$annonce_object = new Annonce($reference_id);
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_formulaire.tpl');
	$tpl->assign('action', get_current_url(true));
	$tpl->assign('type', $type);
	$tpl->assign('mode', $mode);
	$tpl->assign('item_id', $campaign_id);
	$tpl->assign('opinion_id', $opinion_id);
	$tpl->assign('no_notation', vn($GLOBALS['site_parameters']['module_avis_no_notation']));
	if (!empty($GLOBALS['site_parameters']['module_avis_use_html_editor'])) {
		$tpl->assign('html_editor', getTextEditor('avis', vb($GLOBALS['site_parameters']['module_avis_html_editor_width'], 475), vb($GLOBALS['site_parameters']['module_avis_html_editor_height'], 280), "", '../../', 3));
	}
	
	if ($type == 'produit') {
		$tpl->assign('product_name', $product_object->name);
		$tpl->assign('prodid', intval($reference_id));
		$tpl->assign('STR_MODULE_AVIS_WANT_COMMENT_PRODUCT', $GLOBALS['STR_MODULE_AVIS_WANT_COMMENT_PRODUCT']);
	} elseif ($type == 'annonce') {
		$tpl->assign('annonce_titre', $annonce_object->get_titre());
		$tpl->assign('ref', intval($reference_id));
		if ($mode != 'avis') {
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_WANT_NEWS_AD']);
		} else {
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD']);
		}
	}
	if ($mode == 'news') {
		$tpl->assign('STR_YOUR_OPINION', $GLOBALS['STR_MODULE_AVIS_YOUR_NEWS']);
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_MODULE_AVIS_YOUR_NEWS_ADD']);
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
	$tpl->assign('STR_SEND', $GLOBALS['STR_SEND']);
	$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
	return $tpl->fetch();
}

/**
 * Ajoute les infos dans la table avis, pour un utilisateur qui n'avait jamais voté avant (sinon, on gère la mise à jour du vote ailleurs)
 *
 * @param array $frm Array with all fields data
 * @param boolean $update_user_account
 * @return
 */
function ajout_avis($frm, $update_user_account = false)
{
	$frm['avis'] = StringMb::getCleanHTML($frm['avis']);
	if(vb($frm['mode'], 'avis') == 'news') {
		// Une news est un avis avec une note de -99
		$frm['note'] = -99;
	}
	if (!empty($GLOBALS['site_parameters']['filter_user_message_with_contact_information'])) {
		if(PhoneIn($frm['avis'])) {
			$filter = 'Filter : phone';
		} elseif(MailIn($frm['avis'])) {
			$filter = 'Filter : email';
		}
	}
	if(empty($filter)) {
		// Si information filtrée dans le téléphone ou l'email, alors l'utilisateur n'est pas mis au courant qu'il y a un problème, et on n'insère rien en base de données, et on ne prévient pas l'administrateur
		$sql = "INSERT INTO peel_avis (";
		if ($frm['type'] == 'produit') {
			$sql .= " id_produit,";
			$template_technical_code = 'insere_' . $frm['mode'];
		} elseif ($frm['type'] == 'expert' || $frm['type'] == 'agent_co' || $frm['type'] == 'comment_expert' || $frm['type'] == 'contributor' ) {
			$sql .= " evaluated_user_id,";
			$template_technical_code = 'insere_' . $frm['mode'] . '_expert';
		} elseif ($frm['type'] == 'annonce') {
			if(!check_if_module_active('annonces')) {
				return false;
			}
			$sql .= " ref,";
			$template_technical_code = 'insere_' . $frm['mode'] . '_ad';
			if($frm['mode'] == 'avis' && !empty($frm['reference_id']) && isset($frm['note'])) {
				// On met à jour les statistiques générales des votes liés à cette annonce 
				$voted_assoc = get_vote_infos(null, $frm['reference_id'], vn($frm['campaign_id']));
				$count_new = $voted_assoc['nb_votes'] + 1;
				$new_rating = $voted_assoc['total_votes'] + vn($frm['note']);
				$sql_ads_stats = "SELECT *
					FROM peel_ads_stats
					WHERE id=".intval($frm['reference_id']);
				$query_ads_stats = query($sql_ads_stats); 
				if (num_rows($query_ads_stats)>0) {
					// Enregistrement déjà présent, il faut mettre à jour.
					query('UPDATE peel_ads_stats
						SET nb_votes="' . intval($count_new) . '", total_votes="' . nohtml_real_escape_string($new_rating) . '"
						WHERE id="' . intval($frm['reference_id']) . '"');
				} else {
					// Il faut créer l'enregistrement dans peel_ads_stats
					query('INSERT INTO peel_ads_stats
						SET nb_votes="' . intval($count_new) . '", total_votes="' . nohtml_real_escape_string($new_rating) . '", id="' . intval($frm['reference_id']) . '"');
				}
			}
		}
		$sql .= "
				nom_produit
				, id_utilisateur
				, email
				, prenom
				, pseudo
				, avis
				, note
				, datestamp
				, etat
				, lang
				, detail
				, item_id
			) VALUES (
				'" . intval($frm['reference_id']) . "'
				, '" . nohtml_real_escape_string(vb($frm['titre'])) . "'
				, '" . intval($frm['id_utilisateur']) . "'
				, '" . nohtml_real_escape_string($frm['email']) . "'
				, '" . nohtml_real_escape_string(StringMb::strtolower(vb($frm['prenom']))) . "'
				, '" . nohtml_real_escape_string(vb($frm['pseudo'])) . "'
				, '" . real_escape_string($frm['avis']) . "'
				, '" . nohtml_real_escape_string(vn($frm['note'])) . "'
				, '" . date('Y-m-d H:i:s', time()) . "'
				, '" . intval(vb($frm['etat'], 1)) . "'
				, '" . nohtml_real_escape_string($frm['langue']) . "'
				, '" . real_escape_string(vb($frm['detail'])) . "'
				, '" . real_escape_string(vb($frm['item_id'])) . "'
			)";
		$qid = query($sql);
		if (!$qid) {
			return false;
		}
		if ($frm['type'] != 'annonce' && !empty($frm['ref'])) {
			// On a une information supplémentaire à rentrer en base de données. On est pas en mode annonce, mais on souhaite enregistrer un id d'annonce associé à cette notation. C'est utile dans le cas ou la notation porte sur un membre dans le cadre d'une annonce (notation sur la prestation réalisée par un expert sur un projet).
			query("UPDATE peel_avis
				SET ref=" . intval($frm['ref']) ."
				WHERE id=" . intval(insert_id()));
		}
		if($update_user_account && !empty($frm['pseudo']) && !empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
			query("UPDATE peel_utilisateurs
				SET pseudo = '" . nohtml_real_escape_string($frm['pseudo']) . "'
				WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND " . get_filter_site_cond('utilisateurs') . "");
		}
		if (est_identifie()) {
			$nom_famille = $_SESSION['session_utilisateur']['nom_famille'];
			$prenom = $_SESSION['session_utilisateur']['prenom'];
		} elseif(!empty($frm['id_utilisateur'])) {
			$user_info = get_user_information($frm['id_utilisateur']);
			$nom_famille = $user_info['nom_famille'];
			$prenom = $user_info['prenom'];
		}

		$custom_template_tags['PRENOM'] = vb($prenom);
		$custom_template_tags['NOM_FAMILLE'] = vb($nom_famille);
		$custom_template_tags['NOM_PRODUIT'] = StringMb::html_entity_decode_if_needed($frm['titre']);
		$custom_template_tags['AVIS'] = $frm['avis'];
		if (!empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
			$additional_infos_array = array('id_utilisateur' => $_SESSION['session_utilisateur']['id_utilisateur']);
		} else {
			$additional_infos_array = array();
		}
		send_email($GLOBALS['support_sav_client'], '', '', $template_technical_code, $custom_template_tags, null, $GLOBALS['support'], true, false, true, $frm['email'], null, null, $additional_infos_array);
	}
	call_module_hook('ajout_avis', $frm);
	return empty($filter);
}

/**
 * Ajoute les infos dans la table avis
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_avis($frm)
{
	$frm['mode'] = vb($frm['mode'], 'avis');
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_insere.tpl');
	$tpl->assign('mode', $frm['mode']);
	if ($frm['type'] == 'produit') {
		$product_object = new Product($frm['prodid'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
		$urlprod = $product_object->get_product_url();
		$frm['titre'] = $frm['nom_produit'];
		$frm['reference_id'] = $frm['prodid'];
		$tpl->assign('urlprod', $urlprod);
		$tpl->assign('nom_produit', $frm['nom_produit']);
		$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_ON_PRODUCT', $GLOBALS['STR_MODULE_AVIS_YOUR_COMMENT_ON_PRODUCT']);
		$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION', $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION'],$GLOBALS['site'])))->fetch());
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS']);
	} elseif ($frm['type'] == 'annonce') {
		$annonce_object = new Annonce($frm['ref']);
		$urlannonce = $annonce_object->get_annonce_url();
		$frm['titre'] = $frm['titre_annonce'];
		$frm['reference_id'] = $frm['ref'];
		$tpl->assign('urlannonce', $urlannonce);
		$tpl->assign('titre_annonce', $frm['titre_annonce']);
		if ($frm['mode'] == 'news') {
			/*
			if ($frm['type'] == 'annonce') {
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
			}
			*/

			$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_MODULE_AVIS_YOUR_NEWS_ADD']);
			$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION', $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_AVIS_YOUR_NEWS_WAITING_FOR_VALIDATION'],$GLOBALS['site'])))->fetch());
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_YOUR_COMMENT_ON_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_YOUR_NEWS_ON_AD']);
		} else {
			$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS']);
			$tpl->assign('STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION', $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION'],$GLOBALS['site'])))->fetch());
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_YOUR_COMMENT_ON_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_YOUR_COMMENT_ON_AD']);
		}
	}
	if(empty($frm['id'])) {
		// Une insertion d'avis doit être validée par un administrateur par défaut
		$frm['etat'] = vn($GLOBALS['site_parameters']['avis_after_new_insert_status'], 0);
		ajout_avis($frm);
	} else {
		// Même une mise à jour d'avis doit être validée par un administrateur par défaut
		$frm['etat'] = vn($GLOBALS['site_parameters']['avis_after_new_update_status'], 0);
		maj_avis($frm);
	}

	$tpl->assign('site', $GLOBALS['site']);
	$tpl->assign('type', $frm['type']);
	return $tpl->fetch(); 
}

/**
 * render_avis_public_list()
 *
 * @param mixed $prodid
 * @param mixed $type
 * @param mixed $display_specific_note
 * @param mixed $no_display_if_empty
 * @param mixed $mode Par défaut on affiche des avis. Pour afficher les news non liées à des avis notés, utiliser $mode à "news" 
 * @param string $title
 * @return
 */
function render_avis_public_list($prodid, $type, $display_specific_note = null, $no_display_if_empty = false, $mode = 'avis', $title='h2', $campaign_id = null, $submit_value = null)
{
	$output = '';
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/avis_public_list.tpl');

	$tpl->assign('campaign_id', $campaign_id);
	$tpl->assign('mode', $mode);
	$tpl->assign('title', $title);
	$tpl->assign('type', $type);
	$tpl->assign('star_src', get_url('/images/star1.gif'));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_AVIS_PEOPLE_OPINION_ABOUT_PRODUCT', $GLOBALS['STR_MODULE_AVIS_PEOPLE_OPINION_ABOUT_PRODUCT']);
	$tpl->assign('STR_MODULE_AVIS_PEOPLE_NEWS_ABOUT_PRODUCT', $GLOBALS['STR_MODULE_AVIS_PEOPLE_NEWS_ABOUT_PRODUCT']);
	$tpl->assign('STR_MODULE_AVIS_AVERAGE_RATING_GIVEN', $GLOBALS['STR_MODULE_AVIS_AVERAGE_RATING_GIVEN']);
	if ($mode == 'news') {
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_MODULE_AVIS_YOUR_NEWS_ADD']);
	} else {
		$tpl->assign('STR_DONNEZ_AVIS', $GLOBALS['STR_DONNEZ_AVIS']);
	}
	$tpl->assign('STR_ON_DATE_SHORT', $GLOBALS['STR_ON_DATE_SHORT']);
	$tpl->assign('STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT', $GLOBALS['STR_MODULE_AVIS_NO_OPINION_FOR_THIS_PRODUCT']);
	$tpl->assign('STR_BACK_TO_PRODUCT', $GLOBALS['STR_BACK_TO_PRODUCT']);
	$tpl->assign('STR_POSTED_OPINION', $GLOBALS['STR_POSTED_OPINION']);
	$tpl->assign('STR_POSTED_OPINIONS', $GLOBALS['STR_POSTED_OPINIONS']);

	if (!empty($submit_value)) {
		$tpl->assign('submit_value', $submit_value);
	} else {
		$tpl->assign('submit_value', $GLOBALS['STR_MODULE_AVIS_SEND_YOUR_OPINION']);
	}
	if ($type == 'produit') {
		$sql_cond = '';
		$product_object = new Product($prodid, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
		$urlprod = $product_object->get_product_url();
		$is_owner = ($product_object->id_utilisateur == vn($_SESSION['session_utilisateur']['id_utilisateur']));
		if (empty($GLOBALS['site_parameters']['avis_show_on_all_langages'])) {
			// Affichage des avis que pour la langue de l'interface.
			$sql_cond .= " AND a.lang='" . nohtml_real_escape_string($_SESSION['session_langue']) . "'";
		}
		$sqlAvis = "SELECT a.*, u.civilite, u.pseudo
			FROM peel_avis a
			INNER JOIN peel_utilisateurs u ON a.id_utilisateur = u.id_utilisateur AND " . get_filter_site_cond('utilisateurs', 'u') . "
			WHERE a.id_produit='" . intval($prodid) . "' AND a.etat='1' AND " . ($mode == 'avis'?"note>-99":"note=-99") . " AND avis!='' ".$sql_cond;
		if (!empty($campaign_id)) {
			$sqlAvis .= " AND item_id=".intval($campaign_id);
		} else {
			$sqlAvis .= " AND item_id=''";
		}
		$sqlAvis .= "
			ORDER BY a.datestamp DESC";
		$tpl->assign('STR_MODULE_AVIS_OPINION_POSTED_BY', $GLOBALS['STR_MODULE_AVIS_OPINION_POSTED_BY']);
	} elseif ($type == 'annonce') {
		// Le mode news affiche des news qui sont publiées par le propriétaire de l'annonce
		$sql_cond = '';
		$annonce_object = new Annonce($prodid);
		$urlannonce = $annonce_object->get_annonce_url();
		$is_owner = ($annonce_object->id_utilisateur == vn($_SESSION['session_utilisateur']['id_utilisateur']));
		if (empty($GLOBALS['site_parameters']['avis_show_on_all_langages'])) {
			// Affichage des avis que pour la langue de l'interface.
			$sql_cond .= " AND a.lang='" . nohtml_real_escape_string($_SESSION['session_langue']) . "'";
		}
		$sqlAvis = "SELECT a.*, u.pseudo
			FROM peel_avis a
			LEFT JOIN peel_utilisateurs u ON a.id_utilisateur = u.id_utilisateur AND " . get_filter_site_cond('utilisateurs', 'u') . "
			WHERE a.ref='" . intval($prodid) . "' AND a.etat='1' AND " . ($mode == 'avis'?"a.note>-99":"a.note=-99") . " AND a.avis!='' ".$sql_cond;
		if (!empty($campaign_id)) {
			$sqlAvis .= " AND item_id=".intval($campaign_id);
		} else {
			$sqlAvis .= " AND item_id=''";
		}
		$sqlAvis .= "
			ORDER BY datestamp DESC, id ASC";
		$tpl->assign('ad_admin_edit_option', !empty($annonce_object) && a_priv('admin*'));
		if ($mode == 'avis') {
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD']);
			$tpl->assign('STR_MODULE_AVIS_OPINION_POSTED_BY', $GLOBALS['STR_MODULE_AVIS_OPINION_POSTED_BY']);
		} else {
			$tpl->assign('STR_MODULE_ANNONCES_AVIS_NO_OPINION_FOR_THIS_AD', $GLOBALS['STR_MODULE_ANNONCES_AVIS_NO_NEWS_FOR_THIS_AD']);
			$tpl->assign('STR_MODULE_AVIS_OPINION_POSTED_BY', $GLOBALS['STR_MODULE_AVIS_NEWS_POSTED_BY']);
		}
		$tpl->assign('STR_MODULE_ANNONCES_BACK_TO_AD', $GLOBALS['STR_MODULE_ANNONCES_BACK_TO_AD']);
	} else {
		return null;
	}
	$tpl->assign('is_owner', $is_owner);
	
	$resAvis = query($sqlAvis);
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
		$qid .= " AND etat='1'";

		$id = query($qid);
		if($note = fetch_assoc($id)) {
			$avisnote = number_format($note['average_rating'], 0);
		}

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
				$pseudo = StringMb::html_entity_decode_if_needed($Avis['pseudo']);
			} elseif (!empty($Avis['prenom'])) {
				$pseudo = StringMb::html_entity_decode_if_needed($Avis['prenom']);
			} else {
				$pseudo = '[...]';
			}
			$tpl_results[] = array('i' => $i,
				'pseudo' => $pseudo,
				'date' => get_formatted_date($Avis['datestamp'], 'short', true),
				'avis' => $Avis['avis'],
				'note' => $Avis['note'],
				'id' => $Avis['id'],
				'edit_allowed' => ((!empty($GLOBALS['site_parameters']['allow_edit_and_suppr_avis_by_owner']) && vn($_SESSION['session_utilisateur']['id_utilisateur']) == $Avis['id_utilisateur']) || (!empty($GLOBALS['site_parameters']['edit_avis_by_owner']) && $is_owner)),
				'abuse_report_link' => (check_if_module_active('annonces')?get_abuse_report_link($Avis['id'], false, 'avis'):'')
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
		
		$tpl->assign('display_nb_vote_graphic_view', vn($GLOBALS['site_parameters']['display_nb_vote_graphic_view']) && $mode == 'avis');
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
		$message = $GLOBALS['STR_REQUEST_OK'] . '<br />';
		$message .= '<a href="' . $annonce_object->get_annonce_url() . '" >' . $GLOBALS['STR_MODULE_ANNONCES_BACK_TO_AD'] . '</a>';
	} else {
		$message = $GLOBALS['STR_REQUEST_OK'];
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
	return  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $message))->fetch();
}


/**
 * Met à jour l'avis $id avec les nouvelles valeurs contenues dans $frm
 *
 * @param array $frm Array with all fields data
 * @return
 */
function maj_avis($frm)
{
	$qid = query("UPDATE peel_avis SET
			avis='" . nohtml_real_escape_string($frm['avis']) . "'
			".(isset($frm['note'])?", note='" . nohtml_real_escape_string($frm['note']) . "'":"")."
			, etat='" . nohtml_real_escape_string($frm['etat']) . "'
			" . ($frm['etat']==1 ? ", date_validation=IF(YEAR(date_validation)>0,date_validation,'" . nohtml_real_escape_string(date('Y-m-d H:i:s', time())) . "')": "") . "
		WHERE id='" . intval($frm['id']) . "'");
}
















