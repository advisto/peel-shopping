{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: liste_articles.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<div class="entete">{{ STR_ADMIN_CHOOSE_SEARCH_CRITERIA }}</div>
	<div style="margin-top:15px; margin-bottom:15px">
		<div class="row">
			<div class="col-lg-2 col-md-5 col-sm-6 center">
				<label for="search_cat_search">{{ STR_ADMIN_RUBRIQUE }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				<select class="form-control" size="1" id="search_cat_search" name="cat_search" >
					<option value="null">{{ STR_ADMIN_RUBRIQUES_ALL }}</option>
					<option value="0" {% if cat_search=='0' %} selected="selected"{% endif %}>{{ STR_ADMIN_RUBRIQUES_NONE_RELATED }}</option>
					{{ rubrique_options }}
				</select>
			</div>
			<div class="col-lg-2 col-md-5 col-sm-6 center">
				<label for="search_etat">{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				<select class="form-control" size="1" id="search_etat" name="etat">
					<option value="null">{{ STR_ADMIN_ARTICLES_ALL }}</option>
					<option value="1">{{ STR_ADMIN_ONLINE }}</option>
					<option value="0">{{ STR_ADMIN_OFFLINE }}</option>
				</select>
			</div>
			<div class="clearfix visible-sm visible-md"></div>
			<div class="col-lg-3 col-md-5 col-sm-6 center">
				<label for="search_text_in_title">{{ STR_ADMIN_SEARCH_IN_TITLE }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				<input type="text" class="form-control" id="search_text_in_title" name="text_in_title" size="15" value="{{ text_in_title|html_entity_decode_if_needed|str_form_value }}" />
			</div>
			<div class="col-lg-3 col-md-5 col-sm-6 center">
				<label for="search_text_in_article">{{ STR_ADMIN_SEARCH_IN_ARTICLE }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				<input type="text" class="form-control" id="search_text_in_article" name="text_in_article" size="15" value="{{ text_in_article|html_entity_decode_if_needed|str_form_value }}" />
			</div>
			<div class="clearfix visible-sm"></div>
			<div class="col-lg-2 col-md-2 col-sm-12 center" style="padding-top:15px"><input class="btn btn-primary" type="submit" value="{{ STR_SEARCH|str_form_value }}" name="action" /></div>
		</div>
	</div>
</form>
<div class="entete">{{ STR_ADMIN_ARTICLES_ARTICLES_LIST }}</div>
<div style="margin-top:5px;margin-bottom:5px;"><a href="{{ ajout_href|escape('html') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span>{{ STR_ADMIN_ARTICLES_FORM_ADD }}</a></div>
{% if is_empty %}
<div class="alert alert-warning">{{ STR_ADMIN_ARTICLES_NOTHING_FOUND_FOR_LANG }} {{ langue }}.</div>
{% else %}
<div class="table-responsive">
	<table class="table">
		{{ links_header_row }}
	{% for li in lignes %}
		{{ li.tr_rollover }}
			<td class="center"><a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ li.titre|htmlspecialchars }}" href="{{ li.drop_href|escape('html') }}"><img src="{{ li.drop_src|escape('html') }}" alt="" /></a></td>
			<td>
				{% if not (li.rubs) %}
					<span style="color:red">-</span><br />
				{% else %}
					{% for ru in li.rubs %}
						{% if not (ru) %}
							<span style="color:red">-</span><br />
						{% else %}
							{% if (ru.parent_nom) %}
								<span style="color:#666666">{{ ru.parent_nom|html_entity_decode_if_needed }}</span> &gt; 
							{% endif %}
							{{ ru.nom|html_entity_decode_if_needed }}<br />
						{% endif %}
					{% endfor %}
				{% endif %}
			</td>
			<td><a title="{{ STR_ADMIN_ARTICLES_FORM_MODIFY|str_form_value }}" href="{{ li.modif_href|escape('html') }}">{{ li.titre|html_entity_decode_if_needed }}</a></td>
			<td class="center">{{ li.site_name }}</td>
	{% if (STR_ADMIN_SITE_COUNTRY) %}
			<td class="center">{{ li.site_country }}</td>
	{% endif %}
			<td class="center"><img class="change_status" src="{{ li.modif_etat_src|escape('html') }}" alt="" onclick="{{ li.etat_onclick|escape('html') }}" /></td>
		</tr>
	{% endfor %}
	</table>
</div>
{% endif %}
<div class="center">{{ links_multipage }}</div>