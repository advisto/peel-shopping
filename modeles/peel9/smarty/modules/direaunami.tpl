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
// $Id: direaunami.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<h1 property="name">{$STR_TELL_FRIEND}</h1>
<p>{$STR_MODULE_DIREAUNAMI_MSG_TELL_FRIEND|nl2br_if_needed}</p>
<form class="entryform form-inline direaunami" role="form" method="post" action="{$action|escape:'html'}">
	<!-- Début Dire à un ami -->
	<table class="full_width">
		<tr>
			<td class="direaunami_label">{$STR_YOUR_NAME} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$STR_YOUR_EMAIL} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="yname" size="25" value="{$yname|str_form_value}" /></td>
			<td><input type="email" class="form-control" name="yemail" size="29" value="{$yemail|str_form_value}" /></td>
		</tr>
		<tr>
			<td>{$STR_THEIR_NAMES} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
			<td>{$STR_THEIR_EMAILS} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="fname[]" size="25" /></td>
			<td><input type="email" class="form-control" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="fname[]" size="25" /></td>
			<td><input type="email" class="form-control" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="fname[]" size="25" /></td>
			<td><input type="email" class="form-control" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="fname[]" size="25" /></td>
			<td><input type="email" class="form-control" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td><input type="text" class="form-control" name="fname[]" size="25" /></td>
			<td><input type="email" class="form-control" name="femail[]" size="29" /></td>
		</tr>
		<tr>
			<td colspan="2">{$STR_COMMENTS}{$STR_BEFORE_TWO_POINTS}:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea class="form-control" rows="6" cols="54" name="comments"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" class="center">
				<input type="hidden" name="referer" value="{$referer|str_form_value}" />
				<input name="mode" value="send" type="hidden" />
				<input class="btn btn-primary" type="submit" name="action" value="{$STR_SEND|str_form_value}" />
			</td>
		</tr>
		<tr>
			<td colspan="2" class="left"><span class="form_mandatory">(*) {$STR_MANDATORY}</span></td>
		</tr>
	</table>
</form>