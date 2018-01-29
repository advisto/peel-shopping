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
// $Id: installation_index.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}
<p>
	{$STR_ADMIN_INSTALL_WELCOME|escape:'html'}<br />
	{$STR_ADMIN_INSTALL_WELCOME_INTRO|escape:'html'}<br />
</p>
<p>{$STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION|escape:'html'}</p>
<div class="container" style="max-width:600px">
	<hr />
	<div class="row">
		<div class="col-xs-7 col-sm-9">{$STR_ADMIN_INSTALL_PHP_VERSION|escape:'html'}</div>
		<div class="col-xs-5 col-sm-3 right">{$php_version_info}</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-xs-7 col-sm-9">{$STR_ADMIN_INSTALL_MBSTRING|escape:'html'}</div>
		<div class="col-xs-5 col-sm-3 right">{$mbstring_info}</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-xs-7 col-sm-9">{$STR_ADMIN_INSTALL_UTF8|escape:'html'}</div>
		<div class="col-xs-5 col-sm-3 right">{$utf8_info}</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-xs-7 col-sm-9">{$STR_ADMIN_INSTALL_ALLOW_URL_FOPEN|escape:'html'}</div>
		<div class="col-xs-5 col-sm-3 right">{$allow_url_fopen_info}</div>
	</div>
	<hr />
</div>
<form class="entryform form-inline" role="form" action="bdd.php" method="post">
	<p class="center"><input type="submit" value="{$STR_CONTINUE|str_form_value}" class="btn btn-primary btn-lg" /></p>
</form>