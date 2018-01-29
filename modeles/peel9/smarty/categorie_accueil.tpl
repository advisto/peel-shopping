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
// $Id: categorie_accueil.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<h2 class="home_title">{$header}</h2>
<div class="row categorie_accueil">
{foreach $cats as $cat}
	{if isset($cat.href)}
		<div class="center col-md-{floor(12/$nb_col_md)} col-sm-{floor(12/$nb_col_sm)}">
			<p><a href="{$cat.href|escape:'html'}">{$cat.name|html_entity_decode_if_needed}</a></p>
		{if isset($cat.src)}
			<p><a href="{$cat.href|escape:'html'}"><img src="{$cat.src|escape:'html'}" alt="{$cat.name|html_entity_decode_if_needed}" /></a></p>
		{/if}
		</div>
	{/if}
	{if $cat.i%(12/floor(12/$nb_col_md))==0}
	<div class="clearfix visible-md visible-lg"></div>
	{/if}
	{if $cat.i%(12/floor(12/$nb_col_sm))==0}
	<div class="clearfix visible-sm"></div>
	{/if}
{/foreach}
</div>
<div class="clearfix"></div>
