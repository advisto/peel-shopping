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
// $Id: list_admin_actions.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_users,admin_moderation");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$id = intval(vn($_REQUEST['id']));

if (!isset($form_error_object)) {
	$form_error_object = new FormError();
}

switch (vb($_REQUEST['mode'])) {
	case "supp":
		if (!empty($_POST['form_delete'])) {
			foreach($_POST['form_delete'] as $action_id) {
				delete_admin_action($action_id);
			}
		}
		echo affiche_list_admin_action(null, true);
		break;

	case "recherche":
		echo affiche_list_admin_action($_POST, true);
		break;

	default :
		echo affiche_list_admin_action($_GET, true);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * Affiche la liste des actions de moderation
 *
 * @param array $frm Array with all fields data
 * @param boolean $return_mode
 * @return
 */
function affiche_list_admin_action($frm = null, $return_mode = false)
{
	$output = '';
	$search_array = array();
	if (!empty($frm)) {
		// Recherche par id admin
		if (!empty($frm['admin_id'])) {
			$search_array[] = 'paa.id_user="' . intval(vn($frm['admin_id'])) . '" ';
		}
		// Recherche par type d'action
		if (!empty($frm['action_cat'])) {
			$search_array[] = 'paa.action="' . nohtml_real_escape_string(vb($frm['action_cat'])) . '" ';
		}
		// Recherche par id de membre
		if (!empty($frm['account']) && is_numeric($frm['account'])) {
			$search_array[] = 'paa.id_membre="' . intval($frm['account']) . '" ';
		} elseif (!empty($frm['account'])) {
			$search_array[] = '(pu2.email LIKE "%' . nohtml_real_escape_string($frm['account']) . '%" OR pu2.pseudo LIKE "%' . nohtml_real_escape_string($frm['account']) . '%")';
		}
		// Recherche par date
		if (!empty($frm['date_input1']) && !empty($frm['date'])) {
			$this_get = 'date';
			$this_sql_field = 'paa.date';
			$first_value = get_mysql_date_from_user_input($frm[$this_get . '_input1']);
			if ($frm[$this_get] == '1') {
				// Une valeur cherchée uniquement : le X
				$last_value = $first_value . ' 23:59:59';
			} elseif ($frm[$this_get] == '2') {
				// Si "a partir de...", on va recupérer tous les utilisateurs
				$last_value = '2030-12-31 23:59:59';
			} elseif ($frm[$this_get] == '3') {
				// Entre le jour X et le jour Y
				$last_value = str_replace('0000-00-00', '2030-12-31', get_mysql_date_from_user_input($frm[$this_get . '_input2']));
				$last_value .= ' 23:59:59';
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_CASE_NOT_FORECASTED'] . ' : ' . $frm[$this_get]))->fetch();
			}
			$this_cond_temp_expression = word_real_escape_string($this_sql_field) . '>="' . nohtml_real_escape_string($first_value) . '"';
			if ($last_value != '2030-12-31 23:59:59') {
				// On ne passe jamais ici normalement car on ne serait pas dans le cas "à partir du" - mais on laisse pour sécurité
				$this_cond_temp_expression .= ' AND ' . word_real_escape_string($this_sql_field) . '<"' . nohtml_real_escape_string($last_value) . '"';
			}
			$search_array[] = $this_cond_temp_expression;
		}
		// Recherche par mot
		if (!empty($frm['search']) && !empty($frm['type'])) {
			// Recherche par different mot
			$terms = build_search_terms($frm['search'], $frm['type']);
			$fields[] = 'paa.data';
			$fields[] = 'paa.raison';
			$fields[] = 'paa.remarque';
			$search_array[] = build_terms_clause($terms, $fields, $frm['type']);
		}
	}
	// Gestion des actions
	if (!empty($_GET['action_cat'])) {
		if ($_GET['action_cat'] == 'PHONE') {
			$search_array[] = 'paa.action LIKE "' . real_escape_string($_GET['action_cat']) . '%"';
		} else {
			$search_array[] = 'paa.action="' . real_escape_string($_GET['action_cat']) . '"';
		}
	}
	if (!empty($_GET['action_cat']) && $_GET['action_cat'] == 'PHONE') {
		$title = $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_CALLS_LIST'];
	} else {
		$title = $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_ALL_LIST'];
	}

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_list_admin_action.tpl');
	$GLOBALS['js_ready_content_array'][] = '
			display_input2_element("date");
';
	$tpl->assign('action', get_current_url(false));
	$tpl->assign('title', $title);
	$q = query('SELECT id_utilisateur, pseudo, email
		FROM peel_utilisateurs
		WHERE CONCAT("+",priv,"+") LIKE "%+admin%" AND ' . get_filter_site_cond('utilisateurs', null, true) . '');
	if (!empty($q)) {
		$tpl_options = array();
		while ($user_admin = fetch_assoc($q)) {
			$tpl_options[] = array('value' => $user_admin['id_utilisateur'],
				'issel' => !empty($frm['admin_id']) && $frm['admin_id'] == $user_admin['id_utilisateur'],
				'name' => (!a_priv('demo')?(!empty($user_admin['pseudo'])?$user_admin['pseudo']:$user_admin['email']):'private [demo]')
				);
		}
		$tpl->assign('options_ids', $tpl_options);
	}
	$tpl_options = array();
	$q = query('SELECT action
		FROM peel_admins_actions
		WHERE ' . get_filter_site_cond('admins_actions', null, true) . '
		GROUP BY action');
	while ($action = fetch_assoc($q)) {
		$tpl_options[] = array('value' => $action['action'],
			'issel' => !empty($frm['action_cat']) && $frm['action_cat'] == $action['action'],
			'name' => $action['action']
			);
	}
	$tpl->assign('options_actions', $tpl_options);
	$tpl->assign('account', vb($frm['account']));
	$tpl->assign('date', vb($frm['date']));
	$tpl->assign('date_input1', get_formatted_date(vb($frm['date_input1'])));
	$tpl->assign('date_input2', get_formatted_date(vb($frm['date_input2'])));
	$tpl->assign('search', vb($frm['search']));
	$tpl->assign('type', vb($frm['type']));
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_CALLS_EXPLAIN', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_CALLS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_MODERATOR', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_MODERATOR']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_NO_MODERATOR_WITH_ACTIONS_FOUND', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_NO_MODERATOR_WITH_ACTIONS_FOUND']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_ACTIONS', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_ACTIONS']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_CONCERNED_ACCOUNT', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_CONCERNED_ACCOUNT']);
	$tpl->assign('STR_ADMIN_DATE', $GLOBALS['STR_ADMIN_DATE']);
	$tpl->assign('STR_ADMIN_INPUT_SEARCH', $GLOBALS['STR_ADMIN_INPUT_SEARCH']);
	$tpl->assign('STR_ADMIN_DATE_ON', $GLOBALS['STR_ADMIN_DATE_ON']);
	$tpl->assign('STR_ADMIN_DATE_STARTING', $GLOBALS['STR_ADMIN_DATE_STARTING']);
	$tpl->assign('STR_ADMIN_DATE_BETWEEN_START', $GLOBALS['STR_ADMIN_DATE_BETWEEN_START']);
	$tpl->assign('STR_ADMIN_DATE_BETWEEN_AND', $GLOBALS['STR_ADMIN_DATE_BETWEEN_AND']);
	$tpl->assign('STR_SEARCH_ALL_WORDS', $GLOBALS['STR_SEARCH_ALL_WORDS']);
	$tpl->assign('STR_SEARCH_ANY_WORDS', $GLOBALS['STR_SEARCH_ANY_WORDS']);
	$tpl->assign('STR_SEARCH_EXACT_SENTENCE', $GLOBALS['STR_SEARCH_EXACT_SENTENCE']);
	$tpl->assign('STR_ADMIN_DATE_BETWEEN_AND', $GLOBALS['STR_ADMIN_DATE_BETWEEN_AND']);
	$tpl->assign('STR_ADMIN_CHECK_ALL', $GLOBALS['STR_ADMIN_CHECK_ALL']);
	$tpl->assign('STR_ADMIN_UNCHECK_ALL', $GLOBALS['STR_ADMIN_UNCHECK_ALL']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_DATA', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_DATA']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_DELETE_ACTION', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_DELETE_ACTION']);
	$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_NO_ACTION_FOUND', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_NO_ACTION_FOUND']);
	$tpl->assign('STR_ADMIN_ADMIN_ACTIONS_TEMPLATE', $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_TEMPLATE']);
	$tpl->assign('STR_ADMIN_REMARK', $GLOBALS['STR_ADMIN_REMARK']);
	$tpl->assign('STR_ADMIN_REASON', $GLOBALS['STR_ADMIN_REASON']);

	$sql = 'SELECT paa.id AS id, paa.action AS action, paa.data AS data, paa.raison AS raison, paa.remarque AS remarque, paa.date as date, pu1.pseudo AS pseudo_admin, pu2.pseudo AS pseudo_membre, pu1.id_utilisateur AS id_admin, pu1.email AS email_admin, pu2.id_utilisateur AS id_membre, pu2.email AS email_membre
		FROM peel_admins_actions paa
		LEFT JOIN peel_utilisateurs pu1 ON pu1.id_utilisateur = paa.id_user AND ' . get_filter_site_cond('utilisateurs', 'pu1') . '
		LEFT JOIN peel_utilisateurs pu2 ON pu2.id_utilisateur = paa.id_membre AND ' . get_filter_site_cond('utilisateurs', 'pu2') . '
		' . (!empty($search_array)?'WHERE ' . implode(' AND ', $search_array) . ' AND ' . get_filter_site_cond('admins_actions', 'paa', true):'');
	$Links = new Multipage($sql, 'affiche_liste_action_moderation', 50);
	$HeaderTitlesArray = array('', 'date' => $GLOBALS['STR_DATE'], 'id_user' => $GLOBALS['STR_BY'], 'action' => $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_DATE_ACTION_TYPE'], 'id_membre' => $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_CONCERNED_ACCOUNT'], $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_DATA']);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = 'paa.date';
	$Links->SortDefault = 'DESC';
	$results_array = $Links->Query();
	if (!empty($results_array)) {
		$tpl->assign('links_multipage', $Links->GetMultipage());
		$tpl->assign('links_header_row', $Links->getHeaderRow());

		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $actions) {
			$tpl_technical_code = null;
			$tpl_lang = null;
			if (!empty($actions['data'])) {
				if ($actions['action'] == 'SEND_EMAIL' && $actions['data'] != 'NO_TEMPLATE') {
					$template_infos = getTextAndTitleFromEmailTemplateLang(null, null, str_replace('template_', '', $actions['data']));
					$tpl_technical_code = $template_infos['technical_code'];
					if(empty($tpl_technical_code)) {
						$tpl_technical_code = $template_infos['name'];
					}
					$tpl_lang = $template_infos['lang'];
				}
			}

			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'id' => $actions['id'],
				'date' => get_formatted_date(vb($actions['date']), 'short', true),
				'action' => $actions['action'],
				'modif_admin_href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $actions['id_admin'],
				'admin' => (!a_priv('demo')?(!empty($actions['pseudo_admin']) ? $actions['pseudo_admin'] : $actions['email_admin']):'private [demo]'),
				'is_membre' => !empty($actions['id_membre']),
				'modif_membre_href' => $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $actions['id_membre'],
				'membre' => (!a_priv('demo')?(!empty($actions['pseudo_membre']) ? $actions['pseudo_membre'] : $actions['email_membre']):'private [demo]'),
				'raison' => $actions['raison'],
				'remarque' => $actions['remarque'],
				'data' => $actions['data'],
				'tpl_technical_code' => $tpl_technical_code,
				'tpl_lang' => $tpl_lang
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
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
 * Fonction permettant de supprimer une action en fonction de son id
 *
 * @param integer $action_id
 * @return
 */
function delete_admin_action($action_id)
{
	if (!empty($action_id)) {
		query('DELETE
			FROM peel_admins_actions
			WHERE id="' . intval(vn($action_id)) . '" AND ' . get_filter_site_cond('admins_actions', null, true) . '');
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_ADMIN_ACTIONS_MSG_DELETED_OK']))->fetch();
	}
}

