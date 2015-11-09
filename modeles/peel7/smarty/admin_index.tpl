{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 8.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_index.tpl 47600 2015-10-30 17:15:35Z gboussin $
*}
{if $all_sites_name_array|@count>1}
<div class="home_block home_block_select_multisite home_block_black panel panel-primary">
	<div class="panel-heading" style="cursor:pointer" onclick="document.location='{$link}'">
		<h2 class="panel-title">{$STR_ADMIN_CHOOSE_SITE_TO_MODIFY}{$STR_BEFORE_TWO_POINTS}:</h2>
	</div>
	<div class="panel-body">
		<div>
			<form action="{$current_url}" id="admin_multisite_form" method="post">
				<select class="form-control" name="admin_multisite" onchange="document.getElementById('admin_multisite_form').submit();return false;">
					{$site_id_select_options}
				</select>
			</form>
		</div>
	</div>
</div>
{/if}

{if !empty($version_update_link)}
	<p class="alert alert-danger center"><a class="alert-link" href="{$version_update_link}">{$STR_ADMIN_UPDATE_VERSION_INVITE}</a></p>
{/if}

<div style="margin-left:-15px; margin-right:-15px">
	<div class="container">
		<div class="row">
			{if isset($KeyyoCalls)}<div class="col-lg-12 col-md-12 col-sm-12">{$KeyyoCalls}</div>
			<div class="clearfix visible-md visible-sm"></div>{/if}
			<div class="col-lg-4 col-md-6 col-sm-6">{$orders}</div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$sales}</div>
			<div class="clearfix visible-md visible-sm"></div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$products}</div>
			<div class="clearfix visible-lg"></div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$delivery}</div>
			<div class="clearfix visible-md visible-sm"></div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$users}</div>
			<div class="col-lg-4 col-md-6 col-sm-6">{$peel}</div>
		</div>
	</div>
</div>
<br />
<p class="alert alert-danger center"><a href="{$sortie_href|escape:'html'}" class="alert-link">{$STR_ADMIN_INDEX_SECURITY_WARNING}</a></p>
