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
// $Id: database_email_template_text_ru.php 55746 2018-01-15 17:18:01Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["text"] = array(
  "signature_commercial" => "",
  "signature_comptabilite" => "",
  "signature_referencement" => "",
  "signature_informatique" => "",
  "signature_communication" => "",
  "signature_marketing" => "",
  "signature_direction" => "",
  "signature_externe" => "",
  "signature_support" => "",
  "download_product" => "",
  "commande_parrain_avoir" => "",
  "envoie_client_code_promo" => "",
  "insere_ticket" => "",
  "admin_info_payment_credit_card" => "",
  "admin_info_payment_credit_card_3_times" => "",
  "send_client_order_html" => "",
  "send_client_order_pdf" => "",
  "send_avis_expedition" => "",
  "email_commande" => "",
  "send_mail_order_admin" => "",
  "initialise_mot_passe" => "",
  "send_mail_for_account_creation" => "",
  "insere_avis" => "",
  "bons_anniversaires" => "",
  "direaunami_sent" => "",
  "cheques_cadeaux" => "",
  "cree_cheque_cadeau_friend" => "",
  "cree_cheque_cadeau_admin" => "",
  "cree_cheque_cadeau_client_type2" => "",
  "cree_cheque_cadeau_client_admin" => "",
  "gift_list" => "",
  "email_ordered_cadeaux" => "",
  "listecadeau_voir" => "",
  "parrainage" => "",
  "email_alerte" => "",
  "decremente_stock" => "",
  "admin_login" => "",
  "signature" => "",
  "cree_cheque_cadeau_client_type1" => "",
  "warn_admin_user_subscription" => "",
  "warn_admin_reve_subscription" => "",
"email_retour_virement" => "",
  "email_retour_avoir" => "",
  "email_reste_avoir_remboursement" => "",
  "email_remboursement" => "",
  "email_retour_client" => "",
  "cron_order_payment_failure_alerts" => "",
  "cron_order_not_paid_alerts" => "",
  "cron_update_contact_info" => "",
"inscription_newsletter" => "",
  "send_mail_for_account_creation_stop" => "",
  "send_mail_for_account_creation_reve" => "",
  "send_mail_for_account_creation_stand" => "",
  "send_mail_for_account_creation_affi" => "",
  "validating_registration_by_admin" => "",
  "confirm_newsletter_registration" => "",
  "user_double_optin_registration" => ""

);

