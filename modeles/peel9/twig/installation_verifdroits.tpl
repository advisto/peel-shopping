{# Twig
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
// $Id: installation_verifdroits.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}
<form class="entryform form-inline" role="form" action="{{ configuration_url|str_form_value }}" method="post">
	<h2>{{ STR_ADMIN_INSTALL_CHECK_ACCESS_RIGHTS|escape('html') }} {{ STR_BEFORE_TWO_POINTS }}:</h2>
	{{ directories_checkup_messages }}
	<p><br /></p>
	{{ files_checkup_messages }}
	<p><br /></p>
	<h2>{{ STR_ADMIN_INSTALL_EXISTING_TABLES|escape('html') }}</h2>
	{{ tables_checkup_messages }}
	<input type="hidden" name="choixbase" value="{{ choixbase_value|str_form_value }}" />
	{% if not error %}
	<p class="alert alert-success">{{ STR_ADMIN_INSTALL_RIGHTS_OK|escape('html') }}</p>
	<p class="center">
		<br />
		<input type="submit" value="{{ STR_CONTINUE|str_form_value }}" class="btn btn-primary btn-lg" />
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