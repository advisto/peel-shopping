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
// $Id: Invoice.php 55332 2017-12-01 10:44:06Z sdelaporte $
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
 * @version $Id: Invoice.php 55332 2017-12-01 10:44:06Z sdelaporte $
 * @access public
 */
class Invoice extends TCPDF {
	var $colonnes;
	var $format;
	var $angle = 0;
	var $remarque_lignes;
	var $remarque_font_size = 8;
	var $document_name;
	var $document_id;

	/**
	 * PRIVATE FUNCTION : Invoice::InvoiceRoundedRect()
	 *
	 * @param mixed $x
	 * @param mixed $y
	 * @param mixed $w
	 * @param mixed $h
	 * @param mixed $r
	 * @param string $style
	 * @return
	 */
	function InvoiceRoundedRect($x, $y, $w, $h, $r, $style = '')
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
	 * @param integer $font_size
	 * @return
	 */
	function sizeOfText($texte, $largeur, $font_size = null)
	{
		$index = 0;
		$nb_lines = 0;
		$loop = true;
		while ($loop) {
			$pos = StringMb::strpos($texte, "\n");
			if ($pos === false) {
				$loop = false;
				$ligne = $texte;
			} else {
				$ligne = StringMb::substr($texte, $index, $pos);
				$texte = StringMb::substr($texte, $pos + 1);
			}
			$length = floor($this->GetStringWidth($ligne, null, null, $font_size));
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
	 * Cette fonction affiche en haut à gauche le nom de la societe dans la police freesans-12-Bold
	 * les coordonnées de la société dans la police freesans-10
	 *
	 * @param mixed $adresse
	 * @param mixed $logo
	 * @return
	 */
	function addSociete($adresse, $logo, $bill_mode)
	{
		if ($bill_mode == 'user_custom_products_list') {
			$x1 = 90;
			$y1 = 3;
		} else {
			$x1 = 10;
			$y1 = 6;
		}
		if (!empty($logo) && empty($GLOBALS['site_parameters']['invoice_pdf_logo_display_disable'])) {
			if (StringMb::strpos($logo, '://') !== false) {
				// Le fichier est hébergé sur un autre serveur que celui-ci, sinon la fonction getSocieteLogoPath aurait changé le lien URL en chemin serveur
				$logo = thumbs($logo, 125, 80, 'fit', null, null, true, true);
				$this->Image($logo, $x1 + vb($GLOBALS['site_parameters']['logo_pdf_locationX'], 45), $y1 + vb($GLOBALS['site_parameters']['logo_pdf_locationY'], 0));
			} else {
				$destinationW = vb($GLOBALS['site_parameters']['logo_pdf_destinationW'], 35); // Espace max disponible en largeur pour le logo
				$destinationH = vb($GLOBALS['site_parameters']['logo_pdf_destinationH'], 35); // Espace max disponible en hauteur pour le logo
				$imgInfo = @getimagesize($logo);
				$sourceW = $imgInfo[0];
				$sourceH = $imgInfo[1];
				if (!empty($sourceW) && !empty($sourceH)) {
					// on met au même format que celui de la taille demandée
					if ($sourceH * $destinationW > $destinationH * $sourceW) {
						$destinationW = ($sourceW * $destinationH) / $sourceH;
					} else {
						$destinationH = ($sourceH * $destinationW) / $sourceW;
					}
				}
				// Positionnement du logo à droite des informations sur la société
				$this->Image($logo, $x1 + vb($GLOBALS['site_parameters']['logo_pdf_locationX'], 50), $y1 + vb($GLOBALS['site_parameters']['logo_pdf_locationY'], 0), $destinationW, $destinationH);
			}
		}
		if ($bill_mode != 'user_custom_products_list') {
			$this->SetXY($x1, $y1);
			// $this->SetFont('freesans', 'B', 12);
			// $length = $this->GetStringWidth( $nom );
			// $this->Cell( $length, 2, $nom);
			$this->SetFont('freesans', '', 10);
			$length = $this->GetStringWidth($adresse);
			// Coordonnées de la société
			$this->MultiCell($length, 4, $adresse);
		}
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
	function fact_dev($libelle, $num, $change_background_color_by_type = false, $bill_mode = null)
	{
		if ($bill_mode == 'user_custom_products_list') {
			$y1 = 25;
			$r1 = $this->w - 100;
			$r2 = $r1 + 90;
			$y2 = 9;
		} else {
			$y1 = 6;
			$r1 = $this->w - 100;
			$r2 = $r1 + 90;
			$y2 = $y1 + 2;
		}
		$mid = ($r1 + $r2) / 2;

		$texte = $libelle . " N° : " . $num;
		$szfont = 12;
		$loop = 0;

		while ($loop == 0) {
			$this->SetFont("freesans", "B", $szfont);
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
		$this->InvoiceRoundedRect($r1, $y1, ($r2 - $r1), $y2, 2.5, 'DF');
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
	function addDate($date, $date_a, $bill_mode)
	{
		if ($bill_mode == 'user_custom_products_list') {
			$r1 = $this->w - 100;
			$y1 = 38;
		} else {
			$r1 = $this->w - 100;
			$y1 = 17;
		}
		$width = 90;
		$height = 12;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($r1, $y1, $width, $height, 'D');
		$this->Rect($r1, $y1, $width, $header_height, 'DF');

		$this->SetFont("freesans", "B", 10);
		$this->SetXY($r1, $y1 + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_BILL_DATE'], 0, 0, "C");

		$this->SetFont("freesans", "", 10);
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
	 * @param integer $id_utilisateur
	 * @return
	 */
	function addInfoTVA($tva, $mode = null, $id_utilisateur = null)
	{
		$r1 = $this->w / 2 - 15;
		$y1 = $this->h-25;
		
		$text1 = ($mode != 'devis'?$GLOBALS['STR_INVOICE_BOTTOM_TEXT']:$GLOBALS['STR_INVOICE_BOTTOM_TEXT1']);
		$this->SetXY($r1, $y1);
		$this->SetFont("freesans", "", 8);
		$this->Cell(30, 4, $text1 ,0, 0, "C");
		if (floatval($tva)==0) {
			if (check_if_module_active('micro_entreprise')) {
				// Pour les entreprises bénéficiant du régime de franchise de base de TVA, il faut obligatoirement porter sur chaque facture la mention suivante : « TVA non applicable, article 293 B du CGI ».
				// => Déjà géré par l'appel à addTVAs_part_micro
			} elseif(is_user_tva_intracom_for_no_vat($id_utilisateur)) {
				// Pour les livraisons de biens intracommunautaires, les factures doivent obligatoirement comporter la mention suivante : « Exonération de TVA, article 262 ter 1 du CGI ».
				// Lorsqu'il s'agit de prestations de services intracommunautaires dont la taxe est autoliquidée par le preneur, il faudra faire figurer, à notre sens, les mentions « TVA due par le preneur, art. CGI 283-2, et art. 194 de la directive TVA 2006/112/CE »
				// => Texte à définir en conséquence en fonction de votre site dans $GLOBALS['STR_INVOICE_BOTTOM_TEXT2']
				$text2 = $GLOBALS['STR_INVOICE_BOTTOM_TEXT2'];
				$this->SetXY($r1, $y1+4);
				$this->Cell(30, 4, $text2, 0, 0, "C");
			}
		}
	}

	/**
	 * Affiche un cadre avec un numéro de page (en haut, a droite)
	 *
	 * @param mixed $page
	 * @param integer $font_size
	 * @return
	 */
	function addPageNumber($page, $font_size = 8)
	{
		$r1 = $this->w / 2 - 15;
		$y1 = $this->h - 12;

		$this->SetXY($r1, $y1);
		$this->SetFont("helvetica", "", $font_size);
		$this->Cell(30, 4, $GLOBALS['STR_PDF_BILL_PAGE'] . ' ' . $page, 0, 0, "C");
	}

	/**
	 * Affiche l'adresse du client (en haut, a droite)
	 *
	 * @param mixed $pdf_facturation
	 * @param integer $id_utilisateur
	 * @return
	 */
	function addClientAdresseFacturation($pdf_facturation, $id_utilisateur, $bill_mode)
	{
		if ($bill_mode == 'user_custom_products_list') {
			$r1 = $this->w - 200;
			$y1 = 6;
		} else {
			$r1 = $this->w - 200;
			$y1 = 40;
		}
		$width = 90;
		$height = 45;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($r1, $y1, $width, $height, 'D');
		$this->Rect($r1, $y1, $width, $header_height, 'DF');

		$this->SetFont("freesans", "B", 10);
		$this->SetXY($r1, $y1 + 0.5);
		$title = $GLOBALS['STR_PDF_FACTURATION'];
		if (!empty($id_utilisateur)) {
			$title .= ' ' . $id_utilisateur;
		}
		$this->Cell(90, 4, $GLOBALS['STR_PDF_FACTURATION'], 0, 0, "C");

		$this->SetFont("freesans", "", 10);
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

		$this->SetFont("freesans", "B", 10);
		$this->SetXY($r1, $y1 + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_LIVRAISON'], 0, 0, "C");

		$this->SetFont("freesans", "", 10);
		$this->SetXY($r1, $y1 + $this->cMargin + $header_height);
		$this->MultiCell(90, 4, $pdf_facturation2 . "\n");
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

		$this->SetFont("freesans", "B", 10);
		$this->SetXY($r1, $y1 + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_PAIEMENT'], 0, 0, "C");

		$this->SetFont("freesans", "", 10);
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
		$this->InvoiceRoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
		$this->Line($r1, $mid, $r2, $mid);
		$this->SetXY($r1 + ($r2 - $r1) / 2 - 5 , $y1 + 1);
		$this->SetFont("freesans", "B", 10);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_DUE_DATE'], 0, 0, "C");
		$this->SetXY($r1 + ($r2 - $r1) / 2 - 5 , $y1 + 5);
		$this->SetFont("freesans", "", 10);
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
		$this->SetFont("freesans", "", 10);
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
	 * @param integer $y_max_allowed
	 * @param string $bill_mode
	 * @param integer $y1
	 * @param integer $font_size
	 * @return
	 */
	function addCols($y_max_allowed, $bill_mode=null, $y1 = 92, $font_size = 8)
	{

		$r1 = 10;
		$r2 = $this->w - ($r1 * 2) ;
		if($bill_mode == 'user_custom_products_list') {
			$y1 = 60;
			$height = 8;
		} else {
			$height = 5;
		}
		//$y2 = $this->h - 27 - $this->remarque_lignes * 3.5 * ($this->remarque_font_size/8);
		$y2 = $y_max_allowed - 10 - $y1;
		$this->SetXY($r1, $y1);
		$this->Rect($r1, $y1, $r2, $y2, "D");

		$this->SetFillColor(240, 240, 240);
		$this->Rect($r1, $y1, $r2, $height, "DF");

		$colX = $r1;
		$this->SetFont("freesans", "B", $font_size);
		if(!empty($this->colonnes)) {
			foreach($this->colonnes as $lib => $pos) {
				$this->SetXY($colX, $y1 + 1);
				if($bill_mode != 'user_custom_products_list') {
					$this->Cell($pos, 1, $lib, 0, 0, "C");
				} else {
					$this->MultiCell($pos, $height-1, $lib, 0, 0, "C");
				}
				$colX += $pos;
				if($bill_mode != 'user_custom_products_list') {
					// pour l'affichage des listes de produits, les lignes sont horizontales (cf addLine)
					$this->Line($colX, $y1, $colX, $y1 + $y2);
				}
			}
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
		if(!empty($this->colonnes)) {
			foreach($this->colonnes as $lib => $pos) {
				if (isset($tab["$lib"])) {
					$this->format[$lib] = $tab["$lib"];
				}
			}
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
		$max_y_reached = 0;
		if(!empty($this->colonnes)) {
			foreach($this->colonnes as $lib => $pos) {
				$texte = $tab[ $lib ];
				$longCell = $pos -2;
				$size = $this->sizeOfText($texte, $longCell);
				if ($size > $max_y_reached) {
					$max_y_reached = $size;
				}
			}
		}
		return $max_y_reached;
	}

	/**
	 * Invoice::addLine()
	 *
	 * @param mixed $ligne
	 * @param mixed $tab
	 * @param string $bill_mode
	 * @param boolean $fill
	 * @param integer $font_size
	 * @param float $line_height
	 * @return
	 */
	function addLine($ligne, $tab, $bill_mode = null, $fill = false, $font_size = 8, $line_height = 3.5)
	{
		$x = 10;
		$max_y_reached = $ligne;
		if($bill_mode == 'user_custom_products_list') {
			$this->SetFont("freesans", "", 7);
		} else {
			$this->SetFont("freesans", "", $font_size);
		}
		if($bill_mode == 'user_custom_products_list') {
			$ligne = $ligne-3;
		}
		if(!empty($this->colonnes)) {
			foreach($this->colonnes as $lib => $pos) {
				$longCell = $pos;
				$texte = $tab[ $lib ];
				$length = $this->GetStringWidth($texte);
				$formText = $this->format[ $lib ];
				$this->SetXY($x, $ligne);
				$this->MultiCell($longCell, $line_height, $texte, 0, $formText, $fill);
				if ($max_y_reached < ($this->GetY())) {
					$max_y_reached = $this->GetY() ;
				}
				$x += $pos;
			}
			if($bill_mode == 'user_custom_products_list') {
				$y = $ligne-4;
				// pour l'affichage des listes de produits, les lignes de séparations tracées sont horizontales. La séparation s'affiche au dessus des lignes.
				$this->Line(10, $y, $x, $y);
			}
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
		$this->SetFont("freesans", "B", 10);
		$this->Cell(10, 5, $GLOBALS['STR_PDF_TOTAL_HT'], 0, 0, "C");
		$this->SetXY($r1 + ($r2 - $r1) / 2 - 5, $y1 + 9);
		$this->SetFont("freesans", "", 10);
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
		$this->SetFont("freesans", "", $this->remarque_font_size);
		$r1 = 10;
		$r2 = $this->w - ($r1 * 2) ;
		$y2 = $this->remarque_lignes * 3.5 * ($this->remarque_font_size/8);
		$y1 = $this->h - 62 - $y2 - 5;
		$this->SetXY($r1 , $y1);
		if (!empty($GLOBALS['site_parameters']['bill_pdf_add_color_behind_comments'])) {
			// On dessine un cadre coloré
			$this->SetFillColor(240, 240, 240);
			$this->Rect($r1, $y1-1, $r2, $y2+1, "DF");
		}
		// On écrit le texte de commentaire
		$this->MultiCell($this->w - $r1 * 2, 4, $remarque . "\n");
	}

	/**
	 * Trace le cadre des TVAs
	 *
	 * @return
	 */
	function addCadreTVAs()
	{
		$this->SetFont("freesans", "B", 8);
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
		$this->SetFont("freesans", "B", 6);
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
		$this->SetFont("freesans", "B", 10);
		$r1 = 10;
		$r2 = $r1 + 60;
		$y1 = $this->h - 60;
		$y2 = $y1 + 30;
		$this->InvoiceRoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
		$this->Line($r1, $y1 + 6, $r2, $y1 + 6);
		$this->SetXY($r1, $y1 + 1);
		$this->Cell(60, 4, $GLOBALS['STR_ACCORD'], 0, 0, "C");
		$this->SetFont("freesans", "B", 7);
		$this->SetXY($r1, $y2 - 21);
		$this->Cell(60, 0, $GLOBALS['STR_PDF_DATE']);
		$this->SetXY($r1, $y2 - 16);
		$this->Cell(60, 0, $GLOBALS['STR_ACCORD_OK']);
		$this->SetXY($r1, $y2 - 11);
		$this->Cell(60, 0, $GLOBALS['STR_PDF_SIGNATURE']);
	}

	/**
	 * Invoice::addCadreRib()
	 *
	 * @return
	 */
	function addCadreRib()
	{
		$this->SetFont("freesans", "B", 10);
		$r1 = 10;
		$r2 = $r1 + 65;
		$y1 = $this->h - 60;
		$y2 = $y1 + 35;
		$this->InvoiceRoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
		$this->Line($r1, $y1 + 6, $r2, $y1 + 6);
		$this->SetXY($r1, $y1 + 1);
		$this->Cell(60, 4, $GLOBALS['STR_TRANSFER'], 0, 0, "C");
		
		$sql="SELECT code_banque, code_guichet, numero_compte, cle_rib, iban, swift
			FROM peel_societe
			WHERE " . get_filter_site_cond('societe') . " AND id_marques = 0
			ORDER BY site_id DESC
			LIMIT 1";
		$query = query($sql);
		$result = fetch_assoc($query);
		
		$this->SetXY($r1, $y1+6);
		$this->SetFont('freesans', '', 10);
		$rib = $GLOBALS['STR_BANK_ACCOUNT_CODE'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . $result['code_banque'] . "\r\n" . $GLOBALS['STR_BANK_ACCOUNT_COUNTER'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . $result['code_guichet'] . "\r\n" . $GLOBALS['STR_BANK_ACCOUNT_NUMBER'].$GLOBALS['STR_BEFORE_TWO_POINTS'] .  ':'.$result['numero_compte'] . "\r\n" . $GLOBALS['STR_BANK_ACCOUNT_RIB_KEY'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ':'.$result['cle_rib'] . "\r\n" .  $GLOBALS['STR_IBAN'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ':'.$result['iban'] . "\r\n" . $GLOBALS['STR_SWIFT'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ':'.$result['swift']; 
		// $length = $this->GetStringWidth($rib);
		$this->MultiCell(66, 7, $rib, 0, "L");
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
		$this->InvoiceRoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
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
		$this->InvoiceRoundedRect($r1, $y1, ($r2 - $r1), ($y2 - $y1), 2.5, 'D');
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
		$this->SetFont("freesans", "B", 7);
		$k = 0;

		if (abs(get_float_from_user_input($params1["tarif_paiement"])) >= 0.01) {
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_GESTION'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params1['tarif_paiement'], '', '', 'R');
			$k = $k + 3;
		}

		if (check_if_module_active('ecotaxe')) {
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
		if (!check_if_module_active('micro_entreprise')) {
			$this->SetXY($re, $y1 + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_TOTAL_HT']);
			$this->SetXY($re + 30, $y1 + $k);
			$this->Cell(25, 4, $params1["montant_ht"], '', '', 'R');
		} else {
			addNETs_part_micro($this, $re, $y1 + $k, $params1["totalttc"]);
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

		$this->SetFont("freesans", "B", 8);
		$this->SetXY($re, $y1 + $k);
		$this->Cell(25, 4, StringMb::strtoupper($GLOBALS['STR_PDF_NET']) . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
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
		$this->SetFont("freesans", "B", 7);
		$k = 0;

		if (check_if_module_active('micro_entreprise')) {
			addTVAs_part_micro($this, $re, $y1 + $k);
		} else {
			if (!empty($params2["distinct_total_vat"])) {
				foreach($params2["distinct_total_vat"] as $vat_percent => $value) {
					$this->SetXY($re, $y1 + $k);
					$this->Cell(25, 4, $GLOBALS['STR_PDF_TVA'] . ' ' . (StringMb::substr($vat_percent, 0, strlen('transport')) == 'transport'? str_replace('transport', $GLOBALS['STR_PDF_SHIPMENT'], $vat_percent) : $vat_percent) . '%' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . " ");
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
		$this->SetFont('freesans', 'B', 50);
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
	 *
	 * @param string $code_facture
	 * @param string $date_debut
	 * @param string $date_fin
	 * @param string $id_debut
	 * @param string $id_fin
	 * @param integer $user_id
	 * @param integer $id_statut_paiement_filter
	 * @param string $bill_mode
	 * @param mixed $folder Définit la direction de sortie du pdf => Il faut mettre false pour l'affichage à l'écran ou le chemin du dossier de stockage pour l'enregistrement.
	 * @return
	 */
	function FillDocument($code_facture = null, $date_debut = null, $date_fin = null, $id_debut = null, $id_fin = null, $user_id = null, $id_statut_paiement_filter = null, $bill_mode = 'standard', $folder = false, $order_object=null, $product_infos_array = null, $order_array = null, $document_title = null, $ids_array=null)
	{
		$hook_result = call_module_hook('bill_get_configuration_array', array('bill_mode' => $bill_mode), 'array');
		if(count($hook_result)) {
			// Par exemple le module micro_-_entreprise définit le format des factures ici
			$this->colonnes = $hook_result['width'];
			$column_formats = $hook_result['alignement'];
		} else {
			if ($bill_mode!='user_custom_products_list') {
				$this->colonnes = array($GLOBALS['STR_PDF_REFERENCE'] => 22,
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
				$this->colonnes = array($GLOBALS['STR_PHOTO'] => 25,
					$GLOBALS['STR_DESIGNATION'] => 35,
					$GLOBALS['STR_EAN_CODE'] => 62,
					$GLOBALS['STR_BRAND'] => 16,
					$GLOBALS['STR_CATEGORY'] => 20,
					$GLOBALS['STR_QUANTITY_SHORT'] => 7,
					$GLOBALS['STR_PDF_PRIX_TTC'] => 12,
					$GLOBALS['STR_START_PRICE'].' '.$GLOBALS['STR_TTC'] => 13);
				$column_formats = array($GLOBALS['STR_PHOTO'] => "C",
					$GLOBALS['STR_DESIGNATION'] => "L",
					$GLOBALS['STR_EAN_CODE'] => "C",
					$GLOBALS['STR_BRAND'] => "C",
					$GLOBALS['STR_CATEGORY'] => "L",
					$GLOBALS['STR_QUANTITY_SHORT'] => "R",
					$GLOBALS['STR_PDF_PRIX_TTC'] => "R",
					$GLOBALS['STR_START_PRICE'].' '.$GLOBALS['STR_TTC'] => "R");
			}
		}
		$i = 0;
		$file_name = '';
		if ($bill_mode != "user_custom_products_list") {
			if (!empty($product_infos_array) || !empty($order_object)) {
				// Dans un mode de commande standard, order_object et product_infos_array ne doivent pas être défini, ce n'est pas cohérent. order_object et product_infos_array sont défini uniquement si bill_mode = user_custom_products_list
				return null;
			}
			if (!empty($code_facture)) {
				// La collation sur la colonne code_facture est fixée à utf8_bin et non plus utf8_general, donc on peut faire une comparaison avec = qui va utiliser l'INDEX plutôt que de passer par HEX(code_facture) = HEX('" . nohtml_real_escape_string($code_facture) . "')
				$sql_cond_array[] = "c.code_facture = '" . nohtml_real_escape_string($code_facture) . "'";
			}
			if (!empty($date_debut)) {
				$sql_cond_array[] = "c.a_timestamp >= '" . nohtml_real_escape_string($date_debut) . "'";
			}
			if (!empty($date_fin)) {
				$sql_cond_array[] = "c.a_timestamp <= '" . nohtml_real_escape_string($date_fin) . "'";
			}
			if (!empty($id_fin)) {
				$sql_cond_array[] = "c.id BETWEEN '" . intval($id_debut) . "' AND '" . intval($id_fin) . "'";
			} elseif (!empty($id_debut)) {
				$sql_cond_array[] = "c.id>='" . intval($id_debut) . "'";
			}
			if (!empty($user_id)) {
				$sql_cond_array[] = "c.id_utilisateur = '" . intval($user_id) . "'";
			}
			if (is_numeric($id_statut_paiement_filter)) {
				$sql_cond_array[] = "c.id_statut_paiement = '" . intval($id_statut_paiement_filter) . "'";
			}
			if ($bill_mode == 'standard' || $bill_mode == 'facture') {
				// Un numéro doit être obligatoirement renseigné dans la facture
				$sql_cond_array[] = "c.numero != ''";
			}
			if (is_array($ids_array) && !empty($ids_array)) {
				$sql_cond_array[] = "c.id IN (" . implode(',', $ids_array) . ")";
			}
			if (empty($sql_cond_array)) {
				return null;
			}
			$sql_bills = "SELECT c.*, sp.technical_code AS statut_paiement
				FROM peel_commandes c
				LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
				WHERE " . implode(' AND ', $sql_cond_array) . ' AND ' . get_filter_site_cond('commandes', 'c') . "
				ORDER BY c.o_timestamp ASC";
			$query = query($sql_bills);
			while ($order_object = fetch_object($query)) {
				$product_infos_array = get_product_infos_array_in_order($order_object->id, $order_object->devise, $order_object->currency_rate, null, false, vb($GLOBALS['site_parameters'][$bill_mode.'_product_excluded'], array()));
				$this->generatePdfOrderContent($column_formats, $bill_mode, $i, $order_object, $product_infos_array);				
				if(empty($file_name)) {
					$file_name = $this->document_name . '_' . $this->document_id;
					if(!empty($commande->f_datetime) && substr($commande->f_datetime, 0, 10) != '0000-00-00') {
						$file_name .= '_' . get_formatted_date($commande->f_datetime);
					}
					$file_name .= '.pdf';
				} else {
					// Plusieurs factures
					$file_name = 'F-' . md5($sql_bills) . '.pdf';
				}
				$i++;
			}
		} else {
			// Affichage d'une liste de produit dans un document PDF sans que les produits ne soient associés à une commande
			$this->generatePdfOrderContent($column_formats, $bill_mode, 0, $order_object, $product_infos_array, $document_title);
			$file_name = $GLOBALS['STR_LIST_PRODUCT'] . ' ' . vb($order_object->nom_ship) . '.pdf';
			$i++;
		}
		if (!empty($i)) {
			$this->lastPage();
			$file_name = StringMb::convert_accents(str_replace(array('/', ' '), '-', $file_name));
			if ($folder === false) {
				$this->Output($file_name);
			} else {
				$this->Output($folder . $file_name, "F");
			}
			return $file_name;
		} else {
			return false;
		}
	}
	
	/**
	 * generatePdfOrderContent()
	 *
	 * @return string
	 */
	function generatePdfOrderContent($column_formats, $bill_mode, $i, &$order_object, &$product_infos_array, $document_title = null) {
		if (!empty($order_object->id)) {
			$_SESSION['session_last_bill_viewed'] = vn($order_object->id);
		}

		$order_infos = get_order_infos_array($order_object, $product_infos_array, $bill_mode);
		$societeLogoPath = $this->getSocieteLogoPath($order_object->lang);
		if (function_exists('get_order_site_id')) {
			// On regarde si la commande est une commande lié à une demande de fincancement. Dans ce cas la commande prends le site_id du site funding, en remplacement du site_id par défaut de la campagne
			$order_object->site_id = get_order_site_id($order_object->id, $order_object->site_id);
		}
		$societeInfoText = $this->getSocieteInfoText(true, false, $order_object->site_id);
		unset($y);
		if (empty($i)) {
			$this->Open();
			$this->cMargin = 2;
			$this->SetAutoPageBreak(false, 10);
			$this->setPrintHeader(false);
		}
		$this->startPageGroup();
		$next_product_max_size_forecasted = 30;
		if (empty($product_infos_array)) {
			// On rajoute un élément pour pouvoir passer dans la génération de page
			$product_infos_array[] = false;
		}
		$comments = array();
		if(!empty($order_infos['delivery_infos'])) {
			$comments[] = $GLOBALS["STR_SHIPPING_TYPE"] . $GLOBALS["STR_BEFORE_TWO_POINTS"]. ': ' . $order_infos['delivery_infos'];
		}
		if(!empty($order_object->commentaires)) {
			$comments[] = $order_object->commentaires;
		}
		$comments[] = call_module_hook('invoice_pdf_comments', array('order_object' => $order_object), 'string');
		for(true;($this->remarque_lignes === null || $this->remarque_lignes>60) && $this->remarque_font_size>5;$this->remarque_font_size--) {
			// On diminue la taille du texte si la remarque est trop longue. Si c'est vraimpent trop long, il y aura un problème de mise en page quand même
			$this->remarque_lignes = $this->sizeOfText(implode("\n", $comments), $this->w - 10 * 2, $this->remarque_font_size);
		}
		if(!empty($GLOBALS['site_parameters']['billing_as_transaction_receipt'])) {
			$sql_transaction = "SELECT t.reference
				FROM peel_transactions t
				LEFT JOIN peel_commandes c ON c.id = t.orders_id
				WHERE t.orders_id='".intval($order_object->id)."'
				LIMIT 1";
			$query = query($sql_transaction);
			$result = fetch_assoc($query);
			$this->document_name = StringMb::strtoupper($GLOBALS['STR_TRANSACTION']);
			$this->document_id = $result['reference'];
		} else {
			$this->document_name = '';
			if ($bill_mode == "user_custom_products_list") {
				$this->document_id = 0;
			} elseif ($bill_mode == "bdc") {
				$this->document_name = StringMb::strtoupper($GLOBALS['STR_ORDER_FORM']);
				$this->document_id = intval($order_object->order_id);
			} elseif ($bill_mode == "proforma") {
				$this->document_name = StringMb::strtoupper($GLOBALS['STR_PROFORMA']);
				$this->document_id = intval($order_object->order_id);
			} elseif ($bill_mode == "devis") {
				$this->document_name = StringMb::strtoupper($GLOBALS['STR_PDF_QUOTATION']);
				$this->document_id = intval($order_object->order_id);
			} else {
				if(!empty($order_object->numero)) {
					$this->document_name = StringMb::strtoupper($GLOBALS['STR_INVOICE']);
					$this->document_id = $order_object->numero;
				} else {
					$this->document_name = StringMb::strtoupper($GLOBALS['STR_ORDER_FORM']);
					$this->document_id = intval($order_object->order_id);
				}
			}
		}
		if(!empty($document_title)) {
			// On force le nom de document avec $document_title
			$this->document_name = StringMb::strtoupper($document_title);
		}
		// On refera un test de saut de page juste avant l'affichage des remarques et blocs de fin
		$product_infos_array[] = null;
		foreach($product_infos_array as $this_ordered_product) {
			if ($bill_mode == "user_custom_products_list") {
				// on peut descendre plus bas et afficher plus de produits dans ce mode
				$y_max_allowed = $this->h + 5;
				if (empty($this_ordered_product)) {
					// On a fini la liste des produits, on veut la place pour les blocs de fin de facture. L'affichage des blocs de fin de facture dépend du mode
					$y_max_allowed += -13 - vn($this->remarque_lignes) * 3.5 * ($this->remarque_font_size/8)  - 5;
				}
			} else {
				$y_max_allowed = $this->h - 10;
				if (empty($this_ordered_product)) {
					// On a fini la liste des produits, on veut la place pour les blocs de fin de facture. L'affichage des blocs de fin de facture dépend du mode
					$y_max_allowed += -45 - vn($this->remarque_lignes) * 3.5 * ($this->remarque_font_size/8) - 5;
				}
			}
			if (empty($y) || $y + $next_product_max_size_forecasted > $y_max_allowed -5) {
				if(!empty($y) && ($bill_mode !='user_custom_products_list' || ($bill_mode =='user_custom_products_list' &&  !empty($this_ordered_product)))) {
					// On dessine les colonnes de la page précédente, maintenant qu'on sait quelle taille cela a pris
					// La page précédente était avec produits sur hauteur totale
					$this->addCols($this->h - 10, $bill_mode);
				}
				if ($bill_mode !='user_custom_products_list' || ($bill_mode=='user_custom_products_list' &&  !empty($this_ordered_product))) {
					// Nécessité de créer une nouvelle page car on ne va plus avoir de place
					$next_product_max_size_forecasted = (30 + $next_product_max_size_forecasted) / 2;
					$this->AddPage();
					$this->addPageNumber($this->getGroupPageNo() . ' / ' . $this->getPageGroupAlias());
					$this->addSociete($societeInfoText, $societeLogoPath, $bill_mode);
					if ($bill_mode == "bdc") {
						$this->backgoundBigWatermark($GLOBALS['STR_ORDER_FORM'], 25, 190);
					} elseif ($bill_mode == "proforma") {
						$this->backgoundBigWatermark($GLOBALS['STR_PROFORMA'], 40, 190);
					} elseif ($bill_mode == "devis") {
						$this->backgoundBigWatermark($GLOBALS['STR_PDF_QUOTATION'], 80, 200);
					} else {
						if (!empty($GLOBALS['site_parameters']['show_invoice_filigrane'])) {
							// Option spécifique show_invoice_filigrane nécessaire pour afficher le filigrane en cas de facture
							$this->backgoundBigWatermark($GLOBALS['STR_INVOICE'], 80, 200);
						}
					}
					$this->fact_dev($this->document_name, $this->document_id, false, $bill_mode);
					if ($bill_mode != "user_custom_products_list") {
						if(in_array($order_object->statut_paiement, array('cancelled', 'refunded'))) { 
							$this->backgoundBigWatermark(get_payment_status_name($order_object->id_statut_paiement), 65, 470); 
						}
					}
					if(empty($order_object->o_timestamp) || substr($order_object->o_timestamp, 0, 10) == '0000-00-00') {
						// On a besoin d'une date à afficher par défaut : si pas de date de commande, alors on prend la date du jour
						$order_object->o_timestamp = date('Y-m-d H:i:s');
					}
					if($bill_mode == "bdc" || $bill_mode == "devis" || $bill_mode == "user_custom_products_list") {
						$displayed_date = get_formatted_date($order_object->o_timestamp, 'short', vb($GLOBALS['site_parameters']['order_hour_display_mode'], 'long'));
					} else {
						// On veut une date de facture si possible et pas de commande
						if(!empty($order_object->f_datetime) && StringMb::substr($order_object->f_datetime, 0, 10) != '0000-00-00') {
							// Une date de facture est définie
							$displayed_date = get_formatted_date($order_object->f_datetime, 'short');
						} else {
							// Pas de date de facture, on indique la date de commande
							$displayed_date = $GLOBALS['STR_ORDER_NAME'] . $GLOBALS["STR_BEFORE_TWO_POINTS"] . ': ' . get_formatted_date($order_object->o_timestamp, 'short', vb($GLOBALS['site_parameters']['order_hour_display_mode'], 'long'));
						}
					}
					$this->addDate($displayed_date, $order_infos['displayed_paiement_date'], $bill_mode);
					if ($bill_mode != "user_custom_products_list") {
						$this->addReglement(StringMb::str_shorten(get_payment_name($order_object->paiement), 30) . ' - ' . $order_object->devise);
					}
					$this->addClientAdresseFacturation($order_infos['client_infos_bill'], $order_object->id_utilisateur, $bill_mode);
					if ($bill_mode != "user_custom_products_list" && !empty($GLOBALS['site_parameters']['mode_transport']) && !empty($order_infos['client_infos_ship'])) {
						// Ajout de l'adresse de livraison seulement si la boutique a une gestion du port
						$this->addClientAdresseExpedition($order_infos['client_infos_ship']);
					}
					// Alignement du contenu des cellules de chaque ligne
					$this->addLineFormat($column_formats);
					// Initialisation du début de l'affichage des produits
					if ($bill_mode == "user_custom_products_list") {
						$y = 75;
					} else {
						$y = 100;
					}
				}
			}
			if (!empty($this_ordered_product)) {
				$prix = fprix($this_ordered_product["prix"], ($bill_mode != "user_custom_products_list"), $order_object->devise, true, $order_object->currency_rate);
				$prix_ht = fprix($this_ordered_product["prix_ht"], true, $order_object->devise, true, $order_object->currency_rate);
				$total_prix_ht = fprix($this_ordered_product["total_prix_ht"], true, $order_object->devise, true, $order_object->currency_rate);
				$total_prix = fprix($this_ordered_product["total_prix"], true, $order_object->devise, true, $order_object->currency_rate);
				$product_text = filtre_pdf($this_ordered_product["product_text"]);
				if ($bill_mode != "user_custom_products_list") {
					if (!check_if_module_active('micro_entreprise')) {
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
				} else {
					if (!empty($this_ordered_product["photo"])) {
						$this_thumb = thumbs($this_ordered_product["photo"], 50, 35);
						if (!empty($this_thumb) && file_exists($GLOBALS['uploaddir'].'/thumbs/'.StringMb::rawurlencode($this_thumb))) {
							// Positionnement du logo à droite des informations sur la société
							$this->Image($GLOBALS['uploaddir'].'/thumbs/'.StringMb::rawurlencode($this_thumb), 15, $y-4);
						}
					}
					if (!empty($this_ordered_product["barcode_image_src"])) {
						// Positionnement du logo à droite des informations sur la société
						$this->Image($this_ordered_product['barcode_image_src'], 73, $y-6, 58, 12);
					}
					$line = array($GLOBALS['STR_PHOTO'] => "",
						$GLOBALS['STR_DESIGNATION'] => $this_ordered_product["reference"]."\r\n".$product_text,
						$GLOBALS['STR_EAN_CODE'] => "\r\n\r\n\r\n".$this_ordered_product["ean_code"],
						$GLOBALS['STR_BRAND'] => $this_ordered_product["brand"],
						$GLOBALS['STR_CATEGORY'] => $this_ordered_product["category"],
						$GLOBALS['STR_PDF_PRIX_TTC'] => $prix,
						$GLOBALS['STR_QUANTITY_SHORT'] => $this_ordered_product["quantite"],
						$GLOBALS['STR_START_PRICE'].' '.$GLOBALS['STR_TTC'] => $this_ordered_product["minimal_price"]);
				}
				$size = $this->addLine($y, $line, $bill_mode);
				$next_product_max_size_forecasted = max($next_product_max_size_forecasted, min(60, $size));
				$y += $size + 4;
			}
		}
		if($y > ($bill_mode != "user_custom_products_list" ?100: 70)) {
			// La page en cours est avec produits sur hauteur restreinte, pour laisser de la place pour les blocs qui suivent
			$this->addCols($y_max_allowed, $bill_mode);	
		}
		if (!empty($order_infos['code_promo_text'])) {
			foreach($line as $this_key => $this_item) {
				$line[$this_key] = '';
			}
			$line[$GLOBALS['STR_DESIGNATION']] = $order_infos['code_promo_text'];
			$size = $this->addLine($y, $line);
			$y += $size + 4;
		}
		if(!empty($comments)) {
			$this->addRemarque(implode("\n", $comments));
		}
		if (empty($GLOBALS['site_parameters']['pdf_invoice_display_rib_on_invoice_bottom'])) {
			if ($bill_mode == "bdc") {
				$this->addCadreSignature();
			}
		} else {
			$this->addCadreRib();
		}
		if ($bill_mode != "user_custom_products_list") {
			$this->addCadreNet();
			$this->addNETs($order_infos['net_infos_array']);
			$this->addCadreTva();
			$this->addTVAs($order_infos['tva_infos_array']);
			$this->addInfoTVA($order_object->total_tva, $bill_mode, $order_object->id_utilisateur);
		} else {
			$y1 = $this->h-21;
			if (!empty($GLOBALS['site_parameters']['add_copyright_on_pdf_file'])) {
				$qid = query("SELECT * 
					FROM peel_societe
					WHERE " . get_filter_site_cond('societe') . "
					ORDER BY site_id DESC
					LIMIT 1");
				$ligne = fetch_assoc($qid);
				$text = '<a href="' .get_url('/') . '">' . get_url('/') . '</a> Copyright ' . $GLOBALS['site'] . ' ' . date('Y').' / '.$GLOBALS['STR_TEL'].$GLOBALS['STR_BEFORE_TWO_POINTS'].': '.$ligne['tel'].' / '.$GLOBALS['STR_FAX'].$GLOBALS['STR_BEFORE_TWO_POINTS'].': '.$ligne['fax'].' / '.$ligne['email'] ;
				$this->writeHTMLCell("", 4, 10, $y1, $text, 0, 1, false, true, "C");
				$this->SetFont("freesans", "", 6);
				$text = template_tags_replace($GLOBALS['STR_INVOICE_BOTTOM_TEXT2']);
				$this->writeHTMLCell("", 4, 10, $y1+4, $text, 0, 1, false, true,"C");
			}
		}
	}
	
	/**
	 * getSocieteInfoText()
	 *
	 * @param boolean $use_admin_rights
	 * @param boolean $skip_registration_number
	 * @return string
	 */
	function getSocieteInfoText($use_admin_rights = true, $skip_registration_number = false, $site_id = 0)
	{
		// Recherche d'une société correspondant au site sur lequel est passé la commande
		$qid = query("SELECT * 
			FROM peel_societe
			WHERE " . get_filter_site_cond('societe', null, $use_admin_rights, $site_id, true) . " AND id_marques = 0
			ORDER BY site_id DESC
			LIMIT 1");
		$ligne = fetch_object($qid);
		if (empty($ligne)) {
			// Aucune adresse de société trouvée, on fait une recherche plus générale
			$qid = query("SELECT * 
				FROM peel_societe
				WHERE " . get_filter_site_cond('societe', null, $use_admin_rights) . " AND id_marques = 0
				ORDER BY site_id DESC
				LIMIT 1");
			$ligne = fetch_object($qid);
		}
		if (!empty($ligne)) {
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
			if (!empty($ligne->siren) && !$skip_registration_number) {
				$pdf_siret = $GLOBALS['STR_PDF_RCS'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . filtre_pdf($ligne->siren) . "\n";
			} else {
				$pdf_siret = "" ;
			}
			if (!empty($ligne->tvaintra) && !$skip_registration_number) {
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
			if (!empty($GLOBALS['site_parameters']['logo_bill_'.$lang])) {
				$pdf_logo = $GLOBALS['site_parameters']['logo_bill_'.$lang];
			} elseif (!empty($GLOBALS['site_parameters']['logo_'.$lang])) {
				$pdf_logo = $GLOBALS['site_parameters']['logo_'.$lang];
			}
		}
		if(empty($pdf_logo) && file_exists($GLOBALS['dirroot'] . '/factures/logo.jpg')) {
			$pdf_logo = $GLOBALS['dirroot'] . '/factures/logo.jpg';
		}
		if(!empty($pdf_logo) && strpos($pdf_logo, $GLOBALS['dirroot']) === false) {
			// on découpe le contenu du champs à la recherche du non de l'image fixe
			// ceci évitera d'envoyer la transmition du logo avec un chemin en http://
			$pdf_logo = StringMb::rawurldecode(str_replace($GLOBALS['wwwroot'], $GLOBALS['dirroot'], $pdf_logo));
			if (!empty($pdf_logo) && file_exists($GLOBALS['dirroot'] . '/' . $pdf_logo)) {
				// le logo existe sur le serveur
				$pdf_logo = $GLOBALS['dirroot'] . '/' . $pdf_logo;
			} elseif (!empty($pdf_logo) && file_exists($GLOBALS['dirroot'] . '/images/' . $pdf_logo)) {
				// le logo existe sur le serveur dans le dossier images
				$pdf_logo = $GLOBALS['dirroot'] . '/images/' . $pdf_logo;
			} elseif (empty($pdf_logo) || !($handle = @StringMb::fopen_utf8($pdf_logo, 'rb'))) {
				// si le logo renseigné n'existe pas, on ne retourne pas d'information
				$pdf_logo = false;
			}
			// Si :// Alors logo_path par thumbs pour récupérer l'image, faire la bonne taille.
			// 
			if(!empty($handle)) {
				fclose($handle);
			}
		}
		return $pdf_logo;
	}
}

