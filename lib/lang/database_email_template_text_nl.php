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
// $Id: database_email_template_text_nl.php 55746 2018-01-15 17:18:01Z sdelaporte $

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
  "download_product" => "Hallo,

uw bestelling [ORDER_ID] is bevestigd, wij nodigen u uit om uw bestelling te downloaden via de volgende link: 

Uw link om te downloaden: [WWWROOT]/modules/download/telecharger.php?id=[ORDER_ID]&key=[CLE] 

U kunt dit bestand 1 keer downloaden. 
Als u problemen hebt met het downloaden, neem dan contact op [SUPPORT_COMMANDE], zodat u een nieuwe download link krijgt. 

u kunt uw factuur bewerken via de volgende link: 

[WWWROOT]/factures/commande_pdf.php?code_facture=[CODE_FACTURE]&mode=facture",
  "commande_parrain_avoir" => "Hallo, 

na het bestellen van een van uw referenties in onze online shop, profiteert u van een tegoed van [AVOIR] op uw volgende bestelling.",
  "envoie_client_code_promo" => "Hallo [CIVILITE] [PRENOM] [NOM_FAMILLE],

om u te bedanken voor uw bestelling in onze online shop, bieden wij u deze actiecode: [NOM_CODE_PROMO].

Hiermee kunt u profiteren van een [REMISE] korting op uw volgende bestelling.

Deze promotiecode kan slechts eenmaal door u worden gebruikt en u kunt deze vanaf nu tot aan de volgende datum te gebruiken [DATE_FIN].

Wij danken u voor uw vertrouwen.",
  "insere_ticket" => "Hallo,

details van het bericht verzonden op [DATE] :

Naam : [NOM_FAMILLE]
Voornaam : [PRENOM]
Bedrijfsnaam : [SOCIETE]
Adres : [ADRESSE]
Telefoonnummer : [TELEPHONE]
Emailadres : [EMAIL]
Beschikbaarheid : [DISPO]

Onderwerp : [SUJET]

Bericht :

[TEXTE]

IP : [REMOTE_ADDR]
",
  "admin_info_payment_credit_card" => "Hallo, 

een bestelling met nummer [ORDER_ID] is net geregistreerd op [WWWROOT]/",
  "admin_info_payment_credit_card_3_times" => "Hallo, 

een bestelling met betaling in drie termijnen met nummer [ORDER_ID] is geregistreerd op [SITE]
",
  "send_client_order_html" => "Hallo, 

open de volgende link om uw bestelling af te drukken of om uw bestelling te betalen op de website [SITE] 

Uw bestelformulier: 
[URL_FACTURE] 

Als de link niet correct wordt weergegeven in uw browser, deze  alstublieft kopieëren en plakken. De URL moet eindigen met mode = [MODE]. 

Uw bestelling wordt verwerkt na ontvangst van uw betaling. 

Wij danken u voor uw vertrouwen in [SITE]
",
  "send_client_order_pdf" => "Hallo, 

open de volgende link om uw bestelling af te drukken of om uw bestelling te betalen op de website [SITE] 

Uw bestelformulier: 
[URL_FACTURE] 

Als de link niet correct wordt weergegeven in uw browser, deze  alstublieft kopieëren en plakken. De URL moet eindigen met mode = [MODE]. 

Uw bestelling wordt verwerkt na ontvangst van uw betaling. 

Wij danken u voor uw vertrouwen in [SITE]
",
  "send_avis_expedition" => "Hallo [PRENOM] [NOM_FAMILLE], 

We zijn blij om de voorbereiding en de levering van de volgende bestelling te bevestigen [ORDER_ID] 

Verzonden artikelen : 
[SHIPPED_ITEMS] 
De levering die u hebt gekozen bij het maken van opdracht is: [TYPE] 
Referentie van verzending: Het aantal pakketten is [COLIS]. U kunt uw pakket volgen door te klikken op de volgende link: http://www.coliposte.fr/ 

Verzendadres: 
[CLIENT_INFOS_SHIP] 

Belangrijke opmerkingen: 

Volgt u alstublieft nauwgezet onze instructies op om problemen te voorkomen. Het transport is een belangrijke stap die specifieke aandacht vereist 
Dank u voor het voldoen aan de elementaire regels die hieronder genoemd worden: 
- Beschadigde pakketten 
- Open of gebroken pakketen 
- Verpakkingssluiting (plakband...) beschadigd of schijnbaar niet origineel 

WAT TE DOEN ? 
- Open het pakket niet 
- weiger het pakket 
- direct melden bij de vervoerder 
- waarschuw ons direct en geef ons het ordernummer van de zending 

TERUGBETALING 
[SITE] zal van een order systematisch de teruggaaf weigeren indien: 
- geen melding is gedaan bij de vervoerder 
- geen bewijs van de melding wordt geleverd 

Wij danken u voor uw begrip en staan tot uw beschikking voor uw vragen.",
  "email_commande" => "Hallo [CIVILITE] [PRENOM] [NOM_FAMILLE], 

Uw bestelling met nummer [ORDER_ID] gedaan op [DATE] is geregistreerd op de website [SITE]. 

------------- -------------- 
OVERZICHT VAN UW BESTELLING
---------------------------

Bedrag : [MONTANT] inclusief BTW 
Betalingswijze: [PAIEMENT]

---------------------------
Factuuradres 
--- ------------------------ 
[CLIENT_INFOS_BILL] 

---------------------------
Afleveradres
---------------------------
[CLIENT_INFOS_SHIP]

---------------------------
Bestelde artikelen
---------------------------
[BOUGHT_ITEMS]
Verzendkosten: 
[COUT_TRANSPORT] 
verzending soort:
[TYPE]

U kunt de actuele status van uw bestelling volgen: 
zodra uw bestelling is betaald, wordt de factuur weergegeven in uw account 

Om uw bestelhistorie te bekijken: 
- Klik op MIJN ACCOUNT 
- log in 
- Klik op bestelhistorie. 

Dank u voor uw vertrouwen.",
  "send_mail_order_admin" => "Hallo,

de bestelling [ORDER_ID] is geregistreerd op de website [SITE].

Emailadres : [EMAIL]
Ordernummer : [ORDER_ID]
Orderbedrag : [MONTANT]
Orderdatum : [O_TIMESTAMP]
Betaling : [PAIEMENT]

Raadpleeg de administratie-interface van uw website.
",
  "initialise_mot_passe" => "Hallo,

Een aanvraag voor een nieuwe wachtwoord op de website [SITE] is geïnitialiseerd.

Om uw aanvraag voor een nieuw wachtwoord te bevestigen, moet u op de volgende link klikken : [LINK]
U heeft 24 uur na het verzoek om een nieuwe wachtwoord om deze bewerking uit te voeren. Hierna is de link niet meer geldig.

Deze e-mail is automatisch verzonden, dank u voor het niet reageren op dit bericht.
",
  "send_mail_for_account_creation" => "Hallo,

u heeft net een account aangemaakt op [SITE].

Uw login is : [EMAIL]
Uw wachtwoord is : [MOT_PASSE]⏎
",
  "insere_avis" => "Hallo,

[PRENOM] [NOM_FAMILLE] heeft de volgende beoordeling toegevoegd :

Productnaam : [NOM_PRODUIT]

Toegevoegde beoordeling : [AVIS]

Om deze beoordeling te bevestigen, moet u verbinding maken met de administratie interface en de status wijzigen in de rubriek Webmastering> Marketing> Beoordelingen.",
  "bons_anniversaires" => "Hallo [CIVILITE] [PRENOM] [NOM_FAMILLE],

Voor uw verjaardag bieden wij u deze promotiecode : [NOM_CODE_PROMO].

Hiermee kunt u profiteren van een [REMISE] korting op uw volgende bestelling [MAIL_EXTRA_INFOS]

Deze promotiecode is speciaal voor u en geldt slechts eenmalig vanaf nu tot [DATE_FIN].

[SITE] Wij wensen u een fijne verjaardag!",
  "direaunami_sent" => "Hallo [NOM_FAMILLE], 

[PSEUDO] heeft de website [SITE] bezocht en heeft een interessant artikel voor u gevonden :

[PRODUCT_LINK]

Aanvullende opmerkingen : 
------------------------------------ 
[COMMENTS]
------------------------------------",
  "cheques_cadeaux" => "Hallo,

[EMAIL_ACHETEUR] wil u een cadeau geven !

Om hier gebruik van te maken gaat u naar de volgende website [WWWROOT]/ en gebruikt u uw code [CHECK_NAME].

U ontvangt dan een korting van [REMISE_VALEUR] bij een minimaal orderbedrag van [MONTANT_MIN] exclusief BTW.",
  "cree_cheque_cadeau_friend" => "Hallo,

uw vriend [PRENOM] [NOM_FAMILLE] wil u een cadeau geven op de website [SITE].

Om hier gebruik van te maken gaat u naar de volgende website [WWWROOT]/ en gebruikt u uw code [CODE].

U ontvangt dan een korting van [PRIX] inclusief BTW op uw bestelling.
",
  "cree_cheque_cadeau_admin" => "Hallo,

een promotiecode [CODE] via de module CADEAU is net aangemaakt op [SITE].
",
  "cree_cheque_cadeau_client_type2" => "Hallo,

[SITE] biedt u een 30 dagen geldige tegoedbon voor een bedrag van [MONTANT].

Om hier gebruik van te maken gaat u naar de volgende website [WWWROOT]/ en gebruikt u uw code [CODE].
",
  "cree_cheque_cadeau_client_admin" => "Hallo,

de promotiecode [CODE] is aangemaakt via de module sponsoring [SITE].
",
  "gift_list" => "Hallo,

Details van het bericht ontvangen op [DATE]

[PRENOM] [NOM_FAMILLE] heeft u zijn verlanglijstje gestuurd genaamd : [GIFTLIST_NAME]

[GIFTLIST_ITEMS]⏎
",
  "email_ordered_cadeaux" => "Hallo,

[PRENOM] [NOM_FAMILLE] heeft net iets besteld van uw verlanglijstje [GIFTLIST_NAME].

Hier de bestelde artikelen :
[GIFTLIST_ITEMS]",
  "listecadeau_voir" => "Hallo,

Details van het bericht ontvangen op [DATE].

[PRENOM] [NOM_FAMILLE] zendt u zijn verlanglijstje genaamd [GIFTLIST_NAME] :
[URL_LISTE_CADEAU]

[GIFTLIST_ITEMS]",
  "parrainage" => "Hallo,

[PSEUDO] nodigt u uit om de volgende website te ontdekken [SITE] en te profiteren van een voordeel van [REBATE] op uw eerste bestelling, na het valideren van uw account. 

Klik op de volgende link om uw account te valideren: 
[WWWROOT]/modules/parrainage/inscription.php?email=[EMAIL_FILLEUL]&code=[MDP]&id=[ID_UTILISATEUR]

Uw gebruikersnaam : [EMAIL_FILLEUL]
Uw wachtwoord : [MDP]⏎
",
  "email_alerte" => "Hallo,

het volgende product [NOM_PRODUIT] is momenteel op voorraad. Klik hier om het te ontdekken : [URLPROD]
",
  "decremente_stock" => "Hallo,

de voorraddrempel voor het volgende product is bereikt [NOM_PRODUIT].

Resterende voorraad : [STOCK_RESTANT_APRES_DEMANDE]",
  "admin_login" => "Hallo,

deze email waarschuwt u dat een beheerder heeft ingelogd op uw website.

Gebruikers login : [USER]
gebruikt IP adres : [REMOTE_ADDR]
Reverse DNS : [REVERSE_DNS]
Datum en tijd : [DATETIME]

Deze e-mail is bedoeld om u de beveiliging van uw PEEL winkel beter te maken.",
  "signature" => "

Klantenservice
[SITE]
[WWWROOT]/",
  "cree_cheque_cadeau_client_type1" => "Hallo,

[SITE] biedt u een 30 dagen geldige tegoedbon voor een bedrag van [PERCENT].

Om hier gebruik van te maken gaat u naar de volgende website [WWWROOT]/ en gebruikt u uw code [CODE].
",
  "warn_admin_user_subscription" => "Op [DATE], 

heeft de volgende gebruiker zich geregistreerd:

[CIVILITE] [PRENOM] [NOM_FAMILLE]
[EMAIL]
[SOCIETE]
[TELEPHONE]
[PRIV]

[link=\"[ADMIN_URL]\"]Beheer deze gebruiker [/link]",
  "warn_admin_reve_subscription" => "",
"email_retour_virement" => "Hallo,

we hebben uw retournummer ontvangen [RETURN_ID].
Conform uw keuze wordt het betreffende bedrag [MONTANT] per ommegaande via bankoverschrijving aan u overgemaakt.",
  "email_retour_avoir" => "Hallo,

we hebben uw retournummer ontvangen [RETURN_ID].
Conform uw keuze is het betreffende bedrag [MONTANT] zojuist op uw account gecrediteerd. De [MODE] wordt automatisch afgetrokken van uw volgende bestellingen tot dit tegoed op is.",
  "email_reste_avoir_remboursement" => "Hallo,

we hebben uw retournummer ontvangen [RETURN_ID].
De terugbetaling kan niet hoger zijn dan het bedrag van de bestelling, [MONTANT] zal terugbetaald worden via [MODE]. Het saldo dat is bijgeschreven op uw account bedraagt [RESTE_AVOIR]. Dit wordt automatisch afgetrokken van uw volgende bestelling tot dit tegoed op is.",
  "email_remboursement" => "Hallo, 

terugbetaling van uw retournummer [RETURN_ID], voor het bedrag van [MONTANT] is uitgevoerd door [MODE].",
  "email_retour_client" => "Hallo,

 Uw retouraanvraag is geregistreerd.
Stuur ons nu de artikelen naar het volgende adres :

[SOCIETE]
RETOURNUMMER [RETURN_ID].

Houd er rekening mee dat de artikelen teruggestuurd moeten worden in goede staat en in de originele verpakking.
Na ontvangst van uw artikelen, zullen wij de terugbetaling van deze valideren.",
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

