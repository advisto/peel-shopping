{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_configuration.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_CONFIGURATION_FORM_TITLE }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_LANGUAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% for l in langs %}
				<input type="radio" name="lang" id="lang_{{ l.lng|str_form_value }}" value="{{ l.lng|str_form_value }}"{% if l.issel %} checked="checked"{% endif %} /><label for="lang_{{ l.lng|str_form_value }}">{{ l.name }}</label><br />
			{% endfor %}
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="etat" value="1" id="etat_1"{% if etat == '1' %} checked="checked"{% endif %} /><label for="etat_1"> {{ STR_ADMIN_ONLINE }}</label><br />
				<input type="radio" name="etat" value="0" id="etat_0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /><label for="etat_0"> {{ STR_ADMIN_OFFLINE }}</label>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_CONFIGURATION_ORIGIN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" name="origin" value="{{ origin|html_entity_decode_if_needed|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TECHNICAL_CODE }}{{ STR_BEFORE_TWO_POINTS }}:<br /></td>
			<td>
				<input type="text" class="form-control" name="technical_code" value="{{ technical_code|html_entity_decode_if_needed|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td>{{ STR_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:<br /></td>
			<td>
				<input type="text" class="form-control" name="type" value="{{ type|html_entity_decode_if_needed|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_CONFIGURATION_TEXT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" class="form-control" name="string" value="{{ string|html_entity_decode_if_needed|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_COMMENTS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<textarea class="form-control" name="explain" id="explain" style="height:100px;">{{ explain }}</textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><input class="btn btn-primary" type="submit" value="{{ STR_VALIDATE|str_form_value }}" /></td>
		</tr>
	</table>
</form>