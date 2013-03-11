<?php
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: database_email_template_text_es.php 35805 2013-03-10 20:43:50Z gboussin $

if (!defined("IN_PEEL")) {
	die();
}

// The keys shall have quotes so that they are correcly treated by Transifex : write  "1" => ... and not 1 => ...
// Les clés doivent avoir des guillemets pour que ce soit traité correctement par Transifex : indiquez "1" => ... et non pas 1 => ...

$peel_email_template["text"] = array(
  "download_product" => "Hola,

Su pedido [ORDER_ID] ha sido confirmado, le invitamos a descargar su pedido desde el siguiente enlace:

Su enlace para la descarga: [WWWROOT]/modules/download/telecharger.php?id=[ORDER_ID]&key=[CLE]

Podrá descargar este archivo una vez.
Si experimenta problemas con la descarga, por favor contacta con [SUPPORT_COMMANDE] para que le devuelve un enlace de descarga.

Además, le invitamos a editar su factura desde el siguiente enlace:

[WWWROOT]/factures/commande_pdf.php?code_facture=[CODE_FACTURE]&mode=factura ",
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
Nombre: [PRIMERA]
Empresa: [SOCIETE]
Tel: [TELÉFONO]
E-mail: [EMAIL]
Disponibilidad: [disponible]

Asunto: [TEMA]

Mensaje:

[TEXTO]

IP: [REMOTE_ADDR]
",
  "admin_info_payment_credit_card" => "Hola,

Un pedido con el número [ORDER_ID] ha sido registrado [WWWROOT] ",
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
Referencias del envío: le No. del paquete es [PAQUETE]. Por correo, puede seguir su paquete haciendo clic en el siguiente enlace: http://www.coliposte.fr/

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
Envío:
[COUT_TRANSPORT]

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

Para calificar, simplemente ingrese al sitio [WWWROOT] y utilice el código [CHECK_NAME].

A continuación, recibirá un descuento del [REMISE_VALEUR] por una compra mínima de [MONTANT_MIN] HT por su pedido.",
  "cree_cheque_cadeau_friend" => "Hola,

Su amigo [PRENOM] [NOM_FAMILLE] quería darle un regalo en el sitio [SITE].

Para calificar, simplemente ingrese al sitio [WWWROOT] el código y utilice [CODE].

A continuación, recibirá un descuento del [PRIX] en su pedido.
",
  "cree_cheque_cadeau_admin" => "Hola,

El código de descuento [CODE] con el CHEQUE REGALO módulo ha sido creado  en [SITE].
",
  "cree_cheque_cadeau_client_type2" => "Hola,

[SITE] ofrece un regalo de 30 días de [MONTANT].

Para calificar, simplemente ingrese al sitio [WWWROOT] el código y utilice su código [CODE].
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
[WWWROOT] / modules / listecadeau / voir.php?email_liste=[URL_LISTE_CADEAU]

[GIFTLIST_ITEMS] ",
  "parrainage" => "Hola,

[PSEUDO] le invita a descubrir el sitio [SITE] y recibir un crédito de [REBAJA] en su primer pedido validando su cuenta cliente.

Haga clic en el siguiente enlace para validar su cuenta:
 [WWWROOT] / modules / patrocinio / inscription.php?email=[EMAIL_FILLEUL]&code=[MDL]&id=[user_id]

Su nombre de usuario: [EMAIL_FILLEUL]
Contraseña: [MDL]
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
Reverse DNS: [reverse_dns]
Tiempo de conexión: [DATETIME]

Este correo electrónico está diseñado para informarle acerca de la seguridad de su tienda PEEL para protegerlo mejor.",
  "signature" => "

El servicio al cliente
[SITE]
[WWWROOT] ",
  "cree_cheque_cadeau_client_type1" => "Hola,

[SITE] le ofrece un cheque regalo válido durante 30 días de un importe de [PERCENT].

Para calificar, simplemente ingrese al sitio [WWWROOT] y utilice su código [CODE].
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
Tras la recepción de sus artículos, validaremos el reembolso de éstos. "
);

?>