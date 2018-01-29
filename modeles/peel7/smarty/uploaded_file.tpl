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
// $Id: uploaded_file.tpl 55930 2018-01-29 08:36:36Z sdelaporte $
*}
{if !empty($f.url)}
<div {if isset($f.div_id)}id="{$f.div_id|str_form_value}"{elseif isset($f.form_name)}id="{$f.form_name|replace:'[':'_openarray_'|replace:']':'_closearray_'|str_form_value}"{/if}>
	{if $f.type != 'image'}
	<a href="{$f.url|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$f.file_logo_src|escape:'html'}" alt="" style="max-width: 100px; max-height: 100px" /></a>
	{elseif !empty($f.download_picture)}
	<a href="{$f.url|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$f.url|escape:'html'}" alt=""{if empty($f.crop)} style="max-height:100px"{else} style="max-width:300px"{/if}{if !empty($f.class)} class="{$f.class}"{/if} /></a>
	{else}
	<img src="{$f.url|escape:'html'}" alt=""{if empty($f.crop)} style="max-height:100px"{else} style="max-width:300px"{/if}{if !empty($f.class)} class="{$f.class}"{/if} />
	{/if}
	<br />
	{$f.name}&nbsp;
	{if empty($f.read_only)}
		<a href="{$f.drop_href|escape:'html'}"><img src="{$f.drop_src|escape:'html'}" width="16" height="16" alt="" />{$STR_DELETE}</a>
	{/if}
	{if isset($f.form_name)}<input type="hidden" name="{$f.form_name|replace:'_openarray_':'['|replace:'_closearray_':']'|str_form_value}" value="{$f.form_value|str_form_value}" />{/if}
</div>
{elseif $site_parameters.used_uploader=="fineuploader"}
<div {if isset($f.div_id)}id="{$f.div_id|str_form_value}"{elseif isset($f.form_name)}id="{$f.form_name|replace:'[':'_openarray_'|replace:']':'_closearray_'|str_form_value}"{/if}{if isset($f.form_name)} data-name="{$f.form_name|replace:'[':'_openarray_'|replace:']':'_closearray_'|str_form_value}"{/if} class="uploader"></div>
{elseif isset($f.form_name)}
<div {if isset($f.div_id)}id="{$f.div_id|str_form_value}"{elseif isset($f.form_name)}id="{$f.form_name|replace:'[':'_openarray_'|replace:']':'_closearray_'|str_form_value}"{/if}>
	<input name="{$f.form_name|str_form_value}" type="file" value="" />
</div>
{/if}