<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: direaunami.php 39162 2013-12-04 10:37:44Z gboussin $
//

include("../../configuration.inc.php");

if (!is_module_direaunami_active()) {
	// This module is not activated => we redirect to the homepage
	redirect_and_die($GLOBALS['wwwroot']."/");
}

if (!empty($_POST)) {
	$yname = vb($_POST['yname']);//sender
	$yemail = vb($_POST['yemail']);//sender

	$items = 5;
	$fname = array();
	$femail = array();
	for ($numitems = 0; $numitems < $items; $numitems++) {
		if(!empty($_POST['femail'][$numitems])){
			$fname[$numitems] = vb($_POST['fname'][$numitems]);//receiver
			$femail[$numitems] = vb($_POST['femail'][$numitems]);//receiver
		}
	}

	$referer = vb($_POST['referer']);//URL
	$comments = vb($_POST['comments']);//COmments
} else {
	$yname = trim(vb($_SESSION['session_utilisateur']['prenom']).' '.vb($_SESSION['session_utilisateur']['nom_famille']));//sender
	$yemail = vb($_SESSION['session_utilisateur']['email']);//sender
}


define('IN_TELL_FRIEND', true);

include($GLOBALS['repertoire_modele'] . "/haut.php");

switch (vb($_POST['mode'])) {
	case "send" :
		if (empty($_SERVER['HTTP_USER_AGENT']) || $_SERVER['REQUEST_METHOD'] != "POST" || empty($_SESSION['session_init_form_direaunami'])) {
			// Protection du formulaire contre les robots
			die();
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/direaunami_send.tpl');
		$tpl->assign('STR_TELL_FRIEND', $GLOBALS['STR_TELL_FRIEND']);
		$tpl->assign('STR_MODULE_DIREAUNAMI_MSG_ERR_FRIEND', $GLOBALS['STR_MODULE_DIREAUNAMI_MSG_ERR_FRIEND']);
		$tpl->assign('STR_MODULE_DIREAUNAMI_MSG_FRIEND_SEND', $GLOBALS['STR_MODULE_DIREAUNAMI_MSG_FRIEND_SEND']);
		$tpl->assign('STR_MODULE_DIREAUNAMI_BACK_REFERER', $GLOBALS['STR_MODULE_DIREAUNAMI_BACK_REFERER']);
		if (empty($yname) || empty($fname[0]) || empty($femail[0]) || empty($yemail)) {
			$tpl->assign('is_error', true);
		} else {
			$tpl->assign('is_error', false);
			$items = 5;
			if (String::strpos($referer, $GLOBALS['wwwroot']) === 0) {
				$product_link = $referer;
			} elseif (String::substr($referer, 0 , 1) == '/') {
				// Referer court ou tentative de hack
				$product_link = $GLOBALS['wwwroot'] . $referer;
			} else {
				// Tentative de hack a priori
				$product_link = $GLOBALS['wwwroot'];
			}
			for ($numitems = 0; $numitems < $items; $numitems++) {
				if ((!empty($fname[$numitems])) && (!empty($femail[$numitems]))) {
					$custom_template_tags['PSEUDO'] = $yname;
					$custom_template_tags['NOM_FAMILLE'] = $fname[$numitems];
					$custom_template_tags['PRODUCT_LINK'] = $product_link;
					$custom_template_tags['SUPPORT'] = $GLOBALS['support'];
					$custom_template_tags['COMMENTS'] = $comments;
					if (empty($_SESSION['session_form_direaunami_sent'])) {
						$_SESSION['session_form_direaunami_sent'] = 0;
					}
					if ($_SESSION['session_form_direaunami_sent'] < 10) {
						// Limitation pour éviter spam : Un utilisateur peut envoyer 10 fois un email dire à un ami par session
						send_email($femail[$numitems], '', '', 'direaunami_sent', $custom_template_tags, 'html', $GLOBALS['support'], false, false, true, $yemail);
						$_SESSION['session_form_direaunami_sent']++;
					}
				}
			}
			$tpl->assign('referer', $referer);
		}
		echo $tpl->fetch();
		break;
	default :
		$_SESSION['session_init_form_direaunami']=true;
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/direaunami.tpl');
		$tpl->assign('action', get_current_url(false));
		
		if (!empty($_SERVER['HTTP_REFERER']) && String::strpos($_SERVER['HTTP_REFERER'], $GLOBALS['wwwroot']) === 0) {
			// $_SERVER['HTTP_REFERER'] n'est pas toujours disponible, ça dépend du réglage du navigateur
			// Pour éviter des hacks, on ne prend $_SERVER['HTTP_REFERER'] que si il contient $GLOBALS['wwwroot']
			$referer = $_SERVER['HTTP_REFERER'];
		} elseif (!empty($_SESSION['session_referer'])) {
			// Variable de session qui peut être initialisée dans produit_details.php et article_details.php
			// => ATTENTION : si on recharge la page direaunami après avoir été ailleurs, on se trompe de referer
			// Cette méthode n'est donc pas privilégiée
			$referer = $_SESSION['session_referer'];
		} else {
			// Pas de referer trouvé
			$referer = $GLOBALS['wwwroot'];
		}
		$tpl->assign('yname', vb($yname));
		$tpl->assign('yemail', vb($yemail));
		$tpl->assign('referer', $referer);
		$tpl->assign('STR_TELL_FRIEND', $GLOBALS['STR_TELL_FRIEND']);
		$tpl->assign('STR_MODULE_DIREAUNAMI_MSG_TELL_FRIEND', $GLOBALS['STR_MODULE_DIREAUNAMI_MSG_TELL_FRIEND']);
		$tpl->assign('STR_YOUR_NAME', $GLOBALS['STR_YOUR_NAME']);
		$tpl->assign('STR_YOUR_EMAIL', $GLOBALS['STR_YOUR_EMAIL']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_THEIR_NAMES', $GLOBALS['STR_THEIR_NAMES']);
		$tpl->assign('STR_THEIR_EMAILS', $GLOBALS['STR_THEIR_EMAILS']);
		$tpl->assign('STR_COMMENTS', $GLOBALS['STR_COMMENTS']);
		$tpl->assign('STR_SEND', $GLOBALS['STR_SEND']);
		$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
		echo $tpl->fetch();
		break;
}

include($GLOBALS['repertoire_modele'] . "/bas.php");

?>