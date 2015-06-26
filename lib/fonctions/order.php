<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: order.php 46023 2015-06-03 14:59:46Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Fonction qui génère le numéro de facture pour la facture n° $id à partir du format défini dans les paramètres du site
 *
 * @param string $bill_number_format
 * @param integer $id
 * @param boolean $generate_bill_number_if_empty
 * @return string
 */
function get_bill_number($bill_number_format, $id, $generate_bill_number_if_empty = true)
{
	if(empty($bill_number_format) && $generate_bill_number_if_empty){
		// on récupère le format de numéro dans la base, si pas déjà spécifié lors de l'appel (pour édition dans l'administration par exemple)
		$bill_number_format = vb($GLOBALS['site_parameters']['format_numero_facture']);
	}
	if (!empty($bill_number_format) && !empty($id)) {
		// On remplace les tags standards gérés directement par template_tags_replace(...)
		$bill_number_format = template_tags_replace($bill_number_format);
		preg_match_all('#\[(.*?)\]#', $bill_number_format, $matches);
		$tag_names = $matches[1];
		if (!empty($tag_names)) {
			// tag_name : représente le tag à l'intérieur des crochets, à savoir par exemple : ++,5    ou    id    ou    id,5
			// column : le nom de la colonne, ou ++
			foreach($tag_names as $this_key => $this_item) {
				// On traite les valeurs du type [xxx,N]
				$temp = explode(',', $this_item);
				// $tag_full_names_by_colum contient un tableau des différents tags faisant appel à une colonne de la table
				$tag_full_names_by_colum[$temp[0]][] = $this_item;
				if (!empty($temp[1])) {
					$number_zero_fill_by_tag_name[$this_item] = $temp[1];
				}
				if ($temp[0] != '++') {
					// Liste des colonnes de la table, donc les valeurs de columns qui ne sont pas ++
					$column_names[] = $temp[0];
				}
			}
			if (!empty($column_names)) {
				$custom_template_tags = array();
				// On va chercher les valeurs dans les champ de la table qui correspondent aux texte entre crochet du format de facture.
				$sql = "SELECT " . implode(',', word_real_escape_string($column_names)) . "
					FROM peel_commandes
					WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('commandes') . "";
				$q = query($sql, false, null, true);
				if ($result = fetch_assoc($q)) {
					foreach($result as $this_column => $this_value) {
						foreach($tag_full_names_by_colum[$this_column] as $this_tag_name) {
							if (!empty($number_zero_fill_by_tag_name[$this_tag_name])) {
								// On formatte les champs du type [xxx,N]
								$this_value = str_pad($this_value, intval($number_zero_fill_by_tag_name[$this_tag_name]), 0, STR_PAD_LEFT);
							}
							$custom_template_tags[$this_tag_name] = $this_value;
						}
					}
				}
				// On remplace les tags trouvés ci-dessus
				$bill_number_format = template_tags_replace($bill_number_format, $custom_template_tags);
			}
			// On ne gère qu'un seul tag du type ++ dans la formule globale, on ne peut pas en mettre plusieurs (ce qui serait par ailleurs très peu utile concrètement)
			if (!empty($tag_full_names_by_colum['++'])) {
				// On gère l'incrémentation du numéro si utilisation de [++]
				// Par exemple pour TEST43[++]ACD : on cherche les numéros déjà enregistrés commençant par TEST43
				// Si on en trouve un qui est TEST43538ACD, on va donc donner TEST43 concaténé avec 538+1 puis ACD => ça donne TEST43539ACD
				$bill_number_format_begin = String::substr($bill_number_format, 0, String::strpos($bill_number_format, '[++'));
				$bill_number_format_end = String::substr($bill_number_format, String::strpos($bill_number_format, ']') + 1);
				$sql = "SELECT MAX(0+SUBSTRING(numero,1+" . String::strlen($bill_number_format_begin) . ",LENGTH(numero)-" . (String::strlen($bill_number_format_begin) + String::strlen($bill_number_format_end)) . ")) AS max_numero_part
					FROM peel_commandes
					WHERE id<>'" . intval($id) . "' AND " . get_filter_site_cond('commandes') . " AND numero LIKE '" . real_escape_string($bill_number_format_begin) . "%" . real_escape_string($bill_number_format_end) . "' AND SUBSTRING(numero,1+" . String::strlen($bill_number_format_begin) . ",LENGTH(numero)-" . (String::strlen($bill_number_format_begin) + String::strlen($bill_number_format_end)) . ") REGEXP ('^([0-9]+)$')";
				$q = query($sql);
				if ($result = fetch_assoc($q)) {
					$last_number = $result['max_numero_part'];
				} else {
					$last_number = 0;
				}
				$bill_number_format_incremented_part = 1 + $last_number;
				foreach($tag_full_names_by_colum['++'] as $this_tag_name) {
					// NB : on ne gère qu'un seul tag du type ++ au maximum
					if (!empty($number_zero_fill_by_tag_name[$this_tag_name])) {
						// On formatte les champs du type [xxx,N]
						$bill_number_format_incremented_part = str_pad($bill_number_format_incremented_part, $number_zero_fill_by_tag_name[$this_tag_name], 0, STR_PAD_LEFT);
					}
				}
				$bill_number_format = $bill_number_format_begin . $bill_number_format_incremented_part . $bill_number_format_end;
			}
		}
	}
	return $bill_number_format;
}

/**
 * Met à jour le status de paiement et/ou de livraison d'une commande, et gère les stocks suivant le status avant et après modification
 *
 * @param integer $order_id
 * @param mixed $status_or_is_payment_validated Variable booléenne pour dire si c'est validé ou non, ou nombre avec le statut
 * @param boolean $allow_update_paid_orders
 * @param integer $statut_livraison_new
 * @param boolean $delivery_tracking
 * @param boolean $no_stock_decrement_already_done
 * @param mixed $payment_technical_code
 * @return
 */
function update_order_payment_status($order_id, $status_or_is_payment_validated, $allow_update_paid_orders = true, $statut_livraison_new = null, $delivery_tracking = null, $no_stock_decrement_already_done = false, $payment_technical_code=null)
{
	$output = '';
	$sql_set_array = array();
	// Payment status
	if ($status_or_is_payment_validated === true) {
		// Commande payée
		$statut_paiement_new = 'completed';
		if (check_if_module_active('download')) {
			send_mail_product_download($order_id);
		}
	} elseif ($status_or_is_payment_validated === false) {
		// Commande à annuler
		$statut_paiement_new = 'cancelled';
	} elseif(is_numeric($status_or_is_payment_validated)) {
		// conversion de l'id du statut de paiement par son technical_code pour sa bonne prise en compte par update_order_payment_status
		$sql = 'SELECT p.technical_code
			FROM peel_statut_paiement p
			WHERE technical_code!="" AND id=' . intval($status_or_is_payment_validated) . ' AND ' . get_filter_site_cond('statut_paiement', 'p');
		$query = query($sql);
		if ($result = fetch_assoc($query) ) {
			$statut_paiement_new = $result['technical_code'];
		} else {
			$payment_status_by_legacy_id_compatibility_array = array(0 => "discussed", 1 => "pending", 2 => "being_checked", 3 => "completed", 6 => "cancelled", 9 => "refunded");
			$statut_paiement_new = $payment_status_by_legacy_id_compatibility_array[intval($status_or_is_payment_validated)];
		}
	} elseif(!empty($status_or_is_payment_validated)) {
		$statut_paiement_new = $status_or_is_payment_validated;
	} else {
		$statut_paiement_new = null;
	}
	$sql = 'SELECT p.id, p.technical_code
		FROM peel_statut_paiement p
		WHERE ' . get_filter_site_cond('statut_paiement', 'p');
	$query = query($sql);
	while ($result = fetch_assoc($query)) {
		$payment_status_id_by_technical_code_array[$result['technical_code']] = $result['id'];
	}

	// Delivery status
	if(is_numeric($statut_livraison_new)) {
			// conversion de l'id du statut de paiement par son technical_code pour sa bonne prise en compte par update_order_payment_status
		$sql = 'SELECT l.technical_code
			FROM peel_statut_livraison l
			WHERE technical_code!="" AND id=' . intval($statut_livraison_new) . ' AND ' . get_filter_site_cond('statut_livraison', 'l');
		$query = query($sql);
		if ($result = fetch_assoc($query) ) {
			$statut_livraison_new = $result['technical_code'];
		} else {
			$livraison_status_by_legacy_id_compatibility_array = array(0 => "discussed", 1 => "processing", 3 => "dispatched", 6 => "cancelled", 9 => "waiting_for_supply");
			$statut_livraison_new = $livraison_status_by_legacy_id_compatibility_array[intval($statut_livraison_new)];
		}
	}
	$sql = 'SELECT l.id, l.technical_code
		FROM peel_statut_livraison l
		WHERE ' . get_filter_site_cond('statut_livraison', 'l');
	$query = query($sql);
	while ($result = fetch_assoc($query)) {
		$delivery_status_id_by_technical_code_array[$result['technical_code']] = $result['id'];
	}
	// Handling order
	// defined('IN_PEEL_ADMIN') paramètre $use_admin_rights : Si on est dans l'admin, le site associé à la commande n'est pas obligatoirement le site_id associé au nom de domaine, mais le site_id défini pour l'administrateur par session_admin_multisite
	$sql = "SELECT c.*, sp.technical_code AS statut_paiement, sl.technical_code AS statut_livraison
		FROM peel_commandes c
		LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
		LEFT JOIN peel_statut_livraison sl ON sl.id=c.id_statut_livraison AND " . get_filter_site_cond('statut_livraison', 'sl') . "
		WHERE c.id='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes', 'c') . "";
	$query = query($sql);
	// On vérifie si la commande existe déjà
	if ($commande = fetch_assoc($query)) {
		if (empty($GLOBALS['site_parameters']['payment_status_create_bill']) || in_array($statut_paiement_new, explode(',', $GLOBALS['site_parameters']['payment_status_create_bill']))) {
			// Quel que soit l'ancien statut, si la facture est souhaitée dans un statut qui doit avoir la génération de facture, alors on s'assure que le numéro et la date sont bien remplis 
			// get_bill_number crée un numéro de facture si il n'existe pas déjà à partir de $GLOBALS['site_parameters']['format_numero_facture']
			// Si il existe déjà, get_bill_number le transforme en remplaçant les tags si pas déjà remplacés auparavant, et si il n'y en a pas alors le numéro sera inchangé au final
			query("UPDATE peel_commandes
				SET numero = '" . nohtml_real_escape_string(get_bill_number($commande['numero'], $order_id, true)) . "'
				WHERE id = '" . intval($order_id) . "' AND " . get_filter_site_cond('commandes') . "");
			// f_datetime contient la date d'émission de facture, et est éditable en back office.
			// On la remplie si elle est vide
			query("UPDATE peel_commandes
				SET f_datetime = '" . date('Y-m-d H:i:s', time()) . "'
				WHERE id = '" . intval($order_id) . "' AND f_datetime LIKE '0000-00-00%' AND " . get_filter_site_cond('commandes') . "");
		}
		if (!empty($payment_technical_code)) {
			// Changement du moyen de paiement si celui ci est renseigné et que la commande est payé (statut 2 ou 3), même si elle était déjà en payé avant, ou si l'info paiement était vide avant
			$sql="UPDATE peel_commandes
				SET paiement='" . word_real_escape_string($payment_technical_code) . "'
				WHERE id='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes') . "";
			if(!in_array($statut_paiement_new, array('being_checked', 'completed'))){
				$sql.=" AND paiement=''";
			}
			query($sql);
		}
		if ($statut_paiement_new !== null && in_array($statut_paiement_new, array('being_checked', 'completed')) && !in_array($commande['statut_paiement'], array('being_checked','completed'))) {
			if (is_module_gift_checks_active()) {
				$output .= cree_cheque_cadeau($order_id);
			}
			if (!empty($GLOBALS['fonctionsfianet_sac']) && file_exists($GLOBALS['fonctionsfianet_sac'])) {
				require_once($GLOBALS['fonctionsfianet_sac']);
				// envoi des informations sur la commande au service d'analyse des commande FIANET
				send_fianet_sac($order_id);
			}
			// Mise à jour de la date de paiement si le statut est en réglé (et ne l'était pas avant)
			$sql_set_array[] = "a_timestamp='" . date('Y-m-d H:i:s', time()) . "'";
			if (check_if_module_active('annonces')) {
				// On gère les crédits d'annonces GOLD
				$sql = "SELECT pp.technical_code, pca.attributs_list, pca.reference
					FROM peel_produits pp
					INNER JOIN peel_commandes_articles pca ON pca.produit_id = pp.id AND " . get_filter_site_cond('commandes_articles', 'pca') . " 
					INNER JOIN peel_commandes pc ON pc.id = pca.commande_id AND " . get_filter_site_cond('commandes', 'pc') . "
					WHERE pc.id = " . intval($order_id) . " AND " . get_filter_site_cond('produits', 'pp') . "";
				$query_t = query($sql);
				while ($product_ordered_infos = fetch_assoc($query_t)) {
					if (substr($product_ordered_infos['technical_code'], 0, strlen('annonce_g_')) == 'annonce_g_') {
						// Format : annonce_g_12m_1c
						$insert_credit = 'INSERT INTO peel_gold_ads_to_users (user_id,order_id,gold_type)
							VALUES (' . intval($commande['id_utilisateur']) . ',' . intval($commande['id']) . ',"' . nohtml_real_escape_string($product_ordered_infos['technical_code']) . '")';
						query($insert_credit);
					} elseif (substr($product_ordered_infos['technical_code'], 0, strlen('ad_')) == 'ad_') {
						// On ajoute les attributs commandés à l'annonce
						$update_attributs = 'UPDATE peel_lot_vente
							SET attributs_list=CONCAT(attributs_list,IF(attributs_list="", "", "§"), "'.nohtml_real_escape_string($product_ordered_infos['attributs_list']).'")
							WHERE ref ="' . intval($product_ordered_infos['reference']) . '"';
						query($update_attributs);
					}
				}
			}
			if (!empty($GLOBALS['activate_platinum_auto']) && check_if_module_active('abonnement')) {
				// On gère les crédits d'abonnement platinum
				$sql = 'SELECT technical_code
					FROM peel_produits pp
					INNER JOIN peel_commandes_articles pca ON pca.produit_id = pp.id AND ' . get_filter_site_cond('commandes_articles', 'pca') . ' 
					INNER JOIN peel_commandes pc ON pc.id = pca.commande_id AND ' . get_filter_site_cond('commandes', 'pc') . '
					WHERE pc.id = ' . intval($order_id). " AND " . get_filter_site_cond('produits', 'pp') . "";
				$query_t = query($sql);
				while ($product_ordered_infos = fetch_assoc($query_t)) {
					if (substr($product_ordered_infos['technical_code'], 0, strlen('forfait_p_')) == 'forfait_p_') {
						// Format : forfait_p_3
						$n_days = 30 * substr($product_ordered_infos['technical_code'], strlen('forfait_p_'));
						$update_credit = 'UPDATE peel_utilisateurs
							SET platinum_status ="YES", platinum_activation_date ="' . date('Y-m-d H:i:s') . '", platinum_until =GREATEST(' . time() . ',platinum_until)+' . (3600 * 24 * $n_days).'
							WHERE id_utilisateur ="' . intval($commande['id_utilisateur']) . '" AND ' . get_filter_site_cond('utilisateurs') . '';
						query($update_credit);
					}
				}
			}
		}
		if (!empty($payment_status_id_by_technical_code_array[$statut_paiement_new])) {
			$sql_set_array[] = "id_statut_paiement='" . intval($payment_status_id_by_technical_code_array[$statut_paiement_new]) . "'";
		}
		if (!empty($delivery_status_id_by_technical_code_array[$statut_livraison_new])) {
			$sql_set_array[] = "id_statut_livraison='" . intval($delivery_status_id_by_technical_code_array[$statut_livraison_new]) . "'";
		}
		if ($delivery_tracking !==null) {
			// Attention, il faut pouvoir forcer la mise à "" => ne pas faire de test !empty
			$sql_set_array[] = "delivery_tracking='" . nohtml_real_escape_string($delivery_tracking) . "'";
		} elseif(!empty($commande['delivery_tracking'])) {
			$delivery_tracking = $commande['delivery_tracking'];
		}
		if (!empty($sql_set_array) && ($allow_update_paid_orders || !in_array($commande['statut_paiement'], array('being_checked', 'completed')))) {
			query('UPDATE peel_commandes
				SET ' . implode(', ', $sql_set_array) . '
				WHERE id="' . intval($order_id) . '" AND ' . get_filter_site_cond('commandes'));
		}
		if ($statut_paiement_new !== null && $commande['statut_paiement'] != $statut_paiement_new) {
			// On vérifie le statut paiement avant la mise à jour de la base avec celui du formulaire. Ils doivent être différents,
			// afin d'éviter un doublon d'incrémentation des stocks lorsque l'utilisateur choisit l'annulation de livraison.
			if (in_array($statut_paiement_new, array('cancelled', 'refunded')) && !in_array($commande['statut_paiement'], array('cancelled', 'refunded'))) {
				// La commande passe en annulé ou remboursé
				if ($statut_paiement_new === null || $statut_paiement_new === '') {
					// Changement aussi du statut de livraison en annulé s'il n'était pas déjà en statut livré
					query("UPDATE peel_commandes
						SET id_statut_livraison=" . intval($delivery_status_id_by_technical_code_array['cancelled']) . "
						WHERE id='" . intval($order_id) . "' AND id_statut_livraison!=" . intval($delivery_status_id_by_technical_code_array['dispatched']) . " AND " . get_filter_site_cond('commandes') . "");
				}
				// Dans le cas particulier d'une commande contenant des produits cadeaux commandés avec des points puis annulée
				// On devrait gérer ici le fait de recréditer les points de la commande de cadeaux, mais ça nécessite de stocker en BDD les informations de points dépensés des commandes
				// => ça nécessite actuellement une intervention manuelle par un administrateur pour recréditer le compte utilisateur
			}
		}
		if(check_if_module_active('stock_advanced') && $statut_paiement_new !== null) {
			// Les stocks sont gérés sur le site, et un statut de paiement est spécifié => Il faut éventuellement modifier les stocks des produits de la commande. 
			// (Si statut_paiement est vide, il n'est pas nécessaire de faire le calcul).
			$payment_status_decrement_stock_array = explode(',', $GLOBALS['site_parameters']['payment_status_decrement_stock']);
			// $payment_status_decrement_stock_array vaut : pending,being_checked,completed   ou   being_checked,completed
			$want_decrement = in_array($statut_paiement_new, $payment_status_decrement_stock_array);
			$ex_decrement = in_array($commande['statut_paiement'], $payment_status_decrement_stock_array);
			// Quand on appelle cette fonction à partir de create_or_update_order
			// on a remis à 0 la prise en compte des stocks des produits, puisqu'on est susceptible d'avoir changé les produits de la commande
			// donc on arrive ici avec $no_stock_decrement_already_done=true
			// Dans les autres cas, $no_stock_decrement_already_done=false
			$already_decrement = (!$no_stock_decrement_already_done && $ex_decrement);
			if(($want_decrement && !$already_decrement) || (!$want_decrement && $already_decrement)){
				$product_infos_array = get_product_infos_array_in_order($order_id);
				if (!empty($product_infos_array)) {
					foreach ($product_infos_array as $this_ordered_product) {
						if ($this_ordered_product['etat_stock'] == 1) {
							if ($want_decrement && !$already_decrement) {
								// Décrémentation des stocks (par exemple en cas de commande qui était en statut paiement annulé et qui finalement ne doit pas être annulée)
								// si le statut passe dans un statut où on doit décrémenter le stock, alors qu'il ne l'était pas avant
								// $this_order_stock contient le montant à réapprovisionner
								$this_order_stock = decremente_stock($this_ordered_product['produit_id'], $this_ordered_product['couleur_id'], $this_ordered_product['taille_id'], $this_ordered_product['quantite']);
							}elseif (!$want_decrement && $already_decrement) {
								// on réincrémente de quantite-order_stock
								incremente_stock($this_ordered_product['quantite']-$this_ordered_product['order_stock'], $this_ordered_product['produit_id'], $this_ordered_product['couleur_id'], $this_ordered_product['taille_id']);
								// On annule la demande de réapprovisionnement
								$this_order_stock = 0;
							}
							if(isset($this_order_stock)){
								query("UPDATE peel_commandes_articles
									SET order_stock='".intval($this_order_stock)."'
									WHERE id='" . intval($this_ordered_product['id']) . "' AND " . get_filter_site_cond('commandes_articles'));
								unset($this_order_stock);
							}
						}
					}
				}
			}
		}
		if ($statut_livraison_new == 'dispatched' && $commande['statut_livraison'] != $statut_livraison_new && !empty($GLOBALS['site_parameters']['mode_transport'])) {
			// Le statut de livraison passe à expédié pour la première fois
			// => on envoie l'email d'expédition (avec ou sans les infos de delivery_tracking qui peut être vide)
			send_avis_expedition($order_id, $delivery_tracking);
			// Création de la date d'expédition pour la commande. Cette date est administrable par l'administrateur en back office.
			query("UPDATE peel_commandes
				SET e_datetime = '" . date('Y-m-d H:i:s', time()) . "'
				WHERE id = '" . intval($order_id) . "' AND " . get_filter_site_cond('commandes') . "");
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_DELIVERY_EMAIL_SENT'], $commande['email'])))->fetch();
		}
	}
	return $output;
}

/**
 * Récupère les informations du tableau $frm pour les mettre de manière standardisée dans $_SESSION['session_commande']
 *
 * @param array $frm Array with all fields data
 * @return
 */
function put_session_commande(&$frm)
{
	$_SESSION['session_commande']['societe1'] = vb($frm['societe1']);
	$_SESSION['session_commande']['client1'] = vb($frm['client1']);
	$_SESSION['session_commande']['nom1'] = vb($frm['nom1']);
	$_SESSION['session_commande']['prenom1'] = vb($frm['prenom1']);
	$_SESSION['session_commande']['email1'] = vb($frm['email1']);
	$_SESSION['session_commande']['contact1'] = vb($frm['contact1']);
	$_SESSION['session_commande']['adresse1'] = vb($frm['adresse1']);
	$_SESSION['session_commande']['code_postal1'] = vb($frm['code_postal1']);
	$_SESSION['session_commande']['ville1'] = vb($frm['ville1']);
	$_SESSION['session_commande']['pays1'] = vb($frm['pays1']);

	if (!empty($GLOBALS['site_parameters']['mode_transport']) && is_delivery_address_necessary_for_delivery_type(vn($_SESSION['session_caddie']->typeId))) {
		if (empty($_SESSION['session_commande']['is_socolissimo_order'])) {
			// Quand on vient de SoColissimo, on ne change pas les variables de livraison
			$_SESSION['session_commande']['societe2'] = vb($frm['societe2']);
			$_SESSION['session_commande']['nom2'] = (empty($frm['nom2'])? $frm['nom1']:$frm['nom2']);
			$_SESSION['session_commande']['prenom2'] = (empty($frm['prenom2'])? $frm['prenom1']:$frm['prenom2']);
			$_SESSION['session_commande']['contact2'] = (empty($frm['contact2'])? $frm['contact1']:$frm['contact2']);
			$_SESSION['session_commande']['email2'] = (empty($frm['email2'])? $frm['email1']:$frm['email2']);
			$_SESSION['session_commande']['adresse2'] = (empty($frm['adresse2'])? $frm['adresse1']:$frm['adresse2']);
			$_SESSION['session_commande']['code_postal2'] = (empty($frm['code_postal2'])? $frm['code_postal1']:$frm['code_postal2']);
			$_SESSION['session_commande']['ville2'] = (empty($frm['ville2'])? $frm['ville1']:$frm['ville2']);
			$_SESSION['session_commande']['pays2'] = (empty($frm['pays2'])? $frm['pays1']:$frm['pays2']);
		}
	}

	if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
		// Paramètre lié à la fonction get_specific_field_infos.
		foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
			if (isset($frm[$this_field])) {
				// On a ajouter dans la table utilisateur un champ qui concerne l'adresse de facturation => Il faut préremplir les champs du formulaire d'adresse de facturation avec ces infos.
				$_SESSION['session_commande'][$this_field] = $frm[$this_field];
			}
		}
	}
	$_SESSION['session_commande']['commande_interne'] = vb($frm['commande_interne']);
	$_SESSION['session_commande']['commentaires'] = vb($frm['commentaires']);
	$_SESSION['session_commande']['payment_technical_code'] = vb($frm['payment_technical_code']);
	if ($_SESSION['session_commande']['payment_technical_code'] == 'moneybookers') {
		$_SESSION['session_commande']['moneybookers_payment_methods'] = vb($frm['moneybookers_payment_methods']);
	} else {
		$_SESSION['session_commande']['moneybookers_payment_methods'] = '';
	}
	$_SESSION['session_commande']['cgv'] = vn($frm['cgv']);
}

/**
 * Crée ou modifie une commande en base de données, ainsi que les produits commandés
 *
 * @param array $order_infos
 * @param array $articles_array
 * @return
 */
function create_or_update_order(&$order_infos, &$articles_array)
{
	$output = '';
	// "nom du champ dans la BDD" => "nom du champ dans $order_infos"
	$name_compatibility_array = array(
		"paiement" => "payment_technical_code"
		, "zone_tva" => "apply_vat"
		, "zone_franco" => "zoneFranco"
		, "produit_id" => "product_id"
		, "couleur_id" => "couleurId"
		, "taille_id" => "tailleId"
		, "nom_produit" => "product_name"
		, "option" => "prix_option"
		, "option_ht" => "prix_option_ht");
	
	// Si touts les produits ont été supprimés de la commande, on initialise le tableau
	if (empty($articles_array)) {
		$articles_array = array();
	}
	// Verifie si id statut existe
	if (!isset($order_infos['statut_paiement'])) {
		$order_infos['statut_paiement'] = 'pending';
	}
	if (!isset($order_infos['statut_livraison'])) {
		$order_infos['statut_livraison'] = 'discussed';
	}
	
	foreach($name_compatibility_array as $key => $value) {
		// On rend compatible des entrées du tableau order_infos
		if (isset($order_infos[$key])) {
			// Nécessite une conversion du nom de l'index.
			$order_infos[$value] = $order_infos[$key];
			// On ne laisse pas d'index inutile, ça complique la lecture lors du débogage;
			unset($order_infos[$key]);
		}
	}

	$adresses_fields_array = array('prenom', 'nom', 'adresse', 'code_postal', 'ville', 'pays', 'email', 'contact');
	if(!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
		$specific_fields_titles = $GLOBALS['site_parameters']['order_specific_field_titles'];
		$specific_field_types = $GLOBALS['site_parameters']['order_specific_field_types'];
	}
	if (!empty($specific_fields_titles)) {
		// Paramètre lié à la fonction get_specific_field_infos.
		// récupération des champs de la BDD, pour éviter les erreurs de mise à jour du à une erreur d'administration de user_specific_field_titles, et ne pas mettre les champs type separator dans la requête SQL, et tout autre intru qui ferais échoué la requete.
		$this_table_fields_names = get_table_field_names('peel_commandes');
		foreach($specific_fields_titles as $this_field => $this_title) {
			if ((String::substr($this_field,-5) == '_ship' ||  String::substr($this_field,-5) == '_bill') && !in_array(String::substr($this_field, 0, -5), $adresses_fields_array)) {
				$adresses_fields_array[] = String::substr($this_field,0,-5);
			}
			if (!in_array($this_field, $this_table_fields_names)) {
				// Champ pas présent en BDD, on ne l'ajoute pas à la requête SQL.
				continue;
			}
			if ($specific_field_types[$this_field] == 'datepicker') {
				$order_infos[$this_field] = get_mysql_date_from_user_input($order_infos[$this_field]);
			}
			if (isset($order_infos[$this_field])) {
				if (is_array($order_infos[$this_field])) {
					// Si $order_infos[$this_field] est un tableau, il faut le convertir en chaine de caractères pour le stockage en BDD
					$order_infos[$this_field] = implode(',', $order_infos[$this_field]);
				}
				$order_specific_field[] = word_real_escape_string($this_field) . " = '" . nohtml_real_escape_string($order_infos[$this_field]) ."'";
			}
		}
	}
	foreach($adresses_fields_array as $this_item) {
		// En complément de name_compatibility_array
		if ($this_item == 'zip') {
			$this_field = 'code_postal';
		} elseif($this_item == 'telephone') {
			$this_field = 'contact';
		} else {
			$this_field = $this_item;
		}
		if (!empty($order_infos[$this_item . '_bill'])) {
			$order_infos[$this_field . '1'] = $order_infos[$this_item . '_bill'];
		}
		if (!empty($order_infos[$this_item . '_ship'])) {
			$order_infos[$this_field . '2'] = $order_infos[$this_item . '_ship'];
		}
	}
	// On complète les données si nécessaire
	if (!empty($GLOBALS['site_parameters']['mode_transport']) && (empty($order_infos['typeId']) || is_delivery_address_necessary_for_delivery_type($order_infos['typeId']))) {
		foreach($adresses_fields_array as $this_item) {
			if (empty($order_infos[$this_item . '2']) && isset($order_infos[$this_item . '1'])) {
				$order_infos[$this_item . '2'] = $order_infos[$this_item . '1'];
			}
		}
	}
	// Avant de mettre à jour la commande, on récupère l'ancienne valeur du statut de paiement
	if (!empty($order_infos['id'])) {
		$statut_q = query('SELECT c.id_statut_paiement, c.total_points, c.points_etat, c.o_timestamp, sp.technical_code AS statut_paiement
			FROM peel_commandes c
			LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND ' . get_filter_site_cond('statut_paiement', 'sp') . '
			WHERE c.id=' . intval($order_infos['id']) . ' AND ' . get_filter_site_cond('commandes', 'c') . '');
		$order_infos_ex = fetch_assoc($statut_q);
	} else {
		$order_infos_ex = null;
	}
	if (empty($order_infos['devise'])) {
		// Par exemple si !is_devises_module_active() : on prend la devise de la boutique
		$order_infos['devise'] = $GLOBALS['site_parameters']['devise'];
	}
	if (empty($order_infos['lang'])) {
		$order_infos['lang'] = $_SESSION['session_langue'];
	}
	$old_id_utilisateur = $order_infos['id_utilisateur'];
	if(empty($order_infos['email'])) {
		$order_infos['email'] = vb($order_infos['email1']);
	}
	if(!isset($order_infos['site_id'])) {
		// Si pas de site_id précisé (problème de paramétrage), alors la valeur du champ site_id sera celle du site en cours de consultation
		$order_infos['site_id'] = $GLOBALS['site_id'];
	}
	$set_sql = "email = '" . nohtml_real_escape_string(vb($order_infos['email'])) . "'
		";
	if(!empty($order_infos['email1'])){
		// Si on change l'email associé à une commande, et qu'il correspond à un utilisateur en BDD, on ajuste l'id_utilisateur
		// Sinon, on laisse l'id_utilisateur comme il était (0 si la commande avait préalablement été créée sans association à un utilisateur, ou l'id d'un compte quelconque dont l'email n'est peut-être pas à jour)
		$searched_id_utilisateur = get_user_id_from_email($order_infos['email']);
		if(!empty($searched_id_utilisateur)) {
			$order_infos['id_utilisateur'] = $searched_id_utilisateur;
		}
	}
	if (isset($order_infos['commande_interne'])) {
		$set_sql .= ", commande_interne = '" . nohtml_real_escape_string($order_infos['commande_interne']) . "'";
	}
	if (isset($order_infos['id_utilisateur'])) {
		$set_sql .= ", id_utilisateur = '" . nohtml_real_escape_string($order_infos['id_utilisateur']) . "'";
	}
	if (isset($order_infos['commentaires'])) {
		$set_sql .= ", commentaires = '" . nohtml_real_escape_string($order_infos['commentaires']) . "'";
	}
	if (isset($order_infos['commentaires_admin'])) {
		$set_sql .= ", commentaires_admin = '" . nohtml_real_escape_string($order_infos['commentaires_admin']) . "'";
	}
	$set_sql .= ", montant = '" . nohtml_real_escape_string($order_infos['montant']) . "'
		, montant_ht = '" . nohtml_real_escape_string($order_infos['montant_ht']) . "'";
	if (isset($order_infos['total_option'])) {
		$set_sql .= ", total_option = '" . nohtml_real_escape_string(vb($order_infos['total_option'])) . "'";
	}
	if (isset($order_infos['a_timestamp'])) {
		$set_sql .= ", a_timestamp = '" . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($order_infos['a_timestamp']))) . "'";
	}
	if (isset($order_infos['e_datetime'])) {
		$set_sql .= ", e_datetime = '" . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($order_infos['e_datetime']))) . "'";
	}
	if (isset($order_infos['f_datetime'])) {
		$set_sql .= ", f_datetime = '" . nohtml_real_escape_string(get_mysql_date_from_user_input(vb($order_infos['f_datetime']))) . "'";
	}
	if (isset($order_infos['tva_total_option'])) {
		$set_sql .= ", tva_total_option = '" . nohtml_real_escape_string(vb($order_infos['tva_total_option'])) . "'";
	}
	$set_sql .= ", total_produit = '" . nohtml_real_escape_string($order_infos['total_produit']) . "'
		, total_produit_ht = '" . nohtml_real_escape_string($order_infos['total_produit_ht']) . "'
		, tva_total_produit = '" . nohtml_real_escape_string($order_infos['tva_total_produit']) . "'";
	if (isset($order_infos['code_promo'])) {
		$set_sql .= ", code_promo = '" . nohtml_real_escape_string(vb($order_infos['code_promo'])) . "'";
	}
	if (isset($order_infos['percent_remise_user'])) {
		$set_sql .= ", percent_remise_user = '" . nohtml_real_escape_string(vb($order_infos['percent_remise_user'])) . "'";
	}
	if (isset($order_infos['percent_code_promo'])) {
		$set_sql .= ", percent_code_promo = '" . nohtml_real_escape_string(vb($order_infos['percent_code_promo'])) . "'";
	}
	if (isset($order_infos['valeur_code_promo'])) {
		$set_sql .= ", valeur_code_promo = '" . nohtml_real_escape_string(vb($order_infos['valeur_code_promo'])) . "'";
	}
	if (isset($order_infos['total_remise'])) {
		$set_sql .= ", total_remise = '" . nohtml_real_escape_string(vb($order_infos['total_remise'])) . "'";
	}
	if (isset($order_infos['total_remise_ht'])) {
		$set_sql .= ", total_remise_ht = '" . nohtml_real_escape_string(vb($order_infos['total_remise_ht'])) . "'";
	}
	if (isset($order_infos['tva_total_remise'])) {
		$set_sql .= ", tva_total_remise = '" . nohtml_real_escape_string(vb($order_infos['tva_total_remise'])) . "'";
	}
	$set_sql .= ", total_tva = '" . nohtml_real_escape_string($order_infos['total_tva']) . "'";
	if (isset($order_infos['transport'])) {
		$set_sql .= ", transport = '" . nohtml_real_escape_string(vb($order_infos['transport'])) . "'";
	}
	$set_sql .= ", cout_transport = '" . nohtml_real_escape_string(vn($order_infos['cout_transport'])) . "'
		, cout_transport_ht = '" . nohtml_real_escape_string(vn($order_infos['cout_transport_ht'])) . "'
		, tva_cout_transport = '" . nohtml_real_escape_string(vn($order_infos['tva_cout_transport'])) . "'
		, lang = '" . nohtml_real_escape_string($order_infos['lang']) . "'";
	if (isset($order_infos['total_points'])) {
		$set_sql .= ", total_points = '" . nohtml_real_escape_string(vb($order_infos['total_points'])) . "'";
	}
	if (isset($order_infos['total_poids'])) {
		$set_sql .= ", total_poids = '" . nohtml_real_escape_string(vb($order_infos['total_poids'])) . "'";
	}
	if (isset($order_infos['affilie'])) {
		$set_sql .= ", affilie = '" . nohtml_real_escape_string(vb($order_infos['affilie'])) . "'";
	}
	if (isset($order_infos['montant_affilie'])) {
		$set_sql .= ", montant_affilie = '" . nohtml_real_escape_string(vb($order_infos['montant_affilie'])) . "'";
	}
	if (isset($order_infos['statut_affilie'])) {
		$set_sql .= ", statut_affilie = '" . nohtml_real_escape_string(vb($order_infos['statut_affilie'])) . "'";
	}
	if (isset($order_infos['id_affilie'])) {
		$set_sql .= ", id_affilie = '" . nohtml_real_escape_string(vb($order_infos['id_affilie'])) . "'";
	}
	$set_sql .= "
		, total_ecotaxe_ttc = '" . nohtml_real_escape_string(vb($order_infos['total_ecotaxe_ttc'])) . "'
		, total_ecotaxe_ht = '" . nohtml_real_escape_string(vb($order_infos['total_ecotaxe_ht'])) . "'
		, tva_total_ecotaxe = '" . nohtml_real_escape_string(vb($order_infos['tva_total_ecotaxe'])) . "'";
	if (isset($order_infos['avoir'])) {
		$set_sql .= ", avoir = '" . nohtml_real_escape_string(vb($order_infos['avoir'])) . "'";
	}
	$set_sql .= ", paiement = '" . nohtml_real_escape_string(vb($order_infos['payment_technical_code'])) . "'
		, tarif_paiement = '" . nohtml_real_escape_string(vb($order_infos['tarif_paiement'])) . "'
		, tarif_paiement_ht = '" . nohtml_real_escape_string(vb($order_infos['tarif_paiement_ht'])) . "'
		, tva_tarif_paiement = '" . nohtml_real_escape_string(vb($order_infos['tva_tarif_paiement'])) . "'
		, prenom_bill = '" . nohtml_real_escape_string(vb($order_infos['prenom1'])) . "'
		, nom_bill = '" . nohtml_real_escape_string(vb($order_infos['nom1'])) . "'
		, societe_bill = '" . nohtml_real_escape_string(vb($order_infos['societe1'])) . "'
		, adresse_bill = '" . nohtml_real_escape_string(vb($order_infos['adresse1'])) . "'
		, zip_bill = '" . nohtml_real_escape_string(vb($order_infos['code_postal1'])) . "'
		, ville_bill = '" . nohtml_real_escape_string(vb($order_infos['ville1'])) . "'
		, pays_bill = '" . nohtml_real_escape_string(vb($order_infos['pays1'])) . "'
		, email_bill = '" . nohtml_real_escape_string(vb($order_infos['email1'])) . "'
		, telephone_bill = '" . nohtml_real_escape_string(vb($order_infos['contact1'])) . "'
		, prenom_ship = '" . nohtml_real_escape_string(vb($order_infos['prenom2'])) . "'";
	if (isset($order_infos['nom2'])) {
		$set_sql .= ", nom_ship = '" . nohtml_real_escape_string(vb($order_infos['nom2'])) . "'";
	}
	if (isset($order_infos['societe2'])) {
		$set_sql .= ", societe_ship = '" . nohtml_real_escape_string(vb($order_infos['societe2'])) . "'";
	}
	if (isset($order_infos['adresse2'])) {
		$set_sql .= ", adresse_ship = '" . nohtml_real_escape_string(vb($order_infos['adresse2'])) . "'";
	}
	if (isset($order_infos['code_postal2'])) {
		$set_sql .= ", zip_ship = '" . nohtml_real_escape_string(vb($order_infos['code_postal2'])) . "'";
	}
	if (isset($order_infos['ville2'])) {
		$set_sql .= ", ville_ship = '" . nohtml_real_escape_string(vb($order_infos['ville2'])) . "'";
	}
	if (isset($order_infos['pays2'])) {
		$set_sql .= ", pays_ship = '" . nohtml_real_escape_string(vb($order_infos['pays2'])) . "'";
	}
	if (isset($order_infos['email2'])) {
		$set_sql .= ", email_ship = '" . nohtml_real_escape_string(vb($order_infos['email2'])) . "'";
	}
	if (isset($order_infos['contact2'])) {
		$set_sql .= ", telephone_ship = '" . nohtml_real_escape_string(vb($order_infos['contact2'])) . "'";
	}
	if (isset($order_infos['id_parrain'])) {
		$set_sql .= ", id_parrain = '" . nohtml_real_escape_string(vn($order_infos['id_parrain'])) . "'";
	}
	if (isset($order_infos['parrain'])) {
		$set_sql .= ", parrain = '" . nohtml_real_escape_string(vb($order_infos['parrain'])) . "'";
	}
	$set_sql .= "
		, currency_rate = '" . nohtml_real_escape_string($order_infos['currency_rate']) . "'
		, zone_tva = '" . nohtml_real_escape_string(!empty($order_infos['apply_vat'])?1:0) . "'";
	if (isset($order_infos['zoneFranco'])) {
		$set_sql .= ", zone_franco = '" . nohtml_real_escape_string($order_infos['zoneFranco']) . "'";
	}
	$set_sql .= "
		, small_order_overcost_amount = '" . nohtml_real_escape_string(vb($order_infos['small_order_overcost_amount'])) . "'
		, tva_small_order_overcost = '" . nohtml_real_escape_string(vb($order_infos['tva_small_order_overcost'])) . "'
		, pays = '" . nohtml_real_escape_string($order_infos['pays']) . "'
		, zone = '" . nohtml_real_escape_string($order_infos['zoneId']) . "'
		, type = '" . nohtml_real_escape_string(vb($order_infos['type'])) . "'
		, typeId = '" . intval(vn($order_infos['typeId'])) . "'
		, delivery_orderid = '" . nohtml_real_escape_string(vb($_SESSION['session_caddie']->delivery_orderid)) . "'
		, devise = '" . nohtml_real_escape_string($order_infos['devise']) . "'";
	if (isset($order_infos['moneybookers_payment_methods'])) {
		$set_sql .= ", moneybookers_payment_methods = '" . real_escape_string(vb($order_infos['moneybookers_payment_methods'])) . "'";
	}
	$set_sql .= ", delivery_infos = '" . real_escape_string(vb($order_infos['delivery_infos'])) . "'
		, delivery_locationid = '" . nohtml_real_escape_string(vb($order_infos['delivery_locationid'])) . "'
		, site_id = '" . intval(vn($order_infos['site_id'])) . "'";
	if (is_tnt_module_active()) {
		if(!empty($_SESSION['session_commande']['xETTCode'])) {
			$set_sql .= ", xETTCode = '" . word_real_escape_string(vb($_SESSION['session_commande']['xETTCode'])) . "'";		
		}
		$set_sql .= "
		, expedition_date = '" . nohtml_real_escape_string(vb($GLOBALS['web_service_tnt']->shippingDate)) . "'
		, shipping_date = '" . nohtml_real_escape_string(vb($GLOBALS['web_service_tnt']->shippingDate)) . "'";
	}
	if (!empty($order_specific_field)) {
		$set_sql .= ',' . implode(',', $order_specific_field);
	}
	if (!empty($order_infos['commandeid'])) {
		// On met à jour la commande
		$sql = "UPDATE peel_commandes
			SET " . $set_sql . "
			WHERE id_utilisateur='" . intval($old_id_utilisateur) . "' AND id='" . intval($order_infos['commandeid']) . "' AND " . get_filter_site_cond('commandes', null, false, $order_infos['site_id'], true) . "";
		$order_infos['o_timestamp'] = $order_infos_ex['o_timestamp'];
	} else {
		// On crée la commande - pour cela, on définit le code facture, et l'id utilisateur
		$code_facture = vb($order_infos['code_facture']);
		while (empty($code_facture) || (isset($qid_commande) && num_rows($qid_commande))) {
			// On s'assure que le code facture généré n'existe pas encore
			$code_facture = MDP(10);
			$qid_commande = query("SELECT *
				FROM peel_commandes
				WHERE code_facture = '" . nohtml_real_escape_string($code_facture) . "' AND " . get_filter_site_cond('commandes', null, false, $order_infos['site_id'], true) . "");
		}
		// Attention : on ne doit pas mettre de "a_timestamp" ici, car ça dépend de si la commande est à passer en payer ou non => c'est géré dans update_order_payment_status
		$sql = "INSERT INTO peel_commandes
			SET " . $set_sql . "
			, order_id = '" . intval(get_order_id()) . "'
			, code_facture = '" . nohtml_real_escape_string($code_facture) . "'
			, o_timestamp = '" . date('Y-m-d H:i:s', time()) . "' ";
		$order_infos['o_timestamp'] = date('Y-m-d H:i:s', time());
	}
	query($sql);
	if (empty($order_infos['commandeid'])) {
		$order_infos['commandeid'] = insert_id();
	}
	$order_id = $order_infos['commandeid'];
	if(!empty($order_infos['numero']) && $order_infos['numero']!=vb($GLOBALS['site_parameters']['format_numero_facture'])) {
		// Si un numéro est spécifié par l'admin (donc n'est pas le format par défaut), alors on le met ici, sinon la création de numéro sera gérée dans update_order_payment_status car dépendant du status
		$numero = get_bill_number($order_infos['numero'], $order_id, false);
	} else {
		$numero = '';
	}
	// Qu'il soit vide ou non, on met à jour la colonne numero
	query("UPDATE peel_commandes
		SET numero = '" . nohtml_real_escape_string($numero) . "'
		WHERE id = '" . intval($order_id) . "' AND " . get_filter_site_cond('commandes', null, false, $order_infos['site_id'], true) . "");
	// On va enregistrer l'ensemble des produits commandés pour la commande $order_id
	// Tout d'abord on supprime ce qui existait en BDD pour cette commande
	if (!empty($order_infos_ex)) {
		if(check_if_module_active('stock_advanced')) {
			// On réincrémente les stocks si il y avait des articles précédemment stockés, puisqu'on fait comme si les produits commandés étaient tous annulés
			// On gèrera plus tard les stocks dans update_order_payment_status() appelé à la fin de cette fonction
			$product_infos_array = get_product_infos_array_in_order($order_id);
			foreach ($product_infos_array as $this_ordered_product) {
				// On réincrémente le stock uniquement pour les articles onstock=1, c'est-à-dire dont le stock est géré
				if ($this_ordered_product['etat_stock'] == 1 && in_array($order_infos_ex['statut_paiement'], explode(',', $GLOBALS['site_parameters']['payment_status_decrement_stock'])) ) {
					incremente_stock($this_ordered_product['quantite'] - $this_ordered_product['order_stock'], $this_ordered_product['produit_id'], $this_ordered_product['couleur_id'], $this_ordered_product['taille_id']);
					// On initialise les demande de réassort de stock lié à ce produit commandé en supprimant ensuite les lignes de peel_commandes_articles, donc pas besoin de faire :
					// query("UPDATE peel_commandes_articles
					// 	SET order_stock='0'
					// 	WHERE id='" . intval($this_ordered_product['id']) . "' AND " . get_filter_site_cond('commandes_articles') . "");
				}
			}
		}
		if(is_gifts_module_active()) {
			// On retire les points attribués préalablement, pour redonner les nouveaux points ensuite
			update_points($order_infos['total_points'], vn($order_infos['points_etat']), $order_id, null);
		}
	}
	query("DELETE FROM peel_commandes_articles
		WHERE commande_id='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes_articles'));
	// On ajoute les articles à la table commandes_articles
	foreach ($articles_array as $article_infos) {
		// On rend compatible des entrées du tableau 
		foreach($name_compatibility_array as $key => $value) {
		// On rend compatible des entrées du tableau article_infos
			if (isset($article_infos[$key])) {
				// Nécessite une conversion du nom de l'index.
				$article_infos[$value] = $article_infos[$key];
				// On ne laisse pas d'index inutile, ça complique la lecture lors du débogage;
				unset($article_infos[$key]);
			}
		}
		// On construit un objet product à partir des informations de $article_infos.
		// On l'utilise pour des informations diverses, mais surtout pas pour les prix par exemple, qui doivent être ceux imposés par $article_infos
		// L'objet produit n'a pas besoin d'être initialisé avec toute les informations de $article_infos car on ne l'utilise que pour les parties sur lesquelles on n'a pas d'information dans $article_infos
		$product_infos = null;
		$product_object = new Product($article_infos['product_id'], $product_infos, false, null, true, !is_micro_entreprise_module_active());
		// On n'a pas à indiquer si l'utilisateur est un revendeur ou non car on ne va pas utiliser les prix des configurations ci-après, on utilisera $article_infos
		$product_object->set_configuration($article_infos['couleurId'], $article_infos['tailleId'], vb($article_infos['id_attribut']), false, true);
		// Dans le cas d'une création de commande, l'id attribut est renseigné dans la session caddie => On peut configurer les attributs de ce produit avec la classe Product.
		// En revanche, dans le cas d'une modification d'une commande existante, l'id de l'attribut n'est pas disponible, car elle n'est pas sauvegardée lors du passage de la commande.
		// Le nom de l'attribut est stocké dans la table peel_commandes_articles pour que cette information n'évolue pas dans le temps.
		if (!empty($article_infos['id_attribut'])) {
			$attribut = $product_object->configuration_attributs_description;
		} elseif (!empty($article_infos['nom_attribut'])) {
			$attribut = $article_infos['nom_attribut'];
		} else {
			$attribut = '';
		}
		$statut_envoi = ($product_object->on_download == 1) ? "En attente" : "";
		// Il faut que la couleur et la taille soient gardés tels quels lorsqu'une commande est éditée
		// et que la couleur et la taille ne sont plus disponibles => get_color et get_size ne pourraient pas les donner
		// => dans ce cas ces informations sont dans $article_infos['couleur'] et $article_infos['taille']
		if (empty($article_infos['couleur'])) {
			$article_infos['couleur'] = $product_object->get_color();
		}
		if (empty($article_infos['taille'])) {
			$article_infos['taille'] = $product_object->get_size();
		}
		if (!empty($article_infos['nom_produit'])) {
			$article_infos['product_name'] = $article_infos['nom_produit'];
		}
		if (empty($article_infos['product_name'])) {
			$article_infos['product_name'] = $product_object->name;
		}
		if (empty($article_infos['reference'])) {
			$article_infos['reference'] = $product_object->reference;
		}
		$sql = "INSERT INTO peel_commandes_articles SET
			site_id = '" . intval($order_infos['site_id']) . "'
			, commande_id = '" . intval($order_id) . "'
			, produit_id = '" . intval($product_object->id) . "'
			, categorie_id = '" . intval($product_object->categorie_id) . "'";
		if (isset($article_infos['reference'])) {
			$sql .= ", reference = '" . nohtml_real_escape_string($article_infos['reference']) . "'";
		}
		$sql .= "
			, nom_produit = '" . nohtml_real_escape_string($article_infos['product_name']) . "'";
		if (isset($article_infos['couleur'])) {
			$sql .= ", couleur = '" . nohtml_real_escape_string($article_infos['couleur']) . "'";
		}
		if (isset($article_infos['taille'])) {
			$sql .= ", taille = '" . nohtml_real_escape_string($article_infos['taille']) . "'";
		}
		if (isset($article_infos['couleurId'])) {
			$sql .= ", couleur_id = '" . nohtml_real_escape_string($article_infos['couleurId']) . "'";
		}
		if (isset($article_infos['tailleId'])) {
			$sql .= ", taille_id = '" . nohtml_real_escape_string($article_infos['tailleId']) . "'";
		}
		$sql .= "
			, prix_cat = '" . nohtml_real_escape_string(vb($article_infos['prix_cat'])) . "'
			, prix_cat_ht = '" . nohtml_real_escape_string(vb($article_infos['prix_cat_ht'])) . "'
			, prix = '" . nohtml_real_escape_string($article_infos['prix']) . "'
			, prix_ht = '" . nohtml_real_escape_string($article_infos['prix_ht']) . "'
			, prix_achat_ht = '" . nohtml_real_escape_string($product_object->get_supplier_price(false)) . "'";
		if (isset($article_infos['percent_remise_produit'])) {
			$sql .= ", percent_remise_produit = '" . nohtml_real_escape_string(vn($article_infos['percent_remise_produit'])) . "'";
		}
		if (isset($article_infos['remise'])) {
			$sql .= ", remise = '" . nohtml_real_escape_string(vn($article_infos['remise'])) . "'";
		}
		if (isset($article_infos['remise_ht'])) {
			$sql .= ", remise_ht = '" . nohtml_real_escape_string(vn($article_infos['remise_ht'])) . "'";
		}
		$sql .= "
			, total_prix = '" . nohtml_real_escape_string($article_infos['total_prix']) . "'
			, total_prix_ht = '" . nohtml_real_escape_string($article_infos['total_prix_ht']) . "'
			, quantite = '" . nohtml_real_escape_string($article_infos['quantite']) . "'
			, tva = '" . nohtml_real_escape_string($article_infos['tva']) . "'
			, tva_percent = '" . nohtml_real_escape_string($article_infos['tva_percent']) . "'";
		if (isset($article_infos['points'])) {
			$sql .= ", points = '" . nohtml_real_escape_string(vn($article_infos['points'])) . "'";
		}
		if (isset($article_infos['poids'])) {
			$sql .= ", poids = '" . nohtml_real_escape_string(vn($article_infos['poids'])) . "'";
		}
		// Email_check correspond à l'email d'envoi de chèque cadeau qui peut être différent
		if (isset($article_infos['email_check'])) {
			$sql .= ", email_check = '" . nohtml_real_escape_string(vb($article_infos['email_check'])) . "'";
		}
		if (isset($product_object->on_download)) {
			$sql .= ", on_download = '" . intval($product_object->on_download) . "'
			, statut_envoi = '" . nohtml_real_escape_string($statut_envoi) . "'";
		}
		$sql .= "
			, nb_envoi = '0'
			, nb_download = '0'
			, ecotaxe_ttc = '" . nohtml_real_escape_string(vb($article_infos['ecotaxe_ttc'])) . "'
			, ecotaxe_ht = '" . nohtml_real_escape_string(vb($article_infos['ecotaxe_ht'])) . "'
			, prix_option = '" . nohtml_real_escape_string(vn($article_infos['option'])) . "'
			, prix_option_ht = '" . nohtml_real_escape_string(vn($article_infos['option_ht'])) . "'";
		if (check_if_module_active('stock_advanced')) {
			$sql .= "
			, etat_stock = '" . nohtml_real_escape_string(vb($article_infos['etat_stock'])) . "'
			, delai_stock = '" . nohtml_real_escape_string(vb($article_infos['delai_stock'])) . "'";
		}
		if (is_giftlist_module_active() && !empty($article_infos['giftlist_owners'])) {
			$sql .= "
			, listcadeaux_owner = '" . nohtml_real_escape_string(vn($article_infos['giftlist_owners'])) . "'";
		}
		if (is_conditionnement_module_active()) {
			// Les produits sont conditionnés sous forme de lot => ici on sauvegarde la taille des lots de conditionnement
			$sql .= "
			, conditionnement = '" . nohtml_real_escape_string(vb($product_object->conditionnement)) . "'";
		}
		if (is_tnt_module_active()) {
			$sql .= "
			, tnt_parcel_number = '" . nohtml_real_escape_string(vb($article_infos['tnt_parcel_number'])) . "'";
		}
		$sql .= "
			, attributs_list = '" . nohtml_real_escape_string(vb($article_infos['id_attribut'])) . "'
			, nom_attribut = '" . nohtml_real_escape_string(str_replace('<br />', "\r\n", $attribut)) . "'
			, total_prix_attribut = '" . nohtml_real_escape_string(vb($article_infos['total_prix_attribut'])) . "'";
		query($sql);

		unset($product_object);
	}
	if (is_numeric($order_infos['statut_livraison'])) {
		// conversion de l'id du statut de livraison par son technical_code pour sa bonne prise en compte par update_order_payment_status
		$sql = 'SELECT l.technical_code
			FROM peel_statut_livraison l
			WHERE id=' . intval($order_infos['statut_livraison']) . ' AND ' . get_filter_site_cond('statut_livraison', 'l');
		$query = query($sql);
		$result = fetch_assoc($query);
		$order_infos['statut_livraison'] = $result['technical_code'];
	}
	// Tout est maintenant en BDD, sauf les statuts qui n'ont pas été modifiés
	// On met à jour les status, ET on incrémente ou décremente les stocks en fonction des id's (il fallait attendre d'avoir bien les produits mis en BDD ci-dessus)
	// NB : delivery_tracking vaut null habituellement, et n'est pas null que si la demande de modification vient de l'administration => ne pas mettre de vb() sur delivery_tracking
	// output_create_or_update_order sera afficher dans le fichier admin_haut.
	$GLOBALS['output_create_or_update_order'] = update_order_payment_status($order_id, $order_infos['statut_paiement'], true, $order_infos['statut_livraison'], $order_infos['delivery_tracking'], true, vb($order_infos['payment_technical_code']));
	if(check_if_module_active('nexway')) {
		include_once($GLOBALS['dirroot'] . '/modules/nexway/fonctions.php');
		Nexway::create_order($order_infos, $articles_array);
	}
	return $order_id;
}

/**
 * email_commande()
 *
 * @param integer $order_id
 * @return
 */
function email_commande($order_id)
{
	$result = query("SELECT c.*, sp.technical_code AS statut_paiement
		FROM peel_commandes c
		LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
		WHERE c.id ='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes', 'c'));
	if($order_object = fetch_object($result)){
		$order_infos = get_order_infos_array($order_object);
		$user = get_user_information($order_object->id_utilisateur);

		$custom_template_tags['ORDER_ID'] = $order_id;
		$custom_template_tags['NOM_FAMILLE'] = String::htmlspecialchars_decode($user['nom_famille'], ENT_QUOTES);
		$custom_template_tags['CIVILITE'] = $user['civilite'];
		$custom_template_tags['PRENOM'] = String::htmlspecialchars_decode($user['prenom'], ENT_QUOTES);
		$custom_template_tags['TYPE'] = $order_object->type;
		$custom_template_tags['COLIS'] = $order_object->delivery_tracking;
		$custom_template_tags['DATE'] = get_formatted_date($order_object->o_timestamp, 'short', 'long');
		$custom_template_tags['MONTANT'] = fprix($order_object->montant, true);
		$custom_template_tags['PAIEMENT'] = get_payment_name($order_object->paiement);
		$custom_template_tags['CLIENT_INFOS_BILL'] = String::htmlspecialchars_decode($order_infos['client_infos_bill'], ENT_QUOTES);
		$custom_template_tags['CLIENT_INFOS_SHIP'] = String::htmlspecialchars_decode($order_infos['client_infos_ship'], ENT_QUOTES);
		$custom_template_tags['COUT_TRANSPORT'] = (display_prices_with_taxes_active()? fprix($order_object->cout_transport, true) . " " . $GLOBALS['STR_TTC'] : fprix($order_object->cout_transport_ht, true) . " " .$GLOBALS['STR_HT']);
		$custom_template_tags['BOUGHT_ITEMS'] = '';
		$custom_template_tags['COMMENT'] = $order_object->commentaires;

		$product_infos_array = get_product_infos_array_in_order($order_id, $order_object->devise, $order_object->currency_rate);
		foreach ($product_infos_array as $this_ordered_product) {
			$custom_template_tags['BOUGHT_ITEMS'] .= $this_ordered_product["product_text"] . "\n";
			$custom_template_tags['BOUGHT_ITEMS'] .= $GLOBALS['STR_QUANTITY'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $this_ordered_product["quantite"] . "\n";
			$custom_template_tags['BOUGHT_ITEMS'] .= $GLOBALS['STR_PRICE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . (display_prices_with_taxes_active()? fprix($this_ordered_product["total_prix"], true) . ' ' . $GLOBALS['STR_TTC']  : fprix($this_ordered_product["total_prix_ht"], true) . ' ' . $GLOBALS['STR_HT']) . "\n\n";
		}
		foreach ($product_infos_array as $this_ordered_product) {
			if(!empty($this_ordered_product['technical_code'])) {
				send_email($order_object->email, '', '', 'confirm_ordered_'.$this_ordered_product['technical_code'], $custom_template_tags, null, $GLOBALS['support_commande']);
				send_email($GLOBALS['support_commande'], '', '', 'confirm_ordered_'.$this_ordered_product['technical_code'], $custom_template_tags, null, $GLOBALS['support_commande']);
			}
		}

		send_email($order_object->email, '', '', 'email_commande', $custom_template_tags, null, $GLOBALS['support_commande']);
		send_email($GLOBALS['support_commande'], '', '', 'email_commande', $custom_template_tags, null, $GLOBALS['support_commande']);
		if(!defined('IN_PEEL_ADMIN') || (defined('IN_PEEL_ADMIN') && !empty($GLOBALS['site_parameters']['send_email_order_in_admin']))) {
			// Envoi de l'email pour l'administrateur en plus de la copie de ce qui est envoyé au client. L'envoi de cet email si l'on est en back office est paramétrable.
			send_mail_order_admin($order_id);
		}
	}
}

/**
 * send_mail_order_admin()
 *
 * @param integer $order_id
 * @return
 */
function send_mail_order_admin($order_id)
{
	$result = query("SELECT *
		FROM peel_commandes
		WHERE id ='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes') . "");
	$order_object = fetch_object($result);
	$custom_template_tags['ORDER_ID'] = $order_id;
	$custom_template_tags['EMAIL'] = $order_object->email;
	$custom_template_tags['SITE'] = $GLOBALS['site'];
	$custom_template_tags['MONTANT'] = fprix($order_object->montant, true);
	$custom_template_tags['O_TIMESTAMP'] = get_formatted_date($order_object->o_timestamp);
	$custom_template_tags['PAIEMENT'] = get_payment_name($order_object->paiement);

	send_email($GLOBALS['support_commande'], '', '', 'send_mail_order_admin', $custom_template_tags, null, $GLOBALS['support_commande']);
}

/**
 * get_payment_name()
 *
 * @param mixed $id_or_code Id or technical_code of the payment mean
 * @return
 */
function get_payment_name($id_or_code)
{
	$sql_paiement = 'SELECT p.nom_' . $_SESSION['session_langue'] . ' AS nom
		FROM peel_paiement p
		WHERE p.id="' . intval($id_or_code) . '" OR p.technical_code="' . nohtml_real_escape_string($id_or_code) . '"  AND ' .  get_filter_site_cond('paiement', 'p') . '';
	$res_paiement = query($sql_paiement);
	if ($tab_paiement = fetch_assoc($res_paiement)) {
		return $tab_paiement['nom'];
	} else {
		return '';
	}
}

/**
 * get_payment_status_name()
 *
 * @param integer $id
 * @return
 */
function get_payment_status_name($id)
{
	static $payment_status_name_by_id;
	if (!isset($payment_status_name_by_id[$id])) {
		$sql_paiement = 'SELECT p.nom_' . $_SESSION['session_langue'] . ' AS nom
			FROM peel_statut_paiement p
			WHERE p.id="' . intval($id) . '" AND ' . get_filter_site_cond('statut_paiement', 'p');
		$res_paiement = query($sql_paiement);
		if ($tab_paiement = fetch_assoc($res_paiement)) {
			$payment_status_name_by_id[$id] = String::html_entity_decode_if_needed($tab_paiement['nom']);
		} else {
			$payment_status_name_by_id[$id] = $id;
		}
	}
	return $payment_status_name_by_id[$id];
}

/**
 * get_delivery_status_name()
 *
 * @param integer $id
 * @return
 */
function get_delivery_status_name($id)
{
	$sql_livraison = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom
		FROM peel_statut_livraison
		WHERE id="' . intval($id) . '" AND ' . get_filter_site_cond('statut_livraison');
	$res_livraison = query($sql_livraison);
	if ($tab_livraison = fetch_assoc($res_livraison)) {
		return String::html_entity_decode_if_needed($tab_livraison['nom']);
	} else {
		return $id;
	}
}

/**
 * get_delivery_type_name()
 *
 * @param integer $id
 * @return
 */
function get_delivery_type_name($id)
{
	$sql_delivery = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom
		FROM peel_types
		WHERE id="' . intval($id) . '" AND ' . get_filter_site_cond('types');
	$res_delivery = query($sql_delivery);
	if ($tab_delivery = fetch_assoc($res_delivery)) {
		return $tab_delivery['nom'];
	} else {
		return false;
	}
}

/**
 * get_needed_for_free_delivery()
 *
 * @param float $total_weight
 * @param float $total_price
 * @param integer $type_id
 * @param integer $zone_id
 * @param integer $nb_product
 * @return
 */
function get_needed_for_free_delivery($total_weight, $total_price, $type_id = null, $zone_id = null, $nb_product = 1)
{
	$add_for_free_delivery = array();
	// Frais de port gratuits si le total principal (TTC ou HT suivant configuration boutique) des produits est > au seuil.
	if (!empty($GLOBALS['site_parameters']['nb_product']) && $GLOBALS['site_parameters']['nb_product'] <= $nb_product) {
		// Frais de port gratuits si plus de nb_product commandés
		return null;
	}elseif (!empty($GLOBALS['site_parameters']['nb_product'])) {
		$add_for_free_delivery['products'] = $GLOBALS['site_parameters']['nb_product'] - $nb_product;
	}
	
	if (!empty($zone_id)) {
		$query = query('SELECT z.on_franco, z.on_franco_amount, z.on_franco_nb_products
			FROM peel_zones z
			WHERE id = "' . intval($zone_id) . '" AND ' . get_filter_site_cond('zones', 'z') . '
			LIMIT 1');
		$result_zones = fetch_assoc($query);
		if (!empty($result_zones['on_franco'])) {
			// Zone franco de port
			if ($result_zones['on_franco_amount'] <= round($total_price, 2)) {
				return null;
			} else {
				$add_for_free_delivery['amount'] = $result_zones['on_franco_amount'] - round($total_price, 2);
			}
		}
		if (!empty($result_zones['on_franco_nb_products'])) {
			// Zone franco de port
			if($result_zones['on_franco_nb_products'] <= $nb_product) {
				return null;
			} else {
				$add_for_free_delivery['products'] = $result_zones['on_franco_nb_products'] - $nb_product;
			}
		}
	}
	// Le seuil d'exonération de frais de port de la zone est prioritaire sur le seuil d'exonération de frais de port généraux.
	// Don con ne teste ici les exonérations générales que si il n'y a pas de configuration d'exonération par zone
	if (empty($add_for_free_delivery['amount'])) {
		// Cas où un seuil de commande minimal est défini pour l'utilisateur de manière globale au niveau de la configuration du site
		$seuil_total_used = floatval((is_reseller_module_active() && is_reseller()) ? $GLOBALS['site_parameters']['seuil_total_reve'] : $GLOBALS['site_parameters']['seuil_total']);
		if (round($seuil_total_used, 2) > 0) {
			if(round($total_price, 2) >= round($seuil_total_used, 2)) {
				// Si le seuil défini pour le franco de port pour la zone n'est pas atteint par le montant de la commande, l'exénoration des frais de port ne s'applique pas
				return null;
			} else {
				// Pour atteindre frais de ports gratuit, il faut une commande plus importante
				$add_for_free_delivery['amount'] = round($seuil_total_used, 2) - round($total_price, 2);
			}
		}
	}
	return $add_for_free_delivery;
}

/**
 * Calcul des frais de livraison
 * Si type_id est vide, on récupère les tarifs en excluant les tarifs=0 qui correspondent a priori à des points de retrait => si on ne trouve pas de tarif on prendra 0 par défaut
 * Remarques : 
 * - si des tranches tarifaires sont définies, il faut configurer par exemple 200g à 299g puis 300g à 399g si on veut qu'un colis de 300g ait le second tarif et non pas le premier. 
 * - si on veut mixer des règles entre poids et montant, il n'y a aucun problème, dans le mode par défaut de calcul c'est le tarif le moins cher qui est appliqué (et gratuité si 0). Si en revanche GLOBALS['site_parameters']['delivery_cost_calculation_mode'] == 'nearest', alors dans ce cas on identifie la tranche qui semble correspondre le mieux (mode particulier de calcul, a priori à ne pas utiliser)
 *
 * @param float $total_weight
 * @param float $total_price
 * @param integer $type_id
 * @param integer $zone_id
 * @param integer $nb_product
 * @return
 */
function get_delivery_cost_infos($total_weight, $total_price, $type_id = null, $zone_id = null, $nb_product = 1)
{
	static $delivery_cost_infos_by_weight_and_price_array;
	$key_weight_and_price = $type_id . $zone_id . $total_weight . $total_price;
	$add_for_free_delivery = get_needed_for_free_delivery($total_weight, $total_price, $type_id, $zone_id, $nb_product);
	$delivery_cost_infos = array('cost_ht' => 0, 'tva' => 0);
	if ($add_for_free_delivery !== null && !empty($GLOBALS['site_parameters']['mode_transport'])) {
		if (!isset($delivery_cost_infos_by_weight_and_price_array[$key_weight_and_price])) {
			// Frais de port calculés en fonction du poids total et du montant total
			if($GLOBALS['site_parameters']['delivery_cost_calculation_mode'] == 'nearest'){
				// On ne prend pas les frais de port les moins chers trouvés, mais ceux correspondant à la tranche la plus proche : par poids en priorité, et par tarif
				// => Permet d'avoir des frais de port dégressifs
				$order_by = 'IF(poids_max>0, poids_max, 100000000) ASC, IF(total_max>0, total_max, 100000000) ASC, tarif ASC';
			} else {
				// Par défaut : On prend le tarif le moins cher pour un poids et un montant donné
				// Cela permet d'avoir des règles complexes entre poids et montant du caddie, mais oblige à définir des tranches précises qui ne se recouvrent pas si on veut configurer des frais progressifs (frais qui montent en fonction du poids et/ou du montant)
				$order_by = 'tarif ASC';
			}
			$tarifs_sql = 'SELECT tarif, poidsmax, totalmax, tva
				FROM peel_tarifs
				WHERE ' . get_filter_site_cond('tarifs') . ' AND ' . (!empty($type_id)?'type="' . intval($type_id) . '"':'tarif>0') . (!empty($zone_id)?' AND zone = "' . intval($zone_id) . '"':'') . '  AND (poidsmin<="' . floatval($total_weight) . '" OR poidsmin=0) AND (poidsmax>="' . floatval($total_weight) . '" OR poidsmax=0) AND (totalmin<="' . floatval($total_price) . '" OR totalmin=0) AND (totalmax>="' . floatval($total_price) . '" OR totalmax=0)
				ORDER BY ' . $order_by . '
				LIMIT 1';
			$req = query($tarifs_sql);
			if ($this_tarif = fetch_assoc($req)) {
				$delivery_cost_infos['cost_ht'] = $this_tarif['tarif'] / (1 + $this_tarif['tva'] / 100);
				if (!is_micro_entreprise_module_active()) {
					$delivery_cost_infos['tva'] = $this_tarif['tva'];
				} else {
					$delivery_cost_infos['tva'] = 0;
				}
			} elseif (!empty($type_id)) {
				// par défaut : pas de frais de port trouvé => mode de livraison indisponible
				$delivery_cost_infos = false;
			}
			$delivery_cost_infos_by_weight_and_price_array[$key_weight_and_price] = $delivery_cost_infos;
		} else {
			$delivery_cost_infos = $delivery_cost_infos_by_weight_and_price_array[$key_weight_and_price];
		}
	}

	return $delivery_cost_infos;
}

/**
 * get_payment_technical_code()
 *
 * @param mixed $id_or_code
 * @return
 */
function get_payment_technical_code($id_or_code)
{
	$sql_paiement = 'SELECT technical_code
		FROM peel_paiement
		WHERE id="' . intval($id_or_code) . '" OR technical_code="' . nohtml_real_escape_string($id_or_code) . '" AND ' .  get_filter_site_cond('paiement') . '';
	$res_paiement = query($sql_paiement);
	if ($tab_paiement = fetch_assoc($res_paiement)) {
		return $tab_paiement['technical_code'];
	} else {
		return false;
	}
}

/**
 * Fonction permettant l'affichage des taux de TVA dans les factures
 *
 * @param string $code_facture
 * @return
 */
function get_vat_array($code_facture)
{
	$sql = 'SELECT SUM(pca.tva) AS products_tva_for_this_percent, pca.tva_percent, pc.tva_cout_transport, ROUND(pc.tva_cout_transport/pc.cout_transport_ht*100,2) AS cout_transport_tva_percent, pc.tva_tarif_paiement, ROUND(pc.tva_tarif_paiement/pc.tarif_paiement_ht*100,2) AS tarif_paiement_tva_percent, tva_small_order_overcost, ROUND(tva_small_order_overcost/(small_order_overcost_amount-tva_small_order_overcost)*100,2) AS small_order_overcost_tva_percent
		FROM peel_commandes_articles pca
		INNER JOIN peel_commandes pc ON pca.commande_id = pc.id AND ' . get_filter_site_cond('commandes', 'pc') . '
		WHERE pc.code_facture = "' . nohtml_real_escape_string($code_facture) . '" AND ' . get_filter_site_cond('commandes_articles', 'pca') . '
		GROUP BY pc.id, pca.tva_percent';
	$query = query($sql);
	$total_tva = array();
	$i = 0;
	if (!empty($query)) {
		while ($result = fetch_assoc($query)) {
			if (empty($total_tva[$result['tva_percent']])) {
				$total_tva[$result['tva_percent']] = 0;
			}
			$total_tva[$result['tva_percent']] += $result['products_tva_for_this_percent'];
			if (empty($i)) {
				// Avec la jointure, on peut avoir N lignes pour une commande si il y a N taux de TVA produits différents
				// On prend ici en compte une seule fois ce qui est spécifique à la commande et non aux divers produits
				if ($result['tva_cout_transport'] > 0) {
					$total_tva['transport ' . $result['cout_transport_tva_percent']] = $result['tva_cout_transport'];
				}
				if ($result['tva_tarif_paiement'] > 0) {
					if (empty($total_tva[$result['tarif_paiement_tva_percent']])) {
						$total_tva[$result['tarif_paiement_tva_percent']] = 0;
					}
					$total_tva[$result['tarif_paiement_tva_percent']] += $result['tva_tarif_paiement'];
				}
				if ($result['tva_small_order_overcost'] > 0) {
					if (empty($total_tva[$result['small_order_overcost_tva_percent']])) {
						$total_tva[$result['small_order_overcost_tva_percent']] = 0;
					}
					$total_tva[$result['small_order_overcost_tva_percent']] += $result['tva_small_order_overcost'];
				}
			}
			$i++;
		}
	}
	ksort($total_tva);
	return $total_tva;
}

/**
 * get_order_infos_array()
 *
 * @param mixed $order_object
 * @return
 */
function get_order_infos_array($order_object)
{
	if(empty($order_object)){
		return array();
	}
	if (!empty($order_object->a_timestamp) && $order_object->a_timestamp != '0000-00-00' && in_array($order_object->statut_paiement, array('discussed', 'completed'))) {
		$order_infos['displayed_paiement_date'] = get_formatted_date($order_object->a_timestamp);
	} else {
		$order_infos['displayed_paiement_date'] = null;
	}
	// Limitation du nombre de lignes de l'adresse
	$separator_before_country = "\n";
	$separator_before_email = "\n";
	if(String::substr_count($order_object->adresse_bill, "\n")>0) {
		if(String::strlen($order_object->adresse_bill)<40) {
			// Adresse courte mais sur plusieurs lignes : on gagne de la place en retirant les sauts de ligne
 			$order_object->adresse_bill = str_replace("\n", ' - ', $order_object->adresse_bill);
		} else {
			// Adresse longue sur plusieurs lignes : on gagne de la place en mettant le pays à côté de la ville
			$separator_before_country = ' - ';
			if(String::substr_count($order_object->adresse_bill, "\n") >= 2) {
				$separator_before_email = ' - ';
			}
		}
	}
	$order_infos['client_infos_bill'] = (!empty($order_object->societe_bill)?$order_object->societe_bill . "\n":'')
	 . trim($order_object->nom_bill . " " . $order_object->prenom_bill)
	 . "\n" . $order_object->adresse_bill;
	if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
		foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
			if(String::substr($this_title, 0, 4) == 'STR_' && isset($GLOBALS[$this_title])) {
				$this_text = $GLOBALS[$this_title];
			} else {
				$this_text = $this_title;
			}
			if ((String::substr($this_field, -5) == '_bill') && !empty($order_object->$this_field)) {
				// On a ajouté dans la table utilisateurs un champ qui concerne l'adresse de facturation => Il faut préremplir les champs du formulaire d'adresse de facturation avec ces infos.
				$order_infos['client_infos_bill'] .= "\n" . $this_title . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . vb($order_object->$this_field);
			}
		}
	}
	$order_infos['client_infos_bill'] .= "\n" . trim($order_object->zip_bill . " " . $order_object->ville_bill)
	 . $separator_before_country . $order_object->pays_bill
	 . "\n" . get_formatted_phone_number($order_object->telephone_bill)
	 . $separator_before_email . $order_object->email_bill;
 
	$order_infos['client_infos_bill'] = trim(str_replace(array("\n ", "\n\n\n\n", "\n\n\n", "\n\n"), "\n", $order_infos['client_infos_bill']));

	// Limitation du nombre de lignes de l'adresse
	$separator_before_country = "\n";
	$separator_before_email = "\n";
	if(String::substr_count($order_object->adresse_ship, "\n")>0) {
		if(String::strlen($order_object->adresse_ship)<40) {
			// Adresse courte mais sur plusieurs lignes : on gagne de la place en retirant les sauts de ligne
 			$order_object->adresse_ship = str_replace("\n", ' - ', $order_object->adresse_ship);
		} else {
			// Adresse longue sur plusieurs lignes : on gagne de la place en mettant le pays à côté de la ville
			$separator_before_country = ' - ';
			if(String::substr_count($order_object->adresse_ship, "\n") >= 2) {
				$separator_before_email = ' - ';
			}
		}
	}
	$order_infos['client_infos_ship'] = (!empty($order_object->societe_ship)?$order_object->societe_ship . "\n":'')
	 . trim($order_object->nom_ship . " " . $order_object->prenom_ship)
	 . "\n" . $order_object->adresse_ship;
	if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
		foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
			if(String::substr($this_title, 0, 4) == 'STR_' && isset($GLOBALS[$this_title])) {
				$this_text = $GLOBALS[$this_title];
			} else {
				$this_text = $this_title;
			}
			if ((String::substr($this_field, -5) == '_ship') && !empty($order_object->$this_field)) {
				// On a ajouté dans la table utilisateurs un champ qui concerne l'adresse de facturation => Il faut préremplir les champs du formulaire d'adresse de facturation avec ces infos.
				$order_infos['client_infos_ship'] .= "\n" . $this_text . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . vb($order_object->$this_field);
			}
		}
	}
	$order_infos['client_infos_ship'] .= "\n" . trim($order_object->zip_ship . " " . $order_object->ville_ship)
	 . $separator_before_country . $order_object->pays_ship
	 . "\n" . get_formatted_phone_number($order_object->telephone_ship)
	 . $separator_before_email . $order_object->email_ship;
	if (String::strpos($order_infos['client_infos_bill'], 'TVA') === false) {
		$client = get_user_information($order_object->id_utilisateur);
		if (!empty($client) && !empty($client['intracom_for_billing'])) {
			// Ajout du numéro de TVA intracommunautaire qui n'est pas dans $client_infos_bill
			$order_infos['client_infos_bill'] .= "\n" . $GLOBALS['STR_VAT_INTRACOM'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $client['intracom_for_billing'];
		}
	}
	$order_infos['client_infos_ship'] = trim(str_replace(array("\n ", "\n\n\n\n", "\n\n\n", "\n\n"), "\n", $order_infos['client_infos_ship']));

	$order_infos['net_infos_array'] = array("avoir" => fprix($order_object->avoir, true, $order_object->devise, true, $order_object->currency_rate, true),
		"total_remise" => fprix($order_object->total_remise, true, $order_object->devise, true, $order_object->currency_rate, true),
		"cout_transport" => fprix($order_object->cout_transport, true, $order_object->devise, true, $order_object->currency_rate, true),
		"totalttc" => fprix($order_object->montant+$order_object->avoir, true, $order_object->devise, true, $order_object->currency_rate, true),
		"montant" => fprix($order_object->montant, true, $order_object->devise, true, $order_object->currency_rate, true),
		"total_tva" => fprix($order_object->total_tva, true, $order_object->devise, true, $order_object->currency_rate, true),
		"montant_ht" => fprix($order_object->montant_ht, true, $order_object->devise, true, $order_object->currency_rate, true),
		"tarif_paiement" => fprix($order_object->tarif_paiement, true, $order_object->devise, true, $order_object->currency_rate, true),
		"cout_transport_ht" => fprix($order_object->cout_transport_ht, true, $order_object->devise, true, $order_object->currency_rate, true),
		"small_order_overcost_amount" => fprix($order_object->small_order_overcost_amount, true, $order_object->devise, true, $order_object->currency_rate, true),
		"total_ecotaxe_ht" => fprix($order_object->total_ecotaxe_ht, true, $order_object->devise, true, $order_object->currency_rate, true)
		);
	if ($order_object->cout_transport != 0) {
		$order_infos['net_infos_array']['displayed_cout_transport'] = fprix($order_object->cout_transport, true, $order_object->devise, true, $order_object->currency_rate) . " " . $GLOBALS['STR_TTC'];
	} else {
		$order_infos['net_infos_array']['displayed_cout_transport'] = $GLOBALS['STR_OFFERED'];
	}
	$order_infos['tva_infos_array'] = array("total_tva" => fprix($order_object->total_tva, true, $order_object->devise, true, $order_object->currency_rate, true));
	$distinct_total_vat = get_vat_array($order_object->code_facture);
	foreach($distinct_total_vat as $vat_percent => $value) {
		if ($vat_percent>0 && $value>0) {
			$order_infos['tva_infos_array']["distinct_total_vat"][$vat_percent] = fprix($value, true, $order_object->devise, true, $order_object->currency_rate, true);
		}
	}
	if (!empty($order_object->code_promo)) {
		$order_infos['code_promo_text'] = $GLOBALS['STR_CODE_PROMO_REMISE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $order_object->code_promo;
		$code_promo_query = query('SELECT code_promo, valeur_code_promo, percent_code_promo
			FROM peel_commandes pc
			WHERE code_promo="' . nohtml_real_escape_string($order_object->code_promo) . '" AND ' . get_filter_site_cond('commandes', 'pc') . '');
		if ($cp = fetch_assoc($code_promo_query)) {
			$order_infos['code_promo_text'] .= ' - ' . get_discount_text($cp['valeur_code_promo'], $cp['percent_code_promo'], true) . '';
		}
	} else {
		$order_infos['code_promo_text'] = '';
	}
	$order_infos['delivery_infos'] = $order_object->type;
	return $order_infos;
}

/**
 * get_product_infos_array_in_order()
 *
 * @param integer $order_id
 * @param mixed $devise
 * @param mixed $currency_rate
 * @param mixed $order_by
 * @param boolean $add_total_prix_attribut
 * @return
 */
function get_product_infos_array_in_order($order_id, $devise = null, $currency_rate = null, $order_by = null, $add_total_prix_attribut = false)
{
	if(empty($order_by) && !empty($GLOBALS['site_parameters']['order_article_order_by']) && $GLOBALS['site_parameters']['order_article_order_by'] == 'name') {
		$order_by = 'oi.nom_produit ASC';
	} else {
		$order_by = 'oi.id ASC';
	}
	
	$product_infos_array = array();
	$sql = "SELECT oi.*, p.technical_code, c.nom_".$_SESSION['session_langue']." AS category_name, m.nom_".$_SESSION['session_langue']." AS brand_name
		FROM peel_commandes_articles oi
		LEFT JOIN peel_produits p ON p.id=oi.produit_id AND " . get_filter_site_cond('produits', 'p') . "
		LEFT JOIN peel_produits_categories pc ON p.id = pc.produit_id
		LEFT JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
		LEFT JOIN peel_marques m ON m.id = p.id_marque AND " . get_filter_site_cond('marques', 'm') . "
		WHERE commande_id='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes_articles', 'oi') . "
        GROUP BY oi.nom_attribut, oi.attributs_list, oi.produit_id, oi.reference, oi.nom_produit, oi.prix, oi.couleur_id, oi.taille_id
		ORDER BY " . $order_by;
	$qid_items = query($sql);
	while ($prod = fetch_assoc($qid_items)) {
		// On crée la description d'un produit facturé
		$category_text = (!empty($prod['category_name']) && !empty($GLOBALS['site_parameters']['display_category_name_in_product_infos_in_order']) ? "\r\n" . $GLOBALS['STR_CATEGORY'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . String::htmlspecialchars_decode($prod["category_name"], ENT_QUOTES) : "");
		$brand_text = (!empty($prod['brand_name']) && !empty($GLOBALS['site_parameters']['display_brand_name_in_product_infos_in_order']) ? "\r\n" . $GLOBALS['STR_BRAND'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . String::htmlspecialchars_decode($prod["brand_name"], ENT_QUOTES) : "");
		$reference_text = (!empty($prod['reference']) ? "\r\n" . $GLOBALS['STR_REFERENCE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . String::htmlspecialchars_decode($prod["reference"], ENT_QUOTES) : "");
		$couleur_text = (!empty($prod['couleur']) ? "\r\n" . $GLOBALS['STR_COLOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . String::html_entity_decode_if_needed($prod['couleur']) : "");
		$taille_text = (!empty($prod['taille']) ? "\r\n" . $GLOBALS['STR_SIZE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . String::html_entity_decode_if_needed($prod['taille']) : "");
		if ($prod['nom_attribut'] != '') {
			$attribut_text = "\r\n" . trim(String::html_entity_decode_if_needed($prod['nom_attribut']));
			if($add_total_prix_attribut) {
				$attribut_text .= ($prod['total_prix_attribut'] > 0 ? "\r\n" . $GLOBALS['STR_OPTIONS_COST'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($prod['total_prix_attribut'], true) . ' ' . $GLOBALS['STR_TTC'] : '');
			}
		} else {
			$attribut_text = '';
		}
		$delai_text = (!empty($prod['delai_stock']) ? "\r\n" . $GLOBALS['STR_DELIVERY_STOCK'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . get_formatted_duration((intval($prod['delai_stock']) * 24 * 3600), false, true) : "");
		// Attention : un test !empty ne marche pas sur prix_option, percent_remise_produit et ecotaxe_ttc car au format "0.00"
		$option_text = ($prod['prix_option'] != 0 ? "\r\n" . $GLOBALS['STR_OPTION_PRIX'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . fprix($prod['prix_option'], true, $devise, true, $currency_rate) . "" : "");
		// ATTENTION : ne pas utiliser ici $prod['percent_remise_produit']
		// - Dans $prod['percent_remise_produit'] il y a l'ensemble des pourcentages de remise qui est indiqué, que ce soit liés au produit ou à l'utilisateur, ce qui a un intérêt technique et de déboguage.
		// Ce pourcentage ne prend pas en considération des réductions par montant effectuées, donc ça n'est pas le total de réduction en pourcentage
		// - $prod['remise'] est quant à lui la remise totale effectuée, donc c'est une information compréhensible par l'utilisateur.
		$remise_text = ($prod['remise'] > 0 ? "\r\n" . $GLOBALS['STR_PROMOTION_INCLUDE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . get_discount_text((display_prices_with_taxes_active()?$prod['remise']:$prod['remise_ht']), $prod['percent_remise_produit'] , display_prices_with_taxes_active()) : "");
		if (is_module_ecotaxe_active()) {
			// On affiche le montant de l'écotaxe dans la colonne dénomination du produit et non pas prix pour des raisons de largeur de colonne
			$ecotaxe_text = ($prod['ecotaxe_ttc'] > 0) ? "\r\n" . $GLOBALS['STR_ECOTAXE_INCLUDE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . fprix($prod['ecotaxe_ttc'], true, $devise, true, $currency_rate) : "";
		}
		$prod['product_technical_text'] = String::html_entity_decode_if_needed($prod['nom_produit'] . vb($category_text) . vb($brand_text) . vb($reference_text) . vb($couleur_text) . vb($taille_text) . vb($attribut_text));
		$prod['product_text'] = String::html_entity_decode_if_needed($prod['nom_produit'] . vb($category_text) . vb($brand_text) . vb($reference_text) . vb($couleur_text) . vb($taille_text) . vb($attribut_text) . vb($option_text) . vb($remise_text) . vb($ecotaxe_text) . vb($delai_text));
		$product_infos_array[] = $prod;
	}
	return $product_infos_array;
}

/**
 * Renvoie le formulaire de paiement
 *
 * @param integer $order_id
 * @param mixed $forced_type
 * @param mixed $send_admin_email
 * @param mixed $amount_to_pay
 * @param boolean $allow_autosend
 * @return string
 */
function get_payment_form($order_id, $forced_type = null, $send_admin_email = false, $amount_to_pay = 0, $allow_autosend = true)
{
	$output = '';

	$result = query('SELECT *
		FROM peel_commandes
		WHERE id="' . intval($order_id) . '" AND ' . get_filter_site_cond('commandes') . '');
	$com = fetch_object($result);
	// Ce paramètre est utilisé pour les paiements partiels.
	if (empty($amount_to_pay)) {
		$amount_to_pay = floatval($com->montant);
	}
	if($amount_to_pay == 0 || !is_order_modification_allowed($com->o_timestamp)) {
		return null;
	}
	if (!empty($forced_type)) {
		$type = $forced_type;
	} else {
		// In $com->payment_technical_code is stored the "technical_code" found in peel_paiement
		$type = $com->paiement;
	}
	if (empty($type)) {
		// Affichage de tous les modes de paiement si aucun défini (seulement si commande passée dans l'administration)
		$sql_paiement = 'SELECT p.technical_code
			FROM peel_paiement p
			WHERE p.etat = "1" AND ' .  get_filter_site_cond('paiement', 'p') . '
			ORDER BY p.position';
		$res_paiement = query($sql_paiement);
		while ($tab_paiement = fetch_assoc($res_paiement)) {
			if (!empty($tab_paiement['technical_code'])) {
				$this_output = get_payment_form($order_id, $tab_paiement['technical_code'], $send_admin_email, $amount_to_pay, $allow_autosend);
				if(!empty($this_output)) {
					$output_array[] = $this_output;
				}
			}
		}
		return implode('<hr />', $output_array);
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('payment_form.tpl');
	$tpl->assign('type', $type);
	$tpl->assign('commande_pdf_href', get_site_wwwroot($com->site_id) . '/factures/commande_pdf.php?code_facture=' . $com->code_facture . '&mode=bdc');
	$tpl->assign('amount_to_pay_formatted', fprix($amount_to_pay, true, $com->devise, true, get_float_from_user_input(vn($com->currency_rate))));
	$tpl->assign('disable_address_payment_by_check', !empty($GLOBALS['site_parameters']['disable_address_payment_by_check']));
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_FOR_A_CHECK_PAYMENT', $GLOBALS['STR_FOR_A_CHECK_PAYMENT']);
	$tpl->assign('STR_SEND_CHECK', $GLOBALS['STR_SEND_CHECK']);
	$tpl->assign('STR_SEND_TRANSFER', $GLOBALS['STR_SEND_TRANSFER']);
	$tpl->assign('STR_FOR_A_TRANSFERT', $GLOBALS['STR_FOR_A_TRANSFERT']);
	$tpl->assign('STR_PRINT_PROFORMA', $GLOBALS['STR_PRINT_PROFORMA']);
	$tpl->assign('STR_FOLLOWING_ADDRESS', $GLOBALS['STR_FOLLOWING_ADDRESS']);
	$tpl->assign('STR_FOLLOWING_ACCOUNT', $GLOBALS['STR_FOLLOWING_ACCOUNT']);
	switch ($type) {
		case 'check':
			$tpl->assign('societe', print_societe(true));
			break;

		case 'transfer':
			$tpl->assign('rib', print_rib(true));
			break;

		case 'cmcic' :
			if (file_exists($GLOBALS['fonctionscmcic'])) {
				require_once($GLOBALS['fonctionscmcic']);
				$js_action = 'document.getElementById("PaymentRequest").submit()';
				$tpl->assign('form', getCMCICForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, ''));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'cmcic_by_3' :
			if (file_exists($GLOBALS['fonctionscmcic'])) {
				require_once($GLOBALS['fonctionscmcic']);
				$js_action = 'document.getElementById("PaymentRequest").submit()';
				$tpl->assign('form', getCMCICForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 3, ''));
				$send_admin_template_email = 'admin_info_payment_credit_card_3_times';
			}
			break;

		case 'atos' :
			if (file_exists($GLOBALS['fonctionsatos']) && !empty($GLOBALS['site_parameters']['atos_solution_name']['atos'])) {
				require_once($GLOBALS['fonctionsatos']);
				// la validation automatique ne fonctionne pas avec atos.
				$tpl->assign('form', getATOSForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $GLOBALS['site_parameters']['atos_solution_name']['atos']));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'atos_by_3' :
			if (file_exists($GLOBALS['fonctionsatos']) && !empty($GLOBALS['site_parameters']['atos_solution_name']['atos_by_3'])) {
				require_once($GLOBALS['fonctionsatos']);
				// la validation automatique ne fonctionne pas avec atos.
				$tpl->assign('form', getATOSForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 3, '', $GLOBALS['site_parameters']['atos_solution_name']['atos_by_3']));
				$send_admin_template_email = 'admin_info_payment_credit_card_3_times';
			}
			break;

		case 'cetelem' :
			if (file_exists($GLOBALS['fonctionsatos']) && !empty($GLOBALS['site_parameters']['atos_solution_name']['cetelem'])) {
				require_once($GLOBALS['fonctionsatos']);
				// la validation automatique ne fonctionne pas avec atos.
				$tpl->assign('form', getATOSForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $GLOBALS['site_parameters']['atos_solution_name']['cetelem']));
				$send_admin_template_email = 'admin_info_payment_credit_card_3_times';
			}
			break;

		case 'systempay' :
			if (file_exists($GLOBALS['fonctionssystempay'])) {
				require_once($GLOBALS['fonctionssystempay']);
				$js_action = 'document.getElementById("SystempayForm").submit()';
				$tpl->assign('form', getSystempayForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->id_utilisateur, $com->nom_bill, $com->prenom_bill, $com->telephone_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'systempay_3x' :
			if (file_exists($GLOBALS['fonctionssystempay'])) {
				require_once($GLOBALS['fonctionssystempay']);
				$js_action = 'document.getElementById("SystempayForm").submit()';
				$tpl->assign('form', getSystempayForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, vn($GLOBALS['site_parameters']['systempay_payment_count'], 1), '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->id_utilisateur, $com->nom_bill, $com->prenom_bill, $com->telephone_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'spplus' :
			if (file_exists($GLOBALS['fonctionsspplus'])) {
				require_once($GLOBALS['fonctionsspplus']);
				// la validation automatique ne fonctionne pas avec spplus.
				// => pas de possibilité de mettre js_action
				$tpl->assign('form', getSPPlusForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, ''));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'paybox' :
			if (file_exists($GLOBALS['fonctionspaybox'])) {
				require_once($GLOBALS['fonctionspaybox']);
				$js_action = 'document.TheForm.submit()';
				$tpl->assign('form', givePayboxForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', vb($GLOBALS['site_parameters']['paybox_cgi']), vb($GLOBALS['site_parameters']['paybox_site']), vb($GLOBALS['site_parameters']['paybox_rang']), vb($GLOBALS['site_parameters']['paybox_identifiant'])));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'bluepaid' :
			if (file_exists($GLOBALS['fonctionsbluepaid'])) {
				require_once($GLOBALS['fonctionsbluepaid']);
				$js_action = 'document.TheForm.submit()';
				$tpl->assign('form', getBluepaidForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->id_utilisateur, $com->pays_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'bluepaid_abonnement' :
			if (file_exists($GLOBALS['fonctionsbluepaid'])) {
				require_once($GLOBALS['fonctionsbluepaid']);
				$js_action = 'document.TheForm.submit()';
				$tpl->assign('form', getBluepaidForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->id_utilisateur, $com->pays_bill, true));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'kwixo':
			if (file_exists($GLOBALS['fonctionsfianet'])) {
				echo $GLOBALS['STR_THANKS_FIANET'];
				// Librairie de fonctions PEEL pour Fianet
				require_once($GLOBALS['fonctionsfianet']);
				$tpl->assign('form', getKwixoForm($order_id, 'comptant'));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'kwixo_rnp':
			if (file_exists($GLOBALS['fonctionsfianet'])) {
				echo $GLOBALS['STR_THANKS_FIANET'];
				// Librairie de fonctions PEEL pour Fianet
				require_once($GLOBALS['fonctionsfianet']);
				$tpl->assign('form', getKwixoForm($order_id, 'rnp'));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'kwixo_credit':
			if (file_exists($GLOBALS['fonctionsfianet'])) {
				echo $GLOBALS['STR_THANKS_FIANET'];
				// Librairie de fonctions PEEL pour Fianet
				require_once($GLOBALS['fonctionsfianet']);
				$tpl->assign('form', getKwixoForm($order_id, 'credit'));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'ogone' :
		case 'postfinance' :
			if (file_exists($GLOBALS['fonctionsogone'])) {
				require_once($GLOBALS['fonctionsogone']);
				$js_action = 'document.getElementById("ogoneForm").submit()';
				$tpl->assign('form', giveOgoneForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->prenom_bill . ' ' . $com->nom_bill, $com->telephone_bill, $type));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'worldpay' :
			if (file_exists($GLOBALS['dirroot'] . '/modules/worldpay/fonctions.php')) {
				require_once($GLOBALS['dirroot'] . '/modules/worldpay/fonctions.php');
				$js_action = 'document.getElementById("worldpayForm").submit()';
				$tpl->assign('form', giveWorldpayForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->prenom_bill . ' ' . $com->nom_bill, $com->telephone_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'omnikassa' :
			if (file_exists($GLOBALS['fonctionsomnikassa'])) {
				require_once($GLOBALS['fonctionsomnikassa']);
				$js_action = 'document.getElementById("omnikassaForm").submit()';
				$tpl->assign('form', giveOmnikassaForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->prenom_bill . ' ' . $com->nom_bill, $com->telephone_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'moneybookers' :
			if (file_exists($GLOBALS['fonctionsmoneybookers']) && !empty($GLOBALS['site_parameters']['email_moneybookers'])) {
				require_once($GLOBALS['fonctionsmoneybookers']);
				$js_action = 'document.getElementById("MoneyBookersForm").submit()';
				$tpl->assign('form', getMoneyBookersForm(vb($GLOBALS['site_parameters']['email_moneybookers']), $order_id, $_SESSION['session_langue'], $com->id_utilisateur, $com->email, fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->prenom_bill, $com->nom_bill, $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, fprix($com->total_tva, false, $com->devise, true, $com->currency_rate, false, false), $com->moneybookers_payment_methods));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'paypal':
			if (file_exists($GLOBALS['fonctionspaypal']) && !empty($GLOBALS['site_parameters']['email_paypal'])) {
				require_once($GLOBALS['fonctionspaypal']);
				$js_action = 'document.getElementById("paypalForm").submit()';
				$tpl->assign('form', getPaypalForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->id_utilisateur, $com->prenom_ship, $com->nom_ship, $com->adresse_ship, $com->zip_ship, $com->ville_ship, $com->pays_ship, $com->telephone_ship, $com->prenom_bill, $com->nom_bill, $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->telephone_bill));
				$tpl->assign('STR_FOR_A_PAYPAL_PAYMENT', $GLOBALS['STR_FOR_A_PAYPAL_PAYMENT']);
				$tpl->assign('paypal_img_html', $GLOBALS['STR_PAYPAL_IMG']);
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		default :
			break;
	}
	if ($send_admin_email && !empty($send_admin_template_email)) {
		unset($custom_template_tags);
		$custom_template_tags['ORDER_ID'] = $order_id;
		send_email($GLOBALS['support_commande'], '', '', $send_admin_template_email, $custom_template_tags, null, $GLOBALS['support_commande']);
	}
	if($allow_autosend && !empty($js_action) && is_autosend_module_active()) {
		$GLOBALS['js_content_array'][] = '
		setTimeout("' . filtre_javascript($js_action, true, false, true, true, false) . '", ' . vn($GLOBALS['site_parameters']['module_autosend_delay']) * 1000 . ');
';
	}
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Renvoie si il est autorisé de modifier une commande
 *
 * @param string $order_datetime
 * @return boolean true si il est possible de modifier la commande, false sinon
 */
function is_order_modification_allowed($order_datetime)
{
	$allowed = false;
	if (empty($order_datetime) || substr($order_datetime, 0, 10) == '0000-00-00') {
		$allowed = true;
	} elseif (empty($GLOBALS['site_parameters']['keep_old_orders_intact']) || $GLOBALS['site_parameters']['keep_old_orders_intact'] == '0') {
		$allowed = true;
	} elseif ($GLOBALS['site_parameters']['keep_old_orders_intact'] == '1') {
		if (intval(date('m')) > 6) {
			// Année <= N-1 bloquées
			$reference_date = date('Y-06-30', time() - 24 * 3600 * 365);
		} else {
			// Année <= N-2 bloquées
			$reference_date = date('Y-06-30', time() - 24 * 3600 * 365 * 2);
		}
	} else {
		// keep_old_orders_intact est alors un timestamp
		$reference_date = date('Y-m-d H:i:s', $GLOBALS['site_parameters']['keep_old_orders_intact']);
	}
	if (!empty($reference_date) && $order_datetime > $reference_date) {
		$allowed = true;
	}
	return $allowed;
}

/**
 * Fonction qui génère le numéro de commande comptable
 *
 * @param integer $id id technique de la commande qui correspond au champ id de peel_commandes
 * @return string
 */
function get_order_id($id = null)
{
	if (!empty($id)) {
		// Recherche si le numéro de commande est déjà défini.
		$query = query('SELECT order_id
			FROM peel_commandes
			WHERE id='.intval($id).' AND '. get_filter_site_cond('commandes'));
		if($result = fetch_assoc($query)) {
			if (!empty($result['order_id'])) {
				// order_id est déjà défini pour la commande, la fonction retourne la valeur déjà calculée
				return $result['order_id'];
			}
		} else {
			// commande introuvable.
			return false;
		}
	}
	if (empty($result['order_id']) || empty($id)) {
		// La recherche du numéro dans la commande n'a rien donnée, ou $id est vide.
		// Récuperation du numéro de commande le plus élevé pour le site. 
		$query = query('SELECT MAX(order_id) as max_order_id
			FROM peel_commandes
			WHERE ' . get_filter_site_cond('commandes'));
		$result = fetch_assoc($query);
		return $result['max_order_id']+1;
	}
}