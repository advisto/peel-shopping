{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: installation_verifdroits.tpl 39162 2013-12-04 10:37:44Z gboussin $
#}
<form class="entryform form-inline" role="form" action="{{ configuration_url|str_form_value }}" method="post">
	<h3>{{ STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS|escape('html') }}</h3>
	{{ directories_checkup_messages }}
	<p><br /></p>
	{{ files_checkup_messages }}
	<p><br /></p>
	<h3>{{ STR_ADMIN_INSTALL_EXISTING_TABLES|escape('html') }}</h3>
	{{ tables_checkup_messages }}
	<input type="hidden" name="choixbase" value="{{ choixbase_value|str_form_value }}" />
	{% if not error %}
	<p class="alert alert-success">{{ STR_ADMIN_INSTALL_RIGHTS_OK|escape('html') }}</p>
	<p class="center">
		<br />
		<input type="submit" value="{{ STR_CONTINUE|str_form_value }}" class="btn btn-primary btn-large" />
	</p>
	<p>{{ STR_ADMIN_INSTALL_STEP_5_LINK_EXPLAIN|escape('html') }}</p>
	{% else %}
	<p class="alert alert-danger">{{ STR_ADMIN_INSTALL_RIGHTS_NOK|escape('html') }}</p>
	<p class="center">
		<br />
		<input type="button" value="{{ STR_REFRESH|str_form_value }}" onclick="location='verifdroits.php'" class="btn btn-primary" /> &nbsp; &nbsp;
		<input type="submit" value="{{ STR_ADMIN_INSTALL_CONTINUE_WITH_ERRORS_BUTTON|str_form_value }}" class="btn btn-primary" />
	</p>
	{% endif %}
</form>