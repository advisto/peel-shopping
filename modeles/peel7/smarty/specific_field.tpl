{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: specific_field.tpl 39392 2013-12-20 11:08:42Z gboussin $
*}{if $f.field_type == "radio"}
	{foreach $f.options as $o}
		<input type="radio" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if} id="{$f.field_name}#{$o.value|str_form_value}" name="{$f.field_name}[]"/> <label for="{$f.field_name}#{$o.value|str_form_value}">{$o.name}</label><br />
	{/foreach}
{elseif $f.field_type == "checkbox"}
	{foreach $f.options as $o}
	<input type="checkbox" value="{$o.value|str_form_value}"{if $o.issel} checked="checked"{/if} id="{$f.field_name}#{$o.value|str_form_value}" name="{$f.field_name}[]" /> <label for="{$f.field_name}#{$o.value|str_form_value}">{$o.name}</label><br />
	{/foreach}
{else}
<select class="form-control" id="{$f.field_name}" name="{$f.field_name}">
	<option value="">{$f.STR_CHOOSE}...</option>
	{foreach $f.options as $o}
	<option value="{$o.value|str_form_value}"{if $o.issel} selected="selected"{/if}>{$o.name}</option>
	{/foreach}
</select>
{/if}