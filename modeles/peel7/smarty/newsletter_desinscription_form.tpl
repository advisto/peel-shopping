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
// $Id: newsletter_desinscription_form.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<h2>{$header}</h2>
<form class="entryform form-inline" role="form" method="post" action="{$action|escape:'html'}">
	<table class="full_width" >
		<tr>
			<td>{$label}:</td>
			<td>{$error}<input type="email" class="form-control" name="email" value="{$email|str_form_value}" autocapitalize="none" /></td>
		</tr>
		<tr>
			 <td></td>
			<td class="center"><p><input type="submit" value="{$submit|str_form_value}" class="btn btn-primary" style="width:auto;" /></p></td>
		</tr>
	</table>
</form>