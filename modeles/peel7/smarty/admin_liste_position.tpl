{* Smarty
// This file should be in UTF8 without BOM - Accents examples: éèê
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2015 Advisto SAS, service PEEL - contact@peel.fr  |
// +----------------------------------------------------------------------+
// | This file is part of PEEL Shopping 7.2.1, which is subject to an	  |
// | opensource GPL license: you are allowed to customize the code		  |
// | for your own needs, but must keep your changes under GPL			  |
// | More information: https://www.peel.fr/lire/licence-gpl-70.html		  |
// +----------------------------------------------------------------------+
// | Author: Advisto SAS, RCS 479 205 452, France, https://www.peel.fr/	  |
// +----------------------------------------------------------------------+
// $Id: admin_liste_position.tpl 44077 2015-02-17 10:20:38Z sdelaporte $
*}<div class="entete">{$STR_ADMIN_POSITIONS_LIST_TITLE}</div>
<p>
	<b>{$STR_ADMIN_POSITIONS_LIST_EXPLAIN}{$STR_BEFORE_TWO_POINTS}:</b>
	<select class="form-control" name="categorie" onchange="document.location='positions.php?mode=modif&amp;catid='+this.options[this.selectedIndex].value">
		<option value="">{$STR_CHOOSE}...</option>
		{$categorie_options}
	</select>
</p>