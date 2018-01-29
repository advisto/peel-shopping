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
// $Id: admin_formulaire_tarif.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<p>{{ STR_ADMIN_TARIFS_CONFIG_STATUS }}<b><a href="sites.php">{% if mode_transport == 1 %}{{ STR_ADMIN_ACTIVATED }}{% else %}{{ STR_ADMIN_DEACTIVATED }} {"=>"|htmlspecialchars }} {{ STR_ADMIN_TARIFS_CONFIG_DEACTIVATED_COMMENT }}{% endif %}</a></b></p>
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_TARIFS_FORM_TITLE }}</td>
		</tr>
		<tr>
			<td style="width:250px">{{ STR_SHIPPING_ZONE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="zone">
				{% for o in zones_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_SHIPPING_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="type">
				{% for o in type_options %}
					<option value="{{ o.value|str_form_value }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
				{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TARIFS_MINIMAL_WEIGHT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="poidsmin" style="width:100px" value="{{ poidsmin|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TARIFS_MAXIMAL_WEIGHT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="poidsmax" style="width:100px" value="{{ poidsmax|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TARIFS_MINIMAL_TOTAL }} ({{ site_symbole }} {{ STR_TTC }}){{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="totalmin" style="width:100px" value="{{ totalmin|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TARIFS_MAXIMAL_TOTAL }} ({{ site_symbole }} {{ STR_TTC }}){{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="totalmax" style="width:100px" value="{{ totalmax|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TARIF }} ({{ site_symbole }} {{ STR_TTC }}){{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="tarif" style="width:100px" value="{{ tarif|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_VAT_PERCENTAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" name="tva" style="width:100px">{{ vat_select_options }}</select>
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select class="form-control" {% if site_id_select_multiple %} name="site_id[]" multiple="multiple" size="5"{% else %} name="site_id"{% endif %}>
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>