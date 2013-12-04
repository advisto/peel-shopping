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
// $Id: avisAdmin_formulaire.tpl 39162 2013-12-04 10:37:44Z gboussin $
*}<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="{$mode|str_form_value}" />
	<input type="hidden" name="id" value="{$id|str_form_value}" />
	<table class="main_table" width="760">
		<tr>
			<td class="entete" colspan="2">{$STR_MODULE_AVIS_ADMIN_FORM_TITLE} {$nom_produit|html_entity_decode_if_needed}</td>
		</tr>
		<tr>
			<td>{$STR_BY}{$STR_BEFORE_TWO_POINTS}:</td>
			<td><a href="{$modif_href|escape:'html'}">{$email|html_entity_decode_if_needed}</a></td>
		</tr>
		<tr>
			<td>{$STR_PSEUDO}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$pseudo|html_entity_decode_if_needed}</td>
		</tr>
		<tr>
			<td>{$STR_STATUS}{$STR_BEFORE_TWO_POINTS}:</td>
			<td>
			  <input type="radio" name="etat" value="1"{if $etat == "1"} checked="checked"{/if} /> {$STR_ADMIN_ONLINE}<br />
			  <input type="radio" name="etat" value="0"{if $etat == "0" OR empty($etat)} checked="checked"{/if} /> {$STR_ADMIN_OFFLINE}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_AVIS}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" style="width: 100%" name="avis" cols="50" rows="5">{$avis}</textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="title_label">{$STR_ADMIN_NOTE}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2">
				{for $this_note=$note_max; $this_note>=1; $this_note--}
				<input type="radio" name="note" value="{$this_note}"{if $note == $this_note} checked="checked"{/if} />{for $i=1 to $this_note}<img src="{$star_src|escape:'html'}" style="vertical-align:middle" alt="*" />{/for}<br />
				{/for}
			</td>
		</tr>
		<tr>
			<td colspan="2" class="center"><p><input class="btn btn-primary" type="submit" value="{$titre_soumet|str_form_value}" /></p></td>
		</tr>
	</table>
</form>