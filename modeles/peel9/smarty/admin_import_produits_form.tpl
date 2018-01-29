{* Smarty
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
// $Id: admin_import_produits_form.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" name="categories" enctype="multipart/form-data">
 	{$form_token}
	<input type="hidden" name="action" value="import" />
	<input type="hidden" name="nomtable" value="peel_produits" />
	<table class="full_width">
		<tr><td class="entete">{$STR_ADMIN_IMPORT_PRODUCTS_FORM_TITLE}</td></tr>
		<tr>
			<td>
				<div class="alert alert-info">
					<b>{$STR_ADMIN_IMPORT_PRODUCTS_FILE_FORMAT}</b>{$STR_BEFORE_TWO_POINTS}: CSV
					<br />
					{$STR_ADMIN_IMPORT_PRODUCTS_FILE_FORMAT_EXPLAIN}<br />
					{$STR_ADMIN_IMPORT_PRODUCTS_FILE_EXAMPLE}{$STR_BEFORE_TWO_POINTS}: <a href="{$example_href|escape:'html'}" class="alert-link">exemple_prod.csv</a><br />
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<h2>{$STR_ADMIN_IMPORT_PRODUCTS_IMPORT_MODE}{$STR_BEFORE_TWO_POINTS}:</h2>
			</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid black;">
				<p><input type="radio" name="type_import" value="all_fields" /> <label for="import">{$STR_ADMIN_IMPORT_PRODUCTS_IMPORT_ALL_FIELDS}</label></p>
			</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid black;">
				<p><input type="radio" name="type_import" value="chosen_fields" /> <label for="import">{$STR_ADMIN_IMPORT_PRODUCTS_IMPORT_SELECTED_FIELDS}</label></p>
				<p><label for="select">{$STR_ADMIN_IMPORT_PRODUCTS_SELECT_FIELDS}{$STR_BEFORE_TWO_POINTS}:</label></p>
				{foreach $inputs as $in}
				<input type="checkbox" name="on_update[]" value="{$in.field|str_form_value}"{if $in.issel} checked="checked"{/if} /> {if $in.is_important}<b>{/if}{$in.field}{if !empty($in.explanation)}{$STR_BEFORE_TWO_POINTS}: {$in.explanation}{/if}{if $in.is_important}</b>{/if}<br />
				{/foreach}
				<br />
			</td>
		</tr>
		<tr>
			<td>
				<br />
				<div class="alert alert-info">
					<b>{$STR_WARNING}{$STR_BEFORE_TWO_POINTS}:</b><br />{$STR_ADMIN_IMPORT_PRODUCTS_EXPLAIN}
				</div>
			</td>
		</tr>
		<tr>
		  	<td class="center">
				<p class="alert alert-danger">{$STR_ADMIN_IMPORT_PRODUCTS_WARNING_ID}</p>
				<p>{$STR_ADMIN_IMPORT_PRODUCTS_FILE_NAME}{$STR_BEFORE_TWO_POINTS}: <input type="file" name="fichier" /></p>
				<p>{$STR_ADMIN_IMPORT_PRODUCTS_FILE_ENCODING}{$STR_BEFORE_TWO_POINTS}: <select class="form-control" name="import_encoding" style="width: 150px">
						<option value="utf-8"{if $import_encoding == 'utf-8'} selected="selected"{/if}>UTF-8</option>
						<option value="iso-8859-1"{if $import_encoding == 'iso-8859-1'} selected="selected"{/if}>ISO 8859-1</option>
					</select></p>
				<p>{$STR_ADMIN_IMPORT_PRODUCTS_SEPARATOR}{$STR_BEFORE_TWO_POINTS}: <input style="width:50px" type="text" class="form-control" name="columns_separator" value="" /> ({$STR_ADMIN_IMPORT_PRODUCTS_SEPARATOR_EXPLAIN})</p>
				<p><input type="submit" name="submit" value="{$STR_VALIDATE|str_form_value}" class="btn btn-primary" /></p>
			</td>
		</tr>
	 </table>
</form>