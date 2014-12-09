{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_type.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_TYPES_FORM_TITLE }}</td>
		</tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_WEBSITE }}{{ STR_BEFORE_TWO_POINTS }}: </td>
			<td>
				<select class="form-control" name="site_id">
					{{ site_id_select_options }}
				</select>
			</td>
		</tr>
		{% for l in langs %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} {{ l.lng|upper }}</td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_NAME }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" class="form-control" name="nom_{{ l.lng }}" value="{{ l.nom|str_form_value }}" /></td>
   	 	</tr>
		{% endfor %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</td></tr>
		<tr>
			<td class="title_label">{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="number" class="form-control" name="position" value="{{ position|str_form_value }}" /></td>
   	 	</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="etat" value="1"{% if etat == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_ONLINE }}<br />
				<input type="radio" name="etat" value="0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /> {{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_SHIP_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="without_delivery_address" value="0" {% if without_delivery_address == 0 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="without_delivery_address" value="1" {% if without_delivery_address == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_TYPES_NO_DELIVERY }}
			</td>
		</tr>
		{% if is_socolissimo_module_active %}
		<tr>
			<td>{{ STR_ADMIN_TYPES_LINK_TO_SOCOLISSIMO }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="is_socolissimo" value="1" {% if is_socolissimo == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="is_socolissimo" value="0" {% if is_socolissimo == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% endif %}
		{% if is_icirelais_module_active %}
		<tr>
			<td>{{ STR_ADMIN_TYPES_LINK_TO_ICIRELAIS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="is_icirelais" value="1" {% if is_icirelais == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="is_icirelais" value="0" {% if is_icirelais == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% endif %}
		{% if is_tnt_module_active %}
		<tr>
			<td colspan="2" class="bloc">{{ STR_ADMIN_TYPES_TNT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TYPES_LINK_TO_TNT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="is_tnt" value="1" {% if is_tnt == 1 %} checked="checked"{% endif %} /> {{ STR_YES }}
				<input type="radio" name="is_tnt" value="0" {% if is_tnt == 0 %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TYPES_TNT_DESTINATION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="tnt_threshold" value="1" {% if tnt_threshold == 1 %} checked="checked"{% endif %} /> {{ STR_ADMIN_TYPES_TNT_HOME }}
				<input type="radio" name="tnt_threshold" value="0" {% if tnt_threshold == 0 %} checked="checked"{% endif %} /> {{ STR_ADMIN_TYPES_TNT_DELIVERY_POINT }}
			</td>
		</tr>
		{% endif %}
		{% if is_fianet_module_active %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_TYPES_KWIXO }}</td></tr>
		<tr>
			<td>{{ STR_ADMIN_TYPES_LINK_TO_KWIXO }}</td>
			<td>
				<input type="text" class="form-control" name="fianet_type_transporteur" value="{{ fianet_type_transporteur|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p class="alert alert-info">{{ STR_ADMIN_TYPES_LINK_TO_KWIXO_EXPLAIN }}</p>
			</td>
		</tr>
		{% endif %}
		<tr>
			<td class="center" colspan="2"><p><input class="btn btn-primary" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>	