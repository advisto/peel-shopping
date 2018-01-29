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
// $Id: avis_formulaire.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<h2>{$STR_DONNEZ_AVIS}</h2>
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	<table class="avis_formulaire">
		<tr>
			<td colspan="2" class="entete">
			{if $type == 'produit'}
				{$STR_MODULE_AVIS_WANT_COMMENT_PRODUCT} "{$product_name}"
			{elseif $type == 'annonce'}
				{$STR_MODULE_ANNONCES_AVIS_WANT_COMMENT_AD} "{$annonce_titre}"
			{/if}
			</td>
		</tr>
	{if $mode == 'avis'}
		<tr>
			<td class="title_label top"> {$STR_YOU_ARE}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$prenom} {$nom_famille}</td>
		</tr>
		<tr>
			<td class="title_label top"> {$STR_PSEUDO}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><input type="text" class="form-control" name="pseudo" value="{if empty($pseudo)}{$pseudo_ses}{else}{$pseudo|str_form_value}{/if}" maxlength="50" /></td>
		</tr>
	{/if}
		<tr>
		{if !empty($html_editor)}
			<td class="top" colspan="2">
				{$html_editor}
			</td>
		{else}
			<td class="top">
				<b>{$STR_YOUR_OPINION} <span class="etoile">*</span></b>{$STR_BEFORE_TWO_POINTS}:
				<br /><input type="text" class="form-control compteur" name="compteur" size="4" onfocus="blur()" value="255" /> <span style="margin-left:5px;"> {$STR_REMINDING_CHAR}</span>
				<br />{$error_avis}
			</td>
			<td>
				<textarea class="form-control" name="avis" cols="36" rows="6" onfocus="Compter(this,255,compteur, false)" onkeypress="Compter(this,255,compteur, false)" onkeyup="Compter(this,255,compteur, false)" onblur="Compter(this,255,compteur, false)">{$avis|html_entity_decode_if_needed}</textarea>
			</td>
		{/if}
		</tr>
{if empty($no_notation) && $mode == 'avis'}
		<tr>
			<td class="top"><b>{$STR_YOUR_NOTE} <span class="etoile">*</span></b>{$STR_BEFORE_TWO_POINTS}:
				<br />{$error_note}
			</td>
			<td>
				<input type="radio" name="note" value="5" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><br />
				<input type="radio" name="note" value="4" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><br />
				<input type="radio" name="note" value="3" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><br />
				<input type="radio" name="note" value="2" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><br />
				<input type="radio" name="note" value="1" /><img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" /><br />
			</td>
		</tr>
	{/if}
		<tr>
			<td colspan="2" class="center">
				<input type="hidden" name="id_utilisateur" value="{$id_utilisateur|str_form_value}" />
				<input type="hidden" name="prenom" value="{$prenom|str_form_value}" />
				<input type="hidden" name="email" value="{$email|str_form_value}" />
				{if $type == 'produit'}
				<input type="hidden" name="prodid" value="{$prodid|str_form_value}" />
				<input type="hidden" name="nom_produit" value="{$product_name|str_form_value}" />
				{elseif $type == 'annonce'}
				<input type="hidden" name="ref" value="{$ref|str_form_value}" />
				<input type="hidden" name="titre_annonce" value="{$annonce_titre|str_form_value}" />
				{/if}
				<input type="hidden" name="type" value="{$type|str_form_value}" />
				<input type="hidden" name="opinion_id" value="{$opinion_id}" />
				<input type="hidden" name="langue" value="{$langue|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input class="btn btn-primary submit-once-only" type="submit" value="{$STR_SEND|str_form_value}" />
				<p><span class="form_mandatory">(*) {$STR_MANDATORY}</span></p>		
			</td>
		</tr>
	</table>
</form>