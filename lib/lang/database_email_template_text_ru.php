<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_email_template_text_ru.php 39392 2013-12-20 11:08:42Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template['text'] = array(
  "signature_commercial" => "",
  "signature_comptabilite" => "",
  "signature_referencement" => "",
  "signature_informatique" => "",
  "signature_communication" => "",
  "signature_marketing" => "",
  "signature_direction" => "",
  "signature_externe" => "",
  "signature_support" => "",
  "download_product" => "Hello,

Your order #[ORDER_ID] has been validated. We invite you to download your order from the link below:

Your link to download: [WWWROOT]/modules/download/telecharger.php?id=[ORDER_ID]&key=[CLE]

You can download this file only once. If you encounter difficulties while downloading your order, we advise you to contact [SUPPORT_COMMANDE] so that it gives you a new download link.

We also invite you to edit your invoice from the following link:

[WWWROOT]/factures/commande_pdf.php?code_facture=[CODE_FACTURE]&mode=facture",
  "commande_parrain_avoir" => "Hello,

Following the order placed by one of your contacts on our shop online, you can now benefit from a credit of [AVOIR] on your next order.",
  "envoie_client_code_promo" => "Hello [CIVILITE] [PRENOM] [NOM_FAMILLE],

In order to thank you for your order on our online shop, we are offering you this promotional code: [NOM_CODE_PROMO].

It allows you to benefit from a [REMISE] discount on your next order.

This promotional code can only be used by you once, and you can use it from now on until the following date [DATE_FIN].",
  "insere_ticket" => "Hello,

Details of the message sent on [DATE]:

Name: [NOM_FAMILLE]
Firstname: [PRENOM]
Company name: [SOCIETE]
Phone number: [TELEPHONE]
Email address: [EMAIL]
Availability: [DISPO]

Subject: [SUJET]

Message:

[TEXTE]

IP: [REMOTE_ADDR]
",
  "admin_info_payment_credit_card" => "Hello,

The order associated with the following number [ORDER_ID] has just been recorded on [WWWROOT]",
  "admin_info_payment_credit_card_3_times" => "Hello,

The order associated with the following number [ORDER_ID] has just been recorded on [SITE]
",
  "send_client_order_html" => "Hello,

We invite you to open the following link in order to either print or pay your order on the website [SITE]:

Your order form:
[URL_FACTURE]

If the link does not appear correctly in your web browser, please copy and paste it. The URL must end by mode=[MODE].

Your order will be processed as soon as we receive your payment.",
  "send_client_order_pdf" => "Hello,

We invite you to open the following link in order to either print or pay your order on the website [SITE]:

Your order form:
[URL_FACTURE]

If the link does not appear correctly in your web browser, please copy and paste it. The URL must end by 'mode=[MODE]'.

Your order will be processed as soon as we receive your payment.",
  "send_avis_expedition" => "Hello [PRENOM] [NOM_FAMILLE],

We are pleased to confirm the preparation and shipping of your order #[ORDER_ID].

Shipped items:
[SHIPPED_ITEMS]
The shipping method you have chosen during the order process is: [TYPE].
Shipping references: The parcel number is [COLIS], you can follow the shipping of your parcel by clicking on the following link: http://www.coliposte.fr/

Shipping address:
[CLIENT_INFOS_SHIP]

IMPORTANT REMINDER:
Please follow our instructions in order to avoid any problem. The shipping is a sensitive step which requires a specific attention.
As a result, thanks to conform to the elementary rules reminded below:
- Damaged parcel
- Opened and/or broken parcel
- Closing system (adhesive...) damages or not original

WHAT TO DO?
- Do not open the parcel
- refuse the parcel
- immediately warn the shipper
- warn us and give us the number of the concerned order

REIMBURSEMENT
[SITE] will refuse systematically the repayment of an order if:
- no warning has been made to the shipper
- no proof of the warning is provided

Thank for your understanding, please do not hesitate to contact us if you have any queries.",
  "email_commande" => "Hello [CIVILITE] [PRENOM] [NOM_FAMILLE],

Your order #[ORDER_ID] made on [DATE] has been saved on the website [SITE].

---------------------------
YOUR ORDER SUMMARY
---------------------------

amount: [MONTANT] EUR VAT INCLUDED
Payment means [PAIEMENT]: 

---------------------------
Billing address
---------------------------
[CLIENT_INFOS_BILL]

---------------------------
Shipping address
---------------------------
[CLIENT_INFOS_SHIP]

---------------------------
Bought items
---------------------------
[BOUGHT_ITEMS]

Shipping fees
[COUT_TRANSPORT]

You can follow the status of your order:

Once your order is paid for, your invoice will appear in your account in your order details

To access your order history:
 - Click on MY ACCOUNT,
 - Please log in
 - Then click on your order history.

Thank you for your trust.

See you soon on [SITE]",
  "send_mail_order_admin" => "Hello,

The following order [ORDER_ID] has just been recorded on [SITE]

Email address: [EMAIL]
Reference number: [ORDER_ID]
Order amount: [MONTANT]
Order date: [O_TIMESTAMP]
Payment: [PAIEMENT]

Please consult the administration interface of your web site. ",
  "initialise_mot_passe" => "Hello,

A request of a new password on the site [SITE] has been initiated.

To confirm your password renewal application, you must click the following link: [LINK]
You have 24 hours after the renewal application to perform this operation. Afterwards, the link will no longer be valid.
This email was sent automatically, thank you not to reply to this message.
",
  "send_mail_for_account_creation" => "Hello,

You have just created a customer account on [SITE].

Your login is: [EMAIL]
Your new password is: [MOT_PASSE]
",
  "insere_avis" => "Hello,

[PRENOM] [NOM_FAMILLE] has added the following comment:

Product name: [NOM_PRODUIT]

Posted opinion: [AVIS]

In order to confirm your opinion, you have to log in the administration interface and to edit its status in TOOLS > Users opinions.",
  "bons_anniversaires" => "Hello [CIVILITE] [PRENOM] [NOM_FAMILLE],

Knowing that it is your birthday, we are giving you this discount code: [NOM_CODE_PROMO].

It allows you to benefit from a [REMISE] discount on your next order [MAIL_EXTRA_INFOS].

This promotional code can only be used by you once, from now on and until the following date [DATE_FIN].

[SITE] wishes you a happy birthday!",
  "direaunami_sent" => "Hello [NOM_FAMILLE], 

[PSEUDO] visited the website [SITE] and thinks it could be interesting for you:

URL: [PRODUCT_LINK]

My comments: 
------------------------------------ 
[COMMENTS]
------------------------------------",
  "cheques_cadeaux" => "Hello,

[EMAIL_ACHETEUR] has bought you a gift!

To benefit from it, please connect to the website [WWWROOT] and use your code [CHECK_NAME].

You will then benefit from a discount of [REMISE_VALEUR] for a minimal amount of purchase of [MONTANT_MIN] HT regarding your order.",
  "cree_cheque_cadeau_friend" => "Hello,

Your friend [PRENOM] [NOM_FAMILLE] has bought you a gift on the website [SITE].

In order to benefit from it, please connect to the website [WWWROOT] and use your code [CODE].

You will then enjoy a discount of [PRIX] VAT INCLUDED on your order.
",
  "cree_cheque_cadeau_admin" => "Hello,

A promotion code [CODE] using the VOUCHER module has just been created on [SITE].",
  "cree_cheque_cadeau_client_type2" => "Hello,

[SITE] is offering you a 30 days voucher for an amount of [MONTANT].

In order to benefit from it, please connect to the website [WWWROOT] and use your code [CODE].",
  "cree_cheque_cadeau_client_admin" => "Hello,

The promotional code [CODE] has been created using the sponsoring module [SITE].",
  "gift_list" => "Hello,

Details of the message received on [DATE]

[PRENOM] [NOM_FAMILLE] sends you his gift list called: [GIFTLIST_NAME]

[GIFTLIST_ITEMS]",
  "email_ordered_cadeaux" => "Hello,

[PRENOM] [NOM_FAMILLE] has just ordered something from your gift list [GIFTLIST_NAME].

Here are the products ordered: [GIFTLIST_ITEMS]",
  "listecadeau_voir" => "Bonjour,

Details of the message sent on [DATE]:

[PRENOM] [NOM_FAMILLE] sends you his gift list called [GIFTLIST_NAME] :
[WWWROOT]/modules/listecadeau/voir.php?email_liste=[URL_LISTE_CADEAU]

[GIFTLIST_ITEMS]",
  "parrainage" => "Hello,

[PSEUDO] invites you to discover the following website [SITE] and benefit from a [REBATE] credit on your first order, after having validated your customer account. 

Click on the following link to validate your costumer account: :
 [WWWROOT]/modules/parrainage/inscription.php?email=[EMAIL_FILLEUL]&code=[MDP]&id=[ID_UTILISATEUR]

Your ID: [EMAIL_FILLEUL]
Your password: [MDP]",
  "email_alerte" => "Hello,

The following product [NOM_PRODUIT] is currently in stock. Click on the following link to discover it: [URLPROD]

See you soon on [SITE]
",
  "decremente_stock" => "Hello,

The stock threshold has been reached for the following product [NOM_PRODUIT].
Open stock : [STOCK_RESTANT_APRES_DEMANDE]",
  "signature" => "
Customer service
[SITE]
[WWWROOT]",
  "admin_login" => "Hello,

This email warns you that an administrator has logged in your website.

User login : [USER]
IP used : [REMOTE_ADDR]
Reverse DNS : [REVERSE_DNS]
Datetime : [DATETIME]

This email enhances the security on you PEEL eshop.",
  "cree_cheque_cadeau_client_type1" => "Hello,

[SITE] is offering you a 30 days voucher for an amount of [PERCENT].

In order to benefit from it, please connect to the website [WWWROOT] and use your code [CODE].",
  "email_retour_virement" => "Hello,

We have received your return number [RETURN_ID].
According to your choice, the corresponding amount, or will be paid off to [MONTANT] to you by Bank transfer as soon as possible.",
  "email_retour_avoir" => "Hello,

We have received your return number [RETURN_ID].
According to your choice, the corresponding amount has just been credited on your account, that is [MONTANT]. This [MODE] will be automatically deducted from your next order until exhaustion.",
  "email_reste_avoir_remboursement" => "Hello,

We have received your return number [RETURN_ID].
The repayment can not blank exceeds the amount of the order, [MONTANT] will be refunded by [MODE].The balance has been credited to your account, that is [RESTE_AVOIR]. This credit will be automatically deducted from your next order until exhausted.",
  "email_remboursement" => "Hello,

The repayment of your return number [RETURN_ID], to the amount of [MONTANT] has just been made by [MODE]",
  "email_retour_client" => "Hello,

Your return request has been registered.
A this, send us the items to the following address:

[SITE]
RETURN NUMBER [RETURN_ID].

Please remember that items must be returned in good condition and in their original packaging.
Upon receipt of your items, we will validate the repayment thereof."
);

?>