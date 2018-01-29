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
// $Id: sites.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
define('IN_PEEL_CONFIGURE', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_manage", true, true);

$GLOBALS['DOC_TITLE'] = $GLOBALS['STR_ADMIN_SITES_TITLE'];
$output = '';

$frm = $_POST;
$form_error_object = new FormError();

if (!empty($frm['logo']) && strpos($frm['logo'], '://') === false) {
	if (StringMb::substr($frm['logo'], 0, 1) != '/') {
		$frm['logo'] = '/' . $frm['logo'];
	}
	$frm['logo'] = $GLOBALS['wwwroot'] . $frm['logo'];
}
if (!empty($_GET['mode']) && in_array($_GET['mode'], array('insere', 'ajout', 'duplicate', 'suppr')) && $_SESSION['session_utilisateur']['site_id']>0) {
	// La création/duplication/suppression de nouveau site est réservée aux administrateurs multisite. Dans le cas de l'affichage du formulaire ou de l'insertion de données et si l'administrateur n'a pas les droits, on modifie le GET['mode'] pour afficher affiche_liste_site
	// Le lien de création de site ne s'affiche pas aux administrateurs multisite.
	$_GET['mode'] = 'default';
}

// Gestion des différentes pages
switch (vb($_GET['mode'])) {
	case "siteid_to_SET" :
		// pour le mode multisite où l'on peut spécifier une liste de site différent pour un élément. Il faut passer pour cela site_id à SET de 32 possibilités
		// on considère aussi que quand on est en mode multisite, on sera aussi en mode multizone, on change donc aussi le champ zone de peel_pays en SET
		// pour lancer la requête il faut appeler cette page avec mode=siteid_to_SET.
		// Fonction expérimentale , à n'utiliser vous maitriser le sujet.
		query("ALTER TABLE `peel_admins_actions` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_articles` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_categories` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_codes_promos` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_commandes` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_commandes_cadeaux` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_configuration` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_devises` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_html` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_langues` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_marques` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_meta` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';			
			ALTER TABLE `peel_pays` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_pays` CHANGE `zone` `zone` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '0';
			ALTER TABLE `peel_produits` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_rubriques` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_societe` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_tarifs` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_tva` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_utilisateurs` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_utilisateur_connexions` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_vignettes_carrousels` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';
			ALTER TABLE `peel_zones` CHANGE `site_id` `site_id` SET('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31') NOT NULL DEFAULT '1';");
		break;

	case "ajout" :
		// Affiche le formulaire d'ajout de site
		$output .= affiche_formulaire_ajout_site($frm);
		break;

	case "modif" :
		// Affiche le formulaire d'ajout de site si POST est vide, sinon modifie les valeurs en BDD avec la fonction create_or_update_site
		if (!empty($frm)) {
			// $_POST est défini
			$frm['favicon'] = upload('favicon', false, 'image_or_ico', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['favicon']));
			$frm['default_picture'] = upload('default_picture', false, 'image_or_ico', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['default_picture']));
			
			if (!verify_token($_SERVER['PHP_SELF'] . vb($_GET['mode']))) {
				// Contrôle du token
				$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
			}
			
			// Vérification de la présence d'information indispensable pour configurer un site
			// - email du webmaster 
			// - URL
			// - nom du site
			$form_error_object->valide_form($frm, array('email_webmaster' => $GLOBALS['STR_ADMIN_SITES_ERR_EMPTY_EMAIL'] . ' "' . $GLOBALS["STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL"] . '".',
				'nom_'.$_SESSION['session_langue'] => sprintf($GLOBALS['STR_MISSED_ATTRIBUT_MANDATORY'], $GLOBALS['STR_ADMIN_SITES_SITE_NAME']) . '.',
				'wwwroot' => sprintf($GLOBALS['STR_MISSED_ATTRIBUT_MANDATORY'], $GLOBALS['STR_ADMIN_WWWROOT']) . '.'));
			
			if (!$form_error_object->count()) {
				// => Pas d'erreur lors du contrôle du formulaire, on peut faire les modifications en BDD

				if(!empty($frm['install'])) {
					preload_modules();
					// Gestion de l'installation ou la désinstallation d'un module
					foreach($frm['install'] as $this_module => $install_or_uninstall) {
						if(class_exists(StringMb::ucfirst($this_module)) && method_exists(StringMb::ucfirst($this_module), 'check_install')) {
							// Module complet avec classe permettant de gérer proprement l'installation
							$class_name = StringMb::ucfirst($this_module);
							// La syntaxe $class_name::check_install() n'est pas valide pour PHP<5.3 => on utilise call_user_func_array
							if($install_or_uninstall != call_user_func_array(array($class_name, 'check_install'), array())) {
								if(!isset($GLOBALS[$class_name])) {
									$GLOBALS[$class_name] = new $class_name();
								}
								if($install_or_uninstall) {
									$installed = $GLOBALS[$class_name]->install();
								} else {
									$uninstalled = $GLOBALS[$class_name]->uninstall();
								}
								$output .= $GLOBALS[$class_name]->get_messages();
							}
						} else {
							// Module light, sans classe.
							// Ici on ne fait rien, l'installation est gérée dans la fonction affiche_formulaire_site
						}
					}
				}
				$frm['site_id'] = $_GET['id'];
				$output .= create_or_update_site($frm, true, $_GET['mode']);
				$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => $GLOBALS['STR_ADMIN_SITES_MSG_UPDATED_OK']))->fetch();
				$output .= affiche_liste_site();
			} else {
				// Au moins une erreur est présente dans les valeurs envoyées par le formulaire
				foreach ($form_error_object->error as $name => $text) {
					$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $text))->fetch();
				}
				$output .= affiche_formulaire_modif_site($_GET['id'], $frm);
			}
		} else {
			$output .= affiche_formulaire_modif_site($_GET['id'], null);
		}
		break;
		
	case "suppr" :
		// Suppression d'un site
		$output .= supprime_site($_GET['id']);
		$output .= affiche_liste_site();
		break;	

	case "duplicate" :
		// Duplication d'un site existant.
		if (check_if_module_active('duplicate') && isset($_GET['id'])) {
			// Il faut que l'administrateur possède les droits d'administration pour tous les sites pour faire la duplication, sinon il ne pourra pas accéder aux informations du site en cours de création
			if($_SESSION['session_utilisateur']['site_id'] == 0) {
				$_SESSION['session_admin_multisite'] = 0;
			}
			// Exécute la duplication
			duplicate_site(intval($_GET['id']));
			// Redirection vers la page de liste de site, afin d'éviter une nouvelle duplication en faisant F5
			redirect_and_die(get_current_url(false));
		} else {
			// Affiche la liste des sites configurés
			$output .= affiche_liste_site();
		}
		break;

	case "insere" :
		// Fait l'insertion d'un nouveau site (vient après mode=ajout)
		if (!verify_token($_SERVER['PHP_SELF'] . vb($_GET['mode']))) {
			// Contrôle du token
			$form_error_object->add('token', $GLOBALS['STR_INVALID_TOKEN']);
		}
		// Vérification de la présence d'information indispensable pour configurer un site
		// - email du webmaster 
		// - URL
		// - nom du site
		$form_error_object->valide_form($frm, array('email_webmaster' => $GLOBALS['STR_ADMIN_SITES_ERR_EMPTY_EMAIL'] . ' "' . $GLOBALS["STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL"] . '".',
			'nom_'.$_SESSION['session_langue'] => sprintf($GLOBALS['STR_MISSED_ATTRIBUT_MANDATORY'], '"'.$GLOBALS['STR_ADMIN_SITES_SITE_NAME'].'"') . '.',
			'wwwroot' => sprintf($GLOBALS['STR_MISSED_ATTRIBUT_MANDATORY'], '"'.$GLOBALS['STR_ADMIN_WWWROOT'].'"'). '.'));
		if (!$form_error_object->count()) {
			// Récupération du l'id de site la plus élevée pour attribuer un nouvel id au nouveau site.
			$query = query('SELECT MAX( site_id ) as site_id
				FROM peel_configuration');
			$new_site_id = fetch_assoc($query);
			$frm['site_id'] = $new_site_id['site_id']+1;
			$frm['favicon'] = upload('favicon', false, 'image_or_ico', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['favicon']));
			$frm['default_picture'] = upload('default_picture', false, 'image_or_ico', $GLOBALS['site_parameters']['image_max_width'], $GLOBALS['site_parameters']['image_max_height'], null, null, vb($frm['default_picture']));

			// => Pas d'erreur lors du contrôle du formulaire, on peut faire les modifications en BDD
			// Il faut que l'administrateur possède les droits d'administration pour tous les sites pour faire la duplication, sinon il ne pourra pas accéder aux informations du site en cours de création
			if($_SESSION['session_utilisateur']['site_id'] == 0) {
				$_SESSION['session_admin_multisite'] = 0;
			}
			$output .= create_or_update_site($frm, false, $_GET['mode'], $GLOBALS['lang_codes']);
			$output .= $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_SITES_MSG_INSERTED_OK'], $frm['nom_' . $_SESSION['session_langue']])))->fetch();
			$output .= affiche_liste_site();
		} else {
			// Au moins une erreur est présente dans les valeurs envoyées par le formulaire
			foreach ($form_error_object->error as $name => $text) {
				$output .= $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => $text))->fetch();
			}
			$output .= affiche_formulaire_ajout_site($frm);
		}
		break;

	case "supprfavicon" :
		// Suppression du favicon depuis le formulaire de modification de site (mode=modif)
		supprime_favicon(vn($_GET['id']), $_GET['favicon']);
		$output .= affiche_formulaire_modif_site($_GET['id'], $frm);
		break;

	case "supprdefault_picture" :
		// Suppression de l'image par défaut du produit depuis le formulaire de modification de site (mode=modif)
		supprime_default_picture(vn($_GET['id']), $_GET['default_picture']);
		$output .= affiche_formulaire_modif_site($_GET['id'], $frm);
		break;

	default :
		// Affichage de la liste des sites (si il y a plus d'un site configuré)
		$output .= affiche_liste_site();
		break;
}
include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
echo $output;
include($GLOBALS['repertoire_modele'] . "/admin_bas.php");

/**
 * FONCTIONS
 */

/**
 * Retourne le formulaire d'ajout de site en paramétrant la fonction affiche_formulaire_site
 *
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_ajout_site(&$frm)
{
	// Default values
	$urlsite = 'http://' . $_SERVER['HTTP_HOST'];
	$urlscript = dirname($_SERVER['PHP_SELF']);
	$url = ($urlscript == '/') ? trim($urlsite) : trim($urlsite . $urlscript);

	if(empty($frm)) {
		$frm['default_country_id'] = vn($GLOBALS['site_parameters']['default_country_id']);
		foreach($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['nom_' . $lng] = "";
			$frm['logo_' . $lng] = "";
		}
		$frm['pays_exoneration_tva'] = "";
		$frm['css'] = "";
		$frm['template_directory'] = "";
		$frm['template_multipage'] = "";
		$frm['url'] = str_replace(array("/administrer", '/' . $GLOBALS['site_parameters']['backoffice_directory_name']), "", $url);
		$frm['on_logo'] = 1;
		$frm['favicon'] = "";
		$frm['timemax'] = 1800;
		$frm['seuil'] = 5;
		$frm['seuil_total'] = 100;
		$frm['seuil_total_reve'] = 100;
		$frm['module_retail'] = 1;
		$frm['module_affilie'] = 1;
		$frm['commission_affilie'] = 5;
		$frm['module_lot'] = 1;
		$frm['module_parrain'] = 1;
		$frm['module_cadeau'] = 1;
		$frm['module_devise'] = 1;
		// Il ne faut pas définir la devise par défaut en dur, puisque l'id de la devise dépend du site.
		$frm['devise_defaut'] = '';
		$frm['module_nuage'] = 1;
		$frm['module_flash'] = 1;
		$frm['module_captcha'] = 1;
		$frm['module_cart_preservation'] = 1;
		$frm['module_pub'] = 1;
		$frm['module_faq'] = 1;
		$frm['module_vacances'] = 1;
		$frm['module_vacances_type'] = 0;
		$frm['facebook_connect'] = 0;
		$frm['fb_appid'] = "";
		$frm['fb_secret'] = "";
		$frm['fb_baseurl'] = "";
		foreach ($GLOBALS['admin_lang_codes'] as $lng) {
			$frm['module_vacances_client_msg_' . $lng] = "";
		}
		$frm['module_precedent_suivant'] = 1;
		$frm['in_category'] = 1;
		$frm['module_forum'] = 1;
		$frm['module_conditionnement'] = 1;
		$frm['module_giftlist'] = 1;
		$frm['module_rss'] = 1;
		$frm['module_ecotaxe'] = 1;
		$frm['module_url_rewriting'] = 1;
		$frm['module_entreprise'] = 0;
		$frm['display_prices_with_taxes'] = 1;
		$frm['display_prices_with_taxes_in_admin'] = 1;
		$frm['html_editor'] = 0;
		$frm['avoir'] = 10;
		$frm['email_paypal'] = "";
		$frm['email_commande'] = "";
		$frm['email_webmaster'] = "";
		$frm['nom_expediteur'] = "";
		$frm['email_client'] = "";
		$frm['sips'] = "";
		$frm['spplus'] = "";
		$frm['systempay_payment_count'] = "";
		$frm['systempay_payment_period'] = "";
		$frm['systempay_cle_test'] = "";
		$frm['systempay_cle_prod'] = "";
		$frm['systempay_test_mode'] = "";
		$frm['systempay_code_societe'] = "";
		$frm['paybox_cgi'] = "";
		$frm['paybox_site'] = "";
		$frm['paybox_rang'] = "";
		$frm['paybox_identifiant'] = "";
		$frm['email_moneybookers'] = "";
		$frm['secret_word'] = "";
		$frm['module_rollover'] = 1;
		$frm['type_rollover'] = 1;
		$frm['logo_affiliation'] = "";
		$frm['small_order_overcost_limit'] = "";
		$frm['small_order_overcost_amount'] = "";
		$frm['small_order_overcost_tva_percent'] = "";
		$frm['minimal_amount_to_order'] = "";
		$frm['minimal_amount_to_order_reve'] = "";
		$frm['mode_transport'] = 1;
		$frm['format_numero_facture'] = "[id]";
		$frm['module_socolissimo'] = 1;
		$frm['module_icirelais'] = 1;
		$frm['module_autosend'] = 0;
		$frm['module_autosend_delay'] = 5;
		$frm['fb_admins'] = '';
		$frm['facebook_page_link'] = '';
		$frm['socolissimo_foid'] = "";
		$frm['socolissimo_sha1_key'] = "";
		$frm['socolissimo_urlok'] = "";
		$frm['socolissimo_urlko'] = "";
		$frm['socolissimo_preparationtime'] = "";
		$frm['socolissimo_forwardingcharges'] = "";
		$frm['socolissimo_firstorder'] = "";
		$frm['socolissimo_pointrelais'] = "";
		$frm['socolissimo_dyForwardingChargesCMT'] = "";
		$frm['tag_analytics'] = "";
		$frm['availability_of_carrier'] = 0;
		$frm['allow_add_product_with_no_stock_in_cart'] = "0";
		$frm['zoom'] = "jqzoom";
		$frm['enable_prototype'] = "";
		$frm['enable_jquery'] = 1;
		$frm['send_email_active'] = 1;
		$frm['display_errors_for_ips'] = "";
		$frm['display_nb_product'] = "0";
		$frm['module_tnt'] = 0;
		$frm['module_filtre'] = 1;
		$frm['tnt_username'] = 0;
		$frm['tnt_password'] = 0;
		$frm['tnt_account_number'] = 0;
		$frm['tnt_treshold'] = 0;
		$frm['expedition_delay'] = 0;
		$frm['export_encoding'] = 0;
		$frm['category_count_method'] = 0;
		$frm['sessions_duration'] = 180;
		$frm['nb_produit_page'] = 10;
		$frm['small_width'] = 160;
		$frm['small_height'] = 160;
		$frm['medium_width'] = 300;
		$frm['medium_height'] = 300;
		$frm['anim_prod'] = 1;
		// attribut pour l'image par défaut
		$frm['default_picture'] = "";
		// On prend les valeurs par défaut : site_id = 0 en base de données 
		// get_filter_site_cond('configuration') => Ne pas utiliser le paramètre $use_admin_rights, les configurations à récupérer sont uniquement celles communes à tous les sites (id=0). Les droits de l'administrateur qui fait la demande ne sont pas à prendre en compte.
		$sql = 'SELECT *
			FROM peel_configuration
			WHERE ' . get_filter_site_cond('configuration', null, false, 0, true) . '
			ORDER BY site_id ASC';
		$q = query($sql);
		while($result = fetch_assoc($q)) {
			if(!in_array($result['technical_code'], array('wwwroot'))) {
				$frm[$result['technical_code']] = $result['string'];
			}
		}
	}
	$frm['nouveau_mode'] = "insere";
	$frm['id'] = "";
	$frm['titre_bouton'] = $GLOBALS['STR_ADMIN_ADD'];
	$frm_modules = get_modules_array(false, null, null, true);
	return affiche_formulaire_site($frm, $frm_modules);
}

/**
 * Retourne le formulaire de modification pour le site sélectionné en paramétrant la fonction affiche_formulaire_site
 *
 * @param integer $id
 * @param array $frm Array with all fields data
 * @return
 */
function affiche_formulaire_modif_site($id, $frm)
{
	if (empty($frm)) {
		// On charge le tableau de configuration commune aux sites, puis spécifique au site en cours de modification
		// Si les tableaux d'entrées ont des clés en commun, alors, la valeur finale pour cette clé écrasera la précédente. 
		// get_filter_site_cond('configuration') => Ne pas utiliser le paramètre $use_admin_rights, les configurations à récupérer sont celles de $id + celles publiques avec id=0, même si l'administrateur ne pourra pas modifier celles-ci.
		// Les droits de l'administrateur qui fait la demande ne sont pas à prendre en compte ici, mais plus tard à l'insert/update
		$frm = array();
		$sql = 'SELECT *
			FROM peel_configuration
			WHERE ' . get_filter_site_cond('configuration', null, false, $id, false) . '
			ORDER BY site_id ASC';
		$q = query($sql);
		while($result = fetch_assoc($q)) {
			$frm[$result['technical_code']] = $result['string'];
		}
	}
	if (!empty($frm)) {
		// Les modules ne sont pas concerné par le multi-site
		$frm_modules = get_modules_array(false, null, null, true, $id, false);
		$frm['id'] = $id;
		$frm["nouveau_mode"] = "modif";
		$frm["titre_bouton"] = $GLOBALS['STR_ADMIN_FORM_SAVE_CHANGES'];
		return affiche_formulaire_site($frm, $frm_modules);
	} elseif($_SESSION['session_utilisateur']['site_id'] == 0) {
		// Si pas de site avec l'id demandé et que l'administrateur est multisite => on redirige vers le formulaire de création de site.
		redirect_and_die(get_current_url(false).'?mode=ajout');
	} else {
		// Redirection vers la liste des sites administrables.
		redirect_and_die(get_current_url(false));
	}
}

/**
 * Retourne le HTML du formulaire de modification/création de site
 *
 * @param array $frm Array with all fields data
 * @param mixed $frm_modules
 * @return
 */
function affiche_formulaire_site(&$frm, $frm_modules)
{
	$output = '';
	// Correction gestion variables de configuration booléennes
	foreach(array('site_suspended', 'systempay_test_mode') as $this_field) {
		if(isset($frm[$this_field])) {
			if($frm[$this_field] === 'false') {
				$frm[$this_field] = false;
			} elseif($frm[$this_field] === 'true') {
				$frm[$this_field] = true;
			}
		}
	}
	$tpl = $GLOBALS['tplEngine']->createTemplate('admin_formulaire_site.tpl');

	$tpl_modules_infos = array();
	// Préparation de la liste des modules installés
	preload_modules();
	$i=0;
	foreach($GLOBALS['modules_on_disk'] as $this_module => $folder_path) {
		$file_path = vb($GLOBALS['modules_on_disk_infos'][$this_module]['file_path'], null);
		if(class_exists(StringMb::ucfirst($this_module)) && method_exists(StringMb::ucfirst($this_module), 'check_install')) {
			// Une classe est détectée, c'est un module complet avec une méthode d'installation
			$class_name = StringMb::ucfirst($this_module);
			if(!isset($GLOBALS[$class_name])) {
				$GLOBALS[$class_name] = new $class_name();
			}
			$type = 'full';
			// La syntaxe $class_name::check_install() n'est pas valide pour PHP<5.3 => on utilise call_user_func_array
			$GLOBALS['modules_on_disk_infos'][$this_module]['installed'] = call_user_func_array(array($class_name, 'check_install'), array());
			$version = $GLOBALS[$class_name]->version;
			$name = $GLOBALS[$class_name]->name;
		} else {
			// Module léger sans classe - sa présence sur le disque suffit à considérer qu'il est installé
			$type = 'light';
			$name = vb($GLOBALS['modules_light_default_names'][$this_module], StringMb::ucfirst($this_module));
			if(empty($GLOBALS['modules_on_disk_infos'][$this_module]['installed'])) {
				if(!empty($GLOBALS['modules_on_disk_infos'][$this_module]['to_install'])) {
					foreach(array('peel_' . $this_module . '.sql', '' . $this_module . '.sql') as $this_filename) {
						if (file_exists($folder_path . '/' . $this_filename)) {
							// Exécution du SQL d'installation d'un module qui n'a pas de méthode d'installation
							$error_msg = execute_sql($folder_path . '/' . $this_filename, null, true);
							$output .=  $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => 'SQL OK '. $GLOBALS['STR_BEFORE_TWO_POINTS'].': ' . $GLOBALS['dirroot'] . '/modules/' . $this_module . '/' . $this_filename))->fetch();
							if(!empty($error_msg)) {
								$output .=  $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message_to_escape' => $error_msg))->fetch();
							}
						}
					}
				}
				// Intallation Express automatique, exécutée une seule fois :
				if(!empty($GLOBALS['modules_on_disk_infos'][$this_module]['to_install']) && !in_array(str_replace($GLOBALS['dirroot'], '', $GLOBALS['modules_on_disk_infos'][$this_module]['to_install']), vb($GLOBALS['site_parameters']['load_site_specific_files_before_others'], array())) && !in_array(str_replace($GLOBALS['dirroot'], '', $GLOBALS['modules_on_disk_infos'][$this_module]['to_install']), vb($GLOBALS['site_parameters']['load_site_specific_files_after_others'],array()))) {
					// on ajoute la configuration du fichier de fonctions à modules_front_office_functions_files_array pour que le module puisse être chargé (et pas forcément activé)
					$GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module] = str_replace($GLOBALS['dirroot'], '', $GLOBALS['modules_on_disk_infos'][$this_module]['to_install']);
					set_configuration_variable(array('technical_code' => 'modules_front_office_functions_files_array', 'string' => $GLOBALS['site_parameters']['modules_front_office_functions_files_array'], 'type' => 'array', 'origin' => 'sites.php', 'site_id' => 0), true);
				}
				// On cherche si il y a un fichier de fonctions d'administration à installer
				foreach(array('administrer/fonctions.php', 'admin/fonctions.php', 'administrer/functions.php', 'admin/functions.php') as $this_filename) {
					if(file_exists($folder_path . '/' . $this_filename)) {
						// Fichier d'administration, de classe ou de fonctions du module
						$file_path = $folder_path . '/' . $this_filename;
						if(StringMb::strpos($file_path, '.php') !== false && !in_array(vb($GLOBALS['site_parameters']['modules_admin_functions_array'][$this_module]), vb($GLOBALS['modules_loaded_functions'], array())) && (empty($GLOBALS['site_parameters']['modules_no_library_load_array']) || !in_array($this_module, $GLOBALS['site_parameters']['modules_no_library_load_array']))) {
							include($file_path);
							if(!in_array(str_replace($GLOBALS['dirroot'], '', $file_path), $GLOBALS['site_parameters']['modules_admin_functions_array'])) {
								$GLOBALS['site_parameters']['modules_admin_functions_array'][$this_module] = str_replace($GLOBALS['dirroot'], '', $file_path);
								set_configuration_variable(array('technical_code' => 'modules_admin_functions_array', 'string' => $GLOBALS['site_parameters']['modules_admin_functions_array'], 'type' => 'array', 'origin' => 'sites.php', 'site_id' => 0), true);
							}
						}
						break;
					}
				}
			}
			// Détection de la version
			$version = '';
			// On cherche dans le fichier de référence le numéro de version dans une ligne de commentaire d'entête du type
			// PEEL Modules X.X.X
			if($fp = StringMb::fopen_utf8($file_path, "r")) {
				while ($line = fgets($fp, 1024)) { 
					if(StringMb::substr($line, 0, 2) == '//' && preg_match("/PEEL (Shopping|Modules) ([0-9.]*)/i", $line, $matches)) { 
						$version = $matches[2];
						break;
					}
				} 
				fclose($fp); 
			}
		}
		if(!empty($GLOBALS['modules_installed'][$this_module]) || (empty($GLOBALS['site_parameters']['modules_front_office_functions_files_array'][$this_module]) && empty($GLOBALS['site_parameters']['modules_admin_functions_array'][$this_module]) && check_if_module_active($this_module))) {
			$enabled = true;
		} else {
			$enabled = false;
		}
		$tpl_modules_infos[$name.$i] = array('tr_rollover' => tr_rollover($i, true),
			'type' => $type,
			'installed' => !empty($GLOBALS['modules_on_disk_infos'][$this_module]['installed']),
			'enabled' => $enabled,
			'name' => $name,
			'technical_code' => $this_module,
			'version' => $version,
			'package' => (in_array($this_module, $GLOBALS['premium_modules_array'])?'Premium':''),
			'configuration_variable' =>  vb($GLOBALS['site_parameters']['modules_configuration_variable_array'][$this_module], 'module_' . $this_module)
			);
		$i++;
	}
	ksort($tpl_modules_infos);
	$other_modules_array = array_unique(array_merge(array_keys($GLOBALS['site_parameters']['modules_front_office_functions_files_array']), $GLOBALS['site_parameters']['modules_no_library_load_array']));
	sort($other_modules_array);
	foreach($other_modules_array as $this_module) {
		if(empty($GLOBALS['modules_on_disk'][$this_module])) {
			$tpl_modules_infos[] = array('tr_rollover' => tr_rollover($i, true),
				'type' => 'none',
				'installed' => false,
				'enabled' => false,
				'name' => vb($GLOBALS['modules_light_default_names'][$this_module], StringMb::ucfirst($this_module)),
				'technical_code' => $this_module,
				'version' => null,
				'contact' => true,
				'package' => (in_array($this_module, $GLOBALS['premium_modules_array'])?' (inclus dans le module Premium)':'')
				);
			$i++;
		}
	}	
	
	$tpl->assign('modules_infos', $tpl_modules_infos);
	
	// Récupération des informations sur la configuration des zones pour l'affichage dans le formulaire "Pour information, votre configuration actuelle des zones franco de port est :" 
	$tpl_zones = array();
	$qid = query("SELECT *
		FROM peel_zones
		WHERE on_franco=1 AND " . get_filter_site_cond('zones') . "");
	while ($result = fetch_assoc($qid)) {
		$tpl_zones[] = array('href' => $GLOBALS['administrer_url'] . '/zones.php?mode=modif&id=' . $result['id'],
			'nom' => $result['nom_' . $_SESSION['session_langue']]
			);
		$zones_franco_port[] = '<a href="' . $GLOBALS['administrer_url'] . '/zones.php?mode=modif&id=' . $result['id'] . '">' . $result['nom_' . $_SESSION['session_langue']] . '</a>';
	}
	$tpl->assign('zones', $tpl_zones);
	$tpl->assign('zones_href', $GLOBALS['administrer_url'] . '/zones.php');

	$tpl->assign('action', get_current_url(false) . '?mode=' . StringMb::str_form_value($frm["nouveau_mode"]) . (!empty($frm["id"]) ?'&id=' . intval($frm['id']):''));
	$tpl->assign('form_token', get_form_token_input($_SERVER['PHP_SELF'] . $frm['nouveau_mode']));
	$tpl->assign('site_suspended', vb($frm['site_suspended']));

	if(empty($_SESSION['session_admin_multisite']) || $_SESSION['session_admin_multisite'] != $GLOBALS['site_id']) {
		$this_wwwroot =  get_site_wwwroot($_SESSION['session_admin_multisite'], $_SESSION['session_langue']);
	} else {
		$this_wwwroot =  $GLOBALS['wwwroot'];
	}
	$tpl->assign('membre_admin_href', $this_wwwroot . '/membre.php');

	// Contenu multilingue
	$tpl_langs = array();
	foreach ($GLOBALS['admin_lang_codes'] as $lng) {
		$tpl_langs[] = array('lng' => $lng,
			'nom' => vb($frm['nom_' . $lng]),
			'logo' => vb($frm['logo_' . $lng]),
			'module_vacances_value' => (!empty($frm['module_vacances_client_msg_' . $lng]) ? StringMb::html_entity_decode_if_needed(vb($frm['module_vacances_client_msg_' . $lng])) : ""),
			);
	}
	$tpl->assign('langs', $tpl_langs);
	
	$tpl->assign('nouveau_mode', vb($frm["nouveau_mode"]));
	$tpl->assign('wwwroot', vb($frm['wwwroot']));
	$tpl->assign('session_langue', vb($_SESSION['session_langue']));

	$tpl->assign('country_select_options', get_country_select_options(null, vb($frm['default_country_id']), 'id'));

	// Séléction des répertoires présents dans le répertoire modeles du site. Permet de générer un select permettant à l'admin de choisir le template associé au site.
	if ($handle = opendir($GLOBALS['dirroot'] . "/modeles")) {
		$tpl_directory_options = array();
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != ".." && $file != ".svn") {
				$tpl_directory_options[] = array('value' => $file,
					'issel' => $file == vb($frm['template_directory']),
					);
			}
		}
		$tpl->assign('directory_options', $tpl_directory_options);
		closedir($handle);
	}

	$tpl->assign('template_multipage', vb($frm['template_multipage']));
	$tpl->assign('css', vb($frm['css']));
	$tpl->assign('on_logo', vb($frm['on_logo']));
	$tpl->assign('drop_src', $GLOBALS['administrer_url'] . '/images/b_drop.png');

	if (!empty($frm["favicon"])) {
		$tpl->assign('favicon', array('src' => get_url_from_uploaded_filename($frm["favicon"]),
				'favicon' => vb($frm['favicon']),
				'sup_href' => get_current_url(false) . '?mode=supprfavicon&id=' . vb($frm['id']) . '&favicon=' . StringMb::str_form_value(vb($frm["favicon"]))
				));
	}

	$tpl->assign('zoom', vb($frm['zoom']));
	$tpl->assign('enable_prototype', !empty($frm['enable_prototype']));
	$tpl->assign('enable_jquery', !empty($frm['enable_jquery']));
	$tpl->assign('export_encoding', vb($frm['export_encoding']));
	$tpl->assign('module_autosend', vb($frm['module_autosend']));
	$tpl->assign('module_autosend_delay', vb($frm['module_autosend_delay']));
	$tpl->assign('category_count_method', vb($frm['category_count_method']));
	$tpl->assign('popup_width', vb($frm['popup_width']));
	$tpl->assign('popup_height', vb($frm['popup_height']));
	$tpl->assign('admin_force_ssl', vb($frm['admin_force_ssl']));
	$tpl->assign('membre_href', str_replace('http://', 'https://', $this_wwwroot . '/membre.php'));

	$tpl->assign('display_nb_product', vb($frm['display_nb_product']));
	$tpl->assign('small_width', vb($frm['small_width']));
	$tpl->assign('small_height', vb($frm['small_height']));
	$tpl->assign('medium_width', vb($frm['medium_width']));
	$tpl->assign('medium_height', vb($frm['medium_height']));
	$tpl->assign('module_filtre', vb($frm['module_filtre']));
	$tpl->assign('category_order_on_catalog', vb($frm['category_order_on_catalog']));
	$tpl->assign('type_affichage_attribut', vb($frm['type_affichage_attribut']));
	$tpl->assign('anim_prod', vb($frm['anim_prod']));

	$tpl->assign('sessions_duration', vb($frm['sessions_duration']));
	$tpl->assign('nb_produit_page', vb($frm['nb_produit_page']));

	$tpl->assign('is_best_seller_module_active', check_if_module_active('best_seller'));
	$tpl->assign('promotions_href', $GLOBALS['wwwroot_in_admin'] . '/achat/promotions.php');
	$tpl->assign('is_stock_advanced_module_active', check_if_module_active('stock_advanced'));

	$tpl->assign('site_symbole', $GLOBALS['site_parameters']['symbole']);

	$tpl->assign('default_picture_delete_url', get_current_url(false) . '?mode=supprdefault_picture&id=' . vb($frm['id']) . '&default_picture=' . vb($frm["default_picture"]));
	$tpl->assign('default_picture_delete_icon_url', $GLOBALS['administrer_url'] . '/images/b_drop.png');
	$tpl->assign('default_picture', vb($frm["default_picture"]));
	$tpl->assign('default_picture_url',  get_url_from_uploaded_filename(vb($frm["default_picture"])));

	$tpl->assign('devises_href', $GLOBALS['wwwroot_in_admin'] . '/modules/devises/administrer/devises.php');
	$tpl_devices_options = array();
	if (file_exists($GLOBALS['fonctionsdevises'])) {
		$req = "SELECT *
			FROM peel_devises
			WHERE etat = '1' AND " . get_filter_site_cond('devises') . " AND site_id = " . intval(vn($frm['id']));
		$res = query($req);
		while ($tab_devise = fetch_assoc($res)) {
			$tpl_devices_options[] = array('value' => intval($tab_devise['id']),
				'issel' => vb($frm['devise_defaut']) == $tab_devise['id'],
				'name' => $tab_devise['devise']
				);
		}
	}
	$tpl->assign('devices_options', $tpl_devices_options);
	
	$tpl_modules = array();
	$i = 0;
	$emplacement_array['above_middle'] = $GLOBALS['STR_ADMIN_SITES_ABOVE_MIDDLE'];
	$emplacement_array['below_middle'] = $GLOBALS['STR_ADMIN_SITES_BELOW_MIDDLE'];
	$emplacement_array['footer'] = $GLOBALS['STR_ADMIN_SITES_BOTTOM'];
	$emplacement_array['header'] = $GLOBALS['STR_ADMIN_SITES_TOP'];
	$emplacement_array['top_middle'] = $GLOBALS['STR_ADMIN_SITES_CENTER_TOP'];
	$emplacement_array['center_middle'] = $GLOBALS['STR_ADMIN_SITES_CENTER_MIDDLE'];
	$emplacement_array['center_middle_home'] = $GLOBALS['STR_ADMIN_SITES_CENTER_MIDDLE_HOME'];
	$emplacement_array['bottom_middle'] = $GLOBALS['STR_ADMIN_SITES_CENTER_BOTTOM'];
	if (check_if_module_active('banner') && check_if_module_active('vitrine')) {
		$emplacement_array['top_vitrine'] = $GLOBALS['STR_ADMIN_SITES_USER_SHOPS_TOP'];
		$emplacement_array['bottom_vitrine'] = $GLOBALS['STR_ADMIN_SITES_USER_SHOPS_BOTTOM'];
	}
	if (check_if_module_active('annonces')) {
		$emplacement_array['top_annonce'] = $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_TOP'];
		$emplacement_array['sponso_cat'] = $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_SPONSOR'];
		$emplacement_array['ad_detail_bottom'] = $GLOBALS['STR_ADMIN_SITES_POSITION_AD_BOTTOM'];
		$emplacement_array['ad_detail_top'] = $GLOBALS['STR_ADMIN_SITES_POSITION_AD_TOP'];
		$emplacement_array['middle_annonce'] = $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_MIDDLE'];
		$emplacement_array['bottom_annonce'] = $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_BOTTOM'];
		$emplacement_array['left_annonce'] = $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_LEFT'];
		$emplacement_array['right_annonce'] = $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_RIGHT'];
	}
	if (check_if_module_active('iphone-ads')) {
		$emplacement_array['iphone_ads_splashscreen'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_HOME'];
		$emplacement_array['iphone_ads_bottom_annonce'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ADS_BOTTOM'];
		$emplacement_array['iphone_ads_top_annonce'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ADS_TOP'];
		$emplacement_array['iphone_ads_ad_detail_top'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_TOP'];
		$emplacement_array['iphone_ads_ad_detail_bottom'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_BOTTOM'];
		$emplacement_array['iphone_ads_favoris_bottom'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_BOTTOM'];
		$emplacement_array['iphone_ads_favoris_top'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_TOP'];
		$emplacement_array['iphone_ads_account_bottom'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_BOTTOM'];
		$emplacement_array['iphone_ads_account_top'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_TOP'];
		$emplacement_array['iphone_ads_create_account_top'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_TOP'];
		$emplacement_array['iphone_ads_create_account_bottom'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_BOTTOM'];
		$emplacement_array['iphone_ads_publish_top'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_TOP'];
		$emplacement_array['iphone_ads_publish_bottom'] = $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_BOTTOM'];
	}
	asort($emplacement_array);
	foreach ($frm_modules as $this_module_infos) {
		if(empty($emplacement_array[vb($this_module_infos['location'])])){
			$emplacement_array[vb($this_module_infos['location'])] = str_replace('_', ' ', ucfirst(vb($this_module_infos['location'])));
		}
		$tpl_modules[] = array('tr_rollover' => tr_rollover($i, true),
			'title' => $this_module_infos['title_' . $_SESSION['session_langue']],
			'id' => $this_module_infos['id'],
			'display_mode' => $this_module_infos['display_mode'],
			'location' => $this_module_infos['location'],
			'etat' => $this_module_infos['etat'],
			'in_home' => $this_module_infos['in_home'],
			'position' => $this_module_infos['position'],
			'is_above_middle_off' => in_array($this_module_infos['technical_code'], array('menu')),
			'is_below_middle_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane')),
			'is_footer_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'caddie', 'account', 'last_views', 'paiement_secu')),
			'is_header_off' => in_array($this_module_infos['technical_code'], array('ariane', 'advertising', 'advertising1', 'advertising2', 'advertising3', 'advertising4', 'advertising5', 'catalogue', 'last_views', 'paiement_secu', 'news', 'articles_rollover')),
			'is_top_middle_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane')),
			'is_center_middle_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'catalogue', 'caddie', 'tagcloud', 'account', 'last_views', 'paiement_secu', 'search', 'best_seller', 'news', 'advertising', 'advertising1', 'advertising2', 'advertising3', 'advertising4', 'advertising5', 'brand', 'guide', 'articles_rollover')),
			'is_center_middle_home_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'catalogue', 'caddie', 'tagcloud', 'account', 'last_views', 'paiement_secu', 'search', 'best_seller', 'news', 'advertising', 'advertising1', 'advertising2', 'advertising3', 'advertising4', 'advertising5', 'brand', 'guide', 'articles_rollover')),
			'is_bottom_middle_off' => in_array($this_module_infos['technical_code'], array('menu', 'guide')),
			'is_top_vitrine_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			'is_bottom_vitrine_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			'is_annonce_place_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			'is_iphone_place_off' => in_array($this_module_infos['technical_code'], array('menu', 'ariane', 'guide')),
			);
		$i++;
	}
	
	$tpl->assign('emplacement_array', $emplacement_array);
	$tpl->assign('modules', $tpl_modules);

	$tpl->assign('is_flash_sell_module_active', file_exists($GLOBALS['dirroot'] . "/modules/flash/flash.php"));
	$tpl->assign('is_rewritefile', file_exists($GLOBALS['rewritefile']));

	$tpl->assign('nb_on_top', vb($frm['nb_on_top']));
	$tpl->assign('nb_last_views', vb($frm['nb_last_views']));
	$tpl->assign('global_remise_percent', vb($frm['global_remise_percent']));
	$tpl->assign('pays_exoneration_tva', vb($frm['pays_exoneration_tva']));
	$tpl->assign('timemax', vb($frm['timemax']));
	$tpl->assign('seuil', vb($frm['seuil']));
	$tpl->assign('quotation_delay', vb($frm['quotation_delay']));
	$tpl->assign('email_webmaster', vb($frm['email_webmaster']));
	$tpl->assign('nom_expediteur', vb($frm['nom_expediteur']));
	$tpl->assign('email_commande', vb($frm['email_commande']));
	$tpl->assign('email_client', vb($frm['email_client']));
	$tpl->assign('email_paypal', vb($frm['email_paypal']));
	$tpl->assign('email_moneybookers', vb($frm['email_moneybookers']));
	$tpl->assign('secret_word', vb($frm['secret_word']));
	$tpl->assign('availability_of_carrier', vb($frm['availability_of_carrier']));
	$tpl->assign('tag_analytics', vb($frm['tag_analytics']));
	$tpl->assign('googlefriendconnect', vb($frm['googlefriendconnect']));
	$tpl->assign('googlefriendconnect_site_id', vb($frm['googlefriendconnect_site_id']));
	$tpl->assign('sign_in_twitter', vb($frm['sign_in_twitter']));
	$tpl->assign('twitter_consumer_key', vb($frm['twitter_consumer_key']));
	$tpl->assign('twitter_consumer_secret', vb($frm['twitter_consumer_secret']));
	$tpl->assign('commission_affilie', vb($frm['commission_affilie']));
	$tpl->assign('logo_affiliation', vb($frm['logo_affiliation']));
	$tpl->assign('avoir', vb($frm['avoir']));
	$tpl->assign('module_url_rewriting', vn($frm['module_url_rewriting']));
	$tpl->assign('sips', vb($frm['sips']));
	$tpl->assign('spplus', vb($frm['spplus']));
	$tpl->assign('systempay_payment_count', vb($frm['systempay_payment_count']));
	$tpl->assign('systempay_payment_period', vb($frm['systempay_payment_period']));
	$tpl->assign('systempay_cle_test', vb($frm['systempay_cle_test']));
	$tpl->assign('systempay_cle_prod', vb($frm['systempay_cle_prod']));
	$tpl->assign('systempay_test_mode', vb($frm['systempay_test_mode']));
	$tpl->assign('systempay_code_societe', vb($frm['systempay_code_societe']));
	$tpl->assign('paybox_cgi', vb($frm['paybox_cgi']));
	$tpl->assign('paybox_site', vb($frm['paybox_site']));
	$tpl->assign('paybox_rang', vb($frm['paybox_rang']));
	$tpl->assign('paybox_identifiant', vb($frm['paybox_identifiant']));
	$tpl->assign('fb_admins', vb($frm['fb_admins']));
	$tpl->assign('facebook_page_link', vb($frm['facebook_page_link']));
	$tpl->assign('facebook_connect', vb($frm['facebook_connect']));
	$tpl->assign('fb_appid', vb($frm['fb_appid']));
	$tpl->assign('fb_secret', vb($frm['fb_secret']));
	$tpl->assign('fb_baseurl', vb($frm['fb_baseurl']));
	$tpl->assign('display_errors_for_ips', vb($frm['display_errors_for_ips']));
	$tpl->assign('titre_bouton', vb($frm['titre_bouton']));

	$tpl->assign('allow_add_product_with_no_stock_in_cart', vb($frm['allow_add_product_with_no_stock_in_cart']));
	$tpl->assign('format_numero_facture', vb($frm['format_numero_facture']));
	$tpl->assign('small_order_overcost_limit', vb($frm['small_order_overcost_limit']));
	$tpl->assign('small_order_overcost_amount', vb($frm['small_order_overcost_amount']));
	$tpl->assign('small_order_overcost_tva_percent', vb($frm['small_order_overcost_tva_percent']));
	$tpl->assign('minimal_amount_to_order', vb($frm['minimal_amount_to_order']));
	$tpl->assign('minimal_amount_to_order_reve', vb($frm['minimal_amount_to_order_reve']));
	$tpl->assign('seuil_total', vb($frm['seuil_total']));
	$tpl->assign('seuil_total_reve', vb($frm['seuil_total_reve']));
	$tpl->assign('nb_product', vb($frm['nb_product']));
	$tpl->assign('socolissimo_foid', vb($frm['socolissimo_foid']));
	$tpl->assign('socolissimo_sha1_key', vb($frm['socolissimo_sha1_key']));
	$tpl->assign('socolissimo_urlko', vb($frm['socolissimo_urlko']));
	$tpl->assign('socolissimo_preparationtime', vb($frm['socolissimo_preparationtime']));
	$tpl->assign('socolissimo_forwardingcharges', vb($frm['socolissimo_forwardingcharges']));
	$tpl->assign('socolissimo_firstorder', vb($frm['socolissimo_firstorder']));
	$tpl->assign('socolissimo_pointrelais', vb($frm['socolissimo_pointrelais']));
	$tpl->assign('socolissimo_dyForwardingChargesCMT', vb($frm['socolissimo_dyForwardingChargesCMT']));
	$tpl->assign('partner_count_method', vb($frm['partner_count_method']));
	$tpl->assign('tnt_username', vn($frm['tnt_username']));
	$tpl->assign('tnt_password', vn($frm['tnt_password']));
	$tpl->assign('tnt_account_number', vn($frm['tnt_account_number']));
	$tpl->assign('tnt_treshold', vn($frm['tnt_treshold']));
	$tpl->assign('expedition_delay', vn($frm['expedition_delay']));
	$tpl->assign('act_on_top', vn($frm['act_on_top']));
	$tpl->assign('auto_promo', vn($frm['auto_promo']));
	$tpl->assign('mode_transport', vn($frm['mode_transport']));
	$tpl->assign('module_ecotaxe', vn($frm['module_ecotaxe']));
	$tpl->assign('display_prices_with_taxes', vn($frm['display_prices_with_taxes']));
	$tpl->assign('display_prices_with_taxes_in_admin', vn($frm['display_prices_with_taxes_in_admin']));
	$tpl->assign('module_devise', vn($frm['module_devise']));
	$tpl->assign('html_editor', vb($frm['html_editor']));
	$tpl->assign('send_email_active', vn($frm['send_email_active']));
	$tpl->assign('module_nuage', vn($frm['module_nuage']));
	$tpl->assign('module_flash', vn($frm['module_flash']));
	$tpl->assign('module_pub', vn($frm['module_pub']));
	$tpl->assign('module_rollover', vn($frm['module_rollover']));
	$tpl->assign('type_rollover', vn($frm['type_rollover']));
	$tpl->assign('module_rss', vn($frm['module_rss']));
	$tpl->assign('module_avis', vn($frm['module_avis']));
	$tpl->assign('module_captcha', vn($frm['module_captcha']));
	$tpl->assign('module_precedent_suivant', vn($frm['module_precedent_suivant']));
	$tpl->assign('in_category', vn($frm['in_category']));
	$tpl->assign('module_cart_preservation', vn($frm['module_cart_preservation']));
	$tpl->assign('module_retail', vn($frm['module_retail']));
	$tpl->assign('module_affilie', vn($frm['module_affilie']));
	$tpl->assign('module_lot', vn($frm['module_lot']));
	$tpl->assign('module_parrain', vn($frm['module_parrain']));
	$tpl->assign('module_cadeau', vn($frm['module_cadeau']));
	$tpl->assign('module_faq', vn($frm['module_faq']));
	$tpl->assign('module_entreprise', vn($frm['module_entreprise']));
	$tpl->assign('module_forum', vn($frm['module_forum']));
	$tpl->assign('module_giftlist', vn($frm['module_giftlist']));
	$tpl->assign('module_socolissimo', vn($frm['module_socolissimo']));
	$tpl->assign('module_icirelais', vn($frm['module_icirelais']));
	$tpl->assign('module_tnt', vn($frm['module_tnt']));
	$tpl->assign('module_vacances_type', vn($frm['module_vacances_type']));
	
	$tpl->assign('keep_old_orders_intact', vn($frm['keep_old_orders_intact']));
	$tpl->assign('fonctionsconditionnement', file_exists($GLOBALS['fonctionsconditionnement']));
	$tpl->assign('module_conditionnement', vn($frm['module_conditionnement']));
	$tpl->assign('payment_status_decrement_stock', vn($frm['payment_status_decrement_stock']));
	$tpl->assign('keep_old_orders_intact_date', (empty($frm['keep_old_orders_intact_date']) && intval(vn($frm['keep_old_orders_intact']))>1?get_formatted_date(vb($frm['keep_old_orders_intact'])) : vb($frm['keep_old_orders_intact_date'])));
	$tpl->assign('STR_MANDATORY', $GLOBALS['STR_MANDATORY']);
	$tpl->assign('STR_ADMIN_VARIOUS_INFORMATION_HEADER', $GLOBALS['STR_ADMIN_VARIOUS_INFORMATION_HEADER']);
	$tpl->assign('STR_ADMIN_MODULES', 'Modules');
	$tpl->assign('STR_ADMIN_SITES_MODULE_INSTALL', $GLOBALS['STR_ADMIN_SITES_MODULE_INSTALL']);
	$tpl->assign('STR_ADMIN_SITES_PREMIUM_MODULE', $GLOBALS['STR_ADMIN_SITES_PREMIUM_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_CAPTCHA_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_CAPTCHA_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_POSITION', $GLOBALS['STR_ADMIN_POSITION']);
	$tpl->assign('STR_ADMIN_WWWROOT', $GLOBALS['STR_ADMIN_WWWROOT']);
	$tpl->assign('STR_TTC', $GLOBALS['STR_TTC']);
	$tpl->assign('STR_HT', $GLOBALS['STR_HT']);
	$tpl->assign('STR_ADMIN_HTML_PLACE', $GLOBALS['STR_ADMIN_HTML_PLACE']);
	$tpl->assign('STR_BEFORE_TWO_POINTS', $GLOBALS['STR_BEFORE_TWO_POINTS']);
	$tpl->assign('STR_UPDATE_WEBSITE', $GLOBALS['STR_UPDATE_WEBSITE']);
	$tpl->assign('STR_ADMIN_SITES_TITLE', $GLOBALS['STR_ADMIN_SITES_TITLE']);
	$tpl->assign('STR_ADMIN_SITES_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_GENERAL_PARAMETERS', $GLOBALS['STR_ADMIN_SITES_GENERAL_PARAMETERS']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATION', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATION']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATED', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATED']);
	$tpl->assign('STR_ADMIN_SITES_SITE_SUSPENDED', $GLOBALS['STR_ADMIN_SITES_SITE_SUSPENDED']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN2', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN2']);
	$tpl->assign('STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN3', $GLOBALS['STR_ADMIN_SITES_SITE_ACTIVATED_EXPLAIN3']);
	$tpl->assign('STR_ADMIN_SITES_SITE_NAME', $GLOBALS['STR_ADMIN_SITES_SITE_NAME']);
	if(!empty($GLOBALS['site_parameters']['site_country_allowed_array'])) {
		$tpl->assign('STR_ADMIN_SITES_SITE_COUNTRY_PRESELECTED', $GLOBALS['STR_ADMIN_SITES_SITE_COUNTRY_PRESELECTED']);
	}
	$tpl->assign('STR_ADMIN_SITES_TEMPLATE_USED', $GLOBALS['STR_ADMIN_SITES_TEMPLATE_USED']);
	$tpl->assign('STR_ADMIN_SITES_PAGE_LINKS_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_PAGE_LINKS_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_DISPLAY', $GLOBALS['STR_ADMIN_SITES_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_CSS_FILES', $GLOBALS['STR_ADMIN_SITES_CSS_FILES']);
	$tpl->assign('STR_ADMIN_SITES_CSS_FILES_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_CSS_FILES_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_LOGO_URL', $GLOBALS['STR_ADMIN_SITES_LOGO_URL']);
	$tpl->assign('STR_ADMIN_SITES_LOGO_HEADER_DISPLAY', $GLOBALS['STR_ADMIN_SITES_LOGO_HEADER_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_FAVICON', $GLOBALS['STR_ADMIN_SITES_FAVICON']);
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$tpl->assign('STR_ADMIN_SITES_ZOOM_SELECTION', $GLOBALS['STR_ADMIN_SITES_ZOOM_SELECTION']);
	$tpl->assign('STR_ADMIN_SITES_JQZOOM', $GLOBALS['STR_ADMIN_SITES_JQZOOM']);
	$tpl->assign('STR_ADMIN_SITES_CLOUD_ZOOM', $GLOBALS['STR_ADMIN_SITES_CLOUD_ZOOM']);
	$tpl->assign('STR_ADMIN_SITES_LIGHTBOX', $GLOBALS['STR_ADMIN_SITES_LIGHTBOX']);
	$tpl->assign('STR_NONE', $GLOBALS['STR_NONE']);
	$tpl->assign('STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION', $GLOBALS['STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION']);
	$tpl->assign('STR_ADMIN_SITES_JAVASCRIPT_AJAX_ACTIVATE', $GLOBALS['STR_ADMIN_SITES_JAVASCRIPT_AJAX_ACTIVATE']);
	$tpl->assign('STR_ADMIN_SITES_JAVASCRIPT_JQUERY_ACTIVATE', $GLOBALS['STR_ADMIN_SITES_JAVASCRIPT_JQUERY_ACTIVATE']);
	$tpl->assign('STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_JAVASCRIPT_LIBRARIES_ACTIVATION_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING', $GLOBALS['STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING']);
	$tpl->assign('STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_UTF8', $GLOBALS['STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_UTF8']);
	$tpl->assign('STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_ISO', $GLOBALS['STR_ADMIN_SITES_EXPORT_DEFAULT_ENCODING_ISO']);
	$tpl->assign('STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION', $GLOBALS['STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION']);
	$tpl->assign('STR_YES', $GLOBALS['STR_YES']);
	$tpl->assign('STR_NO', $GLOBALS['STR_NO']);
	$tpl->assign('STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS', $GLOBALS['STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS']);
	$tpl->assign('STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PAYMENT_FORM_AUTO_VALIDATION_WAIT_SECONDS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_CATEGORY_COUNT_METHOD', $GLOBALS['STR_ADMIN_SITES_CATEGORY_COUNT_METHOD']);
	$tpl->assign('STR_ADMIN_SITES_CATEGORY_COUNT_INDIVIDUAL', $GLOBALS['STR_ADMIN_SITES_CATEGORY_COUNT_INDIVIDUAL']);
	$tpl->assign('STR_ADMIN_SITES_CATEGORY_COUNT_GLOBAL', $GLOBALS['STR_ADMIN_SITES_CATEGORY_COUNT_GLOBAL']);
	$tpl->assign('STR_ADMIN_SITES_CART_POPUP_SIZE', $GLOBALS['STR_ADMIN_SITES_CART_POPUP_SIZE']);
	$tpl->assign('STR_ADMIN_SITES_CART_POPUP_SIZE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_CART_POPUP_SIZE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SECURITY', $GLOBALS['STR_ADMIN_SITES_SECURITY']);
	$tpl->assign('STR_ADMIN_SITES_ADMIN_FORCE_SSL', $GLOBALS['STR_ADMIN_SITES_ADMIN_FORCE_SSL']);
	$tpl->assign('STR_ADMIN_SITES_ADMIN_FORCE_SSL_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_ADMIN_FORCE_SSL_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_HTTPS_TEST', $GLOBALS['STR_ADMIN_SITES_HTTPS_TEST']);
	$tpl->assign('STR_ADMIN_SITES_SESSIONS_DURATION', $GLOBALS['STR_ADMIN_SITES_SESSIONS_DURATION']);
	$tpl->assign('STR_MINUTES', $GLOBALS['strShortMinutes']);
	$tpl->assign('STR_ADMIN_ACTIVATE', $GLOBALS['STR_ADMIN_ACTIVATE']);
	$tpl->assign('STR_ADMIN_DEACTIVATE', $GLOBALS['STR_ADMIN_DEACTIVATE']);
	$tpl->assign('STR_ADMIN_SITES_SESSIONS_DURATION_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SESSIONS_DURATION_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP', $GLOBALS['STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_DISPLAY', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_COUNT_IN_MENU', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_COUNT_IN_MENU']);
	$tpl->assign('STR_ADMIN_SITES_THUMBS_SIZE', $GLOBALS['STR_ADMIN_SITES_THUMBS_SIZE']);
	$tpl->assign('STR_ADMIN_SITES_IMAGES_SIZE', $GLOBALS['STR_ADMIN_SITES_IMAGES_SIZE']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_FILTER_DISPLAY', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_FILTER_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_ALLOW_ADD_PRODUCT_IN_LIST_PAGES', $GLOBALS['STR_ADMIN_SITES_ALLOW_ADD_PRODUCT_IN_LIST_PAGES']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY', $GLOBALS['STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_SELECT_MENU']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_RADIO_BUTTONS']);
	$tpl->assign('STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX', $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_CHECKBOX']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PRODUCT_ATTRIBUTES_DISPLAY_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_PER_PAGE', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_PER_PAGE']);
	$tpl->assign('STR_ADMIN_SITES_PRODUCTS_PER_PAGE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PRODUCTS_PER_PAGE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_ADD_TO_CART_ANIMATION', $GLOBALS['STR_ADMIN_SITES_ADD_TO_CART_ANIMATION']);
	$tpl->assign('STR_ADMIN_SITES_DEFAULT_PRODUCT_PAGE', $GLOBALS['STR_ADMIN_SITES_DEFAULT_PRODUCT_PAGE']);
	$tpl->assign('STR_DELETE_THIS_FILE', $GLOBALS['STR_DELETE_THIS_FILE']);
	$tpl->assign('STR_ADMIN_SITES_TOP_SALES_CONFIGURATION', $GLOBALS['STR_ADMIN_SITES_TOP_SALES_CONFIGURATION']);
	$tpl->assign('STR_ADMIN_SITES_AUTO_TOP_SALES', $GLOBALS['STR_ADMIN_SITES_AUTO_TOP_SALES']);
	$tpl->assign('STR_ADMIN_SITES_CONFIGURED_TOP_SALES', $GLOBALS['STR_ADMIN_SITES_CONFIGURED_TOP_SALES']);
	$tpl->assign('STR_ADMIN_SITES_TOP_SALES_MAX_PRODUCTS', $GLOBALS['STR_ADMIN_SITES_TOP_SALES_MAX_PRODUCTS']);
	$tpl->assign('STR_ADMIN_SITES_LAST_VISITS_MAX_PRODUCTS', $GLOBALS['STR_ADMIN_SITES_LAST_VISITS_MAX_PRODUCTS']);
	$tpl->assign('STR_ADMIN_SITES_AUTO_PROMOTIONS', $GLOBALS['STR_ADMIN_SITES_AUTO_PROMOTIONS']);
	$tpl->assign('STR_ADMIN_SITES_CONFIGURED_PROMOTIONS', $GLOBALS['STR_ADMIN_SITES_CONFIGURED_PROMOTIONS']);
	$tpl->assign('STR_ADMIN_SITES_GLOBAL_DISCOUNT_PERCENTAGE', $GLOBALS['STR_ADMIN_SITES_GLOBAL_DISCOUNT_PERCENTAGE']);
	$tpl->assign('STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS', $GLOBALS['STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS']);
	$tpl->assign('STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY', $GLOBALS['STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY']);
	$tpl->assign('STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_SHORT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_NO_VAT_FOR_INTRACOM_FOREIGNERS_LOCAL_COUNTRY_SHORT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_BILLING_HEADER', $GLOBALS['STR_ADMIN_SITES_BILLING_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_BILLING_NUMBER_FORMAT', $GLOBALS['STR_ADMIN_SITES_BILLING_NUMBER_FORMAT']);
	$tpl->assign('STR_ADMIN_SITES_BILLING_NUMBER_FORMAT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_BILLING_NUMBER_FORMAT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_HEADER', $GLOBALS['STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS', $GLOBALS['STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS']);
	$tpl->assign('STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_REQUEST_FOR_PROPOSAL_VALIDITY_DAYS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS_LIMIT', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS_LIMIT']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS_AMOUNT', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS_AMOUNT']);
	$tpl->assign('STR_ADMIN_SITES_SMALL_ORDERS_VAT', $GLOBALS['STR_ADMIN_SITES_SMALL_ORDERS_VAT']);
	$tpl->assign('STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED', $GLOBALS['STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED']);
	$tpl->assign('STR_ADMIN_SITES_MINIMUM_ORDER_REVE_AMOUNT_ALLOWED', $GLOBALS['STR_ADMIN_SITES_MINIMUM_ORDER_REVE_AMOUNT_ALLOWED']);
	$tpl->assign('STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_MINIMUM_ORDER_AMOUNT_ALLOWED_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_LIMITATION', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_LIMITATION']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBID', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBID']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_LAST_YEAR', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_LAST_YEAR']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE']);
	$tpl->assign('STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_ORDERS_UPDATING_OLD_FORBIDDEN_IF_OLDER_THAN_DATE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_HEADER', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_RESELLER_FRANCO_LIMIT', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_RESELLER_FRANCO_LIMIT']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_GENERAL_FRANCO_LIMIT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_METHOD', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_METHOD']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_NONE', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_NONE']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_GENERAL', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_GENERAL']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_COST_METHOD_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_COST_METHOD_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_PRODUCTS_IN_CART_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_LIMIT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_FRANCO_NO_ZONE', $GLOBALS['STR_ADMIN_SITES_DELIVERY_FRANCO_NO_ZONE']);
	$tpl->assign('STR_ADMIN_SITES_VAT_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_VAT_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_VAT_DISPLAY_MODE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_VAT_DISPLAY_MODE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_VAT_DISPLAY_MODE_IN_ADMIN', $GLOBALS['STR_ADMIN_SITES_VAT_DISPLAY_MODE_IN_ADMIN']);
	$tpl->assign('STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP_EXPLAIN',  $GLOBALS['STR_ADMIN_SITES_DISPLAY_ERRORS_FOR_IP_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_MODULE', $GLOBALS['STR_ADMIN_SITES_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_VAT_DISPLAY_MODE_HEADER', $GLOBALS['STR_ADMIN_SITES_VAT_DISPLAY_MODE_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY', $GLOBALS['STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_CURRENCY_SELECT_DISPLAY_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DEFAULT_CURRENCY', $GLOBALS['STR_ADMIN_SITES_DEFAULT_CURRENCY']);
	$tpl->assign('STR_ADMIN_SITES_DEFAULT_CURRENCY_WARNING', $GLOBALS['STR_ADMIN_SITES_DEFAULT_CURRENCY_WARNING']);
	$tpl->assign('STR_ADMIN_SITES_CURRENCIES_LINK', $GLOBALS['STR_ADMIN_SITES_CURRENCIES_LINK']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR']);
	$tpl->assign('STR_ADMIN_SITES_DEFAULT', $GLOBALS['STR_ADMIN_SITES_DEFAULT']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR_FCKEDITOR', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR_FCKEDITOR']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR_CKEDITOR', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR_CKEDITOR']);
	$tpl->assign('STR_ADMIN_SITES_TEXT_EDITOR_NICEDITOR', $GLOBALS['STR_ADMIN_SITES_TEXT_EDITOR_NICEDITOR']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_CONFIGURATION', $GLOBALS['STR_ADMIN_SITES_EMAIL_CONFIGURATION']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_SENDING_ALLOWED', $GLOBALS['STR_ADMIN_SITES_EMAIL_SENDING_ALLOWED']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_SENDING_DEACTIVATE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EMAIL_SENDING_DEACTIVATE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_CONFIGURATION_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EMAIL_CONFIGURATION_EXPLAIN']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL']);
	$tpl->assign('STR_MODULE_PREMIUM_MANDATORY_EMAIL', $GLOBALS['STR_MODULE_PREMIUM_MANDATORY_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_SUPPORT_SENDER_NAME', $GLOBALS['STR_ADMIN_SITES_SUPPORT_SENDER_NAME']);
	$tpl->assign('STR_ADMIN_SITES_SUPPORT_SENDER_NAME_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SUPPORT_SENDER_NAME_EXPLAIN']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN']);
	$tpl->assign('STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL', $GLOBALS['STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_EMAIL_EMPTY_DEFAULT_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_MODULES_POSITIONS', $GLOBALS['STR_ADMIN_SITES_MODULES_POSITIONS']);
	$tpl->assign('STR_ADMIN_SITES_MODULES_POSITIONS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_MODULES_POSITIONS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_DISPLAY_MODE', $GLOBALS['STR_ADMIN_DISPLAY_MODE']);
	$tpl->assign('STR_CHOOSE', $GLOBALS['STR_CHOOSE']);
	$tpl->assign('STR_ADMIN_PLACE', $GLOBALS['STR_ADMIN_PLACE']);
	$tpl->assign('STR_ADMIN_SITES_ABOVE_MIDDLE', $GLOBALS['STR_ADMIN_SITES_ABOVE_MIDDLE']);
	$tpl->assign('STR_ADMIN_SITES_BELOW_MIDDLE', $GLOBALS['STR_ADMIN_SITES_BELOW_MIDDLE']);
	$tpl->assign('STR_ADMIN_SITES_LEFT', $GLOBALS['STR_ADMIN_SITES_LEFT']);
	$tpl->assign('STR_ADMIN_SITES_RIGHT', $GLOBALS['STR_ADMIN_SITES_RIGHT']);
	$tpl->assign('STR_ADMIN_SITES_BOTTOM', $GLOBALS['STR_ADMIN_SITES_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_TOP', $GLOBALS['STR_ADMIN_SITES_TOP']);
	$tpl->assign('STR_ADMIN_SITES_CENTER_TOP', $GLOBALS['STR_ADMIN_SITES_CENTER_TOP']);
	$tpl->assign('STR_ADMIN_SITES_CENTER_MIDDLE', $GLOBALS['STR_ADMIN_SITES_CENTER_MIDDLE']);
	$tpl->assign('STR_ADMIN_SITES_CENTER_MIDDLE_HOME', $GLOBALS['STR_ADMIN_SITES_CENTER_MIDDLE_HOME']);
	$tpl->assign('STR_ADMIN_SITES_CENTER_BOTTOM', $GLOBALS['STR_ADMIN_SITES_CENTER_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_USER_SHOPS_TOP', $GLOBALS['STR_ADMIN_SITES_USER_SHOPS_TOP']);
	$tpl->assign('STR_ADMIN_SITES_USER_SHOPS_BOTTOM', $GLOBALS['STR_ADMIN_SITES_USER_SHOPS_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_SPONSOR', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_SPONSOR']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_AD_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_AD_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_AD_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_AD_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_MIDDLE', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_MIDDLE']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_LEFT', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_LEFT']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_ADS_RIGHT', $GLOBALS['STR_ADMIN_SITES_POSITION_ADS_RIGHT']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_HOME', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_HOME']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ADS_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ADS_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ADS_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ADS_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_AD_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_AD_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_FAVORITES_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_ACCOUNT_CREATION_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_TOP', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_TOP']);
	$tpl->assign('STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_BOTTOM', $GLOBALS['STR_ADMIN_SITES_POSITION_IPHONE_AD_CREATION_BOTTOM']);
	$tpl->assign('STR_ADMIN_SITES_ON_HOMEPAGE_ONLY', $GLOBALS['STR_ADMIN_SITES_ON_HOMEPAGE_ONLY']);
	$tpl->assign('STR_ADMIN_SITES_PAYPAL_EMAIL', $GLOBALS['STR_ADMIN_SITES_PAYPAL_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_MONEYBOOKERS_EMAIL', $GLOBALS['STR_ADMIN_SITES_MONEYBOOKERS_EMAIL']);
	$tpl->assign('STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD', $GLOBALS['STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD']);
	$tpl->assign('STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_MONEYBOOKERS_SECRET_WORD_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_DELIVERY_CARRIER_DELAY', $GLOBALS['STR_ADMIN_SITES_DELIVERY_CARRIER_DELAY']);
	$tpl->assign('STR_ADMIN_SITES_ANALYTICS_TAG', $GLOBALS['STR_ADMIN_SITES_ANALYTICS_TAG']);
	$tpl->assign('STR_ADMIN_SITES_ANALYTICS_TAG_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_ANALYTICS_TAG_EXPLAIN']);
	$tpl->assign('STR_ADMIN_CONTACT_PEEL_FOR_MODULE', $GLOBALS['STR_ADMIN_CONTACT_PEEL_FOR_MODULE']);
	$tpl->assign('STR_ADMIN_SITES_ADVERTISING', $GLOBALS['STR_ADMIN_SITES_ADVERTISING']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_HEADER', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_HEADER']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_DISPLAY', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_DISPLAY_REPLACE', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_DISPLAY_REPLACE']);
	$tpl->assign('STR_ADMIN_SITES_ROLLOVER_DISPLAY_SCROLLING', $GLOBALS['STR_ADMIN_SITES_ROLLOVER_DISPLAY_SCROLLING']);
	$tpl->assign('STR_ADMIN_SITES_CAPTCHA_ACTIVATION', $GLOBALS['STR_ADMIN_SITES_CAPTCHA_ACTIVATION']);
	$tpl->assign('STR_ADMIN_SITES_CAPTCHA_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_CAPTCHA_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_ACTIVATION_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_DIRECT_PARENT', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_DIRECT_PARENT']);
	$tpl->assign('STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_ALL_PARENTS', $GLOBALS['STR_ADMIN_SITES_PREVIOUS_NEXT_BUTTONS_DISPLAY_ALL_PARENTS']);
	$tpl->assign('STR_ADMIN_SITES_ALLOW_ORDERS_WITHOUT_STOCKS', $GLOBALS['STR_ADMIN_SITES_ALLOW_ORDERS_WITHOUT_STOCKS']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_BOOKING_SECONDS', $GLOBALS['STR_ADMIN_SITES_STOCKS_BOOKING_SECONDS']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_BOOKING_DEFAULT', $GLOBALS['STR_ADMIN_SITES_STOCKS_BOOKING_DEFAULT']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_BOOKING_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_STOCKS_BOOKING_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_LIMIT_ALERT', $GLOBALS['STR_ADMIN_SITES_STOCKS_LIMIT_ALERT']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS', $GLOBALS['STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS']);
	$tpl->assign('STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_STOCKS_DECREMENT_BY_PAYMENT_STATUS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_RESELLER_MANAGE', $GLOBALS['STR_ADMIN_SITES_RESELLER_MANAGE']);
	$tpl->assign('STR_ADMIN_SITES_RESELLER_MANAGE_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_RESELLER_MANAGE_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_AFFILIATION_COMMISSION', $GLOBALS['STR_ADMIN_SITES_AFFILIATION_COMMISSION']);
	$tpl->assign('STR_ADMIN_SITES_AFFILIATION_LOGO', $GLOBALS['STR_ADMIN_SITES_AFFILIATION_LOGO']);
	$tpl->assign('STR_ADMIN_SITES_SPONSOR_COMMISSION', $GLOBALS['STR_ADMIN_SITES_SPONSOR_COMMISSION']);
	$tpl->assign('STR_ADMIN_SITES_MICROBUSINESS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_MICROBUSINESS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT', $GLOBALS['STR_ADMIN_SITES_PRESENT_AND_ACTIVATED_BY_DEFAULT']);
	$tpl->assign('STR_ADMIN_SITES_COMPARATOR_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_COMPARATOR_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_SITE_ID', $GLOBALS['STR_ADMIN_SITES_GOOGLE_FRIENDS_CONNECT_SITE_ID']);
	$tpl->assign('STR_ADMIN_SITES_TWITTER_SIGN_IN', $GLOBALS['STR_ADMIN_SITES_TWITTER_SIGN_IN']);
	$tpl->assign('STR_ADMIN_SITES_TWITTER_CONSUMER_KEY', $GLOBALS['STR_ADMIN_SITES_TWITTER_CONSUMER_KEY']);
	$tpl->assign('STR_ADMIN_SITES_TWITTER_CONSUMER_SECRET', $GLOBALS['STR_ADMIN_SITES_TWITTER_CONSUMER_SECRET']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_MODULE_TYPE', $GLOBALS['STR_ADMIN_SITES_VACANCY_MODULE_TYPE']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_MODULE_TYPE_ADMIN', $GLOBALS['STR_ADMIN_SITES_VACANCY_MODULE_TYPE_ADMIN']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_MODULE_TYPE_SUPPLIER', $GLOBALS['STR_ADMIN_SITES_VACANCY_MODULE_TYPE_SUPPLIER']);
	$tpl->assign('STR_ADMIN_SITES_VACANCY_ADMIN_MESSAGE', $GLOBALS['STR_ADMIN_SITES_VACANCY_ADMIN_MESSAGE']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FOID', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FOID']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_SHA1_KEY', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_SHA1_KEY']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_URL_KO', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_URL_KO']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FORWARDINGCHARGES']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_DYFORWARDINGCHARGESCMT', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_DYFORWARDINGCHARGESCMT']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_PREPARATIONTIME_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_FIRSTORDER_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SO_COLISSIMO_POINT_RELAIS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_TNT_USERNAME', $GLOBALS['STR_ADMIN_SITES_TNT_USERNAME']);
	$tpl->assign('STR_ADMIN_SITES_TNT_PASSWORD', $GLOBALS['STR_ADMIN_SITES_TNT_PASSWORD']);
	$tpl->assign('STR_ADMIN_SITES_TNT_ACCOUNT_NUMBER', $GLOBALS['STR_ADMIN_SITES_TNT_ACCOUNT_NUMBER']);
	$tpl->assign('STR_ADMIN_SITES_TNT_TRESHOLD', $GLOBALS['STR_ADMIN_SITES_TNT_TRESHOLD']);
	$tpl->assign('STR_ADMIN_SITES_TNT_EXPEDITION_DELAY', $GLOBALS['STR_ADMIN_SITES_TNT_EXPEDITION_DELAY']);
	$tpl->assign('STR_ADMIN_SITES_SIPS_CERTIFICATE', $GLOBALS['STR_ADMIN_SITES_SIPS_CERTIFICATE']);
	$tpl->assign('STR_ADMIN_SITES_SIPS_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_SIPS_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SPPLUS_EXTERNAL_URL', $GLOBALS['STR_ADMIN_SITES_SPPLUS_EXTERNAL_URL']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_CGI', $GLOBALS['STR_ADMIN_SITES_PAYBOX_CGI']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_SITE', $GLOBALS['STR_ADMIN_SITES_PAYBOX_SITE']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_RANG', $GLOBALS['STR_ADMIN_SITES_PAYBOX_RANG']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_ID', $GLOBALS['STR_ADMIN_SITES_PAYBOX_ID']);
	$tpl->assign('STR_ADMIN_SITES_PAYBOX_TEST_EXPLAIN', $GLOBALS['STR_ADMIN_SITES_PAYBOX_TEST_EXPLAIN']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_CERTIFICATE', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_CERTIFICATE']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_TEST', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_TEST']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_ID', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_ID']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_OCCURENCES', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_OCCURENCES']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_DAYS_BETWEEN_OCCURENCES', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_DAYS_BETWEEN_OCCURENCES']);
	$tpl->assign('STR_ADMIN_SITES_SYSTEMPAY_TEST_MODE', $GLOBALS['STR_ADMIN_SITES_SYSTEMPAY_TEST_MODE']);
	$tpl->assign('STR_ADMIN_SITES_PARTNERS_DISPLAY_MODE', $GLOBALS['STR_ADMIN_SITES_PARTNERS_DISPLAY_MODE']);
	$tpl->assign('STR_ADMIN_SITES_PARTNERS_INDIVIDUAL', $GLOBALS['STR_ADMIN_SITES_PARTNERS_INDIVIDUAL']);
	$tpl->assign('STR_ADMIN_SITES_PARTNERS_GLOBAL', $GLOBALS['STR_ADMIN_SITES_PARTNERS_GLOBAL']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_ADMIN', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_ADMIN']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_PAGE_LINK', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_PAGE_LINK']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_CONNECT', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_CONNECT']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_APPID', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_APPID']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_SECRET', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_SECRET']);
	$tpl->assign('STR_ADMIN_SITES_FACEBOOK_BASEURL', $GLOBALS['STR_ADMIN_SITES_FACEBOOK_BASEURL']);
	$output .= $tpl->fetch();
	return $output;
}

/**
 * Efface les paramètres du site spécifié par $id. Laisse le contenu associé au site.
 *
 * @param integer $id
 * @return
 */
function supprime_site($id)
{
	// Récupération du nom des sites, pour afficher le message de confirmation de suppression (avant l'exécution de la requête)
	$all_sites_name_array = get_all_sites_name_array();
	$delete_table_array = array('admins_actions' ,'commandes' ,'codes_promos' ,'articles' ,'categories' ,'configuration' ,'html' ,'produits' ,'rubriques' ,'tarifs' ,'utilisateurs' ,'utilisateur_connexions' ,'zones' ,'societe' ,'langues' ,'devises' ,'marques' ,'meta' ,'cgv' ,'contacts' ,'legal' ,'access_map' ,'tailles' ,'couleurs' ,'banniere' ,'nom_attributs' ,'attributs' ,'ecotaxes' ,'email_template' ,'email_template_cat' ,'import_field' ,'modules' ,'newsletter' ,'paiement' ,'pays' ,'profil' ,'statut_livraison' ,'statut_paiement' ,'tva' ,'types' ,'webmail' ,'commandes_articles'); 
	if (check_if_module_active('faq')) {
		$delete_table_array[] = 'faq';
	}
	if (check_if_module_active('groups')) {
		$delete_table_array[] = 'groupes';
	}
	if (check_if_module_active('lexique')) {
		$delete_table_array[] = 'lexique';
	}
	if (check_if_module_active('lot')) {
		$delete_table_array[] = 'quantites';
	}
	if (check_if_module_active('parrainage')) {
		$delete_table_array[] = 'parrain';
	}
	if (check_if_module_active('stock_advanced')) {
		$delete_table_array[] = 'alertes';
	}
	if (check_if_module_active('affiliation')) {
		$delete_table_array[] = 'affiliation';
	}
	if (check_if_module_active('stock_advanced')) {
		$delete_table_array[] = 'etatstock';
	}
	if (check_if_module_active('carrousel')) {
		$delete_table_array[] = 'carrousels';
		$delete_table_array[] = 'vignettes_carrousels';
	}
	// Exécution de la suppression
	foreach ($delete_table_array as $this_table_short_name) {
		$qid = query("DELETE 
			FROM peel_".word_real_escape_string($this_table_short_name)."
			WHERE " . get_filter_site_cond($this_table_short_name, null, true) . " AND site_id='".nohtml_real_escape_string(get_site_id_sql_set_value($id))."'");
		if ($this_table_short_name == 'configuration' && affected_rows()) {
			$site_erased = true;
		}
	}
	if (!empty($site_erased)) {
		// suppression du site effectuée, il y a avait des entrées correspondantes au site dans la BDD. Il faut afficher un message de confirmation de suppression à l'admin
		return $GLOBALS['tplEngine']->createTemplate('global_success.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_SITES_MSG_DELETED_OK'], StringMb::html_entity_decode_if_needed(vb($all_sites_name_array[$id])))))->fetch();
	} else {
		// Aucune suppression effectuée, il n'y avait pas d'entrées correspondantes au site dans la BDD. Il faut avertir l'admin.
		return $GLOBALS['tplEngine']->createTemplate('global_error.tpl', array('message' => sprintf($GLOBALS['STR_ADMIN_SITES_MSG_DELETED_NOK'], StringMb::html_entity_decode_if_needed(vn($id)))))->fetch();
	}
}


/**
 * Retourne la liste des sites configurés. N'utilise pas SMARTY.
 *
 * @return
 */
function affiche_liste_site()
{
	$output = '
<table class="main_table">
	<tr>
		<td class="entete" colspan="4">' . $GLOBALS['STR_ADMIN_SITES_LIST_TITLE'] . '</td>
	</tr>';
	if(empty($GLOBALS['site_parameters']['multisite_disable']) && $_SESSION['session_utilisateur']['site_id']==0) {
		// La création de site est reservé aux administrateur multisite.
		$output .= '
		<tr>
			<td colspan="4"><a href="' . get_current_url(false) . '?mode=ajout">' . $GLOBALS['STR_ADMIN_SITES_ADD_SITE'] . '</a></td>
		</tr>';
	}
	// Récupération des infos que l'on a pour chaque site pour l'afficher dans la liste des sites
	// noms
	$all_sites_name_array = get_all_sites_name_array();
	// URL
	$sites_wwwroot_array = get_sites_wwwroot_array();
	if (count($all_sites_name_array) == 0) {
		// Pas de site trouvé. En théorie impossible
		$output .= '<tr><td><b>' . $GLOBALS['STR_ADMIN_SITES_LIST_NOTHING_FOUND'] . '</b></td></tr>';
	} else {
		// Affichage de la liste
		$output .= '
	<tr>
		<td class="menu" width="50">' . $GLOBALS['STR_ADMIN_ACTION'] . '</td>
		<td class="menu" width="80">' . $GLOBALS['STR_ADMIN_ID'] . '</td>
		<td class="menu">' . $GLOBALS['STR_ADMIN_SITES_SITE_NAME'] . '</td>
		<td class="menu">' . $GLOBALS['STR_MODULE_PREMIUM_URL_WEBSITE'] . '</td>
	</tr>';
		$i = 0;
		foreach ($all_sites_name_array as $site_id => $nom) {
			// Boucle par nom de site. On peut faire aussi par wwwroot, choix arbitraire
			// tr_rollover génère les tr avec une alternance de couleur, et un effet au survol de la souris
			$output .= tr_rollover($i, true) . '
		<td class="center">';
			if(empty($GLOBALS['site_parameters']['multisite_disable']) && check_if_module_active('duplicate') && $_SESSION['session_utilisateur']['site_id']==0) {
				// La duplication de site est réservé aux administrateurs multisite.
				$output .= '
			<a onclick="bootbox.confirm(\''.filtre_javascript(sprintf($GLOBALS["STR_ADMIN_SITE_DUPLICATE_CONFIRM"], $nom), true, true, true) . '\', function(result)  {if (result) {document.location = \'' . get_current_url(false) . '?mode=duplicate&id=' . $site_id . '\'}} ); return false;" title="' . $GLOBALS['STR_ADMIN_SITES_DUPLICATE'] . '" href="' . get_current_url(false) . '?mode=duplicate&id=' . $site_id . '"><img src="' . $GLOBALS['administrer_url'] . '/images/duplicate.png" alt="' . $GLOBALS['STR_ADMIN_SITES_DUPLICATE'] . '" /></a>';
			}
			$output .= '
			<a title="' . $GLOBALS['STR_ADMIN_SITES_LIST_MODIFY'] . '" href="' . get_current_url(false) . '?mode=modif&id=' . $site_id . '"><img src="' . $GLOBALS['administrer_url'] . '/images/b_edit.png" alt="' . $GLOBALS['STR_ADMIN_SITES_LIST_MODIFY'] . '" /></a>';
			if(empty($GLOBALS['site_parameters']['multisite_disable']) && $_SESSION['session_utilisateur']['site_id']==0) {
				// La suppression de site est réservé aux administrateurs multisite.
				$output .= '
			<a onclick="bootbox.confirm(\''.filtre_javascript(sprintf($GLOBALS["STR_ADMIN_SITE_DELETE_CONFIRM"], $nom), true, true, true) . '\', function(result)  {if (result) {document.location = \'' . get_current_url(false) . '?mode=suppr&id=' . $site_id . '\'}}); return false;" title="' . $GLOBALS['STR_DELETE'] . '" href="' . get_current_url(false) . '?mode=suppr&id=' . $site_id . '"><img src="' . $GLOBALS['administrer_url'] . '/images/b_drop.png" alt="' . $GLOBALS['STR_DELETE'] . '" /></a>';
			}
			$output .= '
		</td>
		<td class="title_label center">' . $site_id . '</td>
		<td class="center" style="padding-left:10px">' . get_site_name($site_id) . '</td>
		<td class="center" style="padding-left:10px">' . vb($sites_wwwroot_array[$site_id]) . '</td>
	</tr>';
			// L'incrément est utile pour la fonction tr_rollover
			$i++;
		}
	}
		$output .= "
</table>";
	return $output;
}

/**
 * Supprime une favicon
 *
 * @param integer $id
 * @param mixed $file
 * @return
 */
function supprime_favicon($id, $file)
{
	set_configuration_variable(array('technical_code' => 'favicon', 'string' => '', 'origin' => 'sites.php', 'site_id' => $id), true);
	return delete_uploaded_file_and_thumbs($file);
}

/**
 * Supprime l'image par défaut utilisée sur le site
 *
 * @param integer $id
 * @param string $file
 * @return
 */
function supprime_default_picture($id, $file)
{
	set_configuration_variable(array('technical_code' => 'default_picture', 'string' => '', 'origin' => 'sites.php', 'site_id' => $id), true);
	return delete_uploaded_file_and_thumbs($file);
}

