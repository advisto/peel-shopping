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
		WHERE lang = '" . real_escape_string($_SESSION['session_langue']) . "' AND nbsearch>0 AND " . get_filter_site_cond('tag_cloud') . "
		GROUP BY tag_name
		ORDER BY RAND()*(10+quantity) DESC
		LIMIT ". intval($limit);

	$query = query($query);
	if (num_rows($query) > 0) {
		$tpl = $GLOBALS['tplEngine']->createTemplate('modules/tagcloud.tpl');
		$tpl_tags = array();
		while ($row = fetch_assoc($query)) {
			$row['quantity'] = max(1, $row['quantity']);
			$this_quantity = log($row['quantity']);
			$tags[StringMb::strtolower($row['tag'])] = $this_quantity;
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
				'href' => get_url('search') . '?match=1&search=' . $key,
				'value' => $value,
				'key' => $key,
				'level' => intval(($value-$min) / max(1, $max-$min) * 10)
			);
		}
		$tpl->assign('tags', $tpl_tags);
		$output .= $tpl->fetch();
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
		$query = query('SELECT id, nbsearch
			FROM peel_tag_cloud
			WHERE tag_name = "'.nohtml_real_escape_string($motclef).'" AND lang = "'.nohtml_real_escape_string($_SESSION['session_langue']).'" AND ' . get_filter_site_cond('tag_cloud'));
		if ($enr = fetch_assoc($query)) {
			query('UPDATE `peel_tag_cloud` 
				SET `nbsearch`=nbsearch+1 
				WHERE `id`="'.intval($enr['id']).'" AND ' . get_filter_site_cond('tag_cloud'));
		} else {
			query('INSERT INTO `peel_tag_cloud` (`tag_name`,`nbsearch`,`lang`,`site_id`) 
				VALUES ("'.nohtml_real_escape_string($motclef).'","1","'.nohtml_real_escape_string($_SESSION['session_langue']).'","'.nohtml_real_escape_string(get_site_id_sql_set_value($GLOBALS['site_id'])).'")');
		}
	}
}

