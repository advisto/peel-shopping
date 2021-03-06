<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: order.php 67177 2021-06-09 13:38:10Z sdelaporte $
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
	// Récupération des informations de la commande
	$sql = "SELECT *
		FROM peel_commandes
		WHERE id='" . intval($id) . "' AND " . get_filter_site_cond('commandes') . "";
	$q = query($sql, false, null, true);
	if($order_infos = fetch_assoc($q)) {
		if(!empty($GLOBALS['site_parameters']['product_reference_create_bill_disable'])) {
			$create_bill_number = true;
			// Permet de ne pas créer de numéro de facture en fonction des produits commandés.
			// On va récupérer la lise de référence des produits commandés, pour voir si l'un correspond au paramètre.
			$sql_ordered_product = "SELECT reference
				FROM peel_commandes_articles
				WHERE commande_id = " . intval($id);
			$ordered_product_query = query($sql_ordered_product);
			while($ordered_product_result = fetch_assoc($ordered_product_query)) {
				if (in_array($ordered_product_result['reference'], $GLOBALS['site_parameters']['product_reference_create_bill_disable'])) {
					// On a trouvé un produit qui correspond aux references paramétrées, on ne va pas créer de numéro de facture pour cette commande.
					// On force la valeur de create_bill_number à false
					$create_bill_number = false;
					// On a trouvé au moins un résultat, on sort de la boucle.
					break;
				}
			}
			if (empty($create_bill_number)) {
				// Si la variable create_bill_number a été passée à false, on retourne null au lieu du numéro de facture.
				return null;
			}
		}
		if(empty($bill_number_format) && $generate_bill_number_if_empty){
			// on récupère le format de numéro dans la base de données en fonction du site de la commande, si pas déjà spécifié lors de l'appel (pour édition dans l'administration par exemple)
			$bill_number_format = get_configuration_variable('format_numero_facture', $order_infos['site_id'], $_SESSION['session_langue']);
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
						$column_names[$temp[0]] = $temp[0];
					}
				}
				if (!empty($tag_full_names_by_colum['++'])) {
					$column_names['site_id'] = 'site_id';
				}
				if (!empty($column_names)) {
					$custom_template_tags = array();
					// On va chercher les valeurs dans les champs de la table qui correspondent aux textes entre crochet du format de facture.
					foreach($order_infos as $this_column => $this_value) {
						if (!empty($tag_full_names_by_colum[$this_column])) {
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
					$bill_number_format_begin = StringMb::substr($bill_number_format, 0, StringMb::strpos($bill_number_format, '[++'));
					$bill_number_format_end = StringMb::substr($bill_number_format, StringMb::strpos($bill_number_format, ']') + 1);
					$sql = "SELECT MAX(0+SUBSTRING(numero,1+" . StringMb::strlen($bill_number_format_begin) . ",LENGTH(numero)-" . (StringMb::strlen($bill_number_format_begin) + StringMb::strlen($bill_number_format_end)) . ")) AS max_numero_part
						FROM peel_commandes
						WHERE id<>'" . intval($id) . "' AND numero LIKE '" . real_escape_string($bill_number_format_begin) . "%" . real_escape_string($bill_number_format_end) . "' AND SUBSTRING(numero,1+" . StringMb::strlen($bill_number_format_begin) . ",LENGTH(numero)-" . (StringMb::strlen($bill_number_format_begin) + StringMb::strlen($bill_number_format_end)) . ") REGEXP ('^([0-9]+)$')";
					if(!empty($GLOBALS['site_parameters']['multisite_disable']) || (!empty($GLOBALS['site_parameters']['multisite_disable_commandes']) && empty($GLOBALS['site_parameters']['multisite_forced_numbering_commandes']))) {
						// Pas de multisite : tous les site_id sont traités sans distinction
						// La ligne suivant est a priori inutile, mais c'est standard de le mettre quand même pour que ce soit propre au niveau de la sécurité d'accès aux données en cas de développements spécifiques
						$sql .= " AND " . get_filter_site_cond('commandes') . "";
					} elseif(empty($GLOBALS['site_parameters']['bill_number_regroup_sites_array']) || !isset($GLOBALS['site_parameters']['bill_number_regroup_sites_array'][$order_infos['site_id']])) {
						// Cas normal : chaque site_id a un enchainement de numérotation indépendant des autres site_id
						$sql .= " AND site_id='" . intval($order_infos['site_id']) . "' AND " . get_filter_site_cond('commandes', null, false, $order_infos['site_id']) . "";
					} else {
						// Cas inhabituel : regroupement de plusieurs site_id pour des sociétés
						$sql .= " AND site_id IN (" . real_escape_string(vb($GLOBALS['site_parameters']['bill_number_regroup_sites_array'][$order_infos['site_id']])) . ")";
					}
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
	} else {
		if(empty($bill_number_format) && $generate_bill_number_if_empty){
			// on récupère le format de numéro dans la base, si pas déjà spécifié lors de l'appel (pour édition dans l'administration par exemple)
			$bill_number_format = vb($GLOBALS['site_parameters']['format_numero_facture']);
		}
	}
	return $bill_number_format;
}

/**
 * Crée une transaction d'encaissement
 *
 * @param integer $order_id
 * @param string $technical_code
 * @param array $data
 * @return
 */
function accounting_insert_transaction($order_id, $technical_code, $data) {
	// Si la REF de la transaction est vide, on vérifie qu'on a le même libellé
	// et quoiqu'il arrive on teste la date pour être sûr qu'on ne modifie pas de vieilles transactions
	$allowed_fields = get_table_field_names('peel_transactions');
	if(empty($data['datetime'])) {
		$data['datetime'] = date('Y-m-d H:i:s', time());
	} else {
		$data['datetime'] = get_mysql_date_from_user_input($data['datetime']);
	}
	$data['orders_id'] = $order_id;
	// date (ddmmyyyy) + plateforme (dto, dinn, dfun, dexpe, dsale, dinve) => corrigé uniquement sur 3 lettres
	//  + heure (hhmmss) + 3 lettres aléatoires de l'alphabet + un nombre aléatoire compris entre 00000 et 99999. => Corrigé en 8 lettres différentes
	//  Exemple final : 30072014dex210738ihz16794
	$data['reference'] = date('dmY') . vb($GLOBALS['site_parameters']['transaction_reference_site_part'], MDP(3)) . date('His') . MDP(8);
	foreach($data as $item => $value) {
		if(in_array($item, $allowed_fields)) {
			$sql_set[$item] = word_real_escape_string($item) . "='" . str_replace(array("\n", "\r"), ' ', real_escape_string($value)) . "'";
		}
	}
	// Sécurité : ne pas imposer l'id
	unset($sql_set['id']);
	$query = query("SELECT t.id, t.bank
		FROM peel_transactions t
		WHERE REF='" . real_escape_string(vb($data['REF'])) . "'" . (true || (empty($data['REF']) || $data['REF'] == '_______' || strpos(vb($data['LIBELLE_OPERATION']), ' AP') !== false)?" AND LIBELLE_OPERATION='" . real_escape_string(vb($data['LIBELLE_OPERATION'])) . "' AND MONTANT_DEBIT='".real_escape_string(vb($data['MONTANT_DEBIT']))."' AND MONTANT_CREDIT='".real_escape_string(vb($data['MONTANT_CREDIT']))."'":"") . " AND (TO_DAYS(datetime) BETWEEN TO_DAYS('" . real_escape_string($data['datetime']) . "')-2 AND TO_DAYS('" . real_escape_string($data['datetime']) . "')+2)
	");
	if ($result_query = fetch_assoc($query)) {
		query("UPDATE peel_transactions
			SET " . implode(',', $sql_set) . "
			WHERE id='" . real_escape_string($result_query['id']) . "' AND bank='" . real_escape_string($result_query['bank']) . "'");
	} else {
		query("INSERT INTO peel_transactions
			SET " . implode(',', $sql_set) . "");
		$inserted_id = insert_id();
		// Traitement des alertes par email
		if (!empty($sql_set['reimbursement']) && vb($data['MONTANT_DEBIT']) > 0) {
			$template_technical_code = 'reimbursement_debit';
		} elseif (!empty($sql_set['cash']) && vb($data['MONTANT_CREDIT']) > 0) {
			$template_technical_code = 'cash_credit';
		} elseif (!empty($sql_set['wire']) && vb($data['MONTANT_CREDIT']) > 0) {
			$template_technical_code = 'wire_credit';
		} elseif (!empty($sql_set['wire']) && vb($data['MONTANT_DEBIT']) > 0) {
			$template_technical_code = 'wire_debit';
		}
		if (!empty($template_technical_code)) {
			// Envoi des emails d'information
			send_email($GLOBALS['support'], null, null, $template_technical_code, $data);
		}
	}
	unset($sql_set);
	call_module_hook('accounting_insert_transaction', array('order_id' => $order_id, 'technical_code' => $technical_code, 'data' => $data));
	return true;
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
	
	//On peut définir un statut de livraison différent suivant le technical code du statut de paiement lorsque le statut de livraison est pas défini
	if(!empty($GLOBALS['site_parameters']['order_payment_livraison_status_switch_by_technical_code']) && is_array($GLOBALS['site_parameters']['order_payment_livraison_status_switch_by_technical_code']) && empty($statut_livraison_new)) {
		foreach($GLOBALS['site_parameters']['order_payment_livraison_status_switch_by_technical_code'] as $order_payment_technical_code => $order_livraison_technical_code){
			if($statut_paiement_new == $order_payment_technical_code){
				$statut_livraison_new = $order_livraison_technical_code;
			}
		}
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
			$livraison_status_by_legacy_id_compatibility_array = array(0 => "discussed", 1 => "processing", 3 => "dispatched", 6 => "cancelled", 9 => "waiting_for_supply", 101 => "processing2");
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
		if(empty($delivery_status_id_by_technical_code_array[$statut_livraison_new]) && in_array($statut_paiement_new, array('cancelled', 'refunded')) && !in_array($commande['statut_paiement'], array('cancelled', 'refunded'))) {
			// La commande n'a pas de statut de livraison demandé, et elle passe par ailleurs en annulé ou remboursé
			if ($commande['id_statut_livraison'] != intval($delivery_status_id_by_technical_code_array['dispatched'])) {
				// Alors changement aussi du statut de livraison en annulé s'il n'était pas déjà en statut livré
				$statut_livraison_new = intval($delivery_status_id_by_technical_code_array['cancelled']);
			}
		}
		//Pour éviter d'une mauvais saisie, on supprime les éventuels espace pour l'utilisation de explode
		$payment_status_create_bill = str_replace(' ', '', $GLOBALS['site_parameters']['payment_status_create_bill']);
		if (empty($GLOBALS['site_parameters']['payment_status_create_bill']) || in_array($statut_paiement_new, explode(',', $payment_status_create_bill))) {
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
			// Changement du moyen de paiement si celui-ci est renseigné et que la commande est payé (statut 2 ou 3), même si elle était déjà en payé avant, ou si l'info paiement était vide avant
			$sql="UPDATE peel_commandes
				SET paiement='" . word_real_escape_string($payment_technical_code) . "'
				WHERE id='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes') . "";
			if(!in_array($statut_paiement_new, array('being_checked', 'completed'))){
				$sql.=" AND paiement=''";
			}
			query($sql);
		}

		if ($statut_paiement_new !== null && in_array($statut_paiement_new, array('being_checked', 'completed')) && !in_array($commande['statut_paiement'], array('being_checked','completed'))) {
			// passage en payé de la commande alors qu'elle ne l'était pas avant
			// => ça déclenche la prise en compte d'abonnements, d'activation de chèque cadeau, etc.
			if (!empty($GLOBALS['site_parameters']['send_order_email_after_payement'])) {
				email_commande($order_id);
			}
			if (!empty($GLOBALS['fonctionsfianet_sac']) && file_exists($GLOBALS['fonctionsfianet_sac'])) {
				require_once($GLOBALS['fonctionsfianet_sac']);
				// envoi des informations sur la commande au service d'analyse des commandes FIANET
				send_fianet_sac($order_id);
			}
			// Mise à jour de la date de paiement si le statut est en réglé (et ne l'était pas avant)
			$sql_set_array[] = "a_timestamp='" . date('Y-m-d H:i:s', time()) . "'";
			// afin d'éviter un doublon d'incrémentation des stocks lorsque l'utilisateur choisit l'annulation de livraison.
			$output .= call_module_hook('order_status_completed', array('order_id' => $order_id, 'order_infos' => $commande, 'statut_paiement_new' => $statut_paiement_new, 'statut_livraison_new' => $statut_livraison_new, 'payment_technical_code' => $payment_technical_code), 'string');
		}
		if(in_array($statut_paiement_new, array('cancelled', 'refunded')) && !in_array($commande['statut_paiement'], array('cancelled', 'refunded'))) {
			// Dans le cas particulier d'une commande contenant des produits cadeaux commandés avec des points puis annulée :
			// On devrait gérer ici le fait de recréditer les points de la commande de cadeaux, mais ça nécessite de stocker en BDD les informations de points dépensés des commandes
			// => ça nécessite actuellement une intervention manuelle par un administrateur pour recréditer le compte utilisateur
			$output .= call_module_hook('order_status_cancelled', array('order_id' => $order_id, 'commande' => $commande, 'statut_paiement_new' => $statut_paiement_new, 'statut_livraison_new' => $statut_livraison_new), 'string');
		}
		if (!empty($payment_status_id_by_technical_code_array[$statut_paiement_new])) {
			$sql_set_array[] = "id_statut_paiement='" . intval($payment_status_id_by_technical_code_array[$statut_paiement_new]) . "'";
		}
		if (!empty($delivery_status_id_by_technical_code_array[$statut_livraison_new])) {
			$sql_set_array[] = "id_statut_livraison='" . intval($delivery_status_id_by_technical_code_array[$statut_livraison_new]) . "'";
		}
		if ($statut_livraison_new == 'dispatched' && $commande['statut_livraison'] != $statut_livraison_new && !empty($GLOBALS['site_parameters']['mode_transport'])) {
			// Le statut de livraison passe à expédié (pour la première fois, ou pas) alors qu'il ne l'était pas juste avant
			// Création de la date d'expédition pour la commande. Cette date est administrable par l'administrateur en back office.
			$sql_set_array[] = "e_datetime='" . date('Y-m-d H:i:s', time()) . "'";
			// On envoie l'email d'expédition (avec ou sans les infos de delivery_tracking qui peut être vide)
			// Si on veut désactiver cet email, désactiver le tempate d'email "send_avis_expedition"
			$output .= send_avis_expedition($order_id, (empty($delivery_tracking)?vb($commande['delivery_tracking']):$delivery_tracking));
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
		$output .= call_module_hook('update_order_payment_status', array('no_stock_decrement_already_done' => $no_stock_decrement_already_done,'order_id' => $order_id, 'commande' => $commande, 'statut_paiement_new' => $statut_paiement_new, 'statut_livraison_new' => $statut_livraison_new), 'string');
		if ($statut_livraison_new == 'pending' && $commande['statut_livraison'] != $statut_livraison_new) {
			$custom_template_tags['ORDER_ID'] = $commande['order_id'];
			$custom_template_tags['NOM_FAMILLE'] = $commande['nom_bill'];
			$custom_template_tags['PRENOM'] = $commande['prenom_bill'];
			$custom_template_tags['EMAIL'] = $commande['email'];
			send_email($commande['email'], '', '', 'status_pending', $custom_template_tags, 'html', $GLOBALS['support']);
	}
	}
	if ($statut_livraison_new == 'ready' && $commande['statut_livraison'] != $statut_livraison_new && !empty($GLOBALS['site_parameters']['mode_transport'])) {
		send_avis_expedition($order_id, $delivery_tracking, 'ready');
	} 
	return $output;
}

/**
 * Récupère les informations du tableau $frm pour les mettre de manière standardisée dans $_SESSION['session_commande']
 *
 * @param array $frm Array with all fields data
 * @return
 */
function put_session_commande(&$frm, $session_commande_name="session_commande")
{
	$_SESSION[$session_commande_name]['societe1'] = vb($frm['societe1']);
	$_SESSION[$session_commande_name]['client1'] = vb($frm['client1']);
	$_SESSION[$session_commande_name]['nom1'] = vb($frm['nom1']);
	$_SESSION[$session_commande_name]['prenom1'] = vb($frm['prenom1']);
	$_SESSION[$session_commande_name]['email1'] = vb($frm['email1']);
	$_SESSION[$session_commande_name]['contact1'] = vb($frm['contact1']);
	$_SESSION[$session_commande_name]['adresse1'] = vb($frm['adresse1']);
	$_SESSION[$session_commande_name]['code_postal1'] = vb($frm['code_postal1']);
	$_SESSION[$session_commande_name]['num_tva1'] = vb($frm['num_tva1']);
	$_SESSION[$session_commande_name]['code_chorus1'] = vb($frm['code_chorus1']);
	$_SESSION[$session_commande_name]['siret1'] = vb($frm['siret1']);
	$_SESSION[$session_commande_name]['ville1'] = vb($frm['ville1']);
	$_SESSION[$session_commande_name]['pays1'] = vb($frm['pays1']);

	if (!empty($GLOBALS['site_parameters']['mode_transport']) && is_delivery_address_necessary_for_delivery_type(vn($_SESSION['session_caddie']->typeId))) {
		if (empty($_SESSION[$session_commande_name]['is_socolissimo_order'])) {
			// Quand on vient de SoColissimo, on ne change pas les variables de livraison
			$_SESSION[$session_commande_name]['societe2'] = vb($frm['societe2']);
			$_SESSION[$session_commande_name]['nom2'] = (empty($frm['nom2'])? $frm['nom1']:$frm['nom2']);
			$_SESSION[$session_commande_name]['prenom2'] = (empty($frm['prenom2'])? $frm['prenom1']:$frm['prenom2']);
			$_SESSION[$session_commande_name]['contact2'] = (empty($frm['contact2'])? $frm['contact1']:$frm['contact2']);
			$_SESSION[$session_commande_name]['email2'] = (empty($frm['email2'])? $frm['email1']:$frm['email2']);
			$_SESSION[$session_commande_name]['adresse2'] = (empty($frm['adresse2'])? $frm['adresse1']:$frm['adresse2']);
			$_SESSION[$session_commande_name]['code_postal2'] = (empty($frm['code_postal2'])? $frm['code_postal1']:$frm['code_postal2']);
            $_SESSION[$session_commande_name]['num_tva2'] = (empty($frm['num_tva2'])? $frm['num_tva1']:$frm['num_tva2']);	
			$_SESSION[$session_commande_name]['ville2'] = (empty($frm['ville2'])? $frm['ville1']:$frm['ville2']);
			$_SESSION[$session_commande_name]['pays2'] = (empty($frm['pays2'])? $frm['pays1']:$frm['pays2']);
		}
	}
	if(check_if_module_active('mondial_relay') && !empty($_SESSION['session_commande']['is_mondial_relay_order'])) {
		$_SESSION['session_commande']['id_target'] = vb($frm['id_target']);
	}
	if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
		foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
			if (isset($frm[$this_field])) {
				// On a ajouté dans la table utilisateurs un champ qui concerne l'adresse de facturation => Il faut préremplir les champs du formulaire d'adresse de facturation avec ces infos.
				$_SESSION[$session_commande_name][$this_field] = $frm[$this_field];
			}
		}
	}
	$_SESSION[$session_commande_name]['commande_interne'] = vb($frm['commande_interne']);
	$_SESSION[$session_commande_name]['commentaires'] = vb($frm['commentaires']);
	if(!empty($frm['order_form_payment_methods'])) {
		$_SESSION[$session_commande_name]['commentaires'] = $GLOBALS['STR_ORDER_FORM'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] .': '. $frm['order_form_payment_methods'] . "\n" . $_SESSION[$session_commande_name]['commentaires'];
	}
	$_SESSION[$session_commande_name]['payment_technical_code'] = vb($frm['payment_technical_code']);
	if ($_SESSION[$session_commande_name]['payment_technical_code'] == 'moneybookers') {
		$_SESSION[$session_commande_name]['moneybookers_payment_methods'] = vb($frm['moneybookers_payment_methods']);
	} else {
		$_SESSION[$session_commande_name]['moneybookers_payment_methods'] = '';
	}
	$_SESSION[$session_commande_name]['cgv'] = vn($frm['cgv']);
	$_SESSION[$session_commande_name]['document'] = vn($frm['document']);
	$_SESSION[$session_commande_name]['document1'] = vn($frm['document1']);
	$_SESSION[$session_commande_name]['document2'] = vn($frm['document2']);
	$_SESSION[$session_commande_name]['document3'] = vn($frm['document3']);
	$_SESSION[$session_commande_name]['numero'] = vn($frm['numero']);
	$_SESSION[$session_commande_name]['date_fin_validite'] = vn($frm['date_fin_validite']);
}

/**
 * Crée ou modifie une commande en base de données, ainsi que les produits commandés
 *
 * @param array $order_infos
 * @param array $articles_array
 * @return
 */
function create_or_update_order($order_infos, &$articles_array)
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
	
	// Si tous les produits ont été supprimés de la commande, on initialise le tableau
	if (empty($articles_array)) {
		$articles_array = array();
	}
	// Vérifie si id statut existe
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
			// On ne laisse pas d'index inutile, ça complique la lecture lors du déboguage
			unset($order_infos[$key]);
		}
	}
	if (!empty($order_infos['bill_mode']) && $order_infos['bill_mode']!='commander') {
		$form_usage = $order_infos['bill_mode'];
		} else {
		$form_usage = 'order';
	}
	handle_specific_fields($order_infos, $form_usage);
	
	// On veut que les données de l'adresse dans le tableau $order_info porte le même nom que les champs dans peel_commandes
	foreach (array('societe','nom','prenom','email','contact','adresse','code_postal','ville','pays') as $this_item) {
		if ($this_item == 'code_postal') {
			$this_field = 'zip';
		} elseif($this_item == 'contact') {
			$this_field = 'telephone';
		} else {
			$this_field = $this_item;
		}
		if (!empty($order_infos[$this_item . '1'])) {
			$order_infos[$this_field . '_bill'] = $order_infos[$this_item . '1'];
		}
		if (!empty($order_infos[$this_item . '2'])) {
			$order_infos[$this_field . '_ship'] = $order_infos[$this_item . '2'];
		}
	}
	// On complète les données si nécessaire
	if (!empty($GLOBALS['site_parameters']['mode_transport']) && (empty($order_infos['typeId']) || is_delivery_address_necessary_for_delivery_type($order_infos['typeId']))) {
		foreach(vb($order_infos['adresses_fields_array'], array()) as $this_item) {
			if (empty($order_infos[$this_item . '2']) && isset($order_infos[$this_item . '1'])) {
				$order_infos[$this_item . '2'] = $order_infos[$this_item . '1'];
			}
		}
	}
	// Avant de mettre à jour la commande, on récupère l'ancienne valeur du statut de paiement
	if (!empty($order_infos['id']) && in_array('peel_commandes', listTables())) {
		$statut_q = query('SELECT c.id_statut_paiement, c.total_points, c.points_etat, c.o_timestamp, sp.technical_code AS statut_paiement, sl.technical_code AS statut_livraison
			FROM peel_commandes c
			LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND ' . get_filter_site_cond('statut_paiement', 'sp') . '
			LEFT JOIN peel_statut_livraison sl ON sl.id=c.id_statut_livraison AND ' . get_filter_site_cond('statut_livraison', 'sl') . '
			WHERE c.id=' . intval($order_infos['id']) . ' AND ' . get_filter_site_cond('commandes', 'c') . '');
		$order_infos_ex = fetch_assoc($statut_q);
		
		$nb_q = query('SELECT ca.nb_envoi, ca.nb_download, ca.statut_envoi, ca.produit_id
			FROM peel_commandes_articles ca
			WHERE ca.commande_id=' . intval($order_infos['id']) . ' AND ' . get_filter_site_cond('commandes_articles', 'ca') . '');
		$prod_download_data = array();
		// Création d'un tableau pour contenir les infos sur le téléchargement de l'article, avant la suppression de la ligne de peel_commandes_articles
		while($order_article_infos_ex = fetch_assoc($nb_q)) {
			$prod_download_data[$order_article_infos_ex['produit_id']] = array('nb_envoi' => $order_article_infos_ex['nb_envoi'], 'nb_download' => $order_article_infos_ex['nb_download'],'statut_envoi' => $order_article_infos_ex['statut_envoi']);
		}
	} else {
		$order_infos_ex = null;
		$prod_download_data = null;
	}
	if (empty($order_infos['devise'])) {
		// Par exemple si !check_if_module_active('devises') : on prend la devise de la boutique
		$order_infos['devise'] = $GLOBALS['site_parameters']['code'];
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

	$order_infos['zone_tva'] = (!empty($order_infos['apply_vat'])?1:0);
	$order_infos['zone'] = vn($order_infos['zoneId']);
	$order_infos['delivery_orderid'] = vb($_SESSION['session_caddie']->delivery_orderid);
	if(!empty($order_infos['email1'])){
		// Si on change l'email associé à une commande, et qu'il correspond à un utilisateur en BDD, on ajuste l'id_utilisateur
		// Sinon, on laisse l'id_utilisateur comme il était (0 si la commande avait préalablement été créée sans association à un utilisateur, ou l'id d'un compte quelconque dont l'email n'est peut-être pas à jour)
		$searched_id_utilisateur = get_user_id_from_email($order_infos['email']);
		if(!empty($searched_id_utilisateur)) {
			$order_infos['id_utilisateur'] = $searched_id_utilisateur;
		}
	}
	if (check_if_module_active('tnt')) {
		$order_infos['xETTCode'] = vb($_SESSION['session_commande']['xETTCode']);
		$order_infos['expedition_date'] = $GLOBALS['web_service_tnt']->shippingDate;
		$order_infos['shipping_date'] = $GLOBALS['web_service_tnt']->shippingDate;
	}
	if (empty($order_infos['paiement']) && !empty($order_infos['payment_technical_code'])) {
		$order_infos['paiement'] = $order_infos['payment_technical_code'];
	}
	if (empty($order_infos['zone_franco']) && !empty($order_infos['zoneFranco'])) {
		$order_infos['zone_franco'] = $order_infos['zoneFranco'];
	}
	if (empty($order_infos['zone']) && !empty($order_infos['zoneId'])) {
		$order_infos['zone'] = $order_infos['zoneId'];
	}
	
	// Appel du hook qui viendra potentiellement remplacer le tableau de correspondance des champs de la table qui stock les commandes.
	$hook_result = call_module_hook('order_table_and_fields',array('order_infos' => $order_infos), 'array');
	if (!empty($hook_result['order_infos'])) {
		$order_infos = $hook_result['order_infos'];
	}
	// Hook qui gère l'intertion de commande n'utilisant pas l'architecture PEEL.
	// Le hook retourne le numéro de commande généré
	// si aucun hook n'existe, la fonction call_module_hook retourne le boolean true.
	$hook_result = call_module_hook('create_or_update_order', array('order_infos'=> $order_infos, 'articles_array'=> $articles_array), 'string');
	if(empty($hook_result) || !is_numeric($hook_result)) {
	$set_sql = array();
	$commandes_fields = get_table_field_names('peel_commandes', null, true);
	$commandes_fields_types = get_table_field_types('peel_commandes', null, true);
	foreach($order_infos as $peel_field=>$value_field) {
		if ($peel_field == 'specific_field_sql_set') {
			$set_sql[] = implode(',', $value_field);
		} elseif(in_array($peel_field, $commandes_fields)) {
			if (StringMb::strpos($commandes_fields_types[$peel_field], 'date') !== false) {
				$value = get_mysql_date_from_user_input($order_infos[$peel_field]);
			} else {
				$value = $order_infos[$peel_field];
			}
			$set_sql[] = $peel_field." = '" . nohtml_real_escape_string($value) . "'";
		}
	}
		// le hook n'a pas retourné de valeur indiquant une éventuelle action en BDD, donc on gère la commande avec PEEL.
		if (!empty($order_infos['commandeid'])) {
			// On met à jour la commande
			$sql = "UPDATE peel_commandes
					SET " . implode(',',$set_sql) . "
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
				// La recherche du numéro dans la commande n'a rien donnée, ou $id est vide.
				// Récuperation du numéro de commande le plus élevé pour le site. 
				$order_id_sql = 'SELECT MAX(c.order_id) as max_order_id
					FROM peel_commandes c
					WHERE ';
				if(!empty($GLOBALS['site_parameters']['multisite_disable']) || (!empty($GLOBALS['site_parameters']['multisite_disable_commandes']) && empty($GLOBALS['site_parameters']['multisite_forced_numbering_commandes']))) {
					// Pas de multisite : tous les site_id sont traités sans distinction
					// La ligne suivant est a priori inutile, mais c'est standard de le mettre quand même pour que ce soit propre au niveau de la sécurité d'accès aux données en cas de développements spécifiques
					$order_id_sql .= "" . get_filter_site_cond('commandes','c') . "";
				} elseif(empty($GLOBALS['site_parameters']['bill_number_regroup_sites_array']) || !isset($GLOBALS['site_parameters']['bill_number_regroup_sites_array'][$order_infos['site_id']])) {
					// Cas normal : chaque site_id a un enchainement de numérotation indépendant des autres site_id
					$order_id_sql .= "c.site_id='" . intval($order_infos['site_id']) . "' AND " . get_filter_site_cond('commandes', 'c', false, $order_infos['site_id']) . "";
				} else {
					// Cas inhabituel : regroupement de plusieurs site_id pour des sociétés
					$order_id_sql .= "c.site_id IN (" . real_escape_string(vb($GLOBALS['site_parameters']['bill_number_regroup_sites_array'][$order_infos['site_id']])) . ")";
				}
				
			// Attention : on ne doit pas mettre de "a_timestamp" ici, car ça dépend de si la commande est à passer en payer ou non => c'est géré dans update_order_payment_status
			$sql = "INSERT INTO peel_commandes
					SET " . implode(',',$set_sql) . "
					, order_id = 1+COALESCE((".$order_id_sql."),0)
				, code_facture = '" . nohtml_real_escape_string($code_facture) . "'
				, o_timestamp = '" . date('Y-m-d H:i:s', time()) . "' ";
			$order_infos['o_timestamp'] = date('Y-m-d H:i:s', time());
		}
		query($sql);
		if (empty($order_infos['commandeid'])) {
			$order_infos['commandeid'] = insert_id();
		}
		$order_id = $order_infos['commandeid'];
		if(!empty($order_infos['numero']) && $order_infos['numero']!=get_configuration_variable('format_numero_facture', vn($order_infos['site_id']), $_SESSION['session_langue'])) {
			// Si un numéro est spécifié par l'admin (donc n'est pas le format par défaut), alors on le met ici, sinon la création de numéro sera gérée dans update_order_payment_status car dépendant du status
			$numero = get_bill_number($order_infos['numero'], $order_id, false);
		} else {
			$numero = '';
		}
		// Qu'il soit vide ou non, on met à jour la colonne numero
		$sql = "UPDATE peel_commandes
			SET numero = '" . nohtml_real_escape_string($numero) . "'
			WHERE id = '" . intval($order_id) . "' AND " . get_filter_site_cond('commandes', null, false, $order_infos['site_id'], true) . "";
		query($sql);
		// On va enregistrer l'ensemble des produits commandés pour la commande $order_id
		// Tout d'abord on supprime ce qui existait en BDD pour cette commande
		if (!empty($order_infos_ex)) {
			if(check_if_module_active('stock_advanced')) {
				// On réincrémente les stocks si il y avait des articles précédemment stockés, puisqu'on fait comme si les produits commandés étaient tous annulés
				// On gèrera plus tard les stocks dans update_order_payment_status() appelé à la fin de cette fonction
				$product_infos_array = get_product_infos_array_in_order($order_id);
				foreach ($product_infos_array as $this_ordered_product) {
					// On réincrémente le stock uniquement pour les articles onstock=1, c'est-à-dire dont le stock est géré
						if(vb($GLOBALS['site_parameters']['stock_update_on_event'], 'order_payment') == 'order_delivery') {
							$status_decrement_stock = $GLOBALS['site_parameters']['delivery_status_decrement_stock'];
							$statut = $order_infos_ex['statut_livraison'];
						} else {
							$status_decrement_stock = $GLOBALS['site_parameters']['payment_status_decrement_stock'];
							$statut = $order_infos_ex['statut_paiement'];
						}
						if ($this_ordered_product['etat_stock'] == 1 && in_array($statut, explode(',', $status_decrement_stock))) {
						incremente_stock($this_ordered_product['quantite'] - $this_ordered_product['order_stock'], $this_ordered_product['produit_id'], $this_ordered_product['couleur_id'], $this_ordered_product['taille_id']);
						// On initialise les demande de réassort de stock lié à ce produit commandé en supprimant ensuite les lignes de peel_commandes_articles, donc pas besoin de faire :
						// query("UPDATE peel_commandes_articles
						// 	SET order_stock='0'
						// 	WHERE id='" . intval($this_ordered_product['id']) . "' AND " . get_filter_site_cond('commandes_articles') . "");
					}
				}
			}
			if(check_if_module_active('gifts')) {
				// On retire les points attribués préalablement, pour redonner les nouveaux points ensuite
				update_points($order_infos['total_points'], vn($order_infos['points_etat']), $order_id, null);
			}
		}
		// Appel du hook qui viendra potentiellement remplacer le tableau de correspondance des champs de la table qui stocke les produits commandés.
		$hook_result = call_module_hook('product_order_table_and_fields',array('order_infos' => $order_infos), 'array');
		if (!empty($hook_result['articles_array'])) {
			$articles_array = $hook_result['articles_array'];
		}
		// le hook n'a pas retourner de valeur indiquant une éventuelle action en BDD, donc on gère la commande avec PEEL.
		query("DELETE FROM peel_commandes_articles
			WHERE commande_id='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes_articles'));
		// On ajoute les articles à la table commandes_articles
		$i=1;
		foreach ($articles_array as $article_infos) {
			// On rend compatible des entrées du tableau 
			foreach($name_compatibility_array as $key => $value) {
				// On rend compatible des entrées du tableau article_infos
				if (isset($article_infos[$key])) {
					// Nécessite une conversion du nom de l'index.
					$article_infos[$value] = $article_infos[$key];
					// On ne laisse pas d'index inutile, ça complique la lecture lors du déboguage;
					unset($article_infos[$key]);
				}
			}
			// On construit un objet product à partir des informations de $article_infos.
			// On l'utilise pour des informations diverses, mais surtout pas pour les prix par exemple, qui doivent être ceux imposés par $article_infos
			// L'objet produit n'a pas besoin d'être initialisé avec toute les informations de $article_infos car on ne l'utilise que pour les parties sur lesquelles on n'a pas d'information dans $article_infos
			$product_infos = null;
			if (!empty($article_infos["data_check"])) {
				$product_infos['on_check'] = 1;
			} else {
				$product_infos['on_check'] = 0;
			}
			$product_object = new Product($article_infos['product_id'], $product_infos, false, $order_infos['lang'], true, !check_if_module_active('micro_entreprise'));
			// On n'a pas à indiquer si l'utilisateur est un revendeur ou non car on ne va pas utiliser les prix des configurations ci-après, on utilisera $article_infos
			$product_object->set_configuration(vn($article_infos['couleurId']), vn($article_infos['tailleId']), vb($article_infos['id_attribut']), false, true);
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
			if (empty($prod_download_data[$product_object->id]['statut_envoi'])) {
				// Si on est dans le cadre de la création de commande
				$statut_envoi = ($product_object->on_download == 1) ? "En attente" : "";
			} else {
				$statut_envoi = $prod_download_data[$product_object->id]['statut_envoi'];
			}
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
				if ($product_object->technical_code == 'over_cost') {
					$article_infos['product_name'] = '#'.$i.' '.$product_object->name;
					$i++;
				} else {
					$article_infos['product_name'] = $product_object->name;
				}
			}
			if (check_if_module_active('product_references_by_options')) {
				// On utilise get_possible_attributs pour récupèrer les attributs associés au produit pour pouvoir récupérer l'id de l'option d'attribut
				$possible_attributes_with_single_options = $product_object->get_possible_attributs('infos', false, get_current_user_promotion_percentage(), display_prices_with_taxes_active(), check_if_module_active('reseller') && is_reseller(), true, true, false, true); 
				foreach($possible_attributes_with_single_options as $this_nom_attribut_id => $this_options_array) {
					foreach($this_options_array as $this_attribut_id) {
						if($this_attribut_id) {
							// on récupère la référence de l'option d'attribut
							$article_infos['reference'] = get_reference_option_attribut($this_attribut_id);
						}
					}
				}
				if (empty($article_infos['reference'])) {
					// On utilise configuration_attributs_list pour récupèrer l'id des options d'attributs associés quand le produit en a plusieurs
					if (!empty($product_object->configuration_attributs_list)) {
						$attributs_list_array = explode('§', vb($product_object->configuration_attributs_list));
						foreach($attributs_list_array as $this_attributs_list) {
							$this_attributs_list_array = explode("|", $this_attributs_list);
							// On récupère l'id de l'option sélectionnée si format est attribut_id|option_id, ou le texte si le format est attribut_id|0|texte
							$article_infos['reference'] = get_reference_option_attribut(end($this_attributs_list_array));
						}
					}
				}
				if (empty($article_infos['reference'])) {
					$article_infos['reference'] = get_product_reference_by_option($product_object);
				}
			}
			if (empty($article_infos['reference'])) {
				$article_infos['reference'] = $product_object->reference;
			}
			if (empty($article_infos['categorie_id'])) {
				$article_infos['categorie_id'] = $product_object->categorie_id;
			}
			if (empty($article_infos['produit_id'])) {
				$article_infos['produit_id'] = $product_object->id;
			}
			if (check_if_module_active('conditionnement')) {
				// Les produits sont conditionnés sous forme de lot => ici on sauvegarde la taille des lots de conditionnement
				$article_infos['conditionnement'] = $product_object->conditionnement;
			}		
			if (check_if_module_active('tnt')) {
				$article_infos['tnt_parcel_number'] = vb($article_infos['tnt_parcel_number']);
				$article_infos['tnt_tracking_url'] = vb($article_infos['tnt_tracking_url']);
			}
			$article_infos['nom_attribut'] = str_replace('<br />', "\r\n", $attribut);
			$article_infos['attributs_list'] = vb($article_infos['id_attribut']);
			if(check_if_module_active('listecadeau') && !empty($article_infos['giftlist_owners'])) {
				$article_infos['listcadeaux_owner'] = vn($article_infos['giftlist_owners']);
			}
			$article_infos['prix_option_ht'] = vb($article_infos['option_ht']);
			$article_infos['prix_option'] = vb($article_infos['option']);
			
			if(isset($article_infos['data_check'])) {
				$article_infos['email_check'] = vb($article_infos['data_check']['email_check']);
				$article_infos['nom_check'] = vb($article_infos['data_check']['nom_check']);
				$article_infos['prenom_check'] = vb($article_infos['data_check']['prenom_check']);
			}	
			$article_infos['on_download'] = intval($product_object->on_download);
			$article_infos['statut_envoi'] = $statut_envoi;
			$article_infos['nb_envoi'] = vn($prod_download_data[$product_object->id]['nb_envoi']);
			$article_infos['nb_download'] = vn($prod_download_data[$product_object->id]['nb_download']);
			$article_infos['prix_achat_ht'] = $product_object->get_supplier_price(false);
			if (isset($article_infos['tailleId'])) {
				$article_infos['taille_id'] = vn($article_infos['tailleId']);
			}	
			if (isset($article_infos['couleurId'])) {
				$article_infos['couleur_id'] = vn($article_infos['couleurId']);
			}
			$article_infos['nom_produit'] = vn($article_infos['product_name']);
			$article_infos['commande_id'] = intval($order_id);
			$article_infos['site_id'] = intval(get_site_id_sql_set_value($order_infos['site_id']));

			$set_sql = array();
			$commandes_produits_fields = get_table_field_names('peel_commandes_articles', null, true);
			foreach($article_infos as $peel_field=>$value) {
				if(in_array($peel_field, $commandes_produits_fields)) {
					$set_sql[] = $peel_field." = '" . nohtml_real_escape_string($value) . "'";
				}
			}
			$sql = "INSERT INTO peel_commandes_articles SET ";
			$sql .= implode(',',$set_sql);
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
		// output_create_or_update_order sera affiché dans le fichier admin_haut.
		$GLOBALS['output_create_or_update_order'] = update_order_payment_status($order_id, $order_infos['statut_paiement'], defined('IN_PEEL_ADMIN'), $order_infos['statut_livraison'], vb($order_infos['delivery_tracking']), true, vb($order_infos['payment_technical_code']));
	} else {
		$order_id = $hook_result;
	}
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
	if($order_object = fetch_object($result)) {
		$order_infos = get_order_infos_array($order_object);
		$user = get_user_information($order_object->id_utilisateur);

		$custom_template_tags['ORDER_ID'] = $order_object->order_id;
		$custom_template_tags['NOM_FAMILLE'] = StringMb::htmlspecialchars_decode($user['nom_famille'], ENT_QUOTES);
		$custom_template_tags['CIVILITE'] = $user['civilite'];
		$custom_template_tags['PRENOM'] = StringMb::htmlspecialchars_decode($user['prenom'], ENT_QUOTES);
		$custom_template_tags['TYPE'] = $order_object->type;
		$custom_template_tags['COLIS'] = $order_object->delivery_tracking;
		$custom_template_tags['DATE'] = get_formatted_date($order_object->o_timestamp, 'short', 'long');
		$custom_template_tags['MONTANT'] = fprix($order_object->montant, true);
		$custom_template_tags['PAIEMENT'] = get_payment_name($order_object->paiement);
		$custom_template_tags['CLIENT_INFOS_BILL'] = StringMb::htmlspecialchars_decode($order_infos['client_infos_bill'], ENT_QUOTES);
		$custom_template_tags['CLIENT_INFOS_SHIP'] = StringMb::htmlspecialchars_decode($order_infos['client_infos_ship'], ENT_QUOTES);
		$custom_template_tags['COUT_TRANSPORT'] = (display_prices_with_taxes_active()? fprix($order_object->cout_transport, true) . " " . $GLOBALS['STR_TTC'] : fprix($order_object->cout_transport_ht, true) . " " .$GLOBALS['STR_HT']);
		$custom_template_tags['URL_FACTURE'] = get_site_wwwroot($order_object->site_id, $_SESSION['session_langue']) . '/factures/commande_pdf.php?code_facture=' . urlencode($order_object->code_facture) . '&mode=facture';
		$custom_template_tags['BOUGHT_ITEMS'] = '';
		$custom_template_tags['COMMENT'] = $order_object->commentaires;
		$document_attachment = array();
		if (!empty($order_object->document)) {
			$document_attachment['path_file_attachment'][0] = $GLOBALS['uploaddir'].'/';
			$document_attachment['name'][0] = $order_object->document;
			$document_attachment['type-mime'][0] = "application/octet-stream";
		}
		$product_infos_array = get_product_infos_array_in_order($order_id, $order_object->devise, $order_object->currency_rate);
		foreach ($product_infos_array as $this_ordered_product) {
			$custom_template_tags['BOUGHT_ITEMS'] .= $this_ordered_product["product_text"] . "\n";
			$custom_template_tags['BOUGHT_ITEMS'] .= $GLOBALS['STR_QUANTITY'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $this_ordered_product["quantite"] . "\n";
			$custom_template_tags['BOUGHT_ITEMS'] .= $GLOBALS['STR_PRICE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . (display_prices_with_taxes_active()? fprix($this_ordered_product["total_prix"], true) . ' ' . $GLOBALS['STR_TTC']  : fprix($this_ordered_product["total_prix_ht"], true) . ' ' . $GLOBALS['STR_HT']) . "\n\n";
		}
		foreach ($product_infos_array as $this_ordered_product) {
			if(!empty($this_ordered_product['technical_code'])) {
				send_email($order_object->email, '', '', 'confirm_ordered_'.$this_ordered_product['technical_code'], $custom_template_tags, null, $GLOBALS['support_commande']);
				send_email($GLOBALS['support_commande'], '', '', 'confirm_ordered_'.$this_ordered_product['technical_code'], $custom_template_tags, null, $GLOBALS['support_commande'],true, false, true, null, $document_attachment);
			}
		}

		$template_technical_codes_array = array('email_commande_' . $order_object->paiement, 'email_commande');
	
		send_email($order_object->email, '', '', $template_technical_codes_array, $custom_template_tags, null, $GLOBALS['support_commande']);
		send_email($GLOBALS['support_commande'], '', '', $template_technical_codes_array, $custom_template_tags, null, $GLOBALS['support_commande'],true, false, true, null, $document_attachment);
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
	if (!empty($GLOBALS['site_parameters']['send_mail_order_admin_display_technical_id'])) {
		$custom_template_tags['ORDER_ID'] = $order_id;
	} else {
		$custom_template_tags['ORDER_ID'] = $order_object->order_id;
	}
	$custom_template_tags['EMAIL'] = $order_object->email;
	$custom_template_tags['SITE'] = $GLOBALS['site'];
	$custom_template_tags['MONTANT'] = fprix($order_object->montant, true);
	$custom_template_tags['O_TIMESTAMP'] = get_formatted_date($order_object->o_timestamp);
	$custom_template_tags['PAIEMENT'] = get_payment_name($order_object->paiement);
	$custom_template_tags['COMMENT'] = $order_object->commentaires;

	send_email($GLOBALS['support_commande'], '', '', 'send_mail_order_admin', $custom_template_tags, null, $GLOBALS['support_commande']);
}

/**
 * get_payment_name()
 *
 * @param mixed $id_or_code Id or technical_code of the payment mean
 * @param mixed $mode
 * @return
 */
function get_payment_name($id_or_code, $mode = 'nom')
{
	static $tab_paiement_by_sql;
	$sql = 'SELECT p.nom_' . $_SESSION['session_langue'] . ' AS nom, p.technical_code
		FROM peel_paiement p
		WHERE (p.id="' . intval($id_or_code) . '" OR p.technical_code="' . nohtml_real_escape_string($id_or_code) . '") AND ' .  get_filter_site_cond('paiement', 'p') . '';
	if(!isset($tab_paiement_by_sql[md5($sql)])) {
		$query = query($sql);
		$tab_paiement_by_sql[md5($sql)] = fetch_assoc($query);
		if($tab_paiement_by_sql[md5($sql)] === null) {
			// Pour pouvoir faire test avec isset par la suite sur la variable static, on met false à la place de null
			$tab_paiement_by_sql[md5($sql)] = false;
		}
	}
	if (!empty($tab_paiement_by_sql[md5($sql)])) {
		if($mode == 'nom') {
			return $tab_paiement_by_sql[md5($sql)]['nom'];
		} else {
			return $tab_paiement_by_sql[md5($sql)]['technical_code'];
		}
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
		$query = query($sql_paiement);
		if ($tab_paiement = fetch_assoc($query)) {
			$payment_status_name_by_id[$id] = StringMb::html_entity_decode_if_needed($tab_paiement['nom']);
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
	static $tab_livraison_by_id;
	if(!isset($tab_livraison_by_id[$id])) {
		$sql_livraison = 'SELECT nom_' . $_SESSION['session_langue'] . ' AS nom
			FROM peel_statut_livraison
			WHERE id="' . intval($id) . '" AND ' . get_filter_site_cond('statut_livraison');
		$res_livraison = query($sql_livraison);
		$tab_livraison_by_id[$id] = fetch_assoc($res_livraison);
	}
	if (!empty($tab_livraison_by_id[$id])) {
		return StringMb::html_entity_decode_if_needed($tab_livraison_by_id[$id]['nom']);
	} elseif (!empty($GLOBALS['site_parameters']['mode_transport'])) {
		return $id;
	} else {
		return '-';
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
	$threshold_reached = false;
	// Frais de port gratuits si le total principal (TTC ou HT suivant configuration boutique) des produits est > au seuil.
	if ((!empty($GLOBALS['site_parameters']['nb_product']) && $GLOBALS['site_parameters']['nb_product'] <= $nb_product) || (empty($total_weight) && empty($total_price))) {
		// Frais de port gratuits si plus de nb_product commandés ou si aucun poids ni aucun montant
		return null;
	} elseif (!empty($GLOBALS['site_parameters']['nb_product'])) {
		$add_for_free_delivery['products'] = $GLOBALS['site_parameters']['nb_product'] - $nb_product;
	}
	$cart_measurement_max_reached = get_cart_measurement_max($_SESSION['session_caddie']);
		if (!empty($cart_measurement_max_reached)) {
			// dans ce cas les frais de port sont spécifique, et ne sont pas calculé par la boutique.
			return null;
		}
	
	if (!empty($zone_id)) {
		$query = query('SELECT z.*
			FROM peel_zones z
			WHERE id = "' . intval($zone_id) . '" AND ' . get_filter_site_cond('zones', 'z') . '
			LIMIT 1');
		$result_zones = fetch_assoc($query);
		if (!empty($result_zones['on_franco'])) {
			if ((empty($result_zones['applied_franco_mode']) || $result_zones['applied_franco_mode'] == 'amount')) {
				$amount_used = floatval((check_if_module_active('reseller') && is_reseller()) ? $result_zones['on_franco_reseller_amount'] : $result_zones['on_franco_amount']);
				if ($amount_used <= round($total_price, 2)) {
						$threshold_reached = true;
				} else {
					$add_for_free_delivery['amount'] = $amount_used - round($total_price, 2);
				}
			} else {
				if ($result_zones['on_franco_weight'] <= round($total_weight, 2)) {
					$threshold_reached = true;
				} else {
					$threshold_reached = false;
					$add_for_free_delivery['weight'] = $result_zones['on_franco_weight'] - round($total_weight, 2);
				}
			}
		}
		if (!empty($result_zones['on_franco_nb_products'])) {
			// Zone franco de port
			if($result_zones['on_franco_nb_products'] <= $nb_product) {
				$threshold_reached = true;
			} else {
				$threshold_reached = false;
				$add_for_free_delivery['products'] = $result_zones['on_franco_nb_products'] - $nb_product;
			}
		}
	}
	if (!empty($type_id)) {
		// On va regarder ensuite le franco de port pour le mode de livraison. On fait la vérification après la zone, car c'est le franco par type qui est prioritaire, donc on remplace le franco de port récupérer pour la zone par le franco par type. 
		$query = query('SELECT t.on_franco_amount
			FROM peel_types t
			WHERE id = "' . intval($type_id) . '" AND ' . get_filter_site_cond('types', 't') . '
			LIMIT 1');
		$result_types = fetch_assoc($query);
		if ($result_types['on_franco_amount']>0) {
			$amount_used = floatval($result_types['on_franco_amount']);
			// Zone franco de port
			if ($amount_used <= round($total_price, 2)) {
				$threshold_reached = true;
			} else {
				$threshold_reached = false;
				$add_for_free_delivery['amount'] = $amount_used - round($total_price, 2);
			}
		}
	}
	if (!empty($threshold_reached)) {
		// Le seuil d'exonération a été atteint pour le franco par zone ou par type de livraison. On retourne null pour signifier qu'il faut exonérer les frais de port.
		return null;
	}
	// Les seuils d'exonérations de frais de port de la zone et du type sont prioritaire sur le seuil d'exonération de frais de port généraux.
	// Donc on ne teste ici les exonérations générales que si il n'y a pas de configuration d'exonération par zone
	if (empty($add_for_free_delivery['amount'])) {
		// Cas où un seuil de commande minimal est défini pour l'utilisateur de manière globale au niveau de la configuration du site
		$seuil_total_used = floatval((check_if_module_active('reseller') && is_reseller()) ? $GLOBALS['site_parameters']['seuil_total_reve'] : $GLOBALS['site_parameters']['seuil_total']);
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
	$delivery_cost_infos = array('cost_ht' => 0, 'tva' => 0);
	if (empty($nb_product)) {
		// si nb_product est vide, on ne cherche pas à calculer les frais de ports.
		return $delivery_cost_infos;
	}
	$key_weight_and_price = $type_id . $zone_id . $total_weight . $total_price;
	$req_tarif_seuil = query('SELECT COUNT(*) nb_tarif_seuil FROM peel_tarifs WHERE ' . get_filter_site_cond('tarifs') . ' AND on_franco="1" AND ' . (!empty($type_id)?'type="' . intval($type_id) . '"':'tarif>0') . (!empty($zone_id)?' AND zone = "' . intval($zone_id) . '"':''));
	$tarif_seuil = fetch_assoc($req_tarif_seuil);
	if ($tarif_seuil['nb_tarif_seuil'] == 0) {
		$add_for_free_delivery = get_needed_for_free_delivery($total_weight, $total_price, $type_id, $zone_id, $nb_product);
	} else {
		// Si il y a un seuil dans les tarifs
		$add_for_free_delivery = true;
	}
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
			$tarifs_sql = 'SELECT tarif, poidsmax, totalmax, tva, on_franco, on_franco_amount
				FROM peel_tarifs
				WHERE ' . get_filter_site_cond('tarifs') . ' AND ' . (!empty($type_id)?'type="' . intval($type_id) . '"':'tarif>0') . (!empty($zone_id)?' AND zone = "' . intval($zone_id) . '"':'') . '  AND (poidsmin<="' . floatval($total_weight) . '" OR poidsmin=0) AND (poidsmax>="' . floatval($total_weight) . '" OR poidsmax=0) AND (totalmin<="' . floatval($total_price) . '" OR totalmin=0) AND (totalmax>="' . floatval($total_price) . '" OR totalmax=0)
				ORDER BY ' . $order_by . '
				LIMIT 1';
			$req = query($tarifs_sql);
			if ($this_tarif = fetch_assoc($req)) {
				if ($this_tarif['on_franco'] && $this_tarif['on_franco_amount'] > 0 && $this_tarif['on_franco_amount'] <= $total_price) {
					// Si on a mis en place un seuil d'exonération de frais de port dans le tarif, on l'applique
					$delivery_cost_infos['cost_ht'] = 0;
					$delivery_cost_infos['tva'] = 0;
				} else {
					$delivery_cost_infos['cost_ht'] = $this_tarif['tarif'] / (1 + $this_tarif['tva'] / 100);
					if (!check_if_module_active('micro_entreprise')) {
						$delivery_cost_infos['tva'] = $this_tarif['tva'];
					} else {
						$delivery_cost_infos['tva'] = 0;
					}
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
 * Fonction permettant l'affichage des taux de TVA dans les factures
 *
 * @param string $code_facture
 * @return
 */
function get_vat_array($code_facture, $bill_mode = array())
{
	$sql = 'SELECT SUM(pca.tva) AS products_tva_for_this_percent, pca.tva_percent, pca.reference, pc.tva_cout_transport, ROUND(pc.tva_cout_transport/pc.cout_transport_ht*100,2) AS cout_transport_tva_percent, pc.tva_tarif_paiement, ROUND(pc.tva_tarif_paiement/pc.tarif_paiement_ht*100,2) AS tarif_paiement_tva_percent, tva_small_order_overcost, ROUND(tva_small_order_overcost/(small_order_overcost_amount-tva_small_order_overcost)*100,2) AS small_order_overcost_tva_percent
		FROM peel_commandes_articles pca
		INNER JOIN peel_commandes pc ON pca.commande_id = pc.id AND ' . get_filter_site_cond('commandes', 'pc') . '
		WHERE pc.code_facture = "' . nohtml_real_escape_string($code_facture) . '" AND ' . get_filter_site_cond('commandes_articles', 'pca') . '
		GROUP BY pc.id, pca.tva_percent';
	$query = query($sql);
	$total_tva = array();
	$i = 0;
	if (!empty($query)) {
		while ($result = fetch_assoc($query)) {
			if (!empty($GLOBALS['calculate_order_total_from_product_list'])) {
				// Pour cette commande on doit prendre les totaux qui viennent de la liste de produits, et pas ce qui est en BDD. Du coup pour le détail de la TVA il faut exclure les produits concernés dans le détail,puisque ces produits n'apparaissent pas dans la facture.
				foreach (vb($GLOBALS['site_parameters'][$bill_mode.'_product_excluded'], array()) as $this_excluded_reference) {
					if (StringMb::strpos($result['reference'], $this_excluded_reference) === 0 && StringMb::strpos($result['reference'], 'commission') === false) {
						continue(2);
					}
				}
			}
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
function get_order_infos_array($order_object, $product_infos_array=array(), $bill_mode = null)
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
	if(StringMb::substr_count(trim($order_object->adresse_bill), "\n")>0) {
		if(StringMb::strlen($order_object->adresse_bill)<40) {
			// Adresse courte mais sur plusieurs lignes : on gagne de la place en retirant les sauts de ligne
 			$order_object->adresse_bill = str_replace("\n", ' - ', $order_object->adresse_bill);
		} else {
			// Adresse longue sur plusieurs lignes : on gagne de la place en mettant le pays à côté de la ville
			$separator_before_country = ' - ';
			if(StringMb::substr_count($order_object->adresse_bill, "\n") >= 2) {
				$separator_before_email = ' - ';
			}
		}
	}
	$order_infos['client_infos_bill'] = (!empty($order_object->societe_bill)?$order_object->societe_bill . "\n":'')
	 . trim($order_object->nom_bill . " " . $order_object->prenom_bill)
	 . "\n" . $order_object->adresse_bill;
	if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
		foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
			if(StringMb::substr($this_title, 0, 4) == 'STR_' && isset($GLOBALS[$this_title])) {
				$this_text = $GLOBALS[$this_title];
			} else {
				$this_text = $this_title;
			}
			if ((StringMb::substr($this_field, -5) == '_bill') && !empty($order_object->$this_field)) {
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
	if(StringMb::substr_count($order_object->adresse_ship, "\n")>0) {
		if(StringMb::strlen($order_object->adresse_ship)<40) {
			// Adresse courte mais sur plusieurs lignes : on gagne de la place en retirant les sauts de ligne
 			$order_object->adresse_ship = str_replace("\n", ' - ', $order_object->adresse_ship);
		} else {
			// Adresse longue sur plusieurs lignes : on gagne de la place en mettant le pays à côté de la ville
			$separator_before_country = ' - ';
			if(StringMb::substr_count($order_object->adresse_ship, "\n") >= 2) {
				$separator_before_email = ' - ';
			}
		}
	}
	$order_infos['client_infos_ship'] = (!empty($order_object->societe_ship)?$order_object->societe_ship . "\n":'')
	 . trim($order_object->nom_ship . " " . $order_object->prenom_ship)
	 . "\n" . $order_object->adresse_ship;
	if (!empty($GLOBALS['site_parameters']['order_specific_field_titles'])) {
		foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_field => $this_title) {
			if(StringMb::substr($this_title, 0, 4) == 'STR_' && isset($GLOBALS[$this_title])) {
				$this_text = $GLOBALS[$this_title];
			} else {
				$this_text = $this_title;
			}
			if ((StringMb::substr($this_field, -5) == '_ship') && !empty($order_object->$this_field)) {
				// On a ajouté dans la table utilisateurs un champ qui concerne l'adresse de facturation => Il faut préremplir les champs du formulaire d'adresse de facturation avec ces infos.
				$order_infos['client_infos_ship'] .= "\n" . $this_text . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . vb($order_object->$this_field);
			}
		}
	}
	$order_infos['client_infos_ship'] .= "\n" . trim($order_object->zip_ship . " " . $order_object->ville_ship)
	 . $separator_before_country . $order_object->pays_ship
	 . "\n" . get_formatted_phone_number($order_object->telephone_ship)
	 . $separator_before_email . $order_object->email_ship;
	if (empty($order_object->num_tva)) {
		$client = get_user_information($order_object->id_utilisateur);
		if (!empty($client) && !empty($client['intracom_for_billing'])) {
			// Ajout du numéro de TVA intracommunautaire qui n'est pas dans $client_infos_bill
			$order_infos['client_infos_bill'] .= "\n" . $GLOBALS['STR_VAT_INTRACOM'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $client['intracom_for_billing'];
		}
	} else {
		// Ajout du numéro de TVA intracommunautaire qui est dans $client_infos_bill (num_tva)
		$order_infos['client_infos_bill'] .= "\n" . $GLOBALS['STR_VAT_INTRACOM'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $order_object->num_tva;
	}
    
    // Code Chorus
    if (!empty($order_object->chorus_code)) {		
		// Ajouter la ligne Code chorus dans le tableau
     	$order_infos['client_infos_bill'] .= "\n" . $GLOBALS['STR_CODE_CHORUS'] . 
        $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " .  $order_object->chorus_code;            
	} 
    //Fin
    // siret
    if (!empty($order_object->siret)) {		
		// Ajouter la ligne siret dans le tableau
     	$order_infos['client_infos_bill'] .= "\n" . $GLOBALS['STR_SIRET'] . 
        $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " .  $order_object->siret;            
	} 
    //Fin 
	$order_infos['client_infos_ship'] = trim(str_replace(array("\n ", "\n\n\n\n", "\n\n\n", "\n\n"), "\n", $order_infos['client_infos_ship']));

	if (!empty($product_infos_array) && !empty($GLOBALS['calculate_order_total_from_product_list'])) {
		// On va calculer les totaux à partir de la liste de produits. C'est utile si on enlève un produit de la commande de l'affichage par exemple.
		$montant = 0;
		$total_tva = 0;
		$montant_ht = 0;
		foreach($product_infos_array as $product_infos) {
			$montant += $product_infos['total_prix'];
			$total_tva += $product_infos['tva'];
			$montant_ht += $product_infos['total_prix_ht'];
		}
	} else {
		// Cas standard, il faut prendre les informations directement de peel_commandes.
		$montant = $order_object->montant;
		$total_tva = $order_object->total_tva;
		$montant_ht = $order_object->montant_ht;
	}
	$order_infos['net_infos_array'] = array("avoir" => fprix($order_object->avoir, true, $order_object->devise, true, $order_object->currency_rate, true),
		"total_remise" => fprix($order_object->total_remise, true, $order_object->devise, true, $order_object->currency_rate, true),
		"cout_transport" => fprix($order_object->cout_transport, true, $order_object->devise, true, $order_object->currency_rate, true),
		"totalttc" => fprix($montant+$order_object->avoir, true, $order_object->devise, true, $order_object->currency_rate, true),
		"montant" => fprix($montant, true, $order_object->devise, true, $order_object->currency_rate, true),
		"total_tva" => fprix($total_tva, true, $order_object->devise, true, $order_object->currency_rate, true),
		"montant_ht" => fprix($montant_ht, true, $order_object->devise, true, $order_object->currency_rate, true),
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
	$order_infos['tva_infos_array'] = array("total_tva" => fprix($total_tva, true, $order_object->devise, true, $order_object->currency_rate, true));
	$distinct_total_vat = get_vat_array($order_object->code_facture, $bill_mode);
	foreach($distinct_total_vat as $vat_percent_name => $value) {
		if (StringMb::substr($vat_percent_name, 0, strlen('transport')) == 'transport') {
			// La variable $vat_percent_name contient la valeur qui sera affichée en face du montant de la TVA sur la facture
			// Dans le cas de la TVA sur le transport, le mot "tranport" est présent dans le nom. Pour connaître le taux de TVA dans ce cas, il faut supprimer le mot "transport" du nom, et supprimer les espaces.
			$vat_percent = trim(StringMb::substr($vat_percent_name, strlen('transport')));
		} else {
			// Dans tous les autres cas, le nom de la TVA est le taux de la TVA, il n'y a pas d'information supplémentaire dans le nom.
			$vat_percent = $vat_percent_name;
		}
		if ($vat_percent>0 && $value>0) {
			$order_infos['tva_infos_array']["distinct_total_vat"][$vat_percent_name] = fprix($value, true, $order_object->devise, true, $order_object->currency_rate, true);
		}
	}
	if (!empty($order_object->code_promo)) {
		$order_infos['code_promo_text'] = $GLOBALS['STR_CODE_PROMO_REMISE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . $order_object->code_promo;
		$code_promo_query = query('SELECT code_promo, valeur_code_promo, percent_code_promo, devise
			FROM peel_commandes pc
			WHERE id="'.intval($order_object->id).'" AND code_promo="' . nohtml_real_escape_string($order_object->code_promo) . '" AND ' . get_filter_site_cond('commandes', 'pc') . '');
		if ($cp = fetch_assoc($code_promo_query)) {
			$order_infos['code_promo_text'] .= ' - ' . get_discount_text($cp['valeur_code_promo'], $cp['percent_code_promo'], true, $cp['devise']) . '';
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
function get_product_infos_array_in_order($order_id, $devise = null, $currency_rate = null, $order_by = null, $add_total_prix_attribut = false, $invoice_product_excluded = null)
{
	if(empty($order_by) && !empty($GLOBALS['site_parameters']['order_article_order_by']) && $GLOBALS['site_parameters']['order_article_order_by'] == 'name') {
		$order_by = 'oi.nom_produit ASC';
	} elseif(!empty($GLOBALS['site_parameters']['order_article_order_by']) && $GLOBALS['site_parameters']['order_article_order_by'] == 'reference') {
		$order_by = 'oi.reference ASC';
	} else {
		$order_by = 'oi.id ASC';
	}

	$product_infos_array = array();
	
	call_module_hook('product_infos_array_in_order', array('order_id' => vb($order_id)));
	
	$sql = "SELECT oi.*
		FROM peel_commandes_articles oi
		WHERE commande_id='" . intval($order_id) . "' AND " . get_filter_site_cond('commandes_articles', 'oi') . "
        GROUP BY oi.nom_attribut, oi.attributs_list, oi.produit_id, oi.reference, oi.nom_produit, oi.prix, oi.couleur_id, oi.taille_id
		ORDER BY " . $order_by;
	// NB : on ne met pas dans le SQL :
	// , p.technical_code, m.nom_".$_SESSION['session_langue']." AS brand_name
	// 	LEFT JOIN peel_produits p ON p.id=oi.produit_id AND " . get_filter_site_cond('produits', 'p') . "
	//	LEFT JOIN peel_marques m ON m.id = p.id_marque AND " . get_filter_site_cond('marques', 'm') . "
	//	LEFT JOIN peel_produits_categories pc ON p.id = pc.produit_id
	//	LEFT JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
	// pour permettre un chargement séparé, ce qui par ailleurs allège les jointures dans cette requête et permet appel en AJAX séparé aux produits
	$qid_items = query($sql);
	while ($prod = fetch_assoc($qid_items)) {
		if (!empty($invoice_product_excluded)) {
			// On souhaite exclure des produits de la liste des produits commandés, 
			// Le paramètre est un tableau, qui contient les références à exclure.
			foreach ($invoice_product_excluded as $this_excluded_reference) {
				if (StringMb::strpos($prod['reference'], $this_excluded_reference) === 0 && StringMb::strpos($prod['reference'], 'commission') === false) {
					// Si le produit commandé contient la référence à exclure et que ce n'est pas une commission, on passe ce produit.
					// calculate_total_from_product_list : Dans le cas où l'on exclue un produit de la liste des produits commandé, il faut recalculer les totaux de la commande à partir des produits commandé, au lieu des totaux qui ont été calculé pour la commande entière et stockée dans peel_commandes.
					$GLOBALS['calculate_order_total_from_product_list'] = true;
					continue(2);
				}
			}
		}
		$sql_join_array = array("LEFT JOIN peel_marques m ON m.id = p.id_marque AND " . get_filter_site_cond('marques', 'm'), "LEFT JOIN peel_produits_categories pc ON p.id = pc.produit_id", "LEFT JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c'));
		$prod_add_infos = get_product_infos($prod['produit_id'], true, array('p.technical_code', "c.nom_".$_SESSION['session_langue']." AS category_name", "m.nom_".$_SESSION['session_langue']." AS brand_name"), 1, true, false, null, null, array(), $sql_join_array);
		if(empty($prod_add_infos)) {
			$prod_add_infos = array('technical_code' => '', 'brand_name' => '');
		}
		$prod = array_merge($prod, $prod_add_infos);
		// On crée la description d'un produit facturé
		$category_text = (!empty($prod['category_name']) && !empty($GLOBALS['site_parameters']['display_category_name_in_product_infos_in_order']) ? "\r\n" . $GLOBALS['STR_CATEGORY'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . StringMb::htmlspecialchars_decode($prod["category_name"], ENT_QUOTES) : "");
		$brand_text = (!empty($prod['brand_name']) && !empty($GLOBALS['site_parameters']['display_brand_name_in_product_infos_in_order']) ? "\r\n" . $GLOBALS['STR_BRAND'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . StringMb::htmlspecialchars_decode($prod["brand_name"], ENT_QUOTES) : "");
		$reference_text = (!empty($prod['reference']) ? "\r\n" . $GLOBALS['STR_REFERENCE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . StringMb::htmlspecialchars_decode($prod["reference"], ENT_QUOTES) : "");
		$couleur_text = (!empty($prod['couleur']) ? "\r\n" . $GLOBALS['STR_COLOR'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . StringMb::html_entity_decode_if_needed($prod['couleur']) : "");
		$taille_text = (!empty($prod['taille']) ? "\r\n" . $GLOBALS['STR_SIZE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . StringMb::html_entity_decode_if_needed($prod['taille']) : "");
		$poids_text = (!empty($prod['poids']) && $prod['poids']>0 ? "\r\n" . $GLOBALS['STR_WEIGHT_SHORT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . StringMb::htmlspecialchars_decode($prod["poids"], ENT_QUOTES) . ' ' . $GLOBALS["STR_GRAMMES"] : "");
		if ($prod['nom_attribut'] != '') {
			$attribut_text = "\r\n" . trim(StringMb::html_entity_decode_if_needed($prod['nom_attribut']));
			if($add_total_prix_attribut) {
				$attribut_text .= ($prod['total_prix_attribut'] > 0 ? "\r\n" . $GLOBALS['STR_OPTIONS_COST'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($prod['total_prix_attribut'], true) . ' ' . $GLOBALS['STR_TTC'] : '');
			}
		} else {
			$attribut_text = '';
		}
		$delai_text = (!empty($prod['delai_stock']) ? "\r\n" . $GLOBALS['STR_DELIVERY_STOCK'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . get_formatted_duration((intval($prod['delai_stock']) * 24 * 3600), false, true) : "");
		$commentaires_admin = (!empty($prod['commentaires_admin']) ? "\r\n" . $prod['commentaires_admin']: "");
		// Attention : un test !empty ne marche pas sur prix_option, percent_remise_produit et ecotaxe_ttc car au format "0.00"
		$option_text = ($prod['prix_option'] != 0 ? "\r\n" . $GLOBALS['STR_OPTION_PRIX'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . fprix($prod['prix_option'], true, $devise, true, $currency_rate) . "" : "");
		// ATTENTION : ne pas utiliser ici $prod['percent_remise_produit']
		// - Dans $prod['percent_remise_produit'] il y a l'ensemble des pourcentages de remise qui est indiqué, que ce soit liés au produit ou à l'utilisateur, ce qui a un intérêt technique et de déboguage.
		// Ce pourcentage ne prend pas en considération des réductions par montant effectuées, donc ça n'est pas le total de réduction en pourcentage
		// - $prod['remise'] est quant à lui la remise totale effectuée, donc c'est une information compréhensible par l'utilisateur.
		$remise_text = ($prod['remise'] > 0 ? "\r\n" . $GLOBALS['STR_PROMOTION_INCLUDE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . get_discount_text((display_prices_with_taxes_active()?$prod['remise']:$prod['remise_ht']), $prod['percent_remise_produit'] , display_prices_with_taxes_active(), $devise) : "");
		if (check_if_module_active('ecotaxe')) {
			// On affiche le montant de l'écotaxe dans la colonne dénomination du produit et non pas prix pour des raisons de largeur de colonne
			if (display_prices_with_taxes_active()) {
				$ecotaxe_text = ($prod['ecotaxe_ttc'] > 0) ? "\r\n" . (defined('IN_INVOICE_PDF') || defined('IN_INVOICE_HTML')?$GLOBALS['STR_ECOTAXE_INCLUDED']:$GLOBALS['STR_ECOTAXE_INCLUDE']) . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . fprix($prod['ecotaxe_ttc'], true, $devise, true, $currency_rate) : "";
			} else {
				$ecotaxe_text = ($prod['ecotaxe_ht'] > 0) ? "\r\n" . (defined('IN_INVOICE_PDF') || defined('IN_INVOICE_HTML')?$GLOBALS['STR_ECOTAXE_INCLUDED']:$GLOBALS['STR_ECOTAXE_INCLUDE']) . ' ' . $GLOBALS['STR_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ": " . fprix($prod['ecotaxe_ht'], true, $devise, true, $currency_rate) : "";
		}
		}
		$prod['product_technical_text'] = StringMb::html_entity_decode_if_needed($prod['nom_produit'] . vb($category_text) . vb($brand_text) . vb($reference_text) . vb($couleur_text) . vb($taille_text) . vb($attribut_text));
		$prod['product_text'] = StringMb::html_entity_decode_if_needed($prod['nom_produit'] . vb($category_text) . vb($brand_text) . vb($reference_text) . vb($couleur_text) . vb($taille_text) . vb($attribut_text) . vb($option_text) . vb($remise_text) . vb($ecotaxe_text) . vb($delai_text) . vb($commentaires_admin) . vb($poids_text));
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
 * @param mixed $amount_to_pay Ce paramètre est utilisé pour les paiements partiels.
 * @param boolean $allow_autosend
 * @param array $params Possibilité de définir des paramètres particuliers pour les moyens de paiement appelés via get_payment_form_XXXX qui les prendraient en compte, par exemple "title"
 * @param boolean $empty_type
 *
 * @return string
 */
function get_payment_form($order_id, $forced_type = null, $send_admin_email = false, $forced_amount_to_pay = 0, $allow_autosend = true, $params = array(), $empty_type = false)
{
	static $admin_email_sent;
	$output = '';

	$warn_payment_admin_email = $GLOBALS['support_commande'];
	
	$hook_result = call_module_hook('get_payment_form_bill_infos', array('order_id' => $order_id, 'params' => $params), 'array');
	if(!empty($hook_result)) {
		// Par exemple le module micro_-_entreprise définit le format des factures ici
		$order_id = $hook_result['order_id'];
		if (!empty($hook_result['sql'])) {
			$result = query($hook_result['sql']);
			$com = fetch_object($result);
		} elseif(!empty($hook_result['order_object'])) {
			$com = $hook_result['order_object'];
		}
		if (!empty($hook_result['warn_payment_admin_email'])) {
			$warn_payment_admin_email = $hook_result['warn_payment_admin_email'];
		}
	} else {
		$sql = 'SELECT *
			FROM peel_commandes
			WHERE id="' . intval($order_id) . '" AND ' . get_filter_site_cond('commandes');
		$result = query($sql);
		$com = fetch_object($result);
	}
	if(empty($com)) {
		return null;
	}
	if (empty($com->email)) {
		// si l'email est absent, ou que le format de l'email n'est pas le bon, on ne va pas plus loin sinon le module de paiement peut refuser la transaction
		return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_MODULE_PREMIUM_MANDATORY_EMAIL']))->fetch();
	} elseif(!empty($com->email) && !EmailOK($com->email)) {
		return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS["STR_ERR_EMAIL_BAD"]))->fetch();
	}
	$email_array = explode(',', str_replace(';', ',', $com->email));
	$com->email = trim($email_array[0]);
	
	call_module_hook('general_custom_params', array(), 'array');

	if(!empty($GLOBALS['t2_add_bill_systempay_link'])) {
		$forced_type = 'systempay';
	} elseif(!empty($GLOBALS['t2_add_bill_paybox_link'])) {
		$forced_type = 'paybox';
	} elseif(!empty($GLOBALS['t2_add_bill_paypal_link'])) {
		$forced_type = 'paypal';
	}

	
	if (!empty($forced_amount_to_pay)) {
		$amount_to_pay = $forced_amount_to_pay;
	} else {
		$amount_to_pay = floatval($com->montant);
	}
	if($amount_to_pay == 0 || !is_order_modification_allowed($com->o_timestamp)) {
		return null;
	}
	if (!empty($forced_type)) {
		// On gère des listes sur le modèle codetechnique1,!codetechnique2,... avec le ! qui permet d'exclure un type de paiement
		if(!is_array($forced_type)) {
			$forced_type = explode(',', $forced_type);
		}
		foreach($forced_type as $this_type) {
			if(StringMb::substr($this_type, 0, 1) == '!') {
				$types_excluded_array[] = StringMb::substr($this_type, 1);
			} else {
				$type[] = $this_type;
			}
		}
		if(!empty($type) && count($type) == 1) {
			$type = $type[0];
		}
	} else {
		// In $com->payment_technical_code is stored the "technical_code" found in peel_paiement
		$type = $com->paiement;
	}

	$hook_result = call_module_hook('get_payment_form_pre', array('order_id' => $order_id, 'type' => $type, 'amount_to_pay' => $amount_to_pay), 'string');

	if (!empty($GLOBALS['site_parameters']['payment_multiple']) && StringMb::strpos($type, '#')!==false) {
		// Si le paiement multiple est actif, et qu'il y a le caractère # dans le code technique du moyen de paiement, on récupère les informations pour faire un paiement partiel.
		// Le paramètre payment_multiple est composé de cette façon : '2'=>'50', '3'=>'30' par exemple, donc la clé contient le nombre de paiement, et la valeur correspond au pourcentage du montant total à payer pour le premier règlement.
		$payment_array = explode('#', $type);
		if (is_numeric($payment_array[1])) {
			// le deuxième élément du tableau est numérique, donc on peut utiliser les infos récupérée via le explode.
			// On récupère le type du paiement, pour qu'il soit correctement traité plus bas dans la fonction dans le switch/case.
			$type = $payment_array[0];
			if (empty($forced_amount_to_pay)) {
					// On passe ici uniquement si le montant à payer provient de la table peel_commandes, dans le cas contraire c'est qu'on souhaite que l'utilisateur paye un montant spécifique donc on y touche pas.
				// Calcul du montant à payer, qui correspond au pourcentage défini dans le paramètre payment_multiple
				$amount_to_pay = $amount_to_pay * ($GLOBALS['site_parameters']['payment_multiple'][$payment_array[1]]/100);
			}
		}
	}
	if ((empty($type) || is_array($type)) && empty($GLOBALS['site_parameters']['payment_method_display_disable_if_type_empty'])) {
		// Affichage de tous les modes de paiement si aucun défini (seulement si commande passée dans l'administration)
		if (!empty($com->site_id)) {
			$site_id = $com->site_id;
		} else {
			$site_id = $GLOBALS['site_id'];
		}
		$sql_paiement = 'SELECT p.technical_code
			FROM peel_paiement p
			WHERE p.etat = "1" AND ' .  get_filter_site_cond('paiement', 'p', null, $site_id) . (!empty($type)?' AND p.technical_code IN ("' . implode('","', real_escape_string($type)).'")':'')  . (!empty($types_excluded_array)?' AND p.technical_code NOT IN ("' . implode('","', real_escape_string($types_excluded_array)).'")':'') . '
			GROUP BY technical_code, nom_' . $_SESSION['session_langue'] . '
			ORDER BY p.position';
		$query = query($sql_paiement);
		while ($tab_paiement = fetch_assoc($query)) {
			if (!empty($tab_paiement['technical_code'])) {
				$this_output = get_payment_form($order_id, $tab_paiement['technical_code'], $send_admin_email, $amount_to_pay, $allow_autosend, $params, true);
				if(!empty($this_output)) {
					$output_array[] = $this_output;
				}
			}
		}
		if (!empty($output_array)) {
		return implode('<hr />', $output_array);
		} else {
			return null;
	}
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('payment_form.tpl');
	$hook_result = call_module_hook('payment_form', array('com' => $com, 'type' => $type), 'array');
	foreach($hook_result as $this_key => $this_value) {
		$tpl->assign($this_key, $this_value);
	}
	$tpl->assign('type', $type);
	if(!empty($com->code_facture)) {
		$tpl->assign('commande_pdf_href', get_site_wwwroot($com->site_id, $_SESSION['session_langue']) . '/factures/commande_pdf.php?code_facture=' . $com->code_facture . '&mode=bdc');
	} else {
		$tpl->assign('commande_pdf_href', null);
	}
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
			$tpl->assign('rib', print_rib(true, vb($GLOBALS['payment_rib'])));
			break;

		case 'cmcic' :
			if (check_if_module_active('cmcic')) {
				require_once($GLOBALS['fonctionscmcic']);
				$js_action = 'document.getElementById("PaymentRequest").submit()';
				$tpl->assign('form', getCMCICForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, ''));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'cmcic_by_3' :
			if (check_if_module_active('cmcic')) {
				require_once($GLOBALS['fonctionscmcic']);
				$js_action = 'document.getElementById("PaymentRequest").submit()';
				$tpl->assign('form', getCMCICForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 3, ''));
				$send_admin_template_email = 'admin_info_payment_credit_card_3_times';
			}
			break;

		case 'cmcic_by_4' :
			if (file_exists($GLOBALS['fonctionscmcic'])) {
				require_once($GLOBALS['fonctionscmcic']);
				$js_action = 'document.getElementById("PaymentRequest").submit()';
				$tpl->assign('form', getCMCICForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 4, ''));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'atos' :
			if (check_if_module_active('sips') && !empty($GLOBALS['site_parameters']['atos_solution_name']['atos'])) {
				require_once($GLOBALS['fonctionsatos']);
				// la validation automatique ne fonctionne pas avec atos.
				$tpl->assign('form', getATOSForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $GLOBALS['site_parameters']['atos_solution_name']['atos']));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'atos_by_3' :
			if (check_if_module_active('sips') && !empty($GLOBALS['site_parameters']['atos_solution_name']['atos_by_3'])) {
				require_once($GLOBALS['fonctionsatos']);
				// la validation automatique ne fonctionne pas avec atos.
				$tpl->assign('form', getATOSForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 3, '', $GLOBALS['site_parameters']['atos_solution_name']['atos_by_3']));
				$send_admin_template_email = 'admin_info_payment_credit_card_3_times';
			}
			break;

		case 'cetelem' :
			if (check_if_module_active('sips') && !empty($GLOBALS['site_parameters']['atos_solution_name']['cetelem'])) {
				require_once($GLOBALS['fonctionsatos']);
				// la validation automatique ne fonctionne pas avec atos.
				$tpl->assign('form', getATOSForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $GLOBALS['site_parameters']['atos_solution_name']['cetelem']));
				$send_admin_template_email = 'admin_info_payment_credit_card_3_times';
			}
			break;

		case 'systempay' :
			if (check_if_module_active('systempay')) {
				require_once($GLOBALS['fonctionssystempay']);
				$js_action = 'document.getElementById("SystempayForm").submit()';
				$tpl->assign('form', getSystempayForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->id_utilisateur, $com->nom_bill, $com->prenom_bill, $com->telephone_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'systempay_3x' :
			if (check_if_module_active('systempay')) {
				require_once($GLOBALS['fonctionssystempay']);
				$js_action = 'document.getElementById("SystempayForm").submit()';
				$tpl->assign('form', getSystempayForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, vn($GLOBALS['site_parameters']['systempay_payment_count'], 1), '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->id_utilisateur, $com->nom_bill, $com->prenom_bill, $com->telephone_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card_3_times';
			}
			break;

		case 'spplus' :
			if (check_if_module_active('spplus')) {
				require_once($GLOBALS['fonctionsspplus']);
				// la validation automatique ne fonctionne pas avec spplus.
				// => pas de possibilité de mettre js_action
				$tpl->assign('form', getSPPlusForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, ''));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'paybox' :
			$forms_paybox = '';
			if(!defined('IN_STEP3')){
				$forms_paybox .= '<table><tr>';
			}
			if(!defined('IN_ORDER_HISTORY') && !defined('IN_STEP3')){
				if($empty_type){
					$tpl->assign('column_paybox', 'col-md-4');
				} else {
					$tpl->assign('column_paybox', 'col-md-4 col-md-offset-4');
				}
			} else {
				$tpl->assign('column_paybox', '');
			}
			if (check_if_module_active('paybox') && (!defined('IN_STEP3')) && !$empty_type) {
				require_once($GLOBALS['fonctionspaybox']);
				$js_action = 'document.TheForm.submit()';
				$forms_paybox .= (defined('IN_ORDER_HISTORY')?'<div>':'<td>') . givePayboxForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', vb($GLOBALS['site_parameters']['paybox_cgi']), vb($GLOBALS['site_parameters']['paybox_site']), vb($GLOBALS['site_parameters']['paybox_rang']), vb($GLOBALS['site_parameters']['paybox_identifiant'])) . (defined('IN_ORDER_HISTORY')?'</div>':'</td>');
				$send_admin_template_email = 'admin_info_payment_credit_card';
			} elseif($type == 'paybox') {
				require_once($GLOBALS['fonctionspaybox']);
				$js_action = 'document.TheForm.submit()';
				$forms_paybox .= '<td>' . givePayboxForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', vb($GLOBALS['site_parameters']['paybox_cgi']), vb($GLOBALS['site_parameters']['paybox_site']), vb($GLOBALS['site_parameters']['paybox_rang']), vb($GLOBALS['site_parameters']['paybox_identifiant'])). '</td>';
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			if(!defined('IN_STEP3')){
				$forms_paybox .= '</tr></table>';
			}
			$tpl->assign('form',$forms_paybox);
			break;

		case 'paybox_2x' :
			$forms_paybox = '';
			if(!defined('IN_STEP3')){
				$forms_paybox .= '<table><tr>';
			}
			if(!defined('IN_ORDER_HISTORY') && !defined('IN_STEP3')){
				if($empty_type){
					$tpl->assign('column_paybox', 'col-md-4');
				} else {
					$tpl->assign('column_paybox', 'col-md-4 col-md-offset-4');
				}
			} else {
				$tpl->assign('column_paybox', '');
			}
			if (check_if_module_active('paybox') && !defined('IN_STEP3') && !$empty_type) {
				require_once($GLOBALS['fonctionspaybox']);
				$js_action = 'document.TheForm.submit()';
				$forms_paybox .= (defined('IN_ORDER_HISTORY')?'<div>':'<td>') . givePayboxForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 2, '', vb($GLOBALS['site_parameters']['paybox_cgi']), vb($GLOBALS['site_parameters']['paybox_site']), vb($GLOBALS['site_parameters']['paybox_rang']), vb($GLOBALS['site_parameters']['paybox_identifiant'])) . (defined('IN_ORDER_HISTORY')?'</div>':'</td>');
				$send_admin_template_email = 'admin_info_payment_credit_card';
			} elseif($type == 'paybox_2x') {
				require_once($GLOBALS['fonctionspaybox']);
				$js_action = 'document.TheForm.submit()';
				$forms_paybox .= '<td>' . givePayboxForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 2, '', vb($GLOBALS['site_parameters']['paybox_cgi']), vb($GLOBALS['site_parameters']['paybox_site']), vb($GLOBALS['site_parameters']['paybox_rang']), vb($GLOBALS['site_parameters']['paybox_identifiant'])) . '</td>';
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			if(!defined('IN_STEP3')){
				$forms_paybox .= '</tr></table>';
			}
			$tpl->assign('form',$forms_paybox);
			break;

		case 'paybox_3x' :
			$forms_paybox = '';
			if(!defined('IN_STEP3')){
				$forms_paybox .= '<table><tr>';
			}
			if(!defined('IN_ORDER_HISTORY') && !defined('IN_STEP3')){
				if($empty_type){
					$tpl->assign('column_paybox', 'col-md-4');
				} else {
					$tpl->assign('column_paybox', 'col-md-4 col-md-offset-4');
				}
			} else {
				$tpl->assign('column_paybox', '');
			}
			if (check_if_module_active('paybox') && !defined('IN_STEP3') && !$empty_type) {
				require_once($GLOBALS['fonctionspaybox']);
				$js_action = 'document.TheForm.submit()';
				$forms_paybox .= (defined('IN_ORDER_HISTORY')?'<div>':'<td>') . givePayboxForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 3, '', vb($GLOBALS['site_parameters']['paybox_cgi']), vb($GLOBALS['site_parameters']['paybox_site']), vb($GLOBALS['site_parameters']['paybox_rang']), vb($GLOBALS['site_parameters']['paybox_identifiant'])) . (defined('IN_ORDER_HISTORY')?'</div>':'</td>');
				$send_admin_template_email = 'admin_info_payment_credit_card';
			} elseif($type == 'paybox_3x') {
				require_once($GLOBALS['fonctionspaybox']);
				$js_action = 'document.TheForm.submit()';
				$forms_paybox .= '<td>' . givePayboxForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 3, '', vb($GLOBALS['site_parameters']['paybox_cgi']), vb($GLOBALS['site_parameters']['paybox_site']), vb($GLOBALS['site_parameters']['paybox_rang']), vb($GLOBALS['site_parameters']['paybox_identifiant'])) . '</td>';
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			if(!defined('IN_STEP3')){
				$forms_paybox .= '</tr></table>';
			}
			$tpl->assign('form',$forms_paybox);
			break;

		case 'bluepaid' :
			if (check_if_module_active('bluepaid')) {
				require_once($GLOBALS['fonctionsbluepaid']);
				$js_action = 'document.TheForm.submit()';
				$tpl->assign('form', getBluepaidForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->id_utilisateur, $com->pays_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'bluepaid_abonnement' :
			if (check_if_module_active('bluepaid')) {
				require_once($GLOBALS['fonctionsbluepaid']);
				$js_action = 'document.TheForm.submit()';
				$tpl->assign('form', getBluepaidForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->id_utilisateur, $com->pays_bill, true));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'kwixo':
			if (check_if_module_active('fianet')) {
				echo $GLOBALS['STR_THANKS_FIANET'];
				// Librairie de fonctions PEEL pour Fianet
				require_once($GLOBALS['fonctionsfianet']);
				$tpl->assign('form', getKwixoForm($order_id, 'comptant'));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'kwixo_rnp':
			if (check_if_module_active('fianet')) {
				echo $GLOBALS['STR_THANKS_FIANET'];
				// Librairie de fonctions PEEL pour Fianet
				require_once($GLOBALS['fonctionsfianet']);
				$tpl->assign('form', getKwixoForm($order_id, 'rnp'));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'kwixo_credit':
			if (check_if_module_active('fianet')) {
				echo $GLOBALS['STR_THANKS_FIANET'];
				// Librairie de fonctions PEEL pour Fianet
				require_once($GLOBALS['fonctionsfianet']);
				$tpl->assign('form', getKwixoForm($order_id, 'credit'));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'ogone' :
		case 'postfinance' :
			if (check_if_module_active('ogone')) {
				require_once($GLOBALS['fonctionsogone']);
				$js_action = 'document.getElementById("ogoneForm").submit()';
				$tpl->assign('form', giveOgoneForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->prenom_bill . ' ' . $com->nom_bill, $com->telephone_bill, $type));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'worldpay' :
			if (check_if_module_active('worldpay')) {
				require_once($GLOBALS['dirroot'] . '/modules/worldpay/fonctions.php');
				$js_action = 'document.getElementById("worldpayForm").submit()';
				$tpl->assign('form', giveWorldpayForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->prenom_bill . ' ' . $com->nom_bill, $com->telephone_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'stripe' :
			if (check_if_module_active('stripe')) {
				require_once($GLOBALS['dirroot'] . '/modules/stripe/fonctions.php');
				$js_action = 'document.getElementById("stripe").submit()';
				$tpl->assign('form', giveStripeForm($order_id));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'omnikassa' :
			if (check_if_module_active('omnikassa')) {
				require_once($GLOBALS['fonctionsomnikassa']);
				$js_action = 'document.getElementById("omnikassaForm").submit()';
				$tpl->assign('form', giveOmnikassaForm($order_id, $_SESSION['session_langue'], fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), $com->devise, $com->email, 1, '', $com->adresse_bill, $com->zip_bill, $com->ville_bill, $com->pays_bill, $com->prenom_bill . ' ' . $com->nom_bill, $com->telephone_bill));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;

		case 'moneybookers' :
			if (check_if_module_active('moneybookers') && !empty($GLOBALS['site_parameters']['email_moneybookers'])) {
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
			if (function_exists('get_payment_form_'.$type)) {
				require_once($GLOBALS['dirroot'] . '/' . $GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$type]);
				$function_name = 'get_payment_form_'.$type;
				$standard_params = array('order_id' => $order_id, 'lang' => $_SESSION['session_langue'], 'amount' => fprix($amount_to_pay, false, $com->devise, true, $com->currency_rate, false, false), 'currency_code' => $com->devise, 'user_email' => $com->email, 'payment_times' => 1, 'sTexteLibre' => '', 'societe_ship' => $com->societe_ship, 'prenom_ship' => $com->prenom_ship, 'nom_ship' => $com->nom_ship, 'adresse_ship' => $com->adresse_ship, 'zip_ship' => $com->zip_ship, 'ville_ship' => $com->ville_ship, 'pays_ship' => $com->pays_ship,'fullname_ship' => $com->prenom_ship . ' ' . $com->nom_ship, 'telephone_ship' => $com->telephone_ship, 'societe_bill' => $com->societe_bill,'prenom_bill' => $com->prenom_bill, 'nom_bill' => $com->nom_bill, 'adresse_bill' => $com->adresse_bill, 'zip_bill' => $com->zip_bill, 'ville_bill' => $com->ville_bill, 'pays_bill' => $com->pays_bill, 'fullname_bill' => $com->prenom_bill . ' ' . $com->nom_bill, 'telephone_bill' => $com->telephone_bill, 'type' => $type, 'id_utilisateur' => $com->id_utilisateur, 'site_id' => $com->site_id);
				$params = array_merge_recursive_distinct($standard_params, $params);
				$tpl->assign('form', $function_name($params));
				$send_admin_template_email = 'admin_info_payment_credit_card';
			}
			break;
	}
	if ($send_admin_email && !empty($send_admin_template_email) && empty($admin_email_sent)) {
		// On n'envoie qu'un seul email de ce type à l'admin même si $forced_type="" et boucle sur tous les moyens de paiement 
		unset($custom_template_tags);
		$custom_template_tags['ORDER_ID'] = $com->order_id;
		send_email($warn_payment_admin_email, '', '', $send_admin_template_email, $custom_template_tags, null, $GLOBALS['support_commande']);
		$admin_email_sent = true;
	}
	if($allow_autosend && !empty($js_action) && (vn($GLOBALS['site_parameters']['module_autosend']) == 1)) {
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
			$reference_date = date('Y-12-31', time() - 24 * 3600 * 365);
		} else {
			// Année <= N-2 bloquées
			$reference_date = date('Y-12-31', time() - 24 * 3600 * 365 * 2);
		}
	} elseif ($GLOBALS['site_parameters']['keep_old_orders_intact'] == '2') {
		$reference_date = get_mysql_date_from_user_input($GLOBALS['site_parameters']['keep_old_orders_intact_date']);
	} else {
		// keep_old_orders_intact est alors un timestamp
		$reference_date = date('Y-m-d H:i:s', $GLOBALS['site_parameters']['keep_old_orders_intact']);
	}
	if (!empty($reference_date) && strtotime($order_datetime) > strtotime($reference_date)) {
		$allowed = true;
	}
	return $allowed;
}

/**
 * Fonction qui récupère le numéro de commande comptable
 *
 * @param integer $id id technique de la commande qui correspond au champ id de peel_commandes
 * @param integer $site_id site_id de la commande qui correspond au champ site_id de peel_commandes
 * @return string
 */
function get_order_id($id = null, $site_id = null)
{
	if (!empty($id)) {
		// Recherche si le numéro de commande est déjà défini.
		$query = query('SELECT order_id, site_id
			FROM peel_commandes
			WHERE id='.intval($id).' AND '. get_filter_site_cond('commandes'));
		if($order_infos = fetch_assoc($query)) {
			if (!empty($order_infos['order_id'])) {
				// order_id est déjà défini pour la commande, la fonction retourne la valeur déjà calculée
				return $order_infos['order_id'];
			} elseif($site_id === null) {
				$site_id = $order_infos['site_id'];
			}
		} else {
			// commande introuvable.
			return false;
		}
	}
}
/**
 * Fonction qui génère la requête SQL de sélection du mode de transport
 *
 * @return string
 */
function get_tarifs_sql()
{
	$sqlType = 'SELECT DISTINCT(t.id), t.nom_' . $_SESSION['session_langue'] . '
		FROM peel_tarifs tf
		INNER JOIN peel_types t ON t.id = tf.type AND ' . get_filter_site_cond('types', 't') . '
		WHERE t.etat = 1 AND ' . get_filter_site_cond('tarifs', 'tf') . ' AND tf.zone = "' . intval($_SESSION['session_caddie']->zoneId) . '" AND (poidsmin<="' . floatval($_SESSION['session_caddie']->total_poids) . '" OR poidsmin=0) AND (poidsmax>="' . floatval($_SESSION['session_caddie']->total_poids) . '" OR poidsmax=0) AND (totalmin<="' . floatval($_SESSION['session_caddie']->total_produit) . '" OR totalmin=0) AND (totalmax>="' . floatval($_SESSION['session_caddie']->total_produit) . '" OR totalmax=0) '.(!empty($_SESSION['session_caddie']->exapaq_order)?' AND (t.technical_code="exapaq" OR t.technical_code="icirelais")':'').'
		ORDER BY t.position ASC, t.nom_' . $_SESSION['session_langue'] . ' ASC';
	return $sqlType;
}


/** 
 * Fonction qui permet de récupérer les dimensions ou le poids maximum des colis du panier 
 *
 * @param array $product_ids_array
 * @return array
 */
function get_cart_measurement_max($session_caddie) {
	if (empty($GLOBALS['site_parameters']['cart_measurement_max_quotation']) || empty($session_caddie->type_technical_code)) {
		return false;
	}
    // On initialise les 3 variables qui stockeront les valeurs max :
    $max_width = 0;
    $max_length = 0;
    $max_depth = 0;
	$total_size = 0;
	$this_product_total_size = 0;
	$this_product_total_weight = 0;
	$a3_size_reached = false;
	$i = 0;
	$add_product_over_cost = false;
    foreach($session_caddie->articles as $numero_ligne => $this_article) {
        // On recherche les dimensions du produit dans la table des produits et si le produit n'est pas hors format :
		// NB : pas beaucoup d'articles 'surface' dans le caddie, donc pas grave si on récupère les informations produits d'abord et éventuellement chargement de l'objet Product ensuite sans optimisation
        if ($result = get_product_infos($this_article, false, 'width, length, depth, technical_code, on_quote, poids', 1, true, false)) {
			$text_array = array();
			if ($result['technical_code'] == 'surface') {
				// Pour les produits "surface", les dimensions du produit a été inséré depuis la page produit en front office par l'utilisateur.
				$product_object = new Product($this_article);
				$product_object->set_configuration(null, null, $session_caddie->id_attribut[$numero_ligne]);
				$attributs_form_part = affiche_attributs_form_part($product_object, 'selected_text', null, null, null, array('dimension'));
				
				//Récupération et traitement de l'attribut épaisseur
				$attributs_form_part_depth = affiche_attributs_form_part($product_object, 'selected_text', null, null, null, array('thickness'));
				$attributs_form_part_depth = trim(str_replace(': ','',strstr($attributs_form_part_depth, ':')));
				$attributs_form_part_depth = trim(str_replace(' ','',$attributs_form_part_depth));
				//Récupération et traitement de l'attribut largeur et longueur
				$text_array_tmp = explode("<br />", $attributs_form_part);				
				foreach($text_array_tmp as $this_text_tmp) {
					$this_sentences_array = explode(":", $this_text_tmp); 
					$text_array[] = trim(vb($this_sentences_array[1]));
				}
				// Concernant les produits dont le code technique est "surface", ajouter 4cm (40mm) à l'épaisseur, la largeur et la longueur pour calculer automatiquement les dimensions du colis correspondant
				// largeur
				$max_width = vb($text_array[0]);
				// longueur
				$max_length = vb($text_array[1]);
				// Epaisseur
				$max_depth = vb($attributs_form_part_depth);
				
				if ($max_width<500 || $max_length<500) {
				/*
					* Un seul côté, que ce soit largeur ou longueur, strictement inférieur à 50 cm (500 mmm)
					On tient compte des cotes de la longueur et de la largeur uniquement. Elles ne sont pas additionnées.
					On ne tient pas compte de l'épaisseur ni de l'emballage.
					C'est valable par exemple pour :
					- une ou plusieurs plaques de 600mm par 200mm
					- une ou plusieurs plaques de 499mm par 499mm
					- une ou plusieurs plaques de 1499mm par 499mm
					=> Ce sont les frais de port de TNT qui s'appliquent
					
					=> Dans ce cas on ne fait rien, ce sont les frais de port standard qui s'appliquent
				*/
				}
				if ($max_width>=500 && $max_length>=500) { 
				/*
					* Les deux côtés, longueur et largeur, supérieurs ou égaux tous les deux à 50 cm (500 mmm)
					On tient compte des cotes de la longueur et de la largeur uniquement. Elles ne sont pas additionnées.
					On ne tient pas compte de l'épaisseur ni de l'emballage.
					C'est valable par exemple pour :
					- une ou plusieurs plaques de 500mm par 500mm
					- une ou plusieurs plaques de 1499mm par 501mm
					=> La ligne de surcoût apparaît dans le panier
				*/
					$add_product_over_cost = true;
				}
				if ($max_width>=$GLOBALS['site_parameters']['tnt_treshold_limit'] || $max_length>=$GLOBALS['site_parameters']['tnt_treshold_limit']) {
					/*
						* Un seul côté, que ce soit largeur ou longueur, supérieur ou égal à 150 cm (1500mm)
						On ne tient pas compte de l'épaisseur ni de l'emballage.
						C'est valable par exemple pour :
						- une ou plusieurs plaques de 1500mm par 50mm
						- une ou plusieurs plaques de 1500mm par 600mm
						=> On déclenche le devis
					*/
					$mode_devis = true;
				}
				if ($max_width+$max_length>=2200) {
					/*
						* On ne tient pas compte de l'épaisseur ni de l'emballage.
						C'est valable par exemple pour :
						- une ou plusieurs plaques de 1100mm par 1100mm
						- une ou plusieurs plaques de 1499mm par 701mm
						=> On déclenche le devis
					*/
					$mode_devis = true;
				}
			} else {
					$max_width = $result['width'];
					$max_length = $result['length'];
					$max_depth = $result['depth'];
			}
			// On va retenir la plaque la plus grande du panier pour appliquer le devis, peu importe la quantité
			$this_product_total_size = ($max_width + $max_length);
			if ($max_width>$max_length) {
				// portrait
				if($max_width > 420 || $max_length > 297) {
					$a3_size_reached = true;
				}
			} else {
				// paysage
				if ($max_length > 420 || $max_width > 297) {
					$a3_size_reached = true;
				}
			}
			// Si le produit à le paramètre "on_quote" de coché ou que la taille (longueur + largeur ) est supèrieur à la limite acceptable définis par "tnt_treshold_limit" on déclenche le process de commande court (devis) ainsi que si le poids total est supèrieur à this_product_total_weight
			if (!empty($result['on_quote']) || $this_product_total_weight >= $session_caddie->total_poids) {
				$mode_devis = true;
				// On a trouvé le caddie est un devis, on défini une variable pour retourner true et on s'arrête là.
				break;
			}
		}
		if ($this_product_total_size>$total_size) {
			// le but est de récupérer le colis le plus grand
			$total_size = $this_product_total_size;
		}
	}
	//On est en mode devis, on ne veut pas de produit surcoût dans la commande, on passe donc la variable $add_product_over_cost à false
	if(!empty($mode_devis)){
		$add_product_over_cost = false;
	}
	if ($add_product_over_cost == true && vb($_SESSION['product_object_extra_cost']) == false && vb($mode_devis) != true && $session_caddie->type_technical_code == 'tnt'){
		$product_object_extra_cost = new Product('over_cost', null, false, null, true, !is_user_tva_intracom_for_no_vat() && !check_if_module_active('micro_entreprise'));
		if(!empty($product_object_extra_cost->id)) {
			// On créer une variable de session avec le numero de la ligne du produit qui a généré l'ajout du produit surcoût
			$_SESSION['product_object_extra_cost'] = true;
			$session_caddie->add_product($product_object_extra_cost, 1, '', ''); 
			$session_caddie->update();
		}
	}
	// Le produit surcoût doit être suprimé du caddie si le mode de transporteur n'est pas TNT
	if(($session_caddie->type_technical_code != 'tnt' || !vb($add_product_over_cost)) && vb($_SESSION['product_object_extra_cost'])==true){
		// On passe la variable de session à false pour pouvoir ajouter un produit surcoût dans un autre processus de mise au panier
		$_SESSION['product_object_extra_cost'] = false;
		// On parcour le caddie jusqu'à trouver le produit surcoût avec le technical code "over_cost"
		foreach ($session_caddie->articles as $numero_ligne2 => $product_id) {
			$product_object2 = new Product($product_id);
			if ($product_object2->technical_code == 'over_cost') {
				$session_caddie->delete_line($numero_ligne2);
			}
		}
	}
	if((!empty($session_caddie->type_technical_code) && $session_caddie->type_technical_code=='pickup')) {
		// Ne pas mettre le mode devis si le choix de livraison est retrait sur place
		return false;
	}
	// Application du process de commande court
	if (!empty($mode_devis)) {
		return true;
	}
	if (check_if_module_active('tnt') && $session_caddie->type_technical_code == 'tnt') {
		/*
			* Si la longueur et la largeur additionnées sont supérieures ou égales à 220 cm (2200mm) : $GLOBALS['site_parameters']['tnt_treshold']
			On ne tient pas compte de l'épaisseur ni de l'emballage.
			C'est valable par exemple pour :
			- une ou plusieurs plaques de 1100mm par 1100mm
			- une ou plusieurs plaques de 1499mm par 701mm
			=> On déclenche le devis
		*/ 
		// On a passé tous les produits du panier en revu, et rempli la variable avec la valeur max. Donc on a les dimensions maximum, tous produits confondus. On retourne le résulat
		return ($total_size > $GLOBALS['site_parameters']['tnt_treshold']) || ($session_caddie->total_poids > vn($GLOBALS['site_parameters']['tnt_weight_treshold']));
	} elseif($session_caddie->type_technical_code == 'colissimo') {
		return $a3_size_reached || ($session_caddie->total_poids > vn($GLOBALS['site_parameters']['colissimo_weight_treshold']));
	}
}