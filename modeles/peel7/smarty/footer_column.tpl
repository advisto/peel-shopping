{* Smarty
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
// $Id: footer_column.tpl 37904 2013-08-27 21:19:26Z gboussin $
*}<ul class="boxes">
	<li>
		<h2><a href="{$wwwroot}/modules/partenaires/partenaires.php">{$partner.label}</a></h2>
		{$partner.content}
	</li>
	<li>
		<h2><a href="{$wwwroot}/lire/partenaires-commerciaux-amp-revendeurs-204.html">{$revendeur.label}</a></h2>
		<div class="footer_box">
			{$revendeur.content}
		</div>
	</li>
	<li class="last">
		<h2><a href="{$wwwroot}/modules/references/references.php">{$references.label}</a></h2>
		<div class="footer_box">
			{$references.content}
		</div>
	</li>
</ul>