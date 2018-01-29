<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an     |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: import_produits.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products,admin_webmastering");

$specific_fields_array = array($GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY'], 'Stock', 'Categorie', 'categorie_id');
$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_TITLE'];

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$action = vb($_POST['action']);
// On récupère les noms des champs de la table de produits
$product_field_names = get_table_field_names('peel_produits');
sort($product_field_names);

// Seléction des attributs, actif ou pas.
$q_nom_attrib = query("SELECT id, nom_" . $_SESSION['session_langue'] . "
	FROM peel_nom_attributs
	WHERE " . get_filter_site_cond('nom_attributs') . "
	ORDER BY nom_" . $_SESSION['session_langue'] . "");
$attributs_array = array();
while ($attrib = fetch_assoc($q_nom_attrib)) {
	$attributs_array[] = $attrib;
}
$columns_skipped = array();

switch ($action) {
	case "import":
		if (a_priv('demo')) {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_RIGHTS_LIMITED'], StringMb::strtoupper($_SESSION['session_utilisateur']['priv']))))->fetch();
			break;
		}
		echo $GLOBALS['tplEngine']->createTemplate('admin_import_produits_table.tpl')->fetch();
		if (isset($_POST['on_update'])) {
			// Mise à jour de la table de préférence des champs
			query("UPDATE peel_import_field SET etat='0'");
			foreach($_POST['on_update'] as $this_id => $this_value) {
				echo '<input type="hidden" name="on_update[' . $this_id . ']" value="' . StringMb::str_form_value($this_value) . '" />';
				query("UPDATE peel_import_field
					SET etat='1'
					WHERE champs='" . nohtml_real_escape_string($this_value) . "' AND " . get_filter_site_cond('import_field', null, true) . "");
				if (!affected_rows()) {
					// Comme etat valait 0 avant, c'est que la ligne n'existait pas, on va donc la créer
					query("INSERT INTO peel_import_field
						SET etat='1', champs='" . nohtml_real_escape_string($this_value) . "', site_id='" . nohtml_real_escape_string(get_site_id_sql_set_value($GLOBALS['site_id']))."'");
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
			$fichier = upload('fichier', false, 'data', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height']);
			if (empty($fichier) || !file_exists($GLOBALS['uploaddir'] . '/' . $fichier)) {
				/* le fichier n'existe pas */
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_ERR_FILE_NOT_FOUND']))->fetch();
			} else {
				$tpl = $GLOBALS['tplEngine']->createTemplate('admin_import_produits_fichier.tpl');
				$tpl->assign('href', get_url_from_uploaded_filename($fichier));
				$tpl->assign('name', $fichier);
				$tpl->assign('STR_FILE', $GLOBALS['STR_FILE']);
				$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
				echo $tpl->fetch();
				if ($_POST['type_import'] == 'chosen_fields') {
					foreach($_POST['on_update'] as $this_field_name) {
						// Sélection des colonnes souhaitées par l'utilisateur
						$selected_product_field_names[] = $this_field_name;
					}
				}
				$fp = StringMb::fopen_utf8($GLOBALS['uploaddir'] . '/' . $fichier, "rb");
				// Effacement des produits
				$nbprod = 0;
				$this_line = StringMb::convert_encoding(fgets($fp, 16777216), GENERAL_ENCODING, $_POST['import_encoding']);
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
							if(!in_array($this_field_name, array($GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], 'Stock', 'Categorie', 'categorie_id'))) {
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
					while (!StringMb::feof($fp)) {
						unset($product_id);
						unset($set_sql_fields);
						unset($field_values);
						$last_treated_columns = 0;
						$line_number++;
						// Si une valeur de cas contient des sauts de ligne, alors on prend quand même la ligne suivante comme si c'était la continuité de cette ligne
						while (!StringMb::feof($fp) && (empty($field_values) || count($field_values) < count($field_names) - count($columns_skipped))) {
							// Tant qu'on n'atteint pas fin de fichier
							$this_line = StringMb::convert_encoding(fgets($fp, 16777216), GENERAL_ENCODING, $_POST['import_encoding']);
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
						create_or_update_product($field_values, $columns_skipped, $product_field_names, $specific_fields_array, true);
					}
				}
				fclose($fp);
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_IMPORT_PRODUCTS_MSG_IMPORTATION_OK'], vn($GLOBALS['nbprod_insert']) + vn($GLOBALS['nbprod_update']) + vn($GLOBALS['nbprod_update_null']), vn($GLOBALS['nbprod_update']), vn($GLOBALS['nbprod_update_null']), vn($GLOBALS['nbprod_insert']), vn($GLOBALS['nbprod_categorie_insert']))))->fetch();
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
			FROM peel_import_field
			WHERE " . get_filter_site_cond('import_field', null, true) . "");
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
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

