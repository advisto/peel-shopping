<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	|
// +----------------------------------------------------------------------+
// $Id: display_product.php 67436 2021-06-28 16:21:18Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('get_produit_details_html')) {
	/**
	 * get_produit_details_html()
	 *
	 * @param object $product_object
	 * @param integer $color_id
	 * @param integer $secondary_images_width
	 * @param integer $secondary_images_height
	 * @param integer $product_ordered_id Id de produit si on veut des informations sur un produit commandé par le passé en réutilisant des informations de la table de produits commandés
	 * @return
	 */
	function get_produit_details_html(&$product_object, $color_id = null, $secondary_images_width = 50, $secondary_images_height = 60, $product_ordered_id = null)
	{
		$output = '';
		call_module_hook('product_details', array('product_object' => $product_object), 'array');
		// On va utiliser sur cette page spécifiquement les icônes Font Awesome 
		$GLOBALS['css_files']['font-awesome'] = get_url('/lib/css/font-awesome.min.css');
		// On exclue ce fichier de la minification car usage ponctuel
		$GLOBALS['site_parameters']['minify_css_exclude_array'][] = 'font-awesome.min.css';
		if (!empty($GLOBALS['site_parameters']['product_order_once_array']) && est_identifie() && in_array($product_object->technical_code, $GLOBALS['site_parameters']['product_order_once_array'])) {
			// Ce produit ne peut être acheté qu'une seule fois par utilisateur. On va regarder si ce produit est dans une commande réglée de l'utilisateur
			$sql = "SELECT c.id
				FROM peel_commandes c
				INNER JOIN peel_commandes_articles ca ON ca.commande_id=c.id AND " . get_filter_site_cond('commandes_articles', 'ca') . "
				INNER JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
				WHERE c.id_utilisateur = ".intval($_SESSION['session_utilisateur']['id_utilisateur'])." AND ca.produit_id = ".intval($product_object->id)." AND " . get_filter_site_cond('commandes', 'c') . " AND sp.technical_code IN ('being_checked','completed')";
			if (!empty($_GET['reference'])) {
				// L'id du projet est contenu dans $_GET['reference']. On test cette valeur pour ne pas pouvoir commander plus d'un produit par projet.
				$sql .= "AND ca.reference = " . intval($_GET['reference']);
			}
			$query = query($sql);
			if (num_rows($query)>0) {
				// On a un résultat, l'utilisateur a bien déjà commandé ce produit. Donc on affiche un message d'erreur.
				$output_error = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_PRODUCT_ORDER_ONCE_ERR']))->fetch();
			}
		}
		if (!empty($output_error)) {
			$output .= $output_error;
		} elseif (empty($product_object->id) || (empty($GLOBALS['site_parameters']['allow_command_product_ongift']) && !empty($product_object->on_gift) && $product_object->on_gift_points > intval($_SESSION['session_utilisateur']['points']))) {
			$output .= $GLOBALS['STR_NO_FIND_PRODUCT'];
		} elseif(!empty($_GET['timestamp']) && $_GET['timestamp'] < strtotime('-'.vn($GLOBALS['site_parameters']['campaign_expert_call_delay'],10).' day')) {
			// Si une date d'expiration de la consultation du produit a été défini par GET['timestamp'], et que le timestamp est vieux de plus de 10 jours
			$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_OFFER_EXPIRE']))->fetch();
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('produit_details_html.tpl');
			$tpl->assign('product_detail_html_zone', affiche_contenu_html('product_detail_html_zone', true));
			if (check_if_module_active('product_pack')) {
				$tpl->assign('add_pack_to_cart_button', get_add_pack_to_cart_form($product_object->id));
			} else {
				$tpl->assign('add_pack_to_cart_button', '');
			}
			$tpl->assign('product_disable_ad_cart_if_user_not_logged', !est_identifie() && !empty($GLOBALS['site_parameters']['product_disable_ad_cart_if_user_not_logged']));
			$tpl->assign('medium_width', $GLOBALS['site_parameters']['medium_width']);
			$tpl->assign('medium_height', $GLOBALS['site_parameters']['medium_height']);
			$tpl->assign('photo_not_available_alt', $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT']);
			$tpl->assign('STR_CONDITIONING', $GLOBALS['STR_CONDITIONING']);
			$tpl->assign('STR_MSG_NEW_CUSTOMER', $GLOBALS['STR_MSG_NEW_CUSTOMER']);
			$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
			$tpl->assign('MODULES_TOP_PRODUCT_DETAIL', get_modules('top_product_detail', true));
			$tpl->assign('module_avis_verifie', vn($GLOBALS['site_parameters']['module_avis_verifie']));
			if(!empty($GLOBALS['site_parameters']['default_picture'])) {
				$tpl->assign('no_photo_src', get_url_from_uploaded_filename($GLOBALS['site_parameters']['default_picture']));
			}
			if(empty($GLOBALS['site_parameters']['avoid_increment_products_nb_view'])) {
				// On comptatilise le nombre de fois où le produit est vu
				query("UPDATE peel_produits
					SET nb_view = (nb_view+1)
					WHERE id = '" . intval($product_object->id) . "' AND " . get_filter_site_cond('produits') . "");
			}
			// On compte le combre d'avis du produit
			if (!empty($GLOBALS['site_parameters']['module_avis_verifie'])) {
				$sql_nb_avis = query("SELECT count(id) AS nb, SUM(note) AS moyenne
					FROM peel_avis
					WHERE id_produit = '" . intval($product_object->id) . "' AND etat = 1 AND avis_verifie = 1");
			} else {
				$sql_nb_avis = query("SELECT count(id) AS nb, SUM(note) AS moyenne
					FROM peel_avis
					WHERE id_produit = '" . intval($product_object->id) . "' AND etat = 1");
			}
			if($res_nb_avis = fetch_assoc($sql_nb_avis)) {
				$tpl->assign('numbers_avis', vn($res_nb_avis['nb']));
				if(!empty($res_nb_avis['moyenne']) && !empty($res_nb_avis['nb'])) {
					$tpl->assign('moyenne_avis', ceil(vn($res_nb_avis['moyenne'])/vn($res_nb_avis['nb'])));
				}
			}
			$product_images = $product_object->get_product_pictures(true, $color_id);
			$display_first_image_on_mini_pictures_list = true;
			$imgInfo = null;
			if (!empty($product_images[0])) {
				$this_thumb = thumbs($product_images[0], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit');
				$imgInfo = @getimagesize($GLOBALS['uploaddir'] . '/thumbs/' . StringMb::rawurldecode($this_thumb));
			}
			if(!empty($imgInfo)) {
				if (!empty($GLOBALS['site_parameters']['lightbox_gallery_enable'])) {
					// Ce mode pose problème avec la fonction switch_product_images() qui devrait être adaptée
					$data_lightbox = ' data-lightbox="gal' . $product_object->id . '"';
				} else {
					$data_lightbox = '';
				}
				$a_other_pictures_attributes = ' href="[ZOOM_IMAGE]"'.$data_lightbox.' onclick="switch_product_images(\'[SMALL_IMAGE]\',\'[ZOOM_IMAGE]\',\'[VIGNETTE_CLASS]\',\'[PRODUCT_ID]\'); return false;"';
				$srcWidth = $imgInfo[0];
				$srcHeight = $imgInfo[1];
				// Attention : Si on veut forcer une taille d'image de zoom plus grande que la thumb, c'est possible mais cause de nombreux problèmes au niveau de jqzoom et cloudzoom
				$min_zoom_width = 0; // Eviter de l'utiliser
				$zoom_width = max($srcWidth, $min_zoom_width);
				$zoom_height = round($srcHeight * $zoom_width/max($srcWidth, 1));
				if ($GLOBALS['site_parameters']['zoom'] == 'cloud-zoom') {
					// Pour clouzoom il ne faut pas spécifier , zoomWidth:' . $zoom_width . ', zoomHeight:' . $zoom_height . ' à cause de bugs de cloud-zoom
					$a_zoom_attributes = ' class="cloud-zoom" zoom="adjustX: 10, adjustY:-4"';
					$a_other_pictures_attributes = ' href="[ZOOM_IMAGE]" class="cloud-zoom-gallery" zoom="useZoom:\'zoom1\', smallImage: \'[SMALL_IMAGE]\'"';
					$GLOBALS['js_content_array'][] = '
a_other_pictures_attributes=\'' . str_replace("'", "\'", $a_other_pictures_attributes) . '\';
';
				} elseif ($GLOBALS['site_parameters']['zoom'] == 'jqzoom') {
					$a_zoom_attributes = ' zoom="gal' . $product_object->id . '" class="jqzoom' . $product_object->id . '"';
					$a_other_pictures_attributes = ' href="javascript:void(0);" zoom="{gallery: \'gal' . $product_object->id . '\', smallimage: \'[SMALL_IMAGE]\',largeimage: \'[ZOOM_IMAGE]\'}"';
					if (!empty($srcWidth)) {
						$GLOBALS['js_ready_content_array'][] = '
				$(".jqzoom' . $product_object->id . '").jqzoom({
					zoomWidth: ' . $zoom_width . ',
					zoomHeight: ' . $zoom_height . ',
					yoffset: 0,
					hideEffect: "fadeout"
				});
';
					}
				} elseif ($GLOBALS['site_parameters']['zoom'] == 'lightbox') {
					$display_first_image_on_mini_pictures_list = false;
					$a_zoom_attributes = ' class="lightbox"'.$data_lightbox;
				} else {
					$a_zoom_attributes = '';
				}
				$tpl->assign('main_image', array(
						'href' => get_url_from_uploaded_filename($product_images[0]),
						'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_thumb,
						'file_type' => get_file_type($product_images[0])
					));
			}
			if ($product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true) > 0 && !empty($GLOBALS['site_parameters']['thumbnail_promotion_in_products_columns'])) {
				$tpl->assign('thumbnail_promotion', true);
				$tpl->assign('promotion', $product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true));
			}
			if (!empty($GLOBALS['site_parameters']['img_promotion_in_products_columns']) && ($product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true) > 0 || !empty($product_object->on_promo))) {
				$tpl->assign('img_promotion', $GLOBALS['repertoire_images'].'/'.vb($GLOBALS['site_parameters']['img_promotion_in_products_columns']));
			}
			if (!empty($GLOBALS['site_parameters']['img_new_in_products_columns']) && !empty($product_object->on_new)) {
				$tpl->assign('img_new', $GLOBALS['repertoire_images'].'/'.vb($GLOBALS['site_parameters']['img_new_in_products_columns']));
			}
			if (!empty($GLOBALS['site_parameters']['img_exclusif_web_in_products_columns']) && !empty($product_object->exclusif_web)) {
				$tpl->assign('exclusif_web', $GLOBALS['repertoire_images'].'/'.vb($GLOBALS['site_parameters']['img_exclusif_web_in_products_columns']));
			}
			if(!empty($product_object->image9)){
				$image_9_url = $GLOBALS['repertoire_upload'] . '/' . $product_object->image9;
			}
			if(!empty($product_object->image10)){
				$image_10_url = $GLOBALS['repertoire_upload'] . '/' . $product_object->image10;
			}
			$tpl->assign('image_9_url', vb($image_9_url));
			$tpl->assign('image_10_url', vb($image_10_url));
			$tpl->assign('a_zoom_attributes', vb($a_zoom_attributes));
			$tpl->assign('a_other_pictures_attributes', vb($a_other_pictures_attributes));
			if (!empty($product_object->on_estimate)) {
				$tpl->assign('title_price', array(
					'txt' => $GLOBALS['STR_ON_ESTIMATE'], 
					'value' => false,
					'technical_code' => $product_object->technical_code
					));
			} elseif ($product_object->on_gift) {
				$tpl->assign('title_price', array(
					'txt' => false, 
					'value' => $product_object->on_gift_points . ' ' . $GLOBALS['STR_GIFT_POINTS'],
					'technical_code' => $product_object->technical_code
					));
			} elseif ($product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller()) != 0) {
				$tpl->assign('title_price', array(
					'txt' => false, 
					'value' => str_replace(' ', ' ', $product_object->affiche_prix(display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true, false, 'title_price', false, true, 'price_in_product_title', true, true)),
					'technical_code' => $product_object->technical_code
					));
			} elseif (!empty($GLOBALS['site_parameters']['free_displayed_if_price_empty_disable'])) {
				$tpl->assign('title_price', array(
					'txt' => false, 
					'value' => false,
					'technical_code' => $product_object->technical_code
					));
			} else {
				$tpl->assign('title_price', array(
					'txt' => $GLOBALS['STR_FREE'], 
					'value' => false,
					'technical_code' => $product_object->technical_code
					));
			}
			if (empty($product_ordered_id) && function_exists('get_attributs_step') && !empty($GLOBALS['site_parameters']['header_product_page_for_attributs_step'][$product_object->technical_code])) {
				$tpl->assign('header_product', vb($GLOBALS['site_parameters']['header_product_page_for_attributs_step'][$product_object->technical_code]));
				$tpl->assign('attributs_step', get_attributs_step($_GET, $product_object));
				$step = vn($_GET["step"],1);
				$tpl->assign('step', $step);
				if ($product_object->technical_code == 'capsule_capzzle') {
					$tpl->assign('STR_STEP_DESCRIPTIF', vb($GLOBALS["STR_STEP_CAPZZLE_DESCRIPTIF_".$step]));
				} else {
					$tpl->assign('STR_STEP_DESCRIPTIF', vb($GLOBALS["STR_STEP_DESCRIPTIF_".$step]));
				}
			}
			$tpl->assign('link_contact', get_contact_url(false, false));
			$tpl->assign('contact', $GLOBALS["STR_CONTACT"]);
			$tpl->assign('product_detail_image', $GLOBALS['site_parameters']['general_product_image']);
			$tpl->assign('product_name', $product_object->name);
			$tpl->assign('product_id', $product_object->id);
			$tpl->assign('product_href', $product_object->get_product_url());
			if (!empty($GLOBALS['site_parameters']['enable_categorie_sentence_displayed_on_product'])) {
				$tpl->assign('categorie_sentence_displayed_on_product', $product_object->categorie_sentence_displayed_on_product);
			}
			if (!empty($_GET['catid']))	{
				// Permet de gérer si catégorie imposée
				$current_catid = intval($_GET['catid']);
			} else {
				$current_catid = $product_object->categorie_id;
			}			
			if ($product_object->is_price_flash(check_if_module_active('reseller') && is_reseller())) {
				$tpl->assign('flash_txt', $GLOBALS['STR_TEXT_FLASH1'] . ' ' . get_formatted_duration(strtotime($product_object->flash_end) - time(), false, 'day') . ' ' . $GLOBALS['STR_TEXT_FLASH2']);
			}
			
			if (empty($GLOBALS['site_parameters']['admin_product_edit_links_in_front_office_disable']) && a_priv('admin_products', false)) {
				$tpl->assign('admin', array(
					'href' => $GLOBALS['administrer_url'] . '/produits.php?mode=modif&id=' . $product_object->id,
					'modify_txt' => $GLOBALS['STR_MODIFY_PRODUCT'],
					'is_offline' => (bool)($product_object->etat == 0),
					'offline_txt' => $GLOBALS['STR_OFFLINE_PRODUCT']
				));
			}
            /*****/
            if (a_priv('admin*') || a_priv('util_sale')) {
				$tpl->assign('privilege', '1');
			}
            /*****/
			if (est_identifie() && !empty($GLOBALS['site_parameters']['enable_create_product_in_front']) && $product_object->id_utilisateur == $_SESSION['session_utilisateur']['id_utilisateur']) {
				// On récupère les informations manquantes
				$sql = "SELECT p.id, p.titre_" . $_SESSION['session_langue'] . " AS titre, p.on_special, pc.rubrique_id, r.nom_" . $_SESSION['session_langue'] . " AS rubrique_nom
					FROM peel_articles p
					INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id
					INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id AND " . get_filter_site_cond('rubriques', 'r') . "
					WHERE p.technical_code = 'display_product_form' AND p.etat='1' AND titre_" . $_SESSION['session_langue'] . "!='' AND " . get_filter_site_cond('articles', 'p') . "";
				$query = query($sql) ;
				if ($result = fetch_assoc($query)) {
					//get_default_content remplace le contenu par la langue par défaut si les conditions sont réunies
					if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
						$result = get_default_content($result, $result['id'], 'articles');
					}
					$tpl->assign('modify_product_by_owner', array(
						'href' => get_content_url($result['id'], $result['titre'], $result['rubrique_id'], $result['rubrique_nom']) . '?mode=modif&product_id=' . $product_object->id,
						'label' => $GLOBALS['STR_MODIFY_PRODUCT']
					));
				}
			}
			if (!empty($product_images) && count($product_images) > 1) {
				$tmp_imgs = array();
				if (!empty($GLOBALS['site_parameters']['order_product_images_per_ratio']))  {
					// Tri des images en fonction du ratio d'image. 
					$tmp = array();
					$new_product_images_array = array();
					foreach ($product_images as $key => $name) {
						// Récupération de la taille de l'image.
						$imgInfo = @getimagesize($GLOBALS['uploaddir'].'/'.$name);
						// Calcul du ratio Height/Width
						if (!empty($imgInfo) && !empty($imgInfo[0])) {
							$tmp[$key] = $imgInfo[1]/$imgInfo[0];
						} else {
							$tmp[$key] = 0;
						}
					}
					// tri par ordre croissant => asort : sans modifier les index
					asort($tmp);
					foreach ($tmp as $key=>$ratio) {
						// Le tableau d'image de produit est reconsitué à partir du tableau qui contient les ratio, dans l'ordre.
						$new_product_images_array[] = $product_images[$key];
					}
					// Le précédent tableau non trié est remplacé par le nouveau, avec les images classées par ordre de ratio
					$product_images = $new_product_images_array;
				}
				foreach ($product_images as $key => $name) {
					if (!$display_first_image_on_mini_pictures_list && $key == 0) {
						// On n'affiche pas l'image principale pour Jq zoom, puisque l'image principal est intervertit avec l'image secondaire.
					} else {
						$vignette_id = 'vignette' . $key;
						if (get_file_type($name) == 'image') {
							$small_image = thumbs($name, $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit', null, null, true, true);
							$tmp_imgs[] = array(
								'is_image' => true,
								'id' => $vignette_id,
								'a_attr' => str_replace(array('[SMALL_IMAGE]', '[ZOOM_IMAGE]', '[VIGNETTE_CLASS]', '[PRODUCT_ID]'), array($small_image, get_url_from_uploaded_filename($name), $vignette_id, $product_object->id), $a_other_pictures_attributes),
								'src' => thumbs($name, $secondary_images_width, $secondary_images_height, 'fit', null, null, true, true),
							);
						} else {
							$tmp_imgs[] = array(
								'is_image' => false,
								'href' => get_url_from_uploaded_filename($name),
								'src' => thumbs($name, $secondary_images_width, $secondary_images_height, 'fit', null, null, true, true),
							);
						}
					}
				}
				$tpl->assign('product_images', $tmp_imgs);
			}
			if (check_if_module_active('direaunami')) {
				$tpl->assign('tell_friends', array(
					'href' => get_tell_friends_url(false),
					'src' => $GLOBALS['site_parameters']['general_send_email_image'],
					'txt' => $GLOBALS['STR_TELL_FRIEND']
				));
			}
			if (!empty($GLOBALS['site_parameters']['quick_add_product_from_search_page'])) {
				$tpl->assign('add_easy_list', array(
					'href' => get_url('search') . '?type=quick_add_product_from_search_page&prodid=' . $product_object->id . '&quantite=1',
					'txt' => $GLOBALS['STR_PRODUCT_ADD_TO_EASY_LIST']
				));
			}
			$tpl->assign('print', array(
				'src' => vb($GLOBALS['site_parameters']['general_print_image']),
				'txt' => $GLOBALS['STR_PRINT_PAGE']
			));
			
			if (!empty($product_object->reference) && empty($GLOBALS['site_parameters']['product_detail_reference_hide']) && empty($product_ordered_id)) {
				$tpl->assign('reference', array(
					'label' => $GLOBALS['STR_REFERENCE'],
					'txt' => $product_object->reference
				));
			}
			if ((!empty($product_object->ean_code))) {
				$tpl->assign('ean_code', array(
					'label' => $GLOBALS['STR_EAN_CODE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
					'txt' => $product_object->ean_code
				));
			}
			$tpl->assign('module_devis_active', check_if_module_active('devis'));
			$tpl->assign('conditionnement', $product_object->conditionnement);
			if (!empty($product_object->id_marque)) {
				$brand_link = trim(get_brand_link_html($product_object->id_marque, true));
				if (!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
					$distributors_link_array = get_distributors_link_html($product_object->id_marque);
				}
				$this_thumb_brand = thumbs(vb($distributors_link_array[0]['logo']), $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit', null, null, true, true);
				$brand_site_country_array = explode(',', vb($distributors_link_array[0]['site_country']));
				if (!empty($distributors_link_array[0]['siteweb']) && strpos(vb($distributors_link_array[0]['siteweb']), 'http://')===false && strpos(vb($distributors_link_array[0]['siteweb']), 'https://')===false) {
					$url_site_web = 'http://' . $distributors_link_array[0]['siteweb'] . '/';
				} else {
					$url_site_web = vb($distributors_link_array[0]['siteweb']);
				}
				if(!empty($brand_link)) {
					if (!empty($GLOBALS['site_parameters']['brand_hide'])) $brand_link = "";
					$tpl->assign('marque', array(
						'label' => $GLOBALS['STR_BRAND'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
						'logo' => get_url_from_uploaded_filename($this_thumb_brand),
						'phone' => vb($distributors_link_array[0]['phone']),
						'email' => vb($distributors_link_array[0]['email']),
						'siteweb' => vb($url_site_web),
						'txt' => $brand_link
					));
				}
				if(in_array($_SESSION['session_site_country'], vb($brand_site_country_array))) {
					$tpl->assign('display_more_brand', true);
				}
			}
			if (!empty($product_object->points)) {
				$tpl->assign('points', array(
					'label' => $GLOBALS['STR_GIFT_POINTS'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
					'txt' => $product_object->points
				));
			}
			$tpl->assign('STR_TELEPHONE', $GLOBALS["STR_TELEPHONE"]);
			$tpl->assign('STR_CONTACT_LOCAL_DISTRIBUTOR', vb($GLOBALS["STR_CONTACT_LOCAL_DISTRIBUTOR"]));
			$tpl->assign('STR_EMAIL', $GLOBALS["STR_EMAIL"]);
			$tpl->assign('STR_WEBSITE', $GLOBALS["STR_WEBSITE"]);
			if(vb($GLOBALS['site_parameters']['show_short_description_on_product_details'])) {
				$tpl->assign('descriptif', StringMb::nl2br_if_needed(trim($product_object->descriptif)));
			} else {
				$tpl->assign('descriptif', '');
			}
			if (!empty($product_ordered_id)) {
				// On veut afficher les détails d'un produit commandé.
				$sql = "SELECT nom_attribut, attributs_list
					FROM peel_commandes_articles ca
					INNER JOIN peel_commandes c ON c.id=ca.commande_id
					WHERE ca.id='" . intval($product_ordered_id) . "' AND id_utilisateur='" . intval(vn($_SESSION['session_utilisateur']['id_utilisateur'])."'");
				$query = query($sql);
				if($result = fetch_assoc($query)) {
					if(function_exists('display_specific_attribut') && empty($GLOBALS['site_parameters']['disabled_display_product_description_specific_attribut'])) {
						$description = display_specific_attribut($product_object, $result['attributs_list']);
					} else {
						$description = str_replace("\n", '<br />', display_option_image(vb($result['nom_attribut']), true));
					}
				}
			} else {
				$description = StringMb::nl2br_if_needed(trim($product_object->description));
			}
			$tpl->assign('description', $description);
			if(!empty($GLOBALS['site_parameters']['show_qrcode_on_product_pages'])) {
				$tpl->assign('qrcode_image_src', $product_object->qrcode_image_src());
			}
			if(!empty($GLOBALS['site_parameters']['show_barcode_on_product_pages'])) {
				$tpl->assign('barcode_image_src', $product_object->barcode_image_src());
			}
			$tpl->assign('extra_link', $product_object->extra_link);
			if (empty($product_ordered_id) && empty($GLOBALS['site_parameters']['product_price_display_disable'])) {
				if (!empty($product_object->on_check) && check_if_module_active('gift_check')) {
					$tpl->assign('check', affiche_check($product_object->id, 'cheque', null, true));
				} else {
					if (empty($product_object->on_estimate)) {
						if(!empty($product_object->on_gift) || $product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), false, false, 1, true, true, false)!=0 || vb($GLOBALS['site_parameters']['show_add_to_cart_on_free_products'])) {
							if(!empty($_GET['liste'])) {
								$liste = explode('|', $_GET['liste']);
							}
							$tpl->assign('critere_stock', affiche_critere_stock($product_object, 'details', null, true, false, null, vn($liste[0]), vn($liste[1])));
						} else {
							$tpl->assign('critere_stock', '');
						}
					} else {
						$tpl->assign('on_estimate', array(
							'label' => $GLOBALS['STR_ON_ESTIMATE'],
							'action' => get_contact_url(false, false),
							'contact_us' => $GLOBALS['STR_CONTACT_US']
						));
					}
				}
			}
			if (!empty($product_object->display_tab)) {
				// Onglets
				$tabs = array();
				// pour connaître le nombre total d'onglets, on initialise un compteur
				$j = 0;
				if (!empty($GLOBALS['site_parameters']['display_opinion_on_product_tab']) && check_if_module_active('avis')) {
					$render_avis_public_list = render_avis_public_list($product_object->id, 'produit', null, true);
					if (!empty($render_avis_public_list)) {
						$tabs[] = array(
							'tab_id' => 'tab_opinion',
							'index' => $j,
							'title' => $GLOBALS['STR_TOUS_LES_AVIS'],
							'is_current' => true,
							'content' => $render_avis_public_list);
						$j++;
					}
				}
				$sql_fields = '
					tab1_html_' . $_SESSION['session_langue'] . ' AS tab1_html,
					tab2_html_' . $_SESSION['session_langue'] . ' AS tab2_html,
					tab3_html_' . $_SESSION['session_langue'] . ' AS tab3_html,
					tab4_html_' . $_SESSION['session_langue'] . ' AS tab4_html,
					tab5_html_' . $_SESSION['session_langue'] . ' AS tab5_html,
					tab6_html_' . $_SESSION['session_langue'] . ' AS tab6_html,
					tab1_title_' . $_SESSION['session_langue'] . ' AS tab1_title,
					tab2_title_' . $_SESSION['session_langue'] . ' AS tab2_title,
					tab3_title_' . $_SESSION['session_langue'] . ' AS tab3_title,
					tab4_title_' . $_SESSION['session_langue'] . ' AS tab4_title,
					tab5_title_' . $_SESSION['session_langue'] . ' AS tab5_title,
					tab6_title_' . $_SESSION['session_langue'] . ' AS tab6_title';
				if ($tab = get_product_infos($product_object->id, true, $sql_fields)) {
					// var_dump($tab);
					for ($i = 1; $i <= 6; $i++) {
						$title = trim($tab['tab' . $i . '_title']);
						if (!empty($title)) {
							$tab_html = template_tags_replace(trim($tab['tab' . $i . '_html']));
							$tabs[] = array(
								'tab_id' => 'tab_' .  $i,
								'index' => $i,
								'title' => $title,
								'is_current' => (bool)($i == 1 && empty($render_avis_public_list)),
								'content' => $tab_html
							);
							$j++;
						}
					}
				}
				if (!empty($j)) {
					// On affiche le contenu des onglets seulement si pas vide
					$tpl->assign('tabs', $tabs);
				}
			}
			if (!empty($product_object->youtube_code)) {
				$tpl->assign('youtube_code', $product_object->youtube_code);
			}
			
			if (!empty($GLOBALS['site_parameters']['category_order_on_catalog'])) {
				$nb_colonnes = vn($GLOBALS['site_parameters']['associated_products_columns_if_order_allowed_on_products_lists']);
			} else {
				$nb_colonnes = vn($GLOBALS['site_parameters']['associated_products_columns_default']);
			}
			if (empty($GLOBALS['site_parameters']['disable_addthis_buttons'])) {
				$tpl->assign('addthis_buttons', addthis_buttons()); 
			}
			$tpl->assign('display_share_tools_on_product_pages', !empty($GLOBALS['site_parameters']['display_share_tools_on_product_pages']));
			// Charge les produits associés
			
			if (empty($GLOBALS['site_parameters']['ajax_products'])) {
				$tpl->assign('associated_products', affiche_produits(null, 3, 'associated_product', $GLOBALS['site_parameters']['nb_produit_page'], (!empty($product_object->technical_code) && $product_object->technical_code== 'tuto'?'line':vb($GLOBALS['site_parameters']['associated_products_display_mode'])), true, $product_object->id, $nb_colonnes, !empty($GLOBALS['site_parameters']['no_display_if_empty']), false));
			} else {
				$tpl->assign('associated_products', get_api_ref_product($product_object->id));
			}
			if(check_if_module_active('product_pack')){
				$tpl->assign('associated_product_pack', get_product_pack_html($product_object->id, true));
			} else {
				$tpl->assign('associated_product_pack', '');
			}
			$tpl->assign('javascript', null); // Pour compatibilité anciens templates avant restructuration javascript v7.1
			$hook_result = call_module_hook('product_details_additional_infos', array('id' => $product_object->id, 'id_utilisateur' => $product_object->id_utilisateur, 'categorie_id' => $product_object->categorie_id, 'current_catid' => $current_catid, 'position' => $product_object->position), 'array');
			foreach($hook_result as $this_key => $this_value) {
				$tpl->assign($this_key, $this_value);
			}
			
			$module_ariane_active = get_modules_array(true,null,'ariane');
			if (empty($module_ariane_active)) {
				// Utilisation exceptionnelle d'un élément
				// Le module ariane n'est pas actif, donc on affiche quand même pour le produit
				$tpl->assign('breadcrumb', affiche_ariane(true, $product_object->name, vb($hook_result['prev']).vb($hook_result['next'])));
			} else {
				$tpl->assign('breadcrumb', "");
			}
            /**
             Afficher les details de commentaire interne
            */
			$tmpData['suggestion_commentaire_details'] = get_suggestion_commentaire_details(0, $product_object->id_marque, vb($_SESSION['session_utilisateur']));
			$tmpData['suggestion_commentaire_details_waiting'] = get_suggestion_commentaire_details(0, $product_object->id_marque, vb($_SESSION['session_utilisateur']), 'waiting');

			$tpl->assign('data', $tmpData);
            /**fin*/
            
			$output .= $tpl->fetch();
		}
		unset($product_object);
		return $output;
	}
}

if (!function_exists('get_products_list_brief_html')) {
	/**
	 *
	 * @param integer $catid
	 * @param boolean $display_subcategories
	 * @param string $type
	 * @return
	 */
	function get_products_list_brief_html($catid, $display_subcategories = true, $type = 'category', $id_marque = null, $product_display_disable = null)
	{
		// Si c'est une marque, on vérifie s'il ne faut pas se baser sur une catégorie de plus bas niveau
		if($id_marque)
		{
			$categorie = get_categories_marque($catid, $id_marque, true);
			// Appliquer le développement sauf si on se trouve dans la catégorie de premier niveau
			if(empty($GLOBALS['site_parameters']['index_id_category_brand']) || !in_array($categorie, $GLOBALS['site_parameters']['index_id_category_brand']))
			{
				$catid = $categorie;
			}
		}

		$tpl = $GLOBALS['tplEngine']->createTemplate('products_list_brief.tpl');
		$sqlcat = "SELECT pc.technical_code, image_" . $_SESSION['session_langue'] . " AS image, pc.description_" . $_SESSION['session_langue'] . ", pc.nom_" . $_SESSION['session_langue'] . ", pc.meta_titre_" . $_SESSION['session_langue'] . " AS meta_titre, pc.type_affichage, pc.etat, pc.nom_court_" . $_SESSION['session_langue'] . " AS nom_court";
		if (check_if_module_active('category_promotion')) {
			$sqlcat .= ", promotion_devises, promotion_percent";
		}
		if (!empty($GLOBALS['site_parameters']['cat_description_details'])) {
			$sqlcat .= ", pc.description_details";
		}
		if(empty($GLOBALS['site_parameters']['cat_image_header_disable'])){
			$sqlcat .= ", image_header_" . $_SESSION['session_langue'] . " AS image_header";
		}
		if(empty($id_marque) || empty($GLOBALS['site_parameters']['index_id_category_brand']) || !is_array($catid)) {
			$sqlcat .= " FROM peel_categories pc
			WHERE id='" . intval($catid) . "' AND " . get_filter_site_cond('categories', 'pc') . "
			ORDER BY position";
		} else {
			//On affiche les catégories souhaitées définis dans $GLOBALS['site_parameters']['index_id_category_brand']
			$array_id_category = implode(',',$GLOBALS['site_parameters']['index_id_category_brand']);
			$sqlcat .= " FROM peel_categories pc
			WHERE pc.id IN (" . $array_id_category . ")
			ORDER BY position";
		}
		$rescat = query($sqlcat);
		$cat_name = '';
		if ($cat_infos = fetch_assoc($rescat)) {
			//Si les noms de la catégorie sont vides, on remplace par les noms courts des catégories parents
			if(empty($cat_infos['nom_' . $_SESSION['session_langue']])){
				$sql_cat = "SELECT c.nom_court_" . $_SESSION['session_langue'] . " AS nom_court 
					FROM peel_categories c 
					WHERE id IN (" . implode(",", real_escape_string(get_category_tree_and_itself(intval($catid),'parents'))) . ") AND c.nom_court_" . $_SESSION['session_langue'] . "!=''";
				$qid = query($sql_cat);
				$name_short_cat = array();
				while ($cat_name_short = fetch_assoc($qid)) {
					$name_short_cat[] = $cat_name_short['nom_court'];
				}
				//Filtre le tableau pour pouvoir tester si vide
				$name_short_cat = array_filter($name_short_cat);
				if(!empty($name_short_cat)){
					$cat_name = implode(' - ',$name_short_cat);
				} elseif(empty($name_short_cat) && !empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
				//Si les noms courts des catégories parents ne retourne rien on applique le remplacement des contenus par celui de la langue par défaut
				//get_default_content remplace le contenu par la langue par défaut si les conditions sont réunies
					$cat_infos = get_default_content($cat_infos, intval($catid), 'categories');
					$cat_name = (!empty($cat_infos['nom_'. $_SESSION['session_langue']])?$cat_infos['nom_'. $_SESSION['session_langue']]:(!empty($cat_infos['nom_court'])?$cat_infos['nom_court']:(!empty($GLOBALS['site_parameters']['display_str_if_category_name_empty'])?$GLOBALS['STR_NOT_ATTRIBUED']:'')));
				} elseif(!empty($GLOBALS['site_parameters']['display_str_if_category_name_empty'])) {
					$cat_name = $GLOBALS['STR_NOT_ATTRIBUED'];
				}
			} elseif(!empty($cat_infos['nom_'. $_SESSION['session_langue']])) {
				$cat_name = $cat_infos['nom_'. $_SESSION['session_langue']];
			}
			
			$page_h1 = StringMb::html_entity_decode_if_needed($cat_name);
			if (empty($GLOBALS['site_parameters']['get_straight_category_page_title']) && empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
				// Pour un meilleur référencement, on améliore la balise h1 présente sur la page
				foreach(explode(' ', $cat_infos['meta_titre']) as $this_word) {
					if ((StringMb::strlen($this_word)>=3 && StringMb::strpos(StringMb::strtolower(' '.$page_h1.' '), StringMb::strtolower(' '.$this_word.' ')) === false) && StringMb::strlen($page_h1 . ' ' . $this_word) < 80) {
						if(StringMb::strpos($page_h1, ' - ') === false) {
							$page_h1 .= ' -';
						}
						$page_h1 .= ' ' . $this_word;
					}
				}
			}
			// Application du titre catégorie spécifique pour les catégories racine des marques
			if($id_marque && !empty($GLOBALS['site_parameters']['title_by_id_category_brand'])){
				if (in_array($catid, $GLOBALS['site_parameters']['index_id_category_brand'])) {
					if(!empty($GLOBALS[$GLOBALS['site_parameters']['title_by_id_category_brand'][$catid]])){
						$page_h1 = $GLOBALS[$GLOBALS['site_parameters']['title_by_id_category_brand'][$catid]];
					}
				}
			}
			$suggestion_description_details = get_suggestion_description_details($catid, $id_marque, vb($_SESSION['session_utilisateur']));
			$suggestion_description_details_waiting = get_suggestion_description_details($catid, $id_marque, vb($_SESSION['session_utilisateur']), 'waiting');
			if(isset($suggestion_description_details_waiting['description'])){
				$suggest_description_details = $suggestion_description_details_waiting['description'];
			} elseif (!empty($GLOBALS['site_parameters']['cat_description_details'])) {
				$suggest_description_details = $cat_infos['description_details'];
			}
            /**
             Afficher les details de commentaire interne
            */
			$tmpData['suggestion_commentaire_details'] = get_suggestion_commentaire_details($catid, 0, vb($_SESSION['session_utilisateur']));
			$tmpData['suggestion_commentaire_details_waiting'] = get_suggestion_commentaire_details($catid, 0, vb($_SESSION['session_utilisateur']), 'waiting');
			$tpl->assign('data', $tmpData);
           /**fin*/			
		   $cat = array(
				'name' => $page_h1,
				'id_categorie' => intval($catid),
				'id_marque' => intval($id_marque)
			);
		   if (a_priv('admin*') || a_priv('util_sale')) {
				$cat['description_details'] = vb($cat_infos['description_details']);
				$cat['description_details_html'] = getTextEditor('textarea_suggest_description_details', 1170, 100, StringMb::html_entity_decode_if_needed(vb($suggest_description_details)));
				$cat['suggestion_description_details'] = $suggestion_description_details;
				$cat['suggestion_description_details_waiting'] = $suggestion_description_details_waiting;
				$tpl->assign('display_description_details', true);
		   } else {	
				$tpl->assign('display_description_details', false);
			}
			// Pour enregistrer la suggestion de description en base de données
			if(isset($_POST['suggest_description_details'])){
				$set_suggestion_description_details = set_suggestion_description_details($_POST['suggest_description_details'], $catid, $id_marque, $_SESSION['session_utilisateur']);
				if($set_suggestion_description_details) {
					redirect_and_die(get_current_url());
				}
			} elseif (isset($_POST['textarea_suggest_description_details'])){
				$set_suggestion_description_details = set_suggestion_description_details($_POST['textarea_suggest_description_details'], $catid, $id_marque, $_SESSION['session_utilisateur']);
				if($set_suggestion_description_details) {
					redirect_and_die(get_current_url());
				}
			} elseif (isset($_POST['suggest_comment_details'])){
				$set_suggestion_comment_details = set_suggestion_comment_details($_POST['comment_details'], $catid, $id_marque, $_SESSION['session_utilisateur']);
				if($set_suggestion_comment_details) {
					redirect_and_die(get_current_url());
				}
			}

			if (a_priv('admin_products', false) && empty($id_marque)) {
				$cat['admin'] = array(
					'href' => $GLOBALS['administrer_url'] . '/categories.php?mode=modif&id=' . $catid,
					'label' => $GLOBALS['STR_MODIFY_CATEGORY']
				);
			}
			if (($cat_infos['etat'] == 0 && a_priv('admin_products', false))) {
				$cat['offline'] = $GLOBALS['STR_OFFLINE_CATEGORY'];
			}
			if (!empty($cat_infos['image_header'])) {
				$this_thumb = thumbs($cat_infos['image_header'], $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit', null, null, true, true);
				if(!empty($this_thumb)) {
					$cat['image'] = array(
						'src' => $this_thumb,
						'name' => StringMb::html_entity_decode_if_needed($cat_infos['nom_' . $_SESSION['session_langue']])
					);
				}
			} elseif (!empty($cat_infos['image'])) {
				$this_thumb = thumbs($cat_infos['image'], $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit', null, null, true, true);
				if(!empty($this_thumb)) {
					$cat['image'] = array(
						'src' => $this_thumb,
						'name' => StringMb::html_entity_decode_if_needed($cat_infos['nom_' . $_SESSION['session_langue']])
					);
				}
			}
			$cat['description'] = $cat_infos['description_' . $_SESSION['session_langue']];
			correct_output($cat['description'], true, 'html', $_SESSION['session_langue']);
			if (check_if_module_active('category_promotion') && (vn($cat_infos['promotion_devises']) > 0 || vn($cat_infos['promotion_percent']) > 0)) {
				$cat['promotion'] = array(
					'label' => $GLOBALS['STR_REDUCTION_ON_ALL_PRODUCTS_FROM_CATEGORIE'],
					'discount_text' => get_discount_text($cat_infos['promotion_devises'], $cat_infos['promotion_percent'], true)
				);
			}
			$tpl->assign('cat', $cat);
		}
		
		if (!empty($cat_infos['type_affichage']) && $cat_infos['type_affichage'] == "1") {
			$products_display_mode = 'line';
			$nb_colonnes = 1;
		} else {
			$products_display_mode = vb($GLOBALS['site_parameters']['global_products_display_mode'], 'column');
			$nb_colonnes = $GLOBALS['site_parameters']['product_category_pages_nb_column'];
		}
		if (!empty($cat_infos['technical_code']) && $cat_infos['technical_code'] == 'show_draft' && est_identifie()) {
			// L'utilisateur consulte la page qui liste ses brouillons (produit en cours de création) et qu'il est connecté.
			$type = 'show_draft';
		}
		if (!empty($display_subcategories)) {
			// Affichage des sous-catégories
			if(empty($id_marque) || empty($GLOBALS['site_parameters']['index_id_category_brand'])) {
				$subcategories_table = get_subcategories_table($catid, $GLOBALS['site_parameters']['subcategorie_nb_column'], true);
			} else {
				//Affiche les sous-catégories lié à la marque
				$subcategories_table = get_subcategories_table($catid, $GLOBALS['site_parameters']['subcategorie_nb_column'], true, true, $id_marque);
			}
			if (!empty($subcategories_table)) {
				$tpl->assign('subcategories', $subcategories_table);
			}
		}
		if (!empty($GLOBALS['site_parameters']['display_main_categories_on_subcategory_pages'])) {
			$tpl->assign('main_categories', get_categories_output(null, 'categories', null, 'html', '&nbsp;&nbsp;', null, null, false, 25, null, 0, $catid));
		}

		// Suppression systématique de "Liste des produits" car inutile.
		// if(empty($subcategories_table) && empty($cat['description'])) {
			// Ne pas afficher le titre "Liste des produits" ensuite car rien au-dessus => On affiche directement les produits
			$GLOBALS['STR_LIST_PRODUCT'] = '';
		// }


		$additional_sql_cond = '';
		//On vérifie si on est dans l'affichage par défaut de la marque avec les catégories mère définis dans le tableau $GLOBALS['site_parameters']['index_id_category_brand']
		if (empty($product_display_disable)) {
			if(!is_array($catid)){
				$tpl->assign('associated_products', affiche_produits($catid, 2, $type, vn($GLOBALS['site_parameters']['nb_produit_page']), $products_display_mode, true, null, $nb_colonnes, !empty($GLOBALS['site_parameters']['no_display_if_empty']), vn($GLOBALS['site_parameters']['always_show_multipage_footer']), null, $additional_sql_cond, null, null, null, null, false, null, 'full', $id_marque));
			} else {
				//
				$associated_products = '';
				foreach($catid as $id){
					 $associated_products .= affiche_produits($id, 2, $type, vn($GLOBALS['site_parameters']['nb_produit_page']), $products_display_mode, true, null, $nb_colonnes, !empty($GLOBALS['site_parameters']['no_display_if_empty']), vn($GLOBALS['site_parameters']['always_show_multipage_footer']), null, $additional_sql_cond, null, null, null, null, false, null, 'full', $id_marque);
				}
				$tpl->assign('on_brand_homepage', true);
				$tpl->assign('associated_products', $associated_products);
			}
		}
		
		$module_ariane_active = get_modules_array(true,null,'ariane');
		if (empty($module_ariane_active)) {
			// Utilisation exceptionnelle d'un élément
			// Le module ariane n'est pas actif, donc on affiche quand même pour le produit
			$tpl->assign('breadcrumb', affiche_ariane(true, null, null, !empty($GLOBALS['site_parameters']['affiche_ariane_use_short_name'])));
		} else {
			$tpl->assign('breadcrumb', "");
		}
		return $tpl->fetch();
	}
}

if (!function_exists('affiche_prix')) {
	/**
	 * affiche_prix()
	 *
	 * @param mixed $product_object
	 * @param mixed $with_taxes
	 * @param mixed $reseller_mode
	 * @param boolean $return_mode
	 * @param mixed $display_with_measurement
	 * @param mixed $item_id
	 * @param mixed $display_ecotax
	 * @param mixed $display_old_price
	 * @param string $table_css_class
	 * @param boolean $display_old_price_inline
	 * @param boolean $force_display_with_vat_symbol
	 * @param boolean $add_rdfa_properties
	 * @param boolean $display_minimal_price
	 * @return
	 */
	function affiche_prix(&$product_object, $with_taxes = true, $reseller_mode = false, $return_mode = false, $display_with_measurement = false, $item_id = null, $display_ecotax = true, $display_old_price = true, $table_css_class = 'full_width', $display_old_price_inline = true, $force_display_with_vat_symbol = true, $add_rdfa_properties = false, $display_minimal_price = null, $quantity = 1)
	{
		static $tpl;
		$output = '';
		$final_price = $product_object->get_final_price(get_current_user_promotion_percentage(), $with_taxes, $reseller_mode);
		if ($final_price != 0 || !empty($GLOBALS['site_parameters']['price_force_display_even_if_empty'])) {
			if(empty($tpl)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('prix.tpl');
			}
			if($display_minimal_price === null) {
				$display_minimal_price = vb($GLOBALS['site_parameters']['product_display_minimal_price'], false);
			}
			$tpl->assign('text_promotion', false);
			$tpl->assign('table_css_class', $table_css_class);
			$tpl->assign('about', '#product'.$product_object->id);
			$tpl->assign('item_id', $item_id);
			$tpl->assign('display_old_price_inline', $display_old_price_inline);
			if (defined('IN_CATALOGUE')) {
				$tpl->assign('display_text_promotion_in_category_disabled', vb($GLOBALS['site_parameters']['display_text_promotion_in_category_disabled']));
			}
			if ($product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true) > 0 && !empty($GLOBALS['site_parameters']['text_promotion_in_products_columns_and_details'])) {
				$tpl->assign('STR_PROMOTION_SHORT', $GLOBALS["STR_PROMOTION_SHORT"]);
				$tpl->assign('text_promotion', true);
				$tpl->assign('promotion', $product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true));
			}
			if (empty($force_display_with_vat_symbol)) {
				if((!display_prices_with_taxes_active() || !empty($GLOBALS['site_parameters']['price_force_tax_display_on_product_and_category_pages']))) {
					 $display_with_vat_symbol = (defined('IN_SEARCH') && empty($GLOBALS['site_parameters']['price_force_tax_display_disable_on_category_pages']) || !defined('IN_SEARCH'));
				} else {
					$display_with_vat_symbol = false;
				}
			} else {
				$display_with_vat_symbol = $force_display_with_vat_symbol;
			}
			//on vérifie si le prix minimal est inférieur au prix standard, ou si le prix final est à 0 ET qu'il y a un prix minimal
			$final_price_minimal = $product_object->get_final_price(0, false, false, false, false, 1, false, false, false);
			if (!empty($display_minimal_price) && (($product_object->get_minimal_price(false) < $final_price_minimal) || $final_price_minimal == 0 && $product_object->get_minimal_price(false)>0)) {
				$tpl->assign('final_price', $GLOBALS['STR_FROM'] . ' ' . $product_object->get_minimal_price());
			} else {
				$tpl->assign('final_price', $product_object->get_final_price(get_current_user_promotion_percentage(), $with_taxes, $reseller_mode, true, $display_with_vat_symbol, $quantity, true, true, $add_rdfa_properties));
			}
			if (($product_object->get_original_price($with_taxes, $reseller_mode) > $final_price) && $display_old_price) {
				// Si le nouveau prix du produit est inférieur au prix original, alors on affiche l'ancien prix
				$tpl->assign('original_price', $product_object->get_original_price($with_taxes, $reseller_mode, true));
			} else {
				$tpl->assign('original_price', null);
			}
			if ($display_ecotax && !empty($product_object->ecotaxe_ht) && check_if_module_active('ecotaxe')) {
				if (empty($GLOBALS['site_parameters']['product_ecotaxe_display_split'])) {
					$tpl->assign('ecotax', array(
						'label' => $GLOBALS['STR_WITH_ECOTAX'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
						'prix' => fprix($product_object->get_ecotax($with_taxes), true),
					));
				} else {
					$tpl->assign('prix_ht_without_ecotax', array(
					'label' => $GLOBALS['STR_ECOTAXE_INCLUDE'] . ' ' . $GLOBALS['STR_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
					'prix_ecotaxe' => fprix($product_object->get_ecotax($with_taxes), true),
					'STR_TOTAL_HT' => $GLOBALS['STR_TOTAL_HT']. $GLOBALS['STR_BEFORE_TWO_POINTS'],
					'prix' => fprix($product_object->get_original_price(false, false, false, false, false), true) . ' ' . $GLOBALS['STR_HT']));
				}
			} else {
				$tpl->assign('ecotax', null);
				$tpl->assign('prix_ht_without_ecotax', null);	
			}
			if ($display_with_measurement && !empty($product_object->poids) && $product_object->display_price_by_weight == '1') {
				$tpl->assign('measurement', array(
					'label' => $GLOBALS['STR_PRICE_WEIGHT'],
					'prix' => fprix($product_object->get_final_price(get_current_user_promotion_percentage(), $with_taxes, $reseller_mode, false, false, 1000 / floatval($product_object->poids)), true)
				));
			} elseif ($display_with_measurement && !empty($product_object->volume) && $product_object->display_price_by_weight == '2') {
				$tpl->assign('measurement', array(
					'label' => $GLOBALS['STR_PRICE_LITRE'],
					'prix' => fprix($product_object->get_final_price(get_current_user_promotion_percentage(), $with_taxes, $reseller_mode, false, false, 1000 / floatval($product_object->volume)), true)
				));
			}else {
				$tpl->assign('measurement', null);
			}
			$tpl->assign('hide_price', !empty($GLOBALS['site_parameters']['price_hide_if_not_loggued']) && !est_identifie() && empty($_GET['page_offline']));
			$tpl->assign('STR_PLEASE_LOGIN', '<a href="/membre.php">'.$GLOBALS['STR_PLEASE_LOGIN'].'</a>');
			$tpl->assign('STR_CONDITIONING_TEXT', (!empty($product_object->conditioning_text)?$product_object->conditioning_text:$GLOBALS['STR_PIECE']));
			$tpl->assign('conditionnement', vn($product_object->conditionnement));
			
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('display_on_estimate_information')) {
	/**
	 * display_on_estimate_information()
	 *
	 * @param boolean $return_mode
	 * @param boolean $return_mode
	 * @return
	 */
	function display_on_estimate_information($return_mode = false, $return_without_div = false)
	{
		$output = '';
		if(!isset($GLOBALS['site_parameters']['show_on_estimate_text']) || $GLOBALS['site_parameters']['show_on_estimate_text']) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('on_estimate_information.tpl');
			$tpl->assign('without_div', $return_without_div);
			$tpl->assign('label', $GLOBALS['STR_ON_ESTIMATE']);
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_produits')) {
	/**
	 * affiche_produits()
	 *
	 * @param mixed $condition_value1
	 * @param mixed $title_level
	 * @param mixed $type
	 * @param mixed $nb_par_page
	 * @param string $mode
	 * @param boolean $return_mode
	 * @param integer $reference_id
	 * @param integer $nb_colonnes
	 * @param mixed $no_display_if_empty
	 * @param boolean $always_show_multipage_footer
	 * @param string $additional_sql_inner
	 * @param string $additional_sql_cond
	 * @param string $additional_sql_having
	 * @param string $description_length
	 * @param string $template_additional_variables
	 * @param string $use_index_sql
	 * @param string $avoid_pagination_calculation
	 * @param string $return_json_html_mode
	 * @param string $page_mode
	 * @param integer $id_marque
	 * @return
	 */
	function affiche_produits($condition_value1, $title_level = 2, $type, $nb_par_page, $mode = 'general', $return_mode = false, $reference_id = 0, $nb_colonnes = null, $no_display_if_empty = false, $always_show_multipage_footer = true, $additional_sql_inner = null, $additional_sql_cond = null, $additional_sql_having = null, $description_length = null, $template_additional_variables = null, $use_index_sql = null, $avoid_pagination_calculation = false, $return_json_html_mode = null, $page_mode = 'full', $id_marque = 0)
	{
		if($mode == 'line') {
			$nb_colonnes = 1;
		}elseif(empty($nb_colonnes)) {
			$nb_colonnes = vn($GLOBALS['site_parameters']['product_category_pages_nb_column'], 3);
		}
		$params = params_affiche_produits($condition_value1, null, $type, $nb_par_page, $mode, $reference_id, $nb_colonnes, $always_show_multipage_footer, $additional_sql_inner, $additional_sql_cond, $additional_sql_having, $use_index_sql, $avoid_pagination_calculation, $id_marque);
		if (empty($description_length)) {
			// La longeur de la description n'est pas renseignée en paramètre de la fonction, il faut vérifier si il existe un paramètre général.
			if (!empty($GLOBALS['site_parameters']['catalog_product_description_length'])) {
				$description_length = $GLOBALS['site_parameters']['catalog_product_description_length'];
			} else {
				// Pas de valeur pour ce paramètre, on fixe à 250 caractères par défaut.
				$description_length = 250;
			}
		}
		if ($return_json_html_mode == 'multipage_info_only' && isset($_POST['products_found'])) {
			// Cas d'optimisation : on appelle en AJAX pour avoir le multipage, et on connaissait déjà le décompte de produits qu'on a transmis en POST
			// (si on a transmis $_POST['products_found'] c'est qu'on sait déjà que le nombre de produits est inférieur au nombre de résultats autorisé par page).
			// Donc pas besoin ici d'exécuter le SQL de sélection des produits.
			$params['Links']->Calcul(true, $_POST['products_found']);
		} else {
			$results_array = $params['Links']->Query();
		}
		
		if (!empty($GLOBALS['site_parameters']['display_product_stock_null_end_list'])) {
			// On souhaite placer les produits hors stock en bas de la liste de produits.
			$prod_array_on_stock = array();
			$prod_array_off_stock = array();
			foreach ($results_array as $prod) {
				$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'), !empty($params['show_draft']));
				$stock = get_stock($product_object->id, null, null);
				// On trie le produit "En stock" et "Hors stock" dans deux tableaux différents.
				if (!empty($stock) || $product_object->on_stock==0) {
					$prod_array_on_stock[] = $prod;
				} else {
					$prod_array_off_stock[] = $prod;
				}
			}
			// Fusion des deux tableaux, avec le tableau "hors stock" en dernier.
			$results_array = array_merge($prod_array_on_stock,$prod_array_off_stock);
		}
		if (check_if_module_active('carrousel') && !empty($results_array) && $type == 'associated_product' && !empty($GLOBALS['site_parameters']['associated_product_in_carrousel_display'])) {
			$slides_array = array();
			$i=0;
			$output ='';
			foreach ($results_array as $prod) {
				$product_object = new Product($prod['id']);
				//A méliorer pour rendre l'affichage paramétrable
				if($i%4 == 0) {
					$output = '';
				}
				$output .= '<div class="col-sm-4 col-md-3">'.get_product_in_container_html($product_object, true, false).'</div>';

				if($i%4 == 3) {
					$slides_array[][] = $output;
				}
				$i++;
			}
			// var_dump(Carrousel::get_carrousel_html($slides_array, false, true, false, 'carrousel_associated_product'));die();
			return '<div class="col-md-12">'.Carrousel::get_carrousel_html($slides_array, false, true, false, 'carrousel_associated_product').'</div>';
		}
		// Information sur nombre de produits trouvés mise en variable globale pour réutilisation a posteriori à l'extérieur de la fonction
		$GLOBALS['products_found'] = $params['Links']->nbRecord;
		if ($return_json_html_mode == 'multipage_info_only') {
			// Si on est en mode multipage_info_only, on souhaite uniquement avoir le bloc de pagination. On ne charge pas le reste de la fonction
			return $params['Links']->GetMultipage();
		}
		if (!empty($GLOBALS['site_parameters']['products_list_no_display_if_empty'])) {
			// permet d'administrer le paramètre no_display_if_empty
			$no_display_if_empty = true;
		}

		$tpl = $GLOBALS['tplEngine']->createTemplate('produits.tpl');
		if((empty($GLOBALS['site_parameters']['price_hide_if_not_loggued']) || (est_identifie() && (a_priv('util*') || a_priv('admin*') || a_priv('reve*')) && !a_priv('*refused') && !a_priv('*wait'))) && empty($GLOBALS['site_parameters']['brand_hide'])) {
			$tpl->assign('display_brand', true);
		}
		$tpl->assign('type', $type);
		$tpl->assign('IN_SEARCH', defined('IN_SEARCH'));
		$tpl->assign('page_offline', !empty($_GET['page_offline']));
		$tpl->assign('page_mode', $page_mode);
		$tpl->assign('associated_product_multiple_add_to_cart', vb($GLOBALS['site_parameters']['associated_product_multiple_add_to_cart'])); 
		$tpl->assign('displays_products_ref_brands_disabled', vb($GLOBALS['site_parameters']['displays_products_ref_brands_disabled'])); 
		$tpl->assign('is_associated_product', ((!$no_display_if_empty || !empty($results_array)) && $type == 'associated_product'));
		$tpl->assign('product_description_catalogue_disabled', !empty($GLOBALS['site_parameters']['product_description_catalogue_disabled']));
		$tpl->assign('show_more_details', vb($GLOBALS['site_parameters']['show_more_details']));
		$tpl->assign('str_more_details', $GLOBALS['STR_MORE_DETAILS']);
		$tpl->assign('str_estimate', $GLOBALS['STR_ESTIMATE']);		

		if(!is_user_bot() && !empty($GLOBALS['allow_show_all_sons_products']) && empty($_GET['sons'])) {
			$tpl->assign('show_all_sons_products_button', true);
			$show_all_sons_products_url = get_current_url(true);
			if(StringMb::strpos($show_all_sons_products_url, '?') !== false) {
				$show_all_sons_products_url .='&sons=1';
			} else {
				$show_all_sons_products_url .='?sons=1';
			}
			$tpl->assign('show_all_sons_products_url', $show_all_sons_products_url);
		}
		if(defined("IN_CATALOGUE") && !empty($_GET['catid']) && empty($_GET['page_offline'])) {
			$tpl->assign('menu_recherche', affiche_menu_recherche(true, 'category'));
		}
		$tpl->assign('is_associated_product', ((!$no_display_if_empty || !empty($results_array)) && $type == 'associated_product'));
		if (!$no_display_if_empty || !empty($results_array)) {
			$tpl->assign('titre', $params['titre']);
			if (!empty($params['titre']) && $type == 'associated_product') {
				$tpl->assign('titre_mode', 'associated');
			} elseif ($params['mode'] == 'home') {
				$tpl->assign('titre_mode', 'home');
			} elseif ($type == 'category') {
				$tpl->assign('titre_mode', 'category');
				$tpl->assign('filtre', $params['affiche_filtre']);
			} elseif (!empty($params['titre'])) {
				$tpl->assign('titre_mode', 'default');
			}
		}
		$tpl->assign('title_level', $title_level);
		$tpl->assign('products_found', $GLOBALS['products_found']);
		
		if (empty($results_array)) {
			$tpl->assign('no_results', true);
			if (!$no_display_if_empty) {
				if ($params['mode'] == 'line' || $params['mode'] == 'column') {
					$tpl->assign('no_results_msg', $GLOBALS['STR_NO_INDEX_PRODUCT']);
				} elseif ($params['mode'] == 'general') {
					$tpl->assign('no_results_msg', $GLOBALS['STR_NOT_AVAILABLE_CURRENTLY']);
				}
			}
		} else {
			$allow_order = false;
			$tpl->assign('no_results', false);
			if (vn($GLOBALS['site_parameters']['category_order_on_catalog']) == '1' || $type == 'save_cart') {
				$details_text = $GLOBALS['STR_MORE_DETAILS'];
				$allow_order = true;
			} elseif (vb($GLOBALS['site_parameters']['category_show_more_on_catalog_if_no_order_allowed'])) {
				$details_text = $GLOBALS['STR_MORE'];
			}
			$tpl->assign('details_text', vb($details_text));
			$tpl->assign('allow_order', $allow_order);
		}

		$tpl->assign('prods_line_mode', ($params['mode'] == 'line'));
		$tpl->assign('cartridge_product_css_class', $params['cartridge_product_css_class']);
		$tpl->assign('small_width', $params['small_width']);
		$tpl->assign('small_height', $params['small_height']);
		$tpl->assign('multipage', $params['Links']->GetMultipage());

		$prods = array();
		$j = 0;
		$total = 0;
		$GLOBALS['current_context'] = 'products_'.$type;
		foreach ($results_array as $prod) {
			if ((!a_priv("admin_product") && !a_priv("reve")) && vn($prod['on_reseller']) == 1) {
				continue;
			}
			$filter_hook = call_module_hook('filter_product_list', array('product' => $prod));
			if(empty($filter_hook)) {
				// on n'affiche pas ce produit;
				continue;
			}
			
			// De base on transmet l'ensemble des informations de la ligne de base de données, et ensuite on va surcharger avec des informations complémentaires
			$tmpProd = $prod;
			$tmpProd['display_border'] = (($j % $params['nb_colonnes'] != $params['nb_colonnes'] - 1) && ($j != count($results_array) - 1));
			
			$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'), !empty($params['show_draft']));
			$product_available_for_user = call_module_hook('check_if_product_available_for_user', array('product_object' => $product_object), 'array');
			if (!empty($GLOBALS['site_parameters']['user_offers_table_enable']) && empty($product_available_for_user['result'])) {
				$tmpProd['add_cart_disable']= true;
				$tmpProd['is_quote'] = $product_available_for_user['is_quote'];
				$tpl->assign('STR_PRODUCT_NOT_AVAILABLE_CONTACT_SELL_SERVICE', $GLOBALS['STR_PRODUCT_NOT_AVAILABLE_CONTACT_SELL_SERVICE']);
			}
			// on affiche une cellule
			$tmpProd['i'] = $j + 1;
			if ($type == 'flash_passed' && est_identifie() && check_if_module_active('photos_gallery') && check_if_module_active('participants') && user_is_already_registred($product_object->id, $_SESSION['session_utilisateur']['id_utilisateur'])) {
				// Il faut afficher le bouton de création de gallerie uniquement aux participants et si la date de l'évènement est passé.
				$tmpProd['gallery_button'] = add_gallery_link($product_object->id);
			}
			if ($type == 'save_cart') { // Si on est dans le module save_cart on ajoute les actions du module
				$tmpProd['save_cart'] = array(
					'src' => get_url('/' . $GLOBALS['site_parameters']['backoffice_directory_name'] . '/images/b_drop.png'),
					'href' => get_current_url(false) . '?mode=delete&id=' . $prod['save_cart_id'],
					'confirm_msg' => $GLOBALS['STR_DELETE_CART_PRESERVATION'],
					'title' => $GLOBALS['STR_DELETE_CART_TITLE'],
					'label' => $GLOBALS['STR_DELETE']
				);
				// Calcul du total TTC de produit.
				$total += $product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), false, false, $prod['saved_quantity']);
			}
			$tmpProd['href'] = $product_object->get_product_url();
			if (!empty($GLOBALS['site_parameters']['display_product_name_shorten_in_category']) && defined('IN_CATALOGUE')) {
				$tmpProd['name'] = StringMb::str_shorten($product_object->name,vn($GLOBALS['site_parameters']['number_string_display_product_name_shorten_in_category'],30), '', '...');
			} else {
				$tmpProd['name'] = $product_object->name;
			}
			if(isset($product_object->price_estimate)) {
				$tmpProd['price_estimate'] = ($product_object->price_estimate>0?fprix($product_object->price_estimate, true):false);
			}
			$tmpProd['quantity_min_order'] = vn($prod['quantity_min_order']);
			$tmpProd['thumbnail_promotion'] = '';
			$tmpProd['text_promotion'] = '';
			if (empty($GLOBALS['site_parameters']['produits_references_quantity_disable'])) {
				$tmpProd['quantity'] = get_quantity_product_reference($prod['id'],$reference_id);
			} else {
				$tmpProd['quantity'] = 0;
			}
			if ($product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true) > 0 && !empty($GLOBALS['site_parameters']['thumbnail_promotion_in_products_columns'])) {
				$tmpProd['thumbnail_promotion'] = true;
				$tmpProd['promotion'] = $product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true);
			}
			if (!empty($GLOBALS['site_parameters']['img_promotion_in_products_columns']) && ($product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true) > 0 || !empty($product_object->on_promo))) {
				$tmpProd['img_promotion'] = $GLOBALS['repertoire_images'].'/'.vb($GLOBALS['site_parameters']['img_promotion_in_products_columns']);
			}
			if (!empty($GLOBALS['site_parameters']['img_new_in_products_columns']) && !empty($product_object->on_new)) {
				$tmpProd['img_new'] = $GLOBALS['repertoire_images'].'/'.vb($GLOBALS['site_parameters']['img_new_in_products_columns']);
			}
			if ($mode == 'line' || empty($GLOBALS['site_parameters']['disable_description_in_products_columns'])) {
				if(empty($GLOBALS['site_parameters']['description_in_products_columns'])) {
					$tmpProd['description'] = StringMb::str_shorten_words(StringMb::str_shorten(str_replace(array("\n","\r", '  ', '   '), ' ',StringMb::strip_tags(StringMb::nl2br_if_needed(trim($product_object->descriptif)))), $description_length), 60);
				} else {
					$tmpProd['description'] = StringMb::str_shorten_words(StringMb::str_shorten(str_replace(array("\n","\r", '  ', '   '), ' ',StringMb::strip_tags(StringMb::nl2br_if_needed(trim($product_object->description)))), $description_length), 60);
				}
			}
			if (!empty($_GET['cId']) && !empty($_GET['pId']) && $_GET['pId'] == $prod['id']) {
				// Lors de la sélection de la couleur d'un produit depuis une page catalogue
				$display_picture = $product_object->get_product_pictures(false, $_GET['cId'], true);
				$display_pictures[] = $display_picture[0];
			} else {
				if(!empty($GLOBALS['site_parameters']['product_overlay_in_category_page'])) {
					$display_pictures = $product_object->get_product_pictures(true);
				} else {
					$display_pictures = array($product_object->get_product_main_picture(true));
				}
			}
			// Boucle permettant d'envoyer les 2 premières images produit
			foreach ($display_pictures as $key => $display_picture) {
				$image_key = 'image';
				// S'il s'agit de la clé 1, il s'agit donc de la seconde image
				if($key == 1) {
					$image_key = 'image_second';
				// On arrête la boucle une fois les 2 premières images envoyées
				} elseif($key > 1) {
					break;
				}
				if (!empty($display_picture)) {
					if (get_file_type($display_picture) != 'image') {
							$tmpProd[$image_key] = array(
							'src' => thumbs($display_picture, $params['small_width'], $params['small_height'], 'fit', null, null, true, true),
							'width' => $GLOBALS['site_parameters']['small_width'],
							'height' => $GLOBALS['site_parameters']['small_height'],
							'alt' => $product_object->name,
							'zoom' => array(
								'href' => get_url_from_uploaded_filename($display_picture),
								'is_lightbox' => false,
								'file_type' => get_file_type($display_picture),
								'label' => $GLOBALS['STR_ZOOM']
							)
						);
					} else {
							$tmpProd[$image_key] = array(
							'src' => thumbs($display_picture, $params['small_width'], $params['small_height'], 'fit', null, null, true, true),
							'width' => false,
							'height' => false,
							'alt' => $product_object->name,
							'zoom' => array(
								'href' => get_url_from_uploaded_filename($display_picture),
								'is_lightbox' => empty($GLOBALS['site_parameters']['lightbox_disable']),
								'file_type' => get_file_type($display_picture),
								'label' => $GLOBALS['STR_ZOOM']
							)
						);
					}
				} elseif(!empty($GLOBALS['site_parameters']['default_picture'])) {
						$tmpProd[$image_key] = array(
							'src' => thumbs($GLOBALS['site_parameters']['default_picture'], $params['small_width'], $params['small_height'], 'fit', null, null, true, true),
							'width' => '130',
							'height' => false,
							'alt' => $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT']
						);
				} else {
						$tmpProd[$image_key] = null;
				}
			}
			if ($product_object->is_price_flash(check_if_module_active('reseller') && is_reseller())) {
				$tmpProd['flash'] = $GLOBALS['STR_TEXT_FLASH1'] . ' ' . get_formatted_duration(strtotime($product_object->flash_end) - time(), false, 'day') . ' ' . $GLOBALS['STR_TEXT_FLASH2'];
			}
			if (check_if_module_active('participants') && est_identifie() && $_SESSION['session_utilisateur']['id_utilisateur'] != $product_object->id_utilisateur) {
				$tmpProd['subscribe_trip_form'] = get_subscribe_trip_form($product_object->id, vn($_SESSION['session_utilisateur']['id_utilisateur']));
			}
			// Affichage des produits en ligne
			if (empty($GLOBALS['site_parameters']['product_price_display_disable'])) {
				if (!empty($product_object->on_estimate)) {
					$tmpProd['on_estimate'] = display_on_estimate_information(true);
				} elseif($product_object->on_gift) {
					$tmpProd['on_estimate'] = $product_object->on_gift_points . ' ' . $GLOBALS['STR_GIFT_POINTS'];
				} elseif(!empty($GLOBALS['site_parameters']['price_force_display_even_if_empty']) || $product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller()) != 0) {
					if ((vn($GLOBALS['site_parameters']['category_order_on_catalog']) != 1) && ($type != 'save_cart') && empty($GLOBALS['site_parameters']['disable_add_to_cart_section_for_products'])) {
						$tmpProd['on_estimate'] = $product_object->affiche_prix(display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true, false, null, false, true, 'full_width', ($params['mode'] != 'line'), false, null);
					}
				} elseif(!empty($GLOBALS['site_parameters']['free_displayed_if_price_empty_disable'])) {
					$tmpProd['on_estimate'] = '';
				} else {
					$tmpProd['on_estimate'] = '<span class="title_price_free">'.$GLOBALS['STR_FREE'].'</span>';
				}
			} else {
				$tmpProd['on_estimate'] = '';
			}
			
			if ($product_object->on_stock == 1 && check_if_module_active('stock_advanced')) {
				$tmpProd['stock_state'] = $product_object->get_product_stock_state();
			}
			$tmpProd['product_brands'] = $product_object->get_product_brands(false);
			$tmpProd['ref'] = $product_object->reference;


			if (check_if_module_active('departements') && !est_identifie() && empty($_SESSION['departement_visiteur'])) {
				// Affichage du bouton de sélection de choix de departement
				$tmpProd['departements_get_bootbox_dialog'] = departements_get_bootbox_dialog($product_object->id);
			} elseif(empty($GLOBALS['site_parameters']['product_disable_ad_cart_if_user_not_logged']) || (!empty($GLOBALS['site_parameters']['product_disable_ad_cart_if_user_not_logged']) && est_identifie())) {
				if (vn($GLOBALS['site_parameters']['category_order_on_catalog']) == '1' || $type == 'save_cart') {
					if (!empty($product_object->on_check) && check_if_module_active('gift_check')) {
						$tmpProd['check_critere_stock'] = affiche_check($product_object->id, 'cheque', null, true);
					} else {
						if ($type == 'save_cart') {
							$tmpProd['check_critere_stock'] = affiche_critere_stock($product_object, 'save_cart_details_', null, true, true, vn($prod['save_cart_id']), vn($prod['saved_couleur_id']), vn($prod['saved_taille_id']), vn($prod['saved_attributs_list']), vn($prod['saved_quantity']));
						} elseif ($type == 'search') {
							$tmpProd['check_critere_stock'] = affiche_critere_stock($product_object, 'catalogue_details_', null, true);
						} else {
							$tmpProd['check_critere_stock'] = affiche_critere_stock($product_object, 'catalogue_details_', null, true, true);
						}
					}
				}
			}
			if (empty($GLOBALS['site_parameters']['admin_product_edit_links_in_front_office_disable']) && a_priv('admin_products', false)) {
				$tmpProd['admin'] = array(
					'href' => $GLOBALS['administrer_url'] . '/produits.php?mode=modif&id=' . $product_object->id,
					'label' => $GLOBALS['STR_MODIFY_PRODUCT']
				);
			}
			$tmpProd['last_month'] = ($prod['date_insere'] > date("Y-m-d", time() - 30 * 24 * 3600));
			if (!empty($product_object->technical_code)) {		
				$tmpProd['product_list_html_zone'] = affiche_contenu_html('product_list_html_zone_'.$product_object->technical_code, true);
			}
			if (est_identifie() && !empty($GLOBALS['site_parameters']['enable_create_product_in_front']) && $product_object->id_utilisateur == $_SESSION['session_utilisateur']['id_utilisateur']) {
				// On récupère les informations manquantes
				$sql = "SELECT p.id, p.titre_" . $_SESSION['session_langue'] . " AS titre, p.on_special, pc.rubrique_id, r.nom_" . $_SESSION['session_langue'] . " AS rubrique_nom
					FROM peel_articles p
					INNER JOIN peel_articles_rubriques pc ON p.id = pc.article_id " . (!empty($category_id)?" AND pc.rubrique_id = '" . intval($category_id) . "'":'') . "
					INNER JOIN peel_rubriques r ON r.id = pc.rubrique_id AND " . get_filter_site_cond('rubriques', 'r') . "
					WHERE p.technical_code = 'display_product_form' AND p.etat='1' AND titre_" . $_SESSION['session_langue'] . "!='' AND " . get_filter_site_cond('articles', 'p') . "";
				$query = query($sql) ;
				if ($result = fetch_assoc($query)) {
					//get_default_content remplace le contenu par la langue par défaut si les conditions sont réunies
					if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
						$result = get_default_content($result, $result['id'], 'articles');
					}
					$tmpProd['modify_product_by_owner'] = array(
						'href' => get_content_url($result['id'], $result['titre'], $result['rubrique_id'], $result['rubrique_nom']) . '?mode=modif&product_id=' . $product_object->id,
						'label' => $GLOBALS['STR_MODIFY_PRODUCT']
					);
				}
			}
			$hook_result = call_module_hook('product_list_additional_infos', array('id' => $product_object->id, 'id_utilisateur' => $product_object->id_utilisateur, 'categorie_id' => $product_object->categorie_id, 'position' => $product_object->position), 'array');
			foreach($hook_result as $this_key => $this_value) {
				$tmpProd[$this_key] = $this_value;
			}
			
			// Requête permettant la récupération de la note moyenne du produit
			$qavisnote = "SELECT AVG(note) AS average_rating
				FROM peel_avis
				WHERE id_produit = '" . intval($product_object->id) . "'
				AND etat='1'";
			$resavisnote = query($qavisnote);
			if($note = fetch_assoc($resavisnote)) {
				$avisnote = number_format($note['average_rating'], 0);
			}
			$tmpProd['avisnote'] = $avisnote;
			
			$j++;
			
			//Limite le nombre d'affichage pour les produits 'special'
			if (!empty($GLOBALS['site_parameters']['product_special_display_limit']) && $GLOBALS['site_parameters']['product_special_display_limit'] == $j && $type == 'special') {
				break;
			}
			
			unset($product_object);
			$prods[] = $tmpProd;
		}
		$GLOBALS['current_context'] = null;
		$tpl->assign('products', $prods);
		$tpl->assign('nb_col_sm', min($params['nb_colonnes'],3));
		$tpl->assign('nb_col_md', $params['nb_colonnes']);
		$tpl->assign('vars', $template_additional_variables);
		$hightlight_found_words_array = '';
		$tpl->assign('hightlight_found_words_array', $hightlight_found_words_array);
		
		$tpl->assign('STR_ADD_CART', $GLOBALS['STR_ADD_CART']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_TOTAL', $GLOBALS['STR_TOTAL'] .' '. (display_prices_with_taxes_active() ? $GLOBALS['STR_TTC'] : $GLOBALS['STR_HT']));
		$tpl->assign('STR_EMAIL_SUBJECT_PRICE_REQUEST', $GLOBALS['STR_EMAIL_SUBJECT_PRICE_REQUEST']);
		$tpl->assign('STR_EMAIL_BODY_PRICE_REQUEST', $GLOBALS['STR_EMAIL_BODY_PRICE_REQUEST']);
		$tpl->assign('STR_ON_REQUEST', $GLOBALS['STR_ON_REQUEST']);
		// On envoi au tpl l'information comme quoi l'affichage des catégories sera différente
		if(!empty($GLOBALS['site_parameters']['product_overlay_in_category_page'])) {
			$tpl->assign('product_overlay_in_category_page', true);
		}
		if (!empty($total)) {
			$tpl->assign('total', fprix($total, true));
		} 

		// Si il n'y a pas de produit associé, on ne retourne rien
		if ($type == 'associated_product' && $j == 0) {
			return false;
		}
		$output = $tpl->fetch();
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_critere_stock')) {
	/**
	 * affiche_critere_stock()
	 *
	 * @param object $product_object
	 * @param string $form_basename
	 * @param integer $save_cart_id
	 * @param integer $saved_color_id
	 * @param integer $saved_size_id
	 * @param string $saved_attributs_list
	 * @param integer $saved_quantity
	 * @return
	 */
	function affiche_critere_stock(&$product_object, $form_basename, $listcadeaux_owner = null, $return_mode = false, $is_in_catalog = false, $save_cart_id = null, $saved_color_id = null, $saved_size_id = null, $saved_attributs_list = null, $saved_quantity = null)
	{
		// Dans le module save_cart on peut avoir plusieurs sauvegardes du même produit avec différentes options
		// la variable $save_suffix_id nous permet de gérer l'unicité d'id dans la page au besoin
		if (!empty($save_cart_id)) {
			$save_suffix_id = '_' . $save_cart_id;
		} elseif ($is_in_catalog) {
			$save_suffix_id = '_' . $product_object->id;
		} else {
			$save_suffix_id = '';
		}
		$form_id = $form_basename . 'ajout' . $product_object->id . $save_suffix_id;
		if ($GLOBALS['site_parameters']['anim_prod'] == 1) {
			$anim_prod_var = ' addToBasket(' . $product_object->id . '); setTimeout(\'document.getElementById(\\\'' . $form_id . '\\\').submit()\',1000); return false;';
		} else {
			$anim_prod_var = '';
		}
		// Si $condensed_display_mode est à true, alors on affiche un seul select pour couleur et taille. C'est le cas pour la page de liste de cadeaux.
		// Affichage de la taille et de la couleur sur une seule ligne, pour permettre l'ajout au panier du produit avec la taille / couleur choisie par l'auteur de la liste uniquement
		$condensed_display_mode = defined('IN_LISTE_CADEAU');
		$selected_color_id = 0;

		$output = '';
		
		// Gestion de la couleur
		$and_scId_if_needed = empty($save_cart_id) || (!empty($save_cart_id) && !empty($_GET['scId']) && $save_cart_id == vn($_GET['scId']));
		if ($and_scId_if_needed && ((!empty($_GET['cId']) && !$is_in_catalog) || ($is_in_catalog && !empty($_GET['cId']) && !empty($_GET['pId']) && vn($_GET['pId']) == $product_object->id))) {
			$selected_color_id = intval($_GET['cId']);
		} elseif (!empty($product_object->configuration_color_id)) {
			$selected_color_id = $product_object->configuration_color_id;
		} elseif (!empty($product_object->default_color_id)) {
			$selected_color_id = $product_object->default_color_id;
		} elseif($saved_color_id) {
			// On prend la première valeur du tableau
			$selected_color_id = $saved_color_id;
		} else {
			// On prend la première valeur du tableau
			$selected_color_id = 0;
		}
	
		$product_object->set_configuration($selected_color_id, $saved_size_id, $saved_attributs_list, check_if_module_active('reseller') && is_reseller(), false);
		if (check_if_module_active('stock_advanced') && $product_object->on_stock == 1) {
			$product_stock_infos = get_product_stock_infos($product_object->id, $product_object->configuration_size_id, $product_object->configuration_color_id);
			// on regarde la quantité du produit en stock
			$stock_remain_all = 0;
			if (!empty($product_stock_infos)) {
				foreach ($product_stock_infos as $stock_infos) {
					if (($is_in_catalog && empty($product_object->configuration_color_id)) || (!empty($product_object->configuration_color_id) && $stock_infos['couleur_id'] == $product_object->configuration_color_id) || (empty($_GET['cId']) && empty($_GET['tId'])) || (!empty($stock_infos['couleur_id']) && !empty($_GET['cId']) && $_GET['cId'] == $stock_infos['couleur_id']) || (!empty($stock_infos['taille_id']) && !empty($_GET['tId']) && $_GET['tId'] == $stock_infos['taille_id'])) {
						$stock_remain_all += $stock_infos['stock_temp'];
					}
				}
			}
			if ($stock_remain_all <= 0 && empty($product_object->allow_add_product_with_no_stock_in_cart) && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
				// Si le stock est inférieur ou égale à 0.
				// on force la rupture de stock
				$product_object->on_rupture = 1;
			}
		}
		$urlprod_with_cid = $product_object->get_product_url(true, true) . 'cId=';
		$urlcat_with_cid = get_current_url(false) . '?'. (!empty($_GET['catid'])?'catid='. $_GET['catid'] .'&':'') . 'pId=' . vn($product_object->id) . '&cId=';
		
		$tpl = $GLOBALS['tplEngine']->createTemplate('critere_stock.tpl');
		
		if (check_if_module_active('pensebete')) {
			$tpl->assign('pensebete', array(
				'href' => $GLOBALS['wwwroot'] . '/modules/pensebete/ajouter.php?mode=ajout&prodid=' . vn($product_object->id),
				'txt' => $GLOBALS['STR_AJOUT_PENSE_BETE']
			));
		}
		if (!empty($GLOBALS['site_parameters']['remove_product_after_adding_to_cart'])) {
			// cette valeur save_cart_id sera envoyée en POST à caddie_ajout, qui supprimera le produit du panier sauvegardé.
			$tpl->assign('save_cart_id', $save_cart_id);
		}
		$tpl->assign('get_possible_sizes_array', $product_object->get_possible_sizes('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true, true));
		$tpl->assign('STR_PRICE', $GLOBALS['STR_PRICE']);
		$tpl->assign('product_technical_code', $product_object->technical_code);
		$tpl->assign('is_sizes_select', empty($GLOBALS['site_parameters']['is_sizes_array']));
		$tpl->assign('save_suffix_id', $save_suffix_id);
		$tpl->assign('is_in_catalog', $is_in_catalog);
		$tpl->assign('urlprod_with_cid', $urlprod_with_cid);
		$tpl->assign('STR_BEFORE_TWO_POINTS', str_replace(' ', '&nbsp;', $GLOBALS['STR_BEFORE_TWO_POINTS']));
		$tpl->assign('STR_COLOR', $GLOBALS['STR_COLOR']);
		$tpl->assign('STR_CHOOSE_COLOR', $GLOBALS['STR_CHOOSE_COLOR']);
		$tpl->assign('STR_NO_AVAILABLE', $GLOBALS['STR_NO_AVAILABLE']);
		$tpl->assign('STR_SIZE', $GLOBALS['STR_SIZE']);
		$tpl->assign('STR_CHOOSE_SIZE', $GLOBALS['STR_CHOOSE_SIZE']);
		$tpl->assign('STR_STOCK_ATTRIBUTS', $GLOBALS['STR_STOCK_ATTRIBUTS']);
		$tpl->assign('product_id', vn($product_object->id));
		$tpl->assign('STR_ORDER_MIN', $GLOBALS['STR_ORDER_MIN']);
		$product_available_for_user = call_module_hook('check_if_product_available_for_user', array('product_object' => $product_object), 'array');
		if (!empty($GLOBALS['site_parameters']['user_offers_table_enable']) && empty($product_available_for_user['result'])) {
			$tpl->assign('add_cart_disable', true);
			$tpl->assign('is_quote', $product_available_for_user['is_quote']);
			$tpl->assign('STR_PRODUCT_NOT_AVAILABLE_CONTACT_SELL_SERVICE', $GLOBALS['STR_PRODUCT_NOT_AVAILABLE_CONTACT_SELL_SERVICE']);
		}
		$sizes_id_out_stock = array();
		if (empty($product_object->on_rupture) || (!empty($product_object->on_rupture) && !empty($GLOBALS['site_parameters']['product_formulaire_alerte_disable']))) {
			$colors_infos_array = $product_object->get_possible_colors('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller());
			$sizes_infos_array = $product_object->get_possible_sizes('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller());
			$attributs_infos_array = $product_object->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller());
			
			if(empty($GLOBALS['site_parameters']['show_add_to_cart_on_free_products']) && empty($product_object->on_gift) && ($product_object->on_estimate || $product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller()) == 0 && empty($colors_infos_array) && empty($sizes_infos_array) && empty($attributs_infos_array) && !empty($GLOBALS['site_parameters']['disable_add_to_cart_section_if_null_base_price_and_no_option']) || (!empty($GLOBALS['site_parameters']['disable_add_to_cart_section_for_products'])))) {
				// Si le produit est "sur devis", cette fonction affiche_critere_stock ne doit pas être utilisé, de la même façon que si le produit n'a pas de prix
				return false;
			}
			$update_class = (!empty($attributs_infos_array) ? 'special_select' : '');
			
			if ((!empty($product_object->on_rupture) && !empty($GLOBALS['STR_POPUP_STOCK_ALERT']) && !empty($GLOBALS['site_parameters']['product_formulaire_alerte_disable']))) {
				$tpl->assign('popup_stock_alert', $GLOBALS['STR_POPUP_STOCK_ALERT']);
			}
				
			$action = $GLOBALS['wwwroot'] . '/achat/caddie_ajout.php?prodid=' . $product_object->id;
			if (!empty($_GET['reference'])) {
				// On peut définir une autre référence pour ce produit. C'est utilisé pour que le produit porte une information supplémentaire, comme un ID d'annonce par exemple.
				$action .= '&reference=' . $_GET['reference'];
			}
			if (!empty($_GET['campaign'])) {
				// $_GET['campaign'] : On ne met pas volontairement campaign_id, car la présence de id dans le paramètre en GET sur la page de produit déclenche une redirection. Par contre pas de problème sur la page caddie_ajout
				$action .= '&campaign_id=' . $_GET['campaign'];
			}
			if (!empty($product_available_for_user['is_quote'])) {
				$action .= '&is_quote=1';
			}
			$tpl->assign('is_form', true);
			$tpl->assign('action', $action);
			$tpl->assign('form_id', $form_id);
			$tpl->assign('update_class', $update_class);
			$tpl->assign('condensed_display_mode', $condensed_display_mode);
			if (!$condensed_display_mode) {
				// DISPLAY BY DEFAULT OF $GLOBALS['STR_COLOR'] AND $GLOBALS['STR_SIZE'] SELECTS BEGINS HERE
				if (!empty($colors_infos_array)) {
					$tpl->assign('is_color', true);
					$id_select_color = 'couleur' . $save_suffix_id;
					if (!empty($save_cart_id)) {
						$scId_if_needed = '+\'&scId=' . $save_cart_id . '#save_cart_' . $save_cart_id . '\'';
					} else {
						$scId_if_needed = '';
					}
					if (!$is_in_catalog && !empty($urlprod_with_cid)) {
						$on_change_action = 'document.location=\'' . $urlprod_with_cid . '\'+getElementById(\'' . $id_select_color . '\').value' . $scId_if_needed . ';';
					} elseif ($is_in_catalog && !defined('IN_SEARCH_BRAND') && !empty($urlcat_with_cid) && check_if_module_active('stock_advanced') && ($product_object->on_stock == 1)) {
						// Ajout de redirection pour recharger la page après sélection d'un attribut
						$on_change_action = 'document.location=\'' . $urlcat_with_cid . '\'+getElementById(\'' . $id_select_color . '\').value' . $scId_if_needed . ';';
					} else {
						$on_change_action = '';
					}
					$tpl->assign('id_select_color', $id_select_color);
					$tpl->assign('color_on_change_action', $on_change_action);
					$tplColors = array();
					foreach ($colors_infos_array as $this_color) {
						$option_content = '';
						$isavailable = true;
						if (check_if_module_active('stock_advanced') && $product_object->on_stock == 1 && empty($product_object->allow_add_product_with_no_stock_in_cart) && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
							// Récupération du stock pour la couleur.
							$this_color_product_stock_infos = get_product_stock_infos($product_object->id, $product_object->configuration_size_id, $this_color['couleur_id']);
							// Par défaut on considère que le produit n'est pas disponible dans la configuration de stock étudiée
							// Si $product_stock_infos est incomplet, cela ne posera donc pas de problème
							$isavailable = false;
							foreach ($this_color_product_stock_infos as $this_stock_info) {
								if ($this_stock_info['couleur_id'] == $this_color['couleur_id'] && $this_stock_info['stock_temp'] > 0) {
									$isavailable = true;
								}
							}
						}
						if (empty($product_object->on_gift)) {
							if (!empty($this_color['row_final_price']) && $this_color['row_final_price'] > 0) {
								$option_content .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': +' . fprix($this_color['final_price_formatted'], true);
							} elseif (!empty($this_color['row_final_price']) && $this_color['row_final_price'] < 0) {
								$option_content .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($this_color['final_price_formatted'], true);
							}
						}
						
						$tplColors[] = array(
							'id' => $this_color['couleur_id'],
							'issel' => ($selected_color_id == $this_color['couleur_id']),
							'isavailable' => $isavailable,
							'suffix' => $option_content,
							'name' => $this_color['nom_' . $_SESSION['session_langue']]
						);
					}
					$tpl->assign('colors', $tplColors);
					
					//Affichage de l'image couleur
					$qid_color = query('SELECT image FROM peel_couleurs WHERE id = ' . intval($product_object->configuration_color_id) . '');
					$image_color = fetch_assoc($qid_color);
					if (!empty($image_color['image'])) {
						if(!empty($image_color['image'])){
							$image = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($image_color['image'], vn($GLOBALS['site_parameters']['small_width_image_color'],50), vn($GLOBALS['site_parameters']['small_height_image_color'],50), "fit");
							$tpl->assign('src_img_color', $image);
						}
					}
				} else {
					$tpl->assign('is_color', false);
					$selected_color_id = 0;
				}
				if (!empty($sizes_infos_array) && count($sizes_infos_array)>=1) {
					$tpl->assign('is_sizes', true);
					// $condensed_display_mode est à false, donc on affiche un select différent pour couleur et taille
					// Gestion de la taille
					$id_select_size = 'taille' . $save_suffix_id;
					$tpl->assign('id_select_size', $id_select_size);

					$tplSizes = array();
					foreach ($sizes_infos_array as $this_size_infos) {
						$selected = false;
						$update_product_price_needed = false;
						$isavailable = true;
						$option_content = '';
						if (!empty($_SESSION['session_taille_id']) && !defined('IN_CART_PRESERVATION')) {
							if ($this_size_infos['id'] == $_SESSION['session_taille_id']) {
								$selected = true;
								unset($_SESSION['session_taille_id']);
							}
						} elseif (!empty($_GET['sId'])) {
							if ($this_size_infos['id'] == $_GET['sId']) {
								$selected = true;
							}
						} elseif (!empty($product_object->configuration_size_id)) {
							if ($this_size_infos['id'] == $product_object->configuration_size_id) {
								$selected = true;
							}
						}
						if($selected){
							// Comme on fait une sélection par défaut de la taille, on doit mettre à jour le prix automatiquement au chargement
							$update_product_price_needed = true;
						}
						
						if (empty($product_object->on_gift)) {
							if (!empty($this_size_infos['row_final_price']) && $this_size_infos['row_final_price'] > 0 && (empty($GLOBALS['site_parameters']['disable_display_size_overcost']))) {
								$option_content .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': +' . fprix($this_size_infos['final_price_formatted'], true);
							} elseif (!empty($this_size_infos['row_final_price']) && $this_size_infos['row_final_price'] < 0 && (empty($GLOBALS['site_parameters']['disable_display_size_overcost']))) {
								$option_content .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($this_size_infos['final_price_formatted'], true);
							}
						}
						$found_stock_info = 0;
						if (check_if_module_active('stock_advanced') && $product_object->on_stock == 1) {
							// on affiche des informations de stock seulement si la couleur est déjà sélectionnée ou si pas de couleur
							foreach ($product_stock_infos as $this_stock_info) {
								// Couleur sélectionnée : on affiche les informations de stock à cette taille combinée à la couleur sélectionnée
								if (($this_stock_info['couleur_id'] == $selected_color_id || empty($selected_color_id)) && $this_stock_info['taille_id'] == $this_size_infos['id']) {
									$found_stock_info += $this_stock_info['stock_temp'];
								}
							}
							if ($found_stock_info > 0 || !empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart']) || !empty($product_object->allow_add_product_with_no_stock_in_cart)) {
								if ($product_object->affiche_stock == 1 && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart']) && empty($product_object->allow_add_product_with_no_stock_in_cart)) {
									$option_content .= ' - ' . $GLOBALS['STR_STOCK_ATTRIBUTS'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $found_stock_info;
								} elseif (!empty($GLOBALS['site_parameters']['display_etat_stock_in_option'])) {
									$option_content .= ' - ' . affiche_etat_stock($found_stock_info, false, true, true);
								}
							} else {
								// Pas disponible : On indique que le critère n'est pas disponible et on désactive l'option
								$isavailable = false;
								$option_content .= ' - ' . $GLOBALS['STR_NO_AVAILABLE'];
							}
						}
						$tplSizes[] = array(
							'id' => $this_size_infos['id'],
							'issel' => $selected,
							'isavailable' => $isavailable,
							'name' => $this_size_infos['nom_' . $_SESSION['session_langue']],
							'found_stock_info' => $found_stock_info,
							'suffix' => $option_content,
							'update_product_price_needed' => $update_product_price_needed
						);
						if ($found_stock_info == 0 && $product_object->on_stock == 1) {
							$sizes_id_out_stock[] = "'".$this_size_infos['id']."'";
						}
					}
					$tpl->assign('sizes_options', $tplSizes);
				} else {
					$tpl->assign('is_sizes', false);
				}
			} else {
				$tplStocks = array();
				if (check_if_module_active('stock_advanced') && $product_object->on_stock == 1) {
					foreach ($product_stock_infos as $stock_infos) {
						if ($stock_infos['stock_temp'] > 0 || !empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart']) || !empty($product_object->allow_add_product_with_no_stock_in_cart)) {
							$tmpStockLabel = '';
							if (!empty($stock_infos['couleur_nom'])) {
								$tmpStockLabel .= $GLOBALS['STR_COLOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $stock_infos['couleur_nom'];
							}
							if (!empty($stock_infos['taille_nom'])) {
								if(!empty($tmpStockLabel)) {
									// Séparateur
									$tmpStockLabel .= ' - '; 
								}
								$tmpStockLabel .= $GLOBALS['STR_SIZE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $stock_infos['taille_nom'];
							}
							if (!empty($stock_infos['taille_id']) && !empty($sizes_infos_array[$stock_infos['taille_id']]['row_final_price'])) {
								$tmpStockLabel .= ' &nbsp; +' . $sizes_infos_array[$stock_infos['taille_id']]['final_price_formatted'];
							}
							if ($product_object->affiche_stock == 1) {
								$tmpStockLabel .= ' - ' . $GLOBALS['STR_STOCK_ATTRIBUTS'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $stock_infos['stock_temp'];
							}
							$output .= '</option>';
							if (!empty($stock_infos['couleur_id']) || !empty($stock_infos['taille_id'])) {
								$tplStocks[] = array(
									'isavailable' => true,
									'value' => $stock_infos['couleur_id'] . '|' . $stock_infos['taille_id'],
									'issel' => (vb($_GET['liste']) == $stock_infos['couleur_id'] . '|' . $stock_infos['taille_id']),
									'label' => $tmpStockLabel
								);
							}
						} else {
							// Produit non disponible => pas achetable
							$tplStocks[] = array(
								'isavailable' => false,
								'couleur_nom' => $stock_infos['couleur_nom'],
								'taille_nom' => $stock_infos['taille_nom'],
							);
						}
					}
				}
				if (!empty($tplStocks)) {
					$tpl->assign('stock_options', $tplStocks);
				}
			}
		} else {
			$tpl->assign('update_class', '');
			$tpl->assign('is_form', false);
		}
		$tpl->assign('sizes_id_out_stock', !empty($sizes_id_out_stock));
		$tpl->assign('STR_DELIVERY_STOCK', $GLOBALS['STR_DELIVERY_STOCK']);
		$tpl->assign('STR_QUANTITY', $GLOBALS['STR_QUANTITY']);
		$tpl->assign('STR_NONE_COLOR_SELECTED', $GLOBALS['STR_NONE_COLOR_SELECTED']);
		$tpl->assign('STR_NONE_SIZE_SELECTED', $GLOBALS['STR_NONE_SIZE_SELECTED']);
		$tpl->assign('STR_ADD_CART', $GLOBALS['STR_ADD_CART']);

		if (empty($product_object->on_rupture) || (!empty($product_object->on_rupture) && !empty($GLOBALS['site_parameters']['product_formulaire_alerte_disable']))) {
			// Gestion des autres attributs
			if (check_if_module_active('attributs')) {
				$attributs_form_part = affiche_attributs_form_part($product_object, 'table', $save_cart_id, $save_suffix_id, $form_id, vb($GLOBALS['site_parameters']['attributs_form_part_technical_code_array'], null), vb($GLOBALS['site_parameters']['attributs_form_part_excluded_technical_code_array'], null));
				$tpl->assign('affiche_attributs_form_part', $attributs_form_part);
				if(!empty($attributs_form_part)) {
					$update_product_price_needed = true;
				}
			}
			if (check_if_module_active('stock_advanced') && $product_object->on_stock == 1) {
				$stock_remain_all = 0;
				$real_stock_remain_all = 0;
				foreach ($product_stock_infos as $stock_infos) {
					if (empty($selected_color_id) || (!empty($selected_color_id) && ($selected_color_id == $stock_infos['couleur_id']))){
						$stock_remain_all += $stock_infos['stock_temp'];
						$real_stock_remain_all += $stock_infos['stock'];
					}
				}
				if (!empty($GLOBALS['STR_PRODUCT_SOON_AVAILABLE'])) {
					if (!empty($real_stock_remain_all) && empty($stock_remain_all)) {
						$tpl->assign('product_soon_available', $GLOBALS['STR_PRODUCT_SOON_AVAILABLE']);
						$disable_affiche_etat_stock = true;
					} elseif (empty($real_stock_remain_all) && empty($stock_remain_all)) {
						$tpl->assign('stock_remain_all', 0);
						$disable_affiche_etat_stock = true;
					}
				}
				if (empty($disable_affiche_etat_stock)) {
					$tpl->assign('affiche_etat_stock', affiche_etat_stock($stock_remain_all, false, true, false, $product_object));
				}
				if ($stock_remain_all > 0) {
					if ($product_object->affiche_stock == 1 && !empty($product_stock_infos)) {
						$tpl->assign('stock_remain_all', $stock_remain_all);
					}
				} elseif(empty($product_object->allow_add_product_with_no_stock_in_cart) && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
					$product_object->on_rupture = 1;
				}
			} elseif(!empty($product_object->scale_stock)){
				$scale_stock = '';
				if($product_object->scale_stock == 1){
					$scale_stock = $GLOBALS["STR_INFORMATION_ETAT_STOCK_DAYS"];
				} elseif ($product_object->scale_stock == 2){
					$scale_stock = $GLOBALS["STR_INFORMATION_ETAT_STOCK_WEEK"];
				} elseif($product_object->scale_stock == 3){
					$scale_stock = $GLOBALS["STR_INFORMATION_ETAT_STOCK_MONTH"];
				}
				$tpl->assign('affiche_etat_stock', '<p style="color: red;">'.$GLOBALS["STR_INFORMATION_ETAT_SCALE_STOCK"]. $product_object->delai_stock . '&nbsp;'. $scale_stock .'</p>');
			}
		}
		$tpl->assign('display_javascript_for_price_update', display_javascript_for_price_update($product_object, $save_suffix_id, $form_id, vn($GLOBALS['site_parameters']['taille_display_mode'],0), !empty($update_product_price_needed), null, $sizes_id_out_stock));
		// Si vous ne voulez pas gérer la notion de disponibilité en stock pour les produits à télécharger,
		// alors activez la ligne suivante et l'accolade fermante ensuite
		// if (empty($product_object->on_download)) {
		if (empty($product_object->on_rupture) && !empty($product_object->delai_stock) && empty($product_object->scale_stock) && empty($GLOBALS['site_parameters']['disable_display_delai_stock'])) {
			// On affiche le délai de livraion si le produit est disponible
			// Le délai de livraions lorsque que le produit est indisponible est affiché par affiche_etat_stock.
			$tpl->assign('delai_stock', get_formatted_duration((intval($product_object->delai_stock) * 24 * 3600), false, 'month'));
		}
		// }
		if ($product_object->on_estimate) {
			$tpl->assign('product_affiche_prix', display_on_estimate_information(true));
		} elseif ($product_object->on_gift) {
			$tpl->assign('product_affiche_prix', $product_object->on_gift_points . ' ' . $GLOBALS['STR_GIFT_POINTS']);
		} else {
			$display_old_price_inline = (vn($GLOBALS['site_parameters']['category_order_on_catalog']) == '1') ? false : true;
			$tpl->assign('product_affiche_prix', $product_object->affiche_prix(display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true, true, 'prix_' . $product_object->id . $save_suffix_id, true, true, 'full_width', (vn($GLOBALS['site_parameters']['force_display_old_price_inline'])?true:$display_old_price_inline), true, null));
		}
		if (!empty($product_object->on_rupture) && !$is_in_catalog && empty($GLOBALS['site_parameters']['product_formulaire_alerte_disable'])) {
			// si la rupture est forcée ou constatée d'après les stocks
			if (check_if_module_active('stock_advanced')) {
				$tpl->assign('formulaire_alerte', formulaire_alerte($product_object->id, $_POST));
			}
		} elseif (!empty($product_object->on_rupture) && $is_in_catalog) {
			$tpl->assign('etat_stock', affiche_etat_stock($stock_remain_all, false, true));
		}
		$tpl->assign('on_rupture', $product_object->on_rupture);
		if (empty($product_object->on_rupture) || (!empty($product_object->on_rupture) && !empty($GLOBALS['site_parameters']['product_formulaire_alerte_disable']))) {
			// On peut commander
			if (check_if_module_active('listecadeau') && isset($listcadeaux_owner)) {
				$q_owner = getNessQuantityFromGiftList($product_object->id, $selected_color_id, $saved_size_id, $listcadeaux_owner);
			} else {
				if (!empty($product_object->quantity_min_order) && $product_object->quantity_min_order > 1){
					$q_owner = $product_object->quantity_min_order;
					$tpl->assign('display_order_minimum', true);
				} else {
					// quantité par défaut administrable, sauf pour les produits les produit "liste d'achat" pour lesquelles la quantité sauvegardée doit être affichée.
					$q_owner = vn($GLOBALS['site_parameters']['product_default_quantity'], 1);
				}
			}
			if (empty($product_object->on_estimate)) {
				$tpl->assign('on_estimate', false);
				if (empty($product_object->on_download) && empty($product_object->on_gift)) {
					if (!empty($GLOBALS['site_parameters']['product_disable_quantity_field']) && in_array($product_object->technical_code, $GLOBALS['site_parameters']['product_disable_quantity_field'])) {
						$tpl->assign('qte_hidden', true);
					} else {
						$tpl->assign('qte_hidden', false);
					}
					$tpl->assign('qte_value', (!empty($saved_quantity) ? intval($saved_quantity) : vn($q_owner)));
				} else {
					$tpl->assign('qte_hidden', true);
					if (!empty($saved_quantity)) {
						$tpl->assign('qte_value', intval($saved_quantity));
					} else {
						// quantité par défaut administrable, sauf pour les produits du panier sauvegardé pour lesquelles la quantité sauvegardée doit être affichée.
						$tpl->assign('qte_value', vn($GLOBALS['site_parameters']['product_default_quantity'], 1));
					}
				}
				if (check_if_module_active('listecadeau')) {
					$tpl->assign('giftlist', array(
						'listcadeaux_owner' => vn($listcadeaux_owner),
						'id' => $product_object->id,
						'form' => get_add_giftlist_form($product_object->id, $form_basename, true)
					));
				}
				if (!empty($colors_infos_array)) {
					$color_array_result = 1;
				} else {
					$color_array_result = 0;
				}
				if (!empty($sizes_infos_array)) {
					$sizes_infos_array_result = 1;
				} else {
					$sizes_infos_array_result = 0;
				}
				$tpl->assign('color_array_result', $color_array_result);
				$tpl->assign('sizes_infos_array_result', $sizes_infos_array_result);
				$tpl->assign('anim_prod_var', $anim_prod_var);
			} else {
				$tpl->assign('on_estimate', true);
			}
		}
		$hook_result = call_module_hook('affiche_critere_stock_additional_infos', array('id' => $product_object->id, 'id_utilisateur' => $product_object->id_utilisateur, 'categorie_id' => $product_object->categorie_id, 'position' => $product_object->position), 'array');
		foreach($hook_result as $this_key => $this_value) {
			$tpl->assign($this_key, $this_value);
		}
		// On envoi au tpl l'information comme quoi l'affichage des catégories sera différente
		if(!empty($GLOBALS['site_parameters']['product_overlay_in_category_page'])) {
			$tpl->assign('product_overlay_in_category_page', true);
		}
		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
	}
}

if (!function_exists('get_subcategories_table')) {
	/**
	 * get_subcategories_table()
	 *
	 * @param mixed $parent_id
	 * @param integer $nb_colonnes
	 * @param boolean $return_mode
	 * @param boolean $display_image
	 * @return
	 */
	function get_subcategories_table($parent_id, $nb_colonnes, $return_mode = false, $display_image = true, $id_marque = null)
	{
		$output = '';
		if(empty($id_marque)) {
			$qid_c = query('SELECT id, nom_' . $_SESSION['session_langue'] . ', nom_court_' . $_SESSION['session_langue'] . ', description_' . $_SESSION['session_langue'] . ', parent_id, image_' . vb($GLOBALS['site_parameters']['main_content_lang'], $_SESSION['session_langue']) . ' AS image_main_content_lang, image_' . $_SESSION['session_langue'] . '
				FROM peel_categories
				WHERE parent_id="' . intval($parent_id) . '" AND id>"0"' . (empty($GLOBALS['site_parameters']['categories_disabled_show_to_admin_in_front']) || !a_priv("admin_products", false)?' AND etat = "1"':'') . ' AND ' . get_filter_site_cond('categories') . '
				ORDER BY position' . (!empty($GLOBALS['site_parameters']['category_primary_order_by'])? ", " . $GLOBALS['site_parameters']['category_primary_order_by']  : '') . '');
			$nb_cellules = num_rows($qid_c);
			if (!empty($nb_cellules)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('subcategories_table.tpl'); 
				$cats = array();
				$j = 0;
				while ($cat = fetch_assoc($qid_c)) {
					//get_default_content remplace le contenu par la langue par défaut si les conditions sont réunies
					if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
						$cat = get_default_content($cat, intval($cat['id']), 'categories');
					}
					$tmpCat = array();
					$tmpCat['href'] = get_product_category_url($cat['id'], $cat['nom_' . $_SESSION['session_langue']]);
					if (empty($GLOBALS['site_parameters']['display_subcategories_table_name_short']) || empty($cat['nom_court_' . $_SESSION['session_langue']])) {
						$tmpCat['name'] = $cat['nom_' . $_SESSION['session_langue']];
					} else {
						$tmpCat['name'] = $cat['nom_court_' . $_SESSION['session_langue']];			
					}
					if(empty($tmpCat['name']) && !empty($GLOBALS['site_parameters']['display_str_if_category_name_empty'])){
						$tmpCat['name'] = $GLOBALS['STR_NOT_ATTRIBUED'];
					}
					//Si l'image de la langue en court est vide on affiche celui de la langue par défaut
					if(vb($GLOBALS['site_parameters']['main_content_lang']) != $_SESSION['session_langue'] && empty($cat['image_' . $_SESSION['session_langue']])){
						$cat['image_' . $_SESSION['session_langue']] = $cat['image_main_content_lang'];	
					}
					$tmpCat['i'] = $j + 1;
					
					if (!empty($cat['image_' . $_SESSION['session_langue']]) && $display_image) {
						$tmpCat['src'] = thumbs($cat['image_' . $_SESSION['session_langue']], vn($GLOBALS['site_parameters']['small_width']), vn($GLOBALS['site_parameters']['small_height']), 'fit', null, null, true, true);
					}
					$tpl->assign('nb_col_md', $nb_colonnes);
					$tpl->assign('nb_col_sm', $nb_colonnes-1);
					$j++;
					$cats[] = $tmpCat;
				}
				
				$tpl->assign('cats', $cats);
				$output .= $tpl->fetch();
			}
		} else {
			// $id_marque est pas vide on souhaite que ce catalogue ne contiendra que les produits de la marque en
			// question. Les branches mortes de cet arbre de catégories, qui n'ont donc pas de produits de la marque, ne
			// doivent pas s'afficher. De même, il n'est pas nécessaire d'afficher tout l'arbre. Les catégories les plus
			// hautes n'ayant qu'une seule fille n'ont pas besoin d'être affiché, afin d'éviter d'avoir des arbres comme ça: 
			// AAAA
			  // BAAA
				// CAAA
				// CBBB
				// CCCC
			//Dans cet exemple, AAAA et BAAA sont inutiles et il faut comment par afficher en racine de ce mini catalogue les filles de BAAA.

			//On vérifie si on est au début de l'affichage d'une marque qui doit comporter uniquement les catégories mère général définis dans le tableau $GLOBALS['site_parameters']['index_id_category_brand']. Dans le cas contraire on est spécifiquement dans une catégorie
			if (!is_array($parent_id)) {
				$category_array = get_categories_marque($parent_id, $id_marque);
				// On liste les catégories racine
			} else {
				$category_array = get_racines_marque($parent_id, $id_marque);
			}
			if (!empty($category_array)) {
				//On effectue le même traitement d'affichage que par défaut
				$qid_c = query('SELECT id, nom_' . $_SESSION['session_langue'] . ', nom_court_' . $_SESSION['session_langue'] . ', description_' . $_SESSION['session_langue'] . ', parent_id, image_' . vb($GLOBALS['site_parameters']['main_content_lang'], $_SESSION['session_langue']) . ' AS image_main_content_lang, image_' . $_SESSION['session_langue'] . ', technical_code
					FROM peel_categories
					WHERE id IN (' . implode(',',$category_array) . ') ' . (empty($GLOBALS['site_parameters']['categories_disabled_show_to_admin_in_front']) || !a_priv("admin_products", false)?' AND etat = "1"':'') . ' AND ' . get_filter_site_cond('categories') . '
					ORDER BY position' . (!empty($GLOBALS['site_parameters']['category_primary_order_by'])? ", " . $GLOBALS['site_parameters']['category_primary_order_by']  : '') . '');

				$nb_cellules = num_rows($qid_c);
				if (!empty($nb_cellules)) {
					$tpl = $GLOBALS['tplEngine']->createTemplate('subcategories_table.tpl'); 
					$cats = array();
					$j = 0;
					while ($cat = fetch_assoc($qid_c)) {
						// get_default_content remplace le contenu par la langue par défaut si les conditions sont réunies
						if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
							$cat = get_default_content($cat, intval($cat['id']), 'categories');
						}
						$tmpCat = array();
						$tmpCat['href'] = get_current_url(false) . "?id_cat=" . $cat['id'] ."&id=".$id_marque;
						if (empty($GLOBALS['site_parameters']['display_subcategories_table_name_short']) || empty($cat['nom_court_' . $_SESSION['session_langue']])) {
							$tmpCat['name'] = $cat['nom_' . $_SESSION['session_langue']];
						} else {
							$tmpCat['name'] = $cat['nom_court_' . $_SESSION['session_langue']];			
						}
						if (empty($tmpCat['name']) && !empty($GLOBALS['site_parameters']['display_str_if_category_name_empty'])) {
							$tmpCat['name'] = $GLOBALS['STR_NOT_ATTRIBUED'];
						}
						if(!empty($GLOBALS['site_parameters']['title_by_id_category_brand'])) {
							foreach ($category_array as $id){
								if (in_array($id,$GLOBALS['site_parameters']['index_id_category_brand'])) {
									if(!empty($GLOBALS[$GLOBALS['site_parameters']['title_by_id_category_brand'][$id]])) {
										$tmpCat['name'] = $GLOBALS[$GLOBALS['site_parameters']['title_by_id_category_brand'][$cat['id']]];
									}
								}
							}
						}
						//Si l'image de la langue en court est vide on affiche celui de la langue par défaut
						if(vb($GLOBALS['site_parameters']['main_content_lang']) != $_SESSION['session_langue'] && empty($cat['image_' . $_SESSION['session_langue']])) {
							$cat['image_' . $_SESSION['session_langue']] = $cat['image_main_content_lang'];	
						}
						$tmpCat['i'] = $j + 1;
						
						if (!empty($cat['image_' . $_SESSION['session_langue']]) && $display_image) {
							$tmpCat['src'] = thumbs($cat['image_' . $_SESSION['session_langue']], vn($GLOBALS['site_parameters']['small_width']), vn($GLOBALS['site_parameters']['small_height']), 'fit', null, null, true, true);
						}
						$tpl->assign('nb_col_md', $nb_colonnes);
						$tpl->assign('nb_col_sm', $nb_colonnes-1);
						$j++;
						$cats[] = $tmpCat;
					}
					
					$tpl->assign('cats', $cats);
					$output .= $tpl->fetch();
				}
			}
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_categorie_accueil')) {
	/**
	 * Affiche la liste des catégories qui sont spéciales
	 *
	 * @param boolean $return_mode
	 * @param integer $nb_col_md
	 * @param integer $nb_col_sm
	 * @return
	 */
	function affiche_categorie_accueil($return_mode = false, $nb_col_md = 3, $nb_col_sm = 3)
	{
		$output = '';
		$qid = query('SELECT c.id, c.nom_' . $_SESSION['session_langue'] . ', nom_court_' . $_SESSION['session_langue'] .', c.image_' . $_SESSION['session_langue'] . ' AS image
			FROM peel_categories c
			WHERE c.etat = "1" AND c.on_special = "1" AND ' . get_filter_site_cond('categories', 'c') . '
			ORDER BY position' . (!empty($GLOBALS['site_parameters']['category_primary_order_by'])? ", " . $GLOBALS['site_parameters']['category_primary_order_by']  : '') . '');
		$nb_cellules = num_rows($qid);
		if (!empty($nb_cellules)) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('categorie_accueil.tpl');
			$tpl->assign('header', $GLOBALS['STR_CATALOG']);
			$cats = array();
			$i = 1;
			while ($cat = fetch_assoc($qid)) {
				//get_default_content remplace le contenu par la langue par défaut si les conditions sont réunies
				if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
					$cat = get_default_content($cat, intval($cat['id']), 'categories');
				}
				// on affiche une cellule
				$tmpCat['i'] = $i;
				if (empty($GLOBALS['site_parameters']['display_subcategories_table_name_short']) || empty($cat['nom_court_' . $_SESSION['session_langue']])) {
					$tmpCat['name'] = $cat['nom_' . $_SESSION['session_langue']];
				} else {
					$tmpCat['name'] = $cat['nom_court_' . $_SESSION['session_langue']];			
				}
				$tmpCat['href'] = get_product_category_url($cat['id'], $tmpCat['name']);
				if(empty($tmpCat['name']) && !empty($GLOBALS['site_parameters']['display_str_if_category_name_empty'])){
					$tmpCat['name'] = $GLOBALS['STR_NOT_ATTRIBUED'];
				}
				if (!empty($cat['image'])) {
					$tmpCat['src'] = thumbs($cat['image'], $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit', null, null, true, true);
				}
				$cats[] = $tmpCat;
				$i++;
				unset($tmpCat);
			}
			$tpl->assign('nb_col_md', $nb_col_md);
			$tpl->assign('nb_col_sm', $nb_col_sm);
			$tpl->assign('cats', $cats);
			$output .= $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('get_categories_marque')) {
	/**
	 * Récupère la liste des catégories d'une marque
	 *
	 * @param integer $parent_id
	 * @param integer $id_marque
	 * @param boolean $return_actual_category
	 * @return
	 */
	function get_categories_marque($parent_id, $id_marque, $return_actual_category = false, $check_no_sister = false)
	{
		$category_array = array();

		//On récupère l'arborescense des fils de la catégorie.
		$category_sons_array = get_category_tree_and_itself($parent_id, 'sons', 'categories', 99, true);

		// Récupère la liste des catégories que contiennent les produits de la marque
		$liste_categories_marque = get_liste_categories_marque($id_marque);
		// Récupère l'intersection entre les sous-catégorie de la catégorie actuelle et les catégories des produits de la marque
		$category_sons_array = array_intersect($category_sons_array, $liste_categories_marque);

		foreach ($category_sons_array as $value) {
			if ($value != vn($_GET['id_cat'])) {
				//On récupère les parents de la catégorie qui possède un produit de la marque
				$category_parents_marque_array[] = get_category_tree_and_itself($value, 'parents');
			}
		}

		$category_parents_array = get_category_tree_and_itself($parent_id, 'parents');
		$cat_requested_depth_level = count($category_parents_array);

		// On inverse la liste de l'arborescences
		if (!empty($category_parents_marque_array)) {
			foreach($category_parents_marque_array as $key => $values) {
				$category_parents_marque_array[$key] = array_reverse($values);
			}
		}

		// Identifiant de la catégorie actuelle
		$actual_category = $parent_id;
		$has_sister = false;

		// Le dev suivant permet d'ignorer les catégories sans soeur (et donc de sauter une ou plusieurs catégories).
		//
		// Exemple 1 :
		// - A
		//  - B  <= B n'a pas de soeur : donc lorsqu'on se trouve dans la catégorie A, on affiche directement les catégories C et D
		//   - C
		//   - D
		//
		// Exemple 2 :
		// - A
		//  - B <= B n'a pas de soeur : donc lorsqu'on se trouve dans la catégorie A, on affiche directement la liste de produits de la catégorie B
		//   - Liste de produits

		if(!empty($GLOBALS['site_parameters']['skip_catogories_without_sister'])) {
			// $level_to_check correspond au niveau de catégorie qu'on va vérifier
			$level_to_check = $cat_requested_depth_level;

			if (!empty($category_parents_marque_array)) {
				// On merge les catégories par niveau d'arborescence
				$category_parents_marque_array_merge = array_merge_by_keys($category_parents_marque_array);

				// On boucle chaque niveau de branche
				foreach($category_parents_marque_array_merge as $level_key => $categories) {

					// Si le foreach n'est pas encore sur le niveau à checker => on continue la boucle
					if($level_key < $level_to_check)
						continue;

					$categories_unique = array_unique($categories);

					// S'il y a une soeur (au moins 2 catégories différentes) => on sort de la boucle
					if(count($categories_unique) > 1)
					{
						$category_array = $categories;
						$has_sister = true;
						break;
					}
					// Si toutes les catégories sont identiques, on récupère les catégories du niveau inférieur => on continue la boucle
					else
					{
						// On sauvegarde la catégorie dans laquelle on se trouve => cela permettra, si besoin, d'afficher les produits de la catégorie en question
						$actual_category = $categories_unique;
						continue;
					}

					// Si on se retrouve en fin d'arborescence, on retourne les catégories du dernier niveau
					$category_array = $categories;
				}
			}
		}
		else
		{
			$has_sister = true;
			
			//A partir de l'arbre des parents des catégories qui ont un produit de la marque, on souhaite afficher qu'un niveau
			if (!empty($category_parents_marque_array)) {
				foreach($category_parents_marque_array as $values) {
					$category_array[] = $values[$cat_requested_depth_level];
				}
			}
		}

		if($return_actual_category)
		{
			// Si $actual_category retourne un array, on récupère sa première valeur
			if(is_array($actual_category))
				return array_shift($actual_category);
			else
				return $actual_category;
		}
		elseif($check_no_sister)
		{
			return $has_sister;
		}
		else
			return $category_array;
	}
}

/**
 * Permet d'effectuer un merge sur les arrays d'arrays (en se basant sur la clé des arrays)
 *
 * @param array $arrays
 * @return
 */
function array_merge_by_keys($arrays)
{
	// Le tableau retourné
    $output = array();
    // Boucle l'array envoyé
    foreach($arrays as $array) {
    	// Boucle sur chaque array à l'intérieur
        foreach($array as $key => $value) {
        	// Si la clé existe déjà, on merge la valeur actuelle avec l'array existant, sinon, on l'ajoute simplement à la variable
            $output[$key] = isset($output[$key]) ? array_merge($output[$key], array($value)) : array($value);
        }
    }
    return $output;
}

if (!function_exists('get_racines_marque')) {
	/**
	 * Récupère la liste des catégories racines d'une marques
	 *
	 * @param array $parent_id
	 * @param integer $id_marque
	 * @return
	 */
	function get_racines_marque($parent_id, $id_marque)
	{
		//On vérifie si les catégories de départ ont des produits lié à des marques
		foreach ($parent_id as $id_mother) {
			$category_sons_array = get_category_tree_and_itself($id_mother, 'sons');

			// Récupère la liste des catégories que contiennent les produits de la marque
			$liste_categories_marque = get_liste_categories_marque($id_marque);
			// Récupère l'intersection entre les sous-catégorie de la catégorie actuelle et les catégories des produits de la marque
			$category_sons_array = array_intersect($category_sons_array, $liste_categories_marque);

			foreach ($category_sons_array as $value) {
				if ($value != vn($_GET['id_cat'])) {
					$category_array[] = $value;
					//On récupère les fils de la catégorie qui possède un produit de la marque
					$category_sons_marque_array[] = get_category_tree_and_itself($value, 'sons');
				}
			}
			if (empty($category_sons_marque_array)) {
				$key = array_search($id_mother, $parent_id);
				// On supprime du tableau les catégories qui sont enfants 
				unset($parent_id[$key]);
			}
		}

		// S'il n'y a qu'une catégorie racine, on affiche ses catégories enfants (fait sur 3 niveaux)
		if(count($parent_id) == 1)
		{
			$category_array = get_categories_marque(array_shift($parent_id), $id_marque);
			if(count($category_array) == 1)
			{
				$category_array = get_categories_marque(array_shift($category_array), $id_marque);
				if(count($category_array) == 1)
				{
					$category_array = get_categories_marque(array_shift($category_array), $id_marque);
				}
			}
		}
		else
			$category_array = $parent_id;

		return $category_array;
	}
}

if (!function_exists('get_liste_categories_marque')) {
	/**
	 * Liste des catégories d'une marques qui contiennent des produits
	 *
	 * @param array $parent_id
	 * @param integer $id_marque
	 * @return
	 */
	function get_liste_categories_marque($id_marque)
	{
		$query = query('SELECT pc.categorie_id as categ
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
			WHERE p.id_marque = ' . intval($id_marque) . '
			GROUP BY categ');
		$nb_cellules = num_rows($query);

		if (!empty($nb_cellules)) {
			$cats = array();
			while ($result = fetch_assoc($query)) {
				$cats[] = $result['categ'];
			}
			return $cats;
		}
		else
			return false;
	}
}

if (!function_exists('affiche_arbre_categorie')) {
	/**
	 * Renvoie l'arbre des catégories des produits, en commençant de top jusqu'à la catégorie specifiée par $catid
	 * Si $id_produit est renseigné, le nom du produit s'affiche dans le fil d'ariane sur la page produit.
	 * 
	 * @param integer $catid
	 * @param mixed $additional_text
	 * @param mixed $id_produit
	 * @param mixed $categories_treated Used only for recursive calls
	 * @param boolean $hidden Used only for generating hidden breadcrumb with microdata for google
	 * @param integer $level Niveau pour les microdonnées BreadcrumbList (1 étant pour la page d'accueil)
	 * @return
	 */
	function affiche_arbre_categorie($catid = 0, $additional_text = null, $id_produit = null, $categories_treated = array(), $hidden = false, $use_short_name = false, $level = null, $id_marque = 0)
	{
		$with_href = true;
		// Si c'est une marque et qu'on se trouve en fin de fil d'ariane, on vérifie s'il ne faut pas se baser sur une catégorie de plus bas niveau
		if($id_marque && empty($categories_treated))
		{
			$new_catid = get_categories_marque($catid, $id_marque, true);
			// S'il y a bien un saut de catégorie (la catégorie proposée est différente de celle du fil d'ariane)
			if($new_catid != $catid)
			{
				$catid = $new_catid;
				$with_href = false;
			}
		}

		if (!empty($id_produit)) {
			$product_object = new Product($id_produit);
			$product_name = $product_object->name;
			unset($product_object);
		}
		$parent = 0;
		$nom = '';
		if($level == null) {
			$category_parents_array = get_category_tree_and_itself($catid,'parents');
			//On récupère la taille du tableau et on ajoute 1 pour pas avoir les catégories mères avec le niveau 1 (niveau qui est réservé à la home)
			$level_total = sizeof($category_parents_array)+1;

			foreach($category_parents_array as $category_id){
				// On décrémente pour que les niveaux affiche en croissant car les id de catégorie vont du plus vers le moins profond du fil d'arianne.
				$level_total--;
				$level[$category_id] = $level_total;
			}
		}
		if(empty($categories_treated[$catid])) {
			if(empty($id_marque)){
			// On évite les cas de boucles entre catégories qui ont pour Nème parent une catégorie dont elles sont elles-même parents => sinon boucle sans fin
			// Fonction utilisée également en back office pour le module comparateur => utilisation de use_admin_right inutile, on veut pouvoir récupérer les catégories ste_id = 0 dans l'export pour les comparateurs également.
				$sql = 'SELECT parent_id, nom_' . $_SESSION['session_langue'] . ' AS nom, nom_court_' . $_SESSION['session_langue'] . ' AS nom_court
					FROM peel_categories
					WHERE id = "' . intval($catid) . '"' . (empty($GLOBALS['site_parameters']['categories_disabled_show_to_admin_in_front']) || !a_priv("admin_products", false)?' AND etat = "1"':'') . ' AND ' . get_filter_site_cond('categories') . '';
				$qid = query($sql);
			} else {
				$qid = query('SELECT COUNT(*) as nombre_produits, c.*, c.nom_' . $_SESSION['session_langue'] . ' AS nom
					FROM peel_produits p
					INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
					INNER JOIN peel_categories c ON pc.categorie_id = c.id
					WHERE pc.categorie_id = ' . intval($catid) . ' AND p.id_marque = ' . intval($id_marque) . '');
			}
			if ($result = fetch_assoc($qid)) {
				//get_default_content remplace le contenu par la langue par défaut si les conditions sont réunies
				if (!empty($GLOBALS['site_parameters']['get_default_content_enable'])) {
					$result = get_default_content($result, intval($catid), 'categories');
				}
				if ($use_short_name) {
					if (empty($GLOBALS['site_parameters']['display_subcategories_table_name_short']) || empty($result['nom_court'])) {
						$used_name = $result['nom'];
					} else {
						$used_name = $result['nom_court'];
					}
				}
				$tpl = $GLOBALS['tplEngine']->createTemplate('arbre_categorie.tpl');
				if($with_href)
				{
					if (defined('IN_SEARCH_BRAND')) {
						$tpl->assign('href', get_current_url(false) . "?id_cat=" . $catid ."&id=".$id_marque);
					} else {
						$tpl->assign('href', get_product_category_url($catid, $result['nom']));
					}
				}
				$tpl->assign('name', (empty($used_name)?$result['nom']:$used_name));

				// Application du titre catégorie spécifique pour les catégories racine des marques
				if($id_marque && !empty($GLOBALS['site_parameters']['title_by_id_category_brand'])){
					if (in_array($catid, $GLOBALS['site_parameters']['index_id_category_brand'])) {
						if(!empty($GLOBALS[$GLOBALS['site_parameters']['title_by_id_category_brand'][$catid]])){
							$tpl->assign('name', $GLOBALS[$GLOBALS['site_parameters']['title_by_id_category_brand'][$catid]]);
						}
					}
				}

				$tpl->assign('hidden', $hidden);
				$tpl->assign('level', $level[$catid]);
				$nom = $tpl->fetch();
				$parent = $result['parent_id'];
			}
			$categories_treated[$catid] = true;
		}

		if (empty($id_marque)) {
			if ($parent > 0) {
				return affiche_arbre_categorie($parent, ' &gt; ' . $nom . ' ' . $additional_text, null, $categories_treated, $hidden, $use_short_name, $level, $id_marque) . (!empty($product_name) && !$hidden ?  ' &gt; ' . $product_name : '');
			} else {
				return $nom . ' ' . $additional_text . (!empty($product_name) && !$hidden ?  ' &gt; ' . $product_name : '');
			}
		} else {
			if ($parent > 0) {
				return affiche_arbre_categorie($parent, ' &gt; ' . $nom . $additional_text, null, $categories_treated, $hidden, $use_short_name, $level, $id_marque) . (!empty($product_name) && !$hidden ?  ' &gt; ' . $product_name : '');
			} else {
				return $nom . ' ' . $additional_text . (!empty($product_name) && !$hidden ?  ' &gt; ' . $product_name : '');
			}
		}
	}
}

if (!function_exists('affiche_select_categorie')) {
	/**
	 * affiche_select_categorie()
	 *
	 * @return
	 */
	function affiche_select_categorie()
	{
		$frm['categories_select'] = array();
		$tpl = $GLOBALS['tplEngine']->createTemplate('select_categorie.tpl');
		$tpl->assign('search_label', $GLOBALS['STR_SEARCH_CATEGORY']);
		$tpl->assign('cats', get_categories_output(null, 'categories', vb($frm['categories_select']), 'option', '&nbsp;&nbsp;', null));
		echo $tpl->fetch();
	}
}

if (!function_exists('get_product_in_container_html')) {
	/**
	 * get_product_in_container_html()
	 *
	 * @param object $product_object
	 * @param boolean $only_show_products_with_picture
	 * @param float $display_minimal_price
	 * @return
	 */
	function get_product_in_container_html(&$product_object, $only_show_products_with_picture = true, $display_minimal_price = null, $disable_display_price = false)
	{
		static $tpl;
		$output = '';
		if($display_minimal_price === null) {
			$display_minimal_price = vb($GLOBALS['site_parameters']['product_display_minimal_price'], false);
		}
		if (!empty($product_object->id) && !empty($product_object->etat)) {
			$urlprod = $product_object->get_product_url();
			$display_picture = $product_object->get_product_main_picture();
			if (!$only_show_products_with_picture || !empty($display_picture)) {
				if(empty($tpl)) {
					$tpl = $GLOBALS['tplEngine']->createTemplate('product_in_container_html.tpl');
				}
				$tpl->assign('thumbnail_promotion', false);
				$tpl->assign('product_description_product_in_container_disabled', !empty($GLOBALS['site_parameters']['product_description_product_in_container_disabled']));
				$tpl->assign('product_estimate_product_in_container_disabled', !empty($GLOBALS['site_parameters']['product_estimate_product_in_container_disabled']));
				if ($product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true) > 0 && !empty($GLOBALS['site_parameters']['thumbnail_promotion_in_container_html'])) {
					$tpl->assign('thumbnail_promotion', true);
					$tpl->assign('promotion', $product_object->get_all_promotions_percentage(false, get_current_user_promotion_percentage(), true));
				}
				$tpl->assign('on_flash', $product_object->on_flash);
				$tpl->assign('flash_date', get_formatted_date($product_object->flash_start, 'long'));
				if (!empty($product_object->id_utilisateur)) {
					$user_info = get_user_information($product_object->id_utilisateur);
					$tpl->assign('user', array('pseudo' => $user_info['pseudo']));
				} else {
					$tpl->assign('user', null);
				}
				$tpl->assign('href', $urlprod);
				$tpl->assign('name', $product_object->name);
				$tpl->assign('descriptif', $product_object->descriptif);
				if (!empty($display_picture)) {
					$this_picture = thumbs($display_picture, $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], "fit");
					if($only_show_products_with_picture && empty($this_picture)) {
						return false;
					}
					$tpl->assign('src', $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_picture);
				} else {
					$tpl->assign('src', null);
				}
				$tpl->assign('more_detail_label', $GLOBALS['STR_MORE_DETAILS']);
				
				$tpl->assign('disable_display_price', $disable_display_price);
				
				if (empty($GLOBALS['site_parameters']['product_price_display_disable'])) {
					if ($product_object->on_estimate) {
						$tpl->assign('on_estimate', display_on_estimate_information(true));
					} elseif ($product_object->on_gift) {
						$tpl->assign('on_estimate', $product_object->on_gift_points . ' ' . $GLOBALS['STR_GIFT_POINTS']);
					} elseif($product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller()) != 0) {
						if (!empty($GLOBALS['site_parameters']['thumbnail_promotion_in_container_html'])) {
							$tpl->assign('on_estimate', $product_object->affiche_prix(display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true, false, null, false, true, 'full_width', true, false, true));
						} else {
							$tpl->assign('on_estimate', $product_object->affiche_prix(display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true, false, null, false, false, 'full_width', true, false, null, $display_minimal_price));
						}
					} elseif(!empty($GLOBALS['site_parameters']['free_displayed_if_price_empty_disable'])) {
						$tpl->assign('on_estimate','');
					} else {
						$tpl->assign('on_estimate','<span class="title_price_free">'.$GLOBALS['STR_FREE'].'</span>');
					}
				}

				$output .= $tpl->fetch();
			}
		}
		return $output;
	}
}

if (!function_exists('get_product_new_list')) {
	/**
	 * NO_TPL get_product_new_list function is not a view formatting function
	 * get_product_new_list()
	 * retourne une liste de produits suivant une requete précise (produit en vedette)
	 *
	 * @param string $location
	 * @param boolean $return_mode
	 * @return
	 */
	function get_product_new_list($location = "left", $return_mode = true)
	{
		$output = 'get_product_new_list';
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('display_javascript_for_price_update')) {
	/**
	 * Affiche le code javascript necessaire pour changer le prix en fonction de l'attribut
	 *
	 * @param string $product_object
	 * @param string $save_suffix_id
	 * @param string $form_id
	 * @param integer $taille_display_mode
	 * @param boolean $update_product_price_needed
	 * @param boolean $product_object2
	 * @return
	 */
	function display_javascript_for_price_update(&$product_object, $save_suffix_id, $form_id, $taille_display_mode = 0, $update_product_price_needed = true, $product_object2 = null, $sizes_id_out_stock = null)
	{
		$output = '';
		$get_infos_js = '';
		$get_javascript_price = array();
		$attributs_infos_array = $product_object->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), false, false, false);
		if(!empty($product_object2)) {
			foreach($product_object2->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller()) as $this_key => $this_value) {
				$attributs_infos_array[$this_key] = $this_value;
			}
		}
		$sizes_infos_array = $product_object->get_possible_sizes('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller());
		$colors_infos_array = $product_object->get_possible_colors('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller());
		if(!empty($product_object2)) {
			foreach($product_object2->get_possible_sizes('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller()) as $this_key => $this_value) {
				$sizes_infos_array[$this_key] = $this_value;
			}
		}

		if ((!empty($colors_infos_array) && count($colors_infos_array)>=1) || (!empty($sizes_infos_array) && count($sizes_infos_array)>=1) || (check_if_module_active('attributs') && !empty($attributs_infos_array))) {
			$output .= '
<script><!--//--><![CDATA[//><!--
';
			if (function_exists('build_extra_var_js')) {
				$get_infos_js .= '
	' . build_extra_var_js();
			}
			if (check_if_module_active('attributs') && !empty($attributs_infos_array)) {
				$get_infos_js .= '
	' . build_attr_var_js('attribut_list', $attributs_infos_array, $form_id, $product_object);
			}
			if (!empty($sizes_infos_array)) {
				// On gère prix de taille uniquement, pas d'attributs
				if ($taille_display_mode == 0) {
					// Affichage sous forme de select de la taille
					$get_infos_js .= '
	select_size = document.getElementById("taille' . $save_suffix_id . '");
	size_id = select_size.options[select_size.selectedIndex].value;
';
				} elseif($taille_display_mode == 1) {
					// Affichage sous forme de boutons radio de la taille
					$get_infos_js .= '
	select_size = document.getElementById("taille' . $save_suffix_id . '");
	for (var i=0; select_size && i<select_size.length;i++) {
		if (radio[i].checked) {
			size_id = radio[i].value;
			break;
		}
	}
';
				} else {
					// Affichage de toutes les tailles et la quantité sous forme de tableau 
					// On utilise .map jQuery avec pour récupérer les variables "qte_array" et "size_id_array"
					$get_infos_js .= '
	qte_array=jQuery(".qte_array").map(function(){return jQuery(this).val();}).get();
	size_id_array=jQuery(".size_id_array").map(function(){return jQuery(this).attr("value");}).get();';
				}
			}
			if(!empty($colors_infos_array) && count($colors_infos_array)>=1){
				$get_infos_js .= '
	select_color = document.getElementById("couleur' . $save_suffix_id . '");
	color_id = select_color.options[select_color.selectedIndex].value;';
			}
			$output .= '
function update_product_price' . ($save_suffix_id) . '(){
	var attribut_list="";
	var size_id="";
	var qte_array ="";	
	var size_id_array ="";
	var quantite="";
	var color_id="";
	if(typeof sel != "undefined"){
		var value = sel.value.substring(2,sel.value.length);
		var first_elem = false;
		jQuery("#image_attribut").html("");
	}else{
		var first_elem = true;
	}
	' . $get_infos_js . '
	jQuery.post("'.$GLOBALS['wwwroot'].'/get_product_price.php", {product_id: '.$product_object->id.', product2_id: '.(!empty($product_object2)?$product_object2->id:'""').', attribut_list: attribut_list, quantite: quantite, qte_array:qte_array,size_id_array:size_id_array,size_id:size_id, color_id: color_id, hash: \''.sha256('HFhza8462naf'.$product_object->id).'\'}, function(data){
		var divtoshow = "#prix_' . vn($product_object->id) . $save_suffix_id . '";
		if(data.length >0) {
			jQuery(divtoshow).show();
			jQuery(divtoshow).html(data);
		}
	});';

	if (check_if_module_active('product_references_by_options')) {
		$output .= '
	jQuery.post("'.$GLOBALS['wwwroot'].'/modules/product_references_by_options/update_reference.php", {product_id: '.$product_object->id.', product2_id: '.(!empty($product_object2)?$product_object2->id:'""').', size_id: size_id, attribut_list: attribut_list, hash: \''.sha256('HFhza8462naf'.$product_object->id).'\'}, function(data){
		var divtoshow = "#reference_' . vn($product_object->id) . '";
		if(data.length >0) {
			jQuery(divtoshow).show();
			jQuery(divtoshow).html(data);
		}
	});';
	}
	$output .= '
}';
			if (!empty($sizes_id_out_stock)) {
				$output .= '
function bootbox_sizes_options() {
	var taille_select = $(\'select#taille\');
	var selectedValue = $(\'option:selected\', taille_select).val();
	var taille = [' . implode(",",$sizes_id_out_stock) . '];
	if (taille.indexOf(selectedValue) != -1) {
		bootbox.alert({message: \'' . $GLOBALS['STR_MESSAGE_BOOTBOX_INVOICE_CRITERE_STOCK'] . '\',size: \'small\'});
	}
}';
			}
			if($update_product_price_needed){
				$GLOBALS['js_ready_content_array'][] = '
	update_product_price' . ($save_suffix_id) . '();
';
			}
			$output .= '
//--><!]]></script>';
		}
		return $output;
	}
}



if (!function_exists('get_recommanded_product_on_cart_page')) {
	/*
	 * Affiche les produits choisis par l'administrateur dans une popup qui apparait lors du clique sur le bouton "finaliser votre commande" sur la page panier. Les produits sont affichés les uns en dessous des autres.
	 *
	 * @return
	 *
	*/
	function get_recommanded_product_on_cart_page()
	{
		$items = array();
		$sql = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
			WHERE p.recommanded_product_on_cart_page = '1' AND p.id NOT IN ('" . implode("','", nohtml_real_escape_string($_SESSION['session_caddie']->articles)) . "') AND p.nom_" . (!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']) . " != '' AND c.nom_" . $_SESSION['session_langue'] . " != '' AND p.etat='1'  ".(empty($GLOBALS['site_parameters']['allow_command_product_ongift'])?" AND p.on_gift != '1'":'')." AND " . get_filter_site_cond('produits', 'p') . "
			GROUP BY p.id
			ORDER BY RAND() ASC
			LIMIT 4";
		$query = query($sql);
		$i = 0;
		$product_html = '';
		$output = '';
		while ($prod = fetch_assoc($query)) {
			$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
			$product_html .= get_product_in_container_html($product_object, $GLOBALS['site_parameters']['only_show_products_with_picture_in_containers']);
			unset($product_object);
		}
		if (!empty($product_html)) {
			// Création du javascript qui affiche les produits.
			$output .= '
		<script>
			var product_liste_html = "'.filtre_javascript($product_html, true, false, true, true, false).'";
			product_liste_html += "<br /><div style=\"width:100%; text-align:center;\"><a href=\"#\" onclick=\"$(\'#caddieFormArticle\').submit();return false;\"  class=\"tooltip_link btn btn-lg btn-primary btn_p1\">'.$GLOBALS['STR_ORDER'].'</a></div>"
			function display_recommanded_product_on_cart_page_popup(){
				bootbox.dialog({
					message: product_liste_html,
					title: "' . filtre_javascript($GLOBALS['STR_HAVE_YOU_THINK_ABOUT'], true, false, true, true, false) . '",
					closeButton: true
					});
			}
		</script>
		<a href="#" onclick="display_recommanded_product_on_cart_page_popup(); return false" class="tooltip_link btn btn-lg btn-primary btn_p1">'.$GLOBALS['STR_ORDER'].' <span class="glyphicon glyphicon-chevron-right"></span></a>';
		}
		return $output;
	}
}

if (!function_exists('get_next_product_flash')) {
	/*
	 * Affiche la prochaine vente flash dans un container HTML. Fonction appelée si configuration du module avec le technical code "next_product_flash" dans peel_modules
	 *
	 * @return
	 *
	*/
	function get_next_product_flash () {
		$output = "";
		$sql = "SELECT id
			FROM peel_produits
			WHERE flash_start > '" . date("Y-m-d", time()) . "' AND " . get_filter_site_cond('produits') . "
			ORDER BY flash_start
			LIMIT 1";
		$query = query($sql);
		$result = fetch_assoc($query);
		$product_object = new Product($result['id']);
		$output = get_product_in_container_html($product_object, $GLOBALS['site_parameters']['only_show_products_with_picture_in_containers']);

		return $output;
	}
}

if (!function_exists('get_suggestion_description_details')) {
	/**
	 * Récupère une suggestion de détail de description dans la table suggestions_details_descriptions
	 * 
	 * @return
	 */
	function get_suggestion_description_details($id_categorie, $id_marque, $utilisateur, $statut = false)
	{
		if (empty($GLOBALS['site_parameters']['suggestions_details_descriptions'])) {
			return false;
		}

		if(!isset($utilisateur['id_utilisateur'])) {
			return false;
		}
		if($id_marque > 0) {
			$type = 'marque';
		} elseif($id_categorie > 0) {
			$type = 'category';
		} else {
			return false;
		}
		$query = query('SELECT *
			FROM suggestions_details_descriptions
			WHERE id_utilisateur_insere = '.intval($utilisateur['id_utilisateur']).'
			AND '.($type == 'category' ? 'id_categorie = "'.nohtml_real_escape_string($id_categorie).'"' : 'id_marque = "'.nohtml_real_escape_string($id_marque).'"').
			($statut ? 'AND statut = "'.nohtml_real_escape_string($statut).'"' : '').'
			ORDER BY date_insere DESC'
		);
		$result = fetch_assoc($query);

		if($result) {
			return $result;
		}
		return false;
	}
}

if (!function_exists('set_suggestion_description_details')) {
	/**
	 * Enregistre une suggestion de détail de description dans la table suggestions_details_descriptions
	 * 
	 * @return
	 */
	function set_suggestion_description_details($description, $id_categorie, $id_marque, $utilisateur)
	{
		if(!isset($utilisateur['id_utilisateur']))
			return false;
		
		$result = get_suggestion_description_details($id_categorie, $id_marque, $utilisateur, 'Waiting');

		if($result && $result['id'] > 0) {
			query('UPDATE suggestions_details_descriptions SET 
				description = "'.real_escape_string($description).'",
				date_maj = "'.date("Y-m-d", time()).'",
				utilisateur_maj = "'.nohtml_real_escape_string($utilisateur['prenom'].' '.$utilisateur['nom_famille']).'"
				WHERE id = '.intval($result['id'])
			);
		} else {
			query('INSERT INTO suggestions_details_descriptions 
				(description, date_insere, date_maj, utilisateur_insere, utilisateur_maj, id_utilisateur_insere, id_categorie, id_marque, statut)
				VALUES (
					"'.real_escape_string($description).'",
					"'.date("Y-m-d", time()).'",
					"'.date("Y-m-d", time()).'",
					"'.nohtml_real_escape_string($utilisateur['prenom'].' '.$utilisateur['nom_famille']).'",
					"'.nohtml_real_escape_string($utilisateur['prenom'].' '.$utilisateur['nom_famille']).'",
					"'.nohtml_real_escape_string($utilisateur['id_utilisateur']).'",
					'.intval($id_categorie).',
					'.intval($id_marque).',
					"Waiting"
				)'
			);
		}
		return true;
	}
}

if (!function_exists('set_suggestion_comment_details')) {
	/**
	 * Enregistre une suggestion de détail de description dans la table suggestions_details_comment
	 * 
	 * @return
	 */
	function set_suggestion_comment_details($comment, $id_categorie, $id_marque, $utilisateur)
	{
		if(!isset($utilisateur['id_utilisateur']))
			return false;
		
		query('INSERT INTO suggestions_details_comment 
				(description, date_insere, date_maj, utilisateur_insere, utilisateur_maj, id_utilisateur_insere, id_categorie, id_marque, statut)
				VALUES (
					"'.real_escape_string($comment).'",
					"'.date("Y-m-d", time()).'",
					"'.date("Y-m-d", time()).'",
					"'.$utilisateur['prenom'].' '.$utilisateur['nom_famille'].'",
					"'.$utilisateur['prenom'].' '.$utilisateur['nom_famille'].'",
					"'.$utilisateur['id_utilisateur'].'",
					'.intval($id_categorie).',
					'.intval($id_marque).',
					1
				)'
			);
		return true;
	}
}
if (!function_exists('get_suggestion_commentaire_details')) {
	/**
	 * Récupère une suggestion de détail de commentaire dans la table suggestions_details_descriptions
	 * 
	 * @return
	 */
	function get_suggestion_commentaire_details($id_categorie, $id_marque, $utilisateur, $statut = false)
	{
		if (empty($GLOBALS['site_parameters']['suggestions_details_comment'])) {
			return false;
		}
		if(!isset($utilisateur['id_utilisateur'])) {
			return false;
		}
		if($id_marque > 0) {
			$type = 'marque';
		} elseif($id_categorie > 0) {
			$type = 'category';
		} else {
			return false;
		}
		if (is_array($id_categorie)) {
			$where_category = "id_categorie IN (" . nohtml_real_escape_string(implode(',', $id_categorie)) . ")";
		} else {
			$where_category = 'id_categorie = "' . intval($id_categorie) . '"';
		}
		$query = query('SELECT *
			FROM suggestions_details_comment
			WHERE id_utilisateur_insere = '.intval($utilisateur['id_utilisateur']).'
			AND '.($type == 'category' ? $where_category : 'id_marque = "' . intval($id_marque) . '"').
			($statut ? 'AND statut = "'.$statut.'"' : '').'
			ORDER BY date_insere DESC'
		);
		$result = fetch_assoc($query);

		if($result) {
			return $result;
		}
		return false;
	}
}

if (!function_exists('set_suggestion_commentaire_details')) {
	/**
	 * Enregistre une suggestion de détail de commentaire dans la table suggestions_details_comment
	 * 
	 * @return
	 */
	function set_suggestion_commentaire_details($description, $id_categorie, $id_marque, $utilisateur)
	{
		if(!isset($utilisateur['id_utilisateur']))
			return false;
		
		$result = get_suggestion_commentaire_details($id_categorie, $id_marque, $utilisateur, 'Waiting');

		if($result && $result['id'] > 0) {
			query('UPDATE suggestions_details_comment SET 
				description = "'.real_escape_string($description).'",
				date_maj = "'.date("Y-m-d", time()).'",
				utilisateur_maj = "'.$utilisateur['prenom'].' '.$utilisateur['nom_famille'].'"
				WHERE id = '.$result['id']
			);
		} else {
            //$actual_link = ;
			query('INSERT INTO suggestions_details_comment 
				(description, date_insere, date_maj, utilisateur_insere, utilisateur_maj, id_utilisateur_insere, id_categorie, id_marque,lien)
				VALUES (
					"'.real_escape_string($description).'",
					"'.date("Y-m-d", time()).'",
					"'.date("Y-m-d", time()).'",
					"'.$utilisateur['prenom'].' '.$utilisateur['nom_famille'].'",
					"'.$utilisateur['prenom'].' '.$utilisateur['nom_famille'].'",
					"'.$utilisateur['id_utilisateur'].'",
					'.intval($id_categorie).',
					'.intval($id_marque).',					
                    "'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]".'"
				)'
			);
		}
		return true;
	}
}



