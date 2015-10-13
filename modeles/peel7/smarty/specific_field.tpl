{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: specific_field.tpl 47145 2015-10-04 11:56:35Z sdelaporte $
*}{if $f.field_type == "radio"}
	{foreach $f.options as $o}
<input type="radio" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if} id="{$f.field_name|str_form_value}#{$o.value|str_form_value}" name="{$f.field_name|str_form_value}" /> <label for="{$f.field_name}#{$o.value|str_form_value}">{$o.name}</label>
	{/foreach}
{elseif $f.field_type == "checkbox"}
	{foreach $f.options as $o}
<input type="checkbox" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if} id="{$f.field_name|str_form_value}#{$o.value|str_form_value}" name="{$f.field_name|str_form_value}[]" /> <label for="{$f.field_name}#{$o.value|str_form_value}">{$o.name}</label>
	{/foreach}
{elseif $f.field_type == "select"}
<select id="{$f.field_name|str_form_value}" name="{$f.field_name|str_form_value}" class="form-control">
	<option value="">{$f.STR_CHOOSE}...</option>
	{foreach $f.options as $o}
	<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
	{/foreach}
</select>
{elseif $f.field_type == "password"}
<input type="password" id="{$f.field_name|str_form_value}" name="{$f.field_name|str_form_value}" value="{$f.field_value|str_form_value}" class="form-control" />
{elseif $f.field_type == "datepicker"}
<input type="text" value="{$f.field_value|str_form_value}" id="{$f.field_name|str_form_value}#{$f.field_value|str_form_value}" name="{$f.field_name|str_form_value}" class="form-control datepicker" />
{elseif $f.field_type == "upload"}
	{if empty($f.upload_infos)}
		{if $site_parameters.used_uploader=="fineuploader"}
{if !empty($f.upload_file_display_title)}<div class="upload_file_field_title">{$f.field_title}</div>{/if}<div id="{$f.field_name|str_form_value}" class="uploader"></div>
		{else}
<input name="{$f.field_name|str_form_value}" type="file" value="" id="{$f.field_name|str_form_value}" />
		{/if}
	{else}
{include file="uploaded_file.tpl" f=$f.upload_infos STR_DELETE=$f.upload_infos.STR_DELETE_THIS_FILE}
	{/if}
{elseif $f.field_type == "hidden"}
<input name="{$f.field_name|str_form_value}" type="hidden" value="{$f.field_value|str_form_value}" id="{$f.field_name}" />
{elseif $f.field_type == "textarea"}
<textarea name="{$f.field_name|str_form_value}" id="{$f.field_name}"class="form-control">{$f.field_value}</textarea>
{elseif $f.field_type == "html"}
{$f.text_editor_html}
{elseif $f.field_type == "separator" || $f.field_type == "tag"}
{* Ici on permet de mettre du HTML. C'est pratique pour faire différents blocs dans un formulaire, avec un titre par bloc *}
{$f.field_value}
{elseif $f.field_type == "text" || empty($f.field_type)}
<input type="text" value="{$f.field_value|str_form_value}" id="{$f.field_name|str_form_value}" name="{$f.field_name|str_form_value}" class="form-control" />
{/if}