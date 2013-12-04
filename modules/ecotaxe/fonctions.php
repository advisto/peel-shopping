<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: fonctions.php 39162 2013-12-04 10:37:44Z gboussin $

if (!defined('IN_PEEL')) {
	die();
}

/**
 * get_ecotax_object()
 *
 * @param mixed $id
 * @return object
 */
function get_ecotax_object($id) {
	static $eco;
	$cache_id = $id;
	if (!isset($eco[$cache_id])) {
		$query = query('SELECT prix_ht, prix_ttc
			FROM peel_ecotaxes
			WHERE id = "' . intval($id) . '"');
		$eco[$cache_id] = fetch_object($query);
	}
	return $eco[$cache_id];
}
?>