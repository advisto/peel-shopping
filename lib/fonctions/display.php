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
// $Id: display.php 55637 2017-12-29 18:35:08Z gboussin $
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
		// PRIORITE 1 : Mix entre $GLOBALS['meta_title'] et table spécifique des metas peel_metas :
		// PRIORITE 2 : Récupération des métas définis en BDD pour des éléments précis
		if (!empty($_GET['id']) && defined('IN_LEXIQUE')) {
			$sql_Meta = 'SELECT word_' . $_SESSION['session_langue'] . ' AS nom, definition_' . $_SESSION['session_langue'] . ' AS description, meta_title_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_definition_' . $_SESSION['session_langue'] . ' AS meta_desc 
				FROM peel_lexique 
				WHERE id = "' . intval($_GET['id']) . '"  AND '. get_filter_site_cond('lexique');
		} elseif (!empty($_GET['catid']) && (defined('IN_CATALOGUE_ANNONCE') || defined('IN_CATALOGUE_ANNONCE_DETAILS'))) {
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, meta_desc_' . vb($_SESSION['session_langue']) . ' AS meta_desc, image_' . $_SESSION['session_langue'] . ' AS image 
				FROM peel_categories_annonces 
				WHERE id = "' . intval($_GET['catid']) . '" AND ' . get_filter_site_cond('categories_annonces') . '';
		} elseif (!empty($_GET['id']) && defined('IN_SEARCH_BRAND')) { 
			// Si on est dans une marque
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, image 
				FROM peel_marques 
				WHERE id = "' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('marques');
		} elseif (!empty($_GET['catid']) && empty($_GET['id'])) { 
			// Si on est dans une catégorie
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom, description_' . $_SESSION['session_langue'] . ' AS description, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, image_' . $_SESSION['session_langue'] . ' AS image 
				FROM peel_categories 
				WHERE id = "' . intval($_GET['catid']) . '" AND ' . get_filter_site_cond('categories') . '';
		} elseif (!empty($_GET['rubid']) && empty($_GET['id'])) { 
			// Si on est dans une rubrique
			$sql_Meta = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom, description_'.$_SESSION['session_langue'].' AS description, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, image 
				FROM peel_rubriques 
				WHERE id = "' . intval($_GET['rubid']) . '" AND ' . get_filter_site_cond('rubriques') . '';
		} elseif (!empty($_GET['id']) && defined('IN_CATALOGUE_PRODUIT')) {
			// Si on est dans une fiche produit
			$display_facebook_tag = 'product';
			$product_fields = array('nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']).' AS nom', 'meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre', 'meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc', 'meta_key_' . $_SESSION['session_langue'] . ' AS meta_key', 'image1 AS image', 'description_'.(!empty($GLOBALS['site_parameters']['product_description_forced_lang'])?$GLOBALS['site_parameters']['product_description_forced_lang']:$_SESSION['session_langue']).' AS description');
			$product_fields = get_table_field_names('peel_produits', null, false, $product_fields);
			$sql_Meta = "SELECT " . implode(', ', $product_fields) . "
				FROM peel_produits 
				WHERE id = '" . intval($_GET['id']) . "' AND " . get_filter_site_cond('produits');
		} elseif (!empty($_GET['id']) && defined('IN_RUBRIQUE_ARTICLE')) {
			// Si on est dans un article de contenu
			$display_facebook_tag = 'article';
			$sql_Meta = 'SELECT titre_' . $_SESSION['session_langue'] . ' AS nom, CONCAT(chapo_' . $_SESSION['session_langue'] . ', texte_' . $_SESSION['session_langue'] . ') AS description, meta_titre_' . $_SESSION['session_langue'] . ' AS meta_titre, meta_desc_' . $_SESSION['session_langue'] . ' AS meta_desc, meta_key_' . $_SESSION['session_langue'] . ' AS meta_key, image1 AS image 
				FROM peel_articles 
				WHERE id = "' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('articles') . '';
		}
		$sql_Meta_hook_result = call_module_hook('meta_sql_get', array(), 'unique');
		if(!empty($sql_Meta_hook_result)) {
			// Le hook a priorité sur le reste
			$sql_Meta = $sql_Meta_hook_result; 
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
			WHERE (technical_code="'.real_escape_string(get_current_url(false)).'" OR technical_code="'.real_escape_string(get_current_url(false, true)).'") AND ' . get_filter_site_cond('meta');
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
			WHERE ("'.real_escape_string(get_current_url(true)).'" LIKE CONCAT(technical_code, "%") OR "'.real_escape_string(get_current_url(true, true)).'" LIKE CONCAT(technical_code, "%")) AND ' . get_filter_site_cond('meta') . '
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
		$this_title = StringMb::html_entity_decode($this_title);
		if (!empty($GLOBALS['meta_description'])) {
			$this_description = str_replace(array('    ', '   ', '  ', "\t"), ' ', trim(StringMb::strip_tags($GLOBALS['meta_description']))) . ' ';
		} else {
			$this_description = '';
		}
		if (StringMb::strlen($this_description) < 100) {
			if (!empty($m['meta_desc'])) {
				$this_description .= $m['meta_desc'];
			} elseif (!empty($m_peel_meta['meta_desc_' . $_SESSION['session_langue']])) {
				$this_description .= $m_peel_meta['meta_desc_' . $_SESSION['session_langue']];
			} elseif (!empty($GLOBALS['strSpecificMeta']['Description'][$page_name])) {
				$this_description .= $GLOBALS['strSpecificMeta']['Description'][$page_name];
			} else {
				if (!empty($m['description'])) {
					// Description disponible, on la prend
					$this_description .= $m['description'];
				} else {
					// Sinon on prendra une metadescription d'ordre général
					$this_description .= $m_default['meta_desc_' . $_SESSION['session_langue']];
				}
				if (!empty($m['nom'])) {
					$this_description = $m['nom'] . '. ' . $this_description;
				} elseif(!empty($GLOBALS['meta_title'])) {
					$this_description = $GLOBALS['meta_title'] . '. ' . $this_description;
				} elseif(!empty($GLOBALS['DOC_TITLE'])) {
					$this_description = $GLOBALS['DOC_TITLE'] . '. ' . $this_description;
				}
			}
		}
		if (!empty($this_title) && $this_title == StringMb::strtoupper($this_title) && StringMb::strlen($this_title) > 25) {
			// Titre tout en majuscule et pas juste un ou deux mots => on passe en minuscule car sinon mauvais pour moteurs de recherche
			$this_title = StringMb::strtolower($this_title);
		}
		if (!empty($GLOBALS['STR_TITLE_SUFFIX']) && empty($GLOBALS['site_parameters']['title_suffix_disable'])) {
			foreach(explode(' ', $GLOBALS['STR_TITLE_SUFFIX']) as $this_word) {
				if ((StringMb::strlen($this_word)<=2 || (StringMb::strpos(StringMb::strtolower($this_title), StringMb::strtolower($this_word)) === false && empty($GLOBALS['site_parameters']['title_suffix_skip_keyword_in_double']))) && StringMb::strlen($this_title . ' ' . $this_word) < 80) {
					$this_title .= ' ' .$this_word;
				}
			}
		}
		if(!empty($m) && !empty($m['meta_key']) && StringMb::strlen($m['meta_key']) > 40 &&  StringMb::strlen($m['meta_key']) < 200) {
			// On respecte la balise méta keywords définie dans le contenu de la page en base de données, car elle parait de taille cohérente
			$this_keywords = $m['meta_key'];
		} elseif((!empty($meta_by_exact_url) || !empty($no_meta_by_page_content)) && !empty($m_peel_meta) && !empty($m_peel_meta['meta_key_' . $_SESSION['session_langue']]) && StringMb::strlen($m_peel_meta['meta_key_' . $_SESSION['session_langue']]) > 40 &&  StringMb::strlen($m_peel_meta['meta_key_' . $_SESSION['session_langue']]) < 150) {
			// On respecte la balise méta keywords définie par URL exacte en base de données, ou par technical code si on est dans une page sans définition possible directe des métas
			// car elle parait de taille cohérente
			$this_keywords = $m_peel_meta['meta_key_' . $_SESSION['session_langue']];
		} else {
			$this_keywords = $this_title . ' ' . vb($GLOBALS['meta_keywords']) . ' ' . vb($m['nom']) . ' ' . vb($m['meta_key']) . ' '. vb($GLOBALS['strSpecificMeta']['Keywords'][$page_name]) . ' ' . vb($m_peel_meta['meta_key_' . $_SESSION['session_langue']]);
			if (StringMb::strlen($this_keywords) < 70) {
				$this_keywords .= ' ' . $this_description;
			}
			if (StringMb::strlen($this_keywords) < 100) {
				$this_keywords .= ' ' . $m_default['meta_key_' . $_SESSION['session_langue']];
			}
			if (!empty($this_keywords)) {
				// Nettoyage des mots clés - on n'en garde que 12 maximum (conseillé : max 8)
				$temp_array = array_unique(explode(',', trim(StringMb::strip_tags(str_replace(array("\r", "\n", "\t", '!', '?', '(', ')', '.', '#', ':', ';', '&nbsp;', '+', '-', " ", ".", '"', "'"), ',', StringMb::html_entity_decode(str_replace(array('&nbsp;'), ',', StringMb::strtolower($this_keywords))))))));
				foreach($temp_array as $this_key => $this_value) {
					if (StringMb::strlen($this_value) < 4 || (StringMb::strlen($this_value) < 5 && $this_key > 6) ) {
						unset($temp_array[$this_key]);
					}
				}
				$this_keywords = implode(', ', array_slice($temp_array, 0, 12));
			}
		}
		// Nettoyage final des mots clés
		$this_keywords = str_replace(', ,', ',', implode(', ', array_unique(explode(',', trim(StringMb::strip_tags(str_replace(array("\r", "\n", "\t", '!', '?', '(', ')', '.', '#', ':', ';', '&nbsp;', '+', '-', " ", ".", '"', "'"), ',', StringMb::html_entity_decode(str_replace(array('&nbsp;'), ',', StringMb::strtolower($this_keywords))))))))));
		$GLOBALS['meta_description_html_uncut'] = $this_description;
		if (!empty($this_description)) {
			$this_description = StringMb::str_shorten(str_replace(array('    ', '   ', '  ', ' .', '....'), array(' ', ' ', ' ', '.', '.'), trim(StringMb::strip_tags(StringMb::html_entity_decode_if_needed(str_replace(array("\r", "\n", "<br>", "<br />", "</p>"), ' ', $this_description))))), 190, '', '...', 170);
			if ($this_description == StringMb::strtoupper($this_description)) {
				$this_description = StringMb::strtolower($this_description);
			}
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('meta.tpl');
		$tpl->assign('charset', GENERAL_ENCODING);
		$tpl->assign('title', StringMb::ucfirst(StringMb::str_shorten(trim(StringMb::strip_tags(StringMb::html_entity_decode_if_needed(str_replace(array("\r", "\n"), '', $this_title)))), 80, '', '', 65)));
		$tpl->assign('keywords', $this_keywords);
		$tpl->assign('site', $GLOBALS['site']);
		if($_SESSION['session_langue'] == 'fr') {
			$tpl->assign('generator', 'https://www.peel.fr/');
		} else {
			$tpl->assign('generator', 'http://www.peel-shopping.com/');
		}
		$tpl->assign('description', StringMb::ucfirst($this_description));
		$tpl->assign('content_language', $_SESSION['session_langue']);
		if ((!empty($display_facebook_tag) || !empty($GLOBALS['site_parameters']['facebook_meta_force_all_pages'])) && check_if_module_active('facebook')) {
			if(empty($GLOBALS['facebook_image'])) {
				$GLOBALS['facebook_image'] = vb($m['image']);
			}
			$display_facebook_tag_array = array('meta_titre' => $this_title, 'meta_desc' => $this_description, 'image' => $GLOBALS['facebook_image'], 'type' => (!empty($display_facebook_tag) && is_string($display_facebook_tag)?$display_facebook_tag:'website'), 'image_size' => vb($GLOBALS['facebook_image_size']));
			$tpl->assign('facebook_tag', display_facebook_tag($display_facebook_tag_array));
		}
		if (!empty($GLOBALS['site_parameters']['ups_viewPortResponsive']) && defined('IN_STEP1')) {
			$tpl->assign('specific_meta', '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,user-scalable=no" />');
		} elseif(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
			$tpl->assign('specific_meta', '<meta name="viewport" content="width=device-width, initial-scale=1.0" />');
		}
		if((!empty($_GET['update']) && $_GET['update'] == 1) || (!empty($_GET['update_thumbs']) && $_GET['update_thumbs'] == 1) || !empty($GLOBALS['robots_noindex']) || !empty($_GET['follow']) || !empty($_GET['nofollow'])) {
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
				$ariane['href'] = get_url('/');
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
				$other['href'] = get_url('/achat/nouveautes.php');
			} elseif (defined('IN_PROMOTIONS')) {
				$other['txt'] = $GLOBALS['STR_PROMOTIONS'];
				$other['href'] = get_url('/achat/promotions.php');
			} elseif (defined('IN_SPECIAL')) {
				$other['txt'] = $GLOBALS['STR_SPECIAL'];
				$other['href'] = get_url('/achat/special.php');
			} elseif (defined('IN_TOP')) {
				$other['txt'] = $GLOBALS['STR_TOP'];
				$other['href'] = get_url('/achat/top.php');
			} elseif (defined('IN_FLASH')) {
				$other['txt'] = $GLOBALS['STR_FLASH'];
				$other['href'] = get_url('/modules/flash/flash.php');
			} elseif (defined('IN_CHEQUE_CADEAU')) {
				$other['txt'] = $GLOBALS['STR_CHEQUE_CADEAU'];
				$other['href'] = get_url('/modules/gift_check/cheques.php');
			} elseif (defined('IN_ORDER_HISTORY')) {
				$other['txt'] = $GLOBALS['STR_ORDER_HISTORY'];
				$other['href'] = get_url('/achat/historique_commandes.php');
			} elseif (defined('IN_COMPTE')) {
				$other['txt'] = $GLOBALS['STR_COMPTE'];
				$other['href'] = get_account_url(false, false);
			} elseif (defined('IN_CONTACT')) {
				$other['txt'] = $GLOBALS['STR_CONTACT'];
				$other['href'] = get_contact_url(false, false);
			} elseif (defined('IN_CONTACT_US')) {
				$other['txt'] = $GLOBALS['STR_CONTACT_US'];
				$other['href'] = get_url('/contacts.php');
			} elseif (defined('IN_SEARCH')) {
				$other['txt'] = $GLOBALS['STR_SEARCH'];
				$other['href'] = get_url('search');
			} elseif (defined('IN_SITEMAP')) {
				$other['txt'] = $GLOBALS['STR_SITEMAP'];
				$other['href'] = get_url('sitemap');
			} elseif (defined('IN_CGV')) {
				$other['txt'] = $GLOBALS['STR_CGV'];
				$other['href'] = get_cgv_url(false);
			} elseif (defined('IN_FAQ')) {
				$other['txt'] = $GLOBALS['STR_FAQ_TITLE'];
				$other['href'] = get_url('/modules/faq/faq.php');
			} elseif (defined('IN_INFO_LEGALE')) {
				$other['txt'] = $GLOBALS['STR_LEGAL_INFORMATION'];
				$other['href'] = get_url('legal');
			} elseif (defined('IN_CONDITION_PARRAIN')) {
				$other['txt'] = $GLOBALS['STR_CONDITION_PARRAIN'];
				$other['href'] = get_url('/modules/parrainage/conditions.php');
			} elseif (defined('IN_PARRAIN_ENTETE')) {
				$other['txt'] = $GLOBALS['STR_PARRAIN_ENTETE'];
				$other['href'] = get_url('/modules/parrainage/parrain.php');
			} elseif (defined('IN_CONDITION_AFFILI')) {
				$other['txt'] = $GLOBALS['STR_CONDITION_AFFILI'];
				$other['href'] = get_url('/modules/affiliation/conditions.php');
			} elseif (defined('IN_AFFILIATE')) {
				$other['txt'] = $GLOBALS['STR_AFFILIATE'];
				$other['href'] = get_url('/modules/affiliation/affiliate.php');
			} elseif (defined('IN_RETAILER')) {
				$other['txt'] = $GLOBALS['STR_RETAILER'];
				$other['href'] = get_url('/modules/reseller/retailer.php');
			} elseif (defined('IN_CHANGE_PASSWORD')) {
				$other['txt'] = $GLOBALS['STR_CHANGE_PASSWORD'];
				$other['href'] = get_url('/utilisateurs/change_mot_passe.php');
			} elseif (defined('IN_CHANGE_PARAMS')) {
				$other['txt'] = $GLOBALS['STR_CHANGE_PARAMS'];
				$other['href'] = get_url('/utilisateurs/change_params.php');
			} elseif (defined('IN_GET_PASSWORD')) {
				$other['txt'] = $GLOBALS['STR_GET_PASSWORD'];
				$other['href'] = get_url('/utilisateurs/oubli_mot_passe.php');
			} elseif (defined('IN_REGISTER')) {
				$other['txt'] = $GLOBALS['STR_REGISTER'];
				$other['href'] = get_account_register_url(false, false);
			} elseif (defined('IN_ACCES_ACCOUNT')) {
				$other['txt'] = $GLOBALS['STR_ACCES_ACCOUNT'];
				$other['href'] = get_url('membre');
			} elseif (defined('IN_TELL_FRIEND')) {
				$other['txt'] = $GLOBALS['STR_TELL_FRIEND'];
				$other['href'] = get_tell_friends_url(false);
			} elseif (defined('IN_TOUS_LES_AVIS')) {
				$other['txt'] = $GLOBALS['STR_TOUS_LES_AVIS'];
			} elseif (defined('IN_DONNEZ_AVIS')) {
				$other['txt'] = $GLOBALS['STR_DONNEZ_AVIS'];
			} elseif (defined('IN_CADDIE')) {
				$other['txt'] = $GLOBALS['STR_CADDIE'];
				$other['href'] = get_url('caddie_affichage');
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
				$other['href'] = get_url('/modules/pensebete/voir.php');
			} elseif (defined('IN_DOWNLOAD')) {
				$other['txt'] = $GLOBALS['STR_YOUR_ORDER_DOWNLOAD'];
				$other['href'] = get_url('/modules/download/telecharger.php', array('id' => vb($_GET['id']) , 'key' => vb($_GET['key'])));
			} elseif (defined('IN_FORUM')) {
				$other['txt'] = $GLOBALS['STR_FORUM'];
				$other['href'] = get_url('/modules/forum/index.php');
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
	function get_brand_link_html($id_marque = null, $unused_params = false, $show_all_brands_link = false, $location = null, $return_mode = 'html')
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
			$this_url = get_url('/achat/marque.php', array('id' => $brand->id));
			$links[] = array('href' => $this_url,
				'value' => $brand->marque,
				'image' => $brand->image,
				'is_current' => (get_current_url(true) == $this_url));
		}
		if($show_all_brands_link) {
			$this_url = get_url('/achat/marque.php');
			$links[] = array('href' => $this_url,
				'value' => $GLOBALS['STR_ALL_BRAND'],
				'image' => '',
				'is_current' => (get_current_url(true) == $this_url));
		}
		if ($return_mode == 'html') {
			$tpl = $GLOBALS['tplEngine']->createTemplate('brand_link_html.tpl');
			$tpl->assign('as_list', empty($id_marque));
			$tpl->assign('links', $links);
			$tpl->assign('location', $location);
			$output .= $tpl->fetch();
			return $output;
		} else {
			return $links;
		}
	}
}

if (!function_exists('get_distributors_link_html')) {
	/**
	 * Affiche la marque du produit
	 *
	 * @param integer $id_marque
	 * @return
	 */
	function get_distributors_link_html($id_marque = null)
	{
		$output = '';
		$sql = '
			SELECT ps.tel, ps.siteweb, ps.site_country, ps.logo, ps.email
			FROM peel_societe ps
			WHERE FIND_IN_SET(' . vb($id_marque) . ', ps.id_marques) AND ' . get_filter_site_cond('societe', 'ps') . '
			LIMIT 1';

		$query = query($sql);
		$links = array();
		while ($brand = fetch_object($query)) {
			$links[] = array('href' => $brand->siteweb,
				'logo' => $brand->logo,
				'phone' => $brand->tel,
				'siteweb' => $brand->siteweb,
				'email' => $brand->email,
				'site_country' => $brand->site_country);
		}
		
		return $links;
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
				'display_brand' => true
				);
			$tmpData['admin_content'] = a_priv('admin_content');
			if ($tmpData['admin_content']) {
				$tmpData['admin_link'] = array('href' => $GLOBALS['administrer_url'] . '/marques.php?mode=modif&id=' . $brand_object->id, 'name' => $GLOBALS['STR_MODIFY_BRAND']);
			}
			$tmpData['small_width'] = $GLOBALS['site_parameters']['small_width'];
			$tmpData['has_image'] = false;
			if (!empty($brand_object->image)) {
				$thumb_file = thumbs($brand_object->image, $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit');
				if(!empty($thumb_file)) {
					$tmpData['has_image'] = true;
					$tmpData['image'] = array('href' => ($show_links_to_details ? get_url('/achat/marque.php', array('id' => $brand_object->id)) : ''),
							'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . $thumb_file
						);
				}
			}
			$tmpData['href'] = ($show_links_to_details ? get_url('/achat/marque.php', array('id' => $brand_object->id)) : '');
			$tmpData['nb_produits_txt'] = $brand_products['nb_produits'] . ' ' . $GLOBALS['STR_ARTICLES'];
			if($brand_products['nb_produits']<=1 && StringMb::strtolower(StringMb::substr($tmpData['nb_produits_txt'], -1)) == 's') {
				$tmpData['nb_produits_txt'] = StringMb::substr($tmpData['nb_produits_txt'], 0, StringMb::strlen($tmpData['nb_produits_txt'])-1);
			}
			$tmpData['description'] = $brand_object->description;
			correct_output($tmpData['description'], true, 'html', $_SESSION['session_langue']);
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
	 * @param integer $text_max_length
	 * @param integer $max_depth_allowed
	 * @param integer $columns_if_related_display_mode
	 * @param integer $parent_id
	 * @param integer $exclude_id
	 * @return
	 */
	function get_categories_output($location = null, $mode = 'categories', $selected_item = null, $display_mode = 'option', $add_indent = '&nbsp;&nbsp;', $input_name = null, $technical_code = null, $use_admin_rights = false, $text_max_length = 30, $max_depth_allowed = null, $columns_if_related_display_mode = null, $parent_id=null, $exclude_id=null)
	{
		$output = '';
		$item_name_array = array();
		if(empty($selected_item)) {
			// use_admin_rights => Converti en chaine de caractère. strval retourne 1 si true, et vide '' si false ou null.
			$cache_id = $location . '_' . $mode . '_' .  $display_mode . '_' . $add_indent .  '_' .  $_SESSION['session_langue'] . '_' . $GLOBALS['site_id'] . '_' . vb($input_name). '_'  . vb($input_name). '_' . vb($technical_code). '_' . strval($use_admin_rights) . '_' . vb($text_max_length).'_' . vb($max_depth_allowed).'_' . vn($columns_if_related_display_mode).'_' . vb($parent_id). '_' . vb($exclude_id). '_' . vn($_SESSION['session_admin_multisite']);
			$this_cache_object = new Cache($cache_id, array('group' => 'categories'));
			// Ce cache est valide 1h, et par ailleurs est effacé automatiquement lors de la MAJ ou l'insertion de catégories via l'administration
			if ($this_cache_object->testTime(3600, true)) {
				return $this_cache_object->get();
			}
		}

		if($max_depth_allowed === null && !empty($GLOBALS['site_parameters']['categories_max_depth_allowed_default_array']) && !empty($GLOBALS['site_parameters']['categories_max_depth_allowed_default_array'][$mode]) && !defined('IN_PEEL_ADMIN')) {
			// Si on est en front-office, on limite la profondeur de l'arborescence affichée
			$max_depth_allowed = $GLOBALS['site_parameters']['categories_max_depth_allowed_default_array'][$mode];
		}
		if($mode == 'categories') {
			// Dans l'admin, il faut pouvoir voir les catégories qui n'ont pas de nom. Cela arrive lorque les information d'une catégorie ne sont pas renseigné pour toutes les langues,dans ce cas l'id entre crochet sera affiché en BO à la place du nom.
			$sql = 'SELECT c.id, c.parent_id, c.nom_' . $_SESSION['session_langue'] . ' AS nom, etat
				FROM peel_categories c
				WHERE '.(defined('IN_PEEL_ADMIN') && $use_admin_rights?'1':'c.etat="1"').(!defined('IN_PEEL_ADMIN')?' AND nom_' . $_SESSION['session_langue'] . '!="" ':'').' '.(!empty($technical_code)?' AND technical_code ="' . nohtml_real_escape_string($technical_code) . '"':'').' AND ' . get_filter_site_cond('categories', 'c', $use_admin_rights) . ' '.(!empty($exclude_id)?' AND c.id !="' . intval($exclude_id) . '"':'').' '.($parent_id!==null?' AND parent_id ="' . intval($parent_id) . '"':'').'
				ORDER BY c.position ASC, nom ASC';
		} elseif($mode == 'rubriques') {
			$sql = 'SELECT r.id, r.parent_id, r.nom_' . $_SESSION['session_langue'] . ' AS nom, etat
				FROM peel_rubriques r
				WHERE '.(defined('IN_PEEL_ADMIN') && $use_admin_rights?'1':'r.etat="1"').' AND r.nom_' . $_SESSION['session_langue'] . '!="" AND r.technical_code NOT IN ("other", "iphone_content") AND r.position>=0 AND ' . get_filter_site_cond('rubriques', 'r', $use_admin_rights) . '
				ORDER BY r.position ASC, nom ASC';
			if(!empty($GLOBALS['site_parameters']['content_categories_nb_limit_footer'])){
				$sql .=' LIMIT '.$GLOBALS['site_parameters']['content_categories_nb_limit_footer'];
			}
		} elseif($mode == 'categories_annonces') {
			$sql = 'SELECT c.id, c.parent_id, c.nom_' . $_SESSION['session_langue'] . ' AS nom, etat
				FROM peel_categories_annonces c
				WHERE '.(defined('IN_PEEL_ADMIN') && $use_admin_rights?'1':'c.etat="1"').' AND '.(!defined('IN_PEEL_ADMIN')?'c.nom_' . $_SESSION['session_langue'] . '!=""':'1') . ' AND ' . get_filter_site_cond('categories_annonces', 'c', $use_admin_rights) . '
				ORDER BY c.position ASC, '.word_real_escape_string($GLOBALS['site_parameters']['ads_categories_order_by']).' ASC';
		} elseif($mode == 'partenaires_categories') {
			$sql = 'SELECT r.id, r.parent_id, r.nom_' . $_SESSION['session_langue'] . ' AS nom, etat
				FROM peel_partenaires_categories r
				WHERE '.(defined('IN_PEEL_ADMIN') && $use_admin_rights?'1':'r.etat="1"').'
				ORDER BY r.position ASC, nom ASC';
		} else {
			return null;
		}
		$qid = query($sql);
		while ($result = fetch_assoc($qid)) {
			if(isset($result['etat']) && empty($result['etat'])) {
				$result['nom'] .= ' ['.$GLOBALS['STR_ADMIN_DEACTIVATED'].']';
			}
			$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
			$item_name_array[$result['id']] = (!empty($result['nom'])?$result['nom']:'['.$result['id'].']');
		}
		$GLOBALS['categories_output_last_count'] = count($item_name_array);
		if (!empty($all_parents_with_ordered_direct_sons_array)) {
			$class = null;
			$columns = null;
			if($display_mode == 'columns_linear') {
				$class = 'col-sm-4 col-md-3';
			} elseif($display_mode == 'columns') {
				$columns = $columns_if_related_display_mode; 
			}
			$output .= get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $selected_item, $mode, $location, $max_depth_allowed, $text_max_length, $display_mode, $add_indent, $input_name, $class, $columns);
			if($display_mode == 'columns_linear') {
				$output = '<div class="row"><ul>' . $output . '</ul></div>';
			}
		}
		if(!empty($this_cache_object)) {
			$this_cache_object->save($output);
			unset($this_cache_object);
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
	 * @param string $display_mode
	 * @param string $add_indent 
	 * @param string $input_name
	 * @param string $class
	 * @param string $columns
	 * @return
	 */
	function get_recursive_items_display(&$all_parents_with_ordered_direct_sons_array, &$item_name_array, $this_parent, $this_parent_depth, $selected_item = null, $mode = 'categories', $location = null, $max_depth_allowed = null, $item_max_length = 25, $display_mode = 'option', $add_indent = '&nbsp;&nbsp;', $input_name = null, $class = null, $columns = null)
	{
		static $tpl;
		$output = '';
		if (!empty($all_parents_with_ordered_direct_sons_array[$this_parent])) {
			if(empty($tpl)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('recursive_items_display.tpl');
			}
			$tpl->assign('sons_ico_src', get_url('/images/right.gif'));
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
					if (vn($GLOBALS['site_parameters']['display_nb_product']) == 1 || (!empty($GLOBALS['site_parameters']['display_not_empty_categories_only']) && !defined('IN_PEEL_ADMIN'))) {
						unset($nb);
						if ($mode == 'categories_annonces') {
							$nb = calcul_nbannonces_parcat($this_item, $all_parents_with_ordered_direct_sons_array);
						} elseif ($mode == 'categories') {
							$nb = calcul_nbprod_parcat($this_item, $all_parents_with_ordered_direct_sons_array);
						}
						if(isset($nb)) {
							if (vn($GLOBALS['site_parameters']['display_nb_product']) == 1) {
								$tplItem['nb'] = $nb;
							}
							if(!empty($GLOBALS['site_parameters']['display_not_empty_categories_only']) && !defined('IN_PEEL_ADMIN') && empty($nb)) {
								continue;
							}
						}
					}
					$tplItem['name'] = $item_name_array[$this_item];
					if(!empty($GLOBALS['STR_ADMIN_DEACTIVATED']) && StringMb::strpos($item_name_array[$this_item], ' ['.$GLOBALS['STR_ADMIN_DEACTIVATED'].']') !== false) {
						$tplItem['disabled'] = true;
					}
				}

				if ($tplItem['has_sons']) {
					$tplItem['SONS'] = get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, $this_item, $this_depth, $selected_item, $mode, $location, $max_depth_allowed, $item_max_length, $display_mode, $add_indent, $input_name, $class);
				}
				if (function_exists('get_technical_code')) {
					$tplItem['technical_code'] = get_technical_code($this_item);
				}
				$tplItem['value'] = $this_item;
				$tplItem['id'] = 'menu_'.substr(md5(vb($tplItem['href']) . '_' . vb($tplItem['name'])),0,8);
				$tplItem['depth'] = $this_depth;
				$tplItem['indent'] = $indent;
				$tplItem['item_max_length'] = $item_max_length;
				$tplItem['class'] = $class;
				$tplItems[] = $tplItem;
			}
			$tpl->assign('items', $tplItems);
			$tpl->assign('columns', $columns);

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
			WHERE " . get_filter_site_cond('societe') . " AND id_marques = 0
			ORDER BY site_id DESC
			LIMIT 1");
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
	function print_rib($return_mode = false, $societe_type = null)
	{
		$output = '';
		$sql = "SELECT * 
			FROM peel_societe
			WHERE " . get_filter_site_cond('societe') . " AND societe_type = '".nohtml_real_escape_string($societe_type)."' AND id_marques = 0
			ORDER BY site_id DESC
			LIMIT 1";
		$query = query($sql);
		if ($ligne = fetch_object($query)) {
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
			$longtext = StringMb::nl2br_if_needed(StringMb::html_entity_decode_if_needed($cgv->texte));
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
			$longtext = StringMb::nl2br_if_needed(StringMb::html_entity_decode_if_needed($cgv->texte));
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
			$longtext = StringMb::nl2br_if_needed(StringMb::html_entity_decode_if_needed($contact_infos->texte));
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
						'image_src' => (!empty($art['image1']) ? thumbs($art['image1'], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit', null, null, true, true) : null),
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
		$est_identifie = est_identifie();
		if ($est_identifie) {
			$user_infos = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
			if (!empty($user_infos)) {
				// $user_infos sera rempli même si vide si on fait vb() de certains éléments => on stocke l'information maintenant de savoir si infos trouvées ou pas
				// $user_infos est vide si l'utilisateur est un utilisateur demo avec droits automatiques (validateur W3C par exemple, si la configuration l'autorise) 
				$user_found = true;
				if (!EmailOK($user_infos['email'], $user_infos['email_bounce'])) {
					// Email vide ou ayant généré une erreur
					$email_form = '';
					$domain = explode('@', vb($user_infos['email']));
					$email_explain = sprintf($GLOBALS['STR_EMAIL_BOUNCE_REPLACE'], vb($domain[1]), vb($user_infos['email_bounce']), vb($user_infos['email']));
					$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_IMPORTANT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': <a href="' . $GLOBALS['wwwroot'] . '/utilisateurs/change_params.php">' . $email_explain . '</a>'))->fetch();
				}
			}
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('compte.tpl');
		
		// On va utiliser sur cette page spécifiquement les icônes Font Awesome 
		$GLOBALS['css_files']['font-awesome'] = get_url('/lib/css/font-awesome.min.css');
		// On exclue ce fichier de la minification car usage ponctuel
		$GLOBALS['site_parameters']['minify_css_exclude_array'][] = 'font-awesome.min.css';
		
		$tpl->assign('compte', $GLOBALS['STR_COMPTE']);
		$tpl->assign('msg_support', $GLOBALS['STR_SUPPORT']);
		$tpl->assign('est_identifie', $est_identifie);
		if ($est_identifie) {
			$modules_data = array();
			$modules_data_group = array();
			$tpl->assign('number', $GLOBALS['STR_NUMBER']);
			$tpl->assign('code_client', vb($user_infos['code_client']));
			$tpl->assign('my_order', $GLOBALS['STR_MY_ORDER']);

			if(!empty($GLOBALS['site_parameters']['header_show_user_account_completion'])) {
				$tpl->assign('user_account_completion_text', '<a href="' . get_url('/utilisateurs/change_params.php') . '">'. sprintf($GLOBALS["STR_USER_ACCOUNT_COMPLETION"], user_account_completion($_SESSION['session_utilisateur'])) . '</a>');
			}
			$modules_data_group['other'] = array('header' => $GLOBALS["STR_OTHER"], 'position' => null);
			$modules_data_group['cart'] = array('header' => $GLOBALS['STR_MY_ORDER'], 'position' => 2);
			if (empty($GLOBALS['site_parameters']['order_history_for_user_disable']) || (!empty($GLOBALS['site_parameters']['order_history_for_user_disable']) && !empty($_SESSION['session_utilisateur']['access_history']))) {
				$modules_data['cart']['historique_commande'] = array('txt' => '<span class="fa fa-shopping-cart fa-5x"></span> <span class="fa fa-history fa-3x"></span><br />' . $GLOBALS['STR_ORDER_HISTORY'], 'href' => get_url('/achat/historique_commandes.php'));
				$modules_data['cart']['product_ordered_history'] = array('txt' => '<span class="fa fa-cart-arrow-down fa-5x"></span><br />' . $GLOBALS['STR_PRODUCTS_PURCHASED_LIST'], 'href' => get_url('/achat/historique_commandes.php', array('mode' => 'product_ordered_history')));
			}
			if (!empty($GLOBALS['site_parameters']['enable_create_product_in_front'])) {
				$modules_data_group['catalog'] = array('header' => $GLOBALS['STR_CATALOGUE'], 'position' => 6);
				$modules_data['catalog']['create_product'] = array('txt' => '' . $GLOBALS['STR_MODULE_CREATE_PRODUCT_IN_FRONT_OFFICE_CREATE_PRODUCT'], 'href' => get_content_url(null, null, null, null, false, false, 'display_product_form'));
			}
			$modules_data_group['account'] = array('header' => null, 'position' => 1);
			$modules_data['account']['change_params'] = array('txt' => '<span class="glyphicon glyphicon-user fa-5x"></span> <span class="fa fa-pencil fa-3x"></span><br />' . $GLOBALS['STR_CHANGE_PARAMS'], 'href' => get_url('/utilisateurs/change_params.php'));
			$modules_data['account']['change_mot_passe'] = array('txt' => '<span class="fa fa-key fa-5x"></span><br />' . $GLOBALS['STR_CHANGE_PASSWORD'], 'href' => get_url('/utilisateurs/change_mot_passe.php'));
			
			if(empty($GLOBALS['site_parameters']['user_multiple_addresses_disable'])) {
				$modules_data['account']['adresse'] = array('txt' => '<span class="fa fa-location-arrow fa-5x"></span><br />' . $GLOBALS['STR_ADDRESS_TEXT'], 'href' => get_url('/utilisateurs/adresse.php'));	
			}
			if (check_if_module_active('lemonway')) {
				$modules_data['account'][] = array('txt' => '<span class="fa fa-money fa-5x"></span><br />' . $GLOBALS['STR_MODULE_DREAMTAKEOFF_INNOV_MANAGE_WALLET'], 'href' => get_url('/modules/lemonway/account.php', array('site_id'=> 2), null, 2));
				$modules_data['account'][] = array('txt' => '<span class="fa fa-money fa-5x"></span><br />' . $GLOBALS['STR_MODULE_DREAMTAKEOFF_FUNDING_MANAGE_WALLET'], 'href' => get_url('/modules/lemonway/account.php', array('site_id'=> 3), null, 3));
			}
			// les codes promo utilisés
			$code_promo_query = query('SELECT code_promo, valeur_code_promo, percent_code_promo, devise
				FROM peel_commandes pc
				WHERE pc.id_utilisateur = "' . intval($_SESSION['session_utilisateur']['id_utilisateur']) . '" AND code_promo != ""  AND ' . get_filter_site_cond('commandes', 'pc'));
			if (num_rows($code_promo_query) > 0) {
				$cpu_data = array();
				while ($cp = fetch_assoc($code_promo_query)) {
					$cpu_data[] = array('code_promo' => $cp['code_promo'], 'discount_text' => get_discount_text($cp['valeur_code_promo'], $cp['percent_code_promo'], true, $cp['devise']));
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
			if (vn($user_infos['remise_percent']) > 0) {
				$tpl->assign('remise_percent', array('label' => $GLOBALS['STR_REMISE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => $user_infos['remise_percent']));
			}
			if (vn($user_infos['avoir']) > 0) {
				$tpl->assign('avoir', array('label' => $GLOBALS['STR_AVOIR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'], 'value' => fprix($user_infos['avoir'], true)));
			}
			$tpl->assign('logout', array('href' => get_url('sortie'), 'txt' => $GLOBALS['STR_LOGOUT']));

			if (a_priv('admin*', true)) {
				$tpl->assign('admin', array('href' => $GLOBALS['administrer_url'] . '/index.php', 'txt' => $GLOBALS['STR_ADMIN']));
			}
			if (function_exists('get_user_infos_resume_array') && !empty($_GET['display_user_infos_resume_array'])) {
				$tpl->assign('user_infos_resume_array',  get_user_infos_resume_array());
			}
			$hook_result = call_module_hook('account_show', $user_infos, 'array');
			$modules_data = array_merge_recursive_distinct($modules_data, vb($hook_result['modules_data'], array()));
			$modules_data_group = array_merge_recursive_distinct($modules_data_group, vb($hook_result['modules_data_group'], array()));
			if(!empty($modules_data)) {
				$position_if_null = 100000;
				foreach(array_keys($modules_data_group) as $this_group) {
					if(!empty($modules_data_group[$this_group]['disabled'])) {
						unset($modules_data_group[$this_group]);
						unset($modules_data[$this_group]);
						continue;
					}
					// Les positions par défaut définies en interne et par les modules peuvent être modifiées avec la variable de configuration account_show_modules_data_group_position
					if(!empty($GLOBALS['site_parameters']['account_show_modules_data_group_position'])) {
						if(isset($GLOBALS['site_parameters']['account_show_modules_data_group_position'][$this_group])) {
							$modules_data_group[$this_group]['position'] = $GLOBALS['site_parameters']['account_show_modules_data_group_position'][$this_group];
						}
					}
					if(!isset($modules_data_group[$this_group]['position'])) {
						// On ajoute des positions pour que le tri par usort ne change pas l'ordre d'éléments de position indéfinie
						$modules_data_group[$this_group]['position'] = $position_if_null++;
					}
				}
				foreach(array_keys($modules_data) as $this_group) {
					foreach(array_keys($modules_data[$this_group]) as $this_key) {
						if(!empty($modules_data[$this_group][$this_key]['disabled'])) {
							unset($modules_data[$this_group][$this_key]);
							continue;
						}
						if(!isset($modules_data[$this_group][$this_key]['position'])) {
							// On ajoute des positions pour que le tri par usort ne change pas l'ordre d'éléments de position indéfinie
							$modules_data[$this_group][$this_key]['position'] = $position_if_null++;
						}
					}
				}
				uasort($modules_data_group, 'data_position_sort');
				$temp = array();
				foreach(array_keys($modules_data_group) as $this_group) {
					if(!empty($modules_data[$this_group])) {
						$temp[$this_group] = $modules_data[$this_group];
						unset($modules_data[$this_group]);
					}
				}
				$modules_data = array_merge_recursive_distinct($temp, $modules_data);
				unset($temp);
				foreach(array_keys($modules_data) as $this_key) {
					usort($modules_data[$this_key], 'data_position_sort');
				}
				$tpl->assign('modules_data_group', $modules_data_group);
				$tpl->assign('modules_data', $modules_data);
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
			$tpl->assign('login_href', get_url('membre'));
			$tpl->assign('login', $GLOBALS['STR_LOGIN']);
		}

		$hook_result = call_module_hook('account_show_template_data', array(), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl->assign($this_key, $this_value);
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
		$tpl->assign('affichage_href', get_url('caddie_affichage'));
		$tpl->assign('count_products', $_SESSION['session_caddie']->count_products());
		$tpl->assign('products_txt', ($_SESSION['session_caddie']->count_products() > 1 ? str_replace(array('(', ')'), array(''), $GLOBALS['STR_CADDIE_OBJECTS_COUNT']) : str_replace(array('(s)', '(es)', '(n)', '(en)'), '', $GLOBALS['STR_CADDIE_OBJECTS_COUNT'])));

		$tpl->assign('has_details', $detailed);
		$tpl->assign('mini_caddy_total_display_disable', vn($GLOBALS['site_parameters']['mini_caddy_total_display_disable']));
		$tplProducts = array();
		foreach ($_SESSION['session_caddie']->articles as $numero_ligne => $product_id) {
			$tmpProd = array();
			$product_object = new Product($product_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
			$product_object->set_configuration($_SESSION['session_caddie']->couleurId[$numero_ligne], $_SESSION['session_caddie']->tailleId[$numero_ligne], check_if_module_active('reseller') && is_reseller());
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
				$product_picture = thumbs($display_picture, 75, 75, 'fit', null, null, true, true);
			} elseif(!empty($GLOBALS['site_parameters']['default_picture'])) {
				$product_picture = thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit', null, null, true, true);
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
		static $tpl;
		$mode = (!empty($rewrite_mame_mode)) ? clean_str($display_mode) : $display_mode;
		if(empty($tpl)){
			$tpl = $GLOBALS['tplEngine']->createTemplate('block.tpl');
		}
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
		$cache_id = 'menu_recherche_' . $display_mode . '_' . vn($_GET["categorie"], vn($_GET['catid'])) . '_' . vb($_GET['cat_statut_detail']) . '_' . vn($_GET["location"]) . '_' . (defined('IN_HOME')?'home':'other') . '_' . $_SESSION['session_langue'] . '_' . $GLOBALS['site_id'].'bas';
		$this_cache_object = new Cache($cache_id, array('group' => 'html_block'));
		if ($this_cache_object->testTime(5400, true)) {
			$output = $this_cache_object->get();
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('menu_recherche.tpl');
			// Sur la homepage on veut des microdatas pour préciser le moteur de recherche du site
			// comme défini sur : https://developers.google.com/webmasters/richsnippets/sitelinkssearch et http://schema.org/WebSite
			$tpl->assign('add_webpage_microdata', defined('IN_HOME'));
			$tpl->assign('action', get_url('search'));
			$tpl->assign('display_mode', $display_mode);
			$tpl->assign('website_type', vb($GLOBALS['site_parameters']['website_type']));
			$tpl->assign('categorie', vn($_GET["categorie"], vn($_GET['catid'])));
			if (check_if_module_active('search')) {
				$tpl->assign('advanced_search_script', get_advanced_search_script());
				$tpl->assign('select_marque', affiche_select_marque(true));
			}
			// on construit la liste des catégories
			if(check_if_module_active('annonces')) {
				// on construit les options du select des catégories
				if(empty($GLOBALS['site_parameters']['advanced_fields_in_search_bar_disabled'])) {
					$tpl->assign('select_categorie', get_categories_output(null, 'categories_annonces', vn($_GET["categorie"], vn($_GET['catid'])), 'option', '&nbsp;&nbsp;', null, null, false, 40));
				}
				$tpl->assign('category_input_name', 'cat_select');
				$tpl->assign('STR_CATEGORY', $GLOBALS['STR_MODULE_ANNONCES_SEARCH_CATEGORY_AD']);
				if(check_if_module_active('maps') && !empty($GLOBALS['site_parameters']['search_location_enable'])) {
					$tpl->assign('additionnal_select', get_search_location_field(null, 'search_location_in_header', 'location', (!empty($GLOBALS['site_parameters']['search_location_in_header_autocomplete'])?'input':'select')));
				} elseif(!empty($GLOBALS['STR_MODULE_ANNONCES_SEARCH_TYPOLOGIE']) && empty($GLOBALS['site_parameters']['ad_search_typologie_disable'])) {
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
				if(empty($GLOBALS['site_parameters']['advanced_fields_in_search_bar_disabled'])) {
					$tpl->assign('select_categorie', get_categories_output(null, 'categories', vn($_GET["categorie"], vn($_GET['catid'])), 'option', '&nbsp;&nbsp;', null, null, false, 40));
				}
				$tpl->assign('category_input_name', 'categorie');
				$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
			}
			if(!empty($GLOBALS['site_parameters']['header_search_form_additionnal_button'])) {
				$additionnal_button = '<a class="btn btn-default btn-header_search" href="'.$GLOBALS['site_parameters']['header_search_form_additionnal_button']['href'].'" title="">'.$GLOBALS['site_parameters']['header_search_form_additionnal_button']['anchor'] . '</a>
	';
				$tpl->assign('additionnal_button', $additionnal_button);
			}
			$tpl->assign('STR_SEARCH', $GLOBALS["STR_SEARCH"]);
			$tpl->assign('STR_TITLE_SEARCH_HEADER', vb($GLOBALS["STR_TITLE_SEARCH_HEADER"]));
			$output = $tpl->fetch();
			$this_cache_object->save($output);
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
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
	function affiche_guide($location, $return_mode = false, $get_preset_infos_only = true)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('guide.tpl');
		$tplLinks = array();
		if(!empty($GLOBALS['site_parameters']['show_on_affiche_guide_article'])){
			if(isset($GLOBALS['site_parameters']['show_on_affiche_guide']) && is_array($GLOBALS['site_parameters']['show_on_affiche_guide'])) {
				foreach($GLOBALS['site_parameters']['show_on_affiche_guide'] as $this_value) {
					if(StringMb::substr($this_value, 0, 4) === 'art_') {
						$art_ids_array[] = StringMb::substr($this_value, 4);
					}
				}
				if(!empty($art_ids_array)) {
					$art_where = 'a.id IN (' . implode(', ', real_escape_string($art_ids_array)) . ')';
				} else {
					$skip_arts = true;
				}
			} else {
				$art_where = '1';
			}
			if(empty($skip_arts)) {
				$sql = 'SELECT a.id, a.titre_' . $_SESSION['session_langue'] . ' AS nom, pc.rubrique_id, r.nom_' . $_SESSION['session_langue'] . ' AS rubrique_nom
					FROM peel_articles a
					INNER JOIN peel_articles_rubriques pc ON a.id = pc.article_id
					INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id AND ' . get_filter_site_cond('rubriques', 'r') . '
					WHERE ' . $art_where . ' AND a.etat = "1" AND a.titre_' . $_SESSION['session_langue'] . '!="" AND a.technical_code NOT IN ("other", "iphone_content") AND a.position>=0 AND ' . get_filter_site_cond('articles', 'a', false) . '
					GROUP BY a.id
					ORDER BY a.position ASC, nom ASC';
				$qid = query($sql);
				while ($result = fetch_assoc($qid)) {
					$tplLinks["art_".$result['id']] = array('href' => get_content_url($result['id'], $result['nom'], $result['rubrique_id'], $result['rubrique_nom']), 'label' => $result['nom'], 'selected' => false);
				}
			}
		}
		if(empty($GLOBALS['site_parameters']['disabled_contactus_left'])){
			$tplLinks['contactus'] = array('name' => 'contact', 'href' => get_contact_url(false, false), 'label' => $GLOBALS['STR_CONTACT_US'], 'selected' => defined('IN_CONTACT'));
		}
		if(empty($GLOBALS['site_parameters']['disabled_link_contact_left'])){
			$tplLinks['contact'] = array('name' => 'contact', 'href' => get_url('/contacts.php'), 'label' => $GLOBALS['STR_CONTACT_INFO'], 'selected' => defined('IN_CONTACT_US'));
		}
		if(empty($GLOBALS['site_parameters']['disabled_access_plan_left'])){
			$tplLinks['access_plan'] = array('name' => 'access_plan', 'href' => get_url('/plan_acces.php'), 'label' => $GLOBALS['STR_ACCESS_PLAN'], 'selected' => defined('IN_PLAN_ACCES'));
		}
		$hook_result = call_module_hook('affiche_guide', array('location' => $location), 'array');
		$tplLinks = array_merge_recursive_distinct($tplLinks, $hook_result);
		if(empty($GLOBALS['site_parameters']['disabled_link_cgv_left'])){
			$tplLinks['cgv'] = array('href' => get_url('cgv'), 'label' => $GLOBALS['STR_CGV'], 'selected' => defined('IN_CGV'));
		}
		if(empty($GLOBALS['site_parameters']['disabled_link_legal_left'])){
			$tplLinks['legal'] = array('href' => get_url('legal'), 'label' => $GLOBALS['STR_LEGAL_INFORMATION'], 'selected' => defined('IN_INFO_LEGALE'));
		} 
		if(isset($GLOBALS['site_parameters']['show_on_affiche_guide']) && is_array($GLOBALS['site_parameters']['show_on_affiche_guide']) && !$get_preset_infos_only) {
			$temp = array();
			foreach($GLOBALS['site_parameters']['show_on_affiche_guide'] as $this_value) {
				if(isset($tplLinks[$this_value])) {
					$temp[$this_value] = $tplLinks[$this_value];
				}
			}
			$tplLinks = $temp;
		}
		$tpl->assign('links', $tplLinks);
		if(empty($GLOBALS['site_parameters']['show_on_affiche_guide_rubrique_disabled']) && empty($GLOBALS['site_parameters']['disabled_menu_contenu_footer']) && !$get_preset_infos_only){
			$content_categories = get_categories_output($location, 'rubriques', vn($_GET['rubid']), 'list', null);
			$tpl->assign('menu_contenu', $content_categories);
		}
		$tpl->assign('affiche_guide_returned_link_list_without_ul', vb($GLOBALS['site_parameters']['affiche_guide_returned_link_list_without_ul']));

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
		$tpl->assign('footer_columns_width_sm', vb($GLOBALS['site_parameters']['footer_columns_width_sm'], 4));
		$tpl->assign('footer_columns_width_md', vb($GLOBALS['site_parameters']['footer_columns_width_md'], 3));
		$tpl->assign('site', $GLOBALS['site']);
		$tpl->assign('date', date('Y'));
		$tpl->assign('propulse', $GLOBALS['STR_PROPULSE']);
		// Valeur par défaut de la colonne de liens du footer
		$tplLinks = array();
		if(empty($GLOBALS['site_parameters']['disabled_link_legal_right'])){
			$tplLinks['legal'] = array('href' => get_url('legal'), 'label' => $GLOBALS['STR_LEGAL_INFORMATION'], 'selected' => defined('IN_INFO_LEGALE'));
		}
		if(empty($GLOBALS['site_parameters']['disabled_link_cgv'])){
			$tplLinks['cgv'] = array('href' => get_url('cgv'), 'label' => $GLOBALS['STR_CGV'], 'selected' => defined('IN_CGV'));
		}
		if (check_if_module_active('parrainage')) {
			$tplLinks['parrain'] = array('href' => get_url('/modules/parrainage/conditions.php'), 'label' => $GLOBALS['STR_CONDITION_PARRAIN'], 'selected' => defined('IN_CONDITION_PARRAIN'));
		}
		if (check_if_module_active('affiliation')) {
			$tplLinks['affiliate'] = array('href' => get_url('/modules/affiliation/conditions.php'), 'label' => $GLOBALS['STR_CONDITION_AFFILI'], 'selected' => defined('IN_CONDITION_AFFILI'));
		}
		foreach(vb($GLOBALS['site_parameters']['footer_disabled_links'], array()) as $this_disabled_link) {
			unset($tplLinks[$this_disabled_link]);
		}
		$tpl->assign('links', vb($GLOBALS['site_parameters']['footer_links'], $tplLinks));
		$tpl->assign('links_2', vb($GLOBALS['site_parameters']['footer_links_2'], array()));
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
		if(function_exists('get_footer_additional_link')) {
			$tpl->assign('footer_additional_link', get_footer_additional_link());
		} else {
			$tpl->assign('footer_additional_link', vb($GLOBALS['site_parameters']['get_footer_additional_link']));
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
		if (empty($GLOBALS['site_parameters']['rss_page_link_disable']) && check_if_module_active('rss')) {
			if (check_if_module_active('annonces')) {
				$tpl->assign('href', get_url('/modules/annonces/rss.php'));
				$tpl->assign('rss_new_window', false);
			} else {
				$tpl->assign('href', $GLOBALS['wwwroot'] . '/modules/rss/rss.php');
				$tpl->assign('rss_new_window', true);
			}
			$tpl->assign('src', $GLOBALS['repertoire_images'] . '/rss.png');
			$load = true;
		}
		foreach(array('facebook' => 'fb', 'twitter' => 'twitter', 'googleplus' => 'googleplus', 'viadeo' => 'viadeo', 'linkedin' => 'linkedin') as $this_social_network => $this_variable_name) {
			if (!empty($GLOBALS['site_parameters'][$this_social_network.'_page_link'])) {
				$tpl->assign($this_variable_name . '_href', $GLOBALS['site_parameters'][$this_social_network . '_page_link']);
				$tpl->assign($this_variable_name . '_src', $GLOBALS['repertoire_images'] . '/' . $this_social_network . '.png');
				$load = true;
			}
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
			$tpl->assign('membre_href', get_url('membre'));
			$tpl->assign('prenom', vb($_SESSION['session_utilisateur']['prenom']));
			$tpl->assign('nom_famille', vb($_SESSION['session_utilisateur']['nom_famille']));
			$tpl->assign('sortie_href', get_url('sortie'));
			if (function_exists('get_social_icone')) {
				$tpl->assign('social_icone', get_social_icone());
			}
			$tpl->assign('compte_href', get_account_url(false, false));
			if (empty($GLOBALS['site_parameters']['order_history_for_user_disable']) || (!empty($GLOBALS['site_parameters']['order_history_for_user_disable']) && !empty($_SESSION['session_utilisateur']['access_history']))) {
				$tpl->assign('history_href', get_url('/achat/historique_commandes.php'));
			}
			if (check_if_module_active('facebook_connect') && !empty($_SESSION['session_utilisateur']['connected_by_fb'])) {
				$tpl->assign('fb_deconnect_lbl', $GLOBALS['STR_FB_DECONNECT']);
			}
			if (a_priv('admin*', true)) {
				$tpl->assign('admin', array('href' => $GLOBALS['administrer_url'] . '/index.php', 'txt' => $GLOBALS['STR_ADMINISTRATION']));
			}
			if (!empty($GLOBALS['site_parameters']['quick_add_product_from_search_page'])) {
				$tpl->assign('quick_add_product_from_search_page_href', $GLOBALS['wwwroot'] . '/search.php?type=quick_add_product_from_search_page');
			}
			$tpl->assign('STR_HELLO', $GLOBALS['STR_HELLO']);
			$tpl->assign('STR_COMPTE', $GLOBALS['STR_COMPTE']);
			$tpl->assign('STR_DECONNECT', $GLOBALS['STR_DECONNECT']);
			$tpl->assign('STR_ORDER_HISTORY', $GLOBALS['STR_ORDER_HISTORY']);
			$output .= $tpl->fetch();
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('compte_login_mini.tpl');
			$tpl->assign('location', $location);
			$tpl->assign('repertoire_images', $GLOBALS['repertoire_images']);
			if (empty($GLOBALS['site_parameters']['pseudo_is_not_used'])) {
				$tpl->assign('email_lbl', $GLOBALS['STR_EMAIL_OR_PSEUDO']);
			} else {
				$tpl->assign('email_lbl', $GLOBALS['STR_EMAIL']);
			}
			$tpl->assign('email', vb($frm['email']));
			$tpl->assign('password_lbl', $GLOBALS['STR_PASSWORD']);
			$tpl->assign('password', vb($frm['mot_passe']));
			$tpl->assign('TOKEN', get_form_token_input('membre.php', true));
			$tpl->assign('forgot_pass_href', get_url('/utilisateurs/oubli_mot_passe.php'));
			$tpl->assign('forgot_pass_lbl', $GLOBALS['STR_FORGOT_YOUR_PASSWORD']);
			if (empty($GLOBALS['site_parameters']['disable_register_button_on_login_form'])) {
				$url_enregistrement = get_account_register_url(false, false);
				$tpl->assign('enregistrement_href', $url_enregistrement);
				$tpl->assign('enregistrement_lbl', $GLOBALS['STR_OPEN_ACCOUNT']);
			}
			$tpl->assign('via_lbl', $GLOBALS['STR_VIA']);
			if (function_exists('get_social_icone')) {
				$tpl->assign('social_icone', get_social_icone());
			}
			$social = call_module_hook('social_login_buttons', array(), 'array');
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
		if (check_if_module_active('facebook')) {
			$tpl->assign('head_attributes', get_facebook_xmlns());
		}
		if(!empty($GLOBALS['meta_rss_links_array'])){
			$link_rss_html = '';
			foreach($GLOBALS['meta_rss_links_array'] as $this_title => $this_url){
				$link_rss_html .= '
	<link rel="alternate" type="application/rss+xml" title="'.StringMb::str_form_value($this_title).'" href="'.StringMb::str_form_value($this_url).'" />';
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
			$tpl->assign('favicon_href', get_url_from_uploaded_filename($GLOBALS['site_parameters']['favicon']));
		}
		if (empty($GLOBALS['site_parameters']['lightbox_disable']) || !empty($GLOBALS['lightbox_force'])) {
			// Lightbox peut servir à différents endroits du logiciel. Si on est sûr qu'on ne s'en sert pas, on peut le désactiver avec disable_lightbox
			$GLOBALS['css_files'][] = get_url('/lib/css/lightbox.css');
			if (!empty($GLOBALS['lightbox_force'])) {
				// On n'utilise la lightbox qu'exceptionnellement, on ne veut donc pas que ce soit minifié avec le reste
				$GLOBALS['site_parameters']['minify_css_exclude_array'][] = 'lightbox.css';
			}
		}
		if (vb($GLOBALS['site_parameters']['zoom']) == 'jqzoom' && vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['css_files'][] = get_url('/lib/css/jqzoom.css');
		} elseif (vb($GLOBALS['site_parameters']['zoom']) == 'cloud-zoom' && vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['css_files'][] = get_url('/lib/css/cloudzoom.css');
		}
		// Début des javascripts
		if (vb($GLOBALS['site_parameters']['enable_jquery']) == 1) {
			$GLOBALS['js_files'][-100] = get_url('/lib/js/jquery.js');
		}
		$GLOBALS['css_files'][] = get_url('/lib/css/jquery-ui.css');
		$GLOBALS['js_files'][-90] = get_url('/lib/js/jquery-ui.js');
		if(file_exists($GLOBALS['dirroot'] . '/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js')) {
			// Configuration pour une langue donnée
			$GLOBALS['js_files'][] = get_url('/lib/js/jquery.ui.datepicker-'.$_SESSION['session_langue'].'.js');
		}
		// <!-- librairie pour activer le zoom sur les categories (et produits si configuration dans l'administration) -->
		if (empty($GLOBALS['site_parameters']['lightbox_disable']) || !empty($GLOBALS['lightbox_force'])) {
			// Lightbox peut servir à différents endroits du logiciel. Si on est sûr qu'on ne s'en sert pas, on peut le désactiver avec disable_lightbox
			$GLOBALS['js_files'][] = get_url('/lib/js/lightbox.js');
			if (!empty($GLOBALS['lightbox_force'])) {
				// On n'utilise la lightbox qu'exceptionnellement, on ne veut donc pas que ce soit minifié avec le reste
				$GLOBALS['site_parameters']['minify_js_exclude_array'][] = 'lightbox.js';
			}
		}
		// <!-- fin de librairie pour activer le zoom sur les categories -->
		if (vb($GLOBALS['site_parameters']['enable_prototype']) == 1 && empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
			$GLOBALS['js_files'][] = get_url('/lib/js/prototype.js');
			$GLOBALS['js_files'][] = get_url('/lib/js/effects.js');
			$GLOBALS['js_files'][] = get_url('/lib/js/controls.js');
		}
		if (!empty($GLOBALS['load_anythingslider'])) {
			// Pour ajouter des vidéos ou des effets au carrousel nivo_slider, il faut inclure les fichiers suivants :
			// AnythingSlider optional extensions
			// $GLOBALS['js_files_nominify'][-18] = get_url('/modules/carrousel/js/jquery.anythingslider.fx.min.js');
			// $GLOBALS['js_files_nominify'][-17] = get_url('/modules/carrousel/js/jquery.anythingslider.video.min.js');
			$GLOBALS['js_files_nominify'][-19] = get_url('/modules/carrousel/js/jquery.anythingslider.min.js');
		}
		if (((defined('IN_RUBRIQUE') && vb($rub['technical_code']) == 'creation') || (defined('IN_REFERENCE') && !empty($GLOBALS['site_parameters']['affiche_reference_multipage_with_pheonix_gallery']))) && check_if_module_active('references')) {
			$GLOBALS['css_files'][] = get_url('/modules/references/style/style.css');
			$GLOBALS['css_files'][] = get_url('/modules/references/phoenixgallery/style/style.css');
			$GLOBALS['js_files'][-60] = get_url('/lib/js/jquery.easing.min.js');
			$GLOBALS['js_files'][] = get_url('/modules/references/phoenixgallery/js/phoenixgallery.js');
		}
		// Librairie pour activer le zoom sur les produits
		if ($GLOBALS['site_parameters']['zoom'] == 'jqzoom' && $GLOBALS['site_parameters']['enable_jquery'] == 1) {
			$GLOBALS['js_files'][-70] = get_url('/lib/js/jquery.jqzoom-core-pack.js');
		} elseif ($GLOBALS['site_parameters']['zoom'] == 'cloud-zoom' && $GLOBALS['site_parameters']['enable_jquery'] == 1) {
			$GLOBALS['js_files'][] = get_url('/lib/js/cloud-zoom.1.0.2.js');
		}
		foreach($GLOBALS['modules_installed'] as $this_module) {
			if(!empty($GLOBALS['site_parameters']['modules_front_office_js_array'][$this_module])) {
				$GLOBALS['js_files'][] = get_url($GLOBALS['site_parameters']['modules_front_office_js_array'][$this_module]);
			}
		}
		if (check_if_module_active('cart_popup') && !empty($_SESSION['session_show_caddie_popup'])) {
			$js_output .= get_cart_popup_script();
		}
		if (check_if_module_active('googlefriendconnect')) {
			$js_output .= google_friend_connect_javascript_library();
		}
		$GLOBALS['js_ready_content_array'][] = get_datepicker_javascript() . '
			'.vb($js_sortable).'
';
		if (empty($GLOBALS['site_parameters']['disable_autocomplete'])) {
			// Barre de recherche principale
			$GLOBALS['js_ready_content_array'][] = '
		bind_search_autocomplete("search", "' . $GLOBALS['wwwroot'] . '/modules/search/produit.php", true);
';
		}
		if (!empty($GLOBALS['site_parameters']['autocomplete_search_advanced_enable'])) {
			// Formulaire de recherche avancée, pas adapté a priori à l'autocomplete en terme d'usage, mais parfois l'autocomplete peut être souhaité
			$GLOBALS['js_ready_content_array'][] = '
		bind_search_autocomplete("search_advanced", "' . $GLOBALS['wwwroot'] . '/modules/search/produit.php", true);
';
		}
		if(!empty($GLOBALS['load_nyromodal'])){
			// Nyromodal ne charge pas en asynchrone, si nyromodal est activé on désactive l'asynchrone pour permettre au 'diaporama' Nyromodal de fonctionner.
			$GLOBALS['site_parameters']['load_javascript_async'] = false;
			$GLOBALS['css_files'][] = get_url('/lib/css/nyroModal.css');
			$GLOBALS['js_files'][] = get_url('/lib/js/jquery.nyroModal.custom.js');
		
			$GLOBALS['js_ready_content_array'][] = '
		$(function() {
			$(".nyroModal").nyroModal();
		});
';
		}
		if(!empty($GLOBALS['load_cropper'])){
			$GLOBALS['css_files'][] = get_url('/lib/css/cropper.min.css');
			$GLOBALS['js_files'][] = get_url('/lib/js/cropper.min.js');
		}
		if(!empty($GLOBALS['site_parameters']['scroll_to_top'])) {
			// Footer sur toutes les pages
			$GLOBALS['js_ready_content_array'][] = '
		// Compatibilité pour iOS
		$(document).touchmove(function() { advisto_scroll() });
		$(window).scroll(function() { advisto_scroll() });
		function advisto_scroll() {
			if ($(document).scrollTop() > 100) {
				if($(".scroll_to_top").css("display") == "none") {
					$(".scroll_to_top").stop(true, false).show(800);
				}
			} else if($(".scroll_to_top").css("display") != "none") {
				$(".scroll_to_top").stop(true, false).hide(400);
			}
			$test_reach_bottom = $(window).scrollTop() + $(window).height();
			if ($test_reach_bottom == $(document).height()) {
				if($(".touch_bottom").css("display") != "none") {
					$(".touch_bottom").stop(true, false).hide(400);
				}
			} else if($(".touch_bottom").css("display") == "none") {
				$(".touch_bottom").stop(true, false).show(800);
			}
		}
';
		}
		if(!empty($GLOBALS['site_parameters']['images_preload_urls_array'])) {
			foreach($GLOBALS['site_parameters']['images_preload_urls_array'] as $this_image_url) {
				if (StringMb::strpos($this_image_url, '//') === false) {
					$this_image = $GLOBALS['wwwroot'] . $this_image_url;
				}
				$GLOBALS['js_content_array'][] = '
		loadImage=new Image();
		loadImage.src="' . $this_image_url . '";';
			}
		}
		// On met Bootstrap en dernier fichier CSS du site pour qu'ils aient priorité
		if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
			$GLOBALS['css_files'][] = get_url('/lib/css/bootstrap.css');
			$GLOBALS['js_files'][] = $GLOBALS['wwwroot_in_admin'] . '/lib/js/bootstrap.js';
			$GLOBALS['js_ready_content_array'][] = '
	bootbox.setDefaults({
		locale: "'.$_SESSION['session_langue'].'"
	});';
		}
		$GLOBALS['js_files'][] = get_url('/lib/js/advisto.js');
		if (vb($GLOBALS['site_parameters']['anim_prod']) == 1) {
			$GLOBALS['js_files'][] = get_url('/lib/js/fly-to-basket.js');
		}
		if (vb($GLOBALS['site_parameters']['anim_loading_page']) == 1) {
			$GLOBALS['js_files'][] = get_url('/lib/js/pace.min.js');
		}
		// effet de changement de couleur pour les boutons btn-primary
		if ($GLOBALS['site_parameters']['template_directory'] == 'peel9') {
			$GLOBALS['js_ready_content_array'][] = '
		$(".btn-primary").css({"background-color": "#0543c3"});
		$(".btn-primary").hover(
			function() {
			$(this).stop().animate({"background-color": "#3265cd"}, "slow");
			},
			function() {
			$(this).stop().animate({"background-color": "#0543c3"}, "slow");
		});
';
		}
		if(!empty($GLOBALS['site_parameters']['css'])) {
			foreach (get_array_from_string($GLOBALS['site_parameters']['css']) as $this_css_file) {
				$this_css_file = trim($this_css_file);
				if (StringMb::strpos($this_css_file, '//') !== false) {
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
				$category_introduction_text = StringMb::html_entity_decode_if_needed(trim($CP->category_introduction_text));
				$background_menu = StringMb::html_entity_decode_if_needed($CP->background_menu);
				$background_color = StringMb::html_entity_decode_if_needed($CP->background_color);

				if (strlen($background_color) > 1 || strlen($background_menu) > 1) {
					$tpl->assign('bg_colors', array('body' => $background_color,
							'menu' => $background_menu
							));
				}
			}
		}

		$hook_result = call_module_hook('front_html_header_template_data', array(), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl->assign($this_key, $this_value);
		}

		$output .= $tpl->fetch();
		
		
		if (!empty($GLOBALS['product_in_caddie_cookie']) && !empty($GLOBALS['site_parameters']['save_caddie_in_cookie'])) {
			// on crée le cookie avec 1 an de vie
			unset($_COOKIE[$GLOBALS['caddie_cookie_name']]);
			// Un cookie ne peut faire que 4Ko. Donc le nombre de produit à retenir dans le cookie est d'environ 25 produits.
			// On pourrait compresser le contenu dans le cookies en utilisant base64_encode(gzcompress(serialize($GLOBALS['product_in_caddie_cookie']))) mais il reste un problème de gestion des caractères =, il faudrait faire de la bidouille pour contourner le problème, donc on ne fait rien.
			if($GLOBALS['site_parameters']['force_sessions_for_subdomains']){
				@setcookie($GLOBALS['caddie_cookie_name'], serialize($GLOBALS['product_in_caddie_cookie']), time() + 365 * 24 * 60 * 60, '/', '.'.get_site_domain());
			} else {
				@setcookie($GLOBALS['caddie_cookie_name'], serialize($GLOBALS['product_in_caddie_cookie']), time() + 365 * 24 * 60 * 60, '/');
			}
		}
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
		$avoid_links_when_hover = !empty($GLOBALS['site_parameters']['bootstrap_enabled']) && (StringMb::strpos(StringMb::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'android') !== false || (StringMb::strpos(StringMb::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'windows') !== false && StringMb::strpos(StringMb::strtolower(vb($_SERVER['HTTP_USER_AGENT'])),'mobile') !== false));
		if (!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
			//  bootstrap est actif, l'affichage de l'arborescence des catégories est faite via des sous menu distinct, qui s'affiche au survol de la souris. Il n'est pas nécessaire de mettre une indentation en plus.
			$indent = '';
		} else {
			// Menu sans bootstrap, les sous catégories sont décallées par rapport à la catégorie mère, pour permettre un affichage clair de l'arborescence
			$indent = '&nbsp;&nbsp;';
		}
		if (empty($GLOBALS['main_menu_items']) || isset($GLOBALS['site_parameters']['main_menu_items_if_available'])) {
			$GLOBALS['main_menu_items']['home'] = array($GLOBALS['wwwroot'] . '/' => $GLOBALS['STR_HOME']);
			$GLOBALS['main_menu_items']['catalog'] = array(get_product_category_url() => $GLOBALS['STR_CATALOGUE']);
			$GLOBALS['main_menu_items']['news'] = array(get_product_category_url() . 'nouveautes.php' => $GLOBALS['STR_NOUVEAUTES']);
			$GLOBALS['main_menu_items']['content'] = array(get_content_category_url() => $GLOBALS["STR_INFORMATIONS"]);
			$GLOBALS['main_menu_items']['other'] = array('#' => $GLOBALS["STR_OTHER"]);
			$GLOBALS['main_menu_items']['faq'] = array(get_url('/modules/faq/faq.php') => $GLOBALS['STR_FAQ_TITLE']);
			$GLOBALS['main_menu_items']['brand'] = array(get_url('/achat/marque.php') => $GLOBALS['STR_ALL_BRAND']);
			$GLOBALS['main_menu_items']['contact_us'] = array(get_url('/contacts.php') => $GLOBALS["STR_CONTACT_INFO"]);
			$GLOBALS['main_menu_items']['contact_form'] = array(get_url('/utilisateurs/contact.php') => $GLOBALS['STR_CONTACT_US']);
			$GLOBALS['main_menu_items']['access_plan'] = array(get_url('/plan_acces.php') => $GLOBALS['STR_ACCESS_PLAN']);
			$GLOBALS['main_menu_items']['flash'][get_url('/modules/flash/flash.php')] = $GLOBALS['STR_FLASH'];
			$GLOBALS['main_menu_items']['promotions'][get_product_category_url() . 'promotions.php'] = $GLOBALS['STR_PROMOTIONS'];
			$GLOBALS['main_menu_items']['reseller'][get_url('/modules/reseller/retailer.php')] = $GLOBALS['STR_RETAILER'];
			if (!empty($GLOBALS['site_parameters']['enable_create_product_in_front'])) {
				$result = fetch_assoc(query("SELECT id FROM peel_categories WHERE technical_code = 'show_draft' AND etat = 1 AND " . get_filter_site_cond('categories') . ""));
				if (!empty($result['id'])) {
					$GLOBALS['main_menu_items']['draft'] = array(get_product_category_url($result['id']) => $GLOBALS['STR_MODULE_CREATE_PRODUCT_IN_FRONT_OFFICE_SORTIE_SAVE_DRAFT']);
				}
			}
			if (est_identifie()) {
				$GLOBALS['main_menu_items']['account'] = array(get_account_url(false, false) => $GLOBALS['STR_COMPTE']);
				if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
					$GLOBALS['menu_items']['account'][get_account_url(false, false)] = $GLOBALS['STR_COMPTE'];
				}
				if (empty($GLOBALS['site_parameters']['order_history_for_user_disable']) || (!empty($GLOBALS['site_parameters']['order_history_for_user_disable']) && !empty($_SESSION['session_utilisateur']['access_history']))) {
					$GLOBALS['menu_items']['account'][get_product_category_url() . 'historique_commandes.php'] = $GLOBALS['STR_ORDER_HISTORY'];
				}
				$GLOBALS['menu_items']['account'][get_url('/utilisateurs/change_mot_passe.php')] = $GLOBALS['STR_CHANGE_PASSWORD'];
				if (check_if_module_active('cart_preservation')) {
					$GLOBALS['menu_items']['account'][get_url('/modules/cart_preservation/cart_preservation.php')] = $GLOBALS['STR_CART_PRESERVATION_TITLE'];
				}
				$GLOBALS['menu_items']['account'][get_url('/utilisateurs/change_params.php')] = $GLOBALS['STR_CHANGE_PARAMS'];
				$GLOBALS['menu_items']['account'][get_url('sortie')] = $GLOBALS['STR_LOGOUT'];
			} else {
				$GLOBALS['main_menu_items']['account'] = array(get_url('membre') => $GLOBALS['STR_COMPTE']);
			}
			$GLOBALS['main_menu_items']['contact'] = array(get_contact_url(false, false) => $GLOBALS['STR_CONTACT']);
			if(empty($GLOBALS['site_parameters']['disable_contact_submenu'])) {
				if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
					$GLOBALS['menu_items']['contact'][get_contact_url(false, false)] = $GLOBALS['STR_CONTACT'];
				}
				$GLOBALS['menu_items']['contact'][get_url('/plan_acces.php')] = $GLOBALS['STR_ACCESS_PLAN'];
			}
			if (a_priv('admin*', true)) {
				$GLOBALS['main_menu_items']['admin'] = array($GLOBALS['administrer_url'] . '/' => $GLOBALS['STR_ADMIN']);
			}
			$GLOBALS['menu_items']['news'][get_product_category_url() . 'promotions.php'] = $GLOBALS['STR_PROMOTIONS'];
			if (is_flash_active_on_site()) {
				$GLOBALS['menu_items']['news'][get_url('/modules/flash/flash.php')] = $GLOBALS['STR_FLASH'];
			}
			if (check_if_module_active('devis')) {
				$GLOBALS['menu_items']['devis'][get_url('/modules/devis/devis.php')] = $GLOBALS['STR_DEVIS'];
			} else {
				$GLOBALS['menu_items']['devis'] = array();
			}
			foreach(vb($GLOBALS['site_parameters']['main_menu_custom_titles'], array()) as $this_key => $this_title) {
				$GLOBALS['main_menu_items'][$this_key][vb($GLOBALS['site_parameters']['main_menu_custom_urls'][$this_key], '#')] = $this_title;
			}			
			$hook_result = call_module_hook('menu_items', array('indent' => $indent), 'array');
			$GLOBALS['main_menu_items'] = array_merge_recursive_distinct($GLOBALS['main_menu_items'], vb($hook_result['main_menu_items'], array()));
			$GLOBALS['menu_items'] = array_merge_recursive_distinct($GLOBALS['menu_items'], vb($hook_result['menu_items'], array()));
			// $GLOBALS['main_menu_items']['news'] est ajouté dans le sous menu de "Autre" si il n'est pas présent dans les éléments principaux du menu
			$GLOBALS['menu_items']['other'] = array_merge($GLOBALS['main_menu_items']['catalog'], $GLOBALS['menu_items']['news'], (!in_array('news', $GLOBALS['site_parameters']['main_menu_items_if_available'])? $GLOBALS['main_menu_items']['news']:array()), array('' => 'divider'), vb($GLOBALS['menu_items']['contact'], array()), $GLOBALS['menu_items']['devis'],(!in_array('contact', $GLOBALS['site_parameters']['main_menu_items_if_available'])? $GLOBALS['main_menu_items']['contact']:array()));
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
				WHERE c.etat="1" AND nom_' . $_SESSION['session_langue'] . '!="" AND ' . get_filter_site_cond('categories', 'c') . ' ' . (!empty($GLOBALS['site_parameters']['categories_exclude_from_main_menu'])?' AND id NOT IN ("'.implode($GLOBALS['site_parameters']['categories_exclude_from_main_menu'], '","').'")':'') . '
				ORDER BY c.position ASC, nom ASC';
			$qid = query($sql);
			while ($result = fetch_assoc($qid)) {
				$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
				$item_name_array[$result['id']] = $result['nom'];
				if(empty($result['parent_id'])) {
					if(!empty($GLOBALS['site_parameters']['main_menu_product_category_strtoupper_disable'])) {
						// Variable de configuration qui permet de ne pas forcer l'uppercase en php et donc de le modifier via css
						$result['nom'] = $result['nom'];
					} else {
						$result['nom'] = StringMb::strtoupper($result['nom']);
					}
				}
				if(!empty($GLOBALS['site_parameters']['insert_product_categories_in_menu'])) {
					// Il faut définir par la suite cat_XX dans le paramètre main_menu_items_if_available depuis le back office pour que la catégorie s'affiche.
					if(!isset($GLOBALS['main_menu_items']["cat_" . $result['id']])) {
						$GLOBALS['main_menu_items']["cat_" . $result['id']] = array(get_product_category_url($result['id'], $result['nom']) => $result['nom']);
					}
					$GLOBALS['categories_level'][$result['parent_id']][] = "cat_" . $result['id'];
				}
			}
			$GLOBALS['submenu_html_array']['catalog'] = '';
			if(!empty($item_name_array) && !empty($GLOBALS['site_parameters']['product_categories_depth_in_menu'])) {
				$GLOBALS['submenu_html_array']['catalog'] .= '<ul class="sousMenu dropdown-menu" role="menu">'.get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $selected_item, 'categories', 'left', vn($GLOBALS['site_parameters']['product_categories_depth_in_menu']), vb($GLOBALS['site_parameters']['categories_top_menu_item_max_length'], 25), 'list', $indent, null).'</ul>';
				foreach($item_name_array as $catid => $nom) {
					if (!empty($all_parents_with_ordered_direct_sons_array[$catid]) && !isset($GLOBALS['submenu_html_array']["cat_".$catid])) {
						$GLOBALS['submenu_html_array']["cat_".$catid] = '<ul class="sousMenu dropdown-menu" role="menu">';
						if(!empty($GLOBALS['site_parameters']['submenus_include_products'])) {
							// Récupération de liste de produits de la catégorie concernée
							$sql_offre = 'SELECT p.nom_' . $_SESSION['session_langue'] . ' AS nom_produit, p.id
								FROM peel_produits p
								INNER JOIN peel_produits_categories pc ON pc.produit_id = p.id
								INNER JOIN peel_categories c ON pc.categorie_id = c.id
								WHERE p.etat="1" AND p.nom_' . $_SESSION['session_langue'] . '!="" AND pc.categorie_id="'.intval($catid).'"
								GROUP BY p.id
								ORDER BY p.`position` ASC, p.id ASC';
							$qid = query($sql_offre);
							while ($result = fetch_assoc($qid)) {
								$product_object = new Product($result['id']);
								$GLOBALS['submenu_html_array']["cat_".$catid] .= '<li><a href="'. $product_object->get_product_url(). '">' .  $result['nom_produit'] . '</a></li>';
								unset($product_object);
							}
						}
						$GLOBALS['submenu_html_array']["cat_".$catid] .= get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, $catid, 0, $selected_item, 'categories', 'left', vn($GLOBALS['site_parameters']['product_categories_depth_in_menu']), vb($GLOBALS['site_parameters']['categories_submenu_item_max_length'], 50), 'list', $indent, null);
						if($avoid_links_when_hover) {
							// Si le lien n'est pas généré dans le menu pour une catégorie mère, il faut ajouter ce lien dans le sous menu pour que la catégorie mère reste accessible
							// On utilise array_shift( array_keys() ) car array_keys()[0] n'est disponible qu'à partir de la version 5.4 de PHP
							// array_shift() extrait la première valeur d'un tableau et la retourne, en raccourcissant le tableau d'un élément, et en déplaçant tous les éléments vers le bas. Toutes les clés numériques seront modifiées pour commencer à zéro.
							$GLOBALS['submenu_html_array']["cat_".$catid] .= '<li><a href="'. array_shift(array_keys($GLOBALS['main_menu_items']["cat_".$catid])) . '">' . array_shift(array_values($GLOBALS['main_menu_items']["cat_".$catid])) . '</a></li>';
						}
						$GLOBALS['submenu_html_array']["cat_".$catid] .='</ul>';
					}
				}
			}
		} elseif (check_if_module_active('sauvegarde_recherche') && est_identifie()) {
			$GLOBALS['submenu_html_array']['catalog'] .= '<ul class="sousMenu dropdown-menu" role="menu">'.display_ads_search_list($_SESSION['session_utilisateur']['id_utilisateur'], true).'</ul>';
		}
		
		if(!empty($GLOBALS['site_parameters']['insert_article_in_menu'])) {
			// Lien d'articles (/lire/article_details.php) dans le menu principal. Il faut définir art_XX dans le paramètre main_menu_items_if_available depuis le back office pour que le lien de l'article s'affiche dans les onglets du menu principal
			$sql = 'SELECT a.id, a.on_reseller, a.titre_' . $_SESSION['session_langue'] . ' as nom, pc.rubrique_id, r.nom_' . $_SESSION['session_langue'] . ' AS rubrique_nom
				FROM peel_articles a
				INNER JOIN peel_articles_rubriques pc ON a.id = pc.article_id
				INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id AND ' . get_filter_site_cond('rubriques', 'r') . '
				WHERE a.etat = "1" AND a.technical_code NOT IN ("other", "iphone_content") AND a.position>=0 AND ' . get_filter_site_cond('articles', 'a') . '
				GROUP BY a.id
				ORDER BY a.position ASC, nom ASC';
			$qid = query($sql);
			while ($result = fetch_assoc($qid)) {
				if ((!a_priv("admin_product") && !a_priv("reve")) && $result['on_reseller'] == 1) {
					continue;
				} else {
					// Charge tous les articles dans main_menu_items, qui sera filtré avec ce que contient main_menu_items_if_available quelques lignes en dessous
					// Il faut définir par la suite art_XX dans le paramètre main_menu_items_if_available depuis le back office pour que la rubrique s'affiche.
					$GLOBALS['main_menu_items']["art_".$result['id']] = array(get_content_url($result['id'], $result['nom'], $result['rubrique_id'], $result['rubrique_nom']) => $result['nom']);
				}
			}
		}

		// Préparation du contenu du menu contenu rédactionnel
		if(!empty($GLOBALS['site_parameters']['content_categories_depth_in_menu'])) {
			// Au moins 1 niveau d'arborescence.
			$selected_item = intval(vn($_GET['rubid']));
			unset($all_parents_with_ordered_direct_sons_array);
			unset($item_name_array);
			$sql = 'SELECT r.id, r.parent_id, r.nom_' . $_SESSION['session_langue'] . ' AS nom
				FROM peel_rubriques r
				WHERE r.etat = "1" AND r.technical_code NOT IN ("other", "iphone_content") AND r.position>=0  AND ' . get_filter_site_cond('rubriques', 'r') . '
				ORDER BY r.position ASC, nom ASC';
			$qid = query($sql);

			while ($result = fetch_assoc($qid)) {
				$all_parents_with_ordered_direct_sons_array[$result['parent_id']][] = $result['id'];
				$item_name_array[$result['id']] = $result['nom'];
				if(!empty($GLOBALS['site_parameters']['insert_article_categories_in_menu'])) {
					// Il faut définir par la suite rub_XX dans le paramètre main_menu_items_if_available depuis le back office pour que la rubrique s'affiche.
					if(!isset($GLOBALS['main_menu_items']["rub_" . $result['id']])) {
						$GLOBALS['main_menu_items']["rub_".$result['id']] = array(get_content_category_url($result['id'], $result['nom']) => $result['nom']);
					}
					$GLOBALS['rubriques_level'][$result['parent_id']][] = "rub_" . $result['id'];
				}
			}
			$GLOBALS['submenu_html_array']['content'] = '';
			if(!empty($item_name_array)) {
				$GLOBALS['submenu_html_array']['content'] .= '<ul class="sousMenu dropdown-menu" role="menu">'.get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $selected_item, 'rubriques', 'left', $GLOBALS['site_parameters']['content_categories_depth_in_menu'], vb($GLOBALS['site_parameters']['categories_top_menu_item_max_length'], 25), 'list', $indent, null).'</ul>';
				foreach($item_name_array as $rubid => $nom) {
					if(!isset($GLOBALS['submenu_html_array']["rub_".$rubid])) {
						if (!empty($GLOBALS['site_parameters']['insert_content_articles_in_menu'])) {
							// Affichage des articles de la rubrique dans le sous menu
							$sql = "SELECT a.id, a.titre_" . $_SESSION['session_langue'] . " as nom
								FROM peel_rubriques r
								INNER JOIN peel_articles_rubriques pc ON r.id = pc.rubrique_id
								INNER JOIN peel_articles a ON a.id = pc.article_id AND " . get_filter_site_cond('articles', 'a') . "
								WHERE r.id ='" . intval($rubid) . "' AND r.technical_code NOT IN ('other', 'iphone_content') AND " . get_filter_site_cond('rubriques', 'r') . "";
							$qid = query($sql);
								$GLOBALS['submenu_html_array']["rub_".$rubid] = '<ul class="sousMenu dropdown-menu" role="menu">';
							if($avoid_links_when_hover) {
								// Si le lien n'est pas généré dans le menu pour une rubrique mère, il faut ajouter ce lien dans le sous menu pour que la rubrique mère reste accessible
								$GLOBALS['submenu_html_array']["rub_".$rubid] .= '<li><a href="'. array_shift(array_keys($GLOBALS['main_menu_items']["rub_".$rubid])) . '">' . array_shift(array_values($GLOBALS['main_menu_items']["rub_".$rubid])) . '</a></li>';
							}
							while ($result = fetch_assoc($qid)) {
								$GLOBALS['submenu_html_array']["rub_".$rubid] .= '<li><a href="'. get_content_url($result['id']) . '">' . $result['nom'] . '</a></li>';
							}
							$GLOBALS['submenu_html_array']["rub_".$rubid] .= '</ul>';
						} elseif (!empty($all_parents_with_ordered_direct_sons_array[$rubid])) {
							// Affichage des sous-rubriques dans le sous-menu
							$GLOBALS['submenu_html_array']["rub_".$rubid] = '
									<ul class="sousMenu dropdown-menu" role="menu">' . get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, $rubid, 0, $selected_item, 'rubriques', 'left', $GLOBALS['site_parameters']['content_categories_depth_in_menu'], vb($GLOBALS['site_parameters']['categories_submenu_item_max_length'], 50), 'list', $indent, null) . vb($article) .'</ul>';
						}
					}
				}
			}
		}
		if(in_array('brand',$GLOBALS['site_parameters']['main_menu_items_if_available'])) {
			// On vérifie si "brand" fait parti des onglets à afficher au menu : administrable en back office
			// Récupération des infos liées aux marques
			$sql = "SELECT id, image, description_" . $_SESSION['session_langue'] . " AS description, nom_" . $_SESSION['session_langue'] . " AS nom
				FROM peel_marques
				WHERE etat=1 AND  " . get_filter_site_cond('marques') . "
				ORDER BY position ASC, nom ASC";
			$query = query($sql);
			// On crée le sous menu des marques
			$GLOBALS['submenu_html_array']['brand'] = '<ul class="sousMenu dropdown-menu" role="menu">';
			while ($result = fetch_assoc($query)) {
				$GLOBALS['submenu_html_array']['brand'] .= '<li><a href="'.get_url('/achat/marque.php', array('id' => $result['id'])).'">
				'.$result['nom'].'
				</a></li>';	
			}
			$GLOBALS['submenu_html_array']['brand'] .= '</ul>';
		}
		if(!empty($GLOBALS['site_parameters']['main_menu_items_if_available']) && is_array($GLOBALS['site_parameters']['main_menu_items_if_available'])) {
			$temp_main_menu_items = array();
			if(in_array('cat_*', $GLOBALS['site_parameters']['main_menu_items_if_available']) && !empty($GLOBALS['categories_level'][0])) {
				$new_menu = array();
				foreach($GLOBALS['site_parameters']['main_menu_items_if_available'] as $this_key => $this_value) {
					if($this_value=='cat_*') {
						foreach($GLOBALS['categories_level'][0] as $this_imported_value) {
							if(StringMb::strpos($this_value, 'cat_') === 0) {
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
							if(StringMb::strpos($this_value, 'rub_') === 0) {
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
				} elseif(!is_numeric($this_key)) {
					$custom++;
					if(StringMb::substr($this_value, 0, 4) == 'STR_' && isset($GLOBALS[$this_value])) {
						$this_text = $GLOBALS[$this_value];
					} else {
						$this_text = $this_value;
					}
					if(StringMb::strpos($this_key, '//') !== false) {
						$this_url = $this_key;
					} else {
						if(StringMb::strpos($this_key, '/') !== 0) {
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
					if(!empty($GLOBALS['site_parameters']['menu_custom_submenus_keep_default'])) {
						$temp_menu_items = $GLOBALS['menu_items'];
					}
					foreach($GLOBALS['site_parameters']['menu_custom_submenus'] as $this_main_menu_items_value => $this_menu_items_value) {
						if(isset($GLOBALS['main_menu_items'][$this_main_menu_items_value])) {
							// Un sous menu est configuré pour ce menu principal
							if(StringMb::strpos($this_menu_items_value, 'menu_html_') === 0) {
								$this_output = affiche_contenu_html($this_menu_items_value, true);
								if(!empty($this_output)) {
									$GLOBALS['submenu_html_array'][$this_main_menu_items_value] = '
							<ul class="sousMenu dropdown-menu" role="menu">
								<li>
									<div class="yamm-content">
										' . $this_output . '
									</div>
								</li>
							</ul>';
								}
							} else {
								foreach(explode(',',str_replace(' ', '', $this_menu_items_value)) as $this_submenu) {
									// Le différents liens du sous menu sont séparés par des virgules
									if(!empty($GLOBALS['site_parameters']['menu_custom_urls'][$this_submenu]) && !empty($GLOBALS['site_parameters']['menu_custom_titles'][$this_submenu])) {
										// modèle pour menu_custom_urls :  technical_code_main_menu_items1 => "http://www.url1.fr", technical_code_main_menu_items2 => "http://www.url2.fr"
										// modèle pour menu_custom_titles : technical_code_main_menu_items1 => "STR_XXXXX", technical_code_main_menu_items2 => "STR_XXX"
										$temp_menu_items[$this_main_menu_items_value][$GLOBALS['site_parameters']['menu_custom_urls'][$this_submenu]] = $GLOBALS[$GLOBALS['site_parameters']['menu_custom_titles'][$this_submenu]];
									} elseif(!empty($GLOBALS['site_parameters']['menu_custom_submenus_keep_default'])) {
										unset($temp_menu_items[$this_main_menu_items_value]);
									}
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
				// $this_main_url peut être vide, auquel cas il n'y aura pas de lien direct à partir du menu
				if($avoid_links_when_hover && (!empty($GLOBALS['menu_items'][$this_main_item]) || !empty($GLOBALS['submenu_html_array'][$this_main_item]))) {
					// On retire le lien sur le menu principal pour le mettre sur un élément en haut du menu si pas existant
					if(!empty($GLOBALS['menu_items'][$this_main_item]) && empty($GLOBALS['menu_items'][$this_main_item][$this_main_url])) {
						$GLOBALS['menu_items'][$this_main_item] = array_merge(array($this_main_url => $this_main_title), $GLOBALS['menu_items'][$this_main_item]);
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
						'id' => 'menu_' . StringMb::substr(md5($this_main_item.'_'.$this_main_title.'_'.$this_main_url), 0, 4),
						'label' => $this_main_title,
						'href' => (!empty($this_main_url) && !is_numeric($this_main_url)) ? ($this_main_url != get_current_url(true) || !empty($_POST)? $this_main_url : '#') : false,
						'selected' => ($current_menu !== false || !empty($this_main_array[$current_url]) || !empty($this_main_array[$current_url_full]) || StringMb::strpos(vb($GLOBALS['submenu_html_array'][$this_main_item]),'class="minus active"')!==false),
						'submenu_global' => vb($GLOBALS['submenu_html_array'][$this_main_item]),
						'class' => vb($GLOBALS['site_parameters']['main_menu_custom_classes'][$this_main_item]),
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
		$tpl->assign('MODULES_HEADER_TOP_MENU', get_modules('header_top_menu', true, null, vn($_GET['catid'])));
		$tpl->assign('menu', template_tags_replace($menu));
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
				if(StringMb::strpos($this_flag, '/') === false) {
					$this_flag = '/lib/flag/' . $this_flag;
				}
				if(StringMb::substr($this_flag, 0, 1) == '/' && StringMb::substr($this_flag, 0, 2) != '//') {
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
			$flag = '<img src="' . $GLOBALS['wwwroot'] . '/lib/flag/' . strtolower($country_code) . '.gif" width="18" height="12" alt="" title="' . StringMb::str_form_value($country_name) . '" /> ';
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
		$tpl->assign('notification_output', implode('', $GLOBALS['notification_output_array']));
		if (empty($full_head_section_text) && $add_general_css_js_files) {
			if(!empty($GLOBALS['site_parameters']['bootstrap_enabled'])) {
				$GLOBALS['js_files'][-100] = get_url('/lib/js/jquery.js');
				$GLOBALS['css_files'][] = get_url('/lib/css/bootstrap.css');
				$GLOBALS['js_files'][] = get_url('/lib/js/bootstrap.js');
			}
			foreach (get_array_from_string($GLOBALS['site_parameters']['css']) as $this_css_filename) {
				$this_css_file = trim($this_css_filename);
				if (StringMb::strpos($this_css_file, '//') !== false) {
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
			echo StringMb::convert_encoding($output, $convert_to_encoding);
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
			// Catégories de produits
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
					$tmp['items'][StringMb::strtoupper($cat['nom_' . $_SESSION['session_langue']]).md5($urlcat)] = array('href' => $urlcat,
						'name' => $cat['nom_' . $_SESSION['session_langue']],
						'count' => $Count['this_count']
						);
				}
			}
			// Rubriques de contenu
			$sqlCat = "SELECT r.id, r.nom_" . $_SESSION['session_langue'] . ", r.image
				FROM peel_rubriques r
				WHERE r.etat = '1' AND r.nom_" . $_SESSION['session_langue'] . " LIKE '" . nohtml_real_escape_string($value) . "%' AND " . get_filter_site_cond('rubriques', 'r') . "";
			$resCat = query($sqlCat);
			while ($cat = fetch_assoc($resCat)) {
				$id = $cat['id'];
				$tmp['items'][StringMb::strtoupper($cat['nom_' . $_SESSION['session_langue']]).md5(get_content_category_url($id, $cat['nom_' . $_SESSION['session_langue']]))] = array('href' => get_content_category_url($id, $cat['nom_' . $_SESSION['session_langue']]),
					'name' => $cat['nom_' . $_SESSION['session_langue']],
					'count' => calcul_nbarti_parrub($id)
					);
			}
			$map[$value] = $tmp;
		}
		$general_items[] = array('href' => get_url('cgv'), 'name' => $GLOBALS['STR_CGV']);
		$general_items[] = array('href' => get_url('legal'), 'name' => $GLOBALS['STR_LEGAL_INFORMATION']);
		$general_items[] = array('href' => get_url('sitemap'), 'name' => $GLOBALS['STR_SITEMAP']);
		foreach($general_items as $this_item) {
			$alpha = StringMb::substr(StringMb::strtoupper(StringMb::convert_accents($this_item['name'])), 0, 1);
			$map[$alpha]['value'] = $alpha;
			$map[$alpha]['items'][StringMb::strtoupper($this_item['name']).md5($this_item['href'])] = $this_item;
		}
		foreach($map as $this_key => $this_map) {
			ksort($map[$this_key]['items']);
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
			$tpl->assign('installation_links', affiche_flags(true, get_url('/installation/index.php'), true, $GLOBALS['admin_lang_codes'], true, null));
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
			$custom_template_tags['MAP_LANG'] = $_SESSION['session_langue'];
			$tag = StringMb::htmlentities(StringMb::html_entity_decode(template_tags_replace($access_plan_infos['map_tag'], $custom_template_tags)), ENT_COMPAT, GENERAL_ENCODING, false, true);
			$longtext = StringMb::nl2br_if_needed(StringMb::html_entity_decode_if_needed($access_plan_infos['texte']));
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
		$tpl->assign('href', get_contact_url(false, false));
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

if (!function_exists('is_flash_active_on_site')) {
	/**
	 * Renvoie si les prix flash sont activés ou non sur le site
	 * NO_TPL is_flash_active_on_site function is not a view formatting function
	 */
	function is_flash_active_on_site()
	{
		return (check_if_module_active('flash') && !empty($GLOBALS['site_parameters']['module_flash']));
	}
}

if (!function_exists('get_newsletter_form')) {
	/**
	 * get_newsletter_form()
	 *
	 * @param string $value
	 * @return
	 */
	function get_newsletter_form($value = null)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('newsletter_form.tpl');
		$tpl->assign('form_token', get_form_token_input('get_simple_newsletter', true));
		$tpl->assign('label', $GLOBALS['STR_NEWSLETTER'] . $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('default', $GLOBALS['STR_WRITE_EMAIL_HERE']);
		$tpl->assign('value', $value);
		return $tpl->fetch();
	}
}

if (!function_exists('newsletter_desinscription_form')) {
	/**
	 * newsletter_desinscription_form()
	 *
	 * @param array $frm
	 * @param object $form_error_object
	 * @return
	 */
	function newsletter_desinscription_form(&$frm, $form_error_object)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('newsletter_desinscription_form.tpl');
		$tpl->assign('header', $GLOBALS['STR_DESINSCRIPTION_NEWSLETTER']);
		$tpl->assign('action', get_current_url());
		$tpl->assign('label', $GLOBALS['STR_EMAIL'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('error', $form_error_object->text('email'));
		$tpl->assign('email', StringMb::str_form_value(vb($frm['email'])));
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
	function newsletter_validation(&$frm, &$form_error_object)
	{
		$message = "";
		$tpl = $GLOBALS['tplEngine']->createTemplate('newsletter_validation.tpl');
		$tpl->assign('header', $GLOBALS['STR_NEWSLETTER_TITLE']);
		if (!empty($frm) && !$form_error_object->count()) {
			// MAJ du compte client s'il existe
			$q_count_users = query("SELECT COUNT(id_utilisateur) AS nb_users
				FROM peel_utilisateurs
				WHERE email = '" . word_real_escape_string($frm['email']) . "' AND " . get_filter_site_cond('utilisateurs') . "");
			$r_count_users = fetch_assoc($q_count_users);
			if (empty($r_count_users['nb_users'])) {
				$frm['priv'] = 'newsletter';
				insere_utilisateur($frm);
			}
			$custom_template_tags['EMAIL'] = $frm['email'];
			// Envoi d'un email confirmant l'inscription à la newsletter
			
			// double optin pour l'inscription à la newsletter
			$custom_template_tags['TYPE'] = $GLOBALS["STR_TO_NEWSLETTER"];
			$custom_template_tags['CONFIRM_NEWSLETTER_REGISTER_LINK'] = $GLOBALS['wwwroot'].'/utilisateurs/newsletter.php?mode=subscribe_newsletter&email='.$frm['email'];
			send_email($frm['email'], '', '', 'confirm_newsletter_registration',$custom_template_tags);

			$message .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_REQUEST_OK'] . ' ' . $GLOBALS['STR_SEE_YOU_SOON'] . ' ' . $GLOBALS['wwwroot'] . '/'))->fetch();
		} else {
			if(!empty($frm)) {
				foreach ($form_error_object->error as $key => $error) {
					$message .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $error))->fetch();
				}
			}
			$message .= get_newsletter_form(vb($frm['email']));
		}
		$tpl->assign('message', $message);
		return $tpl->fetch();
	}
}

if (!function_exists('affiche_contenu_html')) {
	/**
	 * Récupère le contenu de la zone HTML ou des zones HTML correspondant à un emplacement donné
	 * 
	 * @param mixed $place
	 * @param boolean $return_mode
	 * @param array $custom_template_tags
	 * @param boolean $get_title_only
	 * @return
	 */
	function affiche_contenu_html($place, $return_mode = false, $custom_template_tags = null, $get_title_only = false)
	{
		$output = '';
		$sql_cond_array[] = 'etat="1"';
		$sql_cond_array[] = get_filter_site_cond('html');
		$sql_cond_array[] = '(lang="' . $_SESSION['session_langue'] . '" OR lang="")';
		
		$emplacement_sql_cond[] = 'emplacement="' . nohtml_real_escape_string($place) . '"';
		if (!empty($_SESSION['session_site_country'])) {
			$emplacement_sql_cond[] = 'emplacement="' . nohtml_real_escape_string($place) . '|country='.$_SESSION['session_site_country'].'"';
		}
		if (!empty($GLOBALS['site_parameters']['site_suspended'])) {
			// On regarde les emplacements correspondant au site suspendu.
			$emplacement_sql_cond[] = 'emplacement="' . nohtml_real_escape_string($place) . '|site_suspended"';
		}
		if (est_identifie()) {
			$emplacement_sql_cond[] = 'emplacement="' . nohtml_real_escape_string($place) . '|login"';
		} else {
			$emplacement_sql_cond[] = 'emplacement="' . nohtml_real_escape_string($place) . '|logout"';
		}

		$sql_cond_array[] = '('. implode(' OR ', $emplacement_sql_cond) . ')';
		
		$GLOBALS['affiche_contenu_html_last_found'] = false;
		$sql = 'SELECT *
			FROM peel_html
			WHERE ' . implode(' AND ', $sql_cond_array) . '
			GROUP BY SUBSTRING(emplacement,1,' . StringMb::strlen($place).')
			ORDER BY LENGTH(emplacement) DESC, a_timestamp DESC';
		$query = query($sql);
		while ($obj = fetch_object($query)) {
			if(!empty($last_emplacement) && StringMb::strlen($obj->emplacement) < StringMb::strlen($last_emplacement)) {
				// On a déjà chargé du contenu spécifique pour un pays, on ne veut pas prendre de contenu non-spécifique à ce pays, donc on s'arrête
				break;
			}
			// On préserve le HTML mais on corrige les & isolés
			if($get_title_only) {
				$output .= $obj->titre;
			} else {
				$output .= StringMb::htmlentities(StringMb::html_entity_decode_if_needed($obj->contenu_html), ENT_COMPAT, GENERAL_ENCODING, false, true);
			}
			$last_emplacement = $obj->emplacement;
			$GLOBALS['affiche_contenu_html_last_found'] = true;
		}
		if(!empty($output)) {
			$output = template_tags_replace($output, $custom_template_tags, false, 'html');
			correct_output($output, false, 'html', $_SESSION['session_langue']);
		}
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
				$share_item_array = array('twitter', 'google_plusone_share', 'facebook', 'pinterest_share');
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
			// NB : Le fichier d'origine est https://s7.addthis.com/js/300/addthis_widget.js (On appelle addthis en https pour éviter problème d'alerte de sécurité du navigateur si le site est en https.)
			// Là on met le fichier directement sur le site en changeant son nom pour éviter le filtrage par Adblock Plus "Fanboy's Social Blocking List" qui empêche l'exécution du reste du javascript si on charge en mode asynchrone
			$GLOBALS['js_files_nominify'][] = get_url('/lib/js/add_widget300_this.js');
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
			$tmpdiapo['image'] =  get_url_from_uploaded_filename($img_diapo["image"]);
			$tmpdiapo['thumbs'] = thumbs($img_diapo["image"], 175, 275, 'fit', null, null, true, true);
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

if (!function_exists('get_search_form')) {
	/*
	 *
	 * @param string $search
	 * @param string $match
	 * @param string $real_search
	 * @param array $frm
	 * @return
	 */
	function get_search_form($frm = null, $search=null, $match = null, $real_search = false, $display = 'full', $quick_add_product_from_search_page = null) {
		$tpl_f = $GLOBALS['tplEngine']->createTemplate('search_form.tpl');
		$tpl_f->assign('STR_SEARCH_PRODUCT', $GLOBALS['STR_SEARCH_PRODUCT']);
		$tpl_f->assign('action', get_url('search'));
		$tpl_f->assign('value', $search);
		$tpl_f->assign('match', $match);
		$tpl_f->assign('match_display', empty($GLOBALS['site_parameters']['search_match_disable']));
		$tpl_f->assign('display', $display);
		$tpl_f->assign('search', StringMb::strtoupper($real_search));
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
		$additional_search_form_parts_array = call_module_hook('search_form_part', array('frm' => $frm), 'array');
		$tpl_f->assign('additional_search_form_parts_array', $additional_search_form_parts_array);

		if (!empty($_GET['load_saved_products_list'])) {
			$sql = "SELECT * 
				FROM peel_save_cart 
				WHERE saved_products_list_id='".intval($_GET['load_saved_products_list'])."'";
			if ($query = query($sql)) {
				// suppression de la liste en cours pour charger une autre liste à la place.
				unset($_SESSION['session_search_product_list']);
				while ($result = fetch_assoc($query)) {
					$products_list[$result['produit_id']] = $result['quantite'];
					// Par simplicité, chaque ligne contient le nom de la liste à laquelle il appartient.
					$products_list_name = $result['products_list_name'];
				}
			}
		} elseif (!empty($_SESSION['session_search_product_list'])) {
			$products_list = $_SESSION['session_search_product_list'];
		}
		$tpl_f->assign('products_list_name', vb($products_list_name));
		if (!empty($products_list)) {
			$i=0;
			foreach ($products_list as $product_id=>$quantity) {
					$product_object = new Product($product_id);
					$display_picture = $product_object->get_product_main_picture(false);
					if ($display_picture) {
						$product_picture = thumbs($display_picture, 75, 75, 'fit', null, null, true, true);
					} else {
						$product_picture = thumbs($GLOBALS['site_parameters']['default_picture'], 75, 75, 'fit', null, null, true, true);
					}
					$produits_options[] = array(
						'ref' => $product_object->reference,
						'category_name' => $product_object->categorie,
						'nom_produit' => $product_object->name,
						'href_produit' => $product_object->get_product_url(),
						'quantite' => $quantity,
						'id' => $product_object->id,
						'photo_src' => vb($product_picture),
						'barcode_image_src' => $product_object->barcode_image_src(),
						'brand_link_html' => (!empty($product_object->id_marque)?get_brand_link_html($product_object->id_marque):''),
						'href_category' => get_product_category_url($product_object->categorie_id, $product_object->categorie),
						'marque' => $product_object->get_product_brands(false),
						'prix' => $product_object->get_original_price(display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), false),
						'ean_code' => $product_object->ean_code,
						'reference' => $product_object->reference,
						'minimal_price' => $product_object->get_minimal_price(false),
						'product_id' => $product_object->id,
						'i' => $i
					);
				$i++;
			}
		}
		if (!empty($quick_add_product_from_search_page)) {
			$sqlPays = 'SELECT p.id, p.pays_' . $_SESSION['session_langue'] . ' as pays, p.zone, z.tva, z.on_franco
				FROM peel_pays p
				LEFT JOIN peel_zones z ON z.id=p.zone AND ' . get_filter_site_cond('zones', 'z', true) . '
				WHERE p.etat = "1" AND p.id ="' . nohtml_real_escape_string(vb($_SESSION['session_utilisateur']['pays'])) . '"
				LIMIT 1';
			$query = query($sqlPays);
			if ($result = fetch_assoc($query)) {
				$user_vat = $result['tva'];
			} else {
				$user_vat = 1;
			}

			$tpl_f->assign('zone_tva', ($user_vat && !is_user_tva_intracom_for_no_vat(vn($_SESSION['session_utilisateur']['id_utilisateur'])) && !check_if_module_active('micro_entreprise')));
			$tpl_f->assign('id_utilisateur', vn($_SESSION['session_utilisateur']['id_utilisateur']));
			$tpl_f->assign('devise', $_SESSION['session_devise']['code']);
			$tpl_f->assign('currency_rate', $_SESSION['session_devise']['conversion']);
			$tpl_f->assign('rpc_path', get_url('/modules/search/produit.php'));
			$tpl_f->assign('produits_options', vb($produits_options));
			$tpl_f->assign('STR_SCAN_CODE_AND_ADD_LIST', $GLOBALS['STR_SCAN_CODE_AND_ADD_LIST']);
			$tpl_f->assign('quick_add_product_from_search_page', vb($quick_add_product_from_search_page));
			$tpl_f->assign('STR_WHAT_DO_YOU_DO_WITH_THAT_LIST', $GLOBALS['STR_WHAT_DO_YOU_DO_WITH_THAT_LIST']);
			$tpl_f->assign('STR_MODULE_FACTURES_ADVANCED_EXPORT_LIST_PDF', $GLOBALS['STR_MODULE_FACTURES_ADVANCED_EXPORT_LIST_PDF']);
			$tpl_f->assign('display_barcode', !empty($GLOBALS['site_parameters']['display_ean_code_on_product_list']));
			$tpl_f->assign('search_product_list_save_cart', vb($GLOBALS['site_parameters']['search_product_list_save_cart']));
		}
		$hook_result = call_module_hook('search_form_template_data', array('frm' => $frm, 'search' => $search, 'match' => $match, 'real_search' => $real_search, 'display' => $display, 'quick_add_product_from_search_page' => $quick_add_product_from_search_page), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl_f->assign($this_key, $this_value);
		}
		return $tpl_f->fetch();
	}
}

if (!function_exists('get_address_list')) {
	/**
	 * Affiche le formulaire de création d'adresse
	 *
	 * @param integer $user_id
	 * @param boolean $in_admin
	 * @return
	 */
	function get_address_list($user_id, $in_admin = false) {
		$output = '';
		if(empty($user_id)) {
			return false;
		}
		if (!$in_admin) {
			$q = query('SELECT email, nom_famille, prenom, adresse, code_postal, ville, telephone, portable, pays, address_bill_default, address_ship_default
				FROM peel_utilisateurs
				WHERE id_utilisateur="' . intval($user_id) . '"');
			$result = fetch_assoc($q);
			$address_select_array[] = '<p>' . $GLOBALS['STR_INVOICE_ADDRESS'] . ' ' . $GLOBALS['STR_DEFAULT'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . get_personal_address_form(vn($_SESSION['session_utilisateur']['id_utilisateur']), 'bill', vb($_SESSION['session_utilisateur']['address_bill_default']), false, 'max-width:300px;') . '</p>
	';
			if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
				$address_select_array[] = '<p>' . $GLOBALS['STR_SHIP_ADDRESS'] . ' ' . $GLOBALS['STR_DEFAULT'] . '' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . get_personal_address_form(vn($_SESSION['session_utilisateur']['id_utilisateur']), 'ship', vb($_SESSION['session_utilisateur']['address_ship_default']), false, 'max-width:300px;') . '</p>
	';
			}
			$output .= '
	<h1>'.$GLOBALS['STR_ADDRESS_TEXT'].'</h1>
	<p>'.$GLOBALS['STR_INFO_ADDRESS'].'</p>
	<form id="address_default" method="post" action="' . StringMb::str_form_value(get_current_url(false)) . '" class="entryform form-inline">
		' . implode('', $address_select_array). '
	</form>
	';

			if(num_rows($q) < vb($GLOBALS['site_parameters']['addresses_per_user_max'], 1000)) {
				// N adresses en plus maximum de l'adresse enregistrée lors de l'inscription.
				$output .= '
				<p><a class="btn btn-primary" href="'.$GLOBALS['wwwroot'].'/utilisateurs/adresse.php?mode=create_new_address" title="'.$GLOBALS['STR_REGISTER_ORDER_ADDRESS'].'">'.$GLOBALS['STR_REGISTER_ORDER_ADDRESS'].'</a></p>';
			}
			$output .= '
				<div class="row">
					<div class="col-sm-6 col-md-4">
						<div class="well">
							<h2>'.$GLOBALS['STR_DEFAULT_ADDRESS'].'</h2> 
							<div>'.$result['prenom'].' '.strtoupper($result['nom_famille']).'</div>
							<div>'.$result['adresse'].'</div>
							<div>'.$result['code_postal'].' '.$result['ville'].'</div>
							<div>'.get_country_name($result['pays']).'</div>
							<div>'.$result['telephone'].'</div>
							<div>'.$result['email'].'</div>
							<div style="margin-top:10px;"><a class="btn btn-default" href="'.$GLOBALS['wwwroot'].'/utilisateurs/change_params.php" title="'.$GLOBALS['STR_MODIFY'].'">'.$GLOBALS['STR_MODIFY'].'</a></div>
						</div>
					</div>
';
		} else {
			// L'administrateur peut créer autant d'adresses sans être limité.
			$output .= '
			<p class="col-md-12" style="margin-top:10px;"><a class="btn btn-primary" href="'.$GLOBALS['administrer_url'].'/utilisateurs.php?mode=create_new_address&id_utilisateur='.$user_id.'" title="'.$GLOBALS['STR_REGISTER_ORDER_ADDRESS'].'">'.$GLOBALS['STR_REGISTER_ORDER_ADDRESS'].'</a></p>';
		}
		$q = query('SELECT *
			FROM peel_adresses
			WHERE id_utilisateur = "' . intval($user_id) . '" AND address_type NOT LIKE "private_%"');
		$i=1;
		while($result = fetch_assoc($q)) {
			if (!empty($in_admin)) {
				$url_modify = $GLOBALS['administrer_url'].'/utilisateurs.php?mode=modif_address&id='.$result['id'].'&id_utilisateur='.$user_id;
				$url_delete = $GLOBALS['administrer_url'].'/utilisateurs.php?mode=suppr_address&id='.$result['id'].'&id_utilisateur='.$user_id;
			} else {
				$url_modify = $GLOBALS['wwwroot'].'/utilisateurs/adresse.php?mode=modif_address&id='.$result['id'];
				$url_delete = $GLOBALS['wwwroot'].'/utilisateurs/adresse.php?mode=suppr_address&id='.$result['id'];
			}
			$output .= '
				<div class="col-sm-6 col-md-4">
					<div class="well">
						<h2>'.$result['nom'] . ($result['address_type']=='bill'?' ('.$GLOBALS['STR_INVOICE_ADDRESS'].')':($result['address_type']=='ship'?' ('.$GLOBALS['STR_SHIP_ADDRESS'].')':($result['address_type']=='ad'?' ('.$GLOBALS['STR_MODULE_ANNONCES_AD'].')':''))).'</h2>
						<div>'.$result['prenom'].' '.$result['nom_famille'].'</div>
						<div>'.$result['adresse'].'</div>
						<div>'.$result['code_postal'].' '.$result['ville'].'</div>
						<div>'.get_country_name($result['pays']).'</div>
						<div>'.$result['portable'].'</div>
						<div>'.$result['email'].'</div>
						<div style="margin-top:10px;">
							<a class="btn btn-warning" data-confirm="' . StringMb::str_form_value($GLOBALS["STR_DELETE_CONFIRM"]) . '" style="width:auto;" href="'.$url_delete.'" title="'.StringMb::str_form_value($GLOBALS['STR_DELETE']).'">'.$GLOBALS['STR_DELETE'].'</a>
							<a class="btn btn-default" href="'.$url_modify.'" title="'.StringMb::str_form_value($GLOBALS['STR_MODIFY']).'">'.$GLOBALS['STR_MODIFY'].'</a>
						</div>
					</div>
				</div>
';
			if ($in_admin && $i%3 == 0) {
				// Pour gérer le bon affichage dans l'admin, c'est ok en front.
				$output .= '
				<span class="clearfix"></span>
';
			}
			$i++;
		}
		$output .= '
			</div>
';
		return $output;
	}
}
