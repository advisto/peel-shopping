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
// $Id: database_email_template_subject_ro.php 55746 2018-01-15 17:18:01Z sdelaporte $

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
  "download_product" => "Descărcare comanda dvs. [ORDER_ID]",
  "commande_parrain_avoir" => "Factura dvs. de avoir  urmează la comanda  dvs.",
  "envoie_client_code_promo" => "Ca mulţumire a fidelităţii dvs.",
  "insere_ticket" => "Contactați prin  [EMAIL]",
  "admin_info_payment_credit_card" => "Comand CB în  curs de înregistrare",
  "admin_info_payment_credit_card_3_times" => "Comand CB în  trei exemplare în curs de înregistrare pe [SITE]",
  "send_client_order_html" => "Comanda dvs.  [ORDER_ID] pe  [SITE]",
  "send_client_order_pdf" => "Comanda dvs.  [ORDER_ID] pe  [SITE]",
  "send_avis_expedition" => "Avis de expeditie  de la comanda n°[ORDER_ID]",
  "email_commande" => "Confirmare comandă [ORDER_ID]",
  "send_mail_order_admin" => "Înregistrare de la commanda [ORDER_ID] pe [SITE]",
  "initialise_mot_passe" => "Noua parolă a contului dvs. de client",
  "send_mail_for_account_creation" => "Deschide contul dvs. client",
  "insere_avis" => "Un internaut a adăugat un comentariu pe [SITE]",
  "bons_anniversaires" => "[SITE] vă doreşte La mulţi ani",
  "direaunami_sent" => "[PSEUDO] a visitat situl  [SITE] şi vi-l  recomandă",
  "cheques_cadeaux" => "[EMAIL_ACHETEUR] vă oferă un cec cadou",
  "cree_cheque_cadeau_friend" => "[EMAIL] vă oferă un cec cadou",
  "cree_cheque_cadeau_admin" => "Crearea unui cec cadou",
  "cree_cheque_cadeau_client_type2" => "[FRIEND] vă oferă un cec cadou",
  "cree_cheque_cadeau_client_admin" => "Crearea unui cec cadou",
  "gift_list" => "Listă cadouri",
  "email_ordered_cadeaux" => "Comandă pe  lista dvs. Cadou \"[LIST_NAME]\"",
  "listecadeau_voir" => "Comandă pe  lista dvs. Cadou \"[LIST_NAME]\"",
  "parrainage" => "[PSEUDO] dorește să vă sponsorizeze",
  "email_alerte" => "Produs pe stoc pe [SITE]",
  "decremente_stock" => "Notificare alertă de STOC",
  "admin_login" => "Conexiune  a [USER] [REMOTE_ADDR]",
  "signature" => "Semnare automată emailuri ",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] vă oferă un cec cadou",
  "warn_admin_user_subscription" => "[CIVILITE] [PRENOM] [NOM_FAMILLE] s-a înscris pe  [SITE_NAME]",
  "warn_admin_reve_subscription" => "",
  "email_retour_virement" => "Validare retururului dvs. numarul  [RETURN_ID]",
  "email_retour_avoir" => "Validare retururului dvs. numarul  [RETURN_ID]",
  "email_reste_avoir_remboursement" => "Rambursarea  credit note -ului  dvs. numărul  [RETURN_ID]",
  "email_remboursement" => "Rambursarea  returului dvs. numărul  [RETURN_ID]",
  "email_retour_client" => "Comanda dvs. de retur",
  "cron_order_payment_failure_alerts" => "Ajutor pentru plata dvs.",
  "cron_order_not_paid_alerts" => "Plata comenzii dvs.",
  "cron_update_contact_info" => "Confirmarea validităţii coordonatelor dvs.",
  "inscription_newsletter" => "Abonare la newsletter [SITE]",
  "send_mail_for_account_creation_stop" => "",
  "send_mail_for_account_creation_reve" => "",
  "send_mail_for_account_creation_stand" => "",
  "send_mail_for_account_creation_affi" => "",
  "validating_registration_by_admin" => "",
  "confirm_newsletter_registration" => "",
  "user_double_optin_registration" => ""
);

