{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: js_password_control.tpl 35067 2013-02-08 14:21:55Z gboussin $
*}<!-- le javascript de contrôle du niveau de mot de passe -->
<script><!--//--><![CDATA[//><!--
set_password_image_level("{$js_field_id|filtre_javascript:true:true:false}","{$repertoire_images}");
//--><!]]></script>