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
// $Id: newsletter_form.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}<form class="entryform form-inline" role="form" action="{$wwwroot}/utilisateurs/newsletter.php?mode=inscription" id="newsletter_form" method="post">
	<div class="input-group">
		<span class="input-group-addon">{$label}: </span>
		<input type="text" class="form-control" id="newsletter_email" name="email" value="{$value|str_form_value}" title="{$default|str_form_value}" placeholder="{$default|str_form_value}" autocapitalize="none" />
		<span class="input-group-btn"><input type="submit" class="btn btn-primary" value="OK" /></span>
		{$form_token}
	</div>
</form>