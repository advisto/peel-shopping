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
// $Id: attributs_form_part.tpl 43198 2014-11-13 18:33:53Z sdelaporte $
*}
{* On renvoie le formulaire sous forme de table ou de HTML simple *}
{if $display_mode=='table'}
<table class="attributs_form_part">
{/if}
{foreach $attributes_text_array as $a}
	{if $display_mode=='selected_text'}
		{* On renvoie le texte des attributs sélectionnés *}
		{if !empty($a.options)}
			{$a.name}{$STR_BEFORE_TWO_POINTS}: {foreach $a.options as $o}{if $o.issel}{$o.text} {/if}{/foreach}<br />
		{else}
			{$a.name}{$STR_BEFORE_TWO_POINTS}: {$a.input_value}<br />
		{/if}
	{else}
		{if $display_mode=='table'}
	<tr>
		<td class="attribut-cell">
		{/if}
		{if $a.input_type!='radio' && $a.input_type!='checkbox'}<label for="{$a.input_id}">{if $a.name=='Auteur'}<h3 class='auteur_page_produit'>{/if}{$a.name}{$STR_BEFORE_TWO_POINTS}:{if $a.name=='Auteur'}</h3>{/if}</label>{else}{$a.name}{/if}
		{if $a.input_type=='select'}
			<select id="{$a.input_id}" name="{$a.input_name}" onchange="{$a.onchange}" class="form-control{if $a.input_class} {$a.input_class}{/if}">
			{foreach $a.options as $o}	
				<option value="{$o.value}" {if $o.issel} selected="selected"{/if}>{$o.text}</option>
			{/foreach}
			</select>
		{elseif $a.input_type=='radio' || $a.input_type=='checkbox'}
			{foreach $a.options as $o}
			{if $a.max_label_length>=5}<br />{/if}<input type="{$a.input_type}" value="{$o.value}" id="{$o.id}" name="{$o.name}" {if $o.issel} checked="checked"{/if} onclick="{$o.onclick}" class="{if $a.input_class} {$a.input_class}{/if}" /> <label for="{$o.id}">{$o.text}</label>
			{/foreach}
		{elseif $a.input_type=='link'}
			{foreach $a.options as $o}
				<a href="{$wwwroot}/search.php?{$o.name}={$o.value}">{$o.text}</a>
			{/foreach}
		{elseif $a.input_type}
			<input id="{$a.input_id}" type="{$a.input_type}" name="{$a.input_name}" value="{$a.input_value}" class="form-control{if $a.input_class} {$a.input_class}{/if}" />
		{/if}
		{$a.text}
		{if $display_mode=='table'}
		</td>
	</tr>
		{/if}
	{/if}
{/foreach}
{if $display_mode=='table'}
</table>
{/if}