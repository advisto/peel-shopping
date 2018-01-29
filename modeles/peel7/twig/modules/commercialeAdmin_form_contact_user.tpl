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
// $Id: commercialeAdmin_form_contact_user.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
#}<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<table class="full_width">
		<tr>
			<th style="width:40%;">{{ STR_MODULE_COMMERCIAL_ADMIN_CONTACT_TITLE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<input type="hidden" name="id_utilisateur" value="{{ id_user|str_form_value }}" />
				<input type="hidden" name="mode" value="add_contact_planified" />
				<input name="form_edit_contact_user_id" type="hidden" value="{{ id_user|str_form_value }}" />
				<input name="form_contact_planified_date" size="27" id="contact_planified_date" type="text" class="form-control datepicker" value="" style="width:110px" />
			</td>
		</tr>
		<tr>
			<th>{{ STR_ADMIN_REASON }}</th>
			<td>
				<select class="form-control" name="form_contact_planified_reason">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="interesting_profile">{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTING_PROFILE }}</option>
					<option value="interested_by_product">{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTED_BY_PRODUCT }}</option>
					<option value="payment_expected">{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_PAYMENT_EXPECTED }}</option>
					<option value="follow_up">{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_FOLLOW_UP }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>{{ STR_COMMENTS }}</th>
			<td><textarea class="form-control" name="form_contact_planified_comment" rows="2" cols="28" id="contact_planified_comment"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="center"><br /><input name="contact_planified_submit" style="width:80px" type="submit" value="{{ STR_VALIDATE|str_form_value }}" class="btn btn-primary" /></td>
		</tr>
	</table>
</form>
{% if (rce) %}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}">
	<h2 class="entete">{{ STR_MODULE_COMMERCIAL_ADMIN_EDIT_PLANIFIED_CONTACT }} {{ id_contact_planified }}</h2>
	<table class="full_width">
		<tr>
			<th style="width:40%;">{{ STR_MODULE_COMMERCIAL_ADMIN_CONTACT_TITLE }}{{ STR_BEFORE_TWO_POINTS }}:</th>
			<td>
				<input type="hidden" name="mode" value="update_contact_planified" />
				<input name="form_edit_contact_planified_date" size="27" value="{{ rce.date|str_form_value }}" id="edit_contact_planified_date" type="text" class="form-control datepicker" style="width:110px" />
			</td>
		</tr>
		<tr>
			<th>{{ STR_ADMIN_REASON }}</th>
			<td>
				<select class="form-control" name="form_edit_contact_planified_reason">
					<option value="">{{ STR_CHOOSE }}...</option>
					<option value="interesting_profile"{% if rce.reason == 'interesting_profile' %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTING_PROFILE }}</option>
					<option value="interested_by_product"{% if rce.reason == 'interested_by_product' %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_INTERESTED_BY_PRODUCT }}</option>
					<option value="payment_expected"{% if rce.reason == 'payment_expected' %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_PAYMENT_EXPECTED }}</option>
					<option value="follow_up"{% if rce.reason == 'follow_up' %} selected="selected"{% endif %}>{{ STR_ADMIN_UTILISATEURS_FOLLOWED_REASON_FOLLOW_UP }}</option>
				</select>
			</td>
		</tr>
		<tr>
			<th>{{ STR_COMMENTS }}</th>
			<td><textarea class="form-control" name="form_edit_contact_planified_comment" rows="2" cols="28" id="contact_planified_comment" >{{ rce.comments }}</textarea></td>
		</tr>
		<tr>
			<td class="center" colspan="2">
				<input name="form_edit_contact_planified_id" type="hidden" value="{{ id_contact_planified|str_form_value }}" />
				<input name="edit_contact_planified_submit" style="width:80px" type="submit" value="{{ STR_VALIDATE|str_form_value }}" class="btn btn-primary" />
			</td>
		</tr>
	</table>
</form>
{% endif %}
{% if are_results %}
<form id="contact_planified" method="post" action="{{ modif_action }}">
	<table id="tablesForm" class="full_width">
		{{ links_header_row }}
		{% for res in results %}
		{{ res.tr_rollover }}
			<td style="width:30px; text-align:center;">
				<input type="hidden" name="mode" value="suppr_contact_planified" />
				<input name="form_delete_admins_contacts[]" type="checkbox" value="{{ res.id|str_form_value }}" id="cbx_{{ res.id }}" />
				<a href="{{ res.href|escape('html') }}"><img alt="edit" src="{{ edit_src|escape('html') }}" /></a>
			</td>
			<td style="width:100px;text-align:center;">{{ res.pseudo }}</td>
			<td style="width:100px;text-align:center;">{{ res.date }}</td>
			<td style="width:100px;text-align:center;">{{ res.reason }}</td>
			<td style="width:200px;text-align:center;">{{ res.comments }}</td>
		</tr>
		{% endfor %}
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="5" class="center">
				<input name="contact_planified_delete" type="submit" value="{{ STR_DELETE|str_form_value }}" class="btn btn-primary" />
			</td>
		</tr>
	</table>
</form>
{% endif %}