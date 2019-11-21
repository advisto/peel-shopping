{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_import_form.tpl 61970 2019-11-20 15:48:40Z sdelaporte $
*}{if $mode == 'import' && $general_configuration_is_valid}
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" id="import_export_form" enctype="multipart/form-data">
 	{$form_token}
	<h2>{if !empty($test_mode)}{$STR_ADMIN_CHECK_DATA}{else}{$STR_ADMIN_IMPORT_STATUS}{/if}</h2>
	{if $error}<div class="alert alert-danger"><p><b>{$STR_ADMIN_CHECK_DATA_BEFORE_IMPORT}{$STR_BEFORE_TWO_POINTS}:</b></p><br />{$error}</div>
	{else}<p>{$STR_FILE}{$STR_BEFORE_TWO_POINTS}: <a href="{$import_file.url|escape:'html'}">{$import_file.form_value}</a></p>{/if}
	{if $import_output}<div class="well">{$import_output}</div>{/if}
	{if !empty($test_mode)}
	<input type="hidden" name="type" value="{$type}" />
		{if !empty($import_file)}
	<input type="hidden" name="import_file" value="{$import_file.form_value}" />
		{/if}
	<input type="hidden" name="correspondance" value="{$correspondance}" />
	<input type="hidden" name="default_fields" value="{$default_fields}" />
		{foreach $defaults as $this_key => $this_value}
	<input type="hidden" name="{$this_key}" value="{$this_value}" />
		{/foreach}			
	<input type="hidden" name="separator" value="{$separator}" />
	<input type="hidden" name="data_encoding" value="{$data_encoding}" />
		{if empty($error)}
	<input type="hidden" name="mode" value="import" />
	<input type="hidden" name="test_mode" value="0" />
	<p class="center"><input type="submit" name="submit" value="{$STR_VALIDATE|str_form_value}" class="btn btn-primary" /></p>
		{else}
	<input type="hidden" name="mode" value="" />
	<p class="center"><input type="submit" name="submit" value="{$STR_BACK|str_form_value}" class="btn btn-danger" /></p>
		{/if}
	{/if}
</form>
{else}{if $error}{include file="global_error.tpl" text=$error}{/if}
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" id="import_export_form" enctype="multipart/form-data">
 	{$form_token}
	<input type="hidden" name="mode" value="{$next_mode}" />
	<input type="hidden" name="test_mode" value="1" />
	<input type="hidden" id="correspondance_type" name="correspondance_type" value="{$type}" />
	<input type="hidden" id="correspondance" name="correspondance" value="{$correspondance}" />
	<input type="hidden" id="default_fields" name="default_fields" value="{$default_fields}" />
	<div>
		<div class="entete">{$STR_ADMIN_IMPORT_FORM_TITLE}</div>
		<div class="alert alert-info">
			<b>{$STR_ADMIN_IMPORT_FILE_FORMAT}</b>{$STR_BEFORE_TWO_POINTS}: CSV
			<br />
			{$STR_ADMIN_IMPORT_FILE_FORMAT_EXPLAIN}<br />
			{$STR_ADMIN_IMPORT_FILE_EXAMPLE}{$STR_BEFORE_TWO_POINTS}: <a href="{$example_href|escape:'html'}" class="alert-link">exemple.csv</a><br />
			<br />
			<b>{$STR_WARNING}{$STR_BEFORE_TWO_POINTS}:</b><br />{$STR_ADMIN_IMPORT_EXPLAIN}
		</div>
		<p class="alert alert-warning">{$STR_ADMIN_IMPORT_WARNING_ID}</p>
	</div>

	<h2>{$STR_ADMIN_IMPORT_FILE_NAME}{$STR_BEFORE_TWO_POINTS}:</h2>
	<div class="center">
		{if !empty($import_file)}
			{include file="uploaded_file.tpl" f=$import_file STR_DELETE=$STR_DELETE_THIS_FILE}
		{else}
			<input name="import_file" type="file" value="" />
		{/if}
		<p>{$STR_ADMIN_IMPORT_FILE_ENCODING}{$STR_BEFORE_TWO_POINTS}: <select class="form-control" name="data_encoding" style="width: 150px">
				<option value="utf-8"{if $data_encoding == 'utf-8'} selected="selected"{/if}>UTF-8</option>
				<option value="iso-8859-1"{if $data_encoding == 'iso-8859-1'} selected="selected"{/if}>ISO 8859-1</option>
			</select></p>
		<p>{$STR_ADMIN_IMPORT_SEPARATOR}{$STR_BEFORE_TWO_POINTS}: <input style="width:50px" type="text" id="separator" class="form-control" name="separator" value="{$separator}" /> ({$STR_ADMIN_IMPORT_SEPARATOR_EXPLAIN})</p>

	</div>
	<h2>{$STR_ADMIN_IMPORT_TYPE}{$STR_BEFORE_TWO_POINTS}:</h2>
	<div>
		<select name="type" class="form-control" id="import_export_type" onchange="change_import_type()" {$type_disabled}>
			<option value=""> -- </option>
			{foreach $types_array as $this_type => $this_title}
				<option value="{$this_type}" {if $type == $this_type}selected="selected"{/if}>{$this_title}</option>
			{/foreach}
		</select>

		<div class="row" id="fields_rules" style="display:none;">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-sm-9 col-lg-9">
						<div class="pull-right" style="margin:5px">
							<table>
								<tr>
									<td style="padding:5px;">
										<div class="input-group">
											<div id="load_rule_container">
												<select name="load_rule" class="form-control" id="load_rule">
													<option value=""> -- </option>
													{foreach $rules_array as $this_rule}
														<option value="{$this_rule}">{$this_rule}</option>
													{/foreach}
												</select>
											</div>
											<div class="input-group-btn">
												<a href="#" onclick="return false;" class="btn btn-primary" data-target="basic" id="rules_get">{$STR_LOAD_RULES}</a>
												<a href="#" onclick="return false;" class="btn btn-danger" data-target="basic" id="rules_delete">{$STR_DELETE}</a>
											</div>
										</div>
									</td>
									<td style="padding:5px;">
										<div class="input-group">
											<input type="text" id="rule_name" name="rule_name" class="form-control"/>
											<span class="input-group-btn">
												<a href="#" onclick="return false;" class="btn btn-success" data-target="basic" id="rules_set">{$STR_SAVE_RULES}</a>
											</span>
										</div>
									</td>
									<td style="padding:5px;">
										<a href="#" onclick="return false;" class="btn btn-warning" data-target="basic" id="rules_reset">{$STR_INIT_FILTER}</a>
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
	<h2>{$STR_ADMIN_IMPORT_CORRESPONDANCE}{$STR_BEFORE_TWO_POINTS}:</h2>
	<div class="well">
		<div id="div_correspondance" class="collapse">
			<div class="row">
				<div class="col-sm-3" style="margin-right:20px">
					<table class="fields_table">
						<tr>
							<td><h3 class="center" style="margin-top: 10px;">{$STR_ADMIN_SOURCE_FILE}</h3></td>
						</tr>
						<tr>
							<td class="contains_draggable"><div style="padding:5px"><i>{$STR_ADMIN_MOVE_COLUMN_WITH_DRAG_DROP}{$STR_BEFORE_TWO_POINTS}:</i></div></td>
						</tr>
					</table>
				</div>
				{foreach $inputs as $this_type => $fields}
				<div style="display:none" class="fields_div" id="fields_{$this_type}">
					<div class="col-sm-1">
						<div class="btn btn-default" onclick="move_draggable_fields('.contains_draggable', '#fields_{$this_type} .container_drop_draggable', '#fields_{$this_type}')">&gt;&gt;</div>
						<div class="btn btn-default" onclick="move_draggable_fields('#fields_{$this_type} .container_drop_draggable', '.contains_draggable')">&lt;&lt;</div>
					</div>
					<div class="col-sm-7">
						<table class="fields_table">
							<tr>
								<td colspan="4"><h3 class="center" style="margin-top: 10px;">{$site_name}</h3></td>
							</tr>
							<tr>
								<td class="center">{$STR_ADMIN_SITE_COLUMN_IN_DATABASE}</td>
								<td class="center">{$STR_ADMIN_TYPE}</td>
								<td class="center">{$STR_ADMIN_IMPORTED_COLUMN}</td>
								<td class="center">{$STR_ADMIN_DEFAULT_VALUE}</td>
							</tr>
					{foreach $fields as $field_key => $field}
							<tr class="{if $field.primary}bg-primary{else}{if $field.required}bg-info{/if}{/if}">
								<td><span{if !empty($field.explanation)} data-toggle="tooltip" title="{$field.explanation|escape:'html'}"{/if}>{$field.field}{if $field.primary} **{else}{if $field.required} *{/if}{/if}</span></td>
								<td>{$field.type}</td>
								<td id="fields_{$this_type}_{$field.field}" class="container_drop_draggable"></td>
								<td><input type="text" id="default_{$this_type}_{$field.field}" name="default_{$this_type}_{$field.field}" value="{$field.default}" class="form-control"{if !empty($field.maxlength)} maxlength="{$field.maxlength}{/if}" /></td>
							</tr>
					{/foreach}
						</table>
					</div>
				</div>
				{/foreach}
			</div>
			<br /><i>{$STR_ADMIN_IMPORT_MANDATORY_FIELD_INFORMATION_MESSAGE}</i>
		</div>
		<div id="div_correspondance_explain">
			<p>{$STR_ADMIN_CORRESPONDANCE_COLUMN_FILE_AND_SITE}</p>
		</div>
	</div>
	<div class="center">
		<br />
		<div id="email_users" class="hidden"><input type="checkbox" name="send_email" value="1" /> {$STR_ADMIN_SEND_EMAIL_TO_USERS}</div>
		<p><input type="submit" name="submit" value="{$STR_VALIDATE|str_form_value}" class="btn btn-primary" /></p>
	</div>
</form>
{/if}