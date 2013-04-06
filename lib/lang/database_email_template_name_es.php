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
// $Id: database_email_template_name_es.php 36232 2013-04-05 13:16:01Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["name"] = array(
  "download_product" => "Descarga de su pedido",
  "commande_parrain_avoir" => "Su crédito siguiente el pedido de su ahijado",
  "envoie_client_code_promo" => "En agradecimiento por su lealtad",
  "insere_ticket" => "Contacto por [EMAIL] [TELEPHONE]",
  "admin_info_payment_credit_card" => "Pedido CB está grabando",
  "admin_info_payment_credit_card_3_times" => "Pedido CB tres veces durante la grabación",
  "send_client_order_html" => "Su pedido [ORDER_ID] en [SITE] con factura HTML",
  "send_client_order_pdf" => "Su pedido [ORDER_ID] en [SITE] con factura PDF",
  "send_avis_expedition" => "Aviso de envío del pedido #[ORDER_ID]",
  "email_commande" => "Confirmación del pedido [ORDER_ID]",
  "send_mail_order_admin" => "[ORDER_ID] Grabación del pedido [SITE]",
  "initialise_mot_passe" => "Nueva contraseña para su cuenta",
  "send_mail_for_account_creation" => "Abrir una cuenta",
  "insere_avis" => "El usuario ha añadido un comentario en [SITE]",
  "bons_anniversaires" => "[SITE] le desea un feliz cumpleaños",
  "direaunami_sent" => "[PSEUDO] ha visitado el sitio [SITE] y le lo recomenda",
  "cheques_cadeaux" => "[EMAIL_ACHETEUR] le ofrece un cheque regalo",
  "cree_cheque_cadeau_friend" => "[EMAIL] le ofrece un cheque regalo",
  "cree_cheque_cadeau_admin" => "Creación de un cheque regalo",
  "cree_cheque_cadeau_client_type2" => "[FRIEND] le ofrece un cheque regalo (importe)",
  "cree_cheque_cadeau_client_admin" => "Creación de un cheque regalo",
  "gift_list" => "Lista de regalos",
  "email_ordered_cadeaux" => "Pedido sobre su lista de regalos \"[LIST_NAME]\"",
  "listecadeau_voir" => "Pedido sobre su lista de regalos \"[LIST_NAME]\"",
  "parrainage" => "[PSEUDO] quiere patrocinarle",
  "email_alerte" => "Producto en stock en [SITE]",
  "decremente_stock" => "Notificación de alerta de STOCK",
  "admin_login" => "Administrador de información de acceso",
  "signature" => "mensajes de correo electrónico automáticas de firmas",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] le ofrece un cheque regalo (por ciento)",
  "warn_admin_user_subscription" => "Advertencia del registro de un usuario",
  "email_retour_virement" => "Validación de su devolución de mercancías [RETURN_ID]",
  "email_retour_avoir" => "Validación de su devolución de mercancías [RETURN_ID]",
  "email_reste_avoir_remboursement" => "Reembolso de su crédito [RETURN_ID]",
  "email_remboursement" => "Reembolso de su devolución de mercancía [RETURN_ID]",
  "email_retour_client" => "Su solicitud de devolución",
  "cron_order_payment_failure_alerts" => "Ayuda para su pago",
  "cron_order_not_paid_alerts" => "El pago de su pedido",
  "cron_update_contact_info" => "La confirmación de la validez de la información"
);

?>