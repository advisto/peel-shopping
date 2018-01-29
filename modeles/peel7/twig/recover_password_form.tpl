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
// $Id: recover_password_form.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<h1 property="name" class="page_title">{{ get_password }}</h1>
{% if (message) %}
{{ message }}
{% else %}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{% if (email) %}
		<p style="text-justify">{{ email.msg_insert|nl2br_if_needed }}</p>
		<p>{{ email.label }}: <input type="text" class="form-control" name="email" value="{{ email.value|str_form_value }}" style="max-width:300px" /></p>
		{{ email.error }}
	{% elseif (pass) %}
		{{ pass.empty_field_error }}{{ pass.mismatch_password_error }}
		<p style="text-justify">{{ pass.msg_insert_new_password|nl2br_if_needed }}{{ STR_BEFORE_TWO_POINTS }}:</p>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="rec_password_once">{{ pass.STR_NEW_PASSWORD }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="password" class="form-control" name="password_once" id="rec_password_once" size="32" value="{{ pass.password_once|str_form_value }}" /></span>{{ pass.password_once_error }}
		</div>
		 <div class="enregistrement">
			<span class="enregistrementgauche">&nbsp;</span>
			<span class="enregistrementdroite"><div id="pwd_level_image"></div><span class="enregistrementdroite">{{ pass.STR_STRONG_PASSWORD_NOTIFICATION }} </span></span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="rec_password_twice">{{ pass.STR_NEW_PASSWORD_CONFIRM }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="password" class="form-control" name="password_twice" id="rec_password_twice" size="32" value="{{ pass.password_twice|str_form_value }}" /></span>	{{ pass.password_twice_error }}
			</div>
	{% endif %}
	
	<p class="left"><input type="submit" value="{{ STR_SEND|str_form_value }}" class="btn btn-primary" /></p>
	{{ token }}
</form>
{% endif %}