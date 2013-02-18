<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: Caddie.php 35067 2013-02-08 14:21:55Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}
/**
 * Caddie
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Caddie.php 35067 2013-02-08 14:21:55Z gboussin $
 * @access public
 */
class Caddie {
	/* Déclaration des tableaux */

	/* gestion de la liste cadeau */
	var $giftlist_owners;
	/* Tableau des articles */
	var $articles;
	/* Tableau des quantités */
	var $quantite;
	/* Tableau des poids */
	var $poids;
	/* Tableau des points */
	var $points;
	/* Tableau des couleurs */
	var $couleurId;
	/* Tableau des tailles */
	var $tailleId;
	/* Tableau des prix unitaire TTC*/
	var $prix;
	/* Tableau des prix unitaire HT*/
	var $prix_ht;
	/* Tableau des prix unitaire TTC*/
	var $prix_cat;
	/* Tableau des prix unitaire HT*/
	var $prix_cat_ht;
	/* Tableau prix TTC d'un produit avant l'application d'un code promotionnel*/
	var $prix_avant_code_promo;
	/* Tableau prix TTC d'un produit avant l'application d'un code promotionnel*/
	var $prix_ht_avant_code_promo;
	/* Net produit vendu TTC prix x qte*/
	var $total_prix;
	/* Net produit vendu HT prix_ht x qte */
	var $total_prix_ht;
	/* Tableau des taux de TVA */
	var $tva_percent;
	/* Tableau des TVA en valeur */
	var $tva;
	// percent_remise_produit est le tableau des promotions en pourcentage (colonne "promotion" dans peel_produits + toutes les autres réductions en pourcentage)  sur les produits
	var $percent_remise_produit;
	/* Tableau des type (EUR ou %) de remises par marque */
	var $remise;
	/* Tableau des remises HT par produit en EUR */
	var $remise_ht;
	/* Tableau stock produit géré ou non*/
	var $etat_stock;
	/* Tableau délai d'approvisionnement du stock */
	var $delai_stock;
	/* Tableau du prix des options */
	var $option;
	/* Tableau du prix des options */
	var $option_ht;
	/* Tableau des emails amis */
	var $email_check;
	/* Tableau de l'ecotaxe par ligne */
	var $ecotaxe_ttc;
	/* Tableau de l'ecotaxe HT par ligne */
	var $ecotaxe_ht;
	/* Tableau des attributs */
	var $attribut;

	/* Déclaration des variables */
	var $id_attribut;
	var $total_prix_attribut;
	/* Montant total du caddie */
	var $total;
	/* Montant total du caddie HT */
	var $total_ht;
	var $total_quantite;
	/* Montant total du caddie */
	var $total_produit;
	var $total_produit_related_to_code_promo;
	var $total_ecotaxe_ttc_related_to_code_promo;
	var $total_produit_avant_code_promo;
	/* Montant total du caddie HT */
	var $total_produit_ht;
	var $tva_total_produit;
	var $total_tva;
	/* Poids total du caddie */
	var $total_poids;
	/* Total des points cadeaux */
	var $total_points;

	var $cout_transport;
	var $cout_transport_ht;
	var $tva_cout_transport;

	var $total_remise;
	var $total_remise_ht;
	var $tva_total_remise;

	var $total_option;
	var $total_option_ht;
	var $tva_total_option;

	/* Nom du code promo */
	var $code_promo;

	var $percent_remise_user;
	var $percent_code_promo;
	var $valeur_code_promo;
	var $total_reduction_code_promo;
	var $total_reduction_percent_code_promo;
	// Détermine si un code promo s'applique à une seule catégorie ou toutes
	var $cat_code_promo;

	/* Avoir client en EURO */
	var $avoir;
	var $avoir_user;
	// Country name (and not id)
	var $pays;
	var $zone;
	var $zoneId;
	var $zoneTva;
	var $zoneFranco;
	var $zone_technical_code;
	var $type;
	var $typeId;
	var $apply_vat;

	var $payment_technical_code;
	/* supplément pour le paiement */
	var $tarif_paiement;
	/* supplément pour le paiement */
	var $tarif_paiement_ht;
	/* supplément pour le paiement */
	var $tva_tarif_paiement;

	var $total_ecotaxe_ttc;
	var $total_ecotaxe_ht;
	var $tva_total_ecotaxe;

	var $message_caddie;

	/*Order ID for Socolissimo */
	var $delivery_orderid;

	/* ID de la commande liée au caddie */
	var $commande_id;
	var $commande_hash;
	var $conditionnement;

	/**
	 * Caddie::Caddie()
	 *
	 * @param mixed $percent_remise_user
	 */
	function Caddie($percent_remise_user)
	{
		$this->percent_remise_user = $percent_remise_user;
		/* constructeur d'object */
		$this->init();
	}

	/**
	 * Initialise le caddie
	 *
	 * @return
	 */
	function init()
	{
		if (is_stock_advanced_module_active() && empty($this->commande_id) && !empty($this->articles)) {
			foreach($this->articles as $numero_ligne => $product_id) {
				if ($this->etat_stock[$numero_ligne] == 1) {
					// NB : On ne gère les stocks remporaire que si commande_id est vide, sinon les stocks sont gérés directement avec peel_stocks
					// On annule la réservation de stock temporaire
					// c'est-à-dire décrémentation de la table peel_stocks_temp
					$couleur_id = intval($this->couleurId[$numero_ligne]);
					$taille_id = intval($this->tailleId[$numero_ligne]);
					$quantite = intval($this->quantite[$numero_ligne]);
					if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
						// Les produits sont conditionnés sous forme de lot
						$real_stock_used = $this->conditionnement[$numero_ligne] * $quantite;
					} else {
						$real_stock_used = intval($this->quantite[$numero_ligne]);
					}
					liberation_stock_temp($product_id, $couleur_id, $taille_id, $real_stock_used);
				}
			}
		}
		// On initialise tout
		$this->giftlist_owners = array();
		$this->articles = array();
		$this->quantite = array();
		$this->poids = array();
		$this->points = array();
		$this->couleurId = array();
		$this->tailleId = array();
		$this->prix = array();
		$this->prix_ht = array();
		$this->prix_cat = array();
		$this->prix_cat_ht = array();
		$this->prix_avant_code_promo = array();
		$this->total_prix = array();
		$this->total_prix_ht = array();
		$this->tva_percent = array();
		$this->tva = array();
		$this->percent_remise_produit = array();
		$this->remise = array();
		$this->remise_ht = array();
		$this->prix_ht_avant_code_promo = array();
		$this->etat_stock = array();
		$this->delai_stock = array();
		$this->option = array();
		$this->option_ht = array();
		$this->email_check = array();
		$this->ecotaxe_ttc = array();
		$this->ecotaxe_ht = array();
		$this->attribut = array();
		$this->id_attribut = array();
		$this->total_prix_attribut = array();

		/* Montant total du caddie */
		$this->total = 0;
		/* Montant total du caddie HT */
		$this->total_ht = 0;
		$this->total_tva = 0;

		$this->total_quantite = 0;

		/* Montant total des produits dans le caddie */
		$this->total_produit = 0;
		$this->total_produit_ht = 0;
		$this->tva_total_produit = 0;
		$this->total_produit_avant_code_promo = 0;

		/* Poids total du caddie */
		$this->total_poids = 0;
		/* Total des points cadeaux */
		$this->total_points = 0;

		$this->cout_transport = 0;
		$this->cout_transport_ht = 0;
		$this->tva_cout_transport = 0;

		$this->total_remise = 0;
		$this->total_remise_ht = 0;
		$this->tva_total_remise = 0;

		$this->total_option = 0;
		$this->total_option_ht = 0;
		$this->tva_total_option = 0;
		$this->total_reduction_percent_code_promo = 0;
		/* Nom du code promo */
		$this->code_promo = "";
		// NE PAS FAIRE $this->percent_remise_user = 0; : c'est le seul attribut à ne pas initialiser car défini dans le cosntructeur
		$this->percent_code_promo = 0;
		$this->valeur_code_promo = 0;

		/* Avoir client en EURO */
		$this->avoir = 0;
		$this->avoir_user = 0;
		// type est ici le nom du type de livraison (en base de données, la colonne type est une id qui s'appelle ici typeId)
		$this->type = "";
		$this->typeId = "";
		// zone est ici le nom de la zone de livraison (en base de données, la colonne zone est une id qui s'appelle ici zoneId)
		$this->zone = "";
		$this->zoneId = "";
		// Valeur par défaut : on applique la TVA, tant qu'une zone n'est pas encore sélectionnée ce qui permettra de savoir si la TVA est bien applicable ou non
		$this->zoneTva = 1;
		$this->apply_vat = true;
		$this->zoneFranco = 1;
		$this->zone_technical_code = "";

		$this->payment_technical_code = '';
		$this->tarif_paiement = 0;
		$this->tarif_paiement_ht = 0;
		$this->tva_tarif_paiement = 0;

		$this->total_ecotaxe_ttc = 0;
		$this->total_ecotaxe_ht = 0;
		$this->tva_total_ecotaxe = 0;

		$this->message_caddie = array();

		$this->commande_id = 0;
		$this->commande_hash = '';

		$this->delivery_orderid = String::substr(sha1(mt_rand(1, 10000000)), 0, 16);
		// Au cas où certaines variables ne seraient pas bien nettoyées, on recalcule l'ensemble pour assurer une parfaite coéhrence
		$this->update();
	}

	/**
	 * affiche_erreur_caddie()
	 *
	 * @return
	 */
	function affiche_erreur_caddie()
	{
		if (count($this->message_caddie) > 0) {
			if (!empty($this->message_caddie['ERROR_CODE_PROMO'])) {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => String::html_entity_decode_if_needed($this->message_caddie['ERROR_CODE_PROMO'])))->fetch();
				unset($this->message_caddie['ERROR_CODE_PROMO']);
			}
			if (!empty($this->message_caddie['SUCCES_CODE_PROMO'])) {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => String::html_entity_decode_if_needed($this->message_caddie['SUCCES_CODE_PROMO'])))->fetch();
				unset($this->message_caddie['SUCCES_CODE_PROMO']);
			}
		}
	}

	/**
	 * On ajoute un nouveau produit dans le panier
	 *
	 * @param class $product_object
	 * @param integer $quantite
	 * @param string $email_check
	 * @param string $listcadeaux_owner
	 * @return
	 */
	function add_product(&$product_object, $quantite, $email_check, $listcadeaux_owner = null)
	{
		if (in_array($product_object->id, $this->articles) && empty($email_check)) {
			// Si le produit est dans le caddie, et que ce n'est pas un chèque cadeau, alors on va vouloir fusionner les données dans une même ligne
			foreach ($this->articles as $k => $this_produit_id) {
				if ($product_object->id == $this_produit_id && $product_object->configuration_color_id == $this->couleurId[$k] && $product_object->configuration_size_id == $this->tailleId[$k] && $product_object->configuration_attributs_list == $this->id_attribut[$k]) {
					$line_found = $k;
					break;
				}
			}
		}
		// Si le produit est déjà dans le panier avec la bonne configuration de couleur et de taille
		if (isset($line_found)) {
			if (is_giftlist_module_active() && $listcadeaux_owner != null) {
				// on teste pour qui est destiné le cadeau
				if ($this->giftlist_owners[$line_found] == $listcadeaux_owner) {
					// destinataire déjà répertorié
					// on regarde ses besoins en quantité
					$quantite = min($this->quantite[$line_found] + $quantite, getNessQuantityFromGiftList($this->articles[$line_found], $this->giftlist_owners[$line_found]));
					$this->change_line_data($line_found, $product_object->id, $quantite, $product_object->configuration_color_id, $product_object->configuration_size_id, $email_check, $product_object->configuration_attributs_list, $listcadeaux_owner);
				}
			} else {
				$quantite = $this->quantite[$line_found] + $quantite;
				$this->change_line_data($line_found, $product_object->id, $quantite, $product_object->configuration_color_id, $product_object->configuration_size_id, $email_check, $product_object->configuration_attributs_list);
			}
		} else {
			// on ajoute le produit au panier
			// Une nouvelle ligne doit être créée dans le panier
			if (!isset($this->articles[0])) {
				$numero_ligne = 0;
			} else {
				$numero_ligne = max(array_keys($this->articles)) + 1;
			}
			$this->articles[$numero_ligne] = $product_object->id;
			// Si on gère les stocks pour ce produit, la valeur $quantite est temporaire avant validation du stock disponible
			$this->quantite[$numero_ligne] = $quantite;

			$this->couleurId[$numero_ligne] = $product_object->configuration_color_id;
			$this->tailleId[$numero_ligne] = $product_object->configuration_size_id;
			$this->email_check[$numero_ligne] = $email_check;
			$this->id_attribut[$numero_ligne] = $product_object->configuration_attributs_list;
			$this->conditionnement[$numero_ligne] = $product_object->conditionnement;
			if (is_giftlist_module_active()) {
				$this->giftlist_owners[$numero_ligne] = $listcadeaux_owner;
			}
			if (is_stock_advanced_module_active() && empty($this->commande_id)) {
				// NB : On ne gère les stocks remporaire que si commande_id est vide, sinon les stocks sont gérés directement avec peel_stocks
				// On appelle update_line car on doit d'abord récupérer la valeur de $this->etat_stock[$numero_ligne]
				$this->update_line($numero_ligne);
				if ($this->etat_stock[$numero_ligne] == 1) {
					if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
						// Les produits sont conditionnés sous forme de lot
						$this->quantite[$numero_ligne] = reservation_stock_temp_conditionnement($this, $numero_ligne, $product_object->id, $product_object->configuration_color_id, $product_object->configuration_size_id);
					} else {
						$this->quantite[$numero_ligne] = reservation_stock_temp($product_object->id, $product_object->configuration_color_id, $product_object->configuration_size_id, $this->quantite[$numero_ligne]);
					}
				}
			}
		}
	}

	/**
	 * Caddie::change_lines_data()
	 *
	 * @param array $line_infos Array with all fields data
	 * @return
	 */
	function change_lines_data(&$line_infos)
	{
		if (!empty($line_infos['id'])) {
			foreach ($line_infos['id'] as $numero_ligne => $product_id) {
				if (!empty($this->articles[$numero_ligne])) {
					$this->change_line_data($numero_ligne, $product_id, vn($line_infos['quantite'][$numero_ligne]), vn($line_infos['couleurId'][$numero_ligne]), vn($line_infos['tailleId'][$numero_ligne]), vb($line_infos['email_check'][$numero_ligne]), vn($line_infos['id_attribut'][$numero_ligne]), vb($line_infos['listcadeaux_owner'][$numero_ligne]));
				}
			}
		}
		$this->update();
	}

	/**
	 * On met à jour une ligne de produit, en connaissant déjà son numéro
	 *
	 * @param mixed $numero_ligne
	 * @param mixed $product_id
	 * @param mixed $quantite
	 * @param mixed $couleur_id
	 * @param mixed $taille_id
	 * @param mixed $email_check
	 * @param mixed $liste_attribut
	 * @param mixed $listcadeaux_owner
	 * @return
	 */
	function change_line_data($numero_ligne, $product_id, $quantite, $couleur_id, $taille_id, $email_check, $liste_attribut, $listcadeaux_owner = null)
	{
		if (!is_numeric($numero_ligne)) {
			return false;
		}
		if (is_stock_advanced_module_active() && empty($this->commande_id)) {
			// On ne fait pas appel à update_line ici / pour la décision, on garde la valeur de $this->etat_stock[$numero_ligne] qu'on avait
			if ($this->etat_stock[$numero_ligne] == 1) {
				// NB : On ne gère les stocks remporaire que si commande_id est vide, sinon les stocks sont gérés directement avec peel_stocks
				// on libère les stocks temporaires correspondant à l'ancienne ligne
				if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
					$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
				} else {
					$real_stock_used = intval($this->quantite[$numero_ligne]);
				}
				liberation_stock_temp(intval($this->articles[$numero_ligne]), intval($this->couleurId[$numero_ligne]), intval($this->tailleId[$numero_ligne]), $real_stock_used);
			}
		}
		$this->articles[$numero_ligne] = $product_id;
		// Si on gère les stocks pour ce produit, la valeur $quantite est temporaire avant validation du stock disponible
		$this->quantite[$numero_ligne] = $quantite;
		$this->couleurId[$numero_ligne] = $couleur_id;
		$this->tailleId[$numero_ligne] = $taille_id;
		$this->email_check[$numero_ligne] = $email_check;
		$this->id_attribut[$numero_ligne] = $liste_attribut;
		$this->giftlist_owners[$numero_ligne] = $listcadeaux_owner;
		if (is_stock_advanced_module_active() && empty($this->commande_id)) {
			// On appelle update_line car on doit d'abord récupérer la valeur de $this->etat_stock[$numero_ligne]
			$this->update_line($numero_ligne);
			if ($this->etat_stock[$numero_ligne] == 1) {
				// et on réserve des stocks temporaires pour les nouvelles informations de la ligne
				if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
					$this->quantite[$numero_ligne] = reservation_stock_temp_conditionnement($this, $numero_ligne, $product_id, $couleur_id, $taille_id);
				} else {
					$this->quantite[$numero_ligne] = reservation_stock_temp($product_id, $couleur_id, $taille_id, $quantite);
				}
			}
		}
	}

	/**
	 * Caddie::update_line()
	 *
	 * @param mixed $numero_ligne
	 * @return
	 */
	function update_line($numero_ligne)
	{
		$product_object = new Product($this->articles[$numero_ligne], null, false, null, true, $this->apply_vat);
		$product_object->set_configuration($this->couleurId[$numero_ligne], $this->tailleId[$numero_ligne], $this->id_attribut[$numero_ligne], is_reseller_module_active() && is_reseller());

		/* Traitement de l'ecotaxe */
		$this->delai_stock[$numero_ligne] = $product_object->delai_stock;
		$this->etat_stock[$numero_ligne] = $product_object->on_stock;
		$this->ecotaxe_ht[$numero_ligne] = $product_object->ecotaxe_ht;
		if (is_gifts_module_active()) {
			// Total points de la ligne
			$this->points[$numero_ligne] = $product_object->points * $this->quantite[$numero_ligne];
		}
		if ($this->apply_vat) {
			// Si la zone de TVA est active
			$this->tva_percent[$numero_ligne] = $product_object->tva;
			$this->ecotaxe_ttc[$numero_ligne] = $product_object->ecotaxe_ttc;
			$apply_vat = true;
		} else {
			// Si zone hors TVA, on ne doit pas facturer la TVA de l'ecotaxe
			$this->tva_percent[$numero_ligne] = 0;
			$this->ecotaxe_ttc[$numero_ligne] = $product_object->ecotaxe_ht;
			$apply_vat = false;
		}
		if (is_attributes_module_active()) {
			// Ces valeurs servent pour remplir la table peel_commandes, mais pas pour les calculs qui se servent de la classe Product qui gère tout cela
			$this->total_prix_attribut[$numero_ligne] = $product_object->format_prices($product_object->configuration_total_original_price_attributs_ht, $apply_vat, false, false, false);
			$this->attribut[$numero_ligne] = $product_object->configuration_attributs_description;
		}
		// Les valeurs des options ne contiennent pas les éventuelles réductions en pourcentage
		$this->option_ht[$numero_ligne] = $product_object->format_prices($product_object->configuration_size_price_ht + $product_object->configuration_total_original_price_attributs_ht, false, false, false, false);
		$this->option[$numero_ligne] = $product_object->format_prices($product_object->configuration_size_price_ht + $product_object->configuration_total_original_price_attributs_ht, $apply_vat, false, false, false);
		// Total poids de la ligne
		$this->poids[$numero_ligne] = ($product_object->poids + $product_object->configuration_overweight) * $this->quantite[$numero_ligne];
		// Calcul du prix original avant réductions et options
		$this->prix_cat_ht[$numero_ligne] = $product_object->get_original_price(false, is_reseller_module_active() && is_reseller(), false, false, true, false);
		$this->prix_cat[$numero_ligne] = $product_object->get_original_price($apply_vat, is_reseller_module_active() && is_reseller(), false, false, true, false);
		if (display_prices_with_taxes_active()) {
			// On doit arrondir les valeurs tarifaires officielles qui sont en TTC
			$this->prix_cat[$numero_ligne] = round($this->prix_cat[$numero_ligne], 2);
			$this->option[$numero_ligne] = round($this->option[$numero_ligne], 2);
			$this->ecotaxe_ttc[$numero_ligne] = round($this->ecotaxe_ttc[$numero_ligne], 2);
		} else {
			// On doit arrondir les valeurs tarifaires officielles qui sont en HT
			$this->prix_cat_ht[$numero_ligne] = round($this->prix_cat_ht[$numero_ligne], 2);
			$this->option_ht[$numero_ligne] = round($this->option_ht[$numero_ligne], 2);
			$this->ecotaxe_ht[$numero_ligne] = round($this->ecotaxe_ht, 2);
		}
		// NB : on n'applique pas ici la ventilation d'un code promo en valeur sur le nouveau produit, car le calcul concerne forcément tout le panier en même temps
		// On fait donc cela dans la fonction recalc
		// On n'applique pas non plus les codes promos de réductions en pourcentage ici pour tout faire au même endroit.
		if (!empty($this->quantite[$numero_ligne])) {
			// La variable prix_avant_code_promo sert pour connaître le montant acheté pour savoir si on peut appliquer ou non un code promo
			// Pour tenir compte des prix par lots, on récupère le prix pour l'ensemble des produits et on divise par la quantité
			if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
				$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
			} else {
				$real_stock_used = intval($this->quantite[$numero_ligne]);
			}
			$this->prix_ht_avant_code_promo[$numero_ligne] = $product_object->get_final_price($this->percent_remise_user, false, is_reseller_module_active() && is_reseller(), false, false, $real_stock_used, true, true) / $real_stock_used;
			$this->prix_avant_code_promo[$numero_ligne] = $product_object->get_final_price($this->percent_remise_user, $apply_vat, is_reseller_module_active() && is_reseller(), false, false, $real_stock_used, true, true) / $real_stock_used;
		}
		$this->percent_remise_produit[$numero_ligne] = $product_object->get_all_promotions_percentage(is_reseller_module_active() && is_reseller(), $this->percent_remise_user, false);
		unset($product_object);
	}

	/**
	 * Enlève un produit du caddie
	 *
	 * @param mixed $numero_ligne
	 * @return
	 */
	function delete_line($numero_ligne)
	{
		if (is_stock_advanced_module_active() && empty($this->commande_id) && vb($this->etat_stock[$numero_ligne]) == 1) {
			// NB : On ne gère les stocks remporaire que si commande_id est vide, sinon les stocks sont gérés directement avec peel_stocks
			// On annule la réservation de stock temporaire
			// c'est-à-dire décrementation de la table peel_stocks_temp
			if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
				$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
			} else {
				$real_stock_used = intval($this->quantite[$numero_ligne]);
			}
			liberation_stock_temp(intval($this->articles[$numero_ligne]), intval($this->couleurId[$numero_ligne]), intval($this->tailleId[$numero_ligne]), $real_stock_used);
		}
		$attributs_list = $this->id_attribut[$numero_ligne];
		unset($this->giftlist_owners[$numero_ligne],
			$this->articles[$numero_ligne],
			$this->quantite[$numero_ligne],
			$this->poids[$numero_ligne],
			$this->points[$numero_ligne],
			$this->couleurId[$numero_ligne],
			$this->tailleId[$numero_ligne],
			$this->prix[$numero_ligne],
			$this->prix_ht[$numero_ligne],
			$this->prix_cat[$numero_ligne],
			$this->prix_cat_ht[$numero_ligne],
			$this->prix_avant_code_promo[$numero_ligne],
			$this->prix_ht_avant_code_promo[$numero_ligne],
			$this->total_prix[$numero_ligne],
			$this->total_prix_ht[$numero_ligne],
			$this->tva_percent[$numero_ligne],
			$this->tva[$numero_ligne],
			$this->percent_remise_produit[$numero_ligne],
			$this->remise[$numero_ligne],
			$this->remise_ht[$numero_ligne],
			$this->etat_stock[$numero_ligne],
			$this->delai_stock[$numero_ligne],
			$this->option[$numero_ligne],
			$this->option_ht[$numero_ligne],
			$this->email_check[$numero_ligne],
			$this->ecotaxe_ttc[$numero_ligne],
			$this->ecotaxe_ht[$numero_ligne],
			$this->id_attribut[$numero_ligne],
			$this->attribut[$numero_ligne],
			$this->total_prix_attribut[$numero_ligne]);
		// suppression des attributs d'image existants
		if (!empty($attributs_list)) {
			foreach(explode("-", $attributs_list) as $tableau_id_array) {
				$tableau_attribut_id = explode("|", $tableau_id_array);
				if (count($tableau_attribut_id)) {
					if (array_key_exists(0, $tableau_attribut_id)) { // si c'est un attribut en texte libre
						delete_uploaded_file_and_thumbs($tableau_attribut_id[2]);
					}
				}
			}
		}
	}

	/**
	 * Appeler cette fonction avec $new_code_promo="CODE" pour l'appliquer, $new_code_promo="" pour supprimer le code promo,
	 * ou avec $new_code_promo=null pour vérifier si code promo toujours bien applicable
	 *
	 * @param mixed $new_code_promo
	 * @return
	 */
	function update_code_promo($new_code_promo = null)
	{
		$this->total_produit_related_to_code_promo = 0;
		$this->total_ecotaxe_ttc_related_to_code_promo = 0;
		if ($new_code_promo !== null) {
			$this->code_promo = $new_code_promo;
		} elseif (!empty($_GET['code_promo']) && $new_code_promo == "") {
			$this->message_caddie = array("SUCCES_CODE_PROMO" => $GLOBALS['STR_YOUR_CODE_PROMO'] . ' ' . $this->code_promo . ' ' . $GLOBALS['STR_HAS_BEEN_DELETED'] . '.');
		}
		if (!empty($this->code_promo)) {
			// On vérifie d'abord la validité du code promo avec les paramètres insérés en back-office
			// On trouve le code promo si il est valide dans l'absolu
			// Restera à voir ensuite si il est bien valide pour l'utilisateur qui veut l'utiliser
			$sql = "SELECT *
				FROM `peel_codes_promos`
				WHERE nom='" . nohtml_real_escape_string($this->code_promo) . "' AND (nombre_prevue=0 OR compteur_utilisation<nombre_prevue) AND '" . date('Y-m-d', time()) . "' BETWEEN `date_debut` AND `date_fin` AND etat = '1'";
			$query = query($sql);
			$code_infos = fetch_assoc($query);
		}
		// REMARQUE si on passe ici avec $new_code_promo non vide :
		// alors on doit absolument s'assurer que toutes les infos sur les produits et les ecotaxes sont bien à jour (exemple : nouveau produit vient d'être ajouté)
		// et donc dans ce cas on ne doit pas traiter maintenant la réduction, mais plus tard lorsque cette fonction sera appelée via update
		if (!empty($code_infos) && $new_code_promo === null) {
			// On traite un code promo qui est valide et ne vient pas d'être enregistré (cf. explications ci-dessus)
			if (!empty($code_infos['id_categorie'])) {
				// Si le code ne s'applique qu'à une catégorie et à ses filles
				$this->cat_code_promo = $code_infos['id_categorie'];
				$code_only_for_one_cat_and_sons = true;
				foreach ($this->articles as $numero_ligne => $product_id) {
					// On cherche le montant par catégorie de produit pour pouvoir appliquer ensuite
					// des codes promos avec des seuils minimum sur une catégorie donnée
					// On fait la somme des produits en faisant attention à ce qu'un produit pourrait apparaître dans plusieurs catégories donc on peut sommer les montants
					$q_get_product_cat = query('SELECT categorie_id
						FROM peel_produits_categories ppc
						WHERE ppc.produit_id = "' . intval($product_id) . '" AND ppc.categorie_id IN ("' . implode('","', nohtml_real_escape_string(get_category_tree_and_itself($this->cat_code_promo, 'sons'))) . '")
						LIMIT 1');
					if ($r_get_product_cat = fetch_assoc($q_get_product_cat)) {
						$found_cat = true;
						// ATTENTION : la somme de $this->total_produit_related_to_code_promo n'est pas égale au total du caddie si des produits se retrouvent dans plusieurs catégories
						$this->total_produit_related_to_code_promo += $this->prix_avant_code_promo[$numero_ligne] * $this->quantite[$numero_ligne];
						// On calcule l'écotaxe car on doit la retirer du montant sur lequel on applique un pourcentage de réduction
						$this->total_ecotaxe_ttc_related_to_code_promo += $this->ecotaxe_ttc[$numero_ligne] * $this->quantite[$numero_ligne];
					}
				}
			} else {
				$this->cat_code_promo = 0;
				$code_only_for_one_cat_and_sons = false;
				$this->total_ecotaxe_ttc_related_to_code_promo = $this->total_ecotaxe_ttc;
				$this->total_produit_related_to_code_promo = $this->total_produit_avant_code_promo;
			}
			if ($code_only_for_one_cat_and_sons == false || ($code_only_for_one_cat_and_sons == true && !empty($found_cat))) {
				// Si le code s'applique à toutes les catégories OU si le code s'applique à une seule catégorie et qu'il y a au moins un article de la catégorie correspondante dans le panier
				// On vérifie maintenant que le code est bien valide pour l'utilisateur qui veut l'utiliser
				$sql_check_cp_use = "SELECT id
					FROM peel_commandes
					WHERE code_promo = '" . nohtml_real_escape_string($this->code_promo) . "' AND id_utilisateur ='" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'"
				 . (!empty($this->commande_id)? " AND id !='" . intval($this->commande_id) . "'" : "")
				 . " AND id_statut_livraison NOT IN ('6','9')";
				// Le code a-t-il déjà été utilisé par ce client ?
				$q_check_cp_use = query($sql_check_cp_use);
				if (($code_infos['nombre_prevue'] == 0 || num_rows($q_check_cp_use) < $code_infos['nombre_prevue']) && ($code_infos['nb_used_per_client'] == 0 || num_rows($q_check_cp_use) < $code_infos['nb_used_per_client'])) {
					// Si le code promotionnel n'a pas été trouvé dans une commande antérieure pour ce client, on passe à la suite.
					if ($code_infos['montant_min'] <= $this->total_produit_related_to_code_promo) {
						// Le code est OK : le montant minimum du code promotionnel est bien inférieur au montant total des catégories concernées par le code_promo
						$this->message_caddie = array("SUCCES_CODE_PROMO" => $GLOBALS['STR_YOUR_CODE_PROMO'] . ' ' . $code_infos['nom'] . ' ' . $GLOBALS['STR_IS_VALID'] . '.');
						if ($code_infos['on_type'] == 1) {
							$this->percent_code_promo = $code_infos['remise_percent'];
							$this->valeur_code_promo = 0;
						} elseif ($code_infos['on_type'] == 2) {
							$this->percent_code_promo = 0;
							// La réduction ne peut être plus importante que le montant des produits concernés
							$this->valeur_code_promo = min($code_infos['remise_valeur'], $this->total_produit_avant_code_promo);
						}
					} else {
						// Le code n'est pas pris en compte : le montant minimum du code promotionnel est supérieur au montant total des catégories concernées par le code_promo
						$cancel_code = true;
						$this->message_caddie = array("ERROR_CODE_PROMO" => nl2br($GLOBALS['STR_CART_IS_NOT_ENOUGHT']));
					}
				} else {
					$cancel_code = true;
					$this->message_caddie = array("ERROR_CODE_PROMO" => nl2br($GLOBALS['STR_CODE_PROMO_USE_ONLY_ONCE']));
				}
			} else {
				$cancel_code = true;
				$this->message_caddie = array("ERROR_CODE_PROMO" => nl2br($GLOBALS['STR_CODE_PROMO_IS_NOT_FOR_THIS_CAT']));
			}
		} elseif (empty($code_infos)) {
			$cancel_code = true;
			if (!empty($this->code_promo)) {
				$this->message_caddie = array("ERROR_CODE_PROMO" => nl2br($GLOBALS['STR_ERR_CODE_PROMO']));
			}
		}
		if (!empty($cancel_code)) {
			$this->code_promo = "";
			$this->cat_code_promo = 0;
			$this->percent_code_promo = 0;
			$this->valeur_code_promo = 0;
		}
		// On veut appliquer les X euros de réduction par le code PROMO sur le panier en ventilant sur chaque produit indépendamment
		// Par ailleurs le pourcentage de réduction est applicable au total des produits hors ecotaxe
		$this->total_reduction_percent_code_promo = ($this->total_produit_related_to_code_promo - $this->total_ecotaxe_ttc_related_to_code_promo) * $this->percent_code_promo / 100;
		$this->total_reduction_code_promo = $this->valeur_code_promo + $this->total_reduction_percent_code_promo;
	}

	/**
	 * Renvoie le nombre d'articles dans le caddie (toujours positif)
	 *
	 * We apply abs() for each item count in order to protect the calculation from eventual negative quantity values
	 *
	 * @return
	 */
	function count_products()
	{
		$total = 0;
		if (!empty($this->quantite)) {
			foreach ($this->quantite as $qte) {
				$total += abs($qte);
			}
		}
		return $total;
	}

	/**
	 * Traitement du moyen de paiement
	 *
	 * @param integer $payment_technical_code
	 * @return
	 */
	function set_paiement($payment_technical_code)
	{
		// On définit ce nouveau moyen de paiement
		$this->payment_technical_code = $payment_technical_code;
		// On lance les calculs
		$frm = array('payment_technical_code' => $this->payment_technical_code, 'sub_total' => $this->total - $this->tarif_paiement, 'sub_total_ht' => $this->total_ht - $this->tarif_paiement_ht);
		set_paiement($frm);
		$this->tarif_paiement_ht = $frm['tarif_paiement_ht'];
		if ($this->apply_vat) {
			$this->tarif_paiement = $frm['tarif_paiement'];
		} else {
			$this->tarif_paiement = $this->tarif_paiement_ht;
		}
		$this->tva_tarif_paiement = $this->tarif_paiement - $this->tarif_paiement_ht ;
	}

	/**
	 * Définition de la zone d'expédition
	 *
	 * @param mixed $zoneId
	 * @return
	 */
	function set_zone($zoneId)
	{
		$zoneId = intval($zoneId);
		$sql = "SELECT nom_" . $_SESSION['session_langue'] . " AS nom, tva, on_franco, technical_code
			FROM peel_zones
			WHERE id = '" . intval($zoneId) . "'";
		$query = query($sql);
		if ($Zone = fetch_assoc($query)) {
			if ($zoneId != $this->zoneId) {
				// On initilialise le type de port à blank car on a changé de zone ou de pays
				$this->type = "";
				$this->typeId = "";
			}
			$this->zone = $Zone['nom'];
			$this->zone_technical_code = $Zone['technical_code'];
			$this->zoneTva = $Zone['tva'];
			$this->zoneFranco = $Zone['on_franco'];
			$this->zoneId = $zoneId;
		}
	}

	/**
	 * Définition du type de port
	 *
	 * @param integer $typeId
	 * @return
	 */
	function set_type($typeId)
	{
		$typeId = intval($typeId);
		$type_name = get_delivery_type_name($typeId);
		if ($type_name !== false) {
			// On définit le type de port seulement si trouvé en BDD
			$this->typeId = $typeId;
			$this->type = $type_name;
		} else {
			$this->typeId = '';
			$this->type = '';
		}
	}

	/**
	 * Cette méthode doit toujours être appelée après la modification (ou un ensemble de modifications) du caddie, avant son affichage
	 * Elle gère notamment la ventilation des codes promos en valeur, et l'application des codes promos en pourcentage
	 *
	 * @param mixed $percent_remise_user
	 * @return
	 */
	function update($percent_remise_user = null)
	{
		if ($percent_remise_user !== null) {
			$this->percent_remise_user = $percent_remise_user;
		}
		$this->apply_vat = ($this->zoneTva && !is_user_tva_intracom_for_no_vat() && !is_micro_entreprise_module_active());
		// INITIALISATION : On enlève tous les produits non valides
		// c'est à dire ceux dont l'identifiant n'est pas numérique ou dont la quantité est < 1
		foreach ($this->quantite as $numero_ligne => $qte) {
			if ($qte < 1) {
				// Attention ici normalement on nettoie si la quantitécommandée est < à 1
				unset($this->giftlist_owners[$numero_ligne]);
				unset($this->articles[$numero_ligne]);
				unset($this->quantite[$numero_ligne]);
				unset($this->poids[$numero_ligne]);
				unset($this->points[$numero_ligne]);
				unset($this->couleurId[$numero_ligne]);
				unset($this->tailleId[$numero_ligne]);
				unset($this->prix_avant_code_promo[$numero_ligne]);
				unset($this->prix_ht_avant_code_promo[$numero_ligne]);
				unset($this->prix[$numero_ligne]);
				unset($this->prix_ht[$numero_ligne]);
				unset($this->prix_cat[$numero_ligne]);
				unset($this->prix_cat_ht[$numero_ligne]);
				unset($this->total_prix[$numero_ligne]);
				unset($this->total_prix_ht[$numero_ligne]);
				unset($this->tva_percent[$numero_ligne]);
				unset($this->tva[$numero_ligne]);
				unset($this->percent_remise_produit[$numero_ligne]);
				unset($this->remise[$numero_ligne]);
				unset($this->remise_ht[$numero_ligne]);
				unset($this->etat_stock[$numero_ligne]);
				unset($this->delai_stock[$numero_ligne]);
				unset($this->option[$numero_ligne]);
				unset($this->option_ht[$numero_ligne]);
				unset($this->email_check[$numero_ligne]);
				unset($this->ecotaxe_ttc[$numero_ligne]);
				unset($this->ecotaxe_ht[$numero_ligne]);
				unset($this->id_attribut[$numero_ligne]);
				unset($this->attribut[$numero_ligne]);
				unset($this->total_prix_attribut[$numero_ligne]);
			}
		}
		// On initialise les montants qu'on va recalculer
		$this->total = $this->total_ht = 0;
		$this->total_quantite = 0;
		$this->total_produit = $this->total_produit_ht = 0;
		$this->total_produit_avant_code_promo = 0;
		$this->total_option = $this->total_option_ht = $this->tva_total_option = 0;
		$this->total_tva = $this->tva_total_produit = 0;
		$this->total_poids = $this->total_points = 0;
		$this->total_remise = $this->total_remise_ht = $this->tva_total_remise = 0;
		$this->total_ecotaxe_ttc = $this->total_ecotaxe_ht = $this->tva_total_ecotaxe = 0;

		$this->avoir_user = max(vn($_SESSION['session_utilisateur']['avoir']), 0);
		// ETAPE 1 : On calcule les totaux avant réduction
		foreach ($this->articles as $numero_ligne => $product_id) {
			$this->update_line($numero_ligne);
			$this->total_ecotaxe_ttc += $this->ecotaxe_ttc[$numero_ligne] * $this->quantite[$numero_ligne];
			$this->total_ecotaxe_ht += $this->ecotaxe_ht[$numero_ligne] * $this->quantite[$numero_ligne];
			if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
				$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
			} else {
				$real_stock_used = intval($this->quantite[$numero_ligne]);
			}
			$this->total_produit_avant_code_promo += $this->prix_avant_code_promo[$numero_ligne] * $real_stock_used;
		}
		// ETAPE 2 : maintenant qu'on connait le montant total avant réduction pour le catégorie et chaque catégorie de produits,
		// on vérifie si le code promotionnel est toujours actif aprés un changement de montant total du caddie.
		$this->update_code_promo();
		// Recalcul des produits et des totaux en appliquant correctement les ventilations des codes promos
		foreach ($this->articles as $numero_ligne => $product_id) {
			// Gestion du code promo
			$code_promo_applicable = false;
			if (!empty($this->total_produit_related_to_code_promo)) {
				if (!empty($this->cat_code_promo)) {
					// Si le code ne s'applique qu'à une catégorie et à ses filles
					$q_get_product_cat = query('SELECT categorie_id
						FROM peel_produits_categories ppc
						WHERE ppc.produit_id = "' . intval($product_id) . '" AND ppc.categorie_id IN ("' . implode('","', nohtml_real_escape_string(get_category_tree_and_itself($this->cat_code_promo, 'sons'))) . '")
						LIMIT 1');
					if ($r_get_product_cat = fetch_assoc($q_get_product_cat)) {
						$code_promo_applicable = true;
					}
				} else {
					$code_promo_applicable = true;
				}
			}
			if ($code_promo_applicable) {
				// Panier au montant non nul et code promo s'appliquant au total du panier ou à une catégorie et ses filles qui concernent au moins un produit du panier
				$code_promo_ventile_ttc = $this->total_reduction_code_promo * ($this->prix_avant_code_promo[$numero_ligne] * $this->quantite[$numero_ligne]) / $this->total_produit_related_to_code_promo;
			} else {
				// Panier au montant nul ou code promo à une catégorie non applicable au panier
				$code_promo_ventile_ttc = 0;
			}
			// Le produit et l'ecotaxe ont potentiellement deux taux de TVA différents => quand on passe de HT à TTC il faut traiter l'ecotaxe à part
			// Par ailleurs il faut arrondir les prix en TTC ou HT pour qu'ensuite l'application de quantités ne donne pas de problèmes d'arrondi
			if (display_prices_with_taxes_active()) {
				// On arrondit le prix TTC
				$this->prix[$numero_ligne] = round($this->prix_avant_code_promo[$numero_ligne] - $code_promo_ventile_ttc / $this->quantite[$numero_ligne], 2);
				// On recalcule le prix HT à partir du prix TTC arrondi
				$this->prix_ht[$numero_ligne] = ($this->prix[$numero_ligne] - $this->ecotaxe_ttc[$numero_ligne]) / (1 + $this->tva_percent[$numero_ligne] / 100) + $this->ecotaxe_ht[$numero_ligne];
			} else {
				// On arrondit le prix HT
				$this->prix_ht[$numero_ligne] = round($this->prix_ht_avant_code_promo[$numero_ligne] - $code_promo_ventile_ttc / $this->quantite[$numero_ligne] / (1 + $this->tva_percent[$numero_ligne] / 100), 2);
				// On recalcule le prix TTC à partir du prix HT arrondi
				$this->prix[$numero_ligne] = (($this->prix_ht[$numero_ligne] - $this->ecotaxe_ht[$numero_ligne]) * (1 + $this->tva_percent[$numero_ligne] / 100)) + $this->ecotaxe_ttc[$numero_ligne];
			}
			// Si le module conditionnement est présent, il faut prendre en compte le conditionnement dans le calcul du prix de la ligne.
			if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
				$this->total_prix[$numero_ligne] = $this->prix[$numero_ligne] * $this->quantite[$numero_ligne] * $this->conditionnement[$numero_ligne];
				$this->total_prix_ht[$numero_ligne] = $this->prix_ht[$numero_ligne] * $this->quantite[$numero_ligne] * $this->conditionnement[$numero_ligne];
			} else {
				$this->total_prix[$numero_ligne] = $this->prix[$numero_ligne] * $this->quantite[$numero_ligne];
				$this->total_prix_ht[$numero_ligne] = $this->prix_ht[$numero_ligne] * $this->quantite[$numero_ligne];
			}

			if ($this->apply_vat) {
				$this->tva[$numero_ligne] = $this->total_prix[$numero_ligne] - $this->total_prix_ht[$numero_ligne];
			} else {
				$this->tva[$numero_ligne] = 0;
			}
			// Aucune remise ne s'applique sur l'ecotaxe
			// => on calcule la remise sur $prix_ht_no_ecotaxe qui est le prix hors ecotaxe
			if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
				$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
			} else {
				$real_stock_used = intval($this->quantite[$numero_ligne]);
			}

			$this->remise_ht[$numero_ligne] = ($this->prix_cat_ht[$numero_ligne] + $this->option_ht[$numero_ligne] - $this->prix_ht[$numero_ligne]) * $real_stock_used;
			$this->remise[$numero_ligne] = ($this->prix_cat[$numero_ligne] + $this->option[$numero_ligne] - $this->prix[$numero_ligne]) * $real_stock_used;
			$this->total_remise += $this->remise[$numero_ligne];
			$this->total_remise_ht += $this->remise_ht[$numero_ligne];
			$this->total_quantite += $this->quantite[$numero_ligne];
			$this->total_produit += $this->total_prix[$numero_ligne];
			$this->total_produit_ht += $this->total_prix_ht[$numero_ligne];
			$this->total_option += $this->option[$numero_ligne] * $real_stock_used;
			$this->total_option_ht += $this->option_ht[$numero_ligne] * $real_stock_used;
			$this->tva_total_produit += $this->tva[$numero_ligne];
			$this->total_poids += $this->poids[$numero_ligne];
			if (is_gifts_module_active()) {
				$this->total_points += $this->points[$numero_ligne];
			}
		}
		// ETAPE 3 : On gère des éventuels frais supplémentaires si la commande est trop petite
		if ($this->total_produit < vn($GLOBALS['site_parameters']['small_order_overcost_limit']) && $this->total_produit >= vn($GLOBALS['site_parameters']['minimal_amount_to_order'])) {
			$this->small_order_overcost_amount_ht = $GLOBALS['site_parameters']['small_order_overcost_amount'] / (1 + ($GLOBALS['site_parameters']['small_order_overcost_tva_percent'] / 100));
			if ($this->apply_vat) {
				$this->small_order_overcost_amount = $this->small_order_overcost_amount_ht * (1 + ($GLOBALS['site_parameters']['small_order_overcost_tva_percent'] / 100));
			} else {
				$this->small_order_overcost_amount = $this->small_order_overcost_amount_ht;
			}
			$this->tva_small_order_overcost = $this->small_order_overcost_amount - $this->small_order_overcost_amount_ht;
		} else {
			$this->small_order_overcost_amount_ht = 0;
			$this->small_order_overcost_amount = 0;
			$this->tva_small_order_overcost = 0;
		}
		// ETAPE 4 : Calcul des frais de port
		if (!empty($this->typeId) && !empty($this->zoneId)) {
			$delivery_cost_infos = get_delivery_cost_infos($this->total_poids, $this->total_produit, $this->typeId, $this->zoneId, $this->count_products());
		}
		if (!isset($delivery_cost_infos) || $delivery_cost_infos === false) {
			// Pas de port à calculer pour l'instant, ou pas de frais de port trouvés pour ce poids et ce total (dans ce cas, ce n'est pas ici qu'on présente une erreur au client)
			$delivery_cost_infos = array('cost_ht' => 0, 'tva' => 0);
		}
		if (!empty($_SESSION['session_commande']['delivery_dyforwardingcharges'])) {
			// Coût Livraison So Colissimo, en € TTC
			$this->cout_transport_ht = $_SESSION['session_commande']['delivery_dyforwardingcharges'] / (1 + $delivery_cost_infos['tva'] / 100);
		} else {
			$this->cout_transport_ht = $delivery_cost_infos['cost_ht'];
		}

		if ($this->apply_vat) {
			$this->cout_transport = $this->cout_transport_ht * (1 + $delivery_cost_infos['tva'] / 100);
		} else {
			$this->cout_transport = $this->cout_transport_ht;
		}
		$this->tva_cout_transport = $this->cout_transport - $this->cout_transport_ht;

		$this->total_tva = $this->tva_total_produit + $this->tva_cout_transport + $this->tva_tarif_paiement + $this->tva_small_order_overcost;
		// ETAPE 5 : gestion de l'avoir
		// L'avoir est limité au total de la commande.
		// L'avoir est uniquement sur le TTC et n'affecte pas le calcul de la TVA
		$this->avoir = max(0, min($this->avoir_user, $this->total_produit + $this->cout_transport + $this->tarif_paiement + $this->small_order_overcost_amount));
		// ETAPE 6 : finalisation des totaux
		$this->tva_total_option = $this->total_option - $this->total_option_ht;
		$this->tva_total_remise = $this->total_remise - $this->total_remise_ht;
		$this->tva_total_ecotaxe = $this->total_ecotaxe_ttc - $this->total_ecotaxe_ht;
		$this->total = $this->total_produit - $this->avoir + $this->cout_transport + $this->tarif_paiement + $this->small_order_overcost_amount;
		$this->total_ht = $this->total_produit_ht + $this->cout_transport_ht + $this->tarif_paiement_ht + $this->small_order_overcost_amount_ht;
	}

	/**
	 * Enregistre la commande dans la base
	 *
	 * @param mixed $order_infos This array has all user infos related to the order
	 * @return
	 */
	function save_in_database(&$order_infos)
	{
		// On s'assure des montants avant leurs insertion en BDD
		$this->update();
		/* Le reversement affilié est calculé sur le total ht des produits */
		if (is_affiliate_module_active() && !empty($_SESSION['session_affilie']) && is_affiliation_active($_SESSION['session_affilie'])) {
			$order_infos['affilie'] = 1;
			$order_infos['statut_affilie'] = 0;
			$order_infos['montant_affilie'] = $this->total_produit * $GLOBALS['site_parameters']['commission_affilie'] / 100;
			$order_infos['id_affilie'] = intval($_SESSION['session_affilie']);
		} else {
			$order_infos['affilie'] = 0;
			$order_infos['montant_affilie'] = 0;
			$order_infos['statut_affilie'] = 0;
			$order_infos['id_affilie'] = 0;
		}
		foreach(array('total_option',
				'total_option_ht',
				'tva_total_option',
				'total_produit',
				'total_produit_ht',
				'tva_total_produit',
				'code_promo',
				'percent_remise_user',
				'percent_code_promo',
				'valeur_code_promo',
				'total_remise',
				'total_remise_ht',
				'tva_total_remise',
				'total_tva',
				'cout_transport',
				'cout_transport_ht',
				'tva_cout_transport',
				'total_points',
				'total_poids',
				'total_ecotaxe_ttc',
				'total_ecotaxe_ht',
				'tva_total_ecotaxe',
				'avoir',
				'payment_technical_code',
				'apply_vat',
				'tarif_paiement',
				'tarif_paiement_ht',
				'tva_tarif_paiement',
				'zoneFranco',
				'pays',
				'zone',
				'type',
				'typeId',
				'small_order_overcost_amount',
				'tva_small_order_overcost') as $this_item) {
			$order_infos[$this_item] = $this->$this_item;
		}
		$order_infos['montant'] = $this->total;
		$order_infos['montant_ht'] = $this->total_ht;
		$order_infos['id_utilisateur'] = $_SESSION['session_utilisateur']['id_utilisateur'];
		$order_infos['id_parrain'] = $_SESSION['session_utilisateur']['id_parrain'];
		$order_infos['parrain'] = $_SESSION['session_utilisateur']['type'];
		$order_infos['devise'] = $_SESSION['session_devise']['code'];
		$order_infos['currency_rate'] = $_SESSION['session_devise']['conversion'];
		$order_infos['delivery_tracking'] = null;

		foreach ($this->articles as $numero_ligne => $product_id) {
			// Attention ici normalement on nettoie si la quantité commandée est < à 1
			$articles[$numero_ligne]['product_id'] = $product_id;
			$articles[$numero_ligne]['quantite'] = $this->quantite[$numero_ligne];
			if (is_giftlist_module_active()) {
				$articles[$numero_ligne]['giftlist_owners'] = $this->giftlist_owners[$numero_ligne];
			}
			$articles[$numero_ligne]['poids'] = $this->poids[$numero_ligne];
			$articles[$numero_ligne]['points'] = $this->points[$numero_ligne];
			$articles[$numero_ligne]['couleurId'] = $this->couleurId[$numero_ligne];
			$articles[$numero_ligne]['tailleId'] = $this->tailleId[$numero_ligne];
			if (is_conditionnement_module_active ()) {
				$articles[$numero_ligne]['conditionnement'] = $this->conditionnement[$numero_ligne];
			}
			$articles[$numero_ligne]['prix'] = $this->prix[$numero_ligne];
			$articles[$numero_ligne]['prix_ht'] = $this->prix_ht[$numero_ligne];
			$articles[$numero_ligne]['prix_cat'] = $this->prix_cat[$numero_ligne];
			$articles[$numero_ligne]['prix_cat_ht'] = $this->prix_cat_ht[$numero_ligne];
			$articles[$numero_ligne]['total_prix'] = $this->total_prix[$numero_ligne];
			$articles[$numero_ligne]['total_prix_ht'] = $this->total_prix_ht[$numero_ligne];
			$articles[$numero_ligne]['tva_percent'] = $this->tva_percent[$numero_ligne];
			$articles[$numero_ligne]['tva'] = $this->tva[$numero_ligne];
			$articles[$numero_ligne]['percent_remise_produit'] = $this->percent_remise_produit[$numero_ligne];
			$articles[$numero_ligne]['remise'] = $this->remise[$numero_ligne];
			$articles[$numero_ligne]['remise_ht'] = $this->remise_ht[$numero_ligne];
			$articles[$numero_ligne]['etat_stock'] = $this->etat_stock[$numero_ligne];
			$articles[$numero_ligne]['delai_stock'] = $this->delai_stock[$numero_ligne];
			$articles[$numero_ligne]['option'] = $this->option[$numero_ligne];
			$articles[$numero_ligne]['option_ht'] = $this->option_ht[$numero_ligne];
			$articles[$numero_ligne]['email_check'] = $this->email_check[$numero_ligne];
			$articles[$numero_ligne]['ecotaxe_ttc'] = $this->ecotaxe_ttc[$numero_ligne];
			$articles[$numero_ligne]['ecotaxe_ht'] = $this->ecotaxe_ht[$numero_ligne];
			$articles[$numero_ligne]['attribut'] = $this->attribut[$numero_ligne];
			$articles[$numero_ligne]['id_attribut'] = $this->id_attribut[$numero_ligne];
			$articles[$numero_ligne]['total_prix_attribut'] = $this->total_prix_attribut[$numero_ligne];
		}
		if (sha1(serialize($order_infos) . serialize($articles)) != $this->commande_hash) {
			// La commande n'avait jamais été créée, ou les informations de la commande sont différentes
			$this->commande_hash = sha1(serialize($order_infos) . serialize($articles));

			$order_id = create_or_update_order($order_infos, $articles);

			if (empty($this->commande_id)) {
				if (is_stock_advanced_module_active() && !empty($this->articles)) {
					// C'est la première fois qu'on sauvegarde ce caddie :
					// on arrête de gérer les stocks temporaires pour cette commande => réincrémentation du stock temporaire
					foreach($this->articles as $numero_ligne => $product_id) {
						if ($this->etat_stock[$numero_ligne] == 1) {
							// On annule la réservation du stock temporaire (ce qui revient à incrémenter la table peel_stocks_temp)
							$couleur_id = intval($this->couleurId[$numero_ligne]);
							$taille_id = intval($this->tailleId[$numero_ligne]);
							if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
								$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
							} else {
								$real_stock_used = intval($this->quantite[$numero_ligne]);
							}
							liberation_stock_temp($product_id, $couleur_id, $taille_id, $real_stock_used);
						}
					}
				}
			} elseif ($this->commande_id != $order_id) {
				// On annule la commande précédemment liée à ce caddie car on vient de créer une nouvelle commande lui correspondant
				// SAUF si elle est déjà payée (=> 3ème argument à false) bien qu'on ne le sache pas ici !
				update_order_payment_status($this->commande_id, 6, false);
			}
			$this->commande_id = $order_id;
		}
		// Si le caddie correspond à une commande en cours, alors on met à jour la commande en question
		// Sinon, on crée la comande
		if (!empty($this->code_promo)) {
			// Si le code avait été créé spécifiquement pour être envoyé à un utilisateur, alors il est présent dans peel_utilisateurs_codes_promos auquel cas on met à jour l'info d'utilisation
			query("UPDATE peel_utilisateurs_codes_promos
				SET utilise = '1'
				WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND nom_code = '" . nohtml_real_escape_string($this->code_promo) . "'");
		}
		$_SESSION['session_utilisateur']['remise_percent'] = 0;
		$_SESSION['session_utilisateur']['id_parrain'] = 0;
		$_SESSION['session_utilisateur']['type'] = ''; #- Suppression du statut filleul

		// On met à jour l'avoir de la session client
		$_SESSION['session_utilisateur']['avoir'] = max($_SESSION['session_utilisateur']['avoir'] - $_SESSION['session_caddie']->avoir, 0);
		// Annule la remise en % du client, supprimer ces lignes si vous souhaitez que les remises client soient permanentes
		query("UPDATE peel_utilisateurs
			SET remise_percent = '0', avoir = GREATEST(0, avoir-'" . nohtml_real_escape_string($_SESSION['session_caddie']->avoir) . "')
			WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'");
		// Incrémentation du compteur de code promotionnel
		if (!empty($this->code_promo)) {
			query("UPDATE peel_codes_promos
				SET compteur_utilisation=compteur_utilisation+1
				WHERE nom = '" . nohtml_real_escape_string($this->code_promo) . "'");
		}
		// si il y a des commandes de cadeau on envoie un email
		if (is_giftlist_module_active() && !empty($this->giftlist_owners[0])) {
			email_ordered_cadeaux($this->commande_id);
		}
		return $this->commande_id;
	}
}

?>