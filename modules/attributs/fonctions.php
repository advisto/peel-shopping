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

/**
 * Chargement des informations produit manquantes si nécessaire
 *
 * @param array $params
 * @return
 */
function attributs_hook_product_init_post(&$params) {
	// On ajoute au prix les attributs à options uniques, puisque ces attributs ne seront pas sélectionnables par ailleurs (car rien à sélectionner).
	if (empty($GLOBALS['site_parameters']['disable_attributs_hook_product_init_post'])) {
		// disable_attributs_hook_product_init_post => dans certain cas spécifique on souhaite gèrer le surcout des options unique comme des attributs normaux, avec la variable configuration_total_original_price_attributs_ht donc on désactive ce morceau de code pour éviter une double prise en compte du surcout de l'attribut.
		$price_calculation = affiche_attributs_form_part($params['this'], 'price_calculation', null, null, null, null, null, check_if_module_active('reseller') && is_reseller(), false, false, true);;
		$params['this']->prix_ht += vn($GLOBALS['last_calculation_additional_price_ht']);
	}
}

/**
 * Définition des informations de configuration d'un produit
 *
 * @param array $params
 * @return
 */
function attributs_hook_product_set_configuration(&$params) {
	if ($params['this']->configuration_attributs_list !== $params['attributs_list']) {
		// Initialisation
		$params['this']->configuration_attributs_list = $params['attributs_list'];
		$params['this']->configuration_total_original_price_attributs_ht = 0;
		$params['this']->configuration_total_original_price_attributs_ht_without_reduction = 0;
		$params['this']->configuration_attributs_description = "";
		// Traitement des attributs
		if (!empty($params['attributs_list'])) {
			$params['this']->configuration_attributs_description = affiche_attributs_form_part($params['this'], 'selected_text', null, null, null, null, null, $params['reseller_mode']);;
			$params['this']->configuration_total_original_price_attributs_ht = vn($GLOBALS['last_calculation_additional_price_ht']);
			$params['this']->configuration_total_original_price_attributs_ht_without_reduction = vn($GLOBALS['last_calculation_additional_price_ht_without_reduction']);
		}
	}
}

/**
 * Récupère la liste des options liées à un produit
 *
 * @param array $params
 * @return
 */
function attributs_hook_product_get_options(&$params) {
	return get_product_options($params['id_or_technical_code'], $params['lang'], $params['return_mode']);
}


/**
 * Ajout de données pour le formulaire de création ou la mise à jour d'annonce
 *
 * @param array $params
 * @return On renvoie un tableau sous la forme [variable smarty] => [contenu]
 */
function attributs_hook_ad_create_or_update_pre(&$params) {
	// Gestion des attributs
	if (!empty($params['attributs_list'])) {
		$ad_product = 'ad';
		$max_description_length = null;
		$additional_line_price = 0;
		$additional_line_attribut_id = 0;
		$additional_line_size = 50;
		if($max_description_length !== null && StringMb::strlen($params['description_' . $_SESSION['session_langue']]) > $max_description_length) {
			$additionnal_text = StringMb::substr($params['description_' . $_SESSION['session_langue']], $max_description_length);
			$params['description_' . $_SESSION['session_langue']] = StringMb::substr($params['description_' . $_SESSION['session_langue']], 0, $max_description_length);
			$additional_lines = ceil(StringMb::strlen($additionnal_text)/$additional_line_size);
			$GLOBALS['site_parameters']['attribut_decreasing_prices_per_technical_code']['additionnal_text'] = $additional_line_price*$additional_lines;
			$temp = explode('§',$params['attributs_list']);
			$temp[] = $additional_line_attribut_id . '|0|'.$additionnal_text;
			$params['attributs_list'] = implode('§', $temp);
		}
		return $params;
	} else {
		return null;
	}
}

/**
 * Affiche des résultats complémentaires après la création ou la mise à jour d'une annonce
 *
 * @param array $params
 * @return output
 */
function attributs_hook_ad_create_or_update_post(&$params) {
	$output = '';
	if (!empty($params['frm']['attributs_list']) && empty($GLOBALS['site_parameters']['ads_disable_product_attributes'])) {
		$ad_product = 'ad';
		// Gestion des attributs commandables pour l'annonce
		// On met le produit dans le caddie pour que l'utilisateur puisse payer ses attributs
		$product_object = get_ad_product(vb($params['frm']['attributs_list']), $ad_product);
		$journal_attributs_list = get_attribut_list_from_post_data($product_object, $params['frm'], false, false);
		$product_object_internet = get_ad_product(vb($params['frm']['attributs_list']), $ad_product.'_internet');
		$costly_attributs_list_internet = get_attribut_list_from_post_data($product_object_internet, $params['frm'], false, true);
		if(!empty($journal_attributs_list) && !empty($params['frm']['ad_quantity'])) {
			$product_object->set_configuration(0, 0, $journal_attributs_list, check_if_module_active('reseller') && is_reseller(), false);
			$_SESSION['session_caddie']->add_product($product_object, $params['frm']['ad_quantity'], '', null, $params['frm']['ad_id']);
			$_SESSION['session_caddie']->update();
			if (check_if_module_active('cart_popup')) {
				$_SESSION['session_show_caddie_popup'] = true;
			}
		}
		if(!empty($costly_attributs_list_internet)) {
			$product_object_internet->set_configuration(0, 0, $costly_attributs_list_internet, check_if_module_active('reseller') && is_reseller(), false);
			$_SESSION['session_caddie']->add_product($product_object_internet, 1, '', null, $params['frm']['ad_id']);
			$_SESSION['session_caddie']->update();
			if (check_if_module_active('cart_popup')) {
				$_SESSION['session_show_caddie_popup'] = true;
			}
		}
	}
	return $output;
}

/**
 * get_possible_attributs()
 *
 * @param integer $product_id
 * @param string $return_mode Values allowed : 'rough', 'option_name', 'full_name'
 * @param boolean $get_attributes_with_multiple_options_only
 * @param boolean $get_attributes_with_single_options_only
 * @param string $attributs_list
 * @return
 */
function get_possible_attributs($product_id = null, $return_mode = 'rough', $get_attributes_with_multiple_options_only = true, $get_attributes_with_single_options_only = false, $attributs_list = null)
{
	static $possible_attributs;
	$attributs_array = array();
	// $attributs_list permet de sélectionner uniquement certains attributs et leurs options
	// Ceci évite notamment dans certains cas de récupérer des informations inutiles
	if(!empty($attributs_list)) {
		$attributs_list_array = explode('§', $attributs_list);
		foreach($attributs_list_array as $this_attributs_list) {
			$this_attributs_list_array = explode("|", $this_attributs_list);
			if(isset($this_attributs_list_array[1])) {
				// On récupère l'id de l'option sélectionnée si format est attribut_id|option_id, ou le texte si le format est attribut_id|0|texte
				$attribut_and_options_filter_array[$this_attributs_list_array[0]][$this_attributs_list_array[1]] = end($this_attributs_list_array);
				if(empty($this_attributs_list_array[1])) {
					$sql_cond_array[] = 'na.id="'.intval($this_attributs_list_array[0]).'"';
				} else {
					$sql_cond_array[] = '(na.id="'.intval($this_attributs_list_array[0]).'" AND a.id="'.intval($this_attributs_list_array[1]).'")';
				}
			}
		}
	}
	if(empty($sql_cond_array) && ($attributs_list === '' || !empty($attributs_list))) {
		// On veut les attributs possibles correspondant à une liste vide
		$sql_cond_array[] = '0';
	}
	if (!isset($possible_attributs[$product_id . '-' . $_SESSION['session_langue'] . '-' . $attributs_list]) || !empty($GLOBALS['product_possible_attribute_cache_disable'][$product_id])) {
		$possible_attributs[$product_id . '-' . $_SESSION['session_langue'] . '-' . $attributs_list] = array();
		// Les attributs possibles d'un produit (ex : parfum) sont énumérés dans la table peel_nom_attributs
		// Pour chacun des attributs, il y a diverses options possibles qui sont stockées dans peel_attributs
		// Dans le cas d'attributs upload ou texte_libre, aucune option n'est associée => LEFT JOIN peel_attributs et non pas INNER JOIN
		if(!empty($product_id)) {
			// Pour un produit donné, on peut associer les attributs que l'on veut, et également spécifier les options acceptables de ces attributs pour ce produit en particulier
			// Il ne faut donc pas faire de jointure entre peel_attributs et peel_nom_attributs, mais passer par peel_produits_attributs pour faire les deux jointures indépendemment. Il faut dans ce cas prendre l'id de l'attribut dans peel_produits_attributs et pas dans peel_attributs par cohérence, et résoud un problème dans le cas d'attribut fictif (paramètre attribut_fictive_options_functions_by_technical_codes_array)
			$sql_select = 'pa.attribut_id';
			$sql_from_and_where = "FROM peel_produits_attributs pa
				LEFT JOIN peel_attributs a ON a.id = pa.attribut_id AND " . get_filter_site_cond('attributs', 'a') . "
				INNER JOIN peel_nom_attributs na ON na.id = pa.nom_attribut_id AND na.etat = '1' AND " . get_filter_site_cond('nom_attributs', 'na') . "
				WHERE pa.produit_id = '" . intval($product_id) . "'";
		} else {
			$sql_select = 'a.id AS attribut_id';
			$sql_from_and_where = "FROM peel_nom_attributs na
				LEFT JOIN peel_attributs a ON a.id_nom_attribut=na.id AND " . get_filter_site_cond('attributs', 'a') . "
				WHERE na.etat = '1' AND " . get_filter_site_cond('nom_attributs', 'na');
		}
		if(!empty($sql_cond_array)) {
			$sql_from_and_where .= " AND (".implode(' OR ', $sql_cond_array).")";
		}
		$sql = "SELECT ".$sql_select." , na.id AS nom_attribut_id, na.nom_" . $_SESSION['session_langue'] . " AS nom, na.technical_code, na.type_affichage_attribut, na.mandatory, na.texte_libre, na.upload, na.show_description, a.descriptif_" . $_SESSION['session_langue'] . " AS descriptif, a.prix, a.prix_revendeur ".(check_if_module_active('product_references_by_options')?', a.reference':'').", na.disable_reductions
			".$sql_from_and_where."
			ORDER BY IF(a.position IS NULL,9999999,a.position) ASC, a.descriptif_" . $_SESSION['session_langue'] . " ASC, na.nom_" . $_SESSION['session_langue'] . " ASC";
		$query = query($sql);
		while ($result = fetch_assoc($query)) {
			if ($result['type_affichage_attribut'] == 3) {
				// On prend la valeur générale du site
				$result['type_affichage_attribut'] = $GLOBALS['site_parameters']['type_affichage_attribut'];
			}
			$result['descriptif'] = StringMb::str_shorten_words($result['descriptif'], 50, " [...] ", false, false);
			$call_module_hook = call_module_hook('result_possible_attributs', array('result'=>$result, 'produit_id' =>$product_id), 'array');
			if (!empty($call_module_hook['prix'])) {
				$result['prix'] = $call_module_hook['prix'];
			}
			$possible_attributs[$product_id . '-' . $_SESSION['session_langue'] . '-' . $attributs_list][] = $result;
		}
	}
	
	if (!empty($possible_attributs)) {
		foreach($possible_attributs[$product_id . '-' . $_SESSION['session_langue'] . '-' . $attributs_list] as $result) {
			// Si l'attribut n'a pas d'option, $result['attribut_id'] vaut NULL => on applique vn() pour obtenir 0
			$attributs_array[intval($result['nom_attribut_id'])][intval(vn($result['attribut_id']))] = $result;
		}
		foreach ($attributs_array as $this_nom_attribut_id => $this_attribut_values_array) {
			if (!empty($this_attribut_values_array[0]) && count($this_attribut_values_array)==1) {
				if (!empty($GLOBALS['site_parameters']['attribut_fictive_options_functions_by_technical_codes_array']) && !empty($GLOBALS['site_parameters']['attribut_fictive_options_functions_by_technical_codes_array'][$this_attribut_values_array[0]['technical_code']]) && function_exists($GLOBALS['site_parameters']['attribut_fictive_options_functions_by_technical_codes_array'][$this_attribut_values_array[0]['technical_code']])) {
					// DEBUT de gestion d'attributs avec options fictives, n'utilisant pas peel_attributs pour les options mais d'autres sources d'information
					$this_function = $GLOBALS['site_parameters']['attribut_fictive_options_functions_by_technical_codes_array'][$this_attribut_values_array[0]['technical_code']];
					// La fonction doit avoir un seul argument, qui est la liste des options filtrées
					if(!empty($attribut_and_options_filter_array[$this_nom_attribut_id])) {
						// Valeur sélectionnée => format du type texte_libre
						$fictive_options_array = $this_function($attribut_and_options_filter_array[$this_nom_attribut_id]);
					} else {
						// Pas de valeur sélectionnée => format du type liste d'options
						$fictive_options_array = $this_function();
					}
					// Les options fictives peuvent être du texte_libre ou non : 
					// Si c'est texte_libre, c'est une notion de stockage par la suite du texte choisi, sous forme d'id 0
					// Mais dans le tableau des options on fait apparaitre l'id de l'option fictive dans tous les cas 
					// On remplit le contenu de l'attribut pour la liste des options
					foreach($fictive_options_array as $this_id => $this_fictive_options) {
						if (empty($GLOBALS['site_parameters']['attribut_fictive_options_accept_only_if_existing_option']) || empty($product_id) || (!empty($attributs_array[$this_nom_attribut_id][$this_id]['descriptif']) && $attributs_array[$this_nom_attribut_id][$this_id]['descriptif'] == $this_fictive_options )) {
							// Si empty($product_id) on veut tous les attributs possibles pour le produit, sinon on prend ce qui est associé au produit.
							$attributs_array[$this_nom_attribut_id][$this_id] = $attributs_array[$this_nom_attribut_id][0];
							$attributs_array[$this_nom_attribut_id][$this_id]['descriptif'] = $this_fictive_options;
							// L'id de l'attribut dans ce cas est l'id qui est généré par la fonction de attribut_fictive_options_functions_by_technical_codes_array. 
							$attributs_array[$this_nom_attribut_id][$this_id]['attribut_id'] = $this_id;
						}
					}
					if($this_attribut_values_array[0]['type_affichage_attribut'] == 1 && count($attributs_array[$this_nom_attribut_id])>1) {
						unset($attributs_array[$this_nom_attribut_id][0]);
					}
					foreach ($attributs_array[$this_nom_attribut_id] as $this_attribut_id => $this_attribut_values) {
						// Ici on supprime les attributs qui ont un ID à NULL, qu'il faut supprimer de la liste. Les ids NULL sont créées par la requête SQL de sélection d'attribut
						if($this_attribut_values['attribut_id'] === NULL && count($attributs_array[$this_nom_attribut_id])>1) {
							unset($attributs_array[$this_nom_attribut_id][$this_attribut_id]);
						}
					}
					// FIN de gestion d'attributs avec options fictives
				}
				if(!empty($attribut_and_options_filter_array) && !empty($attribut_and_options_filter_array[$this_nom_attribut_id]) && !empty($attribut_and_options_filter_array[$this_nom_attribut_id][key($this_attribut_values_array)])) {
					$attributs_array[$this_nom_attribut_id][key($this_attribut_values_array)]['descriptif'] = $attribut_and_options_filter_array[$this_nom_attribut_id][key($this_attribut_values_array)];
					if(StringMb::strpos($attributs_array[$this_nom_attribut_id][key($this_attribut_values_array)]['technical_code'], 'date') === 0) {
						$attributs_array[$this_nom_attribut_id][key($this_attribut_values_array)]['descriptif'] = get_formatted_date($attributs_array[$this_nom_attribut_id][key($this_attribut_values_array)]['descriptif']);
					}
				}
			}
		}
		if($get_attributes_with_multiple_options_only || $get_attributes_with_single_options_only) {
			// On retire les attributs qui ne respectent pas les conditions : unique ou multiple
			foreach($attributs_array as $this_attribute => $this_options_array) {
				if($get_attributes_with_multiple_options_only && (count($this_options_array)<=1 && $this_options_array[key($this_options_array)]['type_affichage_attribut']!=2)) {
					// Cet attribut n'est pas une checkbox et a strictement moins de 2 valeurs
					if(empty($attributs_list) && key($this_options_array) && empty($this_options_array[key($this_options_array)]['texte_libre']) && empty($this_options_array[key($this_options_array)]['upload'])) {
						// attribut_id est différent de 0 et ce n'est pas un attribut avec options fictives
						// => il s'agit bien d'une option qu'on peut afficher dans la description produit
						unset($attributs_array[$this_attribute]);
					}
				} elseif($get_attributes_with_single_options_only && (count($this_options_array)>1 || $this_options_array[key($this_options_array)]['type_affichage_attribut']==2)) {
					unset($attributs_array[$this_attribute]);
				}
			}
		}
		if ($return_mode == 'option_name' || $return_mode == 'full_name') {
			// On renvoie les noms et non pas les informations plus complètes
			foreach ($attributs_array as $this_nom_attribut_id => $this_attribut_values_array) {
				foreach ($this_attribut_values_array as $this_attribut_id => $this_attribut_infos) {
					$this_name_parts = array();
					if($return_mode == 'full_name') {
						$this_name_parts[] = $this_attribut_infos['name'];
					}
					if(!empty($this_attribut_infos['description'])) {
						$this_name_parts[] = $this_attribut_infos['description'];
					}
					$attributs_array[$this_nom_attribut_id][$this_attribut_id] = implode(' - ', $this_name_parts);
				}
			}
		}
	}
	return $attributs_array;
}

/**
 * affiche_attributs_form_part()
 *
 * @param object $product_object
 * @param string $display_mode
 * @param integer $save_cart_id
 * @param integer $save_suffix_id
 * @param integer $form_id
 * @param array $technical_code_array
 * @param array $excluded_technical_code_array
 * @param boolean $force_reseller_mode
 * @param boolean $get_attributes_with_multiple_options_only
 * @param boolean $filter_using_show_description
 * @param boolean $get_attributes_with_single_options_only
 * @return
 */
function affiche_attributs_form_part(&$product_object, $display_mode = 'table', $save_cart_id = null, $save_suffix_id = null, $form_id = null, $technical_code_array = null, $excluded_technical_code_array = null, $force_reseller_mode = null, $get_attributes_with_multiple_options_only = true, $filter_using_show_description = false, $get_attributes_with_single_options_only = false, $update_last_calculation_additional_price_ht = true, $display_name_attribut = false)
{
	$output = '';
	$GLOBALS['last_calculation_additional_price_ht'] = 0;
	$GLOBALS['last_calculation_additional_price_ht_without_reduction'] = 0;
	// On récupère éventuellement les attributs sauvegardés qui devront être présélectionnés
	$attributs_list_array = explode('§', vb($product_object->configuration_attributs_list));
	foreach($attributs_list_array as $this_attributs_list) {
		$this_attributs_list_array = explode("|", $this_attributs_list);
		// On récupère l'id de l'option sélectionnée si format est attribut_id|option_id, ou le texte si le format est attribut_id|0|texte
		$attribut_preselect_infos[intval($this_attributs_list_array[0])] = end($this_attributs_list_array);
	}
	if (empty($technical_code_array) && $display_mode != 'selected_text' && !empty($GLOBALS['site_parameters']['affiche_attributs_form_part_function_by_product_technical_codes_array']) && !empty($GLOBALS['site_parameters']['affiche_attributs_form_part_function_by_product_technical_codes_array'][$product_object->technical_code]) && function_exists($GLOBALS['site_parameters']['affiche_attributs_form_part_function_by_product_technical_codes_array'][$product_object->technical_code])) {
		// Cas spécifique des formulaires d'attributs générés par des fonctions spécifiques (développements spécifiques pour un site précis)
		$this_function = $GLOBALS['site_parameters']['affiche_attributs_form_part_function_by_product_technical_codes_array'][$product_object->technical_code];
		if(function_exists($this_function)) {
			$output .= $this_function($product_object, $save_cart_id, $save_suffix_id, $form_id);
		}
		return $output;
	}
	// On récupère la liste des attributs qui n'offrent pas juste un choix (auquel cas, ils sont gérés par Product, et ils doivent apparaitre comme partie intégrante de la description d'un produit)
	// L'ajout à la description est par ailleurs gérée dans la classe Product 
	$attributs_array = $product_object->get_possible_attributs('rough', ($display_mode == 'selected_text'), 0, true, false, false, false, $get_attributes_with_multiple_options_only, $get_attributes_with_single_options_only);
	
	if (!empty($attributs_array)) {
		// On affiche la liste des attributs
		foreach ($attributs_array as $this_nom_attribut_id => $this_attribut_values_array) {
			$j = 0;
			$attribut_text = '';
			$input_id = '';
			$input_value = '';
			$input_type = '';
			$input_name = '';
			$input_class = '';
			$input_on_change = '';
			$options = array();
			$max_label_length=0;
			$preselected_value = vb($attribut_preselect_infos[$this_nom_attribut_id]);
			unset($type_affichage_attribut);
			$attribut_additional_price_ttc = 0;
			foreach ($this_attribut_values_array as $this_attribut_id => $this_attribut_infos) {
				if (!empty($technical_code_array) && !in_array($this_attribut_infos['technical_code'], $technical_code_array)) {
					break;
				}
				if (!empty($excluded_technical_code_array) && in_array($this_attribut_infos['technical_code'], $excluded_technical_code_array)) {
					break;
				}
				if($force_reseller_mode!==null) {
					$reseller_mode = $force_reseller_mode;
				} else {
					$reseller_mode = (check_if_module_active('reseller') && is_reseller());
				}
				$show_additionnal_price = true;
				if(!empty($GLOBALS['site_parameters']['attribut_decreasing_prices_per_technical_code']) && !empty($GLOBALS['site_parameters']['attribut_decreasing_prices_per_technical_code'][$this_attribut_infos['technical_code']])) {
					$prices_list_by_elements_count = explode(',',$GLOBALS['site_parameters']['attribut_decreasing_prices_per_technical_code'][$this_attribut_infos['technical_code']]);
					$additional_price_ttc = (isset($prices_list_by_elements_count[$j])?$prices_list_by_elements_count[$j]:$prices_list_by_elements_count[0]) - $attribut_additional_price_ttc;
					$show_additionnal_price = false;
				} elseif ($reseller_mode && $this_attribut_infos['prix_revendeur'] != 0) {
					$additional_price_ttc = vn($this_attribut_infos['prix_revendeur']);
				} else {
					$additional_price_ttc = vn($this_attribut_infos['prix']);
				}
				if ($reseller_mode && isset($GLOBALS['site_parameters']['attribut_prix_revendeur'][$this_nom_attribut_id])) {
					$additional_price_ttc += $GLOBALS['site_parameters']['attribut_prix_revendeur'][$this_nom_attribut_id];
				} elseif(isset($GLOBALS['site_parameters']['attribut_prix'][$this_nom_attribut_id])) {
					$additional_price_ttc += $GLOBALS['site_parameters']['attribut_prix'][$this_nom_attribut_id];
				}
				$attribut_additional_price_ttc += $additional_price_ttc;
				$additional_price_ht = $additional_price_ttc / (1 + $product_object->tva / 100);
				// On garde en mémoire le calcul pour utilisation potentielle après exécution de cette fonction
				if (!empty($update_last_calculation_additional_price_ht) && !in_array($this_attribut_infos['technical_code'], vb($GLOBALS['site_parameters']['attribut_overcost_percent'], array()))) {
					if (empty($this_attribut_infos['disable_reductions'])) {
						// On somme le montant des attributs, qui sera ensuite ajouté au prix du produit. Ce montant attribut+produit sera sujet aux réductions éventuelles.
						$GLOBALS['last_calculation_additional_price_ht'] += $additional_price_ht;
					} else {
						// Ce montant s'appliquera sur le prix du produit APRES l'application des réductions. Cela permet de ne pas appliquer de réductions sur le montant de l'attribut.
						$GLOBALS['last_calculation_additional_price_ht_without_reduction'] += $additional_price_ht;
					}
				}
				if ($additional_price_ttc != 0 && $show_additionnal_price) {
					if (in_array($this_attribut_infos['technical_code'], vb($GLOBALS['site_parameters']['attribut_overcost_percent'], array()))) {
						$price_text = ' + '.$additional_price_ht.'%';
					} else {
						if (empty($this_attribut_infos['disable_reductions'])) {
							$final_additional_price_ht = $additional_price_ht * (1 - $product_object->get_all_promotions_percentage($reseller_mode, get_current_user_promotion_percentage(), false) / 100);
						} else {
							$final_additional_price_ht = $additional_price_ht;
						}
						$price_text = $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . ($additional_price_ttc > 0?'+':'') . $product_object->format_prices($final_additional_price_ht, display_prices_with_taxes_active(), false, true, true);
					}
				} else {
					$price_text = '';
				}
				if($filter_using_show_description && empty($this_attribut_infos['show_description'])) {
					// L'attribut ne doit pas avoir son texte affiché dans la description récapitulative
					$j++;
					continue;
				}
				if ($this_attribut_infos['type_affichage_attribut'] == 3) {
					// L'administrateur choisit la configuration générale du site pour l'affichage de ce paramètre.
					$type_affichage_attribut = $GLOBALS['site_parameters']['type_affichage_attribut'];
				} else {
					$type_affichage_attribut = $this_attribut_infos['type_affichage_attribut'];
				}
				if (!empty($this_attribut_infos['upload']) && empty($this_attribut_id)) {
					// cas des attributs d'upload d'image
					if($display_mode == 'selected_text') {
						$input_value = $preselected_value;
					} else {
						$type_affichage_attribut = 'upload';
						$input_name = 'attribut' . $this_nom_attribut_id . '_upload';
						$input_id = $form_id . '_custom_attribut' . $this_nom_attribut_id;
						unset($_SESSION['apply_crop_after_upload'][$input_name]); // initialisation si plusieurs formulaires différents ont des noms de champs identiques
						if (empty($_SESSION["session_display_popup"][$input_name]) && preg_match('`' . $GLOBALS['site_parameters']['uploaded_images_name_pattern'] . '`' , $preselected_value)) {
							// Si pas déjà image téléchargée en cours et qu'on a passé une image dans attributs_list, on vérifie qu'elle semble avec nom cohérent, et on la prend
							$_SESSION["session_display_popup"][$input_name] = $preselected_value;
						}
						if (!empty($_SESSION["session_display_popup"][$input_name])) {
							// si l'image existe déjà, alors on l'affiche tout simplement (avec la possibilité de la supprimer)
							$attribut_text .= display_option_image($_SESSION["session_display_popup"][$input_name]);
						} else {
							// On ne passe pas de nom d'image dans le formulaire par sécurité
							$input_type = 'file';
							// Charge le javascript fineuploader si c'est l'uploader actif sur le site
							$GLOBALS['allow_fineuploader_on_page'] = true;
							if($this_attribut_infos['technical_code'] == 'crop') {
								// On garde en session l'information que ce champ doit avoir une fonctionnalité de découpage après upload
								$_SESSION['apply_crop_after_upload'][$input_name] = true;
								$GLOBALS['load_cropper'] = true;
							} elseif($this_attribut_infos['technical_code'] == 'cropped') {
								$input_type = 'cropped';
							} 
						}
					}
				} elseif (!empty($this_attribut_infos['texte_libre']) && empty($this_attribut_id)) {
					// Le test sur empty($this_attribut_id) permet de savoir qu'on est dans un texte_libre "normal", sans options fictives qui tirent N valeurs de la base de données
					$type_affichage_attribut = 'texte_libre';
					$input_id = $form_id . '_custom_attribut' . $this_nom_attribut_id;
					$input_name = 'attribut' . $this_nom_attribut_id . '_texte_libre';
					$input_type = 'text';
					$input_value = $preselected_value;
					if(StringMb::strpos($this_attribut_infos['technical_code'], 'date') === 0) {
						$input_class = 'datepicker';
					}
				} else {
					if(!empty($this_attribut_infos['texte_libre'])) {
						// Cas d'options fictives
						$this_value = StringMb::html_entity_decode_if_needed($this_attribut_infos['descriptif']);
						$input_name = 'attribut' . $this_nom_attribut_id . '_texte_libre';
					} else {
						$this_value = $this_nom_attribut_id . '|' . $this_attribut_id;
					}
					if ($type_affichage_attribut == 0) {
						// Affichage sous forme de select
						if(empty($input_name)) {
							$input_name = 'attribut' . $this_nom_attribut_id;
						}
						$input_id = $form_id . '_custom_attribut' . $this_nom_attribut_id;
						$input_type = 'select';
						$options[] = array(
								'value' => $this_value,
								'text' => StringMb::html_entity_decode_if_needed($this_attribut_infos['descriptif']) . $price_text,
								'issel' => in_array($this_value, $attributs_list_array)
							);
					} elseif ($type_affichage_attribut == 1) {
						// Affichage sous forme de boutons radio
						if(empty($input_name)) {
							$input_name = 'attribut' . $this_nom_attribut_id;
						}
						$input_type = 'radio';
						$options[] = array(
								'value' => $this_value,
								'name' => $input_name,
								'id' =>  $form_id . '_custom_attribut' . $this_nom_attribut_id . '-' . $j,
								'issel' => !empty($attributs_list_array) && in_array($this_value, $attributs_list_array),
								'text' => StringMb::html_entity_decode_if_needed($this_attribut_infos['descriptif']) . $price_text,
								'onclick' => $input_on_change.' update_product_price' . $save_suffix_id . '();'
							);
					} elseif ($type_affichage_attribut == 2) {
						// Affichage sous forme de checkbox
						$input_type = 'checkbox';
						$options[] = array(
								'value' => $this_value,
								'name' => 'attribut' . $this_nom_attribut_id . '-' . $j,
								'id' =>  $form_id . '_custom_attribut' . $this_nom_attribut_id . '-' . $j,
								'issel' => !empty($attributs_list_array) && in_array($this_value, $attributs_list_array),
								'text' => StringMb::html_entity_decode_if_needed($this_attribut_infos['descriptif']) . $price_text,
								'onclick' => $input_on_change.' update_product_price' . $save_suffix_id . '();'
							);
					} elseif ($type_affichage_attribut == 4) {
						// Affichage sous forme de lien
						$input_type = 'link';
						$options[] = array(
								'value' => $this_attribut_id,
								'name' => 'custom_attribut[' . $this_nom_attribut_id . ']',
								'text' => StringMb::html_entity_decode_if_needed($this_attribut_infos['descriptif'])
							);
					}
					$max_label_length = max($max_label_length, StringMb::strlen(StringMb::html_entity_decode_if_needed(vb($this_attribut_infos['descriptif']))));
				}
				$j++;
			}
			if(isset($type_affichage_attribut)) {
				$attributes_text_array[] = array(
						'text' => $attribut_text,
						'technical_code' => $this_attribut_infos['technical_code'],
						'name' => StringMb::html_entity_decode_if_needed($this_attribut_infos['nom']).(!empty($this_attribut_infos['mandatory']) && $display_mode != 'selected_text'?' *':''),
						'type_affichage_attribut' => $type_affichage_attribut,
						'input_id' => $input_id,
						'input_name' => $input_name,
						'input_value' => $input_value,
						'input_type' => $input_type,
						'input_class' => $input_class,
						'options' => $options,
						'max_label_length' => $max_label_length,
						'onchange' => (empty($GLOBALS['site_parameters']['ads_disable_product_attributes_price_total'])?$input_on_change . ' update_product_price' . $save_suffix_id . '();':'')
					);
			}
		}
	}
	if(!empty($attributes_text_array)) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/attributs_form_part.tpl');
		$tpl->assign('STR_MODULE_ATTRIBUTS_OPTIONS_ATTRIBUTS', $GLOBALS['STR_MODULE_ATTRIBUTS_OPTIONS_ATTRIBUTS']);
		$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('attributes_text_array', $attributes_text_array);
		$tpl->assign('display_mode', $display_mode);
		$tpl->assign('input_name', $input_name);
		$tpl->assign('input_id', $input_id);
		$tpl->assign('input_type', $input_type);
		$tpl->assign('input_on_change', $input_on_change);
		$tpl->assign('technical_code', $product_object->technical_code);
		$tpl->assign('display_name_attribut', $display_name_attribut);
		$tpl->assign('attribut_first_select_option_is_empty', vb($GLOBALS['site_parameters']['attribut_first_select_option_is_empty']));
		if(empty($GLOBALS['site_parameters']['attribut_display_formated_text'])) {
			// Dans le cas où on veut le résultat en mode texte, il faut retirer les sauts de ligne et tabulations => on applique trim()
			$output .= trim(str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $tpl->fetch()));
		} else {
			// On veut conserver la mise en forme des attributs, pour l'affichage.
			$output .=  $tpl->fetch();
		}
	}
	return $output;
}

/**
 * Formatte l'attribut (de type upload) du produit
 *
 * @param string $str_image : est une chaine de caractère qui peut être au format image directement ou alors peut être un texte qui contient des extraits d'images
 * @param boolean $set : definit si l'on a passé un format d'image (false) ou alors si on a passé du text contenant éventuellement des images (true)
 * @return
 */
function display_option_image($str_image, $set = false)
{
	$output = '';
	if ($set) {
		// si $str_image est un texte contenant éventuellement des images
		$inital_text = $str_image;
		$option_tab = explode("{{", $str_image);
		if (count($option_tab) > 1) {
			// s'il ya au moins une image
			foreach ($option_tab as $str_img) {
				if (($end_str = StringMb::strpos($str_img, "}}")) !== false) {
					$str_img = StringMb::substr($str_img, 0, $end_str);
					$small_option_image = thumbs($str_img, 0, 25, 'fit', null, null, true, true);
					$str_img_new = $GLOBALS['tplEngine']->createTemplate('modules/attributs_option_image.tpl', array(
							'set' => true,
							'href' => get_url_from_uploaded_filename($str_img),
							'src' => $small_option_image,
							'file_type' => get_file_type($str_img),
							'lightbox' => !defined('IN_PEEL_ADMIN')
						))->fetch();
					$str_image = str_replace('{{' . $str_img . '}}', $str_img_new, $str_image);
				}
			}
		}
		$output .= $str_image;
	} else {
		$small_option_image = thumbs($str_image, 0, 25, 'fit', null, null, true, true);
		$output .= $GLOBALS['tplEngine']->createTemplate('modules/attributs_option_image.tpl', array(
				'set' => false,
				'href' => get_url_from_uploaded_filename($str_image),
				'file_type' => get_file_type($str_image),
				'src' => $small_option_image,
				'lightbox' => !defined('IN_PEEL_ADMIN')
			))->fetch();
	}
	return $output;
}

/**
 * Construit les combinaisons possibles d'attributs
 *
 * @param array $attributs_infos_array Informations sur les attributs à traiter (dans l'appel récursif, plus on va profondément, plus on restreint cette liste)
 * @param boolean $option_values_array Résultats temporaires pour récursivité
 * @param boolean $get_agregated_attributs_values
 * @return
 */
function get_all_option_combinations(&$attributs_infos_array, $option_values_array = array(''), $get_agregated_attributs_values = true)
{
	$option_values_array_tmp = $option_values_array;
	if(!empty($attributs_infos_array)) {
		$this_nom_attribut_id = key($attributs_infos_array);
		if($get_agregated_attributs_values) {
			// On veut la liste de toutes les combinaisons possibles attribut_id|attribut_option_id§attribut2_id|attribut_option_id§... sous forme de tableau complet
			// Le nombre de résultats augmente de manière exponentielle avec le nombre d'attributs => Attention !
			$option_values_array_tmp = array();
			foreach ($option_values_array as $option_value) {
				foreach ($attributs_infos_array[$this_nom_attribut_id] as $this_attribut_id => $this_attribut_infos) {
					$option_values_array_tmp[] = (!empty($option_value)? $option_value . '§':'') . $this_nom_attribut_id . '|' . $this_attribut_id;
				}
			}
		} else {
			// On veut juste la liste de toutes les combinaisons simples attribut_id|attribut_option_id sous forme de tableau complet
			// Le nombre de résultats augmente de manière linéaire avec le nombre d'attributs => pas de problème
			foreach ($attributs_infos_array[$this_nom_attribut_id] as $this_attribut_id => $this_attribut_infos) {
				$option_values_array_tmp[] = $this_nom_attribut_id . '|' . $this_attribut_id;
			}
		}
		$next_attributs_infos_array = $attributs_infos_array;
		unset($next_attributs_infos_array[$this_nom_attribut_id]);
	}
	if(!empty($next_attributs_infos_array)) {
		// On appelle récursivement pour construire la liste
		return get_all_option_combinations($attributs_infos_array, $option_values_array_tmp, $attributs_infos_array_keys);
	} else {
		return array_unique($option_values_array_tmp);
	}
}

/**
 * get_option_combination_name_from_code()
 *
 * @param mixed $attributs_infos_array
 * @param mixed $combinaison_option_value
 * @return
 */
function get_option_combination_name_from_value($attributs_infos_array, $combinaison_option_value)
{
	$combinaison_option_name_parts = array();
	$option_value_array_tmp = explode('§', $combinaison_option_value);
	foreach ($option_value_array_tmp as $option_value) {
		$value_array = explode('|', $option_value);
		$combinaison_option_name_parts[] = $attributs_infos_array[$value_array[0]][$value_array[1]]['descriptif'];
	}
	return implode(' - ', $combinaison_option_name_parts);
}

/**
 * build_attr_var_js()
 *
 * @param string $attr_var_name
 * @param int $attributs_infos_array_count
 * @param string $form_id
 * @return
 */
function build_attr_var_js($attr_var_name, $attributs_infos_array, $form_id)
{
	$attributs_infos_array_count = count($attributs_infos_array);
	$output = '' . $attr_var_name . '="";
';
	foreach($attributs_infos_array as $this_nom_attribut_id => $this_attributs_array_infos) {
		$this_attributs_infos = current($this_attributs_array_infos);
		if(empty($this_attributs_infos['attribut_id'])) {
			// Champ texte libre ou upload, sans options à choisir
			continue;
		}
		// type_affichage_attribut vaut 0, 1 ou 2 et jamais 3. En effet 3 est une configuration par produit pour dire : prendre la valeur générale du site, et est déjà remplacé par la vraie valeur retenue qui est <=2.
		if ($this_attributs_infos['type_affichage_attribut'] == 0) {
			// Affichage sous forme de select
			$output .= '
	' . $attr_var_name . '+= "§"+document.getElementById("' . $form_id . '_custom_attribut' . $this_nom_attribut_id . '").options[document.getElementById("' . $form_id . '_custom_attribut' . $this_nom_attribut_id . '").selectedIndex].value;';
		} elseif ($this_attributs_infos['type_affichage_attribut'] == 1) {
			// Affichage sous forme de boutons radio
			$output .= '
	radio = document.getElementById("' . $form_id . '").attribut' . $this_nom_attribut_id . ';
	for (var i=0; radio && i<radio.length;i++) {
		if (radio[i].checked) {
			' . $attr_var_name . '+= "§"+radio[i].value;
			break;
		}
	}';
		} elseif ($this_attributs_infos['type_affichage_attribut'] == 2) {
			// Affichage sous forme de checkbox
			$output .= '
	for (var i=0; document.getElementById("' . $form_id . '_custom_attribut' . $this_nom_attribut_id . '-"+i);i++) {
		checkbox = document.getElementById("' . $form_id . '_custom_attribut' . $this_nom_attribut_id . '-"+i);
		if (checkbox.checked) {
			' . $attr_var_name . '+= "§"+checkbox.value;
		}
	}
';
		}
	}
	return $output;
}

/**
 * Traite les informations relatives aux attributs dans le post d'un formulaire produit
 *
 * @param object $product_object
 * @param array $frm Array with all fields data
 * @param boolean $keep_free_attributs_only
 * @param boolean $keep_costly_attributs_only
 * @return
 */
function get_attribut_list_from_post_data(&$product_object, &$frm, $keep_free_attributs_only = false, $keep_costly_attributs_only = false)
{
	$reseller_mode = check_if_module_active('reseller') && is_reseller();
	$combinaisons_array = array();
	// Les fichiers sont dans $_FILES si upload standard, ou sont déjà sur le serveur et on envoie le chemin (soit dans /cache/ si upload via fineuploader, soit déjà dans le dossier d'upload standard si l'information correspond à une édition de données)
	// Par ailleurs, il est possible aussi que l'image soit data:image/xxx;base64,XXXXXXXX si générée via javascript
	// => tout cela est géré par la fonction upload
	foreach(array_merge(array_keys($frm), array_keys($_FILES)) as $this_key) {
		if(StringMb::strpos($this_key, 'attribut') === 0 && StringMb::strpos($this_key, '_upload') !== false) {
			$frm[$this_key] = upload($this_key, false, 'image', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm[$this_key]));
			if(!empty($_FILES[$this_key]['name']) && $frm[$this_key] === false) {
				// on signale que l'image n'a pas été chargée correctement
				$_SESSION["session_display_popup"][$this_key] = false; 
				// Préparation d'un message d'erreur
				$tpl = $GLOBALS['tplEngine']->createTemplate('image_upload_error_option.tpl');
				$tpl->assign('STR_PICTURE', $GLOBALS['STR_PICTURE']);
				$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
				$tpl->assign('STR_NO_UPLOADED', $GLOBALS['STR_NO_UPLOADED']);
				$tpl->assign('picture_size_extention_error_txt', sprintf($GLOBALS['STR_PICTURE_SIZE_EXTENTION_ERROR'], implode(",", $GLOBALS['site_parameters']['extensions_valides_image']), $GLOBALS['site_parameters']['uploaded_file_max_size'] / 1024));
				$tpl_labels = array();
				foreach ($_SESSION["session_display_popup"] as $label => $error) {
					if ($error === false) {
						$tpl_labels[] = $label;
					}
				}
				$tpl->assign('labels', $tpl_labels);
				$_SESSION["session_display_popup"]["upload_error_text"] = $tpl->fetch();
			} elseif(!empty($frm[$this_key])) {
				// Nom de l'image mise en doubles accolades pour être facilement détecté dans l'administration
				$frm[$this_key] = '{{' . $frm[$this_key] . '}}';
			}
		}
	}
	if(!empty($frm) && is_object($product_object)) {
		// On charge les informations de base de données relative aux attributs choisis par l'utilisateur
		// On va ainsi pouvoir vérifier que ces attributs sont bien possibles, et par ailleurs que ceux obligatoires sont bien remplis
		// get_attributes_with_multiple_options_only => true, parce qu'on ne veut pas retrouver d'attribut unique dans le tableau error_attribut_mandatory, puisque ces attribut sont forcement absent du formulaire d'ajout au panier, il sont juste affiché dans la description du produit. Avec false comme valeur, on a error_attribut_mandatory qui contient des attribut unique, et ça invalide l'ajout au panier.
		$attributs_array = $product_object->get_possible_attributs('rough', false, 0, true, false, false, false, true, false, null);
		if (!empty($attributs_array)) {
			// On affiche la liste des attributs
			foreach ($attributs_array as $this_nom_attribut_id => $this_attribut_values_array) {
				foreach ($this_attribut_values_array as $this_attribut_id => $this_attribut_infos) {
					$attributs_infos = $attributs_array[$this_nom_attribut_id][$this_attribut_id];
					if (intval($attributs_infos['mandatory']) == 1) {
						// Attributs obligatoires : on prépare une liste, et on va retirer tout ce qui est valide ensuite
						// Pour les checkbox et les boutons radio, il n'y aura rien d'envoyé dans le formulaire si pas de sélection utilisateur
						// Il est donc nécessaire de faire la liste exhaustive des attributs obligatoires, indépendamment du formulaire
						$GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id] = $attributs_infos['nom'];
					}
				}
			}
		}
		if(!empty($frm['attributs_list'])){
			$attributs_list_array = explode('§', $frm['attributs_list']);
			foreach($attributs_list_array as  $this_attributs_list) {
				$this_attributs_list_array = explode("|", $this_attributs_list);
				$this_nom_attribut_id = $this_attributs_list_array[0];
				$this_attribut_id = $this_attributs_list_array[1];
				if(isset($attributs_array[$this_nom_attribut_id][$this_attribut_id])) {
					unset($GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id]);
					$attributs_infos = $attributs_array[$this_nom_attribut_id][$this_attribut_id];
					if(!empty($GLOBALS['site_parameters']['attribut_decreasing_prices_per_technical_code']) && !empty($GLOBALS['site_parameters']['attribut_decreasing_prices_per_technical_code'][$attributs_infos['technical_code']])) {
						$costly = true;
					} elseif (($reseller_mode && floatval($attributs_infos["prix_revendeur"]) > 0) || floatval($attributs_infos["prix"]) > 0) {
						$costly = true;
					} else {
						$costly = false;
					}
					if(($keep_free_attributs_only && $costly) || ($keep_costly_attributs_only && !$costly)) {
						continue;
					}
				}
				//if(isset($attributs_array[$this_nom_attribut_id][$this_attribut_id]) || (!empty($attributs_array[$this_nom_attribut_id]) && empty($this_attribut_id))) {
					$combinaisons_array[] = $this_attributs_list;
				//}
			}
		}
		foreach($frm as $this_key => $this_value) {
			 if (StringMb::strpos($this_key, 'attribut_list') !== false) {
				// On transmet déjà la liste des attributs correctement formatés dans le formulaire
				$combinaisons_array[] = $this_value;
			} elseif (StringMb::strpos($this_key, 'attribut') === 0) {
				// On a un attribut
				$temp = explode('_', $this_key);
				$this_nom_attribut_id = intval(StringMb::substr($temp[0], StringMb::strlen('attribut')));
				if(empty($attributs_array[$this_nom_attribut_id])){
					// Attribut invalide pour ce produit (erreur technique, ou bidouille utilisateur de ses données POST)
					continue;
				}
				$attributs_infos = current($attributs_array[$this_nom_attribut_id]);
				// Exclusion des attributs gratuits ou payant suivant les paramètres
				if(!empty($GLOBALS['site_parameters']['attribut_decreasing_prices_per_technical_code']) && !empty($GLOBALS['site_parameters']['attribut_decreasing_prices_per_technical_code'][$attributs_infos['technical_code']])) {
					$costly = true;
				} elseif (($reseller_mode && floatval($attributs_infos["prix_revendeur"]) > 0) || floatval($attributs_infos["prix"]) > 0) {
					$costly = true;
				} else {
					$costly = false;
				}
				if(($keep_free_attributs_only && $costly) || ($keep_costly_attributs_only && !$costly)) {
					continue;
				}
				if (StringMb::strpos($this_key, '_texte_libre') !== false) {
					// attribut au texte libre
					if (!empty($this_value)) {
						// Si cet attribut est obligatoire : c'est OK, pas de problème
						unset($GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id]);
					}
					if(!empty($this_value) && StringMb::strpos($attributs_infos['technical_code'], 'date') === 0) {
						$this_value = get_mysql_date_from_user_input($this_value);
					}
					$combinaisons_array[] = $this_nom_attribut_id . '|0|' . $this_value;
				} elseif (StringMb::strpos($this_key, '_upload') !== false) {
					// attribut des champs file
					if (!empty($_SESSION["session_display_popup"][$this_key])) { 
						// si l'image a été déjà téléchargée
						$combinaisons_array[] = $this_nom_attribut_id . '|0|' . $_SESSION["session_display_popup"][$this_key];
						unset($GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id]);
					} elseif (!empty($this_value)) { 
						// Cas où on vient de télécharger, ou si on vient de la sauvegarde de panier
						$combinaisons_array[] = $this_nom_attribut_id . '|0|' . $this_value;
						// on sauvegarde le nom de l'image en session
						$_SESSION["session_display_popup"][$this_key] = $this_value;
						unset($GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id]);
					}
				} elseif (empty($temp[1]) || is_numeric($temp[1])) {
					// Attribut standard au format 'attributN' ou 'attributN_N' si checkbox
					if (is_array($this_value)) {
						// Tableau d'attributs
						foreach($this_value as $this_combinaison) {
							// On teste la validité des données
							$value_array = explode('|', $this_combinaison);
							if($value_array[0] == $this_nom_attribut_id && !empty($attribut_infos[$this_nom_attribut_id][$value_array[1]])) {
								// L'option existe bien pour cet attribut
								$combinaisons_array[] = $this_combinaison;
								unset($GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id]);
							}
						}
					} elseif(!empty($this_value)) {
						$combinaisons_array[] = $this_value;
						unset($GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id]);
					}
				}
			} elseif($this_key == 'submit_all_value' && function_exists('handle_all_attributs_from_step_form')) {
				// Traitement de l'enregistrement de l'utilisateur, sauvegarde en base de donnée de l'id utilisateur dans un champ attribut. On passe $combinaisons_array à handle_all_attributs_from_step_form pour récupérer des information utile à la création d'utiilisateur
				$combinaisons_array = array_merge($combinaisons_array, handle_all_attributs_from_step_form($frm, $combinaisons_array, $product_object));
			}
		}
	}
	return implode('§', $combinaisons_array);
}

/**
 * Récupère la liste des attributs liés à un produit
 *
 * @param integer $id
 * @param string $lang
 * @param string $return_mode
 * @return
 */
function get_product_options($id_or_technical_code, $lang, $return_mode = 'value') {
	$options_array = array();
	if(is_numeric($id_or_technical_code)) {
		$where = "p.id = '" . intval($id_or_technical_code) . "'";
	} else {
		$where = "p.technical_code = '" . nohtml_real_escape_string($id_or_technical_code) . "'";
	}
	if($return_mode == 'array') {
		$sql = "SELECT a.descriptif_" . $_SESSION['session_langue'] . " AS descriptif, na.nom_" . $_SESSION['session_langue'] . " AS nom
			FROM peel_attributs a
			INNER JOIN peel_produits_attributs pa ON pa.attribut_id=a.id
			INNER JOIN peel_nom_attributs na ON na.id=pa.nom_attribut_id AND " . get_filter_site_cond('nom_attributs', 'na') . "
			INNER JOIN peel_produits p ON pa.produit_id = p.id AND " . get_filter_site_cond('produits', 'p') . "
			WHERE " . $where . " AND " . get_filter_site_cond('attributs', 'a') . "
			ORDER BY na.technical_code";
	} else {
		$sql = "SELECT a.descriptif_" . $lang . "
			FROM peel_attributs a
			INNER JOIN peel_produits_attributs pa ON pa.attribut_id = a.id
			WHERE " . $where . " AND " . get_filter_site_cond('attributs', 'a');
	}
	$query = query($sql);
	while ($result = fetch_assoc($query)) {
		if($return_mode == 'array') {
			$options_array[] = $result;
		} else {
			$options_array[] = $result['descriptif_' . $this->lang];
		}
	}
	return $options_array;
}

/**
 * Insertion d'une liste d'attributs en base de données pour un produit donné
 *
 * @param string $this_field_name
 * @param string $this_field_value
 * @param integer $product_id
 * @param integer $site_id
 * @param boolean $admin_mode
 * @return
 */
function attributes_create_or_update($this_field_name, $this_field_value, $product_id, $site_id, $admin_mode = false) {
	// Pour chaque attribut, on sépare le nom de l'ID
	$nom_attrib = explode('#', $this_field_name);
	$q = query('SELECT id
		FROM peel_nom_attributs
		WHERE id=' . intval($nom_attrib[1]) . " AND " . get_filter_site_cond('nom_attributs'));
	if(!empty($nom_attrib[1])) {
		// attribut existant
		if ($att = fetch_assoc($q)) {
			$nom_attrib[1] = $att['id'];
		} else {
			// Attribut inexistant, on l'insère en base de données.
			$q = query("INSERT INTO peel_nom_attributs
				SET site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($site_id)) . "', id=" . intval($nom_attrib[1]) . ", nom_" . $_SESSION['session_langue'] . "='" . nohtml_real_escape_string($nom_attrib[0]) . "', etat='1'");
			$nom_attrib[1] = insert_id();
			if($admin_mode) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_ATTRIBUTE_CREATED'], $nom_attrib[0], $nom_attrib[1])))->fetch();
			}
		}
		// Pour chaque attribut
		if (!empty($this_field_value)) {
			// On récupère toutes les options de cet attribut
			$id_options = explode(',', $this_field_value);
			// Pour chaque option de cet attribut
			foreach($id_options as $id_o) {
				// On sépare l'ID du nom
				$desc_option = explode('#', $id_o);
				if(!isset($desc_option[1])) {
					continue;
				}
				unset($attribute_ids);
				$sql = 'SELECT id, id_nom_attribut
					FROM peel_attributs
					WHERE id_nom_attribut="' . intval($nom_attrib[1]) . '"';
				if(!empty($desc_option[0])) {
					// Si on a spécifié l'id d'attribut, on ne prend que celui-là. 
					$sql .= ' AND id="' . intval($desc_option[0]) . '"';
				} elseif(!empty($desc_option[1])) {
					// Si on a spécifié le nom d'attribut, on ne prend que celui-là.
					$sql .= ' AND descriptif_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($desc_option[1]) . '"';
				}
				$q = query($sql);
				// Option existante
				while ($attribut = fetch_assoc($q)) {
					$attribute_ids[] = $attribut['id'];
				}
				if(empty($attribute_ids)) {
					// Option inexistante et différente d'upload ou de texte libre, on l'insère en base de données sinon on modifie l'attribut.
					if ($desc_option[1] == '__upload') {
						$q = query('UPDATE peel_nom_attributs
							SET upload=1
							WHERE id="' . intval($nom_attrib[1]) . '" AND ' . get_filter_site_cond('nom_attributs'));
						$attribute_ids[] = $desc_option[0];
					} elseif ($desc_option[1] == '__texte_libre') {
						$q = query('UPDATE peel_nom_attributs
							SET texte_libre=1
							WHERE id="' . intval($nom_attrib[1]) . '" AND ' . get_filter_site_cond('nom_attributs'));
						$attribute_ids[] = $desc_option[0];
					} else {
						$q = query('INSERT INTO peel_attributs
							SET id="' . intval($desc_option[0]) . '"
							, id_nom_attribut="' . intval($nom_attrib[1]) . '"
							, site_id="' . nohtml_real_escape_string(get_site_id_sql_set_value($site_id)) . '"
							, descriptif_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($desc_option[1]) . '"
							, mandatory=1', false, null, true);
						$this_id = insert_id();
						if(empty($this_id)) {
							// On change l'id si déjà prise en BDD
							// C'est un choix plutôt que d'effacer les attributs déjà existants
							$q = query('INSERT INTO peel_attributs
								SET id_nom_attribut="' . intval($nom_attrib[1]) . '"
								, site_id="' . nohtml_real_escape_string(get_site_id_sql_set_value($site_id)) . '"
								, descriptif_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($desc_option[1]) . '"
								, mandatory=1', false, null, true);
							$this_id = insert_id();
						}
						$attribute_ids[] = $this_id;
						if($admin_mode) {
							$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_OPTION_CREATED'], $desc_option[1], $this_id)))->fetch();
						}
					}
				}
				foreach($attribute_ids as $this_attribute_id) {
					// Vérification que l'association entre les attributs, les options d'attributs et les produits existe, sinon, on l'ajoute
					$q = query('SELECT produit_id
						FROM peel_produits_attributs
						WHERE produit_id="' . intval($product_id) . '"
							AND nom_attribut_id="' . intval($nom_attrib[1]) . '"
							AND attribut_id="' . intval($this_attribute_id) . '"');
					if (!num_rows($q)) {
						query('INSERT INTO peel_produits_attributs
							SET produit_id="' . intval($product_id) . '",
								nom_attribut_id="' . intval($nom_attrib[1]) . '",
								attribut_id="' . intval($this_attribute_id) . '"');
					}
				}
			}
		}
	}
	return $output;
}
