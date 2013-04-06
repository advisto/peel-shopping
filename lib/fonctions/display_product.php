<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an  	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	|
// +----------------------------------------------------------------------+
// $Id: display_product.php 36263 2013-04-06 11:50:46Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

if (!function_exists('get_produit_details_html')) {
	/**
	 * get_produit_details_html()
	 *
	 * @param integer $product_id
	 * @param integer $color_id
	 * @param integer $secondary_images_width
	 * @param integer $secondary_images_height
	 * @return
	 */
	function get_produit_details_html($product_id, $color_id = null, $secondary_images_width = 50, $secondary_images_height = 60)
	{
		$output = '';
		$product_object = new Product($product_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		if (empty($product_object->id) || (empty($GLOBALS['site_parameters']['allow_command_product_ongift']) && !empty($product_object->on_gift))) {
			$output .= $GLOBALS['STR_NO_FIND_PRODUCT'];
		} else {
			$tpl = $GLOBALS['tplEngine']->createTemplate('produit_details_html.tpl');
			$tpl->assign('wwwroot', $GLOBALS['wwwroot']);
			$tpl->assign('medium_width', $GLOBALS['site_parameters']['medium_width']);
			$tpl->assign('medium_height', $GLOBALS['site_parameters']['medium_height']);
			$tpl->assign('photo_not_available_alt', $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT']);
			$tpl->assign('no_photo_src', $GLOBALS['repertoire_upload'] . '/' . $GLOBALS['site_parameters']['default_picture']);
			// On comptatilise le nombre de fois où le produit est vu
			query("UPDATE peel_produits
				SET nb_view = (nb_view+1)
				WHERE id = '" . intval($product_object->id) . "'");
			$product_images = $product_object->get_product_pictures(true, $color_id);
			$display_first_image_on_mini_pictures_list = true;
			$javascript = '';
			if (!empty($product_images[0])) {
				if (pathinfo($product_images[0], PATHINFO_EXTENSION) == 'pdf') {
					$this_thumb = thumbs('logoPDF_small.png', $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit', $GLOBALS['dirroot'] .'/images/');
				} else {
					$this_thumb = thumbs($product_images[0], $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit');
				}
				$a_other_pictures_attributes = ' href="javascript:switch_product_images(\'[SMALL_IMAGE]\',\'[ZOOM_IMAGE]\',\'[VIGNETTE_CLASS]\');"';
				$imgInfo = @getimagesize($GLOBALS['uploaddir'] . '/thumbs/' . $this_thumb);
				if (!empty($imgInfo)) {
					$srcWidth = $imgInfo[0];
					$srcHeight = $imgInfo[1];
				}
				if ($GLOBALS['site_parameters']['zoom'] == 'cloud-zoom') {
					$a_zoom_attributes = ' class="cloud-zoom" rel="adjustX: 10, adjustY:-4"';
					$a_other_pictures_attributes = ' href="[ZOOM_IMAGE]" class="cloud-zoom-gallery" rel="useZoom:\'zoom1\', smallImage: \'[SMALL_IMAGE]\'"';
					$javascript .= '
<script><!--//--><![CDATA[//><!--
a_other_pictures_attributes=\'' . str_replace("'", "\'", $a_other_pictures_attributes) . '\';
//--><!]]></script>';
				} elseif ($GLOBALS['site_parameters']['zoom'] == 'jqzoom') {
					$display_first_image_on_mini_pictures_list = false;
					$a_zoom_attributes = ' rel="gal' . $product_id . '" class="jqzoom' . $product_id . '"';
					$a_other_pictures_attributes = ' href="javascript:void(0);" rel="{gallery: \'gal' . $product_id . '\', smallimage: \'[SMALL_IMAGE]\',largeimage: \'[ZOOM_IMAGE]\'}"';
					if (!empty($srcWidth)) {
						$javascript .= '
<script><!--//--><![CDATA[//><!--
( function($) {
	$(document).ready(function(){
		$(function() {
			var options =
			{
				zoomWidth: ' . $srcWidth . ',
				zoomHeight: ' . $srcHeight . ',
				yoffset: 0,
				hideEffect: "fadeout"
			}
			$(".jqzoom' . $product_id . '").jqzoom(options);
		});
	});
} ) ( jQuery )
//--><!]]></script>';
					}
				} elseif ($GLOBALS['site_parameters']['zoom'] == 'lightbox') {
					$a_zoom_attributes = ' class="lightbox"';
				} else {
					$a_zoom_attributes = '';
				}
				$tpl->assign('main_image', array(
						'href' => $GLOBALS['repertoire_upload'] . '/' . $product_images[0],
						'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_thumb,
						'is_pdf' => !(pathinfo($product_images[0], PATHINFO_EXTENSION) != 'pdf')
					));
			}
			$tpl->assign('a_zoom_attributes', vb($a_zoom_attributes));
			$tpl->assign('a_other_pictures_attributes', vb($a_other_pictures_attributes));
			$tpl->assign('javascript', $javascript);
			if (!empty($product_object->on_estimate)) {
				$tpl->assign('title_price', array(
					'txt' => $GLOBALS['STR_ON_ESTIMATE'], 
					'value' => false
					));
			} elseif ($product_object->get_final_price() != 0) {
				$tpl->assign('title_price', array(
					'txt' => false, 
					'value' => str_replace(' ', ' ', $product_object->affiche_prix(display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), true, false, 'title_price', false, true, 'price_in_product_title', true, true))
					));
			} else {
				$tpl->assign('title_price', array(
					'txt' => $GLOBALS['STR_FREE'], 
					'value' => false
					));
			}
			
			if (is_module_vacances_active()) {
				if (get_vacances_type() == 1) {
					$tpl->assign('global_error', array(
						'txt' => vb($GLOBALS['site_parameters']['module_vacances_client_msg_' . $_SESSION['session_langue']]), 
						'date' => false
					));
				} elseif (get_vacances_type() == 2) {
					// on récupère le fournisseur pour afficher sa date de retour
					$supplier_back = query("SELECT on_vacances, on_vacances_date
								FROM peel_utilisateurs
								WHERE id_utilisateur = " . $product_object->id_utilisateur . "");
					$res_supplier_back = fetch_assoc($supplier_back);
					if (num_rows($supplier_back) == 1 && !empty($res_supplier_back['on_vacances_date']) && !empty($res_supplier_back['on_vacances'])) {
						$tpl->assign('global_error', array(
							'txt' => $GLOBALS['STR_HOLIDAY_AVAILABLE'], 
							'date' => get_formatted_date($res_supplier_back['on_vacances_date'])
						));
					}
				}
			}
			$tpl->assign('link_contact', $GLOBALS["wwwroot"] . '/utilisateurs/contact.php');
			$tpl->assign('contact', $GLOBALS["STR_CONTACT"]);
			$tpl->assign('product_detail_image', $GLOBALS['site_parameters']['general_product_image']);
			$tpl->assign('product_name', $product_object->name);
			$tpl->assign('product_id', $product_object->id);
			$tpl->assign('product_href', $product_object->get_product_url());
			if (!empty($_GET['catid']))	{
				// Permet de gérer si catégorie imposée
				$current_catid = intval($_GET['catid']);
			} else {
				$current_catid = $product_object->categorie_id;
			}
			$tpl->assign('prev', (is_module_precedent_suivant_active() ? show_preview_next($product_object->id, $product_object->position, 'prev', $current_catid) : ''));
			$tpl->assign('next', (is_module_precedent_suivant_active() ? show_preview_next($product_object->id, $product_object->position, 'next', $current_catid) : ''));
			
			if ($product_object->is_price_flash(is_reseller_module_active() && is_reseller())) {
				$tpl->assign('flash_txt', $GLOBALS['STR_TEXT_FLASH1'] . ' ' . get_formatted_duration(strtotime($product_object->flash_end) - time(), false, 'day') . ' ' . $GLOBALS['STR_TEXT_FLASH2']);
			}
			
			if (a_priv('admin_products', false)) {
				$tpl->assign('admin', array(
					'href' => $GLOBALS['administrer_url'] . '/produits.php?mode=modif&id=' . $product_id,
					'modify_txt' => $GLOBALS['STR_MODIFY_PRODUCT'],
					'is_offline' => (bool)($product_object->etat == 0),
					'offline_txt' => $GLOBALS['STR_OFFLINE_PRODUCT']
				));
			}			
			if (!empty($product_images) && count($product_images) > 1) {
				$tmp_imgs = array();
				foreach ($product_images as $key => $name) {
					if (!$display_first_image_on_mini_pictures_list && $key == 0) {
						// On n'affiche pas l'image principale pour Jq zoom, puisque l'image principal est intervertit avec l'image secondaire.
					} else {
						$vignette_id = 'vignette' . $key;
						if (pathinfo($name, PATHINFO_EXTENSION) == 'pdf') {
							$tmp_imgs[] = array(
								'is_pdf' => true,
								'href' => $GLOBALS['repertoire_upload'] . '/' . $name,
							);
						} else {
							$small_image = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($name, $GLOBALS['site_parameters']['medium_width'], $GLOBALS['site_parameters']['medium_height'], 'fit');
							$tmp_imgs[] = array(
								'is_pdf' => false,
								'id' => $vignette_id,
								'a_attr' => str_replace(array('[SMALL_IMAGE]', '[ZOOM_IMAGE]', '[VIGNETTE_CLASS]'), array($small_image, $GLOBALS['repertoire_upload'] . '/' . $name, $vignette_id), $a_other_pictures_attributes),
								'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($name, $secondary_images_width, $secondary_images_height, 'fit'),
							);
						}
					}
				}
				$tpl->assign('product_images', $tmp_imgs);
			}
			if (is_module_direaunami_active()) {
				$tpl->assign('tell_friends', array(
					'href' => get_tell_friends_url(false),
					'src' => $GLOBALS['site_parameters']['general_send_email_image'],
					'txt' => $GLOBALS['STR_TELL_FRIEND']
				));
			}
			if (is_module_avis_active()) {
				$tpl->assign('avis', array(
					'href' => $GLOBALS['wwwroot'] . '/modules/avis/avis.php?prodid=' . $product_id,
					'src' => $GLOBALS['site_parameters']['general_give_your_opinion_image'],
					'txt' => $GLOBALS['STR_DONNEZ_AVIS']
				));
				$tpl->assign('tous_avis', array(
					'href' => $GLOBALS['wwwroot'] . '/modules/avis/liste_avis.php?prodid=' . $product_id,
					'src' => $GLOBALS['site_parameters']['general_read_all_reviews_image'],
					'txt' => $GLOBALS['STR_TOUS_LES_AVIS']
				));
			}
			if (is_module_pensebete_active()) {
				$tpl->assign('pensebete', array(
					'href' => $GLOBALS['wwwroot'] . '/modules/pensebete/ajouter.php?mode=ajout&prodid=' . $product_id,
					'src' => $GLOBALS['site_parameters']['general_add_notepad_image'],
					'txt' => $GLOBALS['STR_AJOUT_PENSE_BETE']
				));
			}
			$tpl->assign('print', array(
				'src' => $GLOBALS['site_parameters']['general_print_image'],
				'txt' => $GLOBALS['STR_PRINT_PAGE']
			));
			
			if ((!empty($product_object->reference))) {
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
			$tpl->assign('conditionnement', $product_object->conditionnement);
			if (!empty($product_object->id_marque)) {
				$brand_link = trim(get_brand_link_html($product_object->id_marque, true));
				if(!empty($brand_link)) {
					$tpl->assign('marque', array(
						'label' => $GLOBALS['STR_BRAND'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
						'txt' => $brand_link
					));
				}
			}

			if (!empty($product_object->points)) {
				$tpl->assign('points', array(
					'label' => $GLOBALS['STR_GIFT_POINTS'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
					'txt' => $product_object->points
				));
			}
			if(vb($GLOBALS['site_parameters']['show_short_description_on_product_details'])) {
				$tpl->assign('descriptif', String::nl2br_if_needed(trim($product_object->descriptif)));
			} else {
				$tpl->assign('descriptif', '');
			}
			$possible_attributes_with_single_options = $product_object->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), true, true, false, true);
			foreach($possible_attributes_with_single_options as $this_nom_attribut_id => $this_options_array) {
				foreach($this_options_array as $this_attribut_id => $this_options_infos) {
					if($this_attribut_id && empty($this_options_infos['texte_libre']) && empty($this_options_infos['upload'])) {
						// Ceci n'est pas un attribut texte ou upload
						$product_object->description .= $this_options_infos['nom'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $this_options_infos['descriptif'] . '<br />';
					}
				}
			}
			$tpl->assign('description', String::nl2br_if_needed(trim($product_object->description)));
			if(!empty($GLOBALS['site_parameters']['show_qrcode_on_product_pages'])) {
				$tpl->assign('qrcode_image_src', $product_object->qrcode_image_src());
			}
			$tpl->assign('extra_link', $product_object->extra_link);
			
			if (is_lot_module_active()) {
				include ($GLOBALS['fonctionslot']);
				$tpl->assign('explanation_table', get_lot_explanation_table($product_id));
			}
			
			if (!empty($product_object->on_check) && is_module_gift_checks_active()) {
				$tpl->assign('check', affiche_check($product_id, 'cheque', null, true));
			} else {
				if (empty($product_object->on_estimate)) {
					if($product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), false, false, 1, true, true, false)!=0 || vb($GLOBALS['site_parameters']['show_add_to_cart_on_free_products'])) {
						$tpl->assign('critere_stock', affiche_critere_stock($product_id, 'details', null, true));
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
			
			if (!empty($product_object->display_tab)) {
				// Onglets
				$sql = 'SELECT
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
tab6_title_' . $_SESSION['session_langue'] . ' AS tab6_title
FROM peel_produits
WHERE id="' . $product_object->id . '"';
				$q_tab = query($sql);
				if ($tab = fetch_assoc($q_tab)) {
					$tabs = array();
					// pour connaitre le nombre total d'onglets, on initialise un compteur
					$j = 0;
					for ($i = 1; $i <= 6; $i++) {
						$title = trim($tab['tab' . $i . '_title']);
						if (!empty($title)) {
							$tab_html = trim($tab['tab' . $i . '_html']);
							$tabs[] = array(
								'index' => $i,
								'title' => $title,
								'is_current' => (bool)($i == 1),
								'content' => $tab_html
							);
							$j++;
						}
					}
					if (!empty($j)) {
						// On affiche le contenu des onglets seulement si pas vide
						$tpl->assign('tabs', $tabs);
					}
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
			// Charge les produits associés
			$tpl->assign('associated_products', affiche_produits(null, null, 'associated_product', $GLOBALS['site_parameters']['nb_produit_page'], vb($GLOBALS['site_parameters']['associated_products_display_mode']), true, $product_id, $nb_colonnes, false, false));
			$output = $tpl->fetch();
		}
		unset($product_object);
		return $output;
	}
}

if (!function_exists('get_products_list_brief_html')) {
	/**
	 *
	 * @param integer $catid
	 * @return
	 */
	function get_products_list_brief_html($catid, $display_subcategories = true)
	{
		$tpl = $GLOBALS['tplEngine']->createTemplate('products_list_brief.tpl');
		$sqlcat = "SELECT image_" . $_SESSION['session_langue'] . " AS image, description_" . $_SESSION['session_langue'] . ", nom_" . $_SESSION['session_langue'] . ", type_affichage, etat";
		if (is_category_promotion_module_active()) {
			$sqlcat .= ", promotion_devises, promotion_percent";
		}
		$sqlcat .= " FROM peel_categories
			WHERE id='" . intval($catid) . "'
			ORDER BY position";
		$rescat = query($sqlcat);
		if ($cat_infos = fetch_assoc($rescat)) {
			$cat = array(
				'name' => $cat_infos['nom_' . $_SESSION['session_langue']],
			);
			if (a_priv('admin_products', false)) {
				$cat['admin'] = array(
					'href' => $GLOBALS['administrer_url'] . '/categories.php?mode=modif&id=' . $catid,
					'label' => $GLOBALS['STR_MODIFY_CATEGORY']
				);
			}
			if (($cat_infos['etat'] == 0 && a_priv('admin_products', false))) {
				$cat['offline'] = $GLOBALS['STR_OFFLINE_CATEGORY'];
			}
			if (!empty($cat_infos['image'])) {
				$this_thumb = thumbs($cat_infos['image'], 150, 75, 'fit');
				if(!empty($this_thumb)) {
					$cat['image'] = array(
						'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . $this_thumb,
						'name' => $cat_infos['nom_' . $_SESSION['session_langue']]
					);
				}
			}
			$cat['description'] = $cat_infos['description_' . $_SESSION['session_langue']];
			if (is_category_promotion_module_active() && (vn($cat_infos['promotion_devises']) > 0 || vn($cat_infos['promotion_percent']) > 0)) {
				$cat['promotion'] = array(
					'label' => $GLOBALS['STR_REDUCTION_ON_ALL_PRODUCTS_FROM_CATEGORIE'],
					'discount_text' => get_discount_text($cat_infos['promotion_devises'], $cat_infos['promotion_percent'], display_prices_with_taxes_active())
				);
			}
			$tpl->assign('cat', $cat);
		}
		
		if (!empty($cat_infos['type_affichage']) && $cat_infos['type_affichage'] == "1") {
			$products_display_mode = 'line';
			$nb_colonnes = 1;
		} else {
			$products_display_mode = 'column';
			$nb_colonnes = 3;
		}
		if (!empty($display_subcategories)) {
			// Affichage des sous-catégories
			$subcategories_table = get_subcategories_table($catid, 5, true);
			if (!empty($subcategories_table)) {
				$tpl->assign('subcategories', $subcategories_table);
			}
		}
		$tpl->assign('associated_products', affiche_produits($catid, null, 'category', vn($GLOBALS['site_parameters']['nb_produit_page']), $products_display_mode, true, null, $nb_colonnes, false));
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
	 * @return
	 */
	function affiche_prix(&$product_object, $with_taxes = true, $reseller_mode = false, $return_mode = false, $display_with_measurement = false, $item_id = null, $display_ecotax = true, $display_old_price = true, $table_css_class = 'full_expand_in_container', $display_old_price_inline = true, $display_with_vat_symbol = true, $add_rdfa_properties = false)
	{
		$output = '';
		if (!empty($product_object->prix)) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('prix.tpl');
			$tpl->assign('table_css_class', $table_css_class);
			$tpl->assign('about', '#product'.$product_object->id);
			$tpl->assign('item_id', $item_id);
			$tpl->assign('display_old_price_inline', $display_old_price_inline);
			$tpl->assign('final_price', $product_object->get_final_price(get_current_user_promotion_percentage(), $with_taxes, $reseller_mode, true, $display_with_vat_symbol, 1, true, true, $add_rdfa_properties));
			if (($product_object->get_original_price($with_taxes, $reseller_mode) != $product_object->get_final_price(get_current_user_promotion_percentage(), $with_taxes, $reseller_mode)) && $display_old_price) {
				$tpl->assign('original_price', $product_object->get_original_price($with_taxes, $reseller_mode, true));
			}
			if ($display_ecotax && !empty($product_object->ecotaxe_ht) && is_module_ecotaxe_active()) {
				$tpl->assign('ecotax', array(
					'label' => $GLOBALS['STR_WITH_ECOTAX'] . $GLOBALS['STR_BEFORE_TWO_POINTS'],
					'prix' => fprix($product_object->get_ecotax($with_taxes), true)
				));
			}
			
			if ($display_with_measurement) {
				if (!empty($product_object->poids) && $product_object->display_price_by_weight == '1') {
					$tpl->assign('measurement', array(
						'label' => $GLOBALS['STR_PRICE_WEIGHT'],
						'prix' => fprix($product_object->get_final_price(get_current_user_promotion_percentage(), $with_taxes, $reseller_mode) * 1000 / intval($product_object->poids), true)
					));
				} elseif (!empty($product_object->volume) && $product_object->display_price_by_weight == '2') {
					$tpl->assign('measurement', array(
						'label' => $GLOBALS['STR_PRICE_LITRE'],
						'prix' => fprix($product_object->get_final_price(get_current_user_promotion_percentage(), $with_taxes, $reseller_mode) * 1000 / intval($product_object->volume), true)
					));
				}
			}
			$output = $tpl->fetch();
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
		if(vb($GLOBALS['site_parameters']['show_on_estimate_text'])) {
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
	 * @param mixed $unused
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
	 * @return
	 */
	function affiche_produits($condition_value1, $unused, $type, $nb_par_page, $mode = 'general', $return_mode = false, $reference_id = 0, $nb_colonnes = 2, $no_display_if_empty = false, $always_show_multipage_footer = true, $additional_sql_inner = null, $additional_sql_cond = null, $additionnal_sql_having = null)
	{
		if($mode == 'line') {
			$nb_colonnes = 1;
		}elseif(empty($nb_colonnes)) {
			$nb_colonnes = 3;
		}
		$params = params_affiche_produits($condition_value1, null, $type, $nb_par_page, $mode, $reference_id, $nb_colonnes, $always_show_multipage_footer, $additional_sql_inner, $additional_sql_cond, $additionnal_sql_having);
		$results_array = $params['Links']->Query();
		
		$tpl = $GLOBALS['tplEngine']->createTemplate('produits.tpl');
		$tpl->assign('is_associated_product', ((!$no_display_if_empty || !empty($results_array)) AND $type == 'associated_product'));
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
				$tpl->assign('details_text', $GLOBALS['STR_MORE_DETAILS']);
				$allow_order = true;
			} elseif (vb($GLOBALS['site_parameters']['category_show_more_on_catalog_if_no_order_allowed'])) {
				$tpl->assign('details_text', $GLOBALS['STR_MORE']);
			}
			$tpl->assign('allow_order', $allow_order);
		}
		
		$tpl->assign('prods_line_mode', ($params['mode'] == 'line'));
		$tpl->assign('cartridge_product_css_class', $params['cartridge_product_css_class']);
		$tpl->assign('small_width', $params['small_width']);
		$tpl->assign('small_height', $params['small_height']);
		$tpl->assign('multipage', $params['Links']->GetMultipage());

		$prods = array();
		$j = 0;
		foreach ($results_array as $prod) {
			$tmpProd = array(
				'display_border' => (($j % $params['nb_colonnes'] != $params['nb_colonnes'] - 1) && ($j != count($results_array) - 1))
			);
			$product_object = new Product($prod['id'], $prod, true, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
			// on affiche une cellule
			$tmpProd['is_row'] = ($j % $params['nb_colonnes'] == 0);
			
			if ($type == 'save_cart') { // Si on est dans le module save_cart on ajoute les actions du module
				$tmpProd['save_cart'] = array(
					'src' => $GLOBALS['wwwroot'] . '/' . $GLOBALS['site_parameters']['backoffice_directory_name'] . '/images/b_drop.png',
					'href' => get_current_url(false) . '?mode=delete&id=' . $prod['save_cart_id'],
					'confirm_msg' => $GLOBALS['STR_DELETE_CART_PRESERVATION'],
					'title' => $GLOBALS['STR_DELETE_CART_TITLE'],
					'label' => $GLOBALS['STR_DELETE']
				);
			}
			
			$tmpProd['href'] = $product_object->get_product_url();
			$tmpProd['name'] = $product_object->name;
			$tmpProd['description'] = String::str_shorten(String::nl2br_if_needed(trim($product_object->descriptif)), 250);
			if (!empty($_GET['cId']) && !empty($_GET['pId']) && $_GET['pId'] == $prod['id']) {
				// Lors de la sélection de la couleur d'un produit depuis une page catalogue
				$display_picture = $product_object->get_product_pictures(false, $_GET['cId'], true);
				$display_picture = $display_picture[0];
			} else {
				$display_picture = $product_object->get_product_main_picture(true);
			}
			if (!empty($display_picture)) {
				if (pathinfo($display_picture, PATHINFO_EXTENSION) == 'pdf') {
					$tmpProd['image'] = array(
						'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs('logoPDF_small.png', $params['small_width'], $params['small_height'], 'fit', $GLOBALS['dirroot'] .'/images/'),
						'width' => $GLOBALS['site_parameters']['small_width'],
						'height' => $GLOBALS['site_parameters']['small_height'],
						'alt' => $product_object->name,
						'zoom' => array(
							'href' => $GLOBALS['repertoire_upload'] . '/' . $display_picture,
							'is_lightbox' => false,
							'label' => $GLOBALS['STR_ZOOM']
						)
					);
				} else {
					$tmpProd['image'] = array(
						'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($display_picture, $params['small_width'], $params['small_height'], 'fit'),
						'width' => false,
						'height' => false,
						'alt' => String::str_form_value($product_object->name),
						'zoom' => array(
							'href' => $GLOBALS['repertoire_upload'] . '/' . $display_picture,
							'is_lightbox' => true,
							'label' => $GLOBALS['STR_ZOOM']
						)
					);
				}
			} else {
				$tmpProd['image'] = array(
						'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($GLOBALS['site_parameters']['default_picture'], $params['small_width'], $params['small_height'], 'fit'),
						'width' => '130',
						'height' => false,
						'alt' => $GLOBALS['STR_PHOTO_NOT_AVAILABLE_ALT']
					);
			}
			if ($product_object->is_price_flash(is_reseller_module_active() && is_reseller())) {
				$tmpProd['flash'] = $GLOBALS['STR_TEXT_FLASH1'] . ' ' . get_formatted_duration(strtotime($product_object->flash_end) - time(), false, 'day') . ' ' . $GLOBALS['STR_TEXT_FLASH2'];
			}
			// Affichage des produits en ligne
			if (!empty($product_object->on_estimate)) {
				$tmpProd['on_estimate'] = display_on_estimate_information(true);
			} elseif($product_object->get_final_price() != 0) {
				if ((vn($GLOBALS['site_parameters']['category_order_on_catalog']) != 1) && ($type != 'save_cart')) {
					if ($params['mode'] == 'line') {
						$tmpProd['on_estimate'] = $product_object->affiche_prix(display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), true, false, null, false, true, 'full_expand_in_container', false);
					}else{
						$tmpProd['on_estimate'] = $product_object->affiche_prix(display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), true, false, null, false);
					}
				}
			} else {
				$tmpProd['on_estimate'] = '<span class="title_price_free">'.$GLOBALS['STR_FREE'].'</span>';
			}
			if ($product_object->on_stock == 1 && is_stock_advanced_module_active()) {
				$tmpProd['stock_state'] = $product_object->get_product_stock_state();
			}
			if (vn($GLOBALS['site_parameters']['category_order_on_catalog']) == '1' || $type == 'save_cart') {
				if (!empty($product_object->on_check) && is_module_gift_checks_active()) {
					$tmpProd['check_critere_stock'] = affiche_check($product_object->id, 'cheque', null, true);
				} else {
					if ($type == 'save_cart') {
						$tmpProd['check_critere_stock'] = affiche_critere_stock($product_object->id, 'save_cart_details_', null, true, true, vn($prod['save_cart_id']), vn($prod['saved_couleur_id']), vn($prod['saved_taille_id']), vn($prod['saved_attributs_list']), vn($prod['saved_quantity']));
					} elseif ($type == 'search') {
						$tmpProd['check_critere_stock'] = affiche_critere_stock($product_object->id, 'catalogue_details_', null, true);
					} else {
						$tmpProd['check_critere_stock'] = affiche_critere_stock($product_object->id, 'catalogue_details_', null, true, true);
					}
				}
			}
			if (a_priv('admin_products', false)) {
				$tmpProd['admin'] = array(
					'href' => $GLOBALS['administrer_url'] . '/produits.php?mode=modif&id=' . $product_object->id,
					'label' => $GLOBALS['STR_MODIFY_PRODUCT']
				);
			}
			$j++;
			if ($j % $params['nb_colonnes'] == 0 || $j == count($results_array)) {
				$tmpProd['empty_cells'] = 0;
				if($j > $params['nb_colonnes']) {
					// On a déjà une ligne pleine => il faut compléter la dernière ligne pour du XTML bien structuré
					while ($j % $params['nb_colonnes'] != 0) {
						$tmpProd['empty_cells']++;
						$j++;
					}
				} else {
					// Une seule ligne => on ajuste le nombre de colonnes à la réalité de ce qu'on a affiché
					$params['nb_colonnes'] = $j;
				}
			} 
			unset($product_object);
			$prods[] = $tmpProd;
		}
		$tpl->assign('products', $prods);
		$tpl->assign('n_columns', $params['nb_colonnes']);

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
	 * @param mixed $product_id
	 * @param mixed $form_basename
	 * @param mixed $save_cart_id
	 * @param mixed $saved_color_id
	 * @param mixed $saved_size_id
	 * @param mixed $saved_attributs_list
	 * @param mixed $saved_quantity
	 * @return
	 */
	function affiche_critere_stock($product_id, $form_basename, $listcadeaux_owner = null, $return_mode = false, $is_in_catalog = false, $save_cart_id = null, $saved_color_id = null, $saved_size_id = null, $saved_attributs_list = null, $saved_quantity = null)
	{
		// Dans le module save_cart on peut avoir plusieurs sauvegardes du même produit avec différentes options
		// la variable $save_suffix_id nous permet de gérer l'unicité d'id dans la page au besoin
		if (!empty($save_cart_id)) {
			$save_suffix_id = '_' . $save_cart_id;
		} elseif ($is_in_catalog) {
			$save_suffix_id = '_' . $product_id;
		} else {
			$save_suffix_id = '';
		}
		$form_id = $form_basename . 'ajout' . $product_id . $save_suffix_id;
		if ($GLOBALS['site_parameters']['anim_prod'] == 1) {
			$anim_prod_var = ' addToBasket(' . $product_id . '); setTimeout(\'document.getElementById(\\\'' . $form_id . '\\\').submit()\',1000); return false;';
		} else {
			$anim_prod_var = '';
		}
		// Si $condensed_display_mode est à true, alors on affiche un seul select pour couleur et taille
		$condensed_display_mode = false;
		$selected_color_id = 0;

		$output = '';
		$product_object = new Product($product_id, null, false, null, true, !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		
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
	
		$product_object->set_configuration($selected_color_id, $saved_size_id, $saved_attributs_list, is_reseller_module_active() && is_reseller(), false);
		if (is_stock_advanced_module_active() && $product_object->on_stock == 1) {
			$product_stock_infos = get_product_stock_infos($product_id);
			// on regarde la quantité du produit en stock
			$stock_remain_all = 0;
			if (!empty($product_stock_infos)) {
				foreach ($product_stock_infos as $stock_infos) {
					if (($is_in_catalog && empty($product_object->configuration_color_id)) || (!empty($product_object->configuration_color_id) && $stock_infos['couleur_id'] == $product_object->configuration_color_id) || (empty($_GET['cId']) && empty($_GET['tId'])) || (!empty($stock_infos['couleur_id']) && !empty($_GET['cId']) && $_GET['cId'] == $stock_infos['couleur_id']) || (!empty($stock_infos['taille_id']) && !empty($_GET['tId']) && $_GET['tId'] == $stock_infos['taille_id'])) {
						$stock_remain_all += $stock_infos['stock_temp'];
					}
				}
			}
			if (empty($stock_remain_all) && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
				// on force la rupture de stock
				$product_object->on_rupture = 1;
			}
		}
		$urlprod_with_cid = $product_object->get_product_url(true, true) . 'cId=';
		$urlcat_with_cid = get_current_url(false) . '?catid=' . vn($_GET['catid']) . '&pId=' . vn($product_object->id) . '&cId=';
		
		$tpl = $GLOBALS['tplEngine']->createTemplate('critere_stock.tpl');
		
		$tpl->assign('save_suffix_id', $save_suffix_id);
		$tpl->assign('urlprod_with_cid', $urlprod_with_cid);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_COLOR', $GLOBALS['STR_COLOR']);
		$tpl->assign('STR_CHOOSE_COLOR', $GLOBALS['STR_CHOOSE_COLOR']);
		$tpl->assign('STR_NO_AVAILABLE', $GLOBALS['STR_NO_AVAILABLE']);
		$tpl->assign('STR_SIZE', $GLOBALS['STR_SIZE']);
		$tpl->assign('STR_CHOOSE_SIZE', $GLOBALS['STR_CHOOSE_SIZE']);
		$tpl->assign('STR_STOCK_ATTRIBUTS', $GLOBALS['STR_STOCK_ATTRIBUTS']);
		$tpl->assign('product_id', vn($product_object->id));
			
		if (empty($product_object->on_rupture)) {
			$colors_array = $product_object->get_possible_colors();
			$sizes_infos_array = $product_object->get_possible_sizes('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller());
			$attributs_infos_array = $product_object->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller());
			$update_class = (!empty($attributs_infos_array) ? 'special_select' : '');

			$tpl->assign('is_form', true);
			$tpl->assign('action', $GLOBALS['wwwroot'] . '/achat/caddie_ajout.php?prodid=' . $product_id);
			$tpl->assign('form_id', $form_id);
			$tpl->assign('update_class', $update_class);
			$tpl->assign('condensed_display_mode', $condensed_display_mode);
			if (!$condensed_display_mode) {
				// DISPLAY BY DEFAULT OF $GLOBALS['STR_COLOR'] AND $GLOBALS['STR_SIZE'] SELECTS BEGINS HERE
				if (!empty($colors_array)) {
					$tpl->assign('is_color', true);
					$id_select_color = 'couleur' . $save_suffix_id;
					if (!empty($save_cart_id)) {
						$scId_if_needed = '+\'&scId=' . $save_cart_id . '#save_cart_' . $save_cart_id . '\'';
					} else {
						$scId_if_needed = '';
					}
					if (!$is_in_catalog && !empty($urlprod_with_cid)) {
						$on_change_action = 'document.location=\'' . $urlprod_with_cid . '\'+getElementById(\'' . $id_select_color . '\').value' . $scId_if_needed . ';';
					} elseif ($is_in_catalog && !defined('IN_SEARCH_BRAND') && !empty($urlcat_with_cid) && is_stock_advanced_module_active() && ($product_object->on_stock == 1)) {
						// Ajout de redirection pour recharger la page après sélection d'un attribut
						$on_change_action = 'document.location=\'' . $urlcat_with_cid . '\'+getElementById(\'' . $id_select_color . '\').value' . $scId_if_needed . ';';
					} else {
						$on_change_action = '';
					}
					$tpl->assign('id_select_color', $id_select_color);
					$tpl->assign('color_on_change_action', $on_change_action);
					$tplColors = array();
					foreach ($colors_array as $this_color_id => $this_color_name) {
						$isavailable = true;
						if (is_stock_advanced_module_active() && $product_object->on_stock == 1 && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
							// Par défaut on considère que le produit n'est pas disponible dans la configuration de stock étudiée
							// Si $product_stock_infos est incomplet, cela ne posera donc pas de problème
							$isavailable = false;
							foreach ($product_stock_infos as $this_stock_info) {
								if ($this_stock_info['couleur_id'] == $this_color_id && $this_stock_info['stock_temp'] > 0) {
									$isavailable = true;
								}
							}
						}
						$tplColors[] = array(
							'id' => $this_color_id,
							'issel' => ($selected_color_id == $this_color_id),
							'isavailable' => $isavailable,
							'name' => $this_color_name,
							
						);
					}
					$tpl->assign('colors', $tplColors);
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
						if (!empty($this_size_infos['row_final_price']) && $this_size_infos['row_final_price'] > 0) {
							$option_content .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': +' . fprix($this_size_infos['final_price_formatted'], true);
						} elseif (!empty($this_size_infos['row_final_price']) && $this_size_infos['row_final_price'] < 0) {
							$option_content .= $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($this_size_infos['final_price_formatted'], true);
						}
						if (is_stock_advanced_module_active() && $product_object->on_stock == 1) {
							$found_stock_info = 0;
							// on affiche des informations de stock seulement si la couleur est déjà sélectionnée ou si pas de couleur
							foreach ($product_stock_infos as $this_stock_info) {
								// Couleur sélectionnée : on affiche les informations de stock à cette taille combinée à la couleur sélectionnée
								if (($this_stock_info['couleur_id'] == $selected_color_id || empty($selected_color_id)) && $this_stock_info['taille_id'] == $this_size_infos['id']) {
									$found_stock_info += $this_stock_info['stock_temp'];
								}
							}
							if ($found_stock_info > 0 || !empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
								if ($product_object->affiche_stock == 1 && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
									$option_content .= ' - ' . $GLOBALS['STR_STOCK_ATTRIBUTS'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $found_stock_info;
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
							'suffix' => $option_content,
							'update_product_price_needed' => $update_product_price_needed
							
						);
					}
					$tpl->assign('sizes_options', $tplSizes);
				} else {
					$tpl->assign('is_sizes', false);
				}
			} else {
				$tplStocks = array();
				if (is_stock_advanced_module_active() && $product_object->on_stock == 1) {
					foreach ($product_stock_infos as $stock_infos) {
						if ($product_stock_infos['stock_temp'] > 0 || !empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
							$tmpStockLabel = '';
							if (!empty($stock_infos['couleur_nom'])) {
								$tmpStockLabel .= $GLOBALS['STR_COLOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $stock_infos['couleur_nom'];
							}
							if (!empty($stock_infos['taille_nom'])) {
								$tmpStockLabel .= ' - ' . $GLOBALS['STR_SIZE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $stock_infos['taille_nom'];
							}
							if (!empty($stock_infos['taille_id']) && !empty($sizes_infos_array[$stock_infos['taille_id']]['row_final_price'])) {
								$tmpStockLabel .= ' &nbsp; +' . $sizes_infos_array[$stock_infos['taille_id']]['final_price_formatted'];
							}
							if ($product_object->affiche_stock == 1) {
								$tmpStockLabel .= ' - ' . $GLOBALS['STR_STOCK_ATTRIBUTS'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $product_stock_infos['stock_temp'];
							}
							$output .= '</option>';
							$tplStocks[] = array(
								'isavailable' => true,
								'value' => $stock_infos['couleur_id'] . '|' . $stock_infos['taille_id'],
								'issel' => (vb($_GET['liste']) == $stock_infos['couleur_id'] . '|' . $stock_infos['taille_id']),
								'label' => $tmpStockLabel
							);
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
				$tpl->assign('stock_options', $tplStocks);
			}
		} else {
			$tpl->assign('is_form', false);
		}
		$tpl->assign('STR_DELIVERY_STOCK', $GLOBALS['STR_DELIVERY_STOCK']);
		$tpl->assign('STR_QUANTITY', $GLOBALS['STR_QUANTITY']);
		$tpl->assign('STR_NONE_COLOR_SELECTED', $GLOBALS['STR_NONE_COLOR_SELECTED']);
		$tpl->assign('STR_NONE_SIZE_SELECTED', $GLOBALS['STR_NONE_SIZE_SELECTED']);
		$tpl->assign('STR_ADD_CART', $GLOBALS['STR_ADD_CART']);
		$tpl->assign('display_javascript_for_price_update', display_javascript_for_price_update($product_object, $save_suffix_id, $form_id, 0, !empty($update_product_price_needed)));

		if (empty($product_object->on_rupture)) {
			// Gestion des autres attributs
			if (is_attributes_module_active()) {
				$tpl->assign('affiche_attributs_form_part', affiche_attributs_form_part($product_object, 'table', $save_cart_id, $save_suffix_id, $form_id));
			}
			if (is_stock_advanced_module_active() && $product_object->on_stock == 1) {
				$stock_remain_all = 0;
				foreach ($product_stock_infos as $stock_infos) {
					if (empty($selected_color_id) || (!empty($selected_color_id) && ($selected_color_id == $stock_infos['couleur_id']))){
						$stock_remain_all += $stock_infos['stock_temp'];
					}
				}
				$tpl->assign('affiche_etat_stock', affiche_etat_stock($stock_remain_all, false, true));
				if (!empty($stock_remain_all)) {
					if ($product_object->affiche_stock == 1 && !empty($product_stock_infos)) {
						$tpl->assign('stock_remain_all', $stock_remain_all);
					}
				} elseif(empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
					$product_object->on_rupture = 1;
				}
			}
		}
		// Si vous ne voulez pas gérer la notion de disponibilité en stock pour les produits à télécharger,
		// alors activez la ligne suivante et l'accolade fermante ensuite
		// if (empty($product_object->on_download)) {
		if (empty($product_object->on_rupture) && !empty($product_object->delai_stock)) {
			// On affiche le délai de livraion si le produit est disponible
			// Le délai de livraions lorsque que le produit est indisponible est affiché par affiche_etat_stock.
			$tpl->assign('delai_stock', get_formatted_duration((intval($product_object->delai_stock) * 24 * 3600), false, 'month'));
		}
		// }
		if ($product_object->on_estimate == 0) {
			$display_old_price_inline = (vn($GLOBALS['site_parameters']['category_order_on_catalog']) == '1') ? false : true;
			$tpl->assign('product_affiche_prix', $product_object->affiche_prix(display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), true, true, 'prix_' . $product_object->id . $save_suffix_id, true, true, 'full_expand_in_container', $display_old_price_inline, true));
		} else {
			$tpl->assign('product_affiche_prix', display_on_estimate_information(true));
		}
		
		if (!empty($product_object->on_rupture) && !$is_in_catalog) {
			// si la rupture est forcée ou constatée d'après les stocks
			if (is_stock_advanced_module_active()) {
				$tpl->assign('formulaire_alerte', formulaire_alerte($product_id, $_POST));
			}
		} elseif (!empty($product_object->on_rupture) && $is_in_catalog) {
			$tpl->assign('etat_stock', affiche_etat_stock($stock_remain_all, false, true));
		}
		
		if (empty($product_object->on_rupture)) {
			// On peut commander
			if (is_giftlist_module_active() && isset($listcadeaux_owner)) {
				$q_owner = getNessQuantityFromGiftList($product_id, $listcadeaux_owner);
			} else {
				$q_owner = 1;
			}
			if (empty($product_object->on_estimate)) {
				$tpl->assign('on_estimate', true);
				if (empty($product_object->on_download)) {
					$tpl->assign('qte_hidden', false);
					$tpl->assign('qte_value', (!empty($saved_quantity) ? intval($saved_quantity) : vn($q_owner)));
				} else {
					$tpl->assign('qte_hidden', true);
					if (!empty($saved_quantity)) {
						$tpl->assign('qte_value', intval($saved_quantity));
					} else {
						$tpl->assign('qte_value', 1);
					}
				}
				if (is_giftlist_module_active()) {
					$tpl->assign('giftlist', array(
						'listcadeaux_owner' => vn($listcadeaux_owner),
						'id' => $product_id,
						'form' => get_add_giftlist_form($product_id, $form_basename, true)
					));
				}
				if (!empty($colors_array)) {
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
			}else
				$tpl->assign('on_estimate', false);
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
	function get_subcategories_table($parent_id, $nb_colonnes, $return_mode = false, $display_image = true)
	{
		$output = '';
		$qid_c = query('SELECT id, nom_' . $_SESSION['session_langue'] . ', description_' . $_SESSION['session_langue'] . ', parent_id, image_' . $_SESSION['session_langue'] . ' AS image
			FROM peel_categories
			WHERE parent_id="' . intval($parent_id) . '" AND id>"0" AND etat="1"
			ORDER BY position');
		$nb_cellules = num_rows($qid_c);
		if (!empty($nb_cellules)) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('subcategories_table.tpl');
			$cats = array();
			$j = 0;
			while ($cat = fetch_assoc($qid_c)) {
				$tmpCat = array(
					'row_start' => ($j % $nb_colonnes == 0)
				);
				if ($j % $nb_colonnes != 0 || $j % $nb_colonnes == 0) {
					$tmpCat['href'] = get_product_category_url($cat['id'], $cat['nom_' . $_SESSION['session_langue']]);
					$tmpCat['width'] = floor(100 / $nb_colonnes);
					$tmpCat['name'] = $cat['nom_' . $_SESSION['session_langue']];
					
					if (!empty($cat['image']) && $display_image) {
						$tmpCat['src'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($cat['image'], 120, 120, "fit");
					}
				}
				$j++;
				
				$tmpCat['row_end'] = ($j % $nb_colonnes == 0 || $j == $nb_cellules);
				if ($j % $nb_colonnes == 0 || $j == $nb_cellules) {
					$tmpCat['empty_cells'] = 0;
					while ($j % $nb_colonnes != 0 && $j > $nb_colonnes) {
						// On a déjà une ligne pleine => il faut compléter la dernière ligne pour du XTML bien structuré
						$tmpCat['empty_cells']++;
						$j++;
					}
				}
				$cats[] = $tmpCat;
			}
			
			$tpl->assign('cats', $cats);
			$output = $tpl->fetch();
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
	 * @return
	 */
	function affiche_categorie_accueil($return_mode = false)
	{
		$output = '';
		$qid = query('SELECT c.id, c.nom_' . $_SESSION['session_langue'] . ' AS categorie, c.image_' . $_SESSION['session_langue'] . ' AS image
			FROM peel_categories c
			WHERE c.etat = "1" AND c.on_special = "1"
			ORDER BY c.position');
		$nb_cellules = num_rows($qid);
		if (!empty($nb_cellules)) {
			$nb_colonnes = 2;
			$tpl = $GLOBALS['tplEngine']->createTemplate('categorie_accueil.tpl');
			$tpl->assign('header', $GLOBALS['STR_CATALOG']);
			$cats = array();
			$j = 0;
			while ($cat = fetch_assoc($qid)) {
				$tmpCat = array(
					'row_start' => ($j % $nb_colonnes == 0)
				);
				if ($j % $nb_colonnes != 0 || $j % $nb_colonnes == 0) {
					// on affiche une cellule
					$tmpCat['href'] = get_product_category_url($cat['id'], $cat['categorie']);
					$tmpCat['name'] = $cat['categorie'];
					if (!empty($cat['image'])) {
						$tmpCat['src'] = $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($cat['image'], $GLOBALS['site_parameters']['small_width'], $GLOBALS['site_parameters']['small_height'], 'fit');
					}
				}
				$j++;
				$tmpCat['row_end'] = ($j % $nb_colonnes == 0 || $j == $nb_cellules);
				if ($j % $nb_colonnes == 0 || $j == $nb_cellules) {
					$tmpCat['empty_cells'] = 0;
					while ($j % $nb_colonnes != 0 && $j > $nb_colonnes) {
						// On a déjà une ligne pleine => il faut compléter la dernière ligne pour du XTML bien structuré
						$tmpCat['empty_cells']++;
						$j++;
					}
				}
				$cats[] = $tmpCat;
			}
			$tpl->assign('cats', $cats);
			$output = $tpl->fetch();
		}
		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}
}

if (!function_exists('affiche_menu_catalogue')) {
	/**
	 * affiche_menu_catalogue()
	 *
	 * @param mixed $location indicates the position in the website : left or right
	 * @param boolean $return_mode
	 * @param mixed $add_ul_if_result
	 * @return
	 */
	function affiche_menu_catalogue($location, $return_mode = false, $add_ul_if_result = false)
	{
		$output = '';
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
		if (!empty($all_parents_with_ordered_direct_sons_array)) {
			$output .= get_recursive_items_display($all_parents_with_ordered_direct_sons_array, $item_name_array, 0, 0, $highlighted_item, 'categories', $location);
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('menu_catalogue.tpl');
		$tpl->assign('menu', $output);
		$tpl->assign('add_ul_if_result', $add_ul_if_result);

		if ($return_mode) {
			return $tpl->fetch();
		} else {
			echo $tpl->fetch();
		}
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
	 * @return
	 */
	function affiche_arbre_categorie($catid = 0, $additional_text = null, $id_produit = null, $categories_treated = array())
	{
		if (!empty($id_produit)) {
			$product_object = new Product($id_produit);
			$product_name = $product_object->name;
			unset($product_object);
		}
		$parent = 0;
		$nom = '';
		if(empty($categories_treated[$catid])) {
			// On évite les cas de boucles entre catégories qui ont pour Nème parent une catégorie dont elles sont elles-même parents => sinon boucle sans fin
			$qid = query('SELECT parent_id, nom_' . $_SESSION['session_langue'] . ' AS nom
				FROM peel_categories
				WHERE id = "' . intval($catid) . '" AND etat = "1"');
			if ($result = fetch_assoc($qid)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('arbre_categorie.tpl');
				$tpl->assign('href', get_product_category_url($catid, $result['nom']));
				$tpl->assign('name', $result['nom']);
				$nom = $tpl->fetch();
				$parent = $result['parent_id'];
			}
			$categories_treated[$catid] = true;
		}
		if ($parent > 0) {
			return affiche_arbre_categorie($parent, ' &gt; ' . $nom . ' ' . $additional_text, null, $categories_treated) . (!empty($product_name) ?  ' &gt; ' . $product_name : '');
		} else {
			return $nom . ' ' . $additional_text . (!empty($product_name) ?  ' &gt; ' . $product_name : '');
		}
	}
}

if (!function_exists('construit_arbo_categorie')) {
	/**
	 * Parcourt récursivement l'arbre des catégories, commençant d'un parent
	 * il descend dans l'arbre et affiche les options pour une liste de boîtes de sélection
	 * Les éléments préselectionnés sont marqués comme tels
	 *
	 * @param mixed $sortie
	 * @param mixed $preselectionne Integer or Array
	 * @param integer $parent
	 * @param string $indent
	 * @param boolean $url_as_value
	 * @return
	 */
	function construit_arbo_categorie(&$sortie, &$preselectionne, $parent = 0, $indent = '', $url_as_value = false)
	{
		$sql = 'SELECT c.id, c.nom_' . $_SESSION['session_langue'] . ', c.parent_id
			FROM peel_categories c
			WHERE c.parent_id = "' . intval($parent) . '"
			ORDER BY c.position';
		$qid = query($sql);
		while ($cat = fetch_assoc($qid)) {
			if (is_array($preselectionne)) {
				if (in_array($cat['id'], $preselectionne)) {
					$selectionne = ' selected="selected"';
				} else {
					$selectionne = '';
				}
			} else {
				if ($cat['id'] == $preselectionne) {
					$selectionne = ' selected="selected"';
				} else {
					$selectionne = '';
				}
			}
			if (!$url_as_value) {
				$value = $cat['id'];
			} else {
				$value = get_product_category_url($cat['id'], $cat['nom_' . $_SESSION['session_langue']], false, false);
			}
			$tpl = $GLOBALS['tplEngine']->createTemplate('arbo_categorie.tpl');
			$tpl->assign('value', $value);
			$tpl->assign('is_selected', !empty($selectionne));
			$tpl->assign('indent', $indent);
			$tpl->assign('label', $cat['nom_' . $_SESSION['session_langue']]);
			$sortie .= $tpl->fetch();
			if ($cat['id'] != $parent) {
				construit_arbo_categorie($sortie, $preselectionne, $cat['id'], $indent . '&nbsp;&nbsp;', $url_as_value);
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
		construit_arbo_categorie($categorie_options, $frm['categories_select'], 0, '', is_module_url_rewriting_active());
		$tpl = $GLOBALS['tplEngine']->createTemplate('select_categorie.tpl');
		$tpl->assign('search_label', $GLOBALS['STR_SEARCH_CATEGORY']);
		$tpl->assign('cats', $categorie_options);
		echo $tpl->fetch();
	}
}

if (!function_exists('get_product_in_container_html')) {
	/**
	 * get_product_in_container_html()
	 *
	 * @param object $product_object
	 * @param boolean $only_show_products_with_picture
	 * @return
	 */
	function get_product_in_container_html(&$product_object, $only_show_products_with_picture = true)
	{
		$output = '';
		if (!empty($product_object->id) && !empty($product_object->etat)) {
			$urlprod = $product_object->get_product_url();
			$display_picture = $product_object->get_product_main_picture();
			if ($only_show_products_with_picture && !empty($display_picture)) {
				$tpl = $GLOBALS['tplEngine']->createTemplate('product_in_container_html.tpl');
				$tpl->assign('href', $urlprod);
				$tpl->assign('name', $product_object->name);
				if (!empty($display_picture)) {
					$tpl->assign('src', $GLOBALS['repertoire_upload'] . '/thumbs/' . thumbs($display_picture, 150, 150, "fit"));
				}
				$tpl->assign('more_detail_label', $GLOBALS['STR_MORE_DETAILS']);
				if (empty($product_object->on_estimate)) {
					$tpl->assign('on_estimate', $product_object->affiche_prix(display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller(), true, false, null, false, false));
				} else {
					$tpl->assign('on_estimate', display_on_estimate_information(true));
				}
				$output = $tpl->fetch();
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
	function display_javascript_for_price_update(&$product_object, $save_suffix_id, $form_id, $taille_display_mode = 0, $update_product_price_needed = true, $product_object2 = null)
	{
		$output = '';
		$get_infos_js = '';
		$get_javascript_price = array();
		$attributs_infos_array = $product_object->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller());
		if(!empty($product_object2)) {
			foreach($product_object2->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller()) as $this_key => $this_value) {
				$attributs_infos_array[$this_key] = $this_value;
			}
		}
		$sizes_infos_array = $product_object->get_possible_sizes('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller());
		if(!empty($product_object2)) {
			foreach($product_object2->get_possible_sizes('infos', get_current_user_promotion_percentage(), display_prices_with_taxes_active(), is_reseller_module_active() && is_reseller()) as $this_key => $this_value) {
				$sizes_infos_array[$this_key] = $this_value;
			}
		}
		if ((!empty($sizes_infos_array) && count($sizes_infos_array)>1) || (is_attributes_module_active() && !empty($attributs_infos_array))) {
			$output .= '
<script><!--//--><![CDATA[//><!--
';
			if (is_attributes_module_active() && !empty($attributs_infos_array)) {
				$get_infos_js .= '
	' . build_attr_var_js('attribut_list', $attributs_infos_array, $form_id);
			}
			if (!empty($sizes_infos_array) && count($sizes_infos_array)>1) {
				// On gère prix de taille uniquement, pas d'attributs
				if ($taille_display_mode == 0) {
					// Affichage sous forme de select de la taille
					$get_infos_js .= '
	select_size = document.getElementById("taille' . $save_suffix_id . '");
	size_id = select_size.options[select_size.selectedIndex].value;
';
				} else {
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
				}
			}
			$output .= '
function update_product_price' . ($save_suffix_id) . '(){
	var attribut_list="";
	var size_id="";
	' . $get_infos_js . '
	jQuery.post("get_product_price.php", {product_id: '.$product_object->id.', product2_id: '.(!empty($product_object2)?$product_object2->id:'""').', size_id: size_id, attribut_list: attribut_list, hash: \''.sha256('HFhza8462naf'.$product_object->id).'\'}, function(data){
		var divtoshow = "#prix_' . vn($product_object->id) . $save_suffix_id . '";
		if(data.length >0) {
			jQuery(divtoshow).show();
			jQuery(divtoshow).html(data);
		}
	});
}';
			if($update_product_price_needed){
				$output .= '
jQuery(document).ready(function(){
	update_product_price' . ($save_suffix_id) . '();
});';
			}
			$output .= '
//--><!]]></script>';
		}
		return $output;
	}
}
?>