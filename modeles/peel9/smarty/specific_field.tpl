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
// $Id: specific_field.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}{if !empty($text_only) && $f.field_type != "upload"}
	{$f.field_value}
{elseif $f.field_type == "radio"}
	{if !empty($f.options)}
		{foreach $f.options as $o}
<input {if !empty($f.readonly) && empty($o.issel)} readonly="readonly"{/if} type="radio" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if} {if !empty($disabled)} disabled="disabled"{/if} id="{$f.field_id|str_form_value}#{$o.value|str_form_value}" name="{$f.field_name|str_form_value}" /> <label for="{$f.field_name}#{$o.value|str_form_value}">{$o.name}</label>{if !empty($o.br)}<br />{/if}
		{/foreach}
	{/if}
{elseif $f.field_type == "checkbox"}
	{if !empty($f.options)}
		{foreach $f.options as $o}
<input {if !empty($f.readonly)  && empty($o.issel)} readonly="readonly"{/if} type="checkbox" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if}{if !empty($disabled)} disabled="disabled"{/if} id="{$f.field_id|str_form_value}#{$o.value|str_form_value}" name="{$f.field_name|str_form_value}[]" /> <label for="{$f.field_name}#{$o.value|str_form_value}">{$o.name}</label>{if !empty($o.br)}<br />{/if}
		{/foreach}
	{/if}
{elseif $f.field_type == "select"}
<select {if !empty($f.multiple)} multiple="multiple" size="5" name="{$f.field_name|str_form_value}[]" {else} name="{$f.field_name|str_form_value}" {/if} {if !empty($f.readonly)} readonly="readonly"{/if} id="{$f.field_id|str_form_value}" {if !empty($disabled)} disabled="disabled"{/if} class="form-control" onchange="{$f.javascript|str_form_value}">
	{if $f.options|@count>1 && empty($f.readonly)}
	<option value="">{$f.STR_CHOOSE}...</option>
	{/if}
	{if !empty($f.options)}
		{foreach $f.options as $o}
	<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if} {if empty($o.issel) && !empty($f.readonly)}disabled="disabled"{/if}>{$o.name}</option>
		{/foreach}
	{/if}
</select>
{elseif $f.field_type == "password"}
<input {if !empty($disabled)} disabled="disabled"{/if} type="password" id="{$f.field_id|str_form_value}" name="{$f.field_name|str_form_value}" value="{$f.field_value|str_form_value}" class="form-control" />
{elseif $f.field_type == "number"}
<input {if !empty($disabled)} disabled="disabled"{/if} type="number" step="any" id="{$f.field_id|str_form_value}" name="{$f.field_name|str_form_value}" value="{$f.field_value|str_form_value}" class="form-control" />
{elseif $f.field_type == "datepicker"}
<input {if !empty($disabled)} disabled="disabled"{/if} type="text" value="{$f.field_value|str_form_value}" id="{$f.field_id|str_form_value}#{$f.field_value|str_form_value}" name="{$f.field_name|str_form_value}" class="form-control datepicker" />
{elseif $f.field_type == "upload"}
	{if empty($f.upload_infos)}
		{if $site_parameters.used_uploader=="fineuploader"}
{if !empty($f.upload_file_display_title)}<div class="upload_file_field_title">{$f.field_title}</div>{/if}<div id="{$f.field_id|replace:'[':'_openarray_'|replace:']':'_closearray_'|str_form_value}" data-name="{$f.field_name|replace:'[':'_openarray_'|replace:']':'_closearray_'|str_form_value}" class="uploader"></div>
		{else}
<input name="{$f.field_name|str_form_value}" type="file" value="" id="{$f.field_id|replace:'[':'_openarray_'|replace:']':'_closearray_'|str_form_value}" />
		{/if}
	{else}
{include file="uploaded_file.tpl" f=$f.upload_infos STR_DELETE=$f.upload_infos.STR_DELETE_THIS_FILE}
	{/if}
{elseif $f.field_type == "hidden"}
<input name="{$f.field_name|str_form_value}" type="hidden" value="{$f.field_value|str_form_value}" id="{$f.field_id}" />
{elseif $f.field_type == "textarea"}
<textarea rows="4" {if !empty($f.readonly)} readonly="readonly"{/if} name="{$f.field_name|str_form_value}" id="{$f.field_id}" class="form-control"{if !empty($f.field_placeholder)} placeholder="{$f.field_placeholder|str_form_value}"{/if}>{$f.field_value}</textarea>
{elseif $f.field_type == "html"}
{$f.text_editor_html}
{elseif $f.field_type == "separator" || $f.field_type == "tag"}
{* Ici on permet de mettre du HTML. C'est pratique pour faire différents blocs dans un formulaire, avec un titre par bloc *}
{$f.field_value}
{elseif $f.field_type == "text" || empty($f.field_type)}
<input {if !empty($disabled)} disabled="disabled"{/if} {if !empty($f.readonly)} readonly="readonly"{/if} type="text" value="{$f.field_value|str_form_value}" id="{$f.field_id|str_form_value}" name="{$f.field_name|str_form_value}" class="form-control" {if $f.javascript} onkeyup="{$f.javascript|str_form_value}" onchange="{$f.javascript|str_form_value}" onclick="{$f.javascript|str_form_value}" data-onload="{$f.javascript|str_form_value}" {/if}{if !empty($f.field_maxlength)} maxlength="{$f.field_maxlength|str_form_value}"{/if}{if !empty($f.field_placeholder)} placeholder="{$f.field_placeholder|str_form_value}"{/if} />
{/if}