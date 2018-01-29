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
// $Id: installation_configuration.tpl 53676 2017-04-25 14:51:39Z sdelaporte $
*}
<form class="entryform form-inline" role="form" action="{$next_step_url|escape:'html'}" method="post">
	{$messages}
	{$form_messages}
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-4">{$STR_ADMIN_INSTALL_ADMIN_EMAIL} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="email" class="form-control" name="email" value="" autocapitalize="none" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-4">{$STR_PSEUDO} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="text" class="form-control" name="pseudo" value="" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">	
		<div class="col-md-4">{$STR_PASSWORD} <span class="etoile">*</span>{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="password" class="form-control" name="motdepasse" size="32" autocapitalize="none" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-4">{$STR_NAME}{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="text" class="form-control" name="nom" value="" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-4">{$STR_FIRST_NAME}{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="text" class="form-control" name="prenom" value="" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-4">{$STR_TELEPHONE}{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="tel" class="form-control" name="telephone" value="" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-4">{$STR_ADDRESS}{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="text" class="form-control" name="adresse" value="" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-4">{$STR_ZIP}{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="text" class="form-control" name="code_postal" value="" /></div>
	</div>
	<div class="row" style="margin-bottom:10px">
		<div class="col-md-4">{$STR_TOWN}{$STR_BEFORE_TWO_POINTS}:</div>
		<div class="col-md-8"><input type="text" class="form-control" name="ville" value="" /></div>
	</div>
	<br /><br />

	<p class="center"><input type="submit" value="{$STR_CONTINUE|str_form_value}" class="btn btn-primary btn-lg" /></p>
	<p><span class="form_mandatory">(*) {$STR_MANDATORY}</span></p>
</form>