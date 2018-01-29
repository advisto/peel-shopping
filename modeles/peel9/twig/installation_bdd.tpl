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
// $Id: installation_bdd.tpl 55289 2017-11-27 16:57:22Z sdelaporte $
#}
<p>{{ STR_ADMIN_INSTALL_DATABASE_INTRO_1|escape('html') }}<br />
{{ STR_ADMIN_INSTALL_DATABASE_INTRO_2|escape('html') }}</p>
<p>{{ STR_ADMIN_INSTALL_DATABASE_INTRO_3|escape('html') }}<br />
{{ STR_ADMIN_INSTALL_DATABASE_INTRO_4|escape('html') }}</p>
{{ confirm_message }}
<form class="entryform form-inline" role="form" action="choixbase.php" method="post" class="left">
	<h2>{{ STR_ADMIN_SITES_GENERAL_PARAMETERS|escape('html') }}{{ STR_BEFORE_TWO_POINTS }}:</h2>
	<p><i>{{ STR_ADMIN_INSTALL_EXPLAIN_SSL|escape('html') }}</i></p>
	<div class="row" style="margin-bottom:10px">
		<div class="col-sm-3"><label>{{ STR_ADMIN_INSTALL_URL_STORE|escape('html') }}</label></div>
		<div class="col-sm-9"><input type="url" class="form-control" name="wwwroot" placeholder="http://" value="{{ wwwroot_value|str_form_value }}" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-sm-3"><label>{{ STR_ADMIN_SITES_SITE_NAME|escape('html') }}{{ STR_BEFORE_TWO_POINTS }}:</label></div>
		<div class="col-sm-9"><input type="text" class="form-control" name="site_name" placeholder="{{ STR_ADMIN_SITES_SITE_NAME|str_form_value }}" value="{{ site_name_value|str_form_value }}" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-sm-3"><label>{{ STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL|escape('html') }}{{ STR_BEFORE_TWO_POINTS }}:</label></div>
		<div class="col-sm-9"><input type="email" class="form-control" name="email_webmaster" placeholder="email@exemple.com" value="{{ email_webmaster_value|str_form_value }}" /></div>
	</div>
	<h2>MySQL{{ STR_BEFORE_TWO_POINTS }}:</h2>
	<div class="row" style="margin-bottom:10px">
		<div class="col-sm-3"><label>{{ STR_ADMIN_INSTALL_DATABASE_SERVER|escape('html') }}</label></div>
		<div class="col-sm-9"><input type="text" class="form-control" name="serveur" placeholder="{{ STR_ADMIN_INSTALL_DATABASE_SERVER_EXPLAIN|str_form_value }}" value="{{ serveur_value|str_form_value }}" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-sm-3"><label>{{ STR_ADMIN_INSTALL_DATABASE_USERNAME|escape('html') }}{{ STR_BEFORE_TWO_POINTS }}:</label></div>
		<div class="col-sm-9"><input type="text" class="form-control" name="utilisateur" size="30" value="{{ utilisateur_value|str_form_value }}" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-sm-3"><label>{{ STR_PASSWORD|escape('html') }}{{ STR_BEFORE_TWO_POINTS }}:</label></div>
		<div class="col-sm-9"><input type="password" class="form-control" name="motdepasse" size="32" value="{{ motdepasse_value|str_form_value }}" /></div>
	</div>
	<h2>{{ STR_ADMIN_INSTALL_LANGUAGE_CHOOSE|escape('html') }}</h2>
	<p>
	{% for lang,name in select_languages %}
			<input type="checkbox" name="langs[]" value="{{ lang|str_form_value }}"{% if lang == install_langs_value %} checked="checked"{% endif %} /> {{ name }}<br />
	{% endfor %}
	</p>
	<h2>{{ STR_ADMIN_INSTALL_CHOOSE_WEBSITE_TYPE|escape('html') }}</h2>
	<p>
		<input type="radio" name="website_type" id="shop" {% if website_type_value == "shop" or website_type_value is empty %}checked="checked"{% endif %} value="shop" /><label for="shop">{{ STR_ADMIN_INSTALL_WEBSITE_SHOP }}</label><br />
		<input type="radio" name="website_type" id="showcase" {% if website_type_value == "showcase"}checked="checked"{% endif %} value="showcase" /><label for="showcase">{{ STR_ADMIN_INSTALL_WEBSITE_SHOWCASE }}</label><br />
		<input type="radio" name="website_type" id="ad" {% if website_type_value == "ad"}checked="checked"{% endif %} {% if ad_site_disable is empty %}disabled="disabled"{% endif %} value="ad" /><label for="ad">{{ STR_ADMIN_INSTALL_WEBSITE_AD }}</label><br />
	</p>
	<h2>{{ STR_ADMIN_INSTALL_FILL_DB|escape('html') }}</h2>
	<input type="radio" value="1" name="fill_db" id="filldb1" {% if fill_db == "1" %}checked="checked"{% endif %}/><label for="filldb1">Oui</label>
	<input type="radio" value="0" name="fill_db" id="filldb0" {% if fill_db == "0" or fill_db is empty %}checked="checked"{% endif %} /><label for="filldb0">Non</label>

	<h2>{{ STR_ADMIN_INSTALL_SSL_ADMIN|escape('html') }}</h2>
	<p>
		<label class="radio"><input type="radio" name="admin_force_ssl" value="0"{% if admin_force_ssl_selected == false %} checked="checked"{% endif %} /> {{ STR_ADMIN_INSTALL_SSL_ADMIN_NO|escape('html') }}</label>
		<label class="radio"><input type="radio" name="admin_force_ssl" value="1"{% if admin_force_ssl_selected == true %} checked="checked"{% endif %} /> {{ STR_ADMIN_INSTALL_SSL_ADMIN_YES|escape('html') }}</label>
		{% if ssl_admin_explain == true %}<div class="alert alert-info"><a href="{{ url_installation|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_INSTALL_SSL_ADMIN_EXPLAIN|escape('html') }}</a></div>{% endif %}
	</p>
	<p class="center"><input type="submit" value="{{ STR_CONTINUE|str_form_value }}" class="btn btn-primary btn-lg" /></p>
</form>