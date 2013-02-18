<?
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Modules 7.0.0, which is subject to an      |
// | opensource commercial license: you are allowed to customize the code |
// | for your own needs, but you are NOT entitled to distribute this file |
// | More information: https://www.peel.fr/lire/licence-commerciale-71.html|
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: cartes.php 35103 2013-02-10 22:17:14Z gboussin $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin");

$DOC_TITLE = "Gérer les cartes de clés";

include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/haut.php");

switch (vb($_REQUEST['mode'])) {

	case "prix" : 
		foreach($_POST['id'] as $i => $id) {
			query("update peel_cartes SET prix = '".$_POST['prix'][$i]."', date_maj = now() WHERE id = '".intval($id)."'");
		}
	echo 'La grille de prix des cartes a été mise à jour';
		affiche_liste_cartes($_POST);
		break;
	
	case "ajout" :
		affiche_formulaire_ajout_carte(vn($_REQUEST['categorie_id']));
		break;
	
	case "modif" :
		//affiche_formulaire_modif_carte(vn($_REQUEST['id']));
		break;
	
	case "insere" :
		if (sizeof($_POST) > 0) {
			$frm = $_POST;
			//pour vérifier que le nom de la carte n'existe pas déjà:
			//faire:SELECT nom, if frm(nom) = nom_selected, echo 'déjà inséré', sinon tout le reste
			$message_erreur = valide_form_carte($frm, $erreurs);
		}
		if (empty($message_erreur)) {
			insere_carte(vn($_REQUEST['id']), $_POST);
			affiche_liste_cartes(@$start, 0);
		}
		if (!empty($message_erreur)) {
			echo "<span class='normal'><font color='red' ><b>Attention, votre formulaire est incomplet.</b></font></span><p></p>";
			if (! isset($categorie_id) ) {
				$categorie_id = 0;
			}
		}
		break;
	
	case "suppr" :
		supprime_carte(vn($_REQUEST['id']));
		affiche_liste_cartes($_POST);
		break;

	default :
		affiche_liste_cartes($_POST);
		break;
}
		
include($GLOBALS['dirroot'] . "/" . $GLOBALS['site_parameters']['backoffice_directory_name'] . "/modeles/bas.php");

/******************************************************************************
 * FONCTIONS
 *****************************************************************************/

function affiche_formulaire_ajout_carte($categorie_id = 0) {
/* Affiche un formulaire vierge pour ajouter une carte */

	global $categorie_options, $frm;

	/* Valeurs par défaut */
	$frm['categories'] = array($categorie_id);
	$frm['nouveau_mode'] = "insere";
	$frm['nom'] = "";
	$frm['prix'] = "";
	$frm['tva'] = "";	
	$frm['date_insere'] = "";	
	$frm['date_maj'] = "";	
	$frm['normal_bouton'] = "Ajouter";
	/* Construit la liste des catégories, préselectionne la catégorie racine */
	//construit_arbo_cat($categorie_options, $frm['categories']);

	affiche_formulaire_carte();
}

function affiche_formulaire_modif_carte($id) {
  /* Affiche le formulaire de modification pour la carte sélectionnée */

	global $frm,$categorie_options;
	echo $id;

	/* Charge les informations de la carte */
	$qid = query("
	SELECT *
	FROM peel_carte
	WHERE id = $id
	");
	
	$frm = fetch_assoc($qid);
	
	/* Charge les catégories de la carte */
/*	$qid = query("
	SELECT categorie_id
	FROM peel_cartes_categories
	WHERE cartes_id = $id
	");

	$frm['categories'] = array();
		
	if ($qid) {
	
		if (num_rows($qid) > 0) {
		
			while ($cat = fetch_assoc($qid)) {
			
				$frm['categories'][] = $cat['categorie_id'];
			}
		}		
	}
	
	$frm['nouveau_mode'] = "maj";
	
	$frm['normal_bouton'] = "Sauvegarder changements";
	
	construit_arbo_cat($categorie_options, $frm['categories']);*/
	
	affiche_formulaire_carte();

}

function affiche_formulaire_carte() {
	GLOBAL $frm;
	GLOBAL $id;
	GLOBAL $categorie_options;
	GLOBAL $wwwroot;
	GLOBAL $message_erreur;
	GLOBAL $erreurs;
	/*
	$verif_insert = verif_carte();
	if($verif_insert===true){
		echo "cette carte existe déjà";
		//affiche_formulaire_carte();
		//return;
	}
	*/
	$marqueur_erreur = "<font color=RED>".htmlspecialchars("=>")."</font>";
	?>
		<form name="entryform" method="post" action="<?=$_SERVER['PHP_SELF']?>" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="<?=vb($frm['nouveau_mode'])?>">
		<input type="hidden" name="id" value="<?=vb($frm['id'])?>">
		<h3>Ajouter/modifier une carte</h3>
			<table class=normal border=0 width=100%>
				<tr valign=top>
					<td class=normal colspan=2>Libellé de la carte :</td>
				</tr>
				<tr>
					<td colspan=2><input style="width: 100%" class="formulaire1" type="text" name="nom"  value="<?=vb($frm['nom']) ?>"></td>
				</tr>
				<tr>
			
				<tr valign=top>
					<td class=normal colspan=2>Prix en &euro; T.T.C (hors promotions):</td>
				</tr>
				<tr>
					<td colspan=2><input style="width: 100%" class="formulaire1" type="text" name="prix"  value="<?=vb($frm['prix']) ?>"></td>
				</tr>

				<tr>
					<td colspan=2>
						&nbsp;
					</td>
				</tr>
				
				<tr>
					<td colspan="2"><input class="bouton" type="submit" value="<?echo $frm['normal_bouton']; ?>"></td>
				</tr>
				</table>
		</form>
	<?
}

function verif_carte(){
	
	if(isset($_POST['nom'])) $new_carte = $_POST['nom'];
	else $new_carte = null;
	
	$sql = "SELECT nom FROM peel_cartes ORDER BY nom ASC";			
	$result=query($sql); 

	$cartes = fetch_assoc($result);
	$verif = in_array($new_carte, $cartes);

	return $verif;
	
}

function supprime_carte($id) {
/* Supprime la carte spécificiée par $id. Il faut supprimer la carte
 * puis les entrées correspondantes de la table peel_produits_categories. */


	/* Charge les infos de la carte. 
	$qid = query("SELECT cat.nom AS categorie 
		FROM peel_cartes car, peel_cartes_categories cc, peel_categories cat 
		WHERE car.id = cc.cartes_id AND cat.id = cc.categories_id and cat.id = $id");
	
	$carte = fetch_assoc($qid);

	/* Efface la carte */
	$qid = query("
	DELETE FROM peel_cartes
	WHERE id = $id
	");

	/* Efface cette carte de la table peel_cartes_categories
	$qid = query("
	DELETE FROM peel_cartes_categories
	WHERE cartes_id = $id");

	?>
		<p class=normal>
		La carte <b><?echo stripslashes($carte['categorie']); ?></b> a été effacé.

	<?
	*/
}

function insere_carte($id, $frm) {
/* Ajoute un nouveau sous-produit sous le parent $id.  Les champs sont dans la variable $frm */

	$nom = addSlashes($frm['nom']);
	/*ajoute le produit dans la table produits */
	$qid = query("
	INSERT INTO peel_cartes (
	prix
	, nom
	, date_insere
	, date_maj
	)
	VALUES (
	'$frm[prix]'
	, '$nom'	
	, now()
	, now()
	)
	");
	
}

function maj_cartes($id, $frm) {

	echo $id.', '.$frm[prix];
	/* Met à jour la table produits */
	$qid = query("
	UPDATE peel_cartes SET
		prix = '$frm[prix]'
		'
	WHERE id = $id
	");

	//TODO: 

}

function affiche_liste_cartes()
{
	global $wwwroot;
	global $categorie_options;
?>
<table border="0" cellpadding=0 cellspacing=2 width=100%>   
<tr>
	<td class="entete" colspan="6">Liste des cartes</td>
</tr>


<tr>
	<td colspan="6">
	<a class="normal" href="<?=$_SERVER['PHP_SELF']?>?mode=ajout">[Ajouter une carte]</a>
	
	</td>
</tr>

	<? 
	$nb = 225;
	
	$sql = "SELECT id, nom, prix, date_maj 
		FROM peel_cartes 
		ORDER BY nom ASC";
	
	$sql_count = "SELECT COUNT(*) 
		FROM peel_cartes";
	
	$result=query($sql); 

	if (num_rows($result) == 0)  {
		echo "<tr><td class=normal><b>Aucune carte enregistrée dans la base.</b></td></tr>";
	}  else {

	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']?>" name="formProduct" id="formProduct">
	<input type="hidden" name="mode" value="prix" />
	<tr bgcolor="#6699ff">
		<td class="menu">Action</td>
		<td class="menu">Catégorie</td>
		<td class="menu">Prix &euro; T.T.C</td>
		<td class="menu" align="center">Création / Mise à jour</td>
	</tr>
	<? 
	$i = 0;
	
	while ($ligne = fetch_assoc($result)) { 
	
	?>
			<input type="hidden" name="id[]" value="<?php echo $ligne['id']?>" />
			<tr bgcolor="<?php echo ($i % 2 == 0 ? '#F4F4F4' : '#ffffff' );?>">
				<td class="normal" align="center">
				<a onClick="Javascript:return confirm('Êtes-vous sûr de vouloir supprimer la carte ?');" class=normal title="Supprimer carte <?=$ligne['categorie'] ?>" href="<?=$_SERVER['PHP_SELF']?>?mode=suppr&id=<?=$ligne['id'] ?>">
				<img src=<?=$wwwroot?>/images/poubelle.gif border=0></a></td>
				<!--<td class=normal align="center"><a class=normal title="Modifier ce produit" href="<?=$_SERVER['PHP_SELF']?>?mode=modif&id=<?=$ligne['id'] ?>"><?=stripslashes($ligne['categorie'])?></a></td>-->
				<td class=normal align="center"><?=stripslashes($ligne['nom'])?></td>
				<td class=normal align="center"><input type="text" style="width:75px" name="prix[]" value="<?=$ligne['prix'] ?>" /> € T.T.C</td>
				<td class=normal align="center"><?=get_formatted_date($ligne['date_maj']) ?></td>
			</tr>
			
	<?php 
		$i++;
	} 
	
	}
	
	?>
	<tr><td align="center" class="normal" colspan="6">
			<input type="submit" name="submit" class="bouton" value="mettre à jour" />
			</td>
			</tr>
			<tr><td align="center" class="normal" colspan="6">
	
	<?php
	echo "</td></tr></table>";
}

function valide_form_carte(&$frm, &$erreurs) {

	$erreurs = array();
	$msg = array();
	if (empty($frm['nom'][0])) {
		$erreurs['nom'] = true;
		$msg['nom'] = " Vous devez donner un libellé à la nouvelle carte";
	} 
	return $msg;
}

?>