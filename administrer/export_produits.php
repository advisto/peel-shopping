<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an     |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/   |
// +----------------------------------------------------------------------+
// $Id: export_produits.php 55332 2017-12-01 10:44:06Z sdelaporte $
define('IN_PEEL_ADMIN', true);
include("../configuration.inc.php");
necessite_identification();
necessite_priv("admin_products,admin_webmastering");
$output = '';

switch(vb($_POST['mode'])) {
	case 'export':
		// DEBUT PARAMETRAGE
		// La colonne stock dans peel_produits ne sert pas, donc l'exporter induit en confusion
		$excluded_fields = array('stock');
		$specific_fields_array = array($GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS'], $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY']);
		$hook_result = call_module_hook('export_products_get_configuration_array', array(), 'array');
		$specific_fields_array = array_merge_recursive_distinct($specific_fields_array, vb($hook_result['product_field_names'], array()));

		// FIN PARAMETRAGE
		if (!empty($_GET['encoding'])) {
			$page_encoding = $_GET['encoding'];
		} elseif (!empty($GLOBALS['site_parameters']['export_encoding'])) {
			$page_encoding = $GLOBALS['site_parameters']['export_encoding'];
		} else {
			$page_encoding = 'utf-8';
		}
		$filename = "export_produits_" . str_replace('/', '-', date($GLOBALS['date_basic_format_short'])) . ".csv";
		// On ne veut pas polluer le fichier exporté par un quelconque message d'erreur
		@ini_set('display_errors', 0);
		output_csv_http_export_header($filename, 'csv', $page_encoding);
		// On récupère les noms des champs de la table de produits
		$product_field_names = get_table_field_names('peel_produits');

		// On rajoute ensuite des colonnes calculées
		foreach ($specific_fields_array as $this_field) {
			$product_field_names[] = $this_field;
		}
		// On retire les colonnes non désirées
		foreach($product_field_names as $this_key => $this_field) {
			if (in_array($this_field, $excluded_fields)) {
				unset($product_field_names[$this_key]);
			}
		}
		// On trie les colonnes
		sort($product_field_names);
		// On construit la ligne des titres
		$title_line_output = array();
		foreach($product_field_names as $this_field_name) {
			$title_line_output[] = filtre_csv($this_field_name);
		}
		$output .= implode("\t", $title_line_output) . "\r\n";
		// On construit toutes les lignes de données
		$q = "SELECT p.*, c.id AS categorie_id, c.nom_" . $_SESSION['session_langue'] . " AS categorie
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
			WHERE " . get_filter_site_cond('produits', 'p', true) . "
			GROUP BY id
			ORDER BY id";
		$query = query($q);
		$i = 0;
		while ($result = fetch_assoc($query)) {
			// On récupère les infos liées à chaque produit
			$product_attributs_id_array = array();
			$product_object = new Product($result['id'], $result, true, null, true, !check_if_module_active('micro_entreprise'));
			$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_INCLUDING_VAT']] = fxsl($product_object->get_original_price(true, false, false));
			$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_LISTED_PRICE_EXCLUDING_VAT']] = fxsl($product_object->get_original_price(false, false, false));
			$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_SIZES']] = implode(',', $product_object->get_possible_sizes('export'));
			$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_COLORS']] = implode(',', $product_object->get_possible_colors());
			$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_BRAND']] = implode(',', $product_object->get_product_brands());
			$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS']] = implode(',', $product_object->get_product_references());
			$result[$GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CATEGORY']] = implode(',', $product_object->get_possible_categories());

			$hook_result = call_module_hook('export_products_get_line_infos_array', array('id' => $product_object->id), 'array');
			$result = array_merge_recursive_distinct($result, $hook_result);
			
			// On génère la ligne
			$this_line_output = array();
			foreach($product_field_names as $this_field_name) {
				// La colonne stock dans peel_produits ne sert pas, donc l'exporter induit en confusion
				if (in_array($this_field_name, $specific_fields_array) || StringMb::substr($this_field_name, 0, StringMb::strlen('descriptif_')) == 'descriptif_' || StringMb::substr($this_field_name, 0, StringMb::strlen('description_')) == 'description_') {
					$this_line_output[] = filtre_csv(StringMb::nl2br_if_needed(StringMb::html_entity_decode_if_needed(vb($result[$this_field_name]), ENT_QUOTES)));
				} elseif (!empty($result[$this_field_name]) && is_array($result[$this_field_name])) {
					$this_line_output[] = filtre_csv(implode(',', $result[$this_field_name]));
				} else {
					$this_line_output[] = filtre_csv(vb($result[$this_field_name]));
				}
			}
			$output .= implode("\t", $this_line_output) . "\r\n";
			unset($product_object);
			$i++;
			if($i%10==0) {
				// On transfère au fur et à mesure pour faire patienter utilisateur, et pour éviter erreur du type : Script timed out before returning headers
				echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
				$output = '';
			}
		}

		echo StringMb::convert_encoding($output, $page_encoding, GENERAL_ENCODING);
	break;

	case 'export_pdf':
		$this_line_output_html = "<table cellspacing='0' cellpadding='1' border='1'><tr>";
		$this_line_output_html .= "<td>" . $GLOBALS["STR_PRODUCT_NAME"] . "</td>" ;
		$this_line_output_html .= "<td>" . $GLOBALS["STR_ADMIN_SHORT_DESCRIPTION"] . "</td>" ;
		$this_line_output_html .= "<td>" . $GLOBALS["STR_PDF_PRIX_HT"] . "</td>" ;
		$this_line_output_html .= "<td>" . $GLOBALS["STR_IMAGE"] . "</td>" ;
		$this_line_output_html .= "<td>" . $GLOBALS["STR_ADMIN_EXPORT_PRODUCTS_COLORS"] . "</td>" ;
		$this_line_output_html .= "<td>" . $GLOBALS["STR_ADMIN_EXPORT_PRODUCTS_SIZES"] . "</td>" ;
		$this_line_output_html .= '</tr>';
		$where = '';
		if (!empty(vn($_POST['categories']))) {
			$where .= " c.id IN (" . implode(',',vn($_POST['categories'])) . ") AND " ;
		}
		$q = "SELECT p.*, p.nom_" . (!empty($GLOBALS['site_parameters']['product_name_forced_lang'])?$GLOBALS['site_parameters']['product_name_forced_lang']:$_SESSION['session_langue']) . " AS nom, p.descriptif_" . $_SESSION['session_langue'] . " AS descriptif, p.image1
			FROM peel_produits p
			INNER JOIN peel_produits_categories pc ON pc.produit_id=p.id
			INNER JOIN peel_categories c ON c.id = pc.categorie_id AND " . get_filter_site_cond('categories', 'c') . "
			WHERE " . $where  . get_filter_site_cond('produits', 'p', true) . "
			GROUP BY p.id
			ORDER BY p.id";
			// var_dump($q);die();
		$query = query($q);
		$i = 0;
		while ($result = fetch_assoc($query)) {
			$this_line_output_html .= '<tr>';
			// On récupère les infos liées à chaque produit
			$product_object = new Product($result['id'], $result, true, null, true, !check_if_module_active('micro_entreprise'));
			$possible_sizes = $product_object->get_possible_sizes('infos', 0, true, false, false, true);
			$size_options_html = '';
			if (!empty($possible_sizes)) {
				$purchase_prix = $product_object->get_final_price();
				foreach ($possible_sizes as $this_size_id => $this_size_infos) {
					$option_content = $this_size_infos['name'];
					$option_content .= "<br/><span style='font-size:10px;'>" . $GLOBALS['STR_MODULE_ATTRIBUTS_ADMIN_LIST_OPTION_OVERCOST'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . fprix($purchase_prix + $this_size_infos['final_price_formatted'], true);
					$size_options_html .= $option_content . "</span><br/>";
				}
			}
			$possible_colors = $product_object->get_possible_colors();
				$color_options_html = '';
				if (!empty($possible_colors)) {
					// Code pour recupérer select des couleurs
					foreach ($possible_colors as $this_color_id => $this_color_name) {
						$color_options_html .= $this_color_name . '<br/>';
					}
				}
			$this_line_output_html .= "<td>" . vb($result['nom']) . "</td>";
			$this_line_output_html .= "<td>" . vb($result['descriptif']) . "</td>";
			$this_line_output_html .= "<td>" . fprix($product_object->get_original_price(false, false, false), true) . "<br/><span style='font-size:10px;'>" . $GLOBALS["STR_ADMIN_ECOTAX"] .$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '. fprix($product_object->get_ecotax(), true) . "</span></td>";
			$this_line_output_html .= "<td>";
			if (!empty($result['image1'])) {
				$this_line_output_html .= "<img src='" . thumbs(vb($result['image1']), 80, 50, 'fit', null, null, true, true) . "'/>";
			}
			$this_line_output_html .= "</td>";
			$this_line_output_html .= "<td align='center'>" . (empty($color_options_html)?'<span>-</span>':$color_options_html) . "</td>";
			$this_line_output_html .= "<td align='center'>" . (empty($size_options_html)?'<span>-</span>':$size_options_html) . "</td>";
			$this_line_output_html .= '</tr>';
			unset($product_object);
		}
	// die(); 
	
		$this_line_output_html .= '</table>';
		$this_line_output_html .= '<div style="position:absolute;bottom:0px;">' . vb($_POST['text_bottom']) . '</div>';
		require_once($GLOBALS['dirroot'].'/lib/class/pdf/html2pdf/html2pdf.class.php');
		try
		{
			$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(2, 10, 10, 10));
			// $html2pdf->setModeDebug();
			$html2pdf->setDefaultFont('Arial');
			$html2pdf->writeHTML($this_line_output_html, isset($_GET['vuehtml']));
			ob_start();
			$html2pdf->Output();
			$output .= ob_get_contents();
			ob_end_clean();
		}
		catch(HTML2PDF_exception $e) {
			echo $e;
			exit;
		}
		// On envoie le PDF
		echo $output;
		die();
	break;

	default:
		$output = '
		<form class="entryform form-inline" role="form" method="post" action="' . get_current_url(false) . '" enctype="multipart/form-data">
		<table class="main_table">
			<tr>
				<td class="entete" colspan="2">' . $GLOBALS['STR_ADMIN_EXPORT_PRODUCTS_CHOOSE_EXPORT_CRITERIA'] . '</td>
			</tr>
			<tr>
				<td class="top">' . $GLOBALS['STR_ADMIN_SELECT_CATEGORIES_TO_EXPORT'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ':</td>
				<td>
					<select class="form-control" name="categories[]" multiple="multiple" style="width:100%" size="10">' 
						. get_categories_output(null, 'categories',  vb($frm['categories']), 'option', '&nbsp;&nbsp;', null, null, true, 80) . '
					</select>
				</td>
			</tr>
			<tr>
				<td class="top">'.$GLOBALS['STR_ADMIN_EXPORT_PRICES_DISABLE'].$GLOBALS['STR_BEFORE_TWO_POINTS'].':</td>
				<td><input type="checkbox" name="price_disable" value="1" /></td>
			</tr>
			<tr>
				<td class="top">'.$GLOBALS["STR_ADMIN_EXPORT_CSV"].$GLOBALS['STR_BEFORE_TWO_POINTS'].':</td>
				<td><input type="radio" name="mode" value="export" /></td>
			</tr>
			<tr>
				<td class="top">'.$GLOBALS['STR_MODULE_FACTURES_ADVANCED_EXPORT_LIST_PDF'].''.$GLOBALS['STR_BEFORE_TWO_POINTS'].':</td>
				<td><input type="radio" name="mode" value="export_pdf" /></td>
			</tr>
			<tr>
				<td class="top">'.$GLOBALS["STR_ADMIN_TEXT_FOR_PDF_EXPORT"].$GLOBALS['STR_BEFORE_TWO_POINTS'].':</td>
				<td><input style="width:100%;" type="text" name="text_bottom" value="" /></td>
			</tr>
			<tr>
				<td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="' . $GLOBALS['STR_SUBMIT'] . '" /></p></td>
			</tr>
		</table>
	</form>';
	include($GLOBALS['repertoire_modele'] . "/admin_haut.php");
	echo $output;
	include($GLOBALS['repertoire_modele'] . "/admin_bas.php");
	break;
}