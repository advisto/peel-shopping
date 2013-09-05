{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_email-templates_output2.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}{{ action_html }}
<center>
	<form action="email-templates.php" method="post" name="form_ajout">
		{{ form_token }}
			<table class="full_width">
				<tr>
					<td class="entete" colspan="2">{{ STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE }}</td>
				</tr>
				<tr>
					<td colspan="2"><div class="global_help">{{ STR_ADMIN_EMAIL_TEMPLATES_TAGS_TABLE_EXPLAIN }}</div></td>
				</tr>
				<tr>
					<td class="top">
					<table class="full_width"  cellspacing="3">
						<tr>
							<td width="100">{{ STR_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
							<td>{{ categories_list }}</td>
						</tr>
						<tr>
							<td>{{ STR_ADMIN_TECHNICAL_CODE }}</td>
							<td><input name="form_technical_code" size="60" type="text" id="technical_code" value="{{ form_technical_code|str_form_value }}" /></td>
						</tr>
						<tr>
							<td>{{ STR_ADMIN_EMAIL_TEMPLATES_TEMPLATE_NAME }}</td>
							<td><input name="form_name" size="60" type="text" id="template_name" value="{{ form_name|str_form_value }}" /></td>
						</tr>
						<tr>
							<td>{{ STR_ADMIN_SUBJECT }}</td>
							<td><input name="form_subject" size="60" type="text" id="template_subject" value="{{ form_subject|str_form_value }}" /></td>
						</tr>
						<tr>
							<td>{{ STR_TEXT }}</td>
							<td><textarea name="form_text" id="template_text" style="width:90%; height:300px;">{{ form_text }}</textarea></td>
						</tr>
						<tr>
							<td>{{ STR_ADMIN_LANGUAGE }}</td>
							<td>
							{% for l in langs %}
								<input type="radio" name="form_lang" id="template_lang" value="{{ l.lng|str_form_value }}"{% if l.issel %} checked="checked"{% endif %} /> {{ l.lng }}
							{% endfor %}
							</td>
						</tr>
						<tr>
							<td colspan="2"><br /><center><input name="submit_ajout" type="submit" value="{{ STR_ADMIN_EMAIL_TEMPLATES_INSERT_TEMPLATE|str_form_value }}" class="bouton" /></center></td>
						</tr>
					</table>
				</td>
				<td class="top">{{ emailLinksExplanations }}</td>
			</tr>
		</table>
	</form>
</center>