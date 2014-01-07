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
// $Id: admin_formulaire_code_promo.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<input type="hidden" name="on_type" value="{{ on_type|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_CODES_PROMOS_ADD_CODE_PROMO_HEADER }}</td>
		</tr>
		{% if (STR_ADMIN_CODES_PROMOS_ALREADY_USED) %}
		<tr>
			<td class="title_label" colspan="2"><div class="alert alert-info">{{ STR_ADMIN_CODES_PROMOS_ALREADY_USED }}</div></td>
		</tr>
		{% endif %}
		<tr>
			<td class="title_label">{{ STR_PROMO_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="nom" size="40" value="{{ nom|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_BEGIN_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control datepicker" name="date_debut" size="40" value="{{ date_debut|str_form_value }}" style="width:110px" /></td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_END_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control datepicker" name="date_fin" size="40" value="{{ date_fin|str_form_value }}" style="width:110px" /></td>
		</tr>
		{% if on_type == 1 %}
		<tr>
			<td class="title_label"><input type="hidden" name="remise_valeur" value="0" />{{ STR_ADMIN_CODES_PROMOS_PERCENT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="remise_percent" size="40" value="{{ remise_percent|str_form_value }}" style="width:110px" /></td>
		</tr>
		{% endif %}
		{% if on_type == 2 %}
		<tr>
			<td class="title_label"><input type="hidden" name="remise_percent" value="0" />{{ STR_ADMIN_CODES_PROMOS_VALUE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="remise_valeur" size="40" value="{{ remise_valeur|str_form_value }}" style="width:110px" /></td>
		</tr>
		{% endif %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_CODES_PROMOS_MIN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="montant_min" size="40" value="{{ montant_min|str_form_value }}" style="width:110px" /> ({{ STR_ADMIN_CODES_PROMOS_MIN_EXPLAIN }})</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
		  		<select class="form-control" size="1" name="id_categorie" >
					<option value="NULL">{{ STR_ADMIN_ALL_CATEGORIES }}</option>
						{{ categorie_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_CODES_PROMOS_NB_FORECASTED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="nombre_prevue" size="40" value="{{ nombre_prevue|str_form_value }}" /> ({{ STR_ADMIN_CODES_PROMOS_NB_FORECASTED_EXPLAIN }})</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_CODES_PROMOS_NB_USED_PER_CLIENT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="nb_used_per_client" size="40" value="{{ nb_used_per_client|str_form_value }}" /> ({{ STR_ADMIN_CODES_PROMOS_NB_USED_PER_CLIENT_EXPLAIN }})</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_CODES_PROMOS_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				  <input type="radio" name="etat" value="1" {% if etat == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_ACTIVATED }}<br />
				  <input type="radio" name="etat" value="0" {% if etat == '0' or not(etat) %} checked="checked"{% endif %} /> {{ STR_ADMIN_DEACTIVATED }}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>