{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2021 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 9.4.0, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: index.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
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
{if !empty($tableau_recherche_avancee)}
	<div class="col-xs-12 col-sm-6 pull-right">
		{$tableau_recherche_avancee}
	</div>
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
			{$contenu_html|replace:'[MODULES_LEFT]':$MODULES_LEFT}
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
	{$contenu_html|replace:'[MODULES_LEFT]':$MODULES_LEFT}
{/if}
	{$home_middle_top|replace:'[MODULES_LEFT]':$MODULES_LEFT}
{if isset($fresh_ad_presentation)}
	{$fresh_ad_presentation}
{/if}
	{$categorie_accueil}
	{if !empty($notre_selection) && !empty($website_type) && $website_type == 'shop' && !$page_columns_third} 
	</div></div></div></div></div>
		<div class="full_size_background_section">
			<div class="container">
				<div class="row">
				{$notre_selection}
				</div>
			</div>
		</div>
	<div class="container"><div class="row"><div class="middle_column col-sm-12"><div class="middle_column_repeat"><div class="page_home_content">
	{else}
	<div class="row">
		{$notre_selection}
	</div>
	{/if}
	{$home_middle}
	<div class="row">
	{$nouveaute}
	</div>
	{$center_middle_home}
	{if isset($vitrine_list)}
		{$vitrine_list}
	{/if}
</div>