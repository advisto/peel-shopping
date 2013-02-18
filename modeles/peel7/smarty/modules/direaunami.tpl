{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: direaunami.tpl 35347 2013-02-17 11:26:09Z gboussin $
*}<form method="post" action="{$action|escape:'html'}">
	<!-- Début Dire à un ami -->
	<h2>{$STR_TELL_FRIEND}</h2><p>{$STR_MODULE_DIREAUNAMI_MSG_TELL_FRIEND|nl2br_if_needed}</p>
	<table class="full_width" cellpadding="2">
		<tr>
			<td>{$STR_YOUR_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$STR_YOUR_EMAIL} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><input type="text" name="yname" size="25" value="{$yname|str_form_value}" /></td>
			<td><input type="text" name="yemail" size="29" value="{$yemail|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_THEIR_NAMES} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$STR_THEIR_EMAILS} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><input type="text" name="fname[]" size="25" /></td>
			<td><input type="text" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td><input type="text" name="fname[]" size="25" /></td>
			<td><input type="text" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td><input type="text" name="fname[]" size="25" /></td>
			<td><input type="text" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td><input type="text" name="fname[]" size="25" /></td>
			<td><input type="text" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td><input type="text" name="fname[]" size="25" /></td>
			<td><input type="text" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_COMMENTS}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea rows="6" cols="54" name="comments" style="width:500px"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="center">
				<input type="hidden" name="referer" value="{$referer|str_form_value}" />
				<input name="mode" value="send" type="hidden" />
				<input class="clicbouton" type="submit" name="action" value="{$STR_SEND|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="left"><span class="form_mandatory">(*) {$STR_MANDATORY}</span></td>
		</tr>
	</table>
</form>