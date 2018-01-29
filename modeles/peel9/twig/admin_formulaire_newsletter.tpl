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
// $Id: admin_formulaire_newsletter.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete">{{ STR_ADMIN_NEWSLETTERS_FORM_TITLE }}</td>
		</tr>
		<tr>
			<td>
				<div class="alert alert-info">{{ STR_ADMIN_NEWSLETTERS_WARNING }}</div>
			</td>
		</tr>
		<tr>
			<td>
				<p class="alert alert-info">{{ STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE_INFO }}</p>
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}: </td>
		</tr>
		<tr>
			<td>
				<select class="form-control" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_NEWSLETTERS_CHOOSE_TEMPLATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td>
				<select class="form-control" name="template_technical_code" id="template_technical_code">
					{{ template_technical_code_options }}
				</select>
			</td>
		</tr>
		{% for l in langs %}
		<tr>
			<td>{{ STR_ADMIN_SUBJECT }} {{ l.lng }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="sujet_{{ l.lng }}" style="width:100%" value="{{ l.sujet|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_MESSAGE }} {{ l.lng }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td>{{ l.message_te }}</td>
		</tr>
		{% endfor %}
		<tr>
			<td class="center"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>