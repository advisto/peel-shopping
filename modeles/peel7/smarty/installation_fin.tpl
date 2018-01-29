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
// $Id: installation_fin.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}
<p class="alert alert-success">{$STR_ADMIN_INSTALL_NOW_INSTALLED|escape:'html'}</p>
{$messages}
{$STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN|escape:'html'}<br /><br />
<strong>{$STR_EMAIL|escape:'html'}{$STR_BEFORE_TWO_POINTS}:</strong> {$email|escape:'html'}<br />
<strong>{$STR_PSEUDO|escape:'html'}{$STR_BEFORE_TWO_POINTS}:</strong> {$pseudo|escape:'html'}<br />
<br />
{$STR_ADMIN_INSTALL_ADMIN_LINK_INFOS|escape:'html'}
<br /><br />
<p>{$STR_ADMIN_INSTALL_FINISHED_INFOS|escape:'html'}</p>
<p class="alert alert-danger big"><b>{$STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL|escape:'html'}</b></p>
<p class="alert alert-danger">{$STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN|escape:'html'}</p>
<p class="alert alert-success">{$STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY|escape:'html'}</p>
<p class="alert alert-success">{$STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING|escape:'html'}</p>
<br />
<form class="entryform form-inline" role="form" action="../membre.php" method="post">
	<p class="center"><input type="submit" value="{$STR_ADMIN_INSTALL_FINISH_BUTTON|str_form_value}" class="btn btn-primary btn-lg" /></p>
</form>