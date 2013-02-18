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
// $Id: newsletter_desinscription_form.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<h2>{{ header }}</h2>
<form class="entryform" method="post" action="{{ action|escape('html') }}">
	<table class="full_width" >
		<tr>
			<td>{{ label }}:</td>
			<td>{{ error }}<input type="text" name="email" size="20" value="{{ email|str_form_value }}" /></td>
		</tr>
		<tr>
			 <td></td>
			<td class="center"><p><input type="submit" value="{{ submit|str_form_value }}" class="clicbouton" style="width:auto;" /></p></td>
		</tr>
	</table>
</form>