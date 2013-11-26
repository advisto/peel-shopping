<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: Cache.php 38682 2013-11-13 11:35:48Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 *
 * @brief La classe Cache sauvegarde du contenu texte ou binaire sur le disque du serveur et peut vérifier son ancienneté
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Cache.php 38682 2013-11-13 11:35:48Z gboussin $
 * @access public
 */
class Cache {
	// Filename with path, generated automatically
	var $file;
	var $filemtime = null;
	// Configuration, if not set default values are used
	// directory => 'directory_name_with_complete_path'
	// group => 'page'
	var $cfg;

	/**
	 * Cache::Cache()
	 *
	 * @param integer $id
	 * @param array $cfg
	 */
	function Cache($id, $cfg = array())
	{
		// Configuration par défaut
		$cfgDefault = array('directory' => $GLOBALS['dirroot'] . '/' . $GLOBALS['site_parameters']['cache_folder'] . '/', 'group' => 'page');
		// Si une config perso est envoyée
		if (count($cfg)) {
			foreach($cfgDefault as $k => $v) {
				$this->cfg[$k] = !isset($cfg[$k]) ? $v : $cfg[$k];
			}
		} else {
			// Sinon on charge la config par défaut
			$this->cfg = $cfgDefault;
		}
		$this->file = $this->cfg['directory'] . String::substr(md5($this->cfg['group']), 0, 8) . '_' . String::substr(md5($id), 0, 16);
	}

	/**
	 * Cache::testTime()
	 *
	 * @param integer $lifeTime
	 * @param mixed $update_timestamp_now
	 * @return
	 */
	function testTime($lifeTime = 7200, $update_timestamp_now = false)
	{
		$lifeTime = round($lifeTime);
		// $update_timestamp_now permet de mettre à jour immédiatement le timestamp du fichier pour éviter que plusieurs utilisateurs
		// cherchent à mettre à jour en même temps le même fichier (car décalage entre test des caches et sauvegarde du nouveau cache)
		if (file_exists($this->file) === false || (($this->filemtime = @filemtime($this->file)) < time() - $lifeTime) || (!empty($_GET['update']) && $_GET['update'] == 1)) {
			// Fichier de cache absent ou pas à jour
			if ($update_timestamp_now && file_exists($this->file)) {
				// On fait en sorte que le fichier de cache soit considéré comme OK si d'autres appels sont faits en parallèle,
				// alors que nous allons le mettre à jour par la suite (on ne veut pas générer en parallèle n fois le fichier)
				// mais si la génération échoue, 20 secondes après on réessaiera
				touch($this->file, time() - $lifeTime + 20);
			}
			return false;
		} else {
			// Fichier de cache OK
			return true;
		}
	}

	/**
	 * Cache::testDate()
	 *
	 * @param mixed $lifeDate
	 * @return
	 */
	function testDate($lifeDate)
	{
		if (file_exists($this->file) === false || @filemtime($this->file) < $lifeDate || (!empty($_GET['update']) && $_GET['update'] == 1)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Cache::get()
	 *
	 * @return
	 */
	function get()
	{
		$fp = String::fopen_utf8($this->file, 'rb');
		if ($fp) {
			@flock($fp, LOCK_SH);
			clearstatcache(); // Les résultats de la fonction filesize() sont mis en cache.
			$content = @fread($fp, @filesize($this->file));
			@flock($fp, LOCK_UN);
			@fclose($fp);
			$key = String::substr($content, 0, 32);
			$data = String::substr($content, 32);
			// On vérifie que la signature md5 est bien égale au contenu du fichier md5. S'ils ne correspondent
			// pas, on modifie la date de dernière modification du fichier pour qu'il soit regénéré au prochain appel
			if ($key != md5($data)) {
				@touch($this->file, 0);
			}
			return $data;
		}
		return false;
	}

	/**
	 * Cache::save()
	 *
	 * @param mixed $data
	 * @return
	 */
	function save($data)
	{
		$fp = String::fopen_utf8($this->file, 'wb');
		if ($fp) {
			@flock($fp, LOCK_EX);
			// On utilise strlen et non pas String::strlen car on veut le nombre d'octets et non pas de caractères
			@fwrite($fp, md5($data) . $data, 32 + strlen($data));
			@flock($fp, LOCK_UN);
			@fclose($fp);
			return true;
		}
	}
	
	/**
	 * Cache::echo_headers()
	 *
	 * @return
	 */
	function echo_headers($lifeTime = 7200)
	{
		header('Cache-Control: public');
		header('Pragma:');
		if(!empty($this->filemtime)) {
			$filemtime = $this->filemtime;
		} else {
			$filemtime = time();
		}
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $filemtime).' GMT');
		header('Expires: ' . gmdate('D, d M Y H:i:s', $filemtime + $lifeTime) . ' GMT'); // 30 days
	}
	
	/**
	 * Cache::delete_cache_file()
	 *
	 * @return
	 */
	function delete_cache_file($clean_all_group = false)
	{
		clean_Cache(0, ($clean_all_group?String::substr(md5($this->cfg['group']), 0, 8) . '_':$this->file));
	}
}

?>