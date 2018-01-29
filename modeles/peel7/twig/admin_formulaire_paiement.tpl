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
// $Id: admin_formulaire_paiement.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_PAIEMENT_FORM_TITLE }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}: </td>
			<td>
				<select class="form-control" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="etat" value="1"{% if etat == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_ONLINE }}<br />
				<input type="radio" name="etat" value="0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /> {{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
		{% for l in langs %}
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} - {{ lang_names[l.lng]|upper }}</h2></td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_NAME }} {{ l.lng|upper }}:</td>
			<td><input type="text" class="form-control" name="nom_{{ l.lng }}" value="{{ l.nom|str_form_value }}" /></td>
		</tr>
		{% endfor %}
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</h2></td></tr>
		<tr>
			<td>{{ STR_ADMIN_TECHNICAL_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="technical_code" value="{{ technical_code|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_PAIEMENT_ORDER_OVERCOST }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="tarif" style="width:100px" value="{{ tarif|str_form_value }}" /> {{ site_symbole }}</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="text" class="form-control" name="tarif_percent" style="width:100px" value="{{ tarif_percent|str_form_value }}" /> %</td>
		</tr>
		<tr>
			<td colspan="2"><p class="alert alert-info">{{ STR_ADMIN_PAIEMENT_WARNING }}</p></td>
		</tr>
		<tr>
			<td>{{ STR_VAT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="tva">{{ tva }}</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="number" class="form-control" name="position" value="{{ position|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TARIFS_MINIMAL_TOTAL }} ({{ site_symbole }} {{ STR_TTC }}){{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="totalmin" style="width:100px" value="{{ totalmin|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TARIFS_MAXIMAL_TOTAL }} ({{ site_symbole }} {{ STR_TTC }}){{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="totalmax" style="width:100px" value="{{ totalmax|str_form_value }}" /></td>
		</tr>
		{% if is_payback_module_active %}
		<tr>
			<td>{{ STR_ADMIN_PAIEMENT_ALLOW_REIMBURSMENTS }}{{ STR_BEFORE_TWO_POINTS }}?</td>
			<td>
				<input type="radio" name="retour_possible" id="retour_possible1" value="1"{% if is_retour_possible1 %} checked="checked"{% endif %} /> <label for="retour_possible1">{{ STR_YES }}</label>&nbsp;&nbsp;
				<input type="radio" name="retour_possible" id="retour_possible0" value="0"{% if is_retour_possible0 %} checked="checked"{% endif %} /> <label for="retour_possible0">{{ STR_NO }}</label>
			</td>
		</tr>
		{% endif %}
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>