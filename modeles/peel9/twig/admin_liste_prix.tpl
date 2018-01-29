{# Twig
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
// $Id: admin_liste_prix.tpl 53200 2017-03-20 11:19:46Z sdelaporte $
#}<div class="entete">{{ STR_ADMIN_PRIX_TITLE }}</div>
<p>
	<b>{{ STR_CATEGORY }}{{ STR_BEFORE_TWO_POINTS }}:</b>
	<select class="form-control" name="categorie" onchange="document.location='prix.php?mode=modif&amp;catid='+this.options[this.selectedIndex].value">
		<option value="">{{ STR_CHOOSE }}</option>
		{{ categorie_options }}
	</select>
</p>