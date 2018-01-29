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
// $Id: admin_index.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
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
			{foreach $home_modules as $this_output name=data}
			<div class="col-lg-4 col-md-6 col-sm-6">{$this_output}</div>
				{if $smarty.foreach.data.iteration%2==0}
			<div class="clearfix visible-md visible-sm"></div>
				{/if}
				{if $smarty.foreach.data.iteration%3==0}
			<div class="clearfix visible-lg"></div>
				{/if}
			{/foreach}
		</div>
	</div>
</div>
<br />
<p class="alert alert-danger center"><a href="{$sortie_href|escape:'html'}" class="alert-link">{$STR_ADMIN_INDEX_SECURITY_WARNING}</a></p>
