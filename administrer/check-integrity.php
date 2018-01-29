<?php
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
// $Id: check-integrity.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin");

$GLOBALS['DOC_TITLE'] = 'Véfication de l\'existence de fichiers dans upload/ et suppression de la base de données si absents';
$menu_selected='index-various.php';

$output = '';

$dir_array = vb($GLOBALS['site_parameters']['check_integrity_directories_by_type_array'], array('products' => 'upload/'));
$fields_to_check_array['products'] = array('photo1', 'photo2', 'photo3', 'photo4', 'photo5', 'photo6', 'photo7', 'photo8', 'photo9', 'photo10');

$hook_result = call_module_hook('check_integrity_get_configuration_array', array(), 'array');
$fields_to_check_array = array_merge_recursive_distinct($fields_to_check_array, $hook_result['fields_to_check_array']);
$dir_array = array_merge_recursive_distinct($dir_array, $hook_result['dir_array']);

$photos_to_keep_array = vb($GLOBALS['site_parameters']['photos_to_keep_array'], array());

if (!empty($_GET['delete'])) {
    if (strlen($_GET['delete']) < 255 && in_array(substr($_GET['delete'], strlen($_GET['delete'])-4), array('.gif', '.jpg', 'jpeg', '.png', '.avi', 'mpeg', '.mpg', '.wmv', '.asf')) && file_exists($GLOBALS['dirroot'] . '/' . $_GET['delete'])) {
        $file_size_ko = (round((filesize($GLOBALS['dirroot'] . '/' . $_GET['delete']) / 1024) * 1000) / 1000);
        $output .= $_GET['delete'] . ' supprimé' . $file_size_ko . ' ko)<br />';
        //unlink($GLOBALS['dirroot'] . '/' . $_GET['delete']);
    }
}
// , 'avatars/mini/'=>'avatars'
if (!empty($_GET['force_delete_bad'])) {
    $output .= '<br /><b><a href="check-integrity.php">Revenir en mode normal</a></b><br /><br />';
}

foreach($dir_array as $type => $dir) {
    unset($file_in_db);
    unset($file_not_normal);
    unset($file_bad_size);
    unset($file_nok);
    unset($file_bad);
    unset($sql_delete_link);
	$not_found = 0;
	if ($type == 'products') {
		$table_name = 'peel_produits';
		$sql_select = "SELECT id, " . nohtml_real_escape_string(implode(', ', $fields_to_check_array)) . "
			FROM " . word_real_escape_string($table_name) . "
			WHERE 1";
	} elseif ($type == 'ads' && check_if_module_active('annonces')) {
		$table_name = 'peel_lot_vente';
		$sql_select = "SELECT ref, " . nohtml_real_escape_string(implode(', ', $fields_to_check_array)) . "
			FROM " . word_real_escape_string($table_name) . "
			WHERE 1";
	} else {
		continue;
	}
	/*elseif(false) {
		$q = query('SELECT id_photo
			FROM photos
			WHERE active="TRUE" OR active="DESACTIVER" OR (active!="TRUE" AND date>"' . date('Y-m-d H:i:s', time()-3600 * 24 * 31) . '")');
		while ($res = fetch_assoc($q)) {
			$file_in_db[$res['id_photo'] . '.jpg'] = true;
		}
		$sql_delete_link[] = 'DELETE FROM photos WHERE id_photo="[ID]"';
    } elseif ($type == 'videos') {
        $min_file_size = "100000";
        $q = query('SELECT id_user, extension
				FROM videos
				WHERE active="TRUE" OR active="FALSE"');
        while ($res = fetch_assoc($q)) {
            $file_in_db[$res['id_user'] . '.' . $res['extension']] = true;
        }
        $sql_delete_link[] = 'DELETE FROM videos WHERE id_user="[ID]"';
    } else {
	 //Gestion d'avatars
        $min_file_size = "600";
        $q = query('SELECT avatar
			FROM peel_utilisateurs
			WHERE 1');
        while ($res = fetch_assoc($q)) {
            if (substr($res['avatar'], 0, 7) == 'defaut/') {
                continue;
            }
            if (is_numeric($res['avatar'])) {
                $res['avatar'] .= '.jpg';
            }
            $file_in_db[$res['avatar']] = true;
        }
        $sql_delete_link[] = 'UPDATE users SET avatar="defaut/avatar-boy.gif" WHERE (avatar="[ID]" OR avatar="[ID].jpg") AND sexe="1"';
        $sql_delete_link[] = 'UPDATE users SET avatar="defaut/avatar-girl.gif" WHERE (avatar="[ID]" OR avatar="[ID].jpg") AND sexe="2"';
    } */
	if ($type == 'products' || $type == 'ads') {
		// Préparation du SQL générique à utiliser si on veut supprimer une donnée pour laquelle aucun fichier n'a été trouvé sur le disque
		unset($this_sql_set);
		unset($this_sql_where);
		foreach($fields_to_check_array as $this_item) {
			$this_sql_set[] = word_real_escape_string(($this_item)) . '=IF(' . word_real_escape_string(($this_item)) . '="[ID]","",' . word_real_escape_string($this_item) . ')';
			$this_sql_where[] = word_real_escape_string($this_item) . '="[ID]"';
		}
		$sql_delete_link[] = "UPDATE " . word_real_escape_string($table_name) . "
			SET " . nohtml_real_escape_string(implode(', ', $this_sql_set)) . ' WHERE ' . nohtml_real_escape_string(implode(', ', $this_sql_set)) . '';
	}
	$min_file_size = "1000";
	$q = query($sql_select);
	$j = 0;
	while ($res = fetch_assoc($q)) {
		$j++;
		foreach($fields_to_check_array as $this_item) {
			if(!empty($res[$this_item])) {
				$file_in_db[$res[$this_item]] = true;
			}
		}
	}
	$output .= '<br /><b>Images en BDD : '.$found.' avec fichiers OK, '.$not_found.' sans fichier correspondant</b><br /><br />';
	if (!empty($_GET['force_delete_bad']) && $_GET['force_delete_bad'] == $dir && !empty($_GET['type']) && $_GET['type'] == 'file_in_db') {
		$output .= '<br /><b>Liens dans la BDD supprimés : ' . $not_found . ' liens</b><br /><br />';
	} else {
		$output .= '<br /><b>Option à manipuler avec précaution : <a href="check-integrity.php?force_delete_bad=' . StringMb::str_form_value(rawurlencode($dir)) . '&type=file_in_db">Effacer de la BDD les liens vers les fichiers "ABSENTS DE LA BDD" (' . $not_found . ' liens)</a></b><br /><br />';
	}
	$output .= '<hr />';
    $output .= '<h2>Dossier : ' . $dir . '</h2>';

    $i = 0;
    $file_nok = array();
 	echo 'Fichiers appelés en BDD : ' .count($file_in_db).'<br />';
	// On récupère la liste des fichiers qui sont dans $dir de manière récursive 
	unset($GLOBALS['files_found_in_folder']);
	nettoyer_dir($GLOBALS['dirroot'] . '/' . $dir, null, null, true);
	foreach($GLOBALS['files_found_in_folder'] as $this_file_relative_path_to_dir) {
		$file = basename($this_file_relative_path_to_dir);
		if (StringMb::strlen($file) > 4 && in_array(StringMb::substr($file, StringMb::strlen($file)-4), array('.gif', '.jpg', 'jpeg', '.png', '.avi', 'mpeg', '.mpg', '.wmv', '.asf', '.bmp', '.JPG'))) {
			if (!is_file($GLOBALS['dirroot'] . '/' . $dir . '/' . $this_file_relative_path_to_dir)) {
				$file_bad[] = $dir . '/' . $this_file_relative_path_to_dir;
			} elseif (filesize($GLOBALS['dirroot'] . '/' . $dir .  '/' . $this_file_relative_path_to_dir) < $min_file_size && !in_array(substr($file, 0, strlen($file)-4), $photos_to_keep_array)) {
				$file_bad_size[] = $dir . '/' . $this_file_relative_path_to_dir;
			} elseif ((preg_match('advisto.com', $GLOBALS['wwwroot']) && is_numeric(substr($file, 0, 1))) || is_numeric(substr($file, 0, strlen($file)-4)) || (in_array(substr($file, 0, strlen($file)-4), $photos_to_keep_array))) {
				if (!empty($file_in_db[$file])) {
					unset($file_in_db[$file]);
				} elseif (!in_array(substr($file, 0, strlen($file)-4), $photos_to_keep_array)) {
					$file_nok[] = $dir . '/' . $this_file_relative_path_to_dir;
				}
			} else {
				$file_not_normal[] = $dir . '/' . $this_file_relative_path_to_dir;
			}
		}
    }
 	echo 'Fichiers appelés en BDD non trouvés sur disque : ' .count($file_in_db).'<br />';
	foreach(array('file_bad' => 'Mauvais fichiers', 'file_not_normal' => 'Fichiers aux noms anormaux', 'file_bad_size' => 'Fichiers de taille anormale', 'file_nok' => 'Fichiers présents sur le disque mais pas dans la BDD') as $this_file_problem => $this_file_problem_name) {
		if (!empty($$this_file_problem)) {
			$total_files_size_ko = 0;
			$not_found = 0;
			$output .= '<h2>'.$this_file_problem_name.'</h2>';
			foreach($$this_file_problem as $file) {
				$file_size_ko = (round((filesize($GLOBALS['dirroot'] . '/' . $file) / 1024) * 1000) / 1000);
				$total_files_size_ko += $file_size_ko;
				$not_found++;
				if (!empty($_GET['force_delete_bad']) && $_GET['force_delete_bad'] == $dir && !empty($_GET['type']) && $_GET['type'] == $this_file_problem && !empty($file)) {
					$output .= $file . ' supprimé' . $file_size_ko . ' ko)<br />';
					unlink($GLOBALS['dirroot'] . '/' . $file);
					$output .= 'UNLINK de ' . $file . '<br />';
					// Suppression du thumb
					unlink($GLOBALS['dirroot'] . '/' . str_replace(array('0/', '1/', '2/', '3/', '4/', '5/', '6/', '7/', '8/', '9/', '.jpg'), array('0/th_', '1/th_', '2/th_', '3/th_', '4/th_', '5/th_', '6/th_', '7/th_', '8/th_', '9/th_', '.png'), $file));
					continue;
				}
				if(empty($_SESSION['session_admin_multisite']) || $_SESSION['session_admin_multisite'] != $GLOBALS['site_id']) {
					$this_wwwroot =  get_site_wwwroot($_SESSION['session_admin_multisite'], $_SESSION['session_langue']);
				} else {
					$this_wwwroot =  $GLOBALS['wwwroot'];
				}
				$output .= '<a href="' . $this_wwwroot . '/' . $file . '" target="_blank">' . $this_wwwroot . '/' . $file . '</a> (' . $file_size_ko . ' ko) &nbsp; &nbsp; <a href="check-integrity.php?delete=' . StringMb::str_form_value(rawurlencode($file)) . '" style="color:#FF0000">Supprimer ' . $file . '</a><br />';
				// if(!empty($_GET['delete'])) {
				// $output .= '<br /><b>Mode succint : affichage seulement des premiers fichiers trouvé - <a href="check-integrity.php">Afficher tout</a></b><br /><br />';
				// break;
				// }
				if ($not_found % 100 == 0) {
					echo $output;
					$output ='';
					// Force l'envoi du HTML juste généré au navigateur, pour que l'utilisateur suive en temps réel l'avancée
					flush();
				}
			}
			if (!empty($_GET['force_delete_bad']) && $_GET['force_delete_bad'] == $dir && !empty($_GET['type']) && $_GET['type'] == $this_file_problem) {
				$output .= '<br /><b>'.$this_file_problem_name.' supprimés : ' . $not_found . ' fichiers - ' . $total_files_size_ko . ' ko</b><br /><br />';
			} else {
				$output .= '<br /><b>Option à manipuler avec précaution : <a href="check-integrity.php?force_delete_bad=' . StringMb::str_form_value(rawurlencode($dir)) . '&type='. StringMb::str_form_value($this_file_problem) .'">Effacer automatiquement tous les fichiers "MAUVAIS" (' . $not_found . ' fichiers - ' . $total_files_size_ko . ' ko)</a></b><br /><br />';
			}
		} else {
			$output .= '<h2>Pas de '.$this_file_problem_name.'</h2>';
		}
	}
     if (!empty($file_in_db)) {
        $not_found = 0;
        $output .= '<h2>Fichiers présents dans la bdd mais pas physiquement</h2>';
        foreach(array_keys($file_in_db) as $file) {
            $not_found++;
            if (!empty($_GET['force_delete_bad']) && $_GET['force_delete_bad'] == $dir && !empty($_GET['type']) && $_GET['type'] == 'file_in_db') {
                foreach($sql_delete_link as $sql) {
                    if (strpos($file, '.')) {
                    	if (preg_match('destockplus', $GLOBALS['wwwroot'])) {
                        	$value_for_query = $file;
                        } elseif (preg_match('ame-soeur.com', $GLOBALS['wwwroot'])) {
							$temp_array = explode('.', $file);
							$value_for_query = $temp_array[0];
						}
                        query(nohtml_real_escape_string(str_replace('[ID]', $value_for_query, $sql)));
                        $output .= str_replace('[ID]', $value_for_query, $sql) . '<br />';
                    }
                }
                $output .= 'Lien vers ' . $file . ' supprimé />';
                continue;
            }
            $output .= $file . '<br />';
            // if(!empty($_GET['delete'])) {
            // $output .= '<br /><b>Mode succint : affichage seulement des premiers fichiers trouvé - <a href="check-integrity.php">Afficher tout</a></b><br /><br />';
            // break;
            // }
            // }
        }
        if (!empty($_GET['force_delete_bad']) && $_GET['force_delete_bad'] == $dir && !empty($_GET['type']) && $_GET['type'] == 'file_in_db') {
            $output .= '<br /><b>Liens dans la BDD supprimé: ' . $not_found . ' liens</b><br /><br />';
        } else {
            $output .= '<br /><b>Option à manipuler avec précaution : <a href="check-integrity.php?force_delete_bad=' . StringMb::str_form_value(rawurlencode($dir)) . '&type=file_in_db">Effacer de la BDD les liens vers les fichiers "ABSENTS DE LA BDD" (' . $not_found . ' liens)</a></b><br /><br />';
        }
    } else {
        $output .= '<h2>Aucun fichier présent dans la bdd mais pas physiquement</h2>';
    }
    $output .= '<hr />';
}

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

