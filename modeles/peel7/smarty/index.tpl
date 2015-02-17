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
// $Id: index.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
*}{if isset($error)}
<div class="alert alert-danger">
	{$error}
</div>
{/if}
{if isset($home_title)}
	{$home_title}
{/if}
<div class="page_home_content">
{if isset($carrousel_html)}
	{$carrousel_html}
{/if}
{if isset($categorie_annonce)}
	{$categorie_annonce}
{/if}
	{$contenu_html}
	{$categorie_accueil}
	{$notre_selection}
	{$nouveaute}
</div>