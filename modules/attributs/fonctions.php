<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 37904 2013-08-27 21:19:26Z gboussin $
if (!defined('IN_PEEL')) {
	die();
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
	if($attributs_list === '') {
		// On veut les attributs possibles correspondant à une liste vide
		$sql_cond_array[] = '0';
	} else {
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
	if (!isset($possible_attributs[$product_id . '-' . $_SESSION['session_langue']. '-' . $attributs_list])) {
		$possible_attributs[$product_id . '-' . $_SESSION['session_langue']] = array();
		// Les attributs possibles d'un produit (ex : parfum) sont énumérés dans la table peel_nom_attributs
		// Pour chacun des attributs, il y a diverses options possibles qui sont stockées dans peel_attributs
		// Dans le cas d'attributs upload ou texte_libre, aucune option n'est associée => LEFT JOIN peel_attributs et non pas INNER JOIN
		if(!empty($product_id)) {
			// Pour un produit donné, on peut associer les attributs que l'on veut, et également spécifier les options acceptables de ces attributs pour ce produit en particulier
			// Il ne faut donc pas faire de jointure entre peel_attributs et peel_nom_attributs, mais passer par peel_produits_attributs pour faire les deux jointures indépendemment
			$sql_from_and_where = "FROM peel_produits_attributs pa
				LEFT JOIN peel_attributs a ON a.id = pa.attribut_id
				INNER JOIN peel_nom_attributs na ON na.id = pa.nom_attribut_id AND na.etat = '1'
				WHERE pa.produit_id = '" . intval($product_id) . "'";
		} else {
			$sql_from_and_where = "FROM peel_nom_attributs na
				LEFT JOIN peel_attributs a ON a.id_nom_attribut=na.id
				WHERE na.etat = '1'";
		}
		if(!empty($sql_cond_array)) {
			$sql_from_and_where .= " AND (".implode(' OR ', $sql_cond_array).")";
		}
		$sql = "SELECT a.id AS attribut_id, na.id AS nom_attribut_id, na.nom_" . $_SESSION['session_langue'] . " AS nom, na.technical_code, na.type_affichage_attribut, na.mandatory, na.texte_libre, na.upload, na.show_description, a.descriptif_" . $_SESSION['session_langue'] . " AS descriptif, a.prix, a.prix_revendeur
			".$sql_from_and_where."
			ORDER BY IF(a.position IS NULL,9999999,a.position) ASC, a.descriptif_" . $_SESSION['session_langue'] . " ASC, na.nom_" . $_SESSION['session_langue'] . " ASC";
		$query = query($sql);
		while ($result = fetch_assoc($query)) {
			if ($result['type_affichage_attribut'] == 3) {
				// On prend la valeur générale de la boutique
				$result['type_affichage_attribut'] = $GLOBALS['site_parameters']['type_affichage_attribut'];
			}
			$result['descriptif'] = String::str_shorten_words($result['descriptif'], 50, " [...] ", false, false);
			$possible_attributs[$product_id . '-' . $_SESSION['session_langue']][] = $result;
		}
	}
	if (!empty($possible_attributs)) {
		foreach($possible_attributs[$product_id . '-' . $_SESSION['session_langue']] as $result) {
			// Si l'attribut n'a pas d'option, $result['attribut_id'] vaut NULL => on applique vn() pour obtenir 0
			$attributs_array[intval($result['nom_attribut_id'])][intval(vn($result['attribut_id']))] = $result;
		}
		foreach ($attributs_array as $this_nom_attribut_id => $this_attribut_values_array) {
			// DEBUT de gestion d'attributs avec options fictives, n'utilisant pas peel_attributs pour les options mais d'autres sources d'information
			if (!empty($this_attribut_values_array[0]) && count($this_attribut_values_array)==1) {
				if (!empty($GLOBALS['site_parameters']['attribut_fictive_options_functions_by_technical_codes_array']) && !empty($GLOBALS['site_parameters']['attribut_fictive_options_functions_by_technical_codes_array'][$this_attribut_values_array[0]['technical_code']]) && function_exists($GLOBALS['site_parameters']['attribut_fictive_options_functions_by_technical_codes_array'][$this_attribut_values_array[0]['technical_code']])) {
					$this_function = $GLOBALS['site_parameters']['attribut_fictive_options_functions_by_technical_codes_array'][$this_attribut_values_array[0]['technical_code']];
					// La fonction doit avoir un seul argument, qui est la liste des options filtrées
					if(!empty($attribut_and_options_filter_array[$this_nom_attribut_id])) {
						// Valeur sélectionnée => format du type texte_libre
						$fictive_options_array = $this_function($attribut_and_options_filter_array[$this_nom_attribut_id]);
					} else {
						// Pas de valeur sélectionnée => format du type liste d'options
						$fictive_options_array = $this_function();
					}
					// Les options fictives peuvent pas être du texte_libre ou non : 
					// Si c'est texte_libre, c'est une notion de stockage par la suite du texte choisi, sous forme d'id 0
					// Mais dans le tableau des options on fait apparaitre l'id de l'option fictive dans tous les cas 
					// On remplit le contenu de l'attribut pour la liste des options
					foreach($fictive_options_array as $this_id => $this_fictive_options) {
						$attributs_array[$this_nom_attribut_id][$this_id] = $attributs_array[$this_nom_attribut_id][0];
						$attributs_array[$this_nom_attribut_id][$this_id]['descriptif'] = $this_fictive_options;
					}
					unset($attributs_array[$this_nom_attribut_id][0]);
				}
				if(!empty($attribut_and_options_filter_array) && !empty($attribut_and_options_filter_array[$this_nom_attribut_id]) && !empty($attribut_and_options_filter_array[$this_nom_attribut_id][key($this_attribut_values_array)])) {
					$attributs_array[$this_nom_attribut_id][key($this_attribut_values_array)]['descriptif'] = $attribut_and_options_filter_array[$this_nom_attribut_id][key($this_attribut_values_array)];
					if(String::strpos($attributs_array[$this_nom_attribut_id][key($this_attribut_values_array)]['technical_code'], 'date') === 0) {
						$attributs_array[$this_nom_attribut_id][key($this_attribut_values_array)]['descriptif'] = get_formatted_date($attributs_array[$this_nom_attribut_id][key($this_attribut_values_array)]['descriptif']);
					}
				}
			}
			// FIN de gestion d'attributs avec options fictives
		}
		if($get_attributes_with_multiple_options_only || $get_attributes_with_single_options_only) {
			// On retire les attributs qui ne respectent pas les conditions : unique ou multiple
			foreach($attributs_array as $this_attribute => $this_options_array) {
				if($get_attributes_with_multiple_options_only && (count($this_options_array)<=1 && $this_options_array[key($this_options_array)]['type_affichage_attribut']!=2)) {
					// Cet attribut n'est pas une checkbox et a moins de 2 valeurs
					if(empty($attributs_list) && key($this_options_array) && empty($this_options_array[key($this_options_array)]['texte_libre']) && empty($this_options_array[key($this_options_array)]['upload'])) {
						// attribut_id est différent de 0 et ce n'est pas un attribut avec options fictives
						// => il s'agit bien d'une option qu'on peut afficher dans la description produit
						unset($attributs_array[$this_attribute]);
					}
				} elseif($get_attributes_with_single_options_only && count($this_options_array)>1) {
					unset($attributs_array[$this_attribute]);
				}
			}
		}
		if ($return_mode == 'option_name' || $return_mode == 'full_name') {
			// On renvoie les noms et pas les informations plus complètes
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
function affiche_attributs_form_part(&$product_object, $display_mode = 'table', $save_cart_id = null, $save_suffix_id = null, $form_id = null, $technical_code_array = null, $excluded_technical_code_array = null, $force_reseller_mode = null, $get_attributes_with_multiple_options_only = true, $filter_using_show_description = false, $get_attributes_with_single_options_only = false)
{
	$output = '';
	$GLOBALS['last_calculation_additional_price_ht'] = 0;
	// On récupère éventuellement les attributs sauvegardés qui devront être présélectionnés
	$attributs_list_array = explode('§', vb($product_object->configuration_attributs_list));
	foreach($attributs_list_array as $this_attributs_list) {
		$this_attributs_list_array = explode("|", $this_attributs_list);
		// On récupère l'id de l'option sélectionnée si format est attribut_id|option_id, ou le texte si le format est attribut_id|0|texte
		$attribut_preselect_infos[intval($this_attributs_list_array[0])] = end($this_attributs_list_array);
	}
	if ($display_mode != 'selected_text' && !empty($GLOBALS['site_parameters']['affiche_attributs_form_part_function_by_product_technical_codes_array']) && !empty($GLOBALS['site_parameters']['affiche_attributs_form_part_function_by_product_technical_codes_array'][$product_object->technical_code]) && function_exists($GLOBALS['site_parameters']['affiche_attributs_form_part_function_by_product_technical_codes_array'][$product_object->technical_code])) {
		// Cas spécifique des bouteilles
		$this_function = $GLOBALS['site_parameters']['affiche_attributs_form_part_function_by_product_technical_codes_array'][$product_object->technical_code];
		$output .= $this_function($product_object, $save_cart_id, $save_suffix_id, $form_id);
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
					$reseller_mode = (is_reseller_module_active() && is_reseller());
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
				$GLOBALS['last_calculation_additional_price_ht'] += $additional_price_ht;
				if ($additional_price_ttc != 0 && $show_additionnal_price) {
					$final_additional_price_ht = $additional_price_ht * (1 - $product_object->get_all_promotions_percentage($reseller_mode, get_current_user_promotion_percentage(), false) / 100);
					$price_text = $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . ($additional_price_ttc > 0?'+':'') . $product_object->format_prices($final_additional_price_ht, display_prices_with_taxes_active(), false, true, true);
				} else {
					$price_text = '';
				}
				if($filter_using_show_description && empty($this_attribut_infos['show_description'])) {
					// L'attribut ne doit pas avoir son texte affiché dans la description récapitulative
					$j++;
					continue;
				}
				if ($this_attribut_infos['type_affichage_attribut'] == 3) {
					// L'administrateur choisi la configuration général du site pour l'affichage de ce paramétre.
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
						}
					}
				} elseif (!empty($this_attribut_infos['texte_libre']) && empty($this_attribut_id)) {
					// Le test sur empty($this_attribut_id) permet de savoir qu'on est dans un texte_libre "normal", sans options fictives qui tirent N valeurs de la base de données
					$type_affichage_attribut = 'texte_libre';
					$input_id = $form_id . '_custom_attribut' . $this_nom_attribut_id;
					$input_name = 'attribut' . $this_nom_attribut_id . '_texte_libre';
					$input_type = 'text';
					$input_value = $preselected_value;
					if(String::strpos($this_attribut_infos['technical_code'], 'date') === 0) {
						$input_class = 'datepicker';
					}
				} else {
					if(!empty($this_attribut_infos['texte_libre'])) {
						// Cas d'options fictives
						$this_value = String::html_entity_decode_if_needed($this_attribut_infos['descriptif']);
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
								'text' => String::html_entity_decode_if_needed($this_attribut_infos['descriptif']) . $price_text,
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
								'text' => String::html_entity_decode_if_needed($this_attribut_infos['descriptif']) . $price_text,
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
								'text' => String::html_entity_decode_if_needed($this_attribut_infos['descriptif']) . $price_text,
								'onclick' => $input_on_change.' update_product_price' . $save_suffix_id . '();'
							);
					}
				}
				$j++;
			}
			if(isset($type_affichage_attribut)) {
				$attributes_text_array[] = array(
						'text' => $attribut_text,
						'technical_code' => $this_attribut_infos['technical_code'],
						'name' => String::html_entity_decode_if_needed($this_attribut_infos['nom']).(!empty($this_attribut_infos['mandatory']) && $display_mode != 'selected_text'?' *':''),
						'type_affichage_attribut' => $type_affichage_attribut,
						'input_id' => $input_id,
						'input_name' => $input_name,
						'input_value' => $input_value,
						'input_type' => $input_type,
						'input_class' => $input_class,
						'options' => $options,
						'onchange' => $input_on_change . ' update_product_price' . $save_suffix_id . '();'
					);
			}
		}
	}
	if(!empty($attributes_text_array)) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/attributs_form_part.tpl');
		$tpl->assign('STR_MODULE_ATTRIBUTS_OPTIONS_ATTRIBUTS', $GLOBALS['STR_MODULE_ATTRIBUTS_OPTIONS_ATTRIBUTS']);
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('attributes_text_array', $attributes_text_array);
		$tpl->assign('display_mode', $display_mode);
		$tpl->assign('input_name', $input_name);
		$tpl->assign('input_id', $input_id);
		$tpl->assign('input_type', $input_type);
		$tpl->assign('input_on_change', $input_on_change);
		$tpl->assign('technical_code', $product_object->technical_code);
		// Dans le cas où on veut le résultat en mode texte, il faut retirer les sauts de ligne et tabulations => on applique trim()
		$output .= trim(str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $tpl->fetch()));
	}
	return $output;
}

/**
 * Formatte l'attribut (de type upload) du produit
 *
 * @param string $str_image : est une chaine de caractère qui peut être au format image directement ou alors peut être un texte qui contient des extraits d'images
 * @param boolean $set : definit si l'on a passé un format d'image (false) ou alors si on a passé du text contenant des images (true)
 * @return
 */
function display_option_image($str_image, $set = false)
{
	$output = '';
	if ($set) {
		// si $str_image est un texte contenant des images
		$inital_text = $str_image;
		$option_tab = explode("{{", $str_image);
		if (count($option_tab) > 1) {
			// s'il ya au moins une image
			foreach ($option_tab as $str_img) {
				if (($end_str = String::strpos($str_img, "}}")) !== false) {
					$str_img = String::substr($str_img, 0, $end_str);
					$small_option_image = thumbs($str_img, 0, 25, 'fit');
					$str_img_new = $GLOBALS['tplEngine']->createTemplate('modules/attributs_option_image.tpl', array(
							'set' => TRUE,
							'href' => $GLOBALS['repertoire_upload'] . '/' . $str_img,
							'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . $small_option_image
						))->fetch();
					$str_image = str_replace('{{' . $str_img . '}}', $str_img_new, $str_image);
				}
			}
		}
		$output .= $str_image;
	} else {
		$small_option_image = thumbs($str_image, 0, 25, 'fit');
		$output .= $GLOBALS['tplEngine']->createTemplate('modules/attributs_option_image.tpl', array(
				'set' => FALSE,
				'href' => $GLOBALS['repertoire_upload'] . '/' . $str_image,
				'src' => $GLOBALS['repertoire_upload'] . '/thumbs/' . $small_option_image
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
	$reseller_mode = is_reseller_module_active() && is_reseller();
	$combinaisons_array = array();
	foreach(array_keys($_FILES) as $this_key) {
		if(!isset($frm[$this_key]) && String::strpos($this_key, 'attribut') === 0) {
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
		$attributs_array = $product_object->get_possible_attributs('rough', false, 0, true, false, false, false, false, false, null);
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
			if (String::strpos($this_key, 'attribut') === 0) {
				// On a un attribut
				$temp = explode('_', $this_key);
				$this_nom_attribut_id = intval(String::substr($temp[0], String::strlen('attribut')));
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
				if (String::strpos($this_key, '_texte_libre') !== false) {
					// attribut au texte libre
					if (!empty($this_value)) {
						// Si cet attribut est obligatoire : c'est OK, pas de problème
						unset($GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id]);
					}
					if(!empty($this_value) && String::strpos($attributs_infos['technical_code'], 'date') === 0) {
						$this_value = get_mysql_date_from_user_input($this_value);
					}
					$combinaisons_array[] = $this_nom_attribut_id . '|0|' . $this_value;
				} elseif (String::strpos($this_key, '_upload') !== false) {
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
					} else {
						$combinaisons_array[] = $this_value;
						unset($GLOBALS['error_attribut_mandatory'][$this_nom_attribut_id]);
					}
				}
			}
		}
	}
	return implode('§', $combinaisons_array);
}

?>