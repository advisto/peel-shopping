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
// $Id: database_email_template_text_fr.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["text"] = array(
  "signature_commercial" => "
Service commercial",
  "signature_comptabilite" => "
Service comptabilité",
  "signature_referencement" => "
Service référencement",
  "signature_informatique" => "
Support technique",
  "signature_communication" => "
Service communication",
  "signature_marketing" => "
Service marketing",
  "signature_direction" => "
La direction",
  "signature_externe" => "
Service externe",
  "signature_support" => "
Support Clientèle",
  "download_product" => "Bonjour,

Votre commande [ORDER_ID] ayant été validée, nous vous invitons à télécharger votre commande à partir du lien suivant :

Votre lien pour le téléchargement : [WWWROOT]/modules/download/telecharger.php?id=[ORDER_ID]&key=[CLE]

Vous pourrez télécharger ce fichier 1 fois.
Si vous rencontrez des difficultés lors du téléchargement, nous vous invitons à contacter [SUPPORT_COMMANDE] afin qu'il vous renvoie un lien de téléchargement.

Par ailleurs, nous vous invitons à éditer votre facture à partir du lien suivant :

[WWWROOT]/factures/commande_pdf.php?code_facture=[CODE_FACTURE]&mode=facture",
  "commande_parrain_avoir" => "Bonjour,

Suite à la commande d'un de vos filleuls sur notre boutique en ligne, nous vous faisons bénéficier d'un avoir de [AVOIR] à valoir sur votre prochaine commande.",
  "envoie_client_code_promo" => "Bonjour [CIVILITE] [PRENOM] [NOM_FAMILLE],

Pour vous remercier d'avoir commandé sur notre boutique en ligne nous vous offrons ce code promotionnel : [NOM_CODE_PROMO].

Il vous permet de bénéficier d'une promotion de [REMISE] à valoir sur votre prochaine commande.

Ce code promotionnel vous est spécialement destiné et est valable une seule fois à partir de maintenant jusqu'au [DATE_FIN].

Nous vous remercions pour votre confiance.",
  "insere_ticket" => "Bonjour,

Détail du message envoyé le [DATE] :

Nom : [NOM_FAMILLE]
Prénom : [PRENOM]
Société : [SOCIETE]
Adresse : [ADRESSE]
Tél : [TELEPHONE]
Email : [EMAIL]
Disponibilité : [DISPO]

Sujet : [SUJET]

Message :

[TEXTE]

IP : [REMOTE_ADDR]
",
  "admin_info_payment_credit_card" => "Bonjour, 

Une commande portant le numéro [ORDER_ID] vient d'être enregistrée sur [WWWROOT]/",
  "admin_info_payment_credit_card_3_times" => "Bonjour,

Une commande avec paiement en trois fois portant le numéro [ORDER_ID] vient d'être enregistrée sur [SITE]
",
  "send_client_order_html" => "Bonjour,

Nous vous invitons à ouvrir le lien suivant afin d'imprimer ou de régler votre commande sur le site [SITE] :

Votre bon de commande :
[URL_FACTURE]

Si le lien ne s'affiche pas correctement dans votre navigateur, veuillez le copier - coller. L'URL doit se terminer par mode=[MODE].

Votre commande sera traitée dès réception de votre règlement.

Nous vous remercions pour la confiance que vous accordez à [SITE]
",
  "send_client_order_pdf" => "Bonjour,

Nous vous invitons à ouvrir le lien suivant afin d'imprimer ou de régler votre commande sur le site [SITE] :

Votre bon de commande :
[URL_FACTURE]

Si le lien ne s'affiche pas correctement dans votre navigateur, veuillez le copier - coller. L'URL doit se terminer par 'mode=[MODE]'.

Votre commande sera traitée dès réception de votre règlement.

Nous vous remercions pour la confiance que vous accordez à [SITE]
",
  "send_avis_expedition" => "Bonjour [PRENOM] [NOM_FAMILLE],

Nous avons le plaisir de vous confirmer la préparation et la prochaine livraison de la commande n°[ORDER_ID].

Articles expédiés :
[SHIPPED_ITEMS]
Le mode de livraison que vous avez choisi lors de la prise de commande est : [TYPE]
Références de l'envoi : le n° de colis est [COLIS]. Pour La Poste, vous pouvez suivre l'acheminement de votre colis en cliquant sur le lien suivant : http://www.coliposte.fr/

Adresse d'expédition :
[CLIENT_INFOS_SHIP]

RAPPELS IMPORTANTS :

Nous vous invitons à suivre scrupuleusement nos instructions pour éviter tout litige. Le transport est une phase délicate qui demande une attention toute particulière.
Par conséquent, merci de vous conformer aux règles d'usages élémentaires rappelées ci-dessous :
- Colis en mauvais état
- colis ouvert et/ou écrasé
- système de fermeture (adhésif...) détérioré ou ne semblant pas d'origine

QUE FAIRE ?
- Ne pas ouvrir le colis
- refuser le colis
- émettre immédiatement les réserves d'usage auprès du transporteur
- nous signaler le problème en nous indiquant le numéro de commande concernée

REMBOURSEMENT
[SITE] refusera systématiquement le remboursement d'une commande si :
- aucune réserve n'a été émise auprès du transporteur
- aucune preuve de l'émission des réserves n'est fournie

Nous vous remercions de votre compréhension et restons à votre entière disposition pour tout complément d'information.",
  "email_commande" => "Bonjour [CIVILITE] [PRENOM] [NOM_FAMILLE],

Votre commande n°[ORDER_ID] du [DATE] a bien été enregistrée sur le site [SITE].

---------------------------
RAPPEL DE VOTRE COMMANDE
---------------------------

Montant : [MONTANT] TTC
Mode de paiement : [PAIEMENT]

---------------------------
Adresse de facturation
---------------------------
[CLIENT_INFOS_BILL]

---------------------------
Adresse de livraison
---------------------------
[CLIENT_INFOS_SHIP]

---------------------------
Articles commandés
---------------------------
[BOUGHT_ITEMS]
Frais d'expédition :
[COUT_TRANSPORT]
Type d'expédition :
[TYPE]

Vous pouvez suivre en temps réel l'état d'avancement de votre commande :
une fois votre commande réglée, la facture apparaîtra dans votre compte client au niveau du détail de la commande

Pour accéder à l'historique des commandes :
 - Cliquez sur MON COMPTE,
 - Identifiez-vous
 - Cliquez ensuite sur Historique des commandes.

Nous vous remercions pour votre confiance.",
  "send_mail_order_admin" => "Bonjour,

La commande [ORDER_ID] vient d'être enregistrée sur le site [SITE].

Email client : [EMAIL]
Référence commande : [ORDER_ID]
Montant de la commande : [MONTANT]
Date de la commande : [O_TIMESTAMP]
Paiement : [PAIEMENT]

Merci de consulter l'interface d'administration de votre site.
",
  "initialise_mot_passe" => "Bonjour,

Une demande de nouveau mot de passe sur le site [SITE] a été initialisée.

Pour confirmer votre demande de renouvellement de mot de passe, vous devez cliquer sur le lien suivant : [LINK]
Vous disposez de 24h après la demande de renouvellement pour effectuer cette opération. Passé ce délai, le lien ne sera plus valide.

Cet email a été envoyé automatiquement, merci de ne pas répondre à ce message.
",
  "send_mail_for_account_creation" => "Bonjour,

Vous venez de créer un compte client sur [SITE].

Votre identifiant est le suivant : [EMAIL]
Votre mot de passe est : [MOT_PASSE]
",
  "insere_avis" => "Bonjour,

[PRENOM] [NOM_FAMILLE] a ajouté le commentaire suivant :

Nom du produit : [NOM_PRODUIT]

Avis déposé : [AVIS]

Afin de valider cet avis, vous devez vous connecter à l'interface d'administration et modifier son statut dans la rubrique Webmastering > Marketing > Gérer les avis des internautes.",
  "bons_anniversaires" => "Bonjour [CIVILITE] [PRENOM] [NOM_FAMILLE],

Pour votre anniversaire, nous vous offrons ce code promotionnel : [NOM_CODE_PROMO].

Il vous permet de bénéficier de [REMISE] de réduction à valoir sur votre prochaine commande [MAIL_EXTRA_INFOS]

Ce code promotionnel vous est spécialement destiné et est valable une seule fois à partir d'aujourd'hui jusqu'au [DATE_FIN].

[SITE] vous souhaite un joyeux anniversaire.",
  "direaunami_sent" => "Bonjour [NOM_FAMILLE], 

[PSEUDO] a visité le site [SITE] et pense que vous trouverez cet article intéressant :

[PRODUCT_LINK]

Commentaires supplémentaires : 
------------------------------------ 
[COMMENTS]
------------------------------------",
  "cheques_cadeaux" => "Bonjour,

[EMAIL_ACHETEUR] a souhaité vous offrir un chèque cadeau !

Pour en bénéficier, connectez-vous simplement au site [WWWROOT]/ et utilisez votre code [CHECK_NAME].

Vous bénéficierez alors d'une remise de [REMISE_VALEUR] pour un montant minimum d'achat de [MONTANT_MIN] HT pour votre commande.",
  "cree_cheque_cadeau_friend" => "Bonjour,

Votre ami [PRENOM] [NOM_FAMILLE] a souhaité vous offrir un chèque cadeau sur le site [SITE].

Pour en bénéficier, connectez-vous simplement au site [WWWROOT]/ et utilisez votre code [CODE].

Vous bénéficierez alors d'une remise de [PRIX] TTC sur votre commande.
",
  "cree_cheque_cadeau_admin" => "Bonjour,

Le code promo [CODE] via le module de CHEQUE CADEAU vient d'être créé sur [SITE].
",
  "cree_cheque_cadeau_client_type2" => "Bonjour,

[SITE] vous offre un chèque cadeau valable 30 jours d'un montant de [MONTANT].

Pour en bénéficier, connectez-vous simplement au site [WWWROOT]/ et utilisez votre code [CODE].
",
  "cree_cheque_cadeau_client_admin" => "Bonjour,

Le code promotionnel [CODE] vient d'être créé via le module de parrainage sur [SITE].
",
  "gift_list" => "Bonjour,

Détail du message envoyé le [DATE]

[PRENOM] [NOM_FAMILLE] vous envoie sa liste de cadeaux : [GIFTLIST_NAME]

[GIFTLIST_ITEMS]
",
  "email_ordered_cadeaux" => "Bonjour,

[PRENOM] [NOM_FAMILLE] vient de passer commande à partir de votre liste de cadeaux [GIFTLIST_NAME].

Voici les articles commandés :
[GIFTLIST_ITEMS]",
  "listecadeau_voir" => "Bonjour,

Détail du message envoyé le [DATE].

[PRENOM] [NOM_FAMILLE] vous envoie sa liste de cadeaux [GIFTLIST_NAME] :
[URL_LISTE_CADEAU]

[GIFTLIST_ITEMS]",
  "parrainage" => "Bonjour,

[PSEUDO] vous invite à venir découvrir le site [SITE] et à bénéficier d'un avoir de [REBATE] sur votre première commande en validant votre compte client. 

Cliquez sur le lien suivant pour valider votre compte client : 
 [WWWROOT]/modules/parrainage/inscription.php?email=[EMAIL_FILLEUL]&code=[MDP]&id=[ID_UTILISATEUR]

Votre identifiant : [EMAIL_FILLEUL]
Votre mot de passe : [MDP]
",
  "email_alerte" => "Bonjour,

Le produit [NOM_PRODUIT] est actuellement en stock. Cliquez ici pour le découvrir : [URLPROD]
",
  "decremente_stock" => "Bonjour,

Le seuil d'alerte a été atteint pour le produit [NOM_PRODUIT].

Stock restant : [STOCK_RESTANT_APRES_DEMANDE]",
  "admin_login" => "Bonjour,

Nous vous envoyons cet email à la suite d'une connexion réussie d'un administrateur sur votre site.

Identifiant client : [USER]
IP de connexion : [REMOTE_ADDR]
Reverse DNS : [REVERSE_DNS]
Heure de connexion : [DATETIME]

Cet email à pour but de vous sensibiliser à la sécurité de votre site PEEL pour mieux la protéger.",
  "signature" => "

Le service client
[SITE]
[WWWROOT]/",
  "cree_cheque_cadeau_client_type1" => "Bonjour,

[SITE] vous offre un chèque cadeau valable 30 jours d'un montant de [PERCENT].

Pour en bénéficier, connectez-vous simplement au site [WWWROOT]/ et utilisez votre code [CODE].
",
  "warn_admin_user_subscription" => "Le [DATE], 

L'utilisateur suivant vient de s'inscrire :

[CIVILITE] [PRENOM] [NOM_FAMILLE]
[EMAIL]
[SOCIETE]
[TELEPHONE]
[PRIV]

[link=\"[ADMIN_URL]\"]Administrer le compte de cet utilisateur[/link]
",
  "warn_admin_reve_subscription" => "Nous vous informons que le revendeur [link=\"[ADMIN_URL]\"][CIVILITE] [PRENOM] [NOM_FAMILLE] [/link] s'est inscrit sur [SITE].

Ce compte est actuellement en statut \"Revendeur en attente\". Ce compte est inactif, et ne bénéficiera des tarifs revendeur que lorsque vous l'aurez passé en statut \"Revendeur\". 
",
"email_retour_virement" => "Bonjour,

Nous avons bien reçu votre retour numéro [RETURN_ID].
Conformément à votre choix, le montant correspondant, soit [MONTANT], vous sera remboursé par virement bancaire dans les plus brefs délais.",
  "email_retour_avoir" => "Bonjour,

Nous avons bien reçu votre retour numéro [RETURN_ID].
Conformément à votre choix, le montant correspondant vient d'être crédité sur votre compte, soit [MONTANT]. Cet [MODE] sera déduit automatiquement de vos prochaines commandes jusqu'à épuisement.",
  "email_reste_avoir_remboursement" => "Bonjour,

Nous avons bien reçu votre retour numéro [RETURN_ID].
Le remboursement ne pouvant exéder le montant de la commande, [MONTANT] vous sera remboursé par [MODE]. Le solde vient d'être crédité sur votre compte, soit [RESTE_AVOIR]. Cet avoir sera déduit automatiquement de vos prochaines commandes jusqu'à épuisement.",
  "email_remboursement" => "Bonjour,

Le remboursement de votre retour numéro [RETURN_ID], d'un montant de [MONTANT] vient d'être effectué par [MODE].",
  "email_retour_client" => "Bonjour,

Votre demande de retour a bien été enregistrée.
A présent, expédiez-nous les articles à l'adresse suivante :

[SOCIETE]
RETOUR NUMERO [RETURN_ID].

Nous vous rappelons que les articles retournés doivent être en bon état et dans leur emballage d'origine.
Dès réception de vos articles, nous validerons le remboursement de ces derniers.",
  "cron_order_payment_failure_alerts" => "Bonjour [PRENOM] [NOM],

NB : Si vous avez bien procédé au paiement et avez eu une confirmation de paiement, ne tenez pas compte de cet email car votre paiement sera validé manuellement très bientôt.

Nous vous écrivons à propos de la commande que vous avez essayé de payer récemment sur [WWWROOT]/

Nature de votre commande : [PRODUCT_NAME]
Montant total de votre commande : [TOTAL_AMOUNT]
Moyen de paiement que vous avez choisi : [PAYMENT_MEAN]

Notre système de traitement automatisé des commandes n'a pas reçu confirmation de votre paiement.
Quel problème avez-vous rencontré ?

Nous sommes à votre écoute pour valider cette commande éventuellement via un autre mode de paiement.

Nous nous tenons à votre disposition pour toutes informations supplémentaires.
Dans l'attente de votre réponse, nous vous souhaitons une bonne journée.

A bientôt sur [WWWROOT]/
L'équipe [SITE_NAME]

NB : Ce message vous est envoyé automatiquement. Si vous êtes déjà rentré en contact avec nous à propos de ce paiement, veuillez ne pas tenir compte de cet email.",
  "cron_order_not_paid_alerts" => "Bonjour [PRENOM] [NOM],

Nous vous écrivons à propos de la commande que vous avez passée il y a [DAYS_SINCE] jours sur [SITE_NAME].

Nature de votre commande : [PRODUCT_NAME]
Montant total de votre commande : [TOTAL_AMOUNT]
Moyen de paiement que vous avez choisi : [PAYMENT_MEAN]

Or nous n'avons pas encore reçu votre paiement. Avez-vous besoin d'informations complémentaires afin de nous faire parvenir votre paiement ?

Nous nous tenons à votre disposition pour toute information supplémentaire.

Dans l'attente de votre réponse, nous vous souhaitons une bonne journée.

NB : Ce message vous est envoyé automatiquement. Si vous êtes déjà rentré en contact avec nous à propos de ce paiement, veuillez ne pas tenir compte de cet email.",
  "cron_update_contact_info" => "Bonjour [CIVILITE] [NOM],

Afin de garder à jour les données associées à votre compte utilisateur, nous vous faisons parvenir le détail de vos informations pour vérification.

Détails de vos informations :

Email : [EMAIL]
Civilité : [CIVILITE]
Pseudo : [PSEUDO]
Prénom : [PRENOM]
Nom : [NOM]
Société : [SOCIETE]
N° TVA Intracom : [TVA_INTRA]
Téléphone : [TELEPHONE]
Portable : [PORTABLE]
Fax : [FAX]
Date de naissance : [NAISSANCE]
Adresse : [ADRESSE]
Code postal : [CODE_POSTAL]
Ville : [VILLE]
Pays : [PAYS]
Site web : [SITE_WEB]

Si des informations ci-dessus s'avèrent inexactes, nous vous prions de les mettre à jour en vous connectant à votre compte utilisateur sur [WWWROOT]/utilisateurs/change_params.php

Si vous avez oubliez votre mot de passe, merci d'utiliser l'outil de récupération accessible à partir du lien suivant : [WWWROOT]/utilisateurs/oubli_mot_passe.php

Nous vous rappelons que l'exactitude de ces informations est primordiale pour le bon fonctionnement du site et pour la bonne réussite commerciale de chacun. Toute information inexacte ou erronée peut entraîner l'annulation de votre compte utilisateur.

Pour toute information complémentaire, n'hésitez pas à nous contacter.",
"inscription_newsletter" => "Bonjour,

Merci pour votre inscription à la newsletter de [SITE_NAME]. Vous recevrez une newsletter hebdomadaire à l'adresse [EMAIL].

A bientôt sur [WWWROOT]/",
  "send_mail_for_account_creation_stop" => "Bonjour,

Votre inscription a bien été prise en compte sur [SITE] et doit être validée par un administrateur. Vous serez averti par email de la validation de votre compte et la connexion à votre compte ne sera possible qu'après cette validation.

Cordialement,",
  "send_mail_for_account_creation_reve" => "Bonjour,

Votre compte [EMAIL] sur [SITE] a été activé par un administrateur. Vous bénéficiez maintenant du statut \"[STATUT]\" et des avantages associés, et vous pouvez vous connecter à votre compte.

Cordialement,",
  "send_mail_for_account_creation_stand" => "Bonjour,

Votre inscription a bien été prise en compte sur [SITE] et doit être validée par un administrateur. Vous serez averti par email de la validation de votre compte et la connexion à votre compte ne sera possible qu'après cette validation.

Cordialement,",
  "send_mail_for_account_creation_affi" => "Bonjour,

Votre compte [EMAIL] sur [SITE] a été activé par un administrateur. Vous bénéficiez maintenant du statut \"[STATUT]\" et des avantages associés, et vous pouvez vous connecter à votre compte.

Cordialement,",
  "validating_registration_by_admin" => "Votre inscription sur [SITE] a été validée par un administrateur.",
  "confirm_newsletter_registration" => "Bonjour,

Vous vous êtes inscrit [TYPE] du site [SITE].
Pour confirmer cette inscription veuillez cliquer sur le lien suivant :
<a href='[CONFIRM_NEWSLETTER_REGISTER_LINK]'>[CONFIRM_NEWSLETTER_REGISTER_LINK]</a>",
  "user_double_optin_registration" => "Bonjour,

Vous venez de vous inscrire sur [SITE]. Rappel des informations transmises : 
[FIELDS]

Veuillez cliquer sur le lien ci dessous pour activer votre compte : 
<a href='[VALIDATION_LINK]'>[VALIDATION_LINK]</a>
"

);

