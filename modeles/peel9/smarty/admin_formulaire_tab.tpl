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
// $Id: admin_formulaire_tab.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	{$form_token}
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<input type="hidden" name="lng" value="{$lng|str_form_value}" />
	<table class="full_width">
		<tr>
			<td class="entete" colspan="2">{$STR_ADMIN_PRODUITS_UPDATE_TABS_CONTENT} {$product_name}</td>
		</tr>
		<tr>
			<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
				<input type="radio" name="display_tab" value="1"{if $display_tab == '1'} checked="checked"{/if} /> {$STR_ADMIN_ONLINE}<br />
				<input type="radio" name="display_tab" value="0"{if $display_tab == '0'} checked="checked"{/if} /> {$STR_ADMIN_OFFLINE}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{$STR_ADMIN_PRODUITS_TAB} {$lng|upper} n°1</h2></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_ADMIN_TITLE}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" name="tab1_title_{$lng}" size="70" value="{$tab1_title|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">{$tab1_html_te}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{$STR_ADMIN_PRODUITS_TAB} {$lng|upper} n°2</h2></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_ADMIN_TITLE}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" name="tab2_title_{$lng}" size="70" value="{$tab2_title|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">{$tab2_html_te}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{$STR_ADMIN_PRODUITS_TAB} {$lng|upper} n°3</h2></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_ADMIN_TITLE}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" name="tab3_title_{$lng}" size="70" value="{$tab3_title|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">{$tab3_html_te}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{$STR_ADMIN_PRODUITS_TAB} {$lng|upper} n°4</h2></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_ADMIN_TITLE}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" name="tab4_title_{$lng}" size="70" value="{$tab4_title|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">{$tab4_html_te}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{$STR_ADMIN_PRODUITS_TAB} {$lng|upper} n°5</h2></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_ADMIN_TITLE}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" name="tab5_title_{$lng}" size="70" value="{$tab5_title|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">{$tab5_html_te}</td>
		</tr>
		<tr>
			<td colspan="2" class="bloc"><h2>{$STR_ADMIN_PRODUITS_TAB} {$lng|upper} n°6</h2></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_ADMIN_TITLE}{$STR_BEFORE_TWO_POINTS}: <input type="text" class="form-control" name="tab6_title_{$lng}" size="70" value="{$tab6_title|html_entity_decode_if_needed|str_form_value}" /></td>
		</tr>
		<tr>
			<td colspan="2">{$tab6_html_te}</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><input class="btn btn-primary" type="submit" value="{$titre_soumet|str_form_value}" /></td>
		</tr>
	</table>
</form>