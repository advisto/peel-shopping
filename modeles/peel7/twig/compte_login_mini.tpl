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
// $Id: compte_login_mini.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}
<div id="compte_login_mini">
	<form class="entryform" method="post" action="membre.php">
		<table class="module_login">
			<tr>
				<td>{{ email_lbl }}</td>
				<td class="email_module_login" colspan="2"><input type="text" name="email" style="width:120px;" value="{{ email|str_form_value }}" /></td>
			</tr>
			<tr>
				<td>{{ password_lbl }}</td>
				<td class="email_module_password"><input type="password" name="mot_passe" style="width:120px;" value="{{ password|str_form_value }}" /></td>
				<td><p>{{ TOKEN }}<input type="submit" value="" class="bouton_ok" /></p></td>
			</tr>
			<tr>
				<td class="center" style="padding-top:5px;" colspan="3">
					<a href="{{ forgot_pass_href|escape('html') }}">{{ forgot_pass_lbl|nl2br_if_needed }}</a><br />
					<a href="{{ enregistrement_href|escape('html') }}">{{ enregistrement_lbl }}</a>
					{% if social.is_any %}
						<p class="social_link_intro">{{ via_lbl }}</p><p class="social_link">
							{% if (social.facebook) %}{{ social.facebook }}{% endif %}
							{% if (social.twitter) %}{{ social.twitter }}{% endif %}
							{% if (social.openid) %}{{ social.openid }}{% endif %}
						</p>
					{% endif %}
				</td>
			</tr>
		</table>
	</form>
</div>