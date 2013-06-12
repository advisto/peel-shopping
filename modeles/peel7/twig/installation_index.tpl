{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: installation_index.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<div id="contourMenu">
	<div id="menuHorizontal">
		<h1>{{ step_title|escape('html') }}</h1>
	</div>
</div>
<!-- Contenu -->
<div id="contenu">
	<p>
		{{ STR_ADMIN_INSTALL_WELCOME|escape('html') }}<br />
		{{ STR_ADMIN_INSTALL_WELCOME_INTRO|escape('html') }}<br />
	</p>
	<form action="bdd.php" method="post">
		<p><input type="submit" value="{{ STR_CONTINUE|str_form_value }}" class="bouton" /></p>
	</form>
	<p>{{ STR_ADMIN_INSTALL_VERIFY_SERVER_CONFIGURATION|escape('html') }}</p>
	<table>
		<tr>
			<td><p>- {{ STR_ADMIN_INSTALL_PHP_VERSION|escape('html') }}</p></td>
			<td style="height:45px;"><p>{{ php_version_info }}</p></td>
		</tr>
		<tr>
			<td><p>- {{ STR_ADMIN_INSTALL_MBSTRING|escape('html') }}</p></td>
			<td><p>{{ mbstring_info }}</p></td>
		</tr>
		<tr>
			<td><p>- {{ STR_ADMIN_INSTALL_UTF8|escape('html') }}</p></td>
			<td>{{ utf8_info }}</td>
		</tr>
		<tr>
			<td style="padding-right:10px"><p>- {{ STR_ADMIN_INSTALL_ALLOW_URL_FOPEN|escape('html') }}</p></td>
			<td>{{ allow_url_fopen_info }}</td>
		</tr>
	</table>
</div>