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
// $Id: database_email_template_subject_fr.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["subject"] = array(
  "signature_commercial" => "Signature service commercial",
  "signature_comptabilite" => "Signature service comptabilité",
  "signature_referencement" => "Signature service référencement",
  "signature_informatique" => "Signature support technique",
  "signature_communication" => "Signature service communication",
  "signature_marketing" => "Signature service marketing",
  "signature_direction" => "Signature la direction",
  "signature_externe" => "Signature service externe",
  "signature_support" => "Signature support Clientèle",
  "download_product" => "Téléchargement de votre commande [ORDER_ID]",
  "commande_parrain_avoir" => "Votre avoir suite à la commande de votre filleul",
  "envoie_client_code_promo" => "En remerciement de votre fidélité",
  "insere_ticket" => "Prise de contact par [EMAIL] [TELEPHONE]",
  "admin_info_payment_credit_card" => "Commande CB en cours d'enregistrement",
  "admin_info_payment_credit_card_3_times" => "Commande CB en trois fois en cours d'enregistrement sur [SITE]",
  "send_client_order_html" => "Votre commande [ORDER_ID] sur [SITE]",
  "send_client_order_pdf" => "Votre commande [ORDER_ID] sur [SITE]",
  "send_avis_expedition" => "Avis d'expédition de la commande n°[ORDER_ID]",
  "email_commande" => "Confirmation de la commande n°[ORDER_ID]",
  "send_mail_order_admin" => "Enregistrement de la commande n°[ORDER_ID] sur [SITE]",
  "initialise_mot_passe" => "Nouveau mot de passe de votre compte client",
  "send_mail_for_account_creation" => "Ouverture de votre compte client",
  "insere_avis" => "Un internaute a ajouté un commentaire sur [SITE]",
  "bons_anniversaires" => "[SITE] vous souhaite un joyeux anniversaire",
  "direaunami_sent" => "[PSEUDO] a visité le site [SITE] et vous le recommande",
  "cheques_cadeaux" => "[EMAIL_ACHETEUR] vous offre un chèque cadeau",
  "cree_cheque_cadeau_friend" => "[EMAIL] vous offre un chèque cadeau",
  "cree_cheque_cadeau_admin" => "Création d'un chèque cadeau",
  "cree_cheque_cadeau_client_type2" => "[FRIEND] vous offre un chèque cadeau",
  "cree_cheque_cadeau_client_admin" => "Création d'un chèque cadeau",
  "gift_list" => "Liste de cadeaux",
  "email_ordered_cadeaux" => "Commande sur votre liste de cadeaux \"[LIST_NAME]\"",
  "listecadeau_voir" => "Envoi de liste de cadeaux \"[LIST_NAME]\"",
  "parrainage" => "[PSEUDO] souhaite vous parrainer",
  "email_alerte" => "Produit en stock sur [SITE]",
  "decremente_stock" => "Notification d'alerte STOCK",
  "admin_login" => "Connexion de [USER] [REMOTE_ADDR]",
  "signature" => "Signature emails automatiques",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] vous offre un chèque cadeau",
  "warn_admin_user_subscription" => "[CIVILITE] [PRENOM] [NOM_FAMILLE] vient de s'inscrire sur [SITE_NAME]",
  "warn_admin_reve_subscription" => "Le revendeur [CIVILITE] [PRENOM] [NOM_FAMILLE] vient de s'inscrire sur [SITE_NAME]",
  "email_retour_virement" => "Validation de votre retour numéro [RETURN_ID] - Virement bancaire",
  "email_retour_avoir" => "Validation de votre retour numéro [RETURN_ID] - Création d'avoir",
  "email_reste_avoir_remboursement" => "Remboursement de votre avoir numéro [RETURN_ID]",
  "email_remboursement" => "Remboursement de votre retour numéro[RETURN_ID]",
  "email_retour_client" => "Votre demande de retour",
  "cron_order_payment_failure_alerts" => "Aide pour votre paiement",
  "cron_order_not_paid_alerts" => "Paiement de votre commande",
  "cron_update_contact_info" => "Confirmation de la validité de vos coordonnées",
  "inscription_newsletter" => "Inscription à la newsletter sur [SITE]",
  "send_mail_for_account_creation_stop" => "Ouverture de votre compte revendeur",
  "send_mail_for_account_creation_reve" => "Changement de statut de votre compte revendeur",
  "send_mail_for_account_creation_stand" => "Ouverture de votre compte affilié",
  "send_mail_for_account_creation_affi" => "Changement de statut de votre compte affilié",
  "validating_registration_by_admin" => "Confirmation de la création de compte",
  "confirm_newsletter_registration" => "inscription [TYPE] de [SITE]",
  "user_double_optin_registration" => "Validation de l'inscription sur [SITE]"
);

