{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: delete_installation_folder.tpl 38682 2013-11-13 11:35:48Z gboussin $
*}<div class="launch_installation">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<a href="http://www.peel-shopping.com/"><img src="{$wwwroot}/images/logo-peel.png" alt="" /></a><br /><br />
				<h1 class="center">{$STR_INSTALLATION_PROCEDURE} {$PEEL_VERSION}</h1>
				<p class="center">{$installation_links}</p>
				<br />
				<a href="http://www.advisto.fr/"><img src="http://www.peel.fr/images/peel-shopping-logo-install.png?version={$PEEL_VERSION}" alt="" /></a>
			</div>
		</div>
	</div>
	<div class="footer">
		<h2 class="center">{$STR_INSTALLATION_DELETE_EXPLAIN}</h2>
		<p class="center">{$STR_INSTALLATION_DELETE_EXPLAIN_ALTERNATIVE}</p>
		<p class="center"><a href="{$wwwroot}/">{$STR_INSTALLATION_DELETED_LINK}</a></p>
	</div>
</div>