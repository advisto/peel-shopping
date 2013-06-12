<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_email_template_subject_fr.php 36927 2013-05-23 16:15:39Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["subject"] = array(
  "download_product" => "Téléchargement de votre commande [ORDER_ID]",
  "commande_parrain_avoir" => "Votre avoir suite à la commande de votre filleul",
  "envoie_client_code_promo" => "En remerciement de votre fidélité",
  "insere_ticket" => "Prise de contact par [EMAIL] [TELEPHONE]",
  "admin_info_payment_credit_card" => "Commande CB en cours d'enregistrement",
  "admin_info_payment_credit_card_3_times" => "Commande CB en trois fois en cours d'enregistrement sur [SITE]",
  "send_client_order_html" => "Votre commande [ORDER_ID] sur [SITE]",
  "send_client_order_pdf" => "Votre commande [ORDER_ID] sur [SITE]",
  "send_avis_expedition" => "Avis d'expédition de la commande n°[ORDER_ID]",
  "email_commande" => "Confirmation de la commande [ORDER_ID]",
  "send_mail_order_admin" => "[ORDER_ID] Enregistrement de la commande [SITE]",
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
  "email_retour_virement" => "Validation de votre retour numéro [RETURN_ID] - Virement bancaire",
  "email_retour_avoir" => "Validation de votre retour numéro [RETURN_ID] - Création d'avoir",
  "email_reste_avoir_remboursement" => "Remboursement de votre avoir numéro [RETURN_ID]",
  "email_remboursement" => "Remboursement de votre retour numéro[RETURN_ID]",
  "email_retour_client" => "Votre demande de retour",
  "cron_order_payment_failure_alerts" => "[SITE_NAME] - Aide pour votre paiement",
  "cron_order_not_paid_alerts" => "[SITE_NAME] - Paiement de votre commande",
  "cron_update_contact_info" => "[SITE_NAME] - Confirmation de la validité de vos coordonnées"
);

?>