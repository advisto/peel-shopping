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
// $Id: alertes_24h.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * warnAdminContactPlanified()
 *
 * @return
 */
function warnAdminContactPlanified ()
{
	// Condition AND u2.priv LIKE "%admin%" : Sécurité, si un contact planifié venait à être attribué par erreur à un non-admin, pas d'email envoyé
	$q = query('SELECT acp.*, u.email AS client_login, u.id_utilisateur AS client_id, u2.email AS admin_login, u2.email AS admin_email
		FROM `peel_admins_contacts_planified` acp
		LEFT JOIN `peel_utilisateurs` u  ON u.id_utilisateur = acp.user_id AND ' . get_filter_site_cond('utilisateurs', 'u') . '
		INNER JOIN `peel_utilisateurs` u2 ON u2.id_utilisateur = acp.admin_id AND CONCAT("+",u2.priv,"+") LIKE "%+admin%" AND ' . get_filter_site_cond('utilisateurs', 'u2') . '
		WHERE acp.timestamp BETWEEN UNIX_TIMESTAMP("' . date('Y-m-d 00:00:00') . '") AND UNIX_TIMESTAMP("' . date('Y-m-d 23:59:59') . '")');

	while ($result = fetch_assoc($q)) {
		if (empty($admins_contacts_array[$result['admin_email']])) {
			$admins_contacts_array[$result['admin_email']] = 'Contacts planifiés aujourd\'hui sur ' . $GLOBALS['wwwroot'] . ':<br />(<a href="' . $GLOBALS['wwwroot'] . '/modules/commerciale/administrer/list_admin_contact_planified.php?ad_date=' . get_formatted_date(time()) . '">Voir la liste des contacts planifiés</a>)<br /><br />';
		}
		$admins_contacts_array[$result['admin_email']] .= '<a href="' . $GLOBALS['administrer_url'] . '/utilisateurs.php?mode=modif&id_utilisateur=' . $result['client_id'] . '">' . $result['client_login'] . '</a> : ' . (!empty($result['comments'])?$result['comments']:'Pas de commentaire') . '<br />';
	}

	if (!empty($admins_contacts_array)) {
		if (!empty($admins_contacts_array)) {
			$sujet = 'Contacts planifiés sur ' . $GLOBALS['wwwroot'];
			foreach($admins_contacts_array as $admin_email => $admin_body_email) {
				send_email($admin_email, $sujet, $admin_body_email);
				send_email($GLOBALS['support'], 'Copie de ' . $admin_email . ' - ' . $sujet, $admin_body_email);
			}
		}
	}
}

warnAdminContactPlanified ();

