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
// $Id: articles.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv('admin_content,admin_communication,admin_finance');

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_ARTICLES_TITLE'];
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");

$id = intval(vn($_REQUEST['id']));

if (!isset($form_error_object)) {
	$form_error_object = new FormError();
}

switch (vb($_REQUEST['mode'])) {
	case "ajout" :
		affiche_formulaire_ajout_article(vn($_REQUEST['rubrique_id']), $frm, $form_error_object);
		break;

	case "modif" :
		affiche_formulaire_modif_article($id, $frm, $form_error_object);
		break;

	case "suppr" :
		supprime_article($id);
		affiche_liste_articles($_POST);
		break;

	case "supprfile" :
		supprime_fichier($id, $_GET['file']);
		affiche_formulaire_modif_article($id, $frm, $form_error_object);
		break;

	case "insere" :
		if (!empty($_POST)) {
			$frm = $_POST;
		}
		$form_error_object->valide_form($frm,
			array('rubriques' => $GLOBALS['STR_ADMIN_ARTICLES_ERR_CHOOSE_ONE_CATEGORIE']));
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			if(!empty($frm['titre_' . $lng])) {
				$title_not_empty=true;
				break;
			}
		}
		if (empty($title_not_empty)) {
			// Il faut au moins un nom d'article
			foreach ($GLOBALS['admin_lang_codes'] as $lng) {
				$form_error_object->add('titre_' . $lng, $GLOBALS['STR_ADMIN_ERR_CHOOSE_TITLE']);
			}
		}
		if (!verify_token($_SERVER['PHP_SELF'] . vb($frm['mode']) . vb($frm['id']))) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$_POST['image1'] = upload('image1', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($_POST['image1']));
			insere_article($_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ARTICLES_MSG_CREATED_OK'], vb($_POST['titre_' . $_SESSION['session_langue']]))))->fetch();
			unset($_POST['etat']);
			affiche_liste_articles($_POST);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
			if (!isset($rubrique_id)) {
				$rubrique_id = 0;
			}
			affiche_formulaire_ajout_article(vn($_REQUEST['rubriques']), $frm, $form_error_object);
		}
		break;

	case "maj" :
		if (!empty($_POST)) {
			$frm = $_POST;
		}
		$tested_fields = array('rubriques' => $GLOBALS['STR_ADMIN_ARTICLES_ERR_CHOOSE_ONE_CATEGORIE']);
		if (!empty($tested_fields['texte_' . $_SESSION['session_langue']])) {
			$tested_fields['titre_' . $_SESSION['session_langue']] = $GLOBALS['STR_ADMIN_ERR_CHOOSE_TITLE'];
		}
		$form_error_object->valide_form($frm, $tested_fields);
		if (!verify_token($_SERVER['PHP_SELF'] . vb($frm['mode']) . vb($frm['id']))) {
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		if (!$form_error_object->count()) {
			$_POST['image1'] = upload('image1', false, 'image_or_pdf', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($_POST['image1']));
			maj_article($frm['id'], $_POST);
			echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_MSG_CHANGES_OK'], vn($_REQUEST['id']))))->fetch();
			unset($_POST['etat']);
			affiche_liste_articles($_POST);
		} else {
			if ($form_error_object->has_error('token')) {
				echo $form_error_object->text('token');
			} else {
				echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $GLOBALS['STR_ADMIN_ERR_FORM_INCOMPLETE']))->fetch();
			}
			affiche_formulaire_modif_article($frm['id'], $frm, $form_error_object);
		}
		break;

	case "recherche" :
		affiche_liste_articles($_POST);
		break;

	case "sansrubrique" :
		affiche_liste_articles($_POST);
		break;

	default :
		affiche_liste_articles(null);
		break;
}

include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Affiche un formulaire vierge pour ajouter un article
 *
 * @param integer $rubriques
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_ajout_article($rubriques = 0, &$frm, &$form_error_object)
{
	/* Valeurs par défaut */
	if(empty($frm)) {
		$frm = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['titre_' . $lng] = "";
			$frm['chapo_' . $lng] = "";
			$frm['texte_' . $lng] = "";
			/* gestion des meta */
			$frm['meta_titre_' . $lng] = "";
			$frm['meta_key_' . $lng] = "";
			$frm['meta_desc_' . $lng] = "";
		}
		$frm['etat'] = "";
		$frm['technical_code'] = "";
		$frm['tva'] = "";
		$frm['on_special'] = "";
		$frm['on_reseller'] = "";
		$frm['on_rollover'] = "";
		$frm['on_new'] = "";
		$frm['on_focus'] = "";
		$frm['image1'] = "";
		$frm['position'] = "";
	}
	if(!is_array($rubriques)) {
		$frm['rubriques'] = array($rubriques);
	} else {
		$frm['rubriques'] = $rubriques;
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['lang'] = $_SESSION['session_langue'];
	$frm['isAttached'] = "";
	$frm['date_insere'] = "";
	$frm['date_maj'] = "";
	$frm['site_id'] = "";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$frm['site_country'] =  $GLOBALS['site_parameters']['site_country_allowed_array'];
	}

	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_ARTICLES_FORM_ADD_BUTTON'];
	/* Construit la liste des catégories, présélectionne la catégorie racine */

	affiche_formulaire_article($frm, $form_error_object);
}

/**
 * Affiche le formulaire de modification pour l'article sélectionné
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_modif_article($id, &$frm, &$form_error_object)
{
	if(empty($frm)){
		// Pas de données venant de validation de formulaire, donc on charge le contenu de la base de données
		/* Charge les informations de l'article */
		$qid = query("SELECT *
			FROM peel_articles
			WHERE id = " . intval($id) . " AND " . get_filter_site_cond('articles', null, true) . "");
		if ($frm = fetch_assoc($qid)) {
			if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
				$frm['site_country'] = explode(',', vb($frm['site_country']));
			}
		} else {
			echo $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ARTICLES_ERR_NOT_FOUND'], $id)))->fetch();
			return false;
		}
	}
	/* Charge les catégories de l'article */
	$qid = query("SELECT pa.rubrique_id,nom_" . $_SESSION['session_langue'] . " AS nom_rubrique
		FROM peel_articles_rubriques pa
		INNER JOIN peel_rubriques pr ON pa.rubrique_id=pr.id AND " . get_filter_site_cond('rubriques', 'pr') . "
		WHERE article_id = " . intval($id) . "");
	$frm['rubriques'] = array();
	while ($cat = fetch_assoc($qid)) {
		$frm['rubriques'][] = $cat['rubrique_id'];
		$frm['rubriques'][] = $cat['nom_rubrique'];
	}

	$hook_result = call_module_hook('affiche_formulaire_modif_article', array('id'=>$id, 'frm'=>$frm), 'array');
	$frm = array_merge_recursive_distinct($frm, $hook_result);
		
	$frm['nouveau_mode'] = "maj";
	$frm['normal_bouton'] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];

	affiche_formulaire_article($frm, $form_error_object);
}

/**
 * affiche_formulaire_article()
 *
 * @param array $frm Array with all fields data
 * @param class $form_error_object
 * @return
 */
function affiche_formulaire_article(&$frm, &$form_error_object)
{
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_article.tpl');
	$tpl->assign('add_category_url', $GLOBALS['administrer_url'] . '/rubriques.php?mode=ajout');
	$rubrique_options = get_categories_output(null, 'rubriques', vb($frm['rubriques']), 'option', '&nbsp;&nbsp;', null, null, true, 80);
	$tpl->assign('rubrique_options', $rubrique_options);
	$tpl->assign('STR_ADMIN_WEBSITE', $GLOBALS['STR_ADMIN_WEBSITE']);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('STR_ADMIN_SITE_COUNTRY', $GLOBALS['STR_ADMIN_SITE_COUNTRY']);
	}
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_ADMIN_ARTICLES_CREATE_CATEGORY_FIRST', $GLOBALS['STR_ADMIN_ARTICLES_CREATE_CATEGORY_FIRST']);
	$tpl->assign('STR_ADMIN_ARTICLES_CATEGORIE', $GLOBALS['STR_ADMIN_ARTICLES_CATEGORIE']);
	$tpl->assign('STR_STATUS', $GLOBALS['STR_STATUS']);
	$tpl->assign('STR_ADMIN_OFFLINE', $GLOBALS['STR_ADMIN_OFFLINE']);
	$tpl->assign('STR_ADMIN_ONLINE', $GLOBALS['STR_ADMIN_ONLINE']);
	$tpl->assign('STR_ADMIN_DISPLAY_ON_CONTENT_CATEGORY_PAGE', $GLOBALS['STR_ADMIN_DISPLAY_ON_CONTENT_CATEGORY_PAGE']);
	$tpl->assign('STR_ADMIN_SEE_RESULT_IN_REAL', $GLOBALS['STR_ADMIN_SEE_RESULT_IN_REAL']);
	$tpl->assign('STR_ADMIN_TITLE', $GLOBALS['STR_ADMIN_TITLE']);
	$tpl->assign('STR_ADMIN_OVER_TITLE', $GLOBALS['STR_ADMIN_OVER_TITLE']);
	$tpl->assign('STR_ADMIN_ARTICLE_SHORT_DESCRIPTION', $GLOBALS['STR_ADMIN_ARTICLE_SHORT_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_ARTICLES_COMPLETE_TEXT', $GLOBALS['STR_ADMIN_ARTICLES_COMPLETE_TEXT']);
	$tpl->assign('STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN', $GLOBALS['STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_META_KEYWORDS', $GLOBALS['STR_ADMIN_META_KEYWORDS']);
	$tpl->assign('STR_ADMIN_META_TITLE', $GLOBALS['STR_ADMIN_META_TITLE']);
	$tpl->assign('STR_ADMIN_META_DESCRIPTION', $GLOBALS['STR_ADMIN_META_DESCRIPTION']);
	$tpl->assign('STR_ADMIN_FILE_NAME', $GLOBALS['STR_ADMIN_FILE_NAME']);
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$tpl->assign('STR_IMAGE', $GLOBALS['STR_IMAGE']);
	$tpl->assign('STR_FILE', $GLOBALS['STR_FILE']);
	$tpl->assign('STR_ADMIN_LANGUAGES_SECTION_HEADER', $GLOBALS['STR_ADMIN_LANGUAGES_SECTION_HEADER']);
	$tpl->assign('STR_ADMIN_ARTICLES_FORM_ADD', $GLOBALS['STR_ADMIN_ARTICLES_FORM_ADD']);
	$tpl->assign('STR_ADMIN_ARTICLES_FORM_MODIFY', $GLOBALS['STR_ADMIN_ARTICLES_FORM_MODIFY']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_ARTICLES_IS_ON_ROLLOVER', $GLOBALS['STR_ADMIN_ARTICLES_IS_ON_ROLLOVER']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_ADMIN_TECHNICAL_CODE', $GLOBALS['STR_ADMIN_TECHNICAL_CODE']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_ARTICLES_IS_ON_RESELLER', $GLOBALS['STR_ADMIN_ARTICLES_IS_ON_RESELLER']);
	if (!empty($GLOBALS['STR_ADMIN_DISPLAY_FOCUS_CONTENT_HOME_PAGE'])) {
		$tpl->assign('STR_ADMIN_DISPLAY_FOCUS_CONTENT_HOME_PAGE', $GLOBALS['STR_ADMIN_DISPLAY_FOCUS_CONTENT_HOME_PAGE']);
	}
	if (!empty($GLOBALS['STR_ADMIN_DISPLAY_NEW_CONTENT_HOME_PAGE'])) {
		$tpl->assign('STR_ADMIN_DISPLAY_NEW_CONTENT_HOME_PAGE', $GLOBALS['STR_ADMIN_DISPLAY_NEW_CONTENT_HOME_PAGE']);
	}
	if (!empty($rubrique_options)) {
		$tpl->assign('pdf_logo_src',$GLOBALS['wwwroot_in_admin'] . '/images/logoPDF_small.png');
		$tpl->assign('action', get_current_url(false) . '?start=0');
		$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode'] . intval($frm['id'])));
		$tpl->assign('mode', $frm['nouveau_mode']);
		$tpl->assign('site_id_select_options', get_site_id_select_options(vb($frm['site_id'])));
		$tpl->assign('site_id_select_multiple', !empty($GLOBALS['site_parameters']['multisite_using_array_for_site_id']));
		if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
			$tpl->assign('site_country_checkboxes', get_site_country_checkboxes(vb($frm['site_country'], array())));
		}
		$tpl->assign('id', intval($frm['id']));
		if (isset($_GET['mode']) && $_GET['mode'] == "modif") {
			$tpl->assign('art_href', get_content_url($frm['id'], $frm["titre_" . $_SESSION['session_langue']], vb($frm['rubriques']['0']), vb($frm['rubriques']['1']), false, false, null, vb($frm['site_id'])));
		}
		$tpl->assign('titre', $frm['titre_' . $_SESSION['session_langue']]);
		$tpl->assign('rubrique_error', $form_error_object->text('rubriques'));
		$tpl->assign('etat', $frm['etat']);
		$tpl->assign('position', $frm['position']);
		$tpl->assign('is_rollover_module_active', check_if_module_active('menus'));
		$tpl->assign('is_on_rollover', !empty($frm['on_rollover']));
		$tpl->assign('on_special', $frm['on_special']);
		$tpl->assign('on_reseller', $frm['on_reseller']);
		if(!empty($GLOBALS['site_parameters']['article_focus_and_new_select_multiple'])) {
			$tpl->assign('article_focus_and_new_select_multiple', $GLOBALS['site_parameters']['article_focus_and_new_select_multiple']);
			$tpl->assign('new_site_id_select_options_multiple', get_site_id_select_options(vb($frm['on_new']), null, 'STR_ADMIN_ALL_SITES', false, true));
			$tpl->assign('focus_site_id_select_options_multiple', get_site_id_select_options(vb($frm['on_focus']), null, 'STR_ADMIN_ALL_SITES', false, true));
		} else {
			$tpl->assign('on_new', vb($frm['on_new']));
			$tpl->assign('on_focus', vb($frm['on_focus']));
		}

		$tpl->assign('technical_code', $frm['technical_code']);

		$tpl_langs = array();
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$tpl_langs[] = array('lng' => $lng,
				'error' => $form_error_object->text('titre_' . $lng),
				'titre' => $frm['titre_' . $lng],
				'chapo_te' => getTextEditor('chapo_' . $lng, '100%', 300, StringMb::html_entity_decode_if_needed(vb($frm['chapo_' . $lng]))),
				'texte_te' => getTextEditor('texte_' . $lng, '100%', 500, StringMb::html_entity_decode_if_needed(vb($frm['texte_' . $lng]))),
				'meta_titre' => vb($frm['meta_titre_' . $lng]),
				'meta_key' => $frm['meta_key_' . $lng],
				'meta_desc' => $frm['meta_desc_' . $lng]
				);
		}
		$tpl->assign('langs', $tpl_langs);

		$tpl_marques_options = array();
		$select = query("SELECT id, nom_" . $_SESSION['session_langue'] . ", etat
		   FROM peel_marques
		   WHERE " . get_filter_site_cond('marques', null, true) . "
		   ORDER BY position, nom_" . $_SESSION['session_langue'] . " ASC");
		while ($nom = fetch_assoc($select)) {
			$tpl_marques_options[] = array('value' => intval($nom['id']),
				'issel' => in_array($nom['id'], vb($frm['marques'], array())),
				'name' => $nom['nom_' . $_SESSION['session_langue']] . (empty($nom['etat'])?' ('.$GLOBALS["STR_ADMIN_DEACTIVATED"].')':'')
				);
		}
		$tpl->assign('marques_options', $tpl_marques_options);
		$tpl->assign('STR_ADMIN_PRODUITS_CHOOSE_BRAND', $GLOBALS['STR_ADMIN_PRODUITS_CHOOSE_BRAND']);

		$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');
		if (!empty($frm["image1"])) {
			$i = 1;
			$tpl->assign('image' . $i, get_uploaded_file_infos("image" . $i, $frm["image" . $i], get_current_url(false) . '?mode=supprfile&id=' . vb($frm['id']) . '&file=image' . $i));
		}
		$tpl->assign('normal_bouton', $frm['normal_bouton']);
	}
	echo $tpl->fetch();
}

/**
 * Supprime l'article spécifié par $id. Il faut supprimer l'article puis les entrées correspondantes de la table articles_rubriques
 *
 * @param integer $id
 * @return
 */
function supprime_article($id) 
{
	/* Charge les infos de l'article. */
	$qid = query("SELECT titre_" . $_SESSION['session_langue'] . "
		FROM peel_articles
		WHERE id = " . intval($id) . " AND " . get_filter_site_cond('articles', null, true) . "");
	$prod = fetch_assoc($qid);

	/* Efface le article */
	query("DELETE FROM peel_articles WHERE id=" . intval($id) . " AND " . get_filter_site_cond('articles', null, true) . "");
	/* Efface cet article de la table articles_rubriques */
	query("DELETE FROM peel_articles_rubriques WHERE article_id=" . intval($id));
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_ARTICLES_MSG_DELETED'], StringMb::html_entity_decode_if_needed($prod['titre_' . $_SESSION['session_langue']]))))->fetch();
}

/**
 * Ajoute un nouveau sous-article sous le parent $id.  Les champs sont dans la variable $frm
 *
 * @param array $frm Array with all fields data
 * @return
 */
function insere_article($frm)
{
	// Remplit les contenus vides
	$frm = fill_other_language_content($frm, 'articles');
	
	/* ajoute l'article dans la table articles */
	$sql = "INSERT INTO peel_articles (etat
			, image1
			, date_insere
			, date_maj
			, position
			, site_id
			, technical_code
			, on_reseller
			, on_special
			, on_rollover";
	if(!empty($GLOBALS['site_parameters']['article_on_new_enable'])) {
		$sql .= "
			, on_new";
	}
	if(!empty($GLOBALS['site_parameters']['article_on_focus_enable'])) {
		$sql .= "
			, on_focus";
	}
	if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
		$sql .= "
			, nom_insere
			, nom_maj";
	}
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= ", site_country
		";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", titre_" . $lng . "
			, chapo_" . $lng . "
			, texte_" . $lng . '
			, meta_titre_' . $lng . '
			, meta_key_' . $lng . '
			, meta_desc_' . $lng;
	}
	$sql .= "
		) VALUES ('" . intval(vb($frm['etat'])) . "'
			, '" . nohtml_real_escape_string($frm['image1']) . "'
			, '" . date('Y-m-d H:i:s', time()) . "'
			, '" . date('Y-m-d H:i:s', time()) . "'
			, '" . intval($frm['position']) . "'
			, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
			, '" . nohtml_real_escape_string($frm['technical_code']) . "'
			, '" . intval(vn($frm['on_reseller'])) . "'
			, '" . intval(vn($frm['on_special'])) . "'
			, '" . intval(vn($frm['on_rollover'])) . "'";
	if(!empty($GLOBALS['site_parameters']['article_on_new_enable'])) {
		$sql .= "
			, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['on_new'])) . "'";
	}
	if(!empty($GLOBALS['site_parameters']['article_on_focus_enable'])) {
		$sql .= "
			, '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['on_focus'])) . "'";
	}
	if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
		$sql .= "
			, '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'
			, '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'";
	}
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= ", '" . nohtml_real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'";
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= ", '" . real_escape_string($frm['titre_' . $lng]) . "'
			, '" . real_escape_string($frm['chapo_' . $lng]) . "'
			, '" . real_escape_string(StringMb::getCleanHTML($frm['texte_' . $lng], null, true, true, true, null, false)) . "'
			, '" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'
			, '" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'
			, '" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}
	$sql .= ")";

	query($sql);

	/* Récupère l'id de l'article créé */
	$article_id = insert_id();

	/* Ajoute l'article sous les catégories spécifiées */
	for ($i = 0; $i < count($frm['rubriques']); $i++) {
		$qid = query("INSERT INTO peel_articles_rubriques (rubrique_id, article_id)
			VALUES ('" . intval($frm['rubriques'][$i]) . "', '" . intval($article_id) . "')");
	}

	call_module_hook('insere_article', array('article_id'=>$article_id, 'frm'=>$frm));
	}

/**
 * Met à jour l'article $id avec de nouvelles valeurs. Les champs sont dans $frm
 *
 * @param integer $id
 * @param mixed $img1
 * @param array $frm Array with all fields data
 * @return
 */

function maj_article($id, $frm)
{
	// Remplit les contenu vides
	$frm = fill_other_language_content($frm, 'articles');
	
	/* Met à jour la table articles */
	$sql = "UPDATE peel_articles
		SET etat = '" . intval($frm['etat']) . "'
		, position = '" . intval($frm['position']) . "'
		, site_id = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['site_id'])) . "'
		, technical_code = '" . nohtml_real_escape_string($frm['technical_code']) . "'
		, image1 = '" . nohtml_real_escape_string($frm['image1']) . "'
		, date_maj = '" . date('Y-m-d H:i:s', time()) . "'";
		if(!empty($GLOBALS['site_parameters']['admin_save_name_modify_or_create_content'])) {
			$sql .= "
			, nom_maj = '" . nohtml_real_escape_string($_SESSION['session_utilisateur']['prenom'] . ' ' . $_SESSION['session_utilisateur']['nom_famille']) . "'";
		}
		if(!empty($GLOBALS['site_parameters']['article_on_focus_enable'])) {
			$sql .= "
			, on_focus = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['on_focus'])) . "'";
		}
		if(!empty($GLOBALS['site_parameters']['article_on_new_enable'])) {
			$sql .= "
			, on_new = '" . nohtml_real_escape_string(get_site_id_sql_set_value($frm['on_new'])) . "'";
		}
		$sql .= "
		, on_reseller = '" . intval(vn($frm['on_reseller'])) . "'
		, on_special = '" . intval(vn($frm['on_special'])) . "'
		, on_rollover = '" . intval(vn($frm['on_rollover'])) . "'";
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$sql .= "
		, site_country = '" . nohtml_real_escape_string(implode(',',vb($frm['site_country'], array()))) . "'";		
	}
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$sql .= "
		, titre_" . $lng . "='" . real_escape_string($frm['titre_' . $lng]) . "'
		, chapo_" . $lng . "='" . real_escape_string($frm['chapo_' . $lng]) . "'
		, texte_" . $lng . "='" . real_escape_string($frm['texte_' . $lng]) . "'
		, meta_titre_" . $lng . "='" . nohtml_real_escape_string($frm['meta_titre_' . $lng]) . "'
		, meta_key_" . $lng . "='" . nohtml_real_escape_string($frm['meta_key_' . $lng]) . "'
		, meta_desc_" . $lng . "='" . nohtml_real_escape_string($frm['meta_desc_' . $lng]) . "'";
	}

	$sql .= "
		WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('articles', null, true) . "";
	query($sql);

	/* Efface toutes les catégories auxquelles l'article est associé */
	query("DELETE FROM peel_articles_rubriques WHERE article_id = '" . intval($id) . "'");

	/* Ajoute les  associations pour toutes les catégories auxquelles cet article
	 * appartient. Si aucune catégorie n'a été sélectionnée, il appartient à la catégorie racine. */
	if (count($frm['rubriques']) == 0) {
		$frm['rubriques'][] = 0;
	}

	for ($i = 0; $i < count($frm['rubriques']); $i++) {
		$qid = query("INSERT INTO peel_articles_rubriques (rubrique_id, article_id)
			VALUES ('" . intval($frm['rubriques'][$i]) . "', '" . intval($id) . "')");
	}
	
	call_module_hook('maj_article', array('id'=>$id, 'frm'=>$frm));
	}
	
/**
 * Supprime le produit spécifié par $id. Il faut supprimer le produit puis les entrées correspondantes de la table produits_categories
 *
 * @param integer $id
 * @param mixed $file
 * @return
 */
function supprime_fichier($id, $file)
{
	/* Charge les infos du produit. */
	switch ($file) {
		case "image1":
			$sql = "SELECT image1
				FROM peel_articles
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('articles', null, true) . "";
			$res = query($sql);
			$file = fetch_assoc($res);
			query("UPDATE peel_articles
				SET image1 = ''
				WHERE id = '" . intval($id) . "' AND " . get_filter_site_cond('articles', null, true) . "");
			break;
	}
	delete_uploaded_file_and_thumbs($file['image1']);
	echo $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_FILE_DELETED'], $file['image1'])))->fetch();
}

