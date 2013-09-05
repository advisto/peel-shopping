{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_rubrique.tpl 37993 2013-09-02 16:46:19Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{% if getmode == "modif" %}{{ STR_ADMIN_RUBRIQUES_UPDATE }} "{{ nom }}" - <a href="{{ category_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">{{ STR_ADMIN_SEE_RESULT_IN_REAL }}</a>{% else %}{{ STR_ADMIN_RUBRIQUES_ADD }}{% endif %}</td>
		</tr>
		<tr>
			<td style="width:250px">{{ STR_ADMIN_RUBRIQUES_PARENT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select  name="parent_id" style="width:100%" size="5">
					<option value="0"{% if empty_parent_id %} selected="selected"{% endif %}>{{ STR_ADMIN_AT_ROOT }}</option>
					{{ rubrique_options }}
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
		<tr>
			<td>{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="number" name="position" value="{{ position|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_DISPLAY_MODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			  <input type="radio" name="articles_review" value="1"{% if articles_review == '1' %} checked="checked"{% endif %} /> {{ STR_ADMIN_RUBRIQUES_DISPLAY_SUMMARIES }}<br />
			  <input type="radio" name="articles_review" value="0"{% if articles_review == '0' or not(articles_review) %} checked="checked"{% endif %} /> {{ STR_ADMIN_RUBRIQUES_DISPLAY_NO_SUMMARY }}
			</td>
		</tr>
		<tr>
			<td>{{ STR_ADMIN_TECHNICAL_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input type="text" name="technical_code" value="{{ technical_code|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td>Image{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% if (image) %}
				{{ STR_ADMIN_FILE_NAME }}{{ STR_BEFORE_TWO_POINTS }}:{{ image.name }}&nbsp;
				<a href="{{ image.sup_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" width="16" height="16" alt="" />{{ STR_ADMIN_DELETE_IMAGE }}</a>
				<input type="hidden" name="image" value="{{ image.sup_href|str_form_value }}" />
			{% else %}
				<input style="width: 100%" name="image" type="file" value="" />
			{% endif %}
			</td>
		</tr>
		{% if (image) %}
		<tr>
			<td colspan="2" class="center"><img src="{{ image.src|escape('html') }}" /></td>
		</tr>
		{% endif %}
	{% for l in langs %}
		<tr><td  class="bloc" colspan="2">{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} {{ l.lng|upper }}</td></tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_NAME }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
			<td colspan="2"><input style="width:760px" type="text" name="nom_{{ l.lng }}" value="{{ l.nom|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_DESCRIPTION }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:<br /></td>
		</tr>
		<tr>
			<td colspan="2">{{ l.description_te }}</td>
		</tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_META_TITLE }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="meta_titre_{{ l.lng }}" size="70" value="{{ l.meta_titre|html_entity_decode_if_needed|str_form_value }}" /></td>
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
	{% endfor %}
		{% if (diapo) %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</td></tr>
			{% for i in diapo|keys %}
				{% if (diapo.i) %}
				<tr>
					<td class="label">{% if diapo.i.type=='img' %}{{ STR_ADMIN_IMAGE} {% else %}{{ STR_ADMIN_FILE }} {% endif %}{{ i }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					<td>{% include "uploaded_file.tpl" with ('f':diapo.i,'STR_DELETE':STR_ADMIN_DELETE_THIS_FILE) %}</td>
				</tr>
				{% endif %}
			{% endfor %}
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
			{% for i in 1..5 %}
				<tr>
					<td class="label">{{ STR_ADMIN_FILE }} {{ i }}{{ STR_BEFORE_TWO_POINTS }}:</td>
					<td><input style="width:250px" name="image{{ i }}" type="file" value="" /></td>
				</tr>
			{% endfor %}
		{% endif %}
		<tr>
			<td colspan="2" class="center"><p><input class="bouton" type="submit" value="{{ titre_soumet|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>