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
// $Id: admin_install_es.php 55928 2018-01-26 17:31:15Z sdelaporte $

if (!defined('IN_PEEL')) {
	die();
}

$GLOBALS["STR_ADMIN_INSTALL_STEP1_TITLE"] = "PASO 1/6: Instalación PEEL Shopping";
$GLOBALS["STR_ADMIN_INSTALL_STEP2_TITLE"] = "PASO 2/6: Conexión a la base de datos";
$GLOBALS["STR_ADMIN_INSTALL_STEP3_TITLE"] = "PASO 3/6: Seleccionar la base de datos";
$GLOBALS["STR_ADMIN_INSTALL_STEP4_TITLE"] = "PASO 4/6: Comprobación de los derechos";
$GLOBALS["STR_ADMIN_INSTALL_STEP5_TITLE"] = "PASO 5/6: Configuración del almacén de cuentas de administrador";
$GLOBALS["STR_ADMIN_INSTALL_STEP6_TITLE"] = "PASO 6/6: Finalización de la instalación";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME"] = "Bienvenido al programa de instalación de PEEL Shopping.";
$GLOBALS["STR_ADMIN_INSTALL_WELCOME_INTRO"] = "Nosotros le guiará durante todo el proceso para instalar la aplicación en el sistema.";
$GLOBALS["STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION"] = "Comprobación del servidor:";
$GLOBALS["STR_ADMIN_INSTALL_PHP_VERSION"] = "PHP Version:";
$GLOBALS["STR_ADMIN_INSTALL_MBSTRING"] = "Extensión mbstring:";
$GLOBALS["STR_ADMIN_INSTALL_UTF8"] = "UTF-8 disponible:";
$GLOBALS["STR_ADMIN_INSTALL_ALLOW_URL_FOPEN"] = "Directiva allow_url_fopen habilitada en php.ini:";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_1"] = "Vamos a instalar la información necesaria en la base de datos.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_2"] = "Vamos a pedir esta información diferente.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_3"] = "Usted tiene que estar de tu hosting identificadores MySQL.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_INTRO_4"] = "Evite el uso de la raíz, y prefieren una contraseña mysql que son robustos y diferente de la contraseña de SSH para mayor seguridad";
$GLOBALS["STR_ADMIN_INSTALL_ERROR_CONNEXION"] = "Error: Por favor revise su información de idiomas y de conexión a la base de datos";
$GLOBALS["STR_ADMIN_INSTALL_CHOOSE_WEBSITE_TYPE"] = "Elegir el tipo de sitio web que desea instalar";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_SSL"] = "Información: Usted puede especificar una URL para utilizar el cifrado HTTPS SSL sólo si su dominio tiene un certificado SSL válido configurado en el alojamiento.";
$GLOBALS["STR_ADMIN_INSTALL_URL_STORE"] = "Sitio URL:";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN"] = "Forzar el uso de SSL para la administración:";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_NO"] = "No hacer fuerza";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_YES"] = "Fuerza SSL (más seguro, https, pero debe ser funcional para el campo)";
$GLOBALS["STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN"] = "Si desea forzar el uso de https para la administración, consulte primero una página HTTPS obras";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER"] = "MySQL";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN"] = "(ejemplo: localhost, o nombre de servidor de alojamiento compartido cuando SQL en particular)";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_USERNAME"] = "Nombre de usuario";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC"] = "Para instalar PEEL le recomendamos que utilice una base de datos dedicada exclusivamente a pelar. Sin embargo, como todas las tablas tienen el prefijo \"peel_\", es posible utilizar una base de datos que ya otras tablas";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE"] = "Si su base de datos no se ha creado, crear o póngase en contacto con su proveedor de alojamiento.";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_SELECT"] = "Por favor, elija su base de datos que almacenará su PEEL:";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL"] = "PRECAUCIÓN: Si la base de datos ya contiene tablas \"peel_\", eliminarlos antes de continuar";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_NO_ACCESS"] = "Usted no tiene acceso a la base de datos";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_OK"] = "El directorio %s tiene permisos de escritura";
$GLOBALS["STR_ADMIN_INSTALL_DIRECTORY_NOK"] = "El directorio %s no se puede escribir => Agregar escribir";
$GLOBALS["STR_ADMIN_INSTALL_FILE_OK"] = "El archivo %s se puede escribir";
$GLOBALS["STR_ADMIN_INSTALL_FILE_NOK"] = "El archivo %s no se puede escribir => Agregar escribir";
$GLOBALS["STR_ADMIN_INSTALL_DATABASE_OK_PREFIX"] = "La base de datos %s no contiene PEEL mesa (que es perfecto).";
$GLOBALS["STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS"] = "Vamos a revisar algunos derechos a los archivos y directorios";
$GLOBALS["STR_ADMIN_INSTALL_STEP_5_LINK_EXPLAIN"] = "Nota: El siguiente paso 5/6 creará la estructura de datos y puede tardar unos segundos";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_OK"] = "Los derechos de acceso parecen correctas";
$GLOBALS["STR_ADMIN_INSTALL_RIGHTS_NOK"] = "Por favor, corrija los errores antes de continuar";
$GLOBALS["STR_ADMIN_INSTALL_CONTINUE_WITH_ERRORS_BUTTON"] = "Continuar a pesar de los errores";
$GLOBALS["STR_ADMIN_INSTALL_EXPLAIN_RENAME_TABLES"] = "Si continúa, las tablas existentes no se pueden eliminar, pero si la estructura de datos no se espera que esto genere errores. Además, las bases de datos serán añadido, y que podría crear duplicados debe cambiar el nombre o eliminar tablas existentes.";
$GLOBALS["STR_ADMIN_INSTALL_EXISTING_TABLES"] = "Tablas PEEL existentes:";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_EMAIL"] = "Cuenta de administrador de correo electrónico";
$GLOBALS["STR_ADMIN_INSTALL_SQL_FILE_EXECUTED"] = "Archivo SQL ejecutado";
$GLOBALS["STR_ADMIN_INSTALL_FILE_MISSING"] = "Error archivo faltante";
$GLOBALS["STR_ADMIN_INSTALL_FINISH_BUTTON"] = "Finalización de la instalación";
$GLOBALS["STR_ADMIN_INSTALL_NOW_INSTALLED"] = "PEEL Shopping está instalado.";
$GLOBALS["STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN"] = "Ahora se puede conectar a la interfaz de administración mediante los siguientes parámetros:";
$GLOBALS["STR_ADMIN_INSTALL_ADMIN_LINK_INFOS"] = "Una vez conectado, haga clic en \"Mi cuenta\" > \"Administrar\".";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS"] = "Notas sobre la seguridad de su sitio:";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL"] = "REQUERIDO: Elimine el directorio de instalación para empezar a trabajar";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN"] = "MUY RECOMENDADO: Para la seguridad de su sitio, cambie el nombre del directorio => En la pagina \"Variables de configuración\", cambie la variable \"backoffice_directory_name\" de \"{$GLOBALS['site_parameters']['backoffice_directory_name']}\" a un nombre difícil de adivinar, y despues cambiar este directorio con FTP.";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY"] = "Su sitio ha sido configurado para mostrar los errores de PHP para su propiedad intelectual, a saber, {$_SERVER['REMOTE_ADDR']} Este parámetro se puede cambiar en la administración.";
$GLOBALS["STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING"] = "Nota: Si usted desea trabajar en el código PHP en su sitio, tenga cuidado al editar los archivos con un editor que soporte UTF-8 bien y no añadir la lista de materiales (caracteres invisibles) en los archivos de cabecera. En caso de duda, el uso de Notepad++ es una descarga gratuita a través de Internet.";
$GLOBALS["STR_ADMIN_INSTALL_LANGUAGE_CHOOSE"] = "Seleccione los idiomas que desea instalar:";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB"] = "llenar la base de datos";
$GLOBALS["STR_ADMIN_INSTALL_FILL_DB_EXPLANATION"] = "Puede optar por instalar su sitio sin contenido predeterminado o utilizar el contenido de demostración para completar previamente su base de datos. Este contenido le permitirá descubrir todas las características ofrecidas por PEEL. Las categorías, los productos y los temas de contenido se agregarán automáticamente a su sitio y podrá editar, eliminar y crear contenido nuevo.";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_PHP5"] = "Debe habilitar PHP >= 5.2 en su sitio: editar el archivo htaccess en la raíz del sitio para permitir a las líneas correspondientes a su alojamiento quitando el # al principio de la línea, o póngase en contacto con su hosting - Para el alojamiento en PEEL contacto : contact@peel.fr o +33 (0) 1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_MBSTRING"] = "Usted tendrá que cambiar manualmente la codificación de la página - PEEL contacto: contact@peel.fr o +33 (0) 1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_UTF8"] = "Usted tendrá que cambiar manualmente la codificación de la página - PEEL contacto: contact@peel.fr o +33 (0)  1 75 43 67 97";
$GLOBALS["STR_ADMIN_INSTALL_ACTIVATE_URL_FOPEN"] = "La operación será normal, excepto que módulos no funcionarán";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOP"] = "sitio de comercio electrónico";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_SHOWCASE"] = "escaparate";
$GLOBALS["STR_ADMIN_INSTALL_WEBSITE_AD"] = "anuncio del sitio (sólo si el módulo está presente. Puedes pedir este módulo desde <a onclick=\"return(window.open(this.href)?false:true);\" href=\"https://www.peel-shopping.com/various-128/module-annonces-installation-52.html\">esta página</a>)";
