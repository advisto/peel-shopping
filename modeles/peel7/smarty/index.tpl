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
// $Id: index.tpl 55930 2018-01-29 08:36:36Z sdelaporte $
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
{if isset($search_map)}
	{$search_map}
{/if}
{if !empty($affiche_compte) || !empty($user_register_form)}
	<div class="row">
		<div class="col-md-8">
			{$contenu_html}
		</div>
		<div class="col-md-4">
		{if isset($affiche_compte)}
			{$affiche_compte}
		{/if}
		{if isset($user_register_form)}
			{$user_register_form}
		{/if}
		</div>
	</div>
{else}
	{$contenu_html}
{/if}
{if isset($fresh_ad_presentation)}
	{$fresh_ad_presentation}
{/if}
	{$categorie_accueil}
	{$notre_selection}
	{$nouveaute}
</div>