<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.php 35064 2013-02-08 14:16:40Z gboussin $
include("../configuration.inc.php");

define('IN_RUBRIQUE', true);

$output = '';
$rubid = intval(vn($_GET['rubid']));
$rub_query = query("SELECT r.nom_" . $_SESSION['session_langue'] . " as nom, technical_code
	FROM peel_rubriques r
	WHERE r.id ='" . intval($rubid) . "' AND r.technical_code NOT IN ('other', 'iphone_content')");
$rub = fetch_assoc($rub_query);
if (!empty($rub)) {
	if ($rub['technical_code'] == 'clients' && is_clients_module_active()) {
		include($GLOBALS['fonctionsclients']);
	}
	if ($rub['technical_code'] == 'creation' && is_references_module_active()) {
		include($GLOBALS['fonctionsreferences']);
		include($GLOBALS['dirroot'] . "/modules/references/lang/" . $_SESSION['session_langue'] . ".php");
	}
	
	// Permet de définir l'id de la div principal du site.
	if ($rub['technical_code'] == 'tradefair' || $rub['technical_code'] == 'tradefaire_home') {
		$GLOBALS['main_div_id'] = 'tradefair';
	}
	if ($rub['technical_code'] == 'tradefloor') {
		$GLOBALS['main_div_id'] = 'tradefloor';
	}
}
if (is_module_url_rewriting_active()) {
	if (!empty($rub) && get_content_category_url($rubid, $rub['nom']) != get_current_url(false)) {
		// L'URL sans le get n'est pas comme elle est censée être => on redirige avec une 301
		$theoretical_current_url = (!empty($_GET['page'])?get_content_category_url($rubid, $rub['nom'], true, true) . 'page=' . $_GET['page']:get_content_category_url($rubid, $rub['nom']));
		redirect_and_die($theoretical_current_url, true);
	} elseif (empty($rub) && get_content_category_url(null, null) != get_current_url(false)) {
		redirect_and_die(get_content_category_url(null, null));
	}
}

$class = "";
if (is_advistofr_module_active() && has_special_article($rubid)) {
	$class = "special_content";
}

$tpl = $GLOBALS['tplEngine']->createTemplate('lire.tpl');
$tpl->assign('class', $class);
$tpl->assign('articles_list_brief_html', get_articles_list_brief_html($rubid));
$output .= $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");

?>