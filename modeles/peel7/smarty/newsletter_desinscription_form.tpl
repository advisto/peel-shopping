{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: newsletter_desinscription_form.tpl 39443 2014-01-06 16:44:24Z sdelaporte $
*}<h2>{$header}</h2>
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	<table class="full_width" >
		<tr>
			<td>{$label}:</td>
			<td>{$error}<input type="email" class="form-control" name="email" value="{$email|str_form_value}" /></td>
		</tr>
		<tr>
			 <td></td>
			<td class="center"><p><input type="submit" value="{$submit|str_form_value}" class="btn btn-primary" style="width:auto;" /></p></td>
		</tr>
	</table>
</form>