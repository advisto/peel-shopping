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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

// Définition du tableau de critère SQL (champ => Valeur)
$GLOBALS['page_types_array'] = array('home_page', 'first_page_category', 'other_page_category', 'ad_page_details', 'search_engine_page', 'ad_creation_page', 'background_site');

/**
 * Traitement de la fin de la génération d'une page
 *
 * @param array $params
 * @return
 */
function banner_hook_close_page_generation($params) {
	update_viewed_banners();
}

/**
 * Gestion du changement de status d'un élément dans une table si pas prévu par défaut
 *
 * @param array $params
 * @return
 */
function banner_hook_rpc_status(&$params) {
	// Suppression des caches de bannières
	if ($params['mode']=='banner') {
		$this_cache_object = new Cache(null, array('group' => 'affiche_banner_data'));
		$this_cache_object->delete_cache_file(true);
		unset($this_cache_object);
	}
}

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
 * @param boolean $disable_cache
 * @return
 */
function affiche_banner($position = null, $return_mode = false, $page = null, $cat_id = null, $this_annonce_number = 0, $page_type=null, $keywords_array=null, $lang = null, $return_array_with_raw_information = false, $ad_id=null, $page_related_to_user_id = null, $disable_cache = false)
{
	static $is_module_banner_active;
	if(!isset($is_module_banner_active)) {
		$is_module_banner_active = check_if_module_active('banner');
	}
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
	if ($is_module_banner_active) {
		// Si le champ catégorie est renseigné, alors on prend les bannières pour la catégorie définie OU sans catégorie définie
		// Par la suite dans le tri, on sélectionne en priorité les bannières avec id_catégorie précisée
		if(!empty($page_related_to_user_id)){
			$sql_cond .= ' AND CONCAT(",",do_not_display_on_pages_related_to_user_ids_list,",") NOT LIKE ("%,' . intval($page_related_to_user_id) . ',%")';
			$disable_cache = true;
		}
		if(!empty($cat_id)){
			$sql_cond .= ' AND id_categorie IN ("0", "' . intval($cat_id) . '")';
		}else{
			$sql_cond .= ' AND id_categorie="0"';
		}
		if (!empty($this_annonce_number)) {
			// annonce_number indique la position de la publicité dans une liste d'annonces
			$sql_cond .= ' AND annonce_number="' . intval($this_annonce_number) . '"';
			$disable_cache = true;
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
			$tested_number = StringMb::substr($page, -1);
		} elseif ((defined('IN_CATALOGUE_ANNONCE_DETAILS') || defined('IN_IPHONE_ADS_MODULE')) && !empty($ad_id)) {
			$tested_number = StringMb::substr($ad_id, -1);
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
		$sql_where = "WHERE etat='1' " . (!empty($position) && is_numeric($position) ?" AND position='" . intval($position) . "'":"") . $sql_cond . " AND (lang='" . nohtml_real_escape_string($lang) . "' OR lang='')";

		if(empty($GLOBALS['site_parameters']['banner_disable_cache']) && !$disable_cache) {
			$cache_id = md5($sql_where);
			$this_cache_object = new Cache($cache_id, array('group' => 'affiche_banner_data'));
		}
		if (!empty($this_cache_object) && $this_cache_object->testTime(vb($GLOBALS['site_parameters']['banners_cache_duration_in_seconds'], 15*24*3600), true)) {
			// On récupère le contenu du cache avec d'abord les id des bannières espacées par des virgules, et ensuite le contenu HTML
			$temp = explode('{'.$cache_id.'}',$this_cache_object->get());
			if(!empty($temp[1])){
				foreach(explode(',',$temp[0]) as $this_banner_id){
					$GLOBALS['viewed_banners_array'][]=intval($this_banner_id);
				}
				$output.=$temp[1];
			}
		} else {
			$sql_banner = "SELECT *
				FROM peel_banniere
				" . $sql_where . " AND date_fin>='" . date('Y-m-d') . "' AND " . get_filter_site_cond('banniere') . "
				ORDER BY rang ASC, id_categorie DESC, RAND() ASC";
			$queryBanner = query($sql_banner);
			while ($banner = fetch_assoc($queryBanner)) {
				if(check_if_module_active('annonces') && defined('IN_CATALOGUE_ANNONCE_DETAILS') && !empty($banner['list_id']) && !empty($ad_id) && (StringMb::strpos($banner['list_id'], StringMb::substr($ad_id, -1)) === false)) {
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
							WHERE technical_code = "advertising' . intval($banner['position']) . '" AND etat=1 AND ' . get_filter_site_cond('modules'));
						if($result = fetch_assoc($q)) {
							$banner_location = $result['location'];
							// Traitement des positions des bannières. Si la bannière est associée à un module prévu pour se placer en haut d'une page, il contient top dans son nom par convention de nommage. La règle est la même pour les modules en bas de page. Il est donc possible de spécifier les seules positions gérées par l'application
							if (StringMb::strpos($banner_location, 'top') !== false) {
								$banner_position = 'top'; 
							} elseif(StringMb::strpos($banner_location, 'bottom') !== false) {
								$banner_position = 'bottom'; 
							}
						}
					}
					if (!empty($banner['lien'])) {
						$url = $GLOBALS['wwwroot'] . '/modules/banner/bannerHit.php?id=' . $banner['id'];					
					} else {
						$url = 'null';
					}
					if(strpos($banner['tag_html'],'[ADSENSE_MOBILE=') !== false) {
						$tag_infos = explode(',', str_replace(array('[ADSENSE_MOBILE=', ']'), '', $banner['tag_html']));
						$banner['tag_html'] = '';
						$GLOBALS['google']['client']=$tag_infos[0];
						$GLOBALS['google']['https']=read_global('HTTPS');
						$GLOBALS['google']['ip']=read_global('REMOTE_ADDR');
						$GLOBALS['google']['markup']='xhtml';
						$GLOBALS['google']['output']='xhtml';
						$GLOBALS['google']['ref']=read_global('HTTP_REFERER');
						// $GLOBALS['google']['ref']=read_global('HTTP_HOST') . '/';
						$GLOBALS['google']['slotname']=$tag_infos[1];
						// $GLOBALS['google']['url']=read_global('HTTP_HOST') . read_global('REQUEST_URI');
						$GLOBALS['google']['url']=read_global('HTTP_HOST') . '/';
						// $GLOBALS['google']['useragent']=read_global('HTTP_USER_AGENT');
						$GLOBALS['google']['useragent']='iPhone - Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en)';
						$GLOBALS['google_dt'] = time();
						google_set_screen_res();
						google_set_muid();
						google_set_via_and_accept();
						$google_ad_handle = @fopen(google_get_ad_url(), 'r');
						if ($google_ad_handle) {
							while (!StringMb::feof($google_ad_handle)) {
								$banner['tag_html'] .= fread($google_ad_handle, 8192);
							}
							//trigger_error(StringMb::convert_accents(print_r($banner['tag_html'], true).print_r(google_get_ad_url(), true)), E_USER_NOTICE);
							fclose($google_ad_handle);
						}
					}
					$mobile_application_output_array[] = array("url_img" => get_url_from_uploaded_filename($banner['image']), "url" => $url , "html"=> vb($banner['tag_html']), "position" => $banner_position);
				} elseif (!isset($last_rang) || $banner['rang'] != $last_rang) {
					// On affiche une seule bannière par rang
					// Nous récuperons la dimension de la bannière souhaitée et appliquons les limites initialisées plus haut
					if(strpos($banner['width'], '%')===false) {
						$width = min(intval($banner['width']), $max_banner_width);
					} else {
						$width = $banner['width'];
					}
					if(strpos($banner['height'], '%')===false) {
						$height = min(intval($banner['height']), $max_banner_height);
					} else {
						$height = $banner['height'];
					}
					if(!empty($banner['image'])) {
						// Recupération de l'extension
						$banner_file_extension = @pathinfo($banner['image'], PATHINFO_EXTENSION);
						if ($banner_file_extension == 'swf') {
							if ($disable_cache && !empty($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))) {
								// Sur iOS, on ne prend pas le flash
								unset($banner['image']);
							} elseif(!empty($banner['tag_html']) && empty($GLOBALS['site_parameters']['banner_prefer_swf_to_html5'])) {
								// Le HTML5 si présent est prioritaire par rapport au SWF
								unset($banner['image']);
							} else {
								// Si on met en cache, ou si pas de cache en n'étant pas sur iOS : on prend le flash et pas une potentielle alternative HTML
								unset($banner['tag_html']);
							}
						}
					}
					if (empty($banner['tag_html']) && !empty($banner['image'])) {
						// Recupération de l'extension
						if ($banner_file_extension == 'swf') {
							// Il faut spécifier une taille quoiqu'il arrive quand on a du flash
							if (empty($width)){
								$width = '100%';
							}
							if (empty($height)){
								$height = '300';
							}
							$banner_html = getFlashBannerHTML(get_url_from_uploaded_filename($banner['image']), $width, $height, true);
						} else {
							// Si la taille de la bannière est définie, alors nous appliquons le style de la banniere
							$style_banner = '';
							if (!empty($width)){
								$style_banner .= ' width="' . $width . '" ';
							}
							if (!empty($height) && (strpos($width, '%')===false || strpos($height, '%')!==false)){
								$style_banner .= ' height="' . $height . '" ';
							}
							$banner_html = '<img src="' . get_url_from_uploaded_filename($banner['image']) . '" alt="' . vb($banner['alt'], (!empty($banner['lien']) ? get_site_domain(true, $banner['lien'], true) : '')) . '" ' . $style_banner . ' />';
							if (!empty($banner['lien'])) {
								$banner_html = '<a href="' . $GLOBALS['wwwroot'] . '/modules/banner/bannerHit.php?id=' . $banner['id'] . '" ' . $banner['extra_javascript'] . ' ' . (!empty($banner['target']) && $banner['target'] != '_self' ? ($banner['target'] == '_blank' && StringMb::strpos($banner['extra_javascript'], 'onclick=') === false?'onclick="return(window.open(this.href)?false:true);"':'target="' . $banner['target'] . '"'):'') . '>' . $banner_html . '</a>';
							}
						}
					} else {
						// On préserve le HTML mais on corrige les & isolés
						$banner_html = StringMb::htmlentities($banner['tag_html'], ENT_COMPAT, GENERAL_ENCODING, false, true);
					}
					$output .= '<div class="ba_pu" style="margin-top:3px;">' . $banner_html . '</div>';
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
		if (!$return_array_with_raw_information) {
			if(StringMb::strpos($output, '.swf')!==false && !empty($_SERVER['HTTP_USER_AGENT']) && (strstr($_SERVER['HTTP_USER_AGENT'],'iPhone') || strstr($_SERVER['HTTP_USER_AGENT'],'iPod') || strstr($_SERVER['HTTP_USER_AGENT'],'iPad'))) {
				// On a au moins une bannière flash alors qu'on est sur iOS
				if(!$disable_cache) {
					// $these_banners_id_array est vide => on a chargé le contenu à partir du cache
					// On vérifie a posteriori si on a du flash ou non (ça permet de bénéficier du cache dans près de 100% des cas, sauf cas exeptionnel où on relance la recherche cette fois-ci hors cache
					// On n'envoie pas les pubs flash sur iphone / ipod / ipad (si pas avec autre pub non flash - sinon, on envoie quand même)
					return affiche_banner($position, $return_mode, $page, $cat_id, $this_annonce_number, $page_type, $keywords_array, $lang, $return_array_with_raw_information, $ad_id, $page_related_to_user_id, true);
				} else {
					// Sécurité - Normalement on ne passe jamais ici car si $disable_cache === true, alors on n'a pas mis de flash volontairement.
					$output = '';
				}
			}
			if(StringMb::strpos($output, 'googlesyndication')!==false && (StringMb::strpos($output, 'x90')===false && StringMb::strpos($output, 'x15')===false)) {
				// Cette bannière n'est pas un x90 ou x15 et est donc susceptible de compter dans la limite de 3 bannière Google maximum
				if((StringMb::strpos(StringMb::strtolower($output), 'correspond')===false && StringMb::strpos(StringMb::strtolower($output), 'enable_page_level_ads')===false && StringMb::strpos(StringMb::strtolower($output), 'image-side')===false) || !empty($GLOBALS['disable_google_ads'])) {
					// Cette bannière n'est pas identifiée comme un contenu correspondant, n'est pas "Annonces au niveau de la page" (pubs pour mobiles), et n'est pas une annonce pour flux (qui ne compte pas dans le max de 3 blocs) => elle compte dans la limite de 3 bannière Google maximum
					if(vn($GLOBALS['google_pub_count']) >= 3 || !empty($GLOBALS['disable_google_ads'])) {
						// On ne doit pas afficher plus de 3 espaces Google hors pubs listes de mots clés, de hauteur x90 ou x15 ou ne pas afficher de bannière adsense si $GLOBALS['disable_google_ads'] est true. $GLOBALS['disable_google_ads'] est défini à false dans les cas de figure défini par la fonction is_adsense_compliant
						$output='';
					} else {
						$GLOBALS['google_pub_count']++;
					}
				}
			} elseif(StringMb::strpos($output, 'googlesyndication')!==false && !empty($GLOBALS['disable_google_ads'])) {
				// On ne doit pas afficher de bannière adsense x90 ou x15 si $GLOBALS['disable_google_ads'] est true. $GLOBALS['disable_google_ads'] est défini à false dans les cas de figure définis par la fonction is_adsense_compliant
				$output='';
			}
			// Remplacement dans le code HTML de la bannière des tags par défaut tels que wwwroot
			$output = template_tags_replace($output, array(), false, 'html');
		}
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
			" . $sql_where . " AND annonce_number>0 AND date_fin>='" . date('Y-m-d') . "' AND  " . get_filter_site_cond('banniere'));
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
			WHERE id IN('" . implode("','", real_escape_string($GLOBALS['viewed_banners_array'])) . "') AND  " . get_filter_site_cond('banniere'));
	}
}

/**
 *
 * @param mixed $var
 * @return
 */
function read_global($var) {
  return isset($_SERVER[$var]) ? $_SERVER[$var]: '';
}

/**
 *
 * @param mixed $url
 * @param mixed $param
 * @param mixed $value
 * @return
 */
function google_append_url(&$url, $param, $value) {
  $url .= '&' . $param . '=' . urlencode($value);
}

/**
 *
 * @param mixed $url
 * @param mixed $param
 * @return
 */
function google_append_globals(&$url, $param) {
  google_append_url($url, $param, $GLOBALS['google'][$param]);
}

/**
 *
 * @param mixed $url
 * @param mixed $param
 * @return
 */
function google_append_color(&$url, $param) {
  global $google_dt;
  $color_array = explode(',', $GLOBALS['google'][$param]);
  google_append_url($url, $param,
                    $color_array[$google_dt % count($color_array)]);
}

/**
 *
 * @return
 */
function google_set_screen_res() {
  $screen_res = read_global('HTTP_UA_PIXELS');
  if ($screen_res == '') {
    $screen_res = read_global('HTTP_X_UP_DEVCAP_SCREENPIXELS');
  }
  if ($screen_res == '') {
    $screen_res = read_global('HTTP_X_JPHONE_DISPLAY');
  }
  $res_array = preg_split('/[x,*]/', $screen_res);
  if (count($res_array) == 2) {
    $GLOBALS['google']['u_w']=$res_array[0];
    $GLOBALS['google']['u_h']=$res_array[1];
  }
}

/**
 *
 * @return
 */
function google_set_muid() {
  $muid = read_global('HTTP_X_DCMGUID');
  if ($muid != '') {
    $GLOBALS['google']['muid']=$muid;
     return;
  }
  $muid = read_global('HTTP_X_UP_SUBNO');
  if ($muid != '') {
    $GLOBALS['google']['muid']=$muid;
     return;
  }
  $muid = read_global('HTTP_X_JPHONE_UID');
  if ($muid != '') {
    $GLOBALS['google']['muid']=$muid;
     return;
  }
  $muid = read_global('HTTP_X_EM_UID');
  if ($muid != '') {
    $GLOBALS['google']['muid']=$muid;
     return;
  }
}

/**
 *
 * @return
 */
function google_set_via_and_accept() {
  $ua = read_global('HTTP_USER_AGENT');
  if ($ua == '') {
    $GLOBALS['google']['via']=read_global('HTTP_VIA');
    $GLOBALS['google']['accept']=read_global('HTTP_ACCEPT');
  }
}

/**
 *
 * @return
 */
function google_get_ad_url() {
  $google_ad_url = 'http://pagead2.googlesyndication.com/pagead/ads?';
  google_append_url($google_ad_url, 'dt',
                    round(1000 * array_sum(explode(' ', microtime()))));
  foreach ($GLOBALS['google'] as $param => $value) {
    if (strpos($param, 'color_') === 0) {
      google_append_color($google_ad_url, $param);
    } else if (strpos($param, 'url') === 0) {
      $google_scheme = ($GLOBALS['google']['https'] == 'on')
          ? 'https://' : 'http://';
      google_append_url($google_ad_url, $param,
                        $google_scheme . $GLOBALS['google'][$param]);
    } else {
      google_append_globals($google_ad_url, $param);
    }
  }
  return $google_ad_url;
}


/**
 *
 * @return
 */
function banner_hook_front_html_header_template_data () {
    return array('background_banner' => affiche_banner(null, true, null, null, 0, "background_site", null, null, true));
}