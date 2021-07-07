<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: email-templates.php 66961 2021-05-24 13:26:45Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_white_label,admin_manage,admin_content,admin_communication,admin_finance");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TITLE'];

$form_error_object = new FormError();
$output = '';
if (vb($_GET['mode'])=='supprfile' ) {
    $output .=  supprime_fichier($_GET['id'], $_GET['file']);
}
$params = array('technical_code'=>true, 'intro'=>true, 'signature'=>true, 'site_id'=>true, 'image'=>true, 'emailLinksExplanations'=>true, 'create_template_href' => $GLOBALS['administrer_url'] . '/email-templates.php');
if (!empty($_GET['id'])) {
	// Modification d'un template
	$update_message = update_email_template($_POST, $_GET['id'], $form_error_object);
	$output .= modif_email_template_form($_POST, $_GET['id'], $update_message, $params);
} else {
	// on en transmet pas d'id de template
	$insert_message = insert_email_template($_POST, $form_error_object);
	$output .= insert_email_template_form($_POST, $insert_message, $params);
}

$output .= email_template_search_form($_GET);
$params_email_template_list = array('active' => true, 'technical_code' => true, 'site_id' => true, 'modif_href' => 'email-templates.php?id=');
$output .= get_email_template_list($_GET, $params_email_template_list);

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");


/**
 * Supprime le produit spécificié par $id. Il faut supprimer le produit puis les entrées correspondantes de la table produits_categories
 *
 * @param integer $id
 * @param mixed $file
 * @return
 */
function supprime_fichier($id, $file)
{
    /* Charge les infos du produit. */
    switch ($file) {
        case "image_haut":
            $sql = "SELECT image_haut AS image
                FROM peel_email_template
                WHERE id = '" . intval($id) . "'";
            $res = query($sql);
            $file = fetch_assoc($res);
            query("UPDATE peel_email_template
                SET image_haut = ''
                WHERE id = '" . intval($id) . "'");
            break;

            case "image_bas":
            $sql = "SELECT image_bas AS image
                FROM peel_email_template
                WHERE id = '" . intval($id) . "'";
            $res = query($sql);
            $file = fetch_assoc($res);
            query("UPDATE peel_email_template
                SET image_bas = ''
                WHERE id = '" . intval($id) . "'");
            break;
            
    }
    @unlink($GLOBALS['uploaddir'] . '/' . $file['image']);

    return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS["STR_ADMIN_FILE_DELETED"], $file['image'])))->fetch();
}