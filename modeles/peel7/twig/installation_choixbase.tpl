{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: installation_choixbase.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<div id="contourMenu">
	<div id="menuHorizontal">
		<h1>{{ step_title|escape('html') }}</h1>
	</div>
</div>
<!-- Contenu -->
<div id="contenu">
	<form action="verifdroits.php" method="post">
		<p>{{ STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC|escape('html') }}</p>
		<p>{{ STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE|escape('html') }}</p>
		<p>{{ STR_ADMIN_INSTALL_DATABASE_SELECT|escape('html') }}</p>
		{% if available_databases %}
		<table>
			{% for this_database in available_databases %}
			<tr>
				<td>
					<input type="radio" name="choixbase" id="{{ this_database|str_form_value }}" value="{{ this_database|str_form_value }}" {% if this_database == selected_database %} checked="checked"{% endif %} /><label for="{{ this_database|str_form_value }}">{{ this_database }}</label>
				</td>
			</tr>
			{% endfor %}
		</table>
		{% else %}
			<input type="text" name="choixbase" /> {{ error_message }}
		{% endif %}
		<p class="global_error">{{ STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL|escape('html') }}</p>
		<p><input type="submit" value="{{ STR_CONTINUE|str_form_value }}" class="bouton" /></p>
	</form>
</div>