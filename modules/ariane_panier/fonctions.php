<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 38682 2013-11-13 11:35:48Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * ariane_panier()
 *
 * @return
 */
function ariane_panier()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('modules/ariane_panier.tpl');
	$tpl->assign('cart_logo_src', $GLOBALS['wwwroot'] . '/modules/ariane_panier/images/caddie_command.jpg');
	$tpl->assign('in_caddie', defined('IN_CADDIE'));
	$tpl->assign('was_in_caddie', $_SESSION['session_ariane_panier']['in_caddie']);
	$tpl->assign('STR_CADDIE', $GLOBALS['STR_CADDIE']);
	$tpl->assign('caddie_affichage_href', $GLOBALS['wwwroot'] . '/achat/caddie_affichage.php');
	if (defined('IN_CADDIE') || $_SESSION['session_ariane_panier']['in_caddie']) {
		$_SESSION['session_ariane_panier']['in_caddie'] = true;
	}
	$tpl->assign('in_step1', defined('IN_STEP1'));
	$tpl->assign('was_in_step1', $_SESSION['session_ariane_panier']['in_step1']);
	$tpl->assign('STR_PAYMENT_MEAN', $GLOBALS['STR_PAYMENT_MEAN']);
	$tpl->assign('achat_maintenant_href', $GLOBALS['wwwroot'] . '/achat/achat_maintenant.php');
	if (defined('IN_STEP1') || $_SESSION['session_ariane_panier']['in_step1']) {
		$_SESSION['session_ariane_panier']['in_step1'] = true;
	}
	$tpl->assign('in_step2', defined('IN_STEP2'));
	$tpl->assign('was_in_step2', $_SESSION['session_ariane_panier']['in_step2']);
	$tpl->assign('in_step3', defined('IN_STEP3'));
	if (defined('IN_STEP2')) {
		$_SESSION['session_ariane_panier']['in_step2'] = true;
	}
	$tpl->assign('STR_MODULE_ARIANE_PANIER_SOMMARY', $GLOBALS['STR_MODULE_ARIANE_PANIER_SOMMARY']);
	$tpl->assign('STR_CONFIRMATION', $GLOBALS['STR_CONFIRMATION']);
	return $tpl->fetch();
}

/**
 * close_ariane_panier_session()
 *
 * @return
 */
function close_ariane_panier_session()
{
	$_SESSION['session_ariane_panier'] = null;
}

?>