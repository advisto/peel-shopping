{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2018 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_import_form.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" name="categories" enctype="multipart/form-data">
 	{$form_token}
	<input type="hidden" name="action" value="import" />
	<input type="hidden" name="nomtable" value="peel_produits" />
	<table class="full_width">
		<tr><td class="entete">{$STR_ADMIN_IMPORT_FORM_TITLE}</td></tr>
		<tr>
			<td>
				<div class="alert alert-info">
					<b>{$STR_ADMIN_IMPORT_FILE_FORMAT}</b>{$STR_BEFORE_TWO_POINTS}: CSV
					<br />
					{$STR_ADMIN_IMPORT_FILE_FORMAT_EXPLAIN}<br />
					{$STR_ADMIN_IMPORT_FILE_EXAMPLE}{$STR_BEFORE_TWO_POINTS}: <a href="{$example_href|escape:'html'}" class="alert-link">exemple_prod.csv</a><br />
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<br />
				<div class="alert alert-info">
					<b>{$STR_WARNING}{$STR_BEFORE_TWO_POINTS}:</b><br />{$STR_ADMIN_IMPORT_EXPLAIN}
				</div>
			</td>
		</tr>
		<tr>
		  	<td class="center">
				<p class="alert alert-danger">{$STR_ADMIN_IMPORT_WARNING_ID}</p>
				<p>{$STR_ADMIN_IMPORT_FILE_NAME}{$STR_BEFORE_TWO_POINTS}: <input type="file" name="fichier" onchange="test_files(this)" /></p>
				<p>{$STR_ADMIN_IMPORT_FILE_ENCODING}{$STR_BEFORE_TWO_POINTS}: <select class="form-control" name="import_encoding" style="width: 150px">
						<option value="utf-8"{if $import_encoding == 'utf-8'} selected="selected"{/if}>UTF-8</option>
						<option value="iso-8859-1"{if $import_encoding == 'iso-8859-1'} selected="selected"{/if}>ISO 8859-1</option>
					</select></p>
				<p>{$STR_ADMIN_IMPORT_SEPARATOR}{$STR_BEFORE_TWO_POINTS}: <input style="width:50px" type="text" class="form-control" name="columns_separator" value="" /> ({$STR_ADMIN_IMPORT_SEPARATOR_EXPLAIN})</p>
				
			</td>
		</tr>
		<tr>
			<td>
				<h2>{$STR_ADMIN_IMPORT_TYPE}{$STR_BEFORE_TWO_POINTS}:</h2>
			</td>
		</tr>
		<tr>
			<td>
				<select name="type" class="form-control" onchange="change_import_type(this)">
					<option value=""> -- </option>
					{foreach $type_list as $type_key => $type}
						<option value="{$type_key}">{$type}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		{* Outil visuel d'attribution des colonnes *}
		{* TODO : récupérer les positions choisies par le client pour ensuite importer les colonnes dans le bon ordre *}
		{*<tr>
			<td>
				<h2>{$STR_ADMIN_IMPORT_CORRESPONDANCE}{$STR_BEFORE_TWO_POINTS}:</h2>
			</td>
		</tr>
		<tr>
			<td id="db_field_list">
				{foreach $type_fields as $type_key => $fields}
					<div style="display:none" class="fields_div" id="fields_{$type_key}">
						<table>
							{foreach $fields as $field_key => $field}
								<tr class="{$field.Field}">
									<td>{$field.Field}</td>
									<td>{$field.Type}</td>
									<td class="fields_{$type_key}_csv content_draggable"><span class="field_draggable" id="fields_{$type_key}_{$field_key}"></span></td>
								</tr>
							{/foreach}
						</table>
					</div>
				{/foreach}
			</td>
		</tr>*}
		<tr>
			<td>
				<h2>{$STR_ADMIN_IMPORT_IMPORT_MODE}{$STR_BEFORE_TWO_POINTS}:</h2>
			</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid black;">
				<p><input type="radio" name="type_import" value="all_fields" /> <label for="import">{$STR_ADMIN_IMPORT_IMPORT_ALL_FIELDS}</label></p>
			</td>
		</tr>
		<tr>
			<td style="border-bottom:1px solid black;">
				<p><input type="radio" name="type_import" value="chosen_fields" /> <label for="import">{$STR_ADMIN_IMPORT_IMPORT_SELECTED_FIELDS}</label></p>
				<p><label for="select">{$STR_ADMIN_IMPORT_SELECT_FIELDS}{$STR_BEFORE_TWO_POINTS}:</label></p>

				{foreach $inputs as $type_key => $inputs_list}
					<div style="display:none" class="fields_input_div" id="fields_input_{$type_key}">
						{foreach $inputs_list as $in}
						<input type="checkbox" name="on_update[{$type_key}][]" value="{$in.field|str_form_value}"{if $in.issel} checked="checked"{/if} /> {if $in.is_important}<b>{/if}{$in.field}{if !empty($in.explanation)}{$STR_BEFORE_TWO_POINTS}: {$in.explanation}{/if}{if $in.is_important}</b>{/if}<br />
						{/foreach}
					</div>
				{/foreach}
				<br />
			</td>
		</tr>
		<tr>
		  	<td class="center">
				<div id="email_users" class="hidden"><input type="checkbox" name="send_email" value="1" /> Envoyer des emails aux utilisateurs</div>
				<p><input type="submit" name="submit" value="{$STR_VALIDATE|str_form_value}" class="btn btn-primary" /></p>
			</td>
		</tr>
	 </table>
</form>
<script type="text/javascript">
	// Affiche la div de correspondance des champs en fonction du type d'import sélectionné
	function change_import_type(selectObject)
	{
		// Div correspondant aux champs drag&drop en mode tableau
		$('.fields_div').hide();
		// Découpe la valeur de l'objet : utile lorsqu'un import concerne plusieurs tables
		var type = selectObject.value.split('|');
		$.each(type, function( key, value ) {
			// Affiche div correspondant à la table sélectionnée
			var div_id = 'fields_'+value;
			$('#'+div_id).show();
			// Récupère la première ligne du fichier CSV
			var first_line;
			$.get($('input[name=fichier]').val(), function(data) {
				var lines = data.split("\n");
				first_line = lines[0].split(";");
				// Positionne les colonnes du csv téléchargé dans le tableau de correspondance
				$.each(first_line, function(keyfield, field) {
					$('.'+div_id+'_csv').eq(keyfield).children('span').attr('draggable', 'true').html(field);
				});
			});
		});

		// Div correspondant aux champs avec les input checkbox
		$('.fields_input_div').hide();
		// Découpe la valeur de l'objet : utile lorsqu'un import concerne plusieurs tables
		var type = selectObject.value.split('|');
		$.each(type, function( key, value ) {
			// Affiche div correspondant à la table sélectionnée
			var div_id = 'fields_input_'+value;
			$('#'+div_id).show();
			// Récupère la première ligne du fichier CSV
			var first_line;
			$.get($('input[name=fichier]').val(), function(data) {
				var lines = data.split("\n");
				first_line = lines[0].split(";");
				// Positionne les colonnes du csv téléchargé dans le tableau de correspondance
				$.each(first_line, function(keyfield, field) {
					$('.'+div_id+'_csv').eq(keyfield).children('span').attr('draggable', 'true').html(field);
				});
			});
		});
	}

</script>