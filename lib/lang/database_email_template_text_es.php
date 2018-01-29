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
// $Id: database_email_template_text_es.php 55332 2017-12-01 10:44:06Z sdelaporte $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["text"] = array(
  "signature_commercial" => "
Servicio comercial",
  "signature_comptabilite" => "
Servicio contabilidad",
  "signature_referencement" => "
Servicio toma de referencia",
  "signature_informatique" => "
Servicio técnico",
  "signature_communication" => "
Servicio comunicación",
  "signature_marketing" => "
Servicio marketing",
  "signature_direction" => "
La dirección",
  "signature_externe" => "
Servicio externo",
  "signature_support" => "
Soporte clientela",
  "download_product" => "Hola,

Su pedido [ORDER_ID] ha sido confirmado, le invitamos a descargar su pedido desde el siguiente enlace:

Su enlace para la descarga: [WWWROOT]/modules/download/telecharger.php?id=[ORDER_ID]&key=[CLE]

Podrá descargar este archivo una vez.
Si experimenta problemas con la descarga, por favor contacta con [SUPPORT_COMMANDE] para que le devuelve un enlace de descarga.

Además, le invitamos a editar su factura desde el siguiente enlace:

[WWWROOT]/factures/commande_pdf.php?code_facture=[CODE_FACTURE]&mode=facture ",
  "commande_parrain_avoir" => "Hola,

Siguiendo el pedido de una de sus ahijados en nuestra tienda en línea, usted beneficia de un crédito a [AVOIR] a cuenta de su próximo pedido.",
  "envoie_client_code_promo" => "Hola [CIVILITE] [PRENOM] [NOM_FAMILLE]

Para agradecerle de su pedido en nuestra tienda en línea, le ofrecemos este código promocional: [NOM_CODE_PROMO].

Le permite disfrutar de [REMISE] a cuenta de su próximo pedido.

Este código promocional le está especialmente destinado y es válido sólo una vez a partir de ahora hasta el [DATE_FIN].

Le agradecemos por su confianza.",
  "insere_ticket" => "Hola,

Detalle del mensaje [DATE]:

Nombre: [NOM_FAMILLE]
Nombre: [PRENOM]
Empresa: [SOCIETE]
Dirección : [ADRESSE]
Tel: [TELEPHONE]
E-mail: [EMAIL]
Disponibilidad: [DISPO]

Asunto: [SUJET]

Mensaje:

[TEXTE]

IP: [REMOTE_ADDR]
",
  "admin_info_payment_credit_card" => "Hola,

Un pedido con el número [ORDER_ID] ha sido registrado [WWWROOT]/",
  "admin_info_payment_credit_card_3_times" => "Hola,

Un pedido con pago en tres veces con el número [ORDER_ID] ha sido registrado [SITE]
",
  "send_client_order_html" => "Hola,

Le invitamos a abrir el siguiente enlace para imprimir o pagar su pedido en el sitio [SITE]:

Su pedido:
[URL_FACTURE]


Si el enlace no se muestra correctamente en su navegador, por favor haga un copiar-pegar. La URL debe terminarse con mode=[MODE].

Su pedido será procesado a la recepción de su pago.

Le agradecemos por su confianza en [SITE].
",
  "send_client_order_pdf" => "Hola,

Le invitamos a abrir el siguiente enlace para imprimir o pagar su pedido en el sitio [SITE]:

Su pedido:
[URL_FACTURE]

Si el enlace no se muestra correctamente en su navegador, por favor haga un copiar-pegar. La URL debe terminarse con mode=[MODE].

Su pedido será procesado a la recepción de su pago.

Le agradecemos por su confianza en [SITE].
",
  "send_avis_expedition" => "Hola [PRENOM] [NOM_FAMILLE]

Tenemos el placer de confirmarle la preparación y la próxima entrega del pedido #[ORDER_ID].

Los artículos enviados:
[SHIPPED_ITEMS]
La forma de entrega que ha escogido al realizar un pedido es: [TYPE]
Referencias del envío: le No. del paquete es [COLIS]. Por correo, puede seguir su paquete haciendo clic en el siguiente enlace: http://www.coliposte.fr/

Dirección de envío:
[CLIENT_INFOS_SHIP]

RECORDATORIOS IMPORTANTES:

Le invitamos a seguir nuestras instrucciones cuidadosamente para evitar litigios. El transporte es una fase crítica que requiere atención.
Por lo tanto, le agradecemos a cumplir con las normas básicas de uso recordadas abajo:
- Paquete en mal estado
- Paquete abierto y / o triturado
- Sistema de cierre (adhesivo...) dañado o no original

¿QUÉ HACER?
- No abra el paquete
- Rechazar el paquete
- Hacer reservas inmediatamente al transportista
- Notificar la cuestión, indicando el número de pedido en cuestión

REEMBOLSO
[SITE] rechazará sistemáticamente el reembolso de un pedido si:
- Ninguna reserva ha sido emitido al portador
- No hay pruebas de la la emisión de las reservas

Gracias por su comprensión y quedamos a su disposición para cualquier información adicional.",
  "email_commande" => "Hola [CIVILITE] [PRENOM] [NOM_FAMILLE]

Su pedido #[ORDER_ID] [DATE] ha sido registrado en el sitio [SITE].

---------------------------
RECORDATORIO DE SU ORDEN
---------------------------

Importe: [MONTANT] TTC
Forma de pago: [PAIEMENT]

---------------------------
Dirección de facturación
---------------------------
[CLIENT_INFOS_BILL]

---------------------------
Dirección de entrega
---------------------------
[CLIENT_INFOS_SHIP]

---------------------------
Los artículos pedidos
---------------------------
[BOUGHT_ITEMS]
Los costos de envío:
[COUT_TRANSPORT]
Typo de expedición:
[TYPE]

Usted puede seguir en tiempo real el estado de su pedido:
Una vez pagado su pedido, la factura aparecerá en su cuenta en los detalles de la orden

Para acceder al historial de comandos:
 - Haga clic en MI CUENTA
 - Identifique
 - Haga clic en Historial de pedidos.

Gracias por su confianza.",
  "send_mail_order_admin" => "Hola,

El pedido [ORDER_ID] ha sido registrado en el sitio [SITE].

Cliente de correo electrónico: [EMAIL]
Código de pedido: [ORDER_ID]
Importe del pedido: [MONTANT]
Fecha de pedido: [O_TIMESTAMP]
Pago: [PAIEMENT]

Le agradecemos a consultar a la interfaz de administración de su sitio.
",
  "initialise_mot_passe" => "Hola,

Una solicitud de nueva contraseña en el sitio [SITE] ha sido inicializado.

Para confirmar su solicitud de renovación de contraseña, debe hacer clic en el siguiente enlace: [LINK]
Tiene 24 horas después de la solicitud de renovación para realizar esta operación. Después de este tiempo, el enlace ya no es válido.

Este correo electrónico fue enviado de forma automática, gracias no responder a este mensaje.
",
  "send_mail_for_account_creation" => "Hola,

Tiene que crear una cuenta en [SITE].

Su nombre de usuario es: [EMAIL]
Su contraseña: [MOT_PASSE]
",
  "insere_avis" => "Hola,

[PRENOM] [NOM_FAMILLE] ha añadido el siguiente comentario:

Nombre del Producto: [NOM_PRODUIT]

Comentario enviado: [AVIS]

Para validar este aviso, debe conectarse a la interfaz de administración y cambiar su estatus en la rúbrica Webmastering > Marketing > Comentarios.",
  "bons_anniversaires" => "Hola [CIVILITE] [PRENOM] [NOM_FAMILLE]

Para su cumpleaños, le ofrecemos este código promocional: [NOM_CODE_PROMO].

Le permite beneficiar de [REMISE] descuento en su próximo pedido [MAIL_EXTRA_INFOS]

Este código promocional está especialmente diseñado para usted y es válido sólo una vez a partir de ahora hasta el [DATE_FIN].

[SITE] le desea un feliz cumpleaños.",
  "direaunami_sent" => "Hola [NOM_FAMILLE]

[PSEUDO] ha visitado el sitio [SITE] y cree que este artículo le parecerá interesante:

URL: [PRODUCT_LINK]

Comentarios adicionales:
------------------------------------
[COMMENTS]
------------------------------------ ",
  "cheques_cadeaux" => "Hola,

[EMAIL_ACHETEUR] quería darle un regalo!

Para calificar, simplemente ingrese al sitio [WWWROOT]/ y utilice el código [CHECK_NAME].

A continuación, recibirá un descuento del [REMISE_VALEUR] por una compra mínima de [MONTANT_MIN] HT por su pedido.",
  "cree_cheque_cadeau_friend" => "Hola,

Su amigo [PRENOM] [NOM_FAMILLE] quería darle un regalo en el sitio [SITE].

Para calificar, simplemente ingrese al sitio [WWWROOT]/ el código y utilice [CODE].

A continuación, recibirá un descuento del [PRIX] en su pedido.
",
  "cree_cheque_cadeau_admin" => "Hola,

El código de descuento [CODE] con el CHEQUE REGALO módulo ha sido creado  en [SITE].
",
  "cree_cheque_cadeau_client_type2" => "Hola,

[SITE] ofrece un regalo de 30 días de [MONTANT].

Para calificar, simplemente ingrese al sitio [WWWROOT]/ el código y utilice su código [CODE].
",
  "cree_cheque_cadeau_client_admin" => "Hola,

El código promocional [CODE] ha sido creado con el módulo del patrocinio en [SITE].
",
  "gift_list" => "Hola,

Detalle del mensaje [DATE]

[PRENOM] [NOM_FAMILLE] le envía a su lista de deseo: [GIFTLIST_NAME]

[GIFTLIST_ITEMS]
",
  "email_ordered_cadeaux" => "Hola,

[PRENOM] [NOM_FAMILLE] acaba de cursar un pedido en su lista de regalos [GIFTLIST_NAME].

Éstos son los artículos pedidos:
[GIFTLIST_ITEMS] ",
  "listecadeau_voir" => "Hola,

Detalle del mensaje [DATE].

[PRENOM] [NOM_FAMILLE] le envía a su lista de deseos [GIFTLIST_NAME]:
[URL_LISTE_CADEAU]

[GIFTLIST_ITEMS] ",
  "parrainage" => "Hola,

[PSEUDO] le invita a descubrir el sitio [SITE] y recibir un crédito de [REBATE] en su primer pedido validando su cuenta cliente.

Haga clic en el siguiente enlace para validar su cuenta:
[WWWROOT]/modules/parrainage/inscription.php?email=[EMAIL_FILLEUL]&code=[MDP]&id=[ID_UTILISATEUR]

Su nombre de usuario: [EMAIL_FILLEUL]
Contraseña: [MDP]
",
  "email_alerte" => "Hola,

Producto [NOM_PRODUIT] se encuentra actualmente en stock. Haga clic aquí para averiguarlo: [URLPROD]
",
  "decremente_stock" => "Hola,

El umbral de alerta ha sido alcanzado con el producto [NOM_PRODUIT].

Stock restante: [STOCK_RESTANT_APRES_DEMANDE] ",
  "admin_login" => "Hola,

Le enviamos este correo electrónico después de conexión con éxito de un administrador de su sitio.

ID de cliente: [USER]
Conexión IP: [REMOTE_ADDR]
Reverse DNS: [REVERSE_DNS]
Tiempo de conexión: [DATETIME]

Este correo electrónico está diseñado para informarle acerca de la seguridad de su tienda PEEL para protegerlo mejor.",
  "signature" => "

El servicio al cliente
[SITE]
[WWWROOT]/ ",
  "cree_cheque_cadeau_client_type1" => "Hola,

[SITE] le ofrece un cheque regalo válido durante 30 días de un importe de [PERCENT].

Para calificar, simplemente ingrese al sitio [WWWROOT]/ y utilice su código [CODE].
",
  "warn_admin_user_subscription" => "El [DATE]

El siguiente usuario acaba de registrarse:

[CIVILITE] [PRENOM] [NOM_FAMILLE]
[EMAIL]
[SOCIETE]
[TELEPHONE]
[PRIV]

[link=\"[ADMIN_URL]\"]Dale la cuenta de usuario[/link]
",
  "warn_admin_reve_subscription" => "Le informamos que el distribuidor [link=\"[ADMIN_URL]\"] [CIVILITE] [PRENOM] [NOM_FAMILLE]] [/link] está registrado en [SITE].

Esta cuenta está actualmente en estado de \"Vendedor en espera\". Esta cuenta está inactiva, y beneficiará a distribuidor de tasas cuando lo pasa en estado \"Revendedor\". 
",
"email_retour_virement" => "Hola,

Hemos recibido su número de devolución [RETURN_ID].
De acuerdo a su elección, la cantidad o [MONTANT] será reembolsado por transferencia bancaria tan pronto como sea posible ",
  "email_retour_avoir" => "Hola,

Hemos recibido su número de devolución [RETURN_ID].
Conforme a su elección, el importe ha sido abonado a su cuenta, o [MONTANT]. El [MODE] se deduce automáticamente de su próximo pedido hasta el agotamiento.",
  "email_reste_avoir_remboursement" => "Hola,

Hemos recibido su número de devolución [RETURN_ID].
El reembolso no puede exéder la cantidad de la orden, [MONTANT] será reembolsado por [MODE]. El balance ha sido acreditado a su cuenta, o [RESTE_AVOIR]. Esto se deduce automáticamente de su próximo pedido hasta el agotamiento.",
  "email_remboursement" => "Hola,

El reembolso de su número de devolución [RETURN_ID], del importe de [MONTANT], ha sido efectuado por [MODE].",
  "email_retour_client" => "Hola,

Su solicitud de devolución ha sido registrado.
En la actualidad, los envíos de artículos a la siguiente dirección:

[SOCIETE]
Número Detrás [RETURN_ID].

Le recordamo que los artículos devueltos deben estar en buenas condiciones y en su embalaje original.
Tras la recepción de sus artículos, validaremos el reembolso de éstos. ",
  "cron_order_payment_failure_alerts" => "Hola [PRENOM] [NOM],

Nota: Si ha hecho su pago y ha recibido una confirmación de pago, por favor ignore este email porque su pago pronto será validado manualmente.

Le escribimos en lo que concierne el pedido que ha tratado de pagar recientemente sobre [WWWROOT]/

El contenido de su pedido: [PRODUCT_NAME]
La cantidad total de su pedido: [TOTAL_AMOUNT]
El método de pago que ha elegido: [PAYMENT_MEAN]

Nuestro sistema automatizado para el procesamiento de pedidos no ha recibido confirmación de su pago.
¿Qué problema ha encontrado?

Estamos a su disposición para validar su pedido quizás a través de otro método de pago.

Nota: Este email se envía de forma automática. Si ya ha puesto en contacto con nosotros con respecto a este pago, por favor ignore este email.

Estamos a su disposición para cualquier información adicional. En espera de su respuesta, le deseamos un buen día.",
  "cron_order_not_paid_alerts" => "Hola [PRENOM] [NOM],

Le escribimos en lo que concierne el pedido que ha pasado desde hace [DAYS_SINCE] sobre [SITE_NAME].

El contenido de su pedido: [PRODUCT_NAME]
La cantidad total de su pedido: [TOTAL_AMOUNT]
El método de pago que ha elegido: [PAYMENT_MEAN]

Pero aún no hemos recibido su pago. ¿Necesita más información para enviar su pago?

Nota: Este mensaje se envía de forma automática. Si se encuentra en contacto con nosotros con respecto a este pago, por favor ignore este email. Estamos a su disposición para más información

En espera de su respuesta, le deseamos un buen día.",
  "cron_update_contact_info" => "Hola [CIVILITE] [NOM],

Para mantener al día los datos asociados a su cuenta de usuario, le enviamos la información detallada para su verificación.

Los detalles de su información:

E-mail: [EMAIL]
Título: [CIVILITE]
Apodo: [PSEUDO]
Nombre: [PRENOM]
Apellido: [NOM]
Empresa: [SOCIETE]
Intracom CIF: [TVA_INTRA]
Teléfono: [TELEPHONE]
Móvil: [PORTABLE]
Fax: [FAX]
Fecha de Nacimiento: [NAISSANCE]
Dirección: [ADRESSE]
Código postal: [CODE_POSTAL]
Ciudad: [VILLE]
País: [PAYS]
Sitio web: [SITE_WEB]

Si esta información es inexacta, por favor, actualice su sesión en su cuenta de usuario en [WWWROOT]/utilisateurs/change_params.php

Si usted olvida su contraseña, gracias por utilizar la herramienta de recuperación disponible en el siguiente enlace: [WWWROOT]/utilisateurs/change_params.php

Por favor, recuerde que la exactitud de esta información es esencial para el buen funcionamiento del sitio y para el éxito de cada negocio. Cualquier información incorrecta o inexacta puede resultar en la cancelación de su cuenta.

Para más información, póngase en contacto con nosotros.",
"inscription_newsletter" => "Hola,

Gracias por su suscripción al boletín de [SITE_NAME]. Usted recibirá un boletín semanal a la dirección [EMAIL].

Pronto en [WWWROOT]/.",
  "send_mail_for_account_creation_stop" => "Hola,

Su inscripción se ha tenido en cuenta en [SITE] y debe ser validado por un administrador. Usted será notificado por correo electrónico de la validación de su cuenta, y acceder a su cuenta sera posible sólo después de esta validación.

Atentamente,",
  "send_mail_for_account_creation_reve" => "Hola,

Su cuenta [EMAIL] en [SITE] se ha activado por un administrador. Ahora recibe el estado \"[STATUT]\" y los beneficios asociados, y se puede acceder a su cuenta.

Atentamente,",
  "send_mail_for_account_creation_stand" => "Hola,

Su inscripción se ha tenido en cuenta en [SITE] y debe ser validado por un administrador. Usted será notificado por correo electrónico de la validación de su cuenta, y acceder a su cuenta sera posible sólo después de esta validación.

Atentamente,",
  "send_mail_for_account_creation_affi" => "Hola,

Su cuenta [EMAIL] en [SITE] se ha activado por un administrador. Ahora recibe el estado \"[STATUT]\" y los beneficios asociados, y se puede acceder a su cuenta.

Atentamente,",
  "validating_registration_by_admin" => "Su suscripción en [SITE] ha sido validado por un administrador.",
  "confirm_newsletter_registration" => "",
  "user_double_optin_registration" => ""

);

