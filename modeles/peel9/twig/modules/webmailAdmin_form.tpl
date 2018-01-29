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
// $Id: webmailAdmin_form.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" id="form_send_email" action="{{ action|escape('html') }}">
	{{ form_token }}
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_WEBMAIL_ADMIN_CLIENT_INFORMATION }}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
			{% if (edit_href) %}
				<a href="{{ edit_href|escape('html') }}">{{ STR_MODULE_WEBMAIL_ADMIN_EDIT_USER }} #{{ id_utilisateur }}</a>
			{% endif %}
			</td>
		</tr>
		<tr>
			<th>{{ STR_GENDER }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>{% if (user_gender) %}{{ user_gender }}{% else %}<i>{{ STR_UNAVAILABLE }}</i>{% endif %}</td>
		</tr>
		<tr>
			<th>{{ STR_ADMIN_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>{% if (user_name) %}{{ user_name }}{% else %}<i>{{ STR_UNAVAILABLE }}</i>{% endif %}</td>
		</tr>
		<tr>
			<th>{{ STR_FIRST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>{% if (user_first_name) %}{{ user_first_name }}{% else %}<i>{{ STR_UNAVAILABLE }}</i>{% endif %}</td>
		</tr>
		<tr>
			<th>{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>{% if (user_email) %}{{ user_email }}{% else %}<i>{{ STR_UNAVAILABLE }}</i>{% endif %}</td>
		</tr>
		<tr>
			<td colspan="2">
				&nbsp;
			</td>
		</tr>
{% if (row_mail) %}
		<tr>
			<td class="entete" colspan="2">
				{{ STR_MODULE_WEBMAIL_ADMIN_ANSWER_EMAIL_SENT_BY }} {{ row_mail.email }}
			</td>
		</tr
		<tr>
			<th>{{ STR_ADMIN_SUBJECT }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				{{ row_mail.titre }}
			</td>
		</tr>
		<tr>
			<th>{{ STR_MESSAGE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				{{ row_mail.message }}
			</td>
		</tr>
{% endif %}
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_WEBMAIL_ADMIN_EMAIL_TEMPLATES }}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_MODULE_WEBMAIL_ADMIN_EXPLAIN_TEMPLATES }} <a href="{{ email_templates_href|escape('html') }}" title="template d'email">{{ email_templates_admin_href }}</a>. {{ STR_MODULE_WEBMAIL_ADMIN_EXPLAIN_TAGS }}</div>
			</td>
		</tr>
		<tr>
			<th style="width:250px">{{ STR_MODULE_WEBMAIL_ADMIN_CHOOSE_LANG }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="left">
				<select class="form-control" name="lang_mail" id="lang_mail" onchange="charge_templates_list_by_lang(document.getElementById('lang_mail').value);">
					<option value="">{{ STR_CHOOSE }}...</option>
{% for lng in langs %}
	<option value="{{ lng.lng|str_form_value }}" {% if lng.issel %} selected="selected"{% endif %}>{{ lng.lng }}</option>
{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<th style="width:250px">{{ STR_MODULE_WEBMAIL_ADMIN_CHOOSE_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="left">
				<select class="form-control" name="form_id_cat" id="form_id_cat" onchange="charge_templates_list(document.getElementById('form_id_cat').value);">
					<option value="">{{ STR_CHOOSE }}...</option>
				{% for o in options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_WEBMAIL_ADMIN_CHOSSE_TEMPLATE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="left">
				<select class="form-control" name="template" id="template">
					{{ email_template_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
				<a href="{{ email_templates_href|escape('html') }}">{{ STR_MODULE_WEBMAIL_ADMIN_FORM_TITLE }}</a>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_WEBMAIL_ADMIN_EMAIL_FIELD }}</td>
		</tr>
		<tr>
			<td> &nbsp;
				<input type="hidden" name="mode" value="send_mail" />
			</td>
		</tr>
		{% if is_multidestinataire %}
		<tr>
			<th style="vertical-align: top;">{{ STR_MODULE_WEBMAIL_ADMIN_RECIPIENTS }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="middle">{{ multidestinataire_txt }}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		{% elseif is_destinataire %}
		<tr>
			<th style="vertical-align: top;">{{ STR_MODULE_WEBMAIL_ADMIN_RECIPIENT_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="middle">
				<input type="text" class="form-control" value="{{ user_email|str_form_value }}" name="destination_mail" id="destination_mail" />
				<div class="alert alert-info">{{ STR_MODULE_WEBMAIL_ADMIN_RECIPIENT_EMAIL_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				{{ destination_mail_error }}
			</td>
		</tr>
		{% else %}
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="destination_mail" id="destination_mail" value="{{ user_email|str_form_value }}" />
				<input type="hidden" name="id_utilisateur" id="id_utilisateur" value="{{ user_id|str_form_value }}" />
			</td>
		</tr>
		{% endif %}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<th>{{ STR_ADMIN_SUBJECT }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="middle">
				<input type="text" class="form-control" name="subject" id="subject" />
				<input type="hidden" name="lang" id="lang" style="width:30px;" />
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<th>{{ STR_MESSAGE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="middle">
				<textarea class="form-control" name="message" style="height:400px;" rows="2" cols="54" id="message"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE }}</th>
			<td>
				<select class="form-control" name="signature_template_options" id="signature_template_options">
					{{ signature_template_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><textarea class="form-control" name="signature" style="height:200px;" rows="2" cols="30" id="signature" ></textarea></td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_WEBMAIL_ADMIN_SENDER_EMAIL }}</th>
			<td>
				<select class="form-control" name="email_from">
					<option value="email_webmaster">{{ STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL }}</option>
					<option value="email_commande">{{ STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL }}</option>
					<option value="email_client">{{ STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL }}</option>
					<option value="my_email">{{ my_email }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_MODULE_WEBMAIL_ADMIN_EMAIL_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center" style="padding-top:10px">
				{% if (count_email_all_hash) and (request_email_all_hash) %}
				<p><input type="submit" name="submit_send_email_all" class="btn btn-primary" value="{{ STR_MODULE_WEBMAIL_ADMIN_SEND_EMAIL_TO_N_USERS|str_form_value }}" /></p>
				{% else %}
				<p><input type="submit" name="submit" class="btn btn-primary" value="{{ STR_MODULE_WEBMAIL_ADMIN_SEND_EMAIL|str_form_value }}" /></p>
				{% endif %}
			</td>
		</tr>
	</table>
</form>
<script><!--//--><![CDATA[//><!--
	function charge_templates_list_by_lang(lang_mail)
	{
		window.location.replace({{ by_lang_href }});
	}
	function charge_templates_list(cat_id)
	{
		window.location.replace({{ ctl_href }});
	}
//--><!]]></script>
