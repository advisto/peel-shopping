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
// $Id: database_import_field_eo.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_import_field["texte"] = array(
   "id" => "Unika vara identigo, kiu ebligas ĝisdatigi registritan artiklon. Se ekzistas ĝia ĝusta idento en la datumbazo, ĝia priskribo ĝisdatiĝas.",
   "categorie_id" => "La kategoria nomo aŭ referenco (enmeti \"0\" se ĝi ne ekzistas). La referenco troviĝas en la <a href=\"categories.php\">listo de kategorioj, en la kolumno pri ident-numero</a>. La kategorio aŭtomate kreiĝos se ĝi ne jam ekzistas, kaj ke la nomo taŭgas",
   "Categorie" => "Alia ebla nomo de kolumno por categorie_id",
   "id_marque" => "La marka nomo aŭ referenco. La referenco troviĝas en la <a href=\"marques.php\">listo de markoj</a>. La marko aŭtomate kreiĝos se ĝi ne jam ekzistas",
   "reference" => "Vara referenco",
   "nom_fr" => "Franclingva nomo",
   "descriptif_fr" => "Franclingva vara priskribo",
   "description_fr" => "Franclingva vara priskribo",
   "nom_en" => "Anglalingva nomo",
   "descriptif_en" => "Anglalingva vara priskribo",
   "description_en" => "Anglalingva vara priskribo",
   "prix" => "Publika vendoprezo (ĉiuj impostoj inkluzivitaj)",
   "prix_revendeur" => "Revenda prezo (ĉiuj impostoj inkluzivitaj)",
   "prix_achat" => "Aĉeta prezo (ĉiuj impostoj inkluzivitaj)",
   "tva" => "Valoraldon-imposta elcento",
   "promotion" => "Rabato",
   "poids" => "Pezo (gramojn)",
   "points" => "Donac-poentoj",
   "image1" => "Bildo 1a: ĉefa bildo",
   "image2" => "Bildo 2a",
   "image3" => "Bildo 3a",
   "image4" => "Bildo 4a",
   "image5" => "Bildo 5a",
   "image6" => "Bildo 6a",
   "image7" => "Bildo 7a",
   "image8" => "Bildo 8a",
   "image9" => "Bildo 9a",
   "image10" => "Bildo 10a",
   "on_stock" => "Stoka masrtumado (1=jes, 0=ne)",
   "etat" => "Stato (1=ĉe-rete, 0=atendante)"
);

