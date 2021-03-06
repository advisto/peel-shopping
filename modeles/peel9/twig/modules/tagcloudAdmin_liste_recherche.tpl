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
// $Id: tagcloudAdmin_liste_recherche.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" action="{{ action|escape('html') }}" method="post" style="margin-top:10px">
<table id="tablesForm" class="full_width">
	<tr>
		<td class="entete" colspan="4">{{ STR_MODULE_TAGCLOUD_ADMIN_LIST_TITLE }}</td>
	</tr>
	<tr>
		<td colspan="4">
			<div style="margin-top:5px;">
				<p><a href="{{ add_href|escape('html') }}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {{ STR_MODULE_TAGCLOUD_ADMIN_ADD_SEARCH }}</a></p>
			</div>
		</td>
	</tr>
	{% if (results) %}
	<tr>
		<td class="menu">{{ STR_ADMIN_ACTION }}</td>
		<td class="menu">{{ STR_MODULE_TAGCLOUD_ADMIN_TAG_NAME }}</td>
		<td class="menu">{{ STR_ADMIN_LANGUAGE }}</td>
		<td class="menu">{{ STR_MODULE_TAGCLOUD_ADMIN_SEARCH_COUNT }}</td>
	</tr>
	{% for res in results %}
		{{ res.tr_rollover }}
		<td class="center">
			<input name="tag_ids[]" type="checkbox" value="{{ res.id|str_form_value }}" id="{{ res.id }}" />
			<a data-confirm="{{ STR_ADMIN_DELETE_WARNING|str_form_value }}" title="{{ STR_DELETE|str_form_value }} {{ res.tag_name }}" href="{{ res.drop_href|escape('html') }}"><img src="{{ drop_src|escape('html') }}" alt="{{ STR_DELETE|str_form_value }}" /></a>
			<a title="{{ STR_MODULE_TAGCLOUD_ADMIN_MODIFY_THIS_TAG }}" href="{{ res.edit_href|escape('html') }}"><img src="{{ edit_src|escape('html') }}" width="16" height="16" alt="" /></a>
		</td>
		<td><a title="{{ STR_MODULE_TAGCLOUD_ADMIN_MODIFY_THIS_TAG }}" href="{{ res.edit_href|escape('html') }}">{{ res.tag_name }}</a></td>
		<td class="center"><b>{{ res.lang }}</b></td>
		<td class="center"><b>{{ res.nbsearch }}</b></td>
	</tr>
	{% endfor %}
	{% else %}
	<tr><td colspan="4"><div class="alert alert-warning">{{ STR_MODULE_TAGCLOUD_ADMIN_NOTHING_FOUND }}</div></td></tr>
	{% endif %}
</table>
<p>{{ links_multipage }}</p>
<div class="center" style="margin-top:5px;">
		<input type="button" class="btn btn-info" onclick="if (markAllRows('tablesForm')) return false;" value="Tout cocher">
		<input type="button" class="btn btn-info" onclick="if (unMarkAllRows('tablesForm')) return false;" value="Tout décocher">
		<input type="hidden" name="mode" value="suppr_select" />
		&nbsp; &nbsp; &nbsp; &nbsp; <input class="btn btn-info alert-danger" type="submit" value="supprimer">
</div>
</form>