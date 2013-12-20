<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: display_article.php 39392 2013-12-20 11:08:42Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('get_article_details_html')) {
	/**
	 * get_article_details_html()
	 *
	 * @param mixed $product_id
	 * @param mixed $color_id
	 * @return
	 */
	function get_article_details_html($article_id)
	{
		$output = '';
		$article = charge_article($article_id);
		$tpl = $GLOBALS['tplEngine']->createTemplate('article_details_html.tpl');
		$tpl->assign('is_article', (bool)$article);
		if (!$article)
			$tpl->assign('STR_NO_FIND_ART', $GLOBALS['STR_NO_FIND_ART']);
		else{
			$tpl->assign('titre', $article['titre']);
			$tpl->assign('is_offline', ($article['etat'] == 0));
			$tpl->assign('STR_OFFLINE_ART', $GLOBALS['STR_OFFLINE_ART']);

			if (!empty($article['image1'])) {
				if (pathinfo($article['image1'], PATHINFO_EXTENSION) == 'pdf') {
					$this_thumb = thumbs('logoPDF_small.png', $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit', $GLOBALS['dirroot'] .'/images/');
				} else {
					$this_thumb = thumbs($article['image1'], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit');
				}
				$tpl->assign('main_image', array(
					'href' => $GLOBALS['repertoire_upload'] . '/' . String::rawurlencode($article['image1']),
					'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_thumb,
					'is_pdf' => !(pathinfo($article['image1'], PATHINFO_EXTENSION) != 'pdf')
				));
			}
			$tpl->assign('chapo', $article['chapo']);
			$tpl->assign('texte', $article['texte']);
			
			if (function_exists('get_peelfr_share_feature')) {
				$tpl->assign('share_feature', get_peelfr_share_feature());
			} elseif (is_module_direaunami_active()) {
				$tpl->assign('tell_friends', array(
						'src' => $GLOBALS['site_parameters']['general_send_email_image'],
						'txt' => $GLOBALS['STR_TELL_FRIEND'],
						'href' => get_tell_friends_url(false)
					));
			}
			if (a_priv('admin_content', false)) {
				$tpl->assign('admin', array(
					'href' => $GLOBALS['administrer_url'] . '/articles.php?mode=modif&id=' . $article['id'],
					'modify_article_txt' => $GLOBALS['STR_MODIFY_ARTICLE']
				));
			}
		}
		$output .= $tpl->fetch();
		correct_output($output, true, 'html', $_SESSION['session_langue']);
		return $output;
	}
}

if (!function_exists('get_rubriques_sons_html')) {
	/**
	 * get_rubriques_sons_html()
	 *
	 * @param mixed $rubid
	 * @return
	 */
	function get_rubriques_sons_html($rubid)
	{
		$output = '';
		$qid_r = query("SELECT id, nom_" . $_SESSION['session_langue'] . ", description_" . $_SESSION['session_langue'] . ", parent_id, image
			FROM peel_rubriques r
			WHERE parent_id = '" . intval($rubid) . "' AND nom_" . $_SESSION['session_langue'] . "<>'' AND etat = 1 AND r.technical_code NOT IN ('other', 'iphone_content')");
		if (num_rows($qid_r) > 0) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('rubriques_sons_html.tpl');
			$tpl->assign('list_rubriques_txt', $GLOBALS['STR_LIST_RUBRIQUES'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$data = array();
			while ($rub = fetch_assoc($qid_r)) {
				$tmp = array(
					'href' => get_content_category_url($rub['id'], $rub['nom_' . $_SESSION['session_langue']]),
					'name' => $rub['nom_' . $_SESSION['session_langue']]
				);
				if (!empty($rub['image'])) {
					$tmp['image_src'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($rub['image'], $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit');
				}
				if (!empty($rub['image_lien'])) {
					$tmp['lien_src'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($rub['image_lien'], $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit');
				}
				$data[] = $tmp;
			}
			$tpl->assign('data', $data);
			$tpl->assign('description', String::str_shorten(trim(String::strip_tags(String::html_entity_decode_if_needed($rub['description_' . $_SESSION['session_langue']]))),500,'','...',450));
			$output .= $tpl->fetch();
		}
		correct_output($output, true, 'html', $_SESSION['session_langue']);
		return $output;
	}
}

if (!function_exists('get_articles_html')) {
	/**
	 * get_articles_html()
	 *
	 * @param integer $rubid
	 * @param boolean $get_sub_rubrique
	 * @return
	 */
	function get_articles_html($rubid = 0, $get_sub_rubrique = false) {
		$output = '';
		$extra_sql = '';
		if (!empty($rubid)) {
			if (!empty($get_sub_rubrique)) {
				$extra_sql .= " AND pc.rubrique_id IN (" . real_escape_string(implode(',', get_category_tree_and_itself($rubid, 'sons', 'rubriques'))) . ")";
			} else {
				$extra_sql .= " AND pc.rubrique_id = '" . intval($rubid) . "'";
			}
		}
		$sql = "SELECT p.id, p.surtitre_" . $_SESSION['session_langue'] . " AS surtitre, p.titre_" . $_SESSION['session_langue'] . " AS titre , p.chapo_" . $_SESSION['session_langue'] . " AS chapo, p.texte_" . $_SESSION['session_langue'] . " AS texte, p.image1, p.on_special, pc.rubrique_id, r.nom_" . $_SESSION['session_langue'] . " AS rubrique_nom
			FROM peel_articles p
			INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id " . $extra_sql. "
			INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id
			WHERE p.etat = '1' AND p.titre_" . $_SESSION['session_langue'] . " != ''";
		$Links = new Multipage($sql, 'get_articles_html', 15, 7, 0, false);
		$Links->order_sql_prefix = 'p';
		$Links->order_get_variable = 'tri';
		$Links->sort_get_variable = 'sort';
		$Links->OrderDefault = 'position';
		$Links->SortDefault = 'ASC';
		$Links->forced_second_order_by_string = 'p.id DESC';
		$results_array = $Links->Query();
		
		$tpl = $GLOBALS['tplEngine']->createTemplate('articles_html.tpl');
		$tpl->assign('is_content', !empty($results_array));
		$tpl->assign('STR_MORE_DETAILS', $GLOBALS['STR_MORE_DETAILS']);
		$tpl->assign('haut_de_page_txt', $GLOBALS['STR_HAUT_DE_PAGE']);
		$tpl->assign('haut_de_page_href', get_current_url() . '#haut_de_page');
		
		if (!empty($results_array)) {
			$data = array();
			foreach ($results_array as $art) {
				if(!empty($art['chapo'])){
					$chapo = String::nl2br_if_needed(trim(String::html_entity_decode_if_needed($art['chapo'])));
				}else{
					$chapo = String::str_shorten(trim(strip_tags(String::html_entity_decode_if_needed($art['texte']))),500,'','...',450);
				}
				$chapo = str_replace(array('<h1', '<h2', '<h3', '<h4', '</h1', '</h2', '</h3', '</h4'), array('<p', '<p', '<p', '<p', '</p', '</p', '</p', '</p'), $chapo);
				$data[] = array(
					'href' => get_content_url($art['id'], $art['titre'], $art['rubrique_id'], $art["rubrique_nom"]),
					'src' => (!empty($art['image1']) ? $GLOBALS['repertoire_upload'] . '/' . $art['image1'] : FALSE),
					'titre' => $art['titre'],
					'chapo' => $chapo,
					'texte' => $art['texte'],
					'is_texte' => !empty($art['texte']),
				);
			}
			$tpl->assign('data', $data);
		}
		$tpl->assign('multipage', $Links->GetMultipage());
		$output .= $tpl->fetch();
		correct_output($output, true, 'html', $_SESSION['session_langue']);
		return $output;
	}
}

if (!function_exists('get_articles_list_brief_html')) {
	/**
	 * get_articles_list_brief_html()
	 *
	 * @param mixed $rubid
	 * @return
	 */
	function get_articles_list_brief_html($rubid)
	{
		$output ='';
		$sqlrub = "SELECT image, description_" . $_SESSION['session_langue'] . " AS description, nom_" . $_SESSION['session_langue'] . " AS nom, articles_review, etat, technical_code
			FROM peel_rubriques r
			WHERE id = '" . intval($rubid) . "' AND nom_" . $_SESSION['session_langue'] . " != '' AND r.technical_code NOT IN ('other', 'iphone_content')
			ORDER BY position";
		$resrub = query($sqlrub);
		$rowrub = fetch_assoc($resrub);
		$tpl = $GLOBALS['tplEngine']->createTemplate('articles_list_brief_html.tpl');
		$tpl->assign('is_not_empty', !empty($rowrub));
		if (!empty($rowrub)){
			$tpl->assign('name', $rowrub['nom']);
			if($rowrub['etat'] == 0 && a_priv('admin_content', false)) {
				$tpl->assign('offline_rub_txt', $GLOBALS['STR_OFFLINE_RUB']);
			}
			if (!empty($rowrub['image'])) {
				if (pathinfo($rowrub['image'], PATHINFO_EXTENSION) == 'pdf') {
					$this_thumb = thumbs('logoPDF_small.png', $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit', $GLOBALS['dirroot'] .'/images/');
				} else {
					$this_thumb = thumbs($rowrub['image'], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit');
				}
				$tpl->assign('main_image', array(
					'href' => $GLOBALS['repertoire_upload'] . '/' . String::rawurlencode($rowrub['image']),
					'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_thumb,
					'is_pdf' => !(pathinfo($rowrub['image'], PATHINFO_EXTENSION) != 'pdf')
				));
			}
			$tpl->assign('description', $rowrub['description']);
			if($rowrub['technical_code'] == 'clients' && is_clients_module_active()) {
				$tpl->assign('descriptions_clients', affiche_descriptions_clients());
			}
			if($rowrub['technical_code'] == 'creation' && is_references_module_active()) {
				$tpl->assign('reference_multipage', affiche_reference_multipage(vn($_GET['refid'])));
			}
			if ($rowrub['articles_review'] == '1') {
				// On affiche des extraits d'articles qui correspondent à cette rubrique
				$tpl->assign('articles_html', get_articles_html($rubid));
			} elseif($rowrub['technical_code'] == 'tradefaire_home') {
				$q = query('SELECT id FROM peel_rubriques WHERE technical_code="articles_home_tradefaire"');
				$result_articles_home_tradefaire = fetch_assoc($q);
				$tpl->assign('stocklots_exhibitors', get_user_picture('STOCKLOTS_EXHIBITORS'));
				$tpl->assign('user_picture', get_user_picture('exhibitors'));
				$tpl->assign('articles_html', get_articles_html($result_articles_home_tradefaire['id']));
			}
		}
		if (!empty($GLOBALS['site_parameters']['display_content_category_diaporama'])) {
			$tpl->assign('diaporama', get_diaporama('content_category', $rubid));
		}
		if ($GLOBALS['site_parameters']['category_count_method'] == 'global' || (empty($rubid) && empty($rowrub))) {
			$tpl->assign('rubriques_sons_html', get_rubriques_sons_html($rubid));
		}
		if (est_identifie() && a_priv('admin_content')) {
			$tpl->assign('admin', array(
				'href' => $GLOBALS['administrer_url'] . '/rubriques.php?mode=modif&id=' . $rubid,
				'modify_content_category_txt' => $GLOBALS['STR_MODIFY_CONTENT_CATEGORY']
			));
		}
		if (!empty($rubid) && !empty($GLOBALS['site_parameters']['show_special_on_content_category'])) {
			$sql = "SELECT p.id, p.surtitre_" . $_SESSION['session_langue'] . " AS surtitre, p.titre_" . $_SESSION['session_langue'] . " AS titre , p.chapo_" . $_SESSION['session_langue'] . " AS chapo, p.texte_" . $_SESSION['session_langue'] . " AS texte, p.image1, p.on_special, pc.rubrique_id, r.nom_" . $_SESSION['session_langue'] . " AS rubrique_nom
				FROM peel_articles p
				INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id AND pc.rubrique_id = '" . intval($rubid) . "'
				INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id
				WHERE p.etat = '1' AND p.on_special = 1 AND titre_" . $_SESSION['session_langue'] . " != ''
				ORDER BY p.position ASC, p.id ASC";
			$res = query($sql);
			if (num_rows($res) > 0) {
				$plus = array(
					'src' => $GLOBALS['repertoire_images'] . '/coin.png',
					'arts' => array()
				);
				while ($art = fetch_assoc($res)) {
					$plus['arts'][] = array(
						'titre' => $art['titre'],
						'texte' => $art['texte']
					);
				}
				$tpl->assign('plus', $plus);
			}
		}
		
		$output .= $tpl->fetch();
		correct_output($output, true, 'html', $_SESSION['session_langue']);
		return $output;
	}
}

if (!function_exists('affiche_menu_contenu')) {
	/**
	 * affiche_menu_contenu()
	 *
	 * @param mixed $location indicates the position in the website : left or right
	 * @param boolean $return_mode
	 * @param mixed $add_ul_if_result
	 * @return
	 */
	function affiche_menu_contenu($location, $return_mode = false, $add_ul_if_result = false)
	{
		$output = '';
		if (!empty($_GET['rubid'])) {
			$highlighted_item = intval($_GET['rubid']);
		} else {
			$highlighted_item = 0;
		}
		$cond = ' ';
		$sql = 'SELECT r.id, r.parent_id, r.nom_' . $_SESSION['session_langue'] . ' as nom
			FROM peel_rubriques r
			WHERE r.etat = "1" AND r.technical_code NOT IN ("other", "iphone_content") AND r.position>=0
			ORDER BY r.position ASC, nom ASC';
		$qid = query($sql);
		while ($result = fetch_assoc($qid)) {
			$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
			$item_name_array[$result['id']] = $result['nom'];
		}
		if (!empty($all_parents_with_ordered_direct_sons_array)) {
			$output .= get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $highlighted_item, 'rubriques', $location);
		}
		if (!empty($output) && !empty($add_ul_if_result)) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('menu_contenu.tpl');
			$tpl->assign('menu', $output);
			$output = $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_arbre_rubrique')) {
	/**
	 * Renvoie l'arbre des catégories des articles, en commençant de top jusqu'à la catégorie spécifiée par $id
	 *
	 * @param integer $rubid
	 * @param mixed $additional_text
	 * @return
	 */
	function affiche_arbre_rubrique($rubid = 0, $additional_text = null)
	{
		static $tpl;
		$output = '';
		$qid = query('SELECT parent_id, nom_' . $_SESSION['session_langue'] . '
			FROM peel_rubriques r
			WHERE id = "' . intval($rubid) . '" AND etat = "1" AND r.technical_code NOT IN ("other", "iphone_content")');
		if (num_rows($qid)) {
			list($parent, $nom) = fetch_row($qid);
			if(empty($tpl)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('arbre_rubrique.tpl');
			}
			$tpl->assign('href', get_content_category_url($rubid, $nom));
			$tpl->assign('label', $nom);
			$nom = $tpl->fetch();
		} else {
			$parent = 0;
			$nom = '';
		}
		if ($parent > 0) {
			return affiche_arbre_rubrique($parent, ' &gt; ' . $nom);
		} else {
			return $nom . $additional_text;
		}
	}
}

if (!function_exists('construit_arbo_rubrique')) {
	/**
	 * construit_arbo_rubrique()
	 *
	 * @param mixed $sortie
	 * @param mixed $preselectionne
	 * @param integer $parent
	 * @param string $indent
	 * @return
	 */
	function construit_arbo_rubrique(&$sortie, &$preselectionne, $parent = 0, $indent = '')
	{
		static $tpl;
		$sql = 'SELECT r.id, r.nom_' . $_SESSION['session_langue'] . ', r.parent_id
			FROM peel_rubriques r
			WHERE r.parent_id = "' . intval($parent) . '"
			ORDER BY r.position';
		$qid = query($sql);
		while ($rub = fetch_assoc($qid)) {
			if (is_array($preselectionne)) {
				$selectionne = (in_array($rub['id'], $preselectionne) ? ' selected="selected"' : '');
			} else {
				$selectionne = ($rub['id'] == $preselectionne ? ' selected="selected"' : '');
			}
			if(empty($tpl)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('arbo_rubrique.tpl');
			}
			$tpl->assign('value', intval($rub['id']));
			$tpl->assign('is_selected', !empty($selectionne));
			$tpl->assign('indent', $indent);
			$tpl->assign('label', $rub['nom_' . $_SESSION['session_langue']]);
			$sortie .= $tpl->fetch();
			if ($rub['id'] != $parent) {
				construit_arbo_rubrique($sortie, $preselectionne, $rub['id'], $indent . '&nbsp;&nbsp;');
			}
		}
	}
}

/**
 * Récupère le contenu d'un fichier RSS
 *
 * @param string $feed_url
 * @return
 */
function get_rss_feed_content($feed_url) {
	$output = '';
	// Appel de la libraire SimplePie.
	include_once $GLOBALS['dirroot'].'/lib/class/Simplepie.php';
	$feed = new SimplePie();
	$feed->set_cache_location($GLOBALS['dirroot'].'/'.$GLOBALS['site_parameters']['cache_folder']);
	// On introduit une durée de cache random pour éviter qu'une page avec plusieurs flux mette à chaque fois tout à jour en même temps
	if(!empty($_GET['update']) && $_GET['update']==1){
		$feed->set_cache_duration(1);
	} else {
		$feed->set_cache_duration(3600 * rand(12,16));
	}
	// on peut lui interdire de trier par date. true par défaut.
	// $feed->enable_order_by_date(false);
	$feed->set_feed_url(html_entity_decode($feed_url));
	// on lance la récupération du contenu
	$feed->init();
    if($feed->data){
        // On défini le nombre d'articles qui nous intéressent.
        $max = $feed->get_item_quantity(100);
        // Nous voici au coeur du code d'intégration.
		if($feed->get_title()) {
			$output .= '
		<div class="rss_header">
			<h3>' . $feed->get_title() . '</h3>
			<p>' . $feed->get_description() . '</p>
		</div>
';
		}
        for($x=0; $x<$max; $x++) {
            // On prend le x-iéme item.
            $item=$feed->get_item($x);
			$enclosure=$item->get_enclosure(0);
			$output .= '
			<div class="rss_content" style="margin-bottom: 10px;">
				<h4><a href="'. $item->get_permalink(). '" onclick="return(window.open(this.href)?false:true);">'. $item->get_title().'</a></h4>';
			if(!empty($enclosure) && $enclosure->get_link()!=''){
				$output .= '<img src="' . $enclosure->get_link() . '" style="float: left; margin: 4px; margin-top: 8px;" />';
			}
			if($item->get_date()){
				$output .= String::ucfirst(get_formatted_date($item->get_date(), 'long', 'short')) . ' - ';
			}
			$output .= String::ucfirst($item->get_description()). '
			</div>';
        }
	}
	return $output;
}
?>