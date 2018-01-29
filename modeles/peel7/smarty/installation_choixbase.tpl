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
// $Id: installation_choixbase.tpl 55332 2017-12-01 10:44:06Z sdelaporte $
*}
<form class="entryform form-inline" role="form" action="verifdroits.php" method="post">
	<p>{$STR_ADMIN_INSTALL_DATABASE_ADVISE_SPECIFIC|escape:'html'}</p>
	<p>{$STR_ADMIN_INSTALL_DATABASE_ADVISE_HOW_TO_CREATE|escape:'html'}</p>
	<p>{$STR_ADMIN_INSTALL_DATABASE_SELECT|escape:'html'}</p>
	{if $available_databases}
		{foreach $available_databases as $this_database}
	<p><label class="radio"><input type="radio" name="choixbase" id="{$this_database|str_form_value}" value="{$this_database|str_form_value}" {if $this_database == $selected_database} checked="checked"{/if} /> {$this_database}</label></p>
		{/foreach}
	{else}
		<input type="text" class="form-control" name="choixbase" /> {$error_message}
	{/if}
	<p class="alert alert-danger">{$STR_ADMIN_INSTALL_DATABASE_PLEASE_CLEAN_BEFORE_INSTALL|escape:'html'}</p>
	<p class="center"><input type="submit" value="{$STR_CONTINUE|str_form_value}" class="btn btn-primary btn-lg" /></p>
</form>