<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: connexion_user_liste.php 36927 2013-05-23 16:15:39Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_connexion_user_liste.tpl');
$tpl->assign('action', get_current_url(false));
$tpl->assign('date', vb($_GET['date']));
$tpl->assign('user_ip', vb($_GET['user_ip']));
$tpl->assign('client_info', vb($_GET['client_info']));
$tpl->assign('user_id', vb($_GET['user_id']));
$tpl->assign('action_maj', get_current_url(false) . '?mode=maj_statut');
$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF']));

if (!empty($results_array)) {
	$tpl_results = array();
	$HeaderTitlesArray = array('id' => $GLOBALS["STR_ADMIN_ID"], 'date' => $GLOBALS['STR_DATE'], 'user_ip' => $GLOBALS["STR_ADMIN_REMOTE_ADDR"], 'user_login' => $GLOBALS["STR_ADMIN_LOGIN"], 'user_id' => $GLOBALS["STR_ADMIN_USER"]);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;

	$tpl->assign('links_header_row', $Links->getHeaderRow());

	$i = 0;
	foreach ($results_array as $connexion) {
		$current_user = get_user_information($connexion['user_id']);
		$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
			'id' => $connexion['id'],
			'date' => get_formatted_date($connexion['date']),
			'ip' => (!a_priv('demo') ? long2ip($connexion['user_ip']) : '0.0.0.0 [demo]'),
			'user_id' => $connexion['user_id'],
			'pseudo' => $current_user['pseudo'],
			'prenom' => $current_user['prenom'],
			'nom_famille' => $current_user['nom_famille'],
			'user_login_displayed' => (!a_priv('demo')?$connexion['user_login']:'private [demo]')
			);
		$i++;
	}
	$tpl->assign('results', $tpl_results);
	$tpl->assign('links_multipage', $Links->GetMultipage());
}
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_CONNEXION_USER_TITLE', $GLOBALS['STR_ADMIN_CONNEXION_USER_TITLE']);
$tpl->assign('STR_ADMIN_DATE', $GLOBALS['STR_ADMIN_DATE']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_REMOTE_ADDR', $GLOBALS['STR_ADMIN_REMOTE_ADDR']);
$tpl->assign('STR_PSEUDO', $GLOBALS['STR_PSEUDO']);
$tpl->assign('STR_ADMIN_USER', $GLOBALS['STR_ADMIN_USER']);
$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
$tpl->assign('STR_ADMIN_CONNEXION_NOTHING_FOUND', $GLOBALS['STR_ADMIN_CONNEXION_NOTHING_FOUND']);
echo $tpl->fetch();

?>