<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: display.php 44077 2015-02-17 10:20:38Z sdelaporte $
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
		static $tpl;
		if(empty($tpl)) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('tr_rollover.tpl');
		}
		$tpl->assign('onclick', $onclick);
		$tpl->assign('style', $style);
		$tpl->assign('line_number', $line_number);
		$tpl->assign('id', $id);
		
		$output = $tpl->fetch();
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
	 * - En quatrième priorité, on prendra les métas dans peel_meta par URL ou par $page_name
	 * - En cinquième priorité, on prend les métas génériques du site dans peel_meta
	 *
	 * @param string $page_name
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_meta($page_name, $return_mode = false)
	{
		$output = '';
		// PRIORITE 1 : $GLOBALS['meta_title']
		// PRIORITE 2 : Récupération des métas définis en BDD pour des éléments précis
		if (!empty($_GET['id']) && defined('IN_LEXIQUE')) {
			$sql_Meta = 'SELECT word_' . $_SESSION['session_langue'] . ' AS nom, meta_title_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_definition_' . $_SESSION['session_langue'] . ' AS meta_desc 
				FROM peel_lexique 
				WHERE id = "' . intval($_GET['id']) . '"  AND '. get_filter_site_cond('lexique');
		} elseif (!empty($_GET['catid']) && (defined('IN_CATALOGUE_ANNONCE') || defined('IN_CATALOGUE_ANNONCE_DETAILS'))) {
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, meta_desc_' . vb($_SESSION['session_langue']) . ' AS meta_desc 
				FROM peel_categories_annonces 
				WHERE id = "' . intval($_GET['catid']) . '"';
		} elseif (!empty($_GET['id']) && defined('IN_SEARCH_BRAND')) { 
			// Si on est dans une marque
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key 
				FROM peel_marques 
				WHERE id = "' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('marques');
		} elseif (!empty($_GET['catid']) && empty($_GET['id'])) { 
			// Si on est dans une catégorie
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, image_' . $_SESSION['session_langue'] . ' as image 
				FROM peel_categories 
				WHERE id = "' . intval($_GET['catid']) . '" AND ' . get_filter_site_cond('categories') . '';
		} elseif (!empty($_GET['rubid']) && empty($_GET['id'])) { 
			// Si on est dans une rubrique
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, image 
				FROM peel_rubriques 
				WHERE id = "' . intval($_GET['rubid']) . '" AND ' . get_filter_site_cond('rubriques') . '';
		} elseif (!empty($_GET['id']) && defined('IN_CATALOGUE_PRODUIT')) {
			// Si on est dans une fiche produit
			$display_facebook_tag = true;
			$sql_Meta = "SELECT nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue'])." AS nom, meta_titre_" . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, image1 AS image 
				FROM peel_produits 
				WHERE id = "' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('produits') . '';
		} elseif (!empty($_GET['id']) && defined('IN_RUBRIQUE_ARTICLE')) {
			// Si on est dans un article de contenu
			$display_facebook_tag = true;
			$sql_Meta = 'SELECT titre_' . $_SESSION['session_langue'] . ' AS nom, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, image1 AS image 
				FROM peel_articles 
				WHERE id = "' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('articles') . '';
		}
		if (!empty($sql_Meta)) {
			$query_Meta = query($sql_Meta);
			$m = fetch_assoc($query_Meta);
		} else {
			$no_meta_by_page_content = true;
		}
		// PRIORITE 3 : Récupération des metas par URL ou code technique
		$sql_Meta = 'SELECT *
			FROM peel_meta
			WHERE technical_code="'.real_escape_string(get_current_url(false)).'" OR technical_code="'.real_escape_string(get_current_url(false, true)).'" AND ' . get_filter_site_cond('meta');
		$query_Meta = query($sql_Meta);
		$m_peel_meta = fetch_assoc($query_Meta);
		if(!empty($m_peel_meta)) {
			$meta_by_exact_url = true;
		} elseif(!empty($page_name)) {
			$sql_Meta = 'SELECT *
				FROM peel_meta
				WHERE technical_code="'.real_escape_string($page_name).'" AND ' . get_filter_site_cond('meta');
			$query_Meta = query($sql_Meta);
			$m_peel_meta = fetch_assoc($query_Meta);
		}
		// PRIORITE 4 : Définition de certains métas par défaut, en complément de ce qui est présent dans les fichiers de meta par langue
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
		} elseif (defined('IN_DOWNLOAD_PEEL') && !empty($GLOBALS['STR_MODULE_PEEL_DOWNLOAD_PEEL'])) {
			$GLOBALS['strSpecificMeta']['Title'][$page_name] = $GLOBALS['STR_MODULE_PEEL_DOWNLOAD_PEEL'];
		}
		// PRIORITE 5 : Récupération des metas par défaut
		$sql_Meta = 'SELECT *
			FROM peel_meta
			WHERE "'.real_escape_string(get_current_url(true)).'" LIKE CONCAT(technical_code, "%") OR "'.real_escape_string(get_current_url(true, true)).'" LIKE CONCAT(technical_code, "%") AND ' . get_filter_site_cond('meta') . '
			ORDER BY LENGTH(technical_code) DESC
			LIMIT 1';
		$query_Meta = query($sql_Meta);
		$m_default = fetch_assoc($query_Meta);
		// Application des priorités
		if (!empty($meta_by_exact_url) && !empty($m_peel_meta) && !empty($m_peel_meta['meta_titre_' . $_SESSION['session_langue']])) {
			// Une méta définie pour une URL bien précise a priorité sur tout
			$this_title = $m_peel_meta['meta_titre_' . $_SESSION['session_langue']];
		} elseif (!empty($GLOBALS['meta_title'])) {
			$this_title = $GLOBALS['meta_title'];
		} elseif (!empty($m) && !empty($m['meta_titre'])) {
			$this_title = $m['meta_titre'];
		} elseif (!empty($m) && !empty($m['nom'])) {
			$this_title = $m['nom'];
		} elseif (!empty($m_peel_meta) && !empty($m_peel_meta['meta_titre_' . $_SESSION['session_langue']])) {
			$this_title = $m_peel_meta['meta_titre_' . $_SESSION['session_langue']];
		} elseif (!empty($GLOBALS['DOC_TITLE'])) {
			// DOC_TITLE est un titre par défaut d'une page : il est moins prioritaire notamment que les informations rentrées dans les metas en base de données
			$this_title = $GLOBALS['DOC_TITLE'];
		} elseif (!empty($GLOBALS['strSpecificMeta']['Title'][$page_name])) {
			$this_title = $GLOBALS['strSpecificMeta']['Title'][$page_name];
		} else {
			$this_title = $m_default['meta_titre_' . $_SESSION['session_langue']];
		}
		$this_title = String::html_entity_decode($this_title);
		if (!empty($GLOBALS['meta_description'])) {
			$this_description = str_replace(array('    ', '   ', '  ', "\t"), ' ', trim(String::strip_tags($GLOBALS['meta_description']))) . ' ';
		} else {
			$this_description = '';
		}
		if (String::strlen($this_description) < 100) {
			if (!empty($m['meta_desc'])) {
				$this_description .= $m['meta_desc'];
			} elseif (!empty($m_peel_meta['meta_desc_' . $_SESSION['session_langue']])) {
				$this_description .= $m_peel_meta['meta_desc_' . $_SESSION['session_langue']];
			} elseif (!empty($GLOBALS['strSpecificMeta']['Description'][$page_name])) {
				$this_description .= $GLOBALS['strSpecificMeta']['Description'][$page_name];
			} else {
				$this_description .= $m_default['meta_desc_' . $_SESSION['session_langue']];
				if (!empty($m['nom'])) {
					$this_description = $m['nom'] . '. ' . $this_description;
				} elseif(!empty($GLOBALS['meta_title'])) {
					$this_description = $GLOBALS['meta_title'] . '. ' . $this_description;
				} elseif(!empty($GLOBALS['DOC_TITLE'])) {
					$this_description = $GLOBALS['DOC_TITLE'] . '. ' . $this_description;
				}
			}
		}
		if (!empty($this_title) && $this_title == String::strtoupper($this_title) && String::strlen($this_title) > 25) {
			// Titre tout en majuscule et pas juste un ou deux mots => on passe en minuscule car sinon mauvais pour moteurs de recherche
			$this_title = String::strtolower($this_title);
		}
		if (!empty($GLOBALS['STR_TITLE_SUFFIX'])) {
			foreach(explode(' ', $GLOBALS['STR_TITLE_SUFFIX']) as $this_word) {
				if ((String::strlen($this_word)<=2 || String::strpos(String::strtolower($this_title), String::strtolower($this_word)) === false) && String::strlen($this_title . ' ' . $this_word) < 80) {
					$this_title .= ' ' .$this_word;
				}
			}
		}
		if(!empty($m) && !empty($m['meta_key']) && String::strlen($m['meta_key']) > 40 &&  String::strlen($m['meta_key']) < 200) {
			// On respecte la balise méta keywords définie dans le contenu de la page en base de données, car elle parait de taille cohérente
			$this_keywords = $m['meta_key'];
		} elseif((!empty($meta_by_exact_url) || !empty($no_meta_by_page_content)) && !empty($m_peel_meta) && !empty($m_peel_meta['meta_key_' . $_SESSION['session_langue']]) && String::strlen($m_peel_meta['meta_key_' . $_SESSION['session_langue']]) > 40 &&  String::strlen($m_peel_meta['meta_key_' . $_SESSION['session_langue']]) < 150) {
			// On respecte la balise méta keywords définie par URL exacte en base de données, ou par technical code si on est dans une page sans définition possible directe des métas
			// car elle parait de taille cohérente
			$this_keywords = $m_peel_meta['meta_key_' . $_SESSION['session_langue']];
		} else {
			$this_keywords = $this_title . ' ' . vb($GLOBALS['meta_keywords']) . ' ' . vb($m['nom']) . ' ' . vb($m['meta_key']) . ' '. vb($GLOBALS['strSpecificMeta']['Keywords'][$page_name]) . ' ' . vb($m_peel_meta['meta_key_' . $_SESSION['session_langue']]);
			if (String::strlen($this_keywords) < 70) {
				$this_keywords .= ' ' . $this_description;
			}
			if (String::strlen($this_keywords) < 100) {
				$this_keywords .= ' ' . $m_default['meta_key_' . $_SESSION['session_langue']];
			}
			if (!empty($this_keywords)) {
				// Nettoyage des mots clés - on n'en garde que 12 maximum (conseillé : max 8)
				$temp_array = array_unique(explode(',', trim(String::strip_tags(str_replace(array("\r", "\n", "\t", '!', '?', '(', ')', '.', '#', ':', ';', '&nbsp;', '+', '-', " ", ".", '"', "'"), ',', String::html_entity_decode(str_replace(array('&nbsp;'), ',', String::strtolower($this_keywords))))))));
				foreach($temp_array as $this_key => $this_value) {
					if (String::strlen($this_value) < 4 || (String::strlen($this_value) < 5 && $this_key > 6) ) {
						unset($temp_array[$this_key]);
					}
				}
				$this_keywords = implode(', ', array_slice($temp_array, 0, 12));
			}
		}
		$GLOBALS['meta_description_html_uncut'] = $this_description;
		if (!empty($this_description)) {
			$this_description = String::str_shorten(str_replace(array('    ', '   ', '  ', ' .', '....'), array(' ', ' ', ' ', '.', '.'), trim(String::strip_tags(String::html_entity_decode_if_needed(str_replace(array("\r", "\n", "<br>", "<br />", "</p>"), ' ', $this_description))))), 190, '', '...', 170);
			if ($this_description == String::strtoupper($this_description)) {
				$this_description = String::strtolower($this_description);
			}
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('meta.tpl');
		$tpl->assign('charset', GENERAL_ENCODING);
		$tpl->assign('title', String::ucfirst(String::str_shorten(trim(String::strip_tags(String::html_entity_decode_if_needed(str_replace(array("\r", "\n"), '', $this_title)))), 80, '', '', 65)));
		$tpl->assign('keywords', $this_keywords);
		$tpl->assign('site', $GLOBALS['site']);
		if($_SESSION['session_langue'] == 'fr') {
			$tpl->assign('generator', 'https://www.peel.fr/');
		} else {
			$tpl->assign('generator', 'http://www.peel-shopping.com/');
		}
		$tpl->assign('description', String::ucfirst($this_description));
		$tpl->assign('content_language', $_SESSION['session_langue']);
		if (check_if_module_active('facebook') && !empty($display_facebook_tag)) {
			$display_facebook_tag_array = array('meta_titre'=>$this_title, 'meta_desc'=>$this_description, 'image'=>$m['image']);
			$tpl->assign('facebook_tag', display_facebook_tag($display_facebook_tag_array));
		}
		if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
			$tpl->assign('specific_meta', '<meta name="viewport" content="width=device-width, initial-scale=1.0" />');
		}
		if(!empty($_GET['update']) && $_GET['update'] == 1) {
			$robots = 'noindex, nofollow';
		} else {
			$robots = 'all';
		}
		$tpl->assign('robots', $robots);
		
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
	 * @param boolean $show_home
	 * @param string $page_name
	 * @param string $buttons
	 * @return
	 */
	function affiche_ariane($show_home = true, $page_name = null, $buttons = null)
	{
		$ariane = array();
		$tpl = $GLOBALS['tplEngine']->createTemplate('ariane.tpl');
		$hidden = !empty($GLOBALS['site_parameters']['module_ariane_hidden']);
		$tpl->assign('hidden', $hidden);

		if($show_home) {
			$ariane = array('href' => false,
				'txt' => $GLOBALS['site']
				);
		}
		$other = array('href' => false,
			'txt' => false
			);

		if (!defined('IN_HOME')) {
			if($show_home) {
				$ariane['href'] = $GLOBALS['wwwroot'] . '/';
			}
			if (defined('IN_CATALOGUE')) {
				$other['txt'] = affiche_arbre_categorie(vn($_GET['catid']), null, null, array());
				if($hidden) {
					$other['hidden'] = affiche_arbre_categorie(vn($_GET['catid']), null, null, array(), true);
				}
			} elseif (defined('IN_CATALOGUE_PRODUIT')) {
				$other['txt'] = affiche_arbre_categorie(vn($_GET['catid']), null, null, array());
				if($hidden) {
					$other['hidden'] = affiche_arbre_categorie(vn($_GET['catid']), null, null, array(), true);
				}
			} elseif (defined('IN_RUBRIQUE')) {
				$other['txt'] = affiche_arbre_rubrique(vn($_GET['rubid']), null);
				if($hidden) {
					$other['hidden'] = affiche_arbre_rubrique(vn($_GET['rubid']), null, true);
				}
			} elseif (defined('IN_RUBRIQUE_ARTICLE')) {
				$other['txt'] = affiche_arbre_rubrique(vn($_GET['rubid']), null);
				if($hidden) {
					$other['hidden'] = affiche_arbre_rubrique(vn($_GET['rubid']), null, true);
				}
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
				$other['href'] = get_search_url();
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
			if(!empty($page_name)) {
				$other['txt'] .= ' &gt; ' . $page_name;
			}
			$tpl->assign('ariane', $ariane);
			$tpl->assign('other', $other);
			$tpl->assign('buttons', $buttons);
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
					WHERE c.nom_' . $_SESSION['session_langue'] . '!="" AND c.etat="1" AND id="' . intval($id) . '" AND ' . get_filter_site_cond('categories', 'c') . '
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
	 * @param string $location
	 * @return
	 */
	function get_brand_link_html($id_marque = null, $return_mode = false, $show_all_brands_link = false, $location = null)
	{
		$output = '';
		$sql = '
			SELECT m.id, m.nom_' . $_SESSION['session_langue'] . ' AS marque, m.image
			FROM peel_marques m';
		if (empty($id_marque)) {
			$sql .= '
			LEFT JOIN peel_produits p ON p.id_marque=m.id AND p.etat=1 AND ' . get_filter_site_cond('produits', 'p') . '';
		}
		$sql .= '
			WHERE m.etat=1 AND ' . get_filter_site_cond('marques', 'm');
		if (!empty($id_marque)) {
			$sql .= ' AND m.id="' . intval($id_marque) . '"
			LIMIT 1';
		} else {
			$sql .= '
			GROUP BY m.id
			ORDER BY count(m.id) DESC
			LIMIT 5';
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
		$tpl->assign('location', $location);
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
			WHERE etat=1" . (!empty($id_marque)?" AND id = '" . intval($id_marque) . "'":"") . " AND " . get_filter_site_cond('marques') ."
			ORDER BY position ASC, nom ASC";
		$query = query($sql);
		$tplData = array();
		while ($brand_object = fetch_object($query)) {
			$sql2 = 'SELECT COUNT(*) AS nb_produits
				FROM peel_produits
				WHERE id_marque=' . intval($brand_object->id) . ' AND etat=1 AND nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).' != ""' . " AND " . get_filter_site_cond('produits');
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
					$thumb_file = thumbs($brand_object->image, $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit');
					if(!empty($thumb_file)) {
						$tmpData['image'] = array('href' => ($show_links_to_details ? $GLOBALS['wwwroot'] . '/achat/marque.php?id=' . $brand_object->id : ''),
								'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($brand_object->image, $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit')
							);
					}
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

if (!function_exists('get_categories_output')) {
	/**
	 * Parcourt récursivement l'arbre des catégories, et affiche les valeurs sous la forme souhaitée
	 *
	 * @param mixed $location indicates the position in the website : left or right
	 * @param string $mode
	 * @param integer $selected_item
	 * @param string $display_mode
	 * @param boolean $add_indent
	 * @param boolean $input_name
	 * @param string $technical_code
	 * @param boolean $use_admin_rights
	 * @return
	 */
	function get_categories_output($location = null, $mode = 'categories', $selected_item = null, $display_mode = 'option', $add_indent = '&nbsp;&nbsp;', $input_name = null, $technical_code = null, $use_admin_rights = false)
	{
		$output = '';
		if($mode == 'categories') {
			// Dans l'admin, il faut pouvoir voir les catégories qui n'ont pas de nom. Cela arrive lorque les information d'une catégorie ne sont pas renseigné pour toutes les langues,dans ce cas l'id entre crochet sera affiché en BO à la place du nom.
			$sql = 'SELECT c.id, c.parent_id, c.nom_' . $_SESSION['session_langue'] . ' AS nom
				FROM peel_categories c
				WHERE c.etat="1" '.(!defined('IN_PEEL_ADMIN')?' AND nom_' . $_SESSION['session_langue'] . '!="" ':'').' '.(!empty($technical_code)?' AND technical_code ="' . nohtml_real_escape_string($technical_code) . '"':'').' AND ' . get_filter_site_cond('categories', 'c', $use_admin_rights) . '
				ORDER BY c.position ASC, nom ASC';
		} elseif($mode == 'rubriques') {
			$sql = 'SELECT r.id, r.parent_id, r.nom_' . $_SESSION['session_langue'] . ' AS nom
				FROM peel_rubriques r
				WHERE r.etat = "1" AND r.technical_code NOT IN ("other", "iphone_content") AND r.position>=0 AND ' . get_filter_site_cond('rubriques', 'r', $use_admin_rights) . '
				ORDER BY r.position ASC, nom ASC';
		} elseif($mode == 'categories_annonces') {
			$sql = 'SELECT c.id, c.parent_id, c.nom_' . $_SESSION['session_langue'] . ' AS nom
				FROM peel_categories_annonces c
				WHERE '.(!defined('IN_PEEL_ADMIN')?'c.nom_' . $_SESSION['session_langue'] . '!="" AND c.etat=1':'1').'
				ORDER BY c.position ASC, '.word_real_escape_string($GLOBALS['site_parameters']['ads_categories_order_by']).' ASC';
		} elseif($mode == 'partenaires_categories') {
			$sql = 'SELECT r.id, r.nom_' . $_SESSION['session_langue'] . ' AS nom
				FROM peel_partenaires_categories r
				WHERE 1
				ORDER BY r.position ASC, nom ASC';
		} else {
			return null;
		}
		$qid = query($sql);
		while ($result = fetch_assoc($qid)) {
			$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
			$item_name_array[$result['id']] = (!empty($result['nom'])?$result['nom']:'['.$result['id'].']');
		}
		if (!empty($all_parents_with_ordered_direct_sons_array)) {
			$output .= get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $selected_item, $mode, $location, null, 25, $display_mode, $add_indent, $input_name);
		}
		return $output;
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
	 * @param mixed $selected_item
	 * @param string $mode
	 * @param mixed $location indicates the position in the website : left or right
	 * @param integer $max_depth_allowed
	 * @param integer $item_max_length spécifie le nombre de caractères des ancres dans les liens
	 * @return
	 */
	function get_recursive_items_display(&$all_parents_with_ordered_direct_sons_array, &$item_name_array, $this_parent, $this_parent_depth, $selected_item = null, $mode = 'categories', $location = null, $max_depth_allowed = null, $item_max_length = 25, $display_mode = 'option', $add_indent = '&nbsp;&nbsp;', $input_name = null)
	{
		static $tpl;
		$output = '';
		if (!empty($all_parents_with_ordered_direct_sons_array[$this_parent])) {
			if(empty($tpl)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('recursive_items_display.tpl');
			}
			$tpl->assign('sons_ico_src', $GLOBALS['wwwroot'] . '/images/right.gif');
			$tpl->assign('display_mode', $display_mode);
			$tpl->assign('location', $location);
			$tpl->assign('input_name', $input_name);
			$tplItems = array();
			$this_depth = $this_parent_depth + 1;
			$indent = '';
			for($i=1;$i<$this_depth;$i++) {
				$indent .= $add_indent;
			}
			foreach ($all_parents_with_ordered_direct_sons_array[$this_parent] as $this_item) {
				$searched_item = '';
				$tplItem = array();
				if (is_array($selected_item)) {
					// Plusieurs sélections possibles : checkbox par exemple, ou select multiple
					$is_selected = in_array($this_item, $selected_item);
				} else {
					$is_selected = ($this_item == $selected_item);
				}
				if (!empty($all_parents_with_ordered_direct_sons_array[$this_item])) {
					if(empty($max_depth_allowed) || $this_depth<$max_depth_allowed) {
						$tplItem['has_sons'] = true;
					} else {
						$tplItem['has_sons'] = false;
					}
					if (!is_array($selected_item)) {
						// On cherche si le noeud est sélectionné ou un de ses fils l'est
						// On commence par regarder si le noeud actuel est le parent de la sélection
						$searched_item = $selected_item;
						$i = 0;
						// On met une sécurité pour éviter boucle infinie si un fils est son propre parent
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
					}
				} else {
					$tplItem['has_sons'] = false;
				}
				$tplItem['is_current'] = ($is_selected || !empty($searched_item));
				$tplItem['is_selected'] = $is_selected;

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

				if ($tplItem['has_sons']) {
					$tplItem['SONS'] = get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, $this_item, $this_depth, $selected_item, $mode, $location, $max_depth_allowed, $item_max_length, $display_mode, $add_indent, $input_name);
				}
				if (function_exists('get_technical_code')) {
					$tplItem['technical_code'] = get_technical_code($this_item);
				}
				$tplItem['value'] = $this_item;
				$tplItem['id'] = 'menu_'.substr(md5(vb($tplItem['href']) . '_' . vb($tplItem['name'])),0,8);
				$tplItem['depth'] = $this_depth;
				$tplItem['indent'] = $indent;
				$tplItem['item_max_length'] = $item_max_length;
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
		$qid = query("SELECT * 
			FROM peel_societe
			WHERE " . get_filter_site_cond('societe') . "");
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
		$qid = query("SELECT * 
			FROM peel_societe
			WHERE " . get_filter_site_cond('societe') . "");
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
				$output .= $tpl->fetch();
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
			WHERE ' . get_filter_site_cond('cgv');
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
			WHERE " . get_filter_site_cond('legal');
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
			WHERE " . get_filter_site_cond('contacts');
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
		$sql = 'SELECT p.id, p.on_reseller, p.surtitre_' . $_SESSION['session_langue'] . ', p.titre_' . $_SESSION['session_langue'] . ', p.chapo_' . $_SESSION['session_langue'] . ', p.texte_' . $_SESSION['session_langue'] . ', p.image1, p.on_special, p.date_maj
			FROM peel_articles p
			'.(!empty($rubid)?'INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id AND pc.rubrique_id='.intval($rubid):'').'
			WHERE p.on_special = "1" AND p.etat = "1" AND ' . get_filter_site_cond('articles', 'p') . '
			ORDER BY p.date_maj DESC
			LIMIT 0,1';
		$query = query($sql);

		if (num_rows($query) > 0) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('actu.tpl');
			$tplData = array();
			while ($art = fetch_assoc($query)) {
				if ((!a_priv("admin_product") && !a_priv("reve")) && $art['on_reseller'] == 1) {
					continue;
				} else {
					$tplData[] = array('titre' => $art['titre_' . $_SESSION['session_langue']],
						'date' => get_formatted_date(time()),
						'image_src' => (!empty($art['image1']) ? $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($art['image1'], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit') : null),
						'chapo' => $art['chapo_' . $_SESSION['session_langue']]
						);
				}
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
		if (check_if_module_active('download')) {
			$tpl->assign('downloadable_file_link_array', get_downloadable_file_link(array('user_id' => $_SESSION['session_utilisateur']['id_utilisateur'])));
			$tpl->assign('STR_MODULE_TELECHARGEMENT_FOR_DOWNLOAD', $GLOBALS['STR_MODULE_TELECHARGEMENT_FOR_DOWNLOAD']);
		}
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
			$tpl->assign('product_ordered_history_href', $GLOBALS['wwwroot'] . '/achat/historique_commandes.php?mode=product_ordered_history');
			$tpl->assign('STR_PRODUCTS_PURCHASED_LIST', $GLOBALS['STR_PRODUCTS_PURCHASED_LIST']);
			if (is_cart_preservation_module_active()) {
				$tpl->assign('cart_preservation', array('txt' => $GLOBALS['STR_CART_PRESERVATION_TITLE'], 'href' => $GLOBALS['wwwroot'] . '/modules/cart_preservation/cart_preservation.php'));
			}
			if (check_if_module_active('agenda')) {
				$tpl->assign('STR_MODULE_AGENDA_TITRE', $GLOBALS['STR_MODULE_AGENDA_TITRE']);
				$tpl->assign('page_agenda', array('txt' => $GLOBALS['STR_MODULE_AGENDA_TITRE'], 'href' => $GLOBALS['wwwroot'] . '/modules/agenda/all_events.php'));
			}
			if (!empty($GLOBALS['site_parameters']['enable_create_product_in_front'])) {
				$tpl->assign('STR_CATALOGUE', $GLOBALS['STR_CATALOGUE']);
				$tpl->assign('page_creation_produit', array('txt' => $GLOBALS['STR_MODULE_CREATE_PRODUCT_IN_FRONT_OFFICE_CREATE_PRODUCT'], 'href' => get_content_url(null, null, null, null, false, false, 'display_product_form')));
			}
			if (check_if_module_active('payback')) {
				$tpl->assign('return_history', array('header' => $GLOBALS['STR_MODULE_PAYBACK_MY_RETURN'], 'txt' => $GLOBALS['STR_MODULE_PAYBACK_RETURN_HISTORY'], 'href' => $GLOBALS['wwwroot'] . '/modules/payback/historique_retours.php'));
			}
			if (check_if_module_active('annonces')) {
				$sql = "SELECT id 
					FROM peel_categories 
					WHERE technical_code = 'ads' AND " . get_filter_site_cond('categories') . "";
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
			if (check_if_module_active('telechargement')) {
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
			if (check_if_module_active('vitrine')) {
				$tpl->assign('shop', array('header' => $GLOBALS['STR_MODULE_ANNONCES_MY_SHOP'], 'txt' => $GLOBALS['STR_MODULE_ANNONCES_CREATE_MY_SHOP'] . ' / ' . $GLOBALS['STR_MODULE_ANNONCES_UPDATE_YOUR_SHOP'], 'href' => $GLOBALS['wwwroot'] . '/modules/vitrine/boutique_form.php'));
			}
			$tpl->assign('change_params', array('header' => $GLOBALS['STR_CHANGE_PARAMS'], 'txt' => $GLOBALS['STR_CHANGE_PARAMS'], 'href' => $GLOBALS['wwwroot'] . '/utilisateurs/change_params.php'));
			$tpl->assign('change_password', array('txt' => $GLOBALS['STR_CHANGE_PASSWORD'], 'href' => $GLOBALS['wwwroot'] . '/utilisateurs/change_mot_passe.php'));
			if (check_if_module_active('blog')) {
				$tpl->assign('MON_COMPTE_BLOG', get_mon_compte_blog());
			}
			if (is_giftlist_module_active()) {
				$tpl->assign('giftlist', array('header' => $GLOBALS['STR_LISTE_CADEAU'], 'txt' => $GLOBALS['STR_MODULE_LISTECADEAU_VOIR_LISTE_CADEAU'], 'href' => $GLOBALS['wwwroot'] . '/modules/listecadeau/voir.php'));
			}
			if (is_module_pensebete_active()) {
				$tpl->assign('pensebete', array('header' => $GLOBALS['STR_PENSE_BETE'], 'txt' => $GLOBALS['STR_VOIR_PENSE_BETE'], 'href' => $GLOBALS['wwwroot'] . '/modules/pensebete/voir.php'));
			}
			if (is_parrainage_module_active()) {
				$tpl->assign('parrainage', array('header' => $GLOBALS['STR_PARRAIN_ENTETE'], 'txt' => sprintf($GLOBALS['STR_PARRAIN_TEXTE'], fprix($GLOBALS['site_parameters']['avoir']), fprix($GLOBALS['site_parameters']['avoir'])), 'href' => $GLOBALS['wwwroot'] . '/modules/parrainage/parrain.php'));
			}
			if (is_gifts_module_active()) {
				$tpl->assign('gift', array('header' => $GLOBALS['STR_MY_GIFT_POINT'], 'gifts_points' => vn($user["points"]), 'txt' => $GLOBALS["STR_LISTE_CADEAU"], 'href' => $GLOBALS['wwwroot'] . '/achat/index.php?convert_gift_points=1'));
			}
			// les codes utilisés
			$code_promo_query = query('SELECT code_promo, valeur_code_promo, percent_code_promo
				FROM peel_commandes pc
				WHERE pc.id_utilisateur = "' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '" AND code_promo != ""  AND ' . get_filter_site_cond('commandes', 'pc'));
			if (num_rows($code_promo_query) > 0) {
				$cpu_data = array();
				while ($cp = fetch_assoc($code_promo_query)) {
					$cpu_data[] = array('code_promo' => $cp['code_promo'], 'discount_text' => get_discount_text($cp['valeur_code_promo'], $cp['percent_code_promo'], true));
				}
				$tpl->assign('code_promo_utilise', array('header' => $GLOBALS['STR_MES_CODE_PROMO_UTILISE'], 'data' => $cpu_data));
			}
			// les codes qui peuvent être encore utilisés
			$current_code_promo_query = query('SELECT *
				FROM peel_utilisateurs_codes_promos ucp
				INNER JOIN peel_codes_promos cp ON cp.id = ucp.id_code_promo AND ' . get_filter_site_cond('codes_promos', 'cp') . ' AND cp.etat = "1" AND ("' . date('Y-m-d', time()) . '" BETWEEN cp.date_debut AND cp.date_fin)
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

			$profil = check_if_module_active('profil') ? get_profil($_SESSION['session_utilisateur']['priv']) : null;
			if (check_if_module_active('profil')) {
				if (!empty($profil['document_'.$_SESSION['session_langue']]) || !empty($profil['description_document_'.$_SESSION['session_langue']])) {
					$tpl->assign('profile', array('header' => $GLOBALS['STR_ACCOUNT_DOCUMENTATION'],
							'href' => (!empty($profil['document_'.$_SESSION['session_langue']])? $GLOBALS['repertoire_upload'] . '/' . $profil['document_'.$_SESSION['session_langue']]:''),
							'txt' => $GLOBALS['STR_DOWNLOAD_DOCUMENT'],
							'content' => $profil['description_document_'.$_SESSION['session_langue']]
							));
				}
			}
			if (check_if_module_active('sauvegarde_recherche')) {
				$tpl->assign('STR_MODULE_SAUVEGARDE_SEARCH_LIST', $GLOBALS['STR_MODULE_SAUVEGARDE_SEARCH_LIST']);
				$tpl->assign('sauvegarde_recherche', array('txt' => $GLOBALS["STR_MODULE_SAUVEGARDE_MY_ALERTS"], 'href' => $GLOBALS['wwwroot'] . '/modules/sauvegarde_recherche/mes_alertes.php'));
			}
			$tpl->assign('logout', array('href' => $GLOBALS['wwwroot'] . '/sortie.php', 'txt' => $GLOBALS['STR_LOGOUT']));

			if (a_priv('admin*', true)) {
				$tpl->assign('admin', array('href' => $GLOBALS['administrer_url'] . '/index.php', 'txt' => $GLOBALS['STR_ADMIN']));
			}
			if (check_if_module_active('abonnement')) {
				$tpl->assign('ABONNEMENT_MODULE', verified_status_activated());
			}
			if (check_if_module_active('annonces')) {
				$qannonce = query('SELECT COUNT(*) AS gold_credit
					FROM peel_gold_ads_to_users
					WHERE user_id="' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '"');
				$annonce = fetch_assoc($qannonce);
				$tpl->assign('annonce', array('label' => $GLOBALS['STR_MODULE_ANNONCES_GOLD_AD_CREDIT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'credit' => $annonce['gold_credit']));
			}
			if (check_if_module_active('user_alerts')) {
				$tpl->assign('user_alerts', array('txt' => $GLOBALS["STR_MODULE_USER_ALERTS_MY_ALERTS"], 'href' => $GLOBALS['wwwroot'] . '/modules/user_alerts/mes_alertes.php'));
			}
			if(!empty($GLOBALS['site_parameters']['disable_account_by_user_in_front_office'])) {
				$tpl->assign('disable_account', true);
				$tpl->assign('disable_account_text', $GLOBALS["STR_DISABLE_ACCOUNT"]);
				$tpl->assign('disable_account_href', $GLOBALS['wwwroot'] . '/compte.php?unsubscribe_account=true');
				$tpl->assign('confirm_disable_account', $GLOBALS["STR_CONFIRM_DISABLE_ACCOUNT"]);
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

		$tpl->assign('has_details', $detailed);
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
			$display_picture = $product_object->get_product_main_picture(false);
			if ($display_picture) {
				$product_picture = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($display_picture, 75, 75, 'fit');
			} elseif(!empty($GLOBALS['site_parameters']['default_picture'])) {
				$product_picture = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit');
			} else {
				$product_picture = null;
			}
			$tmpProd['picture'] = $product_picture;
			$tplProducts[] = $tmpProd;
			unset($product_object);
		}
		$tpl->assign('products', $tplProducts);
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
	 * @param string $display_mode
	 * @param string $location
	 * @param string $technical_code
	 * @param string $title
	 * @param string $content
	 * @param string $block_class
	 * @param string $block_style
	 * @param boolean $return_mode
	 * @param boolean $is_slider_mode
	 * @param boolean $is_simplify_mode
	 * @param boolean $extra_class
	 * @return
	 */
	function affiche_block($display_mode = 'sideblocktitle', $location = '', $technical_code = '', $title = '', $content = '', $block_class = '', $block_style = '', $return_mode = true, $is_slider_mode = false, $is_simplify_mode = false, $extra_class = false)
	{
		$mode = (!empty($rewrite_mame_mode)) ? clean_str($display_mode) : $display_mode;
		$tpl = $GLOBALS['tplEngine']->createTemplate('block.tpl');
		$tpl->assign('is_slider_mode', $is_slider_mode);
		$tpl->assign('extra_class', $extra_class);
		$tpl->assign('is_simplify_mode', $is_simplify_mode);
		$tpl->assign('lang', $_SESSION['session_langue']);
		$tpl->assign('mode', $mode);
		$tpl->assign('block_class', $block_class);
		$tpl->assign('location', $location);
		$tpl->assign('technical_code', $technical_code);
		$tpl->assign('block_style', $block_style);
		$tpl->assign('content', $content);
		$tpl->assign('title', $title);
		$tpl->assign('block_columns_width_sm', vb($GLOBALS['site_parameters']['block_columns_width_sm'], 4));
		$tpl->assign('block_columns_width_md', vb($GLOBALS['site_parameters']['block_columns_width_md'], 3));
		$tpl->assign('STR_PREVIOUS_PAGE', $GLOBALS['STR_PREVIOUS_PAGE']);
		$tpl->assign('STR_NEXT_PAGE', $GLOBALS['STR_NEXT_PAGE']);
		$output = $tpl->fetch();
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
		// Sur la homepage on veut des microdatas pour préciser le moteur de recherche du site
		// comme défini sur : https://developers.google.com/webmasters/richsnippets/sitelinkssearch et http://schema.org/WebSite
		$tpl->assign('add_webpage_microdata', defined('IN_HOME'));
		$tpl->assign('action', get_search_url());
		$tpl->assign('display_mode', $display_mode);
		if (check_if_module_active('search')) {
			$tpl->assign('advanced_search_script', get_advanced_search_script());
			$tpl->assign('select_marque', affiche_select_marque(true));
		}
		// on construit la liste des catégories
		if(check_if_module_active('annonces')) {
			// on construit les options du select des catégories
			$tpl->assign('select_categorie', get_categories_output(null, 'categories_annonces', vn($_GET["categorie"]), 'option', '&nbsp;&nbsp;', null));
			$tpl->assign('STR_CATEGORY', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD']);
			if(!empty($GLOBALS['STR_MODULE_ANNONCES_SEARCH_TYPOLOGIE'])) {
				$additionnal_select = '';
				if (!empty($GLOBALS['site_parameters']['ads_verified_status_per_subscription'])) {
					$additionnal_select .= '
						<option value="1" ' . frmvalide((!empty($_GET['cat_statut_detail']) && $_GET['cat_statut_detail'] == 1), 'selected="selected"') . '>' . $GLOBALS['STR_MODULE_ANNONCES_ALT_VERIFIED_ADS'] . '</option>
';
				}
				if(!empty($GLOBALS['site_parameters']['ads_contain_lot_sizes'])) {
					$additionnal_select .= '
						<option value="gros" ' . frmvalide((!empty($_GET['cat_statut_detail']) && $_GET['cat_statut_detail'] == 'gros'), 'selected="selected"') . '>' . $GLOBALS['STR_MODULE_ANNONCES_OFFER_GROS'] . '</option>
						<option value="demigros" ' . frmvalide((!empty($_GET['cat_statut_detail']) && $_GET['cat_statut_detail'] == 'demigros'), 'selected="selected"') . '>' . $GLOBALS['STR_MODULE_ANNONCES_OFFER_DEMIGROS'] . '</option>
						<option value="detail" ' . frmvalide((!empty($_GET['cat_statut_detail']) && $_GET['cat_statut_detail'] == 'detail'), 'selected="selected"') . '>' . $GLOBALS['STR_MODULE_ANNONCES_OFFER_DETAIL'] . '</option>';
				}
				if(!empty($additionnal_select)) {
					$additionnal_select = '	<select class="form-control" name="cat_statut_detail">
						<option value="">' . $GLOBALS['STR_MODULE_ANNONCES_SEARCH_TYPOLOGIE'] . '</option>
						' . $additionnal_select . '
					</select>
';
					$tpl->assign('additionnal_select', $additionnal_select);
				}
			}
		} else {
			// on construit les options du select des catégories
			$tpl->assign('select_categorie', get_categories_output(null, 'categories', vb($_GET["categorie"]), 'option', '&nbsp;&nbsp;', null));
			$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
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
		$tpl = $GLOBALS['tplEngine']->createTemplate('guide.tpl');
		$tplLinks = array();
		$tplLinks['contact'] = array('name' => 'contact', 'href' => $GLOBALS['wwwroot'] . '/contacts.php', 'label' => $GLOBALS['STR_CONTACT_INFO'], 'selected' => defined('IN_CONTACT_US'));
		if (is_affiliate_module_active()) {
			$tplLinks['affiliate'] = array('name' => 'affiliate', 'href' => $GLOBALS['wwwroot'] . '/modules/affiliation/affiliate.php', 'label' => $GLOBALS['STR_AFFILIATE'], 'selected' => defined('IN_AFFILIATE'));
		}
		if (is_reseller_module_active()) {
			$tplLinks['retailer'] = array('name' => 'retailer', 'href' => $GLOBALS['wwwroot'] . '/modules/reseller/retailer.php', 'label' => $GLOBALS['STR_RETAILER'], 'selected' => defined('IN_RETAILER'));
		}
		if (is_module_faq_active()) {
			$tplLinks['faq'] = array('name' => 'faq', 'href' => $GLOBALS['wwwroot'] . '/modules/faq/faq.php', 'label' => $GLOBALS['STR_FAQ_TITLE'], 'selected' => defined('IN_FAQ'));
		}
		if (is_module_forum_active()) {
			$tplLinks['forum'] = array('name' => 'forum', 'href' => $GLOBALS['wwwroot'] . '/modules/forum/index.php', 'label' => $GLOBALS['STR_FORUM'], 'selected' => defined('IN_FORUM'));
		}
		if (check_if_module_active('lexique')) {
			$tplLinks['lexique'] = array('name' => 'lexique', 'href' => get_lexicon_url(), 'label' => $GLOBALS['STR_LEXIQUE'], 'selected' => defined('IN_LEXIQUE'));
		}
		if (check_if_module_active('partenaires')) {
			$tplLinks['partner'] = array('name' => 'partner', 'href' => $GLOBALS['wwwroot'] . '/modules/partenaires/partenaires.php', 'label' => $GLOBALS['STR_OUR_PARTNER'], 'selected' => defined('IN_PARTNER'));
		}
		if (check_if_module_active('references')) {
			$tplLinks['references'] = array('name' => 'references', 'href' => $GLOBALS['wwwroot'] . '/modules/references/references.php', 'label' => $GLOBALS['STR_NOS_REFERENCES'], 'selected' => defined('IN_REFERENCE'));
		}
		$tplLinks['access_plan'] = array('name' => 'access_plan', 'href' => $GLOBALS['wwwroot'] . '/plan_acces.php', 'label' => $GLOBALS['STR_ACCESS_PLAN'], 'selected' => defined('IN_PLAN_ACCES'));
		if(isset($GLOBALS['site_parameters']['show_on_affiche_guide']) && is_array($GLOBALS['site_parameters']['show_on_affiche_guide'])) {
			$temp = array();
			foreach($GLOBALS['site_parameters']['show_on_affiche_guide'] as $this_value) {
				if(isset($tplLinks[$this_value])) {
					$temp[$this_value] = $tplLinks[$this_value];
				}
			}
			$tplLinks = $temp;
		}
		$tpl->assign('links', $tplLinks);
		$tpl->assign('menu_contenu', get_categories_output($location, 'rubriques', vn($_GET['rubid']), 'list', null));

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
		if (empty($GLOBALS['site_parameters']['social_icons_disable'])) {
			$tpl->assign('rss', affiche_social_icons(true));
			$tpl->assign('facebook_page', null);
		} elseif (check_if_module_active('facebook')) {
			$tpl->assign('facebook_page', get_facebook_page('Facebook'));
		}
		if(function_exists('get_footer_additional')) {
			$tpl->assign('footer_additional', get_footer_additional());
		} else {
			$tpl->assign('footer_additional', vb($GLOBALS['site_parameters']['footer_additional']));
		}
		$tpl->assign('STR_SITE_GENERATOR', $GLOBALS['STR_SITE_GENERATOR']);
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('affiche_social_icons')) {
	/**
	 * affiche_social_icons()
	 *
	 * @param boolean $return_mode
	 * @return
	 */
	function affiche_social_icons($return_mode = false)
	{
		$output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/rss_func.tpl');
		if (is_module_rss_active()) {
			if (check_if_module_active('annonces')) {
				$tpl->assign('href', $GLOBALS['wwwroot'] . '/modules/annonces/rss.php');
				$tpl->assign('rss_new_window', false);
			} else {
				$tpl->assign('href', $GLOBALS['wwwroot'] . '/modules/rss/rss.php?critere=on_promo');
				$tpl->assign('rss_new_window', true);
			}
			$tpl->assign('src', $GLOBALS['repertoire_images'] . '/rss.png');
			$load = true;
		}
		if (!empty($GLOBALS['site_parameters']['facebook_page_link'])) {
			$tpl->assign('fb_href', $GLOBALS['site_parameters']['facebook_page_link']);
			$tpl->assign('fb_src', $GLOBALS['repertoire_images'] . '/facebook.png');
			$load = true;
		}
		if (!empty($GLOBALS['site_parameters']['twitter_page_link'])) {
			$tpl->assign('twitter_href', $GLOBALS['site_parameters']['twitter_page_link']);
			$tpl->assign('twitter_src', $GLOBALS['repertoire_images'] . '/twitter.png');
			$load = true;
		}
		if (!empty($GLOBALS['site_parameters']['googleplus_page_link'])) {
			$tpl->assign('googleplus_href', $GLOBALS['site_parameters']['googleplus_page_link']);
			$tpl->assign('googleplus_src', $GLOBALS['repertoire_images'] . '/googleplus.png');
			$load = true;
		}
		if(!empty($load)) {
			$tpl->assign('block_columns_width_sm', vb($GLOBALS['site_parameters']['footer_columns_width_sm'], 4));
			$tpl->assign('block_columns_width_md', vb($GLOBALS['site_parameters']['footer_columns_width_md'], 3));
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_compte')) {
	/**
	 * affiche_compte()
	 *
	 * @param boolean $return_mode
	 * @param string $location
	 * @return
	 */
	function affiche_compte($return_mode = false, $location)
	{
		$output = '';
		if (est_identifie()) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('compte_mini.tpl');
			$tpl->assign('location', $location);
			$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
			$tpl->assign('membre_href', $GLOBALS['wwwroot'] . '/membre.php');
			$tpl->assign('prenom', vb($_SESSION['session_utilisateur']['prenom']));
			$tpl->assign('nom_famille', vb($_SESSION['session_utilisateur']['nom_famille']));
			$tpl->assign('sortie_href', $GLOBALS['wwwroot'] . '/sortie.php');
			if (function_exists('get_social_icone')) {
				$tpl->assign('social_icone', get_social_icone());
			}
			$tpl->assign('compte_href', get_account_url(false, false));
			$tpl->assign('history_href', $GLOBALS['wwwroot'] . '/achat/historique_commandes.php');
			if (is_facebook_connect_module_active() && !empty($_SESSION['session_utilisateur']['connected_by_fb'])) {
				$tpl->assign('fb_deconnect_lbl', $GLOBALS['STR_FB_DECONNECT']);
			}
			if (a_priv('admin*', true)) {
				$tpl->assign('admin', array('href' => $GLOBALS['administrer_url'] . '/index.php', 'txt' => $GLOBALS['STR_ADMINISTRATION']));
			}
			$tpl->assign('STR_HELLO', $GLOBALS['STR_HELLO']);
			$tpl->assign('STR_COMPTE', $GLOBALS['STR_COMPTE']);
			$tpl->assign('STR_DECONNECT', $GLOBALS['STR_DECONNECT']);
			$tpl->assign('STR_ORDER_HISTORY', $GLOBALS['STR_ORDER_HISTORY']);
			$output .= $tpl->fetch();
		} else {
			$url_enregistrement = get_account_register_url(false, false);
			$tpl = $GLOBALS['tplEngine']->createTemplate('compte_login_mini.tpl');
			$tpl->assign('location', $location);
			$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
			$tpl->assign('email_lbl', $GLOBALS['STR_EMAIL']);
			$tpl->assign('email', vb($frm['email']));
			$tpl->assign('password_lbl', $GLOBALS['STR_PASSWORD']);
			$tpl->assign('password', vb($frm['mot_passe']));
			$tpl->assign('TOKEN', get_form_token_input('membre.php', true));
			$tpl->assign('forgot_pass_href', $GLOBALS['wwwroot'] . '/utilisateurs/oubli_mot_passe.php');
			$tpl->assign('forgot_pass_lbl', $GLOBALS['STR_FORGOT_YOUR_PASSWORD']);
			$tpl->assign('enregistrement_href', $url_enregistrement);
			$tpl->assign('enregistrement_lbl', $GLOBALS['STR_OPEN_ACCOUNT']);
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
			if (check_if_module_active('openid')) {
				$social['is_any'] = true;
				$social['openid'] = get_openid_btn();
			}
			$tpl->assign('social', $social);
			$tpl->assign('STR_COMPTE', $GLOBALS['STR_COMPTE']);
			$tpl->assign('STR_LOGIN', $GLOBALS['STR_LOGIN']);
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
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
	function getHTMLHead($page_name, &$category_introduction_text)
	{
		$output = '';
		$js_output = '';
		$tpl = $GLOBALS['tplEngine']->createTemplate('HTMLHead.tpl');
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
			} elseif (defined('IN_DOWNLOAD_PEEL') && !empty($GLOBALS['STR_MODULE_PEEL_DOWNLOAD_PEEL'])) {
				$default_title = $GLOBALS['STR_MODULE_PEEL_DOWNLOAD_PEEL'];
			} else {
				$default_title = null;
			}
			$tpl->assign('meta', affiche_meta($default_title, true));
		}

		if (!empty($GLOBALS['site_parameters']['favicon'])) {
			$tpl->assign('favicon_href', $GLOBALS['repertoire_upload'] . '/' . $GLOBALS['site_parameters']['favicon']);
		}
		if (check_if_module_active('vitrine')) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/vitrine/css/vitrine.css';
		}
		if (check_if_module_active('annonces')) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/rating_bar/rating.css';
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/annonces.css';
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/contentslider/contentslider.css';
		}
		if (check_if_module_active('carrousel')) {
			// Librairie pour activer le carrousel Module a la carte et partenaire
			// Chargé exprès sur toutes les pages pour avoir fichier CSS minifié unique
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/carrousel/css/carrousel.css';

			$GLOBALS['js_files'][-21] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/carrousel.js';
			$GLOBALS['js_files'][-20] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/slides.min.jquery.js';
		}
		if (empty($GLOBALS['site_parameters']['lightbox_disable'])) {
			// Lightbox peut servir à différents endroits du logiciel. Si on est sûr qu'on ne s'en sert pas, on peut le désactiver avec disable_lightbox
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/lightbox.css';
		}
		if (vb($GLOBALS['site_parameters']['zoom']) == 'jqzoom' && vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/jqzoom.css';
		} elseif (vb($GLOBALS['site_parameters']['zoom']) == 'cloud-zoom' && vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/cloudzoom.css';
		}
		// Début des javascripts
		if (check_if_module_active('fianet')) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/fianet/lib/js/fianet.js';
		}
		if (vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['js_files'][-100] = $GLOBALS['wwwroot'] . '/lib/js/jquery.js';
		}
		$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/jquery-ui.css';
		$GLOBALS['js_files'][-90] = $GLOBALS['wwwroot'] . '/lib/js/jquery-ui.js';
		if(file_exists($GLOBALS['dirroot'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js')) {
			// Configuration pour une langue donnée
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js';
		}
		// <!-- librairie pour activer le zoom sur les categories (et produits si configuration dans l'administration) -->
		if (empty($GLOBALS['site_parameters']['lightbox_disable'])) {
			// Lightbox peut servir à différents endroits du logiciel. Si on est sûr qu'on ne s'en sert pas, on peut le désactiver avec disable_lightbox
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/lightbox.js';
		}
		// <!-- fin de librairie pour activer le zoom sur les categories -->
		if (vb($GLOBALS['site_parameters']['enable_prototype']) == 1 && empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/prototype.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/effects.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/controls.js';
		}
		if (check_if_module_active('annonces')) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/rating_bar/js/rating.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/contentslider/contentslider.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/annonces/tooltip.js';
		}
		if (!empty($GLOBALS['load_anythingslider'])) {
			// Pour ajouter des vidéos ou des effets au carrousel nivo_slider, il faut inclure les fichiers suivants :
			// AnythingSlider optional extensions
			// $GLOBALS['js_files_nominify'][-18] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/jquery.anythingslider.fx.min.js';
			// $GLOBALS['js_files_nominify'][-17] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/jquery.anythingslider.video.min.js';
			$GLOBALS['js_files_nominify'][-19] = $GLOBALS['wwwroot'] . '/modules/carrousel/js/jquery.anythingslider.min.js';
		}
		if (((defined('IN_RUBRIQUE') && vb($rub['technical_code']) == 'creation') || (defined('IN_REFERENCE') && !empty($GLOBALS['site_parameters']['affiche_reference_multipage_with_pheonix_gallery']))) && check_if_module_active('references')) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/references/style/style.css';
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/references/phoenixgallery/style/style.css';
			$GLOBALS['js_files'][-60] = $GLOBALS['wwwroot'] . '/lib/js/jquery.easing.min.js';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/references/phoenixgallery/js/phoenixgallery.js';
		}
		if (defined('IN_CONTACT') && check_if_module_active('photodesk')) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/modules/photodesk/css/style.css';
			$GLOBALS['js_files'][-50] = $GLOBALS['wwwroot'] . '/modules/photodesk/js/jquery.transform-0.6.2.min.js';
			$GLOBALS['js_files'][-49] = $GLOBALS['wwwroot'] . '/modules/photodesk/js/jquery.animate-shadow-min.js';
			$GLOBALS['js_files'][-48] = $GLOBALS['wwwroot'] . '/modules/photodesk/js/jquery-ui-1.8.16.custom.min.js';
			$GLOBALS['js_files'][-47] = $GLOBALS['wwwroot'] . '/modules/photodesk/js/photodesk.js';
		}
		// Librairie pour activer le zoom sur les produits
		if (check_if_module_active('welcome_ad')) {
			$js_output .= get_welcome_ad_script();
		}
		if ($GLOBALS['site_parameters']['zoom'] == 'jqzoom' && $GLOBALS['site_parameters']['enable_jquery'] == 1) {
			$GLOBALS['js_files'][-70] = $GLOBALS['wwwroot'] . '/lib/js/jquery.jqzoom-core-pack.js';
		} elseif ($GLOBALS['site_parameters']['zoom'] == 'cloud-zoom' && $GLOBALS['site_parameters']['enable_jquery'] == 1) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/cloud-zoom.1.0.2.js';
		}
		if (is_module_forum_active() && $_SESSION['session_langue'] == 'fr') {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/modules/forum/forum.js';
		}
		if (check_if_module_active('cart_popup') && !empty($_SESSION['session_show_caddie_popup'])) {
			$js_output .= get_cart_popup_script();
			unset($_SESSION['session_show_caddie_popup']);
		}
		if (is_googlefriendconnect_module_active()) {
			$js_output .= google_friend_connect_javascript_library();
		}
		$GLOBALS['js_ready_content_array'][] = get_datepicker_javascript() . '
			'.vb($js_sortable).'
';
		if (empty($GLOBALS['site_parameters']['disable_autocomplete'])) {
			$GLOBALS['js_ready_content_array'][] = '
		bind_search_autocomplete("search", "' . $GLOBALS['wwwroot'] . '/modules/search/produit.php", true);
';
		}
		if(!empty($GLOBALS['load_nyromodal'])){
			// Nyromodal ne charge pas en asynchrone, si nyromodal est activé on désactive l'asynchrone pour permettre au 'diaporama' Nyromodal de fonctionner.
			$GLOBALS['site_parameters']['load_javascript_async'] = false;
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/nyroModal.css';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/jquery.nyroModal.custom.js';
		
			$GLOBALS['js_ready_content_array'][] = '
		$(function() {
			$(".nyroModal").nyroModal();
		});
';
		}
		// On met en dernier fichiers CSS du site pour qu'ils aient priorité
		if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
			$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/bootstrap.css';
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/bootstrap.js';
			$GLOBALS['js_ready_content_array'][] = '
	bootbox.setDefaults({
		locale: "'.$_SESSION['session_langue'].'"
	});';
		}
		$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/advisto.js';
		if (vb($GLOBALS['site_parameters']['anim_prod']) == 1) {
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/fly-to-basket.js';
		}
		if(!empty($GLOBALS['site_parameters']['css'])) {
			foreach (get_array_from_string($GLOBALS['site_parameters']['css']) as $this_css_file) {
				$this_css_file = trim($this_css_file);
				if (String::strpos($this_css_file, '//') !== false) {
					$GLOBALS['css_files'][] = $this_css_file;
				} elseif(file_exists($GLOBALS['repertoire_modele'] . '/css/' . $this_css_file)) {
					$GLOBALS['css_files'][] = $GLOBALS['repertoire_css'] . '/' . $this_css_file;  // .'?'.time()
				}
			}
		}
		$tpl->assign('css_files', get_css_files_to_load(!empty($GLOBALS['site_parameters']['minify_css'])));

		// L'ordre des fichiers js doit être respecté ensuite dans le template
		if (!empty($GLOBALS['site_parameters']['javascript_force_load_header'])) {
			$tpl->assign('js_output', $js_output . get_javascript_output(false, !empty($GLOBALS['site_parameters']['minify_js']), empty($GLOBALS['site_parameters']['javascript_content_force_load_header'])));
		} else {
			$tpl->assign('js_output', $js_output);
		}
		$tpl->assign('msg_err_keyb', $GLOBALS['STR_ERR_KEYB']);

		if (isset($_GET['catid'])) {
			$queryCP = query('SELECT header_html_' . $_SESSION['session_langue'] . ' AS category_introduction_text, background_menu, background_color
				FROM peel_categories
				WHERE id="' . intval($_GET['catid']) . '" AND ' . get_filter_site_cond('categories') . '');
			if ($CP = fetch_object($queryCP)) {
				// $category_introduction_text is a reference of a global variable => it will be used outside this function
				$category_introduction_text = String::html_entity_decode_if_needed(trim($CP->category_introduction_text));
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
	 * Affiche le menu en front-office
	 *
	 * @return
	 */
	function get_menu()
	{
		// Android et Windows mobile déclenchent le hover en même temps que le clic sur un lien, contrairement à iOS sur iPad et iPhone
		$avoid_links_when_hover = !empty($GLOBALS['site_parameters']['bootstrap_enabled']) && (String::strpos(String::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'android') !== false || (String::strpos(String::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'windows') !== false && String::strpos(String::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'mobile') !== false));
		if(!empty($GLOBALS['site_parameters']['categories_top_menu_item_max_length'])) {
			$item_max_length = $GLOBALS['site_parameters']['categories_top_menu_item_max_length'];
		} else {
			$item_max_length = 25;
		}
		if (!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
			//  bootstrap est actif, l'affichage de l'arborescence des catégories est faite via des sous menu distinct, qui s'affiche au survol de la souris. Il n'est pas nécessaire de mettre une indentation en plus.
			$indent = '';
		} else {
			// Menu sans bootstrap, les sous catégories sont décallées par rapport à la catégorie mère, pour permettre un affichage clair de l'arborescence
			$indent = '&nbsp;&nbsp;';
		}
		if (empty($GLOBALS['main_menu_items'])) {
			$GLOBALS['main_menu_items']['home'] = array($GLOBALS['wwwroot'] . '/' => $GLOBALS['STR_HOME']);
			$GLOBALS['main_menu_items']['catalog'] = array(get_product_category_url() => $GLOBALS['STR_CATALOGUE']);
			$GLOBALS['main_menu_items']['news'] = array(get_product_category_url() . 'nouveautes.php' => $GLOBALS['STR_NOUVEAUTES']);
			$GLOBALS['main_menu_items']['content'] = array(get_content_category_url() => $GLOBALS["STR_INFORMATIONS"]);
			$GLOBALS['main_menu_items']['other'] = array('#' => $GLOBALS["STR_OTHER"]);
			$GLOBALS['main_menu_items']['faq'] = array($GLOBALS['wwwroot'] . '/modules/faq/faq.php' => $GLOBALS['STR_FAQ_TITLE']);
			$GLOBALS['main_menu_items']['brand'] = array($GLOBALS['wwwroot'] . '/achat/marque.php' => $GLOBALS['STR_ALL_BRAND']);
			$GLOBALS['main_menu_items']['contact_us'] = array($GLOBALS['wwwroot'] . '/contacts.php' => $GLOBALS['STR_CONTACT_US']);
			$GLOBALS['main_menu_items']['flash'][$GLOBALS['wwwroot'] . '/modules/flash/flash.php'] = $GLOBALS['STR_FLASH'];
			$GLOBALS['main_menu_items']['promotions'][get_product_category_url() . 'promotions.php'] = $GLOBALS['STR_PROMOTIONS'];

			if (is_module_gift_checks_active()) {
				$GLOBALS['main_menu_items']['check'][$GLOBALS['wwwroot'] . '/modules/gift_check/cheques.php'] = $GLOBALS['STR_CHEQUE_CADEAU'];
			}
			if (check_if_module_active('annonces')) {
				$GLOBALS['main_menu_items']['annonces'] = array($GLOBALS['wwwroot'] . '/modules/annonces/index.php' => $GLOBALS['STR_MODULE_ANNONCES_ADS']);
				if (est_identifie()) {
					$GLOBALS['menu_items']['annonces'][$GLOBALS['wwwroot'] . '/modules/annonces/'] = $GLOBALS['STR_MODULE_ANNONCES_LIST_ANNONCES'];
					$GLOBALS['menu_items']['annonces'][$GLOBALS['wwwroot'] . '/modules/annonces/creation_annonce.php'] = $GLOBALS['STR_MODULE_ANNONCES_AD_CREATE'];
				}
				$GLOBALS['main_menu_items']['annonces_verified'] = array(get_verified_url(false) => $GLOBALS['STR_MODULE_ANNONCES_BECOME_VERIFIED']);
			}
			if (check_if_module_active('vitrine')) {
				if (is_module_url_rewriting_active()) {
					$GLOBALS['main_menu_items']['vitrine'] = array(get_list_showcase_url(false, false) => $GLOBALS['STR_MODULE_ANNONCES_SHOP']);
				} else {
					$GLOBALS['main_menu_items']['vitrine'] = array($GLOBALS['wwwroot'] . '/modules/vitrine/' => $GLOBALS['STR_MODULE_ANNONCES_SHOP']);
				}
			}
			if (check_if_module_active('groups_advanced')) {
				$GLOBALS['main_menu_items']['groups_advanced'] = array($GLOBALS['wwwroot'] . '/modules/groups_advanced/user_groups.php'=> $GLOBALS['STR_MODULE_GROUPS_ADVANCED_GROUP']);
				$GLOBALS['main_menu_items']['members_list'] = array($GLOBALS['wwwroot'] . '/modules/groups_advanced/user.php'=> $GLOBALS['STR_MODULE_GROUPS_ADVANCED_MEMBERS_LIST_TITLE']);
			}
			if (!empty($GLOBALS['site_parameters']['enable_create_product_in_front'])) {
				$result = fetch_assoc(query("SELECT id FROM peel_categories WHERE technical_code = 'show_draft' AND etat = 1 AND " . get_filter_site_cond('categories') . ""));
				if (!empty($result['id'])) {
					$GLOBALS['main_menu_items']['draft'] = array(get_product_category_url($result['id']) => $GLOBALS['STR_MODULE_CREATE_PRODUCT_IN_FRONT_OFFICE_SORTIE_SAVE_DRAFT']);
				}
			}
			if (check_if_module_active('agenda')) {
				$GLOBALS['main_menu_items']['agenda'] = array($GLOBALS['wwwroot'] . '/modules/agenda/all_events.php'=> $GLOBALS['STR_MODULE_AGENDA_TITRE']);
			}
			if (is_module_gift_checks_active()) {
				$GLOBALS['menu_items']['news'][$GLOBALS['wwwroot'] . '/modules/gift_check/cheques.php'] = $GLOBALS['STR_CHEQUE_CADEAU'];
			}
			if (est_identifie()) {
				$GLOBALS['main_menu_items']['account'] = array(get_account_url(false, false) => $GLOBALS['STR_COMPTE']);
				if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
					$GLOBALS['menu_items']['account'][get_account_url(false, false)] = $GLOBALS['STR_COMPTE'];
				}
				$GLOBALS['menu_items']['account'][get_product_category_url() . 'historique_commandes.php'] = $GLOBALS['STR_ORDER_HISTORY'];
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
			if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
				$GLOBALS['menu_items']['contact'][get_contact_url(false, false)] = $GLOBALS['STR_CONTACT'];
			}
			$GLOBALS['menu_items']['contact'][$GLOBALS['wwwroot'] . '/plan_acces.php'] = $GLOBALS['STR_ACCESS_PLAN'];
			if (a_priv('admin*', true)) {
				$GLOBALS['main_menu_items']['admin'] = array($GLOBALS['administrer_url'] . '/' => $GLOBALS['STR_ADMIN']);
			}
			$GLOBALS['menu_items']['news'][get_product_category_url() . 'promotions.php'] = $GLOBALS['STR_PROMOTIONS'];
			if (check_if_module_active('flash') && is_flash_active_on_site()) {
				$GLOBALS['menu_items']['news'][$GLOBALS['wwwroot'] . '/modules/flash/flash.php'] = $GLOBALS['STR_FLASH'];
			}
			if (check_if_module_active('devis')) {
				$GLOBALS['menu_items']['devis'][$GLOBALS['wwwroot'] . '/modules/devis/devis.php'] = $GLOBALS['STR_DEVIS'];
			} else {
				$GLOBALS['menu_items']['devis'] = array();
			}
			// $GLOBALS['main_menu_items']['news'] est ajouté dans le sous menu de "Autre" si il n'est pas présent dans les éléments principaux du menu
			$GLOBALS['menu_items']['other'] = array_merge($GLOBALS['main_menu_items']['catalog'], $GLOBALS['menu_items']['news'], (!in_array('news', $GLOBALS['site_parameters']['main_menu_items_if_available'])? $GLOBALS['main_menu_items']['news']:array()), array('' => 'divider'), $GLOBALS['menu_items']['contact'], $GLOBALS['menu_items']['devis'],(!in_array('contact', $GLOBALS['site_parameters']['main_menu_items_if_available'])? $GLOBALS['main_menu_items']['contact']:array()));
		}
		if(isset($GLOBALS['site_parameters']['main_menu_items_if_available']) && is_array($GLOBALS['site_parameters']['main_menu_items_if_available'])) {
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
			$selected_item = intval(vn($_GET['catid']));
			$sql = 'SELECT c.id, c.parent_id, c.nom_' . $_SESSION['session_langue'] . ' as nom, parent_id
				FROM peel_categories c
				WHERE c.etat="1" AND nom_' . $_SESSION['session_langue'] . '!="" AND ' . get_filter_site_cond('categories', 'c') . '
				ORDER BY c.position ASC, nom ASC';
			$qid = query($sql);
			while ($result = fetch_assoc($qid)) {
				$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
				$item_name_array[$result['id']] = $result['nom'];
				if(empty($result['parent_id'])) {
					$result['nom'] = String::strtoupper($result['nom']);
				}
				if(!empty($GLOBALS['site_parameters']['insert_product_categories_in_menu'])) {
					// Il faut définir par la suite cat_XX dans le paramètre main_menu_items_if_available depuis le back office pour que la catégorie s'affiche.
					$GLOBALS['main_menu_items']["cat_" . $result['id']] = array(get_product_category_url($result['id'], $result['nom']) => $result['nom']);
					$GLOBALS['categories_level'][$result['parent_id']][] = "cat_" . $result['id'];
				}
			}
			$submenu_global['catalog'] = '';
			if(!empty($item_name_array) && !empty($GLOBALS['site_parameters']['product_categories_depth_in_menu'])) {
				$submenu_global['catalog'] .= '<ul class="sousMenu dropdown-menu" role="menu">'.get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $selected_item, 'categories', 'left', vn($GLOBALS['site_parameters']['product_categories_depth_in_menu']), $item_max_length, 'list', $indent, null).'</ul>';
				foreach($item_name_array as $catid => $nom) {
					if (!empty($all_parents_with_ordered_direct_sons_array[$catid])) {
						$submenu_global["cat_".$catid] = '<ul class="sousMenu dropdown-menu" role="menu">';
						$submenu_global["cat_".$catid] .= get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, $catid, 0, $selected_item, 'categories', 'left', vn($GLOBALS['site_parameters']['product_categories_depth_in_menu']), 50, 'list', $indent, null);
						if($avoid_links_when_hover) {
							// Si le lien n'est pas généré dans le menu pour une catégorie mère, il faut ajouter ce lien dans le sous menu pour que la catégorie mère reste accessible
							// On utilise array_shift( array_keys() ) car array_keys()[0] n'est disponible qu'à partir de la version 5.4 de PHP
							// array_shift() extrait la première valeur d'un tableau et la retourne, en raccourcissant le tableau d'un élément, et en déplaçant tous les éléments vers le bas. Toutes les clés numériques seront modifiées pour commencer à zéro.
							$submenu_global["cat_".$catid] .= '<li><a href="'. array_shift(array_keys($GLOBALS['main_menu_items']["cat_".$catid])) . '">' . array_shift(array_values($GLOBALS['main_menu_items']["cat_".$catid])) . '</a></li>';
						}
						$submenu_global["cat_".$catid] .='</ul>';
					}
				}
			}
		} elseif (check_if_module_active('sauvegarde_recherche') && est_identifie()) {
			$submenu_global['catalog'] .= '<ul class="sousMenu dropdown-menu" role="menu">'.display_ads_search_list($_SESSION['session_utilisateur']['id_utilisateur'], true).'</ul>';
		}

		// Lien d'articles dans le menu
		if(!empty($GLOBALS['site_parameters']['insert_article_in_menu'])) {
			$sql = 'SELECT a.id, a.on_reseller, a.titre_' . $_SESSION['session_langue'] . ' as nom
				FROM peel_articles a
				WHERE a.etat = "1" AND a.technical_code NOT IN ("other", "iphone_content") AND a.position>=0 AND ' . get_filter_site_cond('articles', 'a') . '
				ORDER BY a.position ASC, nom ASC';
			$qid = query($sql);

			while ($result = fetch_assoc($qid)) {
				if ((!a_priv("admin_product") && !a_priv("reve")) && $result['on_reseller'] == 1) {
					continue;
				} else {
					// Il faut définir par la suite art_XX dans le paramètre main_menu_items_if_available depuis le back office pour que la rubrique s'affiche.
					$GLOBALS['main_menu_items']["art_".$result['id']] = array(get_content_url($result['id']) => $result['nom']);
				}
			}
		}

		// Préparation du contenu du menu contenu rédactionnel
		if(!empty($GLOBALS['site_parameters']['content_categories_depth_in_menu'])) {
			// Au moins 1 niveau d'arborescence.
			$selected_item = intval(vn($_GET['rubid']));
			unset($all_parents_with_ordered_direct_sons_array);
			unset($item_name_array);
			$sql = 'SELECT r.id, r.parent_id, r.nom_' . $_SESSION['session_langue'] . ' as nom
				FROM peel_rubriques r
				WHERE r.etat = "1" AND r.technical_code NOT IN ("other", "iphone_content") AND r.position>=0  AND ' . get_filter_site_cond('rubriques', 'r') . '
				ORDER BY r.position ASC, nom ASC';
			$qid = query($sql);

			while ($result = fetch_assoc($qid)) {
				$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
				$item_name_array[$result['id']] = $result['nom'];
				if(!empty($GLOBALS['site_parameters']['insert_article_categories_in_menu'])) {
					// Il faut définir par la suite rub_XX dans le paramètre main_menu_items_if_available depuis le back office pour que la rubrique s'affiche.
					$GLOBALS['main_menu_items']["rub_".$result['id']] = array(get_content_category_url($result['id'], $result['nom']) => $result['nom']);
					$GLOBALS['rubriques_level'][$result['parent_id']][] = "rub_" . $result['id'];
				}
			}
			$submenu_global['content'] = '';
			if(!empty($item_name_array)) {
				$submenu_global['content'] .= '<ul class="sousMenu dropdown-menu" role="menu">'.get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $selected_item, 'rubriques', 'left', $GLOBALS['site_parameters']['content_categories_depth_in_menu'], $item_max_length, 'list', $indent, null).'</ul>';
				foreach($item_name_array as $rubid => $nom) {
					if (!empty($GLOBALS['site_parameters']['insert_content_articles_in_menu'])) {
						// Affichage des articles de la rubrique dans le sous menu
						$sql = "SELECT a.id, a.titre_" . $_SESSION['session_langue'] . " as nom
							FROM peel_rubriques r
							INNER JOIN peel_articles_rubriques pc ON r.id = pc.rubrique_id
							INNER JOIN peel_articles a ON a.id = pc.article_id AND " . get_filter_site_cond('articles', 'a') . "
							WHERE r.id ='" . intval($rubid) . "' AND r.technical_code NOT IN ('other', 'iphone_content') AND " . get_filter_site_cond('rubriques', 'r') . "";
						$qid = query($sql);
						$submenu_global["rub_".$rubid] = '<ul class="sousMenu dropdown-menu" role="menu">';
						if($avoid_links_when_hover) {
							// Si le lien n'est pas généré dans le menu pour une rubrique mère, il faut ajouter ce lien dans le sous menu pour que la rubrique mère reste accessible
							$submenu_global["rub_".$rubid] .= '<li><a href="'. array_shift(array_keys($GLOBALS['main_menu_items']["rub_".$rubid])) . '">' . array_shift(array_values($GLOBALS['main_menu_items']["rub_".$rubid])) . '</a></li>';
						}
						while ($result = fetch_assoc($qid)) {
							$submenu_global["rub_".$rubid] .= '<li><a href="'. get_content_url($result['id']) . '">' . $result['nom'] . '</a></li>';
						}
						$submenu_global["rub_".$rubid] .= '</ul>';
					} elseif (!empty($all_parents_with_ordered_direct_sons_array[$rubid])) {
						// Affichage des sous-rubriques dans le sous-menu
						$submenu_global["rub_".$rubid] = '
							<ul class="sousMenu dropdown-menu" role="menu">' . get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, $rubid, 0, $selected_item, 'rubriques', 'left', $GLOBALS['site_parameters']['content_categories_depth_in_menu'], 50, 'list', $indent, null) . vb($article) .'</ul>';
					}
				}
			}
		}
		if(in_array('brand',$GLOBALS['site_parameters']['main_menu_items_if_available'])) {
			$sql = "SELECT id, image, description_" . $_SESSION['session_langue'] . " AS description, nom_" . $_SESSION['session_langue'] . " AS nom
				FROM peel_marques
				WHERE etat=1 AND  " . get_filter_site_cond('marques') . "
				ORDER BY position ASC, nom ASC";
			$query = query($sql);

			$submenu_global['brand'] = '<ul class="sousMenu dropdown-menu" role="menu">';
			while ($result = fetch_assoc($query)) {
				$submenu_global['brand'] .= '<li><a href="'.$GLOBALS['wwwroot'].'/achat/marque.php?id='.$result['id'].'">
				'.$result['nom'].'
				</a></li>';	
			}
			$submenu_global['brand'] .= '</ul>';
		}
		if(!empty($GLOBALS['site_parameters']['insert_article_in_menu'])) {
			// Lien d'articles (/lire/article_details.php) dans le menu principal. Il faut définir art_XX dans le paramètre main_menu_items_if_available depuis le back office pour que le lien de l'article s'affiche dans les onglets du menu principal
			$sql = 'SELECT a.id, a.titre_' . $_SESSION['session_langue'] . ' as nom
				FROM peel_articles a
				WHERE a.etat = "1" AND a.technical_code NOT IN ("other", "iphone_content") AND a.position>=0 AND ' . get_filter_site_cond('articles', 'a') . '
				ORDER BY a.position ASC, nom ASC';
			$qid = query($sql);
			while ($result = fetch_assoc($qid)) {
				// Charge tous les articles dans main_menu_items, qui sera filtré avec ce que contient main_menu_items_if_available quelques lignes en dessous
				$GLOBALS['main_menu_items']["art_".$result['id']] = array(get_content_url($result['id']) => $result['nom']);
			}
		}

		if(in_array('brand',$GLOBALS['site_parameters']['main_menu_items_if_available'])) {
		// On vérifie si "brand" fait parti des onglet à afficher au menu : administrable en back office
		// Récupération des infos liées aux marques
			$sql = "SELECT id, image, description_" . $_SESSION['session_langue'] . " AS description, nom_" . $_SESSION['session_langue'] . " AS nom
				FROM peel_marques
				WHERE etat=1 " . get_filter_site_cond('marques') . "
				ORDER BY position ASC, nom ASC";
			$query = query($sql);
			// On crée le sous menu des marques
			$submenu_global['brand'] = '<ul class="sousMenu dropdown-menu" role="menu">';
			while ($result = fetch_assoc($query)) {
				$submenu_global['brand'] .= '<li><a href="'.$GLOBALS['wwwroot'].'/achat/marque.php?id='.$result['id'].'">
				'.$result['nom'].'
				</a></li>';	
			}
			$submenu_global['brand'] .= '</ul>';
		}
		if(!empty($GLOBALS['site_parameters']['main_menu_items_if_available']) && is_array($GLOBALS['site_parameters']['main_menu_items_if_available'])) {
			$temp_main_menu_items = array();
			if(in_array('cat_*', $GLOBALS['site_parameters']['main_menu_items_if_available']) && !empty($GLOBALS['categories_level'][0])) {
				$new_menu = array();
				foreach($GLOBALS['site_parameters']['main_menu_items_if_available'] as $this_key => $this_value) {
					if($this_value=='cat_*') {
						foreach($GLOBALS['categories_level'][0] as $this_imported_value) {
							if(String::strpos($this_value, 'cat_') === 0) {
								$new_menu[] = $this_imported_value;
							}
						}
					} elseif(!is_numeric($this_key)) {
						$new_menu[$this_key] = $this_value;
					} else {
						$new_menu[] = $this_value;
					}
				}
				$GLOBALS['site_parameters']['main_menu_items_if_available'] = $new_menu;
			}
			if(in_array('rub_*', $GLOBALS['site_parameters']['main_menu_items_if_available']) && !empty($GLOBALS['rubriques_level'][0])) {
				$new_menu = array();
				foreach($GLOBALS['site_parameters']['main_menu_items_if_available'] as $this_key => $this_value) {
					if($this_value=='rub_*') {
						foreach($GLOBALS['rubriques_level'][0] as $this_imported_value) {
							if(String::strpos($this_value, 'rub_') === 0) {
								$new_menu[] = $this_imported_value;
							}
						}
					} elseif(!is_numeric($this_key)) {
						$new_menu[$this_key] = $this_value;
					} else {
						$new_menu[] = $this_value;
					}
				}
				$GLOBALS['site_parameters']['main_menu_items_if_available'] = $new_menu;
			}
			$custom = 0;
			foreach($GLOBALS['site_parameters']['main_menu_items_if_available'] as $this_key => $this_value) {
				if(isset($GLOBALS['main_menu_items'][$this_value])) {
					// Filtre des entrées principales du menu à partir de la valeur de main_menu_items_if_available défini en back office
					$temp_main_menu_items[$this_value] = $GLOBALS['main_menu_items'][$this_value];
				} elseif(String::strpos($this_value, 'menu_html_') === 0) {
					$this_output = affiche_contenu_html($this_value, true);
					if(!empty($this_output)) {
						$temp_main_menu_items[$this_value] = '
				<ul class="sousMenu dropdown-menu" role="menu">
					<li>
						<div class="yamm-content">
							' . affiche_contenu_html($this_value, true) . '
						</div>
					</li>
				</ul>';
					}
				} elseif(!is_numeric($this_key)) {
					$custom++;
					if(String::substr($this_value, 0, 4) == 'STR_' && isset($GLOBALS[$this_value])) {
						$this_text = $GLOBALS[$this_value];
					} else {
						$this_text = $this_value;
					}
					if(String::strpos($this_key, '//') !== false) {
						$this_url = $this_key;
					} else {
						if(String::strpos($this_key, '/') !== 0) {
							$this_url = $GLOBALS['wwwroot'] . '/' . $this_key;
						} else {
							$this_url = $GLOBALS['wwwroot'] . $this_key;
						}
					}
					$temp_main_menu_items['custom'.$custom] = array($this_url => $this_text);
				}
				if(!empty($GLOBALS['site_parameters']['menu_custom_submenus']) && is_array($GLOBALS['site_parameters']['menu_custom_submenus']) && isset($GLOBALS['site_parameters']['menu_custom_submenus'][$this_value])) {
					// Gestion des sous menus paramétrés
					// Modèle de menu_custom_submenus : technical_code_main_menu_items1 => "technical_code_pour_le_sousmenu1, technical_code_pour_le_sousmenu2 ,technical_code_pour_le_sousmenu3", technical_code_main_menu_items2 => "technical_code_pour_le_sousmenu1, technical_code_pour_le_sousmenu2, technical_code_pour_le_sousmenu3"
					$temp_menu_items = array();
					foreach($GLOBALS['site_parameters']['menu_custom_submenus'] as $this_main_menu_items_value => $this_menu_items_value) {
						if(isset($GLOBALS['main_menu_items'][$this_main_menu_items_value])) {
							// Un sous menu est configuré pour ce menu principal
							foreach(explode(',',str_replace(' ', '', $this_menu_items_value)) as $this_submenu) {
								// Le différents liens du sous menu sont séparés par des virgules
								if(!empty($GLOBALS['site_parameters']['menu_custom_urls'][$this_submenu]) && !empty($GLOBALS['site_parameters']['menu_custom_titles'][$this_submenu])) {
									// modèle pour menu_custom_urls :  technical_code_main_menu_items1 => "http://www.url1.fr", technical_code_main_menu_items2 => "http://www.url2.fr"
									// modèle pour menu_custom_titles : technical_code_main_menu_items1 => "STR_XXXXX", technical_code_main_menu_items2 => "STR_XXX"
									$temp_menu_items[$this_main_menu_items_value][$GLOBALS['site_parameters']['menu_custom_urls'][$this_submenu]] = $GLOBALS[$GLOBALS['site_parameters']['menu_custom_titles'][$this_submenu]];
								}
							}
						}
					}
					$GLOBALS['menu_items'] = $temp_menu_items;
				}
			}
			$GLOBALS['main_menu_items'] = $temp_main_menu_items;
		}
		// Génération du menu
		$current_url = get_current_url(false);
		$current_url_full = get_current_url(true);
		$menu = array();
		foreach($GLOBALS['main_menu_items'] as $this_main_item => $this_main_array) {
			// On ne prend que les menus demandés pour l'affichage
			foreach ($this_main_array as $this_main_url => $this_main_title) {
				if($avoid_links_when_hover && (!empty($GLOBALS['menu_items'][$this_main_item]) || !empty($submenu_global[$this_main_item]))) {
					// On retire le lien sur le menu principal pour le mettre sur un élément en haut du menu si pas existant
					if(!empty($GLOBALS['menu_items'][$this_main_item]) && empty($GLOBALS['menu_items'][$this_main_item][$this_main_url])) {
						$GLOBALS['menu_items'][$this_main_item] = array_merge(array($this_main_url => $this_main_title), $GLOBALS['main_menu_items'][$this_main_item]);
					}
					$this_main_url = '#';
				}
				if($this_main_item == 'other' && ((defined("IN_CATALOGUE") && !empty($_GET['catid'])) || (defined("IN_RUBRIQUE") && !empty($_GET['rubid'])))) {
					// On ne sélectionne pas le menu "Autre" si on est dans une catégorie de produits ou une rubrique de contenu
					$current_menu = false;
					$full_match = false;
				} else {
					$current_menu = (!empty($GLOBALS['menu_items'][$this_main_item][$current_url_full]));
					$full_match = true;
					if ($current_menu === false && ((defined('IN_RUBRIQUE_ARTICLE') && $this_main_item === 'rub_' . intval(vn($_GET['rubid']))) || (defined('IN_CATALOGUE_PRODUIT') && $this_main_item === 'cat_' . intval(vn($_GET['catid']))))) {
						$current_menu = true;
						$full_match = true;
					}
					if ($current_menu === false && !empty($GLOBALS['menu_items'][$this_main_item])) {
						$current_menu = (!empty($GLOBALS['menu_items'][$this_main_item][$current_url]));
						$full_match = false;
					}
				}
				 $tmp_menu_item = array('name' => $this_main_item,
						'id' => 'menu_' . String::substr(md5($this_main_item.'_'.$this_main_title.'_'.$this_main_url), 0, 4),
						'label' => $this_main_title,
						'href' => (!empty($this_main_url) && !is_numeric($this_main_url)) ? ($this_main_url != get_current_url(true) || !empty($_POST)? $this_main_url : '#') : false,
						'selected' => ($current_menu !== false || !empty($this_main_array[$current_url]) || !empty($this_main_array[$current_url_full]) || String::strpos(vb($submenu_global[$this_main_item]),'class="minus active"')!==false),
						'submenu_global' => vb($submenu_global[$this_main_item]),
						'class' => $this_main_item,
						'submenu' => array()
					);
				if (!empty($GLOBALS['menu_items'][$this_main_item])) {
					foreach ($GLOBALS['menu_items'][$this_main_item] as $this_url => $this_title) {
						$tmp_menu_item['submenu'][] = array('label' => $this_title,
								'href' => (!empty($this_url) && !is_numeric($this_url)) ? $this_url : false,
								'selected' => (($current_url == $this_url && !$full_match) || $current_url_full == $this_url)
							);
					}
				}
				$menu[] = $tmp_menu_item;
			}
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('menu.tpl');
		$tpl->assign('menu', $menu);
		$tpl->assign('site', $GLOBALS['site']);
		$tpl->assign('affiche_contenu_html_menu', affiche_contenu_html('affiche_contenu_html_menu', true));
		return $tpl->fetch();
	}
}

if (!function_exists('affiche_flags')) {
	/**
	 * affiche_flags()
	 *
	 * @param boolean $return_mode
	 * @param string $forced_destination_url
	 * @param boolean $display_names
	 * @param array $langs_array
	 * @uses $GLOBALS['tplEngine']
	 * @return flags view
	 */
	function affiche_flags($return_mode = false, $forced_destination_url = null, $display_names = false, $langs_array = array(), $big_flags = false, $force_width = null)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('flags.tpl');
		$data = array();
		if (count($langs_array) > 1 || !empty($forced_destination_url)) {
			foreach ($langs_array as $this_lang) {
				if(!empty($forced_destination_url)){
					$url = $forced_destination_url . '?langue=' . $this_lang;
				} else {
					$url = get_current_url_in_other_language($this_lang);
				}
				if($big_flags && !empty($GLOBALS['lang_flags_big'][$this_lang])) {
					$this_flag = $GLOBALS['lang_flags_big'][$this_lang];
				} else {
					$this_flag = $GLOBALS['lang_flags'][$this_lang];
				}
				if(String::strpos($this_flag, '/') === false) {
					$this_flag = '/lib/flag/' . $this_flag;
				}
				if(String::substr($this_flag, 0, 1) == '/' && String::substr($this_flag, 0, 2) != '//') {
					$this_flag = (defined('IN_PEEL_ADMIN') ? $GLOBALS['wwwroot_in_admin'] : $GLOBALS['wwwroot']) . $this_flag;
				}
				$data[] = array('lang' => $this_lang,
					'lang_name' => (!empty($GLOBALS['lang_names'][$this_lang])?$GLOBALS['lang_names'][$this_lang]:$this_lang),
					'href' => $url,
					'src' => $this_flag,
					'selected' => ($_SESSION['session_langue'] == $this_lang && empty($forced_destination_url)),
					'flag_css_class' => (($_SESSION['session_langue'] == $this_lang && empty($forced_destination_url)) ? "flag_selected":"flag_not_selected")
					);
			}
		}
		$tpl->assign('data', $data);
		$tpl->assign('display_names', $display_names);
		$tpl->assign('force_width', $force_width);
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('getFlag')) {
	/**
	 * getFlag()
	 *
	 * @param string $country_code
	 * @param string $country_name
	 * @return
	 */
	function getFlag($country_code, $country_name)
	{
		if (!empty($country_code)) {
			$flag = '<img src="' . $GLOBALS['wwwroot'] . '/lib/flag/' . strtolower($country_code) . '.gif" width="18" height="12" alt="" title="' . String::str_form_value($country_name) . '" /> ';
		} else {
			$flag = '';
		}
		return $flag;
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
		if(strip_tags($longtext)==$longtext) {
			$longtext = '<p>' . $longtext . '</p>';
		}
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
	 * @param string $add_general_css_js_files
	 * @return
	 */
	function output_light_html_page($body, $title = '', $additional_header = null, $convert_to_encoding = null, $full_head_section_text = null, $onload = null, $add_general_css_js_files = true)
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
		$tpl->assign('body', $body);
		$tpl->assign('full_head_section_text', $full_head_section_text);
		if (empty($full_head_section_text) && $add_general_css_js_files) {
			if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
				$GLOBALS['js_files'][-100] = $GLOBALS['wwwroot'] . '/lib/js/jquery.js';
				$GLOBALS['css_files'][] = $GLOBALS['wwwroot'] . '/lib/css/bootstrap.css';
				$GLOBALS['js_files'][] = $GLOBALS['wwwroot'] . '/lib/js/bootstrap.js';
			}
			foreach (get_array_from_string($GLOBALS['site_parameters']['css']) as $this_css_filename) {
				$this_css_file = trim($this_css_filename);
				if (String::strpos($this_css_file, '//') !== false) {
					$GLOBALS['css_files'][] = $this_css_file;
				} elseif(file_exists($GLOBALS['repertoire_modele'] . '/css/' . $this_css_file)) {
					$GLOBALS['css_files'][] = $GLOBALS['repertoire_css'] . '/' . $this_css_file;  // .'?'.time()
				}
			}
			if(!empty($GLOBALS['css_files'])) {
				ksort($GLOBALS['css_files']);
				$tpl->assign('css_files', array_unique($GLOBALS['css_files']));
			}
			$tpl->assign('js_output', get_javascript_output(!empty($GLOBALS['site_parameters']['load_javascript_async']), !empty($GLOBALS['site_parameters']['minify_js'])));
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
				WHERE c.etat = '1' AND c.alpha_" . $_SESSION['session_langue'] . "='" . nohtml_real_escape_string($value) . "' AND " . get_filter_site_cond('categories', 'c') . "";
			$resCat = query($sqlCat);
			while ($cat = fetch_assoc($resCat)) {
				$sqlCount = "SELECT COUNT(*) AS this_count
					FROM peel_produits_categories pc
					INNER JOIN peel_produits p ON p.id = pc.produit_id AND " . get_filter_site_cond('produits','p') . "
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
		if (!is_writable($GLOBALS['dirroot'] . "/lib/templateEngines/smarty/compile")) {
			echo sprintf($GLOBALS['STR_ADMIN_INSTALL_DIRECTORY_NOK'], "/lib/templateEngines/smarty/compile");
			die();
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('delete_installation_folder.tpl');
			$tpl->assign('installation_links', affiche_flags(true, $GLOBALS['wwwroot'] . '/installation/index.php', true, $GLOBALS['admin_lang_codes'], true, null));
			$tpl->assign('STR_INSTALLATION_PROCEDURE', $GLOBALS['STR_INSTALLATION_PROCEDURE']);
			$tpl->assign('STR_INSTALLATION_DELETE_EXPLAIN', $GLOBALS['STR_INSTALLATION_DELETE_EXPLAIN']);
			$tpl->assign('STR_INSTALLATION_DELETE_EXPLAIN_ALTERNATIVE', $GLOBALS['STR_INSTALLATION_DELETE_EXPLAIN_ALTERNATIVE']);
			$tpl->assign('STR_INSTALLATION_DELETED_LINK', $GLOBALS['STR_INSTALLATION_DELETED_LINK']);
			$tpl->assign('PEEL_VERSION', PEEL_VERSION);
			$body = $tpl->fetch();
		}
		$additional_header = '
		<style>
			h1 { font-size: 24px; color: #337733; }
			h2 { font-size: 20px; }
			.launch_installation, .center { text-align:center; margin-top: 10px; }
			.flag_not_selected { width: 167px; height:167px; }
			.full_flag { display: inline-block; font-size: 18px; font-weight: bold; color: #000000; padding:15px; }
			.full_flag a { text-decoration: none; color: #000000 !important;}
			.full_flag a:hover { text-decoration: underline;}
			.footer { padding-top:10px; margin-top: 10px;}
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
	function print_access_plan()
	{
		$output = '';
		$sql = "SELECT map_tag AS map_tag, text_" . $_SESSION['session_langue'] . " AS texte
			FROM peel_access_map
			WHERE " . get_filter_site_cond('access_map');
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
		$tpl->assign('form_token', get_form_token_input('get_simple_newsletter', true));
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
	function newsletter_validation($frm, $form_error_object)
	{
		if (empty($frm)) {
			return false;
		}
		$message = "";
		$tpl = $GLOBALS['tplEngine']->createTemplate('newsletter_validation.tpl');
		$tpl->assign('header', $GLOBALS['STR_NEWSLETTER_TITLE']);
		if (!$form_error_object->count()) {
			// MAJ du compte client s'il existe
			$q_count_users = query("SELECT COUNT(id_utilisateur) AS nb_users
				FROM peel_utilisateurs
				WHERE email = '" . word_real_escape_string($frm['email']) . "' AND " . get_filter_site_cond('utilisateurs') . "");
			$r_count_users = fetch_assoc($q_count_users);
			if ($r_count_users['nb_users'] > 0) {
				query("UPDATE peel_utilisateurs
					SET newsletter = '1'
					WHERE email='" . nohtml_real_escape_string($frm['email']) . "' AND " . get_filter_site_cond('utilisateurs') . "");
			} else {
				$frm['newsletter'] = 1;
				$frm['priv'] = 'newsletter';
				insere_utilisateur($frm);
			}
			$custom_template_tags['EMAIL'] = $frm['email'];
			// Envoi d'un email confirmant l'inscription à la newsletter
			send_email($frm['email'], '', '', 'inscription_newsletter', $custom_template_tags, null, $GLOBALS['support']);
			$message .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_REQUEST_OK'] . ' ' . $GLOBALS['STR_SEE_YOU_SOON'] . ' ' . $GLOBALS['wwwroot']))->fetch();
		} else {
			foreach ($form_error_object->error as $key => $error) {
				$message .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $error))->fetch();
			}
		}
		$tpl->assign('message', $message);
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
		if (!isset($_SESSION['session_site_country']) && !empty($_SERVER['REMOTE_ADDR']) && file_exists($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php')) {
			include($GLOBALS['dirroot'] . '/modules/geoip/class/geoIP.php');
			$geoIP = new geoIP();
			$_SESSION['session_site_country'] = $geoIP->geoIPCountryIDByAddr($_SERVER['REMOTE_ADDR']);
			$geoIP->geoIPClose();
			unset($geoIP);
		}
		$sql_cond_array[] = 'etat="1"';
		$sql_cond_array[] = get_filter_site_cond('html');
		$sql_cond_array[] = '(lang="' . $_SESSION['session_langue'] . '" OR lang="")';
		if (!empty($_SESSION['session_site_country'])) {
			$sql_cond_array[] = '(emplacement="' . nohtml_real_escape_string($place) . '|country='.$_SESSION['session_site_country'].'" OR emplacement="' . nohtml_real_escape_string($place) . '")';
		} else {
			$sql_cond_array[] = 'emplacement="' . nohtml_real_escape_string($place) . '"';
		}
		$sql = 'SELECT *
			FROM peel_html
			WHERE ' . implode(' AND ', $sql_cond_array) . '
			ORDER BY LENGTH(emplacement) DESC, a_timestamp DESC';
		$query = query($sql);
		while ($obj = fetch_object($query)) {
			if(!empty($last_emplacement) && String::strlen($obj->emplacement) < String::strlen($last_emplacement)) {
				// On a déjà chargé du contenu spécifique pour un pays, on ne veut pas prendre de contenu non-spécifique à ce pays, donc on s'arrête
				break;
			}
			// On préserve le HTML mais on corrige les & isolés
			$output .= template_tags_replace(String::htmlentities(String::html_entity_decode_if_needed($obj->contenu_html), ENT_COMPAT, GENERAL_ENCODING, false, true), array(), false, 'html');
			$last_emplacement = $obj->emplacement;
		}
		correct_output($output, false, 'html', $_SESSION['session_langue']);
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
	 * @param array $share_item_array tableau de service code addthis : http://www.addthis.com/services/list
	 * @param mixed $text Texte spécifique à placer lors de l'utilisation de la fonction. Inactif par défaut.
	 * @return
	 */
	function addthis_buttons($share_item_array = null, $text = null)
	{
		static $jquery_called;
		if (empty($share_item_array)) {
			if (!empty($GLOBALS['site_parameters']['addthis_share_item_array'])) {
				// Configuration administrable
				$share_item_array = $GLOBALS['site_parameters']['addthis_share_item_array'];
			} else {
				// Configuration par défaut
				$share_item_array = array('twitter', 'google_plusone_share', 'facebook', 'pinterest_share', 'more');
			}
		}
		$output = '
	<table class="addthis_32x32_style">
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
		</table>';
		if(empty($jquery_called)) {
			// On ne veut pas minifier le fichier addthis avec le reste pour éviter de rajouter un délai de génération du minified ou poser un problème si addthis ne répond pas
			// Par ailleurs ça permet à la page d'accueil d'un site d'aller plus vite si elle n'a pas addthis
			// NB : On appelle addthis en https pour éviter problème d'alerte de sécurité du navigateur si le site PEEL est en https.
			$GLOBALS['js_files_nominify'][] = 'https://s7.addthis.com/js/300/addthis_widget.js';
			$jquery_called = true;
		}
		return $output;
	}
}

if (!function_exists('get_user_picture')) {
	/**
	 *
	 * @param array $priv 	privilège des utilisateurs à afficher.
	 * @param array $nb		nombre d'utilisateurs à afficher.
	 * @param boolean $rand	utilisateur tiré aléatoirement
	 * @return
	 */
	function get_user_picture($priv, $nb = 4, $rand = true) {
		$output_array = array();
		$sql_cond = '';
		if (empty($priv) || intval($nb) == 0) {
			// Erreur de paramétrage
			return false;
		}
		if ($priv != '*') {
			// * pour tous les utilisateurs 
			$sql_cond .= ' AND priv="'.nohtml_real_escape_string($priv).'"';
		}
		$sql = 'SELECT logo
			FROM peel_utilisateurs
			WHERE logo !="" AND ' . get_filter_site_cond('utilisateurs') . ' ' . $sql_cond;
		if ($rand) {
			$sql .= '
			ORDER BY RAND()';
		}
		$sql .= '
			LIMIT 0,' . intval($nb);
		$q = query($sql);
		while($result = fetch_assoc($q)) {
			$output_array[] = $result['logo'];
		}
		return $output_array;
	}
}

if (!function_exists('get_diaporama')) {
	/**
	 * get_diaporama()
	 *
	 * @return
	 */
	function get_diaporama($mode, $id)
	{
		if(empty($id)) {
			// Erreur de paramétrage
			return false;
		}
		if ($mode == 'content_category') {
			$id_field = 'id_rubrique';
		} else {
			// Erreur de paramétrage
			return false;
		}
		$nb_colonnes = 3;
		$j = 0;
		$diapo = array();
		$q = query("SELECT `image`
			FROM `peel_diaporama`
			WHERE `" . word_real_escape_string($id_field) . "`=" . intval($id));
		$total_img = num_rows($q);
		while($img_diapo = fetch_assoc($q)) {
			$tmpdiapo['j'] =  $j;
			$tmpdiapo['image'] =  $GLOBALS['repertoire_upload'] .  '/' . $img_diapo["image"];
			$tmpdiapo['thumbs'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($img_diapo["image"], 175, 275, "fit");
			$tmpdiapo['is_row'] = ($j % $nb_colonnes == 0);
			$j++;
	
			if ($j % $nb_colonnes == 0 || $j == $total_img) {
				$tmpdiapo['empty_cells'] = 0;
				if($j > $nb_colonnes) {
					// On a déjà une ligne pleine => il faut compléter la dernière ligne pour du XTML bien structuré
					while ($j % $nb_colonnes != 0) {
						$tmpdiapo['empty_cells']++;
						$j++;
					}
				} else {
					// Une seule ligne => on ajuste le nombre de colonnes à la réalité de ce qu'on a affiché
					$nb_colonnes = $j;
				}
			}
			$diapo[] = $tmpdiapo;
		}
		if(count($diapo)) {
			$GLOBALS['load_nyromodal']=true;
			$tpl = $GLOBALS['tplEngine']->createTemplate('diaporama.tpl');
			$tpl->assign('diaporama', $diapo);
			return $tpl->fetch();
		} else {
			return null;
		}
	}
}

/*
 *
 * @param string $search
 * @param string $match
 * @param string $real_search
 * @param array $frm
 * @return
 */
function get_search_form($frm = null, $search=null, $match = null, $real_search = false, $display = 'full') {
	$tpl_f = $GLOBALS['tplEngine']->createTemplate('search_form.tpl');
	$tpl_f->assign('STR_SEARCH_PRODUCT', $GLOBALS['STR_SEARCH_PRODUCT']);
	$tpl_f->assign('action', get_search_url());
	$tpl_f->assign('value', $search);
	if ($display == 'full') {
		$tpl_f->assign('match', $match);
	}
	$tpl_f->assign('display', $display);
	$tpl_f->assign('search', String::strtoupper($real_search));
	$tpl_f->assign('prix_min', vb($frm['prix_min']));
	$tpl_f->assign('prix_max', vb($frm['prix_max']));
	$tpl_f->assign('date_flash', vb($frm['date_flash']));
	$tpl_f->assign('STR_DATE', $GLOBALS['STR_DATE']);
	$tpl_f->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl_f->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
	$tpl_f->assign('STR_ENTER_KEY', $GLOBALS['STR_ENTER_KEY']);
	$tpl_f->assign('STR_SEARCH_ALL_WORDS', $GLOBALS['STR_SEARCH_ALL_WORDS']);
	$tpl_f->assign('STR_SEARCH_ANY_WORDS', $GLOBALS['STR_SEARCH_ANY_WORDS']);
	$tpl_f->assign('STR_SEARCH_EXACT_SENTENCE', $GLOBALS['STR_SEARCH_EXACT_SENTENCE']);
	$tpl_f->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
	$tpl_f->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
	$tpl_f->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
	$tpl_f->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl_f->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
	$tpl_f->assign('is_advanced_search_active', check_if_module_active('search'));
	$tpl_f->assign('is_annonce_module_active', check_if_module_active('annonces'));
	if (check_if_module_active('sauvegarde_recherche')) {
		$tpl_f->assign('display_save_search_button', display_save_search_button($frm));
	}
	if (check_if_module_active('search')) {
		if (!check_if_module_active('annonces') || (check_if_module_active('annonces') && !empty($GLOBALS['site_parameters']['search_in_product_and_ads']))) {
			// tableau regroupant les caractéristiques des attributs fixes dans peel
			$search_attribute_tab = array('marque' => array('table' => 'marques', 'join' => 'produits', 'join_id' => 'id_marque', 'label' => $GLOBALS['STR_BRAND_LB']),
				'couleur' => array('table' => 'couleurs', 'join' => 'produits_couleurs', 'join_id' => 'couleur_id', 'label' => $GLOBALS['STR_COLOR_LB']),
				'taille' => array('table' => 'tailles', 'join' => 'produits_tailles', 'join_id' => 'taille_id', 'label' => $GLOBALS['STR_TALL_LB'])
			);

			// on construit la liste des catégories
			if(empty($GLOBALS['select_categorie'])) {
				$GLOBALS['parent_categorie'] = vn($frm["categorie"]); // catégorie sélectionnée
				$GLOBALS['select_categorie'] = '';
				$GLOBALS['select_categorie'] = get_categories_output(null, 'categories', vn($_GET["categorie"]), 'option', '&nbsp;&nbsp;', null);
			}
			$tpl_f->assign('select_categorie', $GLOBALS['select_categorie']);
			$tpl_f->assign('STR_CAT_LB', $GLOBALS['STR_CAT_LB']);

			$tpl_f_select_attributes = array();
			// affichage des attributs fixes
			foreach ($search_attribute_tab as $index => $attribute) {
				$tpl_f_select_attributes[] = display_select_attribute($index, $attribute);
			}
			$tpl_f->assign('select_attributes', $tpl_f_select_attributes);
			// affichage des attributs variables
			if (!empty($GLOBALS['site_parameters']['custom_attribut_displayed_in_search_form']) && $display == 'module_products') {
				$technical_code = $GLOBALS['site_parameters']['custom_attribut_displayed_in_search_form'];
			}
			$tpl_f->assign('custom_attribute', display_custom_attribute(vb($frm['custom_attribut']), vb($technical_code), true));
		}
		if(check_if_module_active('annonces') && $display != 'module_products') {
			$tpl_f_cat_ann_opts = array();
			$ad_categories = get_ad_categories();
			foreach ($ad_categories as $this_category_id => $this_category_name) {
				$tpl_f_cat_ann_opts[] = array('value' => $this_category_id,
					'issel' => (!empty($_GET['cat_select']) && $_GET['cat_select'] == $this_category_id),
					'name' => $this_category_name
					);
			}
			$tpl_f->assign('cat_ann_opts', $tpl_f_cat_ann_opts);
			// Définit le type d'annonce détail,gros, etc.. Cependant il y a deux filtres sur destockplus, d'où le test sur les deux get
			if (!empty($frm['cat_statut_detail'])) {
				$type_detail = $frm['cat_statut_detail'];
			} elseif (!empty($frm['cat_detail'])) {
				$type_detail = $frm['cat_detail'];
			}
			if (!empty($frm['cat_statut_detail'])) {
				$type_statut = $frm['cat_statut_detail'];
			} elseif (!empty($frm['cat_statut'])) {
				$type_statut = $frm['cat_statut'];
			}
			$tpl_f->assign('cat_detail', vb($type_detail));
			$tpl_f->assign('cat_statut', vb($type_statut));
			if (count($GLOBALS['lang_names'])>1) {
				$tpl_f->assign('ad_lang_select', get_lang_ads_choose(false));
			}
			$tpl_f->assign('city_zip', vb($frm['city_zip']));
			$tpl_f->assign('country', get_country_select_options(vb($frm['country']), null, 'name', false, null, false, null));
			$query_continent = query("SELECT id, name_" . $_SESSION['session_langue'] . " AS name
				FROM peel_continents
				WHERE " . get_filter_site_cond('continents') . "
				ORDER BY name_".$_SESSION['session_langue']);
			$tpl_continent_inps = array();
			while ($continent = fetch_assoc($query_continent)) {
				$tpl_continent_inps[] = array('value' => $continent['id'],
					'issel' => !empty($frm['continent']) && is_array($frm['continent']) && in_array($continent['id'], $frm['continent']),
					'name' => $continent['name']
					);
			}
			$tpl_f->assign('continent_inputs', $tpl_continent_inps);
			if (check_if_module_active('maps')) {
				if(empty($_SESSION['session_latitude'])){
					$get_position_text = '<a href="javascript:load_position()">'.$GLOBALS['STR_GET_MY_POSITION'].'</a>';
					$get_position_script = getPositionFromUser('document.getElementById("load_position").innerHTML="<span style=\"color:green\">Position OK</span>"; document.getElementById("latitude").value=latitude; document.getElementById("longitude").value=longitude;');
				}else{
					// On connait déjà la position
					$get_position_text = '<span style="color:green">Position OK</span>';
					$get_position_script = '';
				}
				$tpl_f->assign('near_position', sprintf($GLOBALS['STR_NEAR_POSITION_INPUT'], '<input type="text" id="near_position" name="near_position" size="6" value="' . String::str_form_value(vb($frm['near_position'])) . '" />').' <input type="hidden" id="latitude" name="latitude" value="'.vb($_SESSION['session_latitude']).'" /><input type="hidden" id="longitude" name="longitude" value="'.vb($_SESSION['session_longitude']).'" /> - <span id="load_position">'.$get_position_text.'</span>'. $get_position_script);
			}
			$tpl_f->assign('ads_contain_lot_sizes', $GLOBALS['site_parameters']['ads_contain_lot_sizes']);
			$tpl_f->assign('STR_TYPE', $GLOBALS['STR_TYPE']);
			$tpl_f->assign('STR_MODULE_ANNONCES_AD_CATEGORY', $GLOBALS['STR_MODULE_ANNONCES_AD_CATEGORY']);
			$tpl_f->assign('STR_MODULE_ANNONCES_OFFER_GROS', $GLOBALS['STR_MODULE_ANNONCES_OFFER_GROS']);
			$tpl_f->assign('STR_MODULE_ANNONCES_OFFER_DEMIGROS', $GLOBALS['STR_MODULE_ANNONCES_OFFER_DEMIGROS']);
			$tpl_f->assign('STR_MODULE_ANNONCES_OFFER_DETAIL', $GLOBALS['STR_MODULE_ANNONCES_OFFER_DETAIL']);
			$tpl_f->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
			$tpl_f->assign('STR_MODULE_ANNONCES_ALT_VERIFIED_ADS', $GLOBALS['STR_MODULE_ANNONCES_ALT_VERIFIED_ADS']);
			$tpl_f->assign('STR_MODULE_ANNONCES_NOT_VERIFIED_ADS', $GLOBALS['STR_MODULE_ANNONCES_NOT_VERIFIED_ADS']);
			$tpl_f->assign('STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD']);
		}
	}
	return $tpl_f->fetch();
}
