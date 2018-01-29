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
// $Id: display_article.php 55637 2017-12-29 18:35:08Z gboussin $
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
				$tpl->assign('main_image', array(
					'href' => get_url_from_uploaded_filename($article['image1']),
					'src' => thumbs($article['image1'], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit', null, null, true, true),
					'file_type' => get_file_type($article['image1'])
				));
			}
			if(empty($GLOBALS['site_parameters']['chapo_in_article_page_disabled'])) {
				$tpl->assign('chapo', $article['chapo']);
			}
			$tpl->assign('texte', $article['texte']);
			
			if (function_exists('get_share_feature')) {
				$tpl->assign('share_feature', get_share_feature());
			} elseif (empty($GLOBALS['site_parameters']['hide_share_article_link']) && check_if_module_active('direaunami')) {
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
			WHERE parent_id = '" . intval($rubid) . "' AND nom_" . $_SESSION['session_langue'] . "<>'' AND etat = 1 AND r.technical_code NOT IN ('other', 'iphone_content') AND " . get_filter_site_cond('rubriques', 'r') . "
			ORDER BY r.position " . (!empty($GLOBALS['site_parameters']['content_category_primary_order_by'])? ", r." . $GLOBALS['site_parameters']['content_category_primary_order_by']  : '') . "
			");
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
					$tmp['image_src'] = thumbs($rub['image'], $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit', null, null, true, true);
				}
				if (!empty($rub['image_lien'])) {
					$tmp['lien_src'] = thumbs($rub['image_lien'], $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit', null, null, true, true);
				}
				$data[] = $tmp;
			}
			$tpl->assign('data', $data);
			$tpl->assign('description', StringMb::str_shorten(trim(StringMb::strip_tags(StringMb::html_entity_decode_if_needed($rub['description_' . $_SESSION['session_langue']]))),500,'','...',450));
			$output .= $tpl->fetch();
		}
		correct_output($output, true, 'html', $_SESSION['session_langue']);
		return $output;
	}
}

if (!function_exists('get_articles_html')) {
	/**
	 * Récupère la liste des articles correspondant à une rubrique de contenu donnée
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
		$sql = "SELECT p.id, p.on_reseller, p.surtitre_" . $_SESSION['session_langue'] . " AS surtitre, p.titre_" . $_SESSION['session_langue'] . " AS titre , p.chapo_" . $_SESSION['session_langue'] . " AS chapo, p.texte_" . $_SESSION['session_langue'] . " AS texte, p.image1, p.on_special, pc.rubrique_id, r.nom_" . $_SESSION['session_langue'] . " AS rubrique_nom
			FROM peel_articles p
			INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id " . $extra_sql. "
			INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id AND " . get_filter_site_cond('rubriques', 'r') . "
			WHERE p.etat = '1' AND p.titre_" . $_SESSION['session_langue'] . " != '' AND " . get_filter_site_cond('articles', 'p');
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
		$tpl->assign('haut_de_page_href', '#haut_de_page');
		$tpl->assign('category_content_show_explicit_buttons_if_articles_more_to_read', vb($GLOBALS['site_parameters']['category_content_show_explicit_buttons_if_articles_more_to_read'], true));
		
		if (!empty($results_array)) {
			$data = array();
			foreach ($results_array as $art) {
				if ((!a_priv("admin_product") && !a_priv("reve")) && $art['on_reseller'] == 1) {
					continue;
				}
				// L'éditeur de texte est susceptible de rajouter des paragraphes vides, donc on teste en retirant ce qui semble vide pour l'utilisateur mais ne l'est pas techniquement
				if(trim(str_replace(array('<p>','&#160;', '</p>'), '', $art['chapo'])) != ''){
					$chapo = StringMb::nl2br_if_needed(trim(StringMb::html_entity_decode_if_needed($art['chapo'])));
				}else{
					$chapo = StringMb::nl2br_if_needed(StringMb::str_shorten(trim(StringMb::strip_tags(StringMb::html_entity_decode_if_needed($art['texte']))),500,'','...',450));
				}
				$chapo = str_replace(array('<h1', '<h2', '<h3', '<h4', '</h1', '</h2', '</h3', '</h4'), array('<p', '<p', '<p', '<p', '</p', '</p', '</p', '</p'), $chapo);
				if($chapo == strip_tags($chapo)) {
					$chapo = '<p>' . $chapo . '</p>';
				}
				$data[] = array(
					'href' => get_content_url($art['id'], $art['titre'], $art['rubrique_id'], $art["rubrique_nom"]),
					'src' => get_url_from_uploaded_filename($art['image1']),
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
		if(empty($GLOBALS['css_files']['font-awesome'])) {
			// On va utiliser sur cette page spécifiquement les icônes Font Awesome 
			$GLOBALS['css_files']['font-awesome'] = get_url('/lib/css/font-awesome.min.css');
			// On exclut ce fichier de la minification car usage ponctuel
			$GLOBALS['site_parameters']['minify_css_exclude_array'][] = 'font-awesome.min.css';
		}
		$output ='';
		$sqlrub = "SELECT image, description_" . $_SESSION['session_langue'] . " AS description, nom_" . $_SESSION['session_langue'] . " AS nom, articles_review, etat, technical_code
			FROM peel_rubriques r
			WHERE id = '" . intval($rubid) . "' AND nom_" . $_SESSION['session_langue'] . " != '' AND r.technical_code NOT IN ('other', 'iphone_content') AND " . get_filter_site_cond('rubriques', 'r') . "
			ORDER BY r.position ASC, r.id DESC";
		$resrub = query($sqlrub);
		$rowrub = fetch_assoc($resrub);
		$tpl = $GLOBALS['tplEngine']->createTemplate('articles_list_brief_html.tpl');
		$tpl->assign('is_not_empty', !empty($rowrub));
		$tpl->assign('title_article_disabled', empty($GLOBALS['site_parameters']['title_article_disabled'])?'':$GLOBALS['site_parameters']['title_article_disabled']);
		if (!empty($rowrub)){
			if($rowrub['technical_code'] == 'add_cart_by_reference') {
				include($GLOBALS['dirroot'] . "/lib/fonctions/display_caddie.php");
				$tpl->assign('add_cart_by_reference', add_cart_by_reference());
			}
			$tpl->assign('name', $rowrub['nom']);
			if($rowrub['etat'] == 0 && a_priv('admin_content', false)) {
				$tpl->assign('offline_rub_txt', $GLOBALS['STR_OFFLINE_RUB']);
			}
			if (!empty($rowrub['image'])) {
				$tpl->assign('main_image', array(
					'href' => get_url_from_uploaded_filename($rowrub['image']),
					'src' => thumbs($rowrub['image'], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit', null, null, true, true),
					'file_type' => get_file_type($rowrub['image'])
				));
			}
			$tpl->assign('technical_code', $rowrub['technical_code']);
			$tpl->assign('description', StringMb::nl2br_if_needed($rowrub['description']));
			if($rowrub['technical_code'] == 'clients' && check_if_module_active('clients')) {
				$tpl->assign('descriptions_clients', affiche_descriptions_clients());
			}
			if($rowrub['technical_code'] == 'creation' && check_if_module_active('references')) {
				$tpl->assign('reference_multipage', affiche_reference_multipage(vn($_GET['refid'])));
			}
			if ($rowrub['articles_review'] == '1') {
				// On affiche des extraits d'articles qui correspondent à cette rubrique
				$tpl->assign('articles_html', get_articles_html($rubid));
			} elseif($rowrub['technical_code'] == 'tradefaire_home') {
				$q = query('SELECT id 
					FROM peel_rubriques 
					WHERE technical_code="articles_home_tradefaire" AND ' . get_filter_site_cond('rubriques') . '');
				$result_articles_home_tradefaire = fetch_assoc($q);
				$tpl->assign('stocklots_exhibitors', get_user_picture('STOCKLOTS_EXHIBITORS'));
				$tpl->assign('user_picture', get_user_picture('exhibitors'));
				$tpl->assign('articles_html', get_articles_html($result_articles_home_tradefaire['id']));
			}
		}
		if (!empty($GLOBALS['site_parameters']['display_content_category_diaporama'])) {
			$tpl->assign('diaporama', get_diaporama('content_category', $rubid));
		}
		if (vb($GLOBALS['site_parameters']['content_category_count_method'], $GLOBALS['site_parameters']['category_count_method']) == 'global' || (empty($rubid) && empty($rowrub))) {
			$tpl->assign('rubriques_sons_html', get_rubriques_sons_html($rubid));
		}
		if (a_priv('admin_content')) {
			$tpl->assign('admin', array(
				'href' => $GLOBALS['administrer_url'] . '/rubriques.php?mode=modif&id=' . $rubid,
				'modify_content_category_txt' => $GLOBALS['STR_MODIFY_CONTENT_CATEGORY']
			));
		}
		if (!empty($rubid) && !empty($GLOBALS['site_parameters']['show_special_on_content_category'])) {
			$sql = "SELECT p.id, p.surtitre_" . $_SESSION['session_langue'] . " AS surtitre, p.titre_" . $_SESSION['session_langue'] . " AS titre , p.chapo_" . $_SESSION['session_langue'] . " AS chapo, p.texte_" . $_SESSION['session_langue'] . " AS texte, p.image1, p.on_special, pc.rubrique_id, r.nom_" . $_SESSION['session_langue'] . " AS rubrique_nom, on_reseller
				FROM peel_articles p
				INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id AND pc.rubrique_id = '" . intval($rubid) . "'
				INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id AND " . get_filter_site_cond('rubriques', 'r') . "
				WHERE p.etat = '1' AND p.on_special = 1 AND titre_" . $_SESSION['session_langue'] . " != '' AND " . get_filter_site_cond('articles', 'p') . "
				ORDER BY p.position ASC, p.id DESC";
			$res = query($sql);
			if (num_rows($res) > 0) {
				$plus = array(
					'arts' => array()
				);
				while ($art = fetch_assoc($res)) {
					if ((!a_priv("admin_product") && !a_priv("reve")) && $art['on_reseller'] == 1) {
						continue;
					} else {
						$plus['arts'][] = array(
							'titre' => $art['titre'],
							'texte' => $art['texte']
						);
					}
				}
				$tpl->assign('plus', $plus);
			}
		}
		$hook_result = call_module_hook('articles_list_brief_html', array(), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl->assign($this_key, $this_value);
		}
		$output .= $tpl->fetch();
		correct_output($output, true, 'html', $_SESSION['session_langue']);
		return $output;
	}
}

if (!function_exists('affiche_arbre_rubrique')) {
	/**
	 * Renvoie l'arbre des catégories des articles, en commençant de top jusqu'à la catégorie spécifiée par $id
	 *
	 * @param integer $rubid
	 * @param mixed $additional_text
	 * @param boolean $hidden Used only for generating hidden breadcrumb with microdata for google
	 * @param integer $level Niveau pour les microdonnées BreadcrumbList (1 étant pour la page d'accueil)
	 * @return
	 */
	function affiche_arbre_rubrique($rubid = 0, $additional_text = null, $hidden = false, $level = 2)
	{
		static $tpl;
		$output = '';
		$qid = query('SELECT parent_id, nom_' . $_SESSION['session_langue'] . '
			FROM peel_rubriques r
			WHERE id = "' . intval($rubid) . '" AND etat = "1" AND r.technical_code NOT IN ("other", "iphone_content") AND ' . get_filter_site_cond('rubriques', 'r') . '');
		if (num_rows($qid)) {
			list($parent, $nom) = fetch_row($qid);
			if(empty($tpl)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('arbre_rubrique.tpl');
			}
			$tpl->assign('href', get_content_category_url($rubid, $nom));
			$tpl->assign('label', $nom);
			$tpl->assign('hidden', $hidden);
			$tpl->assign('level', $level);
			$nom = $tpl->fetch();
		} else {
			$parent = 0;
			$nom = '';
		}
		if ($parent > 0) {
			return affiche_arbre_rubrique($parent, ' &gt; ' . $nom, $hidden, $level+1);
		} else {
			return $nom . (!$hidden ? $additional_text : '');
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
				$output .= StringMb::ucfirst(get_formatted_date($item->get_date(), 'long', 'short')) . ' - ';
			}
			$output .= StringMb::ucfirst($item->get_description()). '
			</div>';
        }
	}
	return $output;
}

if (!function_exists('get_articles_in_container_html')) {
	/**
	 *
	 * @param object $articles_data_array
	 * @param boolean $only_show_article_with_picture
	 * @return
	 */
	function get_articles_in_container_html($articles_data_array, $only_show_article_with_picture = true)
	{
		static $tpl;
		$output = '';
		if (!empty($articles_data_array['id']) && !empty($articles_data_array['etat'])) {
			$urlprod = get_content_url($articles_data_array['id']);
			$display_picture = $articles_data_array['image'];
			if (!$only_show_article_with_picture || !empty($display_picture)) {
				if(empty($tpl)) {
					$tpl = $GLOBALS['tplEngine']->createTemplate('articles_in_container_html.tpl');
				}
				$tpl->assign('href', $urlprod);
				$tpl->assign('name', $articles_data_array['name']);
				if (!empty($display_picture)) {
					$this_picture = thumbs($display_picture, $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], "fit", null, null, true, true);
					if($only_show_article_with_picture && empty($this_picture)) {
						return false;
					}
					$tpl->assign('src', $this_picture);
				} else {
					$tpl->assign('src', null);
				}
				$tpl->assign('more_detail_label', $GLOBALS['STR_MORE_DETAILS']);
				$output .= $tpl->fetch();
			}
		}
		return $output;
	}
}
