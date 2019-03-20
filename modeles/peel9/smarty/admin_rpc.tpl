{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2019 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.2.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_rpc.tpl 59873 2019-02-26 14:47:11Z sdelaporte $
*}
{if !empty($js_line)}
	{$js_line}
{/if}
<ul>
	{if $mode == "products"}
		{if isset($add_specific_lines_in_order)}
			{if !empty($add_specific_lines_in_order)}
				{$add_specific_lines_in_order}
			{else}
				<li>{$STR_AUCUN_RESULTAT}</li>
			{/if}
		{elseif isset($results)}
			{foreach $results as $res}
			<script><!--//--><![CDATA[//><!--
			var arr{$res.id} = {
				"id" : "{$res.id|str_form_value|htmlentities|filtre_javascript:true:true:true}", 
				"ref" : "{$res.reference|str_form_value|htmlentities|filtre_javascript:true:true:true}",
				"nom" : "{$res.nom|str_form_value|htmlentities|filtre_javascript:true:true:true}",
				"quantite" : "{1|filtre_javascript:true:true:true}",
				"image_thumbs" : "{$res.image_thumbs|str_form_value|htmlentities|filtre_javascript:true:true:true}",
				"image_large" : "{$res.image|str_form_value|htmlentities|filtre_javascript:true:true:true}",
				"purchase_prix_ht" : "{$res.purchase_prix_ht|str_form_value}",
				"tva_options_html" : "{$res.tva_options_html|filtre_javascript:true:true:true:true:false}",
				"color_options_html" : "{$res.color_options_html|filtre_javascript:true:true:true:true:false}",
				"size_options_html" : "{$res.size_options_html|filtre_javascript:true:true:true:true:false}",
				"purchase_prix" : "{$res.purchase_prix}",
				"prix_cat" : "{$res.prix_cat|str_form_value}",
				"prix_cat_ht" : "{$res.prix_cat_ht|str_form_value}",
				"remise" : "0",
				"remise_ht" : "0",
				"percent" : "0"
			}
			//--><!]]></script>
			{/foreach}
			{foreach $results as $res}
				{if !empty($return_mode_for_displayed_values) && $return_mode_for_displayed_values == "order"}
	<li onclick="add_products_list_line(arr{$res.id},'{$STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript:true:true:true}', 'order', true);">{$STR_ADMIN_PRODUITS_ADD_PRODUCT}{$STR_BEFORE_TWO_POINTS}: <b>{$res.reference} {$res.nom|html_entity_decode_if_needed}</b> - {$res.purchase_prix_displayed}</li>
				{else}
	<li onclick="add_products_list_line(arr{$res.id}, '{$STR_ADMIN_COMMANDER_ADD_LINE_TO_ORDER|filtre_javascript:true:true:true}', 'product', false);">{$STR_ADMIN_PRODUITS_ADD_PRODUCT}{$STR_BEFORE_TWO_POINTS}: <b>{$res.reference} {$res.nom|html_entity_decode_if_needed}</b> - {$res.purchase_prix_displayed}</li>
				{/if}
			{/foreach}
		{else}
	<li>{$STR_AUCUN_RESULTAT}</li>
		{/if}
	{elseif $mode == "offers"}
		{if !empty($results)}
			{foreach $results as $res}
	<li style="list-style-type:none" class="offres_proposees" onclick="add_offers_list_line('{$res.id|str_form_value|htmlentities|filtre_javascript:true:true:true}', '{$res.nom|str_form_value|htmlentities|filtre_javascript:true:true:true}','{$res.user_id|str_form_value|htmlentities|filtre_javascript:true:true:true}','{$STR_ADMIN_OFFER_ADD_OFFER|str_form_value|htmlentities|filtre_javascript:true:true:true}','{$res.id|str_form_value|htmlentities|filtre_javascript:true:true:true}');">{$STR_ADMIN_OFFER_ADD_OFFER}{$STR_BEFORE_TWO_POINTS}: <b>{$res.nom|html_entity_decode_if_needed}</b></li>
			{/foreach}
		{else}
	<li>{$STR_OFFER_NO_RESULT}</li>
		{/if}
	{elseif $mode == "offer_add_user"}
		{if !empty($results)}
			{foreach $results as $res}
	<li style="list-style-type:none" class="offres_proposees" onclick="add_user_to_offer('{$res.id_utilisateur|str_form_value|htmlentities|filtre_javascript:true:true:true}','{$res.nom_famille|html_entity_decode_if_needed} {$res.prenom|html_entity_decode_if_needed}','{$res.msg|str_form_value|htmlentities|filtre_javascript:true:true:true}')"><b>{$res.nom_famille|html_entity_decode_if_needed} {$res.prenom|html_entity_decode_if_needed}</b> - {$res.societe|html_entity_decode_if_needed} {if !empty($res.laboratoire)}{$res.laboratoire|html_entity_decode_if_needed} {/if}{$res.ville|html_entity_decode_if_needed} {$res.email|html_entity_decode_if_needed}</li>
			{/foreach}
		{else}
	<li>{$STR_OFFER_NO_RESULT}</li>
		{/if}
	{/if}	
</ul>