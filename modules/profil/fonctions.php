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

/**
 * Renvoie les tableaux d'informations à afficher dans Mon compte
 *
 * @param array $params
 * @return
 */
function profil_hook_account_show($params) {
	$result['modules_data'] = array();
	$result['modules_data_group'] = array();
	$profil = get_profil($_SESSION['session_utilisateur']['priv']);
	if (!empty($profil['document_'.$_SESSION['session_langue']]) || !empty($profil['description_document_'.$_SESSION['session_langue']])) {
		$result['modules_data_group']['profil'] = array('header' => $GLOBALS['STR_ACCOUNT_DOCUMENTATION'], 'position' => 18, 'comments' => $profil['description_document_'.$_SESSION['session_langue']]);
		$result['modules_data']['profil'] = array();
		foreach(array('document_'.$_SESSION['session_langue']) as $this_doc_name) {
			if (!empty($profil[$this_doc_name])) {
				$result['modules_data']['profil'][] = array('txt' => '<img src="' . thumbs($profil[$this_doc_name], 75, 75, 'fit', null, null, true, true) . '" /><br />' . $GLOBALS["STR_DOWNLOAD_DOCUMENT"], 'href' => get_url_from_uploaded_filename($profil[$this_doc_name]));
			}
		}
	}
	return $result;
}

/**
 * Retourne les informations d'un type de profil
 *
 * @param string $technical_code (column named 'priv' into table)
 * @param array $$mode
 * @return
 */
function get_profil($technical_code, $mode = 'array')
{
	$names_array = array();
	$sql = "SELECT *, name_".$_SESSION['session_langue']." AS name
		FROM peel_profil
		WHERE priv IN ('" . str_replace('+', "','", nohtml_real_escape_string($technical_code)) . "') AND " . get_filter_site_cond('profil') . "";
	$query = query($sql);
	while($result = fetch_assoc($query)) {
		if($mode == 'array') {
			// Premier profil trouvé envoyé (ancien mode de fonctionnement de la fonction)
			return $result;	
		} else {
			$names_array[] = $result['name'];
		}
	}
	return implode(', ', $names_array);
}
