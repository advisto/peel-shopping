<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: Invoice.php 64925 2020-11-05 08:47:34Z sdelaporte $
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
 * @version $Id: Invoice.php 64925 2020-11-05 08:47:34Z sdelaporte $
 * @access public
 */
class Invoice extends TCPDF {
	var $colonnes;
	var $format;
	var $angle = 0;
	var $comments_height;
	var $remarque_font_size = 8;
	var $document_name;
	var $document_id;
	var $order_numero_bdc;
	var $h_dispo;
	var $order_object;
	var $bill_mode;

	/**
	 * Fonction pour fixer la hauteur du tableau de ligne de produit. Cette valeur est modulable via un hook
	 *
	 * @return
	 */
	function setHdispo() {
		// h_dispo est la hauteur de page hors pied de page sous forme d'image et hors pagination, ou autre limitation de hauteur
		// NB : La numérotation des pages aura lieu 8 mm (ou page_number_offset_y si défini) en-dessous de h_dispo
		// Habituellement h_dispo vaut donc 289 mm pour du A4
		$this->h_dispo = $this->h - vn($GLOBALS['site_parameters']['bill_footer_height']) - vn($GLOBALS['site_parameters']['page_number_offset_y'], 10);
	}

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
	function addSociete($adresse, $logo)
	{
		if ($this->bill_mode == 'user_custom_products_list') {
			$x1 = 90;
			$y = 3;
		} else {
			$x1 = vn($GLOBALS['site_parameters']['addSociete_x'], 10);
			$y = vn($GLOBALS['site_parameters']['addSociete_y'], 6);
		}
		if (!empty($logo) && empty($GLOBALS['site_parameters']['invoice_pdf_logo_display_disable'])) {
			// NB : ne pas confondre invoice_pdf_logo_w (taille dans PDF) avec invoice_pdf_logo_width(en pixels)
			$destinationW = vb($GLOBALS['site_parameters']['invoice_pdf_logo_w'], 40); // Espace max disponible en largeur pour le logo (taille dans PDF)
			// NB : ne pas confondre invoice_pdf_logo_h (taille dans PDF) avec invoice_pdf_logo_height (en pixels)
			$destinationH = vb($GLOBALS['site_parameters']['invoice_pdf_logo_h'], 35); // (taille dans PDF)
			$invoice_pdf_logo_x = vb($GLOBALS['site_parameters']['invoice_pdf_logo_x'], 45);
			$invoice_pdf_logo_y = vb($GLOBALS['site_parameters']['invoice_pdf_logo_y'], 0);
			$logo_header_full_width = !empty($GLOBALS['site_parameters']['logo_header_full_width']);
			if(empty($GLOBALS['site_parameters']['logo_not_related_to_societe_container'])) {
				// Si le logo doit s'afficher dans le bloc de société, donc la position du logo est relative au bloc société
				$x_logo = $x1 + $invoice_pdf_logo_x;
				$y_logo =  $y + $invoice_pdf_logo_y;
			} else {
				// Le logo est libre, on souhaite positionner le logo indépendamment du bloc société, donc la position est relative au début du document en entier
				$x_logo = $invoice_pdf_logo_x;
				$y_logo = $invoice_pdf_logo_y ;
			}
			if (StringMb::strpos($logo, '://') !== false) {
				// Le fichier est hébergé sur un autre serveur que celui-ci, sinon la fonction getSocieteLogoPath aurait changé le lien URL en chemin serveur
				$logo = thumbs($logo, vn($GLOBALS['site_parameters']['invoice_pdf_logo_width'], 125), vn($GLOBALS['site_parameters']['invoice_pdf_logo_height'], 80), 'fit', null, null, true, true);
				$this->Image($logo, $x_logo, $y_logo);
			} else {
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
				$this->Image($logo, $x_logo, $y_logo, $destinationW, $destinationH);
			}
			if(empty($GLOBALS['site_parameters']['logo_not_related_to_societe_container'])) {
				if($invoice_pdf_logo_x<30) {
					// Le logo est au-dessus de l'adresse, on la décale vers le bas
					$y = $y + $destinationH + 2;
		}
			}
		}
		if ($this->bill_mode != 'user_custom_products_list' && empty($logo_header_full_width) && empty($GLOBALS['site_parameters']['display_societe_address_disable'])) {
			if (!empty($GLOBALS['site_parameters']['SocieteInfoTitle'])) {
				$this->SetXY($x1, $y-5);
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 10);
				$this->Cell( 80, 2, $GLOBALS['site_parameters']['SocieteInfoTitle']);
			}
			$adresse = trim($adresse);
			if(!empty($adresse)) {
				if (!empty($GLOBALS['site_parameters']['addSociete_font_color'])) {
					$this->SetTextColor($GLOBALS['site_parameters']['addSociete_font_color'][0], $GLOBALS['site_parameters']['addSociete_font_color'][1], $GLOBALS['site_parameters']['addSociete_font_color'][2]);
				}
				$this->SetXY($x1, $y);
				if (!empty($GLOBALS['site_parameters']['invoice_societe_background_color_array'])) {
					$c_array = $GLOBALS['site_parameters']['invoice_societe_background_color_array'];
					$this->SetFillColor($c_array['r'], $c_array['v'], $c_array['b']);
				}
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), '', vn($GLOBALS['site_parameters']['invoice_societe__font_size'], 10));
				// Coordonnées de la société
				$ishtml = false;
				if (strip_tags($adresse) != $adresse) {
					// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
					$ishtml = true;
					$adresse = StringMb::nl2br_if_needed($adresse);
				}
				$this->MultiCell(80, 4, $adresse, 0, 'L', !empty($GLOBALS['site_parameters']['invoice_societe_background_color_array']), 1, '', '', true, 0, $ishtml);
				
				if (!empty($GLOBALS['site_parameters']['addSociete_font_color'])) {
					// on repasse en noir, pour ne pas impacter le reste du document
					$this->SetTextColor(0, 0, 0);
				}
			}
		}
	}

	/**
	 * Affiche en haut, à droite le libelle (FACTURE, $GLOBALS['STR_DEVIS'], Bon de commande, etc...) et son numéro
	 * La taille de la fonte est auto-adaptée au cadre
	 *
	 * @param mixed $libelle
	 * @param mixed $num
	 * @param booelan $change_background_color_by_type
	 * @return
	 */
	function fact_dev($libelle, $num, $change_background_color_by_type = false, $first_order = false, $order_numero_bdc = null)
	{
		if ($this->bill_mode == 'user_custom_products_list') {
			$y = 25;
			$h = 9;
		} else {
			$y = vn($GLOBALS['site_parameters']['fact_dev_y'], 6);
			$h = vn($GLOBALS['site_parameters']['fact_dev_h'], 9);
		}
		$x = $this->w - vn($GLOBALS['site_parameters']['fact_dev_r'], 100);
		$w = vn($GLOBALS['site_parameters']['fact_dev_w'], 90);
		
		$mid = $x + ($w / 2);
		if (!empty($GLOBALS['site_parameters']['fact_dev_texte'])) {
			$texte = $GLOBALS['site_parameters']['fact_dev_texte'];
		} elseif (empty($GLOBALS['site_parameters']['fact_dev_num_fact_disable'])) {
			$texte = $libelle . " N° " . $num;
		} else {
			$texte = $libelle;
		}
		if(!empty($first_order)) {
			$texte .= ' *';
		}
		if(!empty($order_numero_bdc)) {
			$texte_bdc =' - Cde N° : '.$order_numero_bdc;
			$texte .= $texte_bdc;
		}
		$szfont = vn($GLOBALS['site_parameters']['fact_dev_font_size'], 12);
		$loop = 0;

		while ($loop == 0) {
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), (empty($GLOBALS['site_parameters']['fact_dev_bold_disable'])?"B":'').(!empty($GLOBALS['site_parameters']['fact_dev_underline'])?"U":''), $szfont);
			$sz = $this->GetStringWidth($texte);
			if ($sz > $w)
				$szfont --;
			else
				$loop ++;
		}
		
		if (!empty($GLOBALS['site_parameters']['fact_dev_font_color'])) {
			$this->SetTextColor($GLOBALS['site_parameters']['fact_dev_font_color'][0], $GLOBALS['site_parameters']['fact_dev_font_color'][1], $GLOBALS['site_parameters']['fact_dev_font_color'][2]);
		}
		
		$this->SetLineWidth(0.1);
		if (empty($GLOBALS['site_parameters']['fact_dev_bg_color'])) {
			$this->SetFillColor(210, 210, 255);
		} else {
			$this->SetFillColor($GLOBALS['site_parameters']['fact_dev_bg_color'][0], $GLOBALS['site_parameters']['fact_dev_bg_color'][1], $GLOBALS['site_parameters']['fact_dev_bg_color'][2]);
		}
		if($change_background_color_by_type) {
			// On modifie la couleur de fond du cadre indiquant en fonction du type de document (Facture proforma,devis Bon de commande, Facture)
			if ($_GET['mode'] == 'proforma') {// Facture proforma - ROSE
				$this->SetFillColor(241, 165, 165);
			} elseif ($_GET['mode'] == 'devis') { // devis - JAUNE
				$this->SetFillColor(241, 228, 165);
			} elseif ($_GET['mode'] == 'bdc') { // Bon de commande - BLEU
				$this->SetFillColor(165, 219, 241);
			} elseif ($_GET['mode'] == 'facture') { //Facture - VERT
				$this->SetFillColor(165, 241, 173);
			}
		}
		if (empty($GLOBALS['site_parameters']['fact_dev_border_disable'])) {
			$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'DF');
		}

		$this->SetXY($x + 1, $y + 2);
		if (!empty($GLOBALS['site_parameters']['fact_dev_new_border_width'])) {
			// C'est utile pour afficher une bordure au coin carré dont l'épaisseur est modifiable. Le border de Cell est plus paramétrable que InvoiceRoundedRect
			// * @param $border (mixed) Indicates if borders must be drawn around the cell. The value can be a number:<ul><li>0: no border (default)</li><li>1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
			$border = array('LTRB' => array('width' => 2));
		} else {
			$border = 0;
		}
		$this->Cell($w - 1, 5, $texte, $border, 0, "C");
		
		if (!empty($GLOBALS['site_parameters']['fact_dev_font_color'])) {
			// on repasse la couleur en noir pour le reste de la page
			$this->SetTextColor(0, 0, 0);
		}
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
		$x = $this->w - 100;
		if ($this->bill_mode == 'user_custom_products_list') {
			$y = 38;
		} else {
			$y = 17;
		}
		$width = 90;
		$height = 12;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($x, $y, $width, $height, 'D');
		$this->Rect($x, $y, $width, $header_height, 'DF');

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 10);
		$this->SetXY($x, $y + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_BILL_DATE'], 0, 0, "C");

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 10);
		$this->SetXY($x, $y + $this->cMargin + $header_height);

		if ($date_a != "") {
			$date .= " - " . $GLOBALS['STR_PDF_DATE_PAID'] . " " . $date_a;
		}
		$this->Cell(90, 4, $date, 0, 0, "C");
	}

	/**
	 * Affiche un cadre avec les informations sur la TVA (en bas au milieu)
	 *
	 * @param integer $y
	 * @param mixed $tva
	 * @param integer $id_utilisateur
	 * @return
	 */
	function display_bottom_text($y, $tva, $id_utilisateur = null)
	{
		$x = vn($GLOBALS['site_parameters']['bottom_text_x'], 10);
		$w = vn($GLOBALS['site_parameters']['bottom_text_w'], 0); // Si 0 : on va jusqu'au bord droit du document
		if (!empty($GLOBALS['site_parameters']['invoice_bottom_text_y'])) {
			// On souhaite forcer la position en hauteur du contenu
			$y = $GLOBALS['site_parameters']['invoice_bottom_text_y'];
		}
		if (isset($GLOBALS['site_parameters']['invoice_bottom_text'])) {
			$text1 = $GLOBALS['site_parameters']['invoice_bottom_text'];
		} else {
			$text1 = ($this->bill_mode == 'devis' || $this->bill_mode == 'quote_prepare'?$GLOBALS['STR_INVOICE_BOTTOM_TEXT1']:$GLOBALS['STR_INVOICE_BOTTOM_TEXT']);
		}
		if(!empty($text1)) {
			$this->SetXY($x, $y);
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 8);
			$ishtml = false;
			if (strip_tags($text1) != $text1) {
				// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
				$ishtml = true;
			}
			if ($ishtml) {
				$this_text_html = StringMb::nl2br_if_needed($text1);
				$this->MultiCell($w, 4, $this_text_html . "\n", 0,  vb($GLOBALS['site_parameters']['bottom_text_align'], "C"), false, 1, '', '', true, 0, $ishtml);
			} else {
				$this->Cell($w, 4, $text1, 0, 0, "C");
			}
		}
			if (floatval($tva)==0) {
				if (check_if_module_active('micro_entreprise')) {
					// Pour les entreprises bénéficiant du régime de franchise de base de TVA, il faut obligatoirement porter sur chaque facture la mention suivante : « TVA non applicable, article 293 B du CGI ».
					// => Déjà géré par l'appel à addTVAs_part_micro
				} elseif(!empty($GLOBALS['STR_INVOICE_BOTTOM_TEXT2']) && is_user_tva_intracom_for_no_vat($id_utilisateur)) {
					// Pour les livraisons de biens intracommunautaires, les factures doivent obligatoirement comporter la mention suivante : « Exonération de TVA, article 262 ter 1 du CGI ».
					// Lorsqu'il s'agit de prestations de services intracommunautaires dont la taxe est autoliquidée par le preneur, il faudra faire figurer, à notre sens, les mentions « TVA due par le preneur, art. CGI 283-2, et art. 194 de la directive TVA 2006/112/CE »
					// => Texte à définir en conséquence en fonction de votre site dans $GLOBALS['STR_INVOICE_BOTTOM_TEXT2']
					$text2 = $GLOBALS['STR_INVOICE_BOTTOM_TEXT2'];
					$this->SetXY($x, $y + 4);
				$this->Cell($w, 4, $text2, 0, 0, "C");
				}
			} elseif(!empty($GLOBALS['site_parameters']['invoice_bottom_text2'])) {
				$text2 = $GLOBALS['site_parameters']['invoice_bottom_text2'];
				$this->SetXY($x, $y + 4);
			$this->Cell($w, 4, $text2, 0, 0, "C");
			}
		}

	/**
	 * Affiche un cadre avec un numéro de page (en haut, a droite)
	 *
	 * @param string $page_text
	 * @param integer $font_size
	 * @return
	 */
	function addPageNumber($page_text, $font_size = 8, $position = null)
	{
		if(!isset($GLOBALS['site_parameters']['page_number_offset_y']) || !empty($GLOBALS['site_parameters']['page_number_offset_y'])) {
			// Si $GLOBALS['site_parameters']['page_number_offset_y'] vaut 0, on désactive la numérotation de pages
			if (empty($position['page_number_x'])) {
				$x = $this->w / 2 - 15;
			} else {
				$x = $position['page_number_x'];
			}
			if (empty($position['page_number_y'])) {
				$y = $this->h_dispo + 3;
			} else {
				$y = $position['page_number_y'];
			}

			$this->SetXY($x, $y);
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "helvetica"), "", $font_size);
			$this->Cell(30, 4, $GLOBALS['STR_PDF_BILL_PAGE'] . ' ' . $page_text, 0, 0, "C");
		}
	}

	/**
	 * Affiche l'adresse du client (en haut, a droite)
	 *
	 * @param mixed $pdf_facturation
	 * @param integer $id_utilisateur
	 * @return
	 */
	function addClientAdresseFacturation($pdf_facturation, $id_utilisateur, $adresse_facturation_position)
	{
		if ($adresse_facturation_position == 'right') {
			// mettre le bloc à droite. Dans ce mode il n'y a pas d'adresse de livraison, donc on met l'adresse de facturation à la place
			$x = $this->w - vn($GLOBALS['site_parameters']['ClientAdresseFacturation_x'],100);
			$y = vb($GLOBALS['site_parameters']['ClientAdresseFacturation_y'], 45);
		} else {
			$x = $this->w - vn($GLOBALS['site_parameters']['ClientAdresseFacturation_x'],200);
			if ($this->bill_mode == 'user_custom_products_list') {
				$y = vb($GLOBALS['site_parameters']['ClientAdresseFacturation_y'], 6);
			} else {
				$y = vb($GLOBALS['site_parameters']['ClientAdresseFacturation_y'], 40);
			}
		}
		$width = vb($GLOBALS['site_parameters']['ClientAdresseFacturation_w'], 90);
		$height = vb($GLOBALS['site_parameters']['ClientAdresseFacturation_h'], 45);
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		if (empty($GLOBALS['site_parameters']['ClientAdresseFacturation_rect_disable'])) {
		$this->Rect($x, $y + vn($GLOBALS['site_parameters']['ClientAdresseFacturation_rect_offset_y']), $width, $height, 'D');
		}
		if (empty($GLOBALS['site_parameters']['ClientAdresseFacturation_header_rect_disable'])) {
			$this->Rect($x, $y, $width, $header_height, 'DF');
		}
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['ClientAdresseFacturation_font_size'], 10));
		$this->SetXY($x, $y + 0.5);
		
		if (empty($GLOBALS['site_parameters']['ClientAdresseFacturation_header_disable'])) {
		$title = $GLOBALS['STR_PDF_FACTURATION'];
			if (!empty($id_utilisateur) && !empty($GLOBALS['site_parameters']['ClientAdresseFacturation_client_id_in_header'])) {
			$title .= ' ' . $id_utilisateur;
		}
			$this->Cell(90, 4, $title, 0, 0, vb($GLOBALS['site_parameters']['ClientAdresseFacturation_title_alignement'], "C"));
		}
		
		if (!empty($GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'])) {
			$this->SetTextColor($GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'][0], $GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'][1], $GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'][2]);
		}
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['ClientAdresseFacturation_font_size'], 10));
		$this->SetXY($x, $y + $this->cMargin + $header_height);
		$ishtml = false;
		if (strip_tags($pdf_facturation) != $pdf_facturation) {
			// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
			$ishtml = true;
			$pdf_facturation = StringMb::nl2br_if_needed($pdf_facturation);
		}
		$this->MultiCell(90, 4, $pdf_facturation . "\n", 0, "L", false, 1, '', '', true, 0, $ishtml);
		if (!empty($GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'])) {
			// on repasse en noir, pour ne pas impacter le reste du document
			$this->SetTextColor(0, 0, 0);
		}
	}

	/**
	 * Invoice::addClientPersonnalizationInfo()
	 *
	 * @return
	 */
	function addClientPersonnalizationInfo()
	{
		$hook_result = call_module_hook('user_personalization_infos', array('bill_mode' => $this->bill_mode, 'order_object' => $this->order_object), 'array');
		$this->get_background_image('bloc_perso');
		if (!empty($hook_result['bloc_header_perso_title']) || !empty($hook_result['bloc_header_perso_text'])) {
			$x = $this->w - vn($hook_result['addClientPersonnalizationInfo_r'], 200);
			$y = vn($hook_result['addClientPersonnalizationInfo_y'], 40);
			$w = vn($hook_result['addClientPersonnalizationInfo_w'], 90);
			$h = vn($hook_result['addClientPersonnalizationInfo_h'], 4);

			if(!empty($hook_result['bloc_perso_frame_enable'])) {
				if (!empty($hook_result['bloc_perso_background_color'])) {
					// ne fonctionne pas, comprends pas pourquoi.
					$this->SetFillColor($hook_result['bloc_perso_background_color'][0], $hook_result['bloc_perso_background_color'][1], $hook_result['bloc_perso_background_color'][2]);
				}
				$this->Rect($x, $y+5, $w, $hook_result['bloc_perso_frame_height'], "D", vb($hook_result['bloc_perso_frame_border_array'], array()));
			}
			if (!empty($GLOBALS['site_parameters']['bloc_perso_display_page_number'])) {
				// TCPDF va gérer tout seul le nombre total de page qu'il va remplacer à la fin, donc pas de soucis que cette fonction soit appelée au fur et à mesure de la création des pages
				$this->addPageNumber($this->getGroupPageNo() . ' / ' . $this->getPageGroupAlias(), 8, array('page_number_x'=>$GLOBALS['site_parameters']['bloc_perso_page_number_x'], 'page_number_y'=>$GLOBALS['site_parameters']['bloc_perso_page_number_y']));
			}
			
			foreach (array(1, 2) as $i) {
				if (!empty($hook_result['bloc_header_perso_title'.$i])) {
					$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 13);
					$this->SetXY($x+1, $y);
					$this->Cell($w-2, $h, $hook_result['bloc_header_perso_title'.$i], 0, 0, vb($hook_result['bloc_header_perso_title_align'], "C"));
					$y += 5;
				}
			}
			if (!empty($hook_result['bloc_header_perso_text'])) {
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['ClientPersonnalizationInfo_font_size'], 10));
				$y += 5;
				foreach ($hook_result['bloc_header_perso_text'] as $this_text) {
					$this->SetXY($x+1, $y);
					$ishtml = false;
					if (strip_tags($this_text) != $this_text) {
						// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
						$ishtml = true;
						$this_text = StringMb::nl2br_if_needed($this_text);
					}
					if (empty($this_text)) {
						// si texte est vide, on veut juste une interligne moins haute qu'un saut de ligne standard
						$h = 2;
					}
					$this->MultiCell($w-2, $h, $this_text . "\n", 0, vb($hook_result['bloc_header_perso_text_align'], "L"), false, 1, '', '', true, 0, $ishtml);
					
					if (empty($this_text)) {
						// si texte est vide, on veut juste une interligne moins haute qu'un saut de ligne standard
						$y += 2;
					} else {
						$y += 5;
					}
				}
			}
		}
	}
	/**
	 * Invoice::addClientAdresseExpedition()
	 *
	 * @param mixed $pdf_facturation2
	 * @return
	 */
	function addClientAdresseExpedition($pdf_facturation2)
	{
		$x = $this->w - 100;
		$y = vb($GLOBALS['site_parameters']['ClientAdresseExpedition_y'], 45);
		$w = vb($GLOBALS['site_parameters']['ClientAdresseExpedition_w'], 90);
		$h = vb($GLOBALS['site_parameters']['ClientAdresseExpedition_h'], 40);
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($x, $y, $w, $h, 'D');
		$this->Rect($x, $y, $w, $header_height, 'DF');

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 10);
		$this->SetXY($x, $y + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_LIVRAISON'], 0, 0, "C");

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 10);
		$this->SetXY($x, $y + $this->cMargin + $header_height);
		$this->MultiCell(90, 4, $pdf_facturation2 . "\n");
	}
	
	/**
	 * Affiche un cadre avec le règlement (chèque, etc...)
	 * (en haut, a gauche)
	 *
	 * @param string $text
	 * @return
	 */
	function addReglement($text)
	{
		$x = $this->w - 100;
		$y = 29;
		$w = 90;
		$h = 12;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($x, $y, $w, $h, 'D');
		$this->Rect($x, $y, $w, $header_height, 'DF');

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 10);
		$this->SetXY($x, $y + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_PAIEMENT'], 0, 0, "C");

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 10);
		$this->SetXY($x, $y + $this->cMargin + $header_height);
		$this->Cell(90, 4, $text, 0, 0, "C");
	}

	/**
	 * Affiche un cadre avec la date d'echeance (en haut, au centre)
	 *
	 * @param mixed $date
	 * @return
	 */
	function addEcheance($date)
	{
		$x = 80;
		$w = 40;
		$y = 80;
		$y2 = $y + 10;
		$mid = $y + (($y2 - $y) / 2);
		$this->InvoiceRoundedRect($x, $y, $w, ($y2 - $y), 2.5, 'D');
		$this->Line($x, $mid, $x + $w, $mid);
		$this->SetXY($x + $w / 2 - 5 , $y + 1);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 10);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_DUE_DATE'], 0, 0, "C");
		$this->SetXY($x + $w / 2 - 5 , $y + 5);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 10);
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
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 10);
		$length = $this->GetStringWidth($GLOBALS['STR_PDF_REF'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . $ref);
		$x = 10;
		$r2 = $x + $length;
		$y = 92;
		$y2 = $y + 5;
		$this->SetXY($x , $y);
		$this->Cell($length, 4, $GLOBALS['STR_PDF_REF'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . $ref);
	}

	/**
	 * Trace le cadre des colonnes du devis/facture
	 *
	 * @param integer $y_begin_products
	 * @param integer $y_end_products
	 * @param integer $header_height
	 * @param integer $font_size
	 * @return
	 */
	function addCols($y_begin_products, $y_end_products, $header_height, $font_size = 8)
	{
		$x = 10;
		$w = $this->w - ($x * 2) ;
		
		$this->SetXY($x, $y_begin_products);
		
		if(empty($GLOBALS['site_parameters']['cv_frame_disable'])) {
			$this->Rect($x, $y_begin_products, $w, $y_end_products - $y_begin_products, "D");
		}
		if (!empty($GLOBALS['site_parameters']['cols_header_background_color'])) {
			$this->SetFillColor($GLOBALS['site_parameters']['cols_header_background_color'][0], $GLOBALS['site_parameters']['cols_header_background_color'][1], $GLOBALS['site_parameters']['cols_header_background_color'][2]);
		} else {
			$this->SetFillColor(240, 240, 240);
		}
		if(empty($GLOBALS['site_parameters']['cv_header_frame_disable'])) {
			$this->Rect($x, $y_begin_products, $w, $header_height, "DF");
		}
		if (!empty($GLOBALS['site_parameters']['cols_header_font_color'])) {
			$this->SetTextColor($GLOBALS['site_parameters']['cols_header_font_color'][0], $GLOBALS['site_parameters']['cols_header_font_color'][1], $GLOBALS['site_parameters']['cols_header_font_color'][2]);
		}
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", $font_size);
		if(!empty($this->colonnes) && empty($GLOBALS['site_parameters']['cv_header_content_disable'])) {
			foreach($this->colonnes as $lib => $pos) {
				$this->SetXY($x, $y_begin_products + 1);
				if($this->bill_mode != 'user_custom_products_list') {
					$this->Cell($pos, 1, $lib, 0, 0, "C");
				} else {
					$this->MultiCell($pos, $header_height-1, $lib, 0, 0, "C");
				}
				$x += $pos;
				if($this->bill_mode != 'user_custom_products_list') {
					// pour l'affichage des listes de produits, les lignes sont horizontales (cf addLine)
					if(empty($GLOBALS['site_parameters']['disable_cv_column_frame'])) {
						$this->Line($x, $y_begin_products, $x, $y_end_products);
					}
				}
			}
		}
		if (!empty($GLOBALS['site_parameters']['cols_header_font_color'])) {
			// On repasse la typo en noir
			$this->SetTextColor(0, 0, 0);
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
	 * @param mixed $y_begin
	 * @param mixed $cells_array
	 * @param boolean $fill
	 * @param integer $font_size
	 * @param float $line_height
	 * @return
	 */
	function addLine($y_begin, $cells_array, $fill = false, $font_size = 8, $line_height = 3.5)
	{
		$x_start_line = 10;
		$x = $x_start_line;
		if($this->bill_mode == 'user_custom_products_list') {
			$y_begin = $y_begin-3;
		}
		if(!empty($this->colonnes)) {
			$start_page = $this->getPage();    
			$end_page = $start_page;
			$last_y = $this->GetY();
			foreach($this->colonnes as $lib => $pos) {
				$texte = vb($cells_array[$lib]);
				if($this->bill_mode == 'user_custom_products_list') {
					$this_font_size = 7;
				} elseif ($texte === 'Total') {
					$this_font_size = 10;
				} else {
					$this_font_size = $font_size;
				}
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", $this_font_size);
				$longCell = $pos;
				$length = $this->GetStringWidth($texte);
				$formText = vb($this->format[ $lib ]);
				$this->SetXY($x, $y_begin);
				// Le champ texte peut contenir plusieurs valeurs à afficher dans une cellule dédié; Le séparateur des différentes valeur est |&| 
				$text_array = explode('|&|', $texte);
			
				$this->writeHTMLCell($longCell, $line_height, $x, $y_begin, add_or_remove_primary_container_in_html(get_clean_html_for_pdf(StringMb::nl2br_if_needed($text_array[0])), 'add', array('font-size' => $this_font_size . 'px')), 0, 1, false, true, $formText);
				
				// $this->MultiCell($longCell, $line_height, $text_array[0], 0, $formText, $fill);
				//var_dump(add_or_remove_primary_container_in_html(get_clean_html_for_pdf($text_array[0]), 'add', array('font-size' => $this_font_size.'px')));
				// $this->writeHTMLCell($longCell, $line_height, $x, $y_begin, strip_tags($text_array[0]), 0, 1, false, true, $formText);
				// $this->writeHTMLCell($longCell, $line_height, $x, $y_begin, $text_array[0], 0, 1, false, true, $formText);
				
				if (!empty($text_array[1])) {
					$details_html['text'] = $text_array[1];
					$details_html['w'] = $pos;
					$details_html['x'] = $x;
					$details_html['y'] = $this->GetY() + 5;
					$details_html['format'] = $formText;
				}	
				$x += $pos;
				if($this->getPage() > $end_page) {
					$end_page = $this->getPage();
					$last_y = $this->GetY();
				} elseif($this->getPage() == $end_page) {
					$last_y = max($last_y, $this->GetY());
				}
				if($this->getPage() > $start_page) {
					$this->setPage($start_page);
				}
			}
			if($this->getPage() < $end_page) {
				$this->setPage($end_page);
			}
			// On repositionne pour la suite : on se met en bas à gauche de la ligne qui vient d'être écrite
			$this->SetXY($x_start_line, $last_y);
			if($this->bill_mode == 'user_custom_products_list') {
				$y2 = $y_begin-4;
				// pour l'affichage des listes de produits, les lignes de séparations tracées sont horizontales. La séparation s'affiche au dessus des lignes.
				$this->Line(10, $y2, $x, $y2);
			}
		}
		if(!empty($details_html)) {
			// libelle détail HTML
			// Pour gérer le multipage, il faut utiliser writeHTMLCell ou writeHTML
			// Alternative moins complète : $this->writeHTML($details_html, true, false, false, false, $formText); 
			$this->writeHTMLCell($details_html['w'], $line_height, $details_html['x'], $details_html['y'], get_clean_html_for_pdf($details_html['text']), 0, 1, false, true, $details_html['format']);
		}
		return true;
	}

	/**
	 * Invoice::addTotalHt()
	 *
	 * @param mixed $total_ht
	 * @return
	 */
	function addTotalHt($total_ht)
	{
		$x = $this->w - 31;
		$w = 19;
		$y = 100;
		$y2 = $y;
		$mid = $y + ($y2 / 2);
		$this->SetXY($x + $w / 2 - 5, $y + 3);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 10);
		$this->Cell(10, 5, $GLOBALS['STR_PDF_TOTAL_HT'], 0, 0, "C");
		$this->SetXY($x + $w / 2 - 5, $y + 9);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 10);
		$this->Cell(10, 5, $total_ht . ' HT', 0, 0, "C");
	}

	/**
	 * Ajoute une remarque (en bas, a gauche)
	 *
	 * @param string $remarque
	 * @param integer $y
	 * @param integer $h
	 * @return
	 */
	function addRemarque($remarque, $y, $h = null)
	{
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", $this->remarque_font_size);
		$x = 10;
		$w = $this->w - ($x * 2) ;
		if (!empty($GLOBALS['site_parameters']['bill_pdf_add_color_behind_comments'])) {
			// On dessine un cadre coloré
			$this->SetFillColor(240, 240, 240);
			$this->Rect($x, $y, $w, $h, "DF");
			$y = $y + 1;
		}
		$this->SetXY($x, $y); 
		$ishtml = false;
		if (strip_tags($remarque) != $remarque) {
			// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
			$ishtml = true;
			$remarque = StringMb::nl2br_if_needed($remarque);
		}
		// On écrit le texte de commentaire.
		$this->MultiCell($w, 4, $remarque . "\n", 0, 'J', false, 1, '', '', true, 0, $ishtml);
		// $this->writeHTMLCell($w, 4, '', '', $remarque . "\n", 0, 1, false, true, "J");
	}

	/**
	 * Trace le cadre des TVAs
	 *
	 * @return
	 *
	function addCadreTVAs()
	{
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 8);
		$x = 10;
		$w = 20;
		$y = $this->h;
		$h = 5;
		$this->Line($x, $y + 4, $x + $w, $y + 4);
		$this->Line($x + 5, $y + 4, $x + 5, $y + $h); // avant BASE HT
		$this->Line($x + 27, $y, $x + 27, $y + $h); // avant REMISE
		$this->Line($x + 63, $y, $x + 63, $y + $h); // avant % TVA
		$this->Line($x + 75, $y, $x + 75, $y + $h); // avant PORT
		$this->Line($x + 91, $y, $x + 91, $y + $h); // avant TOTAUX
		$this->SetXY($x + 9, $y);
		$this->Cell(10, 4, $GLOBALS['STR_TOTAL_HT']);
		$this->SetX($x + 63);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_BILL_TVA']);
		$this->SetX($x + 78);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_BILL_SHIPPING']);
		$this->SetX($x + 100);
		$this->Cell(10, 4, $GLOBALS['STR_PDF_BILL_TOTALS']);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 6);
		$this->SetXY($x + 93, $y + $h - 13);
		$this->Cell(6, 0, $GLOBALS['STR_TTC'] . "   :");
		$this->SetXY($x + 93, $y + $h - 8);
		$this->Cell(6, 0, $GLOBALS['STR_HT'] . "   :");
		$this->SetXY($x + 93, $y + $h - 3);
		$this->Cell(6, 0, $GLOBALS['STR_PDF_BILL_TVA'] . " :");
	}

	/**
	 * Invoice::addCadreSignature()
	 *
	 * @param integer $y
	 * @param integer $h
	 * @return
	 */
	function addCadreSignature($y, $h)
	{
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 10);
		$x = 10;
		$w = 75;
		$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'D');
		if (!empty($GLOBALS['site_parameters']['addCadreSignature_text'])) {
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 7);
			$this->SetXY($x, $y + $h - 29);
			
			$ishtml = false;
			if (strip_tags($GLOBALS['site_parameters']['addCadreSignature_text']) != $GLOBALS['site_parameters']['addCadreSignature_text']) {
				// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
				$ishtml = true;
				$GLOBALS['site_parameters']['addCadreSignature_text'] = StringMb::nl2br_if_needed($GLOBALS['site_parameters']['addCadreSignature_text']);
			}
			$this->MultiCell($w, 4, $GLOBALS['site_parameters']['addCadreSignature_text'], 0, 'J', false, 1, '', '', true, 0, $ishtml);
		} else {
			$this->Line($x, $y + 6, $x + $w, $y + 6);
			$this->SetXY($x, $y + 1);
			$this->Cell($w, 4, $GLOBALS['STR_ACCORD'], 0, 0, "C");
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 7);
			$this->SetXY($x, $y + $h - 21);
			$this->Cell($w, 0, $GLOBALS['STR_PDF_DATE']);
			$this->SetXY($x, $y + $h - 16);
			$this->Cell($w, 0, $GLOBALS['STR_ACCORD_OK']);
			$this->SetXY($x, $y + $h - 11);
			$this->Cell($w, 0, $GLOBALS['STR_PDF_SIGNATURE']);
		}
	}

	/**
	 * Invoice::display_iban_at_bottom()
	 *
	 * @param integer $y
	 * @param integer $w
	 * @param integer $h
	 * @return
	 */
	function display_iban_at_bottom($y, $w, $h, $banque = null)
	{
		$x = 10;
		$hook_result = call_module_hook('invoice_display_iban_at_bottom', array('banque' => $banque), 'array');
		if(!empty($hook_result['hook_result'])) {
			// Il y a bien un hook qui gère la récupération des infos de la banque de la société. Donc il faut passer pas ici et non par peel_societe, même si le hook ne retourne rien d'autre.
			unset($hook_result['hook_result']);
			$result = $hook_result;
		} else {
			$sql="SELECT iban, swift
				FROM peel_societe
				WHERE " . get_filter_site_cond('societe') . " AND id_marques = 0
				ORDER BY site_id DESC
				LIMIT 1";
			$query = query($sql);
			$result = fetch_assoc($query);
		}
		if (!empty($result)) {
			$rib = '';
			if (!empty($result['Nom1'])) {
				$rib .= $GLOBALS['STR_MODULE_TEMPS_BANK_SINGULAR'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $result['Nom1'] . "\r\n";
			}
			if (!empty($result['Adresse1'])) {
				$rib .= $GLOBALS['STR_MODULE_TEMPS_BANK_DOMICILIATION'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $result['Adresse1'] .' ' . $result['Adresse2'] .' '. $result['Code_Postal'] .' '. $result['Ville'] . "\r\n";
			}
			if (!empty($result['iban'])) {
				$rib .= $GLOBALS['STR_IBAN'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '. chunk_split(str_replace(' ', '', $result['iban']), 4, ' ') . "\r\n" . $GLOBALS['STR_SWIFT'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.$result['swift']; 
			}
			if(!empty($rib)) {
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['invoice_display_iban_at_bottom_title_font_size'], 10));
				$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'D');
				$this->Line($x, $y + 6, $x + $w, $y + 6);
				$this->SetXY($x, $y + 1);
				$this->Cell($w, 4, vb($GLOBALS['site_parameters']['invoice_display_iban_at_bottom_title'], $GLOBALS['STR_TRANSFER']), 0, 0, "C");
				$this->SetXY($x + 1, $y+7);
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), '', vn($GLOBALS['site_parameters']['invoice_display_iban_at_bottom_font_size'], 10));
				// $length = $this->GetStringWidth($rib);
				$this->MultiCell($w, 7, $rib, 0, "L");
			}
		}
	}

	/**
	 * Trace le cadre des totaux et le remplit
	 * 
	 * @param integer $x
	 * @param integer $y
	 * @param integer $w
	 * @param integer $h
	 * @param array $params1
	 * @return
	 */
	function addCadreNet($x, $y, $w, $h, $params1, $params2)
	{
		if (empty($GLOBALS['site_parameters']['addCadreNet_RoundedRect_disable'])) {
			$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'D');
		}
		if (!empty($GLOBALS['site_parameters']['addCadreNet_cell_height'])) {
			$cell_height = $GLOBALS['site_parameters']['addCadreNet_cell_height'];
		} else {
			$cell_height = 4;
		}
		if (!empty($GLOBALS['site_parameters']['addCadreNet_cell_width'])) {
			$cell_width = $GLOBALS['site_parameters']['addCadreNet_cell_width'];
		} else {
			$cell_width = 25;
		}
		if (!empty($GLOBALS['site_parameters']['addCadreNet_label_cell_width'])) {
			$label_cell_width = $GLOBALS['site_parameters']['addCadreNet_label_cell_width'];
		} else {
			$label_cell_width = $cell_width;
		}
		
		$y = $y + 5;
		$x_margin = vn($GLOBALS['site_parameters']['addCadreNet_x_margin'], 1);
		if (!empty($GLOBALS['site_parameters']['addCadreNet_force_x'])) {
			$x = $GLOBALS['site_parameters']['addCadreNet_force_x'];
		} else {
			$x = $this->w - 65 + $x_margin;
		}
		$x2 = $x + 30 - 2 * $x_margin;
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), (empty($GLOBALS['site_parameters']['addCadreNet_bold_disable_except_total'])?"B":''), vn($GLOBALS['site_parameters']['addCadreNet_font_size_except_total'], 8));
		$k = 0;

		if (abs(get_float_from_user_input($params1["tarif_paiement"])) >= 0.01) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $cell_height, $GLOBALS['STR_PDF_GESTION'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $cell_height, $params1['tarif_paiement'], '', '', 'R');
			$k = $k + $cell_height-1;
		}

		if (check_if_module_active('ecotaxe')) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $cell_height, $GLOBALS['STR_PDF_ECOTAXE_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $cell_height, $params1["total_ecotaxe_ht"], '', '', 'R');
			$k = $k + $cell_height-1;
		}
		if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $cell_height, $GLOBALS['STR_PDF_COUT_TRANSPORT_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $cell_height, $params1["cout_transport_ht"], '', '', 'R');
			$k = $k + $cell_height-1;
		}

		if (abs(get_float_from_user_input($params1["small_order_overcost_amount"])) >= 0.01) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $cell_height, $GLOBALS['STR_SMALL_ORDER_OVERCOST_TEXT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $cell_height, $params1["small_order_overcost_amount"], '', '', 'R');
			$k = $k + $cell_height-1;
		}
		if (!check_if_module_active('micro_entreprise')) {
			if (!empty($GLOBALS['site_parameters']['distinct_total_ht_by_vat'])) {
				foreach($GLOBALS['site_parameters']['distinct_total_ht_by_vat'] as $label=>$this_total_ht_amount) {
					$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_HT_cell_x']), $y + $k);
					$this->Cell($label_cell_width, $cell_height, $label);
					$this->SetXY($x2, $y + $k);
					if (!empty($GLOBALS['site_parameters']['total_ht_background_color'])) {
						$this->SetFillColor($GLOBALS['site_parameters']['total_ht_background_color'][0], $GLOBALS['site_parameters']['total_ht_background_color'][1], $GLOBALS['site_parameters']['total_ht_background_color'][2]);
						$this->Rect($x2, $y + $k, $cell_width, $cell_height, "F");
					}
					$this->Cell($cell_width, $cell_height, $this_total_ht_amount, vb($GLOBALS['site_parameters']['addCadreNet_montantHT_border']), '', 'R');
					$k = $k + $cell_height-1;
				}
			} elseif (empty($GLOBALS['site_parameters']['total_ht_display_disable'])) {
			$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_HT_cell_x']), $y + $k);
				$this->Cell($label_cell_width, $cell_height, $GLOBALS['STR_PDF_TOTAL_HT']);
			$this->SetXY($x2, $y + $k);
			if (!empty($GLOBALS['site_parameters']['total_ht_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['total_ht_background_color'][0], $GLOBALS['site_parameters']['total_ht_background_color'][1], $GLOBALS['site_parameters']['total_ht_background_color'][2]);
				$this->Rect($x2, $y + $k, $cell_width, $cell_height, "F");
			}
			$this->Cell($cell_width, $cell_height, StringMb::html_entity_decode_if_needed($params1["montant_ht"]), vb($GLOBALS['site_parameters']['addCadreNet_montantHT_border']), '', 'R');
			}
		} else {
			addNETs_part_micro($this, $x, $y + $k, $params1["totalttc"]);
		}
		
		// avoir
		$k = $k + $cell_height;
		if (abs(get_float_from_user_input($params1["avoir"])) >= 0.01) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $cell_height, $GLOBALS['STR_PDF_AVOIR']);
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $cell_height, $params1["avoir"], '', '', 'R');
			$k = $k + $cell_height-1;
		}
		
		
		// détail TVA
		if(!empty($GLOBALS['site_parameters']['total_tva_in_net_bloc'])) {
			if (!empty($params2["distinct_total_vat"])) {
				foreach($params2["distinct_total_vat"] as $vat_percent => $value) {
					if ($vat_percent>0) {
						// vat_percent>0 : La TVA 0% aura toujours pour valeur 0€, donc on n'affiche pas
						$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TVA_cell_x']), $y + $k);
						$this->Cell($label_cell_width , $cell_height, $GLOBALS['STR_PDF_TVA'] . ' ' . (StringMb::substr($vat_percent, 0, strlen('transport')) == 'transport'? str_replace('transport', $GLOBALS['STR_PDF_SHIPMENT'], $vat_percent) : $vat_percent) . '%' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . " ", "", 0 , "L");
						$this->SetXY($x2, $y + $k);
						if (!empty($GLOBALS['site_parameters']['total_tva_detail_background_color'])) {
							$this->SetFillColor($GLOBALS['site_parameters']['total_tva_detail_background_color'][0], $GLOBALS['site_parameters']['total_tva_detail_background_color'][1], $GLOBALS['site_parameters']['total_tva_detail_background_color'][2]);
							$this->Rect($x2, $y + $k, 25, $cell_height, "F");
						}
						$this->Cell($cell_width, $cell_height, $value, '', '', 'R');
						$k = $k + $cell_height;
					}
				}
			}
		}

		/* Affichage TOTAL TTC */
		$this->SetXY($x- vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_TTC_cell_x']), $y + $k);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), (empty($GLOBALS['site_parameters']['addCadreNet_bold_disable_except_total']) || !empty($GLOBALS['site_parameters']['addCadreNet_TOTAL_TTC_bold'])?"B":''), vn($GLOBALS['site_parameters']['addCadreNet_font_size_except_total'], 8));
		if (!empty($GLOBALS['site_parameters']['total_ttc_label_font_color'])) {
			$this->SetTextColor($GLOBALS['site_parameters']['total_ttc_label_font_color'][0], $GLOBALS['site_parameters']['total_ttc_label_font_color'][1], $GLOBALS['site_parameters']['total_ttc_label_font_color'][2]);
		}
		if (!empty($GLOBALS['site_parameters']['total_ttc_label_background_color'])) {
			$this->SetFillColor($GLOBALS['site_parameters']['total_ttc_label_background_color'][0], $GLOBALS['site_parameters']['total_ttc_label_background_color'][1], $GLOBALS['site_parameters']['total_ttc_label_background_color'][2]);
			$this->Rect($x- vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_TTC_cell_x']), $y + $k, $label_cell_width, $cell_height, "F");
		}
		$this->Cell($label_cell_width, $cell_height, ucfirst($GLOBALS['STR_PDFTOTALTTC']) . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':', vb($GLOBALS['site_parameters']['addCadreNet_montantTTC_label_border']));
		if (!empty($GLOBALS['site_parameters']['total_ttc_font_color'])) {
			$this->SetTextColor($GLOBALS['site_parameters']['total_ttc_font_color'][0], $GLOBALS['site_parameters']['total_ttc_font_color'][1], $GLOBALS['site_parameters']['total_ttc_font_color'][2]);
		}
		$this->SetXY($x2, $y + $k);
		if (!empty($GLOBALS['site_parameters']['total_ttc_background_color'])) {
			$this->SetFillColor($GLOBALS['site_parameters']['total_ttc_background_color'][0], $GLOBALS['site_parameters']['total_ttc_background_color'][1], $GLOBALS['site_parameters']['total_ttc_background_color'][2]);
			$this->Rect($x2, $y + $k, $cell_width, $cell_height, "F");
		}
		$this->Cell($cell_width, $cell_height, StringMb::html_entity_decode_if_needed($params1["montant"]), vb($GLOBALS['site_parameters']['addCadreNet_montantTTC_border']), '', 'R');
		if (!empty($GLOBALS['site_parameters']['total_ttc_font_color']) || !empty($GLOBALS['site_parameters']['total_ttc_label_font_color'])) {
			$this->SetTextColor(0, 0, 0);
		}
		$k = $k + $cell_height-1;
		/* FIN Affichage TOTAL TTC */

		// Acompte
		if (abs(get_float_from_user_input(vn($params1["AcompteTTC"]))) >= 0.01) {
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['addCadreNet_font_size_except_total'], 8));
			if (!empty($GLOBALS['site_parameters']['acompte_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['acompte_background_color'][0], $GLOBALS['site_parameters']['acompte_background_color'][1], $GLOBALS['site_parameters']['acompte_background_color'][2]);
				$this->Rect($x2, $y + $k, $cell_width, $cell_height, "F");
			}
			$this->SetXY($x- vn($GLOBALS['site_parameters']['addCadreNet_adjust_ACOMPTE_cell_x']), $y + $k);
			$this->Cell($label_cell_width, $cell_height, $GLOBALS['STR_PDF_ACOMPTE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
		
			$this->Cell($cell_width, $cell_height, $params1["AcompteTTC"], vb($GLOBALS['site_parameters']['addCadreNet_AcompteTTC_border']), '', 'R');
			$k = $k + $cell_height-1;
		}
		
		if (empty($GLOBALS['site_parameters']['total_ttc_top_space_disable'])) {
			$k = $k + 4;
		}
		
		// Net à payer
		if (abs(get_float_from_user_input(vn($params1["SoldeDu"]))) >= 0.01 && empty($GLOBALS['site_parameters']['net_a_payer_display_disable'])) {
			$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_NET_cell_x']), $y + $k);
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['addCadreNet_font_size_total'], 9));
			$this->Cell($label_cell_width, $cell_height, $GLOBALS['STR_PDF_NET'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			if (!empty($GLOBALS['site_parameters']['SoldeDu_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['SoldeDu_background_color'][0], $GLOBALS['site_parameters']['SoldeDu_background_color'][1], $GLOBALS['site_parameters']['SoldeDu_background_color'][2]);
				$this->Rect($x2, $y + $k, $cell_width, $cell_height, "F");
			}
			if (!empty($GLOBALS['site_parameters']['SoldeDu_font_color'])) {
				$this->SetTextColor($GLOBALS['site_parameters']['SoldeDu_font_color'][0], $GLOBALS['site_parameters']['SoldeDu_font_color'][1], $GLOBALS['site_parameters']['SoldeDu_font_color'][2]);
			}
			$this->Cell($cell_width, $cell_height, $params1["SoldeDu"], vb($GLOBALS['site_parameters']['addCadreNet_SoldeDu_border']), '', 'R');
			$k = $k + $cell_height-1;
			if (!empty($GLOBALS['site_parameters']['SoldeDu_font_color'])) {
				// on repasse en noir 
				$this->SetTextColor(0, 0, 0);
			}
		}
	}


	/**
	 * Invoice::addCadreTva()
	 *
	 * @param integer $x
	 * @param integer $y
	 * @param integer $w
	 * @param integer $h
	 * @param array $params2
	 * @return
	 */
	function addCadreTva($x, $y, $w, $h, $params2)
	{
		$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'D');

		$x_margin = 1;
		$y = $y + 5;
		$x = $this->w - 120 + $x_margin;
		$x2 = $x + 25 - 2 * $x_margin;
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", 8);
		$k = 0;

		if (check_if_module_active('micro_entreprise')) {
			addTVAs_part_micro($this, $x, $y + $k);
		} else {
			if (!empty($params2["distinct_total_vat"])) {
				foreach($params2["distinct_total_vat"] as $vat_percent => $value) {
					if ($vat_percent>0) {
						// vat_percent>0 : La TVA 0% aura toujours pour valeur 0€, donc on n'affiche pas
						$this->SetXY($x, $y + $k);
						$this->Cell(25, 4, $GLOBALS['STR_PDF_TVA'] . ' ' . (StringMb::substr($vat_percent, 0, strlen('transport')) == 'transport'? str_replace('transport', $GLOBALS['STR_PDF_SHIPMENT'], $vat_percent) : $vat_percent) . '%' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . " ");
						$this->SetXY($x2, $y + $k);
						$this->Cell(25, 4, $value, '', '', 'R');
						$k = $k + 4;
					}
				}
			}
			$k = $k + 3;
			$this->SetXY($x, $y + $k);
			$this->Cell(25, 4, $GLOBALS['STR_PDF_TVA'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
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
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), 'B', 50);
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
	 * @param object $order_object
	 * @param array $product_infos_array
	 * @param string $order_array
	 * @param string $document_title
	 * @param string $ids_array
	 * @param boolean $sign_if_available
	 * @return
	 */
	function FillDocument($code_facture = null, $date_debut = null, $date_fin = null, $id_debut = null, $id_fin = null, $user_id = null, $id_statut_paiement_filter = null, $bill_mode = 'standard', $folder = false, $order_object = null, $product_infos_array = null, $order_array = null, $document_title = null, $ids_array = null, $sign_if_available = true)
	{
		$this->bill_mode = $bill_mode;
		/*
			setHtmlVSpace permet de définir les espaces vertical des balises HTML. Les espaces des balises HTML qui s'affichent dans les documents PDF sont disproportionés, donc on fixe les tailles à 0 pour corriger des bugs d'affichage
		 * The first array level contains the tag names,
		 * the second level contains 0 for opening tags or 1 for closing tags,
		 * the third level contains the vertical space unit (h) and the number spaces to add (n).
		*/
		$tagvs = array(
			'ul' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)),
			'li' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)),  // Si on met n = 1 pour li ouvrant, alors les listes sont ok mais avec un décalage d'une ligne au départ. Sinon, on met n=0 mais on remplace </li> par </li><br> et c'est ok !
			'div' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)),
			'span' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)),
			'p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n' => 0)),
		);
		$this->setHtmlVSpace($tagvs);
		$i = 0;
		$file_name = '';
		
		call_module_hook('bill_configuration_pre', array('bill_mode' => $bill_mode), 'array');
		
		if ($bill_mode != "user_custom_products_list") {
			if (!empty($product_infos_array) || !empty($order_object)) {
				// Dans un mode de commande standard, order_object et product_infos_array ne doivent pas être définis, ce n'est pas cohérent. order_object et product_infos_array sont défini uniquement si bill_mode = user_custom_products_list
				return null;
			}
			$hook_result = call_module_hook('bill_pdf_get_sql_bills', array('code_facture' => $code_facture, 'date_debut'=> $date_debut, 'date_fin' => $date_fin, 'id_fin' => $id_fin, 'id_debut' => $id_debut, 'user_id' => $user_id, 'id_statut_paiement_filter' => $id_statut_paiement_filter, 'bill_mode' => $bill_mode, 'ids_array' => $ids_array), 'string');

			if(!empty($hook_result)) {
				// Par exemple le module micro_-_entreprise définit le format des factures ici
				$sql_bills = $hook_result;
			} else {
				if (!empty($code_facture)) {
					// La collation sur la colonne code_facture est fixée à utf8_bin et non plus utf8_general, donc on peut faire une comparaison avec = qui va utiliser l'INDEX plutôt que de passer par HEX(code_facture) = HEX('" . nohtml_real_escape_string($code_facture) . "')
					$sql_cond_array[] = "c.code_facture = '" . nohtml_real_escape_string($code_facture) . "'";
				}
				if (!empty($date_debut)) {
					$sql_cond_array[] = "c.f_datetime >= '" . nohtml_real_escape_string($date_debut) . "'";
				}
				if (!empty($date_fin)) {
					$sql_cond_array[] = "c.f_datetime <= '" . nohtml_real_escape_string($date_fin) . "'"; 
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
			}

			$query = query($sql_bills);
			while ($order_object = fetch_object($query)) {
				$hook_file_name = '';
				$hook_result = call_module_hook('bill_get_configuration_array', array('bill_mode' => $bill_mode, 'order_object' => $order_object), 'array');
				
				// Défini les site_parameters spécifique à chaque client pour le modèle de facture
				// NB  : Il faut passer $order_object car la configuration va dépendre de la société émittrice de la facture et du modèle de facture
				call_module_hook('user_personalization_infos', array('bill_mode' => $bill_mode, 'order_object' => $order_object), 'array');
				// La hauteur disponible peut être affectée par l'appel à un hook ci-dessus
				$this->setHdispo();
				if (!empty($GLOBALS['site_parameters']['pdf_column_width']) && !empty($GLOBALS['site_parameters']['pdf_column_alignement'])) {
					// Si colonne personnalisé pour le client => prioritaire sur les autres configurations
					$this->colonnes = $GLOBALS['site_parameters']['pdf_column_width'];
					$column_formats = $GLOBALS['site_parameters']['pdf_column_alignement'];
				} 
				if(count($hook_result)) {
					// Par exemple le module micro_-_entreprise définit le format des factures ici
					if (empty($GLOBALS['site_parameters']['pdf_column_width'])) {
						$this->colonnes = $hook_result['width'];
						$column_formats = $hook_result['alignement'];
					}
					$hook_file_name = $hook_result['file_name'];
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
				
				$hook_result = call_module_hook('bill_pdf_product_infos_array_in_order', array('bill_mode' => $bill_mode, 'order_object' => $order_object, 'id' => $order_object->id, 'devise' => $order_object->devise, 'currency_rate' => $order_object->currency_rate, 'product_excluded' => vb($GLOBALS['site_parameters'][$bill_mode.'_product_excluded'], array())), 'array');
				if (!empty($hook_result['hook_done'])) {
					$product_infos_array = $hook_result['products'];
				} else {
					$product_infos_array = get_product_infos_array_in_order($order_object->id, $order_object->devise, $order_object->currency_rate, null, false, vb($GLOBALS['site_parameters'][$bill_mode.'_product_excluded'], array()));
				}
				$this->generatePdfOrderContent($column_formats, $i, $order_object, $product_infos_array);
				if (empty($hook_file_name)) {
					if(empty($file_name)) {
						$file_name = $this->document_name . '_' . $this->document_id;
						if(!empty($commande->f_datetime) && substr($commande->f_datetime, 0, 10) != '0000-00-00') {
							$file_name .= '_' . get_formatted_date($commande->f_datetime);
						}
						$file_name .= '.pdf';
					} else {
						// Plusieurs factures
						$file_name = 'F-' . substr(md5($sql_bills. $GLOBALS['wwwroot']), 0, 16) . '.pdf';
					}
				} else {
					$file_name = $hook_file_name;
				}

				$i++;
			}
		} else {
			// Affichage d'une liste de produit dans un document PDF sans que les produits ne soient associés à une commande
			$this->generatePdfOrderContent($column_formats, 0, $order_object, $product_infos_array, $document_title);
			$file_name = $GLOBALS['STR_LIST_PRODUCT'] . ' ' . vb($order_object->nom_ship) . '.pdf';
			$i++;
		}
		call_module_hook('bill_get_configuration_end', array('bill_mode' => $bill_mode));
		if (!empty($i)) {
			$this->lastPage();
			if($sign_if_available && function_exists('set_pdf_signature')) {
				set_pdf_signature($this);
			}
			$file_name = StringMb::convert_accents(str_replace(array('/', ' '), '-', $file_name));
			if ($folder === null) {
				// Si $folder vaut null, c'est qu'on veut des informations remplies au cours du processus, mais pas de document PDF
				return true;
			} elseif ($folder === false) {
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
	 * Ajoute le header automatiquement à chaque création de page
	 *
	 * @return string
	 */
	public function Header() {
		// On utilise les fonctions get_background_image ici pour avoir les images de fond de document sur chaque page (même principe que les filigranes)
		
		// image de fond du contenu
		$this->get_background_image('content');
		
		// image de fond 2
		$this->get_background_image('content2');

		// On peut désactiver tous les filigranes avec backgoundBigWatermark_disable
		if (empty($GLOBALS['site_parameters']['backgoundBigWatermark_disable'])) {
			if (!empty($GLOBALS['site_parameters']['backgoundBigWatermark'])) {
				$this->backgoundBigWatermark($GLOBALS['site_parameters']['backgoundBigWatermark'], 25, 190);
			} elseif ($this->bill_mode == "bdc") {
				$this->backgoundBigWatermark($GLOBALS['STR_ORDER_FORM'], 25, 190);
			} elseif ($this->bill_mode == "proforma") {
				$this->backgoundBigWatermark($GLOBALS['STR_PROFORMA'], 40, 190);
			} elseif ($this->bill_mode == "devis" || $this->bill_mode == "quote_prepare") {
				$this->backgoundBigWatermark($GLOBALS['STR_PDF_QUOTATION'], 80, 200);
			} else {
				if (!empty($GLOBALS['site_parameters']['show_invoice_filigrane'])) {
					// Option spécifique show_invoice_filigrane nécessaire pour afficher le filigrane en cas de facture
					$this->backgoundBigWatermark($GLOBALS['STR_INVOICE'], 80, 200);
				}
			}
			if ($this->bill_mode != "user_custom_products_list") {
				if(in_array($this->order_object->statut_paiement, array('cancelled', 'refunded'))) { 
					$this->backgoundBigWatermark(get_payment_status_name($this->order_object->id_statut_paiement), 65, 470); 
				}
			}
		}
		if (!empty($GLOBALS['site_parameters']['header_on_each_page'])) {
			$this->get_document_header();
	}
	}
	
	/**
	 * Ajoute le footer automatiquement à chaque création de page
	 *
	 * @return string
	 */
	public function Footer() {
		if (empty($GLOBALS['site_parameters']['display_footer_disable'])) {
			if (empty($GLOBALS['site_parameters']['display_page_number_footer_disable'])) {
				// TCPDF va gérer tout seul le nombre total de page qu'il va remplacer à la fin, donc pas de soucis que cette fonction soit appelée au fur et à mesure de la création des pages
				$this->addPageNumber($this->getGroupPageNo() . ' / ' . $this->getPageGroupAlias(), 8, array('page_number_x'=>vn($GLOBALS['site_parameters']['page_number_x']), 'page_number_y'=>vn($GLOBALS['site_parameters']['page_number_y'])));
			}
			// AJOUT DES INFORMATIONS DE DERNIERE PAGE
			if (!empty($GLOBALS['site_parameters']['logo_footer_pdf'])) {
				// Permet par exemple de mettre un footer sous forme d'image sur toute la largeur
				$logo_footer_pdf = $GLOBALS['site_parameters']['logo_footer_pdf'];
				$logo_footer_pdf_x = $GLOBALS['site_parameters']['logo_footer_pdf_x'];
				$logo_footer_pdf_y = $GLOBALS['site_parameters']['logo_footer_pdf_y'];
				$logo_footer_pdf_w = $GLOBALS['site_parameters']['logo_footer_pdf_w'];
				$logo_footer_pdf_h = $GLOBALS['site_parameters']['logo_footer_pdf_h'];
				// Calcul de la taille finale de l'image, en respectant l'homotéthie
				$imgInfo = @getimagesize($logo_footer_pdf);
				$sourceW = $imgInfo[0];
				$sourceH = $imgInfo[1];
				if (!empty($sourceW) && !empty($sourceH)) {
					// on met au même format que celui de la taille demandée
					if ($sourceH * $logo_footer_pdf_w > $logo_footer_pdf_h * $sourceW) {
						$logo_footer_pdf_w = ($sourceW * $logo_footer_pdf_h) / $sourceH;
					} else {
						$logo_footer_pdf_h = ($sourceH * $logo_footer_pdf_w) / $sourceW;
					}
				}
				$this->Image($logo_footer_pdf, $logo_footer_pdf_x, $logo_footer_pdf_y, $logo_footer_pdf_w, $logo_footer_pdf_h);
			}
			if(!empty($GLOBALS['site_parameters']['invoice_footer_text'])) {
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 8);
				$ishtml = false;
				if (strip_tags($GLOBALS['site_parameters']['invoice_footer_text']) != $GLOBALS['site_parameters']['invoice_footer_text']) {
				// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
					$ishtml = true;
				}
				$this_text_html = StringMb::nl2br_if_needed($GLOBALS['site_parameters']['invoice_footer_text']);
				$this->MultiCell($GLOBALS['site_parameters']['invoice_footer_text_w'], $GLOBALS['site_parameters']['invoice_footer_text_h'], $this_text_html . "\n", 0,  vb($GLOBALS['site_parameters']['invoice_footer_text_align'], "C"), false, 1, $GLOBALS['site_parameters']['invoice_footer_text_x'], $GLOBALS['site_parameters']['invoice_footer_text_y'], true, 0, $ishtml);
			}
		}
	}
	
	/**
	 * generatePdfOrderContent()
	 *
	 * @return string
	 */
	function generatePdfOrderContent($column_formats, $doc_number, &$order_object, &$product_infos_array, $document_title = null) {
		$this->order_object = &$order_object;
		$bill_mode = &$this->bill_mode;
		
		if (!empty($order_object->id)) {
			$_SESSION['session_last_bill_viewed'] = vn($order_object->id);
		}
		$hook_result = call_module_hook('get_pdf_bill_order_infos', array('order_object' => $order_object, 'product_infos_array' => $product_infos_array, 'bill_mode' => $bill_mode), 'array');
		if (!empty($hook_result)) {
			$order_infos = $hook_result;
		} else {
			$order_infos = get_order_infos_array($order_object, $product_infos_array, $bill_mode);
		}
		$this->order_infos = $order_infos;
			
		if (function_exists('get_order_site_id')) {
			// On regarde si la commande est une commande lié à une demande de fincancement. Dans ce cas la commande prends le site_id du site funding, en remplacement du site_id par défaut de la campagne
			$order_object->site_id = get_order_site_id($order_object->id, $order_object->site_id);
		}
		
		if (empty($product_infos_array)) {
			// On rajoute un élément pour pouvoir passer dans la génération de page
			$product_infos_array[] = false;
		}
		if(!empty($GLOBALS['site_parameters']['invoice_pdf_remarque_font_size'])) {
			$this->remarque_font_size = $GLOBALS['site_parameters']['invoice_pdf_remarque_font_size'];
		}
		$comments_array = array();
		if(!empty($order_infos['delivery_infos'])) {
			$comments_array[] = $GLOBALS["STR_SHIPPING_TYPE"] . $GLOBALS["STR_BEFORE_TWO_POINTS"]. ': ' . $order_infos['delivery_infos'];
		}
		if(!empty($order_object->commentaires)) {
			$comments_array[] = $order_object->commentaires;
		}
		if(!empty($GLOBALS['site_parameters']['comment_specific_invoice'])) {
			$comments_array[] = vb($GLOBALS['site_parameters']['comment_specific_invoice']);
		}
		if(!empty($GLOBALS['site_parameters']['order_specific_field_titles']) && empty($order_infos['invoice_order_specific_field_titles_display_comment_disable'])) {
			foreach($GLOBALS['site_parameters']['order_specific_field_titles'] as $this_key => $this_value) {
				if (is_numeric($order_object->$this_key)) {
					$value = fprix($order_object->$this_key, true);
				} else {
					$value = $order_object->$this_key;
				}
				if (!empty($value)) {
					$comments_array[] = $this_value .' :'. $value;
				}
			}
		}
		$pdf_comments = call_module_hook('invoice_pdf_comments', array('order_object' => $order_object, 'bill_mode' => $bill_mode), 'string');
		$comments_array[] = $pdf_comments;
		if (strip_tags($pdf_comments) != $pdf_comments) {
			// Il y a des tags HTML dans le texte
			$comments_string = implode("<br />", $comments_array);
		} else {
			$comments_string = implode("\n", $comments_array);
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
				if (!empty($GLOBALS['site_parameters']['invoice_display_technical_id_as_document_id'])) {
					$this->document_id = intval($order_object->id);
				} else {
					$this->document_id = intval($order_object->order_id);
				}
			} elseif ($bill_mode == "proforma") {
				$this->document_name = StringMb::strtoupper($GLOBALS['STR_PROFORMA']);
				if (!empty($GLOBALS['site_parameters']['invoice_display_technical_id_as_document_id'])) {
					$this->document_id = intval($order_object->id);
				} else {
					$this->document_id = intval($order_object->order_id);
				}
			} elseif ($bill_mode == "bill_prepare" || $bill_mode == "bill_edit") {
				$this->document_name = StringMb::strtoupper($GLOBALS['STR_INVOICE']);
				$this->document_id = $order_object->Num_Fact;
			} elseif ($bill_mode == "devis" || $bill_mode == "quote_prepare") {
				$this->document_name = StringMb::strtoupper($GLOBALS['STR_PDF_QUOTATION']);
				if (!empty($GLOBALS['site_parameters']['invoice_display_technical_id_as_document_id'])) {
					$this->document_id = intval($order_object->id);
				} elseif(!empty($order_object->Num_Devis)) {
					$this->document_id = $order_object->Num_Devis;
				} else {
					$this->document_id = $order_object->order_id;
				}
			} else {
				if(!empty($order_object->numero)) {
					$this->document_name = StringMb::strtoupper($GLOBALS['STR_INVOICE']);
					$this->document_id = $order_object->numero;
					$this->order_numero_bdc = $order_object->order_id;
				} else {
					$this->document_name = StringMb::strtoupper($GLOBALS['STR_ORDER_FORM']);
					if (!empty($GLOBALS['site_parameters']['invoice_display_technical_id_as_document_id'])) {
						$this->document_id = intval($order_object->id);
					} else {
						$this->document_id = intval($order_object->order_id);
					}
				}	
				if(!empty($order_object->id_payment)) {
					$this->document_id .= ' - '.intval($order_object->id_payment);
				}
			}
		}
		
		if(!empty($document_title)) {
			// On force le nom de document avec $document_title
			$this->document_name = StringMb::strtoupper($document_title);
		}
		
		// On force le nom du document si il vient d'un hook. Ce nom est prioritaire sur le reste
		$hook_output = call_module_hook('invoice_pdf_document_name', array('order_object' => $order_object, 'bill_mode' => $bill_mode), 'array');
		if (!empty($hook_output['document_name'])) {
			$this->document_name = $hook_output['document_name'];
		}
		// On refera un test de saut de page juste avant l'affichage des remarques et blocs de fin
		$product_infos_array[] = null;
		if ($bill_mode == "user_custom_products_list") {
			$y_start_products = 75;
		} else {
			// start_product_cols_y1 start_y : contenu
			$y_start_products = vn($GLOBALS['site_parameters']['start_product_cols_y1']['start_y'], 100);
		}
		$y_max_allowed = $this->h_dispo + vn($GLOBALS['site_parameters']['invoice_full_page_addcols_bottom_offset'], 0); 
		//var_dump($this->h_dispo, $y_max_allowed);

		// OUVERTURE DU DOCUMENT ET CONFIGURATION GENERALE
		if (empty($doc_number)) {
			$this->Open();
		}
		$this->cMargin = 2;
		$this->SetAutoPageBreak(false, 10);
		$this->setPrintHeader(true); // Gestion des filigranes
		
		// Alignement du contenu des cellules de chaque ligne
		$this->addLineFormat($column_formats);

		// On active la gestion de page automatique
		$this->SetAutoPageBreak(true, $this->h - $y_max_allowed);
		$top_margin = vn($GLOBALS['site_parameters']['invoice_pdf_top_margin'], 15); // $this->tMargin a des valeurs parfois incohérentes, donc on va utiliser $top_margin à la place ensuite
		$this->SetTopMargin($top_margin);
		
		// Création de la première page (les autres seront gérées automatiquement)
		$this->startPageGroup();
		$this->AddPage();
		$first_page = $this->page;


		// La fonction getStringHeight ne marche pas correctement avec le HTML, donc on fait un calcul plus précis avec getRemarqueHeight
		// $this->comments_height = $this->getStringHeight($this->w - 10 * 2, implode("\n", $comments_array), false, true, '', '');
		// A FAIRE APRES AVOIR CREE LA PREMIERE PAGE
		$this->comments_height = $this->getRemarqueHeight($comments_string) + 4;
		// Quand on aura fini la liste des produits, on veut la place pour les blocs de fin de facture. L'affichage des blocs de fin de facture dépend du mode
		if ($bill_mode == "user_custom_products_list") {
			$y_max_allowed_last_page = $this->h_dispo - $this->comments_height - 9;
		} else {
			$y_max_allowed_last_page = $this->h_dispo - $this->comments_height - vn($GLOBALS['site_parameters']['bottom_offset'], 50) + 4;
		}

		if (empty($GLOBALS['site_parameters']['header_on_each_page'])) {
			// On n'affiche le header que sur la première page
			$this->get_document_header();
		} else {
			// On redéfinit la marge pour les pages suivantes pour tenir compte de la place de Header
			// En effet après appel du Header(), on sera à la position $y_start_products
			$this->SetTopMargin($y_start_products);
		}
		
		if (!empty($GLOBALS['site_parameters']['standalone_perso_line'])) {
			// Ligne de texte, inclassable, à placer ou l'on veut sur le document PDF.
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 10); 
			$y = vn($GLOBALS['site_parameters']['standalone_perso_line_y'], $y_start_products - 15);
			$x = vn($GLOBALS['site_parameters']['standalone_perso_line_x'], $this->GetX());
			foreach ($GLOBALS['site_parameters']['standalone_perso_line'] as $this_text) {
				$this->SetXY($x, $y);
				$ishtml = false;
				if (strip_tags($this_text) != $this_text) {
					// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
					$ishtml = true;
					$this_text = StringMb::nl2br_if_needed($this_text);
				}

				$this->MultiCell(90, 4, $this_text . "\n", 0, "L", false, 1, '', '', true, 0, $ishtml);
				$y += 5;
			}
		}

		$lines_count = 0;
		// Initialisation du début de l'affichage des produits
		$y = $y_start_products;

		// BOUCLE SUR L'ENSEMBLE DES LIGNES A AFFICHER
		// Pagination automatique sur N pages
		foreach($product_infos_array as $this_ordered_product) {
			if (!empty($this_ordered_product)) {
				$lines_count++;
				$prix = fprix($this_ordered_product["prix"], ($bill_mode != "user_custom_products_list"), $order_object->devise, true, $order_object->currency_rate);
				$prix_ht = fprix($this_ordered_product["prix_ht"], true, $order_object->devise, true, $order_object->currency_rate);
				$total_prix_ht = fprix($this_ordered_product["total_prix_ht"], true, $order_object->devise, true, $order_object->currency_rate);
				$total_prix = fprix($this_ordered_product["total_prix"], true, $order_object->devise, true, $order_object->currency_rate);
				$product_text = filtre_pdf($this_ordered_product["product_text"]);
				$hook_result = call_module_hook('bill_pdf_get_product_line', array('order_object' => $order_object, 'this_ordered_product' => $this_ordered_product, 'product_text' => $product_text, 'prix' => $prix, 'total_prix_ht' => $total_prix_ht, 'total_prix' => $total_prix, 'prix_ht' => $prix_ht), 'array');
				if (!empty($GLOBALS['site_parameters']['invoice_desingation_short'])) {
					// Preg pour supprimer les informations en trop dans $product_text : référence (déjà présente dans une autre colonne), remise...
					$patern_remp[1] = '#\\n.*#i';
					$remp[1] = '';
					$product_text = preg_replace($patern_remp, $remp, $product_text);
				}
				if(!empty($hook_result)) {
					$line = $hook_result;
				} else {
					if ($bill_mode != "user_custom_products_list") {
						if (!check_if_module_active('micro_entreprise')) {
							$line = array($GLOBALS['STR_PDF_REFERENCE'] => $this_ordered_product["reference"],
								$GLOBALS['STR_DESIGNATION'] => $product_text,
								$GLOBALS['STR_PDF_PRIX_HT'] => StringMb::html_entity_decode_if_needed($prix_ht),
								$GLOBALS['STR_PDF_PRIX_TTC'] => StringMb::html_entity_decode_if_needed($prix),
								$GLOBALS['STR_QUANTITY'] => $this_ordered_product["quantite"],
								$GLOBALS['STR_PDFTOTALHT'] => StringMb::html_entity_decode_if_needed($total_prix_ht),
								$GLOBALS['STR_PDFTOTALTTC'] => StringMb::html_entity_decode_if_needed($total_prix),
								$GLOBALS['STR_TAXE'] => "" . number_format($this_ordered_product['tva_percent'], 1) . "%");
						} else {
							$line = array($GLOBALS['STR_PDF_REFERENCE'] => $this_ordered_product["reference"],
								$GLOBALS['STR_DESIGNATION'] => $product_text,
								$GLOBALS['STR_PDF_PRIX_TTC'] => StringMb::html_entity_decode_if_needed($prix),
								$GLOBALS['STR_QUANTITY'] => $this_ordered_product["quantite"],
								$GLOBALS['STR_PDFTOTALTTC'] => StringMb::html_entity_decode_if_needed($total_prix));
						}
					} else {
						if (!empty($this_ordered_product["photo"])) {
							$this_thumb = thumbs($this_ordered_product["photo"], 50, 35);
							if (!empty($this_thumb) && file_exists($GLOBALS['uploaddir'].'/thumbs/'.StringMb::rawurlencode($this_thumb))) {
								// Image produit
								$this->Image($GLOBALS['uploaddir'].'/thumbs/'.StringMb::rawurlencode($this_thumb), 15, $y - 4);
							}
						}
						if (!empty($this_ordered_product["barcode_image_src"])) {
							// Code barre
							$this->Image($this_ordered_product['barcode_image_src'], 73, $y - 6, 58, 12);
						}
						$line = array($GLOBALS['STR_PHOTO'] => "",
							$GLOBALS['STR_DESIGNATION'] => $this_ordered_product["reference"]."\r\n".$product_text,
							$GLOBALS['STR_EAN_CODE'] => "\r\n\r\n\r\n".$this_ordered_product["ean_code"],
							$GLOBALS['STR_BRAND'] => $this_ordered_product["brand"],
							$GLOBALS['STR_CATEGORY'] => $this_ordered_product["category"],
							$GLOBALS['STR_PDF_PRIX_TTC'] => StringMb::html_entity_decode_if_needed($prix),
							$GLOBALS['STR_QUANTITY_SHORT'] => $this_ordered_product["quantite"],
							$GLOBALS['STR_START_PRICE'].' '.$GLOBALS['STR_TTC'] => $this_ordered_product["minimal_price"]);
					}
				}
				
				$this->addLine($y, $line, false, vb($_SESSION['RTFSizeCV'], 8));
				$y = $this->GetY() + 3;
				if($bill_mode == 'facture') {
					// Gestion de génération de fichier d'export reprenant des informations de facture (généré ici pour s'assurer que c'est exactement la même chose que dans les factures)
					$GLOBALS['accounting_export_bill_lines'][$this->document_id][] = $this_ordered_product;
				}
			}
		}
		if (!empty($order_infos['code_promo_text'])) {
			// On ajoute les informations de code promo dans le tableau avec addLine
			foreach($line as $this_key => $this_item) {
				$line[$this_key] = '';
			}
			$line[$GLOBALS['STR_DESIGNATION']] = $order_infos['code_promo_text'];
			$this->addLine($y, $line, false, vb($_SESSION['RTFSizeCV'], 8));
			$y = $this->GetY() + 5;
		}
		
		// On désactive la gestion de page automatique
		$this->SetAutoPageBreak(false, $this->h - $y_max_allowed);
		
		// On crée une dernière page si l'espace disponible n'est pas suffisant
		if($y >= $y_max_allowed_last_page) {
			// La dernière page créée automatiquement ne permet pas de mettre les informations de dernière page
			// Donc on crée manuellement une dernière page
			$this->AddPage();
			$no_products_last_page = true;
		} else {
			$no_products_last_page = false;
		}
		// GESTION DU CADRE PRINCIPAL sur toutes les pages générées contenant des lignes produits
		// On a besoin de savoir si c'est la dernière page ou pas pour définir la hauteur du cadre
		$pages_count = $this->getNumPages();
		for($this_page = $first_page; $this_page <= $pages_count; $this_page++) {
			$this->setPage($this_page);
			
			// On désactive la gestion de page automatique
			$this->pagedim[$this_page]['pb'] = false;

			// FILIGRANE
			// NB : le filigrane est automatiquement mis dans la fonction Header() appelée automatiquement à chaque saut de page automatique, car il faut qu'il soit créé avant le reste du texte
			
			// CADRE PRINCIPAL
			if($lines_count && ($this_page < $pages_count || !$no_products_last_page)) {
				// On dessine les colonnes de la page précédente, maintenant qu'on sait quelle taille cela a pris
				// La page précédente était avec produits sur hauteur totale
				if($this->bill_mode == 'user_custom_products_list') {
					$header_height = 8;
				} else {
					$header_height = 5;
				}
				if($this_page == $first_page || !empty($GLOBALS['site_parameters']['header_on_each_page'])) {
					// On démarre après les entêtes de la première page
					if($this->bill_mode == 'user_custom_products_list') {
						$y_begin_products = 60;
					} else {
						// start_product_cols_y1 addCols : cadre
						$y_begin_products = vb($GLOBALS['site_parameters']['start_product_cols_y1']['addCols'], 92);
					}
				} else {
					$y_begin_products = $top_margin - $header_height - 1;
				}
				if($this_page == $pages_count) {
					// Dessine le tableau pour la dernière page
					// La page en cours est avec produits sur hauteur restreinte, pour laisser de la place pour les blocs qui suivent
					$y_end_products = $y_max_allowed_last_page;
				} else {
					$y_end_products = $y_max_allowed;
				}
				$this->addCols($y_begin_products, $y_end_products, $header_height);
			}
			
			// FOOTER
			// Géré dans Footer() appelé automatiquement lors de la création automatique de page
		}
		$this->setPage($pages_count);

		if(!empty($comments_string)) {
			$this->addRemarque($comments_string, $y_max_allowed_last_page + $this->cMargin, $this->comments_height);
			$y = $y_max_allowed_last_page + $this->comments_height;
		} else {
			$y = $y_max_allowed_last_page + vn($GLOBALS['site_parameters']['y_max_allowed_last_page_without_comment']);
		}
		
		if ($bill_mode != "user_custom_products_list") {
			$cadre_net_w = 55;
			$cadre_tva_w = 50;
			$cadre_iban_at_bottom_w = $this->w - 10 - $cadre_net_w - 5 - $cadre_tva_w - 5 - 10; // 75 par défaut
			$cadre_total_height = vn($GLOBALS['site_parameters']['cadre_total_height'], 30);
			if ($bill_mode == "bdc" || !empty($GLOBALS['site_parameters']['quote_prepare_addCadreSignature'])) {
				// Bon de commande à signer
				$this->addCadreSignature($y, $cadre_total_height);
			} elseif (!empty($GLOBALS['site_parameters']['invoice_display_iban_at_bottom'])) {
				// Facture ou devis à payer par virement : on présente les informations bancaires
				$this->display_iban_at_bottom($y, $cadre_iban_at_bottom_w, $cadre_total_height, vb($order_object->Banque));
			}
			if (!empty($GLOBALS['site_parameters']['force_netbloc_y_without_remarques_height'])) {
				// Pour avoir le tableau de addCadreNet qui colle au cadre du tableau des codes ventes 
				$y = $y_max_allowed_last_page-4;
			}
			$this->addCadreNet($this->w - 10 - $cadre_net_w, $y, $cadre_net_w, $cadre_total_height, $order_infos['net_infos_array'], $order_infos['tva_infos_array']);
			if(empty($GLOBALS['site_parameters']['total_tva_in_net_bloc'])) {
				$this->addCadreTva($this->w - 10 - $cadre_net_w - 5 - $cadre_tva_w, $y, $cadre_tva_w, $cadre_total_height, $order_infos['tva_infos_array']);
			}
			$y = $y + $cadre_total_height;
			// Mentions pour TVA, centrées sous cadres liés à la TVA et au paiement
			if (empty($GLOBALS['site_parameters']['display_footer_disable'])) {
				$this->display_bottom_text($y + 5, $order_object->total_tva, $order_object->id_utilisateur);
			}
			$this->get_background_image('footer');
			if($bill_mode == 'facture') {
				// Gestion de génération de fichier d'export reprenant des informations de facture (généré ici pour s'assurer que c'est exactement la même chose que dans les factures)
				$order_infos['f_datetime'] = $order_object->f_datetime;
				$user_infos = get_user_information($order_object->id_utilisateur);
				$order_infos['pseudo'] = $user_infos['pseudo'];
				$order_infos['prenom'] = $user_infos['prenom'];
				$order_infos['nom_famille'] = $user_infos['nom_famille'];
				$order_infos['societe'] = $user_infos['societe'];
				$GLOBALS['accounting_export_bills'][$this->document_id] = $order_infos;
			}
		} else {
			if (!empty($GLOBALS['site_parameters']['add_copyright_on_pdf_file'])) {
				$y = $this->h_dispo - 21;
				$qid = query("SELECT * 
					FROM peel_societe
					WHERE " . get_filter_site_cond('societe') . " AND id_marques = 0
					ORDER BY site_id DESC
					LIMIT 1");
				$ligne = fetch_assoc($qid);
				$text = '<a href="' .get_url('/') . '">' . get_url('/') . '</a> Copyright ' . $GLOBALS['site'] . ' ' . date('Y').' / '.$GLOBALS['STR_TEL'].$GLOBALS['STR_BEFORE_TWO_POINTS'].': '.$ligne['tel'].' / '.$GLOBALS['STR_FAX'].$GLOBALS['STR_BEFORE_TWO_POINTS'].': '.$ligne['fax'].' / '.$ligne['email'] ;
				$this->writeHTMLCell("", 4, 10, $y, $text, 0, 1, false, true, "C");
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", 6);
				$text = template_tags_replace($GLOBALS['STR_INVOICE_BOTTOM_TEXT2']);
				$this->writeHTMLCell("", 4, 10, $y + 4, $text, 0, 1, false, true, "C");
			}
		}
		
		
		// FIN DE DERNIERE PAGE
		$annexe_html = $this->annexeHtml($product_infos_array);
		if(!empty($annexe_html)) {
			// On réactive la gestion de page automatique
			$this->SetAutoPageBreak(true, $this->h - $y_max_allowed);
			// On crée la première page de l'annexe. Les autres pages éventuellement nécessaires seront créées automatiquement
			$this->AddPage();
			// Mettre ici l'écriture du HTML de l'annexe
			$this->writeHTMLCell(null, null, 5, 5, $annexe_html);
		}
			
		if(!empty($GLOBALS['site_parameters']['content_on_new_page'])) {
			// On réactive la gestion de page automatique
			$this->SetAutoPageBreak(true, $this->h - $y_max_allowed);
			// On crée la première page de l'annexe. Les autres pages éventuellement nécessaires seront créées automatiquement
			$this->AddPage();
			// Mettre ici l'écriture du HTML de l'annexe
			$this->writeHTMLCell(null, null, 5, 5, $GLOBALS['site_parameters']['content_on_new_page'], 0, 1, false, true, "C");
		}
	}
	
	/**
	 * getSocieteInfoText()
	 *
	 * @param boolean $use_admin_rights
	 * @param boolean $skip_registration_number
	 * @param intval $site_id
	 * @param string $societe
	 * @return string
	 */
	function getSocieteInfoText($use_admin_rights = true, $skip_registration_number = false, $site_id = 0, $societe = null)
	{
		$output = '';
		$hook_output = call_module_hook('invoice_societe_info_text', array('Societe' => $societe), 'string');
		if (!empty($hook_output)) {
			$output = $hook_output;
		} else {
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
				$pdf_pays = filtre_pdf($ligne->pays) . "\n";
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
				if (trim($pdf_pays) == "") {
					$display_pays_separator = false;
				} else {
					$display_pays_separator = true;
				}
				if (!empty($GLOBALS['site_parameters']['PDFSocieteInfoText_condensed_mode'])) {
					$output = $pdf_societe . $pdf_adresse . $pdf_codepostal . " " . $pdf_ville . ($display_pays_separator?' - ':'') . $pdf_pays . str_replace("\n", " - ", $pdf_siret) . $pdf_tvaintra_company . str_replace("\n", " - ", $pdf_tel);
				} else {
					$output = $pdf_societe . $pdf_adresse . $pdf_codepostal . " " . $pdf_ville . ($display_pays_separator?' - ':'') . $pdf_pays . $pdf_siret . $pdf_tvaintra_company . $pdf_tel;
				}
				if (!empty($GLOBALS['site_parameters']['invoice_site_web_without_protocol'])) {
					$output .= str_replace(array("https://", "http://"), "" , $pdf_siteweb);
				} else {
					$output .= $pdf_siteweb;
				}
				$output .= "\n";
			} else {
				$output = null;
			}
		}
		
		if (!empty($GLOBALS['site_parameters']['SocieteInfoText'])) {
			$output = $output.$GLOBALS['site_parameters']['SocieteInfoText'];
		}
		return $output;
	}

	/**
	 * permet d'ajouter une page de contenu HTML qui va s'afficher à la suite de la facture.
	 *
	 * @param array $product_infos_array
	 * @return string
	 */
	function annexeHtml($product_infos_array = null)
	{
		$annexeHtml = null;
		$hook_result = call_module_hook('pdf_annexe_html', array('product_infos_array' => $product_infos_array), 'array');
		if (!empty($hook_result['annexeHtml'])) {
			$annexeHtml .= $hook_result['annexeHtml'];
		}
		return $annexeHtml;
	}

	/**
	 * @param string $location
	 *
	 * @return string
	 */
	function get_background_image($location)
	{
		if(!empty($GLOBALS['site_parameters'][$location.'_background_image'])) {
			// Permet par exemple de mettre un footer sous forme d'image sur toute la largeur
			$background_image = $GLOBALS['site_parameters'][$location.'_background_image'];
			$background_image_x = $GLOBALS['site_parameters'][$location.'_background_image_x'];
			$background_image_y = $GLOBALS['site_parameters'][$location.'_background_image_y'];
			$background_image_w = $GLOBALS['site_parameters'][$location.'_background_image_w'];
			$background_image_h = $GLOBALS['site_parameters'][$location.'_background_image_h'];
			// Calcul de la taille finale de l'image, en respectant l'homotéthie
			$imgInfo = @getimagesize($background_image);
			$sourceW = $imgInfo[0];
			$sourceH = $imgInfo[1];
			if (!empty($sourceW) && !empty($sourceH)) {
				// on met au même format que celui de la taille demandée
				if ($sourceH * $background_image_w > $background_image_h * $sourceW) {
					$background_image_w = ($sourceW * $background_image_h) / $sourceH;
				} else {
					$background_image_h = ($sourceH * $background_image_w) / $sourceW;
				}
			}
			$this->Image($background_image, $background_image_x, $background_image_y, $background_image_w, $background_image_h);
		}
	}
	
	/**
	 * Affiche l'entête des documents PDF. Normamelement utilisé 1 fois sur la première page, sauf si le paramètre header_on_each_page est actif
	 *
	 * @return
	 */
	function get_document_header()
	{
		$order_infos = &$this->order_infos;
		$order_object = &$this->order_object;
		$bill_mode = &$this->bill_mode;
		// DEBUT HEADER PAGE
		if(empty($GLOBALS['site_parameters']['display_header_disable'])) {
			$first_order = false;
			if (!empty($GLOBALS['site_parameters']['invoice_tag_first_order'])) {
				// Il faut determiner si la commande est la première du client
				$sql = 'SELECT c.id
					FROM peel_commandes c
					LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND ' . get_filter_site_cond('statut_paiement', 'sp') . '
					WHERE id_utilisateur = '.intval($order_object->id_utilisateur).' AND sp.technical_code IN ("being_checked","completed")
					ORDER BY c.o_timestamp ASC
					LIMIT 1';
				$query = query($sql);
				$result = fetch_assoc($query);
				if ($result['id'] == $order_object->id) {
					$first_order = true;
				}
			}
		
			$societeLogoPath = $this->getSocieteLogoPath($order_object->lang);
			$societeInfoText = $this->getSocieteInfoText(true, false, $order_object->site_id, vb($order_object->Societe));

			// image de fond du header
			$this->get_background_image('header');

			$this->addSociete($societeInfoText, $societeLogoPath);
			if (empty($GLOBALS['site_parameters']['fact_dev_display_disable'])) {
				// Affiche en haut, à droite le libelle (FACTURE, $GLOBALS['STR_DEVIS'], Bon de commande, etc...) et son numéro
				// La taille de la fonte est auto-adaptée au cadre
				$this->fact_dev($this->document_name, $this->document_id, false, $first_order);
			}
			if(empty($order_object->o_timestamp) || substr($order_object->o_timestamp, 0, 10) == '0000-00-00') {
				// On a besoin d'une date à afficher par défaut : si pas de date de commande, alors on prend la date du jour
				$order_object->o_timestamp = date('Y-m-d H:i:s');
			}
			if($bill_mode == "bdc" || $bill_mode == "devis" || $bill_mode == "bill_prepare" || $bill_mode == "quote_prepare" || $bill_mode == "user_custom_products_list") {
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
			if (empty($GLOBALS['site_parameters']['disable_date_rect'])) {
				$this->addDate($displayed_date, $order_infos['displayed_paiement_date']);
			}
			if ($bill_mode != "user_custom_products_list" && empty($order_infos['invoice_payment_method_display_disable'])) {
				$this->addReglement(StringMb::str_shorten(get_payment_name($order_object->paiement), 30) . ' - ' . $order_object->devise);
			}
			if ($bill_mode != "user_custom_products_list" && !empty($GLOBALS['site_parameters']['mode_transport']) && !empty($order_infos['client_infos_ship'])) {
				// Ajout de l'adresse de livraison seulement si la boutique a une gestion du port
				$this->addClientAdresseExpedition($order_infos['client_infos_ship']);
				$adresse_facturation_position = 'left';
			} else {
				$adresse_facturation_position = 'right';
				$this->addClientPersonnalizationInfo();
			}

			$this->addClientAdresseFacturation($order_infos['client_infos_bill'], $order_object->id_utilisateur, vb($GLOBALS['site_parameters']['adresse_facturation_position'], $adresse_facturation_position));
		} elseif (empty($GLOBALS['site_parameters']['fact_dev_display_disable'])) {
			// Affiche en haut, à droite le libelle (FACTURE, $GLOBALS['STR_DEVIS'], Bon de commande, etc...) et son numéro
			// La taille de la fonte est auto-adaptée au cadre
			$this->fact_dev($this->document_name, $this->document_id, false, $first_order);
		}
		// FIN HEADER PAGE
	}
	/**
	 * getSocieteLogoPath()
	 *
	 * @return string
	 */
	function getSocieteLogoPath($lang = null, $site_id = 1)
	{
		if (empty($site_id)) {
			// Si une commande est pour tous les sites
			$site_id = 1;
		}
		$pdf_logo = '';
		if (!empty($GLOBALS['site_parameters']['invoice_pdf_logo'])) {
			$pdf_logo = $GLOBALS['site_parameters']['invoice_pdf_logo'];
		} elseif (empty($GLOBALS['site_parameters']['force_custom_invoice_pdf_logo'])) {
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
				$wwwroot = get_configuration_variable('wwwroot', $site_id, $_SESSION['session_langue']);
				// on découpe le contenu du champ à la recherche du non de l'image fixe
				// ceci évitera d'envoyer la transmition du logo avec un chemin en http://
				$pdf_logo = StringMb::rawurldecode(str_replace($wwwroot, $GLOBALS['dirroot'], $pdf_logo));
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
		}
		return $pdf_logo;
	}

	function getRemarqueHeight($comments_string) {
		// This method return the estimated height needed for printing a simple text string using the Multicell() method.
		// Generally, if you want to know the exact height for a block of content you can use the following alternative technique:
		// store current object
		$this->startTransaction();
		// store starting values
		$start_y = $this->GetY();
		$start_page = $this->getPage();
		// call your printing functions with your parameters
		$this->addRemarque($comments_string, $start_y); 

		// get the new Y
		$end_y = $this->GetY();
		$end_page = $this->getPage();
		// calculate height
		$height = 0;
		if ($end_page == $start_page) {
		   $height = $end_y - $start_y;
		} else {
		   for ($page=$start_page; $page <= $end_page; ++$page) {
			   $this->setPage($page);
			   if ($page == $start_page) {
				   // first page
				   $height = $this->h - $start_y - $this->bMargin;
			   } elseif ($page == $end_page) {
				   // last page
				   $height = $end_y - $this->tMargin;
			   } else {
				   $height = $this->h - $this->tMargin - $this->bMargin;
			   }
		   }
		}
		// restore previous object
		$this->rollbackTransaction(true);
		return $height;
	}
}
