<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: Multipage.php 36966 2013-05-26 17:10:43Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}

/**
 *
 * @brief Multipage permet de générer une liste de résultats avec pagination automatique
 *
 * UTILISATION de la classe Multipage :
 * @code require("multipage.class.php");
 * --- Instantiation de la classe
 * $Links = new Multipage("SELECT champs FROM peel_table");
 * $results_array = $Links->Query();
 * @endcode Pas la peine de construire votre requete de sélection avec les "Limit". Elle est automatiquement créée
 *
 * Voici la liste des variables que vous pourrez utiliser dans vos templates Smarty :
 * - {$loop} avec {$l.page}  : permet de boucler sur une partie du template
 * - {$colspan} : nombre de cellules que peuvent occuper les liens vers les autres pages
 * - {$nbrecord} : nombre total d'enregistrement de la requête (sans la limite)
 * - {$next_page} : lien vers la page suivante
 * - {$previous_page} : lien vers la page précédente
 * - {$first_page} : lien vers la première page
 * - {$last_page} : lien vers la dernière page
 * - {$current_page} : page courante
 * - {$total_page} : nombre total de page
 * - {$results_per_page} : nombre total de résultats par page
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Multipage.php 36966 2013-05-26 17:10:43Z gboussin $
 * @access public
 */
class Multipage {
	var $DefaultResultsPerPage;
	var $ResultPerPage;
	var $sqlRequest;
	var $sql_count = null;
	var $LinkPerPage;
	var $AddToColspan;
	var $always_show;
	var $tpl_data;
	var $tpl_name;
	var $page;
	var $pages_count;
	var $nbRecord = null;
	var $external_results_to_merge_at_beginning = null;
	// $HeaderTitlesArray is a table of Titles. Each key can either be numeric if you do not want any sorting, or it can be the name of the SQL item
	var $HeaderTitlesArray;
	var $OrderDefault;
	var $SortDefault;
	var $forced_order_by_string = null;
	var $nombre_session_var_name;
	var $nb1;
	var $nb2;
	var $nb3;
	var $LimitSQL;

	/**
	 * Constructeur
	 */
	function Multipage($sqlRequest, $nombre_session_var_name = 'default_results_per_page', $DefaultResultsPerPage = 50, $LinkPerPage = 7, $AddToColspan = 0, $always_show = true, $template_name = null, $round_elements_per_page = 1, $external_results_to_merge_at_beginning = null)
	{
		if (empty($template_name)) {
			// Si aucun template n'est précisé spécifiquement lors de l'appel de la fonction, la sélection en back office est utilisée
			$template_name = vb($GLOBALS['site_parameters']['template_multipage']);
		}
		$this->tpl_name = $template_name;
		$this->sqlRequest = $sqlRequest;
		$this->DefaultResultsPerPage = $DefaultResultsPerPage;
		if ($this->DefaultResultsPerPage != '*') {
			$this->nb1 = max($round_elements_per_page, round($this->DefaultResultsPerPage / 5) - round($this->DefaultResultsPerPage / 5) % $round_elements_per_page);
			$this->nb2 = max(2 * $round_elements_per_page, round($this->DefaultResultsPerPage) - round($this->DefaultResultsPerPage) % $round_elements_per_page);
			$this->nb3 = max(10, 3*$round_elements_per_page, round($this->DefaultResultsPerPage * 5) - round($this->DefaultResultsPerPage * 5) % $round_elements_per_page);
		}
		$this->LinkPerPage = $LinkPerPage;
		$this->AddToColspan = $AddToColspan;
		$this->always_show = $always_show;
		$this->external_results_to_merge_at_beginning = $external_results_to_merge_at_beginning;
		if(!empty($nombre_session_var_name)){
			$this->nombre_session_var_name = 'session_multipage_' . $nombre_session_var_name;
		}
		$this->Initialize();
	}

	/**
	 * Paramétrage et construction
	 *
	 * @return
	 */
	function Initialize()
	{
		$this->setResultsNumberPerPage();
		// Mode de compatibilité avec ancien Multipage utilisant start= à la place de page=
		if (!empty($_GET['start'])) {
			$this->page = round($_GET['start'] / $this->ResultPerPage);
			if (empty($_POST) && !defined('IN_PEEL_ADMIN') && String::strpos(get_current_url(true), 'start=' . $_GET['start']) !== false) {
				// L'URL contient bien en GET start=... (sans qu'il ne soit dans une URL réécrite)
				// On fait une redirection 301 pour éviter que cette URL reste indexée
				redirect_and_die(get_current_url(true, false, array('start')), true);
			}
		} else {
			$this->CurrentPage(vn($_GET['page']));
		}
		// Initialisation des valeurs par défaut :
		$this->pages_count = 1;
	}

	/**
	 * Multipage::setResultsNumberPerPage()
	 *
	 * @return
	 */
	function setResultsNumberPerPage()
	{
		if ($this->DefaultResultsPerPage != '*') {
			if (isset($_GET['nombre']) && is_numeric($_GET['nombre']) && $_GET['nombre'] >= $this->nb1 && $_GET['nombre'] <= $this->nb3) {
				if(!empty($this->nombre_session_var_name)){
					$_SESSION[$this->nombre_session_var_name] = $_GET['nombre'];
				}
				if (empty($_POST) && !defined('IN_PEEL_ADMIN') && String::strpos(get_current_url(true), 'nombre=' . urlencode($_GET['nombre'])) !== false) {
					// L'URL contient bien en GET nombre=... (sans qu'il ne soit dans une URL réécrite)
					// On fait une redirection 302 pour éviter que cette URL ne soit indexée
					redirect_and_die(get_current_url(true, false, array('nombre')));
				}
			}
			$ResultsPerPage = $this->DefaultResultsPerPage;
			if (!empty($this->nombre_session_var_name) && isset($_SESSION[$this->nombre_session_var_name])) {
				if (($_SESSION[$this->nombre_session_var_name] <= $this->nb1) || ($_SESSION[$this->nombre_session_var_name] < (($this->nb2 + $this->nb1) / 2))) {
					$ResultsPerPage = $this->nb1;
				} elseif (($_SESSION[$this->nombre_session_var_name] < $this->nb3) && ($_SESSION[$this->nombre_session_var_name] < (($this->nb3 + $this->nb2) / 2))) {
					$ResultsPerPage = $this->nb2;
				} else {
					$ResultsPerPage = $this->nb3;
				}
			}
			$this->ResultPerPage = $ResultsPerPage;
		}
	}

	/**
	 * Calcule les paramètres Limit de la requete SQL en fonction de la page en cours
	 *
	 * @param mixed $page
	 * @return
	 */
	function CurrentPage($page)
	{
		if (empty($page)) {
			$page = 1;
		}
		if ($this->DefaultResultsPerPage != '*') {
			$this->page = max(1, intval($page));
		} else {
			$this->page = 1;
		}
	}

	/**
	 * Exécute le SQL avec LIMIT pour retourner les résultats, et calcule juste après car on a besoin que FOUND_ROWS soit exécuté immédiatement après
	 *
	 * @param mixed $return_objects
	 * @return
	 */
	function Query($return_objects = false)
	{
		$results_array = array();
		$lines_begin = max(0, intval($this->ResultPerPage * ($this->page - 1)) - count($this->external_results_to_merge_at_beginning));
		$lines_count = max(0, intval($this->ResultPerPage) + min(0, intval($this->ResultPerPage * ($this->page - 1)) - count($this->external_results_to_merge_at_beginning)));
		$this->LimitSQL = $this->sqlRequest;
		if($lines_count > 0) {
			$this->LimitSQL .= ' ' . $this->getOrderBy();
		}
		if ($this->DefaultResultsPerPage != '*') {
			// Si le nombre de $this->external_results_to_merge_at_beginning est élevé, potentiellement sur les premières pages on a uniquement des éléments extérieurs à cette requête SQL
			// Donc on obtient ci-dessous LIMIT 0,0 => c'est nécessaire néanmoins de lancer la requête, car elle sert puisqu'elle contient SQL_CALC_FOUND_ROWS qui va servir ensuite au calcul pour le nombre de pages
			$this->LimitSQL .= " LIMIT " . $lines_begin . ", " . $lines_count;
		}
		$sql = $this->LimitSQL;
		if (String::strpos(String::strtoupper($sql), 'SQL_CALC_FOUND_ROWS') === false && (String::substr($sql, 0, 1) != '(' || String::strpos($sql, 'UNION') === false || substr_count($sql, 'SELECT')<2)) {
			// Si nécessaire, on rajoute SQL_CALC_FOUND_ROWS
			// On ne le fait pas pour une requête de type UNION - le test sur la parenthèse est une sécurité qui évite des hacks lors de recherche utilisateur
			$sql = str_replace(array('SELECT ', 'select '), 'SELECT SQL_CALC_FOUND_ROWS ', String::substr($sql, 0, 10)) . String::substr($sql, 10);
		}
		$query = query($sql);
		for($i=max(0, intval($this->ResultPerPage * ($this->page - 1)));isset($this->external_results_to_merge_at_beginning[$i]) && $i<max(0, intval($this->ResultPerPage * $this->page));$i++) {
			$results_array[] = $this->external_results_to_merge_at_beginning[$i];
		}
		if ($return_objects) {
			while ($ligne = fetch_object($query)) {
				$results_array[] = $ligne;
			}
		} else {
			while ($ligne = fetch_assoc($query)) {
				$results_array[] = $ligne;
			}
		}
		$this->Calcul(!empty($query));
		return $results_array;
	}

	/**
	 * Calcule la position - sur la barre de liens - du lien correspondant à la page en cours
	 *
	 * @return
	 */
	function Calcul($query_without_error = true)
	{
		if($query_without_error) {
			// Compte le nombre de liens qu'il y aura (= nombre de page)
			if($this->sql_count === null) {
				$this->sql_count = "SELECT FOUND_ROWS() AS rows_count";
			}
			if(!empty($this->sql_count)) {
				$query_count_rs = query($this->sql_count);
				$query_count_row = fetch_assoc($query_count_rs);
				$this->nbRecord = $query_count_row['rows_count'];
				$this->nbRecord += count($this->external_results_to_merge_at_beginning);
			}
		} else {
			$this->nbRecord = count($this->external_results_to_merge_at_beginning);
		}
		if (!empty($this->ResultPerPage) && ($this->ResultPerPage < $this->nbRecord)) {
			$this->pages_count = ceil($this->nbRecord / $this->ResultPerPage);
		}

		$GLOBALS['all_multipage_limit'] = max($this->pages_count, vn($GLOBALS['all_multipage_limit']));
		if (empty($GLOBALS['multipage_avoid_redirect_if_page_over_limit']) && $this->page > $GLOBALS['all_multipage_limit']) {
			$new_url = $this->getPageURL($this->pages_count);
			if ($new_url != get_current_url(true)) {
				redirect_and_die($new_url);
			}
		}
		// Si LinkPerPage vaut '*' on affiche tous les liens
		if ($this->LinkPerPage == '*') {
			$this->LinkPerPage = $this->pages_count;
		}
		if (($this->always_show && $this->DefaultResultsPerPage != '*') || $this->pages_count > 1) {
			$this->ParseTemplate();
		} else {
			// On n'affiche pas la navigation
			$this->tpl_data = '';
		}
	}

	/**
	 * Multipage::getPageURL()
	 *
	 * @param mixed $page
	 * @return
	 */
	function getPageURL($page, $nombre = null)
	{
		$link = get_current_generic_url();
		if (strpos($link, '[PAGE]') !== false) {
			$link = str_replace('[PAGE]', String::rawurlencode($page), $link);
		} elseif ($page > 1) {
			$link .= (strstr($link, '?') ? '&' : '?') . 'page=' . urlencode($page);
		}
		if ($nombre !== null) {
			if (strpos($link, '[NOMBRE]') !== false) {
				$link = str_replace('[NOMBRE]', String::rawurlencode($nombre), $link);
			} else {
				$link .= (strstr($link, '?') ? '&' : '?') . 'nombre=' . urlencode($nombre);
			}
		}
		return $link;
	}

	/**
	 * Cherche et remplace dans le template les variables connues en lançant les traitements associés
	 *
	 * @return
	 */
	function ParseTemplate($show_page_if_only_one = false)
	{
		if(empty($this->tpl_name)) {
			return false;
		}
		$tpl = $GLOBALS['tplEngine']->createTemplate('multipage_template_' . $this->tpl_name . '.tpl');
		$tpl->assign('page', $GLOBALS['STR_PAGE']);
		$tpl->assign('current_page', $this->page);
		$tpl->assign('nbrecord', $this->nbRecord);
		$tpl->assign('total_page', $this->pages_count);
		$tpl->assign('colspan', $this->pages_count + ($this->AddToColspan));
		$tpl->assign('show_page_if_only_one', $show_page_if_only_one);
		$tpl->assign('next_page', ($this->page < $this->pages_count ? '<a href="' . String::str_form_value($this->getPageURL($this->page + 1)) . '"><img src="' . $GLOBALS['wwwroot'] . '/images/next_page.png" alt="' . $GLOBALS['STR_NEXT_PAGE'] . '" /></a>' : ''));
		$tpl->assign('previous_page', ($this->page > 1 ? '<a href="' . String::str_form_value($this->getPageURL(min($this->page - 1, $this->pages_count))) . '"><img src="' . $GLOBALS['wwwroot'] . '/images/previous_page.png" alt="' . $GLOBALS['STR_PREVIOUS_PAGE'] . '" /></a>' : ''));
		$tpl->assign('first_page', ($this->page != 1 ? '<a href="' . String::str_form_value($this->getPageURL(1)) . '"><img src="' . $GLOBALS['wwwroot'] . '/images/first_page.png" alt="' . $GLOBALS['STR_FIRST_PAGE'] . '" /></a>' : ''));
		$tpl->assign('last_page', (!is_user_bot() && $this->page < $this->pages_count ? '<a href="' . String::str_form_value($this->getPageURL($this->pages_count)) . '"><img src="' . $GLOBALS['wwwroot'] . '/images/last_page.png" alt="' . $GLOBALS['STR_LAST_PAGE'] . '" /></a>' : ''));
		if (is_user_bot()) {
			// Pour les moteurs de recherche on retire la possibilité de changer de nombre de résultats, puisque de toutes façons ça ne peut pas marcher pour eux qui ne gèrent pas les sessions
			$links_per_page = null;
		} else {
			$links_per_page = $GLOBALS['STR_PER_PAGE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':  ' .
			(($this->nb1 != $this->ResultPerPage)?('<a href="' . String::str_form_value($this->getPageURL(1, $this->nb1)) . '" rel="nofollow">' . $this->nb1 . '</a>'):('<b>' . $this->nb1 . '</b>')) . ' ' .
			(($this->nb2 != $this->ResultPerPage)?('<a href="' . String::str_form_value($this->getPageURL(1, $this->nb2)) . '" rel="nofollow">' . $this->nb2 . '</a>'):('<b>' . $this->nb2 . '</b>')) . ' ' .
			(($this->nb3 != $this->ResultPerPage)?('<a href="' . String::str_form_value($this->getPageURL(1, $this->nb3)) . '" rel="nofollow">' . $this->nb3 . '</a>'):('<b>' . $this->nb3 . '</b>')) . ' ' .
			(($this->nb1 != $this->ResultPerPage && $this->nb2 != $this->ResultPerPage && $this->nb3 != $this->ResultPerPage)?(' (' . $this->ResultPerPage . ')'):'');
		}
		$tpl->assign('results_per_page', $links_per_page);
		$liens = array();
		if ($this->pages_count > 1) {
			$this->first_link_page = max(1, min($this->page - ceil($this->LinkPerPage / 2) + 1, $this->pages_count - $this->LinkPerPage + 1));
			for ($this_page = $this->first_link_page; $this_page <= min($this->first_link_page + $this->LinkPerPage - 1, $this->pages_count) ;$this_page++) {
				$page = '';
				if ($this_page != $this->first_link_page) {
					$page .= $GLOBALS['STR_MULTIPAGE_SEPARATOR'];
				}
				if ($this_page == $this->page) {
					$page .= '<b class="current_page_number">' . $this_page . '</b>';
				} else {
					$page .= '<a href="' . String::str_form_value($this->getPageURL($this_page)) . '">' . $this_page . '</a>';
				}
				$liens[]['page'] = $page;
			}
		} elseif($show_page_if_only_one) {
			// Si il n'y a pas plus d'une page, alors on affiche uniquement la page 1 en current non cliquable
			$liens[]['page'] = '<b class="current_page_number">1</b>';
		}
		// Si la requête ne donne pas lieu à plusieurs liens
		$tpl->assign('loop', $liens);
		$this->tpl_data = $tpl->fetch();
	}

	/**
	 * Affiche directement en sortie le contenu du template après traitement
	 *
	 * @return
	 */
	function pMultipage()
	{
		echo $this->tpl_data;
	}

	/**
	 * Renvoie le résultat du template une fois parsé
	 *
	 * @return
	 */
	function GetMultipage()
	{
		return $this->tpl_data;
	}

	/**
	 * Multipage::getHeaderRow()
	 *
	 * @return
	 */
	function getHeaderRow()
	{
		$output = '
	<tr>';
		foreach($this->HeaderTitlesArray as $key => $this_title) {
			if (!empty($last_title) && $last_title == $this_title) {
				unset($this->HeaderTitlesArray[$last_key]);
				$add_colspan[$key] = vn($add_colspan[$last_key]) + 1;
			}
			$last_title = $this_title;
			$last_key = $key;
		}
		foreach($this->HeaderTitlesArray as $key => $this_title) {
			if (!empty($add_colspan[$key])) {
				$colspan_text = ' colspan="' . ($add_colspan[$key] + 1) . '"';
			} else {
				$colspan_text = '';
			}
			if (!empty($_GET['order']) && $key === $_GET['order']) {
				$output .= '
		<th class="menu center"' . $colspan_text . ' style="background-color:#5C5859">';
			} else {
				$output .= '
		<th class="menu center"' . $colspan_text . '>';
			}
			if (!is_numeric($key)) {
				$link_url = $_SERVER['REQUEST_URI'];
				if (empty($_GET['order'])) {
					if (String::strpos($link_url, '?') === false) {
						$link_url .= '?';
					} else {
						$link_url .= '&amp;';
					}
					$link_url .= 'order=' . $key;
				} else {
					$link_url = str_replace('order=' . $_GET['order'], 'order=' . $key, $link_url);
				}
				if (!isset($_GET['sort'])) {
					$url_desc = $link_url . '&amp;sort=desc';
					$url_asc = $link_url . '&amp;sort=asc';
				} else {
					// The following code can be easily changed to be compatible with some simple URL Rewriting
					$url_desc = str_replace('sort=' . $_GET['sort'], 'sort=desc', $link_url);
					$url_asc = str_replace('sort=' . $_GET['sort'], 'sort=asc', $link_url);
				}
				$output .= '<a href="' . $url_desc . '"><img src="' . $GLOBALS['administrer_url'] . '/images/desc.gif" width="7" height="7" alt="-" /></a> ' . $this_title . ' <a href="' . $url_asc . '"><img src="' . $GLOBALS['administrer_url'] . '/images/up.gif" width="7" height="7" alt="+" /></a>';
			} else {
				$output .= $this_title;
			}
			$output .= '</th>';
		}
		$output .= '
	</tr>';
		return $output;
	}

	/**
	 * Multipage::getOrderBy()
	 *
	 * @return
	 */
	function getOrderBy()
	{
		if ($this->forced_order_by_string === null) {
			$columns = array_values(explode(',', str_replace(' ', '', vb($_GET['order']) . ',' . $this->OrderDefault)));
			foreach($columns as $this_column) {
				if (!empty($this_column)) {
					// En cas d'ambiguïté, l'on peut avoir la form : table.colonne
					$this_order_by = '`' . str_replace('.', '`.`', word_real_escape_string($this_column)) . '` ';
					if (!empty($_GET['sort'])) {
						$this_order_by .= String::substr(String::strtoupper(word_real_escape_string($_GET['sort'])), 0, 4);
					} elseif (!empty($this->SortDefault)) {
						$this_order_by .= String::substr(String::strtoupper(word_real_escape_string($this->SortDefault)), 0, 4);
					} else {
						$this_order_by .= 'ASC';
					}
					$order_by[] = $this_order_by;
				}
			}
			if (empty($order_by)) {
				return null;
			} else {
				return 'ORDER BY ' . implode(', ', $order_by);
			}
		} elseif (!empty($this->forced_order_by_string)) {
			return 'ORDER BY ' . $this->forced_order_by_string;
		} else {
			return null;
		}
	}
}

?>