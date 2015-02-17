{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: access_account_form.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
#}<h1 property="name" class="page_title">{{ acces_account_txt }}</h1>
<div class="row">
	<div class="col-md-6">
		<p><b>{{ new_customer }}</b></p>
		{{ msg_new_customer|nl2br_if_needed }}<br />
		<br />
		<p><b>{{ still_customer }}</b></p>
	</div>
	<div class="col-md-6">
		{{ msg_still_customer|nl2br_if_needed }}<p><a class="notice" href="{{ pass_perdu_href|escape('html') }}">{{ pass_perdu_txt|nl2br_if_needed }}</a></p>
		<form class="entryform form-inline" role="form" method="post" action="{{ wwwroot }}/membre.php">
			<table class="access_account_form">
				<tr>
					<td>{{ email_or_pseudo }}:</td>
					<td><input type="email" class="form-control" name="email" value="{{ email|str_form_value }}" /><br />{{ email_error }}</td>
				</tr>
				<tr>
					<td>{{ STR_PASSWORD }}:</td>
					<td><input type="password" class="form-control" name="mot_passe" size="32" value="{{ password|str_form_value }}" /><br />{{ password_error }}</td>
				</tr>
				<tr>
					<td colspan="2" class="center">
						<p>{{ token }}<input type="submit" value="{{ login_txt|str_form_value }}" class="btn btn-primary" /></p>
					{% if social.is_any %}
						<p class="social_link">
							{% if (social.facebook) %}{{ social.facebook }}{% endif %} &nbsp;
							{% if (social.twitter) %}{{ social.twitter }}{% endif %} &nbsp;
							{% if (social.openid) %}{{ social.openid }}{% endif %}
						</p>
					{% endif %}
					</td>
				</tr>
			</table>
		</form>
	</div>
</div>