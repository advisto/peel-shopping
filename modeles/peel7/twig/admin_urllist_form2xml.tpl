{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.4, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_urllist_form2xml.tpl 37904 2013-08-27 21:19:26Z gboussin $
#}<form method="get" name="prod2xml" action="{{ action|escape('html') }}">
	{{ form_token }}
	<input type="hidden" name="mode" value="lire" />
	<br /><br /><input type="submit" class="bouton" value="{{ STR_ADMIN_URLLIST_GENERATE_SITEMAP|str_form_value }}" />
</form>