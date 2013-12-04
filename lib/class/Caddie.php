<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: Caddie.php 39084 2013-11-29 16:31:12Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
/**
 * Caddie
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Caddie.php 39084 2013-11-29 16:31:12Z sdelaporte $
 * @access public
 */
class Caddie {
	/* Déclaration des tableaux */

	/* gestion de la liste cadeau */
	var $giftlist_owners = array();
	/* Tableau des articles */
	var $articles = array();
	/* Tableau des quantités */
	var $quantite = array();
	/* Tableau des poids (pour chaque ligne, le poids est déjà multiplié par la quantité) */
	var $poids = array();
	/* Tableau des points (pour chaque ligne, points est déjà multiplié par la quantité) */
	var $points = array();
	/* Tableau des couleurs */
	var $couleurId = array();
	/* Tableau des tailles */
	var $tailleId = array();
	/* Tableau des prix unitaire TTC*/
	var $prix = array();
	/* Tableau des prix unitaire HT*/
	var $prix_ht = array();
	/* Tableau des prix unitaire TTC*/
	var $prix_cat = array();
	/* Tableau des prix unitaire HT*/
	var $prix_cat_ht = array();
	/* Tableau prix TTC d'un produit avant l'application d'un code promotionnel*/
	var $prix_avant_code_promo = array();
	/* Tableau prix TTC d'un produit avant l'application d'un code promotionnel*/
	var $prix_ht_avant_code_promo = array();
	/* Net produit vendu TTC prix x qte*/
	var $total_prix = array();
	/* Net produit vendu HT prix_ht x qte */
	var $total_prix_ht = array();
	/* Tableau des taux de TVA */
	var $tva_percent = array();
	/* Tableau des TVA en valeur */
	var $tva = array();
	// percent_remise_produit est le tableau des promotions en pourcentage (colonne "promotion" dans peel_produits + toutes les autres réductions en pourcentage)  sur les produits
	var $percent_remise_produit = array();
	/* Tableau des type (EUR ou %) de remises par marque */
	var $remise = array();
	/* Tableau des remises HT par produit en EUR */
	var $remise_ht = array();
	/* Tableau stock produit géré ou non*/
	var $etat_stock = array();
	/* Tableau délai d'approvisionnement du stock */
	var $delai_stock = array();
	/* Tableau du prix des options */
	var $option = array();
	/* Tableau du prix des options */
	var $option_ht = array();
	/* Tableau des emails amis */
	var $email_check = array();
	/* Tableau de l'ecotaxe par ligne */
	var $ecotaxe_ttc = array();
	/* Tableau de l'ecotaxe HT par ligne */
	var $ecotaxe_ht = array();
	/* Tableau des attributs */
	var $attribut = array();
	var $id_attribut = array();
	var $total_prix_attribut = array();
	/* Références produits */
	var $reference = array();

	/* Déclaration des variables */
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
		foreach($this->articles as $numero_ligne => $product_id) {
			$this->delete_line($numero_ligne);
		}
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
	 * @param string $custom_product_reference
	 * @return Added quantity
	 */
	function add_product(&$product_object, $added_quantity_wished, $email_check, $listcadeaux_owner = null, $custom_product_reference = null)
	{
		if (in_array($product_object->id, $this->articles) && empty($email_check)) {
			// Si le produit est dans le caddie, et que ce n'est pas un chèque cadeau, alors on va vouloir fusionner les données dans une même ligne
			foreach ($this->articles as $k => $this_produit_id) {
				if ($product_object->id == $this_produit_id && $product_object->configuration_color_id == $this->couleurId[$k] && $product_object->configuration_size_id == $this->tailleId[$k] && $product_object->configuration_attributs_list == $this->id_attribut[$k]) {
					$numero_ligne = $k;
					break;
				}
			}
		}
		if (is_giftlist_module_active() && $listcadeaux_owner !== null && $this->giftlist_owners[$numero_ligne] != $listcadeaux_owner) {
			// on teste pour qui est destiné le cadeau, et on ne fusionne pas deux lignes pour deux destinataires différents
			unset($numero_ligne);
		}
		if (isset($numero_ligne)) {
			// Le produit est déjà dans le panier avec la bonne configuration de couleur et de taille
			$quantite_start = $this->quantite[$numero_ligne];
			if (is_giftlist_module_active() && $listcadeaux_owner !== null) {
				// destinataire déjà répertorié
				// on regarde ses besoins en quantité
				$quantity_wished = min($this->quantite[$numero_ligne] + $added_quantity_wished, getNessQuantityFromGiftList($this->articles[$numero_ligne], $this->giftlist_owners[$numero_ligne]));
			} else {
				$quantity_wished = $this->quantite[$numero_ligne] + $added_quantity_wished;
			}
		} else {
			// On ajoute le produit au panier : une nouvelle ligne doit être créée dans le panier
			$quantite_start = 0;
			if (!isset($this->articles[0])) {
				$numero_ligne = 0;
			} else {
				$numero_ligne = max(array_keys($this->articles)) + 1;
			}
			$this->conditionnement[$numero_ligne] = $product_object->conditionnement;
			$quantity_wished = $added_quantity_wished;
		}
		
		$this->reference[$numero_ligne] = $custom_product_reference;
		// On met à jour la ligne du caddie
		// Si module de gestion de stock présent : on fait la gestion de stock temporaire et la vérification aussi avec les stocks réels
		$this->change_line_data($numero_ligne, $product_object->id, $quantity_wished, $product_object->configuration_color_id, $product_object->configuration_size_id, $email_check, $product_object->configuration_attributs_list, $listcadeaux_owner);
		
		// On renvoie la quantité réellement ajoutée
		return $this->quantite[$numero_ligne] - $quantite_start;
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
	 * @param mixed $quantity_wished
	 * @param mixed $couleur_id
	 * @param mixed $taille_id
	 * @param mixed $email_check
	 * @param mixed $liste_attribut
	 * @param mixed $listcadeaux_owner
	 * @return
	 */
	function change_line_data($numero_ligne, $product_id, $quantity_wished, $couleur_id, $taille_id, $email_check, $liste_attribut, $listcadeaux_owner = null)
	{
		if (!is_numeric($numero_ligne)) {
			return false;
		}
		$this->articles[$numero_ligne] = $product_id;
		// Si on gère les stocks pour ce produit, la valeur $quantite est temporaire avant validation du stock disponible
		// $added_quantity peut être négative, nulle ou positive suivant évolution depuis dernière mise à jour du caddie
		$added_quantity_wished = $quantity_wished - vn($this->quantite[$numero_ligne]);
		$this->couleurId[$numero_ligne] = $couleur_id;
		$this->tailleId[$numero_ligne] = $taille_id;
		$this->email_check[$numero_ligne] = $email_check;
		$this->id_attribut[$numero_ligne] = $liste_attribut;
		$this->giftlist_owners[$numero_ligne] = $listcadeaux_owner;
		// On appelle update_line pour mettre à jour les autres colonnes, telles que $this->etat_stock[$numero_ligne] qui sert pour les stocks ci-dessous
		$this->update_line($numero_ligne, $this->get_available_point_for_current_line($numero_ligne));
		// Que la valeur de commande_id soit définie ou pas, on continue à faire vivre l'évaluation des variables de stock temporaire
		if(is_stock_advanced_module_active() && $this->etat_stock[$numero_ligne] == 1) {
			// Le module de gestion des stocks est activé et le produit a on_stock=1 ($this->etat_stock[$numero_ligne] est la valeur de on_stock du produit)
			// On réserve des stocks temporaires pour les nouvelles informations de la ligne
			// Si nécessaire on rectifie la quantité dans le panier en fonction de ce qui a pu être réservé après vérification des stocks temporaires et réels
			if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
				$this->quantite[$numero_ligne] += reservation_stock_temp_conditionnement($this, $numero_ligne, $product_id, $couleur_id, $taille_id, $added_quantity_wished);
			} else {
				$this->quantite[$numero_ligne] += reservation_stock_temp($product_id, $couleur_id, $taille_id, $added_quantity_wished);
			}
		} else {
			$this->quantite[$numero_ligne] += $added_quantity_wished;
		}
		if($this->quantite[$numero_ligne]<$quantity_wished && (defined('IN_STEP1') || defined('IN_STEP2') || defined('IN_STEP3'))) {
			// Redirection en cas de problème de stock au dernier moment lors de la commande => on redirige vers la page de caddie
			redirect_and_die($GLOBALS['wwwroot'] . "/achat/caddie_affichage.php");
		}
		// Si on veut avoir les totaux de cette ligne et du caddie, il faut appeler par la suite $this->update(); 
		// On ne le fait pas après chaque ligne pour éviter surcharge inutile
	}

	/**
	 * Caddie::get_available_point_for_current_line()
	 *
	 * @param integer $this_line
	 * @return
	 */
	function get_available_point_for_current_line($this_line)
	{
		$max_available_gift_points = intval(vn($_SESSION['session_utilisateur']['points']));
		foreach ($this->articles as $numero_ligne => $product_id) {
			if($numero_ligne !== $this_line) {
				$product_object = new Product($this->articles[$numero_ligne], null, false, null, true, $this->apply_vat);
				$product_object->set_configuration($this->couleurId[$numero_ligne], $this->tailleId[$numero_ligne], $this->id_attribut[$numero_ligne], is_reseller_module_active() && is_reseller());
				if(!empty($product_object->on_gift) && $product_object->on_gift_points > 0 && empty($this->prix_cat[$numero_ligne])) {
					// Produit cadeau qui est mis dans caddie gratuitement avec les points disponibles
					$max_available_gift_points -= $product_object->on_gift_points * $this->quantite[$numero_ligne];
				}
			}
		}
		return $max_available_gift_points;
	}

	/**
	 * On recalcule les informations d'une ligne
	 *
	 * @param integer $numero_ligne
	 * @param integer $max_available_gift_points
	 * @return
	 */
	function update_line($numero_ligne, $max_available_gift_points)
	{
		$product_object = new Product($this->articles[$numero_ligne], null, false, null, true, $this->apply_vat);
		$product_object->set_configuration($this->couleurId[$numero_ligne], $this->tailleId[$numero_ligne], $this->id_attribut[$numero_ligne], is_reseller_module_active() && is_reseller());

		if(!empty($product_object->on_gift) && $product_object->on_gift_points > 0) {
			// Produit cadeau qui est susceptible d'être mis dans caddie gratuitement avec les points disponibles
			$gift_max_quantity = floor($max_available_gift_points / $product_object->on_gift_points);
			if($gift_max_quantity>=1 || empty($product_object->prix)) {
				// on limite la quantité au max possible payable avec les points dans 2 cas :
				// - quantité prenable avec les points non nulle => ce mode de paiement a priorité
				// - OU prix nul => produit pas commandable par ailleurs
				$this->quantite[$numero_ligne] = min($this->quantite[$numero_ligne], $gift_max_quantity);
			} else {
				// On retire la propriété de cadeau au produit, car il a un prix et l'utilisateur n'a pas les points pour l'avoir gratuitement
				$product_object->on_gift = 0;
				$product_object->on_gift_points = 0;
			}
		}
		
		/* Traitement de l'ecotaxe */
		$this->delai_stock[$numero_ligne] = $product_object->delai_stock;
		// Attention : etat_stock de Caddie est on_stock du produit
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
		// On fait donc cela dans la fonction update
		// On n'applique pas non plus les codes promos de réductions en pourcentage ici pour tout faire au même endroit.
		if (!empty($this->quantite[$numero_ligne])) {
			// La variable prix_avant_code_promo sert pour connaître le montant acheté pour savoir si on peut appliquer ou non un code promo
			// Pour tenir compte des prix par lots, on récupère le prix pour l'ensemble des produits et on divise par la quantité
			if (is_conditionnement_module_active() && !empty($this->conditionnement[$numero_ligne])) {
				$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
			} else {
				$real_stock_used = intval($this->quantite[$numero_ligne]);
			}
			$this->prix_ht_avant_code_promo[$numero_ligne] = $product_object->get_final_price($this->percent_remise_user, false, is_reseller_module_active() && is_reseller(), false, false, $real_stock_used, true, true, false, $this->count_products($product_object->categorie_id)) / $real_stock_used;
			$this->prix_avant_code_promo[$numero_ligne] = $product_object->get_final_price($this->percent_remise_user, $apply_vat, is_reseller_module_active() && is_reseller(), false, false, $real_stock_used, true, true, false,$this->count_products($product_object->categorie_id)) / $real_stock_used;
		}
		$this->percent_remise_produit[$numero_ligne] = $product_object->get_all_promotions_percentage(is_reseller_module_active() && is_reseller(), $this->percent_remise_user, false);
		if(!empty($product_object->on_gift) && $product_object->on_gift_points * $this->quantite[$numero_ligne] <= $max_available_gift_points) {
			foreach(array('ecotaxe', 'ecotaxe_ht', 'prix_cat', 'prix_cat_ht', 'option', 'option_ht', 'prix_ht_avant_code_promo', 'prix_avant_code_promo', 'percent_remise_produit') as $this_property) {
				$this_temp = &$this->$this_property;
				$this_temp[$numero_ligne] = 0;
			}
			$max_available_gift_points -= $product_object->on_gift_points * $this->quantite[$numero_ligne];
		}
		unset($product_object);
		return $max_available_gift_points;
	}

	/**
	 * Enlève un produit du caddie
	 *
	 * @param mixed $numero_ligne
	 * @return
	 */
	function delete_line($numero_ligne)
	{
		$attributs_list = $this->id_attribut[$numero_ligne];
		// Avant d'effacer la ligne, on met proprement la quantité à 0 pour gérer les stocks, etc.
		$this->change_line_data($numero_ligne, vn($this->articles[$numero_ligne]), 0, vn($this->couleurId[$numero_ligne]), vn($this->tailleId[$numero_ligne]), vb($this->email_check[$numero_ligne]), vb($this->id_attribut[$numero_ligne]), vb($this->giftlist_owners[$numero_ligne]));
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
			$this->reference[$numero_ligne],
			$this->id_attribut[$numero_ligne],
			$this->attribut[$numero_ligne],
			$this->total_prix_attribut[$numero_ligne]);
		// suppression des attributs d'image existants
		if (!empty($attributs_list)) {
			foreach(explode("§", $attributs_list) as $attribut_infos_list) {
				$attribut_infos = explode("|", $attribut_infos_list);
				if (!empty($attribut_infos[2])) { 
					// si c'est un attribut de type upload ou texte libre
					delete_uploaded_file_and_thumbs($attribut_infos[2]);
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
	function count_products($cat_id = null)
	{
		$total = 0;
		if (!empty($this->quantite)) {
			foreach ($this->quantite as $numero_ligne => $qte) {
				if (!empty($cat_id)) {
					if(!empty($this->articles[$numero_ligne])) {
						$product_object = new Product($this->articles[$numero_ligne]);
						if (!empty($product_object) && $product_object->categorie_id == $cat_id) {
							// Quantité pour la catégorie.
							$total += abs($qte);
						}
					}
				} else {
					$total += abs($qte);
				}
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
				unset($this->reference[$numero_ligne]);
				unset($this->id_attribut[$numero_ligne]);
				unset($this->attribut[$numero_ligne]);
				unset($this->total_prix_attribut[$numero_ligne]);
			}
		}

		$this->avoir_user = max(vn($_SESSION['session_utilisateur']['avoir']), 0);
		// ETAPE 1 : On calcule les totaux avant réduction
		$max_available_gift_points = intval(vn($_SESSION['session_utilisateur']['points']));
		foreach ($this->articles as $numero_ligne => $product_id) {
			$max_available_gift_points = $this->update_line($numero_ligne, $max_available_gift_points);
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

		// Recalcul des produits en appliquant correctement les ventilations des codes promos
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
		}
		
		// Calcul du total des lignes
		$this->_recalc_line_totals();
		
		// Calcul du pourcentage applicable en fonction du total des produits dans le panier
		if (is_lot_module_active() && !empty($GLOBALS['site_parameters']['global_promotion_percent_by_threshold'])) {
			// Tri des valeurs par ordre croissant, pour permettre la sélection du bon taux en fonction du montant total des produits
			asort($GLOBALS['site_parameters']['global_promotion_percent_by_threshold']);
			foreach ($GLOBALS['site_parameters']['global_promotion_percent_by_threshold'] as $promotion_percent => $threshold) {
				if ($threshold > $this->total_produit) {
					// Le seuil courant est strictement supérieur au total des produits => il faut sortir de la boucle. Le code promotionnel sauvegardé est celui de l'itération précedente
					break;
				}
				$this_global_promotion_percent_by_threshold = $promotion_percent;
			}
			// Application de réductions ventilées par ligne, quand on souhaite ne pas l'appliquer directement dans le calcul du prix d'un produit, mais dans la logique de caddie
			if(!empty($this_global_promotion_percent_by_threshold)) {
				foreach ($this->articles as $numero_ligne => $product_id) {
					// Gestion de la réduction
					$reduction_ventilee_ttc = ($this->prix[$numero_ligne] * $this->quantite[$numero_ligne])*($this_global_promotion_percent_by_threshold / 100) ;
					if (display_prices_with_taxes_active()) {
						// On arrondit le prix TTC
						$this->prix[$numero_ligne] = round($this->prix[$numero_ligne] - $reduction_ventilee_ttc / $this->quantite[$numero_ligne], 2);
						// On recalcule le prix HT à partir du prix TTC arrondi
						$this->prix_ht[$numero_ligne] = ($this->prix[$numero_ligne] - $this->ecotaxe_ttc[$numero_ligne]) / (1 + $this->tva_percent[$numero_ligne] / 100) + $this->ecotaxe_ht[$numero_ligne];
					} else {
						// On arrondit le prix HT
						$this->prix_ht[$numero_ligne] = round($this->prix_ht[$numero_ligne] - $reduction_ventilee_ttc / $this->quantite[$numero_ligne] / (1 + $this->tva_percent[$numero_ligne] / 100), 2);
						// On recalcule le prix TTC à partir du prix HT arrondi
						$this->prix[$numero_ligne] = (($this->prix_ht[$numero_ligne] - $this->ecotaxe_ht[$numero_ligne]) * (1 + $this->tva_percent[$numero_ligne] / 100)) + $this->ecotaxe_ttc[$numero_ligne];
					}
				}
				// Calcul à nouveau du total des lignes, qui contiennent maintenant la réduction ventilée
				$this->_recalc_line_totals();
			}
		}
		
		// ETAPE 3 : On gère des éventuels frais supplémentaires si la commande est trop petite
		if (count($this->articles) && $this->total_produit < vn($GLOBALS['site_parameters']['small_order_overcost_limit']) && $this->total_produit >= vn($GLOBALS['site_parameters']['minimal_amount_to_order'])) {
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
	 * Recalcul des totaux en appliquant correctement les ventilations des codes promos
	 *
	 * @return
	 */
	function _recalc_line_totals() {
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
		// Recalcul des totaux en appliquant correctement les ventilations des codes promos
		foreach ($this->articles as $numero_ligne => $product_id) {
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
				'zoneId',
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
			$articles[$numero_ligne]['reference'] = $this->reference[$numero_ligne];
			$articles[$numero_ligne]['attribut'] = $this->attribut[$numero_ligne];
			$articles[$numero_ligne]['id_attribut'] = $this->id_attribut[$numero_ligne];
			$articles[$numero_ligne]['total_prix_attribut'] = $this->total_prix_attribut[$numero_ligne];
		}
		if (sha1(serialize($order_infos) . serialize($articles)) != $this->commande_hash) {
			// La commande n'avait jamais été créée, ou les informations de la commande sont différentes
			$this->commande_hash = sha1(serialize($order_infos) . serialize($articles));

			$order_id = create_or_update_order($order_infos, $articles);

			$used_gift_points = intval(vn($_SESSION['session_utilisateur']['points'])) - $this->get_available_point_for_current_line(null);
			if (!empty($this->commande_id) && $this->commande_id != $order_id) {
				// On annule la commande précédemment liée à ce caddie car on vient de créer une nouvelle commande lui correspondant
				// SAUF si elle est déjà payée (=> 3ème argument à false)
				echo update_order_payment_status($this->commande_id, 6, false);
			}
			// On retire les points utilisés lors de la commande.
			// NB : Cette gestion n'utilise pas "total_points" de peel_commandes, qui est géré ailleurs et concerne les points gagnés lors de la commande
			query("UPDATE peel_utilisateurs
				SET points=points-" . $used_gift_points . "
				WHERE id_utilisateur='" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "'");
			$user_infos = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
			$_SESSION['session_utilisateur']['points'] = $user_infos['points'];
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
		if (!empty($this->giftlist_owners[0]) && is_giftlist_module_active()) {
			email_ordered_cadeaux($this->commande_id);
		}
		return $this->commande_id;
	}
}

?>