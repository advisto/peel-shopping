{* Smarty
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
// $Id: admin_liste_prix.tpl 36927 2013-05-23 16:15:39Z gboussin $
*}<table class="main_table">
	<tr>
		<td class="entete">{$STR_ADMIN_PRIX_TITLE}</td>
	</tr>
	<tr>
		<td>
			<p>
				<b>{$STR_CATEGORY}{$STR_BEFORE_TWO_POINTS}:</b>
				<select name="categorie" onchange="document.location='prix.php?mode=modif&amp;catid='+this.options[this.selectedIndex].value">
					<option value="">{$STR_CHOOSE}</option>
					{$categorie_options}
				</select>
			</p>
		</td>
	</tr>
</table>