<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: Invoice.php 36232 2013-04-05 13:16:01Z gboussin $
if (!defined('IN_PEEL')) {
	die();
}
require_once($GLOBALS['dirroot'] . "/lib/class/pdf/tcpdf.php");

define('EURO', chr(128));
define('EURO_VAL', 6.55957);
define('FPDF_FONTPATH', $GLOBALS['dirroot'] . '/lib/class/pdf/font/');

/**
 *
 * @brief La classe Invoice génère une facture ou une demande de devis en PDF
 *
 * Exemple d'utilisation :
 * @code
 * 	$invoice_pdf = new Invoice('P', 'mm', 'A4');
 * 	$is_pdf_generated = $invoice_pdf->FillDocument($code_facture, null, null, null, null, null, $mode, false);
 * @endcode
 * @package PEEL
 * @author PEEL <contact@peel.fr>
 * @copyright Advisto SAS 51 bd Strasbourg 75010 Paris https://www.peel.fr/
 * @version $Id: Invoice.php 36232 2013-04-05 13:16:01Z gboussin $
 * @access public
 */
class Invoice extends TCPDF {
	var $colonnes;
	var $format;
	var $angle = 0;
	var $remarque_lignes;

	/**
	 * PRIVATE FUNCTION : Invoice::RoundedRect()
	 *
	 * @param mixed $x
	 * @param mixed $y
	 * @param mixed $w
	 * @param mixed $h
	 * @param mixed $r
	 * @param string $style
	 * @return
	 */
	function RoundedRect($x, $y, $w, $h, $r, $style = '')
	{
		$k = $this->k;
		$hp = $this->h;
		if ($style == 'F')
			$op = 'f';
		elseif ($style == 'FD' or $style == 'DF')
			$op = 'B';
		else
			$op = 'S';
		$MyArc = 4 / 3 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2f %.2f m', ($x + $r) * $k, ($hp - $y) * $k));
		$xc = $x + $w - $r ;
		$yc = $y + $r;
		$this->_out(sprintf('%.2f %.2f l', $xc * $k, ($hp - $y) * $k));

		$this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);
		$xc = $x + $w - $r ;
		$yc = $y + $h - $r;
		$this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($hp - $yc) * $k));
		$this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);
		$xc = $x + $r ;
		$yc = $y + $h - $r;
		$this->_out(sprintf('%.2f %.2f l', $xc * $k, ($hp - ($y + $h)) * $k));
		$this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);
		$xc = $x + $r ;
		$yc = $y + $r;
		$this->_out(sprintf('%.2f %.2f l', ($x) * $k, ($hp - $yc) * $k));
		$this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out($op);
	}

	/**
	 * PRIVATE FUNCTION : Invoice::_Arc()
	 *
	 * @param mixed $x1
	 * @param mixed $y1
	 * @param mixed $x2
	 * @param mixed $y2
	 * @param mixed $x3
	 * @param mixed $y3
	 * @return
	 */
	function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
	{
		$h = $this->h;
		$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1 * $this->k, ($h - $y1) * $this->k,
				$x2 * $this->k, ($h - $y2) * $this->k, $x3 * $this->k, ($h - $y3) * $this->k));
	}

	/**
	 * PRIVATE FUNCTION : Invoice::Rotate()
	 *
	 * @param mixed $angle
	 * @param mixed $x
	 * @param mixed $y
	 * @return
	 */
	function Rotate($angle, $x = -1, $y = -1)
	{
		if ($x == -1)
			$x = $this->x;
		if ($y == -1)
			$y = $this->y;
		if ($this->angle != 0)
			$this->_out('Q');
		$this->angle = $angle;
		if ($angle != 0) {
			$angle *= M_PI / 180;
			$c = cos($angle);
			$s = sin($angle);
			$cx = $x * $this->k;
			$cy = ($this->h - $y) * $this->k;
			$this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm', $c, $s, - $s, $c, $cx, $cy, - $cx, - $cy));
		}
	}

	/**
	 * PRIVATE FUNCTION : Invoice::_endpage()
	 *
	 * @return
	 */
	function _endpage()
	{
		if ($this->angle != 0) {
			$this->angle = 0;
			$this->_out('Q');
		}
		parent::_endpage();
	}
	/**
	 * Invoice::sizeOfText()
	 *
	 * @param mixed $texte
	 * @param mixed $largeur
	 * @return
	 */
	function sizeOfText($texte, $largeur)
	{
		$index = 0;
		$nb_lines = 0;
		$loop = true;
		while ($loop) {
			$pos = String::strpos($texte, "\n");
			if (!$pos) {
				$loop = false;
				$ligne = $texte;
			} else {
				$ligne = String::substr($texte, $index, $pos);
				$texte = String::substr($texte, $pos + 1);
			}
			$length = floor($this->GetStringWidth($ligne));
			if ($largeur != 0) {
				$res = 1 + floor($length / $largeur) ;
			} else {
				$res = 1 + floor($length);
			}
			$nb_lines += $res;
		}
		return $nb_lines;
	}

	/**
	 * Cette fonction affiche en haut à gauche le nom de la societe dans la police Helvetica-12-Bold
	 * les coordonnées de la société dans la police Helvetica-10
	 *
	 * @param mixed $adresse
	 * @param mixed $logo
	 * @return
	 */
	function addSociete($adresse, $logo)
	{
		$x1 = 10;
		$y1 = 6;
		if (!empty($logo)) {
			// Positionnement du logo à droite des informations sur la société
			$this->Image($logo, $x1 + 50, $y1, 35);
		}
		$this->SetXY($x1, $y1);
		// $this->SetFont('Helvetica', 'B', 12);
		// $length = $this->GetStringWidth( $nom );
		// $this->Cell( $length, 2, $nom);
		$this->SetFont('Helvetica', '', 10);
		$length = $this->GetStringWidth($adresse);
		// Coordonnées de la société
		$lignes = $this->sizeOfText($adresse, $length) ;
		$this->MultiCell($length, 4, $adresse);
	}

	/**
	 * Affiche en haut, a droite le libelle (FACTURE, $GLOBALS['STR_DEVIS'], Bon de commande, etc...) et son numero
	 * La taille de la fonte est auto-adaptee au cadre
	 *
	 * @param mixed $libelle
	 * @param mixed $num
	 * @param booelan $change_background_color_by_type
	 * @return
	 */
	function fact_dev($libelle, $num, $change_background_color_by_type = false)
	{
		$r1 = $this->w - 100;
		$r2 = $r1 + 90;
		$y1 = 6;
		$y2 = $y1 + 2;
		$mid = ($r1 + $r2) / 2;

		$texte = $libelle . " N° : " . $num;
		$szfont = 12;
		$loop = 0;

		while ($loop == 0) {
			$this->SetFont("Helvetica", "B", $szfont);
			$sz = $this->GetStringWidth($texte);
			if (($r1 + $sz) > $r2)
				$szfont --;
			else
				$loop ++;
		}
		$this->SetLineWidth(0.1);
		$this->SetFillColor(210, 210, 255);
		if($change_background_color_by_type) {
			// On modifie la couleur de fond du cadre indiquant en fonction du type de document (Facture proforma,devis Bon de commande, Facture)
			if($_GET['mode'] == 'proforma') {// Facture proforma - ROSE
				$this->SetFillColor(241, 165,165);
			}elseif ($_GET['mode'] == 'devis') { // devis - JAUNE
				$this->SetFillColor(241,228,165);
			}elseif ($_GET['mode'] == 'bdc') { // Bon de commande - BLEU
				$this->SetFillColor(165,219,241);
			}elseif ($_GET['mode'] == 'facture') { //Facture - VERT
				$this->SetFillColor(165, 241, 173);
			}
		}
		$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 2.5, 'DF');
		$this->SetXY($r1 + 1, $y1 + 2);
		$this->Cell($r2 - $r1 -1, 5, $texte, 0, 0, "C");
	}

	/**
	 * Génère automatiquement un numéro de facture
	 *
	 * @param mixed $numfact
	 * @return
	 */
	function addFacture($numfact)
	{
		$string = sprintf("FA%04d", $numfact);
		$this->fact_dev($GLOBALS['STR_PDF_BILL'], $string);
	}

	/**
	 * Affiche un cadre avec la date de la facture / devis (en haut, a droite)
	 *
	 * @param mixed $date
	 * @param mixed $date_a
	 * @return
	 */
	function addDate($date, $date_a)
	{
		$r1 = $this->w - 100;
		$y1 = 17;
		$width = 90;
		$height = 12;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($r1, $y1, $width, $height, 'D');
		$this->Rect($r1, $y1, $width, $header_height, 'DF');

		$this->SetFont("Helvetica", "B", 10);
		$this->SetXY($r1, $y1 + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_BILL_DATE'], 0, 0, "C");

		$this->SetFont("Helvetica", "", 10);
		$this->SetXY($r1, $y1 + $this->cMargin + $header_height);

		if ($date_a != "") {
			$date .= " - " . $GLOBALS['STR_PDF_DATE_PAID'] . " " . $date_a;
		}
		$this->Cell(90, 4, $date, 0, 0, "C");
	}

	/**
	 * Affiche un cadre avec les informations sur la TVA (en bas au milieu)
	 *
	 * @param mixed $tva
	 * @param mixed $mode Le mode peut être "devis" ou autre valeur
	 * @return
	 */
	function addInfoTVA($tva, $mode = null)
	{
		$r1 = $this->w / 2 - 15;
		$y1 = $this->h-25;

		$text1 = ($mode != 'devis'?$GLOBALS['STR_INVOICE_BOTTOM_TEXT']:$GLOBALS['STR_INVOICE_BOTTOM_TEXT1']);

		$this->SetXY($r1, $y1);
		$this->SetFont("Helvetica", "", 8);
		$this->Cell(30, 4, $text1, 0, 0, "C");
	}

	/**
	 * Affiche un cadre avec un numéro de page (en haut, a droite)
	 *
	 * @param mixed $page
	 * @return
	 */
	function addPageNumber($page)
	{
		$r1 = $this->w / 2 - 15;
		$y1 = $this->h - 12;

		$this->SetXY($r1, $y1);
		$this->SetFont("Helvetica", "", 8);
		$this->Cell(30, 4, $GLOBALS['STR_PDF_BILL_PAGE'] . ' ' . $page, 0, 0, "C");
	}

	/**
	 * Affiche l'adresse du client (en haut, a droite)
	 *
	 * @param mixed $pdf_facturation
	 * @param integer $id_utilisateur
	 * @return
	 */
	function addClientAdresseFacturation($pdf_facturation, $id_utilisateur)
	{
		$r1 = $this->w - 200;
		$y1 = 40;
		$width = 90;
		$height = 45;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($r1, $y1, $width, $height, 'D');
		$this->Rect($r1, $y1, $width, $header_height, 'DF');

		$this->SetFont("Helvetica", "B", 10);
		$this->SetXY($r1, $y1 + 0.5);
		$title = $GLOBALS['STR_PDF_FACTURATION'];
		if (!empty($id_utilisateur)) {
			$title .= ' ' . $id_utilisateur;
		}
		$this->Cell(90, 4, $GLOBALS['STR_PDF_FACTURATION'], 0, 0, "C");

		$this->SetFont("Helvetica", "", 10);
		$this->SetXY($r1, $y1 + $this->cMargin + $header_height);
		$this->MultiCell(90, 4, $pdf_facturation . "\n");
	}

	/**
	 * Invoice::addClientAdresseExpedition()
	 *
	 * @param mixed $pdf_facturation2
	 * @return
	 */
	function addClientAdresseExpedition($pdf_facturation2)
	{
		$r1 = $this->w - 100;
		$y1 = 45;
		$width = 90;
		$height = 40;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($r1, $y1, $width, $height, 'D');
		$this->Rect($r1, $y1, $width, $header_height, 'DF');

		$this->SetFont("Helvetica", "B", 10);
		$this->SetXY($r1, $y1 + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_LIVRAISON'], 0, 0, "C");

		$this->SetFont("Helvetica", "", 10);
		$this->SetXY($r1, $y1 + $this->cMargin + $header_height);
		$this->MultiCell(90, 4, $pdf_facturation2);
	}
	/**
	 * Affiche un cadre avec le règlement (chèque, etc...)
	 * (en haut, a gauche)
	 *
	 * @param mixed $mode
	 * @return
	 */
	function addReglement($mode)
	{
		$r1 = $this->w - 100;
		$y1 = 29;
		$width = 90;
		$height = 12;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($r1, $y1, $width, $height, 'D');
		$this->Rect($r1, $y1, $width, $header_height, 'DF');

		$this->SetFont("Helvetica", "B", 10);
		$this->SetXY($r1, $y1 + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_PAIEMENT'], 0, 0, "C");

		$this->SetFont("Helvetica", "", 10);
		$this->SetXY($r1, $y1 + $this->cMargin + $header_height);
		$this->Cell(90, 4, $mode, 0, 0, "C");
	}

	/**
	 * Affiche un cadre avec la date d'echeance (en haut, au centre)
	 *
	 * @param mixed $date
	 * @return
	 */
	function addEcheance($date)
	{
		$r1 = 80;
		$r2 = $r1 + 40;
		$y1 = 80;
		$y2 = $y1 + 10;
		$mid = $y1 + (($y2 - $y1) / 2);
		$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
		$this->Line($r1, $mid, $r2, $mid);
		$this->SetXY($r1 + ($r2 - $r1) / 2 - 5 , $y1 + 1);
		$this->SetFont("Helvetica", "B", 10);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_DUE_DATE'], 0, 0, "C");
		$this->SetXY($r1 + ($r2 - $r1) / 2 - 5 , $y1 + 5);
		$this->SetFont("Helvetica", "", 10);
		$this->Cell(10, 5, $date, 0, 0, "C");
	}

	/**
	 * Affiche une ligne avec des references (en haut, a gauche)
	 *
	 * @param mixed $ref
	 * @return
	 */
	function addReference($ref)
	{
		$this->SetFont("Helvetica", "", 10);
		$length = $this->GetStringWidth($GLOBALS['STR_PDF_REF'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . $ref);
		$r1 = 10;
		$r2 = $r1 + $length;
		$y1 = 92;
		$y2 = $y1 + 5;
		$this->SetXY($r1 , $y1);
		$this->Cell($length, 4, $GLOBALS['STR_PDF_REF'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . $ref);
	}

	/**
	 * Trace le cadre des colonnes du devis/facture
	 *
	 * @param mixed $tab
	 * @return
	 */
	function addCols($tab)
	{
		$r1 = 10;
		$r2 = $this->w - ($r1 * 2) ;
		$y1 = 92;
		$y2 = $this->h - 65 - $this->remarque_lignes * 5 - $y1;
		$this->SetXY($r1, $y1);
		$this->Rect($r1, $y1, $r2, $y2, "D");

		$this->SetFillColor(240, 240, 240);
		$this->Rect($r1, $y1, $r2, 5, "DF");

		$colX = $r1;
		$this->colonnes = $tab;
		$this->SetFont("Helvetica", "B", 8);
		while (list($lib, $pos) = each ($tab)) {
			$this->SetXY($colX, $y1 + 1);
			$this->Cell($pos, 1, $lib, 0, 0, "C");
			$colX += $pos;
			$this->Line($colX, $y1, $colX, $y1 + $y2);
		}
	}

	/**
	 * Mémorise le format (gauche, centre, droite) d'une colonne
	 *
	 * @param mixed $tab
	 * @return
	 */
	function addLineFormat($tab)
	{
		while (list($lib, $pos) = each ($this->colonnes)) {
			if (isset($tab["$lib"]))
				$this->format[$lib] = $tab["$lib"];
		}
	}

	/**
	 * Invoice::lineVert()
	 *
	 * @param mixed $tab
	 * @return
	 */
	function lineVert($tab)
	{
		reset($this->colonnes);
		$max_y_reached = 0;
		while (list($lib, $pos) = each ($this->colonnes)) {
			$texte = $tab[ $lib ];
			$longCell = $pos -2;
			$size = $this->sizeOfText($texte, $longCell);
			if ($size > $max_y_reached) {
				$max_y_reached = $size;
			}
		}
		return $max_y_reached;
	}

	/**
	 * Invoice::addLine()
	 *
	 * @param mixed $ligne
	 * @param mixed $tab
	 * @return
	 */
	function addLine($ligne, $tab)
	{
		$x = 10;
		$max_y_reached = $ligne;

		reset($this->colonnes);
		while (list($lib, $pos) = each ($this->colonnes)) {
			$longCell = $pos;
			$texte = $tab[ $lib ];
			$length = $this->GetStringWidth($texte);
			$tailleTexte = $this->sizeOfText($texte, $length);
			$formText = $this->format[ $lib ];
			$this->SetXY($x, $ligne);
			$this->SetFont("Helvetica", "", 8);
			$this->MultiCell($longCell, 3.5, $texte, 0, $formText);
			if ($max_y_reached < ($this->GetY())) {
				$max_y_reached = $this->GetY() ;
			}
			$x += $pos;
		}
		return ($max_y_reached - $ligne);
	}

	/**
	 * Invoice::addTotalHt()
	 *
	 * @param mixed $total_ht
	 * @return
	 */
	function addTotalHt($total_ht)
	{
		$r1 = $this->w - 31;
		$r2 = $r1 + 19;
		$y1 = 100;
		$y2 = $y1;
		$mid = $y1 + ($y2 / 2);
		$this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 3);
		$this->SetFont("Helvetica", "B", 10);
		$this->Cell(10, 5, $GLOBALS['STR_PDF_TOTAL_HT'], 0, 0, "C");
		$this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 9);
		$this->SetFont("Helvetica", "", 10);
		$this->Cell(10, 5, $total_ht . ' HT', 0, 0, "C");
	}

	/**
	 * Ajoute une remarque (en bas, a gauche)
	 *
	 * @param mixed $remarque
	 * @return
	 */
	function addRemarque($remarque)
	{
		$this->SetFont("Helvetica", "", 10);
		$r1 = 10;
		$this->remarque_lignes = $this->sizeOfText($remarque, $this->w - $r1 * 2);
		$y1 = $this->h - 62 - $this->remarque_lignes * 5;
		$y2 = $y1 + 5;
		$this->SetXY($r1 , $y1);
		$this->MultiCell($this->w - $r1 * 2, 4, $remarque . "\n");
	}

	/**
	 * Trace le cadre des TVAs
	 *
	 * @return
	 */
	function addCadreTVAs()
	{
		$this->SetFont("Helvetica", "B", 8);
		$r1 = 10;
		$r2 = $r1 + 20;
		$y1 = $this->h;
		$y2 = $y1 + 5;
		$this->Line($r1, $y1 + 4, $r2, $y1 + 4);
		$this->Line($r1 + 5, $y1 + 4, $r1 + 5, $y2); // avant BASE HT
		$this->Line($r1 + 27, $y1, $r1 + 27, $y2); // avant REMISE
		$this->Line($r1 + 63, $y1, $r1 + 63, $y2); // avant % TVA
		$this->Line($r1 + 75, $y1, $r1 + 75, $y2); // avant PORT
		$this->Line($r1 + 91, $y1, $r1 + 91, $y2); // avant TOTAUX
		$this->SetXY($r1 + 9, $y1);
		$this->Cell(10, 4, $GLOBALS['STR_TOTAL_HT']);
		$this->SetX($r1 + 63);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_BILL_TVA']);
		$this->SetX($r1 + 78);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_BILL_SHIPPING']);
		$this->SetX($r1 + 100);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_BILL_TOTALS']);
		$this->SetFont("Helvetica", "B", 6);
		$this->SetXY($r1 + 93, $y2 - 13);
		$this->Cell(6, 0, $GLOBALS['STR_TTC'] . "   :");
		$this->SetXY($r1 + 93, $y2 - 8);
		$this->Cell(6, 0, $GLOBALS['STR_HT'] . "   :");
		$this->SetXY($r1 + 93, $y2 - 3);
		$this->Cell(6, 0, $GLOBALS['STR_PDF_BILL_TVA'] . " :");
	}

	/**
	 * Invoice::addCadreSignature()
	 *
	 * @return
	 */
	function addCadreSignature()
	{
		$this->SetFont("Helvetica", "B", 10);
		$r1 = 10;
		$r2 = $r1 + 60;
		$y1 = $this->h - 60;
		$y2 = $y1 + 30;
		$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
		$this->Line($r1, $y1 + 6, $r2, $y1 + 6);
		$this->SetXY($r1, $y1 + 1);
		$this->Cell(60, 4, $GLOBALS['STR_ACCORD'], 0, 0, "C");
		$this->SetFont("Helvetica", "B", 7);
		$this->SetXY($r1, $y2 - 21);
		$this->Cell(60, 0, $GLOBALS['STR_PDF_DATE']);
		$this->SetXY($r1, $y2 - 16);
		$this->Cell(60, 0, $GLOBALS['STR_ACCORD_OK']);
		$this->SetXY($r1, $y2 - 11);
		$this->Cell(60, 0, $GLOBALS['STR_PDF_SIGNATURE']);
	}

	/**
	 * trace le cadre des totaux
	 *
	 * @return
	 */
	function addCadreNet()
	{
		$r1 = $this->w - 65;
		$r2 = $r1 + 55;
		$y1 = $this->h - 60;
		$y2 = $y1 + 30;
		$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
	}

	/**
	 * Invoice::addCadreTva()
	 *
	 * @return
	 */
	function addCadreTva()
	{
		$r1 = $this->w - 130;
		$r2 = $r1 + 55;
		$y1 = $this->h - 60;
		$y2 = $y1 + 30;
		$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
	}

	/**
	 * Invoice::addNETs()
	 *
	 * @param array $params1
	 * @return
	 */
	function addNETs($params1)
	{
		$re = $this->w - 65;
		$y1 = $this->h - 55;
		$this->SetFont("Helvetica", "B", 7);
		$k = 0;

		if (abs(get_float_from_user_input($params1["tarif_paiement"])) >= 0.01) {
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_GESTION'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params1['tarif_paiement'], '', '', 'R');
			$k = $k + 3;
		}

		if (is_module_ecotaxe_active()) {
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_ECOTAXE_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params1["total_ecotaxe_ht"], '', '', 'R');
			$k = $k + 3;
		}
		if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_COUT_TRANSPORT_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params1["cout_transport_ht"], '', '', 'R');
			$k = $k + 3;
		}

		if (abs(get_float_from_user_input($params1["small_order_overcost_amount"])) >= 0.01) {
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_SMALL_ORDER_OVERCOST_TEXT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params1["small_order_overcost_amount"], '', '', 'R');
			$k = $k + 3;
		}
		if (!is_micro_entreprise_module_active()) {
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_TOTAL_HT']);
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params1["montant_ht"], '', '', 'R');
		} else {
			addNETs_part_micro($this, $re, $y1 + $k, $params1["montant"]);
		}
		$k = $k + 4;
		if (abs(get_float_from_user_input($params1["avoir"])) >= 0.01) {
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_AVOIR']);
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params1["avoir"], '', '', 'R');
			$k = $k + 3;
		}

		$k = $k + 4;

		$this->SetFont("Helvetica", "B", 8);
		$this->SetXY($re, $y1 + $k);
		$this->Cell(25, 4, $GLOBALS['STR_PDF_NET']);
		$this->SetXY($re + 30, $y1 + $k);
		$this->Cell(25, 4, $params1["montant"], '', '', 'R');
	}

	/**
	 * Invoice::addTVAs()
	 *
	 * @param array $params2
	 * @return
	 */
	function addTVAs($params2)
	{
		$re = $this->w - 130;
		$y1 = $this->h - 55;
		$this->SetFont("Helvetica", "B", 7);
		$k = 0;

		if (is_micro_entreprise_module_active()) {
			addTVAs_part_micro($this, $re, $y1 + $k);
		} else {
			if (!empty($params2["distinct_total_vat"])) {
				foreach($params2["distinct_total_vat"] as $vat_percent => $value) {
					$this->SetXY($re, $y1 + $k);
					$this->Cell(25, 4, $GLOBALS['STR_PDF_TVA'] . ' ' . (String::substr($vat_percent, 0, strlen('transport')) == 'transport'? str_replace('transport', $GLOBALS['STR_PDF_SHIPMENT'], $vat_percent) : $vat_percent) . '%' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . " ");
					$this->SetXY($re + 30, $y1 + $k);
					$this->Cell(25, 4, $value, '', '', 'R');
					$k = $k + 3;
				}
			}
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_TVA'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params2['total_tva'], '', '', 'R');
			$k = $k + 5;
		}
	}

	/**
	 * Permet de rajouter un commentaire (Devis temporaire, REGLE, DUPLICATA, ...) en sous-impression
	 * ATTENTION: APPELER CETTE FONCTION AVANT DE REMPLIR UNE PAGE
	 *
	 * @param string $texte
	 * @param integer $coordx
	 * @param integer $coordy
	 * @return
	 */
	function backgoundBigWatermark($texte, $coordx = null, $coordy = null)
	{
		$this->SetFont('Helvetica', 'B', 50);
		$this->SetTextColor(203, 203, 203);
		$this->Rotate(45, 55, 190);
		$this->Text((!empty($coordx)?$coordx:55), (!empty($coordy)?$coordy:190), $texte);
		$this->Rotate(0);
		$this->SetTextColor(0, 0, 0);
	}

	/**
	 * Cette fonction génère :
	 * - une facture pdf avec $code_facture
	 * - ou une compilation de factures avec ($date_debut, $date_fin) ou ($id_debut et $id_fin) et $user_id.
	 * Le paramètre $file_name defini la direction de sortie du pdf => Il faut mettre FALSE pour l'affichage à l'écran ou le chemin du dossier de stockage pour l'enregistrement.
	 *
	 * @param string $code_facture
	 * @param string $date_debut
	 * @param string $date_fin
	 * @param string $id_debut
	 * @param string $id_fin
	 * @param integer $user_id
	 * @param integer $id_statut_paiement_filter
	 * @param string $bill_mode
	 * @param mixed $file_name
	 * @return
	 */
	function FillDocument($code_facture = null, $date_debut = null, $date_fin = null, $id_debut = null, $id_fin = null, $user_id = null, $id_statut_paiement_filter = null, $bill_mode = 'standard', $file_name = false)
	{
		if (!is_micro_entreprise_module_active()) {
			$column_sizes = array($GLOBALS['STR_PDF_REFERENCE'] => 22,
				$GLOBALS['STR_DESIGNATION'] => 53,
				$GLOBALS['STR_PDF_PRIX_HT'] => 21,
				$GLOBALS['STR_PDF_PRIX_TTC'] => 22,
				$GLOBALS['STR_QUANTITY'] => 14,
				$GLOBALS['STR_PDFTOTALHT'] => 21,
				$GLOBALS['STR_PDFTOTALTTC'] => 23,
				$GLOBALS['STR_TAXE'] => 14);
			$column_formats = array($GLOBALS['STR_PDF_REFERENCE'] => "L",
				$GLOBALS['STR_DESIGNATION'] => "L",
				$GLOBALS['STR_PDF_PRIX_HT'] => "R",
				$GLOBALS['STR_PDF_PRIX_TTC'] => "R",
				$GLOBALS['STR_QUANTITY'] => "C",
				$GLOBALS['STR_PDFTOTALHT'] => "R",
				$GLOBALS['STR_PDFTOTALTTC'] => "R",
				$GLOBALS['STR_TAXE'] => "R");
		} else {
			$column_sizes = define_cols_size('width');
			$column_formats = define_cols_size('alignement');
		}
		$societeInfoText = $this->getSocieteInfoText();
		if (!empty($code_facture)) {
			$sql_cond_array[] = "HEX(code_facture) = HEX('" . nohtml_real_escape_string($code_facture) . "')";
		}
		if (!empty($date_debut)) {
			$sql_cond_array[] = "a_timestamp >= '" . nohtml_real_escape_string($date_debut) . "'";
		}
		if (!empty($date_fin)) {
			$sql_cond_array[] = "a_timestamp <= '" . nohtml_real_escape_string($date_fin) . "'";
		}
		if (!empty($id_fin)) {
			$sql_cond_array[] = "id BETWEEN '" . intval($id_debut) . "' AND '" . intval($id_fin) . "'";
		} elseif (!empty($id_debut)) {
			$sql_cond_array[] = "id>='" . intval($id_debut) . "'";
		}
		if (!empty($user_id)) {
			$sql_cond_array[] = "id_utilisateur = '" . intval($user_id) . "'";
		}
		if (is_numeric($id_statut_paiement_filter)) {
			$sql_cond_array[] = "id_statut_paiement = '" . intval($id_statut_paiement_filter) . "'";
		}
		if (empty($sql_cond_array)) {
			return null;
		}
		$sql = "SELECT *
			FROM peel_commandes
			WHERE " . implode(' AND ', $sql_cond_array) . '
			ORDER BY o_timestamp ASC';
		$qid_commande = query($sql);
		$i = 0;
		while ($commande = fetch_object($qid_commande)) {
			$_SESSION['session_last_bill_viewed'] = $commande->id;
			$societeLogoPath = $this->getSocieteLogoPath($commande->lang);
			unset($y);
			$order_infos = get_order_infos_array($commande);
			if (empty($i)) {
				$this->Open();
				$this->cMargin = 2;
				$this->SetAutoPageBreak(false, 10);
				$this->AliasNbPages('{nb}');
			}
			$this->startPageGroup();
			$next_product_max_size_forecasted = 20;
			$product_infos_array = get_product_infos_array_in_order($commande->id, $commande->devise, $commande->currency_rate);
			if (empty($product_infos_array)) {
				// On rajoute un élément pour pouvoir passer dans la génération de page
				$product_infos_array[] = false;
			}
			foreach ($product_infos_array as $this_ordered_product) {
				if (empty($y) || $y + $next_product_max_size_forecasted > $this->h - 55 - 5 * vn($this->remarque_lignes)) {
					// Nécessité de créer une nouvelle page car on ne va plus avoir de place
					$next_product_max_size_forecasted = (20 + $next_product_max_size_forecasted) / 2;
					$this->AddPage();
					$this->addPageNumber($this->getGroupPageNo() . ' / ' . $this->getPageGroupAlias());
					$this->addSociete($societeInfoText, $societeLogoPath);
					if ($bill_mode == "bdc") {
						$this->fact_dev(String::strtoupper($GLOBALS['STR_ORDER_FORM']) . " ", intval($commande->id));
						$this->backgoundBigWatermark($GLOBALS['STR_ORDER_FORM'], 25, 190);
					} elseif ($bill_mode == "proforma") {
						$this->fact_dev(String::strtoupper($GLOBALS['STR_PROFORMA']) . " ", intval($commande->id));
						// À décommenter pour afficher le filigrane
						// $this->backgoundBigWatermark($GLOBALS['STR_PROFORMA'], 40, 190);
					} elseif ($bill_mode == "devis") {
						$this->fact_dev(String::strtoupper($GLOBALS['STR_PDF_QUOTATION']) . " ", intval($commande->id));
						$this->backgoundBigWatermark($GLOBALS['STR_PDF_QUOTATION'], 80, 200);
					} else {
						$this->fact_dev(String::strtoupper($GLOBALS['STR_INVOICE']), $commande->numero);
						// À décommenter pour afficher le filigrane
						// $this->backgoundBigWatermark($GLOBALS['STR_INVOICE'], 80, 200);
					}
					$this->addDate(get_formatted_date($commande->o_timestamp, 'short', 'long'), $order_infos['displayed_paiement_date']);
					$this->addReglement(String::str_shorten(get_payment_name($commande->paiement), 30) . ' - ' . $commande->devise);

					$this->addClientAdresseFacturation($order_infos['client_infos_bill'], $commande->id_utilisateur);
					if (!empty($GLOBALS['site_parameters']['mode_transport']) && !empty($order_infos['client_infos_ship'])) {
						// Ajout de l'adresse de livraison seulement si la boutique a une gestion du port
						$this->addClientAdresseExpedition($order_infos['client_infos_ship']);
					}
					$comments = array();
					if(!empty($order_infos['delivery_infos'])) {
						$comments[] = $GLOBALS["STR_SHIPPING_TYPE"] . $GLOBALS["STR_BEFORE_TWO_POINTS"]. ': ' . $order_infos['delivery_infos'];
					}
					if(!empty($commande->commentaires)) {
						$comments[] = $commande->commentaires;
					}
					if(!empty($comments)) {
						$this->addRemarque(implode("\n", $comments));
					}
					$this->addCols($column_sizes);
					// Alignement du contenu des cellules de chaque ligne
					$this->addLineFormat($column_formats);
					// Initialisation du début de l'affichage des produits
					$y = 100;
				}
				if (!empty($this_ordered_product)) {
					$prix = fprix($this_ordered_product["prix"], true, $commande->devise, true, $commande->currency_rate);
					$prix_ht = fprix($this_ordered_product["prix_ht"], true, $commande->devise, true, $commande->currency_rate);
					$total_prix_ht = fprix($this_ordered_product["total_prix_ht"], true, $commande->devise, true, $commande->currency_rate);
					$total_prix = fprix($this_ordered_product["total_prix"], true, $commande->devise, true, $commande->currency_rate);
					$product_text = filtre_pdf($this_ordered_product["product_text"]);
					if (!is_micro_entreprise_module_active()) {
						$line = array($GLOBALS['STR_PDF_REFERENCE'] => $this_ordered_product["reference"],
							$GLOBALS['STR_DESIGNATION'] => $product_text,
							$GLOBALS['STR_PDF_PRIX_HT'] => $prix_ht,
							$GLOBALS['STR_PDF_PRIX_TTC'] => $prix,
							$GLOBALS['STR_QUANTITY'] => $this_ordered_product["quantite"],
							$GLOBALS['STR_PDFTOTALHT'] => $total_prix_ht,
							$GLOBALS['STR_PDFTOTALTTC'] => $total_prix,
							$GLOBALS['STR_TAXE'] => "" . number_format($this_ordered_product['tva_percent'], 1) . "%");
					} else {
						$line = array($GLOBALS['STR_PDF_REFERENCE'] => $this_ordered_product["reference"],
							$GLOBALS['STR_DESIGNATION'] => $product_text,
							$GLOBALS['STR_PDF_PRIX_TTC'] => $prix,
							$GLOBALS['STR_QUANTITY'] => $this_ordered_product["quantite"],
							$GLOBALS['STR_PDFTOTALTTC'] => $total_prix);
					}

					$size = $this->addLine($y, $line);
					$next_product_max_size_forecasted = max($next_product_max_size_forecasted, min(60, $size));
					$y += $size + 4;
				}
			}
			if (!empty($order_infos['code_promo_text'])) {
				foreach($line as $this_key => $this_item) {
					$line[$this_key] = '';
				}
				$line[$GLOBALS['STR_DESIGNATION']] = $order_infos['code_promo_text'];
				$size = $this->addLine($y, $line);
				$y += $size + 4;
			}
			if ($bill_mode == "bdc") {
				$this->addCadreSignature();
			}
			$this->addCadreNet();
			$this->addNETs($order_infos['net_infos_array']);
			$this->addCadreTva();
			$this->addTVAs($order_infos['tva_infos_array']);
			$this->addInfoTVA($commande->total_tva, $bill_mode);
			$i++;
		}
		if (!empty($i)) {
			$this->lastPage();
			if ($file_name === false) {
				$this->Output();
			} else {
				$this->Output($file_name, "F");
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * getSocieteInfoText()
	 *
	 * @return string
	 */
	function getSocieteInfoText()
	{
		$qid = query("SELECT * FROM peel_societe");
		if ($ligne = fetch_object($qid)) {
			$pdf_societe = filtre_pdf($ligne->societe) . "\n" ;
			$pdf_adresse = filtre_pdf($ligne->adresse) . "\n" ;
			$pdf_codepostal = filtre_pdf($ligne->code_postal);
			$pdf_ville = filtre_pdf($ligne->ville);
			$pdf_pays = filtre_pdf($ligne->pays) . "\n" ;
			if (!empty($ligne->tel)) {
				$pdf_tel = $GLOBALS['STR_SHORT_TEL'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . filtre_pdf($ligne->tel) . "\n";
			} else {
				$pdf_tel = "" ;
			}
			$pdf_fax = $ligne->fax ;
			if (!empty($ligne->siren)) {
				$pdf_siret = $GLOBALS['STR_PDF_RCS'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . filtre_pdf($ligne->siren) . "\n";
			} else {
				$pdf_siret = "" ;
			}
			if (!empty($ligne->tvaintra)) {
				$pdf_tvaintra_company = $GLOBALS['STR_VAT_INTRACOM'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . filtre_pdf($ligne->tvaintra) . "\n";
			} else {
				$pdf_tvaintra_company = "" ;
			}
			$pdf_siteweb = filtre_pdf($ligne->siteweb);
			if (file_exists($GLOBALS['dirroot'] . '/factures/logo.jpg')) {
				$pdf_logo = $GLOBALS['dirroot'] . '/factures/logo.jpg';
			} else {
				$pdf_logo = $ligne->logo ;
			}
			return $pdf_societe . $pdf_adresse . $pdf_codepostal . " " . $pdf_ville . " - " . $pdf_pays . $pdf_siret . $pdf_tvaintra_company . $pdf_tel . $pdf_siteweb . "\n";
		} else {
			return null;
		}
	}

	/**
	 * getSocieteLogoPath()
	 *
	 * @return string
	 */
	function getSocieteLogoPath($lang = null)
	{
		$pdf_logo = '';
		if (!empty($lang)) {
			if (!empty($GLOBALS['site_parameters']['logo_'.$lang])) {
				// on découpe le contenu du champs à la recherche du non de l'image fixe
				// ceci évitera d'envoyer la transmition du logo avec un chemin en http::// (qui n'est pas pris en compte)
				$pdf_logo = str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $GLOBALS['site_parameters']['logo_'.$lang]);
				if (!empty($pdf_logo) && file_exists($GLOBALS['dirroot'] . '/' . $pdf_logo)) {
					// si le logo renseigné n'existe pas, on ne retourne pas d'information
					$pdf_logo = $GLOBALS['dirroot'] . '/' . $pdf_logo;
				} elseif (!empty($pdf_logo) && file_exists($GLOBALS['dirroot'] . '/images/' . $pdf_logo)) {
					// si le logo renseigné n'existe pas, on ne retourne pas d'information
					$pdf_logo = $GLOBALS['dirroot'] . '/images/' . $pdf_logo;
				} elseif (empty($pdf_logo) || !file_exists($pdf_logo)) {
					// si le logo renseigné n'existe pas, on ne retourne pas d'information
					$pdf_logo = false;
				}
			}
		} elseif (file_exists($GLOBALS['dirroot'] . '/factures/logo.jpg')) {
			$pdf_logo = $GLOBALS['dirroot'] . '/factures/logo.jpg';
		} else {
			$pdf_logo = false;
		}
		return $pdf_logo;
	}
}

?>