<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2012 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_email_template_subject_de.php 35334 2013-02-16 21:00:02Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["subject"] = array(
  "download_product" => "Download Ihrer Bestellung [ORDER_ID]",
  "commande_parrain_avoir" => "Ihr Guthaben nach der Bestellung Ihres Kontakts",
  "envoie_client_code_promo" => "Als Dank für Ihre Treue",
  "insere_ticket" => "Kontaktformular [EMAIL] [TELEPHONE]",
  "admin_info_payment_credit_card" => "Kreditkartenauftrag ist registriert",
  "admin_info_payment_credit_card_3_times" => "Bestellung per Kreditkarte ist dreimal registriert auf [SITE]",
  "send_client_order_html" => "Ihre Bestellung ORDER_ID] auf [SITE]",
  "send_client_order_pdf" => "Ihre Bestellung [ORDER_ID] auf [SITE]",
  "send_avis_expedition" => "Versandnachweis von Bestellung [ORDER_ID]",
  "email_commande" => "Bestellbestätigung [ORDER_ID]",
  "send_mail_order_admin" => "[ORDER_ID] Registrierung der Bestellung [SITE]",
  "initialise_mot_passe" => "Neues Kennwort für Ihr Konto",
  "send_mail_for_account_creation" => "Öffnung Ihres Kontos",
  "insere_avis" => "Ein Nutzer hat auf [SITE] einen Kommentar hinterlassen",
  "bons_anniversaires" => "[SITE] wünscht Ihnen alles Gute zum Geburtstag",
  "direaunami_sent" => "[PSEUDO] hat die Website [SITE] besucht und empfiehlt sie Ihnen",
  "cheques_cadeaux" => "[EMAIL_ACHETEUR] offeriert Ihnen einen Geschenkgutschein",
  "cree_cheque_cadeau_friend" => "[EMAIL] offeriert Ihnen einen Geschenkgutschein",
  "cree_cheque_cadeau_admin" => "Erzeugen eines Geschenkgutscheins",
  "cree_cheque_cadeau_client_type2" => "[FRIEND] offeriert Ihnen einen Geschenkgutschein",
  "cree_cheque_cadeau_client_admin" => "Erzeugen eines Geschenkgutscheins",
  "gift_list" => "Wunschliste",
  "email_ordered_cadeaux" => "Bestellung von Ihrer Wunschliste \"[LIST_NAME]\"",
  "listecadeau_voir" => "Bestellung von Ihrer Wunschliste \"[LIST_NAME]\"",
  "parrainage" => "[PSEUDO] möchte Sie sponsern",
  "email_alerte" => "Produkt vorrätig auf [SITE]",
  "decremente_stock" => "Warnhinweis STOCK",
  "admin_login" => "Login von [USER] [REMOTE_ADDR]",
  "signature" => "Automatische E-Mail-Signatur",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] offeriert Ihnen einen Geschenkgutschein",
  "warn_admin_user_subscription" => "[CIVILITE] [PRENOM] [NOM_FAMILLE] hat sich eingetragen auf [SITE_NAME]",
  "email_retour_virement" => "Validierung Ihrer Rücksendung [RETURN_ID]",
  "email_retour_avoir" => "Validierung Ihrer Rücksendung [RETURN_ID]",
  "email_reste_avoir_remboursement" => "Erstattung Ihrer Rücksendung [RETURN_ID]",
  "email_remboursement" => "Erstattung Ihrer Rücksendung [RETURN_ID]",
  "email_retour_client" => "Ihr Rücksendeantrag"
);

?>