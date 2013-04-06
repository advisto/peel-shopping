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
// $Id: Product.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 *
 * @brief La classe Product porte l'ensemble des fonctionnalités propres aux produits vendus avec PEEL
 * Ses méthodes permettent de récupérer notamment le prix formatté ou non du produit en tenant compte de toutes les réductions éventuelles
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Product.php 36232 2013-04-05 13:16:01Z gboussin $
 * @access public
 */
class Product {
	var $id = null;
	var $technical_code = null;
	var $name = null;
	var $descriptif = null;
	var $description = null;
	var $meta_titre = null;
	var $meta_desc = null;
	var $meta_key = null;
	var $lang = null;
	var $poids = null;
	var $volume = null;
	var $position = null;
	var $display_price_by_weight = null;
	var $prix_barre = null;
	var $prix_barre_ht = null;
	var $prix = null;
	var $prix_ht = null;
	var $prix_achat = null;
	var $reference = null;
	var $ean_code = null;
	var $etat = null;
	var $on_estimate = null;
	// prix_revendeur is the price for resellers : it is stored with taxes included, even if we will usually display it without taxes
	var $prix_revendeur = null;
	var $promotion = null;
	var $tva = null;
	var $points = null;
	var $default_image = null;
	var $image1 = null;
	var $image2 = null;
	var $image3 = null;
	var $image4 = null;
	var $image5 = null;
	var $image6 = null;
	var $image7 = null;
	var $image8 = null;
	var $image9 = null;
	var $image10 = null;
	var $zip = null;
	var $youtube_code = null;
	var $on_stock = null;
	var $comments = null;
	var $delai_stock = null;
	var $etat_stock = null;
	var $affiche_stock = null;
	var $on_special = null;
	var $on_rupture = null;
	var $on_flash = null;
	var $on_gift = null;
	var $on_gift_points = null;
	var $flash_start = null;
	var $flash_end = null;
	var $prix_flash = null;
	var $extrait = null;
	var $on_download = null;
	var $on_check = null;
	var $id_marque = null;
	var $default_color_id = null;
	var $display_tab = null;
	// categorie_id is the id of one of the categories the products belongs to.
	var $categorie_id = null;
	// categorie is the name of one of the categories of the product
	var $categorie = null;
	var $id_ecotaxe = null;
	var $ecotaxe_ttc = null;
	var $ecotaxe_ht = null;
	// Configuration du produit
	var $configuration_color_id = null;
	var $configuration_size_id = null;
	var $configuration_attributs_list = "";
	var $configuration_total_original_price_attributs_ht = 0;
	var $configuration_attributs_description = "";
	var $configuration_size_name = "";
	var $configuration_overweight = null;
	var $configuration_size_price_ht = 0;
	// Id du fournisseur
	var $id_utilisateur = null;
	var $vat_applicable = null;
	var $extra_link = null;
	var $conditionnement = null;
	/**
	 * Product::Product()
	 *
	 * @param integer $id
	 * @param array $product_infos
	 * @param boolean $user_only_product_infos
	 * @param string $lang
	 * @param boolean $show_all_etat_if_admin
	 * @param boolean $vat_applicable
	 */
	function Product($id, $product_infos = null, $user_only_product_infos = false, $lang = null, $show_all_etat_if_admin = true, $vat_applicable = true)
	{
		if (empty($lang)) {
			$lang = $_SESSION['session_langue'];
		}
		if (is_object($product_infos)) {
			$product_infos = get_object_vars($product_infos);
		}
		$this->lang = $lang;
		if (empty($id)) {
			$this->id = vn($product_infos['id']);
		} else {
			$this->id = $id;
		}
		if (!empty($product_infos)) {
			// Faster than making an SQL request if we have data already available
			foreach($product_infos as $this_item => $this_value) {
				$this->$this_item = $this_value;
			}
		}
		if (!$user_only_product_infos) {
			$sql = "SELECT
					 p.id
					, p.technical_code
					, p.reference
					, p.ean_code
					, p.nom_" . $_SESSION['session_langue'] . " AS name
					, p.descriptif_" . $_SESSION['session_langue'] . " AS descriptif
					, p.description_" . $_SESSION['session_langue'] . " AS description
					, p.meta_titre_" . $_SESSION['session_langue'] . " AS meta_titre
					, p.meta_desc_" . $_SESSION['session_langue'] . " AS meta_desc
					, p.meta_key_" . $_SESSION['session_langue'] . " AS meta_key
					, p.on_estimate
					, p.prix
					, p.prix_achat
					, p.prix_revendeur
					, p.tva
					, p.etat
					, p.promotion
					, p.points
					, p.default_image
					, p.image1
					, p.image2
					, p.image3
					, p.image4
					, p.image5
					, p.image6
					, p.image7
					, p.image8
					, p.image9
					, p.image10
					, p.zip
                    , p.id_utilisateur
					, p.youtube_code
					, p.on_stock
					, p.comments
					, p.delai_stock
					, p.etat_stock
					, p.affiche_stock
					, p.on_special
					, p.on_gift
					, p.on_gift_points
					, p.on_rupture
					, p.on_flash
					, p.flash_start
					, p.flash_end
					, p.prix_flash
					, p.extrait
					, p.on_download
					, p.on_check
					, p.id_marque
					, p.default_color_id
					, p.display_price_by_weight
					, p.id_ecotaxe
					, p.display_tab
					, p.poids
					, p.volume
					, p.position
					, p.extra_link
					, p.on_gift
					, p.on_gift_points
					, c.id AS categorie_id
					, c.nom_" . $_SESSION['session_langue'] . " AS categorie";
			if (is_conditionnement_module_active()) {
				$sql .= ", p.conditionnement";
			}
			$sql .= " FROM peel_produits p
				INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
				INNER JOIN peel_categories c ON c.id = pc.categorie_id
				WHERE p.id = '" . intval($id) . "' " . ($show_all_etat_if_admin && a_priv("admin_products", false)?'AND p.etat IN ("1","0")':'AND p.etat = "1"') . "
				LIMIT 1";
			// Le limit 1 est nécessaire car le produit peut être associé à plusieurs catégories => on ne récupère que la première catégorie trouvée
			$query = query($sql);
			if ($product_infos = fetch_assoc($query)) {
				foreach($product_infos as $this_item => $this_value) {
					if ($this->$this_item === null) {
						$this->$this_item = $this_value;
					}
				}
			} else {
				// If the product does not exist, its id is put to 0 even if $id is not 0
				$this->id = 0;
			}
		}
		if (empty($this->name)) {
			$name = 'nom_' . $_SESSION['session_langue'];
			$this->name = String::html_entity_decode_if_needed(vb($this->$name));
		} else {
			$this->name = String::html_entity_decode_if_needed($this->name);
		}
		if (empty($this->descriptif)) {
			$descriptif = 'descriptif_' . $_SESSION['session_langue'];
			$this->descriptif = String::html_entity_decode_if_needed(vb($this->$descriptif));
		} else {
			$this->descriptif = String::html_entity_decode_if_needed($this->descriptif);
		}
		correct_output($this->descriptif, false, 'html');
		if (empty($this->description)) {
			$description = 'description_' . $_SESSION['session_langue'];
			$this->description = String::html_entity_decode_if_needed(vb($this->$description));
		} else {
			$this->description = String::html_entity_decode_if_needed($this->description);
		}
		correct_output($this->description, false, 'html');
		$this->categorie = String::html_entity_decode_if_needed(vb($this->categorie));
		$this->poids = floatval($this->poids);
		$this->volume = floatval($this->volume);
		if (is_module_ecotaxe_active()) {
			if (!empty($product_infos) && isset($product_infos['ecotaxe_ht']) && isset($product_infos['ecotaxe_ttc'])) {
				$this->ecotaxe_ht = $product_infos['ecotaxe_ht'];
				$this->ecotaxe_ttc = $product_infos['ecotaxe_ttc'];
			} else {
				if (!empty($this->id_ecotaxe)) {
					$eco = get_ecotax_object($this->id_ecotaxe);
				}
				if (!empty($eco)) {
					$this->ecotaxe_ht = $eco->prix_ht;
					$this->ecotaxe_ttc = $eco->prix_ttc;
				} else {
					$this->ecotaxe_ht = 0;
					$this->ecotaxe_ttc = 0;
				}
			}
		} else {
			$this->ecotaxe_ht = 0;
			$this->ecotaxe_ttc = 0;
		}
		$this->prix_ht = $this->prix / (1 + $this->tva / 100);
		$this->vat_applicable = $vat_applicable;
		if (empty($vat_applicable)) {
			$this->ecotaxe_ttc = $this->ecotaxe_ht;
			$this->prix = $this->prix_ht;
		}
	}

	/**
	 * Définit la configuration du produit, en tenant compte du statut revendeur ou non de l'utilisateur afin de stocker les bonnes valeurs dans configuration_size_price_ht et configuration_total_original_price_attributs_ht
	 * format_attribut_description_for_database est défini pour distinguer le cas où l'on veut sauvegarder dans la bdd ou pas
	 *
	 * @param integer $color_id
	 * @param integer $size_id
	 * @param mixed $attributs_list
	 * @param boolean $reseller_mode
	 * @param boolean $format_attribut_description_for_database
	 * @return
	 */
	function set_configuration($color_id = null, $size_id = null, $attributs_list = null, $reseller_mode = false, $format_attribut_description_for_database = false)
	{
		static $nom_attribut_array;
		// Color has no impact on price
		$this->configuration_color_id = $color_id;
		if ($this->configuration_size_id !== $size_id) {
			// Size can have an impact on price
			$this->configuration_size_id = $size_id;
			$size_array = $this->get_size('infos', 0, false, $reseller_mode, false, false);
			$this->configuration_size_name = vb($size_array['name']);
			$this->configuration_size_price_ht = vn($size_array['row_original_price']);
			$this->configuration_overweight = vn($size_array['poids']);
		}
		if (is_attributes_module_active() && $this->configuration_attributs_list !== $attributs_list) {
			// Initialisation
			$this->configuration_attributs_list = $attributs_list;
			$this->configuration_total_original_price_attributs_ht = 0;
			$this->configuration_attributs_description = "";
			// Traitement des attributs
			if (!empty($attributs_list)) {
				$this->configuration_attributs_description = affiche_attributs_form_part($this, 'selected_text', null, null, null, null, null, $reseller_mode);
				$this->configuration_total_original_price_attributs_ht = vn($GLOBALS['last_calculation_additional_price_ht']);
			}
		}
	}

	/**
	 * Product::get_product_url()
	 *
	 * @param boolean $add_get_suffixe
	 * @param boolean $html_encode
	 * @return
	 */
	function get_product_url($add_get_suffixe = false, $html_encode = false)
	{
		if (empty($this->categorie_id) || empty($this->categorie)) {
			$query = query("SELECT p.nom_" . $_SESSION['session_langue'] . ", pc.categorie_id, r.nom_" . $_SESSION['session_langue'] . " AS categorie
				FROM peel_produits p
				INNER JOIN peel_produits_categories pc ON p.id = pc.produit_id
				INNER JOIN peel_categories r ON r.id = pc.categorie_id
				WHERE p.id ='" . intval($this->id) . "'");
			if ($prod = fetch_assoc($query)) {
				$this->categorie_id = $prod['categorie_id'];
				$this->categorie = $prod['categorie'];
				$this->name = $prod['nom_' . $_SESSION['session_langue']];
			}
		}
		if (!empty($this->categorie_id)) {
			return get_product_url($this->id, $this->name, $this->categorie_id, $this->categorie, $add_get_suffixe, $html_encode);
		} else {
			return null;
		}
	}

	/**
	 * Product::get_color()
	 *
	 * @return
	 */
	function get_color()
	{
		$colors_array = $this->get_possible_colors();
		if (!empty($colors_array[$this->configuration_color_id])) {
			return $colors_array[$this->configuration_color_id];
		} else {
			return null;
		}
	}

	/**
	 * Product::get_possible_colors()
	 *
	 * @return
	 */
	function get_possible_colors()
	{
		static $possible_colors;
		$colors_array = array();
		if (!isset($possible_colors[$this->id . '-' . $_SESSION['session_langue']])) {
			$possible_colors[$this->id . '-' . $_SESSION['session_langue']] = array();
			$query = query('SELECT pc.couleur_id, c.nom_' . $_SESSION['session_langue'] . '
				FROM peel_produits_couleurs pc
				INNER JOIN peel_couleurs c ON c.id = pc.couleur_id
				WHERE pc.produit_id  = "' . intval($this->id) . '"
				ORDER BY c.position ASC, c.nom_' . $_SESSION['session_langue'] . ' ASC');
			while ($result = fetch_assoc($query)) {
				$possible_colors[$this->id . '-' . $_SESSION['session_langue']][$result['couleur_id']] = $result['nom_' . $_SESSION['session_langue']];
			}
		}
		return $possible_colors[$this->id . '-' . $_SESSION['session_langue']];
	}

	/**
	 * Product::get_size()
	 *
	 * @param string $return_mode
	 * @param integer $user_promotion_percentage
	 * @param boolean $with_taxes
	 * @param boolean $reseller_mode
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @return
	 */
	function get_size($return_mode = 'name', $user_promotion_percentage = 0, $with_taxes = true, $reseller_mode = false, $format = false, $add_tax_type_text = false)
	{
		$sizes_array = $this->get_possible_sizes($return_mode, $user_promotion_percentage, $with_taxes, $reseller_mode, $format, $add_tax_type_text);
		if (!empty($sizes_array[$this->configuration_size_id])) {
			return $sizes_array[$this->configuration_size_id];
		} else {
			return null;
		}
	}

	/**
	 * Product::get_possible_sizes()
	 *
	 * @param string $return_mode
	 * @param integer $user_promotion_percentage
	 * @param boolean $with_taxes
	 * @param boolean $reseller_mode
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @return
	 */
	function get_possible_sizes($return_mode = 'name', $user_promotion_percentage = 0, $with_taxes = true, $reseller_mode = false, $format = false, $add_tax_type_text = false)
	{
		static $possible_sizes;
		$sizes_array = array();
		if (!isset($possible_sizes[$this->id . '-' . $_SESSION['session_langue']])) {
			$possible_sizes[$this->id . '-' . $_SESSION['session_langue']] = array();
			$query = query('SELECT t.*, pt.taille_id
				FROM peel_produits_tailles pt
				INNER JOIN peel_tailles t ON t.id=pt.taille_id
				WHERE pt.produit_id="' . intval($this->id) . '"
				ORDER BY t.position ASC, t.prix ASC, t.nom_' . $_SESSION['session_langue'] . ' ASC');
			while ($result = fetch_assoc($query)) {
				$possible_sizes[$this->id . '-' . $_SESSION['session_langue']][] = $result;
			}
		}
		if (!empty($possible_sizes)) {
			foreach($possible_sizes[$this->id . '-' . $_SESSION['session_langue']] as $result) {
				if ($return_mode == 'name') {
					$sizes_array[$result['taille_id']] = $result['nom_' . $_SESSION['session_langue']];
				} else {
					if ($reseller_mode && $result["prix_revendeur"] != 0) {
						$original_price = $result["prix_revendeur"] / (1 + $this->tva / 100);
					} else {
						$original_price = $result["prix"] / (1 + $this->tva / 100);
					}
					$final_price = $original_price * (1 - $this->get_all_promotions_percentage($reseller_mode, $user_promotion_percentage) / 100);
					$result['name'] = $result['nom_' . $_SESSION['session_langue']];
					$result['row_original_price'] = $this->format_prices($original_price, $with_taxes, false, false, false);
					$result['row_final_price'] = $this->format_prices($final_price, $with_taxes, false, false, false);
					$result['final_price_formatted'] = $this->format_prices($final_price, $with_taxes, false, $format, $add_tax_type_text);
					$sizes_array[$result['taille_id']] = $result;
				}
			}
		}
		return $sizes_array;
	}

	/**
	 * Product::get_possible_attributs()
	 *
	 * @param string $return_mode Values allowed : 'infos', 'rough', 'option_name', 'full_name'
	 * @param boolean $get_configuration_results_only
	 * @param integer $user_promotion_percentage
	 * @param boolean $with_taxes
	 * @param boolean $reseller_mode
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @param boolean $get_attributes_with_multiple_options_only
	 * @param boolean $get_attributes_with_single_options_only
	 * @param string $filter_technical_code
	 * @return
	 */
	function get_possible_attributs($return_mode = 'name', $get_configuration_results_only = false, $user_promotion_percentage = 0, $with_taxes = true, $reseller_mode = false, $format = false, $add_tax_type_text = false, $get_attributes_with_multiple_options_only = true, $get_attributes_with_single_options_only = false, $filter_technical_code = null)
	{
		if (!is_attributes_module_active()) {
			continue;
		}
		if(!empty($this->id)) {
			$attributs_array = get_possible_attributs($this->id, ($return_mode=='infos'?'rough':$return_mode), $get_attributes_with_multiple_options_only, $get_attributes_with_single_options_only, ($get_configuration_results_only?$this->configuration_attributs_list:null));
		} else {
			$attributs_array = array();
		}
		if (!empty($attributs_array) && $return_mode == 'infos') {
			foreach ($attributs_array as $this_nom_attribut_id => $this_attribut_values_array) {
				foreach ($this_attribut_values_array as $this_attribut_id => $result) {
					if(!empty($filter_technical_code) && $result['technical_code'] == $filter_technical_code) {
						continue;
					}
					if ($reseller_mode && $result["prix_revendeur"] != 0) {
						$original_price = $result["prix_revendeur"] / (1 + $this->tva / 100);
					} else {
						$original_price = $result["prix"] / (1 + $this->tva / 100);
					}
					$final_price = $original_price * (1 - $this->get_all_promotions_percentage($reseller_mode, $user_promotion_percentage) / 100);
					$result['name'] = $result['nom'];
					$result['row_original_price'] = $this->format_prices($original_price, $with_taxes, false, false, false);
					$result['row_final_price'] = $this->format_prices($final_price, $with_taxes, false, false, false);
					$result['final_price_formatted'] = $this->format_prices($final_price, $with_taxes, false, $format, $add_tax_type_text);
					$attributs_array[$this_nom_attribut_id][$this_attribut_id] = $result;
				}
			}
		}
		return $attributs_array;
	}
	
	/**
	 * Product::get_product_references()
	 *
	 * @return
	 */
	function get_product_references()
	{
		$references_array = array();
		$query = query('SELECT ppr.reference_id
			FROM peel_produits_references ppr
			WHERE ppr.produit_id="' . intval($this->id) . '"');
		while ($result = fetch_assoc($query)) {
			$references_array[] = $result['reference_id'];
		}
		return $references_array;
	}

	/**
	 * Product::get_product_brands()
	 *
	 * @return
	 */
	function get_product_brands()
	{
		$brands_array = array();
		$query = query('SELECT pm.nom_' . $_SESSION['session_langue'] . '
			FROM peel_marques pm
			WHERE pm.id="' . intval($this->id_marque) . '"');
		while ($result = fetch_assoc($query)) {
			$brands_array[$this->id_marque] = $result['nom_' . $_SESSION['session_langue']];
		}
		return $brands_array;
	}

	/**
	 * Product::get_product_options()
	 *
	 * @return
	 */
	function get_product_options()
	{
		$options_array = array();
		$query = query('SELECT pa.descriptif_' . $_SESSION['session_langue'] . '
			FROM peel_attributs pa
			INNER JOIN peel_produits_attributs ppa ON ppa.attribut_id = pa.id
			WHERE ppa.produit_id = ' . intval($this->id));
		while ($result = fetch_assoc($query)) {
			$options_array[] = $result['descriptif_' . $_SESSION['session_langue']];
		}
		return $options_array;
	}

	/**
	 * Check if a picture or a pdf exist in peel_produit_color and peel_produit and returns the first image file name
	 * if no picture for this product, it return 'false'
	 *
	 * @param boolean $display_pdf
	 * @param integer $force_id_couleur
	 * @return
	 */
	function get_product_main_picture($display_pdf = false, $force_id_couleur = null)
	{
		$product_images = $this->get_product_pictures($display_pdf, $force_id_couleur, true);
		if (!empty($product_images)) {
			return $product_images[0];
		} else {
			return false;
		}
	}
	/**
	 * Check if pictures or pdf files exist in peel_produit_color and peel_produit and returns the array of these file names
	 * if no picture for this product, it return 'false'
	 *
	 * @param boolean $display_pdf
	 * @param integer $force_id_couleur
	 * @param boolean $only_return_first_picture
	 * @return
	 */
	function get_product_pictures($display_pdf = false, $force_id_couleur = null, $only_return_first_picture = false)
	{
		if (!empty($force_id_couleur)) {
			$sql_condition = ' AND couleur_id="' . intval($force_id_couleur) . '"';
		} elseif (!empty($this->configuration_color_id)) {
			$sql_condition = ' AND couleur_id="' . intval($this->configuration_color_id) . '"';
		} else {
			// Si il n'y a pas de couleur choisie, on sélectionne la couleur par défaut choisie par l'admin
			$sql_condition = ' AND couleur_id="' . intval($this->default_color_id) . '"';
		}
		$sql = 'SELECT *
			FROM peel_produits_couleurs
			WHERE produit_id="' . intval($this->id) . '" ' . $sql_condition . '
			LIMIT 1';
		$q = query($sql);
		if ($result = fetch_assoc($q)) {
			// On commence par l'image par défaut pour que ce soit le premier élément du tableau
			if (!empty($result['default_image']) && is_numeric($result['default_image']) && !empty($result['image' . $result['default_image']]) && ($display_pdf || pathinfo($result['image' . $result['default_image']], PATHINFO_EXTENSION) != 'pdf')) {
				$product_images[] = $result['image' . $result['default_image']];
			}
			for($i = 1;$i <= 5;$i++) {
				if (!empty($result['image' . $i]) && $i != $result['default_image'] && (!$only_return_first_picture || empty($product_images)) && ($display_pdf || pathinfo($result['image' . $i], PATHINFO_EXTENSION) != 'pdf')) {
					$product_images[] = $result['image' . $i];
				}
			}
		}
		$sql = 'SELECT default_image, image1, image2, image3, image4, image5, image6, image7, image8, image9, image10
			FROM peel_produits
			WHERE id=' . intval($this->id);
		$q = query($sql);
		if ($result = fetch_assoc($q)) {
			// On commence par l'image par défaut pour que ce soit le premier élément du tableau
			if (!empty($result['default_image']) && is_numeric($result['default_image']) && !empty($result['image' . $result['default_image']]) && (!$only_return_first_picture || empty($product_images)) && ($display_pdf || pathinfo($result['image' . $result['default_image']], PATHINFO_EXTENSION) != 'pdf')) {
				$product_images[] = $result['image' . $result['default_image']];
			}
			for($i = 1;$i <= 10;$i++) {
				if (!empty($result['image' . $i]) && $i != $result['default_image'] && (!$only_return_first_picture || empty($product_images)) && ($display_pdf || pathinfo($result['image' . $i], PATHINFO_EXTENSION) != 'pdf')) {
					$product_images[] = $result['image' . $i];
				}
			}
		}
		if (!empty($product_images)) {
			return $product_images;
		} else {
			return false;
		}
	}

	/**
	 * Product::get_supplier_price()
	 *
	 * @param boolean $with_taxes
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @param boolean $add_ecotax
	 * @param integer $quantity
	 * @return
	 */
	function get_supplier_price($with_taxes = true, $format = false, $add_tax_type_text = false, $add_ecotax = true, $quantity = 1)
	{
		if (isset($this->prix_achat)) {
			$prix_achat_ht = $this->prix_achat / (1 + $this->tva / 100);
			return $this->format_prices($prix_achat_ht, $with_taxes, (!empty($add_ecotax)?$quantity:false), $format, $add_tax_type_text);
		} else {
			return null;
		}
	}

	/**
	 * Product::get_original_price()
	 *
	 * @param boolean $with_taxes
	 * @param boolean $reseller_mode
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @param boolean $add_ecotax
	 * @param boolean $get_price_for_this_configuration
	 * @param integer $quantity
	 * @return
	 */
	function get_original_price($with_taxes = true, $reseller_mode = false, $format = false, $add_tax_type_text = false, $add_ecotax = true, $get_price_for_this_configuration = true, $quantity = 1)
	{
		if ($reseller_mode && $this->prix_revendeur != 0) {
			$price_ht = $this->prix_revendeur / (1 + $this->tva / 100);
		} else {
			$price_ht = $this->prix_ht;
		}
		if ($get_price_for_this_configuration) {
			$price_ht += $this->configuration_size_price_ht;
			if (is_attributes_module_active()) {
				$price_ht += $this->configuration_total_original_price_attributs_ht;
			}
		}
		return $this->format_prices($price_ht, $with_taxes, (!empty($add_ecotax)?$quantity:false), $format, $add_tax_type_text);
	}

	/**
	 * Prix final après application des réductions diverses, pour la quantité demandée (on renvoie le prix total, et non pas le prix unitaire)
	 *
	 * @param integer $user_promotion_percentage
	 * @param boolean $with_taxes
	 * @param boolean $reseller_mode
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @param integer $quantity
	 * @param boolean $add_ecotax
	 * @param boolean $get_price_for_this_configuration
	 * @return
	 */
	function get_final_price($user_promotion_percentage = 0, $with_taxes = true, $reseller_mode = false, $format = false, $add_tax_type_text = false, $quantity = 1, $add_ecotax = true, $get_price_for_this_configuration = true, $add_rdfa_properties = false)
	{
		// Choix entre prix grossiste et prix public
		if ($reseller_mode) {
			// The reseller price is never affected by promotions or flash prices
			$price_ht = $this->get_original_price(false, true, false, false, false, false);
		} elseif ($this->is_price_flash($reseller_mode)) {
			$price_ht = $this->prix_flash / (1 + $this->tva / 100);
		} else {
			$price_ht = $this->get_original_price(false, false, false, false, false, false);
		}
		if (is_lot_module_active()) {
			/* Si le module de gestion des prix / quantité est actif */
			if (!isset($GLOBALS['cache']) || !isset($GLOBALS['cache']['lot_price_by_id']) || !isset($GLOBALS['cache']['lot_price_by_id'][$this->id])) {
				$GLOBALS['cache']['lot_price_by_id'][$this->id] = array();
				// Ces informations ne changent pas en cours d'exécution du script
				// => on met en cache global (pas static car l'objet sera peut-être recréé entre temps)
				$query = query("SELECT quantite, prix, prix_revendeur
					FROM peel_quantites q
					WHERE produit_id = '" . intval($this->id) . "' AND " . (is_reseller_module_active() && is_reseller()?'(q.prix_revendeur>0 OR q.prix>0)':'q.prix>0') . "
					ORDER BY quantite ASC");
				while ($Qte = fetch_assoc($query)) {
					$GLOBALS['cache']['lot_price_by_id'][$this->id][] = $Qte;
				}
			}
			foreach($GLOBALS['cache']['lot_price_by_id'][$this->id] as $Qte) {
				/* il existe des remises / quantité dans la base */
				if ($quantity >= $Qte['quantite']) {
					$price_ht = min ($price_ht, ($reseller_mode && $Qte['prix_revendeur'] != 0? $Qte['prix_revendeur'] / (1 + $this->tva / 100) : $Qte['prix'] / (1 + $this->tva / 100)));
				} else {
					break;
				}
			}
		}
		if ($get_price_for_this_configuration) {
			$price_ht += $this->configuration_size_price_ht;
			if (is_attributes_module_active()) {
				$price_ht += $this->configuration_total_original_price_attributs_ht;
			}
		}
		if (!$this->is_price_flash($reseller_mode)) {
			// Si on veut cumuler les réductions par produit, par marque et par catégorie, décommenter la ligne suivante
			$promotion_devises = 0;
			if (!$reseller_mode && is_marque_promotion_module_active()) {
				if (is_category_promotion_module_active()) {
					$cat = get_category_promotion_by_product($this->id);
					if (!empty($cat) && $cat['promotion_devises'] > 0) {
						// Réduction par marque en valeur et non pas en pourcentage
						$promotion_devises = max($promotion_devises, $cat['promotion_devises']);
					}
				}
				if (is_marque_promotion_module_active()) {
					$marque = get_marque_promotion_by_product($this->id);
					if (!empty($marque) && $marque['promotion_devises'] > 0) {
						// Réduction par marque en valeur et non pas en pourcentage
						$promotion_devises = max($promotion_devises, $marque['promotion_devises']);
					}
				}
			}
			$price_ht = max($price_ht - $promotion_devises / (1 + $this->tva / 100), 0);
			// Application des réductions en pourcentages
			$price_ht = $price_ht * (1 - $this->get_all_promotions_percentage($reseller_mode, $user_promotion_percentage, false) / 100) ;
		} else {
			// Si c'est un prix flash, on n'applique pas les réductions en pourcentage ni en valeur
			// (mais sur les options, les pourcentages seront quand même appliqués - pas gérés ici)
			$price_ht = $price_ht * (1 - $user_promotion_percentage / 100) ;
		}
		$price_ht = $price_ht * $quantity;

		return $this->format_prices($price_ht, $with_taxes, (!empty($add_ecotax)?$quantity:false), $format, $add_tax_type_text, $add_rdfa_properties);
	}

	/**
	 * Product::is_price_flash()
	 *
	 * @param boolean $reseller_mode
	 * @return
	 */
	function is_price_flash($reseller_mode = false)
	{
		return (!$reseller_mode && is_flash_sell_module_active() && $this->prix_flash > 0 && $this->flash_start < date('Y-m-d H:i:s', time()) && $this->flash_end > date('Y-m-d H:i:s', time()) && is_flash_active_on_site());
	}

	/**
	 * Product::get_all_promotions_percentage()
	 *
	 * @param boolean $reseller_mode
	 * @param integer $user_promotion_percentage
	 * @param boolean $format
	 * @return
	 */
	function get_all_promotions_percentage($reseller_mode = false, $user_promotion_percentage = 0, $format = false)
	{
		$user_promotion_percentage = min($user_promotion_percentage, 100);
		if (!$reseller_mode) {
			if (is_category_promotion_module_active()) {
				$cat = get_category_promotion_by_product($this->id);
			}
			if (!empty($GLOBALS['site_parameters']['global_remise_percent'])) {
				$global_promotion = array('nom' => $GLOBALS['STR_GLOBAL_PROMOTION'], 'promotion_devises' => 0, 'promotion_percent' => vn($GLOBALS['site_parameters']['global_remise_percent']));
			} else {
				$global_promotion = array('nom' => '', 'promotion_devises' => 0, 'promotion_percent' => 0);
			}
			if (empty($cat)) {
				$cat = array('nom' => '', 'promotion_devises' => 0, 'promotion_percent' => 0);
			}
			if (is_marque_promotion_module_active()) {
				$marque = get_marque_promotion_by_product($this->id);
			}
			if (empty($marque)) {
				$marque = array('nom' => '', 'promotion_devises' => 0, 'promotion_percent' => 0);
			}
			// ici on ne veut que pourcentage => en cas de réduction par valeur on prend 0
			// La réduction produit est le max de ce qui est indiqué dans le produit, la marque , la catégorie et la promotion générale
			$rebate_coefficient = 1 - (1 - $user_promotion_percentage / 100) * (1 - min(max($this->promotion, $cat['promotion_percent'], $marque['promotion_percent'], $global_promotion['promotion_percent']), 100) / 100);
			// Si on veut cumuler les réductions par produit, par marque et par catégorie, décommenter la ligne suivante
			// $rebate_coefficient = 1 - (1 - $user_promotion_percentage / 100) * (1 - $this->promotion / 100) * (1 - $cat['promotion_percent'] / 100) * (1 - $marque['promotion_percent'] / 100);
		} else {
			// Si on est revendeur, seule la promotion utilisateur est utilisée
			$rebate_coefficient = 1 - (1 - $user_promotion_percentage / 100);
		}
		$percentage = $rebate_coefficient * 100;
		if ($format) {
			return sprintf("%0.2f", $percentage) . '%';
		} else {
			return $percentage;
		}
	}

	/**
	 * Product::get_ecotax()
	 *
	 * @param boolean $with_taxes
	 * @return
	 */
	function get_ecotax($with_taxes = true)
	{
		if ($with_taxes) {
			return $this->ecotaxe_ttc;
		} else {
			return $this->ecotaxe_ht;
		}
	}

	/**
	 * Product::affiche_prix()
	 *
	 * @param boolean $with_taxes
	 * @param boolean $reseller_mode
	 * @param boolean $return_mode
	 * @param boolean $display_with_measurement
	 * @param integer $item_id
	 * @param boolean $display_ecotax
	 * @param boolean $display_old_price
	 * @param string $table_css_class
	 * @param boolean $display_old_price_inline
	 * @return
	 */
	function affiche_prix($with_taxes = true, $reseller_mode = false, $return_mode = false, $display_with_measurement = false, $item_id = null, $display_ecotax = true, $display_old_price = true, $table_css_class = 'full_expand_in_container', $display_old_price_inline = true, $add_rdfa_properties = false)
	{
		$output = affiche_prix($this, $with_taxes, $reseller_mode, $return_mode, $display_with_measurement, $item_id, $display_ecotax, $display_old_price, $table_css_class, $display_old_price_inline, true, $add_rdfa_properties);

		if ($return_mode) {
			return $output;
		} else {
			echo $output;
		}
	}

	/**
	 * Product::format_prices()
	 *
	 * @param float $value_without_taxes
	 * @param boolean $with_taxes
	 * @param integer $ecotax_quantity
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @param boolean $add_rdfa_properties
	 * @return
	 */
	function format_prices($value_without_taxes, $with_taxes = true, $ecotax_quantity = 1, $format = false, $add_tax_type_text = false, $add_rdfa_properties = false)
	{
		if (display_prices_with_taxes_active()) {
			// On doit arrondir les valeurs tarifaires officielles qui sont en TTC
			$value_with_taxes = round($value_without_taxes * (1 + $this->tva / 100), 2);
			$value_without_taxes = $value_with_taxes / (1 + $this->tva / 100);
		} else {
			// On doit arrondir les valeurs tarifaires officielles qui sont en HT
			$value_without_taxes = round($value_without_taxes, 2);
			$value_with_taxes = $value_without_taxes * (1 + $this->tva / 100);
		}
		if (!empty($ecotax_quantity)) {
			$value_without_taxes += $this->get_ecotax(false) * $ecotax_quantity;
			$value_with_taxes += $this->get_ecotax(true) * $ecotax_quantity;
		}
		if ($with_taxes) {
			if (empty($this->vat_applicable)) {
				$value_with_taxes = $value_without_taxes;
			}
			if ($format) {
				$value_with_taxes = fprix($value_with_taxes, true, null, true, null, false, true, ',', $add_rdfa_properties);
				if ($add_tax_type_text) {
					$value_with_taxes .= ' ' . $GLOBALS['STR_TTC'];
				}
			}
			return $value_with_taxes;
		} else {
			if ($format) {
				$value_without_taxes = fprix($value_without_taxes, true, null, true, null, false, true, ',', $add_rdfa_properties);
				if ($add_tax_type_text) {
					$value_without_taxes .= ' ' . $GLOBALS['STR_HT'];
				}
			}
			return $value_without_taxes;
		}
	}

	/**
	 * Retoune le nom du fournisseur connaissant son id
	 *
	 * @return
	 */
	function get_supplier_name()
	{
		$user = get_user_information($this->id_utilisateur);
		if (!empty($user['societe'])) {
			return $user['societe'];
		} else {
			return false;
		}
	}

	/**
	 * Renvoie l'état du stock pour ce produit sous forme de HTML
	 *
	 * @param array $product_stock_infos
	 * @return
	 */
	function get_product_stock_state($product_stock_infos = null)
	{
		if (empty($product_stock_infos)) {
			$product_stock_infos = get_product_stock_infos($this->id);
		}
		$stock_remain_all = 0;
		foreach($product_stock_infos as $stock_infos) {
			$stock_remain_all += $stock_infos['stock_temp'];
		}
		return affiche_etat_stock($stock_remain_all, $this->on_rupture, true);
	}

	/**
	 * Product::get_possible_categories()
	 *
	 * @return
	 */
	function get_possible_categories()
	{
		$categories_array = array();
		$query = query('SELECT pc.categorie_id, c.nom_' . $_SESSION['session_langue'] . '
			FROM peel_produits_categories pc
			INNER JOIN peel_categories c ON c.id = pc.categorie_id
			WHERE pc.produit_id  = "' . intval($this->id) . '"
			ORDER BY c.position ASC, c.nom_' . $_SESSION['session_langue'] . ' ASC');
		while ($result = fetch_assoc($query)) {
			$categories_array[$result['categorie_id']] = $result['nom_' . $_SESSION['session_langue']];
		}
		return $categories_array;
	}

	/**
	 * permet de savoir le nombre d'avis pour le produit
	 *
	 * @return
	 */
	function get_count_opinion()
	{
		$query = query("SELECT COUNT(*) as count_opinion
			FROM peel_avis pa
			WHERE pa.id_produit = '" . intval($this->id) . "' AND etat=1");
		$result = fetch_assoc($query);
		return vn($result['count_opinion']);
	}

	/**
	 * Récupère une image avec le QRCode
	 *
	 * @return
	 */
	function qrcode_image_src()
	{
		return $GLOBALS['wwwroot'].'/qrcode.php?path='.urlencode(str_replace($GLOBALS['wwwroot'], '', $this->get_product_url()));
	}
}

?>