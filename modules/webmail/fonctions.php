<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 36232 2013-04-05 13:16:01Z gboussin $
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
	$tmp = explode(".", $host);
	$serv = '(www.' . ((!empty($tmp[sizeof($tmp) - 2]))?($tmp[sizeof($tmp) - 2] . '.'):'') . $tmp[sizeof($tmp) - 1] . ')';
	if (String::strpos("aol", $host)) {
		$fai = "AOL";
	} elseif (String::strpos("bluewin", $host)) {
		$fai = "Bluewin $serv";
	} elseif (String::strpos("cablecom", $host)) {
		$fai = "Cablecom - swissonline $serv";
	} elseif (String::strpos("hispeed", $host)) {
		$fai = "Cablecom - swissonline $serv";
	} elseif (String::strpos("coltfrance", $host)) {
		$fai = "COLT France $serv";
	} elseif (String::strpos("club-internet", $host)) {
		$fai = "Club Internet $serv";
	} elseif (String::strpos("proxad", $host)) {
		$fai = "Free $serv";
	} elseif (String::strpos("intergga", $host)) {
		$fai = "InterGGA $serv";
	} elseif (String::strpos("noos", $host)) {
		$fai = "Noos $serv";
	} elseif (String::strpos("securepop", $host)) {
		$fai = "SecurePoP $serv";
	} elseif (String::strpos("adslplus", $host)) {
		$fai = "Sunrise $serv";
	} elseif (String::strpos("freesurf", $host)) {
		$fai = "Sunrise $serv";
	} elseif (String::strpos("tiscali.fr", $host)) {
		$fai = "Tiscali France $serv";
	} elseif (String::strpos("tiscali.ch", $host)) {
		$fai = "Tiscali Suisse $serv";
	} elseif (String::strpos("tele2.fr", $host)) {
		$fai = "Tele2 France $serv";
	} elseif (String::strpos("videotron", $host)) {
		$fai = "Vidéotron $serv";
	} elseif (String::strpos("sympatico", $host)) {
		$fai = "Sympatico $serv";
	} elseif (String::strpos("vtx", $host)) {
		$fai = "VTX $serv";
	} elseif (String::strpos("wanadoo", $host)) {
		$fai = "Wanadoo $serv";
	} else {
		$fai = 'www.' . ((!empty($tmp[sizeof($tmp) - 2]))?($tmp[sizeof($tmp) - 2] . '.'):'') . $tmp[sizeof($tmp) - 1];
	}
	if ($fai == "www.proxad.net") {
		$fai = "www.free.fr";
	}
	if ($fai == "") {
		$fai = "Identification ";
	}
	return $fai;
}

/**
 * Cette fonction permet de sauvegarder les emails du formulaire de contact du site en basse de donnée dans la table webmail
 *
 * @param integer $ip
 * @return
 */
function save_mail_db($frm)
{
	$ip = ipGet();
	$Ipclient = "'" . getFAI($ip) . " / IP : " . $ip . " / " . date('d M Y H:i:s') . "'";
	$sql = 'INSERT INTO peel_webmail SET
		Titre = "' . nohtml_real_escape_string(vb($frm['sujet'])) . '"
		, Message = "' . nohtml_real_escape_string(str_replace(array("\r\n", "\n", "\r"), "<br/>", vb($frm['texte']))) . '"
		, Nom = "' . nohtml_real_escape_string(vb($frm['nom']) . (!empty($frm['societe']) ? ' / ' . vb($frm['societe']) : '')) . '"
		, Prenom = "' . nohtml_real_escape_string(vb($frm['prenom'])) . '"
		, telephone = "' . nohtml_real_escape_string(vb($frm['telephone'])) . '"
		, dispo = "' . nohtml_real_escape_string(vb($frm['dispo'])) . '"
		, Ip = "' . nohtml_real_escape_string($Ipclient) . '"
		, Email = "' . nohtml_real_escape_string(vb($frm['email'])) . '"
		, Date = "' . nohtml_real_escape_string(date('Y-m-d')) . '"
		, Heure = "' . nohtml_real_escape_string(date('H:i:s')) . '"';
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

?>