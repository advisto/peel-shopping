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
// $Id: clients_segmentation.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

include($GLOBALS['dirroot'] . '/lib/fonctions/fonctions_admin.php');

/**
 * updateClientsSegBuy()
 *
 * @return
 */
function updateClientsSegBuy()
{
	$query = query('SELECT o.id_utilisateur AS customers_id, count(*) AS this_count, IF( o_timestamp <"' . date('Y-m-d 00:00:00', time()-3600 * 24 * 365) . '",1,0) AS old
		FROM peel_commandes o
		INNER JOIN peel_statut_paiement sp ON sp.id=o.id_statut_paiement
		WHERE sp.technical_code IN ("being_checked","completed")
		GROUP BY o.id_utilisateur, IF(o.o_timestamp < "' . date('Y-m-d 00:00:00', time()-3600 * 24 * 365) . '",1,0)
		ORDER BY o.id_utilisateur ASC');
	while ($result = fetch_assoc($query)) {
		if (empty($result['old'])) {
			$recent_buys[$result['customers_id']] = $result['this_count'];
		} else {
			$old_buys[$result['customers_id']] = $result['this_count'];
		}
		$users[] = $result['customers_id'];
	}
	foreach($users as $user_id) {
		$buys = 0;
		if (!empty($old_buys[$user_id])) {
			$buys += $old_buys[$user_id];
		}
		if (!empty($recent_buys[$user_id])) {
			$buys += $recent_buys[$user_id];
		}
		if ($buys >= 2 && !empty($recent_buys[$user_id])) {
			$users_seg_buy['multi_recent'][$user_id] = true;
		} elseif (!empty($recent_buys[$user_id])) {
			$users_seg_buy['one_recent'][$user_id] = true;
		} elseif ($buys >= 2) {
			$users_seg_buy['multi_old'][$user_id] = true;
		} elseif ($buys == 1) {
			$users_seg_buy['one_old'][$user_id] = true;
		} else {
			// On n'est pas censé passer ici puisqu'on ne traite que les utilisateurs avec au moins une commande
			// $users_seg_buy['no'][$user_id] = true; // inutile car on fait un UPDATE à no pour tous par défaut avant MAJ
		}
	}
	query('UPDATE peel_utilisateurs SET seg_buy="no"');
	// var_dump($users_seg_buy); die();
	foreach($users_seg_buy as $this_value => $ids_as_keys) {
		query('UPDATE peel_utilisateurs
			SET seg_buy="' . nohtml_real_escape_string($this_value) . '"
			WHERE id_utilisateur IN ("' . implode('","', real_escape_string(array_keys($ids_as_keys))) . '") AND ' . get_filter_site_cond('utilisateurs') . '');
	}
	$GLOBALS['contentMail'] .= 'MAJ des valeurs de segmentation achat : OK' . "\n";
}

/**
 * updateClientsContactDates()
 *
 * @return
 */
function updateClientsContactDates()
{
	if (check_if_module_active('annonces')) {
		$query = query('SELECT UNIX_TIMESTAMP(expiration_date) AS paid_until_timestamp, user_id
			FROM peel_gold_ads');
		while ($result = fetch_assoc($query)) {
			$gold_ads_renewals_timestamps[$result['user_id']][] = $result['paid_until_timestamp'];
		}
	}
	$query = query('SELECT user_id, timestamp
		FROM peel_admins_contacts_planified
		WHERE timestamp >= UNIX_TIMESTAMP(NOW())
		ORDER BY timestamp ASC');
	while ($contact = fetch_assoc($query)) {
		$planified_contact_timestamps[$contact['user_id']][] = $contact['timestamp'];
	}
	$query = query('SELECT id_membre, UNIX_TIMESTAMP(MAX(date)) AS last_contact_timestamp, COUNT(*) AS contacts_count
		FROM peel_admins_actions
		WHERE action IN ("PHONE_EMITTED", "PHONE_RECEIVED", "SEND_EMAIL") AND ' . get_filter_site_cond('admins_actions') . '
		GROUP BY id_membre');
	while ($contact = fetch_assoc($query)) {
		$last_contact_timestamps[$contact['id_membre']] = $contact['last_contact_timestamp'];
		$contacts_counts[$contact['id_membre']] = $contact['contacts_count'];
	}
	$query = query('SELECT *, TO_DAYS(now())-TO_DAYS(GREATEST(date_insert,"2008-01-01 00:00:00")) AS followed_days
		FROM peel_utilisateurs
		WHERE ' . get_filter_site_cond('utilisateurs') . '');
	while ($user = fetch_assoc($query)) {
		$user_id = $user['id_utilisateur'];
		$seg_followed = '';
		$is_actif = ($user['etat'] !== '0');
		unset($next_contact_time_subscription_renewal_expected_array);
		unset($next_contact_time);
		$next_contact_reason = '';
		$next_contact_timestamp = 0;
		if ($is_actif) {
			// Evaluation de la fréquence de suivi
			if (!empty($contacts_counts) && !empty($contacts_counts[$user_id])) {
				$frequence_followed = $contacts_counts[$user_id] * 365 / $user['followed_days'];
			} else {
				$frequence_followed = 0;
			}
			if ($frequence_followed == 0) {
				$seg_followed = 'no';
			} elseif ($frequence_followed < 3) {
				$seg_followed = 'poor';
			} else {
				$seg_followed = 'correct';
			}
			// Préparation de la définition de la prochaine date de contact et de la raison
			$seg_note = getClientNote($user);
			if (!empty($last_contact_timestamps[$user_id])) {
				$contact_time_basis = $last_contact_timestamps[$user_id];
			} else {
				$contact_time_basis = strtotime($user['date_insert']);
			}
			if (!empty($seg_note)) {
				// Si seg_not=0 => Pas de contact => on en rentre pas de $next_contact_time['usual']
				if ($seg_note < 38) {
					$seg_contact_days_frequence = 365;
				} elseif ($seg_note < 45) {
					$seg_contact_days_frequence = 182;
				} elseif ($seg_note < 60) {
					$seg_contact_days_frequence = 91;
				} else {
					$seg_contact_days_frequence = 30;
				}
				// Contact X temps après le dernier contact ou l'inscription
				$next_contact_time['usual'] = $contact_time_basis + $seg_contact_days_frequence * 3600 * 24;
			}
			// On vérifie la dernière échéance d'un abonnement type SILVER / PLATINUM / DIAMOND
			if (!empty($user['supramembre'])) {
				// Abonnement silver
				// Contact prévu 7 jours avant la fin de l'abonnement
				$next_contact_time_subscription_renewal_expected_array[] = $user['supramembre']-7 * 24 * 3600;
			}
			if (!empty($user['platinum_until'])) {
				// Abonnement platinum
				// Contact prévu 7 jours avant la fin de l'abonnement
				$next_contact_time_subscription_renewal_expected_array[] = $user['platinum_until']-7 * 24 * 3600;
			}
			if (!empty($user['diamond_until'])) {
				// Abonnement diamond
				// Contact prévu 7 jours avant la fin de l'abonnement
				$next_contact_time_subscription_renewal_expected_array[] = $user['diamond_until']-7 * 24 * 3600;
			}
			if (!empty($next_contact_time_subscription_renewal_expected_array) && max($next_contact_time_subscription_renewal_expected_array) > $contact_time_basis + 7 * 24 * 3600) {
				// on définit la date de contact pour un renouvellement, seulement si on n'a pas contacté l'utilisateur depuis cette date ou dans la semaine précédent
				// NB: c'est max et non pas min car les abonnements sont des variantes d'abonnement
				$next_contact_time['renewal_expected'] = max($next_contact_time_subscription_renewal_expected_array);
			}
			if (!empty($gold_ads_renewals_timestamps) && !empty($gold_ads_renewals_timestamps[$user_id])) {
				// On regarde si annonce GOLD à renouveler
				foreach($gold_ads_renewals_timestamps[$user_id] as $this_timestamp) {
					if ((empty($next_contact_time['renewal_expected']) || $this_timestamp-7 * 24 * 3600 < $next_contact_time['renewal_expected']) && $this_timestamp-7 * 24 * 3600 > $contact_time_basis + 7 * 24 * 3600) {
						// Si l'annonce GOLD vient à expiration, on veut contacter l'auteur une semaine à l'avance
						// mais on ne le fait que si on ne l'a pas contacté dans la semaine précédente
						$next_contact_time['renewal_expected'] = $this_timestamp-7 * 24 * 3600;
						break;
					}
				}
			}
			if (!empty($planified_contact_timestamps) && !empty($planified_contact_timestamps[$user_id])) {
				// On regarde si un contact a été planifié spécifiquement pour cet utilisateur
				foreach($planified_contact_timestamps[$user_id] as $this_timestamp) {
					if ((empty($next_contact_time['already_planified']) || $this_timestamp < $next_contact_time['already_planified']) && $this_timestamp > $contact_time_basis + 7 * 24 * 3600) {
						// Si l'annonce GOLD vient à expiration, on veut contacter l'auteur une semaine à l'avance
						// mais on ne le fait que si on ne l'a pas contacté dans la semaine précédente
						$next_contact_time['already_planified'] = $this_timestamp;
						break;
					}
				}
			}
		}
		if (!empty($next_contact_time)) {
			// On trie pour avoir en premier le timestamp le plus faible
			asort($next_contact_time);
			// On prend la clé pour la première valeur, donc la plus faible
			$next_contact_reason = key($next_contact_time);
			if($next_contact_reason != 'already_planified') {
				$next_contact_timestamp = $next_contact_time[$next_contact_reason];
				// var_dump($user_id,$next_contact_time); echo '<br />';
				query('INSERT INTO peel_admins_contacts_planified (`user_id`, `timestamp`, `reason`)
					VALUES(
						' . intval($user['id_utilisateur']) . ',
						"' . nohtml_real_escape_string(vb($next_contact_timestamp)) . '",
						"' . nohtml_real_escape_string(vb($next_contact_reason)) . '")');
			}
		}
		if ($seg_followed != $user['seg_followed']) {
			// Mise à jour des utilisateurs dont la date de contact (et/ou la raison) a/ont changé
			query('UPDATE peel_utilisateurs
				SET seg_followed="' . nohtml_real_escape_string($seg_followed) . '" AND ' . get_filter_site_cond('utilisateurs') . '
				WHERE id_utilisateur=' . intval($user_id));
		}
	}
	$GLOBALS['contentMail'] .= 'MAJ des dates de contact : OK' . "\n";
}

$contentMail = 'Traitement des segmentations client et des dates de contact

';

updateClientsSegBuy();
updateClientsContactDates();

