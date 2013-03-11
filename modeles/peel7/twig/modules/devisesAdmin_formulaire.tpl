{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: devisesAdmin_formulaire.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<form name="entryform" method="post" action="{{ action|escape('html') }}">
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete" colspan="2">{{ STR_MODULE_DEVISES_ADMIN_TITLE }}</td>
		</tr>
		<tr>
			<td>{{ STR_STATUS }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td>
			  <input type="radio" name="etat" value="1"{% if etat == "1" %} checked="checked"{% endif %} /> {{ STR_ADMIN_ONLINE }}<br />
			  <input type="radio" name="etat" value="0"{% if etat == "0" or not(etat) %} checked="checked"{% endif %} /> {{ STR_ADMIN_OFFLINE }}
			</td>
		</tr>
		<tr>
			<td class="label">{{ STR_DEVISE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:460px" type="text" name="devise" value="{{ devise|str_form_value }}" /></td>
	 	</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_SYMBOL }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:460px" maxlength="10" type="text" name="symbole" value="{{ symbole|str_form_value }}" /></td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_MODULE_DEVISES_ADMIN_SYMBOL_AT_RIGHT }}</td> <td><input type="radio" name="symbole_place" value="1"{% if symbole_place == "1" %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td colspan="2">{{ STR_MODULE_DEVISES_ADMIN_SYMBOL_AT_LEFT }}</td> <td><input type="radio" name="symbole_place" value="0"{% if symbole_place == "0" %} checked="checked"{% endif %} /></td>
		</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_CODE }}{{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:460px" type="text" name="code" value="{{ code|str_form_value }}" /></td>
	 	</tr>
		<tr>
			<td class="label">{{ STR_ADMIN_CONVERSION }} (1 {{ symbole_parameters }} = ...){{ STR_BEFORE_TWO_POINTS }}:</td>
			<td><input style="width:460px" type="text" name="conversion" value="{{ conversion|str_form_value }}" /></td>
	 	</tr>
		<tr>
			<td class="center" colspan="2"><p><input class="bouton" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>