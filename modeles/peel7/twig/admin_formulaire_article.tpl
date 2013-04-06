{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_article.tpl 35330 2013-02-16 18:27:13Z gboussin $
#}{% if not rubrique_options %}
<table class="main_table">
	<tr>
		<td colspan="2" class="entete">{{ STR_ADMIN_ARTICLES_FORM_ADD }}</td>
	</tr>
</table>
<p><a href="{{ add_category_url }}">{{ STR_ADMIN_ARTICLES_CREATE_CATEGORY_FIRST }}</a></p>
{% else %}
<form method="post" action="{{ action|escape('html') }}" enctype="multipart/form-data">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{% if (art_href) %}{{ STR_ADMIN_ARTICLES_FORM_MODIFY }} "{{ titre }}" - <a href="{{ art_href|escape('html') }}" onclick="return(window.open(this.href)?false:true);">Voir en ligne</a>{% else %}{{ STR_ADMIN_ARTICLES_FORM_ADD }}{% endif %}</td>
		</tr>
		<tr>
			<td class="label" style="width:230px">{{ STR_ADMIN_ARTICLES_CATEGORIE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<select name="rubriques[]" multiple="multiple" size="10" style="width: 100%">
				{{ rubrique_options }}
				</select>
				{{ rubrique_error }}
			</td>
		</tr>
		<tr>
			<td class="label">{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="etat" value="1"{% if etat == '1' %} checked="checked"{% endif %} />{{ STR_ADMIN_ONLINE }}<br />
				<input type="radio" name="etat" value="0"{% if etat == '0' or not(etat) %} checked="checked"{% endif %} />{{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_POSITION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" name="position" value="{{ position|html_entity_decode_if_needed|str_form_value }}" />
			</td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_DISPLAY_ON_HOMEPAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="radio" name="on_special" value="1"{% if on_special == '1' %} checked="checked"{% endif %} />{{ STR_YES }}<br />
				<input type="radio" name="on_special" value="0"{% if on_special == '0' or not(on_special) %} checked="checked"{% endif %} />{{ STR_NO }}
			</td>
		</tr>
		<tr>
			<td class="label" style="margin-top:5px;">{{ STR_ADMIN_TECHNICAL_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
				<input type="text" name="technical_code" value="{{ technical_code|html_entity_decode_if_needed|str_form_value }}" /><br />
			</td>
		</tr>
		{% for l in langs %}
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_LANGUAGES_SECTION_HEADER }} {{ l.lng|upper }}</td></tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_OVER_TITLE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><b>{{ STR_ADMIN_TITLE }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</b>
				{{ l.error }}
			</td>
		</tr>
		<tr>
			<td colspan="2"><input style="width:100%" type="text" name="titre_{{ l.lng }}" value="{{ l.titre|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_ARTICLE_SHORT_DESCRIPTION }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">{{ l.chapo_te }}</td>
		</tr>
		<tr>
			<td class="label" colspan="2">{{ STR_ADMIN_ARTICLES_COMPLETE_TEXT }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2">{{ l.texte_te }}</td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_META_TITLE }} {{ l.lng|upper }}{{ STR_BEFORE_TWO_POINTS }}:</td>
		</tr>
		<tr>
			<td colspan="2"><input type="text" name="meta_titre_{{ l.lng }}" size="70" value="{{ l.meta_titre|html_entity_decode_if_needed|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2" class="label">{{ STR_ADMIN_META_KEYWORDS }}{{ STR_ADMIN_META_KEYWORDS }} {{ l.lng|upper }} ({{ STR_ADMIN_SEPARATE_KEYWORDS_EXPLAIN }}){{ STR_BEFORE_TWO_POINTS }}:</td>
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
		<tr><td colspan="2" class="bloc">{{ STR_ADMIN_VARIOUS_INFORMATION_HEADER }}</td></tr>
		<tr>
			<td class="label">{{ STR_ADMIN_IMAGE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			{% if (image) %}
				{{ STR_ADMIN_FILE_NAME }}{{ STR_BEFORE_TWO_POINTS }}:{{ image.nom }}&nbsp;
				<a href="{{ image.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" width="16" height="16" alt="" />{{ STR_ADMIN_DELETE_IMAGE }}</a>
				<input type="hidden" name="image1" value="{{ image.nom|str_form_value }}" />
			{% else %}
				<input style="width: 100%" name="image1" type="file" value="" />
			{% endif %}
			</td>
		</tr>
		{% if (image) %}
		<tr>
			<td colspan="2" class="center"><img src="{{ image.src|escape('html') }}" alt="" /></td>
		</tr>
		{% endif %}
		<tr>
			<td colspan="2" class="center"><p><input class="bouton" type="submit" value="{{ normal_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>
{% endif %}