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
// $Id: TwigEngine.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TwigTemplate.php';

/**
 * Implementation of SmartyEngine on top of the generic PEEL template engine
 *
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: TwigEngine.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
class TwigEngine extends EngineTpl {
	private $twig;
	private $context = array();
	
	public function __construct($templadeDir, $forceCompile = false, $debugging = false)
	{
		$twigConf = array();
		$twigConf['cache'] = $GLOBALS['dirroot'] . '/cache';
		$twigConf['debug'] = $debugging;
		$twigConf['auto_reload'] = $forceCompile;
		$twigConf['autoescape'] = false;
		$loader = new Twig_Loader_Filesystem($GLOBALS['repertoire_modele'] . '/' . $GLOBALS['site_parameters']['template_engine']);
		$this->twig = new Twig_Environment($loader, $twigConf);
		// Ajout de fonctionnalités
		$this->twig->addFilter(new Twig_SimpleFilter("filtre_javascript", "filtre_javascript"));
		$this->twig->addFilter(new Twig_SimpleFilter("html_entity_decode_if_needed", "StringMb::html_entity_decode_if_needed"));
		$this->twig->addFilter(new Twig_SimpleFilter("str_shorten", "StringMb::str_shorten"));
		$this->twig->addFilter(new Twig_SimpleFilter("nl2br_if_needed", "StringMb::nl2br_if_needed"));
		$this->twig->addFilter(new Twig_SimpleFilter("str_form_value", "StringMb::str_form_value"));
		$this->twig->addFilter(new Twig_SimpleFilter("strtoupper", "StringMb::strtoupper"));
		$this->twig->addFilter(new Twig_SimpleFilter("html_entity_decode", "StringMb::html_entity_decode"));
		$this->twig->addFilter(new Twig_SimpleFilter("htmlentities", "StringMb::htmlentities"));
		$this->twig->addFilter(new Twig_SimpleFilter("textEncode", "StringMb::textEncode"));
		$this->twig->addFilter(new Twig_SimpleFilter("htmlspecialchars", "htmlspecialchars"));
		$this->twig->addFilter(new Twig_SimpleFilter("addslashes", "addslashes"));
		$this->twig->addFilter(new Twig_SimpleFilter("intval", "intval"));
		$this->twig->addFilter(new Twig_SimpleFilter("round", "round"));
		$this->twig->addFilter(new Twig_SimpleFilter("strip_tags", "strip_tags"));
		/*$title = new Twig_Function_Function(getTitle, array('is_safe' => array('html')));
		$this->twig->addFunction('getTitle', $title);
		//
		
		$filters = array();
		$ignoredMethods = array(
			'getFilters' => true,
			'getName' => true,
			'initRuntime' => true,
			'getTokenParsers' => true,
			'getNodeVisitors' => true
		);
		foreach (get_class_methods($this) as $methodName) {
			if (!array_key_exists($methodName, $ignoredMethods)) {
				$filters[$methodName] = new Twig_Filter_Method($this, $methodName);
			}
		}
		*/
  }

	public function assign($tpl_var, $value = null)
	{
		$this->context[$tpl_var] = $value;
	}

	public function display($template)
	{
		$this->twig->display($template, $this->context);
	}

	public function fetch($template)
	{
		return $this->twig->render($templat, $this->context);
	}

	public function createTemplate($template, array $data = null)
	{
		// Variables générales disponibles dans Twig, et variables de compatibilité partielle avec certains modèles de templates
		$data['LANG'] = $GLOBALS['LANG'];
		$data['site_parameters'] = $GLOBALS['site_parameters'];
		if(!isset($data['site_id'])) {
			$data['site_id'] = $GLOBALS['site_id'];
		}
		if(!isset($data['wwwroot'])) {
			$data['wwwroot'] = $GLOBALS['wwwroot'];
		}
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
		$this->context = array_merge($this->context, $data);
		try{
			//try {
				return new TwigTemplate($this->twig->loadTemplate($template), $this->context);
			//} catch (Twig_Error_Syntax $e) {
			//	return new TwigTemplate($this->twig->loadTemplate('void.tpl'), $this->context);
			//}
		} catch (Twig_Error_Loader $e) {
			$this->context['missing_template_name'] = $template;
			return new TwigTemplate($this->twig->loadTemplate('void.tpl'), $this->context);
		}
	}
}

