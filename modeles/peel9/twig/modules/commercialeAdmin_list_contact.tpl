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
// $Id: commercialeAdmin_list_contact.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="get" action="{{ action|escape('html') }}">
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_COMMERCIAL_ADMIN_LIST_TITLE }}</td>
		</tr>
		<tr>
			<td colspan="2">
				<div class="alert alert-info">{{ STR_MODULE_COMMERCIAL_ADMIN_LIST_EXPLAIN }}</div>
				<input type="hidden" name="mode" value="search" />&nbsp;
			</td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_COMMERCIAL_ADMIN_LOGIN_TO_CONTACT }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<input name="login_to_contact" type="text" class="form-control" value="{% if (login_to_contact) %}{{ login_to_contact|str_form_value }}{% endif %}" title="{{ STR_ADMIN_INPUT_SEARCH|str_form_value }}" style="width:200px;" />
			</td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_COMMERCIAL_ADMIN_NAME_TO_CONTACT }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<input name="nom_to_contact" type="text" class="form-control" value="{% if (nom_to_contact) %}{{ nom_to_contact|str_form_value }}{% endif %}" title="{{ STR_ADMIN_INPUT_SEARCH|str_form_value }}" style="width:200px;" />
			</td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_COMMERCIAL_ADMIN_ACCOUNT_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<select class="form-control" name="account_type">
					<option value="">{{ STR_ADMIN_ANY }}</option>
					{{ priv_options }}
				</select>
			</td>
		</tr>
		<tr>
			<th>{{ STR_ADMIN_ADMINISTRATOR }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<select class="form-control" name="admin_id" style="width:200px;">
					<option value="">{{ STR_ADMIN_ANY }}</option>
					{% for o in admin_options %}
					<option value="{{ o.value|html_entity_decode_if_needed }}"{% if o.issel %} selected="selected"{% endif %}>{{ o.name }}</option>
					{% endfor %}
				</select>
			</td>
		</tr>
		<tr>
			<th>{{ STR_ADMIN_DATE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<input name="ad_date" type="text" class="form-control datepicker" value="{% if (ad_date) %}{{ ad_date|str_form_value }}{% endif %}" title="{{ STR_ADMIN_INPUT_SEARCH|str_form_value }}" style="width:110px;" />
			</td>
		</tr>
		<tr>
			<th>{{ STR_ADMIN_REASON }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<select class="form-control" name="form_contact_planified_reason">
					<option value="">{{ STR_ADMIN_ANY }}</option>
					<option value="interesting_profile"{% if form_contact_planified_reason == 'interesting_profile' %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTING_PROFILE }}</option>
					<option value="interested_by_product"{% if form_contact_planified_reason == 'interested_by_product' %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTED_BY_PRODUCT }}</option>
					<option value="payment_expected"{% if form_contact_planified_reason == 'payment_expected' %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_PAYMENT_EXPECTED }}</option>
					<option value="follow_up"{% if form_contact_planified_reason == 'follow_up' %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_FOLLOW_UP }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>{{ STR_MODULE_COMMERCIAL_ADMIN_ACTIVE_TASK }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<select class="form-control" name="form_contact_planified_actif" style="width:200px;">
					<option value="">{{ STR_ADMIN_ANY }}</option>
					<option value="TRUE"{% if form_contact_planified_actif == 'TRUE' %} selected="selected"{% endif %}>{{ STR_ADMIN_TO_DO }}</option>
					<option value="FALSE"{% if form_contact_planified_actif == 'FALSE' %} selected="selected"{% endif %}>{{ STR_ADMIN_DONE_OR_CANCELED }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" class="center">
				<input class="btn btn-primary" type="submit" value="{{ STR_SEARCH|str_form_value }}" />
			</td>
		</tr>
	</table>
</form>
<br />
<table>
	<tr>
		<td>
			<div class="alert alert-info">{{ STR_MODULE_WEBMAIL_ADMIN_COLORS_EXPLAIN }}</div>
		</td>
	</tr>
</table>
{% if empty_results %}
<table class="full_width"><tr><td class="center"><b>{{ STR_MODULE_COMMERCIAL_ADMIN_NOBODY_TO_CONTACT }}</b></td></tr></table>
{% else %}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<table class="full_width">
		<tr>
			<td>
				{{ links_multipage }}
			</td>
		</tr>
		<tr>
			<td class="center">
				<input type="button" value="{{ STR_ADMIN_CHECK_ALL|str_form_value }}" onclick="if (markAllRows('tablesForm')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
				<input type="button" value="{{ STR_ADMIN_UNCHECK_ALL|str_form_value }}" onclick="if (unMarkAllRows('tablesForm')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
				<input type="submit" value="{{ STR_MODULE_COMMERCIAL_ADMIN_DELETE_CONTACT|str_form_value }}" class="btn btn-primary"  name="deleteAd_up" />
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="mode" value="suppr" />
				<table id="tablesForm" class="full_width">
				{{ links_header_row }}
				{% for res in results %}
					{{ res.tr_rollover }}
					<td class="center"><input name="form_delete[]" type="checkbox" value="{{ res.id|str_form_value }}" id="cbx_{{ res.id }}" /></td>
					<td class="center">{{ res.pseudo_admin }}</td>
					<td class="center">{{ res.date }}</td>
					<td class="center">
						{% if (res.last_date) %}
						<span style="font-weight:bold; {% if (res.last_date_color) %} color:{{ res.last_date_color }};{% endif %}">{{ res.last_date }}</span>
						{% else %}
						{{ STR_MODULE_COMMERCIAL_ADMIN_NO_CONTACT }}
						{% endif %}
					</td>
					
					<td class="center">
						{{ STR_ADMIN_NAME }} : {{ res.contact_name|escape('html') }}<br />{{ STR_FIRST_NAME }} : {{ res.contact_firstname|escape('html') }}<br />
						{{ STR_ADMIN_LOGIN }} : {{ res.contact_login|escape('html') }}
						<br /><a href="{{ res.edit_href|escape('html') }}">{{ STR_MODULE_COMMERCIAL_ADMIN_EDIT_ACCOUNT }} {{ STR_NUMBER }}{{ res.contact_id }}</a>
					</td>
					<td class="center">{{ res.reason }}</td>
					<td class="center"><img class="change_status" src="{{ res.etat_src|escape('html') }}" alt="" onclick="{{ res.etat_onclick|escape('html') }}" /></td>
					<td class="center">
					{% if (res.comments) %}
						{{ res.comments }}
					{% endif %}
					</td>
					<td class="center">
					<a href="{{ res.email_send_href|escape('html') }}">{{ STR_ADMIN_UTILISATEURS_SEND_EMAIL }}</a><br />
					<a href="{{ res.appeler_href|escape('html') }}">{{ STR_MODULE_COMMERCIAL_ADMIN_CALL_CLIENT }}</a>
					</td>
					</tr>
				{% endfor %}
				</table>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td class="center">
				<input type="button" value="{{ STR_ADMIN_CHECK_ALL|str_form_value }}" onclick="if (markAllRows('tablesForm')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
				<input type="button" value="{{ STR_ADMIN_UNCHECK_ALL|str_form_value }}" onclick="if (unMarkAllRows('tablesForm')) return false;" class="btn btn-info" />&nbsp;&nbsp;&nbsp;
				<input type="submit" value="{{ STR_MODULE_COMMERCIAL_ADMIN_DELETE_CONTACT|str_form_value }}" class="btn btn-primary" name="deleteAd_up" />
			</td>
		</tr>
		<tr>
			<td>
				{{ links_multipage }}
			</td>
		</tr>
	</table>
</form>
{% endif %}