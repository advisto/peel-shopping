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
// $Id: fonctions.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

/**
 * Ajout de données pour le header en front-office
 *
 * @param array $params
 * @return On renvoie un tableau sous la forme [variable smarty] => [contenu]
 */
function devises_hook_header_template_data(&$params) {
	$results['module_devise'] = affiche_module_devise(true);
	return $results;
}

/**
 * Initialisation des variables générales après exécution de configuration.inc.php : initialisation de la devise si demandée par l'utilisateur
 *
 * @param array $params
 * @return
 */
function devises_hook_configuration_end($params) {
	if (!empty($_GET['devise']) && !defined('IN_PEEL_ADMIN')) {
		set_current_devise($_GET['devise']);
		// On redirige 302 après avoir défini la devise (les moteurs ont déjà plus tôt eu droit à redirection 301)
		redirect_and_die(get_current_url(true, false, array('devise')));
	}
}

/**
 * Effectue les actions journalières si le module cron est actif
 *
 * @param array $params
 * @return
 */
function devises_hook_general_actions_24h($params) {
	// Mise à jour des devises
	return update_currencies_rates(vb($GLOBALS['site_parameters']['code']));
}

/**
 * set_current_devise()
 *
 * @param string $currency_id_or_code
 * @param integer $reference_country_id
 * @return
 */
function set_current_devise($currency_id_or_code, $reference_country_id = null)
{
	if ((!empty($_SESSION['session_utilisateur']['devise']) && !defined('IN_PEEL_ADMIN')) || !empty($currency_id_or_code) || !empty($reference_country_id)) {
		if(!empty($_SESSION['session_utilisateur']['devise']) && !defined('IN_PEEL_ADMIN')) {
			// Devise forcée pour l'utilisateur, pas de possibilité d'en choisir une autre
			$cond = "d.id='" . intval($_SESSION['session_utilisateur']['devise']) . "'";
		} elseif(!empty($currency_id_or_code)) {
			// On prend en priorité la devise demandée, sinon la prochaine devise trouvée disponible sur le site
			if(is_numeric($currency_id_or_code)) {
				$cond = "d.id='" . intval($currency_id_or_code) . "'";
			} else {
				$cond = "d.code='" . word_real_escape_string($currency_id_or_code) . "'";
			}
		} elseif(!empty($reference_country_id)) {
			// On cherche uniquement la devise correspondant au pays donné. Si pas disponible sur le site, on ne modifie pas session_devise
			$cond = "c.id='" . intval($reference_country_id) . "'";
			$join = "INNER JOIN peel_pays c ON c.devise=d.code  AND " . get_filter_site_cond('pays', 'c');
		}
		$sql = "SELECT d.*
			FROM peel_devises d
			" . vb($join) . "
			WHERE d.etat='1'" . (!defined('IN_PEEL_ADMIN') || empty($currency_id_or_code) ? " AND " . get_filter_site_cond('devises', 'd') : '') . "
			ORDER BY IF(" . $cond . ", -1, 1) ASC
			LIMIT 1";
		$resDevise = query($sql);
		if ($Devise = fetch_object($resDevise)) {
			$_SESSION['session_devise']['symbole'] = StringMb::html_entity_decode(str_replace('&euro;', '€', $Devise->symbole));
			$_SESSION['session_devise']['symbole_place'] = $Devise->symbole_place;
			$_SESSION['session_devise']['conversion'] = $Devise->conversion;
			$_SESSION['session_devise']['code'] = $Devise->code;
		}
	}
}

/**
 * affiche_module_devise()
 *
 * @param boolean $return_mode
 * @return
 */
function affiche_module_devise($return_mode = false)
{
	if(!empty($_SESSION['session_utilisateur']['devise'])) {
		// Devise imposée
		return null;
	}
	$output = '';
	if(empty($GLOBALS['site_parameters']['currencies_select_in_front_office_disabled'])) {
		$resDevise = query("SELECT *
			FROM peel_devises
			WHERE etat='1' AND " . get_filter_site_cond('devises') . "
			ORDER BY main DESC, devise ASC");
		$url_part = str_replace(array('?devise=' . vb($_GET['devise']), '&devise=' . vb($_GET['devise'])), array('', ''), $_SERVER['REQUEST_URI']);
		if (StringMb::strpos($url_part, '?') === false) {
			$url_part .= '?devise=';
		} else {
			$url_part .= '&devise=';
		}
		$tpl_options = array();
		while ($Devise = fetch_assoc($resDevise)) {
			if(isset($last_main) && $last_main != $Devise['main']) {
				$tpl_options[] = array(
					'value' => 0,
					'issel' => false,
					'name' => '---------'
				);
			}
			$tpl_options[] = array(
				'value' => intval($Devise['id']),
				'issel' => $Devise['code'] == $_SESSION['session_devise']['code'],
				'name' => $Devise['devise']
			);
			$last_main = $Devise['main'];
		}
		if(count($tpl_options)>1) {
			$tpl = $GLOBALS['tplEngine']->createTemplate('modules/devises.tpl');
			$tpl->assign('STR_MODULE_DEVISES_CHOISIR_DEVISE', $GLOBALS['STR_MODULE_DEVISES_CHOISIR_DEVISE']);
			$tpl->assign('url_part', $url_part);
			$tpl->assign('options', $tpl_options);
			$output .= $tpl->fetch();
		}
	}
	if ($return_mode) {
		return $output;
	} else {
		echo $output;
	}
}
