{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: installation_index.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
*}
<p>
	{$STR_ADMIN_INSTALL_WELCOME|escape:'html'}<br />
	{$STR_ADMIN_INSTALL_WELCOME_INTRO|escape:'html'}<br />
</p>
<p>{$STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION|escape:'html'}</p>
<div class="container">
	<table class="table col-md-6">
		<tr>
			<td>- {$STR_ADMIN_INSTALL_PHP_VERSION|escape:'html'}</td>
			<td>{$php_version_info}</td>
		</tr>
		<tr>
			<td>- {$STR_ADMIN_INSTALL_MBSTRING|escape:'html'}</td>
			<td>{$mbstring_info}</td>
		</tr>
		<tr>
			<td>- {$STR_ADMIN_INSTALL_UTF8|escape:'html'}</td>
			<td>{$utf8_info}</td>
		</tr>
		<tr>
			<td style="padding-right:10px">- {$STR_ADMIN_INSTALL_ALLOW_URL_FOPEN|escape:'html'}</td>
			<td>{$allow_url_fopen_info}</td>
		</tr>
	</table>
</div>
<div class="container">
	<form class="entryform form-inline" role="form" action="bdd.php" method="post">
		<p><input type="submit" value="{$STR_CONTINUE|str_form_value}" class="btn btn-primary btn-large" /></p>
	</form>
</div>