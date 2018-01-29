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
// $Id: article_details.php 55332 2017-12-01 10:44:06Z sdelaporte $

define('IN_RUBRIQUE_ARTICLE', true);
if (defined('PEEL_PREFETCH')) {
	call_module_hook('configuration_end', array());
} else {
	include("../configuration.inc.php");
}

if (!empty($_GET['artid']) && empty($_GET['rubid']) && empty($_GET['id'])) {
	// Compatibilité avec anciennes URL
	$_GET['id'] = intval($_GET['artid']);
} elseif (!empty($_GET['rubid']) && empty($_GET['id'])) {
	// Compatibilité avec anciennes URL
	$_GET['id'] = intval($_GET['rubid']);
} elseif (empty($_GET['id'])) {
	redirect_and_die(get_url('/'));
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
	if(!empty($art['technical_code']) && StringMb::strpos($art['technical_code'], 'R=') === 0) {
		// redirection suivie que l'article soit actif ou non
		$url_art = StringMb::substr($art['technical_code'], 2);
		if(strpos($url_art, '://') === false) {
			if(StringMb::substr($url_art, 0, 1) != '/') {
				$url_art = '/' . $url_art;
			}
			$url_art = $GLOBALS['wwwroot'] . $url_art;
		}
		redirect_and_die($url_art, true);
	}
	if($art['on_reseller'] == 1 && !a_priv("admin_product") && !a_priv("reve")) {
		redirect_and_die(get_url('/'));
	}
	// Si on passe ici et que $art['etat']=0 : on continue quand même, et on affichera dans get_article_details_html que l'article n'a pas été trouvé
} else {
	// Article n'existe pas
	redirect_and_die(get_url('/'), true);
}

// ATTENTION : la signification de rubid est historiquement trompeuse
// On appelle des URL avec /lire/article_details.php?rubid=xxx mais en fait on utilise ensuite $_GET['id'] pour cettte valeur d'id
// On force donc ici $_GET['rubid'] à la valeur de la rubrique correspondant au contenu, et non pas au contenu lui-même qui est $_GET['id']
$_GET['rubid'] = $art['rubrique_id'];

// Permet de définir l'id de la div principal du site.
if ($art['technical_code'] == 'tradefair') {
	$GLOBALS['main_div_id'] = 'tradefair';
} elseif ($art['technical_code'] == 'tradefloor') {
	$GLOBALS['main_div_id'] = 'tradefloor';
}

if (check_if_module_active('url_rewriting')) {
	// Attention la redirection ne sera effectuée que si il y a un / dans le REQUEST_URI (hormis le premier caractère) 
	// => les URL courtes ne sont pas redirigées ici (cela permet de créer des urls courtes par le htaccess sans rediriger par la suite, exemple /patrocinador-categoría.html ne sera pas redirigé ici)
	if (get_content_url($art['id'], $art["titre_" . $_SESSION['session_langue']], $art['rubrique_id'], $art["rubrique_nom"]) != get_current_url(false) && StringMb::strpos(substr($_SERVER['REQUEST_URI'], 1), '/') !== false) {
		// L'URL sans le get n'est pas comme elle est censée être => on redirige avec une 301
		$theoretical_current_url = get_content_url($art['id'], $art["titre_" . $_SESSION['session_langue']], $art['rubrique_id'], $art["rubrique_nom"]);
		redirect_and_die($theoretical_current_url, true);
	}
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
