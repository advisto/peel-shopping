{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_categorie.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{% if (cat_href) %}{{ STR_ADMIN_CATEGORIES_FORM_MODIFY }} "{{ nom }}" - <a href="{{ cat_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_SEE_RESULT_IN_REAL }}</a>{% else %}{{ STR_ADMIN_CATEGORIES_FORM_ADD_BUTTON }}{% endif %}</td>
		</tr>
		<tr>
			<td class="label" colspan="2"></td>
		</tr>
		<tr>
			<td class="top" style="width:250px">{{ STR_ADMIN_CATEGORIES_PARENT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select name="parent_id" style="width:100%" size="5">
					<option value="0"{% if issel_parent_zero %} selected="selected"{% endif %}>{{ STR_ADMIN_AT_ROOT }}</option>
					{{ categorie_options }}
				</select>
			</td>
		</tr>
		<tr>
			<td class="top">{{ STR_ADMIN_DISPLAY_ON_HOMEPAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_special" value="1"{% if is_on_special %} checked="checked"{% endif %} /></td>
		</tr>
		{% if is_carrousel_module_active %}
		<tr>
			<td class="top">{{ STR_ADMIN_CATEGORIES_DISPLAY_IN_CARROUSEL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="checkbox" name="on_carrousel" value="1"{% if is_on_carrousel %} checked="checked"{% endif %} /></td>
		</tr>
		{% endif %}
		<tr>
			<td>{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input size="1" type="text" name="position" value="{{ position|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="etat" value="1"{% if etat == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_ONLINE }}<br />
				<input type="radio" name="etat" value="0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} /> {{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_CATEGORIES_DISPLAY_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="type_affichage" value="0"{% if type_affichage == '0' %} checked="checked"{% endif %} /> {{ STR_ADMIN_IN_COLUMNS }}<br />
				<input type="radio" name="type_affichage" value="1"{% if type_affichage == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_IN_LINES }}
			</td>
		</tr>
		{% for l in langs %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} {{ l.lng|upper }}</td></tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_NAME }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><input style="width:100%" type="text" name="nom_{{ l.lng }}" value="{{ l.nom|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_DESCRIPTION }} {{ l.lng|upper }}:</td>
		</tr>
		<tr>
			<td colspan="2">{{ l.description_te }}</td>
		</tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_META_TITLE }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="meta_titre_{{ l.lng }}" size="70" value="{{ l.meta_titre|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_META_KEYWORDS }} {{ l.lng|upper }} ({{ STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN }}){{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="meta_key_{{ l.lng }}" style="width:100%" rows="2" cols="54">{{ l.meta_key|nl2br_if_needed|html_entity_decode_if_needed|strip_tags }}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_META_DESCRIPTION }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea name="meta_desc_{{ l.lng }}" style="width:100%" rows="3" cols="54">{{ l.meta_desc|nl2br_if_needed|html_entity_decode_if_needed|strip_tags }}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_HEADER_HTML_TEXT }}</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea style="width:100%; height:150px;" id="header_html_{{ l.lng }}" name="header_html_{{ l.lng }}" rows="10" cols="54">{{ l.header_html|html_entity_decode_if_needed }}</textarea>
			</td>
 	 	</tr>
		<tr>
			<td>{{ STR_ADMIN_IMAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% if (l.image) %}
				{{ STR_ADMIN_FILE_NAME }}{{ STR_BEFORE_TWO_POINTS }}:{{ l.image.nom }}&nbsp;
				<a href="{{ l.image.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" width="16" height="16" alt="" />{{ STR_ADMIN_DELETE_IMAGE }}</a>
				<input type="hidden" name="image_{{ l.lng }}" value="{{ l.image.nom|str_form_value }}" />
			{% else %}
				<input style="width: 100%" name="image_{{ l.lng }}" type="file" value="" />
			{% endif %}
			</td>
		</tr>
		{% if (l.image) %}
		<tr>
			<td colspan="2" class="center"><img src="{{ l.image.src|escape('html') }}" /></td>
		</tr>
		{% endif %}
		{% endfor %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</td></tr>
		{% if is_category_promotion_module_active %}
		<tr>
			<td class="label">{{ STR_ADMIN_CATEGORIES_DISCOUNT_IN_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:100px" type="text" name="promotion_devises" value="{{ promotion_devises|str_form_value }}" /> {{ site_symbole }} {{ STR_TTC }}
				<input style="width:100px" type="text" name="promotion_percent" value="{{ promotion_percent|str_form_value }}" />%
			</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_CATEGORIES_DISCOUNT_APPLY_TO_SONS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="on_child" value="1"{% if on_child == '1' %} checked="checked"{% endif %} /> {{ STR_YES }} - {{ STR_ADMIN_CATEGORIES_DISCOUNT_APPLY_TO_SONS_EXPLAIN }}<br />
				<input type="radio" name="on_child" value="0"{% if on_child == '0' %} checked="checked"{% endif %} /> {{ STR_NO }}
			</td>
		</tr>
		{% endif %}
		<tr>
			<td colspan="2" class="top bloc">{{ STR_ADMIN_CUSTOMIZE_APPEARANCE }}</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_BACKGROUND_COLOR }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input style="width:100%" type="text" name="background_color" value="{{ background_color|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_BACKGROUND_COLOR_FOR_MENU }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input style="width:100%" type="text" name="background_menu" value="{{ background_menu|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input class="bouton" type="submit" value="{{ titre_soumet|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>