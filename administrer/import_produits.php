<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an     |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: import_produits.php 35350 2013-02-17 12:48:00Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products,admin_webmastering");

if (is_stock_advanced_module_active()) {
	include($fonctionsstock_advanced_admin);
}
$specific_fields_array = array($GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY'], 'Stock');
$DOC_TITLE = $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_TITLE'];
include("modeles/haut.php");

$action = vb($_POST['action']);
// On récupère les noms des champs de la table de produits
$product_fields_infos = get_table_fields('peel_produits');
foreach($product_fields_infos as $this_field_infos) {
	$product_field_names[] = $this_field_infos['Field'];
}
sort($product_field_names);

// Seléction des attributs, actif ou pas.
$q_nom_attrib = query("SELECT id, nom_" . $_SESSION['session_langue'] . "
	FROM peel_nom_attributs
	ORDER BY nom_" . $_SESSION['session_langue'] . "");
$attributs_array = array();
while ($attrib = fetch_assoc($q_nom_attrib)) {
	$attributs_array[] = $attrib;
}
$columns_skipped = array();

switch ($action) {
	case "import":
		if (a_priv('demo')) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_DEMO_RIGHTS_LIMITED']))->fetch();
			break;
		}
		$nbprod_update = 0;
		$nbprod_update_null = 0;
		$nbprod_insert = 0;
		$nbprod_categorie_insert = 0;
		echo $GLOBALS['tplEngine']->createTemplate('admin_import_produits_table.tpl')->fetch();
		if (isset($_POST['on_update'])) {
			// Mise à jour de la table de préférence des champs
			query("UPDATE peel_import_field SET etat='0'");
			foreach($_POST['on_update'] as $this_id => $this_value) {
				echo '<input type="hidden" name="on_update[' . $this_id . ']" value="' . String::str_form_value($this_value) . '"/>';
				query("UPDATE peel_import_field
					SET etat='1'
					WHERE champs='" . nohtml_real_escape_string($this_value) . "'");
				if (!affected_rows()) {
					// Comme etat valait 0 avant, c'est que la ligne n'existait pas, on va donc la créer
					query("INSERT INTO peel_import_field
						SET etat='1', champs='" . nohtml_real_escape_string($this_value) . "'");
				}
			}
		}
		if (!verify_token($_SERVER['PHP_SELF'] . $action)) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_INVALID_TOKEN']))->fetch();
		} elseif (empty($_POST['type_import'])) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_TYPE_NOT_CHOSEN']))->fetch();
		} elseif ($_POST['type_import'] == 'chosen_fields' && empty($_POST['on_update'])) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_FIELDS_NOT_CHOSEN']))->fetch();
		} else {
			$fichier = upload('fichier', true, 'data', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			if (empty($fichier) || !file_exists($GLOBALS['uploaddir'] . '/' . $fichier)) {
				/* le fichier n'existe pas */
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_FILE_NOT_FOUND']))->fetch();
			} else {
				$tpl = $GLOBALS['tplEngine']->createTemplate('admin_import_produits_fichier.tpl');
				$tpl->assign('href', $GLOBALS['repertoire_upload'] . '/' . $fichier);
				$tpl->assign('name', $fichier);
				$tpl->assign('STR_ADMIN_FILE', $GLOBALS['STR_ADMIN_FILE']);
				$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
				echo $tpl->fetch();
				if ($_POST['type_import'] == 'chosen_fields') {
					foreach($_POST['on_update'] as $this_field_name) {
						// Sélection des colonnes souhaitées par l'utilisateur
						$selected_product_field_names[] = $this_field_name;
					}
				}
				$fp = String::fopen_utf8($GLOBALS['uploaddir'] . '/' . $fichier, "rb");
				// Effacement des produits
				$nbprod = 0;
				$this_line = String::convert_encoding(fgets($fp, 16777216), GENERAL_ENCODING, $_POST['import_encoding']);
				if (empty($_POST['columns_separator'])) {
					// détection automatique
					if (strpos($this_line, "\t") !== false) {
						$separator = "\t";
					} elseif (strpos($this_line, ";") !== false) {
						$separator = ";";
					} elseif (strpos($this_line, ",") !== false) {
						$separator = ",";
					} else {
						$separator = "\t";
					}
				} elseif ($_POST['columns_separator'] == '\t') {
					$separator = "\t";
				} else {
					$separator = $_POST['columns_separator'];
				}
				$field_names = explode($separator, $this_line);
				$temp_trim_field_names = array();

				foreach($field_names as $this_key => $this_field_name) {
					$this_field_name = trim($this_field_name);
					$field_names[$this_key] = $this_field_name;
					if ($_POST['type_import'] == 'chosen_fields' && !in_array($this_field_name, $selected_product_field_names)) {
						// Champ non sélectionné par l'utilisateur pour l'import
						$columns_skipped[] = $this_field_name;
						continue;
					}
					if (!in_array($this_field_name, $product_field_names) && strpos($this_field_name, "#") === false && strpos($this_field_name, "§") === false) {
						// Si le champ trouvé dans le fichier n'est pas dans la table produit, et que le nom du produit ne contient pas de séparateur (spécifique aux attributs).
						if (in_array($this_field_name, $specific_fields_array)) {
							if(!in_array($this_field_name, array($GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], 'Stock'))) {
								// Les champs écartés dans la liste ci-dessus ici seront traités dans le script spécifiquement. Les autres champs spécifiques ne sont pas traités
								$columns_skipped[] = $this_field_name;
								echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_COLUMN_NOT_HANDLED'], $this_key, (!empty($this_field_name)?$this_field_name:'[-]'))))->fetch();
							}
						} else {
							// Colonne inconnue
							$columns_skipped[] = $this_field_name;
							echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_COLUMN_NOT_KNOWN'], $this_key, (!empty($this_field_name)?$this_field_name:'[-]'))))->fetch();
						}
						continue;
					}
					if (in_array($this_field_name, $temp_trim_field_names)) {
						echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_INCOHERENT_COLUMNS'], $this_field_name)))->fetch();
						$skip_import = true;
					}
					$temp_trim_field_names[] = $this_field_name;
				}
				unset($temp_trim_field_names);
				$line_number = 0;

				if (empty($skip_import)) {
					while (!feof($fp)) {
						unset($product_id);
						unset($set_sql_fields);
						unset($field_values);
						$last_treated_columns = 0;
						$line_number++;
						// Si une valeur de cas contient des sauts de ligne, alors on prend quand même la ligne suivante comme si c'était la continuité de cette ligne
						while (!feof($fp) && (empty($field_values) || count($field_values) < count($field_names) - count($columns_skipped))) {
							// Tant qu'on n'atteint pas fin de fichier
							$this_line = String::convert_encoding(fgets($fp, 16777216), GENERAL_ENCODING, $_POST['import_encoding']);
							if (empty($this_line)) {
								break;
							}
							// echo '<hr />Ligne Excel : $this_line';
							$line_fields = explode($separator, $this_line);
							foreach($line_fields as $key => $this_field) {
								// On récupère les valeurs présentes dans la ligne en cours
								if(isset($field_names[$key + $last_treated_columns])) {
									if (!isset($field_values[$field_names[$key + $last_treated_columns]])) {
										$field_values[$field_names[$key + $last_treated_columns]] = trim($this_field);
									} else {
										$field_values[$field_names[$key + $last_treated_columns]] .= trim($this_field);
									}
								} else {
									echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_COLUMN_NOT_KNOWN'], $key + $last_treated_columns, $this_field)))->fetch();
								}
							}
							$last_treated_columns += $key;
						}
						if (!empty($field_values) && count($field_values) > count($columns_skipped)) {
							// On a trouvé au moins un champ à importer
							if (empty($field_values['date_insere'])) {
								$field_values['date_insere'] = date('Y-m-d H:i:s', time());
							}
							if (empty($field_values['date_maj'])) {
								$field_values['date_maj'] = date('Y-m-d H:i:s', time());
							}
						} else {
							// On n'a trouvé aucun champ sur la ligne en cours, on passe à la ligne suivante
							continue;
						}
						// Gestion des champs impactant $field_values (transformation d'un nom en id par exemple)
						foreach($field_values as $this_field_name => $this_field_value) {
							if ($this_field_name == 'id_marque') {
								$q = query('SELECT id
									FROM peel_marques
									WHERE id=' . intval($this_field_value));
								// Marque existante
								if ($brand = fetch_assoc($q)) {
									$field_values['id_marque'] = $brand['id'];
								} else {
									// Marque inexistante, on l'insère en base de données.
									$q = query('INSERT INTO peel_marques
										SET nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_field_value) . '", etat="1"');
									$field_values['id_marque'] = insert_id();
									echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_BRAND_CREATED'], $line_number, $field_values['id_marque'])))->fetch();
								}
							}
						}
						// Génération du SQL à partir de $field_values
						foreach($field_values as $this_field_name => $this_value) {
							if (!empty($this_field_name) && !in_array($this_field_name, $columns_skipped)) {
								if(in_array($this_field_name, $product_field_names) && $this_field_name != 'id') {
									// On ne tient compte que des colonnes présentes dans la table produits pour sql_fields, les autres champs sont traités séparément
									$set_sql_fields[$this_field_name] = word_real_escape_string($this_field_name) . "='" . real_escape_string($this_value) . "'";
								}
							} else {
								unset($field_values[$this_field_name]);
							}
						}
						if (!empty($field_values['id'])) {
							// On a spécifié une id Produit, donc on essaie de faire un UPDATE
							if (!empty($set_sql_fields)) {
								$sql = "UPDATE peel_produits
									SET " . implode(', ', $set_sql_fields) . "
									WHERE id='" . intval($field_values['id']) . "'";
								query($sql);
								if (affected_rows()) {
									$product_id = $field_values['id'];
									$nbprod_update++;
									echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_LINE_UPDATED'], $line_number, $product_id)))->fetch();
								} 
							}
							if (!isset($product_id)) {
								// On vérifie si le produit existe déjà (et donc n'a pas été modifié) ou si il est à créer
								$q = query("SELECT id
									FROM peel_produits
									WHERE id='" . intval($field_values['id']) . "'");
								if ($product = fetch_assoc($q)) {
									// Produit existe, et n'avait donc pas été modifié
									$nbprod_update_null++;
									$product_id = $field_values['id'];
								} else {
									// Produit inexistant : on va exécuter l'INSERT INTO plus loin en imposant l'id
									$set_sql_fields['id'] = "id='" . intval($field_values['id']) . "'";
								}
							}
						}
						if (!isset($product_id)) {
							// Produit pas encore existant et $set_sql_fields est forcément non vide ici
							$sql = "INSERT INTO peel_produits
								SET " . implode(', ', $set_sql_fields);
							query($sql);
							$product_id = insert_id();
							$nbprod_insert++;
							echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_LINE_CREATED'], $line_number, $product_id)))->fetch();
						}
						// Gestion des champs nécessitant d'écrire dans d'autres tables en connaissant $product_id
						foreach($field_values as $this_field_name => $this_field_value) {
							if($this_field_name == $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS']){
								// Gestion de la couleur
								query('DELETE FROM peel_produits_couleurs 
									WHERE produit_id="' . intval($product_id) . '"');
								$this_list_color = explode(",", $this_field_value);
								foreach($this_list_color as $this_id => $this_value){
									if(String::strlen($this_value)>0) {
										if(!is_numeric($this_value)) {
											$sql_select_color = 'SELECT * 
												FROM peel_couleurs
												WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_value).'"';
											$query_color = query($sql_select_color);
											if($color = fetch_assoc($query_color)){
												$this_value = $color['id'];
											}else{
												$sql_insert_color = 'INSERT INTO peel_couleurs (nom_'.$_SESSION['session_langue'].') 
													VALUES ("'.real_escape_string($this_value).'")';
												query($sql_insert_color);
												$this_value = insert_id();
											}
										}
										$sql_select_product_color = 'SELECT * 
											FROM peel_produits_couleurs 
											WHERE produit_id = "' . intval($product_id) . '" AND couleur_id = "' . intval($this_value) . '"';
										$query_select_product_color = query($sql_select_product_color);
										if(!fetch_assoc($query_select_product_color)){
											$sql_match_product_color = 'INSERT INTO peel_produits_couleurs(produit_id,couleur_id) 
												VALUES ("' . intval($product_id) . '","' . intval($this_value) . '")';
											query($sql_match_product_color);
										}
									}
								}
							} elseif($this_field_name == $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES']){
								// Gestion de la taille
								query('DELETE FROM peel_produits_tailles 
									WHERE produit_id="' . intval($product_id) . '"');
								$this_list_size = explode(",", $this_field_value);
								foreach($this_list_size as $this_id => $this_value){
									$this_list_size_and_price = explode("§", $this_value);
									$size_name = $this_list_size_and_price[0];
									if(String::strlen($size_name)>0) {
										$size_price = vn($this_list_size_and_price[1]);
										$size_price_reseller = vn($this_list_size_and_price[2]);
										// On ne fait pas de test is_numeric ou pas sur les tailles pour savoir si on parle d'id ou de nom, car une taille peut être un nombre !
										// Donc obligatoirement, on considère qu'une taille est rentrée par son nom
										$sql_size = 'SELECT * 
											FROM peel_tailles 
											WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($size_name).'"';
										$query_size = query($sql_size);
										if($size = fetch_assoc($query_size)){
											if(isset($this_list_size_and_price[1]) && get_float_from_user_input($size_price) != $size['prix']){
												query('UPDATE peel_tailles 
													SET prix = "'.real_escape_string(get_float_from_user_input($size_price)).'" 
													WHERE id="'.intval($size['id']).'"');
											}
											if(isset($this_list_size_and_price[2]) && get_float_from_user_input($size_price_reseller) != $size['prix_revendeur']){
												query('UPDATE peel_tailles 
													SET prix_revendeur = "'.real_escape_string(get_float_from_user_input($size_price_reseller)).'" 
													WHERE id="'.intval($size['id']).'"');
											}
											$this_size_id = $size['id'];
										}else{
											$sql_insert_size = 'INSERT INTO peel_tailles (nom_'.$_SESSION['session_langue'].', prix, prix_revendeur) 
												VALUES ("'.real_escape_string($size_name).'", "'.floatval(get_float_from_user_input(vn($size_price))).'", "'.floatval(get_float_from_user_input(vn($size_price_reseller))).'")';
											query($sql_insert_size);
											$this_size_id = insert_id();
										}
										$select_size_product = 'SELECT * 
											FROM peel_produits_tailles 
											WHERE produit_id = "' . intval($product_id) . '" AND taille_id = "' . intval($this_size_id) . '"';
										$query_size_product = query($select_size_product);
										if(!fetch_assoc($query_size_product)){
											$sql_match_product_size = 'INSERT INTO peel_produits_tailles (produit_id, taille_id) 
												VALUES ("' . intval($product_id) . '", "' . intval($this_size_id) . '")';
											query($sql_match_product_size);
										}
									}
								}
							} elseif (strpos($this_field_name, "§") !== false) {
								// Gestion des prix par lots : tarifs dégressifs
								// Nom du champs
								$this_bulk_discount = explode("§", $this_field_name);
								$this_quantity = $this_bulk_discount[0];
								$this_price_standard = $this_bulk_discount[1];
								$this_price_reseller = $this_bulk_discount[2];
								// Valeur du champs
								if(!empty($this_field_value)){
									$this_package_price = explode("§", $this_field_value);
									$quantity = $this_package_price[0];
									$price_standard = $this_package_price[1];
									$price_reseller = $this_package_price[2];
									$sql_prix_lot = 'SELECT * 
										FROM peel_quantites 
										WHERE produit_id="' . intval($product_id) . '" AND quantite = "'.intval($quantity).'"';
									$query_prix_lot = query($sql_prix_lot);
									if(fetch_assoc($query_prix_lot)){
										$sql_update = 'UPDATE peel_quantites 
											SET quantite = "'.intval($quantity).'"';
										if(isset($this_price_standard) && isset($price_standard)){
											$sql_update.= ', prix ="'.nohtml_real_escape_string($price_standard ).'"';
										}
										if(isset($this_price_reseller) && isset($price_reseller)){
											$sql_update.= ', prix_revendeur ="'.nohtml_real_escape_string($price_reseller).'"';
										}
										$sql_update.= '
											WHERE produit_id="' . intval($product_id) . '" AND quantite = "'.intval($quantity).'"';
										query($sql_update);
										echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_TARIF_UPDATED'], vb($price_standard), vb($price_reseller), vb($quantity), $product_id)))->fetch();
									} else {
										if(isset($quantity) && $quantity > 0){
											$q = 'INSERT INTO peel_quantites 
												SET produit_id="' . intval($product_id) . '"';	
											$q.= ', quantite ="'.intval($quantity).'"';
											if(isset($this_price_standard) && isset($price_standard)){
												$q.= ', prix ="'.nohtml_real_escape_string($price_standard).'"';
											}
											if(isset($this_price_reseller) && isset($price_reseller)){
												$q.= ', prix_revendeur ="'.nohtml_real_escape_string($price_reseller).'"';
											}
											query($q);
											echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_TARIF_CREATED'], vb($price_standard), vb($price_reseller), vb($quantity), $product_id)))->fetch();
										}
									}	
								}
							} elseif (strpos($this_field_name, "#") !== false) {
								// Gestion des attributs
								// Pour chaque attribut, on sépare le nom de l'ID
								$nom_attrib = explode('#', $this_field_name);
								$q = query('SELECT id
									FROM peel_nom_attributs
									WHERE id=' . intval($nom_attrib[1]));
								if(!empty($nom_attrib[1])) {
									// attribut existant
									if ($att = fetch_assoc($q)) {
										$nom_attrib[1] = $att['id'];
									} else {
										// Attribut inexistant, on l'insère en base de données.
										$q = query('INSERT INTO peel_nom_attributs
											SET id=' . intval($nom_attrib[1]) . ', nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($nom_attrib[0]) . '", etat="1"');
										$nom_attrib[1] = insert_id();
										echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_ATTRIBUTE_CREATED'], $nom_attrib[0], $nom_attrib[1])))->fetch();
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
												// Option inexistante et différente d'upload ou de texte libre, on l'insère en base de donnée sinon on modifie l'attribut.
												if ($desc_option[1] == '__upload') {
													$q = query('UPDATE peel_nom_attributs
														SET upload=1
														WHERE id="' . intval($nom_attrib[1]) . '"');
													$attribute_ids[] = $desc_option[0];
												} elseif ($desc_option[1] == '__texte_libre') {
													$q = query('UPDATE peel_nom_attributs
														SET texte_libre=1
														WHERE id="' . intval($nom_attrib[1]) . '"');
													$attribute_ids[] = $desc_option[0];
												} else {
													$q = query('INSERT INTO peel_attributs
														SET id=' . intval($desc_option[0]) . '
														, id_nom_attribut=' . intval($nom_attrib[1]) . '
														, descriptif_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($desc_option[1]) . '"
														, mandatory=1', false, null, true);
													$this_id = insert_id();
													if(empty($this_id)) {
														// On change l'id si déjà prise en BDD
														// C'est un choix plutôt que d'effacer les attributs déjà existants
														$q = query('INSERT INTO peel_attributs
															SET id_nom_attribut=' . intval($nom_attrib[1]) . '
															, descriptif_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($desc_option[1]) . '"
															, mandatory=1', false, null, true);
														$this_id = insert_id();
													}
													$attribute_ids[] = $this_id;
													echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_OPTION_CREATED'], $desc_option[1], $this_id)))->fetch();
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
							}
						}	
						// Gestion de la catégorie
						unset($this_categories_array);
						if (!empty($field_values['categorie_id']) && !is_numeric($field_values['categorie_id']) && empty($field_values['Categorie'])) {
							// Compatibilité avec anciens champs appelés categorie_id et contenant des noms de catégories
							$field_values['Categorie'] = $field_values['categorie_id'];
							unset($field_values['categorie_id']);
						}
						if (!empty($field_values['Categorie'])) {
							// Ce champ contient une liste de catégories séparées par des virgules
							foreach(explode(',', $field_values['Categorie']) as $this_category) {
								if (is_numeric($this_category)) {
									// le champ Categorie est un id
									$this_categorie_id = intval($this_category);
								} else {
									// le champ Categorie n'est pas un nombre, on tente une recherche dans la BDD sur le nom de la catégorie.
									$q = query('SELECT id
										FROM peel_categories
										WHERE nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_category) . '"');
									// Catégorie existante, ou le champ Categorie du fichier n'est ni un ID, ni le nom de la catégorie
									if ($categorie = fetch_assoc($q)) {
										$this_categorie_id = $categorie['id'];
									} else {
										// Catégorie inexistante : on l'insère en base de données
										$q = query('INSERT INTO peel_categories
											SET nom_' . $_SESSION['session_langue'] . '="' . nohtml_real_escape_string($this_category) . '", etat="1"');
										$this_categorie_id = insert_id();
										echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_CATEGORY_CREATED'], $line_number, $this_categorie_id)))->fetch();
									}
								}
								$this_categories_array[] = $this_categorie_id;
							}
						}
						if (!empty($field_values['categorie_id'])) {
							// On a déjà testé plus haut si categorie_id était numérique ou non, et si pas numérique on l'a supprimé
							// donc là il est forcément numérique
							if (get_category_name($field_values['categorie_id']) !== false) {
								$this_categories_array[] = $field_values['categorie_id'];
							} else {
								echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_REFERENCE_DOES_NOT_EXIST'], $field_values['categorie_id'])))->fetch();
							}
						}
						if (!empty($this_categories_array)) {
							foreach($this_categories_array as $this_categorie_id) {
								if (!empty($this_categorie_id)) {
									// Vérification que l'association entre les produits, les catégories de produits
									$q = query('SELECT produit_id, categorie_id
										FROM peel_produits_categories
										WHERE produit_id="' . intval($product_id) . '" AND categorie_id="' . intval($this_categorie_id) . '"');
									if (!num_rows($q)) {
										query('INSERT INTO peel_produits_categories
										SET produit_id="' . intval($product_id) . '",
											categorie_id="' . intval($this_categorie_id) . '"');
										$nbprod_categorie_insert++;
									}
								}
							}
						}
						// Gestion des stocks
						// Doit être fait à la fin car on doit déjà avoir les couleurs et tailles bien rentrées en base de données
						if(!empty($field_values["Stock"]) && is_stock_advanced_module_active()){
							// Format stock ou stock§color§size, et les combinaisons sont séparées par ,
							$this_list_stock = explode(",", $field_values["Stock"]);
							$stock_frm = array();
							foreach($this_list_stock as $this_id => $this_value){
								$this_list_infos = explode("§", $this_value);
								$stock_frm["id"][$this_id] = $product_id;
								$stock_frm["stock"][$this_id] = $this_list_infos[0];
								$this_value = vb($this_list_infos[1]);
								if(is_numeric($this_value)) {
									$stock_frm["couleur_id"][$this_id] = $this_value;
								} elseif(!empty($this_value) && !is_numeric($this_value)) {
									$sql_select_color = 'SELECT * 
										FROM peel_couleurs
										WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_value).'"';
									$query_color = query($sql_select_color);
									if($color = fetch_assoc($query_color)){
										$stock_frm["couleur_id"][$this_id] = $color['id'];
									}
								}
								if(!empty($this_list_infos[2])) {
									// Taille donnée forcément par son nom
									$sql_size = 'SELECT * 
										FROM peel_tailles 
										WHERE nom_'.$_SESSION['session_langue'].' = "'.real_escape_string($this_list_infos[2]).'"';
									$query_size = query($sql_size);
									if($size = fetch_assoc($query_size)){
										$stock_frm["taille_id"][$this_id] = $size['id'];
									}
								}
							}
							insere_stock_produit($stock_frm);
						}
						if (is_stock_advanced_module_active() && !empty($field_values['on_stock']) && $field_values['on_stock'] == 1) {
							insert_product_in_stock_table_if_not_exist($product_id, 1);
						}
					}
				}
				fclose($fp);
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_IMPORTATION_OK'], $nbprod_insert + $nbprod_update + $nbprod_update_null, $nbprod_update, $nbprod_update_null, $nbprod_insert, $nbprod_categorie_insert)))->fetch();
			}
		}
		break;

	default:
		/* FORMULAIRE DE CHOIX D'IMPORTATION */
		$tpl = $GLOBALS['tplEngine']->createTemplate('admin_import_produits_form.tpl');
		$tpl->assign('action', get_current_url(false));
		$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . 'import'));
		$tpl_inputs = array();
		$req = query("SELECT champs, etat, texte_" . $_SESSION['session_langue'] . "
			FROM peel_import_field");
		while ($result = fetch_assoc($req)) {
			$fields_explanations_arrays[$result['champs']] = $result;
		}
		// Pour afficher categorie_id dans la liste des champs importables
		$product_field_names[] = 'categorie_id';
		$product_field_names['Categorie'] = 'Categorie';
		$product_field_names[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY']] = $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY'];
		$product_field_names[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES']] = $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'];
		$product_field_names[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS']] = $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'];
		$product_field_names['Stock'] = 'Stock';
		sort($product_field_names);
		foreach ($product_field_names as $this_field) {
			if ($this_field != 'stock') {
				$tpl_inputs[] = array('field' => $this_field,
					'issel' => vb($fields_explanations_arrays[$this_field]['etat']) == 1,
					'explanation' => vb($fields_explanations_arrays[$this_field]['texte']),
					'is_important' => ($this_field == 'id' || $this_field == 'categorie_id')
					);
			}
		}
		$tpl->assign('inputs', $tpl_inputs);
		$tpl->assign('uploaddir', $uploaddir);
		$tpl->assign('import_encoding', vb($frm['import_encoding']));
		$tpl->assign('example_href', 'import/exemple_prod.csv');
		$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_FORM_TITLE', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_FORM_TITLE']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_FILE_FORMAT', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_FILE_FORMAT']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_FILE_FORMAT_EXPLAIN', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_FILE_FORMAT_EXPLAIN']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_FILE_EXAMPLE', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_FILE_EXAMPLE']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_IMPORT_MODE', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_IMPORT_MODE']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_IMPORT_ALL_FIELDS', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_IMPORT_ALL_FIELDS']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_IMPORT_SELECTED_FIELDS', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_IMPORT_SELECTED_FIELDS']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_SELECT_FIELDS', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_SELECT_FIELDS']);
		$tpl->assign('STR_WARNING', $GLOBALS['STR_WARNING']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_EXPLAIN', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_EXPLAIN']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_WARNING_ID', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_WARNING_ID']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_FILE_NAME', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_FILE_NAME']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_FILE_ENCODING', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_FILE_ENCODING']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_SEPARATOR', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_SEPARATOR']);
		$tpl->assign('STR_ADMIN_IMPORT_PRODUCTS_SEPARATOR_EXPLAIN', $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_SEPARATOR_EXPLAIN']);
		$tpl->assign('STR_VALIDATE', $GLOBALS['STR_VALIDATE']);
		echo $tpl->fetch();
		break;
}
include("modeles/bas.php");

?>