{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2016 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: uploaded_file.tpl 48447 2016-01-11 08:40:08Z sdelaporte $
*}
<div {if isset($f.div_id)}id="{$f.div_id|str_form_value}"{elseif isset($f.form_name)}id="{$f.form_name|str_form_value}"{/if}>
{if !empty($f.url)}
	{if $f.type != 'image'}
	<a href="{$f.url|escape:'html'}" onclick="return(window.open(this.href)?false:true);"><img src="{$f.file_logo_src|escape:'html'}" alt="" style="max-width: 100px; max-height: 100px" /></a>
	{else}
	<img src="{$f.url|escape:'html'}" alt=""{if empty($f.crop)} style="max-height:100px"{else} style="max-width:300px"{/if}{if !empty($f.class)} class="{$f.class}"{/if} />
	{/if}
	<br />
	{$f.name}&nbsp;
	<a href="{$f.drop_href|escape:'html'}"><img src="{$f.drop_src|escape:'html'}" width="16" height="16" alt="" />{$STR_DELETE}</a>
	{if isset($f.form_name)}<input type="hidden" name="{$f.form_name|str_form_value}" value="{$f.form_value|str_form_value}" />{/if}
{elseif $site_parameters.used_uploader=="fineuploader"}
	<div {if isset($f.form_name)}id="{$f.form_name|str_form_value}"{/if} class="uploader"></div>
{elseif isset($f.form_name)}
	<input name="{$f.form_name|str_form_value}" type="file" value="" />
{/if}
</div>