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
// $Id: database_email_template_name_de.php 55746 2018-01-15 17:18:01Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["name"] = array(
  "signature_commercial" => "",
  "signature_comptabilite" => "",
  "signature_referencement" => "",
  "signature_informatique" => "",
  "signature_communication" => "",
  "signature_marketing" => "",
  "signature_direction" => "",
  "signature_externe" => "",
  "signature_support" => "",
  "download_product" => "Download Ihrer Bestellung",
  "commande_parrain_avoir" => "Ihr Guthaben nach der Bestellung Ihres Kontakts",
  "envoie_client_code_promo" => "Als Dank für Ihre Treue",
  "insere_ticket" => "Kontaktformular [EMAIL] [TELEPHONE]",
  "admin_info_payment_credit_card" => "Kreditkartenauftrag ist registriert",
  "admin_info_payment_credit_card_3_times" => "Kreditkartenauftrag ist dreimal registriert",
  "send_client_order_html" => "Ihre Bestellung [ORDER_ID] auf [SITE] mit HTML-Rechnung",
  "send_client_order_pdf" => "Ihre Bestellung [ORDER_ID] auf [SITE] mit PDF-Rechnung",
  "send_avis_expedition" => "Versandnachweis von Bestellung [ORDER_ID]",
  "email_commande" => "Bestellbestätigung Nr.[ORDER_ID]",
  "send_mail_order_admin" => "Registrierung der BestellungNr.[ORDER_ID] auf [SITE]",
  "initialise_mot_passe" => "Neues Kennwort für Ihr Konto",
  "send_mail_for_account_creation" => "Öffnung Ihres Kontos",
  "insere_avis" => "Ein Nutzer hat auf [SITE] einen Kommentar hinterlassen",
  "bons_anniversaires" => "[SITE] wünscht Ihnen alles Gute zum Geburtstag",
  "direaunami_sent" => "[PSEUDO] hat die Website [SITE] besucht und empfiehlt sie Ihnen",
  "cheques_cadeaux" => "[EMAIL_ACHETEUR] offeriert Ihnen einen Geschenkgutschein",
  "cree_cheque_cadeau_friend" => "[EMAIL] offeriert Ihnen einen Geschenkgutschein",
  "cree_cheque_cadeau_admin" => "Erzeugen eines Geschenkgutscheins",
  "cree_cheque_cadeau_client_type2" => "[FRIEND] offeriert Ihnen einen Geschenkgutschein (Betrag)",
  "cree_cheque_cadeau_client_admin" => "Erzeugen eines Geschenkgutscheins",
  "gift_list" => "Wunschliste",
  "email_ordered_cadeaux" => "Bestellung von Ihrer Wunschliste \"[GIFTLIST_NAME]\"",
  "listecadeau_voir" => "Bestellung von Ihrer Wunschliste \"[GIFTLIST_NAME]\"",
  "parrainage" => "[PSEUDO] möchte Sie sponsern",
  "email_alerte" => "Produkt vorrätig auf [SITE]",
  "decremente_stock" => "Warnhinweis STOCK",
  "admin_login" => "Verwalter-Login",
  "signature" => "Automatische E-Mail-Signatur",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] offeriert Ihnen einen Geschenkgutschein (Prozent)",
  "warn_admin_user_subscription" => "Hinweis auf Registrierung eines Nutzers",
  "warn_admin_reve_subscription" => "",
  "email_retour_virement" => "Validierung Ihrer Rücksendung [RETURN_ID]",
  "email_retour_avoir" => "Validierung Ihrer Rücksendung [RETURN_ID]",
  "email_reste_avoir_remboursement" => "Erstattung Ihres Guthabens [RETURN_ID]",
  "email_remboursement" => "Erstattung Ihrer Rücksendung [RETURN_ID]",
  "email_retour_client" => "Ihr Rücksendungsantrag",
  "cron_order_payment_failure_alerts" => "Hilfe bei der Bezahlung",
  "cron_order_not_paid_alerts" => "Bezahlung Ihrer Bestellung",
  "cron_update_contact_info" => "Bestätigung der Gültigkeit Ihrer Angaben",
  "inscription_newsletter" => "",
  "send_mail_for_account_creation_stop" => "",
  "send_mail_for_account_creation_reve" => "",
  "send_mail_for_account_creation_stand" => "",
  "send_mail_for_account_creation_affi" => "",
  "validating_registration_by_admin" => "",
  "confirm_newsletter_registration" => "",
  "user_double_optin_registration" => ""
);

