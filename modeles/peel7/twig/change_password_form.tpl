{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: change_password_form.tpl 35330 2013-02-16 18:27:13Z gboussin $
#}<h1 class="page_title">{{ change_password }}</h1>
{% if (noticemsg) %}
	{{ noticemsg }}
{% else %}
	{% if (token_error) %}{{ token_error }}{% endif %}
	<form class="entryform" method="post" action="{{ action|escape('html') }}">
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="ancien_mot_passe">{{ STR_OLD_PASSWORD }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="password" name="ancien_mot_passe" id="ancien_mot_passe" size="25" value="{{ old_password|str_form_value }}" /> {{ old_password_error }}{{ old_password_error2 }}</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="nouveau_mot_passe">{{ STR_NEW_PASSWORD }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="password" name="nouveau_mot_passe" id="nouveau_mot_passe" size="25" value="{{ new_password|str_form_value }}" /> {{ new_password_error }}</span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche">&nbsp;</span>
			<span class="enregistrementdroite" id="pwd_level_image"></span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche">&nbsp;</span>
			<span class="enregistrementdroite">{{ STR_STRONG_PASSWORD_NOTIFICATION }} </span>
		</div>
		<div class="enregistrement">
			<span class="enregistrementgauche"><label for="nouveau_mot_passe">{{ STR_NEW_PASSWORD_CONFIRM }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</label></span>
			<span class="enregistrementdroite"><input type="password" name="nouveau_mot_passe2" id="nouveau_mot_passe2" size="25" value="{{ new_password_confirm|str_form_value }}" /> {{ new_password_confirm_error }}</span>
		</div>
		<p class="center">{{ token }}<input type="submit" value="{{ STR_CHANGE|str_form_value }}" class="clicbouton" />&nbsp;&nbsp;&nbsp;<input type="reset" value="{{ STR_EMPTY_FIELDS|str_form_value }}" class="clicbouton" /></p>
	</form>
	{{ js_password_control }}
{% endif %}