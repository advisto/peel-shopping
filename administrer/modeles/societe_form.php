<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: societe_form.php 36232 2013-04-05 13:16:01Z gboussin $

if (!defined('IN_PEEL')) {
	die();
}

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_societe_form.tpl');
$tpl->assign('action', get_current_url(false));
$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval(vb($frm['id']))));
$tpl->assign('mode', vb($frm['nouveau_mode']));
$tpl->assign('id', intval(vb($frm['id'])));
$tpl->assign('societe', vb($frm['societe']));
$tpl->assign('prenom', vb($frm['prenom']));
$tpl->assign('nom', vb($frm['nom']));
$tpl->assign('email', vb($frm['email']));
$tpl->assign('siteweb', vb($frm['siteweb']));
$tpl->assign('tel', vb($frm['tel']));
$tpl->assign('fax', vb($frm['fax']));
$tpl->assign('siren', vb($frm['siren']));
$tpl->assign('tvaintra', vb($frm['tvaintra']));
$tpl->assign('cnil', vb($frm['cnil']));
$tpl->assign('adresse', vb($frm['adresse']));
$tpl->assign('code_postal', vb($frm['code_postal']));
$tpl->assign('ville', vb($frm['ville']));
$tpl->assign('pays', vb($frm['pays']));
$tpl->assign('code_banque', vb($frm['code_banque']));
$tpl->assign('code_guichet', vb($frm['code_guichet']));
$tpl->assign('numero_compte', vb($frm['numero_compte']));
$tpl->assign('cle_rib', vb($frm['cle_rib']));
$tpl->assign('iban', vb($frm['iban']));
$tpl->assign('swift', vb($frm['swift']));
$tpl->assign('titulaire', vb($frm['titulaire']));
$tpl->assign('domiciliation', vb($frm['domiciliation']));
$tpl->assign('pays2', vb($frm['pays2']));
$tpl->assign('ville2', vb($frm['ville2']));
$tpl->assign('adresse2', vb($frm['adresse2']));
$tpl->assign('code_postal2', vb($frm['code_postal2']));
$tpl->assign('tel2', vb($frm['tel2']));
$tpl->assign('fax2', vb($frm['fax2']));
$tpl->assign('titre_soumet', vb($frm['titre_soumet']));
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$tpl->assign('STR_ADMIN_SOCIETE_FORM_COMPANY_PARAMETERS', $GLOBALS['STR_ADMIN_SOCIETE_FORM_COMPANY_PARAMETERS']);
$tpl->assign('STR_ADMIN_SOCIETE_FORM_EXPLAIN', $GLOBALS['STR_ADMIN_SOCIETE_FORM_EXPLAIN']);
$tpl->assign('STR_ADMIN_SOCIETE_FORM_SECOND_ADDRESS', $GLOBALS['STR_ADMIN_SOCIETE_FORM_SECOND_ADDRESS']);
$tpl->assign('STR_COUNTRY', $GLOBALS['STR_COUNTRY']);
$tpl->assign('STR_TOWN', $GLOBALS['STR_TOWN']);
$tpl->assign('STR_ADDRESS', $GLOBALS['STR_ADDRESS']);
$tpl->assign('STR_ZIP', $GLOBALS['STR_ZIP']);
$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
$tpl->assign('STR_COMPANY', $GLOBALS['STR_COMPANY']);
$tpl->assign('STR_FIRST_NAME', $GLOBALS['STR_FIRST_NAME']);
$tpl->assign('STR_NAME', $GLOBALS['STR_NAME']);
$tpl->assign('STR_EMAIL', $GLOBALS['STR_EMAIL']);
$tpl->assign('STR_WEBSITE', $GLOBALS['STR_WEBSITE']);
$tpl->assign('STR_TELEPHONE', $GLOBALS['STR_TELEPHONE']);
$tpl->assign('STR_FAX', $GLOBALS['STR_FAX']);
$tpl->assign('STR_SIREN', $GLOBALS['STR_SIREN']);
$tpl->assign('STR_VAT_INTRACOM', $GLOBALS['STR_VAT_INTRACOM']);
$tpl->assign('STR_CNIL_NUMBER', $GLOBALS['STR_CNIL_NUMBER']);
$tpl->assign('STR_BANK_ACCOUNT_CODE', $GLOBALS['STR_BANK_ACCOUNT_CODE']);
$tpl->assign('STR_BANK_ACCOUNT_RIB', $GLOBALS['STR_BANK_ACCOUNT_RIB']);
$tpl->assign('STR_BANK_ACCOUNT_COUNTER', $GLOBALS['STR_BANK_ACCOUNT_COUNTER']);
$tpl->assign('STR_BANK_ACCOUNT_NUMBER', $GLOBALS['STR_BANK_ACCOUNT_NUMBER']);
$tpl->assign('STR_IBAN', $GLOBALS['STR_IBAN']);
$tpl->assign('STR_SWIFT', $GLOBALS['STR_SWIFT']);
$tpl->assign('STR_ACCOUNT_MASTER', $GLOBALS['STR_ACCOUNT_MASTER']);
$tpl->assign('STR_BANK_ACCOUNT_DOMICILIATION', $GLOBALS['STR_BANK_ACCOUNT_DOMICILIATION']);
echo $tpl->fetch();

?>