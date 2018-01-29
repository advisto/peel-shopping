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
// $Id: template_mail.php 55332 2017-12-01 10:44:06Z sdelaporte $
include("../../../configuration.inc.php");

$output = '';
if ((!empty($_POST['id']) || !empty($_POST['technical_code'])) && !empty($_POST['mode'])) {
	$sql = 'SELECT subject, text, lang
		FROM peel_email_template
		WHERE ' . get_filter_site_cond('email_template', null) . ' AND id="' . intval(vb($_POST['id'])) . '"';
	$query = query($sql);
	if($row_template = fetch_assoc($query)) {
		if ($_POST['mode'] == "message") {
			$output .= $row_template['text'];
		} elseif ($_POST['mode'] == "title") {
			$output .= $row_template['subject'];
		} elseif ($_POST['mode'] == "lang") {
			$output .= $row_template['lang'];
		}
	}
}

echo $output;

