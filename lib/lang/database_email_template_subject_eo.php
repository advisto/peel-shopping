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
// $Id: database_email_template_subject_eo.php 55746 2018-01-15 17:18:01Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["subject"] = array(
  "signature_commercial" => "",
  "signature_comptabilite" => "",
  "signature_referencement" => "",
  "signature_informatique" => "",
  "signature_communication" => "",
  "signature_marketing" => "",
  "signature_direction" => "",
  "signature_externe" => "",
  "signature_support" => "",
  "download_product" => "Elŝuto de via mendo [ORDER_ID]",
  "commande_parrain_avoir" => "Via kredito sekve la mendon de via patronito",
  "envoie_client_code_promo" => "Danke pri via fideleco",
  "insere_ticket" => "Konatiĝo per [EMAIL]",
  "admin_info_payment_credit_card" => "Bankokarta mendo registranta",
  "admin_info_payment_credit_card_3_times" => "Bankokarta mendo trioble registranta ĉe [SITE]",
  "send_client_order_html" => "Via mendo [ORDER_ID] ĉe [SITE]",
  "send_client_order_pdf" => "Via mendo [ORDER_ID] ĉe [SITE]",
  "send_avis_expedition" => "Ekspeda avizo de la mendo n-o [ORDER_ID]",
  "email_commande" => "Konfirmo pri mendo [ORDER_ID]",
  "send_mail_order_admin" => "[ORDER_ID] Registro de mendo [SITE]",
  "initialise_mot_passe" => "Nova pasvorto de la klienta konto",
  "send_mail_for_account_creation" => "Kreado de via klienta konto",
  "insere_avis" => "Retano aldonis komenton ĉe [SITE]",
  "bons_anniversaires" => "[SITE] deziras vin bona naskiĝdatrevenon",
  "direaunami_sent" => "[PSEUDO] vizitis la retejon [SITE] kaj rekomendas ĝin al vi.",
  "cheques_cadeaux" => "[EMAIL_ACHETEUR] donas al vi donac-ĉekon",
  "cree_cheque_cadeau_friend" => "[EMAIL] donas al vi donac-ĉekon",
  "cree_cheque_cadeau_admin" => "Kreado de donac-ĉekon",
  "cree_cheque_cadeau_client_type2" => "[FRIEND] donas al vi donac-ĉekon",
  "cree_cheque_cadeau_client_admin" => "Kreado de donac-ĉekon",
  "gift_list" => "Listo de donacoj",
  "email_ordered_cadeaux" => "Mendo ĉe via listo de donacoj \"[LIST_NAME]\"",
  "listecadeau_voir" => "Mendo ĉe via listo de donacoj \"[LIST_NAME]\"",
  "parrainage" => "[PSEUDO] deziras patroni vin",
  "email_alerte" => "Varoj en la stoko ĉe [SITE]",
  "decremente_stock" => "Avertilo pri stoko-nivelo",
  "admin_login" => "Konektiĝo de [USER] [REMOTE_ADDR]",
  "signature" => "Aŭtomataj retpoŝtaj subskriboj",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] donas al vi donac-ĉekon",
  "warn_admin_user_subscription" => "[CIVILITE] [PRENOM] [NOM_FAMILLE] ĵus aliĝis ĉe [SITE_NAME]",
  "warn_admin_reve_subscription" => "",
  "email_retour_virement" => "Validigo de via returno n-o [RETURN_ID]",
  "email_retour_avoir" => "Validigo de via returno n-o [RETURN_ID] - Kreado de kredito",
  "email_reste_avoir_remboursement" => "Repago de via kredito n-o [RETURN_ID]",
  "email_remboursement" => "Repago de via returno [Return_ID]",
  "email_retour_client" => "Via peto pri returno",
  "cron_order_payment_failure_alerts" => "Helpo pri pago",
  "cron_order_not_paid_alerts" => "Pago de via mendo",
  "cron_update_contact_info" => "Konfirmo pri valido de viaj kontaktinformoj",
  "inscription_newsletter" => "Aliĝo al la novaĵ-bulteno ĉe [SITE]",
  "send_mail_for_account_creation_stop" => "",
  "send_mail_for_account_creation_reve" => "",
  "send_mail_for_account_creation_stand" => "",
  "send_mail_for_account_creation_affi" => "",
  "validating_registration_by_admin" => "",
  "confirm_newsletter_registration" => "",
  "user_double_optin_registration" => ""
);

