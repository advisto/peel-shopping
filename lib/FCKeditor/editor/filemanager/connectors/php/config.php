<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2009 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Configuration file for the File Manager Connector for PHP.
 */

global $Config ;

// SECURITY: You must explicitly enable this "connector". (Set it to "true").
// WARNING: don't just set "$Config['Enabled'] = true ;", you must be sure that only
//		authenticated users can access this file or use some kind of session checking.
$Config['Enabled'] = true ;

// On va chercher la configuration de PEEL
define('IN_PEEL', true);
include('../../../../../../lib/setup/info.inc.php');
// Paramétrage des sessions
$session_length = 3;
$session_cookie_name = "sid" . substr(md5($GLOBALS['wwwroot']), 0, 8);
ini_set('session.gc_maxlifetime', 3600 * $session_length);
ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1'); // évite les attaques avec session id dans l'URL
ini_set('session.use_trans_sid', '0'); // empêche la propagation des SESSION_ID dans les URL
ini_set('url_rewriter.tags', '');
ini_set('session.name', $session_cookie_name);
if (!empty($session_save_path)) {
	ini_set('session.save_path', $session_save_path);
}
session_start();
// Vérification des droits : l'utilisateur doit être un administrateur PEEL
if(!a_priv('admin*', false)){
	die();
}

// Path to user files relative to the document root.
$Config['UserFilesPath'] = $GLOBALS['wwwroot'] .'/upload/' ;

// Fill the following value it you prefer to specify the absolute path for the
// user files directory. Useful if you are using a virtual directory, symbolic
// link or alias. Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
// Attention: The above 'UserFilesPath' must point to the same directory.
$Config['UserFilesAbsolutePath'] = '../../../../../../upload/' ;

// Due to security issues with Apache modules, it is recommended to leave the
// following setting enabled.
$Config['ForceSingleExtension'] = true ;

// Perform additional checks for image files.
// If set to true, validate image size (using getimagesize).
$Config['SecureImageUploads'] = true;

// What the user can do with this connector.
$Config['ConfigAllowedCommands'] = array('QuickUpload', 'FileUpload', 'GetFolders', 'GetFoldersAndFiles', 'CreateFolder') ;

// Allowed Resource Types.
$Config['ConfigAllowedTypes'] = array('File', 'Image', 'Flash', 'Media') ;

// For security, HTML is allowed in the first Kb of data for files having the
// following extensions only.
$Config['HtmlExtensions'] = array("html", "htm", "xml", "xsd", "txt", "js") ;

// After file is uploaded, sometimes it is required to change its permissions
// so that it was possible to access it at the later time.
// If possible, it is recommended to set more restrictive permissions, like 0755.
// Set to 0 to disable this feature.
// Note: not needed on Windows-based servers.
$Config['ChmodOnUpload'] = 0664 ;

// See comments above.
// Used when creating folders that does not exist.
$Config['ChmodOnFolderCreate'] = 0775 ;

/*
	Configuration settings for each Resource Type

	- AllowedExtensions: the possible extensions that can be allowed.
		If it is empty then any file type can be uploaded.
	- DeniedExtensions: The extensions that won't be allowed.
		If it is empty then no restrictions are done here.

	For a file to be uploaded it has to fulfill both the AllowedExtensions
	and DeniedExtensions (that's it: not being denied) conditions.

	- FileTypesPath: the virtual folder relative to the document root where
		these resources will be located.
		Attention: It must start and end with a slash: '/'

	- FileTypesAbsolutePath: the physical path to the above folder. It must be
		an absolute path.
		If it's an empty string then it will be autocalculated.
		Useful if you are using a virtual directory, symbolic link or alias.
		Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
		Attention: The above 'FileTypesPath' must point to the same directory.
		Attention: It must end with a slash: '/'

	 - QuickUploadPath: the virtual folder relative to the document root where
		these resources will be uploaded using the Upload tab in the resources
		dialogs.
		Attention: It must start and end with a slash: '/'

	 - QuickUploadAbsolutePath: the physical path to the above folder. It must be
		an absolute path.
		If it's an empty string then it will be autocalculated.
		Useful if you are using a virtual directory, symbolic link or alias.
		Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
		Attention: The above 'QuickUploadPath' must point to the same directory.
		Attention: It must end with a slash: '/'

	 	NOTE: by default, QuickUploadPath and QuickUploadAbsolutePath point to
	 	"userfiles" directory to maintain backwards compatibility with older versions of FCKeditor.
	 	This is fine, but you in some cases you will be not able to browse uploaded files using file browser.
	 	Example: if you click on "image button", select "Upload" tab and send image
	 	to the server, image will appear in FCKeditor correctly, but because it is placed
	 	directly in /userfiles/ directory, you'll be not able to see it in built-in file browser.
	 	The more expected behaviour would be to send images directly to "image" subfolder.
	 	To achieve that, simply change
			$Config['QuickUploadPath']['Image']			= $Config['UserFilesPath'] ;
			$Config['QuickUploadAbsolutePath']['Image']	= $Config['UserFilesAbsolutePath'] ;
		into:
			$Config['QuickUploadPath']['Image']			= $Config['FileTypesPath']['Image'] ;
			$Config['QuickUploadAbsolutePath']['Image'] 	= $Config['FileTypesAbsolutePath']['Image'] ;

*/

// WARNING: It is recommended to remove swf extension from the list of allowed extensions.
// SWF files can be used to perform XSS attack.

$Config['AllowedExtensions']['File']	= array('7z', 'aiff', 'asf', 'avi', 'bmp', 'csv', 'doc', 'fla', 'flv', 'gif', 'gz', 'gzip', 'jpeg', 'jpg', 'mid', 'mov', 'mp3', 'mp4', 'mpc', 'mpeg', 'mpg', 'ods', 'odt', 'pdf', 'png', 'ppt', 'pxd', 'qt', 'ram', 'rar', 'rm', 'rmi', 'rmvb', 'rtf', 'sdc', 'sitd', 'swf', 'sxc', 'sxw', 'tar', 'tgz', 'tif', 'tiff', 'txt', 'vsd', 'wav', 'wma', 'wmv', 'xls', 'xml', 'zip') ;
$Config['DeniedExtensions']['File']		= array() ;
$Config['FileTypesPath']['File']		= $Config['UserFilesPath'] . '' ;
$Config['FileTypesAbsolutePath']['File']= ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'' ;
$Config['QuickUploadPath']['File']		= $Config['UserFilesPath'] ;
$Config['QuickUploadAbsolutePath']['File']= $Config['UserFilesAbsolutePath'] ;

$Config['AllowedExtensions']['Image']	= array('bmp','gif','jpeg','jpg','png') ;
$Config['DeniedExtensions']['Image']	= array() ;
$Config['FileTypesPath']['Image']		= $Config['UserFilesPath'] . '' ;
$Config['FileTypesAbsolutePath']['Image']= ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'' ;
$Config['QuickUploadPath']['Image']		= $Config['UserFilesPath'] ;
$Config['QuickUploadAbsolutePath']['Image']= $Config['UserFilesAbsolutePath'] ;

$Config['AllowedExtensions']['Flash']	= array('swf','flv') ;
$Config['DeniedExtensions']['Flash']	= array() ;
$Config['FileTypesPath']['Flash']		= $Config['UserFilesPath'] . '' ;
$Config['FileTypesAbsolutePath']['Flash']= ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'' ;
$Config['QuickUploadPath']['Flash']		= $Config['UserFilesPath'] ;
$Config['QuickUploadAbsolutePath']['Flash']= $Config['UserFilesAbsolutePath'] ;

$Config['AllowedExtensions']['Media']	= array('aiff', 'asf', 'avi', 'bmp', 'fla', 'flv', 'gif', 'jpeg', 'jpg', 'mid', 'mov', 'mp3', 'mp4', 'mpc', 'mpeg', 'mpg', 'png', 'qt', 'ram', 'rm', 'rmi', 'rmvb', 'swf', 'tif', 'tiff', 'wav', 'wma', 'wmv') ;
$Config['DeniedExtensions']['Media']	= array() ;
$Config['FileTypesPath']['Media']		= $Config['UserFilesPath'] . '' ;
$Config['FileTypesAbsolutePath']['Media']= ($Config['UserFilesAbsolutePath'] == '') ? '' : $Config['UserFilesAbsolutePath'].'' ;
$Config['QuickUploadPath']['Media']		= $Config['UserFilesPath'] ;
$Config['QuickUploadAbsolutePath']['Media']= $Config['UserFilesAbsolutePath'] ;


/**
 * Renvoie true si l'utilisateur de la session a le privilège $requested_priv ou un droit supérieur
 * Des droits peuvent être combinés :
 * - OU : de type droit1,droit2 : l'un des deux droits suffit
 * - ET : de type droit1+droit2 : les deux droits sont nécessaires
 * La virgule est prioritaire par rapport au + : droit1+droit2,droit3 : droits 1 et 2 nécessaire, ou bien droit3 seulement suffit
 * Si on demande des droits de type admin*, n'importe quel admin, admin_products, etc. a le droit d'accès
 * Si on demande un droit de type admin_xxx, alors un utilisateur de type "admin" a le droit également d'accéder (administrateur superglobal)
 *
 * @param string $requested_priv
 * @param boolean $demo_allowed
 * @return string
 */
function a_priv($requested_priv, $demo_allowed = false)
{
	if (isset($_SESSION) && isset($_SESSION['session_utilisateur']) && !empty($_SESSION['session_utilisateur']['priv'])) {
		if (strpos($requested_priv, ',') !== false) {
			// On autorise plusieurs droits différents => récursif
			$requested_priv_array = explode(',', $requested_priv);
			$allowed = false;
			foreach($requested_priv_array as $this_requested_priv) {
				if (a_priv(trim($this_requested_priv), $demo_allowed)) {
					$allowed = true;
				}
			}
			return $allowed;
		} elseif (strpos($requested_priv, '+') !== false) {
			// On demande plusieurs droits => récursif
			$requested_priv_array = explode('+', $requested_priv);
			$not_allowed = false;
			foreach($requested_priv_array as $this_requested_priv) {
				if (!a_priv(trim($this_requested_priv), $demo_allowed)) {
					$not_allowed = true;
				}
			}
			return !$not_allowed;
		} else {
			$user_priv_array = explode('+', $_SESSION['session_utilisateur']['priv']);
			foreach($user_priv_array as $this_user_priv) {
				if (substr($requested_priv, 0, 5) == 'admin' && $this_user_priv == 'admin') {
					// admin est un administrateur global qui a tous les droits de type admin*
					return true;
				} elseif ($demo_allowed && $this_user_priv == 'demo') {
					// On a les droits demo qui sont autorisés
					return true;
				}
				if (strpos($requested_priv, '*') !== false) {
					// Pour admin*, tout droit du type admin ou admin_.... est autorisé
					if (strpos($requested_priv, substr($this_user_priv, 0, strpos($requested_priv, '*'))) === 0) {
						return true;
					}
				} elseif ($this_user_priv == $requested_priv) {
					return true;
				}
			}
			return false;
		}
	} else {
		return null;
	}
}

?>