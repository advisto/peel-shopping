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
// $Id: ProductsBought.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * ProductsBought
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: ProductsBought.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
class ProductsBought {
	var $nom;
	var $couleur;
	var $lien;

	/**
	 * 
	 *
	 * @param mixed $obj
	 */
	function __construct($obj)
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
	 * @param integer $limit
	 * @param boolean $raw
	 * @return string SQL
	 */
	public static function _sql_de_base($id = null, $limit = 500, $raw = false)
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
		INNER JOIN peel_commandes c ON ca.commande_id = c.id AND " . get_filter_site_cond('commandes', 'c', true) . "
		INNER JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
		LEFT JOIN peel_produits p ON p.id = ca.produit_id AND " . get_filter_site_cond('produits', 'p') . "
		WHERE " . get_filter_site_cond('commandes_articles', 'ca', true) . "
			AND sp.technical_code IN ('being_checked','completed')
			AND ca.quantite > '0'
			" . (!empty($id)?" AND ca.produit_id='" . intval($id) . "'":"") . "
		GROUP BY IF(ca.produit_id>0,ca.produit_id,ca.nom_produit), ca.couleur, ca.taille";
		if(!$raw) {
			$sql .= "
		ORDER BY quantite_totale DESC
		LIMIT " . intval($limit);
		}
		return $sql;
	}

	/**
	 * ProductsBought::find()
	 *
	 * @param integer $id
	 * @return object
	 */
	public static function find($id)
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
	 * @param integer $limit
	 * @return array
	 */
	public static function find_all($limit = 500)
	{
		$ret = array();
		$req = query(ProductsBought::_sql_de_base(null, $limit));
		while ($tmp = fetch_object($req)) {
			$ret[] = new ProductsBought($tmp);
		}
		return $ret;
	}

	/**
	 * 
	 *
	 * @return array
	 */
	public function clients()
	{
		$ret = array();
		$req = query("SELECT u.*,
				SUM(ca.total_prix) AS total_paye,
				SUM(ca.quantite) AS total_quantite
			FROM peel_utilisateurs u
			RIGHT JOIN peel_commandes c ON c.id_utilisateur = u.id_utilisateur AND " . get_filter_site_cond('commandes', 'c', true) . "
			INNER JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
			INNER JOIN peel_commandes_articles ca ON ca.commande_id=c.id AND  " . get_filter_site_cond('commandes_articles', 'ca', true) . "
			WHERE ca.produit_id = " . intval($this->produit_id) . " AND ca.quantite > 0 AND sp.technical_code IN ('being_checked','completed') AND " . get_filter_site_cond('utilisateurs', 'u', true) . "
			GROUP BY u.id_utilisateur");
		while ($tmp = fetch_object($req)) {
			$ret[] = $tmp;
		}
		return $ret;
	}
}

