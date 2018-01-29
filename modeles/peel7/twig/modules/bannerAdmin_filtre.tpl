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
// $Id: bannerAdmin_filtre.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<table class="full_width">
		<tr>
			<td class="entete">{{ STR_ADMIN_CHOOSE_SEARCH_CRITERIA }}</td>
		</tr>
		<tr>
			<td class="input_search">
				<table class="full_width" class="center">
					<tr>
						<td>{{ STR_ADMIN_SEARCH_IN_TITLE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_LANGUAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_BEGIN_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_ADMIN_END_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
						<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					</tr>
					<tr>
						<td><input type="text" class="form-control" name="filter_description" id="filter_description" value="{{ filter_description|str_form_value }}" /></td>
						<td>
							<select class="form-control" name="filter_categorie_banniere" id="filter_categorie_banniere">
								<option value="">---</option>
								{% for o in options %}
								<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
								{% endfor %}
							</select>
						</td>
						<td><input type="text" class="form-control" name="filter_lang" id="filter_lang" value="{{ filter_lang|str_form_value }}" /></td>
						<td><input type="text" class="form-control datepicker" name="filter_date_debut" id="filter_date_debut" value="{{ filter_date_debut|str_form_value }}" /></td>
						<td><input type="text" class="form-control datepicker" name="filter_date_fin" id="filter_date_fin" value="{{ filter_date_fin|str_form_value }}" /></td>
						<td>
							<select class="form-control" name="filter_etat">
								<option value="-"{% if filter_etat == "-" %} selected="selected"{% endif %}>{{ STR_MODULE_BANNER_ADMIN_ALL }}</option>
								<option value="1"{% if filter_etat == "1" %} selected="selected"{% endif %}>{{ STR_ADMIN_ONLINE }}</option>
								<option value="0"{% if filter_etat == "0" %} selected="selected"{% endif %}>{{ STR_ADMIN_OFFLINE }}</option>
							</select>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="center"><p><input type="hidden" name="mode" value="search" /><input type="submit" class="btn btn-primary" value="{{ STR_SEARCH|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>
<br />