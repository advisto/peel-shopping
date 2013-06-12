{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: best_seller_produit_colonne.tpl 37077 2013-05-31 15:39:56Z sdelaporte $
*}
{if !empty($products)}
	<div id="top">
		{foreach $products as $prod}
			{$prod}
			{if !$prod@last}
			<hr style="width:60%" />
			{/if}
		{/foreach}
	</div>
{/if}