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
// $Id: delete_installation_folder.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}<div class="launch_installation">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<a href="http://www.peel-shopping.com/"><img src="{$wwwroot}/images/logo-peel.png" alt="" /></a><br /><br />
				<h1 property="name" class="center">{$STR_INSTALLATION_PROCEDURE} {$PEEL_VERSION}</h1>
				<p class="center">{$installation_links}</p>
				<br />
				<a href="https://www.advisto.fr/"><img src="https://www.peel.fr/images/peel-shopping-logo-install.png?version={$PEEL_VERSION}" alt="" /></a>
			</div>
		</div>
	</div>
	<div class="footer">
		<h2 class="center">{$STR_INSTALLATION_DELETE_EXPLAIN}</h2>
		<p class="center">{$STR_INSTALLATION_DELETE_EXPLAIN_ALTERNATIVE}</p>
		<p class="center"><a href="{$wwwroot}/">{$STR_INSTALLATION_DELETED_LINK}</a></p>
	</div>
</div>