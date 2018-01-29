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
// $Id: index.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_RUBRIQUE', true);
if (defined('PEEL_PREFETCH')) {
	call_module_hook('configuration_end', array());
} else {
	include("../configuration.inc.php");
}
if (empty($_GET['rubid']) && !empty($GLOBALS['site_parameters']['disallow_main_content_category'])) {
	// Si pas autorisé de voir /lire/ , retour à la page d'accueil
	redirect_and_die(get_url('/'), true);
}
$output = '';
$GLOBALS['page_name'] = 'rubriques';
$rubid = intval(vn($_GET['rubid']));
$on_new = intval(vn($_GET['on_new']));
$sql = "SELECT r.nom_" . $_SESSION['session_langue'] . " as nom, etat, technical_code
	FROM peel_rubriques r
	WHERE r.id ='" . intval($rubid) . "' AND r.technical_code NOT IN ('other', 'iphone_content') AND " . get_filter_site_cond('rubriques', 'r') . "
	ORDER BY r.position " . (!empty($GLOBALS['site_parameters']['content_category_primary_order_by'])? ", r." . $GLOBALS['site_parameters']['content_category_primary_order_by']  : '') . "
	";
$rub_query = query($sql);
if ($rub = fetch_assoc($rub_query)) {
	if(!empty($rub['technical_code']) && StringMb::strpos($rub['technical_code'], 'R=') === 0) {
		// redirection suivie que la rubrique soit active ou non
		redirect_and_die(get_url(StringMb::substr($rub['technical_code'], 2)), true);
	}
	if($rub['etat']==0 && !a_priv('admin_content', false)) {
		redirect_and_die(get_url('/'), true);
	}
	// Permet de définir l'id de la div principal du site.
	if ($rub['technical_code'] == 'tradefair' || $rub['technical_code'] == 'tradefaire_home') {
		$GLOBALS['main_div_id'] = 'tradefair';
	} elseif ($rub['technical_code'] == 'tradefloor') {
		$GLOBALS['main_div_id'] = 'tradefloor';
	}
	if($rub['technical_code']=='timeline' && check_if_module_active('timeline')) {
		$display_timeline = true;
	}
}
if (check_if_module_active('url_rewriting')) {
	if (!empty($rub) && get_content_category_url($rubid, $rub['nom']) != get_current_url(false)) {
		// L'URL sans le get n'est pas comme elle est censée être => on redirige avec une 301
		$theoretical_current_url = (!empty($_GET['page'])?get_content_category_url($rubid, $rub['nom'], true, true) . 'page=' . $_GET['page']:get_content_category_url($rubid, $rub['nom']));
		redirect_and_die($theoretical_current_url, true);
	} elseif (empty($rub) && get_content_category_url(null, null) != get_current_url(false)) {
		redirect_and_die(get_content_category_url(null, null));
	}
}

$class = "";
if (function_exists('has_special_article') && has_special_article($rubid)) {
	$class = "special_content";
}

$tpl = $GLOBALS['tplEngine']->createTemplate('lire.tpl');
$tpl->assign('class', $class);
$tpl->assign('articles_list_brief_html', get_articles_list_brief_html($rubid, $on_new));
if(!empty($display_timeline)){
	$tpl->assign('display_timeline', get_display_timeline(true));
}
$output .= $tpl->fetch();

$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['lire_index_page_columns_count'];
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");
