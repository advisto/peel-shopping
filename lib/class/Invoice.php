<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: Invoice.php 67425 2021-06-28 12:27:13Z sdelaporte $
if (!defined('IN_PEEL')) {
	die();
}
use Atgp\FacturX\Facturx;

define('EURO', chr(128));
define('EURO_VAL', 6.55957);

require_once($GLOBALS['dirroot'] . "/lib/class/pdf/tcpdf.php");


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
 * @version $Id: Invoice.php 67425 2021-06-28 12:27:13Z sdelaporte $
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

    public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {
        // call parent constructor
        parent::__construct($orientation, $unit, $format, $unicode, $encoding, $diskcache, $pdfa);
        // disable the tcpdf link

        $this->setTcpdfLink(false);
    }

    /**
     * Allows to disable the invisible "Powered by www.tcpdf.org" link at the bottom of the page.
     * @param type $tcpdflink
     */
    public function setTcpdfLink($tcpdflink = true) {
        $this->tcpdflink = $tcpdflink ? true : false;
    }
	
	/**
	 * Fonction pour fixer la hauteur du tableau de ligne de produit. Cette valeur est modulable via un hook
	 *
	 * @return
	 */
	function setHdispo() {
		// h_dispo est la hauteur de page hors pied de page sous forme d'image et hors pagination, ou autre limitation de hauteur
		// NB : La numérotation des pages aura lieu 8 mm (ou page_number_offset_y si défini) en-dessous de h_dispo
		// Habituellement h_dispo vaut donc 289 mm pour du A4
		// bill_footer_height : permet de définir une taille personnalisé du footer sur chacune des pages de la facture
		$this->h_dispo = $this->h - ($this->tMargin - 10) - ($this->bMargin - 20) -  vn($GLOBALS['site_parameters']['bill_footer_height']) - vn($GLOBALS['site_parameters']['page_number_offset_y'], 10);

		// La largeur disponible du document w_dispo est défini en fonction de la largeur total - les marges par défaut qui s'applique sur $this->w - les marges défini pour ce document
		$this->w_dispo = $this->w - ($this->lMargin) - ($this->rMargin);
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
				$destinationW = 0;
				$destinationH = 0;
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
			}
			// Positionnement du logo (a priori à droite des informations sur la société)
			if(strpos($logo, '.svg') !== false) {
				// Il faut préférer apparemment SVG quand on veut du vectoriel
				$this->ImageSVG($logo, $x_logo, $y_logo, $destinationW, $destinationH);
			} elseif(strpos($logo, '.ai') !== false || strpos($logo, '.eps') !== false) {
				$this->ImageEps($logo, $x_logo, $y_logo, $destinationW, $destinationH);
			} else {
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
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['global_font_size'], 10));
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
					$this->SetFillColor($c_array[0], $c_array[1], $c_array[2]);
				}
				if (!empty($GLOBALS['site_parameters']['address_societe_text_font_color'])) {
					$this->SetTextColor($GLOBALS['site_parameters']['address_societe_text_font_color'][0], $GLOBALS['site_parameters']['address_societe_text_font_color'][1], $GLOBALS['site_parameters']['address_societe_text_font_color'][2]);
				}
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), '', vn($GLOBALS['site_parameters']['invoice_societe_font_size'],10));
				// Coordonnées de la société
				$ishtml = false;
				if (strip_tags($adresse) != $adresse) {
					// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
					$ishtml = true;
					$adresse = StringMb::nl2br_if_needed($adresse);
				}
				
				$this->MultiCell(vn($GLOBALS['site_parameters']['addSociete_text_w'], 80), vn($GLOBALS['site_parameters']['addSociete_text_h'], 4), $adresse, 0, vn($GLOBALS['site_parameters']['addSociete_text_align'], 'L'), !empty($GLOBALS['site_parameters']['invoice_societe_background_color_array']), 1, '', '', true, 0, $ishtml);
				
				if (!empty($GLOBALS['site_parameters']['addSociete_font_color'])) {
					// on repasse en noir, pour ne pas impacter le reste du document
					$this->SetTextColor(0, 0, 0);
				}
				if (!empty($GLOBALS['site_parameters']['address_societe_text_font_color'])) {
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
		if (!empty($GLOBALS['site_parameters']['fact_dev_libelle_uppercase'])) {
			$libelle = StringMb::strtoupper($libelle);
		}
		if ($this->bill_mode == 'user_custom_products_list') {
			$y = 25;
			$h = 9;
		} else {
			$y = vn($GLOBALS['site_parameters']['fact_dev_y'], 6);
			$h = vn($GLOBALS['site_parameters']['fact_dev_h'], 9);
		}
		$x = $this->w - ($this->rMargin - 10) - vn($GLOBALS['site_parameters']['fact_dev_r'], 100);
		$w = vn($GLOBALS['site_parameters']['fact_dev_w'], 90);

		$mid = $x + ($w / 2);
		if (!empty($GLOBALS['site_parameters']['fact_dev_texte'])) {
			$texte = $GLOBALS['site_parameters']['fact_dev_texte'];
		} elseif (empty($GLOBALS['site_parameters']['fact_dev_num_fact_disable']) && strlen($num)>0) {
			if (!empty($GLOBALS['site_parameters']['fact_dev_num_fact_second_line'])) {
				$texte = $libelle . " <br /> N° " . $num;
			} else {
				$texte = $libelle . " N° " . $num;
			}
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
		if (empty($GLOBALS['site_parameters']['fact_dev_auto_correct_font_size_disable'])) {
			while ($loop == 0) {
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), (empty($GLOBALS['site_parameters']['fact_dev_bold_disable'])?"B":'').(!empty($GLOBALS['site_parameters']['fact_dev_underline'])?"U":''), $szfont);
				$sz = $this->GetStringWidth($texte);
				if ($sz > $w)
					$szfont --;
				else
					$loop ++;
			}
		} else {
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), (empty($GLOBALS['site_parameters']['fact_dev_bold_disable'])?"B":'').(!empty($GLOBALS['site_parameters']['fact_dev_underline'])?"U":''), $szfont);
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
			if (!empty($GLOBALS['site_parameters']['fact_dev_square_rect'])) {
				$this->Rect($x, $y, $w, $h, 'DF');
			} else {
				$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'DF');
			}
		}

		$this->SetXY($x + 1, $y + 2);
		if (!empty($GLOBALS['site_parameters']['fact_dev_new_border_width'])) {
			// C'est utile pour afficher une bordure au coin carré dont l'épaisseur est modifiable. Le border de Cell est plus paramétrable que InvoiceRoundedRect
			// * @param $border (mixed) Indicates if borders must be drawn around the cell. The value can be a number:<ul><li>0: no border (default)</li><li>1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
			$border = array('LTRB' => array('width' => 2));
		} else {
			$border = 0;
		}
		$ishtml = false;
		if (strip_tags($texte) != $texte) {
			// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
			$ishtml = true;
			$texte = StringMb::nl2br_if_needed($texte);
		}
		$this->MultiCell($w - 1, 5, $texte, $border, 'C', false, 1, '', '', true, 0, $ishtml);

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
		// Avant on avait -100, mais en corrigeant la marge par défaut de 10 on fait -90
		$x = $this->w - ($this->rMargin) - 90;
		if ($this->bill_mode == 'user_custom_products_list') {
			$y = 38;
		} else {
			$y = vb($GLOBALS['site_parameters']['addDate_y'], 17);
		}
		$width = 90;
		$height = 12;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($x, $y, $width, $height, 'D');
		$this->Rect($x, $y, $width, $header_height, 'DF');
		if (!empty($GLOBALS['site_parameters']['addDate_bold_disable'])) {
			$bold = "";
		} else {
			$bold = "B";
		}
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), $bold, vn($GLOBALS['site_parameters']['global_font_size'], 10));
		$this->SetXY($x, $y + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_BILL_DATE'], 0, 0, "C");

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['global_font_size'], 10));
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
	function display_bottom_text($y, $order_object)
	{
		$x = vn($GLOBALS['site_parameters']['bottom_text_x'], $this->lMargin);
		$w = vn($GLOBALS['site_parameters']['bottom_text_w'], 0); // Si 0 : on va jusqu'au bord droit du document
		if (!empty($GLOBALS['site_parameters']['invoice_bottom_text_y'])) {
			// On souhaite forcer la position en hauteur du contenu
			$y = $GLOBALS['site_parameters']['invoice_bottom_text_y'];
		}	
		if (!empty($GLOBALS['site_parameters']['invoice_bottom2_text_y'])) {
			// On souhaite forcer la position en hauteur du contenu
			$y2 = $GLOBALS['site_parameters']['invoice_bottom2_text_y'];
		}
		if (!empty($GLOBALS['site_parameters']['invoice_bottom_text_y_relative_correction'])) {
			// On souhaite forcer la position en hauteur du contenu
			$y -= $GLOBALS['site_parameters']['invoice_bottom_text_y_relative_correction'];
		}
		if (empty($GLOBALS['site_parameters']['invoice_bottom_text_disable'])) {
			if (isset($GLOBALS['site_parameters']['invoice_bottom_text'])) {
				$text1 = $GLOBALS['site_parameters']['invoice_bottom_text'];
			} else {
				$text1 = ($this->bill_mode == 'devis' || $this->bill_mode == 'quote_prepare'?$GLOBALS['STR_INVOICE_BOTTOM_TEXT1']:$GLOBALS['STR_INVOICE_BOTTOM_TEXT']);
			}
		}
		if(!empty($text1)) {
			$this->SetXY($x, $y);
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['display_bottom_text_font_size'], 8));
			$ishtml = false;
			if (strip_tags($text1) != $text1) {
				// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
				$ishtml = true;
			}
			if ($ishtml) {
				$this_text_html = StringMb::nl2br_if_needed($text1);
				$this->MultiCell($w, 4, $this_text_html . "\n", vn($GLOBALS['site_parameters']['bottom_text_border_size'], 0),  vb($GLOBALS['site_parameters']['bottom_text_align'], "C"), false, 1, '', '', true, 0, $ishtml);
			} else {
				$this->Cell($w, 4, $text1, 0, 0, "C");
			}
		}
		if (floatval($order_object->total_tva)==0 && empty($GLOBALS['site_parameters']['force_display_invoice_bottom_text2'])) {
			if (check_if_module_active('micro_entreprise')) {
				// Pour les entreprises bénéficiant du régime de franchise de base de TVA, il faut obligatoirement porter sur chaque facture la mention suivante : « TVA non applicable, article 293 B du CGI ».
				// => Déjà géré par l'appel à addTVAs_part_micro
			} elseif(!empty($GLOBALS['STR_INVOICE_BOTTOM_TEXT2']) && is_user_tva_intracom_for_no_vat($order_object->id_utilisateur)) {
				// Pour les livraisons de biens intracommunautaires, les factures doivent obligatoirement comporter la mention suivante : « Exonération de TVA, article 262 ter 1 du CGI ».
				// Lorsqu'il s'agit de prestations de services intracommunautaires dont la taxe est autoliquidée par le preneur, il faudra faire figurer, à notre sens, les mentions « TVA due par le preneur, art. CGI 283-2, et art. 194 de la directive TVA 2006/112/CE »
				// => Texte à définir en conséquence en fonction de votre site dans $GLOBALS['STR_INVOICE_BOTTOM_TEXT2']
				$text2 = $GLOBALS['STR_INVOICE_BOTTOM_TEXT2'];
				$this->SetXY($x, $y + 4);
				$this->Cell($w, 4, $text2, 0, 0, "C");
			}
		} elseif(!empty($GLOBALS['site_parameters']['invoice_bottom_text2'])) {
			$text2 = $GLOBALS['site_parameters']['invoice_bottom_text2'];
			if (!empty($GLOBALS['site_parameters']['invoice_bottom_text2_gety'])) {
				$this->SetXY($x, $this->GetY());
			} else {
				$this->SetXY($x, vn($GLOBALS['site_parameters']['invoice_bottom_text2_y'], $y + 4));
			}
			if (strip_tags($text2) != $text2) {
				// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
				$ishtml = true;
			}
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['display_bottom_text2_font_size'],8));
			if ($ishtml) {
				$this_text_html = StringMb::nl2br_if_needed($text2);
				$this->MultiCell($w, 4, $this_text_html . "\n", vn($GLOBALS['site_parameters']['bottom_text2_border_size'], 0),  vb($GLOBALS['site_parameters']['bottom_text2_align'], "C"), false, 1, '', '', true, 0, $ishtml);
			} else {
				$this->Cell($w, 4, $text2, 0, 0, "C");
			}
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
		if(empty($GLOBALS['site_parameters']['page_number_display_disable']) && (!isset($GLOBALS['site_parameters']['page_number_offset_y']) || !empty($GLOBALS['site_parameters']['page_number_offset_y']))) {
			// Si $GLOBALS['site_parameters']['page_number_offset_y'] vaut 0 => la page n'est pas correctement terminée en cas de facture sur plusieurs page. On uilise donc page_number_display_disable pour ne pas afficher la pagination, et on laisse page_number_offset_y qui permet de cloturer correctement le bas de page.
			if (empty($position['page_number_x'])) {
				// ici on ne corrige pas la valeur de x avec la marge, puisque l'on veut que la pagination soit centré par rapport au bord du document, indépendamment des marges
				$x = ($this->w  / 2) - 15;
			} else {
				$x = $position['page_number_x'];
			}
			if (empty($position['page_number_y'])) {
				$y = $this->h_dispo + 3;
			} else {
				$y = $position['page_number_y'];
			}

			$this->SetXY($x, $y);
			// il faut forcer "helvetica" ici, sinon le nombre total de page est mal décodé
			$this->SetFont("helvetica", "", $font_size);
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
			$x = $this->w - ($this->rMargin - 10) - vn($GLOBALS['site_parameters']['ClientAdresseFacturation_x'],100);
			$y = vb($GLOBALS['site_parameters']['ClientAdresseFacturation_y'], 45);
		} else {
			$x = $this->w - ($this->rMargin - 10) - vn($GLOBALS['site_parameters']['ClientAdresseFacturation_x'],200);
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
			if (!empty($GLOBALS['site_parameters']['ClientAdresseFacturation_round_rect'])) {
				$this->InvoiceRoundedRect($x, $y + vn($GLOBALS['site_parameters']['ClientAdresseFacturation_rect_offset_y']), $width, $height, 2, "D");
			} else {
				$this->Rect($x, $y + vn($GLOBALS['site_parameters']['ClientAdresseFacturation_rect_offset_y']), $width, $height, 'D');
			}
		}
		if (empty($GLOBALS['site_parameters']['ClientAdresseFacturation_header_rect_disable'])) {
			$this->Rect($x, $y, $width, $header_height, 'DF');
		}
		if (!empty($GLOBALS['site_parameters']['ClientAdresseFacturation_bold_disable'])) {
			$bold = "";
		} else {
			$bold = "B";
		}
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), $bold, vn($GLOBALS['site_parameters']['ClientAdresseFacturation_font_size'], 10));
		$this->SetXY($x, $y + 0.5);
		
		if (empty($GLOBALS['site_parameters']['ClientAdresseFacturation_header_disable'])) {
			$title = $GLOBALS['STR_PDF_FACTURATION'];
			if (!empty($id_utilisateur) && empty($GLOBALS['site_parameters']['ClientAdresseFacturation_id_utilisateur_header_disable'])) {
				$title .= ' ' . $id_utilisateur;
			}
			$this->Cell(90, 4, $title, 0, 0, vb($GLOBALS['site_parameters']['ClientAdresseFacturation_title_alignement'], "C"));
		}
		
		if (!empty($GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'])) {
			$this->SetTextColor($GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'][0], $GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'][1], $GLOBALS['site_parameters']['ClientAdresseFacturation_font_color'][2]);
		}
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['ClientAdresseFacturation_font_size'], 10));
		$this->SetXY($x + vn($GLOBALS['site_parameters']['ClientAdresseFacturation_cell_offset_x']), $y + $this->cMargin + $header_height);
		$ishtml = false;
		if (strip_tags($pdf_facturation) != $pdf_facturation) {
			// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
			$ishtml = true;
			$pdf_facturation = StringMb::nl2br_if_needed($pdf_facturation);
		}
		if (empty($GLOBALS['site_parameters']['ClientAdresseFacturation_add_empty_line'])) {
			// si ClientAdresseFacturation_add_empty_line alors on veut une ligne vide entre deux champs de l'adresse
			$pdf_facturation = str_replace(array("<br />\r\n<br />", "<br />\n<br />", "<br>\r\n<br>", "<br>\n<br>"), "<br />", $pdf_facturation);
		}
		// var_dump($pdf_facturation);
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
		if (!empty($hook_result['bloc_header_perso_title']) || !empty($hook_result['bloc_header_perso_text']) || !empty($GLOBALS['site_parameters']['bloc_perso_display_page_number'])) {
			if (!empty($hook_result['addClientPersonnalizationInfo_x'])) {
				$x = $hook_result['addClientPersonnalizationInfo_x'];
			} else {
				$x = $this->w - ($this->rMargin - 10) - vn($hook_result['addClientPersonnalizationInfo_r'], 200);
			}
			$y = vn($hook_result['addClientPersonnalizationInfo_y'], 40);
			$w = vn($hook_result['addClientPersonnalizationInfo_w'], 90);
			$h = vn($hook_result['addClientPersonnalizationInfo_h'], 4);

			if(!empty($hook_result['bloc_perso_frame_enable'])) {
				if (!empty($hook_result['bloc_perso_background_color'])) {
					// ne fonctionne pas, comprends pas pourquoi.
					$this->SetFillColor($hook_result['bloc_perso_background_color'][0], $hook_result['bloc_perso_background_color'][1], $hook_result['bloc_perso_background_color'][2]);
				}
				if (!empty($hook_result['bloc_perso_frame_round_rect'])) {
					$this->InvoiceRoundedRect($x, $y+5, $w, $hook_result['bloc_perso_frame_height'], 2, "D");
				} else {
					$this->Rect($x, $y+5, $w, $hook_result['bloc_perso_frame_height'], "D", vb($hook_result['bloc_perso_frame_border_array'], array()));
				}
			}
			if (!empty($GLOBALS['site_parameters']['bloc_perso_display_page_number'])) {
				// TCPDF va gérer tout seul le nombre total de page qu'il va remplacer à la fin, donc pas de soucis que cette fonction soit appelée au fur et à mesure de la création des pages
				$this->addPageNumber($this->getGroupPageNo() . ' / ' . $this->getPageGroupAlias(), vn($GLOBALS['site_parameters']['PageNumber_font_size'], 8), array('page_number_x'=>$GLOBALS['site_parameters']['bloc_perso_page_number_x'], 'page_number_y'=>$GLOBALS['site_parameters']['bloc_perso_page_number_y']));
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

				if (!empty($GLOBALS['site_parameters']['bloc_header_perso_text_font_color'])) {
					$this->SetTextColor($GLOBALS['site_parameters']['bloc_header_perso_text_font_color'][0], $GLOBALS['site_parameters']['bloc_header_perso_text_font_color'][1], $GLOBALS['site_parameters']['bloc_header_perso_text_font_color'][2]);
				}
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
				if (!empty($GLOBALS['site_parameters']['bloc_header_perso_text_font_color'])) {
					$this->SetTextColor(0, 0, 0);
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
		// Avant on avait -100 mais en prennant en compte la marge par défaut à droite de 10 ça fait 90
		$x = $this->w - ($this->rMargin) - 90;
		$y = vb($GLOBALS['site_parameters']['ClientAdresseExpedition_y'], 45);
		$w = vb($GLOBALS['site_parameters']['ClientAdresseExpedition_w'], 90);
		$h = vb($GLOBALS['site_parameters']['ClientAdresseExpedition_h'], 40);
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($x, $y, $w, $h, 'D');
		$this->Rect($x, $y, $w, $header_height, 'DF');

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['global_font_size'], 10));
		$this->SetXY($x, $y + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_LIVRAISON'], 0, 0, "C");

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['global_font_size'], 10));
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
		// Avant on avait -100 mais en prennant en compte la marge par défaut à droite de 10 ça fait 90
		$x = $this->w - ($this->rMargin) - 90;
		$y = 29;
		$w = 90;
		$h = 12;
		$header_height = 5;

		$this->SetFillColor(240, 240, 240);
		$this->Rect($x, $y, $w, $h, 'D');
		$this->Rect($x, $y, $w, $header_height, 'DF');

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['global_font_size'], 10));
		$this->SetXY($x, $y + 0.5);
		$this->Cell(90, 4, $GLOBALS['STR_PDF_PAIEMENT'], 0, 0, "C");

		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['global_font_size'], 10));
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
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['global_font_size'], 10));
		$this->Cell(10, 4, $GLOBALS['STR_PDF_DUE_DATE'], 0, 0, "C");
		$this->SetXY($x + $w / 2 - 5 , $y + 5);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['global_font_size'], 10));
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
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['global_font_size'], 10));
		$length = $this->GetStringWidth($GLOBALS['STR_PDF_REF'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . $ref);
		$x = $this->lMargin;
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
	 * @return
	 */
	function addCols($y_begin_products, $y_end_products, $header_height)
	{
		$x = $this->lMargin;
		// avant on avait la formule $this->w - ($x * 2), ce qui correspond maintenant à w_dispo
		$w = $this->w_dispo;
		
		$this->SetXY($x, $y_begin_products);
		
		if(!empty($GLOBALS['site_parameters']['addCols_header_font_size'])) {
			$font_size = $GLOBALS['site_parameters']['addCols_header_font_size'];
		} elseif(!empty($GLOBALS['site_parameters']['global_font_size'])) {
			$font_size = $GLOBALS['site_parameters']['global_font_size'];
		} else {
			$font_size = 8;
		}
		if(empty($GLOBALS['site_parameters']['cv_frame_disable'])) {
			$height = $y_end_products - $y_begin_products;
			if (!empty($GLOBALS['site_parameters']['addCols_round_rect'])) {
				$this->InvoiceRoundedRect($x, $y_begin_products, $w, $height, 2, "D");
			} else {
				$this->Rect($x, $y_begin_products, $w, $height, "D");
			}
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
			foreach($this->colonnes as $lib => $width) {
				$this->SetXY($x, $y_begin_products + 1);
				if (!empty($GLOBALS['site_parameters']['cv_header_content_align'][$lib])) {
					$alignement = $GLOBALS['site_parameters']['cv_header_content_align'][$lib];
				} else {
					$alignement = get_string_from_array(vb($GLOBALS['site_parameters']['cv_header_content_align'], array("C")), true);
				}
				if($this->bill_mode != 'user_custom_products_list') {
					if (!empty($GLOBALS['site_parameters']['cv_header_content_upper'])) {
						$lib = StringMb::strtoupper($lib);
					}
					$this->Cell($width, $header_height-1, $lib, 0, 0, $alignement);
				} else {
					$this->MultiCell($width, $header_height-1, $lib, 0, 0, $alignement);
				}
				$x += $width;
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
		$x_start_line = $this->lMargin;
		$x = $x_start_line;
		if($this->bill_mode == 'user_custom_products_list') {
			$y_begin = $y_begin-3;
		}
		if(!empty($cells_array['subtitle'])) {
			// Ajout de sous-titre dans la facture, avant d'écrire une ligne
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", $font_size);
			$this->writeHTMLCell(0, 0, $x, $y_begin, $cells_array['subtitle'], 0, 1, false, true, 'L');
			$y_begin = $this->GetY() + 3;
		}
		if(!empty($this->colonnes)) {
			$start_page = $this->getPage();    
			$end_page = $start_page;
			$last_y = $this->GetY();
			foreach($this->colonnes as $lib => $pos) {
				$first_case_html = false;
				$texte = vb($cells_array[$lib]);
				if($this->bill_mode == 'user_custom_products_list') {
					$this_font_size = 7;
				//} elseif ($texte === 'Total' || $lib == 'Total HT') {
				//	$this_font_size = $font_size + 2;
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
				if ((strpos($text_array[0], '<p') !== false) || (strpos($text_array[0], '<div') !== false)) {
					// la première case contient du HTML. Ne pas utiliser strip_tags parce qu'il prend également en compte les tags <##> ce qui créer un faux positif
					$first_case_html = true;
				}
				if (!empty($text_array[1]) && !empty($GLOBALS['site_parameters']['libelle_underline'])) {
					// si il y a du détail HTML, on souligne le libellé
					$text_array[0] = '<u>'.$text_array[0].'</u>';
				}
				$this->writeHTMLCell($longCell, $line_height, $x, $y_begin, add_or_remove_primary_container_in_html(get_clean_html_for_pdf(StringMb::nl2br_if_needed($text_array[0])), 'add', array('font-size' => $this_font_size . 'px')), 0, 1, false, true, $formText);
				
				// $this->MultiCell($longCell, $line_height, $text_array[0], 0, $formText, $fill);
				//var_dump(add_or_remove_primary_container_in_html(get_clean_html_for_pdf($text_array[0]), 'add', array('font-size' => $this_font_size.'px')));
				// $this->writeHTMLCell($longCell, $line_height, $x, $y_begin, strip_tags($text_array[0]), 0, 1, false, true, $formText);
				// $this->writeHTMLCell($longCell, $line_height, $x, $y_begin, $text_array[0], 0, 1, false, true, $formText);
				
				if (!empty($text_array[1])) {
					if (!empty($GLOBALS['site_parameters']['libelleDetail_html_italic_bold'])) {
						$details_html['text'] = '<b><i>' . $text_array[1] . '</i></b>';
					} else {
						$details_html['text'] = $text_array[1];
					}
					if (!empty($GLOBALS['site_parameters']['libelleDetail_html_add_blank_line'])) {
						$details_html['text'] = '<br /><br />'.$details_html['text'];
					}
					$details_html['w'] = $pos;
					$details_html['x'] = $x;
					// il faut laisser $this->GetY() ici puisque la cellule au dessus a une hauteur variable
					if (!empty($first_case_html)) {
						// si la première case contient du HTML, il faut corriger GetY qui surévalue la hauteur prise par le HTML
						$details_html['y'] = $this->GetY() - 5;
					} else {
						$details_html['y'] = $this->GetY();
					}
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
		// Avant on avait -31 mais en prennant en compte la marge par défaut à droite de 10 ça fait 21
		$x = $this->w - ($this->rMargin) - 21;
		$w = 19;
		$y = 100;
		$y2 = $y;
		$mid = $y + ($y2 / 2);
		$this->SetXY($x + $w / 2 - 5, $y + 3);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['global_font_size'], 10));
		$this->Cell(10, 5, $GLOBALS['STR_PDF_TOTAL_HT'], 0, 0, "C");
		$this->SetXY($x + $w / 2 - 5, $y + 9);
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['global_font_size'], 10));
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
		$x = $this->lMargin;
		$w = $this->w_dispo + ($this->lMargin + $this->rMargin) - ($x * 2) ;

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
	 * Trace le cadre des TVAs : désactivé, à retravailler si on veut l'utiliser
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
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['global_font_size'], 10));
		$x = $this->lMargin;
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
	 * Invoice::display_address_at_bottom()
	 *
	 * @param integer $y
	 * @param integer $w
	 * @param integer $h
	 * @return
	 */
	function display_soc_address_at_bottom($y, $w, $h, $order_object)
	{
		$x = vn($GLOBALS['site_parameters']['address_at_bottom_x'], $this->lMargin);
		if (!empty($GLOBALS['site_parameters']['address_at_bottom_h'])) {
			// on veut une taille spécifique pour le bloc adresse du footer
			$h = $GLOBALS['site_parameters']['address_at_bottom_h'];
		}
		$output = $this->getSocieteInfoText(true, false, $order_object->site_id, vb($order_object->Societe), array('display_email_disable' => true));
		if (!empty($order_object->societe_siren)) {
			$output .= "\r\n";
			$output .= 'SIRET : ' . vb($order_object->societe_siren);
		}
		if (!empty($output)) {
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['invoice_display_address_at_bottom_title_font_size'], vn($GLOBALS['site_parameters']['global_font_size'], 10)));
			if (!empty($GLOBALS['site_parameters']['address_at_bottom_not_round_rect'])) {
				$this->Rect($x, $y, $w, $h, 'D');
			} else {
				$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'D');
			}
			$this->SetXY($x, $y);
			/*
				if (!empty($GLOBALS['site_parameters']['address_at_bottom_bank_name_in_title'])) {
					$title = $result['Nom1'];
				} elseif(!empty($GLOBALS['site_parameters']['invoice_display_address_at_bottom_title'])) {
					$title = $GLOBALS['site_parameters']['invoice_display_address_at_bottom_title'];
				} else {
					$title = $GLOBALS['STR_TRANSFER'];
				}
				$this->Line($x, $y + 6, $x + $w, $y + 6);
				if (!empty($GLOBALS['site_parameters']['address_at_bottom_background_color_title'])) {
					$this->SetFillColor($GLOBALS['site_parameters']['address_at_bottom_background_color_title'][0], $GLOBALS['site_parameters']['address_at_bottom_background_color_title'][1], $GLOBALS['site_parameters']['address_at_bottom_background_color_title'][2]);
					// pour ajouter une couleur de fond à la cellule, il faut mettre le 7ème paramètre fill à true
					$fill = true;
				} else {
					$fill = false;
				}
				// cellule pour le titre
				$this->Cell($w, 6, $title, 0, 0, "C", $fill);
				$this->SetXY($x + 1, $y+7);
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), '', vn($GLOBALS['site_parameters']['invoice_display_address_at_bottom_font_size'], vn($GLOBALS['site_parameters']['global_font_size'], 10)));
			*/
			// $length = $this->GetStringWidth($rib);
			$this->MultiCell($w, 7, $output, 0, "L");
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
		$x = vn($GLOBALS['site_parameters']['iban_at_bottom_x'], $this->lMargin);
		if (!empty($GLOBALS['site_parameters']['iban_at_bottom_h'])) {
			// on veut une taille spécifique pour le bloc IBAN
			$h = $GLOBALS['site_parameters']['iban_at_bottom_h'];
		}
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
			if (!empty($result['Nom1']) && empty($GLOBALS['site_parameters']['iban_at_bottom_bank_name_in_title'])) {
				$rib .= $GLOBALS['STR_MODULE_TEMPS_BANK_SINGULAR'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $result['Nom1'] . "<br />";
			}
			if (false && !empty($result['Adresse1'])) {
				// on désactive la domiciliation, info pas utile et pose problème si adresse trop longue
				$rib .= $GLOBALS['STR_MODULE_TEMPS_BANK_DOMICILIATION'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': ' . $result['Adresse1'] .' ' . $result['Adresse2'] .' '. $result['Code_Postal'] .' '. $result['Ville'] . "<br />";
			}
			if (!empty($result['iban'])) {
				$rib .= '<b>'.$GLOBALS['STR_IBAN'].$GLOBALS['STR_BEFORE_TWO_POINTS'] . '</b>: '. chunk_split(str_replace(' ', '', $result['iban']), 4, ' ') . "<br />" . '<b>'.$GLOBALS['STR_SWIFT'].'</b>'.$GLOBALS['STR_BEFORE_TWO_POINTS'] . ': '.$result['swift']; 
			}
			if(!empty($rib)) {
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['invoice_display_iban_at_bottom_title_font_size'], vn($GLOBALS['site_parameters']['global_font_size'], 10)));
				if (!empty($GLOBALS['site_parameters']['iban_at_bottom_not_round_rect'])) {
					$this->Rect($x, $y, $w, $h, 'D');
				} else {
					$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'D');
				}
				if (!empty($GLOBALS['site_parameters']['iban_at_bottom_bank_name_in_title'])) {
					$title = $result['Nom1'];
				} elseif(!empty($GLOBALS['site_parameters']['invoice_display_iban_at_bottom_title'])) {
					$title = $GLOBALS['site_parameters']['invoice_display_iban_at_bottom_title'];
				} else {
					$title = $GLOBALS['STR_TRANSFER'];
				}
				$this->Line($x, $y + 6, $x + $w, $y + 6);
				$this->SetXY($x, $y);
				if (!empty($GLOBALS['site_parameters']['iban_at_bottom_background_color_title'])) {
					$this->SetFillColor($GLOBALS['site_parameters']['iban_at_bottom_background_color_title'][0], $GLOBALS['site_parameters']['iban_at_bottom_background_color_title'][1], $GLOBALS['site_parameters']['iban_at_bottom_background_color_title'][2]);
					// pour ajouter une couleur de fond à la cellule, il faut mettre le 7ème paramètre fill à true
					$fill = true;
				} else {
					$fill = false;
				}

				// cellule pour le titre
				$this->Cell($w, 6, $title, 0, 0, "C", $fill);
				$this->SetXY($x + 1, $y+7);
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), '', vn($GLOBALS['site_parameters']['invoice_display_iban_at_bottom_font_size'], vn($GLOBALS['site_parameters']['global_font_size'], 10)));
				// $length = $this->GetStringWidth($rib);
				$this->MultiCell($w, 7, $rib, 0, "L", false, 1, null, null, true, 0, true);
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
			if (!empty($GLOBALS['site_parameters']['addCadreNet_RoundedRect_all_width'])) {
				if (!empty($GLOBALS['site_parameters']['addCadreNet_square_rect'])) {
					$this->Rect(10 + ($this->rMargin - 10), $y+4, $this->w_dispo, $h-3);
				} else {
					$this->InvoiceRoundedRect(10 + ($this->rMargin - 10), $y+4, $this->w_dispo, $h-3, 2.5, 'D');
				}
			} else {
				if (!empty($GLOBALS['site_parameters']['addCadreNet_square_rect'])) {
					$this->Rect($x, $y, $w, $h);
				} else {
					$this->InvoiceRoundedRect($x, $y, $w, $h, 2.5, 'D');
				}
			}
		}
		
		// taille de typo, pour toutes les lignes sauf le net à payer
		$addCadreNet_font_size_except_total = vn($GLOBALS['site_parameters']['addCadreNet_font_size_except_total'], 8);
		// taille de typo du net à payer
		$addCadreNet_font_size_total = vn($GLOBALS['site_parameters']['addCadreNet_font_size_total'], 9);

		// réglage de la hauteur de la ligne
		if (!empty($GLOBALS['site_parameters']['addCadreNet_cell_height'])) {
			$max_cell_height = $GLOBALS['site_parameters']['addCadreNet_cell_height'];
		} else {
			$max_cell_height = 3;
		}


		if (!empty($GLOBALS['site_parameters']['addCadreNet_cell_height_egal_line_height']) || !empty($GLOBALS['site_parameters']['addCadreNet_cell_height'])) {
			// addCadreNet_cell_height_egal_line_height : Cette variable permet d'avoir les hauteurs de cellule égale à la hauteur de ligne, ce qui fait que les cellules se touchent
			// !empty($GLOBALS['site_parameters']['addCadreNet_cell_height']) : Si on a forcé la taille de la celulle, on ne peut plus appliquer le même coefficent pour la hauteur de ligne.
			$line_height = $max_cell_height;
		} else {
			// hauteur de ligne en fonction de la taille de typo. On fait la moyenne de taille de typo pour le total (addCadreNet_font_size_total) et pas total (addCadreNet_font_size_except_total), puis on multiplie par un coefficient.
			$line_height = $max_cell_height * (0.15 * (($addCadreNet_font_size_except_total+$addCadreNet_font_size_total)/2));
		}
		// largeur de Cell
		if (!empty($GLOBALS['site_parameters']['addCadreNet_cell_width'])) {
			$cell_width = $GLOBALS['site_parameters']['addCadreNet_cell_width'];
		} else {
			$cell_width = 25;
		}
		
		// Largeur de la cellule du labelle du montant (TOTAL HT, TOTAL TVA, TOTAL TTC, ...)
		if (!empty($GLOBALS['site_parameters']['addCadreNet_label_cell_width'])) {
			$label_cell_width = $GLOBALS['site_parameters']['addCadreNet_label_cell_width'];
		} else {
			$label_cell_width = $cell_width;
		}
		
		// marge entre le bloc de produit et le ce bloc de totaux
		if (empty($GLOBALS['site_parameters']['addCadreNet_top_margin_disable'])) {
			$y = $y + 5;
		}
		
		// marge à gauche des montants
		$x_margin = vn($GLOBALS['site_parameters']['addCadreNet_x_margin'], 1);
		
		// pour définir la position x (lateral) de ce bloc de totaux
		if (!empty($GLOBALS['site_parameters']['addCadreNet_force_x'])) {
			$x = $GLOBALS['site_parameters']['addCadreNet_force_x'];
		} else {
			// Avant on avait -65 mais en prennant en compte la marge par défaut à droite de 10 ça fait 55
			$x = $this->w - ($this->rMargin) - 55 + $x_margin;
		}
		if (!empty($GLOBALS['site_parameters']['addCadreNet_amount_position_x_with_label_width'])) {
			// x2 détermine la position latéral des montants
			$x2 = $x + $label_cell_width;
		} else {
			$x2 = $x + 30 - 2 * $x_margin;
		}

		// ce SetFont s'applique sur les lignes de total jusqu'au total TTC. Donc prend en compte les taxes et le HT
		$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), (empty($GLOBALS['site_parameters']['addCadreNet_bold_disable_except_total'])?"B":''), $addCadreNet_font_size_except_total);

		$k = 0;
		if (abs(get_float_from_user_input($params1["tarif_paiement"])) >= 0.01) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $max_cell_height, $GLOBALS['STR_PDF_GESTION'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $max_cell_height, $params1['tarif_paiement'], '', '', 'R');
			$k = $k + $line_height;
		}

		if (check_if_module_active('ecotaxe')) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $max_cell_height, $GLOBALS['STR_PDF_ECOTAXE_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $max_cell_height, $params1["total_ecotaxe_ht"], '', '', 'R');
			$k = $k + $line_height;
		}

		if (!empty($GLOBALS['site_parameters']['mode_transport'])) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $max_cell_height, $GLOBALS['STR_PDF_COUT_TRANSPORT_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $max_cell_height, $params1["cout_transport_ht"], '', '', 'R');
			$k = $k + $line_height;
		}

		if (abs(get_float_from_user_input($params1["small_order_overcost_amount"])) >= 0.01) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $max_cell_height, $GLOBALS['STR_SMALL_ORDER_OVERCOST_TEXT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $max_cell_height, $params1["small_order_overcost_amount"], '', '', 'R');
			$k = $k + $line_height;
		}

		if (!check_if_module_active('micro_entreprise')) {
			if (!empty($GLOBALS['site_parameters']['distinct_total_ht_by_vat'])) {
				foreach($GLOBALS['site_parameters']['distinct_total_ht_by_vat'] as $label=>$this_total_ht_amount) {
					$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_HT_cell_x']), $y + $k);
					if (!empty($GLOBALS['site_parameters']['add_line_separation_before_totalHT'])) {
						$this->Line($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_HT_cell_x']), $y + $k-1 , $x2 + $cell_width, $y + $k-1);
					}
					$this->Cell($label_cell_width, $max_cell_height, $label);
					$this->SetXY($x2, $y + $k);
					if (!empty($GLOBALS['site_parameters']['total_ht_background_color'])) {
						$this->SetFillColor($GLOBALS['site_parameters']['total_ht_background_color'][0], $GLOBALS['site_parameters']['total_ht_background_color'][1], $GLOBALS['site_parameters']['total_ht_background_color'][2]);
						$this->Rect($x2, $y + $k, $cell_width, $max_cell_height, "F");
					}
					$this->Cell($cell_width, $max_cell_height, $this_total_ht_amount, vb($GLOBALS['site_parameters']['addCadreNet_montantHT_border']), '', 'R');
					$k = $k + $line_height;
				}
			} elseif (empty($GLOBALS['site_parameters']['total_ht_display_disable'])) {
				if (!empty($GLOBALS['site_parameters']['total_ht_label_background_color'])) {
					$this->SetFillColor($GLOBALS['site_parameters']['total_ht_label_background_color'][0], $GLOBALS['site_parameters']['total_ht_label_background_color'][1], $GLOBALS['site_parameters']['total_ht_label_background_color'][2]);
					//  $max_cell_height+0.5 : nécessaire pour que le fond de couleur occupe toute la cellule. Je sais pas d'où ce décallage peut venir, donc c'est corrigé ici en dur
					$this->Rect($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_HT_cell_x']), $y + $k, $label_cell_width+4, $max_cell_height, "F");
				}
				$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_HT_cell_x']), $y + $k);
				$this->Cell($label_cell_width, $max_cell_height, $GLOBALS['STR_PDF_TOTAL_HT']);
				$this->SetXY($x2, $y + $k);
				if (!empty($GLOBALS['site_parameters']['total_ht_background_color'])) {
					$this->SetFillColor($GLOBALS['site_parameters']['total_ht_background_color'][0], $GLOBALS['site_parameters']['total_ht_background_color'][1], $GLOBALS['site_parameters']['total_ht_background_color'][2]);
					$this->Rect($x2, $y + $k, $cell_width, $max_cell_height, "F");
				}
				$this->Cell($cell_width, $max_cell_height, StringMb::html_entity_decode_if_needed($params1["montant_ht"]), vb($GLOBALS['site_parameters']['addCadreNet_montantHT_border']), '', 'R');
			}
		} else {
			addNETs_part_micro($this, $x, $y + $k, $params1["totalttc"]);
		}

		// avoir
		$k = $k + $line_height;
		if (abs(get_float_from_user_input($params1["avoir"])) >= 0.01) {
			$this->SetXY($x, $y + $k);
			$this->Cell($label_cell_width, $max_cell_height, $GLOBALS['STR_PDF_AVOIR']);
			$this->SetXY($x2, $y + $k);
			$this->Cell($cell_width, $max_cell_height, $params1["avoir"], '', '', 'R');
			$k = $k + $line_height;
		}
		
		
		// détail TVA
		if(!empty($GLOBALS['site_parameters']['total_tva_in_net_bloc'])) {
			if (!empty($params2["distinct_total_vat"])) {
				foreach($params2["distinct_total_vat"] as $vat_percent => $value) {
					if ($vat_percent>0) {
						if (!empty($GLOBALS['site_parameters']['add_line_separation_before_totalTVA'])) {
							$this->Line($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TVA_cell_x']), $y + $k-1 , $x2 + $cell_width, $y + $k-1);
						}
						// vat_percent>0 : La TVA 0% aura toujours pour valeur 0€, donc on n'affiche pas
						$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_TVA_cell_x']), $y + $k);
						$this->Cell($label_cell_width , $max_cell_height, $GLOBALS['STR_PDF_TVA'] . ' ' . (StringMb::substr($vat_percent, 0, strlen('transport')) == 'transport'? str_replace('transport', $GLOBALS['STR_PDF_SHIPMENT'], $vat_percent) : $vat_percent) . '%' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . " ", "", 0 , "L");
						$this->SetXY($x2, $y + $k);
						if (!empty($GLOBALS['site_parameters']['total_tva_detail_background_color'])) {
							$this->SetFillColor($GLOBALS['site_parameters']['total_tva_detail_background_color'][0], $GLOBALS['site_parameters']['total_tva_detail_background_color'][1], $GLOBALS['site_parameters']['total_tva_detail_background_color'][2]);
							$this->Rect($x2, $y + $k, $cell_width, $max_cell_height, "F");
						}
						$this->Cell($cell_width, $max_cell_height, $value, '', '', 'R');
						$k = $k + $line_height;
					}
				}
			}
		}
		
		// Montant total TVA (sans le détail)
		if(!empty($GLOBALS['site_parameters']['grand_total_tva_in_net_bloc']) && !empty($params2["total_tva"])) {
			$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_grand_total_tva_cell_x']), $y + $k);
			$this->Cell($label_cell_width , $max_cell_height, $GLOBALS['STR_PDF_TVA'] . ' ' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . " ", "", 0 , "L");
			$this->SetXY($x2, $y + $k);
			if (!empty($GLOBALS['site_parameters']['grand_total_tva_detail_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['grand_total_tva_detail_background_color'][0], $GLOBALS['site_parameters']['grand_total_tva_detail_background_color'][1], $GLOBALS['site_parameters']['grand_total_tva_detail_background_color'][2]);
				$this->Rect($x2, $y + $k, 25, $max_cell_height, "F");
			}
			$this->Cell($cell_width, $max_cell_height, $params2["total_tva"], '', '', 'R');
			$k = $k + $line_height;
		}
		if (!empty($GLOBALS['site_parameters']['ttc_top_space_enable'])) {
			// espace supplémentaire avant l'affichage du net à payer
			$k = $k + 4;
		}
		
		if (empty($GLOBALS['site_parameters']['total_ttc_display_disable'])) {
			// Affichage TOTAL TTC
			$this->SetXY($x- vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_TTC_cell_x']), $y + $k);
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), (empty($GLOBALS['site_parameters']['addCadreNet_bold_disable_except_total']) || !empty($GLOBALS['site_parameters']['addCadreNet_TOTAL_TTC_bold'])?"B":''), $addCadreNet_font_size_except_total);
			if (!empty($GLOBALS['site_parameters']['total_ttc_label_font_color'])) {
				$this->SetTextColor($GLOBALS['site_parameters']['total_ttc_label_font_color'][0], $GLOBALS['site_parameters']['total_ttc_label_font_color'][1], $GLOBALS['site_parameters']['total_ttc_label_font_color'][2]);
			}
			if (!empty($GLOBALS['site_parameters']['total_ttc_label_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['total_ttc_label_background_color'][0], $GLOBALS['site_parameters']['total_ttc_label_background_color'][1], $GLOBALS['site_parameters']['total_ttc_label_background_color'][2]);
				//  $max_cell_height+0.5 : nécessaire pour que le fond de couleur occupe toute la cellule. Je sais pas d'où ce décallage peut venir, donc c'est corrigé ici en dur
				$this->Rect($x- vn($GLOBALS['site_parameters']['addCadreNet_adjust_TOTAL_TTC_cell_x']), $y + $k, $label_cell_width, $max_cell_height+1.5, "F");
			}
			$this->Cell($label_cell_width, $max_cell_height, ucfirst($GLOBALS['STR_PDFTOTALTTC']) . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':', vb($GLOBALS['site_parameters']['addCadreNet_montantTTC_label_border']));
			if (!empty($GLOBALS['site_parameters']['total_ttc_font_color'])) {
				$this->SetTextColor($GLOBALS['site_parameters']['total_ttc_font_color'][0], $GLOBALS['site_parameters']['total_ttc_font_color'][1], $GLOBALS['site_parameters']['total_ttc_font_color'][2]);
			}
			$this->SetXY($x2, $y + $k);
			if (!empty($GLOBALS['site_parameters']['total_ttc_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['total_ttc_background_color'][0], $GLOBALS['site_parameters']['total_ttc_background_color'][1], $GLOBALS['site_parameters']['total_ttc_background_color'][2]);
				//  $max_cell_height+0.5 : nécessaire pour que le fond de couleur occupe toute la cellule. Je sais pas d'où ce décallage peut venir, donc c'est corrigé ici en dur
				$this->Rect($x2, $y + $k, $cell_width, $max_cell_height+1.5, "F");
			}
			$this->Cell($cell_width, $max_cell_height, StringMb::html_entity_decode_if_needed($params1["montant"]), vb($GLOBALS['site_parameters']['addCadreNet_montantTTC_border']), '', 'R');
			if (!empty($GLOBALS['site_parameters']['total_ttc_font_color']) || !empty($GLOBALS['site_parameters']['total_ttc_label_font_color'])) {
				$this->SetTextColor(0, 0, 0);
			}
			$k = $k + $line_height;
		}

		// Acompte
		if (abs(get_float_from_user_input(vn($params1["AcompteTTC"]))) >= 0.01) {
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", $addCadreNet_font_size_except_total);
			if (!empty($GLOBALS['site_parameters']['AcompteTTC_font_color'])) {
				$this->SetTextColor($GLOBALS['site_parameters']['AcompteTTC_font_color'][0], $GLOBALS['site_parameters']['AcompteTTC_font_color'][1], $GLOBALS['site_parameters']['AcompteTTC_font_color'][2]);
			}
			if (!empty($GLOBALS['site_parameters']['acompte_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['acompte_background_color'][0], $GLOBALS['site_parameters']['acompte_background_color'][1], $GLOBALS['site_parameters']['acompte_background_color'][2]);
				$this->Rect($x2, $y + $k, $cell_width, $max_cell_height, "F");
			}
			$this->SetXY($x- vn($GLOBALS['site_parameters']['addCadreNet_adjust_ACOMPTE_cell_x']), $y + $k);
			$this->Cell($label_cell_width, $max_cell_height, $GLOBALS['STR_PDF_ACOMPTE'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
			$this->SetXY($x2, $y + $k);
		
			$this->Cell($cell_width, $max_cell_height, $params1["AcompteTTC"], vb($GLOBALS['site_parameters']['addCadreNet_AcompteTTC_border']), '', 'R');
			$k = $k + $line_height-1;
			if (!empty($GLOBALS['site_parameters']['AcompteTTC_font_color'])) {
				// on repasse en noir 
				$this->SetTextColor(0, 0, 0);
			}
		}
		
		if (empty($GLOBALS['site_parameters']['total_ttc_top_space_disable'])) {
			// espace supplémentaire avant l'affichage du net à payer
			$k = $k + 4;
		}
		
		// Net à payer. Mention obligatoire car peut être différent du TTC en cas de la présence d'un acompte
		if (abs(get_float_from_user_input(vn($params1["SoldeDu"]))) >= 0.01 && empty($GLOBALS['site_parameters']['net_a_payer_display_disable'])) {
			if (!empty($GLOBALS['site_parameters']['add_line_separation_before_totalNet'])) {
				$this->Line($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_NET_cell_x']), $y + $k-1 , $x2 + $cell_width, $y + $k-1);
			}
			
			
			$this->SetXY($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_NET_cell_x']), $y + $k);
			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), (empty($GLOBALS['site_parameters']['addCadreNet_bold_disable_total'])?'B':''), $addCadreNet_font_size_total);
			
			if (!empty($GLOBALS['site_parameters']['SoldeDu_label_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['SoldeDu_label_background_color'][0], $GLOBALS['site_parameters']['SoldeDu_label_background_color'][1], $GLOBALS['site_parameters']['SoldeDu_label_background_color'][2]);
				$this->Rect($x - vn($GLOBALS['site_parameters']['addCadreNet_adjust_NET_cell_x']), $y + $k-1, $label_cell_width+4 + vn($GLOBALS['site_parameters']['addCadreNet_adjust_NET_cell_x']), $max_cell_height+2, "F");
			}
			if (!empty($GLOBALS['site_parameters']['SoldeDu_label_font_color'])) {
				$this->SetTextColor($GLOBALS['site_parameters']['SoldeDu_label_font_color'][0], $GLOBALS['site_parameters']['SoldeDu_label_font_color'][1], $GLOBALS['site_parameters']['SoldeDu_label_font_color'][2]);
			}
			$this->Cell($label_cell_width, $max_cell_height, $GLOBALS['STR_PDF_NET'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':', vb($GLOBALS['site_parameters']['addCadreNet_SoldeDu_label_border']));
			$this->SetXY($x2, $y + $k);
			if (!empty($GLOBALS['site_parameters']['SoldeDu_background_color'])) {
				$this->SetFillColor($GLOBALS['site_parameters']['SoldeDu_background_color'][0], $GLOBALS['site_parameters']['SoldeDu_background_color'][1], $GLOBALS['site_parameters']['SoldeDu_background_color'][2]);
				$this->Rect($x2, $y + $k-1, $cell_width, $max_cell_height+2, "F");
			}
			if (!empty($GLOBALS['site_parameters']['SoldeDu_font_color'])) {
				$this->SetTextColor($GLOBALS['site_parameters']['SoldeDu_font_color'][0], $GLOBALS['site_parameters']['SoldeDu_font_color'][1], $GLOBALS['site_parameters']['SoldeDu_font_color'][2]);
			}
			$this->Cell($cell_width, $max_cell_height, $params1["SoldeDu"], vb($GLOBALS['site_parameters']['addCadreNet_SoldeDu_border']), '', 'R');
			$k = $k + $line_height;
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
		if (!empty($GLOBALS['site_parameters']['addCadreTva_by_line'])) {
			$line_tva = '
			'.$GLOBALS["STR_VAT_DETAIL"].' : <br />
			<table>
				<tr>
					<td  '.vb($GLOBALS['site_parameters']['addCadreTva_style_css_td_vat_detail'],'').'><span style="color:#666666;"><b>'.$GLOBALS['STR_AMOUNT'].' HT</b></span></td>
					<td  '.vb($GLOBALS['site_parameters']['addCadreTva_style_css_td_vat_detail'],'').'><span style="color:#666666;"><b>'.$GLOBALS['STR_VAT_PERCENTAGE'].'</b></span></td>
					<td  '.vb($GLOBALS['site_parameters']['addCadreTva_style_css_td_vat_detail'],'').'><span style="color:#666666;"><b>'.$GLOBALS['STR_AMOUNT'].' TVA</b></span></td>
				</tr>';
			foreach($params2['tva_infos_array']["distinct_total_vat"] as $vat_percent => $value) {
				$line_tva .= '
				<tr>
					<td><span style="color:#666666;">'.$params2['net_infos_array']['montant_ht'].'</span></td>
					<td><span style="color:#666666;">'.$vat_percent.'%</span></td>
					<td><span style="color:#666666;">'.$value.'</span></td>
				</tr>';
			}
			$line_tva .= '
			</table>';

			$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), '', 10);
			if (empty($GLOBALS['site_parameters']['addCadreTva_margin_force_y'])) {
				// Si on affiche les TVA ligne par ligne, on commence l'affichage tout à gauche ($this->rMargin = 10 par défaut)
				$this->SetXY($this->rMargin, $y+20);
			} else {
				$this->SetXY($this->rMargin, $y+vn($GLOBALS['site_parameters']['addCadreTva_margin_force_y']));
			}
			$this->MultiCell(vn($GLOBALS['site_parameters']['addCadreTva_by_line_w'], 80), 6, $line_tva, 0, vb($GLOBALS['site_parameters']['addCadreTva_by_line_align'], "L"), false, 1, '', '', true, 0, true);
		} else {
			if (empty($GLOBALS['site_parameters']['addCadreTva_border_disable'])) {
				if (!empty($GLOBALS['site_parameters']['addCadreTva_by_column_half_total_width'])) {
					$rect_width = $w - 25;
				} else {
					$rect_width = $w;
				}
				if (!empty($GLOBALS['site_parameters']['addCadreTva_square_rect'])) {
					$this->Rect($x, $y, $rect_width, $h, 'D');
				} else {
					$this->InvoiceRoundedRect($x, $y, $rect_width, $h, 2.5, 'D');
				}
			}
			$x_margin = 1;
			$k = 0;
			if (!empty($GLOBALS['site_parameters']['addCadreTva_by_column'])) {
				$x += 6;
				$x2 = $x + 25 - 2 * $x_margin;
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "", vn($GLOBALS['site_parameters']['addCadreTva_font_size'], 8));
				// 3 colonnes : base HT, le taux et le montant de la tva pour le taux
				if (empty($GLOBALS['site_parameters']['addCadreTva_inner_line_disable'])) {
					// on trace les lignes vertical pour les 3 colonnes
					$this->Line($x + 15, $y , $x + 15, $y + $h); 
					$this->Line($x + 33, $y, $x + 33, $y + $h); 
					
					// il faut tracer la ligne horizontal pour la ligne de titre
					$this->Line($x-6, $y + 6 , $x + 57, $y + 6); 
				}
				// on place les cellules qui vont remplir la ligne de titre
				
				// base HT
				$this->SetXY($x-3, $y+1);
				$this->Cell(25, 4, $GLOBALS['STR_TOTAL_HT']);
				$this->SetXY($x-3, $y + 6);
				$this->Cell(15, 4, $params2['net_infos_array']['montant_ht'], '', '', 'C'); 
				
				if (!empty($GLOBALS['site_parameters']['addCadreTva_by_column_half_total_width'])) {
					$pos_cell_percent = $x+38;
					$this->SetXY($x+35, $y+1);
					$pos_cell_amount = $x+65;
					$x += 73;
				} else {
					$pos_cell_percent = $x+18;
					$this->SetXY($x+15, $y+1);
					$pos_cell_amount = $x+25;
					$x += 33;
				}
				$this->Cell(10, 4, $GLOBALS['STR_VAT_PERCENTAGE']);
				$this->SetXY($x, $y+1);
				$this->Cell(25, 4, $GLOBALS['STR_AMOUNT_TVA']);
				$k = 6;
				if (!empty($params2['tva_infos_array']["distinct_total_vat"])) {
					// pour chacun des taux, on va afficher le taux et le montant associé
					foreach($params2['tva_infos_array']["distinct_total_vat"] as $vat_percent => $value) {
						if ($vat_percent>0) {
							// vat_percent>0 : La TVA 0% aura toujours pour valeur 0€, donc on n'affiche pas
							$this->SetXY($pos_cell_percent, $y + $k);
							$this->Cell(10, 4,  number_format($vat_percent, 2, '.', ''));
							$this->SetXY($pos_cell_amount, $y + $k);
							$this->Cell(25, 4, $value, '', '', 'R');
							$k = $k + 4;
						}
					}
				}
			} else {
				$x += 1;
				$x2 = $x + 30 - 2 * $x_margin;
				$y = $y + 5;
				$this->SetFont(vb($GLOBALS['site_parameters']['pdf_font_family'], "freesans"), "B", vn($GLOBALS['site_parameters']['addCadreTva_font_size'], 8));
				if (!empty($GLOBALS['site_parameters']['CadreTva_display_ht_amount'])) {
					$this->SetXY($x, $y + $k);
					$this->Cell(20, 4, $GLOBALS['STR_TOTAL_HT'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
					$this->SetXY($x2, $y + $k);
					$this->Cell(20, 4, $params2['net_infos_array']['montant_ht'], '', '', 'R'); 
					
					$k = $k + 5;
				}
				if (check_if_module_active('micro_entreprise')) {
					addTVAs_part_micro($this, $x, $y + $k);
				} else {
					if (!empty($params2['tva_infos_array']["distinct_total_vat"])) {
						// Liste des taux de TVA appliqués
						foreach($params2['tva_infos_array']["distinct_total_vat"] as $vat_percent => $value) {
							if ($vat_percent>0) {
								// vat_percent>0 : La TVA 0% aura toujours pour valeur 0€, donc on n'affiche pas
								$this->SetXY($x, $y + $k);
								$this->Cell(20, 4, $GLOBALS['STR_TAXE'] . ' ' . (StringMb::substr($vat_percent, 0, strlen('transport')) == 'transport'? str_replace('transport', $GLOBALS['STR_PDF_SHIPMENT'], $vat_percent) : $vat_percent) . '%' . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':' . " ");
								$this->SetXY($x2, $y + $k);
								$this->Cell(20, 4, $value, '', '', 'R');
								$k = $k + 4;
							}
						}
					}
					// Total de la TVA
					$k = $k + 3;
					$this->SetXY($x, $y + $k);
					$this->Cell(20, 4, $GLOBALS['STR_PDF_TVA'] . $GLOBALS['STR_BEFORE_TWO_POINTS'] . ':');
					$this->SetXY($x2, $y + $k);
					$this->Cell(20, 4, $params2['tva_infos_array']['total_tva'], '', '', 'R'); 
					$k = $k + 5;
				}
			}
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
	 * @param boolean $facturx_enabled
	 * @return
	 */
	function FillDocument($code_facture = null, $date_debut = null, $date_fin = null, $id_debut = null, $id_fin = null, $user_id = null, $id_statut_paiement_filter = null, $bill_mode = 'standard', $folder = false, $order_object = null, $product_infos_array = null, $order_array = null, $document_title = null, $ids_array = null, $sign_if_available = true, $facturx_enabled = false, $send_mode_if_no_folder = 'I')
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
		/*
			// INSTALLATION D'UNE POLICE : https://www.downloadfonts.io/
			// calibriRegular.ttf
			// CalibriItalic.ttf
			// CalibriBold.ttf
			=> Format de nom de fichier à respecter
			$fontname = TCPDF_FONTS::addTTFfont($GLOBALS['uploaddir'].'/CalibriItalic.ttf', 'TrueTypeUnicode');
			var_dump($fontname);
		*/
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
				// NB : On ajoute des informations sur le client, et sur la société liée au site de la commande
				$sql_bills = "SELECT u.*, c.*, sp.technical_code AS statut_paiement,
						'' AS acompte,
						'' AS adresse_bill2,
						'' AS adresse_bill3,
						s.societe AS societe,
						s.siren AS societe_siren,
						s.code_postal AS societe_code_postal,
						s.adresse AS societe_adresse,
						'' AS societe_adresse2,
						'' AS societe_adresse3,
						s.ville AS societe_ville,
						s.pays AS societe_pays,
						s.email AS societe_email,
						s.tvaintra AS societe_tvaintra,
						s.iban AS societe_iban,
						s.swift AS societe_swift
					FROM peel_commandes c
					LEFT JOIN peel_statut_paiement sp ON sp.id=c.id_statut_paiement AND " . get_filter_site_cond('statut_paiement', 'sp') . "
					LEFT JOIN peel_utilisateurs u ON u.id_utilisateur=c.id_utilisateur
					LEFT JOIN peel_societe s ON " . get_filter_site_cond('societe', 's') . "
					WHERE " . implode(' AND ', $sql_cond_array) . " AND " . get_filter_site_cond('commandes', 'c') . "
					GROUP BY c.id
					ORDER BY c.o_timestamp ASC";
			}

			$query = query($sql_bills);
			while ($this_order_object = fetch_object($query)) {
				$order_object = $this_order_object;
				$hook_file_name = '';
				$hook_result = call_module_hook('bill_get_configuration_array', array('bill_mode' => $bill_mode, 'order_object' => $order_object), 'array');

				// Définit les site_parameters spécifique à chaque client pour le modèle de facture
				// NB  : Il faut passer $order_object car la configuration va dépendre de la société émittrice de la facture et du modèle de facture
				$model_infos_result = call_module_hook('user_personalization_infos', array('bill_mode' => $bill_mode, 'order_object' => $order_object), 'array');
				if (!empty($GLOBALS['site_parameters']['document_margin'])) {
					// on fait ça ici pour pouvoir utiliser site_parameters qui est éventuellement défini dans le hook user_personalization_infos
					// document_margin est un tableau qui contient les marges du documents. Par défaut c'est 10 pour les cotés et le haut, et 20 pour le bas
					$this->SetMargins(vn($GLOBALS['site_parameters']['document_margin']['gauche'], 10), vn($GLOBALS['site_parameters']['document_margin']['haut'], 10), vn($GLOBALS['site_parameters']['document_margin']['droite'], 10), true);
					$this->SetLeftMargin(vn($GLOBALS['site_parameters']['document_margin']['gauche'], 10));
					$this->SetRightMargin(vn($GLOBALS['site_parameters']['document_margin']['droite'], 10));
					$this->SetTopMargin(vn($GLOBALS['site_parameters']['document_margin']['haut'], 10));
					$this->bMargin= vn($GLOBALS['site_parameters']['document_margin']['bas'], 20);
				}
				// Ce qui suit défini si ou accepte FactureX ou pas
				// NB : ça ne concerne le cas de génération de document PDF pour une seule facture, et pas plusieurs factures dans 1 seul document
				
				if($facturx_enabled && isset($model_infos_result['modelfac_infos']['facturx'])) {
					// Factur-x autorisée => on regarde si le modèle de facture l'active ou pas
					$facturx_enabled = !empty($model_infos_result['modelfac_infos']['facturx']);
				} else {
					// Pas de précision relative au modèle, on active factur-x selon le paramètre $facturx_enabled
				}
				if ($bill_mode == "quote_prepare") {
					// on ne veut pas de factureX pour les devis
					$facturx_enabled = false;
				}
				// La hauteur disponible peut être affectée par l'appel à un hook ci-dessus
				$this->setHdispo();
				if (!empty($GLOBALS['site_parameters']['pdf_column_width']) && !empty($GLOBALS['site_parameters']['pdf_column_alignement'])) {
					// Si colonne personnalisé pour le client => prioritaire sur les autres configurations
					$this->colonnes = $GLOBALS['site_parameters']['pdf_column_width'];
					$column_formats = $GLOBALS['site_parameters']['pdf_column_alignement'];
				}
				if (count($hook_result)) {
					// Par exemple le module micro_-_entreprise définit le format des factures ici
					if (empty($GLOBALS['site_parameters']['pdf_column_width']) || !empty($hook_result['force_column_configuration'])) {
						$this->colonnes = $hook_result['width'];
						$column_formats = $hook_result['alignement'];
					}
					$hook_file_name = $hook_result['file_name'];
				} else {
					$this->colonnes = array($GLOBALS['STR_PDF_REFERENCE'] => 22,
						$GLOBALS['STR_DESIGNATION'] => 53 - ($this->lMargin - 10) - ($this->rMargin - 10), // on soustrait la taille des marges, 10 par défaut
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
				}

				$hook_result = call_module_hook('bill_pdf_product_infos_array_in_order', array('bill_mode' => $bill_mode, 'order_object' => $order_object, 'id' => $order_object->id, 'devise' => $order_object->devise, 'currency_rate' => $order_object->currency_rate, 'product_excluded' => vb($GLOBALS['site_parameters'][$bill_mode . '_product_excluded'], array())), 'array');
				if (!empty($hook_result['hook_done'])) {
					$product_infos_array = $hook_result['products'];
				} else {
					$product_infos_array = get_product_infos_array_in_order($order_object->id, $order_object->devise, $order_object->currency_rate, null, false, vb($GLOBALS['site_parameters'][$bill_mode . '_product_excluded'], array()));
				}
			
			
				$this->generatePdfOrderContent($column_formats, $i, $order_object, $product_infos_array);
				if (empty($hook_file_name)) {
					if (empty($file_name)) {
						$file_name = $this->document_name . '_' . $this->document_id;
						if (!empty($order_object->f_datetime) && substr($order_object->f_datetime, 0, 10) != '0000-00-00') {
							$file_name .= '_' . get_formatted_date($order_object->f_datetime);
						}
						$file_name .= '.pdf';
					} else {
						// Plusieurs factures
						$file_name = 'F-' . substr(md5($sql_bills . $GLOBALS['wwwroot']), 0, 16) . '.pdf';
					}
				} else {
					$file_name = $hook_file_name;
				}
				$i++;
			}
		} else {
			$this->setHdispo();
			$this->colonnes = array($GLOBALS['STR_PHOTO'] => 25,
				$GLOBALS['STR_DESIGNATION'] => 35,
				$GLOBALS['STR_EAN_CODE'] => 62 - ($this->lMargin - 10) - ($this->rMargin - 10),  // on soustrait la taille des marges, 10 par défaut
				$GLOBALS['STR_BRAND'] => 16,
				$GLOBALS['STR_CATEGORY'] => 20,
				$GLOBALS['STR_QUANTITY_SHORT'] => 7,
				$GLOBALS['STR_PDF_PRIX_TTC'] => 12,
				$GLOBALS['STR_START_PRICE'] . ' ' . $GLOBALS['STR_TTC'] => 13);
			$column_formats = array($GLOBALS['STR_PHOTO'] => "C",
				$GLOBALS['STR_DESIGNATION'] => "L",
				$GLOBALS['STR_EAN_CODE'] => "C",
				$GLOBALS['STR_BRAND'] => "C",
				$GLOBALS['STR_CATEGORY'] => "L",
				$GLOBALS['STR_QUANTITY_SHORT'] => "R",
				$GLOBALS['STR_PDF_PRIX_TTC'] => "R",
				$GLOBALS['STR_START_PRICE'] . ' ' . $GLOBALS['STR_TTC'] => "R");
			// Affichage d'une liste de produit dans un document PDF sans que les produits ne soient associés à une commande
			$facturx_enabled = false;
			$this->generatePdfOrderContent($column_formats, 0, $order_object, $product_infos_array, $document_title);
			$file_name = $GLOBALS['STR_LIST_PRODUCT'] . ' ' . vb($order_object->nom_ship) . '.pdf';
			$i++;
		}
		call_module_hook('bill_get_configuration_end', array('bill_mode' => $bill_mode));
		if (!empty($i)) {
			$this->lastPage();
			$file_name = StringMb::convert_accents(str_replace(array('/', ' '), '-', $file_name));
			if ($folder === null) {
				// Si $folder vaut null, c'est qu'on veut des informations remplies au cours du processus, mais pas de document PDF
				return true;
			} else {
				if ($i == 1 && $facturx_enabled) {
					// Pas utile de faire un PDF/A de la facture d'abord, puisque ensuite le module Factur-X va le charger avec FPDI et aucune entête n'est gardée
					// (et en plus TCPDF veut du PDF/A-1 alors que nous du PDF/A-3b, donc pour les transparences d'images etc. pas mêmes règles
					// En revanche, on veut toutes les polices inclues réellement => j'ai créé une propriété force_all_fonts_embedded pour forcer juste ça
					// $this->pdfa_mode = true;
					$this->force_all_fonts_embedded = true;
					$pdf_string = $this->Output($file_name, 'S');

					// Generating Factur-X PDF invoice from PDF and Factur-X XML
					require_once($GLOBALS['dirroot'] . "/modules/factur-x/src/autoload.php");
					require_once($GLOBALS['dirroot'] . "/modules/fpdi/autoload.php");
					// on n'utilise pas la vers FPDF de FPDI, donc pas besoin de : require_once($GLOBALS['dirroot'] . "/modules/fpdf/fpdf.php");
					$facturx = new Facturx();
					
					$DateTimeString = date('Ymd', strtotime($order_object->f_datetime));
					$societe_pays = $order_object->societe_pays;
					if(empty($societe_pays)) {
						if(!empty($GLOBALS['site_parameters']['country_iso'])) {
							$societe_pays = $GLOBALS['site_parameters']['country_iso'];
						} else {
							$societe_pays = get_country_iso_2_letter_code(vn($GLOBALS['site_parameters']['default_country_id']));
						}
					}

					$facturxXml = '<?xml version="1.0" encoding="UTF-8"?>
<rsm:CrossIndustryInvoice xmlns:qdt="urn:un:unece:uncefact:data:standard:QualifiedDataType:100"
	xmlns:ram="urn:un:unece:uncefact:data:standard:ReusableAggregateBusinessInformationEntity:100"
	xmlns:rsm="urn:un:unece:uncefact:data:standard:CrossIndustryInvoice:100"
	xmlns:udt="urn:un:unece:uncefact:data:standard:UnqualifiedDataType:100"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <rsm:ExchangedDocumentContext>
      <ram:BusinessProcessSpecifiedDocumentContextParameter>';
// Par défaut : A1 si pas présent
					$facturxXml .= '
	  <ram:ID>A1</ram:ID>
      </ram:BusinessProcessSpecifiedDocumentContextParameter>
      <ram:GuidelineSpecifiedDocumentContextParameter>';
// Spécification de norme en16931 => plus exigente que le minimum
					$facturxXml .= '         <ram:ID>urn:cen.eu:en16931:2017</ram:ID>
      </ram:GuidelineSpecifiedDocumentContextParameter>
   </rsm:ExchangedDocumentContext>
   <rsm:ExchangedDocument>
      <ram:ID>' . TCPDF_STATIC::_escapeXML($order_object->numero) . '</ram:ID>';
					/*
					  BT-3 : type de facture dans la balise « ram:TypeCode »,pour les valeurs suivantes :
					  380 : Facture commerciale
					  381 : Avoir (note de crédit)
					  384 : Facture rectificative
					  389 : Facture d’autofacturation (créée par l'acheteur pour le compte du fournisseur). Code
					  non accepté pour ChorusPro
					  261 : Avoir d’autofacturation. Code non accepté pour ChorusPro
					  386 : Facture d'acompte
					  751 : Informations de facture pour comptabilisation : code exigé en Allemagne pour
					  satisfaire ses contraintes réglementaires. Code non accepté pour ChorusPro.
					 */
					if ($order_object->montant < 0) {
						// NB : cf. page 26 des spécifications 1.0.05 :
						// Il ne faut pas utiliser 386 pour dire avoir (et dans ce cas il faut inverser tous les montants pour qu'ils soient positifs),
						// mais garder 380 pour avoir facture négative
						$TypeCode = '380';
					} else {
						$TypeCode = '380';
					}
					$facturxXml .= '
      <ram:TypeCode>' . $TypeCode . '</ram:TypeCode>
      <ram:IssueDateTime>
         <udt:DateTimeString format="102">' . $DateTimeString . '</udt:DateTimeString>
      </ram:IssueDateTime>
      <ram:IncludedNote>
         <ram:Content>' . TCPDF_STATIC::_escapeXML($order_object->societe) . '</ram:Content>';
// REG = règlementation => c'est le domaine qui correspond à l'info de raison sociale
					$facturxXml .= '
	     <ram:SubjectCode>REG</ram:SubjectCode>
      </ram:IncludedNote>
      <ram:IncludedNote>';
// SIRET ou SIREN à mettre dans raison sociale 2, cf. aussi modif présentation sur le web
					$facturxXml .= '
         <ram:Content>' . TCPDF_STATIC::_escapeXML($order_object->societe_siren) . '</ram:Content>';
// ABL = Information légale => c'est le domaine qui correspond à l'info SIRET ou SIREN
					$facturxXml .= '
         <ram:SubjectCode>ABL</ram:SubjectCode>
      </ram:IncludedNote>
   </rsm:ExchangedDocument>
   <rsm:SupplyChainTradeTransaction>';

					$j = 0;
					foreach ($product_infos_array as $this_ordered_product) {
						$j = $j + 1;
						$facturxXml .= '
		<ram:IncludedSupplyChainTradeLineItem>
         <ram:AssociatedDocumentLineDocument>
            <ram:LineID>' . ($j) . '</ram:LineID>
         </ram:AssociatedDocumentLineDocument>
         <ram:SpecifiedTradeProduct>';
//'            <ram:GlobalID schemeID="0088">598785412598745</ram:GlobalID>
						$facturxXml .= '
		       <ram:Name>' . TCPDF_STATIC::_escapeXML($this_ordered_product['Code_Vente']) . '</ram:Name>
         </ram:SpecifiedTradeProduct>
         <ram:SpecifiedLineTradeAgreement>';
//'            <ram:BuyerOrderReferencedDocument>
//'               <ram:LineID>NUMERO DE DEVIS PAR EXEMPLE</ram:LineID>
//'            </ram:BuyerOrderReferencedDocument>
						$facturxXml .= '
		        <ram:NetPriceProductTradePrice>';
// Prix unitaire, chez nous c'est le prix de la ligne car quantité est tjs égale à 1
						$facturxXml .= '
               <ram:ChargeAmount>' . $this_ordered_product['HT_Ligne'] . '</ram:ChargeAmount>';
// Quantité packagée : 1
						$facturxXml .= '
               <ram:BasisQuantity unitCode="C62">1</ram:BasisQuantity>
            </ram:NetPriceProductTradePrice>
         </ram:SpecifiedLineTradeAgreement>
         <ram:SpecifiedLineTradeDelivery>';
// Quantité v}ue : 1
						$facturxXml .= '
            <ram:BilledQuantity unitCode="C62">1</ram:BilledQuantity>
         </ram:SpecifiedLineTradeDelivery>
         <ram:SpecifiedLineTradeSettlement>
            <ram:ApplicableTradeTax>
               <ram:TypeCode>VAT</ram:TypeCode>';
						/* BG-23 : bloc de détail de TVA, obligatoire sauf si la facture est hors champ de TVA, répétable (autant de fois qu’il y a de code TVA dans la facture), sous la
						  balise « ram:ApplicableTradeTax ». Les règles de gestion sur la codification de la TVA sont détaillées au chapitre 6.4.3 de la norme sémantique. Il est prévu
						  9 types de situations (codifiées sous la balise « CategoryCode ») :
						  TVA applicable sur un taux non nul : « S »
						  TVA applicable sur taux de TVA égal à 0 : « Z »
						  TVA non appliquée, mais payée par le client (donc pas de TVA sur la facture) en cas de livraison intracommunautaire B2B : « K »
						  TVA non appliquée, mais payée par le client (donc pas de TVA sur la facture) en cas d’autoliquidation de TVA : « AE »
						  TVA non applicable (exempté) : « E »
						  TVA non appliquée en cas d’export en dehors de la Communauté Européenne : « G »
						  Hors champs de TVA : « O »
						  TVA pour des ventes relevant des territoires des Iles Canaries : « L »
						  TVA pour des ventes relevant des territoires des Iles Ceuta et Melilla : « M »
						 */
						if ((!empty($order_object->intracom_for_billing) && is_user_tva_intracom_for_no_vat(null, $order_object->intracom_for_billing)) || (!empty($order_object->client_code_tva) && get_vat_rate($order_object->client_code_tva) === 0)) {
							if (!empty($order_object)) {
								$TVACatCode = 'K';  // Intracom
							} else {
								$TVACatCode = 'G'; // Export hors UE
							}
						} else {
							if ($this_ordered_product['Taux_TVA'] == 0) {
								$TVACatCode = 'Z'; // Débours par exemple
							} else {
								$TVACatCode = 'S'; // Normal
							}
						}

						$facturxXml .= '
               <ram:CategoryCode>' . $TVACatCode . '</ram:CategoryCode>
               <ram:RateApplicablePercent>' . $this_ordered_product['Taux_TVA'] . '</ram:RateApplicablePercent>
            </ram:ApplicableTradeTax>';
// Si livraison intracom et pas de date de livraison : il faut bien remplir BillingSpecifiedPeriod
						$facturxXml .= '
            <ram:BillingSpecifiedPeriod>
               <ram:StartDateTime>
                  <udt:DateTimeString format="102">' . $DateTimeString . '</udt:DateTimeString>
               </ram:StartDateTime>
               <ram:EndDateTime>
                  <udt:DateTimeString format="102">' . $DateTimeString . '</udt:DateTimeString>
               </ram:EndDateTime>
            </ram:BillingSpecifiedPeriod>
            <ram:SpecifiedTradeSettlementLineMonetarySummation>
               <ram:LineTotalAmount>' . $this_ordered_product['HT_Ligne'] . '</ram:LineTotalAmount>
            </ram:SpecifiedTradeSettlementLineMonetarySummation>
         </ram:SpecifiedLineTradeSettlement>
      </ram:IncludedSupplyChainTradeLineItem>';
					}
					// Fin des lignes de facture
					// On continue à rajouter certaines informations complémentaires
					$facturxXml .= '
      <ram:ApplicableHeaderTradeAgreement>';
//         <ram:BuyerReference>SERVEXEC</ram:BuyerReference>
					$facturxXml .= '
         <ram:SellerTradeParty>';
//            <ram:GlobalID schemeID="0088">587451236587</ram:GlobalID>
					$facturxXml .= '
           <ram:Name>' . TCPDF_STATIC::_escapeXML($order_object->societe) . '</ram:Name>
            <ram:SpecifiedLegalOrganization>
               <ram:ID schemeID="0002">' . TCPDF_STATIC::_escapeXML($order_object->societe_siren) . '</ram:ID>
               <ram:TradingBusinessName>' . TCPDF_STATIC::_escapeXML($order_object->societe) . '</ram:TradingBusinessName>
            </ram:SpecifiedLegalOrganization>
            <ram:PostalTradeAddress>
               <ram:PostcodeCode>' . TCPDF_STATIC::_escapeXML($order_object->societe_code_postal) . '</ram:PostcodeCode>
               <ram:LineOne>' . TCPDF_STATIC::_escapeXML($order_object->societe_adresse) . '</ram:LineOne>
               <ram:LineTwo>' . TCPDF_STATIC::_escapeXML($order_object->societe_adresse2);
					if (Trim($order_object->societe_adresse3) <> '') {
						$facturxXml .= ' ' . TCPDF_STATIC::_escapeXML($order_object->societe_adresse3);
					}
					$facturxXml .= '</ram:LineTwo>
               <ram:CityName>' . TCPDF_STATIC::_escapeXML($order_object->societe_ville) . '</ram:CityName>
               <ram:CountryID>' . Facturx::get_country_code($societe_pays) . '</ram:CountryID>
            </ram:PostalTradeAddress>';
					if ($order_object->societe_email <> '') {
						$email = $order_object->societe_email;
						if (strpos($email, ';') !== false) { // L'Email de la société émettrice est multiple, on prend le premier
							$tmp = explode(';', $email);
							$email = end($tmp);
						}
						$facturxXml .= '
            <ram:URIUniversalCommunication>
               <ram:URIID schemeID="EM">' . TCPDF_STATIC::_escapeXML($email) . '</ram:URIID>
            </ram:URIUniversalCommunication>';
					}
					if (!empty($order_object->societe_tvaintra)) {
						$facturxXml .= '
            <ram:SpecifiedTaxRegistration>
               <ram:ID schemeID="VA">' . TCPDF_STATIC::_escapeXML($order_object->societe_tvaintra) . '</ram:ID>
            </ram:SpecifiedTaxRegistration>';
					}
					$facturxXml .= '
         </ram:SellerTradeParty>
         <ram:BuyerTradeParty>';
//            <ram:GlobalID schemeID="0088">3654789851</ram:GlobalID>
					$facturxXml .= '
            <ram:Name>' . TCPDF_STATIC::_escapeXML($order_object->societe_bill) . '</ram:Name>';
					if (!empty($order_object->siren)) {
						$facturxXml .= '
            <ram:SpecifiedLegalOrganization>
               <ram:ID schemeID="0002">' . TCPDF_STATIC::_escapeXML($order_object->siren) . '</ram:ID>
            </ram:SpecifiedLegalOrganization>';
					}
					$facturxXml .= '
            <ram:PostalTradeAddress>
               <ram:PostcodeCode>' . TCPDF_STATIC::_escapeXML($order_object->code_postal_bill) . '</ram:PostcodeCode>
               <ram:LineOne>' . TCPDF_STATIC::_escapeXML($order_object->adresse_bill) . '</ram:LineOne>
               <ram:LineTwo>' . TCPDF_STATIC::_escapeXML($order_object->adresse_bill2);
					if (Trim($order_object->adresse_bill3) <> '') {
						$facturxXml .= ' ' . TCPDF_STATIC::_escapeXML($order_object->adresse_bill3);
					}
					$facturxXml .= '</ram:LineTwo>
               <ram:CityName>' . TCPDF_STATIC::_escapeXML($order_object->ville_bill) . '</ram:CityName>
               <ram:CountryID>' . Facturx::get_country_code(!empty($order_object->pays_bill)?$order_object->pays_bill:$societe_pays) . '</ram:CountryID>
            </ram:PostalTradeAddress>';
					if (!empty($order_object->email_bill)) {
						$email = $order_object->email_bill;
						if (strpos($email, ';') !== false) { // L'Email de la société émettrice est multiple, on prend le premier
							$tmp = explode(';', $email);
							$email = end($tmp);
						}
						$facturxXml .= '
            <ram:URIUniversalCommunication>
               <ram:URIID schemeID="EM">' . TCPDF_STATIC::_escapeXML($email) . '</ram:URIID>
            </ram:URIUniversalCommunication>';
					}
					if (!empty($order_object->intracom_for_billing )) {
						$facturxXml .= '
            <ram:SpecifiedTaxRegistration>
               <ram:ID schemeID="VA">' . TCPDF_STATIC::_escapeXML($order_object->intracom_for_billing) . '</ram:ID>
            </ram:SpecifiedTaxRegistration>';
					}
					$facturxXml .= '
         </ram:BuyerTradeParty>';
					/* '         <ram:SellerOrderReferencedDocument>
					  <ram:IssuerAssignedID>SALES REF 2547</ram:IssuerAssignedID>
					  </ram:SellerOrderReferencedDocument>
					  <ram:BuyerOrderReferencedDocument>
					  <ram:IssuerAssignedID>PO201925478</ram:IssuerAssignedID>
					  </ram:BuyerOrderReferencedDocument>
					  <ram:ContractReferencedDocument>
					  <ram:IssuerAssignedID>CT2018120802</ram:IssuerAssignedID>
					  </ram:ContractReferencedDocument>
					  <ram:AdditionalReferencedDocument>
					  <ram:IssuerAssignedID>SUPPort doc</ram:IssuerAssignedID>
					  <ram:URIID>url:gffter</ram:URIID>
					  <ram:TypeCode>916</ram:TypeCode>
					  <ram:Name>support descript</ram:Name>
					  </ram:AdditionalReferencedDocument>
					  <ram:AdditionalReferencedDocument>
					  <ram:IssuerAssignedID>T}ER-002</ram:IssuerAssignedID>
					  <ram:TypeCode>50</ram:TypeCode>
					  </ram:AdditionalReferencedDocument>
					  <ram:AdditionalReferencedDocument>
					  <ram:IssuerAssignedID>REFCLI0215</ram:IssuerAssignedID>
					  <ram:TypeCode>130</ram:TypeCode>
					  </ram:AdditionalReferencedDocument>
					  <ram:SpecifiedProcuringProject>
					  <ram:ID>PROJET2547</ram:ID>
					  <ram:Name>Project reference</ram:Name>
					  </ram:SpecifiedProcuringProject>
					 */
					$facturxXml .= '
      </ram:ApplicableHeaderTradeAgreement>
      <ram:ApplicableHeaderTradeDelivery>';
					/* '         <ram:ShipToTradeParty>
					  <ram:GlobalID schemeID="0088">3654789851</ram:GlobalID>
					  <ram:Name>DEL Name</ram:Name>
					  <ram:PostalTradeAddress>
					  <ram:PostcodeCode>06000</ram:PostcodeCode>
					  <ram:LineOne>DEL 58 rue de la mer</ram:LineOne>
					  <ram:LineTwo>DEL line 2</ram:LineTwo>
					  <ram:CityName>BERLIN</ram:CityName>
					  <ram:CountryID>DE</ram:CountryID>
					  </ram:PostalTradeAddress>
					  </ram:ShipToTradeParty>';
					 */
					$facturxXml .= '
         <ram:ActualDeliverySupplyChainEvent>
            <ram:OccurrenceDateTime>
               <udt:DateTimeString format="102">' . $DateTimeString . '</udt:DateTimeString>
            </ram:OccurrenceDateTime>
         </ram:ActualDeliverySupplyChainEvent>';
					/*         <ram:DespatchAdviceReferencedDocument>
					  <ram:IssuerAssignedID>DESPADV002</ram:IssuerAssignedID>
					  </ram:DespatchAdviceReferencedDocument>
					  <ram:ReceivingAdviceReferencedDocument>
					  <ram:IssuerAssignedID>RECEIV-ADV002</ram:IssuerAssignedID>
					  </ram:ReceivingAdviceReferencedDocument>
					 */
					$facturxXml .= '
      </ram:ApplicableHeaderTradeDelivery>
      <ram:ApplicableHeaderTradeSettlement>';
//         <ram:PaymentReference>F20180023BUYER</ram:PaymentReference>
					$facturxXml .= '
         <ram:InvoiceCurrencyCode>EUR</ram:InvoiceCurrencyCode>
         <ram:SpecifiedTradeSettlementPaymentMeans>';
					// Moyen de paiement :
					/* En particulier, les codes
					  suivants peuvent être utilisés:
					  ZZZ : moyen défini préalablement entre les parties
					  10 : Espèces
					  20 : Chèque
					  30 : Virement (inclut Virement SEPA pour CHORUSPRO)
					  42 : Paiement sur compte bancaire
					  48 : Paiement par carte bancaire
					  49 : prélèvement (inclut Prélèvement SEPA pour CHORUSPRO)
					  57 : Moyen de paiement déjà défini entre les parties 58 : Virement SEPA (non utilisé pour CHORUSPRO : code 30)
					  59 : Prélèvement SEPA (non utilisé pour CHORUSPRO : code 49)
					  97 : Report
					 */
					if (($order_object->Mode_Reglement == '08') or ($order_object->Mode_Reglement == '85')) {
						$payment_mean = '49';  // Prélèvement
					} else {
						$payment_mean = '30'; // Virement
					}
					$facturxXml .= '
            <ram:TypeCode>' . $payment_mean . '</ram:TypeCode>
            <ram:PayeePartyCreditorFinancialAccount>
               <ram:IBANID>' . TCPDF_STATIC::_escapeXML($order_object->societe_iban) . '</ram:IBANID>
            </ram:PayeePartyCreditorFinancialAccount>
         </ram:SpecifiedTradeSettlementPaymentMeans>';
					/*
					  <ram:ApplicableTradeTax>
					  <ram:CalculatedAmount>0.00</ram:CalculatedAmount>
					  <ram:TypeCode>VAT</ram:TypeCode>
					  <ram:BasisAmount>-100.00</ram:BasisAmount>
					  <ram:CategoryCode>' . CategoryCode . '</ram:CategoryCode>
					  <ram:ExemptionReasonCode>vatex-eu-ic</ram:ExemptionReasonCode>
					  <ram:DueDateTypeCode>72</ram:DueDateTypeCode>
					  <ram:RateApplicablePercent>0.00</ram:RateApplicablePercent>
					  </ram:ApplicableTradeTax>
					  <ram:BillingSpecifiedPeriod>
					  <ram:StartDateTime>
					  <udt:DateTimeString format="102">' . $DateTimeString . '</udt:DateTimeString>
					  </ram:StartDateTime>
					  <ram:EndDateTime>
					  <udt:DateTimeString format="102">' . $DateTimeString . '</udt:DateTimeString>
					  </ram:EndDateTime>
					  </ram:BillingSpecifiedPeriod>
					  <ram:SpecifiedTradeAllowanceCharge>
					  <ram:ChargeIndicator>
					  <udt:Indicator>false</udt:Indicator>
					  </ram:ChargeIndicator>
					  <ram:ActualAmount>-5.00</ram:ActualAmount>
					  <ram:Reason>REMISE COMMERCIALE</ram:Reason>
					  <ram:CategoryTradeTax>
					  <ram:TypeCode>VAT</ram:TypeCode>
					  <ram:CategoryCode>' . CategoryCode . '</ram:CategoryCode>
					  <ram:RateApplicablePercent>0.00</ram:RateApplicablePercent>
					  </ram:CategoryTradeTax>
					  </ram:SpecifiedTradeAllowanceCharge>
					  <ram:SpecifiedTradeAllowanceCharge>
					  <ram:ChargeIndicator>
					  <udt:Indicator>true</udt:Indicator>
					  </ram:ChargeIndicator>
					  <ram:ActualAmount>-10.00</ram:ActualAmount>
					  <ram:Reason>FRAIS DEPLACEMENT</ram:Reason>
					  <ram:CategoryTradeTax>
					  <ram:TypeCode>VAT</ram:TypeCode>
					  <ram:CategoryCode>' . CategoryCode . '</ram:CategoryCode>
					  <ram:RateApplicablePercent>0.00</ram:RateApplicablePercent>
					  </ram:CategoryTradeTax>
					  </ram:SpecifiedTradeAllowanceCharge>
					  <ram:SpecifiedTradePaymentTerms>
					  <ram:DueDateDateTime>
					  <udt:DateTimeString format="102">' . $DateTimeString . '</udt:DateTimeString>
					  </ram:DueDateDateTime>
					  </ram:SpecifiedTradePaymentTerms>
					 */
					$facturxXml .= '
         <ram:SpecifiedTradeSettlementHeaderMonetarySummation>';
					/*
					  '            <ram:LineTotalAmount>-95.00</ram:LineTotalAmount>
					  <ram:ChargeTotalAmount>-10.00</ram:ChargeTotalAmount>
					  <ram:AllowanceTotalAmount>-5.00</ram:AllowanceTotalAmount>
					 */
					$facturxXml .= '
            <ram:TaxBasisTotalAmount>' . TCPDF_STATIC::_escapeXML($order_object->montant_ht) . '</ram:TaxBasisTotalAmount>
            <ram:TaxTotalAmount currencyID="EUR">' . TCPDF_STATIC::_escapeXML($order_object->total_tva) . '</ram:TaxTotalAmount>';

// BT 112 : GrandTotalAmount = total TTC
					$facturxXml .= '
            <ram:GrandTotalAmount>' . TCPDF_STATIC::_escapeXML($order_object->montant) . '</ram:GrandTotalAmount>
            <ram:TotalPrepaidAmount>' . TCPDF_STATIC::_escapeXML($order_object->acompte) . '</ram:TotalPrepaidAmount>
            <ram:DuePayableAmount>' . TCPDF_STATIC::_escapeXML($order_object->montant - $order_object->acompte) . '</ram:DuePayableAmount>
         </ram:SpecifiedTradeSettlementHeaderMonetarySummation>';
					/*
					  <ram:InvoiceReferencedDocument>
					  <ram:IssuerAssignedID>F20200003</ram:IssuerAssignedID>
					  <ram:FormattedIssueDateTime>
					  <qdt:DateTimeString format="102">' . $DateTimeString . '</qdt:DateTimeString>
					  </ram:FormattedIssueDateTime>
					  </ram:InvoiceReferencedDocument>
					  <ram:ReceivableSpecifiedTradeAccountingAccount>
					  <ram:ID>BUYER ACCOUNT REF</ram:ID>
					  </ram:ReceivableSpecifiedTradeAccountingAccount>
					 */
					$facturxXml .= '
      </ram:ApplicableHeaderTradeSettlement>
   </rsm:SupplyChainTradeTransaction>
</rsm:CrossIndustryInvoice>';
					//echo $facturxXml;  die();
					$facturx->generateFacturxFromFiles($pdf_string, $facturxXml, 'autodetect', true, ($folder === false?$send_mode_if_no_folder:$folder), array(), false, $sign_if_available, $file_name);
					// FICHIER envoyé : dans ce cas on a déjà fait un die() dans generateFacturxFromFiles
					// on a généré un fichier $file_name qui sera retourné à la fin de la fonction

					/* // Extract Factur-X XML
					  $facturx = new Facturx();
					  $facturxXml = $facturx->getFacturxXmlFromPdf($facturxPdf);

					  // Check Factur-X XML against official Factur-X XML Schema Definition
					  $facturx = new Facturx();
					  $isValid = $facturx->checkFacturxXsd($facturxXml);
					 */
				} else {
					// PDF normal sans Factur-X
					if ($sign_if_available && function_exists('set_pdf_signature')) {
						set_pdf_signature($this);
					}
					if ($folder === false) {
						// $send_mode_if_no_folder = I : Sortie directe vers le navigateur
						//   ou en mode D : Download forcé
						$GLOBALS['last_bill_file_name'] = $file_name;
						if($send_mode_if_no_folder == 'S') {
							// Contenu renvoyé en tant que chaine de caractères
							return $this->Output($file_name, $send_mode_if_no_folder);
						} else {
							$this->Output($file_name, $send_mode_if_no_folder);
							die();
						}
					} else {
						// Création d'un fichier et on continue ensuite
						$this->Output($folder . $file_name, "F");
					}
				}
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
					$this->backgoundBigWatermark(get_payment_status_name($this->order_object->id_statut_paiement), 40, 470); 
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
				$this->addPageNumber($this->getGroupPageNo() . ' / ' . $this->getPageGroupAlias(), vn($GLOBALS['site_parameters']['PageNumber_font_size'], 8), array('page_number_x'=>vn($GLOBALS['site_parameters']['page_number_x']), 'page_number_y'=>vn($GLOBALS['site_parameters']['page_number_y'])));
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
		if(!empty($GLOBALS['site_parameters']['invoice_pdf_remarque_font_size'])) {
			$this->remarque_font_size = $GLOBALS['site_parameters']['invoice_pdf_remarque_font_size'];
		}
		$comments_array = array();
		if(!empty($order_infos['delivery_infos'])) {
			$comments_array[] = $GLOBALS["STR_SHIPPING_TYPE"] . $GLOBALS["STR_BEFORE_TWO_POINTS"]. ': ' . $order_infos['delivery_infos'];
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

		if (isset($GLOBALS['site_parameters']['amount_to_order_display_first_message']) && isset($GLOBALS['site_parameters']['amount_to_order_display_message'])) {
			if ($order_object->total_produit > $GLOBALS['site_parameters']['amount_to_order_display_first_message'] && $order_object->total_produit < $GLOBALS['site_parameters']['amount_to_order_display_message']) {
				$comments_array[] = $GLOBALS['STR_AMOUNT_TO_ORDER_DISPLAY_FIRST_MESSAGE'];
			} elseif ($order_object->total_produit > $GLOBALS['site_parameters']['amount_to_order_display_message']){
				$comments_array[] = $GLOBALS['STR_AMOUNT_TO_ORDER_DISPLAY_SECOND_MESSAGE'];
			}
		}
		if(!empty($order_object->commentaires)) {
			$comments_array[] = $order_object->commentaires;
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
		if ($bill_mode == "user_custom_products_list") {
			$y_start_products = 75;
		} else {
			// start_product_cols_y1 start_y : contenu
			$y_start_products = vn($GLOBALS['site_parameters']['start_product_cols_y1_start_y'], 100);
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
		$this->AddPage("", "", true);
		$first_page = $this->page;

		$cadre_total_height = vn($GLOBALS['site_parameters']['cadre_total_height'], 30);
		
		// La fonction getStringHeight ne marche pas correctement avec le HTML, donc on fait un calcul plus précis avec getRemarqueHeight
		// $this->comments_height = $this->getStringHeight($this->w - 10 * 2, implode("\n", $comments_array), false, true, '', '');
		// A FAIRE APRES AVOIR CREE LA PREMIERE PAGE
		$this->comments_height = $this->getRemarqueHeight($comments_string) + 2 * $this->cMargin;
		// Quand on aura fini la liste des produits, on veut la place pour les blocs de fin de facture. L'affichage des blocs de fin de facture dépend du mode
		if ($bill_mode == "user_custom_products_list") {
			$y_max_allowed_last_page = $this->h_dispo - $this->comments_height - 9;
		} else {
			// pour gérer l'espace en bas à la fin du document (dernière page), on peut utiliser bottom_offset par exemple si on a rempli la variable invoice_bottom_text.
			// NB : Par défaut bottom_offset contient la hauteur "cadre_total_height" = la hauteur des blocs de TVA
			$bottom_offset_default = 20;
			if(empty($GLOBALS['site_parameters']['cadrenet_starts_with_remarques'])) {
				$bottom_offset_default += $cadre_total_height;
			} else {
				// Dans cette configuration spécifique, le bloc de cadrenet est inclus dans comments_height, donc bottom_offset ne contient pas $cadre_total_height
				$bottom_offset_default += vn($GLOBALS['site_parameters']['cadrenet_starts_with_remarques_spacing']);
			}
			$y_max_allowed_last_page = $this->h_dispo - $this->comments_height - vn($GLOBALS['site_parameters']['bottom_offset'], $bottom_offset_default) + 4;
		}

		if (empty($GLOBALS['site_parameters']['header_on_each_page'])) {
			// On n'affiche le header que sur la première page
			$this->get_document_header();
		} else {
			// On redéfinit la marge pour les pages suivantes pour tenir compte de la place de Header
			// En effet après appel du Header(), on sera à la position $y_start_products
			$this->SetTopMargin($y_start_products);
		}
		foreach (array('standalone_perso_line', 'standalone_perso_line_2', 'standalone_perso_line_3', 'standalone_perso_line_4') as $this_config_name) {
			if (!empty($GLOBALS['site_parameters'][$this_config_name])) {
				// Ligne de texte, inclassable, à placer ou l'on veut sur le document PDF.
				$font_size = 10;
				if (!empty($GLOBALS['site_parameters'][$this_config_name . '_font_size'])) {
					$font_size = $GLOBALS['site_parameters'][$this_config_name . '_font_size'];
				} elseif (!empty($GLOBALS['site_parameters']['global_font_size'])) {
					$font_size = $GLOBALS['site_parameters']['global_font_size'];
				}
				if (!empty($GLOBALS['site_parameters'][$this_config_name . '_font_family'])) {
					$font_family = $GLOBALS['site_parameters'][$this_config_name . '_font_family'];
				} elseif(!empty($GLOBALS['site_parameters']['pdf_font_family'])) {
					$font_family = $GLOBALS['site_parameters']['pdf_font_family'];
				} else {
					$font_family = "freesans";
				}
				if (!empty($GLOBALS['site_parameters'][$this_config_name . '_font_color'])) {
					$this->SetTextColor($GLOBALS['site_parameters'][$this_config_name . '_font_color'][0], $GLOBALS['site_parameters'][$this_config_name . '_font_color'][1], $GLOBALS['site_parameters'][$this_config_name . '_font_color'][2]);
				}
				$this->SetFont($font_family, vb($GLOBALS['site_parameters'][$this_config_name . '_bold'],""), $font_size); 
				$y = vn($GLOBALS['site_parameters'][$this_config_name . '_y'], $y_start_products - 15);
				$x = vn($GLOBALS['site_parameters'][$this_config_name . '_x'], $this->GetX());		
				$ishtml = true;
				$this->SetXY($x, $y);
				$this_bloc_text = array();
				foreach ($GLOBALS['site_parameters'][$this_config_name] as $this_text) {
					if (strip_tags($this_text) != $this_text) {
						// Il y a des tags HTML dans le texte. On configure MultiCell en conséquence.
						$this_text = StringMb::nl2br_if_needed($this_text);
					}
					$this_bloc_text[] = $this_text;
				}
				$this->MultiCell(vn($GLOBALS['site_parameters'][$this_config_name . '_w'], 90), 4, implode('<br />', $this_bloc_text) . "\n", vn($GLOBALS['site_parameters'][$this_config_name . '_border'], 0), vn($GLOBALS['site_parameters'][$this_config_name . '_align'], "L"), false, 1, '', '', true, 0, $ishtml);
			}
		}

		$lines_count = 0;
		$line = null;
		// Initialisation du début de l'affichage des produits
		$y = $y_start_products;

		// BOUCLE SUR L'ENSEMBLE DES LIGNES A AFFICHER
		// Pagination automatique sur N pages
		
		foreach($product_infos_array as $this_ordered_product) {
			$lines_count++;
			$prix = fprix($this_ordered_product["prix"], ($bill_mode != "user_custom_products_list"), $order_object->devise, true, $order_object->currency_rate);
			$prix_ht = fprix($this_ordered_product["prix_ht"], true, $order_object->devise, true, $order_object->currency_rate);
			$total_prix_ht = fprix($this_ordered_product["total_prix_ht"], true, $order_object->devise, true, $order_object->currency_rate);
			$total_prix = fprix($this_ordered_product["total_prix"], true, $order_object->devise, true, $order_object->currency_rate);
			// on ne veut pas utiliser filtre_pdf sur le texte du produit, car il contient éventuellement du HTML, ce qui est géré avec writeHTMLCell dans la fonction addLine
			// $product_text = filtre_pdf($this_ordered_product["product_text"]);
			
			$product_text = $this_ordered_product["product_text"];
			$hook_result = call_module_hook('bill_pdf_get_product_line', array('order_object' => $order_object, 'this_ordered_product' => $this_ordered_product, 'product_text' => $product_text, 'prix' => $prix, 'total_prix_ht' => $total_prix_ht, 'total_prix' => $total_prix, 'prix_ht' => $prix_ht, 'previous_line' => $line), 'array');
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
							$this->Image($GLOBALS['uploaddir'].'/thumbs/'.StringMb::rawurlencode($this_thumb), 15, $y - 6);
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
			$this->AddPage("", "", true);
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
					$header_height = vn($GLOBALS['site_parameters']['header_cols_height'], 5);
				}
				if($this_page == $first_page || !empty($GLOBALS['site_parameters']['header_on_each_page'])) {
					// On démarre après les entêtes de la première page
					if($this->bill_mode == 'user_custom_products_list') {
						$y_begin_products = 60;
					} else {
						// start_product_cols_y1 addCols : cadre
						$y_begin_products = vb($GLOBALS['site_parameters']['start_product_cols_y1_addCols'], 92);
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

		$cadre_net_w = (!empty($GLOBALS['site_parameters']['cadre_net_w'])?$GLOBALS['site_parameters']['cadre_net_w']:55);
		$cadre_tva_w = (!empty($GLOBALS['site_parameters']['addCadreTva_by_column_half_total_width'])?129:50);
		$addRemarque_start = $y_max_allowed_last_page + $this->cMargin;
		if (!empty($GLOBALS['site_parameters']['cadrenet_starts_with_remarques'])) {
			// Affichage non standard : on commence le cadre des totaux à la même hauteur que les commentaires
			$this->addCadreNet($this->w - ($this->rMargin - 10) - 10 - $cadre_net_w, $y_max_allowed_last_page, $cadre_net_w, $cadre_total_height, $order_infos['net_infos_array'], $order_infos['tva_infos_array']);
			if (!empty($GLOBALS['site_parameters']['cadrenet_starts_with_remarques_spacing'])) {
				// Décale les commentaires par rapport à CadreNet, par exemple pour passer sous le cadre
				$addRemarque_start += $GLOBALS['site_parameters']['cadrenet_starts_with_remarques_spacing'];
			}
		}
		if(!empty($comments_string)) {
			// $this->comments_height contient deux fois la cMargin => du coup une fois cMargin est compris au début dans $addRemarque_start, et l'autre cMargin laisse un espace à la fin
			$this->addRemarque($comments_string, $addRemarque_start, $this->comments_height); 
			$y = $y_max_allowed_last_page + $this->comments_height;
		} else {
			$y = $y_max_allowed_last_page + vn($GLOBALS['site_parameters']['y_max_allowed_last_page_without_comment']);
		}
		if ($bill_mode != "user_custom_products_list") {
			$cadre_iban_at_bottom_w = $this->w_dispo + ($this->lMargin + $this->rMargin) - 10 - $cadre_net_w - 5 - $cadre_tva_w - 5 - 10; // 75 par défaut
			if ($bill_mode == "bdc" || !empty($GLOBALS['site_parameters']['quote_prepare_addCadreSignature'])) {
				// Bon de commande à signer
				$this->addCadreSignature($y, $cadre_total_height);
			} elseif (!empty($GLOBALS['site_parameters']['invoice_display_iban_at_bottom'])) {
				// Facture ou devis à payer par virement : on présente les informations bancaires
				$this->display_iban_at_bottom($y, $cadre_iban_at_bottom_w, $cadre_total_height, vb($order_object->Banque));
			} elseif (!empty($GLOBALS['site_parameters']['invoice_display_soc_address_at_bottom'])) {
				// Affichage de l'adresse postale et le SIRET de la société en bas du footer, à l'emplacement habituellement reservé aux coordonnées bancaires
				$this->display_soc_address_at_bottom($y, $cadre_iban_at_bottom_w, $cadre_total_height, $order_object);
			}
			if (!empty($GLOBALS['site_parameters']['force_netbloc_y_without_remarques_height'])) {
				// Pour avoir le tableau de addCadreNet qui colle au cadre du tableau des codes ventes 
				$y = $y_max_allowed_last_page-4;
			}
			if (!empty($GLOBALS['site_parameters']['add_line_separation_before_CadreNet_CadreTVA'])) {
				$this->Line($x, $y + 1 , $this->w_dispo + $this->lMargin, $y + 1); 
			}
			// Par défaut : CadreNet et CadreTVA sont à la même hauteur
			if (empty($GLOBALS['site_parameters']['cadrenet_starts_with_remarques'])) {
				// NB : $y n'est pas modifié dans addCadreNet
				$this->addCadreNet($this->w - ($this->rMargin - 10) - 10 - $cadre_net_w, $y, $cadre_net_w, $cadre_total_height, $order_infos['net_infos_array'], $order_infos['tva_infos_array']);
			}
			if(empty($GLOBALS['site_parameters']['total_tva_in_net_bloc']) && empty($GLOBALS['site_parameters']['CadreTva_display_disable'])) {
				// NB : $y n'est pas modifié dans addCadreTva
				$this->addCadreTva($this->w - ($this->rMargin - 10) - 10 - $cadre_net_w - 5 - $cadre_tva_w, $y, $cadre_tva_w, $cadre_total_height, $order_infos);
			}
			if (empty($GLOBALS['site_parameters']['cadrenet_starts_with_remarques']) || empty($GLOBALS['site_parameters']['total_tva_in_net_bloc'])) {
				$y = $y + $cadre_total_height;
			}
			// Mentions pour TVA, centrées sous cadres liés à la TVA et au paiement
			if (empty($GLOBALS['site_parameters']['display_footer_disable'])) {
				$this->display_bottom_text($y + 5, $order_object);
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
		$annexe_html = $this->annexeHtml($product_infos_array, $order_object);
		if(!empty($annexe_html) && empty($GLOBALS['site_parameters']['invoice_pdf_annexe_html_disable'])) {
			// On réactive la gestion de page automatique
			$this->SetAutoPageBreak(true, $this->h - $y_max_allowed);
			// On crée la première page de l'annexe. Les autres pages éventuellement nécessaires seront créées automatiquement
			$this->AddPage("", "", true);
			// Mettre ici l'écriture du HTML de l'annexe
			$this->writeHTMLCell($this->w_dispo, null, $this->lMargin, $this->tMargin, $annexe_html);
		}

		if(!empty($GLOBALS['site_parameters']['content_on_new_page'])) {
			// On réactive la gestion de page automatique
			$this->SetAutoPageBreak(true, $this->h - $y_max_allowed);
			// On crée la première page de l'annexe. Les autres pages éventuellement nécessaires seront créées automatiquement
			$this->AddPage("", "", true);
			// Mettre ici l'écriture du HTML de l'annexe
			$this->writeHTMLCell(null, null, 5, 5, $GLOBALS['site_parameters']['content_on_new_page'], 0, 1, false, true, "C");
		}
		if(function_exists('get_echeancier')) {
			$echeancier_html = get_echeancier($order_object, $this->bill_mode);
			if(!empty($echeancier_html)) {
				// On réactive la gestion de page automatique
				$this->SetAutoPageBreak(true, $this->h - $y_max_allowed);
				// On crée la première page de l'annexe. Les autres pages éventuellement nécessaires seront créées automatiquement
				$this->AddPage("", "", true);
				if (empty($GLOBALS['site_parameters']['header_on_each_page'])) {
					$this->get_document_header();
				}
				// pour définir la hauteur de l'affichage de echeancier_html, on se base sur la hauteur défini pour le bloc de produits, qui est déjà réglé en fonction de la hauteur de $this->get_document_header();
				// La hauteur ne doit pas être défini avec GetY, puisque dans ce cas la position en hauteur varie en fonction de la longueur de l'adresse de facturation
				$y = vn($GLOBALS['site_parameters']['start_product_cols_y1_start_y'], 100);
				// Mettre ici l'écriture du HTML de l'echéancier
				$this->writeHTMLCell(null, null, 5, $y, $echeancier_html, 0, 1, false, true);
			}
		}
	}
	
	/**
	 * getSocieteInfoText()
	 *
	 * @param boolean $use_admin_rights
	 * @param boolean $skip_registration_number
	 * @param intval $site_id
	 * @param string $societe
	 * @param array $params
	 * @return string
	 */
	function getSocieteInfoText($use_admin_rights = true, $skip_registration_number = false, $site_id = 0, $societe = null, $params = null)
	{
		$output = '';
		$hook_output = call_module_hook('invoice_societe_info_text', array('Societe' => $societe, 'params'=>$params), 'string');
		if (!empty($GLOBALS['site_parameters']['getSocieteInfoText'])) {
			$output = $GLOBALS['site_parameters']['getSocieteInfoText'];
		} elseif (!empty($hook_output)) {
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
				$output = $pdf_societe . $pdf_adresse . $pdf_codepostal . " " . $pdf_ville . ($display_pays_separator?' - ':'') . $pdf_pays . str_replace("\n", " - ", $pdf_siret) . $pdf_tvaintra_company . str_replace("\n", " - ", $pdf_tel);
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
	function annexeHtml($product_infos_array = null, $order_object = null)
	{
		$annexeHtml = null;
		$hook_result = call_module_hook('pdf_annexe_html', array('product_infos_array' => $product_infos_array, 'order_object' => $order_object), 'array');
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
		$first_order = false;
		if(empty($GLOBALS['site_parameters']['display_header_disable'])) {
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
			if ($bill_mode != "user_custom_products_list") {
				if(!empty($GLOBALS['site_parameters']['mode_transport']) && !empty($order_infos['client_infos_ship'])) {
					// Ajout de l'adresse de livraison seulement si la boutique a une gestion du port
					$this->addClientAdresseExpedition($order_infos['client_infos_ship']);
					$adresse_facturation_position = 'left';
				} else {
					$adresse_facturation_position = 'right';
					$this->addClientPersonnalizationInfo();
				}
			}

			$this->addClientAdresseFacturation($order_infos['client_infos_bill'], $order_object->id_utilisateur, vb($GLOBALS['site_parameters']['adresse_facturation_position'], vb($adresse_facturation_position)));
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
