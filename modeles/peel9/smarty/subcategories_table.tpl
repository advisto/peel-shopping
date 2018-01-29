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
// $Id: subcategories_table.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
*}<div class="sub_category row">
{foreach $cats as $cat}
	{if isset($cat.href)}
		<div class="center col-md-{floor(12/$nb_col_md)} col-sm-{floor(12/$nb_col_sm)}">
			<table class="subcategories_table">
				<tbody>
					{if isset($cat.src)}
					<tr>
						<td>
							<a href="{$cat.href|escape:'html'}"><img src="{$cat.src|escape:'html'}" alt="{$cat.name|html_entity_decode_if_needed|str_form_value}" /></a>
						</td>
					</tr>
					{/if}
					<tr class="subcategories_title_block">
						<td>
							<a href="{$cat.href|escape:'html'}" class="sub_category_title">
								{$cat.name|html_entity_decode_if_needed}
							</a>
						</td>
					</tr>
				</tbody>
			</table>
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