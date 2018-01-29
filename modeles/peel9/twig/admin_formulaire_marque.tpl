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
// $Id: admin_formulaire_marque.tpl 55303 2017-11-28 15:35:45Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_ADMIN_MARQUES_FORM_TITLE }}</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="number" class="form-control" name="position" value="{{ position|html_entity_decode_if_needed|str_form_value }}" /></td>
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
			<td class="title_label" colspan="2">{{ STR_ADMIN_NAME }}{{ STR_BEFORE_TWO_POINTS }}: {{ l.error }}</td>
		</tr>
		<tr>
			<td colspan="2"><input style="width: 100%" type="text" class="form-control" name="nom_{{ l.lng }}" placeholder="{{ l.placeholder }}" value="{{ l.nom|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{{ STR_ADMIN_DESCRIPTION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">{{ l.description_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{{ STR_ADMIN_META_TITLE }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" class="form-control" name="meta_titre_{{ l.lng }}" size="70" value="{{ l.meta_titre|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{{ STR_ADMIN_META_KEYWORDS }} {{ l.lng|upper }} ({{ STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN }}){{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" name="meta_key_{{ l.lng }}" style="width:100%" rows="2" cols="54">{{ l.meta_key|nl2br_if_needed|html_entity_decode_if_needed|strip_tags }}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{{ STR_ADMIN_META_DESCRIPTION }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" name="meta_desc_{{ l.lng }}" style="width:100%" rows="3" cols="54">{{ l.meta_desc|nl2br_if_needed|html_entity_decode_if_needed|strip_tags }}</textarea></td>
		</tr>
{% endfor %}
		<tr><td colspan="2" class="bloc"><h2>{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</h2></td></tr>
		<tr>
			<td colspan="2" class="title_label">{{ STR_IMAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">
		{% if (image) %}
			{% include "uploaded_file.tpl" with {'f':image,'STR_DELETE':STR_DELETE_THIS_FILE } %}
		{% else %}
			<input style="width: 100%" name="image" type="file" value="" />
		{% endif %}
			</td>
		</tr>
		{% if is_marque_promotion_module_active %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_MARQUES_DISCOUNT_ON_BRAND }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" class="form-control" name="promotion_devises" value="{{ promotion_devises|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}
				<input style="width:100px" type="text" class="form-control" name="promotion_percent" value="{{ promotion_percent|str_form_value }}" />%
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
	{% if (STR_ADMIN_SITE_COUNTRY) %}
		<tr>
			<td class="title_label">{{ STR_ADMIN_SITE_COUNTRY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				{{ site_country_checkboxes }}
			</td>
		</tr>
	{% endif %}
		<tr>
			<td colspan="2" class="center"><br /><input class="btn btn-primary" type="submit" value="{{ titre_soumet|str_form_value }}" />
				{% if mode != 'insere' and STR_ADMIN_SITE_COUNTRY %}
				<input name="update_product_countries_submit" class="btn btn-default" type="submit" value="{{ titre_soumet|str_form_value }} + {{ LANG.STR_ADMIN_EXPORT_PRODUCTS_ASSOCIATED_PRODUCTS|str_form_value }}{{ STR_BEFORE_TWO_POINTS }}: {{ LANG.STR_ADMIN_UPDATE|str_form_value }}" />
				{% endif %}
			</td>
		</tr>
	</table>
</form>