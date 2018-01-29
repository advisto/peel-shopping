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
 * Initialisation des variables générales après exécution de configuration.inc.php
 *
 * @param array $params
 * @return
 */
function webmail_hook_configuration_end($params) {
	if (!IN_INSTALLATION && defined('IN_PEEL_ADMIN')) {
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/modules/webmail/administrer/function.js';
	}
}

/**
 * Cette fonction permet de retourner a partir de l'adresse IP, le Fournisseur d'Acces Internet appartenant a cet IP
 *
 * @param integer $ip
 * @return
 */
function getFAI($ip)
{
	$host = @gethostbyaddr($ip);
	if (StringMb::strpos("aol", $host)) {
		$fai = "AOL";
	} elseif (StringMb::strpos("bluewin", $host)) {
		$fai = "Bluewin";
	} elseif (StringMb::strpos("cablecom", $host)) {
		$fai = "Cablecom - swissonline";
	} elseif (StringMb::strpos("hispeed", $host)) {
		$fai = "Cablecom - swissonline";
	} elseif (StringMb::strpos("coltfrance", $host)) {
		$fai = "COLT France";
	} elseif (StringMb::strpos("club-internet", $host)) {
		$fai = "Club Internet";
	} elseif (StringMb::strpos("proxad", $host)) {
		$fai = "Free";
	} elseif (StringMb::strpos("intergga", $host)) {
		$fai = "InterGGA";
	} elseif (StringMb::strpos("noos", $host)) {
		$fai = "Noos";
	} elseif (StringMb::strpos("securepop", $host)) {
		$fai = "SecurePoP";
	} elseif (StringMb::strpos("adslplus", $host)) {
		$fai = "Sunrise";
	} elseif (StringMb::strpos("freesurf", $host)) {
		$fai = "Sunrise";
	} elseif (StringMb::strpos("tiscali.fr", $host)) {
		$fai = "Tiscali France";
	} elseif (StringMb::strpos("tiscali.ch", $host)) {
		$fai = "Tiscali Suisse";
	} elseif (StringMb::strpos("tele2.fr", $host)) {
		$fai = "Tele2 France";
	} elseif (StringMb::strpos("videotron", $host)) {
		$fai = "Vidéotron";
	} elseif (StringMb::strpos("sympatico", $host)) {
		$fai = "Sympatico";
	} elseif (StringMb::strpos("vtx", $host)) {
		$fai = "VTX";
	} elseif (StringMb::strpos("wanadoo", $host)) {
		$fai = "Wanadoo";
	} elseif (StringMb::strpos("proxad", $host)) {
		$fai = "Free";
	}
	if(!empty($fai)) {
		return $host . ' (' . $fai.')';
	} else {
		return $host;
	}
}
	

/**
 * Cette fonction permet de sauvegarder les emails du formulaire de contact du site en base de données dans la table webmail
 *
 * @param integer $ip
 * @return
 */
function save_mail_db($frm)
{
	
	$ip = ipGet();
	$Ipclient = $ip . " / " . getFAI($ip);
	if(!empty($frm['site_id'])) {
		$site_id = $frm['site_id'];
	} else {
		$site_id = $GLOBALS['site_id'];
	}
	$sql = 'INSERT INTO peel_webmail SET
		titre = "' . nohtml_real_escape_string(vb($frm['sujet'])) . '"
		, message = "' . nohtml_real_escape_string(vb($frm['texte']) . "\n" . vb($frm['adresse']) . ' ' . vb($frm['code_postal']) . ' ' . vb($frm['ville']) . ' ' . vb($frm['pays'])) . '"
		, nom = "' . nohtml_real_escape_string(vb($frm['nom']) . (!empty($frm['societe']) ? ' / ' . vb($frm['societe']) : '')) . '"
		, prenom = "' . nohtml_real_escape_string(vb($frm['prenom'])) . '"
		, telephone = "' . nohtml_real_escape_string(vb($frm['telephone'])) . '"
		, dispo = "' . nohtml_real_escape_string(vb($frm['dispo'])) . '"
		, ip = "' . nohtml_real_escape_string($Ipclient) . '"
		, email = "' . nohtml_real_escape_string(vb($frm['email'])) . '"
		, date = "' . nohtml_real_escape_string(date('Y-m-d')) . '"
		, heure = "' . nohtml_real_escape_string(date('H:i:s')) . '"
		, site_id = "' . nohtml_real_escape_string(get_site_id_sql_set_value($site_id)) . '"';
	if (!empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
		$sql .= "
		, id_user = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'";
	}
	if (!empty($GLOBALS['site_parameters']['user_contact_file_upload']) && !empty($frm['file'])) {
		$sql .= "
		, file = '" . nohtml_real_escape_string($frm['file']) . "'";
	}
	if (!empty($frm['commande_id'])) {
		$sql .= "
		, commande_id = '" . intval($frm['commande_id']) . "'";
	}
	query($sql);
}

