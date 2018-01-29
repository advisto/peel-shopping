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
// $Id: database_email_template_name_nl.php 55746 2018-01-15 17:18:01Z sdelaporte $

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
  "download_product" => "Downloaden van uw bestelling",
  "commande_parrain_avoir" => "Uw tegoed over de bestelling van uw contact",
  "envoie_client_code_promo" => "Als een beloning voor uw loyaliteit",
  "insere_ticket" => "Contactformulier [EMAIL] [TELEPHONE]",
  "admin_info_payment_credit_card" => "Opdracht per creditcard is geregistreerd",
  "admin_info_payment_credit_card_3_times" => "Opdracht per creditcard is drie maal geregistreerd",
  "send_client_order_html" => "Uw bestelling [ORDER_ID] op [SITE] met factuur HTML",
  "send_client_order_pdf" => "Uw bestelling [ORDER_ID] op [SITE] met factuur PDF",
  "send_avis_expedition" => "Verzendopdracht van bestelling [ORDER_ID]",
  "email_commande" => "Bevestiging van de bestelling [ORDER_ID]",
  "send_mail_order_admin" => "[ORDER_ID] Registratie van bestelling [SITE]",
  "initialise_mot_passe" => "Nieuw wachtwoord voor uw account",
  "send_mail_for_account_creation" => "Uw account",
  "insere_avis" => "Een gebruiker heeft een bericht achtergelaten op [SITE] ",
  "bons_anniversaires" => "[SITE] wenst u een fijne verjaardag",
  "direaunami_sent" => "[PSEUDO] heeft de website [SITE] bezocht en raad u deze aan",
  "cheques_cadeaux" => "[EMAIL_ACHETEUR] biedt u een cadeaubon aan",
  "cree_cheque_cadeau_friend" => "[EMAIL] biedt u een cadeaubon aan",
  "cree_cheque_cadeau_admin" => "Creëren van een cadeaubon",
  "cree_cheque_cadeau_client_type2" => "[FRIEND] biedt u een cadeaubon aan (bedrag)",
  "cree_cheque_cadeau_client_admin" => "Creëren van een cadeaubon",
  "gift_list" => "Lijst van geschenken",
  "email_ordered_cadeaux" => "Opdracht in uw giftlijst \"[GIFTLIST_NAME]\"",
  "listecadeau_voir" => "Opdracht in uw giftlijst \"[GIFTLIST_NAME]\"",
  "parrainage" => "[PSEUDO] wil u sponsoren",
  "email_alerte" => "Product op voorraad op [SITE]",
  "decremente_stock" => "Waarschuwingsbericht STOCK",
  "admin_login" => "Beheerder inlog info",
  "signature" => "Automatische e-mail handtekening",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] biedt u een cadeaubon aan (percentage)",
  "warn_admin_user_subscription" => "Waarschuwing van de gebruikersregistratie",
  "warn_admin_reve_subscription" => "",
  "email_retour_virement" => "Validatie van uw retour nummer [RETURN_ID]",
  "email_retour_avoir" => "Validatie van uw retour nummer [RETURN_ID]",
  "email_reste_avoir_remboursement" => "Terugbetaling van uw krediet nummer [RETURN_ID]",
  "email_remboursement" => "Terugbetaling van uw retour nummer [RETURN_ID]",
  "email_retour_client" => "Uw retour aanvraag",
  "cron_order_payment_failure_alerts" => "Help voor uw betaling",
  "cron_order_not_paid_alerts" => "Betaling van uw bestelling",
  "cron_update_contact_info" => "Bevestiging van de geldigheid van uw informatie",
  "inscription_newsletter" => "",
  "send_mail_for_account_creation_stop" => "",
  "send_mail_for_account_creation_reve" => "",
  "send_mail_for_account_creation_stand" => "",
  "send_mail_for_account_creation_affi" => "",
  "validating_registration_by_admin" => "",
  "confirm_newsletter_registration" => "",
  "user_double_optin_registration" => ""
);

