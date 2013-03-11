{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: installation_configuration.tpl 35330 2013-02-16 18:27:13Z gboussin $
#}<div id="contourMenu">
	<div id="menuHorizontal">
		<h1>{{ step_title|escape('html') }}</h1>
	</div>
</div>
{{ messages }}
<!-- Contenu -->
<div id="contenu" style="width:600px">
	<form action="{{ next_step_url|escape('html') }}" method="post">
		{{ form_messages }}
		<div class="col">{{ STR_ADMIN_INSTALL_ADMIN_EMAIL }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" size="30" name="email" value="" /><br /></div>
 
		<div class="col">{{ STR_PSEUDO }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" size="30" name="pseudo" value="" /><br /></div>

		<div class="col">{{ STR_PASSWORD }} <span class="etoile">*</span>{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" name="motdepasse" size="30" /><br /></div>

		<div class="col">{{ STR_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" name="nom" value="" size="30" /></div>

		<div class="col">{{ STR_FIRST_NAME }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" name="prenom" value="" size="30" /></div>

		<div class="col">{{ STR_TELEPHONE }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" name="telephone" value="" size="30" /></div>

		<div class="col">{{ STR_ADDRESS }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" name="adresse" value="" size="30" /></div>

		<div class="col">{{ STR_ZIP }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" name="code_postal" value="" size="30" /></div>

		<div class="col">{{ STR_TOWN }}{{ STR_BEFORE_TWO_POINTS }}:</div>
		<div class="col"><input type="text" name="ville" value="" size="30" /></div>

		<div class="col"><br /><br /></div>

		<p><input type="submit" value="{{ STR_CONTINUE|str_form_value }}" class="bouton" /></p>
		<p><span class="form_mandatory">(*) {{ STR_MANDATORY }}</span></p>
 	</form>
</div>