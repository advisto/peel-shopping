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
// $Id: database_email_template_text_de.php 55746 2018-01-15 17:18:01Z sdelaporte $

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
  "download_product" => "Guten Tag,

Ihre Bestellung [ORDER_ID] ist bestätigt. Sie können sie über folgenden Link laden:

Ihr Download-Link: [WWWROOT]/modules/download/telecharger.php?id=[ORDER_ID]&key=[CLE]

Sie können diese Datei einmal laden. Wenn Sie Probleme mit dem Download haben, wenden Sie sich bitte an [SUPPORT_COMMANDE], Sie erhalten dann einen neuen Link.

Über folgenden Link können Sie Ihre Rechnung bearbeiten:

[WWWROOT]/factures/commande_pdf.php?code_facture=[CODE_FACTURE]&mode=facture",
  "commande_parrain_avoir" => "Guten Tag,

nach der Bestellung einer Ihrer Referenzen in unserem Netzladen profitieren Sie von einem Guthaben von [AVOIR] für Ihre nächste Bestellung.",
  "envoie_client_code_promo" => "Guten Tag [CIVILITE] [PRENOM] [NOM_FAMILLE],

als Dank für Ihre Bestellung in unserem Netzladen offerieren wir Ihnen diesen Rabattcode: [NOM_CODE_PROMO].

Damit kommen Sie in den Genuss eines Rabatts von [REMISE] über Ihre nächste Bestellung.

Dieser Rabattcode ist nur für Sie bestimmt und kann nur einmal verwendet werden, und zwar bis [DATE_FIN].

Wir danken Ihnen für Ihr Vertrauen.",
  "insere_ticket" => "Guten Tag,

hierunter sehen Sie die Einzelheiten der am [DATE] versandten Nachricht:

Name: [NOM_FAMILLE]
Vorname: [PRENOM]
Firma: [SOCIETE]
Adresse: [ADRESSE]
Tel: [TELEPHONE]
E-Mail: [EMAIL]
Verfügbarkeit [DISPO]

Betreff: [SUJET]

Nachricht:

[TEXTE]

IP : [REMOTE_ADDR]
",
  "admin_info_payment_credit_card" => "Guten Tag, 

eine Bestellung mit der Nummer [ORDER_ID] wurde gerade auf [WWWROOT]/ registriert.",
  "admin_info_payment_credit_card_3_times" => "Guten Tag,

eine Bestellung mit einer Bezahlung in drei Raten mit der Nummer [ORDER_ID] wurde gerade auf [SITE] registriert.
",
  "send_client_order_html" => "Guten Tag,

über den folgenden Link können Sie Ihre Bestellung auf der Website [SITE] drucken oder bearbeiten:

Ihr Bestellschein:
[URL_FACTURE]

Wenn der Link nicht ordentlich in den Browser übertragen wird, kopieren und fügen Sie ihn ein. Die URL muss mit mode=[MODE] enden.

Ihre Bestellung wird nach Eingang Ihrer Bezahlung bearbeitet.

Wir danken Ihnen für Ihr Vertrauen in [SITE]
",
  "send_client_order_pdf" => "Guten Tag,

über folgenden Link können Sie Ihre Bestellung auf der Website [SITE] drucken oder bearbeiten:

Ihr Bestellschein:
[URL_FACTURE]

Wenn der Link nicht ordentlich in den Browser übertragen wird, kopieren und fügen Sie ihn ein. Die URL muss mit mode=[MODE] enden.

Ihre Bestellung wird nach Eingang Ihrer Bezahlung bearbeitet.

Wir danken Ihnen für Ihr Vertrauen in [SITE]
",
  "send_avis_expedition" => "Guten Tag [PRENOM] [NOM_FAMILLE],

mit Freude bestätigen wir Ihnen die Vorbereitung und anstehende Lieferung der Bestellung [ORDER_ID].

Versandte Artikel:
[SHIPPED_ITEMS]
Die von Ihnen gewählte Versandart ist: [TYPE]
Referenz der Lieferung: Es gibt [COLIS] Pakete. Verfolgen Sie Ihr Postpaket durch Klicken auf folgenden Link: http://www.coliposte.fr/

Lieferanschrift:
[CLIENT_INFOS_SHIP]

WICHTIGER HINWEIS:

Bitte halten Sie sich genauestens an unsere Anweisungen, um Problemen vorzubeugen. Der Transport ist ein wichtiger Schritt, der Ihrer Aufmerksamkeit bedarf.
Berücksichtigen Sie daher bitte Folgendes:
- Beschädigte Pakete
- Geöffnete oder zerquetschte Pakete
- Verschlussmittel (Klebeband usw.) beschädigt oder scheinbar nicht original

WAS TUN?
- Öffnen Sie das Paket nicht.
- Weigern Sie die Annahme des Pakets.
- Melden Sie den Vorbehalt dem Spediteur.
- Teilen Sie uns das Problem mit, geben Sie dabei die betreffende Bestellnummer an.

ERSTATTUNG
[SITE] verweigert die Erstattung einer Bestellung automatisch, wenn:
- gegenüber dem Spediteur kein Vorbehalt gemacht wurde
- kein Nachweis dieses Vorbehalts erbracht wurde.

Wir danken Ihnen für Ihr Verständnis und stehen Ihnen für Fragen jederzeit gern zur Verfügung.",
  "email_commande" => "Guten Tag [CIVILITE] [PRENOM] [NOM_FAMILLE],

Ihre Bestellung [ORDER_ID] vom [DATE] ist in bester Ordnung auf [SITE] eingegangen.

---------------------------
ÜBERSICHT IHRER BESTELLUNG
---------------------------

Betrag: [MONTANT] TTC
Zahlungsart: [PAIEMENT]

---------------------------
Rechnungsanschrift
---------------------------
[CLIENT_INFOS_BILL]

---------------------------
Lieferanschrift
---------------------------
[CLIENT_INFOS_SHIP]

---------------------------
Bestellte Artikel
---------------------------
[BOUGHT_ITEMS]
Versandkosten:
[COUT_TRANSPORT]
Die Versandkosten:
[TYPE]

Sie können den Status Ihrer Bestellung in Echtzeit verfolgen:
Sobald Ihre Bestellung bezahlt ist, erscheint die Rechnung in Ihrem Konto.

Um Ihre vergangenen Bestellung zu sehen:
 - Klicken Sie auf MEIN KONTO.
 - Melden Sie sich an.
 - Klicken Sie auf Bestellverlauf.

Wir danken Ihnen für Ihr Vertrauen.",
  "send_mail_order_admin" => "Guten Tag,

die Bestellung [ORDER_ID] wurde auf der Website [SITE] registriert.

E-Mail Kunde : [EMAIL]
Bestellnummer: [ORDER_ID]
Bestellsumme: [MONTANT]
Bestelldatum: [O_TIMESTAMP]
Zahlungsweise: [PAIEMENT]

Gehen Sie auch zur Verwaltung Ihrer Website.
",
  "initialise_mot_passe" => "Guten Tag,

auf der Website [SITE] wurde ein neues Kennwort angefordert.

Zur Bestätigung Ihrer Kennwortänderung klicken Sie bitte auf folgenden Link: [LINK]
Dafür haben Sie 24 Stunden Zeit, danach ist der Link nicht mehr gültig.

Diese E-Mail wurde automatisch versandt, Antworten darauf werden nicht gelesen.
",
  "send_mail_for_account_creation" => "Guten Tag,

Sie haben gerade ein Konto auf [SITE] erstellt.

Ihr Nutzername ist: [EMAIL]
Ihr Kennwort lautet: [MOT_PASSE]
",
  "insere_avis" => "Guten Tag,

[PRENOM] [NOM_FAMILLE] hat folgenden Kommentar hinterlassen:

Artikelname: [NOM_PRODUIT]

Abgegebene Beurteilung: [AVIS]

Um diese Beurteilung zu validieren, gehen Sie zur Verwaltung und ändern den Status unter: Webmastering > Marketing > Beurteilungen von Nutzern.",
  "bons_anniversaires" => "Guten Tag [CIVILITE] [PRENOM] [NOM_FAMILLE],

anlässlich Ihres Geburtstags offerieren wir Ihnen folgenden Rabattcode: [NOM_CODE_PROMO].

Damit bekommen Sie bei Ihrem nächsten Einkauf einen Rabatt von [REMISE]  [MAIL_EXTRA_INFOS]

Dieser Rabattcode ist persönlich und kann nur einmal eingesetzt werden, und zwar bis [DATE_FIN].

[SITE] wünscht Ihnen alles Gute und vor allem Gesundheit!",
  "direaunami_sent" => "Guten Tag [NOM_FAMILLE], 

[PSEUDO] hat die Website [SITE] besucht und folgenden womöglich auch für Sie interessanten Artikel gefunden:

URL: [PRODUCT_LINK]

Bemerkungen: 
------------------------------------ 
[COMMENTS]
------------------------------------",
  "cheques_cadeaux" => "Guten Tag,

[EMAIL_ACHETEUR] möchte Ihnen gern einen Gutschein schenken!

Um ihn einzulösen, gehen Sie zur Website [WWWROOT]/ und tragen dort Ihren Code ein [CHECK_NAME].

Damit erhalten Sie bei Ihrer nächsten Bestellung mit einer Mindestbestellsumme von [MONTANT_MIN] zzgl. MwSt. einen Rabatt von [REMISE_VALEUR].",
  "cree_cheque_cadeau_friend" => "Guten Tag,

Ihr/e Freund/in [PRENOM] [NOM_FAMILLE] möchte Ihnen einen Gutschein für die Website [SITE] schenken.

Um ihn einzulösen, gehen Sie zur Website [WWWROOT]/ und tragen dort Ihren Code ein [CODE].

Damit erhalten Sie bei Ihrer Bestellung einen Rabatt von [PRIX] inkl. MwSt.
",
  "cree_cheque_cadeau_admin" => "Guten Tag,

gerade wurde auf der Website [SITE] über das Modul CHEQUE CADEAU der Rabattcode [CODE] erzeugt.
",
  "cree_cheque_cadeau_client_type2" => "Guten Tag,

[SITE] schenkt Ihnen einen Gutschein über [MONTANT] (30 Tage gültig).

Um ihn einzulösen, gehen Sie zu [WWWROOT]/ und geben dort den Code ein [CODE].
",
  "cree_cheque_cadeau_client_admin" => "Guten Tag,

über das Sponsoring-Modul auf [SITE] wurde der Rabattcode [CODE] erzeugt.
",
  "gift_list" => "Guten Tag,

Einzelheiten der am [DATE] versandten Nachricht:

[PRENOM] [NOM_FAMILLE] hat seine Wunschliste versandt: [GIFTLIST_NAME]

[GIFTLIST_ITEMS]
",
  "email_ordered_cadeaux" => "Guten Tag,

[PRENOM] [NOM_FAMILLE] hat etwas von Ihrer Wunschliste [GIFTLIST_NAME] bestellt.

Dies sind die bestellten Artikel:
[GIFTLIST_ITEMS]",
  "listecadeau_voir" => "Guten Tag,

Einzelheiten der am [DATE] versandten Nachricht:

[PRENOM] [NOM_FAMILLE] hat seine Wunschliste versandt [GIFTLIST_NAME]:
[URL_LISTE_CADEAU]

[GIFTLIST_ITEMS]",
  "parrainage" => "Guten Tag,

[PSEUDO] lädt Sie ein, die Website [SITE] kennenzulernen und - nach der Validierung Ihres Kontos - einen Rabatt von [REBATE] über Ihre Bestellung zu empfangen.

Klicken Sie auf den folgenden Link, um Ihr Konto zu validieren: 
 [WWWROOT]/modules/parrainage/inscription.php?email=[EMAIL_FILLEUL]&code=[MDP]&id=[ID_UTILISATEUR]

Ihr Nutzername: [EMAIL_FILLEUL]
Ihr Kennwort: [MDP]
",
  "email_alerte" => "Guten Tag,

der Artikel [NOM_PRODUIT] ist zur Zeit vorrätig. Klicken Sie hier, um ihn zu entdecken: [URLPROD]
",
  "decremente_stock" => "Guten Tag,

die Alarmschwelle für den Artikel [NOM_PRODUIT] ist erreicht.

Verbleibender Vorrat: [STOCK_RESTANT_APRES_DEMANDE]",
  "admin_login" => "Guten Tag,

hiermit setzen wir Sie darüber in Kenntnis, dass sich ein Verwalter auf Ihrer Website angemeldet hat.

Nutzername: [USER]
IP-Adresse: [REMOTE_ADDR]
Reverse DNS: [REVERSE_DNS]
Datum und Zeit: [DATETIME]

Mit dieser E-Mail möchten wir Sie bezüglich der Sicherheit Ihres PEEL-Netzladens sensibilisieren.",
  "signature" => "

Kundendienst
[SITE]
[WWWROOT]/",
  "cree_cheque_cadeau_client_type1" => "Guten Tag,

[SITE] schenkt Ihnen einen 30 Tage lang gültigen Gutschein über  [PERCENT].

Um ihn einzulösen, gehen Sie zu [WWWROOT]/ und geben dort den Code [CODE] ein.
",
  "warn_admin_user_subscription" => "Am [DATE] 

hat sich folgender Nutzer angemeldet:

[CIVILITE] [PRENOM] [NOM_FAMILLE]
[EMAIL]
[SOCIETE]
[TELEPHONE]
[PRIV]

[link=\"[ADMIN_URL]\"]Verwalten Sie das Konto dieses Nutzers[/link]
",
  "warn_admin_reve_subscription" => "",
"email_retour_virement" => "Guten Tag,

Ihre Rücksendung mit der Nummer [RETURN_ID] ist in bester Ordnung bei uns eingegangen.
Gemäß Ihrer Auswahl wird Ihnen umgehend der Betrag von [MONTANT] überwiesen.",
  "email_retour_avoir" => "Guten Tag,

Ihre Rücksendung mit der Nummer [RETURN_ID] ist in bester Ordnung bei uns eingegangen.
Gemäß Ihrer Auswahl wird der Betrag von [MONTANT] Ihrem Nutzerkonto gutgeschrieben. Ihre nächsten Bestellungen werden automatisch per [MODE] von diesem Guthaben abgezogen, bis es aufgebraucht ist.",
  "email_reste_avoir_remboursement" => "Guten Tag,

Ihre Rücksendung mit der Nummer [RETURN_ID] ist in bester Ordnung bei uns eingegangen.
Die Erstattung kann die Bestellsumme nicht überschreiten; Ihnen werden [MONTANT] mittels [MODE] erstattet. Der Ihrem Nutzerkonto gutgeschriebene Betrag beläuft sich auf [RESTE_AVOIR]. Ihre nächsten Bestellungen werden automatisch von diesem Guthaben abgezogen, bis es aufgebraucht ist.",
  "email_remboursement" => "Guten Tag,

die Erstattung für Ihre Rücksendenummer [RETURN_ID] in Höhe von [MONTANT] wurde durch [MODE] ausgeführt.",
  "email_retour_client" => "Guten Tag,

Ihr Rücksendeantrag ist eingegangen.
Senden Sie die Artikel an folgende Adresse:

[SOCIETE]
RÜCKSENDENUMMER [RETURN_ID].

Wir erinnern Sie daran, dass die zurückzusendenden Artikel in gutem Zustand und in ihrer Originalverpackung sein müssen.
Nach Eingang Ihrer Artikel werden wir die entsprechende Erstattung validieren.",
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

