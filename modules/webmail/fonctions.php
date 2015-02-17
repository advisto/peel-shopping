<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 44077 2015-02-17 10:20:38Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
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
	if (String::strpos("aol", $host)) {
		$fai = "AOL";
	} elseif (String::strpos("bluewin", $host)) {
		$fai = "Bluewin";
	} elseif (String::strpos("cablecom", $host)) {
		$fai = "Cablecom - swissonline";
	} elseif (String::strpos("hispeed", $host)) {
		$fai = "Cablecom - swissonline";
	} elseif (String::strpos("coltfrance", $host)) {
		$fai = "COLT France";
	} elseif (String::strpos("club-internet", $host)) {
		$fai = "Club Internet";
	} elseif (String::strpos("proxad", $host)) {
		$fai = "Free";
	} elseif (String::strpos("intergga", $host)) {
		$fai = "InterGGA";
	} elseif (String::strpos("noos", $host)) {
		$fai = "Noos";
	} elseif (String::strpos("securepop", $host)) {
		$fai = "SecurePoP";
	} elseif (String::strpos("adslplus", $host)) {
		$fai = "Sunrise";
	} elseif (String::strpos("freesurf", $host)) {
		$fai = "Sunrise";
	} elseif (String::strpos("tiscali.fr", $host)) {
		$fai = "Tiscali France";
	} elseif (String::strpos("tiscali.ch", $host)) {
		$fai = "Tiscali Suisse";
	} elseif (String::strpos("tele2.fr", $host)) {
		$fai = "Tele2 France";
	} elseif (String::strpos("videotron", $host)) {
		$fai = "Vidéotron";
	} elseif (String::strpos("sympatico", $host)) {
		$fai = "Sympatico";
	} elseif (String::strpos("vtx", $host)) {
		$fai = "VTX";
	} elseif (String::strpos("wanadoo", $host)) {
		$fai = "Wanadoo";
	} elseif (String::strpos("proxad", $host)) {
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
	$sql = 'INSERT INTO peel_webmail SET
		Titre = "' . nohtml_real_escape_string(vb($frm['sujet'])) . '"
		, Message = "' . nohtml_real_escape_string(vb($frm['texte']) . "\n" . vb($frm['adresse']) . ' ' . vb($frm['code_postal']) . ' ' . vb($frm['ville']) . ' ' . vb($frm['pays'])) . '"
		, Nom = "' . nohtml_real_escape_string(vb($frm['nom']) . (!empty($frm['societe']) ? ' / ' . vb($frm['societe']) : '')) . '"
		, Prenom = "' . nohtml_real_escape_string(vb($frm['prenom'])) . '"
		, telephone = "' . nohtml_real_escape_string(vb($frm['telephone'])) . '"
		, dispo = "' . nohtml_real_escape_string(vb($frm['dispo'])) . '"
		, Ip = "' . nohtml_real_escape_string($Ipclient) . '"
		, Email = "' . nohtml_real_escape_string(vb($frm['email'])) . '"
		, Date = "' . nohtml_real_escape_string(date('Y-m-d')) . '"
		, Heure = "' . nohtml_real_escape_string(date('H:i:s')) . '"
		, site_id = "' . intval($GLOBALS['site_id']) . '"';
	if (!empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
		$sql .= "
		, id_user = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'";
	}
	if (!empty($frm['commande_id'])) {
		$sql .= "
		, commande_id = '" . intval($frm['commande_id']) . "'";
	}
	query($sql);
}

