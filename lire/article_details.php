<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: article_details.php 43037 2014-10-29 12:01:40Z sdelaporte $

define('IN_RUBRIQUE_ARTICLE', true);

if(!defined('PEEL_PREFETCH') || substr($_SERVER["SCRIPT_NAME"], -strlen("/prefetch.php")) != "/prefetch.php") {
	include("../configuration.inc.php");
}
if (!empty($_GET['artid']) && empty($_GET['rubid']) && empty($_GET['id'])) {
	// Compatibilité avec anciennes URL
	$_GET['id'] = intval($_GET['artid']);
} elseif (!empty($_GET['rubid']) && empty($_GET['id'])) {
	// Compatibilité avec anciennes URL
	$_GET['id'] = intval($_GET['rubid']);
} elseif (empty($_GET['id'])) {
	redirect_and_die($GLOBALS['wwwroot'] . "/");
} else {
	$_GET['id'] = intval($_GET['id']);
}

$sql = "SELECT p.technical_code, p.on_reseller, p.id, p.titre_" . $_SESSION['session_langue'] . ", pc.rubrique_id, r.nom_" . $_SESSION['session_langue'] . " AS rubrique_nom
	FROM peel_articles p
	INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id
	INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id AND r.technical_code NOT IN ('other', 'iphone_content') AND " . get_filter_site_cond('rubriques', 'r') . "
	WHERE p.id ='" . intval($_GET['id']) . "' AND " . get_filter_site_cond('articles', 'p') . "";

$art_query = query($sql);
;
if ($art = fetch_assoc($art_query)) {
	if(!empty($art['technical_code']) && String::strpos($art['technical_code'], 'R=') === 0) {
		// redirection suivie que l'article soit actif ou non
		redirect_and_die($GLOBALS['wwwroot'] . '/' . String::substr($art['technical_code'], 2), true);
	}
	if($art['on_reseller'] == 1 && !a_priv("admin_product") && !a_priv("reve")) {
		redirect_and_die($GLOBALS['wwwroot'] . "/");
	}
	// Si on passe ici et que $art['etat']=0 : on continue quand même, et on affichera dans get_article_details_html que l'article n'a pas été trouvé
} else {
	// Article n'existe pas
	redirect_and_die($GLOBALS['wwwroot'] . "/");
}

// Permet de définir l'id de la div principal du site.
if ($art['technical_code'] == 'tradefair') {
	$GLOBALS['main_div_id'] = 'tradefair';
} elseif ($art['technical_code'] == 'tradefloor') {
	$GLOBALS['main_div_id'] = 'tradefloor';
}

if (is_module_url_rewriting_active()) {
	// Attention la redirection ne sera effectuée que si il n'y a pas de / sur le REQUEST_URI car cela permet de crée des urls courtes par le htaccess sans rediriger par la suite.
	// Exemple redirection htaccess pour un article avec l'url /patrocinador-categoría.html
	if (get_content_url($art['id'], $art["titre_" . $_SESSION['session_langue']], $art['rubrique_id'], $art["rubrique_nom"]) != get_current_url(false) && String::strpos(substr($_SERVER['REQUEST_URI'], 1), '/') !== false) {
		// L'URL sans le get n'est pas comme elle est censée être => on redirige avec une 301
		$theoretical_current_url = get_content_url($art['id'], $art["titre_" . $_SESSION['session_langue']], $art['rubrique_id'], $art["rubrique_nom"]);
		redirect_and_die($theoretical_current_url, true);
	}
} else {
	$_GET['rubid'] = $art['rubrique_id'];
}

$tpl = $GLOBALS['tplEngine']->createTemplate('article_details.tpl');
$tpl->assign('article_details_html', get_article_details_html(intval($_GET['id'])));
$output = $tpl->fetch();

if (!empty($GLOBALS['site_parameters']['enable_create_product_in_front_office']) && $art['technical_code'] == 'display_product_form') {
	$output .= update_product_from_front_office($_POST);
}
$GLOBALS['page_columns_count'] = $GLOBALS['site_parameters']['article_details_index_page_columns_count'];
include($GLOBALS['repertoire_modele'] . "/haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/bas.php");
