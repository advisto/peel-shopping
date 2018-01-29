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
// $Id: fonctions.php 55928 2018-01-26 17:31:15Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Renvoie les éléments de menu affichables
 *
 * @param array $params
 * @return
 */
function commerciale_hook_admin_menu_items($params) {
	$result = array();
	if (a_priv('admin_users,admin_finance,admin_operations,admin_productsline', true)) {
		$result['menu_items']['users_sales'][$GLOBALS['wwwroot_in_admin'] . '/modules/commerciale/administrer/list_admin_contact_planified.php'] = $GLOBALS["STR_ADMIN_MENU_USERS_CLIENTS_TO_CONTACT"];
		$result['menu_items']['users_sales'][$GLOBALS['administrer_url'] . '/utilisateurs.php?mode=search&commercial=' . $_SESSION['session_utilisateur']['id_utilisateur']] = sprintf($GLOBALS["STR_ADMIN_MENU_USERS_CLIENTS_PER_SALESMAN"], vb($_SESSION['session_utilisateur']['pseudo']));
	}
	return $result;
}

/**
 * Affiche la liste des clients à contacter par les administrateurs
 *
 * @param array $recherche Array with all fields data
 * @param boolean $return_mode
 * @return
 */
function affiche_list_admin_contact($recherche = null, $return_mode = false)
{
	$output = '';
	$sql_cond = array();
	if (!empty($recherche)) {
		if (!empty($recherche['login_to_contact'])) {
			// Gestion de la personne à contacter : login
			$sql_cond[] = ' u.pseudo LIKE "' . nohtml_real_escape_string(vb($recherche['login_to_contact'])) . '%" ';
		}

		if (!empty($recherche['nom_to_contact'])) {
			// Gestion de la personne à contacter : nom
			$sql_cond[] = ' u.nom_famille  LIKE "' . nohtml_real_escape_string(vb($recherche['nom_to_contact'])) . '%" ';
		}

		if (!empty($recherche['account_type'])) {
			// Gestion en fonction du type de compte
			$sql_cond[] = ' CONCAT("+",u.priv,"+") LIKE "%+' . nohtml_real_escape_string(vb($recherche['account_type'])) . '+%" ';
		}

		if (!empty($recherche['admin_id'])) {
			// Gestion en fonction de l'admin
			$sql_cond[] = ' c.admin_id = "' . intval(vn($recherche['admin_id'])) . '" ';
		}

		if (!empty($recherche['ad_date'])) {
			// Gestion en fonction de la date
			$sql_cond[] = ' timestamp LIKE "' . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($recherche['ad_date']))) . '%" ';
		}

		if (!empty($recherche['form_contact_planified_reason'])) {
			// Gestion de la personne à contacter : nom
			$sql_cond[] = ' c.reason="' . nohtml_real_escape_string(vb($recherche['form_contact_planified_reason'])) . '" ';
		}

		if (!empty($recherche['form_contact_planified_actif'])) {
			// Gestion de la planification actif
			$sql_cond [] = ' c.actif="' . nohtml_real_escape_string(vb($recherche['form_contact_planified_actif'])) . '"';
		}
	}

	$query_contact = 'SELECT u.id_utilisateur AS contact_id, u.nom_famille AS contact_name, u.prenom AS contact_firstname, u.pseudo AS contact_login, u.etat AS contact_valid, u_admin.pseudo AS pseudo_admin, c.*
		FROM peel_admins_contacts_planified c
		LEFT JOIN peel_utilisateurs u_admin ON u_admin.id_utilisateur = c.admin_id AND ' . get_filter_site_cond('utilisateurs', 'u_admin') . '
		LEFT JOIN peel_utilisateurs u ON u.id_utilisateur = c.user_id AND ' . get_filter_site_cond('utilisateurs', 'u') . '
		' . (!empty($sql_cond)?'WHERE ' . implode(' AND ', $sql_cond):'');

	$Links = new Multipage($query_contact, 'liste_contact');
	$HeaderTitlesArray = array(' ', $GLOBALS["STR_BY"], $GLOBALS["STR_MODULE_COMMERCIAL_ADMIN_FORECASTED_DATE"], $GLOBALS["STR_MODULE_COMMERCIAL_ADMIN_LAST_CONTACT"], $GLOBALS["STR_MODULE_COMMERCIAL_ADMIN_PERSON_TO_CONTACT"], $GLOBALS["STR_ADMIN_REASON"], $GLOBALS["STR_STATUS"], $GLOBALS["STR_COMMENTS"], $GLOBALS["STR_MODULE_COMMERCIAL_ADMIN_TAKE_CONTACT"]);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$results_array = $Links->Query();

	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/commercialeAdmin_list_contact.tpl');
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('login_to_contact', vb($recherche['login_to_contact']));
	$tpl->assign('nom_to_contact', vb($recherche['nom_to_contact']));
	$tpl->assign('priv_options', get_priv_options(vb($recherche['account_type'])));

	$tpl_admin_options = array();
	$sql = "SELECT *
		FROM peel_utilisateurs
		WHERE priv LIKE 'admin%' AND " . get_filter_site_cond('utilisateurs') . "";
	$res = query($sql);
	// Recherche des profils administrateur
	while ($account_admin = fetch_assoc($res)) {
		$tpl_admin_options[] = array('value' => $account_admin['id_utilisateur'],
			'issel' => $account_admin['id_utilisateur'] == vn($recherche['admin_id']),
			'name' => vb($account_admin['pseudo'])
			);
	}
	$tpl->assign('admin_options', $tpl_admin_options);
	$tpl->assign('form_contact_planified_reason', vb($recherche['form_contact_planified_reason']));
	$tpl->assign('form_contact_planified_actif', vb($recherche['form_contact_planified_actif']));

	$tpl->assign('ad_date', vb($recherche['ad_date']));

	if (empty($results_array)) {
		$tpl->assign('empty_results', true);
	} else {
		$tpl->assign('empty_results', false);
		$tpl->assign('links_multipage', $Links->GetMultipage());
		$tpl->assign('links_header_row', $Links->getHeaderRow());

		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $contact) {
			$tpl_last_date = null;
			$tpl_last_date_color = null;
			// On vérifie si il y a déjà eu des actions sur l'utilisateur
			$query = query('SELECT UNIX_TIMESTAMP(MAX(date)) AS last_contact_timestamp
			FROM peel_admins_actions
			WHERE id_membre = "' . intval(vn($contact['user_id'])) . '" AND action IN ("PHONE_EMITTED", "PHONE_RECEIVED", "SEND_EMAIL") AND ' . get_filter_site_cond('admins_actions', null, true) . '');
			$rep_query = fetch_assoc($query);
			if (!empty($rep_query['last_contact_timestamp'])) {
				if (($contact['timestamp'] > time()) && ($contact['timestamp'] > $rep_query['last_contact_timestamp'])) {
					$tpl_last_date_color = '#000000';
				} elseif (($contact['timestamp'] <= time()) && ($contact['timestamp'] < $rep_query['last_contact_timestamp'])) {
					$tpl_last_date_color = '#009900';
				} elseif (($contact['timestamp'] <= time()) && ($contact['timestamp'] > $rep_query['last_contact_timestamp'])) {
					$tpl_last_date_color = '#FF0000';
				}
				$tpl_last_date = get_formatted_date($rep_query['last_contact_timestamp']);
			}
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'id' => intval(vn($contact['id'])),
				'contact_login' => $contact['contact_login'],
				'pseudo_admin' => $contact['pseudo_admin'],
				'date' => get_formatted_date($contact['timestamp']),
				'last_date' => $tpl_last_date,
				'last_date_color' => $tpl_last_date_color,
				'contact_name' => vb($contact['contact_name']),
				'contact_firstname' => vb($contact['contact_firstname']),
				'edit_href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . intval(vn($contact['contact_id'])) . '&start=0',
				'contact_id' => intval(vn($contact['contact_id'])),
				'reason' => $contact['reason'],
					'etat_onclick' => 'change_status("contact", "' . $contact['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
				'etat_src' => $GLOBALS['administrer_url'] . '/images/' . (!empty($contact['actif']) && $contact['actif'] == 'FALSE' ? 'puce-blanche.gif' : 'puce-verte.gif'),
				'comments' => $contact['comments'],
				'email_send_href' => (check_if_module_active('webmail')?$GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/webmail_send.php?id_utilisateur=' . intval(vn($contact['contact_id'])):''),
				'appeler_href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . intval(vn($contact['contact_id'])) . '&start=0#phone_event',
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_LIST_TITLE', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_LIST_TITLE']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_LIST_EXPLAIN', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_LIST_EXPLAIN']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_LOGIN_TO_CONTACT', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_LOGIN_TO_CONTACT']);
	$tpl->assign('STR_ADMIN_INPUT_SEARCH', $GLOBALS['STR_ADMIN_INPUT_SEARCH']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_ACCOUNT_TYPE', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_ACCOUNT_TYPE']);
	$tpl->assign('STR_ADMIN_ANY', $GLOBALS['STR_ADMIN_ANY']);
	$tpl->assign('STR_ADMIN_ADMINISTRATOR', $GLOBALS['STR_ADMIN_ADMINISTRATOR']);
	$tpl->assign('STR_ADMIN_DATE', $GLOBALS['STR_ADMIN_DATE']);
	$tpl->assign('STR_ADMIN_INPUT_SEARCH', $GLOBALS['STR_ADMIN_INPUT_SEARCH']);
	$tpl->assign('STR_ADMIN_REASON', $GLOBALS['STR_ADMIN_REASON']);
	$tpl->assign('STR_ADMIN_ANY', $GLOBALS['STR_ADMIN_ANY']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTING_PROFILE', $GLOBALS['STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTING_PROFILE']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTED_BY_PRODUCT', $GLOBALS['STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTED_BY_PRODUCT']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_PAYMENT_EXPECTED', $GLOBALS['STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_PAYMENT_EXPECTED']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_FOLLOW_UP', $GLOBALS['STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_FOLLOW_UP']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_ACTIVE_TASK', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_ACTIVE_TASK']);
	$tpl->assign('STR_ADMIN_TO_DO', $GLOBALS['STR_ADMIN_TO_DO']);
	$tpl->assign('STR_ADMIN_DONE_OR_CANCELED', $GLOBALS['STR_ADMIN_DONE_OR_CANCELED']);
	$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_COLORS_EXPLAIN', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_COLORS_EXPLAIN']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_NOBODY_TO_CONTACT', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_NOBODY_TO_CONTACT']);
	$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
	$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_DELETE_CONTACT', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_DELETE_CONTACT']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_NO_CONTACT', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_NO_CONTACT']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
	$tpl->assign('STR_ADMIN_LOGIN', $GLOBALS['STR_ADMIN_LOGIN']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_EDIT_ACCOUNT', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_EDIT_ACCOUNT']);
	$tpl->assign('STR_NUMBER', $GLOBALS['STR_NUMBER']);
	$tpl->assign('STR_ADMIN_UTILISATEURS_SEND_EMAIL', $GLOBALS['STR_ADMIN_UTILISATEURS_SEND_EMAIL']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_CALL_CLIENT', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_CALL_CLIENT']);
	$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
	$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_DELETE_CONTACT', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_DELETE_CONTACT']);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_NAME_TO_CONTACT', $GLOBALS['STR_MODULE_COMMERCIAL_ADMIN_NAME_TO_CONTACT']);
	$output .= $tpl->fetch();
	if ($return_mode) {
		return $output;
	} elseif (!empty($output)) {
		echo $output;
	} else {
		return false;
	}
}

/**
 * Mise à jour de l'état d'une personne à contacter
 *
 * @param array $frm Array with all fields data
 * @return
 */
function update_state_contact($frm)
{
	if (!empty($frm['id']) && !empty($frm['etat'])) {
		query("UPDATE peel_admins_contacts_planified
			SET actif ='" . nohtml_real_escape_string(($frm['etat'] == 'TRUE'?'FALSE':'TRUE')) . "'
			WHERE id='" . intval(vn($frm['id'])) . "'");
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMERCIALE_MSG_STATUS_OK']))->fetch();
	}
}

/**
 * Supprime l'action de  contact de la personne à contacter
 *
 * @param integer $id
 * @return
 */
function delete_contact($id)
{
	if (!empty($id)) {
		query("DELETE FROM peel_admins_contacts_planified
			WHERE id='" . intval(vn($id)) . "'");
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_COMMERCIALE_MSG_DELETED_OK'], intval(vn($id)))))->fetch();
	}
}

/**
 * Affiche dans la fiche utilisateur la partie du formulaire de contact
 *
 * @param integer $id_user
 * @param boolean $return_mode
 * @return
 */
function affiche_form_contact_user($id_user, $return_mode = false)
{
	$output = '';
	$query = query("SELECT u.pseudo AS pseudo_user
		FROM peel_utilisateurs u
		WHERE u.id_utilisateur='" . intval(vn($id_user)) . "' AND " . get_filter_site_cond('utilisateurs', 'u') . "");
	$rep_query = fetch_assoc($query);

	$query_contact = 'SELECT acp.*, u.pseudo, u.id_utilisateur
		FROM peel_admins_contacts_planified acp
		LEFT JOIN peel_utilisateurs u ON u.id_utilisateur = acp.admin_id AND ' . get_filter_site_cond('utilisateurs', 'u') . '
		WHERE user_id="' . intval(vn($id_user)) . '"';
	$Links = new Multipage($query_contact, 'liste_contact');
	$HeaderTitlesArray = array(' ', $GLOBALS["STR_ADMIN_ADMINISTRATOR"], $GLOBALS["STR_DATE"], $GLOBALS["STR_ADMIN_REASON"], $GLOBALS["STR_COMMENTS"]);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = 'timestamp';
	$Links->SortDefault = 'ASC';
	$Links->allow_get_sort = false;
	$results_array = $Links->Query();

	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/commercialeAdmin_form_contact_user.tpl');
	$tpl->assign('action', get_current_url(true));
	$tpl->assign('id_user', intval(vn($id_user)));
	$tpl->assign('edit_src', $GLOBALS['administrer_url'] . '/images/b_edit.png');
	$tpl->assign('are_results', !empty($results_array));
	if (!empty($results_array)) {
		$tpl->assign('modif_action', get_current_url(false) . '?mode=modif&id_utilisateur=' . intval(vn($id_user)) . '&start=' . (isset($_GET['start']) ? $_GET['start'] : 0));
		$tpl->assign('links_header_row', $Links->getHeaderRow());
		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $contact) {
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'id' => intval(vn($contact['id'])),
				'href' => get_current_url(false) . '?id_utilisateur=' . intval(vn($id_user)) . '&id_contact_planified=' . intval(vn($contact['id'])) . '&mode=modif#contact_planified',
				'pseudo' => vb($contact['pseudo']),
				'date' => date(('Y-m-d'), $contact['timestamp']),
				'reason' => vb($contact['reason']),
				'comments' => vb($contact['comments']),
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	// Si id_contact_planified contact existe, cela signifie qu'il y a une demande de contact à modifier, donc nous l'affichons
	if (!empty($_GET['id_contact_planified'])) {
		$q_contact_edit = query('SELECT acp.*, u.pseudo
			FROM peel_admins_contacts_planified acp
			INNER JOIN peel_utilisateurs u ON u.id_utilisateur = acp.user_id AND ' . get_filter_site_cond('utilisateurs', 'u') . '
			WHERE acp.id="' . intval(vn($_GET['id_contact_planified'])) . '"
			LIMIT 1');
		if ($r_contact_edit = fetch_assoc($q_contact_edit)) {
			$tpl->assign('id_contact_planified', intval(vn($_GET['id_contact_planified'])));
			$tpl->assign('rce', array('date' => get_formatted_date($r_contact_edit['timestamp']),
					'reason' => $r_contact_edit['reason'],
					'comments' => vb($r_contact_edit['comments']),
					));
		}
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS["STR_BEFORE_TWO_POINTS"]);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_CONTACT_TITLE', sprintf($GLOBALS["STR_MODULE_COMMERCIAL_ADMIN_CONTACT_TITLE"], vb($rep_query['pseudo_user'])));
	$tpl->assign('STR_COMMENTS', $GLOBALS["STR_COMMENTS"]);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_FOLLOW_UP', $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_FOLLOW_UP"]);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_PAYMENT_EXPECTED', $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_PAYMENT_EXPECTED"]);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTED_BY_PRODUCT', $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTED_BY_PRODUCT"]);
	$tpl->assign('STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTING_PROFILE', $GLOBALS["STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTING_PROFILE"]);
	$tpl->assign('STR_ADMIN_REASON', $GLOBALS["STR_ADMIN_REASON"]);
	$tpl->assign('STR_VALIDATE', $GLOBALS["STR_VALIDATE"]);
	$tpl->assign('STR_DELETE', $GLOBALS["STR_DELETE"]);
	$tpl->assign('STR_CHOOSE', $GLOBALS["STR_CHOOSE"]);
	$tpl->assign('STR_MODULE_COMMERCIAL_ADMIN_EDIT_PLANIFIED_CONTACT', $GLOBALS["STR_MODULE_COMMERCIAL_ADMIN_EDIT_PLANIFIED_CONTACT"]);

	$output .= $tpl->fetch();
	if ($return_mode) {
		return $output;
	} elseif (!empty($output)) {
		echo $output;
	} else {
		return false;
	}
}

/**
 * Fonction d'insertion et de mise à jour des dates de contact
 *
 * @param array $frm Array with all fields data
 * @return
 */
function create_or_update_contact_planified($frm)
{
	// Si $frm['form_edit_contact_planified_id'] existe, cela signifie que nous sommes en mode mise à jour
	$output = '';
	if (!empty($frm['form_edit_contact_planified_id'])) {
		if(empty($frm['form_edit_contact_planified_date'])) {
			$frm['form_edit_contact_planified_date'] = date('d-m-Y', time());
		}
		$timestamp_planified_contact = mktime(0, 0, 0, intval(StringMb::substr($frm['form_edit_contact_planified_date'], 3, 2)), intval(StringMb::substr($frm['form_edit_contact_planified_date'], 0, 2)), intval(StringMb::substr($frm['form_edit_contact_planified_date'], 6, 4)));
		query('UPDATE peel_admins_contacts_planified
			SET `timestamp` = "' . nohtml_real_escape_string(vb($timestamp_planified_contact)) . '",
			reason = "' . nohtml_real_escape_string(vb($frm['form_edit_contact_planified_reason'])) . '",
			comments = "' . nohtml_real_escape_string(vb($frm['form_edit_contact_planified_comment'])) . '"
			WHERE id = "' . intval($frm['form_edit_contact_planified_id']) . '"');
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_COMMERCIALE_MSG_CONTACT_PLANIFIED_UPDATED_OK'], intval(vn($_GET['id_contact_planified'])))))->fetch();
	} elseif ($frm['form_edit_contact_user_id']) {
		if(empty($frm['form_contact_planified_date'])) {
			$frm['form_contact_planified_date'] = date('d-m-Y', time());
		}
		$timestamp_planified_contact = mktime(0, 0, 0, intval(StringMb::substr($frm['form_contact_planified_date'], 3, 2)), intval(StringMb::substr($frm['form_contact_planified_date'], 0, 2)), intval(StringMb::substr($frm['form_contact_planified_date'], 6, 4)));
		query('INSERT INTO peel_admins_contacts_planified (user_id, admin_id, timestamp, reason, comments)
			VALUES(
				' . intval($frm['form_edit_contact_user_id']) . ',
				' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . ',
				"' . nohtml_real_escape_string(vb($timestamp_planified_contact)) . '",
				"' . nohtml_real_escape_string(vb($frm['form_contact_planified_reason'])) . '",
				"' . nohtml_real_escape_string(vb($frm['form_contact_planified_comment']))
			 . '")');
		$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMERCIALE_MSG_CONTACT_PLANIFIED_CREATED_OK']))->fetch();
	}
	return $output;
}

/**
 * Fonction de suppression d'une planification de contact client
 *
 * @param integer $form_edit_contact_planified_id
 * @return
 */
function delete_contact_planified($form_edit_contact_planified_id)
{
	$output = '';
	if (!empty($form_edit_contact_planified_id)) {
		query('DELETE FROM peel_admins_contacts_planified 
			WHERE id  = "' . intval(vn($form_edit_contact_planified_id)) . '"');
		$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_COMMERCIALE_MSG_CONTACT_PLANIFIED_DELETED_OK']))->fetch();
	}
	return $output;
}

