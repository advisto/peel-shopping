<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: newsletter.php 59873 2019-02-26 14:47:11Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_white_label,admin_users,admin_content,admin_communication,admin_finance");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_NEWSLETTERS_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$frm = $_POST;
$form_error_object = new FormError();

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_newsletter($frm);
		break;

	case "modif" :
		affiche_formulaire_modif_newsletter($_GET['id'], $frm);
		break;

	case "suppr" :
		supprime_newsletter($_GET['id']);
		affiche_liste_newsletter();
		break;

	case "insere" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			insere_newsletter($_POST);
			affiche_liste_newsletter();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_ajout_newsletter($frm);
		}
		break;

	case "send" :
		if (!verify_token($_SERVER['PHP_SELF'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$limit = 1; // nombre de messages envoyés pas boucles.
			if (!isset($debut)) {
				$debut = 0;
			} else {
				$debut = intval($_GET['debut']);
			}
			$id = intval($_GET['id']);
			echo send_newsletter($id, $debut, $limit, !empty($_GET['test']));
		} elseif ($form_error_object->has_error('token')) {
			echo $form_error_object->text('token');
		}
		affiche_liste_newsletter();
		break;

	case "maj" :
		if (!verify_token($_SERVER['PHP_SELF'] . $frm['mode'] . $frm['id'])) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			maj_newsletter($_POST['id'], $_POST);
			affiche_liste_newsletter();
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			}
			affiche_formulaire_modif_newsletter($_POST['id'], $frm);
		}
		break;

	default :
		affiche_liste_newsletter();
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un newsletter
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_newsletter(&$frm)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		$frm['sujet'] = "";
		$frm['message'] = "";
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['format'] = "html";
	$frm['titre_bouton'] = $GLOBALS["STR_ADMIN_NEWSLETTERS_CREATE"];
	$frm['product_info_array'] = array();

	affiche_formulaire_newsletter($frm);
}

/**
 * Affiche le formulaire de modification pour la newsletter sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_newsletter($id, &$frm)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations du produit */
		$qid = query("SELECT *
			FROM peel_newsletter
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('newsletter', null, true));
		$frm = fetch_assoc($qid);
	}
	if (!empty($frm)) {
		$frm['id'] = $id;
		$frm["nouveau_mode"] = "maj";
		$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		
		$product_info_array = array();
	if (!empty($frm['product_ids'])) {
        $q = query("SELECT nom_".$_SESSION['session_langue']." as name, reference, id as value
            FROM peel_produits
            WHERE id IN (".$frm['product_ids'].")");
    while($result = fetch_assoc($q)) {
        $product_info_array[] = $result;
    }
		}
    $frm['product_info_array'] = $product_info_array;
		affiche_formulaire_newsletter($frm);
	} else {
		redirect_and_die(get_current_url(false).'?mode=ajout');
	}
}

/**
 * affiche_formulaire_newsletter()
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_newsletter(&$frm)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_newsletter.tpl');
	$i=1;
	foreach ($frm['product_info_array'] as $produits_options) {
		$tpl_produits_options[] = array('value' => intval($produits_options['value']),
			'reference' => $produits_options['reference'],
			'name' => $produits_options['name'],
			'i' => $i,
		);
		$i++;
	}
	$tpl->assign('associated_product_multiple_add_to_cart', vb($GLOBALS['site_parameters']['associated_product_multiple_add_to_cart']));
	$tpl->assign('products_in_newsletter', vn($GLOBALS['site_parameters']['products_in_newsletter']));
	$tpl->assign('produits_options', vb($tpl_produits_options));
	$tpl->assign('nb_produits', count($frm['product_info_array']));
	$tpl->assign('STR_ADMIN_PRODUCT_ORDERED_DELETE', $GLOBALS['STR_ADMIN_PRODUCT_ORDERED_DELETE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH', $GLOBALS['STR_ADMIN_COMMANDER_OR_ADD_PRODUCT_WITH_FAST_SEARCH']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_REFERENCE', $GLOBALS['STR_REFERENCE']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM', $GLOBALS['STR_ADMIN_PRODUCT_ORDERED_DELETE_CONFIRM']);
	$tpl->assign('administrer_url', $GLOBALS['administrer_url']);
	$tpl->assign('action', get_current_url(false) . '?start=0');
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
	$tpl->assign('mode', $frm["nouveau_mode"]);
	$tpl->assign('id', intval($frm['id']));
	$tpl->assign('template_technical_code_options', get_email_template_options('technical_code', null, null, vb($frm['template_technical_code'])));
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
	
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $this_lang) {
		$tpl_langs[] = array('lng' => $this_lang,
			'sujet' => vb($frm['sujet_' . $this_lang]),
			'message_te' => getTextEditor('message_' . $this_lang, '100%', 500, vb($frm['message_' . $this_lang]))
			);
	}
	$tpl->assign('langs', $tpl_langs);

	$tpl->assign('titre_bouton', $frm['titre_bouton']);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_FORM_TITLE', $GLOBALS['STR_ADMIN_NEWSLETTERS_FORM_TITLE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_WARNING', $GLOBALS['STR_ADMIN_NEWSLETTERS_WARNING']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE', $GLOBALS['STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE_INFO', $GLOBALS['STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE_INFO']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_MESSAGE', $GLOBALS['STR_MESSAGE']);
	echo $tpl->fetch();
}

/**
 * Supprime la newsletter spécifié par $id.
 *
 * @param integer $id
 * @return
 */
function supprime_newsletter($id)
{
	$qid = query("SELECT *
		FROM peel_newsletter
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('newsletter', null, true));
	$n = fetch_assoc($qid);

	/* Efface la newsletter */
	query("DELETE FROM peel_newsletter
		WHERE id=" . intval($id) . " AND " . get_filter_site_cond('newsletter', null, true));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_NEWSLETTERS_MSG_NEWSLETTER_DELETED'], $n['sujet_' . $_SESSION['session_langue']])))->fetch();
}

/**
 * Ajoute la newsletter dans la table newsletter
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_newsletter($frm)
{
	 $req = "INSERT INTO peel_newsletter (";
    // Insertion de la nouvelle news en fonction des langues définies sur le site
    foreach($GLOBALS['admin_lang_codes'] as $this_lang) {
        $req .= "sujet_" . $this_lang . ", message_" . $this_lang . ",";
    }
    $req .= "date, format, statut, template_technical_code, product_ids, site_id) VALUES (";
    foreach($GLOBALS['admin_lang_codes'] as $this_lang) {
        $req .= "'" . nohtml_real_escape_string($frm['sujet_' . $this_lang]) . "','" . real_escape_string($frm['message_' . $this_lang]) . "',";
    }
    $req .= " '" . date('Y-m-d H:i:s', time()) . "', 'html', 'envoi nok', '" . nohtml_real_escape_string($frm['template_technical_code']) . "' , '" . implode(',', nohtml_real_escape_string(vb($frm['product_ids'], array()))). "', '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "')";

	$qid = query($req);
}

/**
 * Met à jour la newsletter $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function maj_newsletter($id, $frm)
{
	$req = "UPDATE peel_newsletter
        SET ";
    // Maj d'une news en fonction des langues définies sur le site
    foreach($GLOBALS['admin_lang_codes'] as $this_lang) {
        $req .= "
            sujet_" . $this_lang . " = '" . nohtml_real_escape_string($frm['sujet_' . $this_lang]) . "',
            message_" . $this_lang . " = '" . real_escape_string($frm['message_' . $this_lang]) . "',";
    }
    $req .= "
            format='html',
            date = '" . date('Y-m-d H:i:s', time()) . "',
            template_technical_code = '" . nohtml_real_escape_string($frm['template_technical_code']) . "',
            product_ids = '" . implode(',', nohtml_real_escape_string(vb($frm['product_ids'], array()))) . "',
            site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
        WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('newsletter', null, true);
	$qid = query($req);
}

/**
 * affiche_liste_newsletter()
 *
 * @return
 */
function affiche_liste_newsletter()
{
	$sql = "SELECT *
		FROM peel_newsletter
		WHERE " . get_filter_site_cond('newsletter', null, true) . "";
	$HeaderTitlesArray = array($GLOBALS['STR_ADMIN_ACTION'], 'sujet_' . $_SESSION['session_langue'] => $GLOBALS['STR_ADMIN_NAME'], 'date' => $GLOBALS['STR_ADMIN_CREATION_DATE'], $GLOBALS['STR_ADMIN_NEWSLETTERS_SUBSCRIBERS_NUMBER'], 'format' => $GLOBALS['STR_ADMIN_FORMAT'], 'statut' => $GLOBALS['STR_STATUS'], 'date_envoi' => $GLOBALS['STR_ADMIN_NEWSLETTERS_LAST_SENDING'], $GLOBALS['STR_ADMIN_NEWSLETTERS_SEND_TO_USERS'], $GLOBALS['STR_ADMIN_NEWSLETTERS_SENDING_TEST'], 'site_id' => $GLOBALS['STR_ADMIN_WEBSITE']);
		
	$Links = new Multipage($sql, 'admin_liste_newsletter', 40);
	$Links->HeaderTitlesArray = $HeaderTitlesArray;
	$Links->OrderDefault = "date";
	$Links->SortDefault = "DESC";

	$results_array = $Links->query();

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_liste_newsletter.tpl');
	$tpl->assign('links_header_row', $Links->getHeaderRow());
	$tpl->assign('links_multipage', $Links->GetMultipage());
	$tpl->assign('is_crons_module_active', check_if_module_active('crons'));
	$tpl->assign('add_src', $GLOBALS['administrer_url'] . '/images/add.png');
	$tpl->assign('add_href', get_current_url(false) . '?mode=ajout');
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('mail_src', $GLOBALS['administrer_url'] . '/images/mail.gif');

	if (!empty($results_array)) {
		$tpl_results = array();
		$i = 0;
		foreach ($results_array as $ligne) {
			$this_langs_array = array();
			$titre = $ligne['sujet_' . $_SESSION['session_langue']];
			foreach($GLOBALS['admin_lang_codes'] as $this_lang) {
				if (!empty($ligne['message_' . $this_lang]) || !empty($ligne['sujet_' . $this_lang])) {
					$this_langs_array[] = $this_lang;
				}
				if (empty($titre)) {
					$titre = $ligne['sujet_' . $this_lang];
				}
			}
			$titre = '[' . StringMb::strtoupper(implode(",", $this_langs_array)) . '] ' . $titre;

			$sql_u = "SELECT count(*) AS this_count
				FROM peel_utilisateurs
				WHERE newsletter = '1' AND " . get_filter_site_cond('utilisateurs') . " AND etat='1' AND email_bounce NOT LIKE '5.%' AND email!='' AND lang IN ('" . implode("','", $this_langs_array) . "')";
			if(!empty($GLOBALS['site_parameters']['newsletter_and_commercial_double_optin_validation'])) {
				$sql_u .= " AND newsletter_validation_date NOT LIKE '0000-00-00%'";
			}
			$query = query($sql_u);
			$result = fetch_assoc($query);
			$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
				'sujet' => $titre,
				'drop_href' => get_current_url(false) . '?mode=suppr&id=' . $ligne['id'],
				'edit_href' => get_current_url(false) . '?mode=modif&id=' . $ligne['id'],
				'date' => get_formatted_date($ligne['date']),
				'subscribers_number' => $result['this_count'],
				'format' => $ligne['format'],
				'statut' => $ligne['statut'],
				'date_envoi' => $ligne['date_envoi'],
				'mail_href' => get_current_url(false) . '?mode=send&id=' . $ligne['id'] . '&format=' . $ligne['format'] . '&token=' . get_form_token_input($_SERVER['PHP_SELF'], true, false),
				'test_href' => get_current_url(false) . '?mode=send&id=' . $ligne['id'] . '&format=' . $ligne['format'] . '&test=test&token=' . get_form_token_input($_SERVER['PHP_SELF'], true, false), 
				'site_name' => get_site_name($ligne['site_id'])
				);
			$i++;
		}
		$tpl->assign('results', $tpl_results);
	}
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_TITLE', $GLOBALS['STR_ADMIN_NEWSLETTERS_TITLE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CRON_ACTIVATED_EXPLAIN', $GLOBALS['STR_ADMIN_NEWSLETTERS_CRON_ACTIVATED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CRON_DEACTIVATED_EXPLAIN', $GLOBALS['STR_ADMIN_NEWSLETTERS_CRON_DEACTIVATED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_CREATE', $GLOBALS['STR_ADMIN_NEWSLETTERS_CREATE']);
	$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
	$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
	$tpl->assign('STR_ADMIN_CREATION_DATE', $GLOBALS['STR_ADMIN_CREATION_DATE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SUBSCRIBERS_NUMBER', $GLOBALS['STR_ADMIN_NEWSLETTERS_SUBSCRIBERS_NUMBER']);
	$tpl->assign('STR_ADMIN_FORMAT', $GLOBALS['STR_ADMIN_FORMAT']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_LAST_SENDING', $GLOBALS['STR_ADMIN_NEWSLETTERS_LAST_SENDING']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SEND_TO_USERS', $GLOBALS['STR_ADMIN_NEWSLETTERS_SEND_TO_USERS']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SENDING_TEST', $GLOBALS['STR_ADMIN_NEWSLETTERS_SENDING_TEST']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_DELETE', $GLOBALS['STR_DELETE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_UPDATE', $GLOBALS['STR_ADMIN_NEWSLETTERS_UPDATE']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SEND_CONFIRM', $GLOBALS['STR_ADMIN_NEWSLETTERS_SEND_CONFIRM']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_SEND_ALL_USERS', $GLOBALS['STR_ADMIN_NEWSLETTERS_SEND_ALL_USERS']);
	$tpl->assign('STR_ADMIN_DELETE_WARNING', $GLOBALS['STR_ADMIN_DELETE_WARNING']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_NOTHING_FOUND', $GLOBALS['STR_ADMIN_NEWSLETTERS_NOTHING_FOUND']);
	$tpl->assign('STR_ADMIN_NEWSLETTERS_TEST_SENDING_TO_ADMINISTRATORS', $GLOBALS['STR_ADMIN_NEWSLETTERS_TEST_SENDING_TO_ADMINISTRATORS']);
	echo $tpl->fetch();
}

