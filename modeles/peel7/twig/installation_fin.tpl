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
// $Id: installation_fin.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<div id="contourMenu">
	<div id="menuHorizontal">
		<h1>{{ step_title|escape('html') }}</h1>
	</div>
</div>
<!-- Contenu -->
<div id="contenu">
	<p class="global_success">{{ STR_ADMIN_INSTALL_NOW_INSTALLED|escape('html') }}</p>
	{{ STR_ADMIN_INSTALL_YOU_CAN_LOGIN_ADMIN|escape('html') }}<br /><br />
	<strong>{{ STR_EMAIL|escape('html') }}{{ STR_BEFORE_TWO_POINTS }}:</strong> {{ email|escape('html') }}<br />
	<strong>{{ STR_PASSWORD|escape('html') }}{{ STR_BEFORE_TWO_POINTS }}:</strong> {{ motdepasse|escape('html') }}<br />
	<strong>{{ STR_PSEUDO|escape('html') }}{{ STR_BEFORE_TWO_POINTS }}:</strong> {{ pseudo|escape('html') }}<br />
	<br />
	{{ STR_ADMIN_INSTALL_ADMIN_LINK_INFOS|escape('html') }}
	<br /><br />
	<p>{{ STR_ADMIN_INSTALL_FINISHED_INFOS|escape('html') }}</p>
	<p class="global_error">{{ STR_ADMIN_INSTALL_FINISHED_INFOS_DELETE_INSTALL|escape('html') }}</p>
	<p class="global_error">{{ STR_ADMIN_INSTALL_FINISHED_INFOS_RENAME_ADMIN|escape('html') }}</p>
	<p class="global_success">{{ STR_ADMIN_INSTALL_FINISHED_INFOS_PHP_ERRORS_DISPLAY|escape('html') }}</p>
	<p class="global_success">{{ STR_ADMIN_INSTALL_FINISHED_INFOS_UTF8_WARNING|escape('html') }}</p>
	<br /><br />
	<form action="../membre.php" method="post">
		<p><input type="submit" value="{{ STR_ADMIN_INSTALL_FINISH_BUTTON|str_form_value }}" class="bouton" /></p>
	</form>
</div>