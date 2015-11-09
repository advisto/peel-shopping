{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: newsletter_form.tpl 47592 2015-10-30 16:40:22Z sdelaporte $
#}<form class="entryform form-inline" role="form" action="{{ wwwroot }}/utilisateurs/newsletter.php?mode=inscription" id="newsletter_form" method="post">
	<div class="input-group">
		<span class="input-group-addon">{{ label }}: </label>
		<input type="text" class="form-control" id="newsletter_email" name="email" title="{{ default|str_form_value }}" value="" placeholder="{{ default|str_form_value }}" />
		<span class="input-group-btn"><input type="submit" class="btn btn-primary" value="OK" /></span>
		{{ form_token }}
	</div>
</form>