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
// $Id: database_email_template_subject_en.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["subject"] = array(
  "signature_commercial" => "Commercial department signature",
  "signature_comptabilite" => "Accounting department signature",
  "signature_referencement" => "Referencing department signature",
  "signature_informatique" => "Technical department signature",
  "signature_communication" => "Communication department signature",
  "signature_marketing" => "Marketing department signature",
  "signature_direction" => "The direction signature",
  "signature_externe" => "External department signature",
  "signature_support" => "Support customers signature",
  "download_product" => "Download your order #[ORDER_ID]",
  "commande_parrain_avoir" => "Your credit following the order of your contact",
  "envoie_client_code_promo" => "In appreciation for your loyalty",
  "insere_ticket" => "Contact from [EMAIL] [TELEPHONE]",
  "admin_info_payment_credit_card" => "Order by credit card being recorded on [SITE]",
  "admin_info_payment_credit_card_3_times" => "Order by credit card three times during recording [SITE]",
  "send_client_order_html" => "Your order [ORDER_ID] on [SITE]",
  "send_client_order_pdf" => "Your order [ORDER_ID] on [SITE]",
  "send_avis_expedition" => "Shipping order #[ORDER_ID]",
  "email_commande" => "Order confirmation #[ORDER_ID]",
  "send_mail_order_admin" => "Record of the order #[ORDER_ID]",
  "initialise_mot_passe" => "New password for your customer account",
  "send_mail_for_account_creation" => "Your customer account",
  "insere_avis" => "A user has left a comment on [SITE]",
  "bons_anniversaires" => "[SITE] wishes you an happy birthday",
  "direaunami_sent" => "[PSEUDO] visited the website [SITE] and recommands you to have a look on it",
  "cheques_cadeaux" => "[EMAIL_ACHETEUR] offers you a voucher",
  "cree_cheque_cadeau_friend" => "[EMAIL] offers you a voucher",
  "cree_cheque_cadeau_admin" => "Creation of a voucher",
  "cree_cheque_cadeau_client_type2" => "[FRIEND] offers you a voucher",
  "cree_cheque_cadeau_client_admin" => "Creation of a voucher",
  "gift_list" => "Gift list",
  "email_ordered_cadeaux" => "Control your gift list \"[LIST_NAME]\"",
  "listecadeau_voir" => "Control your gift list \"[LIST_NAME]\"",
  "parrainage" => "[PSEUDO] wishes to sponsor you",
  "email_alerte" => "Product in stock on [SITE]",
  "decremente_stock" => "Alert notification STOCK",
  "admin_login" => "Login from [USER] [REMOTE_ADDR]",
  "signature" => "Automatic emails signature",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] offers you a voucher",
  "warn_admin_user_subscription" => "[CIVILITE] [PRENOM] [NOM_FAMILLE] just opened an account on [SITE_NAME]",
  "warn_admin_reve_subscription" => "The reseller [CIVILITE] [PRENOM] [NOM_FAMILLE] just opened an account on [SITE_NAME]",
  "email_retour_virement" => "Validation of your return number [RETURN_ID]",
  "email_retour_avoir" => "Validation of your return number [RETURN_ID]",
  "email_reste_avoir_remboursement" => "Repayment of your credit number [RETURN_ID]",
  "email_remboursement" => "Repayment of your return number [RETURN_ID]",
  "email_retour_client" => "Your return request",
  "cron_order_payment_failure_alerts" => "Help for your payment",
  "cron_order_not_paid_alerts" => "Payment of your order",
  "cron_update_contact_info" => "Confirmation of the validity of your information",
  "inscription_newsletter" => "Subscribe to the newsletter on [SITE]",
  "send_mail_for_account_creation_stop" => "Your reseller account",
  "send_mail_for_account_creation_reve" => "Change of status of your reseller account",
  "send_mail_for_account_creation_stand" => "Your affiliate account",
  "send_mail_for_account_creation_affi" => "Change in status of your affiliate account",
  "validating_registration_by_admin" => "Confirmation of account creation",
  "confirm_newsletter_registration" => "register [TYPE] from [SITE]",
  "user_double_optin_registration" => "Validation of the inscription on [SITE]"
);

