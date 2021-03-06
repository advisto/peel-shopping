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
// $Id: admin_liste_rubrique.tpl 66961 2021-05-24 13:26:45Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_RUBRIQUES_LIST_TITLE}</div>
<div style="margin-top:5px;">
	<p><a href="{$ajout_href|escape:'html'}" class="btn btn-primary"><span class="glyphicon glyphicon-plus" title=""></span> {$STR_ADMIN_RUBRIQUES_ADD}</a></p>
</div>
<div class="alert alert-info">
	<div>
		<img src="{$rubrique_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_RUBRIQUES_ADD_SUBCATEGORY}
	</div>
	<div>
		<img src="{$prod_cat_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_ARTICLES_FORM_ADD}
	</div>
	<div>
		<img src="{$drop_src|escape:'html'}" width="16" height="16" alt="" class="middle" /> {$STR_ADMIN_RUBRIQUES_DELETE_CATEGORY}
	</div>
	{$STR_ADMIN_RUBRIQUES_POSITION_EXPLAIN}
</div>

<div class="table-responsive">
	<table class="table">
		<tr>
			<td class="menu">{$STR_ADMIN_ACTION}</td>
			<td class="menu">{$STR_IMAGE}</td>
			<td class="menu">{$STR_ADMIN_RUBRIQUE}</td>
			<td class="menu">{$STR_WEBSITE}</td>
			<td class="menu">{$STR_ADMIN_POSITION}</td>
			<td class="menu">{$STR_STATUS}</td>
		</tr>
		{$rubrique_options}
	</table>
</div>