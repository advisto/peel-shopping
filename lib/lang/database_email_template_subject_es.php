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
// $Id: database_email_template_subject_es.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["subject"] = array(
  "signature_commercial" => "firma servicio comercial",
  "signature_comptabilite" => "firma servicio contabilidad",
  "signature_referencement" => "firma servicio toma de referencia",
  "signature_informatique" => "firma servicio técnico",
  "signature_communication" => "firma servicio comunicación",
  "signature_marketing" => "firma servicio marketing",
  "signature_direction" => "firma la dirección",
  "signature_externe" => "firma servicio externo",
  "signature_support" => "firma soporte clientela",
  "download_product" => "Descarga de su pedido [ORDER_ID]",
  "commande_parrain_avoir" => "Su crédito siguiente el pedido de su ahijado",
  "envoie_client_code_promo" => "En agradecimiento por su lealtad",
  "insere_ticket" => "Contacto por [EMAIL] [TELEPHONE]",
  "admin_info_payment_credit_card" => "Pedido CB está grabando",
  "admin_info_payment_credit_card_3_times" => "Pedido CB tres veces durante la grabación",
  "send_client_order_html" => "Su pedido [ORDER_ID] en [SITE]",
  "send_client_order_pdf" => "Su pedido [ORDER_ID] en [SITE]",
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
  "cree_cheque_cadeau_client_type2" => "[FRIEND] le ofrece un cheque regalo",
  "cree_cheque_cadeau_client_admin" => "Creación de un cheque regalo",
  "gift_list" => "Lista de regalos",
  "email_ordered_cadeaux" => "Pedido sobre su lista de regalos \"[LIST_NAME]\"",
  "listecadeau_voir" => "Pedido sobre su lista de regalos \"[LIST_NAME]\"",
  "parrainage" => "[PSEUDO] quiere patrocinarle",
  "email_alerte" => "Producto en stock en [SITE]",
  "decremente_stock" => "Notificación de alerta de STOCK",
  "admin_login" => "Conexión de [USER] [REMOTE_ADDR]",
  "signature" => "Firmas mensajes de correo electrónico automáticos",
  "cree_cheque_cadeau_client_type1" => "[FRIEND] le ofrece un cheque regalo",
  "warn_admin_user_subscription" => "[CIVILITE] [NAME] [NOM_FAMILLE] acaba de registrarse en [SITE_NAME]",
  "warn_admin_reve_subscription" => "El distribuidor [CIVILITE] [NAME] [NOM_FAMILLE] acaba de registrarse en [SITE_NAME]",
  "email_retour_virement" => "Validación de su devolución de mercancías #[RETURN_ID]",
  "email_retour_avoir" => "Validación de su devolución de mercancías #[RETURN_ID]",
  "email_reste_avoir_remboursement" => "Reembolso de su crédito #[RETURN_ID]",
  "email_remboursement" => "Validación de su devolución de mercancías #[RETURN_ID]",
  "email_retour_client" => "Su solicitud de devolución",
  "cron_order_payment_failure_alerts" => "Ayuda para su pago",
  "cron_order_not_paid_alerts" => "El pago de su pedido",
  "cron_update_contact_info" => "La confirmación de la validez de la información",
  "inscription_newsletter" => "Suscripción al boletín de noticias de [SITE]",
  "send_mail_for_account_creation_stop" => "Su cuenta de revendedor",
  "send_mail_for_account_creation_reve" => "El cambio de estado de su cuenta de revendedor",
  "send_mail_for_account_creation_stand" => "Su cuenta de afiliado",
  "send_mail_for_account_creation_affi" => "Cambio en el estado de su cuenta de afiliado",
  "validating_registration_by_admin" => "Confirmación de la creación de cuenta",
  "confirm_newsletter_registration" => "suscripción al boletín / ofertas comerciales",
  "user_double_optin_registration" => "Validación de la inscripción en [SITE]"
);

