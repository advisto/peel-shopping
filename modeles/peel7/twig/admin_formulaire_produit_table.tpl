{# Twig
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2013 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.0.3, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_formulaire_produit_table.tpl 36927 2013-05-23 16:15:39Z gboussin $
#}<table class="main_table">
	<tr>
		<td colspan="2" class="entete">{{ STR_ADMIN_PRODUITS_ADD }}</td>
	</tr>

</table>
<p><a href="{{ href|escape('html') }}">{{ STR_ADMIN_PRODUITS_CREATE_CATEGORY_FIRST }}</a></p>