<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: SmartyEngine.php 43037 2014-10-29 12:01:40Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}

require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR . 'Smarty.class.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'SmartyTemplate.php';

/**
 * Implementation of SmartyEngine on top of the generic PEEL template engine
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: SmartyEngine.php 43037 2014-10-29 12:01:40Z sdelaporte $
 * @access public
 */
class SmartyEngine extends EngineTpl {
	private $smarty;

	public function __construct($templadeDir, $forceCompile = false, $debugging = false)
	{
		$dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'smarty' . DIRECTORY_SEPARATOR;
		$this->smarty = new Smarty();
		$this->smarty->setCompileDir($dir . 'compile' . DIRECTORY_SEPARATOR);
		$this->smarty->setCacheDir($dir . 'cache' . DIRECTORY_SEPARATOR);
		$this->smarty->setTemplateDir($templadeDir);
		$this->smarty->caching = Smarty::CACHING_OFF;
		$this->smarty->compile_check = (bool)$forceCompile;
		$this->smarty->force_compile = false;
		$this->smarty->debugging = (bool)$debugging;
		$this->smarty->_file_perms = vb($GLOBALS['site_parameters']['chmod_new_files'], null);
		$this->smarty->registerPlugin("modifier", "filtre_javascript", "filtre_javascript");
		$this->smarty->registerPlugin("modifier", "html_entity_decode_if_needed", "String::html_entity_decode_if_needed");
		$this->smarty->registerPlugin("modifier", "str_shorten", "String::str_shorten");
		$this->smarty->registerPlugin("modifier", "nl2br_if_needed", "String::nl2br_if_needed");
		$this->smarty->registerPlugin("modifier", "str_form_value", "String::str_form_value");
		$this->smarty->registerPlugin("modifier", "strtoupper", "String::strtoupper");
		$this->smarty->registerPlugin("modifier", "html_entity_decode", "String::html_entity_decode");
		$this->smarty->registerPlugin("modifier", "htmlentities", "String::htmlentities");
		$this->smarty->registerPlugin("modifier", "textEncode", "String::textEncode");
		$this->smarty->registerPlugin("modifier", "highlight_found_text", "highlight_found_text");
		$this->smarty->registerDefaultTemplateHandler('SmartyDefaultTemplateHandler');
	}

	public function assign($tpl_var, $value = null)
	{
		$this->smarty->assign($tpl_var, $value);
	}

	public function display($template)
	{
		$this->smarty->display($template);
	}

	public function fetch($template)
	{
		return $this->smarty->fetch($template);
	}

	public function createTemplate($template, array $data = null)
	{
		// Variables générales disponibles dans Smarty, et variables de compatibilité partielle avec certains modèles de templates
		$data['LANG'] = $GLOBALS['LANG'];
		$data['site_parameters'] = $GLOBALS['site_parameters'];
		$data['wwwroot'] = $GLOBALS['wwwroot'];
		$data['base_dir'] = $GLOBALS['wwwroot'];
		$data['content_dir'] = $GLOBALS['wwwroot'] . $GLOBALS['apparent_folder'];
		$data['dirroot'] = $GLOBALS['dirroot'];
		$data['img_ps_dir'] = $GLOBALS['wwwroot'] . '/images';
		$data['repertoire_images'] = $GLOBALS['repertoire_images'];
		$data['repertoire_upload'] = $GLOBALS['repertoire_upload'];
		$data['img_dir'] = $GLOBALS['repertoire_images'];
		$data['repertoire_css'] = $GLOBALS['repertoire_css'];
		$data['css_dir'] = $GLOBALS['repertoire_css'];
		$data['js_dir'] = $GLOBALS['wwwroot'] . '/js';
		$data['tpl_dir'] = $GLOBALS['repertoire_modele'];
		$data['modules_dir'] = $GLOBALS['wwwroot'] . '/modules';
		$data['mail_dir'] = null;
		$data['come_from'] = vb($_SERVER['HTTP_REFERER']);
		$data['languages'] = $GLOBALS['lang_codes'];
		$data['lang_names'] = $GLOBALS['lang_names'];
		$data['lang_iso'] = $_SESSION['session_langue'];
		$data['shop_name'] = vb($GLOBALS['site_parameters']['nom_' . $_SESSION['session_langue']]);
		if(!empty($_SESSION['session_caddie'])) {
			$data['cart_qties'] = $_SESSION['session_caddie']->count_products();
			$data['cart'] = $_SESSION['session_caddie']->articles;
		}
		$data['currencies'] = array($_SESSION['session_devise']['code']);
		$data['id_currency_cookie'] = $_SESSION['session_devise']['code'];
		$data['logged'] = (!empty($_SESSION['session_utilisateur']['id_utilisateur']));
		$data['page_name'] = null; // Non disponible systématiquement ici
		$data['customerName'] = (!empty($_SESSION['session_utilisateur']['id_utilisateur'])?vb($_SESSION['session_utilisateur']['prenom']) . ' '. vb($_SESSION['session_utilisateur']['nom_famille']):null);
		$data['priceDisplay'] = null;
		return new SmartyTemplate($this->smarty->createTemplate($template, null, null, $data));
	}
}

function SmartyDefaultTemplateHandler($resource_type, $resource_name, &$template_source, &$template_timestamp, Smarty $smarty)
{
	if($resource_type == 'file' ) {
		return $GLOBALS['dirroot'] . "/modeles/peel7/smarty/".$resource_name;
	} else {
		// pas un fichier
		return false;
	}
}
