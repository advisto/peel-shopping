{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: liste_articles.tpl 39495 2014-01-14 11:08:09Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<div class="entete">{{ STR_ADMIN_CHOOSE_SEARCH_CRITERIA }}</div>
	<div style="margin-top:15px; margin-bottom:15px">
		<div class="row">
			<div class="col-lg-2 col-md-5 col-sm-6 center">
				<label for="search_cat_search">{{ STR_ADMIN_RUBRIQUE }}{{ STR_BEFORE_TWO_POINTS }}:</label>
				<select class="form-control" size="1" id="search_cat_search" name="cat_search" >
					<option value="null">{{ STR_ADMIN_RUBRIQUES_ALL }}</option>
					<option value="0" {% if cat_search=='0' %} selected="selected"{% endif %}{{ STR_ADMIN_RUBRIQUES_NONE_RELATED }}</option>
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
<table>
	<tr>
		<td><img src="images/add.png" width="16" height="16" alt="" /></td>
		<td><a href="{{ ajout_href|escape('html') }}">{{ STR_ADMIN_ARTICLES_FORM_ADD }}</a></td>
	</tr>
</table>
{% if is_empty %}
<div class="alert alert-warning">{{ STR_ADMIN_ARTICLES_NOTHING_FOUND_FOR_LANG }} {{ langue }}.</div>
{% else %}
<div class="table-responsive">
	<table class="table">
		<tr>
			<th class="menu">{{ STR_ADMIN_ACTION }}</th>
			<th class="menu">{{ STR_ADMIN_RUBRIQUE }}</th>
			<th class="menu">{{ STR_ADMIN_TITLE }}</th>
			<th class="menu">{{ STR_WEBSITE }}</th>
			<th class="menu">{{ STR_STATUS }}</th>
		</tr>
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
			<td class="center">
				{% if not (li.sites) %}
				<span style="color:red">-</span><br />
				{% else %}
					{% for si in li.sites %}
					{{ si|html_entity_decode_if_needed }}
					{% endfor %}
				{% endif %}
			</td>
			<td class="center"><img class="change_status" src="{{ li.modif_etat_src|escape('html') }}" alt="" onclick="{{ li.etat_onclick|escape('html') }}" /></td>
		</tr>
	{% endfor %}
	</table>
</div>
{% endif %}
<div class="center">{{ Multipage }}</div>