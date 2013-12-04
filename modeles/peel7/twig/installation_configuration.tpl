{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.1.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: installation_configuration.tpl 39162 2013-12-04 10:37:44Z gboussin $
#}{{ messages }}
<!-- Contenu -->
<div id="contenu" style="width:600px">
	<form class="entryform form-inline" role="form" action="{{ next_step_url|escape('html') }}" method="post">
		{{ form_messages }}
		<div class="col">{{ STR_ADMIN_INSTALL_ADMIN_EMAIL }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="email" class="form-control" name="email" value="" /><br /></div>
 
		<div class="col">{{ STR_PSEUDO }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" class="form-control" name="pseudo" value="" /><br /></div>

		<div class="col">{{ STR_PASSWORD }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" class="form-control" name="motdepasse" size="32" /><br /></div>

		<div class="col">{{ STR_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" class="form-control" name="nom" value="" /></div>

		<div class="col">{{ STR_FIRST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" class="form-control" name="prenom" value="" /></div>

		<div class="col">{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="tel" class="form-control" name="telephone" value="" /></div>

		<div class="col">{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" class="form-control" name="adresse" value="" /></div>

		<div class="col">{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" class="form-control" name="code_postal" value="" /></div>

		<div class="col">{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" class="form-control" name="ville" value="" /></div>

		<div class="col"><br /><br /></div>

		<p><input type="submit" value="{{ STR_CONTINUE|str_form_value }}" class="btn btn-primary" /></p>
		<p><span class="form_mandatory">(*) {{ STR_MANDATORY }}</span></p>
 	</form>
</div>