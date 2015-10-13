<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_email_template_text_eo.php 46935 2015-09-18 08:49:48Z gboussin $

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
  "download_product" => "Saluton,

Via mendo [ORDER_ID] estis validigita, do ni petas vin elŝuti ĝin pere de la sekva ligilo:

Elŝuta ligilo: [WWWROOT]/modules/download/telecharger.php?id=[ORDER_ID]&key=[CLE]

Eblos elŝuti la dosieron 1 fojon.
Kaze de problemo pri la elŝuto, ni invitas vin kontakti [SUPPORT_COMMANDE] cele al ricevi denove elŝutan ligilon.

Plie, ni petas vin eldoni la fakturon pere de la sekva ligilo:

[WWWROOT]/factures/commande_pdf.php?code_facture=[CODE_FACTURE]&mode=facture",
  "commande_parrain_avoir" => "Saluton,

Post mendo de iu el viaj patronitoj ĉe nia ret-vendejo, profitu ĉi krediton je [AVOIR] elspezebla je venonta mendo.",
  "envoie_client_code_promo" => "Saluton [CIVILITE] [PRENOM] [NOM_FAMILLE],

Danke de via mendo ĉe nia ret-vendejo, profitu ĉi rabat-kodon: [NOM_CODE_PROMO].

Ĝi ebligas profiti rabaton je [REMISE] elspezebla je venonta mendo.

La rabat-kodo speciale profitu al vi kaj validas unu fojon de nun ĝis dato [DATE_FIN].

Dankon al vi pri via fideleco.",
  "insere_ticket" => "Saluton,

Mesaĝaj detaloj senditaj je [DATE] :

Familinomo: [NOM_FAMILLE]
Antaŭnomo : [PRENOM]
Entrepreno: [SOCIETE]
Adreso: [ADRESSE]
Telef.: [TELEPHONE]
Retpoŝtadreso: [EMAIL]
Disponeblo: [DISPO]

Temo: [SUJET]

Mesaĝo:

[TEXTE]

IP : [REMOTE_ADDR]
",
  "admin_info_payment_credit_card" => "Saluton, 

La mendo n-o [ORDER_ID] ĵus registriĝis ĉe [WWWROOT]/",
  "admin_info_payment_credit_card_3_times" => "Saluton,

Mendo trioble pagota kun n-o [ORDER_ID] ĵus registriĝis ĉe [SITE]
",
  "send_client_order_html" => "Saluton,

Ni invitas vin viziti la sekvan paĝon por printi aŭ pagi vian mendon ĉe [SITE]:

Jen via pag-atestilo:
[URL_FACTURE]

Se la ligilo ne aperas ĉe la ret-foliumilo, bonvolu kopi-glui en ties adres-breto. La retadreso finĝu per \"mode=[MODE]\".

Via mendo estos traktita tuj post konfirmo de la pago.

Ni elkore dankas vin pri via fideleco al [SITE]
",
  "send_client_order_pdf" => "Saluton,

Ni invitas vin viziti la sekvan paĝon por printi aŭ pagi vian mendon ĉe [SITE] :

Jen via pag-atestilo:
[URL_FACTURE]

Se la ligilo ne aperas ĉe la ret-foliumilo, bonvolu kopi-glui en ties adres-breto. La retadreso finĝu per \"mode=[MODE]\".

Via mendo estos traktita tuj post konfirmo de la pago.

Ni elkore dankas vin pri via fideleco al [SITE]
",
  "send_avis_expedition" => "Saluton [PRENOM] [NOM_FAMILLE],

Ni plezure konfirmas la pretigadon kaj baldaŭan liveron de via menditajho n-o [ORDER_ID].

Liverotaj varoj:
[SHIPPED_ITEMS]
La liver-maniero elektita de via ĉe mendo estas: [TYPE]
Liveraj detaloj: liveraĵo n-o [COLIS]. Kaze de livero pere de 'La Poste', eblas sekvi la liveron pere de la sekva ligilo: http://www.coliposte.fr/

Livera adreso:
[CLIENT_INFOS_SHIP]

GRAVAJ INFORMOJ:

Ni petas vin atente sekvi la instrukciojn donitaj, por eviti ian konflikton. Transporto estas ja tikla proceso, kiu postulas specialan atenton.
Konsekvence, bonvolu alkonformiĝi al la bazaj uz-reguloj ĉi poste rememorigataj - se:
- la liveraĵo estas malbon-stata
- la liveraĵo estas malfermita / difekta
- la liveraĵa fermilo (glubendo, ktp) estas difekta aŭ anstataŭigita

DO KION FARI?
- oni ne malfermu la liveraĵon
- oni rifuzas la liveraĵon
- oni tuj forrezervu kontraŭ la liveranto
- oni klarigu al ni pri la problemo, indike de la koncernata mendo-numero

REPAGO
[SITE] sisteme rifuzos repagon de mendo, se:
- neniu forrezervo estis sendita al la liveranto
- neniu pruvo pri forrezervado estas sendita

Ni dankas vin pri via kompreno kaj staras tutpretaj respondi ĉiajn pliajn demandojn.",
  "email_commande" => "Saluton [CIVILITE] [PRENOM] [NOM_FAMILLE],

Via mendo n-o [ORDER_ID] je la [DATE] bone registriĝis ĉe [SITE].

---------------------------
DETALOJ DE VIA MENDO
---------------------------

Monsumo: [MONTANT] ĉiujn impostojn inkluzivitaj
pag-maniero: [PAIEMENT]

---------------------------
Faktura adreso
---------------------------
[CLIENT_INFOS_BILL]

---------------------------
Livera adreso
---------------------------
[CLIENT_INFOS_SHIP]

---------------------------
Menditaj varoj
---------------------------
[BOUGHT_ITEMS]
Liveraj kostoj:
[COUT_TRANSPORT]
Shipping tipo
[TYPE]

Eblas realtempe sekvi la disvolviĝo de via mendo:
post pago de la mendo, ĝia fakturo aperos ĉe via klienta konto ĉe la paĝo pri mendaj detaloj.

Proceso por viziti la historion de mendoj:
 - Klaku la ligilon MIA KONTO,
 - Ensalutu,
 - Poste, alklaku la ligilon Mendo-listo.

Ni elkore dankas vin pri via fideleco.",
  "send_mail_order_admin" => "Saluton,

La mendo n-o [ORDER_ID] ĵus registriĝis ĉe [SITE].

Klienta retpoŝtadreso: [EMAIL]
Menda referenco: [ORDER_ID]
Menda monsumo: [MONTANT]
Menda dato: [O_TIMESTAMP]
Pago: [PAIEMENT]

Bonvolu viziti la administran paĝon de via retejo.
",
  "initialise_mot_passe" => "Saluton,

Oni petis novan pasvorton ĉe la retejo [SITE].

Por konfirmi la peton pri renovigo de pasvorto, klaku la sekvan ligilon: [LINK]
Vi disponas 24 horojn por efektivigi la pasvortan renovigon, post kiam la ligilo malfunkcios.

Ĉi retmesaĝo estinte aŭtomate sendita, ĝi ne ebligas respondon.
",
  "send_mail_for_account_creation" => "Saluton,

Vi ĵus kreis klientan konton ĉe [SITE].

Jen via salutnomo: [EMAIL]
Kaj jen via pasvorto: [MOT_PASSE]
",
  "insere_avis" => "Saluton,

[PRENOM] [NOM_FAMILLE] aldonis la sekvan komenton:

Pri la varo: [NOM_PRODUIT]

Komento publikigota: [AVIS]

Por konfirmi publikigon, vizitu la administran paĝon kaj agordu la staton ĉe rubriko Retestrejo > Merkatado > Komentoj de vizitantoj.",
  "bons_anniversaires" => "Saluton [CIVILITE] [PRENOM] [NOM_FAMILLE],

Okaze de via naskiĝ-datreveno, ni donacas al vi ĉi rabat-kodon: [NOM_CODE_PROMO].

Ĝi ebligas vin profiti rabaton je [REMISE] elspezebla je venonta mendo [MAIL_EXTRA_INFOS]

La rabat-kodo speciale profitu al vi kaj validas unu fojon de nun ĝis dato [DATE_FIN].

[SITE] deziras al vi belegan datrevenan tagon.",
  "direaunami_sent" => "Saluton [NOM_FAMILLE], 

[PSEUDO] vizitis la retejon [SITE] kaj opinias ĉi varon interesa al vi:

URL : [PRODUCT_LINK]

Aldonaj komentoj: 
------------------------------------ 
[COMMENTS]
------------------------------------",
  "cheques_cadeaux" => "Saluton,

[EMAIL_ACHETEUR] deziras regali vin per donac-ĉekon!

Por profiti ĝin, sufiĉas viziti la retejon [WWWROOT]/ kaj uzi la rabat-kodon [CHECK_NAME].

Vi ricevos rabaton je [REMISE_VALEUR], valida por aĉeto je mendo valora minimume [MONTANT_MIN], krom impostoj.",
  "cree_cheque_cadeau_friend" => "Saluton,

Via amiko [PRENOM] [NOM_FAMILLE] deziras regali vin per donac-ĉekon ĉe la retejo [SITE].

Por profiti ĝin, sufiĉas viziti la retejon [WWWROOT]/ kaj uzi la rabat-kodon [CHECK_NAME].

Vi ricevos rabaton por mendo je [PRIX], ĉiujn impostojn inkluzivaj.
",
  "cree_cheque_cadeau_admin" => "Saluton,

La rabat-kodo [CODE] pere de la modulo DONAC-ĈEKO ĵus kreiĝis ĉe [SITE].
",
  "cree_cheque_cadeau_client_type2" => "Saluton,

[SITE] profitigas al vi donac-ĉekon valida dum 30 tagoj je monsumo [MONTANT].

Por profiti ĝin, sufiĉas viziti la retejon [WWWROOT]/ kaj uzi la rabat-kodon [CHECK_NAME].
",
  "cree_cheque_cadeau_client_admin" => "Saluton,

La rabat-kodo [CODE] ĵus kreiĝis pere de la patrona modulo ĉe [SITE].
",
  "gift_list" => "Saluton,

Jen la detaloj de la mesaĝo sendita je la [DATE]

[PRENOM] [NOM_FAMILLE] sendas al vi sian donac-liston: [GIFTLIST_NAME]

[GIFTLIST_ITEMS]
",
  "email_ordered_cadeaux" => "Saluton,

[PRENOM] [NOM_FAMILLE] ĵus mendis ion el via donac-listo [GIFTLIST_NAME].

Jen la menditaj varoj:
[GIFTLIST_ITEMS]",
  "listecadeau_voir" => "Saluton,

Detaloj de la mesaĝo sendita je la [DATE].

[PRENOM] [NOM_FAMILLE] sendis al vi sian donac-liston [GIFTLIST_NAME] :
[URL_LISTE_CADEAU]

[GIFTLIST_ITEMS]",
  "parrainage" => "Saluton,

[PSEUDO] invitas vin malkovri la retejon [SITE] kaj profiti rabaton [REBATE] ĉe via unua mendo, post konfirmi kreadon de via klienta konto. 

Klaku la sekvan ligilon por finkonfirmi vian klientan konton: 
 [WWWROOT]/modules/parrainage/inscription.php?email=[EMAIL_FILLEUL]&code=[MDP]&id=[ID_UTILISATEUR]

Jen via salutnomo: [EMAIL_FILLEUL]
Kaj jen via pasvorto: [MDP]
",
  "email_alerte" => "Saluton,

La varo [NOM_PRODUIT] ja ĉeestas la stokon, sekvu ĉi ligilon por malkovri ĝin: [URLPROD]
",
  "decremente_stock" => "Saluton,

La alarman sojlon atingis la varo-stoko [NOM_PRODUIT].

Restas en la stoko: [STOCK_RESTANT_APRES_DEMANDE]",
  "admin_login" => "Saluton,

Vi ricevas ĉi mesaĝon post sukcesa konektiĝo de administranto de via retejo.

Klienta salutnomo: [USER]
IP de la konektanto: [REMOTE_ADDR]
Retroa DNS: [REVERSE_DNS]
Dato de la konekto: [DATETIME]

Ĉi retpoŝtaĵo celas atentigi vin pri sekurigo de via PEEL-vendejo.",
  "signature" => "

Klienta servo
[SITE]
[WWWROOT]/",
  "cree_cheque_cadeau_client_type1" => "Saluton,

[SITE] profitigas al vi donac-ĉekon valida dum 30 tagoj je monsumo [PERCENT].

Por profiti ĝin, sufiĉas viziti la retejon [WWWROOT]/ kaj uzi la rabat-kodon [CODE].
",
  "warn_admin_user_subscription" => "Je la [DATE], 

La sekva uzanto ĵus aliĝis:

[CIVILITE] [PRENOM] [NOM_FAMILLE]
[EMAIL]
[SOCIETE]
[TELEPHONE]
[PRIV]

[link=\"[ADMIN_URL]\"]Administri ĉi klientan konton[/link]
",
  "warn_admin_reve_subscription" => "",
"email_retour_virement" => "Saluton,

Ni bone ricevis vian returnaĵon n-o [RETURN_ID].
Konforme vian elekton, la kongrua monsumo [MONTANT] estos repagita pere de banka ĝiro kiel eble plej baldaŭ.",
  "email_retour_avoir" => "Saluton,

Ni bone ricevis vian returnaĵon n-o [RETURN_ID].
Konforme vian elekton, la kongrua monsumo [MONTANT] estis rekredigita en vian konton. Tiu [MODE] estos aŭtomate subtrahita el viaj venontaj mendoj, ĝis elĉerpiĝo.",
  "email_reste_avoir_remboursement" => "Saluton,

Ni bone ricevis vian returnaĵon n-o [RETURN_ID].
Ĉar la repaga monsomo ne povas esti pli alta ol la menda, la monsumo [MONTANT] estos repagita per [MODE]. La kredito estis ĵus enkalkulita en vian konton, t.e. [RESTE_AVOIR]. Ĉi kredito estos aŭtomate subtrahita el viaj venontaj mendoj, ĝis elĉerpiĝo.",
  "email_remboursement" => "Bonjour,

La repago de via returnaĵo n-o [RETURN_ID], kiu valoras [MONTANT] ĵus efektiviĝis per [MODE].",
  "email_retour_client" => "Saluton,

Vian peton pri returno ĵus registriĝis.
Nun, bonvolu sendi la varojn al la sekva adreso:

[SOCIETE]
RETURNO-NUMERO [RETURN_ID].

Memoru ke la returnitaj varoj estu bonstataj kaj pakitaj en la origina pakaĵo.
Tuj post ricevi la varojn, ni konfirmos ties repagon.",
  "cron_order_payment_failure_alerts" => "Saluton [PRENOM] [NOM],

Notu: se vi jam pagis kaj ricevis konfirmon, bonvolu ne kalkuli pri ĉi mesaĝon, ĉar vian pagon ni baldaŭ mane validigos.

Ĉi mesaĝo temas pri la mendo, kiun vi antaŭ nelonge provis fari ĉe [WWWROOT]/

Mendaĵo: [PRODUCT_NAME]
Entuta monsumo de via mendo: [TOTAL_AMOUNT]
Pagmaniero kiun vi elektis: [PAYMENT_MEAN]

Nia aŭtomata mendo-sistemo ankoraŭ ne ricevis konfirmon pri via pago.
Kiun problemon vi renkontis?

Ni atentas pri vi koncerne vian mendon pere de alia pagmaniero.

Ni disponigas nin por alporti al vi ĉiajn pliajn informojn.
Dum ni atendas vian respondon, ni deziras al vi plej bonan tagon.

Ĝis baldaŭ ĉe [WWWROOT]/
La skipo de [SITE_NAME]

Notu: ĉi mesaĝo estis aŭtomate sendita. Ni pardonpetas se vi jam provis nin kontakti, kaj ĉi kaze bonvolu ne kalkuli pri ĉi mesaĝo.",
  "cron_order_not_paid_alerts" => "Saluton [PRENOM] [NOM],

Ni skribas ĉi mesaĝon koncerne vian mendon farita antaŭ [DAYS_SINCE] tagoj ĉe [SITE_NAME].

Mendaĵo: [PRODUCT_NAME]
Entuta monsumo de via mendo: [TOTAL_AMOUNT]
Pagmaniero kiun vi elektis: [PAYMENT_MEAN]

Ni ankoraŭ ne ricevis vian pagon, ĉu vi bezonas pliajn informojn tiurilate, por efektivigi vian pagon?

Ni disponigas nin por alporti al vi ĉiajn pliajn informojn.

Dum ni atendas vian respondon, ni deziras al vi plej bonan tagon.

Notu: ĉi mesaĝo estis aŭtomate sendita. Ni pardonpetas se vi jam provis nin kontakti, kaj ĉi kaze bonvolu ne kalkuli pri ĉi mesaĝo.",
  "cron_update_contact_info" => "Saluton [CIVILITE] [NOM],

Cele al konservi ĝisdatigitajn informojn pri via klienta konto, ni petas vin kontroli la detalojn de ĉi informoj.

Detaloj pri viaj kontaj informoj:

Retpoŝtadreso: [EMAIL]
Ĝentileco: [CIVILITE]
Kromnomo: [PSEUDO]
Antaŭnomo: [PRENOM]
Familinomo: [NOM]
Entrepreno: [SOCIETE]
\"Intracom\" valoraldona-impost-kodo : [TVA_INTRA]
Telefonnumero: [TELEPHONE]
Poŝtelefonnumero: [PORTABLE]
Faksilnumero: [FAX]
Naskiĝdato: [NAISSANCE]
Adreso: [ADRESSE]
Poŝtkodo: [CODE_POSTAL]
Urbo: [VILLE]
lando: [PAYS]
Retejo-adreso: [SITE_WEB]

Kaze de misinformo, ni petas vin ĝisdatigi la informojn rekte ĉe via uzanto-paĝo, ĉe paĝo [WWWROOT]/utilisateurs/change_params.php

Kaze de perdo de via pasvorto, eblas uzi la pasvortilon ĉe paĝo [WWWROOT]/utilisateurs/oubli_mot_passe.php

Gravas memorigi, ke ĝusteco de tiuj informoj nepras por bona funkciado de la retejo kaj por ĉies komerca sukceso. Ĉiu misinformo povas okazigi la nuligon de via uzanto-konto.

Ni disponigas nin por alporti al vi ĉiajn pliajn informojn.",
"inscription_newsletter" => "Saluton,

Dankon pri via aliĝo al la novaĵ-bulteno de [SITE_NAME]. Vi de nun ricevos ĉiun semajnon la bultenon ĉe vian retpoŝtan keston [EMAIL].

Ĝis baldaŭ ĉe [WWWROOT]/",
  "send_mail_for_account_creation_stop" => "",
  "send_mail_for_account_creation_reve" => "",
  "send_mail_for_account_creation_stand" => "",
  "send_mail_for_account_creation_affi" => "",
  "validating_registration_by_admin" => ""
);

