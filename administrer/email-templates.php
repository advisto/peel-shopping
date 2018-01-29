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
// $Id: email-templates.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage,admin_content,admin_communication,admin_finance");

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TITLE'];

$form_error_object = new FormError();
$report = '';
$output = '';

// Modification d'un template
if (!empty($_GET['id'])) {
	if (isset($_POST['form_name'], $_POST['form_subject'], $_POST['form_text'])) {
		if ($_POST['form_id_cat'] == "0") $form_error_object->add('form_id_cat');
		if (empty($_POST['form_name'])) $form_error_object->add('form_name');
		if (empty($_POST['form_subject'])) $form_error_object->add('form_subject');
		if (!verify_token('email-templates.php?id=' . $_GET['id'])) $form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		if (empty($_POST['form_text'])) {
			$form_error_object->add('form_text');
		} elseif (strip_tags($_POST['form_text']) != $_POST['form_text']) {
			// ATTENTION ne pas utiliser StringMb::strip_tags car sinon les remplacements d'espaces divers altèreraient la validité du test ci-dessus
			// On corrige le HTML si nécessaire
			if (StringMb::strpos($_POST['form_text'], '<br>') === false && StringMb::strpos($_POST['form_text'], '<br />') === false && StringMb::strpos($_POST['form_text'], '</p>') === false && StringMb::strpos($_POST['form_text'], '<table') === false) {
				// Par exemple si on a mis des balises <b> ou <u> dans email sans mettre de <br /> nulle part, on rajoute <br /> en fin de ligne pour pouvoir nettoyer ensuite le HTML de manière cohérente
				$added_br = true;
				$_POST['form_text'] = str_replace(array("\n"), "<br />\n", str_replace(array("\r\n", "\r"), "\n", $_POST['form_text']));
			}
			$_POST['form_text'] = StringMb::getCleanHTML($_POST['form_text'], null, true, true, true, null, false);
			if (!empty($added_br)) {
				$_POST['form_text'] = str_replace(array("<br />\n"), "\n", $_POST['form_text']);
			}
		}

		if ($form_error_object->count()) {
			if ($form_error_object->has_error['token']) {
				$action = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $form_error_object->text['token']))->fetch();
			} else {
				$action = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_FILL_IN_ALL']))->fetch();
			}
			$ok = false;
		} else {
			query('UPDATE peel_email_template SET
					site_id="' . intval(vn($_POST['site_id'])) . '",
					technical_code="' . nohtml_real_escape_string(trim($_POST['form_technical_code'])) . '",
					name="' . nohtml_real_escape_string(trim($_POST['form_name'])) . '",
					subject="' . real_escape_string(trim($_POST['form_subject'])) . '",
					text="' . real_escape_string($_POST['form_text']) . '",
					id_cat="' .intval($_POST['form_id_cat']) . '",
					lang="' . nohtml_real_escape_string(trim($_POST['form_lang'])) . '",
					default_signature_code ="' . nohtml_real_escape_string($_POST['default_signature_code']) . '"
				WHERE id="' . intval($_GET['id']) . '"');
			$action = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS["STR_ADMIN_EMAIL_TEMPLATES_MSG_UPDATED"]))->fetch();
		}
	}

	$query_update = query('SELECT id, technical_code, name, subject, text, lang, id_cat, default_signature_code, site_id
		FROM peel_email_template
		WHERE id="' . intval($_GET['id']) . '" AND ' . get_filter_site_cond('email_template', null, true) . '
		LIMIT 1');
	$template_infos = fetch_assoc($query_update);
	// On va chercher les catégories
	$sql = 'SELECT id, name_' . $_SESSION['session_langue'] . ' AS name, site_id
		FROM peel_email_template_cat
		WHERE ' . get_filter_site_cond('email_template_cat', null) . '
		ORDER BY name ASC';
	$query = query($sql);
	$tpl_categories_list = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_categories_list.tpl');
	$tpl_categories_list->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl_options = array();
	while ($row_categories = fetch_assoc($query)) {
		$tpl_options[] = array('value' => intval($row_categories['id']),
			'issel' => vb($_POST['form_id_cat']) == $row_categories['id'] || $row_categories['id'] == $template_infos['id_cat'],
			'name' => get_site_info($row_categories) . $row_categories['name']
			);
	}
	$tpl_categories_list->assign('options', $tpl_options);
	$categories_list = $tpl_categories_list->fetch();

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_output.tpl');
	$tpl->assign('action_html', (isset($action) ? $action : ''));
	$tpl->assign('href', $GLOBALS['administrer_url'] . '/email-templates.php');
	$tpl->assign('id', $_GET['id']);
	$tpl->assign('action', $GLOBALS['administrer_url'] . '/email-templates.php?id=' . $_GET['id']);
	$tpl->assign('form_token', get_form_token_input('email-templates.php?id=' . $_GET['id'] . ''));
	$tpl->assign('categories_list', $categories_list);
	$tpl->assign('technical_code', (isset($_POST['form_technical_code']) ? $_POST['form_technical_code'] : vb($template_infos['technical_code'])));
	$tpl->assign('name', (isset($_POST['form_name']) ? $_POST['form_name'] : vb($template_infos['name'])));
	$tpl->assign('subject', vb($template_infos['subject']));
	$tpl->assign('text', vb($template_infos['text']));
	$tpl->assign('signature_template_options', get_email_template_options('technical_code', null, vb($template_infos['lang']), vb($template_infos['default_signature_code']), true));

	$tpl_langs = array();
	$langs_array = $GLOBALS['admin_lang_codes'];
	if (!empty($template_infos['lang']) && !in_array($template_infos['lang'], $GLOBALS['admin_lang_codes'])) {
		$langs_array[] = $template_infos['lang'];
	}
	foreach ($langs_array as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'issel' => vb($template_infos['lang']) == $lng
			);
	}
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($template_infos['site_id'])));
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('emailLinksExplanations', emailLinksExplanations());
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_TEXT', $GLOBALS['STR_TEXT']);
	$tpl->assign('STR_NUMBER', $GLOBALS['STR_NUMBER']);
	$tpl->assign('STR_CLICK_HERE', $GLOBALS['STR_CLICK_HERE']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_UPDATE_TEMPLATE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_UPDATE_TEMPLATE']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_SIGNATURE', $GLOBALS['STR_SIGNATURE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_WARNING', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_WARNING']);
	$output .= $tpl->fetch();
}
// Insertion d'un nouveau template = requete sql
if (isset($_POST['form_name'], $_POST['form_subject'], $_POST['form_text'], $_POST['form_lang']) && empty($_GET['id'])) {
	if (empty($_POST['form_name']) || empty($_POST['form_subject']) || empty($_POST['form_text']) || empty($_POST['form_lang']) || $_POST['form_id_cat'] == "0") {
		$action = $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ERR_FILL_IN_ALL']))->fetch();
	}
	if (!verify_token('email-templates.php-ajout')) {
		$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
	}
	if (empty($_POST['form_subject'])) {
		$form_error_object->add('form_subject');
	}
	if (empty($_POST['form_text'])) {
		$form_error_object->add('form_text');
	}
	if (!$form_error_object->count()) {
		query('INSERT INTO peel_email_template (site_id, technical_code, name, subject, text, lang, id_cat, default_signature_code ) VALUES(
			"' . nohtml_real_escape_string(trim($_POST['site_id'])) . '",
			"' . nohtml_real_escape_string(trim($_POST['form_technical_code'])) . '",
			"' . nohtml_real_escape_string(trim($_POST['form_name'])) . '",
			"' . real_escape_string(trim($_POST['form_subject'])) . '",
			"' . real_escape_string(trim($_POST['form_text'])) . '",
			"' . nohtml_real_escape_string(trim($_POST['form_lang'])) . '",
			"' . intval($_POST['form_id_cat']) . '",
			"' . nohtml_real_escape_string(trim($_POST['default_signature_code'])) . '")');
		$action = $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_MSG_TEMPLATE_CREATED']))->fetch();
	}
}
// Insertion d'un template
if (empty($_GET['id'])) {
	// On va chercher les catégories
	$query = query('SELECT id, name_' . $_SESSION['session_langue'] . ' AS name, site_id
		FROM peel_email_template_cat
		WHERE ' . get_filter_site_cond('email_template_cat', null) . '
		ORDER BY name ASC');
	$tpl_categories_list = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_categories_list.tpl');
	$tpl_categories_list->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl_options = array();
	while ($row_categories = fetch_assoc($query)) {
		$tpl_options[] = array('value' => intval($row_categories['id']),
			'issel' => vb($_POST['form_id_cat']) == $row_categories['id'],
			'name' => get_site_info($row_categories) . $row_categories['name']
			);
	}
	$tpl_categories_list->assign('options', $tpl_options);
	$categories_list = $tpl_categories_list->fetch();

	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_output2.tpl');
	$tpl->assign('action_html', (isset($action) ? $action : ''));
	$tpl->assign('form_token', get_form_token_input('email-templates.php-ajout'));
	$tpl->assign('categories_list', $categories_list);
	$tpl->assign('form_technical_code', vb($_POST['form_technical_code']));
	$tpl->assign('form_name', vb($_POST['form_name']));
	$tpl->assign('form_subject', vb($_POST['form_subject']));
	$tpl->assign('form_text', vb($_POST['form_text']));
	$tpl_langs = array();
	$langs_array = $GLOBALS['admin_lang_codes'];
	if (!empty($_POST['form_lang']) && !in_array($_POST['form_lang'], $GLOBALS['admin_lang_codes'])) {
		$langs_array[] = $_POST['form_lang'];
	}
	foreach ($langs_array as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'issel' => vb($_POST['form_lang']) == $lng
			);
	}
	$tpl->assign('signature_template_options', get_email_template_options('technical_code', null, null, null, true));
	$tpl->assign('langs', $tpl_langs);
	$tpl->assign('emailLinksExplanations', emailLinksExplanations());
	$tpl->assign('site_id_select_options', get_site_id_select_options(vb($template_infos['site_id'])));
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_MSG_LAYOUT_EXPLAINATION', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_MSG_LAYOUT_EXPLAINATION']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN']);
	$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME']);
	$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
	$tpl->assign('STR_TEXT', $GLOBALS['STR_TEXT']);
	$tpl->assign('STR_SIGNATURE', $GLOBALS['STR_SIGNATURE']);
	$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
	
	$output .= $tpl->fetch();
}
// Filtre de recherche de modèle d'email
$tpl = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_search.tpl');
$form_error_object = new FormError();
$tpl_options = array();
// Récupération des catégories de template email
$query = query('SELECT tc.id, tc.name_' . $_SESSION['session_langue'] . ' AS name, tc.site_id
	FROM peel_email_template_cat tc
	INNER JOIN peel_email_template t ON t.id_cat=tc.id AND t.active="TRUE" AND ' . get_filter_site_cond('email_template', 't', true) . '
	WHERE ' . get_filter_site_cond('email_template_cat', 'tc') . '
	GROUP BY tc.id
	ORDER BY name');
while ($row_categories = fetch_assoc($query)) {
	$tpl_options[] = array('value' => intval($row_categories['id']),
		'issel' => vb($_GET['form_lang_template']) == $row_categories['id'],
		'name' => get_site_info($row_categories) . $row_categories['name']
		);
}
$tpl->assign('options', $tpl_options);
$tpl_langs = array();
foreach ($GLOBALS['admin_lang_codes'] as $lng) {
	$tpl_langs[] = array('name' => $lng,
		'value' => $lng,
		'issel' => vb($_GET['form_lang_template']) == $lng
		);
}
$tpl->assign('langs', $tpl_langs);
$tpl->assign('etat', vb($_GET['etat']));
$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
$tpl->assign('STR_ADMIN_ACTIVATED', $GLOBALS['STR_ADMIN_ACTIVATED']);
$tpl->assign('STR_ADMIN_DEACTIVATED', $GLOBALS['STR_ADMIN_DEACTIVATED']);
$tpl->assign('STR_SEARCH', $GLOBALS['STR_SEARCH']);
$tpl->assign('STR_ADMIN_CHOOSE_SEARCH_CRITERIA', $GLOBALS['STR_ADMIN_CHOOSE_SEARCH_CRITERIA']);
$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
$output .= $tpl->fetch();

// Affichage de tous les templates
$sql = 'SELECT id, technical_code, name, subject, text, lang, active, id_cat, site_id
	FROM peel_email_template
	WHERE ' . get_filter_site_cond('email_template', null, true);

if (!empty($_GET['form_lang_template'])) {
	$sql .= ' AND lang = "' . nohtml_real_escape_string($_GET['form_lang_template']) . '"';
}
if (empty($_GET['form_lang_template'])) {
	$sql .= '';
}
if (!empty($_GET['form_id_cat'])) {
	$sql .= ' AND id_cat = "' . intval($_GET['form_id_cat']) . '"';
}
if (empty($_GET['form_id_cat'])) {
	$sql .= '';
}

if (isset($_GET['etat']) && empty($_GET['etat'])) {
	$sql .= '';
}
if (isset($_GET['etat']) && $_GET['etat'] == "1") {
	$sql .= ' AND active = "TRUE"';
}
if (isset($_GET['etat']) && $_GET['etat'] == "0") {
	$sql .= ' AND active = "FALSE"';
}

$HeaderTitlesArray = array('id' => $GLOBALS['STR_ADMIN_ID'], 'technical_code' => $GLOBALS['STR_ADMIN_TECHNICAL_CODE'], 'id_cat' => $GLOBALS['STR_CATEGORY'], 'name' => $GLOBALS['STR_ADMIN_NAME'], 'subject' => $GLOBALS['STR_ADMIN_SUBJECT'], 'text' => $GLOBALS['STR_ADMIN_HTML_TEXT'], 'lang' => $GLOBALS['STR_ADMIN_LANGUAGE'], 'active' => $GLOBALS['STR_STATUS'], $GLOBALS['STR_ADMIN_ACTION'], 'site_id' => $GLOBALS['STR_ADMIN_WEBSITE']);

$Links = new Multipage($sql, 'email_templates', 100);
$Links->HeaderTitlesArray = $HeaderTitlesArray;
$Links->OrderDefault = "technical_code, lang";
$Links->SortDefault = "ASC";

$results_array = $Links->query();

$tpl = $GLOBALS['tplEngine']->createTemplate('admin_email-templates_report.tpl');
$tpl->assign('links_header_row', $Links->getHeaderRow());
$tpl->assign('links_multipage', $Links->GetMultipage());

if (!empty($results_array)) {
	$tpl_results = array();
	$i = 0;
	$bold = 0;
	foreach ($results_array as $this_template) {
		// On récupère la catégorie du template (s'il en a une)
		$category_name = '';
		if ($this_template['id_cat'] != 0) {
			$query = query('SELECT name_' . $_SESSION['session_langue'] . ' AS name, site_id
				FROM peel_email_template_cat
				WHERE id=' . intval($this_template['id_cat']) . ' AND ' . get_filter_site_cond('email_template_cat', null));
			if($row_category = fetch_assoc($query)) {
				$category_name = get_site_info($row_category) . $row_category['name'];
			}else {
				$category_name = '';
			}
		}
		$tpl_results[] = array('tr_rollover' => tr_rollover($i, true),
			'id' => $this_template["id"],
			'technical_code' => StringMb::str_shorten_words($this_template["technical_code"], 20, '<br />'),
			'category_name' => $category_name,
			'name' => $this_template["name"],
			'subject' => StringMb::str_shorten_words($this_template["subject"], 40),
			'text' => StringMb::str_shorten_words($this_template["text"], 40),
			'lang' => $this_template["lang"],
			'etat_onclick' => 'change_status("email-templates", "' . $this_template['id'] . '", this, "'.$GLOBALS['administrer_url'] . '")',
			'etat_src' => $GLOBALS['administrer_url'] . '/images/' . ($this_template["active"] != "TRUE" ? 'puce-blanche.gif' : 'puce-verte.gif'),
			'edit_href' => 'email-templates.php?id=' . $this_template['id'],
			'site_name' => get_site_name($this_template['site_id'])
			);
		$i++;
	}
	$tpl->assign('results', $tpl_results);
}
$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TITLE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TITLE']);
$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
$tpl->assign('STR_ADMIN_SUBJECT', $GLOBALS['STR_ADMIN_SUBJECT']);
$tpl->assign('STR_ADMIN_LANGUAGE', $GLOBALS['STR_ADMIN_LANGUAGE']);
$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
$tpl->assign('STR_ADMIN_ACTION', $GLOBALS['STR_ADMIN_ACTION']);
$tpl->assign('STR_ADMIN_ID', $GLOBALS['STR_ADMIN_ID']);
$tpl->assign('STR_CATEGORY', $GLOBALS['STR_CATEGORY']);
$tpl->assign('STR_ADMIN_HTML_TEXT', $GLOBALS['STR_ADMIN_HTML_TEXT']);
$tpl->assign('STR_ADMIN_NAME', $GLOBALS['STR_ADMIN_NAME']);
$tpl->assign('STR_MODIFY', $GLOBALS['STR_MODIFY']);

$report = $tpl->fetch();

include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output . $report;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * emailLinksExplanations()
 *
 * @return
 */
function emailLinksExplanations()
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_emailLinksExplanations.tpl');
	if(empty($_SESSION['session_admin_multisite']) || $_SESSION['session_admin_multisite'] != $GLOBALS['site_id']) {
		$this_wwwroot =  get_site_wwwroot($_SESSION['session_admin_multisite'], $_SESSION['session_langue']);
	} else {
		$this_wwwroot =  $GLOBALS['wwwroot'];
	}
	$tpl->assign('link', $this_wwwroot);
	$tpl->assign('is_annonce_module_active', check_if_module_active('annonces'));
	$tpl->assign('is_vitrine_module_active', check_if_module_active('vitrine'));
	
	if(check_if_module_active('vitrine')) {
		$tpl->assign('explication_tag_windows', get_explication_tag_windows(true));
	}
	if(check_if_module_active('annonces')) {
		$tpl->assign('explication_tag_last_ads_verified', get_explication_tag_last_ads_verified(true));
		$tpl->assign('explication_tag_list_category_ads', get_explication_tag_list_category_ads(true));
		$tpl->assign('explication_tag_list_ads_by_category', get_explication_tag_list_ads_by_category(true));
	}
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_EXAMPLES_TITLE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_EXAMPLES_TITLE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAGS_EXPLAIN']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_WWWROOT', $GLOBALS['STR_ADMIN_WWWROOT']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_SITE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_SITE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_PHP_SELF', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_PHP_SELF']);
	$tpl->assign('STR_ADMIN_REMOTE_ADDR', $GLOBALS['STR_ADMIN_REMOTE_ADDR']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_DATETIME', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_DATETIME']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_NEWSLETTER', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_NEWSLETTER']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXPLAIN', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXPLAIN']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXAMPLE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_LINK_EXAMPLE']);
	$tpl->assign('STR_ADMIN_EMAIL_TEMPLATES_TAG_OTHER_AVAILABLE', $GLOBALS['STR_ADMIN_EMAIL_TEMPLATES_TAG_OTHER_AVAILABLE']);
	return $tpl->fetch();
}

