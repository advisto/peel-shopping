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
// $Id: footer.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="col-sm-{$footer_columns_width_sm} col-md-{$footer_columns_width_md} footer_col">
	<ul class="link">
{foreach $links as $l}
	<li><a href="{$l.href|escape:'html'}">{if $l.selected}<b>{/if}{$l.label}{if $l.selected}</b>{/if}</a></li>
{/foreach}
{$footer_additional_link}
{if isset($links_2)}
	</ul>
</div>
<div class="col-sm-{$footer_columns_width_sm} col-md-{$footer_columns_width_md} footer_col">
	<ul class="link">
	{foreach $links_2 as $l}
		<li><a href="{$l.href|escape:'html'}">{if $l.selected}<b>{/if}{$l.label}{if $l.selected}</b>{/if}</a></li>
	{/foreach}
{/if}
{if isset($facebook_page)}
	<li>{$facebook_page}</li>
{/if}
{if !empty($propulse)}
		<li class="li_separated">{$propulse} <a href="https://www.peel.fr/" onclick="return(window.open(this.href)?false:true);">{$STR_SITE_GENERATOR}</a></li>
{/if}
{if !empty($site)}<li{if empty($propulse)} class="li_separated"{/if}>&copy;{$site} {$date}</li>{/if}
	</ul>
</div>
{$footer_additional}