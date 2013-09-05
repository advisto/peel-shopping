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
 * get_ecotax_object()
 *
 * @param mixed $id
 * @return object
 */
function get_ecotax_object($id) {
	$query = query('SELECT prix_ht, prix_ttc
		FROM peel_ecotaxes
		WHERE id = "' . intval($id) . '"');
	if ($eco = fetch_object($query)) {
		return $eco;
	} else {
		return false;
	}
}
?>