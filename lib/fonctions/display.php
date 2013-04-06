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
// $Id: display.php 36236 2013-04-05 14:10:14Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('tr_rollover')) {
	/**
	 * tr_rollover()
	 *
	 * @param mixed $line_number
	 * @param boolean $return_mode
	 * @param string $style
	 * @param string $onclick
	 * @param string $id
	 * @return
	 */
	function tr_rollover($line_number, $return_mode = false, $style = null, $onclick = null, $id = null)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('tr_rollover.tpl');
		$tpl->assign('onclick', $onclick);
		$tpl->assign('style', $style);
		$tpl->assign('line_number', $line_number);
		$tpl->assign('id', $id);
		$output .= $tpl->fetch();
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_meta')) {
	/**
	 * Affiche des meta HTML pour une page.
	 * - En première priorité $GLOBALS['meta_...] si ils sont définis
	 * - En seconde priorité, on prend les métas en base de données pour un produit, catégorie, marque, article ou rubrique
	 * - En troisième priorité, on prendra les métas par section du site qui sont définis dans strSpecificMeta
	 * - En quatrième priorité, on prend les métas génériques du site
	 *
	 * @param string $page_name
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_meta($page_name, $return_mode = false)
	{
		$output = '';
		// PRIORITE 2 : Récupération des métas définis en BDD pour des éléments précis
		if (!empty($_GET['id']) && defined('IN_LEXIQUE')) {
			$sql_Meta = 'SELECT word_' . $_SESSION['session_langue'] . ' as nom, meta_title_' . $_SESSION['session_langue'] . ' as meta_titre, meta_definition_' . $_SESSION['session_langue'] . ' as meta_desc 
				FROM peel_lexique 
				WHERE id = "' . intval($_GET['id']) . '"';
		} elseif (!empty($_GET['catid']) && defined('IN_CATALOGUE_ANNONCE')) {
			$sql_Meta = 'SELECT meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, meta_desc_' . vb($_SESSION['session_langue']) . ' AS meta_desc 
				FROM peel_categories_annonces 
				WHERE id = "' . intval($_GET['catid']) . '"';
		} elseif (!empty($_GET['id']) && defined('IN_SEARCH_BRAND')) { 
			// Si on est dans une marque
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' as nom, meta_titre_' . $_SESSION['session_langue'] . ' as meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' as meta_desc, meta_key_' . $_SESSION['session_langue'] . ' as meta_key 
				FROM peel_marques 
				WHERE id = "' . intval($_GET['id']) . '"';
		} elseif (!empty($_GET['catid']) && empty($_GET['id'])) { 
			// Si on est dans une catégorie
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' as nom, meta_titre_' . $_SESSION['session_langue'] . ' as meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' as meta_desc, meta_key_' . $_SESSION['session_langue'] . ' as meta_key, image_' . $_SESSION['session_langue'] . ' as image 
				FROM peel_categories 
				WHERE id = "' . intval($_GET['catid']) . '"';
		} elseif (!empty($_GET['rubid']) && empty($_GET['id'])) { 
			// Si on est dans une rubrique
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' as nom, meta_titre_' . $_SESSION['session_langue'] . ' as meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' as meta_desc, meta_key_' . $_SESSION['session_langue'] . ' as meta_key, image 
				FROM peel_rubriques 
				WHERE id = "' . intval($_GET['rubid']) . '"';
		} elseif (!empty($_GET['id']) && defined('IN_CATALOGUE_PRODUIT')) {
			// Si on est dans une fiche produit
			$display_facebook_tag = true;
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' as nom, meta_titre_' . $_SESSION['session_langue'] . ' as meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' as meta_desc, meta_key_' . $_SESSION['session_langue'] . ' as meta_key, image1 as image 
				FROM peel_produits 
				WHERE id = "' . intval($_GET['id']) . '"';
		} elseif (!empty($_GET['id']) && defined('IN_RUBRIQUE_ARTICLE')) {
			// Si on est dans un article de contenu
			$display_facebook_tag = true;
			$sql_Meta = 'SELECT titre_' . $_SESSION['session_langue'] . ' as nom, meta_titre_' . $_SESSION['session_langue'] . ' as meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' as meta_desc, meta_key_' . $_SESSION['session_langue'] . ' as meta_key, image1 as image 
				FROM peel_articles 
				WHERE id = "' . intval($_GET['id']) . '"';
		}
		if (!empty($sql_Meta)) {
			$query_Meta = query($sql_Meta);
			$m = fetch_assoc($query_Meta);
		}
		// PRIORITE 3 : Définition de certains métas par défaut, en complément de ce qui est présent dans les fichiers de meta par langue
		if (defined('IN_PARTNER')) {
			$GLOBALS['strSpecificMeta']['Title'][$page_name] = $GLOBALS['STR_OUR_PARTNER'];
		} elseif (defined('IN_MAP')) {
			$GLOBALS['strSpecificMeta']['Title'][$page_name] = $GLOBALS['STR_WORD_RESELLER'];
		} elseif (defined('IN_RETAILER')) {
			$GLOBALS['strSpecificMeta']['Title'][$page_name] = $GLOBALS['STR_RETAILER_SUBSCRIBE'];
		} elseif (defined('IN_REFERENCE')) {
			$GLOBALS['strSpecificMeta']['Title'][$page_name] = $GLOBALS['STR_REFERENCE_ON_LINE_SHOP'];
		} elseif (defined('IN_DEVIS')) {
			$GLOBALS['strSpecificMeta']['Title'][$page_name] = $GLOBALS['STR_DEVIS_ON_LINE_SHOP'];
		} elseif (defined('IN_DOWNLOAD_PEEL')) {
			$GLOBALS['strSpecificMeta']['Title'][$page_name] = $GLOBALS['STR_MODULE_PEEL_DOWNLOAD_PEEL'];
		}
		// PRIORITE 4 : Récupération des metas par défaut
		$sql_Meta = 'SELECT *
			FROM peel_meta
			WHERE id = "1"';
		$query_Meta = query($sql_Meta);
		$m_default = fetch_assoc($query_Meta);
		// Application des priorités
		if (!empty($GLOBALS['meta_title'])) {
			$this_title = $GLOBALS['meta_title'];
		} elseif (!empty($GLOBALS['DOC_TITLE'])) {
			$this_title = $GLOBALS['DOC_TITLE'];
		} elseif (!empty($m['meta_titre'])) {
			$this_title = $m['meta_titre'];
		} elseif (!empty($m['nom'])) {
			$this_title = $m['nom'];
		} elseif (!empty($GLOBALS['strSpecificMeta']['Title'][$page_name])) {
			$this_title = $GLOBALS['strSpecificMeta']['Title'][$page_name];
		} else {
			$this_title = $m_default['meta_titre_' . $_SESSION['session_langue']];
		}
		$this_title = String::html_entity_decode($this_title);
		if (!empty($GLOBALS['STR_TITLE_SUFFIX'])) {
			if (String::strpos(String::strtolower($this_title), String::strtolower($GLOBALS['STR_TITLE_SUFFIX'])) === false && String::strlen($this_title . ' - ' . $GLOBALS['STR_TITLE_SUFFIX']) < 80) {
				if(!empty($this_title)) {
					$this_title .= ' - ';
				}
				$this_title .= $GLOBALS['STR_TITLE_SUFFIX'];
			}
		}
		if (!empty($GLOBALS['meta_description'])) {
			$this_description = str_replace(array('    ', '   ', '  ', "\t"), ' ', trim(String::strip_tags($GLOBALS['meta_description']))) . ' ';
		} else {
			$this_description = '';
		}
		if (String::strlen($this_description) < 50) {
			if (!empty($m['meta_desc'])) {
				$this_description .= $m['meta_desc'];
			} elseif (!empty($GLOBALS['strSpecificMeta']['Description'][$page_name])) {
				$this_description .= $GLOBALS['strSpecificMeta']['Description'][$page_name];
			} else {
				$this_description .= $m_default['meta_desc_' . $_SESSION['session_langue']];
				if (!empty($m['nom'])) {
					$this_description = $m['nom'] . '. ' . $this_description;
				}
			}
		}
		if (!empty($GLOBALS['meta_keywords'])) {
			$this_keywords = $GLOBALS['meta_keywords'] . ' ';
		} else {
			$this_keywords = '';
		}
		if (String::strlen($this_keywords) < 60) {
			if (!empty($m['meta_key'])) {
				$this_keywords .= $m['meta_key'];
			} elseif (!empty($GLOBALS['strSpecificMeta']['Keywords'][$page_name])) {
				$this_keywords .= $GLOBALS['strSpecificMeta']['Keywords'][$page_name];
			} else {
				if (!empty($m['nom'])) {
					$this_keywords = $m['nom'] . ',' . $this_keywords;
				}
				// On va prendre la description en plus des mots clés par défaut, et on retraitera ensuite
				$this_keywords .= $this_description . ',' . $m_default['meta_key_' . $_SESSION['session_langue']];
			}
		}
		if (!empty($this_keywords)) {
			// Nettoyage des mots clés - on n'en garde que 30 maximum
			$temp_array = array_unique(explode(',', trim(String::strip_tags(str_replace(array("\r", "\n", "\t", '!', '?', '(', ')', '.', '#', ';', '&nbsp;', '+', '-', " ", ".", '"', "'"), ',', String::html_entity_decode(str_replace(array('&nbsp;'), ',', String::strtolower($this_keywords))))))));
			foreach($temp_array as $this_key => $this_value) {
				if (String::strlen($this_value) < 4) {
					unset($temp_array[$this_key]);
				}
			}
			$this_keywords = implode(', ', array_slice($temp_array, 0, 30));
		}
		if (!empty($this_title) && $this_title == String::strtoupper($this_title) && String::strlen($this_title) > 25) {
			// Titre tout en majuscule et pas juste un ou deux mots => on passe en minuscule car sinon mauvais pour moteurs de recherche
			$this_title = String::strtolower($this_title);
		}
		if (!empty($this_description)) {
			$this_description = String::str_shorten(str_replace('  ', ' ', trim(strip_tags(String::html_entity_decode_if_needed(str_replace(array("\r", "\n", "<br>", "<br />", "</p>"), ' ', $this_description))))), 255, '', '...', 240);
			if ($this_description == String::strtoupper($this_description)) {
				$this_description = String::strtolower($this_description);
			}
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('meta.tpl');
		$tpl->assign('charset', GENERAL_ENCODING);
		$tpl->assign('title', String::ucfirst(String::str_shorten(trim(String::strip_tags(String::html_entity_decode_if_needed(str_replace(array("\r", "\n"), '', $this_title)))), 100, '', '', 80)));
		$tpl->assign('keywords', $this_keywords);
		$tpl->assign('site', $GLOBALS['site']);
		if($_SESSION['session_langue'] == 'fr') {
			$tpl->assign('generator', 'https://www.peel.fr/');
		} else{
			$tpl->assign('generator', 'http://www.peel-shopping.com/');
		}
		$tpl->assign('description', String::ucfirst($this_description));
		$tpl->assign('content_language', $_SESSION['session_langue']);
		if (is_facebook_module_active() && !empty($display_facebook_tag)) {
			$tpl->assign('facebook_tag', display_facebook_tag($m));
		}
		$output .= $tpl->fetch();
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_ariane')) {
	/**
	 * affiche_ariane()
	 *
	 * @return
	 */
	function affiche_ariane()
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('ariane.tpl');
		$tpl->assign('wwwroot', $GLOBALS['wwwroot']);

		$ariane = array('href' => false,
			'txt' => $GLOBALS['site']
			);
		$other = array('href' => false,
			'txt' => false
			);

		if (!defined('IN_HOME')) {
			$ariane['href'] = $GLOBALS['wwwroot'] . '/';

			if (defined('IN_CATALOGUE')) {
				$other['txt'] = affiche_arbre_categorie(vn($_GET['catid']));
			} elseif (defined('IN_CATALOGUE_PRODUIT')) {
				$other['txt'] = affiche_arbre_categorie(vn($_GET['catid']));
			} elseif (defined('IN_RUBRIQUE')) {
				$other['txt'] = affiche_arbre_rubrique(vn($_GET['rubid']));
			} elseif (defined('IN_RUBRIQUE_ARTICLE')) {
				$other['txt'] = affiche_arbre_rubrique(vn($_GET['rubid']));
			} elseif (defined('IN_NOUVEAUTES')) {
				$other['txt'] = $GLOBALS['STR_NOUVEAUTES'];
				$other['href'] = $GLOBALS['wwwroot'] . '/achat/nouveautes.php';
			} elseif (defined('IN_PROMOTIONS')) {
				$other['txt'] = $GLOBALS['STR_PROMOTIONS'];
				$other['href'] = $GLOBALS['wwwroot'] . '/achat/promotions.php';
			} elseif (defined('IN_SPECIAL')) {
				$other['txt'] = $GLOBALS['STR_SPECIAL'];
				$other['href'] = $GLOBALS['wwwroot'] . '/achat/special.php';
			} elseif (defined('IN_TOP')) {
				$other['txt'] = $GLOBALS['STR_TOP'];
				$other['href'] = $GLOBALS['wwwroot'] . '/achat/top.php';
			} elseif (defined('IN_FLASH')) {
				$other['txt'] = $GLOBALS['STR_FLASH'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/flash/flash.php';
			} elseif (defined('IN_CHEQUE_CADEAU')) {
				$other['txt'] = $GLOBALS['STR_CHEQUE_CADEAU'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/gift_check/cheques.php';
			} elseif (defined('IN_ORDER_HISTORY')) {
				$other['txt'] = $GLOBALS['STR_ORDER_HISTORY'];
				$other['href'] = $GLOBALS['wwwroot'] . '/achat/historique_commandes.php';
			} elseif (defined('IN_COMPTE')) {
				$other['txt'] = $GLOBALS['STR_COMPTE'];
				$other['href'] = get_account_url(false, false);
			} elseif (defined('IN_CONTACT')) {
				$other['txt'] = $GLOBALS['STR_CONTACT'];
				if (is_module_url_rewriting_active()) {
					$other['href'] = get_contact_url(false, false);
				} else {
					$other['href'] = $GLOBALS['wwwroot'] . '/utilisateurs/contact.php';
				}
			} elseif (defined('IN_CONTACT_US')) {
				$other['txt'] = $GLOBALS['STR_CONTACT_US'];
				$other['href'] = $GLOBALS['wwwroot'] . '/contacts.php';
			} elseif (defined('IN_SEARCH')) {
				$other['txt'] = $GLOBALS['STR_SEARCH'];
				$other['href'] = $GLOBALS['wwwroot'] . '/search.php';
			} elseif (defined('IN_SITEMAP')) {
				$other['txt'] = $GLOBALS['STR_SITEMAP'];
				$other['href'] = $GLOBALS['wwwroot'] . '/sitemap.php';
			} elseif (defined('IN_CGV')) {
				$other['txt'] = $GLOBALS['STR_CGV'];
				$other['href'] = get_cgv_url(false);
			} elseif (defined('IN_FAQ')) {
				$other['txt'] = $GLOBALS['STR_FAQ_TITLE'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/faq/faq.php';
			} elseif (defined('IN_INFO_LEGALE')) {
				$other['txt'] = $GLOBALS['STR_LEGAL_INFORMATION'];
				$other['href'] = $GLOBALS['wwwroot'] . '/legal.php';
			} elseif (defined('IN_CONDITION_PARRAIN')) {
				$other['txt'] = $GLOBALS['STR_CONDITION_PARRAIN'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/parrainage/conditions.php';
			} elseif (defined('IN_PARRAIN_ENTETE')) {
				$other['txt'] = $GLOBALS['STR_PARRAIN_ENTETE'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/parrainage/parrain.php';
			} elseif (defined('IN_CONDITION_AFFILI')) {
				$other['txt'] = $GLOBALS['STR_CONDITION_AFFILI'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/affiliation/conditions.php';
			} elseif (defined('IN_AFFILIATE')) {
				$other['txt'] = $GLOBALS['STR_AFFILIATE'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/affiliation/affiliate.php';
			} elseif (defined('IN_RETAILER')) {
				$other['txt'] = $GLOBALS['STR_RETAILER'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/reseller/retailer.php';
			} elseif (defined('IN_CHANGE_PASSWORD')) {
				$other['txt'] = $GLOBALS['STR_CHANGE_PASSWORD'];
				$other['href'] = $GLOBALS['wwwroot'] . '/utilisateurs/change_mot_passe.php';
			} elseif (defined('IN_CHANGE_PARAMS')) {
				$other['txt'] = $GLOBALS['STR_CHANGE_PARAMS'];
				$other['href'] = $GLOBALS['wwwroot'] . '/utilisateurs/change_params.php';
			} elseif (defined('IN_GET_PASSWORD')) {
				$other['txt'] = $GLOBALS['STR_GET_PASSWORD'];
				$other['href'] = $GLOBALS['wwwroot'] . '/utilisateurs/oubli_mot_passe.php';
			} elseif (defined('IN_REGISTER')) {
				$other['txt'] = $GLOBALS['STR_REGISTER'];
				$other['href'] = get_account_register_url(false, false);
			} elseif (defined('IN_ACCES_ACCOUNT')) {
				$other['txt'] = $GLOBALS['STR_ACCES_ACCOUNT'];
				$other['href'] = $GLOBALS['wwwroot'] . '/membre.php';
			} elseif (defined('IN_TELL_FRIEND')) {
				$other['txt'] = $GLOBALS['STR_TELL_FRIEND'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/direaunami/direaunami.php';
			} elseif (defined('IN_TOUS_LES_AVIS')) {
				$other['txt'] = $GLOBALS['STR_TOUS_LES_AVIS'];
			} elseif (defined('IN_DONNEZ_AVIS')) {
				$other['txt'] = $GLOBALS['STR_DONNEZ_AVIS'];
			} elseif (defined('IN_CADDIE')) {
				$other['txt'] = $GLOBALS['STR_CADDIE'];
				$other['href'] = $GLOBALS['wwwroot'] . '/achat/caddie_affichage.php';
			} elseif (defined('IN_STEP1')) {
				$other['txt'] = $GLOBALS['STR_STEP1'];
			} elseif (defined('IN_STEP2')) {
				$other['txt'] = $GLOBALS['STR_STEP2'];
			} elseif (defined('IN_STEP3')) {
				$other['txt'] = $GLOBALS['STR_STEP3'];
			} elseif (defined('IN_SEARCH_BRAND')) {
				$other['txt'] = $GLOBALS['STR_SEARCH_BRAND'];
			} elseif (defined('IN_PENSE_BETE')) {
				$other['txt'] = $GLOBALS['STR_PENSE_BETE'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/pensebete/voir.php';
			} elseif (defined('IN_DOWNLOAD')) {
				$other['txt'] = $GLOBALS['STR_YOUR_ORDER_DOWNLOAD'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/download/telecharger.php?id=' . vb($_GET['id']) . '&key=' . vb($_GET['key']);
			} elseif (defined('IN_FORUM')) {
				$other['txt'] = $GLOBALS['STR_FORUM'];
				$other['href'] = $GLOBALS['wwwroot'] . '/modules/forum/index.php';
			} elseif (defined('IN_LEXIQUE')) {
				$other['txt'] = $GLOBALS['STR_LEXIQUE'];
				$other['href'] = get_lexicon_url();
			}
			$tpl->assign('ariane', $ariane);
			$tpl->assign('other', $other);
			return $tpl->fetch();
		}
	}
}

if (!function_exists('affiche_filtre')) {
	/**
	 * affiche_filtre()
	 *
	 * @param integer $id
	 * @return
	 */
	function affiche_filtre($id = null, $return_mode = false)
	{
		$output = '';
		if (vn($GLOBALS['site_parameters']['module_filtre']) == 1) {
			if (!empty($id)) {
				$qid = query('SELECT id, c.nom_' . $_SESSION['session_langue'] . ' as categorie, c.image_' . $_SESSION['session_langue'] . '
					FROM peel_categories c
					WHERE c.nom_' . $_SESSION['session_langue'] . '!="" AND c.etat="1" AND id="' . intval($id) . '"
					ORDER BY c.position');
				$cat = fetch_assoc($qid);
				$urlcat_with_suffixe = get_product_category_url($id, $cat['categorie'], true, true);
				// En cas d'ajout d'une option ici, il faut aussi ajouter le champ dans le tableau de vérification dans la fonction params_affiche_produits => if(!in_array($_GET['tri'], array('nom_fr', 'prix'))) {
			} else {
				$urlcat_with_suffixe = get_current_url(false) . '?';
			}
			$tpl = $GLOBALS['tplEngine']->createTemplate('filtre.tpl');
			$tpl->assign('options', array(
					$_SERVER['REQUEST_URI'] => $GLOBALS['STR_ORDER_RESULTS_BY'],
					$urlcat_with_suffixe . 'tri=nom_' . $_SESSION['session_langue'] . '&sort=asc' => $GLOBALS['STR_PRODUCT_NAME'] . ' ' . $GLOBALS['STR_ASC'],
					$urlcat_with_suffixe . 'tri=nom_' . $_SESSION['session_langue'] . '&sort=desc' => $GLOBALS['STR_PRODUCT_NAME'] . ' ' . $GLOBALS['STR_DESC'],
					$urlcat_with_suffixe . 'tri=prix&sort=asc' => $GLOBALS['STR_PRICE'] . ' ' . $GLOBALS['STR_ASC'],
					$urlcat_with_suffixe . 'tri=prix&sort=desc' => $GLOBALS['STR_PRICE'] . ' ' . $GLOBALS['STR_DESC']
					));
			$selected = '';
			if(!empty($_GET['tri']) && !empty($_GET['sort'])) {
				$selected = $urlcat_with_suffixe . 'tri=' . $_GET['tri'] . '&sort=' . $_GET['sort'];
			}
			$tpl->assign('selected', $selected);
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('get_brand_link_html')) {
	/**
	 * Affiche la marque du produit
	 *
	 * @param integer $id_marque
	 * @param boolean $return_mode
	 * @param boolean $show_all_brands_link
	 * @return
	 */
	function get_brand_link_html($id_marque = null, $return_mode = false, $show_all_brands_link = false)
	{
		$output = '';
		$sql = 'SELECT id, nom_' . $_SESSION['session_langue'] . ' AS marque, image
			FROM peel_marques
			WHERE etat=1';
		if (!empty($id_marque)) {
			$sql .= ' AND id="' . intval($id_marque) . '"';
		}
		$query = query($sql);
		$links = array();
		while ($brand = fetch_object($query)) {
			$this_url = $GLOBALS['wwwroot'] . '/achat/marque.php?id=' . $brand->id;
			$links[] = array('href' => $this_url,
				'value' => $brand->marque,
				'image' => $brand->image,
				'is_current' => (get_current_url(true) == $this_url));
		}
		if($show_all_brands_link) {
			$this_url = $GLOBALS['wwwroot'] . '/achat/marque.php';
			$links[] = array('href' => $this_url,
				'value' => $GLOBALS['STR_ALL_BRAND'],
				'image' => '',
				'is_current' => (get_current_url(true) == $this_url));
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('brand_link_html.tpl');
		$tpl->assign('as_list', empty($id_marque));
		$tpl->assign('links', $links);
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_brand_description_html')) {
	/**
	 * get_brand_description_html()
	 *
	 * @param integer $id_marque
	 * @param boolean $return_mode
	 * @param boolean $show_links_to_details
	 * @return
	 */
	function get_brand_description_html($id_marque, $return_mode = false, $show_links_to_details = true)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('brand_description_html.tpl');
		$sql = "SELECT id, image, description_" . $_SESSION['session_langue'] . " AS description, nom_" . $_SESSION['session_langue'] . " AS nom
			FROM peel_marques
			WHERE etat=1 " . (!empty($id_marque)?"AND id = '" . intval($id_marque) . "'":"")."
			ORDER BY position ASC, nom ASC";
		$query = query($sql);
		$tplData = array();
		while ($brand_object = fetch_object($query)) {
			$sql2 = 'SELECT COUNT(*) AS nb_produits
				FROM peel_produits
				WHERE id_marque=' . intval($brand_object->id) . ' AND etat=1';
			$query2 = query($sql2);
			$brand_products = fetch_assoc($query2);
			$tmpData = array('nom' => $brand_object->nom,
				'display_brand' => false,
				);
			if (!empty($brand_object)) {
				$tmpData['display_brand'] = true;
				$tmpData['admin_content'] = a_priv('admin_content');
				if ($tmpData['admin_content']) {
					$tmpData['admin_link'] = array('href' => $GLOBALS['administrer_url'] . '/marques.php?mode=modif&id=' . $brand_object->id, 'name' => $GLOBALS['STR_MODIFY_BRAND']);
				}
				$tmpData['small_width'] = $GLOBALS['site_parameters']['small_width'];
				$tmpData['has_image'] = !empty($brand_object->image);
				if ($tmpData['has_image']) {
					$tmpData['image'] = array('href' => ($show_links_to_details ? $GLOBALS['wwwroot'] . '/achat/marque.php?id=' . $brand_object->id : ''),
							'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($brand_object->image, $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit')
						);
				}
				$tmpData['href'] = ($show_links_to_details ? $GLOBALS['wwwroot'] . '/achat/marque.php?id=' . $brand_object->id : '');
				$tmpData['nb_produits_txt'] = $brand_products['nb_produits'] . ' ' . $GLOBALS['STR_ARTICLES'];
				if($brand_products['nb_produits']<=1 && String::strtolower(String::substr($tmpData['nb_produits_txt'], -1)) == 's') {
					$tmpData['nb_produits_txt'] = String::substr($tmpData['nb_produits_txt'], 0, String::strlen($tmpData['nb_produits_txt'])-1);
				}
				$tmpData['description'] = $brand_object->description;
			}
			$tplData[] = $tmpData;
		}

		if (empty($tplData)) {
			$tpl->assign('is_error', true);
			$tpl->assign('error_header', $GLOBALS['STR_BRAND']);
			$tpl->assign('error_content', $GLOBALS['STR_SEARCH_NO_RESULT_BRAND']);
		} else {
			$tpl->assign('is_error', false);
			$tpl->assign('data', $tplData);
		}
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('get_recursive_items_display')) {
	/**
	 * Affiche les éléments listés dans $all_parents_with_ordered_direct_sons_array
	 *
	 * @param mixed $all_parents_with_ordered_direct_sons_array
	 * @param mixed $item_name_array
	 * @param mixed $this_parent
	 * @param mixed $this_parent_depth
	 * @param mixed $highlighted_item
	 * @param string $mode
	 * @param mixed $location indicates the position in the website : left or right
	 * @param integer $max_depth_allowed
	 * @param integer $item_max_length spécifie le nombre de caractère des ancres dans les liens
	 * @return
	 */
	function get_recursive_items_display(&$all_parents_with_ordered_direct_sons_array, &$item_name_array, $this_parent, $this_parent_depth, $highlighted_item, $mode = 'categories', $location = null, $max_depth_allowed = null, $item_max_length = 25)
	{
		$output = '';
		if (!empty($all_parents_with_ordered_direct_sons_array[$this_parent])) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('recursive_items_display.tpl');
			$tpl->assign('sons_ico_src', $GLOBALS['wwwroot'] . '/images/right.gif');
			$tpl->assign('location', $location);
			$tpl->assign('item_max_length', $item_max_length);
			$tplItems = array();
			$this_depth = $this_parent_depth + 1;
			foreach ($all_parents_with_ordered_direct_sons_array[$this_parent] as $this_item) {
				$searched_item = '';
				$tplItem = array();
				if (!empty($all_parents_with_ordered_direct_sons_array[$this_item])) {
					if(empty($max_depth_allowed) || $this_depth<$max_depth_allowed) {
						$tplItem['has_sons'] = true;
					} else {
						$tplItem['has_sons'] = false;
					}
					// On cherche si le noeud est sélectionné ou un de ses fils l'est
					// On commence par regarder si le noeud actuel est le parent de la sélection
					$searched_item = $highlighted_item;
					$i = 0;
					while ($i++ < 500 && !empty($searched_item) && $searched_item != $this_item && !in_array($searched_item, $all_parents_with_ordered_direct_sons_array[$this_item])) {
						// On cherche un cran plus loin dans l'arborescence
						$result = false;
						foreach ($all_parents_with_ordered_direct_sons_array as $this_tested_item => $tested_items_array) {
							if (in_array($searched_item, $tested_items_array)) {
								$result = $this_tested_item;
							}
						}
						if ($result != $searched_item) {
							$searched_item = $result;
						} else {
							$searched_item = false;
						}
					}
				} else {
					$tplItem['has_sons'] = false;
				}
				$tplItem['is_current'] = ($this_item == $highlighted_item || !empty($searched_item));

				if (!empty($item_name_array[$this_item])) {
					if ($mode == 'categories') {
						$tplItem['href'] = get_product_category_url($this_item, $item_name_array[$this_item]);
					} elseif ($mode == 'categories_annonces') {
						$tplItem['href'] = get_annonce_category_url($this_item, $item_name_array[$this_item]);
					} else {
						$tplItem['href'] = get_content_category_url($this_item, $item_name_array[$this_item]);
					}
					$tplItem['nb'] = null;
					if (vn($GLOBALS['site_parameters']['display_nb_product']) == 1) {
						if ($mode == 'categories_annonces') {
							$nb = calcul_nbannonces_parcat($this_item, $all_parents_with_ordered_direct_sons_array);
							$tplItem['nb'] = $nb;
						} elseif ($mode == 'categories') {
							$nb = calcul_nbprod_parcat($this_item, $all_parents_with_ordered_direct_sons_array);
							$tplItem['nb'] = $nb;
						}
					}
					$tplItem['name'] = $item_name_array[$this_item];
				}

				if (!empty($all_parents_with_ordered_direct_sons_array[$this_item]) && (empty($max_depth_allowed) || $this_depth<$max_depth_allowed)) {
					$tplItem['SONS'] = get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, $this_item, $this_depth, $highlighted_item, $mode, $location, $max_depth_allowed, $item_max_length);
					$tplItem['depth'] = $this_depth;
				}
				if (is_advistofr_module_active())
					$tplItem['technical_code'] = get_technical_code($this_item);

				$tplItems[] = $tplItem;
			}

			$tpl->assign('items', $tplItems);
			$output .= $tpl->fetch();
		}
		return $output;
	}
}

if (!function_exists('print_societe')) {
	/**
	 * print_societe()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function print_societe($return_mode = false)
	{
		$output = '';
		$qid = query('SELECT * FROM peel_societe');
		if ($ligne = fetch_object($qid)) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('societe.tpl');
			$tpl->assign('societe', $ligne->societe);
			$tpl->assign('adresse', $ligne->adresse);
			$tpl->assign('code_postal', $ligne->code_postal);
			$tpl->assign('ville', $ligne->ville);
			$tpl->assign('pays', $ligne->pays);
			$tpl->assign('tel', $ligne->tel);
			$tpl->assign('tel_label', $GLOBALS['STR_SHORT_TEL'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('fax', $ligne->fax);
			$tpl->assign('fax_label', $GLOBALS['STR_SHORT_FAX'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('siren', $ligne->siren);
			$tpl->assign('siren_label', $GLOBALS['STR_SIREN'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('tvaintra', $ligne->tvaintra);
			$tpl->assign('tvaintra_label', $GLOBALS['STR_VAT_INTRACOM'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('cnil', $ligne->cnil);
			$tpl->assign('cnil_label', $GLOBALS['STR_CNIL_NUMBER'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('print_rib')) {
	/**
	 * Affiche la liste des catégories qui sont spéciales
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function print_rib($return_mode = false)
	{
		$output = '';
		$qid = query('SELECT * FROM peel_societe');
		if ($ligne = fetch_object($qid)) {
			$tplData = array();
			if (!empty($ligne->code_banque)) {
				$tplData[] = array('label' => $GLOBALS['STR_BANK_ACCOUNT_CODE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $ligne->code_banque);
			}
			if (!empty($ligne->code_guichet)) {
				$tplData[] = array('label' => $GLOBALS['STR_BOX_OFFICE_CODE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $ligne->code_guichet);
			}
			if (!empty($ligne->numero_compte)) {
				$tplData[] = array('label' => $GLOBALS['STR_ACCOUNT_NUMBER'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $ligne->numero_compte);
			}
			if (!empty($ligne->cle_rib)) {
				$tplData[] = array('label' => $GLOBALS['STR_BANK_ACCOUNT_RIB_KEY'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $ligne->cle_rib);
			}
			if (!empty($ligne->titulaire)) {
				$tplData[] = array('label' => $GLOBALS['STR_ACCOUNT_MASTER'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $ligne->titulaire);
			}
			if (!empty($ligne->domiciliation)) {
				$tplData[] = array('label' => $GLOBALS['STR_DOMICILIATION'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $ligne->domiciliation);
			}
			if (!empty($ligne->iban)) {
				$tplData[] = array('label' => $GLOBALS['STR_IBAN'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $ligne->iban);
			}
			if (!empty($ligne->swift)) {
				$tplData[] = array('label' => $GLOBALS['STR_SWIFT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $ligne->swift);
			}

			if (!empty($tplData)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('rib.tpl');
				$tpl->assign('data', $tplData);
				$output = $tpl->fetch();
			}
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('print_cgv')) {
	/**
	 * NO_TPL print_cgv function is not a view formatting function
	 * print_cgv()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function print_cgv($return_mode = false)
	{
		$output = '';
		$sql = 'SELECT titre_' . $_SESSION['session_langue'] . ' as titre, texte_' . $_SESSION['session_langue'] . ' as texte
			FROM peel_cgv
			WHERE id = "1"';
		$res = query($sql);
		$cgv = fetch_object($res);
		if (!empty($cgv->texte)) {
			$longtext = String::nl2br_if_needed(String::html_entity_decode_if_needed($cgv->texte));
			$title = $cgv->titre;
		} else {
			$title = '';
			$longtext = $GLOBALS['STR_EMPTY_TEXT_CGV'];
		}
		$output .= get_formatted_longtext_with_title($longtext, $title, 'cgv');
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('print_legal')) {
	/**
	 * NO_TPL print_legal function is not a view formatting function
	 * print_legal()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function print_legal($return_mode = false)
	{
		$output = '';
		$sql = "SELECT titre_" . $_SESSION['session_langue'] . " as titre, texte_" . $_SESSION['session_langue'] . " as texte
			FROM peel_legal
			WHERE id = '1'";
		$res = query($sql);
		$cgv = fetch_object($res);
		if (!empty($cgv->texte)) {
			$longtext = String::nl2br_if_needed(String::html_entity_decode_if_needed($cgv->texte));
			$title = $cgv->titre;
		} else {
			$title = '';
			$longtext = $GLOBALS['STR_EMPTY_TEXT_LEGAL'];
		}
		$output .= get_formatted_longtext_with_title($longtext, $title, 'legal');
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('print_contact')) {
	/**
	 * NO_TPL print_contact function is not a view formatting function
	 * print_contact()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function print_contact($return_mode = false)
	{
		$output = '';
		$sql = "SELECT titre_" . $_SESSION['session_langue'] . " AS titre, texte_" . $_SESSION['session_langue'] . " AS texte
			FROM peel_contacts
			WHERE id='1'";
		$res = query($sql);
		$contact_infos = fetch_object($res);
		if (!empty($contact_infos) && !empty($contact_infos->titre)) {
			$title = $contact_infos->titre;
		} else {
			$title = $GLOBALS["STR_CONTACT"];
		}
		if (!empty($contact_infos) && !empty($contact_infos->texte)) {
			$longtext = String::nl2br_if_needed(String::html_entity_decode_if_needed($contact_infos->texte));
		} else {
			$longtext = $GLOBALS['STR_EMPTY_TEXT_CONTACTS'];
		}
		$output .= get_formatted_longtext_with_title($longtext, $title, 'contact');
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('print_actu')) {
	/**
	 * print_actu()
	 *
	 * @param boolean $return_mode
	 * @param integer $rubid
	 * @return
	 */
	function print_actu($return_mode = false, $rubid = null)
	{
		$output = '';
		$sql = 'SELECT p.id, p.surtitre_' . $_SESSION['session_langue'] . ', p.titre_' . $_SESSION['session_langue'] . ', p.chapo_' . $_SESSION['session_langue'] . ', p.texte_' . $_SESSION['session_langue'] . ', p.image1, p.on_special, p.date_maj
			FROM peel_articles p
			'.(!empty($rubid)?'INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id AND pc.rubrique_id='.intval($rubid):'').'
			WHERE p.on_special = "1" AND p.etat = "1"
			ORDER BY p.date_maj DESC
			LIMIT 0,1';
		$query = query($sql);

		if (num_rows($query) > 0) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('actu.tpl');
			$tplData = array();
			while ($art = fetch_assoc($query)) {
				$tplData[] = array('titre' => $art['titre_' . $_SESSION['session_langue']],
					'date' => get_formatted_date(),
					'image_src' => (!empty($art['image1']) ? $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($art['image1'], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit') : null),
					'chapo' => $art['chapo_' . $_SESSION['session_langue']]
					);
			}
			$tpl->assign('data', $tplData);
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('print_new')) {
	/**
	 * print_new()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function print_new($return_mode = false)
	{
		$output = '';
		$qid = query('SELECT p.*, c.id AS categorie_id, c.nom_' . $_SESSION['session_langue'] . ' AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
			INNER JOIN peel_categories c ON c.id=pc.categorie_id
			WHERE p.on_new = "1" AND p.etat = "1"
			GROUP BY p.id
			LIMIT 0,2');
		if (num_rows($qid) > 0) {
			$tplData = array();
			$tpl = $GLOBALS['tplEngine']->createTemplate('new.tpl');
			$tpl->assign('title', NEWS);
			while ($prod = fetch_assoc($qid)) {
				$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
				if (!empty($product_object->image2)) {
					$tplData[] = array('is_full' => true,
						'href' => $product_object->get_product_url(),
						'name' => $product_object->name,
						'src' => $GLOBALS['repertoire_upload'] . '/' . $product_object->image1,
						'trail' => $GLOBALS['repertoire_upload'] . '/' . $product_object->image2,
						'descriptif' => $product_object->descriptif
						);
				} else {
					$tplData[] = array('is_full' => false,
						'href' => $product_object->get_product_url(),
						'alt' => !empty($product_object->image1) ? $product_object->name : $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT'],
						'src' => !empty($product_object->image1) ? $GLOBALS['repertoire_upload'] . '/' . $product_object->image1 : $GLOBALS['repertoire_upload'] . '/pasimage.gif',
						);
				}
				unset($product_object);
			}
			$tpl->assign('data', $tplData);
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('print_compte')) {
	/**
	 * print_compte()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function print_compte($return_mode = false)
	{
		$output = '';
		if (est_identifie()) {
			$user_infos = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
		}
		if (String::substr(vb($user_infos['email_bounce']), 0, 2) == '5.' || empty($user_infos['email'])) {
			// Email vide ou ayant généré une erreur
			$email_form = '';
			$domain = explode('@', vb($user_infos['email']));
			$email_explain = sprintf($GLOBALS['STR_EMAIL_BOUNCE_REPLACE'], vb($domain[1]), vb($user_infos['email_bounce']), vb($user_infos['email']));
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_IMPORTANT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': <a href="' . $GLOBALS['wwwroot'] . '/utilisateurs/change_params.php">' . $email_explain . '</a>'))->fetch();
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('compte.tpl');
		$tpl->assign('compte', $GLOBALS['STR_COMPTE']);
		$tpl->assign('msg_support', $GLOBALS['STR_SUPPORT']);
		$est_identifie = est_identifie();
		$tpl->assign('est_identifie', $est_identifie);
		if ($est_identifie) {
			$u = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
			$tpl->assign('number', $GLOBALS['STR_NUMBER']);
			$tpl->assign('code_client', $u['code_client']);
			$tpl->assign('my_order', $GLOBALS['STR_MY_ORDER']);
			$tpl->assign('order_history', $GLOBALS['STR_ORDER_HISTORY']);
			$tpl->assign('order_history_href', $GLOBALS['wwwroot'] . '/achat/historique_commandes.php');
			if (is_cart_preservation_module_active()) {
				$tpl->assign('cart_preservation', array('txt' => $GLOBALS['STR_CART_PRESERVATION_TITLE'], 'href' => $GLOBALS['wwwroot'] . '/modules/cart_preservation/cart_preservation.php'));
			}
			if (is_payback_module_active()) {
				$tpl->assign('return_history', array('header' => $GLOBALS['STR_MODULE_PAYBACK_MY_RETURN'], 'txt' => $GLOBALS['STR_MODULE_PAYBACK_RETURN_HISTORY'], 'href' => $GLOBALS['wwwroot'] . '/modules/payback/historique_retours.php'));
			}
			if (is_annonce_module_active()) {
				$sql = "SELECT id 
					FROM peel_categories 
					WHERE technical_code = 'ads'";
				$query_cat = query($sql);
				$cat = fetch_assoc($query_cat);
				$tpl->assign('ads', array('header' => $GLOBALS['STR_MODULE_ANNONCES_MY_ADS'],
						'STR_MODULE_ANNONCES_MY_AD_LIST' => $GLOBALS['STR_MODULE_ANNONCES_MY_AD_LIST'],
						'list_href' => get_ad_wholesaler_url(true) . 'affiche=perso',
						'STR_MODULE_ANNONCES_AD_CREATE' => $GLOBALS['STR_MODULE_ANNONCES_AD_CREATE'],
						'create_href' => $GLOBALS['wwwroot'] . '/modules/annonces/creation_annonce.php',
						'STR_MODULE_ANNONCES_BUY_GOLD_ADS' => $GLOBALS['STR_MODULE_ANNONCES_BUY_GOLD_ADS'],
						'buy_href' => $GLOBALS['wwwroot'] . '/achat/?catid=' . $cat['id'],
						)
					);
			}
			if (is_telechargement_module_active()) {
				$sql = "SELECT *
					FROM peel_telechargement
					WHERE user_restriction='".intval($_SESSION['session_utilisateur']['id_utilisateur'])."'";
				$res = query($sql);
				while($select = fetch_assoc($res)) {
					$select['href'] = $GLOBALS['wwwroot'] . '/modules/telechargement/download.php?id=' . $select['id'];
					$download_links[] = $select;
				}
				if(!empty($download_links)) {
					$tpl->assign('download_links', $download_links);
				}
				$tpl->assign('STR_DOWNLOAD_CENTER', $GLOBALS['STR_DOWNLOAD_CENTER']);
			}
			if (is_vitrine_module_active()) {
				$tpl->assign('shop', array('header' => $GLOBALS['STR_MODULE_ANNONCES_MY_SHOP'], 'txt' => $GLOBALS['STR_MODULE_ANNONCES_CREATE_MY_SHOP'] . ' / ' . $GLOBALS['STR_MODULE_ANNONCES_UPDATE_YOUR_SHOP'], 'href' => $GLOBALS['wwwroot'] . '/modules/vitrine/boutique_form.php'));
			}
			$tpl->assign('change_params', array('header' => $GLOBALS['STR_CHANGE_PARAMS'], 'txt' => $GLOBALS['STR_CHANGE_PARAMS'], 'href' => $GLOBALS['wwwroot'] . '/utilisateurs/change_params.php'));
			$tpl->assign('change_password', array('txt' => $GLOBALS['STR_CHANGE_PASSWORD'], 'href' => $GLOBALS['wwwroot'] . '/utilisateurs/change_mot_passe.php'));
			if (is_module_blog_active()) {
				$tpl->assign('MON_COMPTE_BLOG', get_mon_compte_blog());
			}
			if (is_giftlist_module_active()) {
				$tpl->assign('giftlist', array('header' => $GLOBALS['STR_LISTE_CADEAU'], 'txt' => $GLOBALS['STR_VOIR_LISTE_CADEAU'], 'href' => $GLOBALS['wwwroot'] . '/modules/listecadeau/voir.php'));
			}
			if (is_module_pensebete_active()) {
				$tpl->assign('pensebete', array('header' => $GLOBALS['STR_PENSE_BETE'], 'txt' => $GLOBALS['STR_VOIR_PENSE_BETE'], 'href' => $GLOBALS['wwwroot'] . '/modules/pensebete/voir.php'));
			}
			if (is_parrainage_module_active()) {
				$tpl->assign('parrainage', array('header' => $GLOBALS['STR_PARRAIN_ENTETE'], 'txt' => sprintf($GLOBALS['STR_PARRAIN_TEXTE'], fprix($GLOBALS['site_parameters']['avoir']), fprix($GLOBALS['site_parameters']['avoir'])), 'href' => $GLOBALS['wwwroot'] . '/modules/parrainage/parrain.php'));
			}
			// les codes utilisés
			$code_promo_query = query('SELECT code_promo, valeur_code_promo, percent_code_promo
				FROM peel_commandes pc
				WHERE pc.id_utilisateur = "' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '" AND code_promo != ""');
			if (num_rows($code_promo_query) > 0) {
				$cpu_data = array();
				while ($cp = fetch_assoc($code_promo_query)) {
					$cpu_data[] = array('code_promo' => $cp['code_promo'], 'discount_text' => get_discount_text($cp['valeur_code_promo'], $cp['percent_code_promo'], display_prices_with_taxes_active()));
				}
				$tpl->assign('code_promo_utilise', array('header' => $GLOBALS['STR_MES_CODE_PROMO_UTILISE'], 'data' => $cpu_data));
			}
			// les codes qui peuvent être encore utilisés
			$current_code_promo_query = query('SELECT *
				FROM peel_utilisateurs_codes_promos ucp
				INNER JOIN peel_codes_promos cp ON cp.id = ucp.id_code_promo AND cp.etat = "1" AND ("' . date('Y-m-d', time()) . '" BETWEEN cp.date_debut AND cp.date_fin)
				WHERE ucp.id_utilisateur = "' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '" AND (cp.nombre_prevue=0 OR ucp.utilise<cp.nombre_prevue) AND (cp.nb_used_per_client=0 OR ucp.utilise<cp.nb_used_per_client)');
			if (num_rows($current_code_promo_query) > 0) {
				$cpv_data = array();
				while ($cp = fetch_assoc($current_code_promo_query)) {
					$cpv_data[] = array('nom_code' => $cp['nom_code'], 
						'discount_text' => get_discount_text($cp['remise_valeur'], $cp['remise_percent'], display_prices_with_taxes_active()),
						'code_promo_valid_from' => $GLOBALS['STR_CODE_PROMO_VALID_FROM'], 
						'date_from' => get_formatted_date($cp['date_debut']),
						'flash_to' => $GLOBALS['STR_FLASH_TO'], 
						'date_to' => get_formatted_date($cp['date_fin'])
					);
				}
				$tpl->assign('code_promo_valide', array('header' => $GLOBALS['STR_MES_CODE_PROMO_VALIDE'], 'data' => $cpv_data));
			}
			if ($u['remise_percent'] > 0) {
				$tpl->assign('remise_percent', array('label' => $GLOBALS['STR_REMISE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $u['remise_percent']));
			}
			if ($u['avoir'] > 0) {
				$tpl->assign('avoir', array('label' => $GLOBALS['STR_AVOIR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => fprix($u['avoir'], true)));
			}
			if (is_affiliate_module_active()) {
				if (a_priv('affi', false)) {
					$tpl->assign('affiliate', array('account' => $GLOBALS['STR_AFFILIATE_ACCOUNT'],
							'account_msg' => $GLOBALS['STR_AFFILIATE_ACCOUNT_MSG'],
							'account_url' => $GLOBALS['STR_AFFILIATE_ACCOUNT_URL'],
							'account_href' => $GLOBALS['wwwroot'] . '/?affilie=' . $_SESSION['session_utilisateur']['id_utilisateur'],
							'account_prod_href' => $GLOBALS['wwwroot'] . '/modules/affiliation/affiliation_produits_liste.php',
							'STR_AFFILIATE_ACCOUNT_PROD' => $GLOBALS['STR_AFFILIATE_ACCOUNT_PROD'],
							'account_ban_href' => $GLOBALS['wwwroot'] . '/modules/affiliation/affiliation_produits_liste.php?mode=generehtmlstd',
							'STR_AFFILIATE_ACCOUNT_BAN' => $GLOBALS['STR_AFFILIATE_ACCOUNT_BAN'],
							'account_sell_href' => $GLOBALS['wwwroot'] . '/modules/affiliation/affiliation_rapport_ventes.php',
							'STR_AFFILIATE_ACCOUNT_SELL' => $GLOBALS['STR_AFFILIATE_ACCOUNT_SELL']
						));
				}
			}

			$profil = is_module_profile_active() ? get_profil($_SESSION['session_utilisateur']['priv']) : null;
			if (is_module_profile_active()) {
				if (!empty($profil['document']) || !empty($profil['description_document'])) {
					$tpl->assign('profile', array('header' => $GLOBALS['STR_ACCOUNT_DOCUMENTATION'],
							'href' => $GLOBALS['repertoire_upload'] . '/' . $profil['document'],
							'txt' => DOWNLOAD_DOCUMENT
							));
				}
			}

			$tpl->assign('logout', array('href' => $GLOBALS['wwwroot'] . '/sortie.php', 'txt' => $GLOBALS['STR_LOGOUT']));

			if (a_priv('admin*', true)) {
				$tpl->assign('admin', array('href' => $GLOBALS['administrer_url'] . '/index.php', 'txt' => $GLOBALS['STR_ADMIN']));
			}
			if (is_abonnement_module_active()) {
				$tpl->assign('ABONNEMENT_MODULE', verified_status_activated());
			}
			if (is_annonce_module_active()) {
				$qannonce = query('SELECT gold_credit
					FROM peel_utilisateurs
					WHERE id_utilisateur="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '"');
				$annonce = fetch_assoc($qannonce);
				$tpl->assign('annonce', array('label' => $GLOBALS['STR_MODULE_ANNONCES_GOLD_AD_CREDIT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'credit' => $annonce['gold_credit']));
			}
			if (is_user_alerts_module_active()) {
				$tpl->assign('user_alerts', array('txt' => $GLOBALS["STR_MODULE_USER_ALERTS_MY_ALERTS"], 'href' => $GLOBALS['wwwroot'] . '/modules/user_alerts/mes_alertes.php'));
			}
		} else {
			$tpl->assign('register_href', get_account_register_url(false, false));
			$tpl->assign('register', $GLOBALS['STR_REGISTER']);
			$tpl->assign('login_href', $GLOBALS['wwwroot'] . '/membre.php');
			$tpl->assign('login', $GLOBALS['STR_LOGIN']);
		}
		$output .= $tpl->fetch();
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_mini_caddie')) {
	/**
	 * affiche_mini_caddie()
	 *
	 * @param mixed $detailed
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_mini_caddie($detailed = true, $return_mode = false)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('mini_caddie.tpl');
		$tpl->assign('logo_src', $GLOBALS['repertoire_images'] . '/cart-logo.png');
		$tpl->assign('affichage_href', $GLOBALS['wwwroot'] . '/achat/caddie_affichage.php');
		$tpl->assign('count_products', $_SESSION['session_caddie']->count_products());
		$tpl->assign('products_txt', ($_SESSION['session_caddie']->count_products() > 1 ? str_replace(array('(', ')'), array(''), $GLOBALS['STR_CADDIE_OBJECTS_COUNT']) : str_replace(array('(s)', '(es)', '(n)', '(en)'), '', $GLOBALS['STR_CADDIE_OBJECTS_COUNT'])));

		$tpl->assign('has_details', false);
		if ($_SESSION['session_caddie']->count_products() != 0 && $detailed) {
			$tpl->assign('has_details', true);
			$tplProducts = array();
			foreach ($_SESSION['session_caddie']->articles as $numero_ligne => $product_id) {
				$tmpProd = array();
				$product_object = new Product($product_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
				$product_object->set_configuration($_SESSION['session_caddie']->couleurId[$numero_ligne], $_SESSION['session_caddie']->tailleId[$numero_ligne], is_reseller_module_active() && is_reseller());
				// Récupére la taille si elle existe
				if (!empty($product_object->configuration_size_id)) {
					$tmpProd['size'] = array('label' => $GLOBALS['STR_SIZE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $product_object->get_size());
				}
				$urlprod = $product_object->get_product_url();
				if (!empty($product_object->configuration_color_id)) {
					// Si le produit a une couleur
					$tmpProd['color'] = array('label' => $GLOBALS['STR_COLOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $product_object->get_color());
					$urlprod .= '?cId=' . $_SESSION['session_caddie']->couleurId[$numero_ligne];
					if (isset($tmpProd['size'])) { // Si le produit a une couleur et une taille
						$urlprod .= '&sId=' . $_SESSION['session_caddie']->tailleId[$numero_ligne];
					}
				} elseif (isset($tmpProd['size'])) { // si le produit a seulement une taille
					$urlprod .= '?sId=' . $_SESSION['session_caddie']->tailleId[$numero_ligne];
				}
				$tmpProd['href'] = $urlprod;

				if (display_prices_with_taxes_active()) {
					$price_displayed = $_SESSION['session_caddie']->total_prix[$numero_ligne];
				} else {
					$price_displayed = $_SESSION['session_caddie']->total_prix_ht[$numero_ligne];
				}
				$tmpProd['quantite'] = $_SESSION['session_caddie']->quantite[$numero_ligne];
				$tmpProd['name'] = $product_object->name;
				$tmpProd['price'] = fprix($price_displayed, true);
				$tplProducts[] = $tmpProd;
				unset($product_object);
			}
			$tpl->assign('products', $tplProducts);

			if ($_SESSION['session_caddie']->total > 0) {
				if (display_prices_with_taxes_active()) {
					$total_displayed = $_SESSION['session_caddie']->total;
					$shipping_displayed = $_SESSION['session_caddie']->cout_transport;
				} else {
					$total_displayed = $_SESSION['session_caddie']->total_ht;
					$shipping_displayed = $_SESSION['session_caddie']->cout_transport_ht;
				}
				if (!empty($_SESSION['session_caddie']->cout_transport)) {
					$tpl->assign('transport', array('label' => $GLOBALS['STR_SHIPPING_COST'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => fprix($shipping_displayed, true)));
				}
				if (display_prices_with_taxes_active()) {
					$tpl->assign('total', array('label' => $GLOBALS["STR_NET"] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => fprix($total_displayed, true)));
				} else {
					$tpl->assign('total', array('label' => $GLOBALS['STR_TOTAL_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => fprix($total_displayed, true)));
				}
			}
		}
		$tpl->assign('STR_CADDIE', $GLOBALS['STR_CADDIE']);
		$tpl->assign('STR_DETAILS_ORDER', $GLOBALS['STR_DETAILS_ORDER']);
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('affiche_sideblock')) {
	/**
	 * affiche_sideblock()
	 *
	 * @param mixed $content
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_sideblock($title, $text, $block_class, $return_mode = false)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('sideblock.tpl');
		$tpl->assign('block_class', $block_class);
		$tpl->assign('text', $text);
		$output .= $tpl->fetch();
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_sideblocktitle')) {
	/**
	 * affiche_sideblocktitle()
	 *
	 * @param mixed $content
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_sideblocktitle($title, $text, $block_class, $return_mode = false)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('sideblocktitle.tpl');
		$tpl->assign('block_class', $block_class);
		$tpl->assign('title', $title);
		$tpl->assign('text', $text);
		$output .= $tpl->fetch();
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_block')) {
	/**
	 *
	 * @param mixed $content
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_block($display_mode = 'sideblocktitle', $location = '', $technical_code = '', $title = '', $content = '', $block_class = '', $block_style = '', $return_mode = true, $is_slider_mode = false)
	{
		$mode = (!empty($rewrite_mame_mode)) ? clean_str($display_mode) : $display_mode;
		$tpl = $GLOBALS['tplEngine']->createTemplate('block.tpl');
		$tpl->assign('is_slider_mode', $is_slider_mode);
		$tpl->assign('lang', $_SESSION['session_langue']);
		$tpl->assign('mode', $mode);
		$tpl->assign('block_class', $block_class);
		$tpl->assign('location', $location);
		$tpl->assign('technical_code', $technical_code);
		$tpl->assign('block_style', $block_style);
		$tpl->assign('content', $content);
		$tpl->assign('title', $title);
		$tpl->assign('STR_PREVIOUS_PAGE', $GLOBALS['STR_PREVIOUS_PAGE']);
		$tpl->assign('STR_NEXT_PAGE', $GLOBALS['STR_NEXT_PAGE']);
		$output .= $tpl->fetch();
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_menu_recherche')) {
	/**
	 * affiche_menu_recherche()
	 *
	 * @param boolean $return_mode
	 * @param string $display_mode
	 * @return
	 */
	function affiche_menu_recherche($return_mode = false, $display_mode = 'header')
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('menu_recherche.tpl');
		$tpl->assign('action', $GLOBALS['wwwroot'] . '/search.php');
		$tpl->assign('use_autocomplete', $GLOBALS['site_parameters']['enable_prototype'] == 1);
		$tpl->assign('autocomplete_href', $GLOBALS['wwwroot'] . '/modules/search/produit.php');
		$tpl->assign('display_mode', $display_mode);
		if (is_advanced_search_active()) {
			$tpl->assign('advanced_search_script', get_advanced_search_script());
			$tpl->assign('select_marque', affiche_select_marque(true));
		}
		$tpl->assign('STR_SEARCH', $GLOBALS["STR_SEARCH"]);
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('affiche_guide')) {
	/**
	 * affiche_guide()
	 *
	 * @param mixed $location indicates the position in the website : left or right
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_guide($location, $return_mode = false, $more_infos = true)
	{
		if (!is_advistofr_module_active()) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('guide.tpl');
			$tplLinks = array();
			$tplLinks[] = array('name' => 'contact', 'href' => $GLOBALS['wwwroot'] . '/contacts.php', 'label' => $GLOBALS['STR_CONTACT_INFO'], 'selected' => defined('IN_CONTACT_US'));
			if (is_affiliate_module_active())
				$tplLinks[] = array('name' => 'affiliate', 'href' => $GLOBALS['wwwroot'] . '/modules/affiliation/affiliate.php', 'label' => $GLOBALS['STR_AFFILIATE'], 'selected' => defined('IN_AFFILIATE'));
			if (is_reseller_module_active())
				$tplLinks[] = array('name' => 'retailer', 'href' => $GLOBALS['wwwroot'] . '/modules/reseller/retailer.php', 'label' => $GLOBALS['STR_RETAILER'], 'selected' => defined('IN_RETAILER'));
			if (is_module_faq_active())
				$tplLinks[] = array('name' => 'faq', 'href' => $GLOBALS['wwwroot'] . '/modules/faq/faq.php', 'label' => $GLOBALS['STR_FAQ_TITLE'], 'selected' => defined('IN_FAQ'));
			if (is_module_forum_active())
				$tplLinks[] = array('name' => 'forum', 'href' => $GLOBALS['wwwroot'] . '/modules/forum/index.php', 'label' => $GLOBALS['STR_FORUM'], 'selected' => defined('IN_FORUM'));
			if (is_lexique_module_active())
				$tplLinks[] = array('name' => 'lexique', 'href' => get_lexicon_url(), 'label' => $GLOBALS['STR_LEXIQUE'], 'selected' => defined('IN_LEXIQUE'));
			if (is_partenaires_module_active())
				$tplLinks[] = array('name' => 'partner', 'href' => $GLOBALS['wwwroot'] . '/modules/partenaires/partenaires.php', 'label' => $GLOBALS['STR_OUR_PARTNER'], 'selected' => defined('IN_PARTNER'));
			if (is_references_module_active())
				$tplLinks[] = array('name' => 'references', 'href' => $GLOBALS['wwwroot'] . '/modules/references/references.php', 'label' => $GLOBALS['STR_NOS_REFERENCES'], 'selected' => defined('IN_REFERENCE'));
			$tplLinks[] = array('name' => 'access_plan', 'href' => $GLOBALS['wwwroot'] . '/plan_acces.php', 'label' => $GLOBALS['STR_ACCESS_PLAN'], 'selected' => defined('IN_PLAN_ACCES'));
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('guide_advistofr_module.tpl');
			$tplLinks[] = array('name' => 'contact', 'href' => $GLOBALS['wwwroot'] . '/utilisateurs/contact.php', 'label' => 'STR_CONTACT', 'selected' => defined('IN_CONTACT'));
		}
		$tpl->assign('links', $tplLinks);
		$tpl->assign('menu_contenu', affiche_menu_contenu($location, true, false));

		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('affiche_footer')) {
	/**
	 * affiche_footer()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_footer($return_mode = false)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('footer.tpl');
		$tpl->assign('site', $GLOBALS['site']);
		$tpl->assign('propulse', $GLOBALS['STR_PROPULSE']);
		$tplLinks = array();
		$tplLinks['legal'] = array('href' => $GLOBALS['wwwroot'] . '/legal.php', 'label' => $GLOBALS['STR_LEGAL_INFORMATION'], 'selected' => false);
		$tplLinks['cgv'] = array('href' => $GLOBALS['wwwroot'] . '/cgv.php', 'label' => $GLOBALS['STR_CGV'], 'selected' => defined('IN_CGV'));
		if (is_parrainage_module_active()) {
			$tplLinks['parrain'] = array('href' => $GLOBALS['wwwroot'] . '/modules/parrainage/conditions.php', 'label' => $GLOBALS['STR_CONDITION_PARRAIN'], 'selected' => defined('IN_CONDITION_PARRAIN'));
		}
		if (is_affiliate_module_active()) {
			$tplLinks['affiliate'] = array('href' => $GLOBALS['wwwroot'] . '/modules/affiliation/conditions.php', 'label' => $GLOBALS['STR_CONDITION_AFFILI'], 'selected' => defined('IN_CONDITION_AFFILI'));
		}
		$tpl->assign('links', $tplLinks);
		if (is_module_rss_active()) {
			$tpl->assign('rss', affiche_rss(true));
		}
		if (is_facebook_module_active()) {
			$tpl->assign('facebook_page', get_facebook_page('Facebook'));
		}
		$tpl->assign('contenu_html', affiche_contenu_html("footer_link", true));
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('affiche_compte')) {
	/**
	 * affiche_compte()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_compte($return_mode = false)
	{
		$output = '';
		if (est_identifie()) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('compte_mini.tpl');
			$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
			$tpl->assign('membre_href', $GLOBALS['wwwroot'] . '/membre.php');
			$tpl->assign('prenom', vb($_SESSION['session_utilisateur']['prenom']));
			$tpl->assign('nom_famille', vb($_SESSION['session_utilisateur']['nom_famille']));
			$tpl->assign('sortie_href', $GLOBALS['wwwroot'] . '/sortie.php');
			if (function_exists('get_social_icone')) {
				$tpl->assign('social_icone', get_social_icone());
			}
			if (is_module_url_rewriting_active()) {
				$tpl->assign('compte_href', get_account_url(false, false));
			} else {
				$tpl->assign('compte_href', $GLOBALS['wwwroot'] . '/compte.php');
			}
			$tpl->assign('history_href', $GLOBALS['wwwroot'] . '/achat/historique_commandes.php');
			if (is_facebook_connect_module_active() && !empty($_SESSION['session_utilisateur']['connected_by_fb'])) {
				$tpl->assign('fb_deconnect_lbl', $GLOBALS['STR_FB_DECONNECT']);
			}
			$tpl->assign('STR_HELLO', $GLOBALS['STR_HELLO']);
			$tpl->assign('STR_COMPTE', $GLOBALS['STR_COMPTE']);
			$tpl->assign('STR_DECONNECT', $GLOBALS['STR_DECONNECT']);
			$tpl->assign('STR_ORDER_HISTORY', $GLOBALS['STR_ORDER_HISTORY']);
			$output .= $tpl->fetch();
		} else {
			if (is_module_url_rewriting_active()) {
				$url_enregistrement = get_account_register_url(false, false);
			} else {
				$url_enregistrement = $GLOBALS['wwwroot'] . "/utilisateurs/enregistrement.php";
			}
			$tpl = $GLOBALS['tplEngine']->createTemplate('compte_login_mini.tpl');
			$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
			$tpl->assign('email_lbl', $GLOBALS['STR_EMAIL']);
			$tpl->assign('email', vb($frm['email']));
			$tpl->assign('password_lbl', $GLOBALS['STR_PASSWORD']);
			$tpl->assign('password', vb($frm['mot_passe']));
			$tpl->assign('TOKEN', get_form_token_input('membre.php', true));
			$tpl->assign('forgot_pass_href', $GLOBALS['wwwroot'] . '/utilisateurs/oubli_mot_passe.php');
			$tpl->assign('forgot_pass_lbl', $GLOBALS['STR_FORGOT_YOUR_PASSWORD']);
			$tpl->assign('enregistrement_href', $url_enregistrement);
			$tpl->assign('enregistrement_lbl', $GLOBALS['STR_REGISTER']);
			$tpl->assign('via_lbl', $GLOBALS['STR_VIA']);
			if (function_exists('get_social_icone')) {
				$tpl->assign('social_icone', get_social_icone());
			}
			$social = array('is_any' => false);
			if (is_facebook_connect_module_active()) {
				$social['is_any'] = true;
				$social['facebook'] = get_facebook_connect_btn();
			}
			if (is_sign_in_twitter_module_active()) {
				$social['is_any'] = true;
				$social['twitter'] = get_sign_in_twitter_btn();
			}
			if (is_openid_module_active()) {
				$social['is_any'] = true;
				$social['openid'] = get_openid_btn();
			}
			$tpl->assign('social', $social);
			$tpl->assign('STR_COMPTE', $GLOBALS['STR_COMPTE']);
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('getHTMLHead')) {
	/**
	 * getHTMLHead()
	 *
	 * @param mixed $page_name
	 * @return
	 */
	function getHTMLHead($page_name, &$header_html)
	{
		$output = '';
		$js_output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('HTMLHead.tpl');
		$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
		if(!empty($GLOBALS['meta_rss_links_array'])){
			$link_rss_html = '';
			foreach($GLOBALS['meta_rss_links_array'] as $this_title => $this_url){
				$link_rss_html .= '
		<link rel="alternate" type="application/rss+xml" title="'.String::str_form_value($this_title).'" href="'.String::str_form_value($this_url).'" />';
			}
			$tpl->assign('link_rss_html', $link_rss_html);
		}
		if (!empty($page_name)) {
			$tpl->assign('meta', affiche_meta($page_name, true));
		} else {
			if (defined('IN_PARTNER')) {
				$default_title = $GLOBALS['STR_OUR_PARTNER'];
			} elseif (defined('IN_MAP')) {
				$default_title = $GLOBALS['STR_WORD_RESELLER'];
			} elseif (defined('IN_RETAILER')) {
				$default_title = $GLOBALS['STR_RETAILER_SUBSCRIBE'];
			} elseif (defined('IN_REFERENCE')) {
				$default_title = $GLOBALS['STR_REFERENCE_ON_LINE_SHOP'];
			} elseif (defined('IN_DEVIS')) {
				$default_title = $GLOBALS['STR_DEVIS_ON_LINE_SHOP'];
			} elseif (defined('IN_DOWNLOAD_PEEL')) {
				$default_title = $GLOBALS['STR_MODULE_PEEL_DOWNLOAD_PEEL'];
			} else {
				$default_title = null;
			}
			$tpl->assign('meta', affiche_meta($default_title, true));
		}

		if (!empty($GLOBALS['site_parameters']['favicon'])) {
			$tpl->assign('favicon_href', $GLOBALS['repertoire_upload'] . '/' . $GLOBALS['site_parameters']['favicon']);
		}

		if (is_vitrine_module_active()) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/vitrine/css/vitrine.css';
		}
		if (is_annonce_module_active()) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/rating_bar/rating.css';
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/annonces.css';
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/contentslider/contentslider.css';
		}
		if (is_carrousel_module_active()) {
			// Librairie pour activer le carrousel Module a la carte et partenaire
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/carrousel/css/carrousel.css';
		}
		$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/lightbox.css';
		if (vb($GLOBALS['site_parameters']['zoom']) == 'jqzoom' && vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/jqzoom.css';
		} elseif (vb($GLOBALS['site_parameters']['zoom']) == 'cloud-zoom' && vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/cloudzoom.css';
		}
		// Début des javascripts
		if (is_fianet_module_active()) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/fianet/lib/js/fianet.js';
		}
		if (vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/jquery.js';
		}
		$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/jquery-ui.css';
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/jquery-ui.js';
		if(file_exists($GLOBALS['dirroot'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js')) {
			// Configuration pour une langue donnée
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js';
		}
		// <!-- librairie pour activer le zoom sur les categories (et produits si configuration dans l'administration) -->
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/lightbox.js';
		// <!-- fin de librairie pour activer le zoom sur les categories -->
		if (vb($GLOBALS['site_parameters']['enable_prototype']) == 1) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/prototype.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/effects.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/controls.js';
		}
		if (is_annonce_module_active()) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/rating_bar/js/rating.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/contentslider/contentslider.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/tooltip.js';
		}
		if (is_carrousel_module_active()) {
			// Pour ajouter des vidéos ou des effets au carrousel nivo_slider, il faut inclure les fichiers suivants :
			// AnythingSlider optional extensions
			// $GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/jquery.anythingslider.fx.min.js';
			// $GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/jquery.anythingslider.video.min.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/slides.min.jquery.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/carrousel.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/jquery.anythingslider.min.js';
		}
		if (is_advistofr_module_active()) {
			$rub_query = query("SELECT technical_code
				FROM peel_rubriques r
				WHERE r.id ='" . intval(vn($_GET['rubid'])) . "'");
			$rub = fetch_assoc($rub_query);
			if (!empty($rub)) {
				if (is_clients_module_active() && defined('IN_RUBRIQUE') && $rub['technical_code'] == 'clients') {
					$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/clients/style/style.css';
					$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/clients/dzsscroller/scroller.css';
					$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/clients/prettyphoto/prettyPhoto.css';
					$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/clients/dzsscroller/jquery.mousewheel.js';
					$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/clients/dzsscroller/scroller.js';
					$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/clients/dzsscroller/scrollergallery.js';
					$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/clients/js/jquery.masonry.min.js';
					$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/clients/prettyphoto/jquery.prettyPhoto.js';
				}
				if (is_references_module_active() && defined('IN_RUBRIQUE') && $rub['technical_code'] == 'creation') {
					$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/references/style/style.css';
					$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/references/phoenixgallery/style/style.css';
					$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/jquery.easing.min.js';
					$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/references/phoenixgallery/js/phoenixgallery.js';
				}
			}
			if (is_photodesk_module_active() && defined('IN_CONTACT')) {
				$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/photodesk/css/style.css';
				$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/photodesk/js/jquery.transform-0.6.2.min.js';
				$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/photodesk/js/jquery.animate-shadow-min.js';
				$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/photodesk/js/photodesk.js';
				$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/photodesk/js/jquery-ui-1.8.16.custom.min.js';
			}
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/advistofr/advistofr.js';
		}
		// Librairie pour activer le zoom sur les produits
		if (is_welcome_ad_module_active()) {
			load_welcome_ad();
			$js_output .= get_welcome_ad_script();
		}
		if ($GLOBALS['site_parameters']['zoom'] == 'jqzoom' && $GLOBALS['site_parameters']['enable_jquery'] == 1) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/jquery.jqzoom-core-pack.js';
		} elseif ($GLOBALS['site_parameters']['zoom'] == 'cloud-zoom' && $GLOBALS['site_parameters']['enable_jquery'] == 1) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/cloud-zoom.1.0.2.js';
		}
		if (is_destockplus_module_active() || is_algomtl_module_active() || is_advistocom_module_active()) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modeles/' . vb($GLOBALS['site_parameters']['template_directory']) . '/menu.js';
		}
		if (is_module_forum_active() && $_SESSION['session_langue'] == 'fr') {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/forum/forum.js';
		}
		if (is_cart_popup_module_active() && !empty($_SESSION['session_show_caddie_popup'])) {
			$js_output .= get_cart_popup_script();
		}
		if (is_googlefriendconnect_module_active()) {
			$js_output .= google_friend_connect_javascript_library();
		}
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/filesearchhover.js';
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/overlib.js';
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/advisto.js';
		if (vb($GLOBALS['site_parameters']['anim_prod']) == 1) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/fly-to-basket.js';
		}
		$js_content_array[] = '
(function($) {
    $(document).ready(function() {
		$(".datepicker").datepicker({                    
			dateFormat: "'.str_replace(array('%d','%m','%Y','%y'), array('dd','mm','yy','y'), $GLOBALS['date_format_short']).'",
			changeMonth: true,
			changeYear: true,
			yearRange: "1902:2037"
		});
		'.vb($js_sortable).'
   });
})(jQuery);
';
		// On met en dernier fichiers CSS du site pour qu'ils aient priorité
		if(!empty($GLOBALS['site_parameters']['css'])) {
			foreach (explode(',', $GLOBALS['site_parameters']['css']) as $this_css_filename) {
				$GLOBALS['css_files'][] = $GLOBALS['repertoire_css'] . '/' . trim($this_css_filename);
			}
		}
		$GLOBALS['css_files'] = array_unique($GLOBALS['css_files']);
		if(!empty($GLOBALS['site_parameters']['minify_css'])) {
			$GLOBALS['css_files'] = get_minified_src($GLOBALS['css_files'], 'css', 3600);
		}
		$tpl->assign('css_files', $GLOBALS['css_files']);
		// L'ordre des fichiers js doit être respecté ensuite dans le template
		if(!empty($GLOBALS['site_parameters']['minify_js'])) {		
			$GLOBALS['js_files'] = get_minified_src($GLOBALS['js_files'], 'js', 3600);
		}
		$tpl->assign('js_files', array_unique($GLOBALS['js_files']));
		if(!empty($js_content_array)) {
			$tpl->assign('js_content', implode("\n", $js_content_array));
		}
		$tpl->assign('msg_err_keyb', $GLOBALS['STR_ERR_KEYB']);
		$tpl->assign('js_output', $js_output);

		if (isset($_GET['catid'])) {
			$queryCP = query('SELECT header_html_' . $_SESSION['session_langue'] . ' AS header_html, background_menu, background_color
				FROM peel_categories
				WHERE id="' . intval($_GET['catid']) . '"');
			if ($CP = fetch_object($queryCP)) {
				// $header_html is a reference of a global variable => it will be used outside this function
				$header_html = String::html_entity_decode_if_needed(trim($CP->header_html));
				$background_menu = String::html_entity_decode_if_needed($CP->background_menu);
				$background_color = String::html_entity_decode_if_needed($CP->background_color);

				if (strlen($background_color) > 1 || strlen($background_menu) > 1) {
					$tpl->assign('bg_colors', array('body' => $background_color,
							'menu' => $background_menu
							));
				}
			}
		}
		$output .= $tpl->fetch();
		return $output;
	}
}

if (!function_exists('get_menu')) {
	/**
	 * get_admin_menu()
	 *
	 * @return
	 */

	function get_menu()
	{
		if (empty($GLOBALS['main_menu_items'])) {
			$GLOBALS['main_menu_items']['home'] = array($GLOBALS['wwwroot'] . '/' => $GLOBALS['STR_HOME']);
			$GLOBALS['main_menu_items']['catalog'] = array(get_product_category_url() => $GLOBALS['STR_CATALOGUE']);
			$GLOBALS['main_menu_items']['news'] = array(get_product_category_url() . 'nouveautes.php' => $GLOBALS['STR_NOUVEAUTES']);
			$GLOBALS['main_menu_items']['promotions'] = array(get_product_category_url() . 'promotions.php' => $GLOBALS['STR_DO_NOT_MISS']);
			$GLOBALS['main_menu_items']['content'] = array(get_content_category_url() => $GLOBALS["STR_INFORMATIONS"]);
			if (is_annonce_module_active()) {
				$GLOBALS['main_menu_items']['annonces'] = array($GLOBALS['wwwroot'] . '/modules/annonces/' => $GLOBALS['STR_MODULE_ANNONCES_ADS']);
				if (est_identifie()) {
					$GLOBALS['menu_items']['annonces'][$GLOBALS['wwwroot'] . '/modules/annonces/'] = $GLOBALS['STR_MODULE_ANNONCES_LIST_ANNONCES'];
					$GLOBALS['menu_items']['annonces'][$GLOBALS['wwwroot'] . '/modules/annonces/creation_annonce.php'] = $GLOBALS['STR_MODULE_ANNONCES_AD_CREATE'];
				}
				// $GLOBALS['main_menu_items']['annonces_verified'] = array(get_verified_url(false) => $GLOBALS['STR_MODULE_ANNONCES_BECOME_VERIFIED']);
			}
			if (is_vitrine_module_active()) {
				if (is_module_url_rewriting_active()) {
					$GLOBALS['main_menu_items']['vitrine'] = array(get_list_showcase_url(false, false) => $GLOBALS['STR_MODULE_ANNONCES_SHOP']);
				} else {
					$GLOBALS['main_menu_items']['vitrine'] = array($GLOBALS['wwwroot'] . '/modules/vitrine/' => $GLOBALS['STR_MODULE_ANNONCES_SHOP']);
				}
			}
			if (is_module_gift_checks_active()) {
				$GLOBALS['main_menu_items']['check'] = array($GLOBALS['wwwroot'] . '/modules/gift_check/cheques.php' => $GLOBALS['STR_CHEQUE_CADEAU']);
			}
			if (est_identifie()) {
				$GLOBALS['main_menu_items']['account'] = array(get_account_url(false, false) => $GLOBALS['STR_COMPTE']);
				$GLOBALS['menu_items']['account'][$GLOBALS['wwwroot'] . '/achat/historique_commandes.php'] = $GLOBALS['STR_ORDER_HISTORY'];
				$GLOBALS['menu_items']['account'][$GLOBALS['wwwroot'] . '/utilisateurs/change_mot_passe.php'] = $GLOBALS['STR_CHANGE_PASSWORD'];
				if (is_cart_preservation_module_active()) {
					$GLOBALS['menu_items']['account'][$GLOBALS['wwwroot'] . '/modules/cart_preservation/cart_preservation.php'] = $GLOBALS['STR_CART_PRESERVATION_TITLE'];
				}
				$GLOBALS['menu_items']['account'][$GLOBALS['wwwroot'] . '/utilisateurs/change_params.php'] = $GLOBALS['STR_CHANGE_PARAMS'];
				$GLOBALS['menu_items']['account'][$GLOBALS['wwwroot'] . '/sortie.php'] = $GLOBALS['STR_LOGOUT'];
			} else {
				$GLOBALS['main_menu_items']['account'] = array($GLOBALS['wwwroot'] . '/membre.php' => $GLOBALS['STR_COMPTE']);
			}
			$GLOBALS['main_menu_items']['contact'] = array(get_contact_url(false, false) => $GLOBALS['STR_CONTACT']);
			if (a_priv('admin*', true)) {
				$GLOBALS['main_menu_items']['admin'] = array($GLOBALS['administrer_url'] . '/index.php' => $GLOBALS['STR_ADMIN']);
			}
			$GLOBALS['menu_items']['promotions'][get_product_category_url() . '/promotions.php'] = $GLOBALS['STR_PROMOTIONS'];
			if (is_flash_sell_module_active() && is_flash_active_on_site()) {
				$GLOBALS['menu_items']['promotions'][$GLOBALS['wwwroot'] . '/modules/flash/flash.php'] = $GLOBALS['STR_FLASH'];
			}
		}
		if(!empty($GLOBALS['site_parameters']['main_menu_items_if_available']) && is_array($GLOBALS['site_parameters']['main_menu_items_if_available'])) {
			$temp = array();
			foreach($GLOBALS['site_parameters']['main_menu_items_if_available'] as $this_value) {
				if(isset($GLOBALS['main_menu_items'][$this_value])) {
					$temp[$this_value] = $GLOBALS['main_menu_items'][$this_value];
				}
			}
			$GLOBALS['main_menu_items'] = $temp;
		}
		// Préparation du contenu du menu catalogue produits
		if(!empty($GLOBALS['site_parameters']['product_categories_depth_in_menu'])) {
			if (!empty($_GET['catid'])) {
				$highlighted_item = intval($_GET['catid']);
			} else {
				$highlighted_item = 0;
			}
			$sql = 'SELECT c.id, c.parent_id, c.nom_' . $_SESSION['session_langue'] . ' as nom
				FROM peel_categories c
				WHERE c.etat="1" AND nom_' . $_SESSION['session_langue'] . '!=""
				ORDER BY c.position ASC, nom ASC';
			$qid = query($sql);
			while ($result = fetch_assoc($qid)) {
				$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
				$item_name_array[$result['id']] = $result['nom'];
			}
			$submenu_global['catalog'] = '';
			if (!empty($all_parents_with_ordered_direct_sons_array)) {
				$submenu_global['catalog'] .= '<ul class="sousMenu">'.get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $highlighted_item, 'categories', 'left', vn($GLOBALS['site_parameters']['product_categories_depth_in_menu'])).'</ul>';
			}
		}
		// Préparation du contenu du menu contenu rédactionnel
		if(!empty($GLOBALS['site_parameters']['content_categories_depth_in_menu'])) {
			if (!empty($_GET['rubid'])) {
				$highlighted_item = intval($_GET['rubid']);
			} else {
				$highlighted_item = 0;
			}
			unset($all_parents_with_ordered_direct_sons_array);
			$sql = 'SELECT r.id, r.parent_id, r.nom_' . $_SESSION['session_langue'] . ' as nom
				FROM peel_rubriques r
				WHERE r.etat = "1" AND r.technical_code NOT IN ("other", "iphone_content") AND r.position>=0
				ORDER BY r.position ASC, nom ASC';
			$qid = query($sql);
			while ($result = fetch_assoc($qid)) {
				$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
				$item_name_array[$result['id']] = $result['nom'];
			}
			$submenu_global['content'] = '';
			if (!empty($all_parents_with_ordered_direct_sons_array)) {
				$submenu_global['content'] .= '<ul class="sousMenu">'.get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $highlighted_item, 'rubriques', 'left', $GLOBALS['site_parameters']['content_categories_depth_in_menu']).'</ul>';
			}
		}
		// Génération du menu
		$current_url = get_current_url(false);
		$current_url_full = get_current_url(true);
		$menu = array();
		foreach($GLOBALS['main_menu_items'] as $this_main_item => $this_main_array) {
			$current_menu = (!empty($GLOBALS['menu_items'][$this_main_item][$current_url_full]));
			$full_match = true;
			if ($current_menu === false && !empty($GLOBALS['menu_items'][$this_main_item])) {
				$current_menu = (!empty($GLOBALS['menu_items'][$this_main_item][$current_url]));
				$full_match = false;
			}
			foreach ($this_main_array as $this_main_url => $this_main_title) {
				$tmp_menu_item = array('name' => $this_main_item,
						'label' => $this_main_title,
						'href' => (!empty($this_main_url) && !is_numeric($this_main_url)) ? $this_main_url : false,
						'selected' => ($current_menu !== false || !empty($this_main_array[$current_url]) || !empty($this_main_array[$current_url_full])),
						'submenu_global' => vb($submenu_global[$this_main_item]),
						'submenu' => array()
					);
				if (!empty($GLOBALS['menu_items'][$this_main_item])) {
					foreach ($GLOBALS['menu_items'][$this_main_item] as $this_url => $this_title) {
						$tmp_menu_item['submenu'][] = array('label' => $this_title,
							'href' => (!empty($this_url) && !is_numeric($this_url)) ? $this_url : false,
							'selected' => (($current_url == $this_url && !$full_match) || $current_url_full == $this_url),
							);
					}
				}
				$menu[] = $tmp_menu_item;
			}
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('menu.tpl');
		$tpl->assign('menu', $menu);
		$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
		return $tpl->fetch();
	}
}

if (!function_exists('getFlagLinks')) {
	/**
	 * NO_TPL getFlagLinks function was rewriten as "affiche_flags" whitch uses template
	 * Renvoie un tableau de tous les drapeaux sous format HTML pour affichage sur une page avec des liens pour chaque langue
	 *
	 * @return
	 */
	function getFlagLinks()
	{
		$flags_links = array();
		if (count($GLOBALS['lang_codes']) > 1) {
			foreach($GLOBALS['lang_codes'] as $this_lang) {
				$this_url_lang = get_current_url_in_other_language($this_lang);
				$this_flag = '<img class="flag" src="' . ((String::strpos($GLOBALS['lang_flags'][$this_lang], '/') !== false)?$GLOBALS['lang_flags'][$this_lang]:$GLOBALS['wwwroot'] . '/lib/flag/' . $GLOBALS['lang_flags'][$this_lang]) . '" alt="' . $GLOBALS['lang_names'][$this_lang] . '" width="18" height="12" />';
				if ($_SESSION['session_langue'] != $this_lang) {
					$this_flag = '<a href="' . htmlspecialchars($this_url_lang) . '" title="' . $GLOBALS['lang_names'][$this_lang] . '">' . $this_flag . '</a>';
				}
				$flags_links[] = '<span lang="' . $this_lang . '" title="' . $this_lang . '">' . $this_flag . '</span>';
			}
		}
		return $flags_links;
	}
}

if (!function_exists('affiche_flags')) {
	/**
	 * affiche_flags()
	 *
	 * @param boolean $return_mode
	 * @param string $forced_destination_url
	 * @param boolean $display_names
	 * @uses $GLOBALS['tplEngine']
	 * @return flags view
	 */
	function affiche_flags($return_mode = false, $forced_destination_url = null, $display_names = false)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('flags.tpl');
		$data = array();
		if (count($GLOBALS['lang_codes']) > 1 || !empty($forced_destination_url)) {
			foreach ($GLOBALS['lang_codes'] as $this_lang) {
				if(!empty($forced_destination_url)){
					$url = $forced_destination_url . '?langue=' . $this_lang;
				} else {
					$url = get_current_url_in_other_language($this_lang);
				}
				$data[] = array('lang' => $this_lang,
					'lang_name' => $GLOBALS['lang_names'][$this_lang],
					'href' => $url,
					'src' => ((String::strpos($GLOBALS['lang_flags'][$this_lang], '/') !== false) ? $GLOBALS['lang_flags'][$this_lang] : $GLOBALS['wwwroot'] . '/lib/flag/' . $GLOBALS['lang_flags'][$this_lang]),
					'selected' => ($_SESSION['session_langue'] == $this_lang && empty($forced_destination_url)),
					'flag_css_class' => (($_SESSION['session_langue'] == $this_lang && empty($forced_destination_url)) ? "flag_selected":"flag_not_selected")
					);
			}
		}
		$tpl->assign('data', $data);
		$tpl->assign('display_names', $display_names);
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('get_formatted_longtext_with_title')) {
	/**
	 * get_formatted_longtext_with_title()
	 *
	 * @param string $longtext
	 * @param string $title
	 * @return
	 */
	function get_formatted_longtext_with_title($longtext, $title, $mode = 'general')
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('longtext_with_title.tpl');
		$tpl->assign('mode', $mode);
		$tpl->assign('longtext', $longtext);
		$tpl->assign('title', $title);
		return $tpl->fetch();
	}
}

if (!function_exists('output_light_html_page')) {
	/**
	 * Affiche une page simple
	 *
	 * @param string $body
	 * @param string $title
	 * @param string $additional_header
	 * @param string $convert_to_encoding
	 * @param string $full_head_section_text
	 * @param string $onload
	 * @return
	 */
	function output_light_html_page($body, $title = '', $additional_header = null, $convert_to_encoding = null, $full_head_section_text = null, $onload = null)
	{
		if (!empty($convert_to_encoding)) {
			$encoding = $convert_to_encoding;
		} else {
			$encoding = GENERAL_ENCODING;
		}
		header('Content-type: text/html; charset=' . $encoding);
		$tpl = $GLOBALS['tplEngine']->createTemplate('light_html_page.tpl');
		$tpl->assign('lang', $_SESSION['session_langue']);
		$tpl->assign('charset', $encoding);
		$tpl->assign('title', $title);
		$tpl->assign('onload', $onload);
		$tpl->assign('additional_header', $additional_header);
		$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
		$tpl->assign('body', $body);
		$tpl->assign('full_head_section_text', $full_head_section_text);
		if (empty($full_head_section_text)) {
			if(!empty($GLOBALS['css_files'])) {
				$tpl->assign('css_files', array_unique($GLOBALS['css_files']));
			}
			if(!empty($GLOBALS['js_files'])) {
				// L'ordre des fichiers js doit être respecté ensuite dans le template
				$tpl->assign('js_files', array_unique($GLOBALS['js_files']));
			}
		}
		$output = $tpl->fetch();
		if (!empty($convert_to_encoding)) {
			echo String::convert_encoding($output, $convert_to_encoding);
		} else {
			echo $output;
		}
	}
}

if (!function_exists('print_alpha')) {
	/**
	 * Affiche la liste des catégories par ordre alphabétique
	 *
	 * @return
	 */
	function print_alpha()
	{
		$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
		$map = array();
		foreach ($alpha as $value) {
			$tmp = array('value' => $value, 'items' => array());
			$sqlCat = "SELECT c.id, c.nom_" . $_SESSION['session_langue'] . ", c.alpha_" . $_SESSION['session_langue'] . ", c.image_" . $_SESSION['session_langue'] . "
				FROM peel_categories c
				WHERE c.etat = '1' AND c.alpha_" . $_SESSION['session_langue'] . "= '" . nohtml_real_escape_string($value) . "'";
			$resCat = query($sqlCat);
			while ($cat = fetch_assoc($resCat)) {
				$sqlCount = "SELECT COUNT(*) AS this_count
					FROM peel_produits_categories pc
					INNER JOIN peel_produits p ON p.id = pc.produit_id
					WHERE pc.categorie_id='" . intval($cat['id']) . "'";
				$resCount = query($sqlCount);
				if ($Count = fetch_assoc($resCount)) {
					$urlcat = get_product_category_url($cat['id'], $cat['nom_' . $_SESSION['session_langue']]);
					$tmp['items'][] = array('href' => $urlcat,
						'name' => $cat['nom_' . $_SESSION['session_langue']],
						'count' => $Count['this_count']
						);
				}
			}
			$map[] = $tmp;
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('alpha.tpl');
		$tpl->assign('title', $GLOBALS['STR_SITEMAP']);
		$tpl->assign('map', $map);
		echo $tpl->fetch();
	}
}

if (!function_exists('print_delete_installation_folder')) {
	/**
	 * Affiche la liste des catégories par ordre alphabétique
	 *
	 * @return
	 */
	function print_delete_installation_folder()
	{
		// Tout ce qui est ci-dessous ne peut pas aller chercher d'informations en base de données car logiciel non installé
		$title = $GLOBALS['STR_INSTALLATION'];
		// Gestion de l'affichage des drapeaux lors de l'installation
		@include($GLOBALS['dirroot'] . '/lib/lang/database_langues_'.$_SESSION['session_langue'].'.php');
		foreach(array('fr', 'en', 'es', 'de', 'it', 'nl') as $this_lang) {
			$GLOBALS['lang_flags'][$this_lang] = $GLOBALS['wwwroot'] . '/images/'.$this_lang.'.png';
		}
		if(!empty($peel_langues['nom'])) {
			$GLOBALS['lang_names'] = $peel_langues['nom'];
		}
		if (!is_writable($GLOBALS['dirroot'] . "/lib/templateEngines/smarty/compile")) {
			$body = sprintf($GLOBALS['STR_ADMIN_INSTALL_DIRECTORY_NOK'], "/lib/templateEngines/smarty/compile");
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('delete_installation_folder.tpl');
			$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
			$tpl->assign('installation_links', affiche_flags(true, $GLOBALS['wwwroot'] . '/installation/index.php', true));
			$tpl->assign('STR_INSTALLATION_PROCEDURE', $GLOBALS['STR_INSTALLATION_PROCEDURE']);
			$tpl->assign('STR_INSTALLATION_DELETE_EXPLAIN', $GLOBALS['STR_INSTALLATION_DELETE_EXPLAIN']);
			$tpl->assign('STR_INSTALLATION_DELETE_EXPLAIN_ALTERNATIVE', $GLOBALS['STR_INSTALLATION_DELETE_EXPLAIN_ALTERNATIVE']);
			$tpl->assign('STR_INSTALLATION_DELETED_LINK', $GLOBALS['STR_INSTALLATION_DELETED_LINK']);
			$tpl->assign('PEEL_VERSION', PEEL_VERSION);
			$body = $tpl->fetch();
		}
		$additional_header = '
		<style>
			h1 { font-family: Arial, sans-serif; font-size: 24px; font-weight: bold; color: #337733; }
			h2 { font-family: Arial, sans-serif; font-size: 14px; font-weight: bold; color: #CC0000; }
			p { font-family: Arial, sans-serif; font-size: 12px; font-weight: bold; color: #000000; }
			.launch_installation, .center { text-align:center; }
			.launch_installation { margin: 30px; min-width: 770px; }
			.flag_not_selected { width: 167px; height:167px; }
			.full_flag { display: inline-block; font-family: Arial, sans-serif; font-size: 18px; font-weight: bold; color: #000000; padding:15px; }
			.full_flag a { text-decoration: none; color: #000000 !important;}
			.full_flag a:hover { text-decoration: underline;}
			.footer { background-color: #DDDDDD; padding:20px; }
		</style>
';
		output_light_html_page($body, $title, $additional_header);
	}
}

if (!function_exists('print_access_plan')) {
	/**
	 * NO_TPL print_access_plan is not a view formatting function
	 * print_access_plan()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function print_access_plan($id)
	{
		$output = '';
		$sql = "SELECT map_tag AS map_tag, text_" . $_SESSION['session_langue'] . " AS texte
			FROM peel_access_map
			WHERE id='" . intval($id) . "'";
		$res = query($sql);
		$access_plan_infos = fetch_assoc($res);
		if (!empty($access_plan_infos['texte'])) {
			// Comme le tag a probablement été copié collé dans la source de l'éditeur, les & ne sont probablement pas sous la forme &amp;
			// On décode et on réencode donc les &
			$tag = String::htmlentities(String::html_entity_decode($access_plan_infos['map_tag']), ENT_COMPAT, GENERAL_ENCODING, false, true);
			$longtext = String::nl2br_if_needed(String::html_entity_decode_if_needed($access_plan_infos['texte']));
		} else {
			$tag = '';
			$longtext = $GLOBALS['STR_EMPTY_TEXT_ACCESS_PLAN'];
		}
		$title = $GLOBALS['STR_ACCESS_PLAN'];
		$output .= get_formatted_longtext_with_title($longtext, $title, 'access_plan') . $tag;

		return $output;
	}
}

if (!function_exists('get_modules_paiement_secu')) {
	/**
	 * get_modules_paiement_secu()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function get_modules_paiement_secu()
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules_paiement_secu.tpl');
		$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
		$tpl->assign('name', $GLOBALS['STR_PAIEMENT_SECURISE']);
		return $tpl->fetch();
	}
}

if (!function_exists('get_contact_sideblock')) {
	/**
	 * get_contact_sideblock()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function get_contact_sideblock($return_mode = true)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('contact_sideblock.tpl');
		$tpl->assign('lang', $_SESSION['session_langue']);
		if (is_module_url_rewriting_active()) {
			$tpl->assign('href', get_contact_url(false, false));
		} else {
			$tpl->assign('href', $GLOBALS['wwwroot'] . '/utilisateurs/contact.php');
		}
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('extra_catalogue_condition')) {
	/**
	 * NO_TPL extra_catalogue_condition function is not a view formatting function
	 */
	function extra_catalogue_condition()
	{
		return true;
	}
}

if (!function_exists('get_banner_help')) {
	/**
	 * NO_TPL get_banner_help function is not a view formatting function
	 */
	function get_banner_help()
	{
		return '';
	}
}

if (!function_exists('is_flash_active_on_site')) {
	/**
	 * NO_TPL is_flash_active_on_site function is not a view formatting function
	 */
	function is_flash_active_on_site()
	{
		return !empty($GLOBALS['site_parameters']['module_flash']);
	}
}

if (!function_exists('get_newsletter_form')) {
	/**
	 * get_newsletter_form()
	 *
	 * @return
	 */
	function get_newsletter_form()
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('newsletter_form.tpl');
		$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
		$tpl->assign('label', $GLOBALS['STR_NEWSLETTER'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('default', $GLOBALS['STR_WRITE_EMAIL_HERE']);
		return $tpl->fetch();
	}
}

if (!function_exists('newsletter_desinscription_form')) {
	/**
	 * newsletter_desinscription_form()
	 *
	 * @param mixed $frm
	 * @param mixed $form_error_object
	 * @return
	 */
	function newsletter_desinscription_form(&$frm, $form_error_object)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('newsletter_desinscription_form.tpl');
		$tpl->assign('header', $GLOBALS['STR_DESINSCRIPTION_NEWSLETTER']);
		$tpl->assign('action', get_current_url());
		$tpl->assign('label', $GLOBALS['STR_EMAIL'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('error', $form_error_object->text('email'));
		$tpl->assign('email', String::str_form_value(vb($frm['email'])));
		$tpl->assign('submit', $GLOBALS['STR_DESINSCRIPTION']);
		return $tpl->fetch();
	}
}

if (!function_exists('newsletter_validation')) {
	/**
	 * newsletter_validation()
	 *
	 * @return
	 */
	function newsletter_validation($frm)
	{
		if (empty($frm)) {
			return false;
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('newsletter_validation.tpl');
		$tpl->assign('header', $GLOBALS['STR_NEWSLETTER_TITLE']);
		if (!empty($frm['email']) && EmailOK($frm['email'])) {
			// MAJ du compte client s'il existe
			$q_count_users = query("SELECT COUNT(id_utilisateur) AS nb_users
				FROM peel_utilisateurs
				WHERE email = '" . word_real_escape_string($frm['email']) . "'");
			$r_count_users = fetch_assoc($q_count_users);
			if ($r_count_users['nb_users'] > 0) {
				query("UPDATE peel_utilisateurs
					SET newsletter = '1'
					WHERE email='" . nohtml_real_escape_string($frm['email']) . "'");
			} else {
				$frm['newsletter'] = 1;
				$frm['priv'] = 'newsletter';
				insere_utilisateur($frm);
				// Envoi d'un email confirmant l'inscription à la newsletter
			}
			$custom_template_tags['EMAIL'] = $frm['email'];
			send_email($frm['email'], '', '', 'inscription_newsletter', $custom_template_tags, 'html', $GLOBALS['support']);
			$tpl->assign('is_error', false);
			$tpl->assign('message', $GLOBALS['STR_REQUEST_OK'] . ' ' . $GLOBALS['STR_SEE_YOU_SOON'] . ' ' . $GLOBALS['wwwroot']);
		} else {
			$tpl->assign('is_error', true);
			$tpl->assign('message', $GLOBALS['STR_ERR_EMAIL_BAD']);
		}
		return $tpl->fetch();
	}
}


if (!function_exists('affiche_contenu_html')) {
	/**
	 *
	 * @param mixed $place
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_contenu_html($place, $return_mode = false)
	{
		$output = '';
		$sql = 'SELECT *
			FROM peel_html
			WHERE emplacement="' . nohtml_real_escape_string($place) . '" AND etat="1" AND (lang="' . $_SESSION['session_langue'] . '" OR lang="")
			ORDER BY a_timestamp DESC';
		$query = query($sql);
		while ($obj = fetch_object($query)) {
			// On préserve le HTML mais on corrige les & isolés
			$output .= template_tags_replace(String::htmlentities(String::html_entity_decode_if_needed($obj->contenu_html), ENT_COMPAT, GENERAL_ENCODING, false, true), array(), false, 'html');
		}
		correct_output($output, false, 'html');
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('addthis_buttons')) {
	/**
	 *
	 * @param array $share_item_array : tableau de service code addthis : http://www.addthis.com/services/list
	 * @param mixed $text
	 * @return
	 */
	function addthis_buttons($share_item_array = null, $text = null)
	{
		if (empty($share_item_array)) {
			// Configuration par défaut
			$share_item_array = array('twitter', 'google_plusone_share', 'facebook', 'pinterest_share');
		}
		$output = '
	<table class="addthis_16x16_style product_link_to_modules">
		<tr>';
		if (!empty($text)) {
			$output .= '
				<td>
					<span class="text_product" style="display:block;height:100%;float:left;vertical-align:middle;">' . $text . '</span>
				</td>';
		}
		foreach($share_item_array as $this_item) {
			$output .= '
				<td>
					<a class="addthis_button_' . $this_item . '"></a>
				</td>';
		}
		$output .= '
			</tr>
		</table>
	<script src="http://s7.addthis.com/js/300/addthis_widget.js"></script>';
		return $output;
	}
}

if (!function_exists('get_user_picture')) {
	/**
	 *
	 * @param array $priv 	privilège des utilisateurs à afficher.
	 * @param array $nb		nombre d'utilisateurs à afficher.
	 * @param mixed $rand	utilisateur tiré aléatoirement
	 * @return
	 */
	function get_user_picture($priv, $nb = 4, $rand = true) {
		$output_array = array();
		$sql_condition = '';
		if (empty($priv) || intval($nb) == 0) {
			// Erreur de paramétrage
			return false;
		}
		if ($priv != '*') {
			// * pour tous les utilisateurs 
			$sql_condition .= ' AND priv="'.nohtml_real_escape_string($priv).'"';
		}
		if (!empty($rand)) {
			$sql_condition .= ' ORDER BY RAND() LIMIT 0,' . $nb;
		}
		$sql = 'SELECT logo
			FROM peel_utilisateurs
			WHERE logo !="" ' . $sql_condition;
		$q = query($sql);
		while($result = fetch_assoc($q)) {
			$output_array[] = $result['logo'];
		}
		return $output_array;
	}
}
?>