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
//

if (!defined('IN_PEEL')) {
	die();
}

/**
 * affiche_tagcloud()
 *
 * @param boolean $return_mode
 * @return
 */
function affiche_tagcloud($return_mode = false)
{
	// Avec la complicité de 3dvf.fr
	$output = '';
	$max = 0;
	if(empty($GLOBALS['site_parameters']['tagcloud_display_count'])) {
		$limit = 25;
	} else {
		$limit = $GLOBALS['site_parameters']['tagcloud_display_count'];
	}
	$query = "SELECT tag_name AS tag, nbsearch AS quantity
		FROM peel_tag_cloud
		WHERE lang = '" . $_SESSION['session_langue'] . "' AND nbsearch>0
		GROUP BY tag_name
		ORDER BY RAND()*(10+quantity) DESC
		LIMIT ". intval($limit);

	$result = query($query);
	
	if (num_rows($result) > 0) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/tagcloud.tpl');
		$tpl_tags = array();
		while ($row = fetch_assoc($result)) {
			$row['quantity'] = max(1, $row['quantity']);
			$this_quantity = log($row['quantity']);
			$tags[String::strtolower($row['tag'])] = $this_quantity;
			if ($this_quantity > $max) {
				$max = $this_quantity;
			}
			if (!isset($min) || $this_quantity < $min) {
				$min = $this_quantity;
			}
		}
		ksort($tags);
		foreach ($tags as $key => $value) {
			$tpl_tags[] = array(
				'href' => $GLOBALS['wwwroot'] . '/search.php?match=1&search=' . $key,
				'value' => $value,
				'key' => $key,
				'level' => intval(($value-$min) / max(1, $max-$min) * 10)
			);
		}
		$tpl->assign('tags', $tpl_tags);
		$output = $tpl->fetch();
	}
	if ($return_mode) {
		return $output;
	} else {
		echo $output;
	}
}

/**
 * Insertion Tag de recherche
 *
 * @param string $motclef
 * @return
 */
function sql_tagcloud($motclef)
{
	$motclef = trim($motclef);

	if (!empty($motclef)) {
		$result = query('SELECT id, nbsearch
			FROM peel_tag_cloud
			WHERE tag_name = "'.nohtml_real_escape_string($motclef).'" AND lang = "'.nohtml_real_escape_string($_SESSION['session_langue']).'"');
		if ($enr = fetch_assoc($result)) {
			query('UPDATE `peel_tag_cloud` 
				SET `nbsearch`=nbsearch+1 
				WHERE `id`="'.intval($enr['id']).'"');
		} else {
			query('INSERT INTO `peel_tag_cloud` (`tag_name`,`nbsearch`,`lang`) 
				VALUES ("'.nohtml_real_escape_string($motclef).'","1","'.$_SESSION['session_langue'].'")');
		}
	}
}

?>