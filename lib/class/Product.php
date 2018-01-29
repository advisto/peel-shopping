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
// $Id: Product.php 55709 2018-01-11 15:36:30Z sdelaporte $
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
 * @version $Id: Product.php 55709 2018-01-11 15:36:30Z sdelaporte $
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
	var $reference_fournisseur = null;
	var $ean_code = null;
	var $etat = null;
	var $on_estimate = null;
	// prix_revendeur is the price for resellers : it is stored with taxes included, even if we will usually display it without taxes
	var $prix_revendeur = null;
	var $promotion = null;
	var $prix_promo = null;
	var $on_promo = null;
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
	var $image11 = null;
	var $image12 = null;
	var $image13 = null;
	var $image14 = null;
	var $image15 = null;
	var $image16 = null;
	var $image17 = null;
	var $image18 = null;
	var $image19 = null;
	var $image20 = null;
	var $image21 = null;
	var $image22 = null;
	var $image23 = null;
	var $image24 = null;
	var $image25 = null;
	var $image26 = null;
	var $image27 = null;
	var $image28 = null;
	var $image29 = null;
	var $image30 = null;
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
	var $on_reseller = null;
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
	var $configuration_total_original_price_attributs_ht_without_reduction = 0;
	var $configuration_attributs_description = "";
	var $configuration_size_name = "";
	var $configuration_color_name = "";
	var $configuration_overweight = null;
	var $configuration_size_price_ht = 0;
	var $configuration_color_price_ht = 0;
	// Id du fournisseur
	var $id_utilisateur = null;
	var $vat_applicable = null;
	var $extra_link = null;
	var $unit_per_pallet = null;
	var $conditionnement = null;
	var $categorie_sentence_displayed_on_product = null;
	var $date_maj = null;
	var $attributes_with_single_options_array = null;
	var $paiement = null;
	var $site_id = null;
	var $zone_id = null;
	var $user_id = null;
	var $lien = null;
	var $cat_weight = null;
	var $allow_add_product_with_no_stock_in_cart = null;
	var $on_new = null;
	var $conditioning_text = null;

	/**
	 * Product::Product()
	 *
	 * @param integer $id Id ou code technique (si code technique et qu'il n'est pas unique en BDD, on prend le premier produit trouvé avec ce code)
	 * @param array $product_infos
	 * @param boolean $user_only_product_infos
	 * @param string $lang
	 * @param boolean $show_all_etat_if_admin
	 * @param boolean $vat_applicable
	 * @param boolean $show_all
	 * @param boolean $skip_additional_data
	 * @param integer $user_id
	 */
	function __construct($id, $product_infos = null, $user_only_product_infos = false, $lang = null, $show_all_etat_if_admin = true, $vat_applicable = true, $show_all = false, $skip_additional_data = false, $user_id = null)
	{
		static $product_infos_sql;
		if (empty($lang)) {
			$lang = $_SESSION['session_langue'];
		}
		if (is_object($product_infos)) {
			$product_infos = get_object_vars($product_infos);
		}
		$this->lang = $lang;
		$lang_items = array('name' => 'nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$lang), 'descriptif' => 'descriptif_' . $lang, 'description' => 'description_' . (!empty($GLOBALS['site_parameters']['product_description_forced_lang'])?$GLOBALS['site_parameters']['product_description_forced_lang']:$lang), 'meta_titre' => 'meta_titre_' . $lang, 'meta_desc' => 'meta_desc_' . $lang, 'meta_key' => 'meta_key_' . $lang);
		if (empty($id)) {
			$this->id = vn($product_infos['id']);
		} else {
			if(!is_numeric($id)) {
				$sql = "SELECT p.id
					FROM peel_produits p
					WHERE p.technical_code = '" . real_escape_string($id) . "' AND " . get_filter_site_cond('produits', 'p') . "
					LIMIT 1";
				$query = query($sql);
				if($result = fetch_assoc($query)) {
					$this->id = $result['id'];
				} else {
					$this->id = false;
				}
			} else {
				$this->id = $id;
			}
		}
		if (!empty($product_infos)) {
			// Faster than making an SQL request if we have data already available
			foreach(array_keys(get_object_vars($this)) as $this_item) {
				if (isset($product_infos[$this_item]) && !in_array($this_item, array('id', 'lang'))) {
					$this->$this_item = $product_infos[$this_item];
				} elseif (!empty($lang_items[$this_item]) && isset($product_infos[$lang_items[$this_item]])) {
					$this->$this_item = $product_infos[$lang_items[$this_item]];
				}
			}
		}
		if (!$user_only_product_infos) {
			if(empty($GLOBALS['site_parameters']['use_ads_as_products'])) {
				$product_fields = array('p.id', 'p.technical_code', 'p.reference', 'p.ean_code', 'p.nom_'.(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$lang).' AS name', 'p.descriptif_' . $lang . ' AS descriptif', 'p.description_' . (!empty($GLOBALS['site_parameters']['product_description_forced_lang'])?$GLOBALS['site_parameters']['product_description_forced_lang']:$lang) . ' AS description', 'p.meta_titre_' . $lang . ' AS meta_titre', 'p.meta_desc_' . $lang . ' AS meta_desc', 'p.meta_key_' . $lang . ' AS meta_key', 'p.on_estimate', 'p.prix', 'p.prix_achat', 'p.prix_revendeur', 'p.tva', 'p.etat', 'p.prix_promo', 'p.promotion', 'p.points', 'p.default_image', 'p.image1', 'p.image2', 'p.image3', 'p.image4', 'p.image5', 'p.image6', 'p.image7', 'p.image8', 'p.image9', 'p.image10', 'p.zip', 'p.id_utilisateur', 'p.youtube_code', 'p.on_stock', 'p.comments', 'p.delai_stock', 'p.etat_stock', 'p.affiche_stock', 'p.on_special', 'p.on_gift', 'p.on_gift_points', 'p.on_rupture', 'p.on_flash', 'p.flash_start', 'p.flash_end', 'p.prix_flash', 'p.extrait', 'p.on_download', 'p.on_check', 'p.on_reseller', 'p.id_marque', 'p.default_color_id', 'p.display_price_by_weight', 'p.id_ecotaxe', 'p.display_tab', 'p.poids', 'p.volume', 'p.position', 'p.extra_link', 'p.paiement', 'p.site_id');
				if (!empty($GLOBALS['site_parameters']['products_table_additionnal_fields'])) {
					$product_fields = array_merge_recursive_distinct($product_fields, array_keys($GLOBALS['site_parameters']['products_table_additionnal_fields'])) ;
				}
				$product_fields = get_table_field_names('peel_produits', null, false, $product_fields);
				$sql = "SELECT
						" . implode(', ', $product_fields) . "
						, p.allow_add_product_with_no_stock_in_cart
						, IF(c.id IS NOT NULL, c.id, 0) AS categorie_id
						, IF(c.nom_" . $lang . " IS NOT NULL, c.nom_" . $lang . ", 0) AS categorie";
				if (check_if_module_active('conditionnement')) {
					$sql .= ", p.unit_per_pallet";
					$sql .= ", p.conditionnement";
					$sql .= ", p.conditioning_text";
				}
				if (!empty($GLOBALS['site_parameters']['enable_categorie_sentence_displayed_on_product'])) {
					$sql .= ", c.sentence_displayed_on_product_" . $lang . " AS categorie_sentence_displayed_on_product";
				}
                if (!empty($GLOBALS['site_parameters']['categorie_weight_enable'])) {
                    $sql .= ", c.poids AS cat_weight";
                }
				// Les chèques cadeaux n'ont pas de catégorie associée, donc il faut modifier la requête SQL de cette classe en conséquence pour ne pas faire de jointure INNER sur les catégories (même effet que la variable global allow_products_without_category)
				$sql .= " FROM peel_produits p
					" . (!empty($GLOBALS['site_parameters']['allow_products_without_category']) || $this->on_check == 1 ? 'LEFT' : 'INNER') . " JOIN peel_produits_categories pc ON pc.produit_id=p.id
					" . (!empty($GLOBALS['site_parameters']['allow_products_without_category']) || $this->on_check == 1 ? 'LEFT' : 'INNER') . " JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
					WHERE p.id = '" . intval($this->id) . "' AND " . get_filter_site_cond('produits', 'p') . " " . (empty($show_all)?($show_all_etat_if_admin && a_priv("admin_products", false)?'AND p.etat IN ("1","0")':'AND p.etat = "1"') :'') . "
					LIMIT 1";
				// Le limit 1 est nécessaire car le produit peut être associé à plusieurs catégories => on ne récupère que la première catégorie trouvée
				if(empty($product_infos_sql[md5($sql)])) {
					$query = query($sql);
					$product_infos_sql[md5($sql)] = fetch_assoc($query);
				}
				$product_infos = $product_infos_sql[md5($sql)];
			} else {
				$ad_object = new Annonce($this->id);
				$product_infos = $ad_object->get_product_infos_object();
				unset($ad_object);
			}
			if (!empty($product_infos)) {
				foreach($product_infos as $this_item => $this_value) {
					if ((!empty($GLOBALS['site_parameters']['products_table_additionnal_fields']) && !isset($this->$this_item)) || @$this->$this_item === null) {
						// Si la valeur est null (tel que défini au début de la classe) ou n'existe pas (dans le cas de l'utilisation du paramètre products_table_additionnal_fields) 
						$this->$this_item = $this_value;
					}
				}
			} else {
				// If the product does not exist, its id is put to 0 even if $id is not 0
				$this->id = 0;
			}
		}
		// Initialisation de variables non présentes dans peel_produits
		// L'écotaxe est gérée par l'appel au hook "product_init_post" si le module est présent
		$this->ecotaxe_ht = 0;
		$this->ecotaxe_ttc = 0;
		if (!empty($GLOBALS['site_parameters']['specific_categorie_used_for_product_array'])) {
			// Cette variable de configuration permet de spécifier une catégorie différente de l'association en back office. Cela permet par exemple de choisir la catégorie avec laquelle l'url du produit sera générée, dans le cas où plusieurs catégories sont associées au produit.
			// Il est possible de créer une règle qui s'applique à tous les produits en définissant le paramètre comme ceci : '*'=>'id_de_categorie'
			// On peut aussi définir une règle différente produit par produit : 'id_de_produit1'=>'id_de_categorie1','id_de_produit2'=>'id_de_categorie2','id_de_produit3'=>'id_de_categorie3'
			// faire un mélange des deux, la priorité est faite sur la configuration spécifique à un produit : '*'=>'id_de_categorie','id_de_produit1'=>'id_de_categorie1', 'id_de_produit2'=>'id_de_categorie2'
			// Cette configuration est incompatible avec allow_multiplie_product_url_with_categorie === true.
			if (!empty($GLOBALS['site_parameters']['specific_categorie_used_for_product_array'][$this->id])) {
				// une règle est définie pour ce produit, il faut spécifier la catégorie choisie
				$this->categorie_id = $GLOBALS['site_parameters']['specific_categorie_used_for_product_array'][$this->id];
				$this->categorie = get_category_name($GLOBALS['site_parameters']['specific_categorie_used_for_product_array'][$this->id]);
			} elseif(!empty($GLOBALS['site_parameters']['specific_categorie_used_for_product_array']['*'])) {
				// une règle générale s'applique
				$this->categorie_id = $GLOBALS['site_parameters']['specific_categorie_used_for_product_array']['*'];
				$this->categorie = get_category_name($GLOBALS['site_parameters']['specific_categorie_used_for_product_array']['*']);
			}
		}
		$this->name = StringMb::html_entity_decode_if_needed($this->name);
		if(!$skip_additional_data) {
			$extra_description = '';
			$this->descriptif = StringMb::html_entity_decode_if_needed($this->descriptif);
			if(function_exists('get_extra_product_description')) {
				$extra_description = get_extra_product_description($this);
			}
			$possible_attributes_with_single_options = $this->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true, true, false, true);
			foreach($possible_attributes_with_single_options as $this_nom_attribut_id => $this_options_array) {
				foreach($this_options_array as $this_attribut_id => $this_options_infos) {
					if($this_attribut_id && empty($this_options_infos['texte_libre']) && empty($this_options_infos['upload'])) {
						// Ceci n'est pas un attribut texte ou upload
						if (empty($GLOBALS['site_parameters']['disable_display_attributes_with_single_options_on_product_description'])) {
							$extra_description .= $this_options_infos['nom'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $this_options_infos['descriptif'] . '<br />';
						}
						$this->attributes_with_single_options_array[$this_options_infos['technical_code']] = array('nom'=>$this_options_infos['nom'],'descriptif'=>$this_options_infos['descriptif']);
					}
				}
			}
			if (empty($GLOBALS['site_parameters']['display_extra_product_description_mode']) || $GLOBALS['site_parameters']['display_extra_product_description_mode']=='after') {
				$this->description = StringMb::html_entity_decode_if_needed($this->description) .'<br />'. $extra_description;
			} elseif ($GLOBALS['site_parameters']['display_extra_product_description_mode']=='before') {
				$this->description = $extra_description .'<br />'. StringMb::html_entity_decode_if_needed($this->description);
			}
			correct_output($this->descriptif, true, 'html', $lang);
			correct_output($this->description, true, 'html', $lang);
			// On ajoute à la description les attributs à options uniques, puisque ces attributs ne seront pas sélectionnables par ailleurs (car rien à sélectionner)
			if(empty($this->descriptif) && !empty($GLOBALS['site_parameters']['product_short_description_generate_if_empty'])) {
				$this->descriptif = StringMb::str_shorten(StringMb::strip_tags($this->description), 500);
			}
		}
		$this->categorie = StringMb::html_entity_decode_if_needed(vb($this->categorie));
		if (!empty($this->cat_weight) && $this->cat_weight >0 ) {
			// un poids est rempli pour la catégorie du produit. On prend ce poids car il est prioritaire sur le poids du produit;
			$this->poids = floatval($this->cat_weight);
		} else {
			$this->poids = floatval($this->poids);
		}
		$this->volume = floatval($this->volume);
 		$this->prix_ht = $this->prix / (1 + $this->tva / 100);
		$this->user_id = $user_id;
		// On exécute des fonctions de modules qui permettent de compléter le prix, de calculer certaines propriétés de l'objet, ...
		
		call_module_hook('product_init_post', array('this' => $this, 'user_only_product_infos' => $user_only_product_infos, 'product_infos' => $product_infos, 'show_all_etat_if_admin' => $show_all_etat_if_admin));
	
 		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			// Après clacul du prix_ht, et avant nouveau calcul du prix TTC :
			// Des taux de TVA peuvent varier suivant les pays => on vérifie que le taux de la table peel_produit est disponible pour le site et le pays en question, et si pas trouvé on prend le plus faible taux de tva disponible, et sinon on prend 0
			// Ce contournement fonctionne correctement uniquement si un seul taux de TVA est utilisé sur le site. Sinon, il faudrait faire une gestion plus complexe dans ce cas et celui de l'utilisation des fonctionnalités multipays liées à site_country_allowed_array
			$query = query("SELECT tva
				FROM peel_tva 
				WHERE " . get_filter_site_cond('tva') . "
				ORDER BY IF(tva='" . $this->tva . "',-1,tva) ASC
				LIMIT 1");
			if ($result = fetch_assoc($query)) {
				$this->tva = $result['tva'];
			} else {
				$this->tva = 0;
			}
		}
		$this->vat_applicable = $vat_applicable;
		if (empty($vat_applicable)) {
			$this->ecotaxe_ttc = $this->ecotaxe_ht;
			$this->prix = $this->prix_ht;
		}else {
			$this->prix = $this->prix_ht * (1 + $this->tva / 100);
		}
		if(!empty($GLOBALS['site_parameters']['price_hide_if_not_loggued']) && (!est_identifie() || (!a_priv('util*') && !a_priv('admin*') && !a_priv('reve*')) || a_priv('*refused') || a_priv('*wait')) && !check_if_module_active('devis')) {
			$this->on_estimate = 1;
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
		if ($this->configuration_size_id !== $size_id) {
			// Size can have an impact on price
			$this->configuration_size_id = $size_id;
			$size_array = $this->get_size('infos', 0, false, $reseller_mode, false, false);
			$this->configuration_size_name = vb($size_array['name']);
			$this->configuration_size_price_ht = vn($size_array['row_original_price']);
			$this->configuration_overweight = vn($size_array['poids']);
		}
		if ($this->configuration_color_id !== $color_id) {
			// Color can have an impact on price
			$this->configuration_color_id = $color_id;
			$color_array = $this->get_color('infos', 0, false, $reseller_mode, false, false);
			$this->configuration_color_name = vb($color_array['name']);
			$this->configuration_color_price_ht = vn($color_array['row_original_price']);
		}
		call_module_hook('product_set_configuration', array('this' => $this, 'attributs_list' => $attributs_list, 'reseller_mode' => $reseller_mode));
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
		if(empty($this->id)) {
			return null;
		} elseif(empty($GLOBALS['site_parameters']['use_ads_as_products'])) {
			if ($this->categorie_id === null || $this->categorie === null) {
				$query = query("SELECT p.nom_".(!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$this->lang)." AS name, pc.categorie_id, r.nom_" . $this->lang . " AS categorie
					FROM peel_produits p
					" . (!empty($GLOBALS['site_parameters']['allow_products_without_category']) || $this->on_check == 1 ? 'LEFT' : 'INNER') . " JOIN peel_produits_categories pc ON p.id = pc.produit_id
					" . (!empty($GLOBALS['site_parameters']['allow_products_without_category']) || $this->on_check == 1 ? 'LEFT' : 'INNER') . " JOIN peel_categories r ON r.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'r') . "
					WHERE p.id ='" . intval($this->id) . "' AND " . get_filter_site_cond('produits', 'p') . "
					LIMIT 1");
				if ($prod = fetch_assoc($query)) {
					$this->categorie_id = $prod['categorie_id'];
					$this->categorie = $prod['categorie'];
					if(empty($this->name)) {
						$this->name = $prod['name'];
					}
				}
			}
			if(!empty($GLOBALS['site_parameters']['product_check_specific_link_column'])) {
				$column = $GLOBALS['site_parameters']['product_check_specific_link_column'];
				$GLOBALS['product_current_specific_link'] = $this->$column;
			}
			if (!empty($this->categorie_id)) {
				return get_product_url($this->id, $this->name, $this->categorie_id, $this->categorie, $add_get_suffixe, $html_encode);
			} else {
				return get_product_url($this->id, $this->name, 0, null, $add_get_suffixe, $html_encode);
			}
		} else {
			$ad_object = new Annonce($this->id);
			$url = $ad_object->get_annonce_url();
			unset($ad_object);
			return $url;
		}
	}

	/**
	 * Product::get_color()
	 *
	 * @param string $return_mode
	 * @param integer $user_promotion_percentage
	 * @param boolean $with_taxes
	 * @param boolean $reseller_mode
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @return
	 */
	function get_color($return_mode = 'name', $user_promotion_percentage = 0, $with_taxes = true, $reseller_mode = false, $format = false, $add_tax_type_text = false)
	{
		$colors_array = $this->get_possible_colors($return_mode, $user_promotion_percentage, $with_taxes, $reseller_mode, $format, $add_tax_type_text);

		if (!empty($colors_array[$this->configuration_color_id])) {
			return $colors_array[$this->configuration_color_id];
		} else {
			return null;
		}
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
		// Utilisation de array_keys car isset($possible_sizes[$this->id . '-' . $this->lang]) renvoie faux si c'est défini mais vaut null.
		if(empty($possible_sizes) || !in_array($this->id . '-' . $this->lang, array_keys($possible_sizes))){
			$possible_sizes[$this->id . '-' . $this->lang] = array();
			$query = query('SELECT t.*, pt.taille_id
				FROM peel_produits_tailles pt
				INNER JOIN peel_tailles t ON t.id=pt.taille_id AND ' .  get_filter_site_cond('tailles', 't') . '
				WHERE pt.produit_id="' . intval($this->id) . '"
				ORDER BY t.position ASC, t.prix ASC, t.nom_' . $this->lang . ' ASC');
			while ($result = fetch_assoc($query)) {
				$possible_sizes[$this->id . '-' . $this->lang][] = $result;
			}
		}
		if (!empty($possible_sizes) && !empty($possible_sizes[$this->id . '-' . $this->lang])) {
			foreach($possible_sizes[$this->id . '-' . $this->lang] as $result) {
				if ($return_mode == 'name') {
					$sizes_array[$result['taille_id']] = $result['nom_' . $this->lang];
				} elseif ($return_mode == 'export') {
					$sizes_array[$result['taille_id']] = $result['nom_' . $this->lang];
					if($result['prix']!=0 || $result['prix_revendeur']!=0) {
						// Ajout d'informations sur le prix si adapté
						$sizes_array[$result['taille_id']] .= '§'.$result['prix'].'§'.$result['prix_revendeur'];
					}
				} else {
					if ($reseller_mode && check_if_module_active('reseller') && is_reseller() && $result["prix_revendeur"] != 0) {
						$original_price = $result["prix_revendeur"] / (1 + $this->tva / 100);
					} else {
						$original_price = $result["prix"] / (1 + $this->tva / 100);
					}
					$final_price = $original_price * (1 - $this->get_all_promotions_percentage($reseller_mode, $user_promotion_percentage) / 100);
					$result['name'] = $result['nom_' . $this->lang];
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
	 * Product::get_possible_colors()
	 *
	 * @param string $return_mode
	 * @param integer $user_promotion_percentage
	 * @param boolean $with_taxes
	 * @param boolean $reseller_mode
	 * @param boolean $format
	 * @param boolean $add_tax_type_text
	 * @return
	 */
	function get_possible_colors($return_mode = 'name', $user_promotion_percentage = 0, $with_taxes = true, $reseller_mode = false, $format = false, $add_tax_type_text = false)
	{
		static $possible_color;
		$color_array = array();
		if (!isset($possible_color[$this->id . '-' . $this->lang])) {
			$possible_color[$this->id . '-' . $this->lang] = array();
			$query = query('SELECT t.*, pc.couleur_id
				FROM peel_produits_couleurs pc
				INNER JOIN peel_couleurs t ON t.id=pc.couleur_id
				WHERE pc.produit_id="' . intval($this->id) . '"
				ORDER BY t.position ASC, t.prix ASC, t.nom_' . $this->lang . ' ASC');
			while ($result = fetch_assoc($query)) {
				$possible_color[$this->id . '-' . $this->lang][] = $result;
			}
		}
		if (!empty($possible_color) && !empty($possible_color[$this->id . '-' . $this->lang])) {
			foreach($possible_color[$this->id . '-' . $this->lang] as $result) {
				if ($return_mode == 'name') {
					$color_array[$result['couleur_id']] = $result['nom_' . $this->lang];
				} else {
					if ($result['percent']<0) {
						// valeur négative, on veux faire une réduction
						$original_price = ($this->prix_ht * (1 - abs($result['percent'])/100)) - $this->prix_ht;
					} elseif ($result['percent']>0) {
						// Valeur positive, on veux majorer le prix.
						$original_price = $this->prix_ht * ($result['percent'] /100);
					} else {
						if ($reseller_mode && $result["prix_revendeur"] != 0) {
							$original_price = $result["prix_revendeur"] / (1 + $this->tva / 100);
						} else {
							$original_price = $result["prix"] / (1 + $this->tva / 100);
						}
					}
					$final_price = $original_price * (1 - $this->get_all_promotions_percentage($reseller_mode, $user_promotion_percentage) / 100);
					$result['name'] = $result['nom_' . $this->lang];
					$result['row_original_price'] = $this->format_prices($original_price, $with_taxes, false, false, false);
					$result['row_final_price'] = $this->format_prices($final_price, $with_taxes, false, false, false);
					$result['final_price_formatted'] = $this->format_prices($final_price, $with_taxes, false, $format, $add_tax_type_text);
					$color_array[$result['couleur_id']] = $result;
				}
			}
		}
		return $color_array;
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
		static $attributs_array;
		if (!check_if_module_active('attributs')) {
			return array();
		}
		$cache_id = md5(serialize(array($return_mode, $get_configuration_results_only, $user_promotion_percentage, $with_taxes, $reseller_mode, $format, $add_tax_type_text, $get_attributes_with_multiple_options_only, $get_attributes_with_single_options_only, $filter_technical_code, $this->id, $this->configuration_attributs_list)));
		// Utilisation de arra_keys car isset($attributs_array[$cache_id]) renvoie faux si c'est défini mais vaut null.
		if(empty($attributs_array) || !in_array($cache_id, array_keys($attributs_array))){
			if(!empty($this->id)) {
				$attributs_array[$cache_id] = get_possible_attributs($this->id, ($return_mode=='infos'?'rough':$return_mode), $get_attributes_with_multiple_options_only, $get_attributes_with_single_options_only, ($get_configuration_results_only?$this->configuration_attributs_list:null));
			} else {
				$attributs_array[$cache_id] = array();
			}
			if (!empty($attributs_array[$cache_id]) && $return_mode == 'infos') {
				foreach ($attributs_array[$cache_id] as $this_nom_attribut_id => $this_attribut_values_array) {
					foreach ($this_attribut_values_array as $this_attribut_id => $result) {
						if(!empty($filter_technical_code) && $result['technical_code'] == $filter_technical_code) {
							continue;
						}
						if ($reseller_mode && check_if_module_active('reseller') && is_reseller() && $result["prix_revendeur"] != 0) {
							$original_price = $result["prix_revendeur"] / (1 + $this->tva / 100);
						} else {
							$original_price = $result["prix"] / (1 + $this->tva / 100);
						}
						if (empty($result['disable_reductions'])) {
							$final_price = $original_price * (1 - $this->get_all_promotions_percentage($reseller_mode, $user_promotion_percentage) / 100);
						} else {
							$final_price = $original_price;
						}
						$result['name'] = $result['nom'];
						$result['reference'] = vb($result['reference']);
						$result['row_original_price'] = $this->format_prices($original_price, $with_taxes, false, false, false);
						$result['row_final_price'] = $this->format_prices($final_price, $with_taxes, false, false, false);
						$result['final_price_formatted'] = $this->format_prices($final_price, $with_taxes, false, $format, $add_tax_type_text);
						$attributs_array[$cache_id][$this_nom_attribut_id][$this_attribut_id] = $result;
					}
				}
			}
		}
		return $attributs_array[$cache_id];
	}
	
	/**
	 * Product::get_product_references()
	 *
	 * @return
	 */
	function get_product_references()
	{
		$references_array = array();
		$sql = 'SELECT ppr.reference_id
			FROM peel_produits_references ppr
			WHERE ppr.produit_id="' . intval($this->id) . '"';
		$query = query($sql);
		while ($result = fetch_assoc($query)) {
			$references_array[] = $result['reference_id'];
		}
		return $references_array;
	}

	/**
	 * Product::get_product_brands()
	 *
	 * @param integer $return_array
	 * @return
	 */
	function get_product_brands($return_array = true)
	{
		static $brands_array;
		$cache_id = $this->id_marque . '_' . vb($this->marque) . '_' . $this->lang;
		if(empty($brands_array) || !in_array($cache_id, array_keys($brands_array))){
			$brands_array[$cache_id] = array();
			$query = query("SELECT pm.nom_" . $this->lang . "
				FROM peel_marques pm
				WHERE pm.id='" . intval($this->id_marque) . "' AND " . get_filter_site_cond('marques', 'pm'));
			while ($result = fetch_assoc($query)) {
				$brands_array[$cache_id][$this->id_marque] = $result['nom_' . $this->lang];
			}
			if(empty($brands_array[$cache_id]) && !empty($this->marque)) {
				$brands_array[$cache_id][] = $this->marque;
			}
		}
		if($return_array) {
			return $brands_array[$cache_id];
		} else {
			return implode(', ', $brands_array[$cache_id]);
		}
	}

	/**
	 * Product::get_product_options()
	 *
	 * @return
	 */
	function get_product_options()
	{
		return call_module_hook('product_get_options', array('id_or_technical_code' => $this->id, 'lang' => $this->lang, 'return_mode' => 'value'), 'array');
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
		static $product_images;
		$cache_id = md5(serialize(array($this->id, $this->configuration_color_id, $this->default_color_id, $display_pdf, $force_id_couleur, $only_return_first_picture)));
		if(empty($product_images) || !in_array($cache_id, array_keys($product_images))){
			if(empty($GLOBALS['site_parameters']['use_ads_as_products'])) {
				if (!empty($force_id_couleur)) {
					$this_color = $force_id_couleur;
				} elseif (!empty($this->configuration_color_id)) {
					$this_color = $this->configuration_color_id;
				} else {
					// Si il n'y a pas de couleur choisie, on sélectionne la couleur par défaut choisie par l'admin
					$this_color = $this->default_color_id;
				}
				if(!empty($this_color) && empty($GLOBALS['site_parameters']['disable_product_colors'])) {
					$sql_condition = ' AND couleur_id="' . intval($this_color) . '"';
					$sql = 'SELECT *
						FROM peel_produits_couleurs
						WHERE produit_id="' . intval($this->id) . '" ' . $sql_condition . '
						LIMIT 1';
					$q = query($sql);
					if ($result = fetch_assoc($q)) {
						// On commence par l'image par défaut pour que ce soit le premier élément du tableau
						if (!empty($result['default_image']) && is_numeric($result['default_image']) && !empty($result['image' . $result['default_image']]) && ($display_pdf || pathinfo($result['image' . $result['default_image']], PATHINFO_EXTENSION) != 'pdf')) {
							$product_images[$cache_id][] = $result['image' . $result['default_image']];
						}
						for($i = 1;$i <= 5;$i++) {
							if (!empty($result['image' . $i]) && $i != $result['default_image'] && (!$only_return_first_picture || empty($product_images[$cache_id])) && ($display_pdf || pathinfo($result['image' . $i], PATHINFO_EXTENSION) != 'pdf')) {
								$product_images[$cache_id][] = $result['image' . $i];
							}
						}
					}
				}
				if($this->default_image === null) {
					// Produit chargé à partir de données transmises de l'extérieur => nécessite de compléter les informations
					$product_fields = array("default_image", "image1", "image2", "image3", "image4", "image5", "image6", "image7", "image8", "image9", "image10");
					if(!empty($GLOBALS['site_parameters']['products_check_existing_fields'])) {
						$product_field_names = get_table_field_names('peel_produits');
						foreach($product_fields as $this_key => $this_field) {
							$temp = explode(' ', $this_field);
							if(!in_array(str_replace('p.', '', $temp[0]), $product_field_names)) {
								unset($product_fields[$this_key]);
							}
						}
					}
					if(!empty($product_fields)) {
						$sql = "SELECT " . implode(', ', $product_fields) . "
							FROM peel_produits
							WHERE id=" . intval($this->id). " AND " . get_filter_site_cond('produits') . "";
						$q = query($sql);
						if ($result = fetch_assoc($q)) {
							foreach($result as $this_item => $this_value) {
								$this->$this_item = $this_value;
							}
						}
					}
				}
				// On commence par l'image par défaut pour que ce soit le premier élément du tableau
				$this_image_item = 'image' . $this->default_image;
				if (!empty($this->default_image) && is_numeric($this->default_image) && !empty($this->$this_image_item) && (!$only_return_first_picture || empty($product_images[$cache_id])) && ($display_pdf || pathinfo($this->$this_image_item, PATHINFO_EXTENSION) != 'pdf')) {
					$product_images[$cache_id][] = $this->$this_image_item;
				}
				for($i = 1;$i <= 10;$i++) {
					$this_image_item = 'image' . $i;
					if (!empty($this->$this_image_item) && $i != $this->default_image && (!$only_return_first_picture || empty($product_images[$cache_id])) && ($display_pdf || pathinfo($this->$this_image_item, PATHINFO_EXTENSION) != 'pdf')) {
						$product_images[$cache_id][] = $this->$this_image_item;
					}
				}
				if (!empty($GLOBALS['site_parameters']['products_table_additionnal_fields'])) {
					foreach($GLOBALS['site_parameters']['products_table_additionnal_fields'] as $this_key => $this_value) {
						if (strpos($this_key, 'image') === 0) {
							// Prise en compte des images complémentaires
							$i++;
							$this_image_item = $this_key;
							if (!empty($this->$this_image_item) && $i != $this->default_image && (!$only_return_first_picture || empty($product_images[$cache_id])) && ($display_pdf || pathinfo($this->$this_image_item, PATHINFO_EXTENSION) != 'pdf')) {
								$product_images[$cache_id][] = $this->$this_image_item;
							}
						}
					}
				}
			} else {
				$ad_object = new Annonce($this->id);
				$product_images[$cache_id][] = $ad_object->get_annonce_picture();
				unset($ad_object);
			}
			if (empty($product_images[$cache_id])) {
				$product_images[$cache_id] = false;
			}
		}
		return $product_images[$cache_id];
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
		if ($reseller_mode && check_if_module_active('reseller') && is_reseller() && $this->prix_revendeur != 0) {
			$price_ht = $this->prix_revendeur / (1 + $this->tva / 100);
		} else {
			$price_ht = $this->prix_ht;
		}
		if ($get_price_for_this_configuration) {
			$price_ht += $this->configuration_size_price_ht;
			$price_ht += $this->configuration_color_price_ht;
			if (check_if_module_active('attributs')) {
				$price_ht += $this->configuration_total_original_price_attributs_ht + $this->configuration_total_original_price_attributs_ht_without_reduction;
			}
		}
		$call_module_hook = call_module_hook('product_get_original_price', array('quantity' => $quantity, 'reseller_mode' => $reseller_mode, 'price_ht' => $price_ht, 'this' => $this), 'min');
		if ($call_module_hook !== null) {
			if (!empty($GLOBALS['site_parameters'][$this->technical_code.'_product_price_from_hook'])) {
				// Le prix du produit est déterminé par la valeur calculée par le hook pour ce produit.
				$price_ht = $call_module_hook;
			} else {
				// un prix a été défini par le hook. Si la valeur est null, c'est qu'aucun hook est appelé par la fonction 	call_module_hook
				$price_ht = min($price_ht, $call_module_hook);
			}
		}
		if(!empty($GLOBALS['site_parameters']['prices_whole_site_rebate_percentage'])) {
			$price_ht = $price_ht * (1 - $GLOBALS['site_parameters']['prices_whole_site_rebate_percentage']/100);
		}
		$price_ht = $price_ht * $quantity;
		if(!empty($GLOBALS['site_parameters']['prices_round_by_currency'])) {
			// Après les hooks product_init_post et product_get_original_price, et les réductions générales du site, qui peuvent éventuellement altérer le prix, on arrondit le prix HT si c'est demandé, en fonction de la devise de l'utilisateur
			$price_ht = round($price_ht * vn($_SESSION['session_devise']['conversion'], 1)) / vn($_SESSION['session_devise']['conversion'], 1);
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
	 * @param boolean $add_rdfa_properties
	 * @param integer $quantity_all_products_in_category
	 * @return
	 */
	function get_final_price($user_promotion_percentage = 0, $with_taxes = true, $reseller_mode = false, $format = false, $add_tax_type_text = false, $quantity = 1, $add_ecotax = true, $get_price_for_this_configuration = true, $add_rdfa_properties = false, $quantity_all_products_in_category = null)
	{
		if($quantity_all_products_in_category === null) {
			$quantity_all_products_in_category = $quantity;
		}
		if ($quantity == 0 || $quantity == '') {
			// Si 0 ou vide, il faut attribuer une valeur par défaut (par défaut 1) sinon cela créer une erreur de division par 0
			$quantity = 1;
		}
		// Choix entre prix revendeur et prix public
		if ($reseller_mode) {
			// The reseller price is never affected by flash prices
			$price_ht = $this->get_original_price(false, true, false, false, false, false, $quantity, false) / $quantity;
		} elseif ($this->is_price_flash($reseller_mode)) {
			$price_ht = $this->prix_flash / (1 + $this->tva / 100);
		} else {
			$price_ht = $this->get_original_price(false, false, false, false, false, false, $quantity, false) / $quantity;
		}
		// Récupération du prix modifié par d'éventuels modules (par exemple module "lot" pour donner le prix réduit pour une quantité donnée)
		$call_module_hook = call_module_hook('product_get_final_price', array('quantity' => $quantity, 'reseller_mode' => $reseller_mode, 'price_ht' => $price_ht, 'this' => $this), 'min');
		if ($call_module_hook !== null) {
			if (!empty($GLOBALS['site_parameters'][$this->technical_code.'_product_price_from_hook'])) {
				// Le prix du produit est déterminé par la valeur calculée par le hook pour ce produit.
				$price_ht = $call_module_hook;
			} else {
				// un prix a été défini par le hook. Si la valeur est null, c'est qu'aucun hook est appelé par la fonction 	call_module_hook
				$price_ht = min($price_ht, $call_module_hook);
			}
		}
		if ($get_price_for_this_configuration) {
			$price_ht += $this->configuration_size_price_ht;
			$price_ht += $this->configuration_color_price_ht;
			if (check_if_module_active('attributs')) {
				$price_ht += $this->configuration_total_original_price_attributs_ht;
			}
			$attribut_overcost_percent = call_module_hook('attribut_overcost_percent', array('product_object'=>$this), 'unique');
		}
		if (!$this->is_price_flash($reseller_mode)) {
			if (!$reseller_mode) {
				// Pour les revendeurs, on n'applique pas d'autre réduction que le pourcentage de réduction explicite pour cet utilisateur
				$promotion_devises = 0;
				if (check_if_module_active('category_promotion')) {
					$cat = get_category_promotion_by_product($this->id, $quantity_all_products_in_category);
					if (!empty($cat) && $cat['promotion_devises'] > 0) {
						// Réduction par marque en valeur et non pas en pourcentage
						$promotion_devises = max($promotion_devises, $cat['promotion_devises']);
					}
				}
				if (!empty($this->id_marque) && check_if_module_active('marques_promotion')) {
					$marque = get_marque_promotion($this->id_marque);
					if (!empty($marque) && $marque['promotion_devises'] > 0) {
						// Réduction par marque en valeur et non pas en pourcentage
						// Si on veut cumuler les réductions par produit, par marque et par catégorie, changer la ligne ci-dessous
						$promotion_devises = max($promotion_devises, $marque['promotion_devises']);
					}
				}
				$get_promotion_by_user_offer_object = $this->get_promotion_by_user_offer($quantity);
				if(!empty($get_promotion_by_user_offer_object) && $get_promotion_by_user_offer_object->prix>0) {
					if (!empty($GLOBALS['site_parameters']['get_offer_minimum_price_users_array']) && est_identifie() && in_array($_SESSION['session_utilisateur']['id_utilisateur'],$GLOBALS['site_parameters']['get_offer_minimum_price_users_array'])) {
						// Si l'utilisateur connecté est dans le tableau get_offer_minimum_price_users_array alors on prend le prix le plus faible parmit le prix initial du produit et le prix de l'offre.
						$price_ht = min($price_ht, $get_promotion_by_user_offer_object->prix);
					} else {
						// Pour le cas général, on prend le prix de l'offre, qu'il soit plus interressant pour l'utilisateur ou non.
						$price_ht = $get_promotion_by_user_offer_object->prix;
					}
				}
				// Application des réductions automatique en fonction de mots clés dans la description ou la référence du produit 
				$promotion_by_product_filter_object = $this->get_promotion_by_product_filter();
				if(!empty($promotion_by_product_filter_object)) {
					$promotion_devises = max($promotion_devises, $promotion_by_product_filter_object->remise_valeur);
				}
				$price_ht = max($price_ht - $promotion_devises / (1 + $this->tva / 100), 0);
			}
			// Application des réductions en pourcentages
			$price_ht = $price_ht * (1 - $this->get_all_promotions_percentage($reseller_mode, $user_promotion_percentage, false, $quantity, $quantity_all_products_in_category) / 100);
			if ($get_price_for_this_configuration && check_if_module_active('attributs')) {
				$price_ht += $this->configuration_total_original_price_attributs_ht_without_reduction;
			}
		} else {
			// Si c'est un prix flash, on n'applique pas les réductions en pourcentage ni en valeur
			// (mais sur les options, les pourcentages seront quand même appliqués - pas gérés ici)
			$price_ht = $price_ht * (1 - $user_promotion_percentage / 100) ;
		}
		if(!empty($GLOBALS['site_parameters']['all_prices_rebate_percentage'])) {
			$price_ht = $price_ht * (1 - $GLOBALS['site_parameters']['all_prices_rebate_percentage']/100);
		}
		$price_ht = $price_ht * $quantity;
		if (!empty($attribut_overcost_percent)) {
			$price_ht = $price_ht * (1 + $attribut_overcost_percent / 100);
		}
		if(!empty($GLOBALS['site_parameters']['prices_round_by_currency'])) {
			// Après les hooks product_init_post et product_get_final_price, et toutes les réductions, qui peuvent éventuellement altérer le prix, on arrondit le prix HT si c'est demandé, en fonction de la devise de l'utilisateur
			// $price_ht = round($price_ht * vn($_SESSION['session_devise']['conversion'], 1)) / vn($_SESSION['session_devise']['conversion'], 1);
		}
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
		return (!$reseller_mode && $this->prix_flash > 0 && $this->flash_start < date('Y-m-d H:i:s', time()) && $this->flash_end > date('Y-m-d H:i:s', time()) && is_flash_active_on_site());
	}

	/**
	 * is_code_promo_applicable()
	 *
	 * @param integer $id_categorie
	 * @param string $product_filter
	 * @param boolean $found_cat
	 * @return
	 */
	function is_code_promo_applicable($id_categorie, $product_filter, &$found_cat, $cat_not_apply_code_promo = null, $promo_code_combinable = null)
	{
		$apply_code_on_this_product = true;
		if (!empty($id_categorie) || !empty($cat_not_apply_code_promo)) {
			$sql = 'SELECT categorie_id
				FROM peel_produits_categories ppc
				WHERE ppc.produit_id = "' . intval($this->id) . '"' .
				(!empty($id_categorie) ? 'AND ppc.categorie_id IN ("' . implode('","', nohtml_real_escape_string(get_category_tree_and_itself($id_categorie, 'sons'))) . '")' : '' ) .
				(!empty($cat_not_apply_code_promo) ? 'AND ppc.categorie_id NOT IN (' . str_replace(' ', '', implode(',', nohtml_real_escape_string(get_category_tree_and_itself($cat_not_apply_code_promo, 'sons')))) . ')' : '' ) .
				'LIMIT 1';
			$q_get_product_cat = query($sql);
			if ($r_get_product_cat = fetch_assoc($q_get_product_cat)) {
				$found_cat = true;
			} else {
				$found_cat = false;
			}
			if(!$found_cat) {
				$apply_code_on_this_product = false;
			}
		}
		if (!empty($product_filter)) {
			$found_product = false;
			foreach(array('description', 'reference') as $this_item) {
				if(StringMb::strpos(StringMb::strtolower($this->$this_item), StringMb::strtolower($product_filter)) !== false) {
					$found_product = true;
					break;
				}
			}
			if(!$found_product) {
				$apply_code_on_this_product = false;
			}
		}
		if (empty($promo_code_combinable) && (($this->get_all_promotions_percentage() > 0) ||  ($this->promotion>0))) {
			// le produit est remisé => on n'applique pas le code promo
			$apply_code_on_this_product = false;
		}
		return $apply_code_on_this_product;
	}

	/**
	 * Récupère une éventuelle réduction définie dans la table code promo, avec nom vide (pour application automatique) et une définition de filtre produit,
	 * qui s'applique automatiquement en fonction du texte dans la référence et la description
	 *
	 * @param boolean $reseller_mode
	 * @return
	 */
	function get_promotion_by_product_filter($reseller_mode = false)
	{
		static $promotion_by_product_id_array;
		$sql = 'SELECT *
				FROM peel_codes_promos cp
				WHERE ' . get_filter_site_cond('codes_promos', 'cp') . ' AND nom="" AND cp.etat = "1" AND ("' . date('Y-m-d', time()) . '" BETWEEN cp.date_debut AND cp.date_fin) AND (' . (!empty($this->description)?'"' . nohtml_real_escape_string(trim(StringMb::substr($this->description,0,1024))) . '" LIKE CONCAT("%", product_filter, "%") OR ':'') . '"' . nohtml_real_escape_string(trim($this->reference)) . '" LIKE CONCAT("%", product_filter, "%")) 
				ORDER BY remise_percent DESC
				LIMIT 1';
		$cache_id = md5($sql);
		if(empty($promotion_by_product_id_array) || !in_array($cache_id, array_keys($promotion_by_product_id_array))){
			$query = query($sql);
			$promotion_by_product_id_array[$cache_id] = fetch_object($query);
		}
		return $promotion_by_product_id_array[$cache_id];
	}

	/**
	 * Product::get_promotion_by_user_offer()
	 *
	 * @param integer $quantity
	 * @return
	 */
	function get_promotion_by_user_offer($quantity = 1)
	{
		static $promotion_by_user_offer_array;
		if(!empty($GLOBALS['site_parameters']['user_offers_table_enable']) && !empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
			$quantity_total = 0;
			$value_total = 0;
			foreach ($_SESSION['session_caddie']->articles as $numero_ligne => $product_id) {
				$product_object = new Product($product_id, null, false, null, true, $_SESSION['session_caddie']->apply_vat);
				if(empty($quantity_by_brand['brand_'.$product_object->get_product_brands(false)]) || $product_object->get_product_brands(false) == '') {
					$quantity_by_brand['brand_'.$product_object->get_product_brands(false)] = 0;
					$total_by_brand['brand_'.$product_object->get_product_brands(false)] = 0;
				}
				$quantity_by_brand['brand_'.$product_object->get_product_brands(false)] += $_SESSION['session_caddie']->quantite[$numero_ligne];
				$total_by_brand['brand_'.$product_object->get_product_brands(false)] += floatval($_SESSION['session_caddie']->quantite[$numero_ligne]*$product_object->prix_ht);
				$quantity_total += $_SESSION['session_caddie']->quantite[$numero_ligne];
				$value_total += floatval($_SESSION['session_caddie']->quantite[$numero_ligne]*$product_object->prix_ht);
				unset($product_object);
			}
			$sql = "SELECT o.*
				FROM peel_offres o
				LEFT JOIN peel_utilisateurs_offres uo ON uo.id_utilisateur='" . intval(vn($_SESSION['session_utilisateur']['id_utilisateur'])) . "' AND o.id_offre=uo.id_offre
				WHERE " . get_filter_site_cond('offres', 'o') . " AND (o.id_offre=0 OR uo.id_offre IS NOT NULL) AND o.date_limite>='" . date('Y-m-d', time()) . "' AND (" . (!empty($this->reference)?"(o.ref='".real_escape_string($this->reference)."' AND o.qnte<='".intval($quantity)."' AND o.seuil<='".floatval(max($quantity*$this->prix_ht,vn($value_total)))."') OR ":"") . "(o.ref='' AND o.fournisseur IN ('".implode("','", real_escape_string($this->get_product_brands(true)))."') AND o.qnte<='".intval(max(vn($quantity_by_brand['brand_'.$this->get_product_brands(false)]), $quantity))."' AND o.seuil<='".floatval(max(vn($total_by_brand['brand_'.$this->get_product_brands(false)]), $quantity*$this->prix_ht, vn($value_total)))."') OR (o.ref='' AND o.fournisseur='' AND o.qnte<='".intval(max(vn($quantity_total), $quantity))."' AND o.seuil<='".floatval(max(vn($value_total), $quantity*$this->prix_ht))."'))
				ORDER BY " . (floatval($this->prix_ht)>0 ? "IF(o.prix>0 AND o.remise_percent>0, LEAST(o.prix, (1-o.remise_percent/100)*'".floatval($this->prix_ht)."'), IF(o.remise_percent>0, (1-o.remise_percent/100)*'".floatval($this->prix_ht)."', o.prix))" : "o.prix") . " ASC, o.remise_percent DESC
				LIMIT 1";
			$cache_id = md5($sql);
			if(empty($promotion_by_user_offer_array) || !in_array($cache_id, array_keys($promotion_by_user_offer_array))){
				$query = query($sql);
				$promotion_by_user_offer_array[$cache_id] = fetch_object($query);
			}
			return $promotion_by_user_offer_array[$cache_id];
		} else {
			return null;
		}
	}

	/**
	 * Product::get_all_promotions_percentage()
	 *
	 * @param boolean $reseller_mode
	 * @param integer $user_promotion_percentage
	 * @param boolean $format
	 * @param integer $quantity
	 * @param integer $quantity_all_products_in_category
	 * @return
	 */
	function get_all_promotions_percentage($reseller_mode = false, $user_promotion_percentage = 0, $format = false, $quantity = 1, $quantity_all_products_in_category = null)
	{
		static $all_promotions_percentage_array;
		if (isset($_SESSION['session_caddie'])) {
			// On utilise isset ici dans le cas où l'on appel la class Product avant l'initialisation de la classe Caddie, ce qui est possible lors de l'appel au module qui se passe avant l'initialisation du panier
			$articles = $_SESSION['session_caddie']->articles;
		}
		$cache_id = md5(serialize(array($this->id, $reseller_mode, $user_promotion_percentage, $format, $quantity, $quantity_all_products_in_category, vb($articles))));
		if(!isset($all_promotions_percentage_array[$cache_id])) {
			if($quantity_all_products_in_category === null) {
				$quantity_all_products_in_category = $quantity;
			}
			$user_promotion_percentage = min($user_promotion_percentage, 100);
			if (!$reseller_mode) {
				// Pour les revendeurs, on n'applique pas d'autre réduction que le pourcentage de réduction explicite pour cet utilisateur
				if (check_if_module_active('category_promotion')) {
					$cat = get_category_promotion_by_product($this->id, $quantity_all_products_in_category);
				}
				if (empty($cat)) {
					$cat = array('nom' => '', 'promotion_devises' => 0, 'promotion_percent' => 0);
				}
				if (isset($_SESSION['session_caddie'])) {
					// On utilise isset ici dans le cas où l'on appel la class Product avant l'initialisation de la classe Caddie, ce qui est possible lors de l'appel au module qui se passe avant l'initialisation du panier
					$global_promotion = $_SESSION['session_caddie']->get_global_promotion();
				}
				if (!empty($this->id_marque) && check_if_module_active('marques_promotion')) {
					$marque = get_marque_promotion($this->id_marque);
				}
				if (empty($marque)) {
					$marque = array('nom' => '', 'promotion_devises' => 0, 'promotion_percent' => 0);
				}
				$get_promotion_by_user_offer_object = $this->get_promotion_by_user_offer($quantity);
				if(!empty($get_promotion_by_user_offer_object)) {
					$promotion_by_user_offer = $get_promotion_by_user_offer_object->remise_percent;
				} else {
					$promotion_by_user_offer = 0;
				}
				// Application des réductions automatique en fonction de mots clés dans la description ou la référence du produit 
				$promotion_by_product_filter_object = $this->get_promotion_by_product_filter();
				if(!empty($promotion_by_product_filter_object)) {
					$promotion_by_product_filter = $promotion_by_product_filter_object->remise_percent;
				} else {
					$promotion_by_product_filter = 0;
				}
				// Calcul du pourcentage de réduction à partir du champ prix_promo. Ne s'applique pas si le prix est défini par les réductions par lot
				if ($this->prix_promo > 0 && $this->prix > 0 && empty($GLOBALS['cache']['lot_price_by_id'][$this->id])) {
					if (!empty($this->vat_applicable)) {
						$prix_promo = $this->prix_promo;
				} else {
						$prix_promo = $this->prix_promo / (1 + $this->tva / 100);
					}
					$prix_promo_percent = ($this->prix - $prix_promo) * 100 / $this->prix;
				} else {
					$prix_promo_percent = 0;
				}
				$prices_whole_site_promotion_percentage = vn($GLOBALS['site_parameters']['prices_whole_site_promotion_percentage']);
				if (!empty($GLOBALS['site_parameters']['product_add_all_percent_discount'])) {
					// Si on veut cumuler les réductions par produit, par marque et par catégorie
					$rebate_coefficient = 1 - (1 - $user_promotion_percentage / 100) * (1 - $this->promotion / 100) * (1 - $cat['promotion_percent'] / 100) * (1 - $marque['promotion_percent'] / 100) * (1 - $global_promotion / 100) * (1 - $promotion_by_product_filter / 100) * (1 - $promotion_by_user_offer / 100) * (1 - $prices_whole_site_promotion_percentage / 100);
				} else {
					// La réduction produit est le max de ce qui est indiqué dans le produit, la marque , la catégorie et la promotion générale
					$rebate_coefficient = 1 - (1 - $user_promotion_percentage / 100) * (1 - min(max($this->promotion, $cat['promotion_percent'], $marque['promotion_percent'], vn($global_promotion), $promotion_by_product_filter, $promotion_by_user_offer, $prix_promo_percent, $prices_whole_site_promotion_percentage), 100) / 100);
				}
			} else {
				// Si on est revendeur, seule la promotion utilisateur est utilisée
				$rebate_coefficient = 1 - (1 - $user_promotion_percentage / 100);
			}
			$all_promotions_percentage_array[$cache_id] = $rebate_coefficient * 100;
		}
		if ($format) {
			return sprintf("%0.2f", $all_promotions_percentage_array[$cache_id]) . '%';
		} else {
			return $all_promotions_percentage_array[$cache_id];
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
	function affiche_prix($with_taxes = true, $reseller_mode = false, $return_mode = false, $display_with_measurement = false, $item_id = null, $display_ecotax = true, $display_old_price = true, $table_css_class = 'full_width', $display_old_price_inline = true, $add_rdfa_properties = false, $force_display_with_vat_symbol = null, $display_minimal_price = null, $quantity = 1)
	{
		$output = affiche_prix($this, $with_taxes, $reseller_mode, $return_mode, $display_with_measurement, $item_id, $display_ecotax, $display_old_price, $table_css_class, $display_old_price_inline, $force_display_with_vat_symbol, $add_rdfa_properties, $display_minimal_price, $quantity);

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
				$value_with_taxes = fprix($value_with_taxes, true, null, true, null, false, true, null, $add_rdfa_properties);
				if ($add_tax_type_text) {
					$value_with_taxes .= ' ' . $GLOBALS['STR_TTC'];
				}
			}
			return $value_with_taxes;
		} else {
			if ($format) {
				$value_without_taxes = fprix($value_without_taxes, true, null, true, null, false, true, null, $add_rdfa_properties);
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
			$product_stock_infos = get_product_stock_infos($this->id, $this->configuration_size_id, $this->configuration_color_id);
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
		$query = query('SELECT pc.categorie_id, c.nom_' . $this->lang . '
			FROM peel_produits_categories pc
			INNER JOIN peel_categories c ON c.id = pc.categorie_id AND ' . get_filter_site_cond('categories', 'c') . '
			WHERE pc.produit_id  = "' . intval($this->id) . '"
			ORDER BY c.position ASC, c.nom_' . $this->lang . ' ASC');
		while ($result = fetch_assoc($query)) {
			$categories_array[$result['categorie_id']] = $result['nom_' . $this->lang];
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
		return $GLOBALS['wwwroot'].'/qrcode.php?path='.urlencode(rawurldecode(str_replace($GLOBALS['wwwroot'], '', $this->get_product_url())));
	}

	/**
	 * Récupère une image avec le code barre au format EAN13
	 *
	 * @return
	 */
	function barcode_image_src()
	{
		if(!empty($this->ean_code)) {
			return $GLOBALS['wwwroot'].'/qrcode.php?barcode='.urlencode($this->ean_code);
		} else {
			return false;
		}
	}
	/**
	 * Retourne le prix d'appel du produit, toutes réductions inclue
	 *
	 * @return
	 */
	function get_minimal_price()
	{
		$price_ht = $this->get_final_price(get_current_user_promotion_percentage(), false, check_if_module_active('reseller') && is_reseller());
		if (check_if_module_active('lot')) {
			// recherche dans les prix par lot
			$minimal_price_array = array();
			$sql = "SELECT MIN(prix) AS prix, MIN(prix_revendeur) AS prix_revendeur
				FROM peel_quantites
				WHERE produit_id = '" . intval($this->id) . "' AND "  . get_filter_site_cond('quantites');
			$query = query($sql);
			if($Qte = fetch_assoc($query)) {
				$price_Qte_ht = (check_if_module_active('reseller') && is_reseller() && $Qte['prix_revendeur'] != 0? $Qte['prix_revendeur'] / (1 + $this->tva / 100) : $Qte['prix'] / (1 + $this->tva / 100));
				if ($price_Qte_ht > 0) {
					$minimal_price_array[] = $price_Qte_ht;
				}
			}
			if ($price_ht > 0) {
				$minimal_price_array[] = $price_ht;
			}
			if(!empty($minimal_price_array)) {
				$price_ht = min ($minimal_price_array);
			} else {
				$price_ht = 0;
			}
		}
		return $this->format_prices($price_ht, display_prices_with_taxes_active(), false, true, true);
	}
}
