{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: subcategories_table.tpl 39162 2013-12-04 10:37:44Z gboussin $
*}<div class="sub_category row">
{foreach $cats as $cat}
	{if isset($cat.href)}
		<div class="center col-md-{floor(12/$nb_col_md)} col-sm-{floor(12/$nb_col_sm)}">
			{if isset($cat.src)}
				<a href="{$cat.href|escape:'html'}"><img src="{$cat.src|escape:'html'}" alt="{$cat.name|html_entity_decode_if_needed}" /></a>
				<br />
			{/if}
			<a href="{$cat.href|escape:'html'}" class="sub_category_title">{$cat.name|html_entity_decode_if_needed}</a>
		</div>
	{/if}
	{if $cat.i%$nb_col_md==0}
	<div class="clearfix visible-md visible-lg"></div>
	{/if}
	{if $cat.i%$nb_col_sm==0}
	<div class="clearfix visible-sm"></div>
	{/if}
{/foreach}
</div>