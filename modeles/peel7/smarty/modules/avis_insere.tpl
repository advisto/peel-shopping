{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: avis_insere.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
*}<h2>{$STR_DONNEZ_AVIS}</h2>
<p><b>
		{if $type == 'produit'}
			{$STR_MODULE_AVIS_YOUR_COMMENT_ON_PRODUCT} "<a href="{$urlprod}">{$nom_produit}</a>"
		{elseif $type == 'annonce'}
			{$STR_MODULE_ANNONCES_AVIS_YOUR_COMMENT_ON_AD} "<a href="{$urlannonce}">{$titre_annonce}</a>"
		{/if}
</b></p>
<p> {$STR_MODULE_AVIS_YOUR_COMMENT_WAITING_FOR_VALIDATION} {$site}.</p>