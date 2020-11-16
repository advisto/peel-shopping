{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2020 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.3.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_import_form.tpl 59808 2019-02-18 13:57:06Z sdelaporte $
#}{% if mode == 'import' and general_configuration_is_valid %}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" id="import_export_form" enctype="multipart/form-data">
 	{{ form_token }}
	<h2>{% if test_mode %}{{ STR_ADMIN_CHECK_DATA }}{% else %}{{ STR_ADMIN_IMPORT_STATUS }}{% endif %}</h2>
	{% if error %}<div class="alert alert-danger"><p><b>{{ STR_ADMIN_CHECK_DATA_BEFORE_IMPORT }}{{ STR_BEFORE_TWO_POINTS }}:</b></p><br />{{ error }}</div>
	{% else %}<p>{{ STR_FILE }}{{ STR_BEFORE_TWO_POINTS }}: <a href="{{ import_file.url|escape('html') }}">{{ import_file.form_value }}</a></p>{% endif %}
	{% if import_output %}<div class="well">{{ import_output }}</div>{% endif %}
	{% if test_mode %}
	<input type="hidden" name="type" value="{{ type }}" />
		{% if import_file %}
	<input type="hidden" name="import_file" value="{{ import_file.form_value }}" />
		{% endif %}
	<input type="hidden" name="correspondance" value="{{ correspondance }}" />
	<input type="hidden" name="default_fields" value="{{ default_fields }}" />
		{% for this_key,this_value in defaults %}
	<input type="hidden" name="{{ this_key }}" value="{{ this_value }}" />
		{% endfor %}			
	<input type="hidden" name="separator" value="{{ separator }}" />
	<input type="hidden" name="data_encoding" value="{{ data_encoding }}" />
		{% if error is empty %}
	<input type="hidden" name="mode" value="import" />
	<input type="hidden" name="test_mode" value="0" />
	<p class="center"><input type="submit" name="submit" value="{{ STR_VALIDATE|str_form_value }}" class="btn btn-primary" /></p>
		{% else %}
	<input type="hidden" name="mode" value="" />
	<p class="center"><input type="submit" name="submit" value="{{ STR_BACK|str_form_value }}" class="btn btn-danger" /></p>
		{% endif %}
	{% endif %}
</form>
{% else %}{% if error %}{% include "global_error.tpl" with {'text':error} %}{% endif %}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" id="import_export_form" enctype="multipart/form-data">
 	{{ form_token }}
	<input type="hidden" name="mode" value="{{ next_mode }}" />
	<input type="hidden" name="test_mode" value="1" />
	<input type="hidden" id="correspondance_type" name="correspondance_type" value="{{ type }}" />
	<input type="hidden" id="correspondance" name="correspondance" value="{{ correspondance }}" />
	<input type="hidden" id="default_fields" name="default_fields" value="{{ default_fields }}" />
	<div>
		<div class="entete">{{ STR_ADMIN_IMPORT_FORM_TITLE }}</div>
		<div class="alert alert-info">
			<b>{{ STR_ADMIN_IMPORT_FILE_FORMAT }}</b>{{ STR_BEFORE_TWO_POINTS }}: CSV
			<br />
			{{ STR_ADMIN_IMPORT_FILE_FORMAT_EXPLAIN }}<br />
			{{ STR_ADMIN_IMPORT_FILE_EXAMPLE }}{{ STR_BEFORE_TWO_POINTS }}: <a href="{{ example_href|escape('html') }}" class="alert-link">exemple.csv</a><br />
			<br />
			<b>{{ STR_WARNING }}{{ STR_BEFORE_TWO_POINTS }}:</b><br />{{ STR_ADMIN_IMPORT_EXPLAIN }}
		</div>
		<p class="alert alert-warning">{{ STR_ADMIN_IMPORT_WARNING_ID }}</p>
	</div>

	<h2>{{ STR_ADMIN_IMPORT_FILE_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</h2>
	<div class="center">
		<p>{{ STR_ADMIN_IMPORT_FILE_ENCODING }}{{ STR_BEFORE_TWO_POINTS }}: <select class="form-control" name="data_encoding" style="width: 150px">
				<option value="utf-8"{% if data_encoding == 'utf-8' %} selected="selected"{% endif %}>UTF-8</option>
				<option value="iso-8859-1"{% if data_encoding == 'iso-8859-1' %} selected="selected"{% endif %}>ISO 8859-1</option>
			</select></p>
		<p>{{ STR_ADMIN_IMPORT_SEPARATOR }}{{ STR_BEFORE_TWO_POINTS }}: <input style="width:50px" type="text" id="separator" class="form-control" name="separator" value="{{ separator }}" /> ({{ STR_ADMIN_IMPORT_SEPARATOR_EXPLAIN }})</p>

		{% if import_file %}
			{% include "uploaded_file.tpl" with {'f':import_file, 'STR_DELETE':STR_DELETE_THIS_FILE} %}
		{% else %}
			<input name="import_file" type="file" value="" />
		{% endif %}

	</div>
	<h2>{{ STR_ADMIN_IMPORT_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</h2>
	<div>
		<select name="type" class="form-control" id="import_export_type" onchange="change_import_type()" {{ type_disabled }}>
			<option value=""> -- </option>
			{% for this_type,this_title in types_array %}
				<option value="{{ this_type }}" {% if type == this_type %}selected="selected"{% endif %}>{{ this_title }}</option>
			{% endfor %}
		</select>

		<div class="row" id="fields_rules" style="display:none;">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-9 col-lg-9">
						<h2>{{ STR_ADMIN_IMPORT_SAVE_IMPORT_PARAMS }}{{ STR_BEFORE_TWO_POINTS }}:</h2>
						<div class="pull-right" style="margin:5px">
							<table>
								<tr>
									<td style="padding:5px;">
										<div class="input-group">
											<div id="load_rule_container">
												<select name="load_rule" class="form-control" id="load_rule">
													<option value=""> -- </option>
													{% for this_rule in rules_array %}
														<option value="{{ this_rule }}">{{ this_rule }}</option>
													{% endfor %}
												</select>
											</div>
											  <div class="input-group-btn">
												<a href="#" onclick="return false;" class="btn btn-primary" data-target="basic" id="rules_get">{{ STR_LOAD_RULES }}</a>
											</div>
										</div>
									</td>
									<td style="padding:5px;">
										<div class="input-group">
											<input type="text" id="rule_name" name="rule_name" class="form-control"/>
											<span class="input-group-btn">
												<a href="#" onclick="return false;" class="btn btn-success" data-target="basic" id="rules_set">{{ STR_SAVE_RULES }}</a>
												<a href="#" onclick="return false;" class="btn btn-danger" data-target="basic" id="rules_delete">{{ STR_DELETE }}</a>
											</span>
										</div>
									</td>
									<td style="padding:5px;">
										<a href="#" onclick="return false;" class="btn btn-warning" data-target="basic" id="rules_reset">{{ STR_INIT_FILTER }}</a>
									</td>
								  </tr>
							  </table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<br />
	</div>
	<h2>{{ STR_ADMIN_IMPORT_CORRESPONDANCE }}{{ STR_BEFORE_TWO_POINTS }}:</h2>
	<div class="alert alert-info">{{ STR_ADMIN_IMPORT_CORRESPONDANCE_EXPLANATION }}</div>
	<div class="well">
		<div id="div_correspondance" class="collapse">
			<div class="row">
				<div class="col-sm-3" style="margin-right:20px">
					<table class="fields_table">
						<tr>
							<td><h3 class="center" style="margin-top: 10px;">{{ STR_ADMIN_SOURCE_FILE }}</h3></td>
						</tr>
						<tr>
							<td class="contains_draggable"><div style="padding:5px"><i>{{ STR_ADMIN_MOVE_COLUMN_WITH_DRAG_DROP }}{{ STR_BEFORE_TWO_POINTS }}:</i></div></td>
						</tr>
					</table>
				</div>
				{% for this_type,fields in inputs %}
				<div id="fields_{{ this_type }}" class="div_hidden_by_default">
					<div class="col-sm-1">
						<div class="btn btn-default" onclick="move_draggable_fields('.contains_draggable', '#fields_{{ this_type }} .container_drop_draggable', '#fields_{{ this_type }}')">&gt;&gt;</div>
						<div class="btn btn-default" onclick="move_draggable_fields('#fields_{{ this_type }} .container_drop_draggable', '.contains_draggable')">&lt;&lt;</div>
					</div>
					<div class="col-sm-7">
						<table class="fields_table">
							<tr>
								<td colspan="4"><h3 class="center" style="margin-top: 10px;">{{ site_name }}</h3></td>
							</tr>
							<tr>
								<td class="center">{{ STR_ADMIN_SITE_COLUMN_IN_DATABASE }}</td>
								<td class="center">{{ STR_ADMIN_TYPE }}</td>
								<td class="center">{{ STR_ADMIN_IMPORTED_COLUMN }}</td>
								<td class="center">{{ STR_ADMIN_DEFAULT_VALUE }}</td>
							</tr>
					{% for field_key,field in fields %}
							<tr class="{% if field.primary %}bg-primary{% else %}{% if field.required %}bg-info{% endif %}{% endif %}">
								<td><span{% if field.explanation %} data-toggle="tooltip" title="{{ field.explanation|escape('html') }}"{% endif %}>{{ field.field_title }}{% if field.primary %} **{% else %}{% if field.required %} *{% endif %}{% endif %}</span></td>
								<td>{{ field.type }}</td>
								<td id="fields_{{ this_type }}_{{ field.field }}" class="container_drop_draggable"></td>
								<td><input type="text" id="default_{{ this_type }}_{{ field.field }}" name="default_{{ this_type }}_{{ field.field }}" value="{{ field.default }}" class="form-control"{% if field.maxlength %} maxlength="{{ field.maxlength }}"{% endif %} /></td>
							</tr>
					{% endfor %}
						</table>
					</div>
				</div>
				{% endfor %}
			</div>
			<br /><i>{{ STR_ADMIN_IMPORT_MANDATORY_FIELD_INFORMATION_MESSAGE }}</i>
		</div>
		<div id="div_correspondance_explain">
			<p>{{ STR_ADMIN_CORRESPONDANCE_COLUMN_FILE_AND_SITE }}</p>
		</div>
	</div>
	<div class="center">
		<br />
		<div id="email_users" class="hidden"><input type="checkbox" name="send_email" value="1" /> {{ STR_ADMIN_SEND_EMAIL_TO_USERS }}</div>
		<p><input type="submit" name="submit" value="{{ STR_VALIDATE|str_form_value }}" class="btn btn-primary" /></p>
	</div>
</form>
{% endif %}