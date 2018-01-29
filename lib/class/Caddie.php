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
// $Id: Caddie.php 55512 2017-12-13 17:28:58Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
/**
 * Caddie
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Caddie.php 55512 2017-12-13 17:28:58Z sdelaporte $
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
	var $option_without_reduction = array();
	/* Tableau du prix des options */
	var $option_without_reduction_ht = array();
	/* Tableau du prix des options */
	var $option = array();
	/* Tableau du prix des options */
	var $option_ht = array();
	/* Tableau des emails amis */
	var $data_check = array();
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
	/* Références annonce qui seront stocké dans le champ référence du produit */
	var $ad_reference = array();

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
	var $code_infos;

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
	var $global_promotion = null;
	var $products_count = array();
	
	/*Order ID for Socolissimo */
	var $delivery_orderid;

	/* ID de la commande liée au caddie */
	var $commande_id;
	var $commande_hash;
	
	// Module conditionnement
	var $conditionnement;
	var $pallet_count;
	var $global_percent_pallet_filled;
	/* Commentaire pour un produit */
	var $commentaires_admin;
	var $payment_multiple;


	/**
	 * Caddie::Caddie()
	 *
	 * @param mixed $percent_remise_user
	 */
	function __construct($percent_remise_user)
	{
		$this->percent_remise_user = $percent_remise_user;
		/* constructeur d'object */
		$this->init(true, false);
	}

	/**
	 * Initialise le caddie
	 *
	 * @param mixed $load_from_caddie_cookie_if_available Charge les informations de caddie à partir du cookie si disponible
	 * @param mixed $erase_caddie_cookie Efface les informations de caddie à partir du cookie si disponible
	 * @return
	 */
	function init($load_from_caddie_cookie_if_available = false, $erase_caddie_cookie = true)
	{
		unset($_SESSION['session_commande']);
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
		// NE PAS FAIRE $this->percent_remise_user = 0; : c'est le seul attribut à ne pas initialiser car défini dans le constructeur
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
		
		$this->commentaires_admin = '';

		$this->pallet_count = 0;
		$this->global_percent_pallet_filled = 0;

		$this->delivery_orderid = StringMb::substr(sha1(mt_rand(1, 10000000)), 0, 16);
		if($load_from_caddie_cookie_if_available) {
			// Protection pour éviter injection via cookie de demande de création de n'importe quel objet
			if (!empty($GLOBALS['site_parameters']['save_caddie_in_cookie']) && !empty($_COOKIE[$GLOBALS['caddie_cookie_name']]) && !preg_match('/(^|;|{|})O:\+?[0-9]+:"/', $_COOKIE[$GLOBALS['caddie_cookie_name']])) {
				// Le panier vient d'être initialisé. Si un cookie qui contient des produits n'est pas vide, il faut remplir le panier avec les informations du cookie si le paramétrage de la boutique le permet.
			
				// Un cookie ne peut faire que 4Ko. Donc le nombre de produit à retenir dans le cookie est d'environ 25 produits.
				// On pourrait décompresser le contenu dans le cookie en utilisant unserialize(gzuncompress(base64_decode($GLOBALS['product_in_caddie_cookie']))) mais il reste un problème de gestion des caractères =, il faudrait faire de la bidouille pour contourner le problème, donc on ne fait rien.
				$product_in_caddie_cookie = @unserialize($_COOKIE[$GLOBALS['caddie_cookie_name']]);
				foreach ($product_in_caddie_cookie as $this_product_info) {
					// Il ne faut pas mettre les données stockées dans le cookie directement dans le panier. Les données dans le cookies peuvent être erronées, ou frauduleuses.
					$product_object = new Product($this_product_info['product_id'], null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'), false, true);
					$product_object->set_configuration($this_product_info['couleurId'], $this_product_info['tailleId'], $this_product_info['id_attribut'], check_if_module_active('reseller') && is_reseller());
					$this->add_product($product_object, $this_product_info['quantite'], $data_check, $listcadeaux_owner);
					unset($product_object);
				}
			}
		} elseif($erase_caddie_cookie) {
			if (!empty($_COOKIE[$GLOBALS['caddie_cookie_name']])) {
				// Il faut supprimer le cookie qui contient les produits du panier, sinon le caddie est automatiquement rechargé dans init().
				unset($_COOKIE[$GLOBALS['caddie_cookie_name']]);
			}
		}
		// Au cas où certaines variables ne seraient pas bien nettoyées, on recalcule l'ensemble pour assurer une parfaite cohérence
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
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => StringMb::html_entity_decode_if_needed($this->message_caddie['ERROR_CODE_PROMO'])))->fetch();
				unset($this->message_caddie['ERROR_CODE_PROMO']);
			}
			if (!empty($this->message_caddie['SUCCES_CODE_PROMO'])) {
				echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => StringMb::html_entity_decode_if_needed($this->message_caddie['SUCCES_CODE_PROMO'])))->fetch();
				unset($this->message_caddie['SUCCES_CODE_PROMO']);
			}
		}
	}

	/**
	 * On ajoute un nouveau produit dans le panier
	 *
	 * @param class $product_object
	 * @param integer $quantite
	 * @param string $data_check
	 * @param string $listcadeaux_owner
	 * @param string $complementary_data_array
	 * @return Added quantity
	 */
	function add_product(&$product_object, $added_quantity_wished, $data_check = null, $listcadeaux_owner = null, $complementary_data_array = array())
	{
		if(empty($GLOBALS['site_parameters']['allow_float_quantity'])) {
			$added_quantity_wished = intval($added_quantity_wished);
		}
		if (in_array($product_object->id, $this->articles) && empty($data_check)) {
			// Si le produit est dans le caddie, et que ce n'est pas un chèque cadeau, alors on va vouloir fusionner les données dans une même ligne
			foreach ($this->articles as $k => $this_produit_id) {
				if ($product_object->id == $this_produit_id && $product_object->configuration_color_id == $this->couleurId[$k] && $product_object->configuration_size_id == $this->tailleId[$k] && $product_object->configuration_attributs_list == $this->id_attribut[$k]) {
					$numero_ligne = $k;
					break;
				}
			}
		}
		if (check_if_module_active('listecadeau') && $listcadeaux_owner !== null && $this->giftlist_owners[$numero_ligne] != $listcadeaux_owner) {
			// on teste pour qui est destiné le cadeau, et on ne fusionne pas deux lignes pour deux destinataires différents
			unset($numero_ligne);
		}
		if (isset($numero_ligne)) {
			// Le produit est déjà dans le panier avec la bonne configuration de couleur et de taille
			$quantite_start = $this->quantite[$numero_ligne];
			if (check_if_module_active('listecadeau') && $listcadeaux_owner !== null) {
				// destinataire déjà répertorié
				// on regarde ses besoins en quantité
				$quantity_wished = min($this->quantite[$numero_ligne] + $added_quantity_wished, getNessQuantityFromGiftList($this->articles[$numero_ligne], $product_object->configuration_color_id, $product_object->configuration_size_id, $this->giftlist_owners[$numero_ligne]));
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
		
		foreach($complementary_data_array as $this_type => $values_array) {
			foreach($values_array as $this_field=>$this_value) {
				if ($this_type == 'product') {
					// cette valeur concerne le produit uniquement
					if (!isset($this->$this_field)) {
						$this->$this_field = array();
					}
					$this->$this_field[$numero_ligne] = $this_value;
				} else {
					// Concerne la commande
					$this->$this_field = $this_value;
				}
			}
		}
		// On met à jour la ligne du caddie
		// Si module de gestion de stock présent : on fait la gestion de stock temporaire et la vérification aussi avec les stocks réels
		$this->change_line_data($numero_ligne, $product_object->id, $quantity_wished, $product_object->configuration_color_id, $product_object->configuration_size_id, $data_check, $product_object->configuration_attributs_list, $listcadeaux_owner);
		
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
					if (!empty($line_infos['email_check'][$numero_ligne])) {
						$line_infos['data_check'][$numero_ligne] = array('email_check'=>$line_infos['email_check'][$numero_ligne], 'nom_check'=>$line_infos['nom_check'][$numero_ligne], 'prenom_check'=>$line_infos['prenom_check'][$numero_ligne]);
				}
					$this->change_line_data($numero_ligne, $product_id, get_float_from_user_input(vn($line_infos['quantite'][$numero_ligne])), vn($line_infos['couleurId'][$numero_ligne]), vn($line_infos['tailleId'][$numero_ligne]), vb($line_infos['data_check'][$numero_ligne]), vn($line_infos['id_attribut'][$numero_ligne]), vb($line_infos['listcadeaux_owner'][$numero_ligne]));
			}
		}
		}
		// On recalcule tout, notamment pour les frais de port
		// Par ailleurs à la fin de l'update, on va éventuellement rediriger vers la page de caddie si une quantité demandée n'a pas été donnée car pas en stock
		$this->update();
	}

	/**
	 * On met à jour une ligne de produit, en connaissant déjà son numéro
	 *
	 * @param mixed $numero_ligne
	 * @param mixed $product_id
	 * @param mixed $quantity_wished contient la quantité global souhaitée pour la ligne. Si le module de stock est actif, c'est reservation_stock_temp qui fixera la quantité de la ligne.
	 * @param mixed $couleur_id
	 * @param mixed $taille_id
	 * @param mixed $data_check
	 * @param mixed $liste_attribut
	 * @param mixed $listcadeaux_owner
	 * @param mixed $do_update_line
	 * @return
	 */
	function change_line_data($numero_ligne, $product_id, $quantity_wished, $couleur_id, $taille_id, $data_check, $liste_attribut, $listcadeaux_owner = null, $do_update_line = true)
	{
		if (!is_numeric($numero_ligne)) {
			return false;
		}
		if(empty($GLOBALS['site_parameters']['allow_float_quantity'])) {
			$quantity_wished = intval($quantity_wished);
		}
		$this->articles[$numero_ligne] = $product_id;
		// Si on gère les stocks pour ce produit, la valeur $quantite est temporaire avant validation du stock disponible
		// $added_quantity_wished peut être négative, nulle ou positive suivant évolution depuis dernière mise à jour du caddie
		$added_quantity_wished = $quantity_wished - vn($this->quantite[$numero_ligne]);
		$this->couleurId[$numero_ligne] = $couleur_id;
		$this->tailleId[$numero_ligne] = $taille_id;
		$this->data_check[$numero_ligne] = $data_check;
		$this->id_attribut[$numero_ligne] = $liste_attribut;
		$this->giftlist_owners[$numero_ligne] = $listcadeaux_owner;
		if (!empty($do_update_line)) {
			// On appelle update_line pour mettre à jour les autres colonnes, telles que $this->etat_stock[$numero_ligne] qui sert pour les stocks ci-dessous
			$this->update_line($numero_ligne, $this->get_available_point_for_current_line($numero_ligne));
		}
		// Que la valeur de commande_id soit définie ou pas, on continue à faire vivre l'évaluation des variables de stock temporaire
		if(check_if_module_active('stock_advanced') && vb($this->etat_stock[$numero_ligne]) == 1) {
			// Le module de gestion des stocks est activé et le produit a on_stock=1 ($this->etat_stock[$numero_ligne] est la valeur de on_stock du produit)
			// On réserve des stocks temporaires pour les nouvelles informations de la ligne
			// Si nécessaire on rectifie la quantité dans le panier en fonction de ce qui a pu être réservé après vérification des stocks temporaires et réels
			
			if (check_if_module_active('conditionnement') && !empty($this->conditionnement[$numero_ligne])) {
				$this->quantite[$numero_ligne] += Conditionnement::reservation_stock_temp($this, $numero_ligne, $product_id, $couleur_id, $taille_id, $added_quantity_wished);
			} else {
				$this->quantite[$numero_ligne] += reservation_stock_temp($product_id, $couleur_id, $taille_id, $added_quantity_wished);
			}
			// texte informatif pour chaque produit commandé hors stock
			if (!get_stock($this->articles[$numero_ligne], $this->couleurId[$numero_ligne], $this->tailleId[$numero_ligne])) {
				$this->commentaires_admin[$numero_ligne] = $GLOBALS["STR_MESSAGE_BOOTBOX_INVOICE_CRITERE_STOCK"];
			}
		} else {
			$this->quantite[$numero_ligne] += $added_quantity_wished;
		}
		if (!empty($this->data_check[$numero_ligne])) {
			$product_infos['on_check'] = 1;
		} else {
			$product_infos['on_check'] = 0;
		}
		$product_object = new Product($product_id, vb($product_infos, array()));
		if (!empty($this->quantite[$numero_ligne]) && !empty($product_object->quantity_min_order) && $product_object->quantity_min_order > 1 && $this->quantite[$numero_ligne] < $product_object->quantity_min_order) {
			$this->quantite[$numero_ligne] = $product_object->quantity_min_order;
			$_SESSION['session_display_popup']['error_text'] .= $GLOBALS['STR_ORDER_MIN'].' '.$product_object->quantity_min_order;
			redirect_and_die($GLOBALS['wwwroot'] . "/achat/caddie_affichage.php");
		}
		if($this->quantite[$numero_ligne]<$quantity_wished) {
			// Si on ne peut pas allouer la quantité souhaitée, alors nous redirigerons l'utilisateur vers le caddie pour que l'utilisateur se rende compte de l'état du caddie
			// Mais on ne pourra le faire qu'uniquement après avoir tout traité, et seulement si ça semble adapté
			$GLOBALS['quantity_wished_not_fulfilled'] = true;
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
	function get_available_point_for_current_line($this_line, $return_used_points = false)
	{
		$max_available_gift_points = intval(vn($_SESSION['session_utilisateur']['points']));
		$used_points = 0;
		foreach ($this->articles as $numero_ligne => $product_id) {
			if($max_available_gift_points>0 && $numero_ligne !== $this_line) {
				$product_object = new Product($this->articles[$numero_ligne], null, false, null, true, $this->apply_vat, false, true);
				$product_object->set_configuration($this->couleurId[$numero_ligne], $this->tailleId[$numero_ligne], $this->id_attribut[$numero_ligne], check_if_module_active('reseller') && is_reseller());
				if(!empty($product_object->on_gift) && $product_object->on_gift_points > 0 && empty($this->prix_cat[$numero_ligne])) {
					// Produit cadeau qui est mis dans caddie gratuitement avec les points disponibles
					$used_points += $product_object->on_gift_points * $this->quantite[$numero_ligne];
					$max_available_gift_points -= $used_points;
				}
				unset($product_object);
			}
		}
		if($return_used_points) {
			return $used_points;
		} else {
			return $max_available_gift_points;
		}
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
		if (!empty($this->data_check[$numero_ligne])) {
			$product_infos['on_check'] = 1;
		} else {
			$product_infos['on_check'] = 0;
		}
		$product_object = new Product($this->articles[$numero_ligne], $product_infos, false, null, true, $this->apply_vat, false, empty($GLOBALS['site_parameters']['cart_optimize_loading_disabled']));
		$product_object->set_configuration($this->couleurId[$numero_ligne], $this->tailleId[$numero_ligne], $this->id_attribut[$numero_ligne], check_if_module_active('reseller') && is_reseller());
		if(check_if_module_active('stock_advanced') && $product_object->etat_stock == 1) {
			// Le module de gestion des stocks est activé et le produit a on_stock=1 ($product_object->etat_stock est la valeur de on_stock du produit)
			// On réserve des stocks temporaires pour les nouvelles informations de la ligne
			// Si nécessaire on rectifie la quantité dans le panier en fonction de ce qui a pu être réservé après vérification des stocks temporaires et réels.
			if (empty($product_object->allow_add_product_with_no_stock_in_cart) && empty($GLOBALS['site_parameters']['allow_add_product_with_no_stock_in_cart'])) {
				// Dans le cas d'un timemax faible, si un produit avec un exemplaire en stock est commandé par un premier utilisateur, le produit sera à nouveau disponible si l'uilisateur fait une pause longue sur les pages de process de commande après le panier. Le produit pourra alors être à nouveau ajouté au panier par un autre utilisateur. On se retrouvera avec 2 panier avec ce produit qui est pourtant disponible en un seul exemplaire.
				// => pour cette raison il faut vérifier à chaque mise à jour du panier si le produit est disponible dans le stock temporaire, et mettre la quantité à jour en fonction. Un utilisateur risque de voir son produit disparaitre de son panier sans comprendre pourquoi. Il faudra dans ce cas augmenter le timemax.
				$quantity_wished = min($this->quantite[$numero_ligne], get_stock($product_object->id, $product_object->configuration_color_id, $product_object->configuration_size_id, 0));
				if ($quantity_wished != $this->quantite[$numero_ligne]) {
					// Si la quantité est différente, il faut mettre à jour le stock temporaire puis la quantité dans le panier via la fonction change_line_data 
					// $this->email_check[$numero_ligne] : le but est de contrôler la quantité, donc l'email check est repris des infos déjà dans le panier.
					$this->change_line_data($numero_ligne, $product_object->id, $quantity_wished, $product_object->configuration_color_id, $product_object->configuration_size_id, $this->data_check[$numero_ligne], $product_object->configuration_attributs_list, null, false);
					// On ne peut pas garder la quantité souhaitée car pas en stock réellement, alors nous redirigerons l'utilisateur vers le caddie pour que l'utilisateur se rende compte de l'état du caddie
					// Mais on ne pourra le faire qu'uniquement après avoir tout traité, et seulement si ça semble adapté
					$GLOBALS['quantity_wished_not_fulfilled'] = true;
				}
			}
		}
		if(!empty($product_object->on_gift) && $product_object->on_gift_points > 0) {
			// Produit cadeau qui est susceptible d'être mis dans caddie gratuitement avec les points disponibles
			$gift_max_quantity = floor($max_available_gift_points / $product_object->on_gift_points);
			if($gift_max_quantity>=1 || $product_object->get_final_price(get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller()) == 0) {
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
		$this->reference[$numero_ligne] = $product_object->reference;
		if (check_if_module_active('gifts')) {
			// Total points de la ligne
			$this->points[$numero_ligne] = $product_object->points * $this->quantite[$numero_ligne];
		} else {
			$this->points[$numero_ligne] = 0;
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
		if (check_if_module_active('attributs')) {
			// Ces valeurs servent pour remplir la table peel_commandes, mais pas pour les calculs qui se servent de la classe Product qui gère tout cela
			$this->total_prix_attribut[$numero_ligne] = $product_object->format_prices($product_object->configuration_total_original_price_attributs_ht + $product_object->configuration_total_original_price_attributs_ht_without_reduction, $apply_vat, false, false, false);
			$this->attribut[$numero_ligne] = $product_object->configuration_attributs_description;
		} else {
			$this->total_prix_attribut[$numero_ligne] = null;
			$this->attribut[$numero_ligne] = null;
		}
		// Les valeurs des options ne contiennent pas les éventuelles réductions en pourcentage
		$this->option_ht[$numero_ligne] = $product_object->format_prices($product_object->configuration_size_price_ht + $product_object->configuration_color_price_ht + $product_object->configuration_total_original_price_attributs_ht, false, false, false, false);
		$this->option[$numero_ligne] = $product_object->format_prices($product_object->configuration_size_price_ht + $product_object->configuration_color_price_ht + $product_object->configuration_total_original_price_attributs_ht, $apply_vat, false, false, false);
		
		// montant des options pour lesquels aucune réduction ne s'applique (valeur calculée dans la fonction affiche_attributs_form_part du module attribut.)
		$this->option_without_reduction_ht[$numero_ligne] = $product_object->format_prices($product_object->configuration_total_original_price_attributs_ht_without_reduction, false, false, false, false);
		$this->option_without_reduction[$numero_ligne] = $product_object->format_prices($product_object->configuration_total_original_price_attributs_ht_without_reduction, $apply_vat, false, false, false);

		// Total poids de la ligne
		$this->poids[$numero_ligne] = ($product_object->poids + $product_object->configuration_overweight) * $this->quantite[$numero_ligne];
		// Calcul du prix original avant réductions et options
		$this->prix_cat_ht[$numero_ligne] = $product_object->get_original_price(false, check_if_module_active('reseller') && is_reseller(), false, false, true, false);
		$this->prix_cat[$numero_ligne] = $product_object->get_original_price($apply_vat, check_if_module_active('reseller') && is_reseller(), false, false, true, false);
		if (display_prices_with_taxes_active()) {
			// On doit arrondir les valeurs tarifaires officielles qui sont en TTC
			$this->prix_cat[$numero_ligne] = round($this->prix_cat[$numero_ligne], 2);
			$this->option[$numero_ligne] = round($this->option[$numero_ligne], 2);
			$this->option_without_reduction[$numero_ligne] = round($this->option_without_reduction[$numero_ligne], 2);
			$this->ecotaxe_ttc[$numero_ligne] = round($this->ecotaxe_ttc[$numero_ligne], 2);
		} else {
			// On doit arrondir les valeurs tarifaires officielles qui sont en HT
			$this->prix_cat_ht[$numero_ligne] = round($this->prix_cat_ht[$numero_ligne], 2);
			$this->option_ht[$numero_ligne] = round($this->option_ht[$numero_ligne], 2);
			$this->option_without_reduction_ht[$numero_ligne] = round($this->option_without_reduction_ht[$numero_ligne], 2);
			$this->ecotaxe_ht[$numero_ligne] = round($this->ecotaxe_ht[$numero_ligne], 2);
		}
		// NB : on n'applique pas ici la ventilation d'un code promo en valeur sur le nouveau produit, car le calcul concerne forcément tout le panier en même temps
		// On fait donc cela dans la fonction update
		// On n'applique pas non plus les codes promos de réductions en pourcentage ici pour tout faire au même endroit.
		if (!empty($this->quantite[$numero_ligne])) {
			// La variable prix_avant_code_promo sert pour connaître le montant acheté pour savoir si on peut appliquer ou non un code promo
			// Pour tenir compte des prix par lots, on récupère le prix pour l'ensemble des produits et on divise par la quantité
			if (!empty($GLOBALS['site_parameters']['product_quantity_for_lot_by_product_id'])) {
				$real_stock_tested = 0;
				foreach($this->articles as $this_numero_ligne => $this_id_produit) {
					if ($this_id_produit == $product_object->id) {
						if (check_if_module_active('conditionnement') && !empty($this->conditionnement[$this_numero_ligne])) {
							$real_stock_tested += $this->conditionnement[$this_numero_ligne] * $this->quantite[$this_numero_ligne];
						} else {
							$real_stock_tested += $this->quantite[$this_numero_ligne];
						}
					}
				}
			} else {
				$real_stock_tested = $this->quantite[$numero_ligne];
			}
			if (check_if_module_active('conditionnement') && !empty($this->conditionnement[$numero_ligne])) {
				$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
			} else {
				$real_stock_used = intval($this->quantite[$numero_ligne]);
			}
			$this->prix_ht_avant_code_promo[$numero_ligne] = $product_object->get_final_price($this->percent_remise_user, false, check_if_module_active('reseller') && is_reseller(), false, false, $real_stock_tested, true, true, false, $this->count_products($product_object->categorie_id)) / $real_stock_tested;

			$this->prix_avant_code_promo[$numero_ligne] = $product_object->get_final_price($this->percent_remise_user, $apply_vat, check_if_module_active('reseller') && is_reseller(), false, false, $real_stock_tested, true, true, false,$this->count_products($product_object->categorie_id)) / $real_stock_tested;
			
		}
		$this->percent_remise_produit[$numero_ligne] = $product_object->get_all_promotions_percentage(check_if_module_active('reseller') && is_reseller(), $this->percent_remise_user, false);
		if(!empty($product_object->on_gift) && $product_object->on_gift_points * $this->quantite[$numero_ligne] <= $max_available_gift_points) {
			foreach(array('ecotaxe', 'ecotaxe_ht', 'prix_cat', 'prix_cat_ht', 'option', 'option_ht', 'option_without_reduction', 'option_without_reduction_ht', 'prix_ht_avant_code_promo', 'prix_avant_code_promo', 'percent_remise_produit') as $this_property) {
				$this_temp = &$this->$this_property;
				$this_temp[$numero_ligne] = 0;
			}
			$max_available_gift_points -= $product_object->on_gift_points * $this->quantite[$numero_ligne];
		}
		if (check_if_module_active('conditionnement') && !empty($product_object->unit_per_pallet)) {
			// Calcul du pourcentage de remplissage de palette pour la ligne de produit. Le champ unit_per_pallet est actif uniqument si le module conditionnement est installé.
			$this->global_percent_pallet_filled += ($real_stock_used*100)/$product_object->unit_per_pallet;
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
		$attributs_list = vb($this->id_attribut[$numero_ligne]);
		// Avant d'effacer la ligne, on met proprement la quantité à 0 pour gérer les stocks, etc.
		$this->change_line_data($numero_ligne, vn($this->articles[$numero_ligne]), 0, vn($this->couleurId[$numero_ligne]), vn($this->tailleId[$numero_ligne]), vb($this->data_check[$numero_ligne]), vb($this->id_attribut[$numero_ligne]), vb($this->giftlist_owners[$numero_ligne]));
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
			$this->option_without_reduction[$numero_ligne],
			$this->option_without_reduction_ht[$numero_ligne],
			$this->data_check[$numero_ligne],
			$this->ecotaxe_ttc[$numero_ligne],
			$this->ecotaxe_ht[$numero_ligne],
			$this->reference[$numero_ligne],
			$this->id_attribut[$numero_ligne],
			$this->attribut[$numero_ligne],
			$this->total_prix_attribut[$numero_ligne]);
		if (!empty($this->ad_reference[$numero_ligne])) {
			unset($this->ad_reference[$numero_ligne]);
		}
		// Suppression des attributs d'image existants
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
		if ($new_code_promo !== null && $this->code_promo != $new_code_promo) {
			$this->code_promo = $new_code_promo;
			$code_promo_updated = true;
			if ($new_code_promo === '') {
				$this->message_caddie = array("SUCCES_CODE_PROMO" => $GLOBALS['STR_YOUR_CODE_PROMO'] . ' ' . $this->code_promo . ' ' . $GLOBALS['STR_HAS_BEEN_DELETED'] . '.');
			}
		}
		if (!empty($this->code_promo)) {
			// On vérifie d'abord la validité du code promo avec les paramètres insérés en back-office
			// On trouve le code promo si il est valide dans l'absolu
			// Restera à voir ensuite si il est bien valide pour l'utilisateur qui veut l'utiliser
			$sql = "SELECT *
				FROM `peel_codes_promos`
				WHERE " . get_filter_site_cond('codes_promos') . " AND nom='" . nohtml_real_escape_string($this->code_promo) . "' AND (nombre_prevue=0 OR compteur_utilisation<nombre_prevue) AND '" . date('Y-m-d', time()) . "' BETWEEN `date_debut` AND `date_fin` AND etat = '1'";
			$query = query($sql);
			$code_infos = fetch_assoc($query);
			if(!empty($code_promo_updated) && !empty($code_infos)) {
				$this->message_caddie = array("SUCCES_CODE_PROMO" => $GLOBALS['STR_YOUR_CODE_PROMO'] . ' ' . $code_infos['nom'] . ' ' . $GLOBALS['STR_IS_VALID'] . '.');
			}
		}
		// REMARQUE si on passe ici avec $new_code_promo non vide :
		// alors on doit absolument s'assurer que toutes les infos sur les produits et les ecotaxes sont bien à jour (exemple : nouveau produit vient d'être ajouté)
		// et donc dans ce cas on ne doit pas traiter maintenant la réduction, mais plus tard lorsque cette fonction sera appelée via update
		if (!empty($code_infos) && $new_code_promo === null) {
			// On traite un code promo qui est valide et ne vient pas d'être enregistré (cf. explications ci-dessus)
			$this->code_infos = $code_infos;
			if (!empty($code_infos['id_categorie']) || !empty($code_infos['cat_not_apply_code_promo'])) {
				// Si le code ne s'applique qu'à une catégorie et à ses filles
				$code_only_for_one_cat_and_sons = true;
			} else {
				$code_only_for_one_cat_and_sons = false;
			}
			foreach ($this->articles as $numero_ligne => $product_id) {
				// On cherche le montant par catégorie de produit pour pouvoir appliquer ensuite
				// des codes promos avec des seuils minimum sur une catégorie donnée
				// On fait la somme des produits en faisant attention à ce qu'un produit pourrait apparaître dans plusieurs catégories donc on peut sommer les montants
				$found_cat = null;
				$product_object = new Product($this->articles[$numero_ligne], null, false, null, true, true, false, true);
				$apply_code_on_this_product = $product_object->is_code_promo_applicable($code_infos['id_categorie'], $code_infos['product_filter'], $found_cat, $code_infos['cat_not_apply_code_promo'], $code_infos['promo_code_combinable']);
				unset($product_object);
				if($apply_code_on_this_product) {
					// ATTENTION : la somme de $this->total_produit_related_to_code_promo n'est pas égale au total du caddie si des produits se retrouvent dans plusieurs catégories
					if (check_if_module_active('conditionnement') && !empty($this->conditionnement[$numero_ligne])) {
						$this->total_produit_related_to_code_promo += $this->prix_avant_code_promo[$numero_ligne] * $this->quantite[$numero_ligne] * $this->conditionnement[$numero_ligne];
						// On calcule l'écotaxe car on doit la retirer du montant sur lequel on applique un pourcentage de réduction
						$this->total_ecotaxe_ttc_related_to_code_promo += $this->ecotaxe_ttc[$numero_ligne] * $this->quantite[$numero_ligne] * $this->conditionnement[$numero_ligne];
					} else {
						$this->total_produit_related_to_code_promo += $this->prix_avant_code_promo[$numero_ligne] * $this->quantite[$numero_ligne];
						// On calcule l'écotaxe car on doit la retirer du montant sur lequel on applique un pourcentage de réduction
						$this->total_ecotaxe_ttc_related_to_code_promo += $this->ecotaxe_ttc[$numero_ligne] * $this->quantite[$numero_ligne];
					}
				}
			}
			if ($this->total_produit_related_to_code_promo > 0 && ($code_only_for_one_cat_and_sons == false || ($code_only_for_one_cat_and_sons == true && !empty($found_cat)))) {
				// Il y a des produits qui s'applique au code promo
				// Si le code s'applique à toutes les catégories OU si le code s'applique à une seule catégorie et qu'il y a au moins un article de la catégorie correspondante dans le panier
				// On vérifie maintenant que le code est bien valide pour l'utilisateur qui veut l'utiliser
				$sql_check_cp_use = "SELECT c.id
					FROM peel_commandes c
					LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
					WHERE c.code_promo = '" . nohtml_real_escape_string($this->code_promo) . "' AND c.id_utilisateur ='" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND " . get_filter_site_cond('commandes', 'c') . ""
				 . (!empty($this->commande_id)? " AND c.id !='" . intval($this->commande_id) . "'" : "")
				 . " AND sp.technical_code NOT IN ('cancelled','refunded')";
				// Le code a-t-il déjà été utilisé par ce client ?
				$q_check_cp_use = query($sql_check_cp_use);
				if (($code_infos['nombre_prevue'] == 0 || num_rows($q_check_cp_use) < $code_infos['nombre_prevue']) && ($code_infos['nb_used_per_client'] == 0 || num_rows($q_check_cp_use) < $code_infos['nb_used_per_client'])) {
					// Si le code promotionnel n'a pas été trouvé dans une commande antérieure pour ce client, on passe à la suite.
					if ($code_infos['montant_min'] <= $this->total_produit_related_to_code_promo) {
						// Le code est OK : le montant minimum du code promotionnel est bien inférieur au montant total des catégories concernées par le code_promo
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
						if ($this->count_products()>0) {
							$this->message_caddie = array("ERROR_CODE_PROMO" => StringMb::nl2br_if_needed($GLOBALS['STR_CART_IS_NOT_ENOUGHT']));
						}
					}
				} else {
					$cancel_code = true;
					if ($this->count_products()>0) {
						$this->message_caddie = array("ERROR_CODE_PROMO" => StringMb::nl2br_if_needed($GLOBALS['STR_CODE_PROMO_USE_ONLY_ONCE']));
					}
				}
			} else {
				$cancel_code = true;
				if ($this->count_products()>0) {
					$this->message_caddie = array("ERROR_CODE_PROMO" => StringMb::nl2br_if_needed($GLOBALS['STR_CODE_PROMO_IS_NOT_FOR_THIS_CAT']));
				}
			}
		} elseif (empty($code_infos)) {
			$cancel_code = true;
			if (!empty($this->code_promo)) {
				$this->message_caddie = array("ERROR_CODE_PROMO" => StringMb::nl2br_if_needed($GLOBALS['STR_ERR_CODE_PROMO']));
			}
		}
		
		if (!empty($cancel_code)) {
			$this->code_promo = "";
			$this->code_infos = array();
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
		if(!isset($this->products_count[$cat_id])) {
			$this->products_count[$cat_id] = 0;
			if (!empty($this->quantite)) {
				foreach ($this->quantite as $numero_ligne => $qte) {
					if ($cat_id !== null) {
						if(!empty($this->articles[$numero_ligne])) {
							$product_object = new Product($this->articles[$numero_ligne], null, false, null, true, true, false, true);
							if (!empty($product_object) && intval($product_object->categorie_id) == $cat_id) {
								// Quantité pour la catégorie.
								$this->products_count[$cat_id] += abs($qte);
							}
							unset($product_object);
						}
					} else {
						$this->products_count[$cat_id] += abs($qte);
					}
				}
			}
		}
		return $this->products_count[$cat_id];
	}

	/**
	 * Traitement du moyen de paiement
	 *
	 * @param string $payment_technical_code
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
		$this->tva_tarif_paiement = $this->tarif_paiement - $this->tarif_paiement_ht;
	}

	/**
	 * Définition de la zone d'expédition
	 *
	 * @param mixed $zoneId
	 * @return
	 */
	function set_zone($zoneId)
	{
		$hook_result = call_module_hook('caddie_set_zone', array('this'=>$this, 'user'=>vb($_SESSION['session_utilisateur'])), 'array');
		if(!empty($hook_result['zone_id'])) {
			$zoneId = intval($hook_result['zone_id']);
		} else {
			$zoneId = intval($zoneId);
		}
		if ($zoneId != $this->zoneId) {
			$sql = "SELECT nom_" . $_SESSION['session_langue'] . " AS nom, tva, on_franco, technical_code
				FROM peel_zones z
				WHERE id = '" . intval($zoneId) . "' AND " . get_filter_site_cond('zones', 'z') . "";
			$query = query($sql);
			if ($Zone = fetch_assoc($query)) {
				$this->zone = $Zone['nom'];
				$this->zone_technical_code = $Zone['technical_code'];
				$this->zoneTva = $Zone['tva'];
				$this->zoneFranco = $Zone['on_franco'];
				$this->zoneId = $zoneId;
				// On initialise le type de port à blank car on a changé de zone ou de pays 
				$old_type = $this->type;
				$this->set_type('');
				// On essaye d'associer à nouveau le type, si la configuration le permet.
				$this->set_type($old_type);

			}
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
		if ($typeId != $this->typeId) {
			$type_name = get_delivery_type_name($typeId);
			// Pour fixer le type, il faut obligatoirement une zone associée. Donc on regarde en base de donnée si ce type est asssocié à la zone configurée.
			// L'association entre type et zone est faite dans peel_tarifs
			$sql = "SELECT 1
				FROM peel_tarifs tf
				INNER JOIN peel_types t ON t.id = tf.type AND " . get_filter_site_cond('types', 't') . "
				WHERE t.etat = 1 AND tf.type='" . intval($typeId) . "' AND tf.zone = '" . intval($this->zoneId) . "' AND " . get_filter_site_cond('tarifs', 'tf') . "";
			$query = query($sql);
			if ($type_name !== false && num_rows($query) > 0) {
				// On définit le type de port seulement si trouvé en BDD
				$this->typeId = $typeId;
				$this->type = $type_name;
			} else {
				$this->typeId = '';
				$this->type = '';
			}
			$this->update();
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
		static $update_in_process;
		$this->products_count = array(); // On demande recalcul de cette valeur en initialisant le tableau
		$this->global_promotion = null; // On demande recalcul de cette valeur en la mettant à null
		// Evite les boucles infinies
		if(!empty($update_in_process)) {
			$skip_add_products = true;
		}
		$update_in_process = true;
		$quantity_total = 0;
		$value_total = 0;
		foreach ($this->articles as $numero_ligne => $product_id) {
			$product_object = new Product($product_id, null, false, null, true, $this->apply_vat);
			if(vn($product_object->temperature) <0 && !empty($GLOBALS['site_parameters']['delivery_with_carbo_glace'])) {
				$apply_carboglace = true;
			}
			if(!empty($GLOBALS['site_parameters']['user_offers_table_enable'])) {
				if(empty($quantity_by_brand['brand_'.$product_object->get_product_brands(false)]) || $product_object->get_product_brands(false) == '') {
					$quantity_by_brand['brand_'.$product_object->get_product_brands(false)] = 0;
					$total_by_brand['brand_'.$product_object->get_product_brands(false)] = 0;
				}
				$quantity_by_brand['brand_'.$product_object->get_product_brands(false)] += $this->quantite[$numero_ligne];
				$total_by_brand['brand_'.$product_object->get_product_brands(false)] += floatval($this->quantite[$numero_ligne]*$product_object->prix_ht);
				$quantity_total += $this->quantite[$numero_ligne];
				$value_total += floatval($this->quantite[$numero_ligne]*$product_object->prix_ht);
			}
			if($product_object->technical_code == 'carboglace') {
				// on efface puis on va recréer systématiquement la ligne de carboglace pour éviter toute incohérence et avoir quantité à 1 quoiqu'il arrive
				$this->delete_line($numero_ligne);
			}
			unset($product_object);
		}
		if (!empty($this->zoneId) && empty($skip_add_products)) {
			if(!empty($apply_carboglace)) {
				if(!empty($GLOBALS['site_parameters']['user_offers_table_enable']) && !empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
					foreach ($this->articles as $numero_ligne => $product_id) {
						$product_object = new Product($product_id, null, false, null, true, $this->apply_vat);
						$sql = "SELECT o.*
							FROM peel_offres o
							LEFT JOIN peel_utilisateurs_offres uo ON uo.id_utilisateur='" . intval(vn($_SESSION['session_utilisateur']['id_utilisateur'])) . "' AND o.id_offre=uo.id_offre
							WHERE " . get_filter_site_cond('offres', 'o') . " AND (o.id_offre=0 OR uo.id_offre IS NOT NULL) AND o.date_limite>='" . date('Y-m-d', time()) . "' AND (" . (!empty($product_object->reference)?"(o.ref='".real_escape_string($product_object->reference)."' AND o.qnte<='".intval($this->quantite[$numero_ligne])."' AND o.seuil<='".max(floatval($this->quantite[$numero_ligne]*$product_object->prix_ht), floatval($value_total))."') OR ":"") . "(o.ref='' AND o.fournisseur IN ('".implode("','", real_escape_string($product_object->get_product_brands(true)))."') AND o.qnte<='".intval(vb($quantity_by_brand['brand_'.$product_object->get_product_brands(false)]))."' AND o.seuil<='".max(floatval($total_by_brand['brand_'.$product_object->get_product_brands(false)]), floatval($value_total))."') OR (o.ref='' AND o.fournisseur='' AND o.qnte<='".intval($quantity_total)."' AND o.seuil<='".floatval($value_total)."'))
							ORDER BY " . (floatval($product_object->prix_ht)>0 ? "IF(o.prix>0 AND o.remise_percent>0, LEAST(o.prix, (1-o.remise_percent/100)*'".floatval($product_object->prix_ht)."'), IF(o.remise_percent>0, (1-o.remise_percent/100)*'".floatval($product_object->prix_ht)."', o.prix))" : "o.prix") . " ASC, o.remise_percent DESC, o.port_offert DESC, o.carbo_offert DESC
							LIMIT 1";
						$query = query($sql);
						if(($result = fetch_object($query)) && $result->carbo_offert == 1) {
							unset($apply_carboglace);
							break;
						}
						unset($product_object);
					}
				}
				if(!empty($apply_carboglace)) {
					$sql = 'SELECT z.carboglace_ht, z.carboglace_tva_percent
						FROM peel_zones z
						WHERE id = "' . intval($this->zoneId) . '" AND ' . get_filter_site_cond('zones', 'z') . '
						LIMIT 1';
					$query = query($sql);
					$result_zones = fetch_assoc($query);
					if (!empty($result_zones)) {
						$product_object = new Product('carboglace', null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
						if(!empty($product_object->id)) {
							$_SESSION['session_caddie']->add_product($product_object, 1, '', '');
						}
					}
				}
			}
		}
		if ($percent_remise_user !== null) {
			$this->percent_remise_user = $percent_remise_user;
		}
		$this->apply_vat = ($this->zoneTva && !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
		$max_available_gift_points = intval(vn($_SESSION['session_utilisateur']['points']));
		// ETAPE 0 :
		// On met d'abord à jour toutes les lignes et notamment les quantités si la gestion de stock est activée et qu'un stock vient de disparaitre depuis le dernier update du caddie
		foreach ($this->articles as $numero_ligne => $product_id) {
			$max_available_gift_points = $this->update_line($numero_ligne, $max_available_gift_points);
		}
		// INITIALISATION : On enlève tous les produits non valides
		// c'est-à-dire ceux dont l'identifiant n'est pas numérique ou dont la quantité est < 1
		foreach ($this->quantite as $numero_ligne => $qte) {
			if ($qte < 1) {
				// Attention ici normalement on nettoie si la quantité commandée est < à 1
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
				unset($this->option_without_reduction[$numero_ligne]);
				unset($this->option_without_reduction_ht[$numero_ligne]);
				unset($this->option_ht[$numero_ligne]);
				unset($this->data_check[$numero_ligne]);
				unset($this->ecotaxe_ttc[$numero_ligne]);
				unset($this->ecotaxe_ht[$numero_ligne]);
				unset($this->reference[$numero_ligne]);
				unset($this->id_attribut[$numero_ligne]);
				unset($this->attribut[$numero_ligne]);
				unset($this->total_prix_attribut[$numero_ligne]);
				if (!empty($this->ad_reference[$numero_ligne])) {
					unset($this->ad_reference[$numero_ligne]);
				}
			}
		}

		$this->avoir_user = max(vn($_SESSION['session_utilisateur']['avoir']), 0);
		$this->global_percent_pallet_filled = 0;
		// ETAPE 1 : On calcule les totaux avant réduction
		foreach ($this->articles as $numero_ligne => $product_id) {
			$this->total_ecotaxe_ttc += $this->ecotaxe_ttc[$numero_ligne] * $this->quantite[$numero_ligne];
			$this->total_ecotaxe_ht += $this->ecotaxe_ht[$numero_ligne] * $this->quantite[$numero_ligne];
			if (check_if_module_active('conditionnement') && !empty($this->conditionnement[$numero_ligne])) {
				$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
			} else {
				$real_stock_used = intval($this->quantite[$numero_ligne]);
			}
			$this->total_produit_avant_code_promo += $this->prix_avant_code_promo[$numero_ligne] * $real_stock_used;
		}
		// ETAPE 2 : maintenant qu'on connait le montant total avant réduction pour les produits et chaque catégorie de produits,
		// on vérifie si le code promotionnel est toujours actif après un changement de montant total du caddie.
		$this->update_code_promo();

		if (empty($this->code_infos) && !empty($GLOBALS['site_parameters']['cart_offer_products_for_each_lot_count'])) {
			// L'exonération de produits en fonction du nombre ajouté au panier est active. Il faut mettre à 0 le montant des produits les moins chères si les conditions sont réunis pour le faire :
			// Sur N produits achetés, le moins cher des N est offert. Cette opération est cumulable : pour 2*N produits / 2 offerts, etc.
			// On commence par calculer le nombre de produits à offrir, puis on regarde les moins chers du caddie en tenant compte des quantités dans le panier.
			$free_cost_cart_nb_product = floor($this->count_products()/$GLOBALS['site_parameters']['cart_offer_products_for_each_lot_count']);
			if(!empty($free_cost_cart_nb_product)) {
				// Récupération des prix de tous les produits dans le caddie
				foreach($this->articles as $numero_ligne => $product_id) {
					$this_quantity = $this->quantite[$numero_ligne];
					while($this_quantity--) {
						$prices_offered_array[] = $this->prix_avant_code_promo[$numero_ligne];
					}
				}
				// Récupération des $free_cost_cart_nb_product produits les moins chers. La fonction sort réinitialise les clés.
				sort($prices_offered_array);
				foreach($prices_offered_array as $this_item => $this_price) {
					if($this_item>=$free_cost_cart_nb_product) {
						// Le tableau est trié dans l'ordre, on conserve les $free_cost_cart_nb_product dans le tableau
						unset($prices_offered_array[$this_item]);
					}
				}
			}
		}
		// Recalcul des produits en appliquant correctement les ventilations des codes promos
		foreach ($this->articles as $numero_ligne => $product_id) {
			$promotion_ventile_ttc = 0;
			// Gestion du code promo
			if (!empty($this->total_produit_related_to_code_promo) && !empty($this->code_infos)) {
				$found_cat = null;
				$product_object = new Product($this->articles[$numero_ligne], null, false, null, true, true, false, true);
				$apply_code_on_this_product = $product_object->is_code_promo_applicable($this->code_infos['id_categorie'], $this->code_infos['product_filter'], $found_cat, $this->code_infos['cat_not_apply_code_promo'], $this->code_infos['promo_code_combinable']);
				unset($product_object);
				if ($apply_code_on_this_product) {
					// Panier au montant non nul et code promo s'appliquant au total du panier ou à une catégorie et ses filles qui concernent au moins un produit du panier
					$promotion_ventile_ttc = $this->total_reduction_code_promo * ($this->prix_avant_code_promo[$numero_ligne] * $this->quantite[$numero_ligne]) / $this->total_produit_related_to_code_promo;
				}
			} elseif (!empty($prices_offered_array)) {
				// On calcule le nombre de produits offerts au prix unitaire correspondant à la ligne en cours, et on les prend en compte tant que le décompte prévu n'est pas atteint
				// Attention : il peut y avoir plusieurs produits dans le panier avec le même prix, d'où le raisonnement par prix et non pas par produit
				$this_quantity_offered = 0;
				while(!empty($prices_offered_array) && in_array($this->prix_avant_code_promo[$numero_ligne], $prices_offered_array)) {
					$this_quantity_offered++;
					unset($prices_offered_array[array_search($this->prix_avant_code_promo[$numero_ligne], $prices_offered_array)]);
				}
				$promotion_ventile_ttc = $this->prix_avant_code_promo[$numero_ligne] * $this_quantity_offered;
			} else {
				// Panier au montant nul ou code promo à une catégorie non applicable au panier
			}
			// Le produit et l'ecotaxe ont potentiellement deux taux de TVA différents => quand on passe de HT à TTC il faut traiter l'ecotaxe à part
			// Par ailleurs il faut arrondir les prix en TTC ou HT pour qu'ensuite l'application de quantités ne donne pas de problèmes d'arrondi
			if (display_prices_with_taxes_active()) {
				// On arrondit le prix TTC
				$this->prix[$numero_ligne] = round($this->prix_avant_code_promo[$numero_ligne] - $promotion_ventile_ttc / $this->quantite[$numero_ligne], 2);
				// On recalcule le prix HT à partir du prix TTC arrondi
				$this->prix_ht[$numero_ligne] = ($this->prix[$numero_ligne] - $this->ecotaxe_ttc[$numero_ligne]) / (1 + $this->tva_percent[$numero_ligne] / 100) + $this->ecotaxe_ht[$numero_ligne];
			} else {
				// On arrondit le prix HT
				// var_dump($this->prix_ht_avant_code_promo[$numero_ligne]);
				$this->prix_ht[$numero_ligne] = round($this->prix_ht_avant_code_promo[$numero_ligne] - $promotion_ventile_ttc / $this->quantite[$numero_ligne] / (1 + $this->tva_percent[$numero_ligne] / 100), 2);
				// On recalcule le prix TTC à partir du prix HT arrondi
				$this->prix[$numero_ligne] = (($this->prix_ht[$numero_ligne] - $this->ecotaxe_ht[$numero_ligne]) * (1 + $this->tva_percent[$numero_ligne] / 100)) + $this->ecotaxe_ttc[$numero_ligne];
			}
		}
		
		// Calcul du total des lignes
		$this->_recalc_line_totals();
		
		// Calcul du pourcentage applicable en fonction du total des produits dans le panier
		if (check_if_module_active('lot') && !empty($GLOBALS['site_parameters']['global_promotion_percent_by_threshold'])) {
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
		if(check_if_module_active('reseller') && is_reseller() && isset($GLOBALS['site_parameters']['minimal_amount_to_order_reve'])) {
			$treshold_to_use = $GLOBALS['site_parameters']['minimal_amount_to_order_reve'];
		} else {
			$treshold_to_use = vn($GLOBALS['site_parameters']['minimal_amount_to_order']);
		}
		if (count($this->articles) && $this->total_produit < vn($GLOBALS['site_parameters']['small_order_overcost_limit']) && $this->total_produit >= $treshold_to_use) {
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
		if(!empty($GLOBALS['site_parameters']['user_offers_table_enable']) && !empty($_SESSION['session_utilisateur']['id_utilisateur'])) {
			foreach ($this->articles as $numero_ligne => $product_id) {
				$product_object = new Product($product_id, null, false, null, true, $this->apply_vat, false, true);
				$sql = "SELECT o.*
					FROM peel_offres o
					LEFT JOIN peel_utilisateurs_offres uo ON uo.id_utilisateur='" . intval(vn($_SESSION['session_utilisateur']['id_utilisateur'])) . "' AND o.id_offre=uo.id_offre
					WHERE " . get_filter_site_cond('offres', 'o') . " AND (o.id_offre=0 OR uo.id_offre IS NOT NULL) AND o.date_limite>='" . date('Y-m-d', time()) . "' AND (" . (!empty($product_object->reference)?"(o.ref='".real_escape_string($product_object->reference)."' AND o.qnte<='".intval($this->quantite[$numero_ligne])."' AND o.seuil<='".max(floatval($this->quantite[$numero_ligne]*$product_object->prix_ht), floatval($value_total))."') OR ":"") . "(o.ref='' AND o.fournisseur IN ('".implode("','", real_escape_string($product_object->get_product_brands(true)))."') AND o.qnte<='".intval(vn($quantity_by_brand['brand_'.$product_object->get_product_brands(false)]))."' AND o.seuil<='".max(floatval(vn($total_by_brand['brand_'.$product_object->get_product_brands(false)])), floatval($value_total))."') OR (o.ref='' AND o.fournisseur='' AND o.qnte<='".intval($quantity_total)."' AND o.seuil<='".floatval($value_total)."'))
					ORDER BY " . (floatval($product_object->prix_ht)>0 ? "IF(o.prix>0 AND o.remise_percent>0, LEAST(o.prix, (1-o.remise_percent/100)*'".floatval($product_object->prix_ht)."'), IF(o.remise_percent>0, (1-o.remise_percent/100)*'".floatval($product_object->prix_ht)."', o.prix))" : "o.prix") . " ASC, o.remise_percent DESC, o.port_offert DESC, o.carbo_offert DESC
					LIMIT 1";
				$query = query($sql);
				if(($result = fetch_object($query)) && $result->port_offert == 1) {
					$delivery_cost_infos = false;
					break;
				}
				unset($product_object);
			}
			// Fin de l'usage des informations pour peel_offer : carboglace et frais de ports ont été étudiés
			unset($quantity_by_brand);
			unset($total_by_brand);
			unset($quantity_total);
			unset($value_total);
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
		$free_delivery_vat = false;
		if (!empty($GLOBALS['site_parameters']['delivery_vat_free_if_product_with_no_vat'])) {
			// Mode d'exonération de la TVA des frais de port si un produit n'a pas de TVA.
			foreach ($this->articles as $numero_ligne => $product_id) {
				if (empty($this->tva[$numero_ligne])) {
					// un produit n'a pas de TVA. On exonère la TVA pour le transport
					$free_delivery_vat = true;
				}
			}
		}
		
		if ($this->apply_vat && empty($free_delivery_vat)) {
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
		
		if (!empty($this->global_percent_pallet_filled)) {
			// Calcul du nombre total de palette à partir du pourcentage de remplissage. 100% = 1 palette.
			$this->pallet_count = ceil($this->global_percent_pallet_filled/100);
		}
		
		if (!empty($GLOBALS['site_parameters']['save_caddie_in_cookie'])) {
			// ETAPE 7 : Le contenu du panier est stocké dans un cookie, pour que l'utilisateurs puisse retrouver son panier après que la session soit expiré. Le panier est chargé avec les informations du cookie dans la fonction init
			if (isset($_COOKIE[$GLOBALS['caddie_cookie_name']])) {
				// Le cookie qui contient les produits est vidé à chaque calcul du caddie, c'est plus simple que de mettre à jour ligne par ligne.
				unset($_COOKIE[$GLOBALS['caddie_cookie_name']]);
			}
			$GLOBALS['product_in_caddie_cookie'] = array();
			foreach ($this->articles as $numero_ligne => $product_id) {
				// on ajoute le produit à la liste.
				$GLOBALS['product_in_caddie_cookie'][] = array('quantite'=>$this->quantite[$numero_ligne],'product_id'=>$product_id,'couleurId'=>$this->couleurId[$numero_ligne],'tailleId'=>$this->tailleId[$numero_ligne],'id_attribut'=>$this->id_attribut[$numero_ligne]);
			}
			
		}
		// FIN
		$update_in_process = false;
		// Redirection en cas de problème de stock au dernier moment lors de la commande => on redirige vers la page de caddie
		if(!empty($GLOBALS['quantity_wished_not_fulfilled']) && (defined('IN_STEP1') || defined('IN_STEP2') || defined('IN_STEP3'))) {
			redirect_and_die(get_url('caddie_affichage'));
		}
		return true;
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
			if (check_if_module_active('conditionnement') && !empty($this->conditionnement[$numero_ligne])) {
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
			if (check_if_module_active('conditionnement') && !empty($this->conditionnement[$numero_ligne])) {
				$real_stock_used = $this->conditionnement[$numero_ligne] * $this->quantite[$numero_ligne];
			} else {
				$real_stock_used = intval($this->quantite[$numero_ligne]);
			}

			$this->remise_ht[$numero_ligne] = ($this->prix_cat_ht[$numero_ligne] + $this->option_ht[$numero_ligne] + $this->option_without_reduction_ht[$numero_ligne] - $this->prix_ht[$numero_ligne]) * $real_stock_used;
			$this->remise[$numero_ligne] = ($this->prix_cat[$numero_ligne] + $this->option[$numero_ligne] + $this->option_without_reduction[$numero_ligne] - $this->prix[$numero_ligne]) * $real_stock_used;
			$this->total_remise += $this->remise[$numero_ligne];
			$this->total_remise_ht += $this->remise_ht[$numero_ligne];
			$this->total_quantite += $this->quantite[$numero_ligne];
			$this->total_produit += $this->total_prix[$numero_ligne];
			$this->total_produit_ht += $this->total_prix_ht[$numero_ligne];
			$this->total_ecotaxe_ttc += $this->ecotaxe_ttc[$numero_ligne] * $real_stock_used;
			$this->total_ecotaxe_ht += $this->ecotaxe_ht[$numero_ligne] * $real_stock_used;
			$this->total_option += ($this->option[$numero_ligne]+$this->option_without_reduction[$numero_ligne]) * $real_stock_used;
			$this->total_option_ht += ($this->option_ht[$numero_ligne]+$this->option_without_reduction_ht[$numero_ligne]) * $real_stock_used;
			$this->tva_total_produit += $this->tva[$numero_ligne];
			$this->total_poids += $this->poids[$numero_ligne];
			if (check_if_module_active('gifts')) {
				$this->total_points += $this->points[$numero_ligne];
			}
		}
		return true;
	}

	/**
	 * Enregistre la commande dans la base
	 *
	 * @param mixed $order_infos This array has all user infos related to the order
	 * @return
	 */
	function save_in_database($order_infos)
	{
		// On s'assure des montants avant leur insertion en BDD
		$this->update();
		/* Le reversement affilié est calculé sur le total HT des produits */
		if (check_if_module_active('affiliation') && !empty($_SESSION['session_affilie']) && is_affiliation_active($_SESSION['session_affilie'])) {
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
		$order_infos['id_utilisateur'] = vb($_SESSION['session_utilisateur']['id_utilisateur']);
		$order_infos['id_parrain'] = vb($_SESSION['session_utilisateur']['id_parrain']);
		$order_infos['parrain'] =  vb($_SESSION['session_utilisateur']['type']);
		$order_infos['devise'] =  vb($_SESSION['session_devise']['code']);
		$order_infos['currency_rate'] =  vb($_SESSION['session_devise']['conversion']);
		$order_infos['delivery_tracking'] = null;
		$order_infos['campaign_id'] = vb($this->campaign_id);
		$order_infos['site_id'] = $GLOBALS['site_id'];
		
		$articles = array();
		foreach ($this->articles as $numero_ligne => $product_id) {
			// Attention ici normalement on nettoie si la quantité commandée est < à 1
			$articles[$numero_ligne]['product_id'] = $product_id;
			$articles[$numero_ligne]['quantite'] = $this->quantite[$numero_ligne];
			if (check_if_module_active('listecadeau')) {
				$articles[$numero_ligne]['giftlist_owners'] = $this->giftlist_owners[$numero_ligne];
			}
			$articles[$numero_ligne]['poids'] = $this->poids[$numero_ligne];
			$articles[$numero_ligne]['points'] = $this->points[$numero_ligne];
			$articles[$numero_ligne]['couleurId'] = $this->couleurId[$numero_ligne];
			$articles[$numero_ligne]['tailleId'] = $this->tailleId[$numero_ligne];
			if (check_if_module_active('conditionnement')) {
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
			$articles[$numero_ligne]['option'] = $this->option[$numero_ligne] + $this->option_without_reduction[$numero_ligne];
			$articles[$numero_ligne]['option_ht'] = $this->option_ht[$numero_ligne] + $this->option_without_reduction_ht[$numero_ligne];
			$articles[$numero_ligne]['data_check'] = $this->data_check[$numero_ligne];
			$articles[$numero_ligne]['ecotaxe_ttc'] = $this->ecotaxe_ttc[$numero_ligne];
			$articles[$numero_ligne]['ecotaxe_ht'] = $this->ecotaxe_ht[$numero_ligne];
			$articles[$numero_ligne]['reference'] = (!empty($this->ad_reference[$numero_ligne])?$this->ad_reference[$numero_ligne]:$this->reference[$numero_ligne]);
			$articles[$numero_ligne]['attribut'] = vb($this->attribut[$numero_ligne]);
			$articles[$numero_ligne]['id_attribut'] = vb($this->id_attribut[$numero_ligne]);
			$articles[$numero_ligne]['total_prix_attribut'] = vb($this->total_prix_attribut[$numero_ligne]);
			$articles[$numero_ligne]['commentaires_admin'] = vb($this->commentaires_admin[$numero_ligne]);
		}
		if (sha1(serialize($order_infos) . serialize($articles)) != $this->commande_hash) {
			// La commande n'avait jamais été créée, ou les informations de la commande sont différentes
			$this->commande_hash = sha1(serialize($order_infos) . serialize($articles));

			$order_id = create_or_update_order($order_infos, $articles);

			$used_gift_points = $this->get_available_point_for_current_line(null, true);
			if (!empty($this->commande_id) && $this->commande_id != $order_id) {
				// On annule la commande précédemment liée à ce caddie car on vient de créer une nouvelle commande lui correspondant
				// SAUF si elle est déjà payée (=> 3ème argument à false)
				$GLOBALS['output_create_or_update_order'] = update_order_payment_status($this->commande_id, 'cancelled', false);
			}
			// On retire les points utilisés lors de la commande.
			// NB : Cette gestion n'utilise pas "total_points" de peel_commandes, qui est géré ailleurs et concerne les points gagnés lors de la commande
			query("UPDATE peel_utilisateurs
				SET points=points-" . intval($used_gift_points) . "
				WHERE id_utilisateur='" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND " . get_filter_site_cond('utilisateurs') . "");
			$user_infos = get_user_information($_SESSION['session_utilisateur']['id_utilisateur']);
			$_SESSION['session_utilisateur']['points'] = $user_infos['points'];
			$this->commande_id = $order_id;
			// Incrémentation du compteur de code promotionnel
			if (!empty($this->code_promo)) {
				query("UPDATE peel_codes_promos
					SET compteur_utilisation=compteur_utilisation+1
					WHERE nom = '" . nohtml_real_escape_string($this->code_promo) . "' AND " . get_filter_site_cond('codes_promos') . "");
			}
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
			WHERE id_utilisateur = '" . intval($_SESSION['session_utilisateur']['id_utilisateur']) . "' AND " . get_filter_site_cond('utilisateurs') . "");

		// si il y a des commandes de cadeau on envoie un email
		if (!empty($this->giftlist_owners[0]) && check_if_module_active('listecadeau')) {
			email_ordered_cadeaux($this->commande_id, $order_infos, $this->giftlist_owners[0]);
		}
		return $this->commande_id;
	}
	
	/**
	 * Calcule le pourcentage de promotion général à appliquer à tous les produits (réduction parmi beaucoup d'autres)
	 *
	 * @return
	 */
	function get_global_promotion() {
		if($this->global_promotion === null) {
			if (!empty($GLOBALS['site_parameters']['global_remise_percent'])) {
				if(is_array($GLOBALS['site_parameters']['global_remise_percent'])) {
					// Si c'est un tableau, on souhaite définir un seuil d'application du montant. On trie le tableau du plus petit seuil au seuil le plus important pour faire une boucle.
					ksort($GLOBALS['site_parameters']['global_remise_percent']);
					$total = 0;
					foreach($this>articles as $numero_ligne => $id) {
						$product_object = new Product($id);
						// impossible d'utiliser directement $_SESSION['session_caddie']->total, puisque la variable est en cours de calcul quand on la teste
						if (empty($GLOBALS['site_parameters']['product_promotion_plurality_disable']) || (!empty($GLOBALS['site_parameters']['product_promotion_plurality_disable']) && $product_object->promotion==0)) {
							// On n'utilise que les produits sur lesquels aucune réduction ne s'applique pour calculer le seuil.
							$total += $this->prix_cat[$numero_ligne] * $this->quantite[$numero_ligne];
						}
						unset($product_object);
					}
					foreach($GLOBALS['site_parameters']['global_remise_percent'] as $this_treshold => $this_percent) {
						if (vn($total) >= $this_treshold) {
						   // On a dépassé le seuil, donc la valeur la plus proche est celle précédemment trouvée.
						   $this->global_promotion = vn($this_percent);
						}
					}
				} else {
					$this->global_promotion = vn($GLOBALS['site_parameters']['global_remise_percent']);
				}
			} else {
				$this->global_promotion = 0;
			}
		}
		return $this->global_promotion;
	}
}

