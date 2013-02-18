{# Twig
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
// $Id: admin_formulaire_tva.tpl 35064 2013-02-08 14:16:40Z gboussin $
#}<form method="post" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="{{ mode|str_form_value }}" />
	<input type="hidden" name="id" value="{{ id|str_form_value }}" />
	<table class="main_table">
		<tr>
			<td class="entete">{{ STR_ADMIN_TVA_FORM_TITLE }}</td>
		</tr>
		<tr>
			<td><p>{{ STR_ADMIN_VAT_PERCENTAGE }}{{ STR_BEFORE_TWO_POINTS }}: <input type="text" name="tva" style="width:60px" value="{{ tva|str_form_value }}"> %</p></td>
		</tr>
		<tr>
			<td class="center"><p><input class="bouton" type="submit" value="{{ titre_bouton|str_form_value }}" /></p></td>
		</tr>
	</table>
</form>