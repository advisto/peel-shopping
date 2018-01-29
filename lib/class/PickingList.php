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
// $Id: PickingList.php 55332 2017-12-01 10:44:06Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
require_once($GLOBALS['dirroot'] . "/lib/class/pdf/tcpdf.php");

/**
 * PickingList
 *
 * @package PEEL
 * @author oodorizzi
 * @copyright Copyright (c) 2010
 * @version $Id: PickingList.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
class PickingList extends TCPDF {
	var $PickingList_title;
	/**
	 * En-tête
	 *
	 * @return
	 */
	function Header()
	{
		global $dateAdded1, $dateAdded2;
		// Police freesans gras 15
		$this->SetFont('freesans', 'B', 15);
		$this->SetDrawColor(0, 80, 180);
		$this->SetFillColor(230, 210, 0);
		$this->SetTextColor(220, 50, 50);
		$title = $GLOBALS["STR_ADMIN_PICKING_LIST"];
		if(!empty($_GET['statut'])) {
			$title .= ' - ' . get_delivery_status_name($_GET['statut']);
		}
		$this->SetY(5);
		$this->Cell(0, 10, $title . ' ' . $GLOBALS['strStartingOn'] . ' ' . get_formatted_date($dateAdded1) . ' ' . $GLOBALS['strTillDay'] . ' ' . get_formatted_date($dateAdded2) . '', 1, 0, 'C', 1);
		// Saut de ligne
		$this->Ln(20);
	}
	/**
	 * Pied de page
	 *
	 * @return
	 */
	function Footer()
	{
		// Positionnement à 1,5 cm du bas
		$this->SetY(-15);
		// Police freesans italique 8
		$this->SetFont('helvetica', 'I', 8);
		// Numéro de page
		$this->Cell(0, 10, $GLOBALS['STR_PDF_BILL_PAGE'] . ' ' . $this->PageNo() . ' / ' . $this->getAliasNbPages(), 0, 0, 'C');
	}

	/**
	 * Numéro de page
	 *
	 * @return
	 */
	function AddPage()
	{
		TCPDF::AddPage();
		$y1 = 16;
		$this->SetXY(0, $y1);
		$this->SetFont("freesans", "B", 9);
		foreach(explode("\n", $this->PickingList_title) as $this_title) {
			$this->Cell(0, 4, $this_title, 0, 0, "C");
			$y1 = $y1 + 6;
			$this->SetXY(0, $y1);
		}
		$this->SetFont("freesans", "", 8);
	}

	/**
	 * PickingList::FillDocument()
	 *
	 * @param mixed $dateAdded1
	 * @param mixed $dateAdded2
	 * @param mixed $statut
	 * @return
	 */
	function FillDocument($dateAdded1, $dateAdded2, $statut)
	{
		$this->PickingList_title = sprintf($GLOBALS["STR_ADMIN_PICKING_GENERATED_TITLE"], $GLOBALS['site_parameters']['nom_' . $_SESSION['session_langue']], get_formatted_date(time(), 'short', 'long'), get_formatted_date($dateAdded1), get_formatted_date($dateAdded2));
		if (is_numeric($statut)) {
			$this->PickingList_title .= " ".$GLOBALS["STR_ADMIN_PICKING_GENERATED_WITH_DELIVERY_STATUS"].$GLOBALS["STR_BEFORE_TWO_POINTS"].": " . StringMb::strtoupper(get_delivery_status_name($statut));
		}
		$sql = "SELECT c.*, sp.technical_code AS statut_paiement
			FROM peel_commandes c
			LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
			WHERE c.o_timestamp>='" . nohtml_real_escape_string($dateAdded1) . "' AND c.o_timestamp<='" . nohtml_real_escape_string($dateAdded2) . "' " . (is_numeric($statut)?" AND c.id_statut_livraison = '" . intval($statut) . "'":"") . " AND " . get_filter_site_cond('commandes', 'c') . "
			ORDER BY c.o_timestamp";
		$query = query($sql);

		$this->AddPage();

		$k = 1;
		$x1 = 10;
		$x_margin = 5;
		$y_top = 15;
		$y_max = 270;
		$y_block_margin = 13;

		$y1 = $y_top;
		while ($commande = fetch_assoc($query)) {
			$id = $commande['id'];
			$date_commande = get_formatted_date($commande['o_timestamp'], 'short', 'long');
			$order_infos = get_order_infos_array((object) $commande);
			$client = $order_infos['client_infos_ship'];
			$product_infos_array = get_product_infos_array_in_order($id, $commande['devise'], $commande['currency_rate']);

			$w = 91;
			$h = 24;
			$h += count(explode("\n", $client)) * 3;
			if (!empty($product_infos_array)) {
				foreach ($product_infos_array as $this_ordered_product) {
					$h += 1 + (1 + count(explode("\n", $this_ordered_product['product_technical_text']))) * 3;
				}
			}
			if ($y1 + $y_block_margin + $h > $y_max && $y1 > ($y_max - $y_top) / 2) {
				if ($x1 > 100) {
					// nouvelle page
					$x1 = 10;
					$this->AddPage();
				} else {
					// nouvelle colonne
					$x1 = 109;
				}
				$y1 = $y_top;
			}

			$y1 = $y1 + $y_block_margin;

			$this->SetFillColor(210, 210, 255);
			$this->Rect($x1, $y1, $w, 9, 'DF');

			$this->SetFillColor(255, 255, 255);
			// $this->Rect($x1, $y1 + 9, $w, min($h-9, $y_max - ($y1 + 9)), 'DF');
			$this->SetTextColor(0, 0, 0); #Noir*/
			$this->SetFont("freesans", "B", 10);
			$this->SetXY($x1 + 2, $y1 + 1.5);
			$this->Cell($w-2, 6, $GLOBALS["STR_ORDER_NAME"].$GLOBALS["STR_BEFORE_TWO_POINTS"].": ".$commande['order_id']."       ".$GLOBALS["STR_DATE"].$GLOBALS["STR_BEFORE_TWO_POINTS"].": ".$date_commande);

			$y1 = $y1 + 11;
			$this->SetXY($x1 + 2, $y1);

			$this->SetFont("freesans", "B", 8);
			$this->Cell($w-2, 4, $GLOBALS["STR_SHIP_ADDRESS"].$GLOBALS["STR_BEFORE_TWO_POINTS"].":");

			$y1 = $y1 + 5;
			$this->SetXY($x1 + 2, $y1);

			$this->SetFont("freesans", "", 8);
			foreach(explode("\n", $client) as $this_line) {
				$this->Cell(0, 3, $this_line);
				$y1 = $y1 + 3;
				$this->SetXY($x1 + 2, $y1);
			}

			$y1 = $y1 + 2;
			$this->SetXY($x1 + 2, $y1);

			$this->SetFont("freesans", "B", 8);
			$this->Cell($w-2, 4, $GLOBALS['STR_LIST_PRODUCT'] . " :");
			$y1 = $y1 + 4;

			$this->SetFont("freesans", "", 8);
			if (!empty($product_infos_array)) {
				$i = 1;
				foreach ($product_infos_array as $this_ordered_product) {
					$y1 = $y1 + 2;
					$this->SetXY($x1 + $x_margin, $y1);
					$produit_text = $this_ordered_product['product_technical_text'] . " - " . $GLOBALS['STR_QUANTITY_SHORT'] . " : " . $this_ordered_product['quantite'];
					$this->MultiCell($w-2 * $x_margin, 4, $produit_text . "\n", 0, 'J', false, 1, $x1 + 12);

					if (!empty($this_ordered_product["produit_id"])) {
						$product_object = new Product($this_ordered_product["produit_id"]);
						$main_image = $product_object->get_product_main_picture();
						if (!empty($main_image)) {
							$this->Image($GLOBALS['uploaddir'] . '/' . $main_image, $x1 + 3, $y1, 9, 9, '', '', '', true);
						}
						unset($product_object);
					}
					$y1 = $this->GetY();
					if ($y1 + 6 > $y_max) {
						if ($x1 > 100) {
							// nouvelle page
							$x1 = 10;
							$this->AddPage();
						} else {
							// nouvelle colonne
							$x1 = 110;
						}
						$y1 = $y_top + $y_block_margin;
						// On refait le fond
						$this->SetFillColor(255, 255, 255);
						// $this->Rect($x1, $y1, $w, 11 + 6 * (count($product_infos_array) - $i), 'DF');
						$this->SetXY($x1 + $x_margin, $y1 + 1);
						$this->Cell(0, 4, '... '.$GLOBALS["STR_ADMIN_PICKING_GENERATED_NEW_PAGE_FOR_ORDER"].' ' . $commande['order_id'] . ' ...');
						$y1 = $y1 + 4;
					}
					$i++;
				}
			}
			$k++;
		}
		$this->lastPage();
		$this->Output();
	}
}

