{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.2, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: newsletter_form.tpl 35067 2013-02-08 14:21:55Z gboussin $
#}<form action="{{ wwwroot }}/utilisateurs/newsletter.php?mode=inscription" id="newsletter_form" method="post">
	<div id="newsletter_div">
		<label>{{ label }}: </label>
		<input type="text" id="newsletter_email" name="email" value="" placeholder="{{ default|str_form_value }}" />
		<input type="submit" class="bouton_ok" value="" />
	</div>
</form>