{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2014 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: specific_field.tpl 43037 2014-10-29 12:01:40Z sdelaporte $
*}{if $f.field_type == "radio"}
	{foreach $f.options as $o}
		<input type="radio" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if} id="{$f.field_name|str_form_value}#{$o.value|str_form_value}" name="{$f.field_name}[]" /> <label for="{$f.field_name}#{$o.value|str_form_value}">{$o.name}</label><br />
	{/foreach}
{elseif $f.field_type == "checkbox"}
	{foreach $f.options as $o}
	<input type="checkbox" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if} id="{$f.field_name|str_form_value}#{$o.value|str_form_value}" name="{$f.field_name}[]" /> <label for="{$f.field_name}#{$o.value|str_form_value}">{$o.name}</label><br />
	{/foreach}
{elseif $f.field_type == "text"}
	{foreach $f.options as $o}
		<input type="text" value="{$o.value|str_form_value}" id="{$f.field_name}#{$o.value|str_form_value}" name="{$f.field_name}" class="form-control" /><br />
	{/foreach}
{elseif $f.field_type == "password"}
	{foreach $f.options as $o}
		<input type="password" id="{$f.field_name}" name="{$f.field_name}" class="form-control" value="" /><br />
	{/foreach}
{elseif $f.field_type == "datepicker"}
	{foreach $f.options as $o}
		<input type="text" value="{$o.value|str_form_value}" id="{$f.field_name}#{$o.value|str_form_value}" name="{$f.field_name}" class="form-control datepicker" /><br />
	{/foreach}
{elseif $f.field_type == "upload"}
	{foreach $f.options as $o}
		<input class="uploader" name="{$f.field_name}" type="file" value="" id="{$f.field_name}" /><br />
	{/foreach}
{elseif $f.field_type == "hidden"}
	{foreach $f.options as $o}
		<input name="{$f.field_name}" type="hidden" value="{$o.value|str_form_value}" id="{$f.field_name}" /><br />
	{/foreach}
{elseif $f.field_type == "textarea"}
	{foreach $f.options as $o}
		<textarea class="form-control" name="{$f.field_name}" id="{$f.field_name}">{$o.value}</textarea>
	{/foreach}
{elseif $f.field_type == "separator"}
	{* Ici on permet de mettre un séparateur à la place d'un champ. C'est pratique pour faire différent bloc dans un formulaire, avec un titre par bloc *}
	{foreach $f.options as $o}
		<{$o.name}>{$o.value}</{$o.name}>
	{/foreach}
{else}
<select class="form-control" id="{$f.field_name}" name="{$f.field_name}">
	<option value="">{$f.STR_CHOOSE}...</option>
	{foreach $f.options as $o}
	<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
	{/foreach}
</select>
{/if}