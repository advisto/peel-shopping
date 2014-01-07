{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: attributsAdmin_formulaire.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_ATTRIBUTS_ADMIN_CREATE_OPTION }} {{ nom|html_entity_decode_if_needed }}</td>
		</tr>
		{% for lng in langs %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} {{ lng.code|upper }}</td></tr>
		<tr>
			<td class="title_label" style="width:350px">{{ STR_ADMIN_SHORT_DESCRIPTION }} {{ lng.code|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input style="width: 100%" type="text" class="form-control" name="descriptif_{{ lng.code }}" value="{{ lng.descriptif|html_entity_decode_if_needed|str_form_value }}" />{{ lng.error }}</td>
		</tr>
		{% endfor %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_IMAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left">
				{% if (image) %}
				<img src="{{ image.src|escape('html') }}" /><br />
				{{ STR_ADMIN_FILE_NAME }}{{ STR_BEFORE_TWO_POINTS }}: {{ image.nom }}&nbsp;
				<a href="{{ image.drop_href|escape('html') }}"><img src="{{ image.drop_src|escape('html') }}" width="16" height="16" alt="" />{{ STR_ADMIN_DELETE_IMAGE }}</a>
				<input type="hidden" name="image" value="{{ image.nom|str_form_value }}" />
				{% else %}
				<input name="image" type="file" value="" />
				{% endif %}
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_MODULE_ATTRIBUTS_ADMIN_OVERCOST }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input style="width:250px" type="text" class="form-control" name="prix" value="{{ prix|str_form_value }}" /> <b>{{ symbole }} {{ STR_TTC }}</b></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_MODULE_ATTRIBUTS_ADMIN_OVERCOST_RESELLER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input style="width:250px" type="text" class="form-control" name="prix_revendeur" value="{{ prix_revendeur|str_form_value }}" /> <b>{{ symbole }} {{ STR_TTC }}</b></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td class="left"><input type="number" class="form-control" name="position" value="{{ position|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_MANDATORY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="mandatory" value="1" {% if mandatory == '1' %} checked="checked"{% endif %} /> {{ STR_YES }} <br />
				<input type="radio" name="mandatory" value="0" {% if mandatory == '0' %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="{{ titre_soumet|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>	