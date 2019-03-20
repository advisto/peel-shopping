{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_export_form.tpl 59808 2019-02-18 13:57:06Z sdelaporte $
#}
<form class="entryform form-inline" role="form" method="post" action="{{ action|escape('html') }}" id="import_export_form" enctype="multipart/form-data">
 	{{ form_token }}
	<input type="hidden" name="mode" value="{{ next_mode }}" />
	<input type="hidden" id="correspondance" name="correspondance" value="" />
	{% if format == 'csv' %}
	<div class="row">
		<div class="col-sm-4">{{ STR_ADMIN_IMPORT_FILE_ENCODING }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col-sm-8"><select class="form-control" name="data_encoding" style="width: 150px">
				<option value="utf-8"{% if data_encoding == 'utf-8' %} selected="selected"{% endif %}>UTF-8</option>
				<option value="iso-8859-1"{% if data_encoding == 'iso-8859-1' %} selected="selected"{% endif %}>ISO 8859-1</option>
			</select></div>
	</div>
	<div class="row">
		<div class="col-sm-4">{{ STR_ADMIN_IMPORT_SEPARATOR }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col-sm-8"><input style="width:50px" type="text" class="form-control" name="separator" value="{{ separator }}"  id="separator"/> ({{ STR_ADMIN_EXPORT_SEPARATOR_EXPLAIN }})</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<input type="checkbox" name="header" {% if header %} checked="checked"{% endif %} value="1" /> {{ STR_ADMIN_COLUMN_TTTLE_FIRST_LINE }}
		</div>
	</div>
	{% for this_type in footer_optional_array %}
	<div id="footer_{{ this_type }}" class="fields_div" style="display:none;">
		<div class="row">
			<div class="col-sm-12">
				<input type="checkbox" name="footer" {% if footer %} checked="checked"{% endif %} value="1" /> {{ STR_ADMIN_ADD_FOOTER_FILE_EXPORT }}
			</div>
		</div>
	</div>
	{% endfor %}
	{% endif %}
	<h2>{{ STR_ADMIN_EXPORT_TYPE }}{{ STR_BEFORE_TWO_POINTS }}:</h2>
	<p><select name="type" class="form-control" id="import_export_type" onchange="change_export_type()">
		<option value="">{{ STR_CHOOSE }}...</option>
		{% for this_type,type in types_array %}
			<option value="{{ this_type }}" {% if selected_type == this_type %}selected="selected"{% endif %}>{{ type }}</option>
		{% endfor %}
		</select></p>
	{# Outil visuel d'attribution des colonnes #}
	
			<div class="row" id="fields_rules" style="display:none;">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-9 col-lg-9">
						<div class="pull-right" style="margin:5px">
							<table>
								<tr>
									<td style="padding:5px;">
										<div class="input-group">
											<select name="load_rule" class="form-control" id="load_rule">
												<option value=""> -- </option>
												{% for this_rule in rules_array %}
													<option value="{{ this_rule }}">{{ this_rule }}</option>
												{% endfor %}
											</select>
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
		<h2>{{ STR_ADMIN_EXPORT_COLUMNS }}{{ STR_BEFORE_TWO_POINTS }}:</h2>
		<div class="well">
			<div id="div_correspondance" class="collapse">
				<div class="row">
					{% for this_type,fields in inputs %}
					<div style="display:none" class="fields_div" id="fields_{{ this_type }}">
						<div class="col-sm-3" style="margin-right:20px">
							<table class="fields_table">
								<tr>
									<td><h3>{{ STR_ADMIN_COLUMN_AVAILABLE }}</h3></td>
								</tr>
								<tr>
									<td class="contains_draggable"><div style="padding:5px"><i>{{ STR_ADMIN_MOVE_COLUMN_WITH_DRAG_DROP_FOR_EXCLUDE }}{{ STR_BEFORE_TWO_POINTS }}:</i></div>
							{% for field_key,field in fields %}
								{% if field.selected %}
								<span class="field_draggable" id="filecol_{{ field.field }}" draggable="true"><span{% if field.explanation %} data-toggle="tooltip" title="{{ field.explanation|escape('html') }}"{% endif %}>{{ field.field }}</span><br /></span>
								{% endif %}
							{% endfor %}
									</td>
								</tr>
							</table>
						</div>
						<div class="col-sm-1">
							<div class="btn btn-default" onclick="move_draggable_fields('#fields_{{ this_type }} .contains_draggable', '#fields_{{ this_type }} .container_drop_draggable')">&gt;&gt;</div>
							<div class="btn btn-default" onclick="move_draggable_fields('#fields_{{ this_type }} .container_drop_draggable', '#fields_{{ this_type }} .contains_draggable')">&lt;&lt;</div>
						</div>
						<div class="col-sm-7"> 
							<table class="fields_table">
								<tr>
									<td colspan="3"><h3 style="margin-top: 10px;">{{ STR_ADMIN_GENERATE_FILE }}</h3></td>
								</tr>
								<tr class="">
									<td>{{ STR_ADMIN_FILE_COLUMN_EXPORTED }}{{ STR_BEFORE_TWO_POINTS }}:</td>
								</tr>
								<tr class="">
								<td id="fields_type_{{ this_type }}" class="container_drop_draggable">
						{% for field_key,field in fields %}
							{% if field.selected %}
							<span class="field_draggable" id="filecol_{{ field.field }}" draggable="true"><span{% if field.explanation %} data-toggle="tooltip" title="{{ field.explanation|escape('html') }}"{% endif %}>{{ field.field }}</span><br /></span>
							{% endif %}
						{% endfor %}
									</td>
								</tr>
							</table>
						</div>
					</div>
					{% endfor %}
				</div>
			</div>
			<div id="div_correspondance_explain">
				<p>{{ STR_ADMIN_SELECTED_COLUMN_FOR_EXPORT }}</p>
			</div>
		</div>
		{% if STR_ADMIN_EXPORT_PRODUCTS_CHOOSE_EXPORT_CRITERIA %}
		<div id="form_produits" style="display:none">
			<h2>{{ STR_ADMIN_EXPORT_PRODUCTS_CHOOSE_EXPORT_CRITERIA }}</h2>
			<div class="row">
				<div class="col-sm-6">{{ STR_ADMIN_SELECT_CATEGORIES_TO_EXPORT }}{{ STR_BEFORE_TWO_POINTS }}:</div>
				<div class="col-sm-6">
					<select class="form-control" name="categories[]" multiple="multiple" size="10">
						{{ product_categories }}
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6"></div>
				<div class="col-sm-6"><input type="checkbox" name="price_disable" value="1" /> {{ STR_ADMIN_EXPORT_PRICES_DISABLE }}</div>
			</div>
		</div>
		{% endif %}
	<div id="report_header_form"{% if format != 'html' and format != 'pdf' %} style="display:none"{% endif %}>
			<div class="row">
				<div class="col-sm-6">{{ STR_ADMIN_TEXT_HEADER_FOR_REPORT }}{{ STR_BEFORE_TWO_POINTS }}:</div>
				<div class="col-sm-6"><input type="text" name="report_header" value="" class="form-control" /></div>
			</div>
		</div>
	<div id="report_footer_form"{% if format != 'html' and format != 'pdf' %} style="display:none"{% endif %}>
			<div class="row">
			<div class="col-sm-6">{{ STR_ADMIN_TEXT_FOOTER_FOR_REPORT }}{{ STR_BEFORE_TWO_POINTS }}:</div>
			<div class="col-sm-6"><input type="text" name="report_footer" value="" class="form-control" /></div>
		</div>
	</div>
	<div id="page_bottom_form"{% if format != 'pdf' %} style="display:none"{% endif %}>
		<div class="row">
				<div class="col-sm-6">{{ STR_ADMIN_TEXT_FOR_PDF_EXPORT }}{{ STR_BEFORE_TWO_POINTS }}:</div>
				<div class="col-sm-6"><input type="text" name="page_bottom" value="" class="form-control" /></div>
			</div>
		</div>
		<div id="date_filter_form" style="display:none">
			{{ admin_date_filter_form }}
		</div>
		<br />
		<p class="center"><input type="submit" name="submit" value="{{ STR_VALIDATE|str_form_value }}" class="btn btn-primary" /></p>
</form>