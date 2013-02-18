{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: webmailAdmin_form.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<table class="full_width">
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
		<th style="width:100px">{{ STR_GENDER }}{{ STR_BEFORE_TWO_POINTS }}:</th>
		<td>{% if (user_gender) %}{{ user_gender }}{% else %}<i>{{ STR_UNAVAILABLE }}</i>{% endif %}</td>
	</tr>
	<tr>
		<th style="width:100px">{{ STR_ADMIN_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</th>
		<td>{% if (user_name) %}{{ user_name }}{% else %}<i>{{ STR_UNAVAILABLE }}</i>{% endif %}</td>
	</tr>
	<tr>
		<th style="width:100px">{{ STR_FIRST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</th>
		<td>{% if (user_first_name) %}{{ user_first_name }}{% else %}<i>{{ STR_UNAVAILABLE }}</i>{% endif %}</td>
	</tr>
	<tr>
		<th style="width:100px">{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</th>
		<td>{% if (user_email) %}{{ user_email }}{% else %}<i>{{ STR_UNAVAILABLE }}</i>{% endif %}</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
</table>
<script><!--//--><![CDATA[//><!--
	function charge_templates_list_by_lang(lang_mail)
	{ldelim }}
		window.location.replace({{ by_lang_href }});
	{rdelim }}
	function charge_templates_list(cat_id)
	{ldelim }}
		window.location.replace({{ ctl_href }});
	{rdelim }}
//--><!]]></script>
{% if (row_mail) %}
<table class="full_width">
	<tr>
		<td class="entete" colspan="2">
			{{ STR_MODULE_WEBMAIL_ADMIN_ANSWER_EMAIL_SENT_BY }} {{ row_mail.email }}
		</td>
	</tr
	<tr>
		<th style="width:100px">{{ STR_ADMIN_SUBJECT }}{{ STR_BEFORE_TWO_POINTS }}:</th>
		<td>
			{{ row_mail.titre }}
		</td>
	</tr>
	<tr>
		<th style="width:100px">{{ STR_MESSAGE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
		<td>
			{{ row_mail.message }}
		</td>
	</tr>
</table>
{% endif %}
<form method="post" id="form_send_email" action="{{ action|escape('html') }}">
	{{ form_token }}
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_WEBMAIL_ADMIN_EMAIL_TEMPLATES }}</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{{ STR_MODULE_WEBMAIL_ADMIN_EXPLAIN_TEMPLATES }} <a href="{{ email_templates_href|escape('html') }}" title="template d'email">{{ email_templates_admin_href }}</a>. {{ STR_MODULE_WEBMAIL_ADMIN_EXPLAIN_TAGS }}</div>
			</td>
		</tr>
		<tr>
			<th style="width:250px">{{ STR_MODULE_WEBMAIL_ADMIN_CHOOSE_LANG }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="left">
				<select name="lang_mail" id="lang_mail" onchange="charge_templates_list_by_lang(document.getElementById('lang_mail').value);">
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
				<select name="form_id_cat" id="form_id_cat" onchange="charge_templates_list(document.getElementById('form_id_cat').value);">
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
				<select name="template" id="template">
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
	</table>
	<table class="full_width">
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
				<input type="text" value="{{ user_email|str_form_value }}" name="destination_mail" id="destination_mail" style="width:300px;" />
				<div class="global_help">{{ STR_MODULE_WEBMAIL_ADMIN_RECIPIENT_EMAIL_EXPLAIN }}</div>
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
				<input type="text" name="subject" id="subject" style="width:300px;" />
				<input type="hidden" name="lang" id="lang" style="width:30px;" />
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<th>{{ STR_MESSAGE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td class="middle">
				<textarea name="message" style="height:400px;width:600px;" rows="2" cols="54" id="message" ></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE }}</th>
			<td>
				<select name="function" id="function" onchange="mail_signature('{{ nom_famille|str_form_value }}', '{{ prenom|str_form_value }}', '{{ site|str_form_value }}', '[link=&#34;http://www.{{ HTTP_HOST }}/&#34;]www.{{ HTTP_HOST }}[/link]', '');">
					<option value="none">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_NONE }}</option>
					<option value="">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_UNDEFINED_SERVICE }}</option>
					<option value="support">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_CLIENT_SERVICE }}</option>
					<option value="commercial">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_SALES }}</option>
					<option value="comptabilite">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_ACCOUNTING }}</option>
					<option value="referencement">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_SEO }}</option>
					<option selected="selected" value="informatique">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_TECHNICAL }}</option>
					<option value="communication">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_COMMUNICATION }}</option>
					<option value="marketing">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_MARKETING }}</option>
					<option value="direction">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_DIRECTION }}</option>
					<option value="externe">{{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_EXTERNAL }}</option>
				</select>
				<input type="checkbox" name="signature_with_name" id="signature_with_name" value="1" onclick="mail_signature('{{ nom_famille|str_form_value }}', '{{ prenom|str_form_value }}', '{{ site|str_form_value }}', '[link=&#34;http://www.{{ HTTP_HOST }}/&#34;]www.{{ HTTP_HOST }}[/link]', '');" /> {{ STR_MODULE_WEBMAIL_ADMIN_SIGNATURE_ADD_NAME }}
			</td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_WEBMAIL_ADMIN_SENDER_EMAIL }}</th>
			<td>
				<select name="email_from">
					<option value="email_webmaster">{{ STR_MODULE_WEBMAIL_ADMIN_WEBMASTER_EMAIL }}</option>
					<option value="email_commande">{{ STR_MODULE_WEBMAIL_ADMIN_ORDER_MANAGEMENT_EMAIL }}</option>
					<option value="email_client">{{ STR_MODULE_WEBMAIL_ADMIN_CLIENT_SERVICE_EMAIL }}</option>
					<option value="my_email">{{ my_email }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="global_help">{{ STR_MODULE_WEBMAIL_ADMIN_EMAIL_EXPLAIN }}</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center">
				{% if (count_email_all_hash) and (request_email_all_hash) %}
				<br /><br /><center><input type="submit" name="submit_send_email_all" class="bouton" value="{{ STR_MODULE_WEBMAIL_ADMIN_SEND_EMAIL_TO_N_USERS|str_form_value }}" /></center>
				{% else %}
				<br /><br /><center><input type="submit" name="submit" class="bouton" value="{{ STR_MODULE_WEBMAIL_ADMIN_SEND_EMAIL|str_form_value }}" /></center>
				{% endif %}
			</td>
		</tr>
	</table>
</form>

<script><!--//--><![CDATA[//><!--
window.onload = (function(){
	jQuery("#template").change(function () {

		form_template_content_add("template", "message", "message", "{{ wwwroot }}");
		form_template_content_add("template", "subject", "title", "{{ wwwroot }}");
		form_template_content_add("template", "lang", "lang", "{{ wwwroot }}");
	})
.change();
});
//--><!]]></script>
