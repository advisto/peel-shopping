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
// $Id: fonctions.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

// Définition du tableau de critère SQL (champ => Valeur)
$GLOBALS['page_types_array'] = array('home_page', 'first_page_category', 'other_page_category', 'ad_page_details', 'search_engine_page');

/**
 * affiche_banner()
 *
 * @param integer $position
 * @param boolean $return_mode
 * @param integer $page
 * @param integer $cat_id
 * @param integer $this_annonce_number
 * @param boolean $page_type
 * @param mixed $keywords_array
 * @param string $lang
 * @param boolean $return_array_with_raw_information
 * @param integer $ad_id
 * @param integer $page_related_to_user_id
 * @return
 */
function affiche_banner($position = null, $return_mode = false, $page = null, $cat_id = null, $this_annonce_number = 0, $page_type=null, $keywords_array=null, $lang = null, $return_array_with_raw_information = false, $ad_id=null, $page_related_to_user_id = null)
{
	$output = '';
	$cmp = 0;
	$sql_cond = '';
	$style_banner = '';
	$banner_position = '';
	$mobile_application_output_array = array();
	if (empty($lang)) {
		$lang = $_SESSION['session_langue'];
	}

	// Taille par défaut des bannières à modifier en fonction du template du site.
	if (!empty($GLOBALS['page_columns_count']) && $GLOBALS['page_columns_count'] == 2) {
		$max_banner_width = 750;
		$max_banner_height = 748;
	} else {
		$max_banner_width = 1200;
		$max_banner_height = 748;
	}
	if (is_module_banner_active()) {
		// Si le champ catégorie est renseigné, alors on prend les bannières pour la catégorie définie OU sans catégorie définie
		// Par la suite dans le tri, on sélectionne en priorité les bannières avec id_catégorie précisée
		if(!empty($page_related_to_user_id)){
			$sql_cond .= ' AND CONCAT(",",do_not_display_on_pages_related_to_user_ids_list,",") NOT LIKE ("%,' . intval($page_related_to_user_id) . ',%")';
		}
		if(!empty($cat_id)){
			$sql_cond .= ' AND id_categorie IN ("0", "' . intval($cat_id) . '")';
		}else{
			$sql_cond .= ' AND id_categorie="0"';
		}
		if (!empty($this_annonce_number)) {
			// annonce_number indique la position de la publicité dans une liste d'annonces
			$sql_cond .= ' AND annonce_number="' . intval($this_annonce_number) . '"';
		}
		// On prend les bannières qui correspondent au type de page recherché
		// Si $page_type === null, on ne tient pas compte de ce paramètre : a priori, on ne met jamais $page_type à null
		if(in_array($page_type, $GLOBALS['page_types_array'])) {
			// Type de page connu => on prend les bannières qui correspondent
			$sql_cond .= ' AND on_'.$page_type .'=1';
		} elseif($page_type!==null) {
			// Type de page non listé => rentre dans le cadre de on_other_page
			$sql_cond .= ' AND on_other_page=1';
		}
		if(!empty($keywords_array)) {
			// On veut aussi chercher si keywords vaut "" et pas juste les bannières pour ce mot clé
			$keywords_array[]='';
			$sql_cond .= ' AND ' . build_terms_clause(array_unique($keywords_array), array('keywords'), 2);
			// On ne met pas en cache les bannières dans ce cas, sinon le nombre de fichiers de cache peut devenir déraisonnable
			$disable_cache = true;
		} else {
			$sql_cond .= ' AND keywords=""';
		}
		// Alternance de bannière pair/impair sur le dernier chiffre de l'id de l'annonce ou de la page d'annonce pour une catégorie. Le choix du type de page est fait précédemment dans la requête.
		if ((defined('IN_CATALOGUE_ANNONCE') || defined('IN_IPHONE_ADS_MODULE')) && !empty($page)) {
			// pour une catégorie
			$tested_number = String::substr($page, -1);
		} elseif ((defined('IN_CATALOGUE_ANNONCE_DETAILS') || defined('IN_IPHONE_ADS_MODULE')) && !empty($ad_id)) {
			$tested_number = String::substr($ad_id, -1);
			// pour une annonce
		}
		if(isset($tested_number)){
			// pages_allowed = odd => bannière impair
			// pages_allowed = even => bannière pair
			if($tested_number %2 == 0) {
				$sql_cond .= ' AND pages_allowed IN ("all","even")';
			} elseif($tested_number %2 == 1) {
				$sql_cond .= ' AND pages_allowed IN ("all","odd")';
			}
		}
		if($return_array_with_raw_information){
			$disable_cache = true;
		}
		$sql_where = "WHERE etat='1' " . (!empty($position) && is_numeric($position)?" AND position='" . intval($position) . "'":"") . $sql_cond . " AND (lang='" . nohtml_real_escape_string($lang) . "' OR lang='')";

		if(empty($disable_cache)) {
			$cache_id = md5($sql_where);
			$this_cache_object = new Cache($cache_id, array('group' => 'affiche_banner_data'));
		}
		if (!empty($this_cache_object) && $this_cache_object->testTime(1800, true)) {
			// On récupère le contenu du cache avec d'abord les id des bannières espacées par des virgules, et ensuite le contenu HTML
			$temp = explode('{'.$cache_id.'}',$this_cache_object->get());
			if(!empty($temp[1])){
				foreach(explode(',',$temp[0]) as $this_banner_id){
					$GLOBALS['viewed_banners_array'][]=intval($this_banner_id);
				}
				$output.=$temp[1];
			}
		} else {
			$queryBanner = query("SELECT *
				FROM peel_banniere
				" . $sql_where . " AND date_fin>='" . date('Y-m-d') . "'
				ORDER BY rang ASC, id_categorie DESC, RAND() ASC");
			while ($banner = fetch_assoc($queryBanner)) {
				if(is_annonce_module_active() && defined('IN_CATALOGUE_ANNONCE_DETAILS') && !empty($banner['list_id']) && !empty($ad_id) && (String::strpos($banner['list_id'], String::substr($ad_id, -1)) === false)) {
					// Sélection d'annonce en fonction du dernier chiffre de l'id d'une annonce. Si une liste d'id est définie, et que l'id courante n'est pas trouvée dans la liste, on passe
					continue;
				}

				if ($return_array_with_raw_information) {
					if (!empty($banner['annonce_number'])) {
						// Un numéro d'annonce est défini, l'annonce doit apparaitre dans la liste d'annonce, à partir du numéro spécifié.
						$banner_position = $banner['annonce_number'];
					} else {
						// Le champ location contient le nom de l'emplacement du module. L'application pourra ainsi positionner la bannière au bon endroit.
						$q = query('SELECT location
							FROM peel_modules
							WHERE technical_code = "advertising' . intval($banner['position']) . '" AND etat = 1');
						if($result = fetch_assoc($q)) {
							$banner_location = $result['location'];
							// Traitement des positions des bannières. Si la bannière est associée à un module prévu pour se placer en haut d'une page, il contient top dans son nom par convention de nommage. La règle est la même pour les modules en bas de page. Il est donc possible de spécifier les seules positions gérées par l'application
							if (String::strpos($banner_location, 'top') !== false) {
								$banner_position = 'top'; 
							} elseif(String::strpos($banner_location, 'bottom') !== false) {
								$banner_position = 'bottom'; 
							}
						}
					}
					$mobile_application_output_array[] = array("url_img" => $GLOBALS['repertoire_upload'] . '/' . $banner['image'], "url" => $GLOBALS['wwwroot'] . '/modules/banner/bannerHit.php?id=' . $banner['id'] , "html"=> vb($banner['tag_html']), "position" => $banner_position);
				} elseif (!isset($last_rang) || $banner['rang'] != $last_rang) {
					// On affiche une seule bannière par rang
					// Nous récuperons la dimension de la bannière souhaitée et appliquons les limites initialiséss plus haut
					$width = min(intval($banner['width']), $max_banner_width);
					$height = min(intval($banner['height']), $max_banner_height);
					if (empty($banner['tag_html'])) {
						// Recupération de l'extension
						$extension = @pathinfo($banner['image'], PATHINFO_EXTENSION);
						if ($extension == 'swf') {
							// Il faut spécifier une taille quoiqu'il arrive quand on a du flash
							if (empty($width)){
								$width = '100%';
							}
							if (empty($height)){
								$height = '300';
							}
							$banner_html = getFlashBannerHTML($GLOBALS['repertoire_upload'] . '/' . $banner['image'], $width, $height);
						} else {
							// Si la taille de la bannière est définie, alors nous appliquons le style de la banniere
							$style_banner = '';
							if (!empty($width)){
								$style_banner .= ' width="' . $width . '" ';
							}
							if (!empty($height)){
								$style_banner .= ' height="' . $height . '" ';
							}
							$banner_html = '<img src="' . $GLOBALS['repertoire_upload'] . '/' . $banner['image'] . '" alt="' . vb($banner['lien']) . '" ' . $style_banner . ' />';
						}
						if (!empty($banner['lien'])) {
							$banner_html = '<a href="' . $GLOBALS['wwwroot'] . '/modules/banner/bannerHit.php?id=' . $banner['id'] . '" ' . $banner['extra_javascript'] . ' ' . (!empty($banner['target']) && $banner['target'] != '_self' ? ($banner['target'] == '_blank' && String::strpos($banner['extra_javascript'], 'onclick=') === false?'onclick="return(window.open(this.href)?false:true);"':'target="' . $banner['target'] . '"'):'') . '>' . $banner_html . '</a>';
						}
					} else {
						// On préserve le HTML mais on corrige les & isolés
						$banner_html = String::htmlentities($banner['tag_html'], ENT_COMPAT, GENERAL_ENCODING, false, true);
					}
					$output .= '<div class="publicite" style="margin-top:3px;">' . $banner_html . '</div>';
					$last_rang = $banner['rang'];
				}
				// Attention : these_banners_id_array est local, alors que viewed_banners_array est global et contient les autres bannières de la page
				$these_banners_id_array[]=$banner['id'];
				$GLOBALS['viewed_banners_array'][]=$banner['id'];

			}
			if (!empty($this_cache_object)) {
				if(!empty($output)){
					$this_cache_object->save(implode(',',$these_banners_id_array).'{'.$cache_id.'}'.$output);
				}else{
					$this_cache_object->save('');
				}
			}
		}
		if (!empty($this_cache_object)) {
			unset($this_cache_object);
		}
		if(String::strpos($output, 'googlesyndication')!==false && (String::strpos($output, 'x90')===false && String::strpos($output, 'x15')===false)) {
			if(vn($GLOBALS['google_pub_count']) >= 3 || !empty($GLOBALS['disable_google_ads'])) {
				// On ne doit pas afficher plus de 3 espaces Google hors pubs listes de mots clés, de hauteur x90 ou x15 ou ne pas afficher de bannière adsense si $GLOBALS['disable_google_ads'] est true. $GLOBALS['disable_google_ads'] est défini à false dans les cas de figure défini par la fonction is_adsense_compliant
				$output='';
			} else {
				$GLOBALS['google_pub_count']++;
			}
		}
		$output = template_tags_replace($output, array(), false, 'html');
	}

	if ($return_array_with_raw_information) {
		return $mobile_application_output_array;
	} elseif ($return_mode) {
		return $output;
	} else {
		echo $output;
	}
}

/**
 * get_possible_ad_positions()
 *
 * @param mixed $position
 * @param mixed $cat_id
 * @param mixed $page
 * @param boolean $page_type
 * @return
 */
function get_possible_banner_positions_between_ads($position, $cat_id, $page, $page_type=null)
{
	$sql_cond = '';
	// Si le champ catégorie est renseigné, alors on prend les bannières pour la catégorie définie OU sans catégorie définie
	// Par la suite dans le tri, on sélectionne en priorité les bannières avec id_catégorie précisée
	if(!empty($cat_id)){
		$sql_cond .= ' AND id_categorie IN ("0", "' . intval($cat_id) . '")';
	}else{
		$sql_cond .= ' AND id_categorie="0"';
	}

	// On prend les bannières qui correspondent au type de page recherché
	// Si $page_type === null, on ne tient pas compte de ce paramètre : a priori, on ne met jamais $page_type à null
	if(in_array($page_type, $GLOBALS['page_types_array'])) {
		// Type de page connu => on prend les bannières qui correspondent
		$sql_cond .= ' AND on_'.$page_type .'=1';
	}elseif($page_type!==null){
		// Type de page non listé => rentre dans le cadre de on_other_page
		$sql_cond .= ' AND on_other_page=1';
	}
	$sql_where = "WHERE etat = '1' " . (!empty($position) && is_numeric($position)?" AND position='" . intval($position) . "'":"") . $sql_cond . " AND (lang='" . $_SESSION['session_langue'] . "' OR lang='')";

	$cache_id = md5($sql_where);
	$this_cache_object = new Cache($cache_id, array('group' => 'get_possible_banner_positions_between_ads_data'));
	if ($this_cache_object->testTime(1800, true)) {
		$output = $this_cache_object->get();
	} else {
		$annonce_number_array = array();
		$queryBanner = query("SELECT annonce_number
			FROM peel_banniere
			" . $sql_where . " AND annonce_number>0 AND date_fin>='" . date('Y-m-d') . "'");
		while ($banner = fetch_assoc($queryBanner)) {
			$annonce_number_array[] = $banner['annonce_number'];
		}
		$output = implode(',', $annonce_number_array);
		$this_cache_object->save($output);
	}
	return explode(',', $output);
}

/**
 * Met à jour le compteur "vue" de la table des bannières en une seule requête SQL
 *
 * @return
 */
function update_viewed_banners()
{
	if (!empty($GLOBALS['viewed_banners_array'])) {
		foreach($GLOBALS['viewed_banners_array'] as $this_key => $this_id) {
			$GLOBALS['viewed_banners_array'][$this_key] = intval($this_id);
		}
		query("UPDATE peel_banniere
			SET vue=vue+1
			WHERE id IN('" . implode("','", real_escape_string($GLOBALS['viewed_banners_array'])) . "')");
	}
}

?>