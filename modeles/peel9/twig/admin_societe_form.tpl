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
// $Id: admin_societe_form.tpl 55304 2017-11-28 15:49:01Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_SOCIETE_FORM_COMPANY_PARAMETERS }}</td>
		</tr>
		<tr>
			<td colspan="2"><div class="alert alert-info">{{ STR_ADMIN_SOCIETE_FORM_EXPLAIN }}</div></td>
		</tr>
		<tr>
			<td style="width:250px;">{{ STR_COMPANY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="societe" style="width:100%" value="{{ societe|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_FIRST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="prenom" style="width:100%" value="{{ prenom|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="nom" style="width:100%" value="{{ nom|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_EMAIL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="email" class="form-control" name="email" style="width:100%" value="{{ email|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="siteweb" placeholder="http://" style="width:100%" value="{{ siteweb|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_SIREN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="siren" style="width:100%" value="{{ siren|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_VAT_INTRACOM }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="tvaintra" style="width:100%" value="{{ tvaintra|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_CNIL_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="cnil" style="width:100%" value="{{ cnil|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea class="form-control" name="adresse" class="form-control">{{ adresse }}</textarea></td>
		</tr>
		<tr>
			<td>{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_postal" style="width:100%" value="{{ code_postal|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="ville" style="width:100%" value="{{ ville|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="pays" style="width:100%" value="{{ pays|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="tel" class="form-control" name="tel" style="width:100%" value="{{ tel|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_FAX }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="tel" class="form-control" name="fax" style="width:100%" value="{{ fax|str_form_value }}" /></td>
		</tr>
		{% if distributor is empty %}
		<tr>
			<td>{{ STR_BANK_ACCOUNT_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_banque" style="width:100%" value="{{ code_banque|str_form_value }}" maxlength="5" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_COUNTER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_guichet" style="width:100%" value="{{ code_guichet|str_form_value }}" maxlength="5" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_NUMBER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="numero_compte" style="width:100%" value="{{ numero_compte|str_form_value }}" maxlength="11" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_RIB }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="cle_rib" style="width:100%" value="{{ cle_rib|str_form_value }}" maxlength="2" /></td>
		</tr>
		<tr>
			<td>{{ STR_BANK_ACCOUNT_DOMICILIATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="domiciliation" style="width:100%" value="{{ domiciliation|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_IBAN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="iban" style="width:100%" value="{{ iban|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_SWIFT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="swift" style="width:100%" value="{{ swift|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ACCOUNT_MASTER }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="titulaire" style="width:100%" value="{{ titulaire|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_SOCIETE_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="societe_type" style="width:100%" value="{{ societe_type|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2"><p>{{ STR_ADMIN_SOCIETE_FORM_SECOND_ADDRESS }}</p></td>
		</tr>
		<tr>
			<td>{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><textarea class="form-control" name="adresse2">{{ adresse2 }}</textarea></td>
		</tr>
		<tr>
			<td>{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="code_postal2" style="width:100%" value="{{ code_postal2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="ville2" style="width:100%" value="{{ ville2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="pays2" style="width:100%" value="{{ pays2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="tel" class="form-control" name="tel2" style="width:100%" value="{{ tel2|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_FAX }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="tel" class="form-control" name="fax2" style="width:100%" value="{{ fax2|str_form_value }}" /></td>
		</tr>
		{% endif %}
		{% if distributor %}
		<tr>
			<td>{$STR_IMAGE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
		{% if image %}
				{{ image }}
		{else}
				<input name="image_{{ l.lng }}" type="file" value="" />
		{% endif %}
			</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_PRODUITS_CHOOSE_BRAND }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="id_marques[]" style="width:100%" size="5" multiple="multiple" style="margin-top:15px;" >
					<option value="0">-------------------------------------------</option>
					
					{% for o in marques_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name|html_entity_decode_if_needed }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
		{% endif %}
 		<tr>
			<td>{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
	{% if distributor is empty %}
		{% if STR_ADMIN_SITE_COUNTRY %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_SITE_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				{{ site_country_checkboxes }}
			</td>
		</tr>
		{% endif %}
	{% else %}
		<tr>
			<td>{{ STR_ADMIN_SITE_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" id="site_country" name="site_country[]" style="margin-top:15px;" multiple="multiple" >
					{foreach $tpl_country_options as $co}
					{% for co in tpl_country_options %}
					<option value="{{ co.value|str_form_value }}"{% if co.issel %} selected="selected"{% endif %}>{{ co.name }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
	{% endif %}
		<tr>
			<td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="{{ titre_soumet|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>