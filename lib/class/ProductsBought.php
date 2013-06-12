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
// $Id: ProductsBought.php 36927 2013-05-23 16:15:39Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * ProductsBought
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: ProductsBought.php 36927 2013-05-23 16:15:39Z gboussin $
 * @access public
 */
class ProductsBought {
	var $nom;
	var $couleur;
	var $lien;
	/**
	 * ProductsBought::ProductsBought()
	 *
	 * @param mixed $obj
	 */
	function ProductsBought($obj)
	{
		foreach(get_object_vars($obj) as $name => $val) {
			if ($name == 'nom_' . $_SESSION['session_langue'] || $name == 'nom_produit') {
				$name = 'nom';
			} elseif ($name == 'couleur' && !empty($val)) {
				$val = "Couleur : " . $val;
			}
			$this->$name = $val;
		}
		if (!empty($this->in_catalog)) {
			$this->lien = '<a href="' . $GLOBALS['administrer_url'] . '/produits.php?mode=modif&amp;id=' . $this->produit_id . '">' . $this->nom . '</a>';
		} else {
			$this->lien = $this->nom . ' ('.$GLOBALS["STR_ADMIN_PRODUITS_NO_MORE_IN_CATALOG"].')';
		}
		$this->lien .= ' - ' . $this->couleur . ' - <a href="' . get_current_url(false) . '?id=' . $this->produit_id . '">'.$GLOBALS["STR_ADMIN_PRODUITS_BUYERS_LIST"].'</a>';
	}

	/**
	 * ProductsBought::_sql_de_base()
	 *
	 * @param string $id
	 * @return string SQL
	 */
	function _sql_de_base($id = null)
	{
		$sql = "SELECT ca.produit_id,
			ca.commande_id,
			ca.nom_produit,
			ca.couleur,
			ca.taille,
			SUM( ca.quantite ) AS quantite_totale,
			SUM( ca.total_prix ) AS montant_total,
			IF(p.id IS NOT NULL, 1,0) AS in_catalog
		FROM peel_commandes_articles ca
		LEFT JOIN peel_commandes c ON ca.commande_id = c.id
		LEFT JOIN peel_produits p ON p.id = ca.produit_id
		WHERE c.id_statut_paiement IN ('2','3')
			AND ca.quantite > '0'
			AND c.id_ecom = '" . intval($GLOBALS['site_parameters']['id']) . "'
			" . (!empty($id)?" AND ca.produit_id='" . intval($id) . "'":"") . "
		GROUP BY IF(ca.produit_id>0,ca.produit_id,ca.nom_produit), ca.couleur, ca.taille
		ORDER BY quantite_totale DESC
		LIMIT 500";
		return $sql;
	}

	/**
	 * ProductsBought::find()
	 *
	 * @param integer $id
	 * @return object
	 */
	function find($id)
	{
		$ret = null;
		$req = query(ProductsBought::_sql_de_base($id));
		if (!$req) {
			return null;
		}
		$ret = fetch_object($req);
		if ($ret) {
			$ret = new ProductsBought($ret);
		}
		return $ret;
	}

	/**
	 * ProductsBought::find_all()
	 *
	 * @return array
	 */
	function find_all()
	{
		$ret = array();
		$req = query(ProductsBought::_sql_de_base());
		while ($tmp = fetch_object($req)) {
			$ret[] = new ProductsBought($tmp);
		}
		return $ret;
	}

	/**
	 * ProductsBought::clients()
	 *
	 * @return array
	 */
	function clients()
	{
		$ret = array();
		$req = query("SELECT peel_utilisateurs.*,
				SUM(peel_commandes_articles.total_prix) AS total_paye,
				SUM(peel_commandes_articles.quantite) AS total_quantite
			FROM peel_utilisateurs
			RIGHT JOIN peel_commandes ON peel_commandes.id_utilisateur = peel_utilisateurs.id_utilisateur
			INNER JOIN peel_commandes_articles ON peel_commandes_articles.commande_id=peel_commandes.id
			WHERE peel_commandes_articles.produit_id = " . intval($this->produit_id) . " AND peel_commandes_articles.quantite > 0 AND peel_commandes.id_statut_paiement IN ('2','3')
			GROUP BY peel_utilisateurs.id_utilisateur");
		while ($tmp = fetch_object($req)) {
			$ret[] = $tmp;
		}
		return $ret;
	}
}

?>