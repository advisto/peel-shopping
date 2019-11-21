<?php
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: check-php-integrity.php 61970 2019-11-20 15:48:40Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_white_label,admin");

include $GLOBALS['dirroot'] . '/lib/class/Integrity.php';

$GLOBALS['DOC_TITLE'] = 'Vérification de l\'existence de fichiers dans upload/ et suppression de la base de données si absents';
$menu_selected='index-various.php';
$output = '';
$integrity_object = new Integrity($GLOBALS['dirroot']);
$file_path = './integrity-hash.txt';

if(!file_exists($file_path) || !empty($_GET['update'])) {
	if($integrity_object->getMd5Hashes('integrity-hash.txt') > 0){
		echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message_to_escape' => "MD5 File Hashes Generated"))->fetch();
	} else {
		echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => "Error"))->fetch();
	}
}

$files = $integrity->checkMD5Hashes('integrity-hash.txt');
unset($integrity_object);

$output .= '
<h2>Modified Files</h2>
        <table>
            <thead>
                <tr>
                    <th>File</th>
                    <th>State</th>
                    <th>User ID</th>
                    <th>Group ID</th>
                    <th>Last Access</th>
                    <th>Last Modified</th>
                </tr>
            </thead>
            <tbody>
';
foreach($files as $file) {
	$output .= '
<tr>
                    <td>' . $file['filename'] . '</td>
                    <td>' . $file['stat'] . '</td>
                    <td>' . $file['uid'] . '</td>
                    <td>' . $file['gid'] . '</td>
                    <td>' . $file['lastAccess'] . '</td>
                    <td>' . $file['lastModification'] . '</td>
                </tr>
'; 
}
$output .= '
			</tbody>
        </table>
';
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

