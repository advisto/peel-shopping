{* Smarty
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
// $Id: user_register_success.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<h1 property="name" class="page_title">{$hello_txt} {$name|html_entity_decode_if_needed}</h1>
<p>{$msg_login_ok_txt|nl2br_if_needed}</p>
<p>{$STR_EMAIL}: <b>{$email}</b></p>
<p>{$STR_PASSWORD}: <b>{$password}</b></p>